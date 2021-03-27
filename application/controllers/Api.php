<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {
	public function naver($keyword=''){
		$this->load->library('NaverSearchAdApi');
		if( empty($keyword) ){
			$keyword = $this->input->get('keyword', true) ?? '손목보호대';
		} else {
			$keyword = urldecode($keyword);
		}
		$dataList = $this->naversearchadapi->relKwdStat($keyword);
		echo "<pre>";
		print_r($dataList);
		echo "</pre>";
	}
}