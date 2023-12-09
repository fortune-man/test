<?php

#### 젠피씨, 프록시 서버 DB 연결
@$sub_site['zenpc'] = @mysqli_connect('localhost', 'root', 'autoset', 'zenserver');
//if(!$sub_site['zenpc']) die("DB error ".mysqli_connect_error()) ;
@$db_ses['zenpc'] = mysqli_select_db( $sub_site['zenpc'], 'zenserver');
//if(!$db_ses['zenpc']) die("db slt " .mysqli_connect_error());



@$sub_site['proxy'] = @mysqli_connect('localhost', 'root', 'autoset', 'proxyserver');
//if(!$sub_site['proxy']) die("DB error ".mysqli_connect_error()) ;
@$db_ses['proxy'] = mysqli_select_db( $sub_site['proxy'], 'proxyserver');
//if(!$db_ses['proxy']) die("db slt " .mysqli_connect_error());
?>