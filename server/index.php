<?php
include_once('./_common.php');

if($member['mb_id']=="7979"||$member['mb_id']=="1212") goto_url(G5_URL."/server/adclick_form.php");
//error_reporting(E_ALL);
//ini_set("display_errors",1);

function device_type($type = '') {
		switch($type) {
		case "" : $a = "sperms";break;
		case "zenpc" : $a = "zsperms";break;
		case "mobile" : $a = "msperms";break;
		default:$a= "sperms";break;
	}
	return $a;
}
function parking_random_url($parkingurl) {
	if(strpos($parkingurl,"\n") !==false){
		$park_urls = explode("\n",$parkingurl);
		$park_rand = array_rand($park_urls);
		$result =  trim($park_urls[$park_rand]);
		return $result;
	}
}

function random_project($proxy,$x=40,$type="",$opt=""){//검색q값,접속수, 오래된날짜, 의 비율 지정.
	//기존 프로젝트와 다른 프로젝트만을 제공해준다.
	$c = sql_fetch(" select q from a_server where proxy = '{$proxy}' ");	
	if($c) {
		$q = $c['q'];
	} 
	$zen_on = '';
	$zen_q_where = '';
	if($type=="zenpc") {
		$zen_head = array("00x","01x");
		$zen_proxy = str_replace($zen_head,"x",$proxy);
		
		//echo " select q from a_server where proxy like '%{$zen_proxy}' and q <> '{$q}' ";
		$d = sql_fetch(" select q from a_server where proxy like '%{$zen_proxy}' and q <> '{$q}' ");
		if($d) {
			$zen_q_where = " and a.q <> '".$d['q']."' ";
		}
		else {
			$zen_q_where = " ";
		}
		$zen_on = " and b.type = 'zenpc' " ;
	}
	
	$cnt_rand_datetime  = rand(0,100);
	$a = device_type($type) ;
	
	if($opt == 'ori') {
		if(!$q) {
			$ca = sql_fetch(" select q from a_serverset where status = 'healthy' ordery by rand() limit 0,1 ");	
			$q = $ca['q'];
		}
		$project_array = sql_fetch(" SELECT c.project_id, c.q , c.".$a." , c.cnt from (
														SELECT a.id, a.project_id , 
														a.".$a." 
														, COUNT(b.q) AS cnt, a.q , a.datetime  FROM `a_serverset` a 
														LEFT JOIN a_server b ON (b.q = a.q  and b.message = 'viewstart' ".$zen_on.") 
														WHERE a.status = 'healthy'
														
														and a.q = '{$q}' ".$zen_q_where."											
														GROUP BY a.q, a.id 
														ORDER BY cnt
													) c
													WHERE 
													c.".$a." >= c.cnt										
													limit 0, 1
													");	
		return $project_array;
	
	}
	
	if($cnt_rand_datetime < $x ) {//40% 이하를 카운트 순으로 정해주면
	
				
					$project_array = sql_fetch(" SELECT c.project_id, c.q , c.".$a." , c.cnt from (
												SELECT a.id, a.project_id , 
												a.".$a." 
												, COUNT(b.q) AS cnt, a.q , a.datetime  FROM `a_serverset` a 
												LEFT JOIN a_server b ON (b.q = a.q  and b.message = 'viewstart' ".$zen_on.") 
												WHERE a.status = 'healthy'
												
												and a.q <> '{$q}' ".$zen_q_where."											
												GROUP BY a.q, a.id 
												ORDER BY cnt
											) c
											WHERE 
											c.".$a." >= c.cnt										
											limit 0, 1
											");	
																							
		} else {//나머지 60% 조건
	
				$project_array = sql_fetch(" SELECT c.project_id, c.q , c.".$a." , c.cnt from (
												SELECT a.id, a.project_id , 
												a.".$a."
												 , COUNT(b.q) AS cnt, a.q , a.datetime  FROM `a_serverset` a 
												LEFT JOIN a_server b ON (b.q = a.q  and b.message = 'viewstart'	".$zen_on." ) 
												WHERE a.status = 'healthy' 
												
												and a.q <> '{$q}' ".$zen_q_where."
												GROUP BY a.q, a.id 
												ORDER BY cnt
											) c
											WHERE 
											c.".$a." >= c.cnt
											ORDER BY rand()
											limit 0, 1
											");	
		
		
											
		
	}
	
	if($project_array) //랜덤프로젝트가 존재한다면
			return $project_array;
			
}

$proxy = '';
$computer = '';
$profile = '';
$message = '';
$mode = '';
$type = '';

