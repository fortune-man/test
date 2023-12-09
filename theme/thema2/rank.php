<?php
include_once('./_common.php');

//error_reporting(E_ALL);
//ini_set("display_errors", 1);
// 상품이 많을 경우 대비 설정변경
//set_time_limit ( 0 );
//ini_set('memory_limit', '50M');
if($config['cf_7']>1 || 1==true) {
	$del_time =  G5_SERVER_TIME -($config['cf_7']*60);
	$latest_pcgettime = sql_fetch("select gettime from a_rank where type = 'pc' order by gettime DESC limit 0,1");	
	$latest_pcongettime = sql_fetch("select gettime from a_rank where type = 'pcon' order by gettime DESC limit 0,1");	
	$latest_mobilegettime = sql_fetch("select gettime from a_rank where type = 'mobile' order by gettime DESC limit 0,1");
	$distinctpc_sql = sql_fetch("select count(*) as db_count from (select DISTINCT gettime as cnt from a_rank where type='pc' and gettime !='{$latest_pcgettime['gettime']}' order by gettime DESC ) a ");
	if($distinctpc_sql['db_count']>1) sql_query("delete from a_rank where type='pc' and gettime < '{$del_time}' and gettime !='{$latest_pcgettime['gettime']}' "); 
	$distinctpcon_sql = sql_fetch("select count(*) as db_count from (select DISTINCT gettime as cnt from a_rank where type='pcon' and gettime !='{$latest_pcongettime['gettime']}' order by gettime DESC ) a ");
	if($distinctpcon_sql['db_count']>1) sql_query("delete a_rank from a_rank where type='pcon' and  gettime < '{$del_time}' and gettime !='{$latest_pcgettime['gettime']}' ");
	$distinctmobile_sql = sql_fetch("select count(*) as db_count from (select DISTINCT gettime as cnt from a_rank where type='mobile' and gettime !='{$latest_mobilegettime['gettime']}' order by gettime DESC ) a ");
	if($distinctmobile_sql['db_count']>1) sql_query("delete a_rank from a_rank where type='mobile' and  gettime < '{$del_time}' and gettime !='{$latest_mobilegettime['gettime']}' ");
}






$file_path = G5_PATH."/pc.csv";
if($config['cf_1'] < G5_SERVER_TIME - $config['cf_2'] ){//2분후
	$time =time();
	$pclog_time = date('Y_m_d__H_i_s', $time);
	if(file_exists($file_path)) {
		$readfile_t = fopen($file_path,'r');
		$lineNumber=0;
		while (($raw_string = fgets($readfile_t)) !== false) {
			if($lineNumber){
				$youtuberank_array  = str_getcsv($raw_string);
				//print_r($youtuberank_array);
				$rank = $lineNumber;    //필드
				$name = addslashes($youtuberank_array[0]); ;    //필드1
				$title = addslashes($youtuberank_array[1]);     //필드2
				$url_link = addslashes($youtuberank_array[2]);    //필드3
				$online = addslashes($youtuberank_array[3]);    //필드4
				$ysql = " insert into `a_rank` set ranking = '{$rank}' , name = '{$name}' , title = '{$title}', url_link = '{$url_link}', online = '{$online}' , type = 'pc' , gettime = '{$time}' ";				
				sql_query($ysql);
			}
			$lineNumber++;
		}
		fclose($readfile_t );	
		sql_query("update g5_config set cf_1 = '{$time}' ");
		$rename_path = G5_PATH."/data/log_rank/pc".$pclog_time.".csv";
		rename($file_path,$rename_path);
		

	}
}


if($config['cf_5'] < G5_SERVER_TIME - $config['cf_6'] ){//2분후
	$ofile_path = G5_PATH."/pcon.csv";
	$time = time();
	$pconlog_time = date('Y_m_d__H_i_s', $time);
	if(file_exists($ofile_path)) {
		$readfile_t = fopen($ofile_path,'r');
		$lineNumber=0;
		while (($raw_string = fgets($readfile_t)) !== false) {
			if($lineNumber){
				$youtuberank_array  = str_getcsv($raw_string);
				//print_r($youtuberank_array);
				$rank = $lineNumber;    //필드
				$name = addslashes($youtuberank_array[0]); ;    //필드1
				$title = addslashes($youtuberank_array[1]);     //필드2
				$url_link = addslashes($youtuberank_array[2]);    //필드3
				$online = addslashes($youtuberank_array[3]);    //필드4
				$ysql = " insert into `a_rank` set ranking = '{$rank}' , name = '{$name}' , title = '{$title}', url_link = '{$url_link}', online = '{$online}' , type = 'pcon' , gettime = '{$time}' ";				
				sql_query($ysql);
			}
			$lineNumber++;
		}
		fclose($readfile_t );	
		sql_query("update g5_config set cf_5 = '{$time}' ");
		$pconrename_path = G5_PATH."/data/log_rank/pcon".$pconlog_time.".csv";
		rename($ofile_path,$pconrename_path);
		

	}
}


if($config['cf_3'] < G5_SERVER_TIME - $config['cf_4'] ){//2분후
	$mfile_path = G5_PATH."/mobile.csv";
	$mobiletime = time();	
	$mobilelog_time = date('Y_m_d__H_i_s',$mobiletime);
	if(file_exists($mfile_path)) {
		$mfile_path;
		$youtuberank_array = array();
		$readfile_t = fopen($mfile_path,'r');
		$lineNumber=0;
		while (($raw_string = fgets($readfile_t)) !== false) {
			if($lineNumber){
				$youtuberank_array  = str_getcsv($raw_string);
				//print_r($youtuberank_array);
				$rank = $lineNumber;    //필드
				$name = addslashes($youtuberank_array[0]); ;    //필드1
				$title = addslashes($youtuberank_array[1]);     //필드2
				$url_link = addslashes($youtuberank_array[2]);    //필드3
				$online = addslashes($youtuberank_array[3]);    //필드4
				$ysql = " insert into `a_rank` set ranking = '{$rank}' , name = '{$name}' , title = '{$title}', url_link = '{$url_link}', online = '{$online}' , type = 'mobile' , gettime = '{$mobiletime}' ";				
				sql_query($ysql);
			}
			$lineNumber++;
		}
		fclose($readfile_t );	
		sql_query("update g5_config set cf_3 = '{$time}' ");
		$mrename_path = G5_PATH."/data/log_rank/mobile".$mobilelog_time.".csv";
		rename($mfile_path,$mrename_path);		
	}

}

?>
