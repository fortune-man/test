<?php 
include_once('./_common.php');
//if(isset($_GET['cid']) && $_GET['cid']) $cid = strip_tags(clean_xss_attributes($_GET['cid']));
if(isset($_GET['q']) && $_GET['q']) $q = strip_tags(clean_xss_attributes($_GET['q']));
if(isset($_GET['on']) && $_GET['on']) $on = strip_tags(clean_xss_attributes($_GET['on']));
if(isset($_GET['off']) && $_GET['off']) $off = strip_tags(clean_xss_attributes($_GET['off']));


//$q = "@https://youtu.be/jgKYE2SXTF8@7팀@파워볼,파워볼제우스,파워볼 jgKYE2SXTF8";

/*
닉네임 = 팀명
시간 채널아이디
mb_2 검색주소
mb_3 on / off
mb_5 채널아이디
mb_7 검색어

*/
$request = array();
$datetime = date("Y-m-d H:i:s");
if($q) {	
	$que = find_mb_nick($q,1);
	$q_mb_nick = find_mb_nick($q,2);
	$q_mb_key = find_mb_nick($q,3);
	if($q_mb_key) $set_add = " , keyword = '".$q_mb_key."' ";
	else $set_add  = '';
	$old_q = sql_fetch("select * from a_serverset  where project_name = '{$q_mb_nick}' ");//".$set_add."
	if($old_q['q'] == $que) {
		sql_query("update a_serverset set  q = '{$que}' where project_name = '{$q_mb_nick}' ");//  {$set_add } 
		$request['msg'] = 'keywordupdate';
	}
	else {		
	echo "update a_serverset set datetime = '{$datetime}', q = '{$que}'   where project_name = '{$q_mb_nick}' ";
		sql_query("update a_serverset set datetime = '{$datetime}', q = '{$que}'   where project_name = '{$q_mb_nick}' ");// {$set_add }
		/*
		$diff = time() - strtotime($old_q['datetime']);
		if($diff<$old_q['setdatetime']){
			$request['msg'] = get_date_diff($diff).'후 변경 가능합니다';
			}
		else {
			sql_query("update a_serverset set datetime = '{$datetime}', q = '{$que}'  , {$set_add } where project_name = '{$q_mb_nick}' ");//".$set_add."
			*/
		$request['msg'] = 'q_change';
		//}
	}
}
else if($on) {
	$q_mb_nick = find_mb_nick($on,2);
	$q_mb_key = find_mb_nick($on,3);
	if($q_mb_key) $set_add = " , keyword = '".$q_mb_key."' ";		
	sql_query("update a_serverset set datetime = '{$datetime}', status = 'healthy'  where project_name = '{$q_mb_nick}' ");//".$set_add."
	$request['msg'] = 'on';
}
else if($off) {
	$q_mb_nick = find_mb_nick($off,2);
	$q_mb_key = find_mb_nick($off,3);
	if($q_mb_key) $set_add = " , keyword = '".$q_mb_key."' ";	
	sql_query("update a_serverset set datetime = '{$datetime}', status = 'spoil'  where project_nick = '{$q_mb_nick}'   ");//".$set_add."
	$request['msg'] = 'off';
}
if(!$request['msg']) $request['msg'] = 'none';

function find_mb_nick($q,$b){
	$tmp_q = explode("@",$q);	
	if($b=='1') {
		$tmp_q['1'] = str_replace("https://youtu.be/","",$tmp_q['1']);
		$tmp_q['1'] = str_replace("?feature=share","",$tmp_q['1']);
		$tmp_q['1'] = str_replace("https://www.youtube.com/watch?v=","",$tmp_q['1']);
		$tmp_q['1'] = str_replace("https://youtube.com/live/","",$tmp_q['1']);
		$tmp_q['1'] = str_replace("https://wwww.youtube.com/live/","",$tmp_q['1']);
		$tmp_q['1'] = str_replace("https://youtube.com/live/","",$tmp_q['1']);
		return $tmp_q['1'];
	}
	else if($b=='2') return $tmp_q['2'];
	else if($b=='3') return $tmp_q['3'];
	else return false;	
}

// Date & Time
function get_date_diff($diff){
	//$date
	//$diff = time() - strtotime($date);

	$s = 60; //1분 = 60초
	$h = $s * 60; //1시간 = 60분
	$d = $h * 24; //1일 = 24시간
	$y = $d * 30; //1달 = 30일 기준
	$a = $y * 12; //1년

	if ($diff < $s) {
		$result = $diff . '초후';
	} elseif ($h > $diff && $diff >= $s) {
		$result = round($diff/$s) . '분';
	} elseif ($d > $diff && $diff >= $h) {
		$result = round($diff/$h) . '시간';
	} elseif ($y > $diff && $diff >= $d) {
		$result = round($diff/$d) . '일';
	} elseif ($a > $diff && $diff >= $y) {
		$result = round($diff/$y) . '달';
	} else {
		$result = round($diff/$a) . '년';
	}

	return $result;
}


header('Content-type: application/json');
echo json_encode($request,true);
?>