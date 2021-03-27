<?php defined('BASEPATH') OR exit('No direct script access allowed');

ini_set("default_socket_timeout", 30);

class NaverSearchAdApi{
	/**
	 * @var $api NaverRestApi
	 */
	protected $api = null;

	public function __construct(){
		$this->initApi();
	}

	protected function initApi(){
		$config = parse_ini_file(APPPATH . "../secure.ini");
		$apiParams = [
			'baseUrl' => $config['BASE_URL'],
			'apiKey' => $config['API_KEY'],
			'secretKey' => $config['SECRET_KEY'],
			'customerId' => $config['CUSTOMER_ID'],
		];
		Ci::app()->load->library('NaverRestApi', $apiParams);
		$this->api = Ci::app()->naverrestapi;
	}

	public function relKwdStat($keyword=''){
		if( empty($keyword) ){
			return json_encode(array());
		}

		return $this->api->GET('/keywordstool', array(
			'hintKeywords'=>$keyword,
		));
	}
}