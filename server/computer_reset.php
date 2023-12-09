<?php
include_once('./_common.php');
include_once('./sub_site.php');

if($is_guest) {
	$var['status'] = 'nopermission';
} else {
	if(isset($_POST['computer']) && $_POST['computer']) $computer = $_POST['computer'];
	if(isset($_POST['type']) && $_POST['type']) $type = $_POST['type'];
	if($type == 'zenpc') {//젠피씨컴퓨터
		$computer_type = "zenpc";
		$bm_sql = "";
	} else if ($type == 'proxy') {//프록시컴퓨터
		$computer_type = "proxy";
		$bm_sql = "";
	} else if ($type == 'mobile') {//모바일컴퓨터
		$computer_type = "proxy"; 
		$bm_sql = "_b";
	}
	//$computer_type = 'zenpc';
	if($computer_type && $computer){
		mysqli_query($sub_site[$computer_type]," UPDATE a_server{$bm_sql} set message = 'endview' where computer = '{$computer}' ");		
		$var['status'] = 'success';
	} else {
		$var['status'] = 'error';
	}
}
header('Content-type: application/json');
echo json_encode($var)?>
?>