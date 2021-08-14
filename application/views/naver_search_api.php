<?php
/* @var $keyword */
/* @var $dataList */
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>키워드 추출</title>

	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<link rel="stylesheet" href="/datatable/datatables.min.css">
	<script src="/datatable/datatables.min.js"></script>
</head>
<body>
	<div style="width: 100%; padding: 20px;">
		<form action="/api/naver" method="get">
			<input type="text" placeholder="키워드" name="keyword" value="<?=$keyword?>">
			<input type="submit" value="조회">
		</form>
	</div>
	<table id="keywordList" class="display" style="width:100%">
		<thead>
		<tr>
			<th>relKeyword</th>
			<th>monthlyPcQcCnt</th>
			<th>monthlyMobileQcCnt</th>
		</tr>
		</thead>
		<tbody>
			<?php
			foreach ($dataList['keywordList'] as $list){
				$monthlyPcQcCnt = $list['monthlyPcQcCnt'];
				$monthlyPcQcCnt = ($monthlyPcQcCnt == '< 10' ? 1 : $monthlyPcQcCnt);
				$monthlyMobileQcCnt = $list['monthlyMobileQcCnt'];
				$monthlyMobileQcCnt = ($monthlyMobileQcCnt == '< 10' ? 1 : $monthlyMobileQcCnt);
				echo "
					<tr>
						<td>{$list['relKeyword']}</td>
						<td>{$monthlyPcQcCnt}</td>
						<td>{$monthlyMobileQcCnt}</td>
					</tr>
				";
			}
			?>
		</tbody>
	</table>
	<div style="width: 100%;">
		<input type="text" id="topKeywordList" style="width: 100%; padding: 10px 0; margin: 10px 0;">
	</div>
<script>
	jQuery(function($){
		$('[name="keyword"]').focus();
		$('#keywordList').DataTable({
			"order": [[ 2, "desc" ]]
			, "initComplete": function () {
				// var api = this.api();
				showKeyList();
			}
		});

		$('#keywordList').on('click', 'tr', function () {
			showKeyList();
		});
	});

	function showKeyList(){
		var showKeyList = [];
		$("#keywordList > tbody > tr > td:nth-child(1)").each(function(){
			var keyword = $(this).text();
			showKeyList.push(keyword);
		});
		$("#topKeywordList").val(showKeyList.join());
	}
</script>
</body>
</html>
