<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 라이브러리에서 인스턴스를 참조 사용
 * Class Ci
 */
class Ci{
	private static $_ci;

	public function __construct(){
		if( self::$_ci === null || empty(self::$_ci) ){
			self::$_ci =& get_instance();
		}
	}

	/**
	 * @return CI_Controller
	 */
	public static function app(){
		return self::$_ci;
	}
}