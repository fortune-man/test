<?php
//include_once('./_common.php');
error_reporting(E_ALL);
ini_set("display_errors",1);


#### 젠피씨, 프록시 서버 DB 연결
@$sub_site['zenpc'] = @mysqli_connect('61.109.34.245', 'zenpc', 'qetuo00!!zenpc', 'zenserver');
//if(!$sub_site['zenpc']) die("DB error ".mysqli_connect_error()) ;
@$db_ses['zenpc'] = mysqli_select_db( $sub_site['zenpc'], 'zenserver');
//if(!$db_ses['zenpc']) die("db slt " .mysqli_connect_error());



@$sub_site['proxy'] = @mysqli_connect('61.109.34.246', 'proxy', 'qetuo000!!proxy', 'powersearch');
if(!$sub_site['proxy']) die("DB error ".mysqli_connect_error()) ;
@$db_ses['proxy'] = mysqli_select_db( $sub_site['proxy'], 'powersearch');
if(!$db_ses['proxy']) die("db slt " .mysqli_connect_error());

$t =  mysqli_query($sub_site['proxy']," select * from a_server limit 0 , 1 ");
print_r($t);
?>