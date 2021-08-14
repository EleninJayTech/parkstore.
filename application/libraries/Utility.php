<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 주요 유틸리티 모음
 * Class Util
 */
class Utility{
	private static $cryptPass = '$2y$10$Rya9qGgXa'; // @brief 첫 설정 후 변경 금지
	private static $cryptIv = 'olSKUEK5MKC0.t1H'; // @brief 첫 설정 후 변경 금지

	/**
	 * json 파일 데이터를 object 로 반환
	 * @doc getJsonFileData|json to object
	 * @param $jsonFilePath
	 * @param string $returnType object|array
	 * @param bool $md5decode true 일시 md5decode 진행 후 json_decode
	 * @return mixed|null
	 */
	public static function getJsonFileData($jsonFilePath, $returnType='object', $md5decode=false){
		$return = null;
		if (file_exists($jsonFilePath)) {
			$cacheParams = file_get_contents($jsonFilePath);
			$assoc = false;
			if( $returnType == 'array' ){
				$assoc = true;
			}
			if( $md5decode == true ){
				$cacheParams = self::md5decode($cacheParams);
			}
			$return = json_decode($cacheParams, $assoc);
		}
		return $return;
	}

	/**
	 * 리뉴얼모드 확인
	 * @doc isRenewalMode|리뉴얼모드
	 * @return bool
	 */
	public static function isRenewalMode(){
		$return = false;
		if( get_cookie('renewalMode') /* @todo 관리자만 */ ){
			$return = true;
		}
		return $return;
	}

	/**
	 * CSRF 모드 확인
	 * @doc isCsrfMode|CSRF 모드 확인
	 * @return bool
	 */
	public static function isCsrfMode(){
		$return = true;
		if( get_cookie('csrfOff') == 'CSRFOFFY' /* @todo 관리자만 */ ){
			$return = false;
		}
		return $return;
	}

	/**
	 * Profiler 모드 확인
	 * @doc isProfilerMode|Profiler 모드 확인
	 * @return bool
	 */
	public static function isProfilerMode(){
		$return = false;
		if( get_cookie('profilerMode') /* @todo 관리자만 */ ){
			$return = true;
		}
		return $return;
	}

	/**
	 * cookie 헬퍼를 이용해 쿠키를 생성한다
	 * @doc setCookie|쿠키생성
	 * get 은 기본 헬퍼를 이용하세요
	 * @param $name
	 * @param string $value
	 * @param int $day
	 * @param string $domain
	 * @param string $path
	 */
	public static function setCookie($name, $value = '1', $day = 0, $domain = "", $path = "/"){
		if ($domain == "") {
			$domain = $_SERVER['SERVER_NAME'];
		}
		set_cookie(array(
			'name' => $name,
			'value' => $value,
			'expire' => 86400 * $day,
			'domain' => $domain,
			'path' => $path
		));
	}

	/**
	 * cookie 헬퍼를 이용해 쿠키를 삭제
	 * @doc deleteCookie|쿠키삭제
	 * @param $name
	 * @param string $domain
	 * @param string $path
	 * @param string $prefix
	 */
	public static function deleteCookie($name, $domain = "", $path = "/", $prefix = ""){
		if ($domain == "") {
			$domain = $_SERVER['SERVER_NAME'];
		}
		set_cookie($name, '', '', $domain, $path, $prefix);
	}

	/**
	 * 알림창을 띄우고 페이지를 이동한다.
	 * @doc movePage|페이지이동(alert)
	 * @param string $text
	 * @param string $moveUrl
	 */
	public static function movePage($text = "", $moveUrl = ""){
		$script = "";
		if (trim($text) != "") {
			$script .= "alert('$text');";
		}
		if (trim($moveUrl) == "") {
			$script .= "window.history.back();";
		} else {
			$script .= "window.location.replace('$moveUrl');";
		}

		echo "
            <!DOCTYPE html>
            <html lang='ko'>
            <head>
                <meta charset='UTF-8'>
                <title>HH</title>
            </head>
            <body>
                <script type='text/javascript'>
                    {$script}
                </script>
                <h1>{$text}</h1>
            </body>
            </html>
        ";
		exit;
	}

