<?php
include_once('./_common.php');


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

if(isset($_POST['chkgid']) && $_POST['chkgid']) $chkgid =  trim(strip_tags(clean_xss_attributes($_POST['chkgid'])));

$var = array();
$var = sql_fetch("select * from  a_gidpwrepair where gid = '{$chkgid}' or  gid = '{$chkgid}@gmail.com' ");

if($var['proxy'] ) {
	$ttime = sql_fetch("select * from a_server where proxy = '{$var['proxy']}' ");
	if($ttime['proxydatetime']) $var['latest'] = get_date_diff($ttime['proxydatetime']);
	$var['condition'] = 'available';
} else if($var['status'] == 'clear' ) $var['condition'] = 'clear';
else if($var['gid'] && $var['status'] != 'clear' && !$var['proxy']) $var['condition'] = 'available';
else if(!$var) $var['condition'] = 'none';

header("Content-Type: application/json");
echo (json_encode($var));
?>