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
			'pk_code'=>$saveData['pk_code'],
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

	/**
	 * @return CI_DB_result|bool|mixed|string
	 */
	public function getProductList(){
		$sql = "
			SELECT
			    p.product_id
			    , p.product_no
			    , '신상품' as A
			    , p.category_code AS B
			    , p.product_name AS C
			    , (SELECT pil.info_value FROM product_info_list AS pil WHERE pil.product_id = p.product_id AND pil.info_name = '공급가' ) AS price_origin
			    , (SELECT pil.info_value FROM product_info_list AS pil WHERE pil.product_id = p.product_id AND pil.info_name = '최저 판매 준수가' ) AS D
			    , (SELECT img_file_name FROM product_img AS pi WHERE pi.product_id = p.product_id ORDER BY seq ASC LIMIT 1) AS H
			    , (SELECT GROUP_CONCAT(img_file_name) FROM product_img AS pi WHERE pi.product_id = p.product_id AND seq != 1 ORDER BY seq ASC) AS I
			    , p.product_detail_info_html AS J
			    , (SELECT pil.info_value FROM product_info_list AS pil WHERE pil.product_id = p.product_id AND pil.info_name = '자체상품코드' ) AS K
			    , (SELECT IF(pil.info_value = '국내', '00', '0200037') FROM product_info_list AS pil WHERE pil.product_id = p.product_id AND pil.info_name = '제조국' ) AS T
				, (SELECT pil.info_value FROM product_info_list AS pil WHERE pil.product_id = p.product_id AND pil.info_name = '제조국' ) AS origin_area
				, (SELECT pil.info_value FROM product_info_list AS pil WHERE pil.product_id = p.product_id AND pil.info_name = '배송방법' ) AS X
			FROM product AS p
		";
		return $this->db->query($sql);
	}

	/**
	 * @param $product_id
	 * @return CI_DB_result|bool|mixed|string
	 */
	public function getOptionList($product_id){
		if( empty($product_id) ){
			return false;
		}

		$sql = "
			SELECT o.option_name
			    , GROUP_CONCAT(o.option_value) AS option_value
			    , GROUP_CONCAT(IF(o.option_price_type = 'plus', o.option_price, CONCAT('-', o.option_price))) AS option_price
				, GROUP_CONCAT(999) AS option_stock
			FROM product_option_list AS o
			where o.product_id = {$product_id}
			GROUP BY o.option_name
			ORDER BY seq ASC
		";
		return $this->db->query($sql);
	}
}