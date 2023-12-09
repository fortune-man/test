<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/PHPExcel.php');
include_once('./sub_site.php');
// 상품이 많을 경우 대비 설정변경
set_time_limit ( 0 );
ini_set('memory_limit', '50M');

error_reporting(E_ALL);
ini_set("display_errors", 1);

function column_char($i) {
	return chr( 65 + $i ); 
}
$mode ='';
$act = '';
$type = '';
$nowdatetime = date("Y-m-d H:i:s");
if( isset($_GET['mode']) && $_GET['mode']) $mode = trim(strip_tags(clean_xss_attributes($_GET['mode'])));
if( isset($_GET['act']) && $_GET['act']) $act = trim(strip_tags(clean_xss_attributes($_GET['act'])));
if( isset($_GET['type']) && $_GET['type']) $type = trim(strip_tags(clean_xss_attributes($_GET['type'])));

if($type == "zenpc") $file_head_name = "Zenpc";
else $file_head_name = "Proxy";

if($mode == 'clear') {
	$headers = array('번호', '계정', '비번', '복구메일', '메모','수정날짜');
	$widths  = array(5, 30, 20, 40, 20, 20);
	$header_bgcolor = 'FFABCDEF';
	$last_char = column_char(count($headers) - 1);
	
	$rows =  array();
	if($type=='zenpc') {//젠피씨인경우
		$sql = mysqli_query($sub_site['zenpc']," select *  from a_gidpwrepair where  status = 'clear' order by type ASC ,updatetime DESC , proxydatetime DESC ");
		for($i=1;$row=mysqli_fetch_array($sql);$i++){
			$rows[] = array(
					   $i,
					   $row['gid'],
					   $row['pwd'],
					   $row['repair'],
					   $row['memo'],
					   $row['proxydatetime']
					 );	
	
			if($act == 'clear') {
				mysqli_query($sub_site['zenpc']," insert into a_gidbank set gid = '{$row['gid']}' , pwd  = '{$row['gid']}', repair  = '{$row['gid']}', memo  = '{$row['gid']}' , updatetime = '{$nowdatetime}', proxydatetime = '{$nowdatetime}' , status = 'backup' , type = 'zenpc' ");
				mysqli_query($sub_site['zenpc']," delete from a_gidpwrepair where gid = '{$row['gid']}' ");
			}
		} 
					 
	} else {//프록시인경우
	
		$sql = mysqli_query($sub_site['proxy']," select *  from a_gidpwrepair where  status = 'clear' order by type ASC ,updatetime DESC , proxydatetime DESC ");
		for($i=1;$row=mysqli_fetch_array($sql);$i++){
			$rows[] = array(
					   $i,
					   $row['gid'],
					   $row['pwd'],
					   $row['repair'],
					   $row['memo'],
					   $row['proxydatetime']
					 );	
	
			if($act == 'clear') {
				mysqli_query($sub_site['proxy']," insert into a_gidbank set gid = '{$row['gid']}' , pwd  = '{$row['gid']}', repair  = '{$row['gid']}', memo  = '{$row['gid']}', updatetime = '{$nowdatetime}' , proxydatetime = '{$nowdatetime}' , status = 'backup' , type = '' ");
				mysqli_query($sub_site['proxy']," delete from a_gidpwrepair where gid = '{$row['gid']}' ");
			}
		} 		
	}
	if($rows){
		$data = array_merge(array($headers), $rows);
		
		$excel = new PHPExcel();
		foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
		$excel->getActiveSheet()->fromArray($data,NULL,'A1');
		
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".$file_head_name."ClearID-".date("ymd", time()).".xls\"");
		header("Cache-Control: max-age=0");
		
		$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
		$writer->save('php://output'); 
	} else {
		alert("점검할 데이타가 없습니다. ");		
	}
} else if($mode=='errorlist'){

	$headers = array('번호','프록시','컴퓨터','프로필','계정', '비번', '복구메일','수정날짜', '계정디비','계정디비등록프록시');
	$widths  = array(5, 15,15,10, 20, 20,35, 20, 20,20,20);
	$header_bgcolor = 'FFABCDEF';
	$last_char = column_char(count($headers) - 1);
	
	if($type=="zenpc") {
		$sql = mysqli_query($sub_site['zenpc'],"select a.* , b.gid as bgid , b.proxy as bproxy from a_server a
							left join a_gidpwrepair b
							on (a.gid = b.gid and a.proxy != b.proxy )
							where a.gid <> '' 
							and a.type = 'zenpc'
							and b.gid <> ''
							order by a.gid ASC
							");
		for($i=1;$row=mysqli_fetch_array($sql);$i++){
			
			$rows[] = array(
						   $i,
						   $row['proxy'],
						   $row['computer'],
						   $row['profile'],
						   $row['gid'],
						   $row['pwd'],
						   $row['repair'],
						   $row['log_time'],
						   $row['bgid'],
						   $row['bproxy']
						 );	
		
			if($act == 'clear') {
				//sql_query(" delete from a_gidpwrepair where gid = '{$row['gid']}' ");
			}
		
						 
		}
	} else {
		$sql = mysqli_query($sub_site['proxy'],"select a.* , b.gid as bgid , b.proxy as bproxy from a_server a
							left join a_gidpwrepair b
							on (a.gid = b.gid and a.proxy != b.proxy )
							where a.gid <> '' 
							and a.type = ''
							and b.gid <> ''
							order by a.gid ASC
							");
		for($i=1;$row=mysqli_fetch_array($sql);$i++){
			
			$rows[] = array(
						   $i,
						   $row['proxy'],
						   $row['computer'],
						   $row['profile'],
						   $row['gid'],
						   $row['pwd'],
						   $row['repair'],
						   $row['log_time'],
						   $row['bgid'],
						   $row['bproxy']
						 );	
		
			if($act == 'clear') {
				//sql_query(" delete from a_gidpwrepair where gid = '{$row['gid']}' ");
			}
		
						 
		}		
	}
	$data = array_merge(array($headers), $rows);
	
	$excel = new PHPExcel();
	foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
	$excel->getActiveSheet()->fromArray($data,NULL,'A1');
	
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"".$file_head_name."ErrorID-".date("ymd", time()).".xls\"");
	header("Cache-Control: max-age=0");
	
	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
	$writer->save('php://output'); 	
} else if($mode=='logoff'){

	$headers = array('번호','프록시','컴퓨터','프로필','계정', '비번', '복구메일','활동날짜', '뷰어타입');
	$widths  = array(5, 15,15,10, 20, 20, 20, 20, 20);
	$header_bgcolor = 'FFABCDEF';
	$last_char = column_char(count($headers) - 1);
	
	if($type=="zenpc") {
		$sql = mysqli_query($sub_site['zenpc'],"select * from a_server where gid <> '' and loginstatus = 'off' and type = 'zenpc' order by log_time ASC ");
		for($i=1;$row=mysqli_fetch_array($sql);$i++){
			
			$rows[] = array(
						   $i,
						   $row['proxy'],
						   $row['computer'],
						   $row['profile'],
						   $row['gid'],
						   $row['pwd'],
						   $row['repair'],
						   $row['log_time'],
						   $row['type']
						   );	
		
			if($act == 'clear') {
				//sql_query(" delete from a_gidpwrepair where gid = '{$row['gid']}' ");
			}
		
						 
		}
	} else {
		$sql = mysqli_query($sub_site['proxy'],"select * from a_server where gid <> '' and loginstatus = 'off' and type = '' order by log_time ASC ");
		for($i=1;$row=mysqli_fetch_array($sql);$i++){
			
			$rows[] = array(
						   $i,
						   $row['proxy'],
						   $row['computer'],
						   $row['profile'],
						   $row['gid'],
						   $row['pwd'],
						   $row['repair'],
						   $row['log_time'],
						   $row['type']
						   );	
		
			if($act == 'clear') {
				//sql_query(" delete from a_gidpwrepair where gid = '{$row['gid']}' ");
			}
		
						 
		}		
	}
	$data = array_merge(array($headers), $rows);
	
	$excel = new PHPExcel();
	foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
	$excel->getActiveSheet()->fromArray($data,NULL,'A1');
	
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"logoff".$file_head_name."ID-".date("ymd", time()).".xls\"");
	header("Cache-Control: max-age=0");
	
	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
	$writer->save('php://output'); 	
} else if($mode=='missing'){

	$headers = array('번호','계정', '비번', '복구메일','메모', '뷰어타입');
	$widths  = array(5, 15,15,10, 20, 20, 20, 20, 20);
	$header_bgcolor = 'FFABCDEF';
	$last_char = column_char(count($headers) - 1);
	
	if($type=="zenpc") {
		$sql = mysqli_query($sub_site['zenpc'],"select a.* , b.proxy as bproxy , c.proxy as cproxy from a_gidpwrepair a 
left join a_server b 
on a.gid = b.gid and b.proxy like concat('00x',a.proxy,'%')
left join a_server c 
on a.gid = c.gid and c.proxy like concat('01x',a.proxy,'%')
where a.proxy <>'' and ( b.proxy is null or c.proxy is null) order by a.proxydatetime DESC ");
		for($i=1;$row=mysqli_fetch_array($sql);$i++){
			
			$rows[] = array(
						   $i,
						   $row['gid'],
						   $row['pwd'],
						   $row['repair'],
						   $row['memo'],
						   $row['type']
						   );	
		
			if($act == 'clear') {
					$date = date_create(date("Y-m-d H:i:s"));
					date_add($date, date_interval_create_from_date_string("-7 days"));
					$clear_time = date_format($date, "Y-m-d H:i:s");
					if($row['proxydatetime'] < $clear_time) {
						mysqli_query($sub_site['zenpc']," update a_gidpwrepair set proxy = '' ,status = '' , type = '' , updatetime = '{$nowdatetime}' , proxydatetime = '{$nowdatetime}'  where gid = '{$row['gid']}' ");			
					}
		
			}
		}
	} else {
		$sql = mysqli_query($sub_site['proxy'],"select a.*  from a_gidpwrepair a left join a_server b on a.proxy = b.proxy where b.proxy is null and a.proxy !='' order by a.proxydatetime DESC ");
		for($i=1;$row=mysqli_fetch_array($sql);$i++){
			
			$rows[] = array(
						   $i,
						   $row['gid'],
						   $row['pwd'],
						   $row['repair'],
						   $row['memo'],
						   $row['type']
						   );	
		
			if($act == 'clear') {
					$date = date_create(date("Y-m-d H:i:s"));
					date_add($date, date_interval_create_from_date_string("-7 days"));
					$clear_time = date_format($date, "Y-m-d H:i:s");
					if($row['proxydatetime'] < $clear_time) {						
						mysqli_query($sub_site['proxy']," update a_gidpwrepair set proxy = '' ,status = '' , type = '' , updatetime = '{$nowdatetime}' , proxydatetime = '{$nowdatetime}' where gid = '{$row['gid']}' ");
					}
			}
		
						 
		}		
	}
	$data = array_merge(array($headers), $rows);
	
	$excel = new PHPExcel();
	foreach($widths as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setWidth($w);
	$excel->getActiveSheet()->fromArray($data,NULL,'A1');
	
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"Missing".$file_head_name."ID-".date("ymd", time()).".xls\"");
	header("Cache-Control: max-age=0");
	
	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
	$writer->save('php://output'); 	
}
?>
