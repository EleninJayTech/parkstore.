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

	public function product($type='form'){
	    if( $type == 'upload' ){
	        $this->product_upload();
	        exit;
        }

        $this->load->view('product_save_form', [

        ]);
    }

    public function product_upload(){
        $config['upload_path']          = FCPATH . '_data/upload';
//        $config['allowed_types']        = 'csv|xls|xlsx';
//        $config['max_size']             = 1000000;
//        $config['max_width']            = 10240;
//        $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if( !$this->upload->do_upload("upload_file") ){
            echo 'ERROR!!';
            exit;
        }

        $fileInfo = $this->upload->data();

        var_dump($fileInfo);
    }
}