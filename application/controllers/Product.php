<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Product extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function createExcel($shop='choitem') {
		$this->load->model('Product_m');
		$fileName = "{$shop}.xlsx";
//		$employeeData = $this->Product_m->employeeList();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$rows = 1;
		foreach ($employeeData as $val){
			// 상품상태 (신상품,중고상품)'
			$sheet->setCellValue('A' . $rows, $val['test']);
			// 카테고리ID'
			$sheet->setCellValue('B' . $rows, $val['test']);
			// 상품명'
			$sheet->setCellValue('C' . $rows, $val['test']);
			// 판매가'
			$sheet->setCellValue('D' . $rows, $val['test']);
			// 재고수량'
			$sheet->setCellValue('E' . $rows, $val['test']);
			// A/S 안내내용 (토요일 10:00 ~ 14:00 까지 응대가 가능하며 일요일은 쉽니다.)'
			$sheet->setCellValue('F' . $rows, $val['test']);
			// A/S 전화번호 (02-0000-0000)'
			$sheet->setCellValue('G' . $rows, $val['test']);
			// 대표 이미지 파일명 (1.jpg)'
			$sheet->setCellValue('H' . $rows, $val['test']);
			// 추가 이미지 파일명 (2.jpg,3.jpg)'
			$sheet->setCellValue('I' . $rows, $val['test']);
			// 상품 상세정보 (<img src="http://bshop.phinf.naver.net/aaa.jpg">)'
			$sheet->setCellValue('J' . $rows, $val['test']);

			// 판매자 상품코드 (초이템하나지정)'
			$sheet->setCellValue('K' . $rows, $val['test']);
			// 판매자 바코드'
//			$sheet->setCellValue('L' . $rows, $val['test']);
			// 제조사'
//			$sheet->setCellValue('M' . $rows, $val['test']);
			// 브랜드'
//			$sheet->setCellValue('N' . $rows, $val['test']);
			// 제조일자'
//			$sheet->setCellValue('O' . $rows, $val['test']);
			// 유효일자'
//			$sheet->setCellValue('P' . $rows, $val['test']);

			// 부가세 (과세상품,면세상품,영세상품 )'
			$sheet->setCellValue('Q' . $rows, $val['test']);
			// 미성년자 구매 (Y,N)'
			$sheet->setCellValue('R' . $rows, $val['test']);
			// 구매평 노출여부 (Y,N)'
			$sheet->setCellValue('S' . $rows, $val['test']);
			// 원산지 코드 (9680)'
			$sheet->setCellValue('T' . $rows, $val['test']);

			// 수입사'
//			$sheet->setCellValue('U' . $rows, $val['test']);

			// 복수원산지 여부 (Y,N)'
			$sheet->setCellValue('V' . $rows, $val['test']);

			// 원산지 직접입력'
//			$sheet->setCellValue('W' . $rows, $val['test']);
			// 배송방법'
//			$sheet->setCellValue('X' . $rows, $val['test']);
			// 배송비 유형'
//			$sheet->setCellValue('Y' . $rows, $val['test']);
			// 기본배송비'
//			$sheet->setCellValue('Z' . $rows, $val['test']);
			// 배송비 결제방식'
//			$sheet->setCellValue('AA' . $rows, $val['test']);
			// 조건부무료-상품판매가합계'
//			$sheet->setCellValue('AB' . $rows, $val['test']);
			// 수량별부과-수량'
//			$sheet->setCellValue('AC' . $rows, $val['test']);
			// 반품배송비'
//			$sheet->setCellValue('AD' . $rows, $val['test']);
			// 교환배송비'
//			$sheet->setCellValue('AE' . $rows, $val['test']);
			// 지역별 차등배송비 정보'
//			$sheet->setCellValue('AF' . $rows, $val['test']);
			// 별도설치비'
//			$sheet->setCellValue('AG' . $rows, $val['test']);
			// 판매자 특이사항'
//			$sheet->setCellValue('AH' . $rows, $val['test']);
			// 즉시할인 값'
//			$sheet->setCellValue('AI' . $rows, $val['test']);
			// 즉시할인 단위'
//			$sheet->setCellValue('AJ' . $rows, $val['test']);
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
			// 옵션형태'
//			$sheet->setCellValue('AX' . $rows, $val['test']);
			// 옵션명'
//			$sheet->setCellValue('AY' . $rows, $val['test']);
			// 옵션값'
//			$sheet->setCellValue('AZ' . $rows, $val['test']);
			// 옵션가'
//			$sheet->setCellValue('BA' . $rows, $val['test']);
			// 옵션 재고수량'
//			$sheet->setCellValue('BB' . $rows, $val['test']);
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
			$sheet->setCellValue('BK' . $rows, $val['test']);

			// 문화비 소득공제'
//			$sheet->setCellValue('BL' . $rows, $val['test']);
			// ISBN'
//			$sheet->setCellValue('BM' . $rows, $val['test']);
			// 독립출판'
//			$sheet->setCellValue('BN' . $rows, $val['test']);
			$rows++;
		}
		$writer = new Xlsx($spreadsheet);
		$writer->save("upload/".$fileName);
		header("Content-Type: application/vnd.ms-excel");
		redirect(base_url()."/upload/".$fileName);
	}
}