<?php
include_once('./_common.php');

//error_reporting(E_ALL);
//ini_set("display_erross",1);

// Date & Time
function get_date_diff($date){

	$diff = time() - strtotime($date);

	$s = 60; //1분 = 60초
	$h = $s * 60; //1시간 = 60분
	$d = $h * 24; //1일 = 24시간
	$y = $d * 30; //1달 = 30일 기준
	$a = $y * 12; //1년

	if ($diff < $s) {
		$result = $diff . '초전';
	} elseif ($h > $diff && $diff >= $s) {
		$result = round($diff/$s) . '분전';
	} elseif ($d > $diff && $diff >= $h) {
		$result = round($diff/$h) . '시간전';
	} elseif ($y > $diff && $diff >= $d) {
		$result = round($diff/$d) . '일전';
	} elseif ($a > $diff && $diff >= $y) {
		$result = round($diff/$y) . '달전';
	} else {
		$result = round($diff/$a) . '년전';
	}
	return $result;
}

$set = '';
$proxy = '';
//http://3.34.42.218/server/zenpc.php?ip=00x124.5.185.244&com=PC-yRVtpHrl&usr=00
if(isset($_GET['ip']) && $_GET['ip']) $proxy =  trim(strip_tags(clean_xss_attributes($_GET['ip'])));
if(isset($_GET['com']) && $_GET['com']) $computer = trim(strip_tags(clean_xss_attributes($_GET['com'])));
if(isset($_GET['usr']) && $_GET['usr']) $profile = trim(strip_tags(clean_xss_attributes($_GET['usr'])));
if(isset($_GET['set']) && $_GET['set']) $set = trim(strip_tags(clean_xss_attributes($_GET['set'])));
if(isset($_GET['msg']) && $_GET['msg']) $message = trim(strip_tags(clean_xss_attributes($_GET['msg'])));

if(isset($_GET['mode']) && $_GET['mode']) $mode =  trim(strip_tags(clean_xss_attributes($_GET['mode'])));

if(!$proxy) $real_proxy = $_SERVER['REMOTE_ADDR'];
else {
	$real_proxy = str_replace("00x","",$proxy);
	$real_proxy = str_replace("01x","",$real_proxy );
}


$proxydatetime = date("Y-m-d H:i:s");
$gid = '';
$pwd = '';
$repair = '';
$memo = '';

$old_res = sql_fetch(" select * from a_gidpwrepair where proxy = '{$real_proxy}' " );



if($message) $sql_message =  " message = '{$message}' ,		";
else $sql_message = "";

if($computer && $profile) $comprofile = " , computer = '{$computer}' ,	profile = '{$profile}'";
else $comprofile = "";

$log_proxy = sql_fetch(" select id from a_server where proxy = '{$proxy}' ");
if($log_proxy) sql_query(" UPDATE a_server SET {$sql_message} log_time = '{$datetime}' {$loginstatus_set} {$comprofile}  WHERE proxy = '{$proxy}' and type = 'zenpc' ");
else sql_query(" INSERT INTO a_server set {$sql_message}
											 proxy = '{$proxy}' ,
											type = 'zenpc' ,										
											datetime = '{$datetime}' ,
											log_time = '{$datetime}'
											{$loginstatus_set} {$comprofile}  ");
$proxy_info = sql_fetch( "select * from a_server where proxy = '{$proxy}' ");
											
if($old_res['gid'] && $old_res['pwd'] ) {// && $old_res['repair']
	$gid = $old_res['gid'];
	$pwd = $old_res['pwd'];
	$repair = $old_res['repair'];
//	$memo_t = sql_fetch( " select * from a_gidpwrepair where proxy = '{$real_proxy}' ");
	$memo = $old_res['memo'];
	$pdatetime =  $old_res['proxydatetime'];
	$udatetime =  $old_res['updatetime'];
	$title_h1 = "기존데이터";
	$datetime = date("Y-m-d H:i:s");
		if($old_res['gid'] != $proxy_info['gid']) sql_query(" update a_server set gid = '{$gid}'  , pwd = '{$pwd}' , repair = '{$rid}'  where proxy = '{$proxy}' ");	

	
} 
/*
else {//구글아이디비번 새로발급
	$new_res = sql_fetch(" select * from a_gidpwrepair where proxy = '' and exid <> '{$real_proxy}'  and status = '' order by rand() limit 0,1 ");
	if($new_res['gid'] && $new_res['pwd']) {// && $new_res['repair'] 
		$gid = $new_res['gid'];
		$pwd = $new_res['pwd'];
		$repair = $new_res['repair'];
		$memo = $new_res['memo'];
		$pdatetime =  $new_res['proxydatetime'];
		$udatetime =  $new_res['updatetime'];
		$title_h1 = "아이디재발급 ";
		
		sql_query(" update a_gidpwrepair set proxy = '{$real_proxy}' , proxydatetime = '{$proxydatetime}' , status='act' , type = 'zenpc' where id = '{$new_res['id']}'  ");
		sql_query(" update a_server set gid = '{$gid}' , pwd = '{$pwd}' , repair = '{$repair}' where proxy = '00x{$real_proxy}' ");
		sql_query(" update a_server set gid = '{$gid}' , pwd = '{$pwd}' , repair = '{$repair}' where proxy = '01x{$real_proxy}' ");		
	}
	else $title_h1 = '재발급 실패';
}
*/

//data 정리

$gid = trim($gid);
$pwd = trim($pwd);
$repair = trim($repair);


if($proxy) $proxy_info = sql_fetch(" select * from a_server where proxy = '{$proxy}' ");
else $proxy_info = sql_fetch(" select * from a_server where proxy like '%x{$real_proxy}' ");

$scrapstatus = $proxy_info['scrapstatus'];
$loginstatus = $proxy_info['loginstatus'];



if($set =="json"){
	$var['gid'] = $gid;
	$var['pwd'] = $pwd;
	$var['rid'] = $repair;
	$gtime_t = sql_fetch ( " select zgtime from a_serverset where project_id = 'xxx' ");
	$var['gtime'] = $gtime_t['zgtime'];
	//$var['scrapstatus'] = $scrapstatus;//on 이면 파이썬에서 실행
header('Content-type: application/json');
echo json_encode($var);
exit;
} else {?>
	<style>
    <?php if($gid) {?>
    #proxy {height:500px;background-color: #c8dafa;}
    <?php } else {?>
    #proxy {height:500px;background-color: #ff7777; color: white;}
    <?php } ?>
    </style>
    <div id="proxy" class="container">
        <div class="">
            <h4><?php echo $real_proxy;?> <?php echo $computer;?> <?php if($proxy) echo $profile;?></h4>
            <h4>구글id : <?php echo $gid;?>  비번 : <?php echo $pwd;?> 복구메일 : <?php echo $rid;?> </h4>
                <a class="btn btn-xs btn-danger" target="_blank" href="./zenpc_html.php">수동제어주소</a>                      
        </div>  
    </div>
<?php }?>