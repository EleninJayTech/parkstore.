<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_m extends MY_Model {
	public function __construct(){
		parent::__construct();
	}

	/**
	 * 상품 기본 정보 저장
	 * @param $saveData
	 * @return int|null
	 */
	public function saveProduct($saveData): int
	{
		$this->db->select('product_id');
		$this->db->where([
			'shop_code'=>$saveData['shop_code'],
			'product_no'=>$saveData['product_no'],
		]);
		$product_id = $this->db->get('product')->row('product_id');

		$set = [
			'product_link'=>$saveData['product_link'],
			'product_name'=>$saveData['product_name'],
			'product_info'=>$saveData['product_info'],
			'product_info_html'=>$saveData['product_info_html'],
			'product_detail_info_html'=>$saveData['product_detail_info_html'],
			'category_code'=>$saveData['category_code'],
			'category_depth_1'=>$saveData['category_depth_1'],
			'category_depth_2'=>$saveData['category_depth_2'],
			'category_depth_3'=>$saveData['category_depth_3'],
			'category_depth_4'=>$saveData['category_depth_4'],
		];

		if( empty($product_id) ){
			$set['reg_date'] = time();
			$set['shop_code'] = $saveData['shop_code'];
			$set['product_no'] = $saveData['product_no'];
			$this->db->insert('product', $set);
			$product_id = $this->db->insert_id();
		} else {
			$set['mod_date'] = time();
			$this->db->where([
				'shop_code'=>$saveData['shop_code'],
				'product_no'=>$saveData['product_no'],
			]);
			$this->db->update('product', $set);
		}

		return $product_id;
	}

	/**
	 * 상품 정보 기록
	 * @param $saveData
	 * @return bool|int
	 */
	public function saveProductInfo($saveData){
		if( count($saveData) == 0 ){
			return true;
		}

		$this->db->delete('product_info_list', [
			'product_id'=>$saveData[0]['product_id'],
		]);
		return $this->db->insert_batch('product_info_list', $saveData);
	}

	/**
	 * 상품 정보 기록
	 * @param $saveData
	 * @return bool|int
	 */
	public function saveOptionInfo($saveData){
		if( count($saveData) == 0 ){
			return true;
		}

		$this->db->delete('product_option_list', [
			'product_id'=>$saveData[0]['product_id'],
		]);
		return $this->db->insert_batch('product_option_list', $saveData);
	}

	/**
	 * 상품 기타 정보 기록
	 * @param $saveData
	 * @return bool|int
	 */
	public function saveEtcInfo($saveData){
		if( count($saveData) == 0 ){
			return true;
		}

		$this->db->delete('product_etc_info', [
			'product_id'=>$saveData[0]['product_id'],
		]);
		return $this->db->insert_batch('product_etc_info', $saveData);
	}

	/**
	 * 상품 이미지 정보 기록
	 * @param $saveData
	 * @return bool|int
	 */
	public function saveImgInfo($saveData){
		if( count($saveData) == 0 ){
			return true;
		}

		$this->db->delete('product_img', [
			'product_id'=>$saveData[0]['product_id'],
		]);
		return $this->db->insert_batch('product_img', $saveData);
	}

	/**
	 * 상품 이미지 상세 정보 기록
	 * @param $saveData
	 * @return bool|int
	 */
	public function saveImgDetailInfo($saveData){
		if( count($saveData) == 0 ){
			return true;
		}

		$this->db->delete('detail_img', [
			'product_id'=>$saveData[0]['product_id'],
		]);
		return $this->db->insert_batch('detail_img', $saveData);
	}
}