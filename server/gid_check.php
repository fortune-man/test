<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.sub.php');
include_once('./sub_site.php');


function get_time() { $t=explode(' ',microtime()); return (float)$t[0]+(float)$t[1]; }
$start = get_time();

$zenpc_all = array();

$zenpc_sql = mysqli_query($sub_site['zenpc'],"select gid from a_gidpwrepair ");
for($i=0;$zenpc_row = mysqli_fetch_array($zenpc_sql);$i++){
	$zenpc_all[] = $zenpc_row['gid'];
}
//print_r($zenpc_all);

$proxy_all = array();

$proxy_sql = mysqli_query($sub_site['proxy'],"select gid from a_gidpwrepair ");
for($i=0;$proxy_row = mysqli_fetch_array($proxy_sql);$i++){
	$proxy_all[] = $proxy_row['gid'];
}
//print_r($proxy_all);
$gid_all = array_merge($zenpc_all,$proxy_all);
$inter_all = array_intersect($zenpc_all,$proxy_all);
echo "전체 계정수 :".  $gid_cnt = @count($gid_all);
echo "<br>";
echo "프록시 계정수 : ".@count($proxy_all)." 젠피씨 계정수 : ".@count($zenpc_all);
echo "중복 계정수 : ".@count($inter_all);
echo "중복 계정목록 :";
@print_r($inter_all);

$end = get_time();
$time = $end - $start;
echo number_format($time,6) . " 초 걸림";


$inter_all = array_intersect($zenpc_all,$proxy_all);
print_r($inter_all);
?>