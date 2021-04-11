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
			'shop_code'=>$saveData['shop_code'],
			'product_no'=>$saveData['product_no'],
			'product_link'=>$saveData['product_link'],
			'product_name'=>$saveData['product_name'],
			'product_info'=>$saveData['product_info'],
			'product_info_html'=>$saveData['product_info_html'],
			'product_detail_info_html'=>$saveData['product_detail_info_html'],
		];

		if( empty($product_id) ){
			$set['reg_date'] = time();
			$this->db->insert('product', $set);
			$product_id = $this->db->insert_id();
		} else {
			$set['mod_date'] = time();
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
}