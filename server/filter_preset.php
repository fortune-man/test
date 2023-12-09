<?php
include_once('./sub_site.php');
include_once('./_common.php');

if($is_guest) {
	$var['status'] = 'nopermission';
} else {
	if(isset($_POST['fid']) && $_POST['fid']) $fid = $_POST['fid'];
	if(isset($_POST['t']) && $_POST['t']) $t = $_POST['t'];
	if($t == "net") $check_message = "xxxxxxxxxxxxxxxxxxx";
	else $check_message = "off";
	if($fid=="all"){
		mysqli_query($sub_site['proxy'],"delete from a_server where  loginstatus = '{$check_message}' ");
		$var['status'] = "all";
	} else {
		$fid_array = explode("-",$fid);
		$computer = $fid_array[0];
		$profile = $fid_array[1];
		//$computer_type = 'zenpc';
		if($computer && $profile){
			mysqli_query($sub_site['proxy'],"delete from a_server where loginstatus ='{$check_message}' 
			and computer = '{$computer}' 
			and profile = '{$profile}'  " );		
			$var['status'] = 'success';
		} else {
			$var['status'] = 'error';
		}
	}
}
header('Content-type: application/json');
echo json_encode($var);
?>