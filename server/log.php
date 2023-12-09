<?php
include_once('./_common.php');

if(isset($_GET['ip']) && $_GET['ip']) $proxy =  trim(strip_tags(clean_xss_attributes($_GET['ip'])));
if(isset($_GET['error']) && $_GET['error']) $message = trim(strip_tags(clean_xss_attributes($_GET['error'])));
if(isset($_GET['com']) && $_GET['com']) $computer = trim(strip_tags(clean_xss_attributes($_GET['com'])));
if(isset($_GET['usr']) && $_GET['usr']) $profile = trim(strip_tags(clean_xss_attributes($_GET['usr'])));
if(isset($_GET['type']) && $_GET['type']) $type =  trim(strip_tags(clean_xss_attributes($_GET['type'])));
if(isset($_GET['loginstatus']) && $_GET['loginstatus']) $loginstatus = trim(strip_tags(clean_xss_attributes($_GET['loginstatus'])));


//if($message == "viewstart" || $message == "endview" || $message == "break") {}else exit;
$proxy = trim($proxy);
$message;
$datetime = date("Y-m-d H:i:s");


if($loginstatus =="on")   {
	$loginstatus_set = " , loginstatus = 'on' , gidtime = '{$datetime}' ";//로그인 확인 이후는 시청으로 넘어가기 때문에
	$message = 'servercheck';
}
else if($loginstatus =="off")   {
	$loginstatus_set = " , loginstatus = 'off' , gidtime = '{$datetime}' ";//로그인 안되었을때는 대기 모드로 감
	$message = 'servercheck';
}

if($computer && $profile) $comprofile = " , computer = '{$computer}' ,	profile = '{$profile}'";
else $comprofile = "";

//파이썬 오류수정
//http://13.124.191.129/server/log.php?type=&ip=1.1.1.1&com=ccc&usr=04&error=netstat
if($message == "nofindtaget") $message = "nofindtarget";
$log_proxy = sql_fetch(" select id from a_server where computer = '{$computer}' and profile = '{$profile}' ");
if($log_proxy) sql_query(" UPDATE a_server SET   proxy = '{$proxy}' , message = '{$message}' , log_time = '{$datetime}' {$loginstatus_set} 
	WHERE computer = '{$computer}' and profile = '{$profile}' and type = '{$type}' ");
else {

		sql_query(" INSERT INTO a_server set proxy = '{$proxy}' ,
											type = '{$type}' ,
											message = '{$message}' ,
											datetime = '{$datetime}' ,
											log_time = '{$datetime}'
											{$loginstatus_set} {$comprofile} ");
}

?>