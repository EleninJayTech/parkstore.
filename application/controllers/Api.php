<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function product($process='save'){
		$encrypt_key = $this->input->post('encrypt_key');
		$product_info = $this->input->post('product_info');

		if( $encrypt_key !== 'e8b6a94f577bd529c2e67da6aa449219' ){
			echo "ERR";
			exit;
		}

		$product_info = json_decode($product_info, true);
		echo $product_info['shop_code']; // 사이트 코드
		echo $product_info['product_no']; // 상품 번호
		echo $product_info['product_link']; // 상품 링크
		echo $product_info['product_name']; // 상품 명
		echo $product_info['product_info']; // 상품 정보 전체 텍스트
		echo $product_info['product_info_html']; // 상품 정보 전체 HTML
		echo $product_info['product_detail_info_html']; // 상품 상세 정보 전체 HTML
		$product_info_list = $product_info['product_info_list']; // 상품 정보
		$product_option_list = $product_info['product_option_list']; // 옵션 정보
		$product_etc_info = $product_info['product_etc_info']; // 상품 기타 정보
		$product_img = $product_info['product_img']; // 이미지 정보
		$detail_img = $product_info['detail_img']; // 상세 이미지 정보
	}

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