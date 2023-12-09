<?php
include_once('./_common.php');

set_time_limit(2);

$mode = $comp_name = $usr = $turl = $gglkeyword ='';
if(isset($_GET['mode']) && $_GET['mode']) $mode = trim(strip_tags($_GET['mode']));
if(isset($_GET['usr']) && $_GET['usr']) $usr = trim(strip_tags($_GET['usr']));
if(isset($_GET['com']) && $_GET['com']) $com = trim(strip_tags($_GET['com']));
if(isset($_GET['turl']) && $_GET['turl']) $turl = trim(strip_tags($_GET['turl']));
if(isset($_GET['gglkeyword']) && $_GET['gglkeyword']) $gglkeyword = trim(strip_tags($_GET['gglkeyword']));
if(!$mode || !$com || !$usr ) exit;

#DESCTOP-LH5DHQ4
#ip 58.245.155.49
#http://61.109.34.245/blue/?mode=set&com=PCtest&ip=2.2.2.2&viewers=10
#http://61.109.34.245/blue/?mode=projectt&com=DESCTOP-LH5DHQ4&ip=58.245.155.49
//error_reporting( E_ALL );
//ini_set( "display_errors", 1 );

## 광고클릭 로그기록 
#mysqli_query($sub_site ['proxy'],"delete from a_adclick_log_b where datetime < DATE_FORMAT( CURDATE() + INTERVAL -1 MONTH , '%Y/%m/%d' )  ");	 
#mysqli_query($sub_site ['proxy'],"delete from a_adclick_log_b where datetime < DATE_FORMAT( CURDATE() + INTERVAL -1 MONTH , '%Y/%m/%d' )  ");	 
$var = array();

##---------구글 광고 클릭할당
if($mode == 'getadclick' && $com && $usr ){	
	$var = array();
	$var['status'] = "off";
	$var['gglkeyword'] =  "";
	$var['mysite'] = "";


	$adclickon = sql_fetch(" select * from a_adclick_set where adid = '1' and now() > DATE_ADD(addatetime , interval terms SECOND)  ");			
	if($adclickon['status']=="on") {
		$var['status']= "on";
		#구글키워드는 랜덤
		$tt_gglkeyword =  explode(',',$adclickon['gglkeyword']);
		$tt_rand = array_rand($tt_gglkeyword);
		$var['gglkeyword'] =  $tt_gglkeyword[$tt_rand];
		$var['mysite'] = explode(',',$adclickon['mysite']);
		sql_query(" update a_adclick_set set addatetime = now() where adid = '1' ");		
	}
	header('Content-type: application/json');
	echo json_encode($var);		
	exit;	
}


#로그기록
if($mode == 'log' && $turl && $com && $usr){	//$ip 는 꼭 체크할 필요는 없다. 	
	$var = array();
	$exit_log = sql_fetch(" select * from a_adclick_log where turl = '{$turl}' and com = '{$com}' and usr = '{$usr}' ");
	if ($exit_log['usr']) $var['status'] = 'off';
	else $var['status'] = 'on';
	$logcheck = sql_query(" insert into a_adclick_log_b SET gglkeyword = '{$gglkeyword}' , turl = '{$turl}' , com = '{$com}' , usr = '{$usr}' ");		  
	header('Content-type: application/json');
	echo json_encode($var);	

	exit;
}