if(isset($_GET['proxy']) && $_GET['proxy']) $proxy =  trim(strip_tags(clean_xss_attributes($_GET['proxy'])));
/*
if(isset($_GET['project_id']) && $_GET['project_id']) $project_id = trim(strip_tags(clean_xss_attributes($_GET['project_id'])));
*/
if(isset($_GET['com']) && $_GET['com']) $computer = trim(strip_tags(clean_xss_attributes($_GET['com'])));
if(isset($_GET['usr']) && $_GET['usr']) $profile = trim(strip_tags(clean_xss_attributes($_GET['usr'])));
if(isset($_GET['msg']) && $_GET['msg']) $message = trim(strip_tags(clean_xss_attributes($_GET['msg'])));
if(isset($_GET['mode']) && $_GET['mode']) $mode =  trim(strip_tags(clean_xss_attributes($_GET['mode'])));
if(isset($_GET['type']) && $_GET['type']) $type =  trim(strip_tags(clean_xss_attributes($_GET['type'])));


if(strpos($proxy,"x") !==false)	$type = "zenpc";
else $type="";
//초기화 인경우
if($mode == 'clear') {
	if($proxy) sql_query("UPDATE a_server SET  q = '' where proxy = '".$proxy."' ");
	else sql_query("UPDATE a_server SET  q = '' ");	
	exit;
}

$var = array();

//랜덤프로젝트 할당
if($proxy && $proxy != "0.0.0.0") {
	if($mode != "view"){//시청중이 아니라면 새로 프로젝트 정보를 받아오고(새로 받을때는 기존 프로젝트가 아닌 것으로 받아오기 때문에)
		//여기서 프로젝트별 할당 순위를 정해준다
		$rand_project = random_project($proxy,80,$type);
	} 
	else if($mode == "view"){//시청중 튕겨나올 명령어부분
		$view_rand_change  = rand(0,100);
		if($view_rand_change < 2) {//시청중 3%확률로 종료하고 랜덤 아이디값 가진다.
			$rand_project = random_project($proxy,40,$type);
		} else {
			$rand_project = random_project($proxy,40,$type,'ori');
		}
	}
}

if($rand_project){
	$project_id = $rand_project['project_id'];
	$project_count = $rand_project['cnt'];
	$res = sql_fetch("select * from `a_serverset` where project_id = '{$project_id}' ");
	$var['status'] = $res['status'];//건강상태
	if($res['zscrapper']) {
		$zscrap_per = rand(0,$res['zscrapper']);
		if($zscrap_per>$res['zscrapper']-1) $var['zscrap'] = 'on';
	} else $var['zscrap'] ='';
	if($res['zlike']) {
		$zlike_per = rand(0,$res['zlike']);
		if($zlike_per>$res['zlike']-1) $var['zlike'] = 'on';
	} else $var['zlike'] ='';	
} else {
		$res = sql_fetch("select * from `a_serverset` where  status = 'healthy' order by rand() limit 0,1 ");
		$var['status'] = $res['healthy'];//건강상태
	
}
										


$var['target'] = 'https://youtu.be/'.$res['q'];//영상주소

#랜덤키워드
$keyword = explode(",",$res['keyword']);
if(count($keyword)<=1 || !$keywordper){
	$selected = array_rand($keyword);
	$var['keyword'] = trim($keyword[$selected ]);
}else {
	
	$keywordper = explode(",",$res['keywordper']);
	$keywordsum = array_sum($keywordper);
	$keywordrand = rand(0,$keywordsum);
	for($pi=0;$pi<count($keywordper);$pi++){
		$keywordrand  -= $keywordper[$pi];
		if($keywordrand < 1 ) $var['keyword'] = $keyword[$pi]; 
	}
	
}
if(!$var['keyword']) $var['keyword'] = trim($keyword['0']);

$var['percent'] = $res['percent'];//주소로확률
$var['sperms'] = $res['sperms'];//들어갈 갯수
$var['zsperms'] = $res['zsperms'];//들어갈 갯수 
$var['msperms'] = $res['msperms'];//들어갈 갯수 

$var['interval'] = $res['intervals'];//브라우저 프로세스  간격
$var['intertime'] = $res['intertime'];//min
$var['time'] = $res['time'];//시청후대기시간 
$var['fireout'] = $res['fireout'];//몇초시청할건지
$var['mode'] = $res['mode'];// 초기 설정  setting 원하면 .전체적용



$var['q'] = trim($res['q']);

if($res['linecnt']) {
	$tmp_linecnt = explode(",",$res['linecnt']);
	if($tmp_linecnt[0]) $var['linecnt1'] = $tmp_linecnt[0];
	else $var['linecnt1'] = 14;
	if($tmp_linecnt['1'])  $var['linecnt2'] = $tmp_linecnt[0]+$tmp_linecnt[1];
	else $var['linecnt2'] = 28;
	
}

