<?php
/**
 * @var $shop_code
 * @var $encoding
 * @var $code_list
 * @var $change_code
 */
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>코드변경</title>

	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<!--	<link rel="stylesheet" href="/datatable/datatables.min.css">-->
<!--	<script src="/datatable/datatables.min.js"></script>-->
	<style type="text/css">
		@import url(http://fonts.googleapis.com/earlyaccess/nanumgothic.css);
		@font-face {
			font-family: 'Nanum Gothic';
			font-style: normal;
			font-weight: 700;
			src: url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-Bold.eot);
			src: url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-Bold.eot?#iefix) format('embedded-opentype'),
			url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-Bold.woff2) format('woff2'),
			url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-Bold.woff) format('woff'),
			url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-Bold.ttf) format('truetype');
		}
		@font-face {
			font-family: 'Nanum Gothic';
			font-style: normal;
			font-weight: 400;
			src: url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-Regular.eot);
			src: url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-Regular.eot?#iefix) format('embedded-opentype'),
			url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-Regular.woff2) format('woff2'),
			url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-Regular.woff) format('woff'),
			url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-Regular.ttf) format('truetype');
		}
		@font-face {
			font-family: 'Nanum Gothic';
			font-style: normal;
			font-weight: 800;
			src: url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-ExtraBold.eot);
			src: url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-ExtraBold.eot?#iefix) format('embedded-opentype'),
			url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-ExtraBold.woff2) format('woff2'),
			url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-ExtraBold.woff) format('woff'),
			url(//themes.googleusercontent.com/static/fonts/earlyaccess/nanumgothic/v3/NanumGothic-ExtraBold.ttf) format('truetype');
		}
		html body *{
			font-family: 'Nanum Gothic', sans-serif;
			font-weight: bold;
		}
		textarea{font-size: 16px}
	</style>
</head>
<body>
	<form action="<?=base_url().'shop_manage'?>" method="get">
		<div>
			<label><input type="radio" name="shop_code" value="choitem" <?=Utility::checked($shop_code, 'choitem');?>>초이템</label>
			<label><input type="radio" name="shop_code" value="goodsdeco" <?=Utility::checked($shop_code, 'goodsdeco');?>>굿즈데코</label>
		</div>
		<div>
			<label><input type="radio" name="encoding" value="decode" <?=Utility::checked($encoding, 'decode');?>>제거</label>
			<label><input type="radio" name="encoding" value="encode" <?=Utility::checked($encoding, 'encode');?>>붙이기</label>
		</div>
		<textarea name="code_list" id="codeList" cols="35" rows="20"><?=$code_list?></textarea>
		<textarea readonly cols="35" rows="20"><?=$change_code?></textarea>
		<input type="submit" value="추출">
	</form>
<script>
	jQuery(function($){

	});
</script>
</body>
</html>
