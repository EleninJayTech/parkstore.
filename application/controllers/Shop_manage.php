<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop_manage extends MY_Controller {
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$this->code();
	}

	public static $codeInfo = [
		'choitem'=>'CHO_',
		'goodsdeco'=>'GDC',
	];
	public function code(){
		$shop_code = $this->input->get('shop_code', true) ?? '';
		$encoding = $this->input->get('encoding', true) ?? '';
		$code_list = $this->input->get('code_list', true) ?? '';

		$code_list_arr = explode("\n", $code_list);
		$save_code_arr = [];
		foreach ($code_list_arr as $code){
			$code = trim($code);
			if( $code == '' ){
				continue;
			}
			if( $encoding == 'encode' ){
				$save_code_arr[] = self::$codeInfo[$shop_code]."{$code}";
			} else {
				$save_code_arr[] = str_replace(self::$codeInfo[$shop_code], '', $code);
			}
		}
		$change_code = implode("\n", $save_code_arr);
		$this->load->view('code_change', [
			'shop_code'=>$shop_code,
			'encoding'=>$encoding,
			'code_list'=>$code_list,
			'change_code'=>$change_code,
		]);
	}
}