$var['width'] = trim($res['width']);
$var['height'] = trim($res['height']);
$var['wposition'] = trim($res['wposition']);
$var['hposition'] = trim($res['hposition']);

$var['delaytime'] =  $res['delaytime'];//브라우저 열때마다 더해주는 시간
$var['browsercnt'] =  $res['browsercnt'];//브라우저 개수
$var['zbrowsercnt'] =  $res['zbrowsercnt'];//브라우저 개수
$var['mbrowsercnt'] =  $res['mbrowsercnt'];//브라우저 개수

$var['browserinterval'] =  $res['browserinterval'] + rand(1,120);//서버확인간격


//$var['datetime'] = $res['datetime'];

$cnt_tmp = sql_fetch(" select count(*) as cnt from `a_server` where q = '{$var['q']}' and message = 'viewstart' and type = '{$type}' ");
if($cnt_tmp['cnt']) $var['cnt'] = $cnt_tmp['cnt'];
else  $var['cnt'] = 0;



//zenpc만 reatime 모드 사용하게 한다.
//if($type == 'zenpc' && $res['mode'] == 'realtime' ) {}
//else $var['mode'] = 'basic';


//zenpc인 경우 실제 뷰어수를 zsperms로 검사한다..
if($type == "zenpc") $realsperms =  $var['zsperms'];
else  $realsperms = $var['sperms'];

//젠피씨 강제고정
$realsperms =  $var['zsperms'];


//최종 시청수 체크 부분 브레이크 설치
if($res['maxtime'] || $res['zmaxtime'] || $res['mmaxtime'] ) {
	$dt1 = mktime();
	$dt2 = strtotime($res['datetime']);
	$diff = $dt1 - $dt2;
	if($type=='zenpc') $breakzone = $res['zmaxtime'] ;
	else if($type=='mobile') $breakzone = $res['mmaxtime'] ;
	else $breakzone = $res['maxtime'] ;
		
	if($breakzone > $diff){
		$var['break'] = $diff ;	 
		$realsperms = ceil(sin($diff / $breakzone) * $realsperms);
		$var['realsperms'] = $realsperms;
	}
}


$var['oldstatus'] = $var['status'];
$datetime = date("Y-m-d H:i:s");
$var['realsperms'] = $realsperms;
if( $var['cnt'] <= $realsperms && $proxy != "0.0.0.0" && $var['status'] != 'parking') {//일반뷰어들 접속시는 //
	if(!$var['status'])  $var['status']= 'healthy';//기본 쓰레드 설정인겨우
	//일반뷰어 파이선에서 서버로 접속할때 업데이트 또는 신규 추가 
	$d = sql_fetch(" select * from a_server where proxy = '{$proxy}' ");
	if($d){
		 if($d['q'] == $var['q'] ) sql_query("UPDATE a_server SET  q = '{$var['q']}' , computer = '{$computer}' , message = 'viewstart' , profile='{$profile}' WHERE proxy = '{$proxy}'  ");
		 else sql_query("UPDATE a_server SET  q = '{$var['q']}' , computer = '{$computer}' , profile='{$profile}', message = 'servercheck' , type = '{$type}' , log_time = '{$datetime}' WHERE proxy = '{$proxy}'  ");
		 
	}
	else  sql_query("INSERT INTO a_server SET proxy = '{$proxy}' , q = '{$var['q']}'  , computer = '{$computer}' , profile='{$profile}' , type = '{$type}' , datetime = '{$datetime}' , log_time = '{$datetime}' , message = 'servercheck' ");
} else {
	//일반뷰어 파이선에서 서버로 접속할때 주차모드로 업데이트 또는 신규 추가 
	//$d = sql_fetch(" select * from a_server where proxy = '{$proxy}' ");
	//if($d) sql_query("UPDATE a_server SET  q = '{$var['q']}' , computer = '{$computer}' , profile='{$profile}', message = 'parking' , type = '{$type}' , log_time = '{$datetime}' WHERE proxy = '{$proxy}'  ");
	//else sql_query("INSERT INTO a_server SET proxy = '{$proxy}' , q = '{$var['q']}'  , computer = '{$computer}' , profile='{$profile}' , type = '{$type}' , datetime = '{$datetime}' , log_time = '{$datetime}' , message = 'parking' ");	
	$var['status'] = 'parking';//기본 쓰레드 설정인겨우
}

//if($var['status'] == 'spoil')  $var['status'] = 'parking';//기본 쓰레드 설정인겨우


$var['parkingurl'] =  parking_random_url($res['parkingurl']);
$var['parkingtime'] = $res['parkingtime'];

header('Content-type: application/json');
echo json_encode($var);