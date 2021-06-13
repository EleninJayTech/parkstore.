<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Product extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function createExcel($PK_CODE='', $shop_code='choitem') {
		$this->load->model('Product_m');
		$fileName = "{$shop_code}.xlsx";
		$productList = $this->Product_m->getProductList($PK_CODE, $shop_code)->result_array();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$rows = 2;
		foreach ($productList as $val){
			// 상품상태 (신상품,중고상품)'
			$sheet->setCellValue('A' . $rows, '신상품');
			// 카테고리ID'
			$sheet->setCellValue('B' . $rows, "{$val['B']}");
			// 상품명'
			$sheet->setCellValue('C' . $rows, $val['C']);
			// 배송비 계산
			$delivery = $val['X'];
			$col_X_number_only = Utility::numberOnly($val['X']);
			$delivery = ($delivery == '택배' || preg_match('/택배/', $delivery) ? '택배‚ 소포‚ 등기' : '');
			$deliveryPrc = 2500;
			// WH 상품 3천원
			if( preg_match('/^WH/', $val['K']) ){
				$deliveryPrc = 3000;
			}
			if( preg_match('/^EP/', $val['K']) ){
				$deliveryPrc = 3000;
			}

			if( $shop_code == 'goodsdeco' ){
				if( $col_X_number_only > 0 ){
					$deliveryPrc = $col_X_number_only;
				} else {
					$deliveryPrc = 3000;
				}
			}
			// 판매가'
			$min_price = $val['D']; // 최저 판매 준수가
			$min_price = explode('/', $min_price);
			$min_price = Utility::numberOnly($min_price[0]);
			$price_origin = $val['price_origin'];
			$price_origin = Utility::numberOnly($price_origin);
			// 최대 수수료 상품만 2% + 전체 금액 3.85% 14700
			$fees = ($price_origin * 0.02) + (($price_origin + $deliveryPrc) * 0.0385); // 최대 수수료
			// 금액 별 마진 계산
			if( $price_origin < 10000 ){
				$up_margin = [0.4, 0.42, 0.44, 0.46, 0.48, 0.5]; // 할인가 구하기 위한 업 마진
				$margin = 0.1; // 실제 마진
			} else if( $price_origin < 50000 ){
				$up_margin = [0.3, 0.32, 0.34, 0.36, 0.38, 0.4];
				$margin = 0.09;
			} else if( $price_origin < 100000 ){
				$up_margin = [0.2, 0.22, 0.24, 0.26, 0.28, 0.3];
				$margin = 0.08;
			} else {
				$up_margin = [0.1, 0.12, 0.14, 0.16, 0.18, 0.2];
				$margin = 0.07;
			}

			if( is_array($up_margin) ){
				shuffle($up_margin);
				$up_margin = $up_margin[0];
			}

			// 수수료 더하고 마진 더하고
			$newPrice = (int) (($price_origin + $fees) + ($price_origin * $margin));
			// 나갈 적립금 더해서 금액 만들기
			// 기본 적립금 1% 리뷰적립 50원 사진리뷰 150원
			$newPrice = $newPrice + (int) ($price_origin * 0.01) + 50 + 150;
			// 10 단위 내림
			$newPrice = ((int) ($newPrice / 10)) * 10; // 최종 원하는 판매가
			$price = ($newPrice < $min_price ? $min_price : $newPrice); // 계산된 판매가가 최저판매 준수가 보다 작으면

			// 할인가를 만들기 위한 할인 전 가격 추출
			$up_price = $price_origin + (int) ($price_origin * $up_margin); // 할인 전 가격
			$up_price = ((int) ($up_price / 100)) * 100; // 최종 원하는 판매가 100 단위 내림
			$up_price = ($up_price < $min_price ? $min_price : $up_price); // 계산된 판매가가 최저판매 준수가 보다 작으면

			$sheet->setCellValue('D' . $rows, $up_price);
			// 재고수량'
			$sheet->setCellValue('E' . $rows, 999);
			// A/S 안내내용 (토요일 10:00 ~ 14:00 까지 응대가 가능하며 일요일은 쉽니다.)'
			$sheet->setCellValue('F' . $rows, 'A/S를 원하실 경우 판매자에게 연락 주시기 바랍니다. 단, 일부 품목의 경우 A/S가 불가능 할 수 있습니다.');
			// A/S 전화번호 (02-0000-0000)'
			$sheet->setCellValue('G' . $rows, '010-4963-0515');
			// 대표 이미지 파일명 (1.jpg)'
			$sheet->setCellValue('H' . $rows, $val['H']);
			// 추가 이미지 파일명 (2.jpg,3.jpg)'
			$sheet->setCellValue('I' . $rows, $val['I']);
			// 상품 상세정보 (<img src="http://bshop.phinf.naver.net/aaa.jpg">)'
			$productDetailHtml = $val['J'];
			if( $shop_code=='choitem' ){
				$productDetailHtml = str_replace('ec-data-src', 'ec-data-img', $productDetailHtml);
				$productDetailHtml = str_replace('src=', 'ori-src=', $productDetailHtml);
				$productDetailHtml = str_replace('ec-data-img="', 'src="http://choitemb2b.com', $productDetailHtml);
			}
			/*
			preg_match_all('/ec-data-src\s*=\s*"(.+?)"/',$productDetailHtml,$matches);
			$detailList = '';
			foreach ($matches as $imgSrc){
				$imgSrc = str_replace('ec-data-src="', '', $imgSrc);
				$imgSrc = str_replace('"', '', $imgSrc);
				foreach ($imgSrc as $item) {
					$link = "http://choitemb2b.com{$item}";
					$detailList .= "<img src='{$link}'>";
				}
			}
			*/
			$sheet->setCellValue('J' . $rows, $productDetailHtml);

			switch ($shop_code){
				case 'goodsdeco':
					$col_L = '굿즈데코';
					$pre_K = 'GDC';
					break;
				default:
					$col_L = '초이템';
					$pre_K = 'CHO_';
					break;
			}
			// 판매자 상품코드
			$sheet->setCellValue('K' . $rows, "{$pre_K}{$val['K']}");
			// 판매자 바코드' (초이템)'
			$sheet->setCellValue('L' . $rows, $col_L);
			// 제조사'
//			$sheet->setCellValue('M' . $rows, $val['test']);
			// 브랜드'
//			$sheet->setCellValue('N' . $rows, $val['test']);
			// 제조일자'
//			$sheet->setCellValue('O' . $rows, $val['test']);
			// 유효일자'
//			$sheet->setCellValue('P' . $rows, $val['test']);

			// 부가세 (과세상품,면세상품,영세상품 )'
			$sheet->setCellValue('Q' . $rows, '과세상품');
			// 미성년자 구매 (Y,N)'
			$sheet->setCellValue('R' . $rows, 'Y');
			// 구매평 노출여부 (Y,N)'
			$sheet->setCellValue('S' . $rows, 'Y');
			// 원산지 코드 (9680)'
//			$sheet->setCellValue('T' . $rows, "{$val['T']}");
			$sheet->setCellValue('T' . $rows, "04");

			// 수입사'
//			$sheet->setCellValue('U' . $rows, $val['test']);

			// 복수원산지 여부 (Y,N)'
			$sheet->setCellValue('V' . $rows, 'N');

			// 원산지 직접입력'
			$sheet->setCellValue('W' . $rows, $val['origin_area']);
			// 배송방법'
			$sheet->setCellValue('X' . $rows, $delivery);
			if( !empty($delivery) ){
				// 배송비 유형
				$sheet->setCellValue('Y' . $rows, '유료');
				// 기본배송비
				$sheet->setCellValue('Z' . $rows, $deliveryPrc);
				// 배송비 결제방식
				$sheet->setCellValue('AA' . $rows, '선결제');
				// 반품배송비'
				$sheet->setCellValue('AD' . $rows, $deliveryPrc);
				// 교환배송비'
				$deliveryPrc_CROSS = $deliveryPrc * 2;
				$sheet->setCellValue('AE' . $rows, $deliveryPrc_CROSS);
			}
			// 조건부무료-상품판매가합계'
//			$sheet->setCellValue('AB' . $rows, $val['test']);
			// 수량별부과-수량'
//			$sheet->setCellValue('AC' . $rows, $val['test']);
			// 지역별 차등배송비 정보'
//			$sheet->setCellValue('AF' . $rows, $val['test']);
			// 별도설치비'
//			$sheet->setCellValue('AG' . $rows, $val['test']);
			// 판매자 특이사항'
//			$sheet->setCellValue('AH' . $rows, $val['test']);
			// 즉시할인 값'
			$sale_amount = 0;
			if( $up_price > $price ){
				$sale_amount = $up_price - $price; // 할인가 구하기
			}
			$sheet->setCellValue('AI' . $rows, $sale_amount);
			// 즉시할인 단위'
			$sheet->setCellValue('AJ' . $rows, '원');
			// 복수구매할인 조건 값'
//			$sheet->setCellValue('AK' . $rows, $val['test']);
			// 복수구매할인 조건 단위'
//			$sheet->setCellValue('AL' . $rows, $val['test']);
			// 복수구매할인 값'
//			$sheet->setCellValue('AM' . $rows, $val['test']);
			// 복수구매할인 단위'
//			$sheet->setCellValue('AN' . $rows, $val['test']);
			// 상품구매시 포인트 지급 값'
//			$sheet->setCellValue('AO' . $rows, $val['test']);
			// 상품구매시 포인트 지급 단위'
//			$sheet->setCellValue('AP' . $rows, $val['test']);
			// 텍스트리뷰 작성시 지급 포인트'
//			$sheet->setCellValue('AQ' . $rows, $val['test']);
			// 포토/동영상 리뷰 작성시 지급 포인트'
//			$sheet->setCellValue('AR' . $rows, $val['test']);
			// "한달사용 텍스트리뷰 작성시 지급 포인트"'
//			$sheet->setCellValue('AS' . $rows, $val['test']);
			// "한달사용 포토/동영상리뷰 작성시 지급 포인트"'
//			$sheet->setCellValue('AT' . $rows, $val['test']);
			// "톡톡친구/스토어찜고객 리뷰 작성시 지급 포인트"'
//			$sheet->setCellValue('AU' . $rows, $val['test']);
			// 무이자 할부 개월'
//			$sheet->setCellValue('AV' . $rows, $val['test']);
			// 사은품'
//			$sheet->setCellValue('AW' . $rows, $val['test']);

			$product_id = $val['product_id'];
			$optionList = $this->Product_m->getOptionList($product_id)->result_array();
			if( !empty($optionList) && count($optionList) > 0 ){
				// 옵션형태 있으면 단독형
				$sheet->setCellValue('AX' . $rows, '조합형');
				$optionNameArr = [];
				$option_value_arr = [];
				$option_price_arr = [];
				$option_stock_arr = [];
				foreach ($optionList as $optionName){
					$optionNameArr[] = $optionName['option_name'];
					$option_value_arr[] = $optionName['option_value'];
					$_option_price = Utility::numberOnly($optionName['option_price']);
					$_option_price = (int) ($_option_price * $margin);
					$_option_price = ($_option_price / 10) * 10; // 최종 원하는 판매가
					$option_price_arr[] = $_option_price;
					$option_stock_arr[] = $optionName['option_stock'];
				}
				// 옵션명'
				$optionListStr = implode("\n", $optionNameArr);
				$sheet->setCellValue('AY' . $rows, $optionListStr);
				// 옵션값'
				$option_value_str = implode("\n", $option_value_arr);
				$option_value_str = str_replace('*', 'x', $option_value_str);
				$option_value_str = preg_replace('/\([\+|\-].+\)/', '', $option_value_str);
				$sheet->setCellValue('AZ' . $rows, $option_value_str);
				// 옵션가'
				$option_price_str = implode("\n", $option_price_arr);
				$sheet->setCellValue('BA' . $rows, $option_price_str);
				// 옵션 재고수량'
				$option_stock_str = implode("\n", $option_stock_arr);
				$sheet->setCellValue('BB' . $rows, $option_stock_str);
			}

			// 추가상품명'
//			$sheet->setCellValue('BC' . $rows, $val['test']);
			// 추가상품값'
//			$sheet->setCellValue('BD' . $rows, $val['test']);
			// 추가상품가'
//			$sheet->setCellValue('BE' . $rows, $val['test']);
			// 추가상품 재고수량'
//			$sheet->setCellValue('BF' . $rows, $val['test']);
			// 상품정보제공고시 품명'
//			$sheet->setCellValue('BG' . $rows, $val['test']);
			// 상품정보제공고시 모델명'
//			$sheet->setCellValue('BH' . $rows, $val['test']);
			// 상품정보제공고시 인증허가사항'
//			$sheet->setCellValue('BI' . $rows, $val['test']);
			// 상품정보제공고시 제조자'
//			$sheet->setCellValue('BJ' . $rows, $val['test']);

			// 스토어찜회원 전용여부 (Y,N)'
			$sheet->setCellValue('BK' . $rows, 'N');

			// 문화비 소득공제'
//			$sheet->setCellValue('BL' . $rows, $val['test']);
			// ISBN'
//			$sheet->setCellValue('BM' . $rows, $val['test']);
			// 독립출판'
//			$sheet->setCellValue('BN' . $rows, $val['test']);
			$rows++;
			if( $rows == 2 ){
//				break;
			}
		}
//		exit;
		$writer = new Xlsx($spreadsheet);
		$writer->save("upload/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		redirect(base_url()."/upload/".$fileName);
	}
}