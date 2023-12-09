<?php 
include_once('./_common.php');



$file_path = G5_PATH."/Youtube_search_df.csv";
//echo $file_url = G5_URL."/Youtube_search_df.csv";
//echo 

if($config['cf_1'] < G5_SERVER_TIME - $config['cf_2'] ){//2분후
	$time = G5_SERVER_TIME;
	$log_time = date('Y_m_d__H_i_s', G5_SERVER_TIME);
	if(file_exists($file_path)) {
		$readfile_t = fopen($file_path,'r');
		$lineNumber=0;
		while (($raw_string = fgets($readfile_t)) !== false) {
			if($lineNumber){
				$youtuberank_array  = str_getcsv($raw_string);
				//print_r($youtuberank_array);
				$rank = $lineNumber;    //필드1
				$name = addslashes($youtuberank_array[0]); ;    //필드2	
				$title = addslashes($youtuberank_array[1]);     //필드2
				$url_link = addslashes($youtuberank_array[2]);    //필드4
				$ysql = " insert into `a_rank` set ranking = '{$rank}' , name = '{$name}' , title = '{$title}', url_link = '{$url_link}' , gettime = '{$time}' ";				
				sql_query($ysql);
			}
			$lineNumber++;
		}
		fclose($readfile_t );	
		sql_query("update g5_config set cf_1 = '{$time}' ");
		$rename_path = G5_PATH."/data/log_rank/".$log_time.".csv";
		rename($file_path,$rename_path);
	}
}
//http://powersearch.dothome.co.kr/latestrank.php?key=dsfjlasjoi&type=pc&q=https://www.youtube.com/watch?v=wSEJbn1YThE
if(isset($_GET['cid']) && $_GET['cid']) $cid = strip_tags(clean_xss_attributes($_GET['cid']));

$apisql = "select * from {$g5['member_table']} where mb_5 = '{$cid}' and mb_3 = 'on' and mb_id != 'admin' order by mb_10 asc ";
for($i=0;$api_member=sql_fetch_array($apisql);$i++){
	$request[] = search_ranks($api_memeber);
}



function search_ranks($api_member){
	if(!$api_member['mb_9']) $api_member['mb_9'] = 20;
	if($api_member['mb_10'] < G5_SERVER_TIME - $api_member['mb_9'] ){//2분후
		$ytx = $api_member['mb_2'];
	}	
	if($api_member['mb_2']) {
		$video_id  = '';
		$yt_rx = '/^((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/+(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?$/';
		$has_match_youtube = preg_match($yt_rx, $ytx, $yt_matches);
		if($has_match_youtube) {
		$video_id = $yt_matches[5]; 
		} else $ytx = addslashes($ytx);
		$chk_pcgettime = sql_fetch("select gettime from a_rank where type = 'pc' order by gettime DESC limit 0,1");	
		$chk_pcongettime = sql_fetch("select gettime from a_rank where type = 'pcon' order by gettime DESC limit 0,1");	
		//echo $chk_pcgettime['gettime'];	
		$chk_mobilegettime = sql_fetch("select gettime from a_rank where type = 'mobile' order by gettime DESC limit 0,1");	
		//echo "-".$chk_pcgettime['gettime'];	
		//echo 
		$sql_head = "select * from a_rank ";
		$pctime = " where gettime = '{$chk_pcgettime['gettime']}' ";
		$pcontime = " where gettime = '{$chk_pcongettime['gettime']}' ";
		$mobiletime = " where gettime = '{$chk_mobilegettime['gettime']}' ";
		$and = " and ";
		if($video_id) $wheresql =  " ( url_link like '%{$video_id}%' or url_link like '%{$video_id}' or  url_link like '{$video_id}%' or  url_link like '{$video_id}' )  ";
		else $wheresql = " ( name like '%{$ytx}%' or  name like '%{$ytx}' or name like '{$ytx}%' or name = '{$ytx}' ) "; 
		$pctype = " and type = 'pc' "; 
		$mobiletype = " and type = 'mobile' ";
		$pconline_type  = " and type='pcon ' ";
		$mobileonline_type  = " online = '실시간' ";
		$sql_tail = " order by ranking ASC ";
	
				
		$pcsql = sql_query($sql_head.$pctime.$and.$wheresql.$pctype.$sql_tail);
		for($i=0;$pc_rank=sql_fetch_array($pcsql);$i++){
			$result['pc'][] = $pc_rank['ranking'];
			if($pc_rank['name']) $result['name'] = $pc_rank['name'];
		}
		if(!$result['pc']) $result['pc']= '';
		$pconlinesql = sql_query($sql_head.$pcontime.$and.$wheresql.$pconline_type.$sql_tail);
		for($i=0;$pc_online_rank=sql_fetch_array($pconlinesql);$i++){
			$result['pcon'][] = $pc_online_rank['ranking'];
			if($pc_online_rank['name']) $result['name'] = $pc_online_rank['name'];
		}		
		if(!$result['pcon']) $result['pcon']= '';
		$mobilesql = sql_query($sql_head.$mobiletime.$and.$wheresql.$mobiletype.$sql_tail);
		for($i=0;$mobile_rank = sql_fetch_array($mobilesql);$i++){
			$result['mobile'][] = $mobile_rank['ranking'];
			if($mobile_rank['name']) $result['name'] = $mobile_rank['name'];
		}
		if(!$result['mobile']) $result['mobile']= '';
	 
	
		$result['que'] = $api_member['mb_2'];
		//$result['cid'] = $api_member['mb_5'];
		//$result['key1'] = $api_member['mb_6'];
		$result['key2'] = $api_member['mb_7'];
		$result['room'] = $api_member['mb_nick'];
		
		if(!$result['pc'] && !$result['pcon'] && !$result['mobile']){
			$result['name'] = "순위 업데이트중..";
		} else {
			sql_query("update {$g5['member_table']} set mb_10 = '".time()."' where mb_id = '{$api_member['mb_id']}'");
		}
		if($ytx) {	
		$result['code']='ok';
		}
		else $result['code']='none';
		return $result;
	}
	else return false;
}

header('Content-type: application/json');
echo json_encode($request);
?>
