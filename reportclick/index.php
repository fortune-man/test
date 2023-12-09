<?php
include_once('./_common.php');

set_time_limit(2);

$mode = $comp_name = $usr = $turl = $keyword ='';
if(isset($_GET['mode']) && $_GET['mode']) $mode = trim(strip_tags($_GET['mode']));
if(isset($_GET['usr']) && $_GET['usr']) $usr = trim(strip_tags($_GET['usr']));
if(isset($_GET['com']) && $_GET['com']) $com = trim(strip_tags($_GET['com']));
if(isset($_GET['turl']) && $_GET['turl']) $turl = trim(strip_tags($_GET['turl']));
if(isset($_GET['keyword']) && $_GET['keyword']) $keyword = trim(strip_tags($_GET['keyword']));
if(isset($_GET['title']) && $_GET['title']) $title = trim(strip_tags($_GET['title']));
if(!$mode || !$com  ) exit;

$var = array();

#로그기록

if($mode == 'report' && $turl && $com ){	//$ip 는 꼭 체크할 필요는 없다. 	
	#echo " select * from a_reportclick_zenpc where  turl = '{$turl}' , com = '{$com}'  ";
	$is_clicked = sql_fetch(" select * from a_reportclick_zenpc where  turl = '{$turl}' and com = '{$com}'  ");
	if($is_clicked) {
		$var['msg'] = 'exist';
		
		$var['reportmessage'] ='';
	}
	else {	
		$logcheck = sql_query(" insert into a_reportclick_zenpc SET	keyword = '{$keyword}' , turl = '{$turl}' , com = '{$com}' , usr = '{$usr}' , title = '{$title}' ");		  
		$var['msg'] ='new';

	}
	$serverset = sql_fetch("select * from a_serverset where project_id = 'xxx' ");
	$var['reportmessage'] = $serverset['reportmessage'];
	$var['reportviewtime'] = $serverset['reportviewtime'];
	$var['reportaftertime'] = $serverset['reportaftertime'];
	$var['reportafterview'] = $serverset['reportafterview'];
	header('Content-type: application/json');
	echo json_encode($var);	
	exit;
}

?>