	/**
	 * returnUrl
	 * @doc moveReturnUrl|페이지이동
	 * 페이지 이동
	 * @param string $returnUrl
	 */
	public static function moveReturnUrl($returnUrl = ""){
		if (trim($returnUrl) == "") {
			$returnUrl = "/";
			if ($_GET["returnUrl"]) {
				$returnUrl = htmlspecialchars($_GET["returnUrl"]);
			}
		}
		redirect($returnUrl);
		exit;
	}

	/**
	 * 모바일 접속 확인
	 * @doc isMobileMode|모바일접속 확인
	 * @return bool
	 */
	public static function isMobileMode(){
		$return = false;
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
			$return = true;
		}
		return $return;
	}

	/**
	 * md5 ENCODE
	 * @doc md5encode|md5로 인코딩
	 * @param $q
	 * @return string
	 */
	public static function md5encode($q){
		// php 7 미만
//		$cryptKey = 'leHGf13Bi4CD2jF5p15D4qx';
//		$qEncoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), $q, MCRYPT_MODE_CBC, md5(md5($cryptKey))));

		$qEncoded = openssl_encrypt($q, "aes-128-cbc", self::$cryptPass, true, self::$cryptIv);
		$qEncoded = base64_encode($qEncoded);

		return $qEncoded;
	}

	/**
	 * md5 DECODE
	 * @doc md5decode|md5 디코드
	 * @param $q
	 * @return string
	 */
	public static function md5decode($q){
		// php 7 미만
//		$cryptKey = 'leHGf13Bi4CD2jF5p15D4qx';
//		$qDecoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($q), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");

		$data = base64_decode($q);
		$qDecoded = openssl_decrypt($data, "aes-128-cbc", self::$cryptPass, true, self::$cryptIv);
		return ($qDecoded);
	}

	/**
	 * 입력된 문자열을 입력된 길이만큼 자름
	 * @doc cutStr|문자열 자르기
	 * @param    string (입력 문자열)
	 * @param    length (잘라낼 길이)
	 * @param string $ellipsis (문자열을 잘라낸후 붙여줄 끝문자열)
	 * @return    string
	 */
	public static function cutStr($string, $length, $ellipsis = '..'){
		mb_internal_encoding('UTF-8');
		if (mb_strlen($string) <= $length) {
			return $string;
		} else {
			return mb_substr($string, 0, $length) . $ellipsis;
		}
	}

	/**
	 * 입력된 UTF-8 문자열을 너비가 일정하게 자름
	 * @doc cutStrimWidth|UTF-8 기준으로 문자열을 자름
	 * @param $string (입력 문자열)
	 * @param $length (잘라낼 길이)
	 * @param boolean $rtrim (자르면서 발생한 공백제거 여부)
	 * @param string $ellipsis (생략부호)
	 * @return string  processed string
	 */
	public static function cutStrimWidth($string, $length, $rtrim = false, $ellipsis = '...'){
		$returnStr = mb_strimwidth($string, 0, (int)$length, $ellipsis, "UTF-8");
		if ($rtrim) {//치환비용문제로 옵션화
			$returnStr = preg_replace('/\s' . preg_quote($ellipsis) . '$/', $ellipsis, $returnStr);
		}
		return $returnStr;
	}

	/**
	 * 사용자 환경을 가져온다
	 * 브라우저 확인, 운영체제 확인
	 * @doc getUserAgent|사용자 환경 확인
	 * @param null $userAgent
	 * @return array
	 */
	public static function getUserAgent($userAgent = null){
		if ($userAgent === null) {
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
		}
		$browser = 'etc';
		$version = '';
		$osName = 'etc';
		if (preg_match("/Windows NT ([\\.0-9]+);/", $userAgent, $_windowVersion)) {
			if ($_windowVersion[1] == '4.0') {
				$osName = 'Windows NT';
			} else if ($_windowVersion[1] == '5.0') {
				$osName = 'Windows 2000';
			} else if ($_windowVersion[1] == '5.1') {
				$osName = 'Windows XP';
			} else if ($_windowVersion[1] == '5.2') {
				$osName = 'Windows Vista'; // Windows XP 64-Bit Edition, Windows Server 2003 R2, Windows Server 2003
			} else if ($_windowVersion[1] == '6.0') {
				$osName = 'Windows Vista';
			} else if ($_windowVersion[1] == '6.1') {
				$osName = 'Windows 7'; // Windows Server 2008 R2
			} else if ($_windowVersion[1] == '6.2') {
				$osName = 'Windows 8'; // Windows Server 2012
			} else if ($_windowVersion[1] == '6.3') {
				$osName = 'Windows 8.1'; // Windows Server 2012 R2
			}
		} else if (preg_match("/(Windows (NT|98|95))/", $userAgent, $_oldWindowName)) {
			$osName = $_oldWindowName[0];
		} else if (preg_match("/Mac OS X ([_\\.0-9]+)/", $userAgent, $_osxVersion)) {
			$osName = 'Mac OS X';
		} else if (preg_match("/Mac_PowerPC/", $userAgent, $_osxVersion)) {
			$osName = 'Mac OS PowerPC';
		} else if (preg_match("/Android|iPod|iPad/", $userAgent, $_otherOsName)) {
			$osName = $_otherOsName[0];
		} else if (preg_match("/iPhone/", $userAgent, $_otherOsName)) {
			$osName = $_otherOsName[0];
		} else if (preg_match("/Linux/", $userAgent)) {
			$osName = 'Linux';
		} else if (preg_match("/Unix/", $userAgent)) {
			$osName = 'Unix';
		}

		if (preg_match("/Trident\\/|MSIE/", $userAgent)) {
			$browser = 'msie';
			$ieVersion1 = 0;
			$ieVersion2 = 0;

			if (preg_match("/Trident\\/([\\.0-9]+)/", $userAgent, $_tridentVersion)) { // IE 신버전
				$tridentVersion = (int)$_tridentVersion[1];
				if ($tridentVersion == 4) {
					$ieVersion1 = 8.0;
				} else if ($tridentVersion == 5) {
					$ieVersion1 = 9.0;
				} else if ($tridentVersion == 6) {
					$ieVersion1 = 10.0;
				} else if ($tridentVersion == 7) {
					$ieVersion1 = 11.0;
				}
				if ($ieVersion1 >= 10 && preg_match("/; rv:([\\.0-9]+)/", $userAgent, $_rv)) {
					$ieVersion1 = floatval($_rv[1]);
				}
			}

			if (preg_match("/MSIE ([\\.0-9a-z]+);/", $userAgent, $_version)) { // IE 구버전
				$ieVersion2 = floatval($_version[1]);
				if ($osName === 'Windows 7' && $ieVersion2 < 8.0) { // Windows 7 최소 IE 버전
					$ieVersion2 = 8.0;
				}
			}

			$version = (($ieVersion2 == 0 || ($ieVersion2 >= 7 && $ieVersion1 >= $ieVersion2)) ? $ieVersion1 : $ieVersion2);
		} else if (preg_match("/Firefox\\/?([\\.0-9]+)/", $userAgent, $_version)) {
			$browser = 'firefox';
			$version = $_version[1];
		} else if (preg_match("/Chrome\\/([\\.0-9]+)/", $userAgent, $_version)) {
			$browser = 'chrome';
			$version = $_version[1];
		} else if (preg_match("/CriOS\\/([\\.0-9]+) Mobile\\/([0-9a-zA-Z]+) Safari/", $userAgent, $_version)) {
			$browser = 'chrome';
			$version = $_version[1];
		} else if (preg_match("/Android ([\\.0-9]+)/", $userAgent, $_version)) {
			$browser = 'android';
			$version = $_version[1];
		} else if (preg_match("/Version\\/([\\.0-9]+) Safari/", $userAgent, $_version)) { // Version/4.0.4 Mobile/7B334b Safari/531.21.10
			$browser = 'safari';
			$version = $_version[1];
		} else if (preg_match("/Version\\/([\\.0-9]+) Mobile\\/([0-9a-zA-Z]+) Safari/", $userAgent, $_version)) {
			$browser = 'safari';
			$version = $_version[1];
		} else if (preg_match("/Opera\\/?([\\.0-9]+)/", $userAgent, $_version)) {
			$browser = 'opera';
			$version = $_version[1];
		}

		return array('browser' => $browser, 'version' => $version, 'os' => $osName);
	}

	/**
	 * 해당 URL의 콘텐츠를 가져온다
	 * @doc getUrlContents|웹페이지의 콘텐트를 가져온다 CURL
	 * @param $url
	 * @return mixed
	 */
	public static function getUrlContents($url){
		$crl = curl_init();
		$timeout = 5;
		curl_setopt($crl, CURLOPT_URL, $url);
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		$ret = curl_exec($crl);
		curl_close($crl);
		return $ret;
	}

	/**
	 * 해당 인터넷 주소의 컨텐츠를 가져온다.
	 * @param $url
	 * @param null $referer
	 * @param string $encoding
	 * @param bool $getInfo
	 * @param string $method
	 * @param null $postData
	 * @param int $timeout
	 * @param bool $noBody
	 * @param null $customHeaders
	 * @param string $userAgent
	 * @return array|mixed|string
	 */
	public static function curlGetContents($url, $referer = null, $encoding = 'UTF-8', $getInfo = false, $method = 'get', $postData = null, $timeout = 0, $noBody = false, $customHeaders = null, $userAgent = 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.104 Safari/537.36')
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, (($noBody) ? 1 : 0));
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		if ($referer) {
			curl_setopt($ch, CURLOPT_REFERER, $referer);
		}
		if (is_array($customHeaders) && !empty($customHeaders)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $customHeaders);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($timeout > 0) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		}
		if ($noBody) {
			curl_setopt($ch, CURLOPT_NOBODY, 1);
		}
		if ($method == 'post') {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		}

		$cookieTempFile = '/tmp/CURL_COOKIE';
		// curl_setopt($ch, CURLOPT_COOKIESESSION, true );
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieTempFile);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieTempFile);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		if (($result = curl_exec($ch)) !== false) {
			$encodingList = array('UTF-8', 'EUC-KR');
			if (!in_array($encoding, $encodingList)) {
				$encodingList[] = $encoding;
			}
			$detectedEncoding = mb_detect_encoding($result, $encodingList, true);
			if ($encoding != $detectedEncoding) {
				if ($detectedEncoding) {
					$result = mb_convert_encoding($result, $encoding, $detectedEncoding);
				} else {
					$result = mb_convert_encoding($result, $encoding);
				}
			}
		}
		if ($getInfo) {
			$info = curl_getinfo($ch);
		}

		curl_close($ch);

		if ($getInfo) {
			return array('contents' => $result, 'info' => $info);
		}
		return $result;
	}

	/**
	 * xml string 을 Object 로 변환하여 리턴
	 * @doc getObjectFromXmlString|xml to object
	 * @param $cont
	 * @return mixed
	 */
	public static function getObjectFromXmlString($cont){
		return json_decode(json_encode((array)simplexml_load_string($cont)));
	}

	/**
	 * 인자로 받아온 URL 문자에서 슬러시 '/' 를 기준으로 배열을 생성
	 * @doc uriSegmentExplode|인자값을 슬러시'/' 기준으로 배열로 변경
	 * @param string $uriString
	 * @return array
	 */
	public static function uriSegmentExplode($uriString=''){
		if( trim($uriString) == '' ){
			$uriString = uri_string();
		}

		$len = strlen($uriString);
		if (substr($uriString, 0, 1) == '/') {
			$uriString = substr($uriString, 1, $len);
		}
		$len = strlen($uriString);
		if (substr($uriString, -1) == '/') {
			$uriString = substr($uriString, 0, $len - 1);
		}
		$uriString_exp = explode("/", $uriString);
		return $uriString_exp;
	}

	/**
	 * 배열에서 $key 다음 값을 가져온다
	 * @doc arrayNextValue|해당 배열의 원하는 키의 값을 가져온다.
	 * @param $array
	 * @param $key
	 * @param string $returnType ''|'array'
	 * @return mixed
	 */
	public static function arrayNextValue($array, $key, $returnType=''){
		$cnt = count($array);
		for ($i = 0; $cnt > $i; $i++) {
			if ($array[$i] == $key) {
				$k = $i + 1;
				if( $returnType == 'array' ){
					return array(
						'key'=>$k,
						'value'=>$array[$k]
					);
				} else {
					return $array[$k];
				}
			}
		}
	}

	/**
	 * 개발모드를 허용하는 IP
	 * @doc freePassIp|개발모드 등을 허용하기위한 IP설정
	 * @return bool
	 */
	public static function freePassIp(){
		if (isset($_SERVER['HTTP_CLIENT_IP'])){
			$ipAddress = $_SERVER['HTTP_CLIENT_IP'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_X_FORWARDED'])){
			$ipAddress = $_SERVER['HTTP_X_FORWARDED'];
		} else if(isset($_SERVER['HTTP_FORWARDED_FOR'])){
			$ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
		} else if(isset($_SERVER['HTTP_FORWARDED'])){
			$ipAddress = $_SERVER['HTTP_FORWARDED'];
		} else if(isset($_SERVER['REMOTE_ADDR'])){
			$ipAddress = $_SERVER['REMOTE_ADDR'];
		} else{
			$ipAddress = 'UNKNOWN';
		}

		$checkIp = array(
			'127.0.0.1',
			'192\.168\.0\.\d+',
		);
		return ( $ipAddress != 'UNKNOWN' && preg_match('/^(' . implode('|', $checkIp) . ')$/', $ipAddress) );
	}

	/**
	 * 도메인 유효성 검사
	 * @doc existsUrl|도메인 유효성 검사
	 * @param $url
	 * @param string $port
	 * @return bool
	 */
	public static function existsUrl($url, $port="80") {
		$fp = @fsockopen($url, $port);
		if($fp){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 전화번호 출력 타입을 정한다
	 * @doc phoneFormat|전화번호에 하이픈 등 기호를 넣는다
	 * @param string $phoneNumber 전화번호
	 * @param string $attach 연결할 기호
	 * @return mixed
	 * 021112222 -> 02-111-2222, 03111112222 -> 031-1111-2222, 0101112222 -> 010-111-2222
	 */
	public static function phoneFormat($phoneNumber, $attach='-'){
		return preg_replace("/(^02.{0}|^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/", "$1".$attach."$2".$attach."$3", $phoneNumber);
	}

	/**
	 * 사무실에서의 접속 여부 확인
	 * @doc fromOfficeAccess|사무실 접속 확인
	 */
	public static function fromOfficeAccess(){
		return in_array($_SERVER['REMOTE_ADDR'], array('192.168.0.1'));
	}

	/**
	 * range를 이용하여 키와 값이 동일한 배열을 생성
	 * select option 값에 유용하다
	 * @doc rangeToArray|키와값이동일한 숫자 구간 배열생성 (select option 값에 유용하다)
	 * @param int $low
	 * @param int $high
	 * @param int $step
	 * @param array $defaultArray
	 * @return array
	 */
	public static function rangeToArray($low, $high, $step=1, $defaultArray=array()){
		$return = $defaultArray;
		$rangeArray = range($low, $high, $step);
		foreach ($rangeArray as $value){
			$return[$value] = $value;
		}

		return $return;
	}

	/**
	 * 자바스크립트 삭제
	 * @doc stripJavascript|내용중 자바스크립트를 삭제한다
	 * @param $html
	 * @return string
	 */
	public static function stripJavascript($html){
		return preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);
	}

	/**
	 * xss 클린으로 바뀐 HTML 주석을 되돌린다
	 * @param string $html
	 * @return string
	 */
	public static function htmlCommentReSet($html){
		$reHtml = array();
		// 바뀐 주석을 찾는다
		preg_match_all("/&lt;!--.+--&gt;/", $html, $htmlComment);
		if( is_array($htmlComment) && count($htmlComment) > 0 ){
			if( is_array($htmlComment[0]) && count($htmlComment[0]) > 0 ){
				foreach($htmlComment[0] as $key => $comment){
					$reComment = $comment;
					$reComment = str_replace('&lt;', '<', $reComment);
					$reComment = str_replace('&gt;', '>', $reComment);
					$reHtml[$comment] = $reComment;
				}
			}
		}
		
		// 되돌린다
		if( is_array($reHtml) && count($reHtml) > 0 ){
			foreach($reHtml as $xssComment => $reComment){
				$html = str_replace($xssComment, $reComment, $html);
			}
		}
		return $html;
	}

	/**
	 * 배열에 데이터가 존재하는지 확인
	 * @param array $array
	 * @return bool
	 */
	public static function arrayCheck($array){
		if( is_array($array) && count($array) > 0 ){
			return true;
		}
		
		return false;
	}

	/**
	 * URL QUERY STRING 값을 가져온다
	 * @return string
	 */
	public static function getQueryString(){
		return parse_url($_SERVER['QUERY_STRING'], PHP_URL_PATH);
	}

	/**
	 * 리턴 URL 을 정의한다
	 * @brief 현재 URL 을 urlencode 처리 하여 리턴 (도메인 제외)
	 * @brief 중복 URL 인코딩 방지를 위해 디코딩 후 인코딩처리
	 * @return string
	 */
	public static function setReturnUrl(){
		$returnUrlInfo = '/' . uri_string();
		$queryString = self::getQueryString();
		if( trim($queryString) != '' ){
			$returnUrlInfo = $returnUrlInfo . '?' . $queryString;
		}
		return urlencode(urldecode($returnUrlInfo));
	}

	/**
	 * 캐시 저장용 디렉터리 존재여부
	 * @var bool 
	 */
	public static $existCacheDir = false;

	/**
	 * 캐시 설정
	 * @brief Memcached 생성한다
	 * @param $cacheName
	 * @param $cacheData
	 * @param int $cacheTime
	 * @param string $cacheDescription
	 * @return bool
	 */
	public static function setCache($cacheName, $cacheData, $cacheTime=43200, $cacheDescription=''){
		if( self::$existCacheDir == false ){
			$dirPath = Ci::app()->config->config['cache_path'];
			if (!is_dir($dirPath)) {
				mkdir($dirPath, 0700, true);
			}
			self::$existCacheDir = true;
		}

		$cacheData = json_encode($cacheData);
		$cacheData = self::md5encode($cacheData);

		// 캐시 목록에 추가
		self::addCacheList($cacheName, $cacheDescription);

		Ci::app()->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
		return Ci::app()->cache->save($cacheName, $cacheData, $cacheTime);
	}

	/**
	 * 캐시 데이터
	 * @var array
	 */
	public static $cacheData = array();

	/**
	 * 캐시 값을 가져온다
	 * @param $cacheName
	 * @param bool $assoc // 강제 배열 리턴
	 * @return mixed
	 */
	public static function getCache($cacheName, $assoc=false){
		if( empty(self::$cacheData[$cacheName]) ){
			Ci::app()->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
			self::$cacheData[$cacheName] = Ci::app()->cache->get($cacheName);
		}

		$cacheData = self::$cacheData[$cacheName];
		$cacheData = self::md5decode($cacheData);
		$cacheData = json_decode($cacheData, $assoc);

		return $cacheData;
	}

	/**
	 * 캐시 삭제
	 * @brief Memcached 삭제
	 * @param $cacheName
	 * @return bool
	 */
	public static function deleteCache($cacheName){
		Ci::app()->load->driver('cache', array('adapter' => 'memcached', 'backup' => 'file'));
		return Ci::app()->cache->delete($cacheName);
	}

	/**
	 * 서버 캐시 리스트업
	 * @param $cacheName
	 * @param string $cacheDescription
	 */
	public static function addCacheList($cacheName, $cacheDescription=''){
		$cacheParams = Params::$_CACHE_LIST_PATH;
		$objectCacheData = self::getJsonFileData($cacheParams, 'array', true);
		if( empty($objectCacheData) || !is_array($objectCacheData) ){
			$objectCacheData = array();
		}

		$objectCacheData[$cacheName] = $cacheDescription;

		$fp = fopen($cacheParams, "w");

		// json 형태로 변경후
		$objectCacheData = json_encode($objectCacheData);
		$objectCacheData = self::md5encode($objectCacheData);

		// 파일로 재 저장
		fwrite($fp, $objectCacheData);
	}

	/**
	 * 숫자만 남기고 나머지 삭제
	 * @param $replaceData
	 * @return null|string|string[]
	 */
	public static function numberOnly($replaceData) {
		$replaceData = trim($replaceData);
		$num = preg_replace('/[^0-9]/', '', $replaceData);
		return $num;
	}

	/**
	 * checkbox 선택처리
	 * @param $value
	 * @param $targetValue
	 * @return string
	 */
	public static function checked($value, $targetValue){
		$checked = 'checked';
		if( $value != $targetValue ){
			$checked = '';
		}
		return $checked;
	}

	/**
	 * select 선택처리
	 * @param $value
	 * @param $targetValue
	 * @return string
	 */
	public static function selected($value, $targetValue){
		$checked = 'selected';
		if( $value != $targetValue ){
			$checked = '';
		}
		return $checked;
	}

	/**
	 * DB 설정값을 기준으로 테이블명의 대소문자 구분체크후 테이블명 변경
	 * @brief table lower_case_table_names
	 * @param string $tableName
	 * @return string
	 */
	public static function dbTableCaseCheck($tableName){
		// DB 테이블 대소문자 구분여부에 따른 처리
		if( Ci::app()->siteConfig['lowerCaseTableNames'] == 1 ){
			$tableName = strtolower($tableName);
		}
		return $tableName;
	}
}