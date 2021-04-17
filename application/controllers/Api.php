<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function product($process='save'){
		$encrypt_key = $this->input->post('encrypt_key');
		$product = $this->input->post('product');

		$this->load->model('Product_m');
		$this->db->trans_begin();
		try{
			if( $encrypt_key !== 'e8b6a94f577bd529c2e67da6aa449219' ){
				throw new Exception("ERR");
			}

			$product = json_decode($product, true);
			$shop_code = $product['shop_code']; // 사이트 코드
			$product_no = $product['product_no']; // 상품 번호
			$product_link = $product['product_link']; // 상품 링크
			$product_name = $product['product_name']; // 상품 명
			$product_info = $product['product_info']; // 상품 정보 전체 텍스트
			$product_info_html = $product['product_info_html']; // 상품 정보 전체 HTML
			$product_detail_info_html = $product['product_detail_info_html']; // 상품 상세 정보 전체 HTML

			$product_info_list = $product['product_info_list']; // 상품 정보
			$product_option_list = $product['product_option_list']; // 옵션 정보
			$product_etc_info = $product['product_etc_info']; // 상품 기타 정보
			$product_img = $product['product_img']; // 이미지 정보
			$detail_img = $product['detail_img']; // 상세 이미지 정보

			// 상품 기록
			$product_id = $this->Product_m->saveProduct([
				'shop_code'=>$shop_code,
				'product_no'=>$product_no,
				'product_link'=>$product_link,
				'product_name'=>$product_name,
				'product_info'=>$product_info,
				'product_info_html'=>$product_info_html,
				'product_detail_info_html'=>$product_detail_info_html,
			]);

			if( empty($product_id) ){
				throw new Exception("상품 오류 [{$product_no}]:{$product_link}");
			}

			// 상품 정보 기록
			if( !empty($product_info_list) && is_array($product_info_list) ){
				$saveData = [];
				foreach ($product_info_list as $info) {
					$info_name = trim($info['info_name']);
					$info_value = trim($info['info_value']);
					if( $info_name == '' && $info_value == '' ){
						continue;
					}

					$saveData[] = [
						'product_id'=>$product_id,
						'info_name'=>$info_name,
						'info_value'=>$info_value,
						'seq'=>$info['seq'],
					];
				}
				$saveProductInfo = $this->Product_m->saveProductInfo($saveData);

				if( empty($saveProductInfo) ){
					throw new Exception("상품 정보 오류 [{$product_no}]:{$product_link}");
				}
			}

			$optionPass = [
				'-------------------',
				'- [필수] 옵션을 선택해 주세요 -',
			];
			// 상품 옵션 기록
			if( !empty($product_option_list) && is_array($product_option_list) ){
				$saveData = [];
				foreach ($product_option_list as $info) {
					$option_name = trim($info['option_name']);
					$option_value = trim($info['option_value']);
					if( $option_name == '' && $option_value == '' ){
						continue;
					}

					if( in_array($option_value, $optionPass) ){
						continue;
					}

					$saveData[] = [
						'product_id'=>$product_id,
						'option_name'=>$option_name,
						'option_value'=>$option_value,
						'seq'=>$info['seq'],
					];
				}
				$saveOptionInfo = $this->Product_m->saveOptionInfo($saveData);

				if( empty($saveOptionInfo) ){
					throw new Exception("상품 옵션 오류 [{$product_no}]:{$product_link}");
				}
			}

			// 상품 기타정보 기록
			if( !empty($product_etc_info) && is_array($product_etc_info) ){
				$saveData = [];
				foreach ($product_etc_info as $info) {
					$info_title = trim($info['info_title']);
					$info_desc = trim($info['info_desc']);
					if( $info_title == '' && $info_desc == '' ){
						continue;
					}

					$saveData[] = [
						'product_id'=>$product_id,
						'info_title'=>$info_title,
						'info_desc'=>$info_desc,
						'seq'=>$info['seq'],
					];
				}
				$saveEtcInfo = $this->Product_m->saveEtcInfo($saveData);

				if( empty($saveEtcInfo) ){
					throw new Exception("상품 기타 오류 [{$product_no}]:{$product_link}");
				}
			}

			// 상품 이미지 정보 기록
			if( !empty($product_img) && is_array($product_img) ){
				$saveData = [];
				foreach ($product_img as $info) {
					$img_url = trim($info['img_url']);
					$img_file_name = trim($info['img_file_name']);
					$img_file_full_path = trim($info['img_file_full_path']);
					if( $img_url == '' && $img_file_name == '' ){
						continue;
					}

					$saveData[] = [
						'product_id'=>$product_id,
						'img_url'=>$img_url,
						'img_file_name'=>$img_file_name,
						'img_file_full_path'=>$img_file_full_path,
						'seq'=>$info['seq'],
					];
				}
				$saveInfo = $this->Product_m->saveImgInfo($saveData);

				if( empty($saveInfo) ){
					throw new Exception("상품 이미지 오류 [{$product_no}]:{$product_link}");
				}
			}

			// 상품 이미지 정보 기록
			if( !empty($detail_img) && is_array($detail_img) ){
				$saveData = [];
				foreach ($detail_img as $info) {
					$img_url = trim($info['img_url']);
					$img_file_name = trim($info['img_file_name']);
					$img_file_full_path = trim($info['img_file_full_path']);
					if( $img_url == '' && $img_file_name == '' ){
						continue;
					}

					$saveData[] = [
						'product_id'=>$product_id,
						'img_url'=>$img_url,
						'img_file_name'=>$img_file_name,
						'img_file_full_path'=>$img_file_full_path,
						'seq'=>$info['seq'],
					];
				}
				$saveInfo = $this->Product_m->saveImgDetailInfo($saveData);

				if( empty($saveInfo) ){
					throw new Exception("상품 상세이미지 오류 [{$product_no}]:{$product_link}");
				}
			}

			$message = '';
			$status = 200;
			$this->db->trans_commit();
		} catch (Exception $e){
			$message = $e->getMessage();
			$status = 0;
			$this->db->trans_rollback();
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200) // Return status
			->set_output(json_encode(['message'=>$message,'status'=>$status,]));
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