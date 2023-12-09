<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.sub.php');
include_once('./sub_site.php');

### 활동이 3분 지난 채팅마스터/슬레이브는 디비삭제
@mysqli_query($sub_site['proxy'],"delete from a_chat_b where datetime + interval 180 second < now()");

//error_reporting(E_ALL);
//ini_set("display_errors",1);
if($member['mb_id']=="7979"||$member['mb_id']=="1212") goto_url(G5_URL."/server/adclick_form.php");
error_reporting(0);
#모바일 로그 지우기
function filter_q($q,$opt=false){
    $tmp_q = str_replace("https://youtu.be/","",$q);
    $tmp_q = str_replace("?feature=share","",$tmp_q);
    $tmp_q = str_replace("https://www.youtube.com/watch?v=","",$tmp_q);
    $tmp_q = str_replace("https://www.youtube.com/live/","",$tmp_q);
    $tmp_q = str_replace("https://youtube.com/live/","",$tmp_q);
    if ($opt){
        $tmp_q = $opt.$tmp_q;
    }
    return $tmp_q;
}
function get_project($project_id,$key=''){
	$res = sql_fetch(" select * from a_serverset where project_id = '{$project_id}'" );
	if($key) return $res[$key];
	else return $res;
}

if( $_GET['project_id'] && !$_POST['project_id']){
	if(isset($_GET['project_id']) && $_GET['project_id'])  $project_id = trim(strip_tags(clean_xss_attributes($_GET['project_id'])));
	if(isset($_GET['spermtype']) && $_GET['spermtype'])  $spermtype = trim(strip_tags(clean_xss_attributes($_GET['spermtype'])));;
	$project = sql_fetch(" select * from a_serverset where project_id = '{$project_id}' ");
} else {
	if(isset($_POST['pcusr']) && $_POST['pcusr'])  $pcusr = trim(strip_tags(clean_xss_attributes( $_POST['pcusr'])));
	if(isset($_POST['project_name']) && $_POST['project_name']) $project_name =  trim(strip_tags(clean_xss_attributes($_POST['project_name'])));
	if(isset($_POST['cid']) && $_POST['cid']) $cid =  trim(strip_tags(clean_xss_attributes($_POST['cid'])));
	if(isset($_POST['project_id']) && $_POST['project_id']) $project_id =  trim(strip_tags(clean_xss_attributes($_POST['project_id'])));
	if(isset($_POST['q']) && $_POST['q']) {
        $q =  trim(strip_tags(clean_xss_attributes($_POST['q'])));
        $q = filter_q($q,'');       
    }
	if(isset($_POST['qname']) && $_POST['qname']) $qname =  trim(strip_tags(clean_xss_attributes($_POST['qname'])));
	
	if(isset($_POST['keyword']) && $_POST['keyword']) $keyword =  trim(strip_tags(clean_xss_attributes($_POST['keyword'])));
	if(isset($_POST['pkeyword']) && $_POST['pkeyword']) $pkeyword =  trim(strip_tags(clean_xss_attributes($_POST['pkeyword'])));
	if(isset($_POST['mkeyword']) && $_POST['mkeyword']) $mkeyword =  trim(strip_tags(clean_xss_attributes($_POST['mkeyword'])));
	if(isset($_POST['zkeyword']) && $_POST['zkeyword']) $zkeyword =  trim(strip_tags(clean_xss_attributes($_POST['zkeyword'])));


	if(isset($_POST['autokey']) && $_POST['autokey']) $autokey =  trim(strip_tags(clean_xss_attributes($_POST['autokey'])));

	
	if(isset($_POST['keywordper']) && $_POST['keywordper']) $keywordper =  trim(strip_tags(clean_xss_attributes($_POST['keywordper'])));
	if(isset($_POST['pkeywordper']) && $_POST['pkeywordper']) $pkeywordper =  trim(strip_tags(clean_xss_attributes($_POST['pkeywordper'])));
	if(isset($_POST['mkeywordper']) && $_POST['mkeywordper']) $mkeywordper =  trim(strip_tags(clean_xss_attributes($_POST['mkeywordper'])));
	if(isset($_POST['zkeywordper']) && $_POST['zkeywordper']) $zkeywordper =  trim(strip_tags(clean_xss_attributes($_POST['zkeywordper'])));	
		
	if(isset($_POST['status']) && $_POST['status']) $status =  trim(strip_tags(clean_xss_attributes($_POST['status'])));
	if(isset($_POST['pstatus']) && $_POST['pstatus']) $pstatus =  trim(strip_tags(clean_xss_attributes($_POST['pstatus'])));
	if(isset($_POST['mstatus']) && $_POST['mstatus']) $mstatus =  trim(strip_tags(clean_xss_attributes($_POST['mstatus'])));
	if(isset($_POST['zstatus']) && $_POST['zstatus']) $zstatus =  trim(strip_tags(clean_xss_attributes($_POST['zstatus'])));
	
	if(isset($_POST['percent']) && $_POST['percent']) $percent =  trim(strip_tags(clean_xss_attributes($_POST['percent'])));
	if(isset($_POST['ppercent']) && $_POST['ppercent']) $ppercent =  trim(strip_tags(clean_xss_attributes($_POST['ppercent'])));
	if(isset($_POST['mpercent']) && $_POST['mpercent']) $mpercent =  trim(strip_tags(clean_xss_attributes($_POST['mpercent'])));		
	if(isset($_POST['zpercent']) && $_POST['zpercent']) $zpercent =  trim(strip_tags(clean_xss_attributes($_POST['zpercent'])));
	
	if(isset($_POST['sperms']) && $_POST['sperms']) $sperms =  trim(strip_tags(clean_xss_attributes($_POST['sperms'])));
	if(isset($_POST['psperms']) && $_POST['psperms']) $psperms =  trim(strip_tags(clean_xss_attributes($_POST['psperms'])));	
	if(isset($_POST['msperms']) && $_POST['msperms']) $msperms =  trim(strip_tags(clean_xss_attributes($_POST['msperms'])));	
	if(isset($_POST['zsperms']) && $_POST['zsperms']) $zsperms =  trim(strip_tags(clean_xss_attributes($_POST['zsperms'])));
		
	if(isset($_POST['testsperms']) && $_POST['testsperms']) $testsperms =  trim(strip_tags(clean_xss_attributes($_POST['testsperms'])));
		
	if(isset($_POST['intervals']) && $_POST['intervals']) $intervals =  trim(strip_tags(clean_xss_attributes($_POST['intervals'])));
	if(isset($_POST['intertime']) && $_POST['intertime']) $intertime =  trim(strip_tags(clean_xss_attributes($_POST['intertime'])));
	if(isset($_POST['time']) && $_POST['time']) $time =  trim(strip_tags(clean_xss_attributes($_POST['time'])));
	if(isset($_POST['requests']) && $_POST['requests']) $requests =  trim(strip_tags(clean_xss_attributes($_POST['requests'])));
	
	if(isset($_POST['fireout']) && $_POST['fireout']) $fireout =  trim(strip_tags(clean_xss_attributes($_POST['fireout'])));
	if(isset($_POST['pfireout']) && $_POST['pfireout']) $pfireout =  trim(strip_tags(clean_xss_attributes($_POST['pfireout'])));
	if(isset($_POST['mfireout']) && $_POST['mfireout']) $mfireout =  trim(strip_tags(clean_xss_attributes($_POST['mfireout'])));		
	if(isset($_POST['zfireout']) && $_POST['zfireout']) $zfireout =  trim(strip_tags(clean_xss_attributes($_POST['zfireout'])));	
	
	if(isset($_POST['mode']) && $_POST['mode']) $mode =  trim(strip_tags(clean_xss_attributes($_POST['mode'])));
	if(isset($_POST['pmode']) && $_POST['pmode']) $pmode =  trim(strip_tags(clean_xss_attributes($_POST['pmode'])));
	if(isset($_POST['zmode']) && $_POST['zmode']) $zmode =  trim(strip_tags(clean_xss_attributes($_POST['zmode'])));
	if(isset($_POST['mmode']) && $_POST['mmode']) $mmode =  trim(strip_tags(clean_xss_attributes($_POST['mmode'])));
				
	if(isset($_POST['delaytime']) && $_POST['delaytime']) $delaytime =  trim(strip_tags(clean_xss_attributes($_POST['delaytime'])));

	if(isset($_POST['maxtime']) && $_POST['maxtime']) $maxtime =  trim(strip_tags(clean_xss_attributes($_POST['maxtime'])));
	if(isset($_POST['pmaxtime']) && $_POST['pmaxtime']) $pmaxtime =  trim(strip_tags(clean_xss_attributes($_POST['pmaxtime'])));
	if(isset($_POST['mmaxtime']) && $_POST['mmaxtime']) $mmaxtime =  trim(strip_tags(clean_xss_attributes($_POST['mmaxtime'])));			
	if(isset($_POST['zmaxtime']) && $_POST['zmaxtime']) $zmaxtime =  trim(strip_tags(clean_xss_attributes($_POST['zmaxtime'])));

	
	if(isset($_POST['scrapper']) && $_POST['scrapper']) $scrapper =  trim(strip_tags(clean_xss_attributes($_POST['scrapper'])));
	if(isset($_POST['pscrapper']) && $_POST['pscrapper']) $pscrapper =  trim(strip_tags(clean_xss_attributes($_POST['pscrapper'])));	
	if(isset($_POST['zscrapper']) && $_POST['zscrapper']) $zscrapper =  trim(strip_tags(clean_xss_attributes($_POST['zscrapper'])));
	if(isset($_POST['mscrapper']) && $_POST['mscrapper']) $mscrapper =  trim(strip_tags(clean_xss_attributes($_POST['mscrapper'])));		

	if(isset($_POST['plike']) && $_POST['plike']) $plike =  trim(strip_tags(clean_xss_attributes($_POST['plike'])));
	if(isset($_POST['zlike']) && $_POST['zlike']) $zlike =  trim(strip_tags(clean_xss_attributes($_POST['zlike'])));
	if(isset($_POST['mlike']) && $_POST['mlike']) $mlike =  trim(strip_tags(clean_xss_attributes($_POST['mlike'])));		
	
		
	if(isset($_POST['browsercnt']) && $_POST['browsercnt']) $browsercnt =  trim(strip_tags(clean_xss_attributes($_POST['browsercnt'])));
	if(isset($_POST['zbrowsercnt']) && $_POST['zbrowsercnt']) $zbrowsercnt =  trim(strip_tags(clean_xss_attributes($_POST['zbrowsercnt'])));	
	if(isset($_POST['mbrowsercnt']) && $_POST['mbrowsercnt']) $mbrowsercnt =  trim(strip_tags(clean_xss_attributes($_POST['mbrowsercnt'])));			
	if(isset($_POST['browserinterval']) && $_POST['browserinterval']) $browserinterval =  trim(strip_tags(clean_xss_attributes($_POST['browserinterval'])));
	if(isset($_POST['zbrowserinterval']) && $_POST['zbrowserinterval']) $zbrowserinterval =  trim(strip_tags(clean_xss_attributes($_POST['zbrowserinterval'])));
	

	if(isset($_POST['zgtime']) && $_POST['zgtime']) $zgtime =  trim(strip_tags(clean_xss_attributes($_POST['zgtime'])));

	if(isset($_POST['parkingurl']) && $_POST['parkingurl']) $parkingurl =  trim(strip_tags(clean_xss_attributes($_POST['parkingurl'])));	
	if(isset($_POST['parkingurl1']) && $_POST['parkingurl1']) $parkingurl1 =  trim(strip_tags(clean_xss_attributes($_POST['parkingurl1'])));	
	
	if(isset($_POST['parkingtime']) && $_POST['parkingtime']) $parkingtime =  trim(strip_tags(clean_xss_attributes($_POST['parkingtime'])));
	if(isset($_POST['zparkingtime']) && $_POST['Zparkingtime']) $zparkingtime =  trim(strip_tags(clean_xss_attributes($_POST['zparkingtime'])));
			
	if(isset($_POST['width']) && $_POST['width']) $width =  trim(strip_tags(clean_xss_attributes($_POST['width'])));
	if(isset($_POST['height']) && $_POST['height']) $height =  trim(strip_tags(clean_xss_attributes($_POST['height'])));
	if(isset($_POST['wposition']) && $_POST['wposition']) $wposition =  trim(strip_tags(clean_xss_attributes($_POST['wposition'])));
	if(isset($_POST['hposition']) && $_POST['hposition']) $hposition =  trim(strip_tags(clean_xss_attributes($_POST['hposition'])));
	if(isset($_POST['linecnt']) && $_POST['linecnt']) $linecnt =  trim(strip_tags(clean_xss_attributes($_POST['linecnt'])));

	if(isset($_POST['setdatetime']) && $_POST['setdatetime']) $setdatetime =  trim(strip_tags(clean_xss_attributes($_POST['setdatetime'])));	

	if(isset($_POST['viewpercent']) && $_POST['viewpercent']) $viewpercent =  trim(strip_tags(clean_xss_attributes($_POST['viewpercent'])));
	if(isset($_POST['creative']) && $_POST['creative']) $creative =  trim(strip_tags(clean_xss_attributes($_POST['creative'])));

  	if(isset($_POST['pbooster']) && $_POST['pbooster']) $pbooster =  trim(strip_tags(clean_xss_attributes($_POST['pbooster'])));
  	if(isset($_POST['mbooster']) && $_POST['mbooster']) $mbooster =  trim(strip_tags(clean_xss_attributes($_POST['mbooster'])));
  	if(isset($_POST['zbooster']) && $_POST['zbooster']) $zbooster =  trim(strip_tags(clean_xss_attributes($_POST['zbooster'])));

    if(isset($_POST['pbreakin']) && $_POST['pbreakin']) $pbreakin =  trim(strip_tags(clean_xss_attributes($_POST['pbreakin'])));
    if(isset($_POST['mbreakin']) && $_POST['mbreakin']) $mbreakin =  trim(strip_tags(clean_xss_attributes($_POST['mbreakin'])));
    if(isset($_POST['pbreakout']) && $_POST['pbreakout']) $pbreakout =  trim(strip_tags(clean_xss_attributes($_POST['pbreakout']))); 
    if(isset($_POST['mbreakout']) && $_POST['mbreakout']) $mbreakout =  trim(strip_tags(clean_xss_attributes($_POST['mbreakout'])));        


    if(isset($_POST['msgonoff']) && $_POST['msgonoff']) $msgonoff =  trim(strip_tags(clean_xss_attributes($_POST['msgonoff'])));
    if(isset($_POST['mchatnick']) && $_POST['mchatnick']) $mchatnick =  trim(strip_tags(clean_xss_attributes($_POST['mchatnick'])));
    if(isset($_POST['mchatmaster']) && $_POST['mchatmaster']) $mchatmaster =  trim(strip_tags(clean_xss_attributes($_POST['mchatmaster'])));
    if(isset($_POST['mchatslave']) && $_POST['mchatslave']) $mchatslave =  trim(strip_tags(clean_xss_attributes($_POST['mchatslave'])));        
    if(isset($_POST['msgset']) && $_POST['msgset']) $msgset =  trim(strip_tags(clean_xss_attributes($_POST['msgset'])));
    if(isset($_POST['msgsend']) && $_POST['msgsend']) $msgsend =  trim(strip_tags(clean_xss_attributes($_POST['msgsend'])));

	if(isset($_POST['pcontrole']) && $_POST['pcontrole']) $pcontrole =  trim(strip_tags(clean_xss_attributes($_POST['pcontrole'])));	
	if(isset($_POST['mcontrole']) && $_POST['mcontrole']) $mcontrole =  trim(strip_tags(clean_xss_attributes($_POST['mcontrole'])));			
    if(isset($_POST['mchannelkeyword']) && $_POST['mchannelkeyword']) $mchannelkeyword =  trim(strip_tags(clean_xss_attributes($_POST['mchannelkeyword'])));

    if(isset($_POST['weepath']) && $_POST['weepath']) $weepath =  trim($_POST['weepath']);

    if(isset($_POST['keyqname']) && $_POST['keyqname']) $keyqname =  trim($_POST['keyqname']);

    if(isset($_POST['chromeversion']) && $_POST['chromeversion']) $chromeversion =  trim($_POST['chromeversion']);
    
    
    
//if(!$zfireout) $zfireout = $zfireout;

	if($pcusr =="mod"&& $project_id && $project_name && $status){
		
		$datetime = date("Y-m-d H:i:s");
		
		$sql_project = "update a_serverset set 
						project_name = '{$project_name}',
						cid = '{$cid}',
						q = '{$q}',
						qname = '{$qname}',
						keyword = '{$keyword}',
						keywordper = '{$keywordper}',
						autokey = '{$autokey}',
						status = '{$status}',
						intertime = '{$intertime}',
						time = '{$time}',
						parkingurl1 ='{$parkingurl1}',	
																	
						

						
						
						";
		####
        # q값이 변경되는 경우에는 
		$sql_datetime = sql_fetch(" select q ,project_name from a_serverset where project_id = '{$project_id}' ");
        #q값 회원정보값 업데이트 
        $mb_q = filter_q($q,"https://youtu.be/");  
        
        $sql_mb_q = " update g5_member set mb_2 = '{$mb_q}' where mb_nick = '{$sql_datetime['project_name']}'";
        sql_query( $sql_mb_q);//랭킹 메인서서버 회원 mb_2값변경	
        mysqli_query($sub_site ['zenpc'],$sql_mb_q );	//젠피씨 회원 mb_2값변경	
        mysqli_query($sub_site ['proxy'],$sql_mb_q );	//프록시 회원 mb_2값변경

		if($q != $sql_datetime['q'] ) {
            ##q값 없데이트 부분
			$sql_project .=	" datetime = '{$datetime}', ";
  	
            
		}
        

		
		$main_sql_project = "	
						
						pkeyword = '{$pkeyword}',
						mkeyword = '{$mkeyword}',
						zkeyword = '{$zkeyword}',
						
						pkeywordper = '{$pkeywordper}',						
						mkeywordper = '{$mkeywordper}',						
						zkeywordper = '{$zkeywordper}',
								

						
						ppercent = '{$ppercent}',	
						mpercent = '{$mpercent}',
						zpercent = '{$zpercent}',	
						
						fireout = '{$fireout}',
						pfireout = '{$pfireout}',
						mfireout = '{$mfireout}',												
						zfireout = '{$zfireout}',	

						mode = '{$mode}',
						zmode = '{$zmode}',
						pmode = '{$pmode}',
						mmode = '{$mmode}',									
						
						sperms = '{$sperms}',
						psperms = '{$psperms}',						
						zsperms = '{$zsperms}',
						testsperms = '{$testsperms}',
						msperms = '{$msperms}',
						
						scrapper = '{$scrapper}',
						pscrapper = '{$pscrapper}',
						mscrapper = '{$mscrapper}',												
						zscrapper = '{$zscrapper}',

						plike = '{$plike}',	
						mlike = '{$mlike}',																			
						zlike = '{$zlike}',			
									

						maxtime = '{$maxtime}',
						pmaxtime = '{$pmaxtime}',						
						zmaxtime = '{$zmaxtime}',
						mmaxtime = '{$mmaxtime}',

                        booster = '{$booster}',
                        pbooster = '{$ppbooster}',
                        mbooster = '{$mbooster}',
                        zbooster = '{$zbooster}',

                        pbreakin = '{$pbreakin}',
                        mbreakin = '{$mbreakin}',
                        pbreakout = '{$pbreakout}',
                        mbreakout = '{$mbreakout}',

                        msgonoff = '{$msgonoff}',
                        mchatnick =  '{$mchatnick}',
                        mchatmaster = '{$mchatmaster}',
                        mchatslave = '{$mchatslave}',
                        msgset = '{$msgset}',
                        msgsend = '{$msgsend}',                        
						
                        pcontrole = '{$pcontrole}',
						mcontrole = '{$mcontrole}',
						mchannelkeyword = '{$mchannelkeyword}',

                        keyqname = '{$keyqname}'
                        
                        
						where project_id = 	'{$project_id}'
						";
						
						
		$proxy_sql_project = "
						pmode = '{$pmode}',
						mmode = '{$mmode}',
		
						pkeyword = '{$pkeyword}',
						mkeyword = '{$mkeyword}',

						
						pkeywordper = '{$pkeywordper}',						
						mkeywordper = '{$mkeywordper}',						

												
						ppercent = '{$ppercent}',	
						mpercent = '{$mpercent}',	
													
						pfireout = '{$pfireout}',
						zfireout = '{$zfireout}',
						mfireout = '{$mfireout}',	
															
						psperms = '{$psperms}',
						zsperms = '{$zsperms}',						
						msperms = '{$msperms}',

						scrapper = '{$scrapper}',												
						pscrapper = '{$pscrapper}',
						mscrapper = '{$mscrapper}',
																		
						plike = '{$plike}',						
						mlike = '{$mlike}'	,

						maxtime = '{$maxtime}',
						pmaxtime = '{$pmaxtime}',						
						zmaxtime = '{$zmaxtime}',
						mmaxtime = '{$mmaxtime}',
						
						parkingurl1 ='{$parkingurl1}',	
						
						pbooster = '{$pbooster}',
						mbooster = '{$mbooster}',

                        pbreakin = '{$pbreakin}',
                        mbreakin = '{$mbreakin}',
                        pbreakout = '{$pbreakout}',
                        mbreakout = '{$mbreakout}',

                        msgonoff = '{$msgonoff}',
                        mchatnick =  '{$mchatnick}',
                        mchatmaster = '{$mchatmaster}',
                        mchatslave = '{$mchatslave}',                        
                        msgset = '{$msgset}',
                        msgsend = '{$msgsend}',

						pcontrole = '{$pcontrole}',
						mcontrole = '{$mcontrole}',
						mchannelkeyword = '{$mchannelkeyword}',

                        keyqname = '{$keyqname}'
                        
						
						where project_id = 	'{$project_id}'
						";
										
		$zenpc_sql_project = "
						mode = '{$zmode}',
						booster = '{$zbooster}',
						parkingurl1 ='{$parkingurl1}',	
						percent = '{$zpercent}',	
						fireout = '{$zfireout}',						
						sperms = '{$zsperms}',
						zsperms = '{$zsperms}',
						msperms = '{$zsperms}',
						zscrapper = '{$zscrapper}',
						zlike = '{$zlike}',
						testsperms = '{$testsperms}',
						scrapper = '{$zscrapper}',
						zscrapper = '{$zscrapper}',
						mscrapper = '{$zscrapper}',
						maxtime = '{$zmaxtime}',
						zmaxtime = '{$zmaxtime}',
						mmaxtime = '{$zmaxtime}' ,

                        keyqname = '{$keyqname}'

						where project_id = 	'{$project_id}'


						";														
	//	echo $sql_project.$main_sql_project;		exit;
						
	sql_query($sql_project.$main_sql_project);//랭킹 메인서버 업데이트
	

	mysqli_query($sub_site ['zenpc'],$sql_project.$zenpc_sql_project);	//젠피씨프로젝트업데이트
	mysqli_query($sub_site ['proxy'],$sql_project.$proxy_sql_project);	//프록시프로젝트업데이트
							
	unset($_POST['project_id']);
	unset($_POST['pcusr']);
	unset($pcusr);
	unset($datetime);
	}
	
    if($project_id) goto_url("/server/project_form.php?project_id=".$project_id );
}

#########모바일로그지우기 ####
###
//mysqli_query($sub_site ['proxy'],"delete from a_error_log_b where 1");
//mysqli_query($sub_site ['proxy'],"delete from a_error_log_b where datetime < DATE_FORMAT( CURDATE() + INTERVAL -1 MONTH , '%Y/%m/%d' )");	
mysqli_query($sub_site ['proxy'],"delete from a_error_log_b where datetime < DATE_FORMAT( CURDATE() + INTERVAL -7 DAY , '%Y/%m/%d' )  ");	 
mysqli_query($sub_site ['proxy'],"delete from a_chat_b where datetime < DATE_FORMAT( CURDATE() + INTERVAL -3 DAY , '%Y/%m/%d' )  ");
mysqli_query($sub_site ['proxy'],"delete from a_likescrap_b where datetime < DATE_FORMAT( CURDATE() + INTERVAL -3 DAY , '%Y/%m/%d' )  ");

if(!$project_id &&  !$spermtype && $browsercnt)   { 
	//서버공통 설정
	$sql_set_common = " update a_serverset set
						linecnt = '{$linecnt}',						
						requests = '{$requests}',	
						intervals = '{$intervals}',											
						width = '{$width}',
						height = '{$height}',
						wposition = '{$wposition}',
						hposition = '{$hposition}',
						browsercnt = '{$browsercnt}',
						zbrowsercnt = '{$zbrowsercnt}',
						mbrowsercnt = '{$mbrowsercnt}',	
						zgtime = '{$zgtime}',
						parkingurl ='{$parkingurl}',						
                        viewpercent = '{$viewpercent}',
                        creative = '{$creative}',
						setdatetime = '{$setdatetime}',
                        delaytime = '{$delaytime}',	
                        weekpath = '{$weekpath}', 
                        chromeversion = '{$chromeversion}'	
                        		
						";
                        //
	sql_query($sql_set_common." , browserinterval ='{$browserinterval}', zbrowserinterval ='{$zbrowserinterval}', parkingtime ='{$parkingtime}', zparkingtime ='{$zparkingtime}' ");	//랭킹 메인서버 공통설정 업데이트
	mysqli_query($sub_site['zenpc'],$sql_set_common." , browserinterval ='{$zbrowserinterval}', parkingtime ='{$zparkingtime}'");	//랭킹 메인서버 공통설정 업데이트
	
	mysqli_query($sub_site['proxy'],$sql_set_common." , browserinterval ='{$browserinterval}', parkingtime ='{$parkingtime}'");	//랭킹 메인서버 공통설정 업데이트

}
						
if(isset($_GET['clear']) && $_GET['clear'])  $clear =  trim(strip_tags(clean_xss_attributes($_GET['clear'])));

if($clear == "day"){//7일전으로 청소
	
	$date = date_create(date("Y-m-d H:i:s"));
	date_add($date, date_interval_create_from_date_string("-4 days"));
	$clear_time = date_format($date, "Y-m-d H:i:s");
	
	//$time = date("Y-m-d H:i:s");$clear_time = date("Y-m-d H:i:s", strtotime("-720 minutes", strtotime($time)));
	
	$clear_sql = " select proxy FROM a_server where log_time < '{$clear_time}' ";
	$csql = mysqli_query($sub_site['zenpc'],$clear_sql);
	
	for($tt=0;$crow=mysqli_fetch_array($csql);$tt++){
		$datetime = date("Y-m-d H:i:s");
		mysqli_query($sub_site['zenpc']," UPDATE a_gidpwrepair SET proxy = '' , exid = '{$proxy}', proxydatetime = '{$datetime}' , status ='clear' , gidstats = '' ,type = '' where proxy = '{$crow['proxy']}' ");
		
	}
	mysqli_query($sub_site['zenpc']," DELETE FROM a_server where log_time < '{$clear_time}' ");
	
	
	$pclear_sql = mysqli_query($sub_site ['proxy'],$clear_sql);//프록시 뷰청소
	for($ptt=0;$pcrow=mysqli_fetch_array($pclear_sql);$ptt++){
		$datetime = date("Y-m-d H:i:s");
		mysqli_query($sub_site ['proxy']," UPDATE a_gidpwrepair SET proxy = '' , exid = '{$proxy}', proxydatetime = '{$datetime}' , status = 'clear' , gidstats = '' ,type = '' where proxy = '{$pcrow['proxy']}' ");
		
	}	
	mysqli_query($sub_site ['proxy']," DELETE FROM a_server where log_time < '{$clear_time}' ");


	$mclear_sql = mysqli_query($sub_site ['proxy']," select proxy FROM a_server_b where log_time < '{$clear_time}' ");//모바일 뷰청소
	for($mtt=0;$mcrow=mysqli_fetch_array($mclear_sql);$mtt++){
		//$datetime = date("Y-m-d H:i:s");
		//mysqli_query($sub_site ['proxy']," UPDATE a_gidpwrepair SET proxy = '' , exid = '{$proxy}', proxydatetime = '{$datetime}' , status = 'clear' , gidstats = '' ,type = '' where proxy = '{$pcrow['proxy']}' ");
		
	}	
	mysqli_query($sub_site ['proxy']," DELETE FROM a_server_b where log_time < '{$clear_time}' ");    
	
	alert(" 젠피씨는  $tt 건이 , 프록시는 $ptt 건이 , 모바일은 $mtt 건이 처리되었습니다.");// , 프록시는 $ptt 건

	goto_url("/server/project_form.php?project_id=".$project_id );
}


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


?>

<style>

.log-computer {position:relative;padding: 20px 5px 10px 5px;border: 2px solid #f90000;background: #f5fdf0;margin-bottom:20px;}

.btn-escaping {background-color:#4c8de3;border-color:#2800ff;}
.log-pb80 {padding-bottom:80px;}
.viewstart {color:blue; border: 2px solid blue;} .viewstart span {background:blue;}
.servercheck {color:#0093ab; border: 2px solid #0093ab;} .servercheck span {background:#0093ab;}
.newsperm{color:#7d00ee; border: 2px solid #7d00ee;} .newsperm span {background:#7d00ee;}
.critical {color:red;border: 2px solid red;} .critical span {background:red;}
.nofindtarget {color:#C60;border: 2px solid #C60;} .nofindtarget span {background:#C60;}
.failfindkeyword {color:#9e33ff;border: 2px solid #9e33ff;} .failfindkeyword span {background:#9e33ff;}
.iderror {color:#9e33ff;border: 2px solid #9e33ff;} .iderror span {background:#9e33ff;}
.neterror {color:#9e33ff;border: 2px solid #9e33ff;} .neterror span {background:#9e33ff;}
.notinteract{color:#F63;border: 2px solid #F63;} .notinteract span {background:#F63;}
.break {color:#003;border: 2px solid #003;} .break span {background:#003;}
.endview {color:#003;border: 2px solid #003;} .endview span {background:#003;}
.parking {color:#4caf50;border: 2px solid #4caf50;} .parking span {background:#4caf50;}
.seterror {color:#c700ff;border: 2px solid #c700ff;} .seterror span {background:#c700ff;}
.etc {color:#df9932;border: 2px solid #df9932;} .etc span {background:#df9932;}
.nogie {color:#df9932;border: 2px solid #df9932;} .nogid span {background:#df9932;}
.mia {color:#f0ad4e;border: 2px solid #f0ad4e;} .mia span {background:#f0ad4e;}
.zenpc-viewers .rankinfo {border-radius:10px;}
.rankinfo {position:relative;padding: 2px 5px;font-size:10px;display: inline-block;font-weight: bold;}
.rankinfo-text {display: none;position: absolute;min-width: 200px;top:27px;left:5px;border: 1px solid;border-radius: 5px;padding: 5px;font-size: 0.8em;color: white;z-index:3;}
.rankinfo-text a {color:white;}
.rankinfo:hover {background-color:#E4E4E4;}
.rankinfo:hover .rankinfo-text {display: inline-block;}
.log-computer .computer_reset {position:absolute;top:2px;left:2px;}
.log-computer .computer_reset  .viewerreset {color:white;font-weight:bold;border: 2px solid #990808;
    background: #d74b4b;}   
.log-computer .computer_reset  .viewerlog {color:white;font-weight:bold; border: 2px solid #36d99d;
    background: #07682a;}


.common-wrap , #mia-computer , #current-computer , #escaping-computer,  #input-excel {display:none;margin:10px;}
 .btn-allescaping {display:none;margin:5px 0px;}
.project_wrap {float:left;position:relative;}
.project_wrap .channel_url {position:absolute;font-size: 15px; top: 0px;right: 0px;}
.project_rank {position:absolute;}
#current-computer {border:1px solid #900;}
#mia-computer {border:1px solid #F90;}
.zenpc {position:absolute;top:0px;left:0px;width:20px;height:5px;text-align:center;}
.loginoff {position:absolute;top:0px;right:0px;width:20px;height:5px;text-align:center;}

.uilog {padding:0px 3px;color:white;border-radius:5px;}


.project-tab {margin:3px 2px;}
.viewers {padding-top:20px;}
.viewers.border-right {border-right:2px solid #0080FF;}
.gidpw-info  {font-size:0.7em;padding: 10px;border: 1px solid;}
.idmemo {width: 100%;height: 2.2em;padding: 0.1em 1em;}


.project_monitor {text-align:left;}
#current-computer .viewers .btn-danger a {color:#fff;}
.neonText {
  color: #fff;
  text-shadow: 0 0 7px #fff, 0 0 10px #fff, 0 0 21px #fff, 0 0 42px #0fa,
    0 0 82px #0fa, 0 0 92px #0fa, 0 0 102px #0fa, 0 0 151px #0fa;
}

.goto_top {position:fixed;width:80px;bottom:100px;right:50px;}


.proxy-viewers {}
.zenpc-viewers {display:none;}

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.4.0/clipboard.js"></script>
<script>
      $(document).ready( function() {
        $('#common-set').click( function() {
          $('.common-wrap').toggle();
        } );
        $('#mia-com').click( function() {
          $('#mia-computer').toggle();
        } );
        $('#current-com').click( function() {
          $('#current-computer').toggle();
        } );
        $('#escaping-com').click( function() {
          $('#escaping-computer').toggle();
        } );    
        $('#escaping-toggle').click( function() {
          $('.btn-allescaping').toggle();
        } );              
        $('#vpn_list').click( function() {
          $('#vpn_error').toggle();
        } );  
        $('#filter_list').click( function() {
          $('#filter_error').toggle();
        } );
        $('#pfilter_list').click( function() {
          $('#pfilter_error').toggle();
        } );           
        $('#net_list').click( function() {
          $('#net_error').toggle();
        } );               
        $('#gid-excel').click( function() {
          $('#input-excel').toggle();
        } );
        	
        
        get_today_news('on');
      } );
</script>
<?php
	if($project_id) $log_q = get_project($project_id,'q');
	$log_sql_head = " select * from a_server";
	if($spermtype) $log_sql_where = " where message = '".$spermtype."' ";	
	else $log_sql_where = " where message <> '' ";
	$los_sql_q_where = '';
	if($log_q) $los_sql_q_where = " and q = '".$log_q."' ";
	$log_sql_tail = " order by log_time desc ";
	$log_sql = mysqli_query($sub_site['zenpc'],$log_sql_head.$log_sql_where.$los_sql_q_where.$log_sql_tail);
	
	$log_sql_proxy = mysqli_query($sub_site['proxy'],$log_sql_head.$log_sql_where.$los_sql_q_where.$log_sql_tail);//프록시 뷰어 쿼리

	$log_sql_mobile = mysqli_query($sub_site['proxy'], $log_sql_head."_b ".$log_sql_where.$los_sql_q_where.$log_sql_tail);//모바일 뷰어 쿼리		
	//echo $log_sql_head."_b ".$log_sql_where.$los_sql_q_where.$log_sql_tail;
   
	if($project_id && $log_q) {
		$sperm_log_q_total = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select  count(*)  as cnt from a_server where q = '{$log_q}' "));
		$msgtype['total'] = $sperm_log_q_total['cnt'];
		$sperm_msg_sql = mysqli_query($sub_site['zenpc']," select message , count(message) as cnt from a_server where q = '{$log_q}' group by message ");
	} else if (!$project_id) {
		$sperm_q_total = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," SELECT SUM(c.cnt) AS live from (SELECT a.id, a.project_id ,a.zsperms , COUNT(b.q) AS cnt, a.q , a.datetime   FROM `a_serverset` a 
											LEFT JOIN a_server b ON (b.q = a.q  ) 
											WHERE a.status = 'healthy'											
											and a.q <> '' 
											and b.message <> ''
											GROUP BY a.q, a.id 
											ORDER BY cnt
                                                  ) c "));
		$msgtype['total'] = $sperm_q_total['live'];		
		$sperm_msg_sql = mysqli_query($sub_site['zenpc'],"select message , count(message) as cnt from a_server a 
								left join a_serverset b ON (a.q=b.q)
								where b.status = 'healthy'
								and b.q <> ''
								group by a.message
								");
	}
	if(isset($sperm_msg_sql) && $sperm_msg_sql){
		for($i=0;$msg_type=mysqli_fetch_array($sperm_msg_sql);$i++){
			$msgtype[$msg_type['message']] =  $msg_type['cnt'];
		}
	}
	
?>
<?php
###애즈클릭
$adrow = sql_fetch(" select * from a_adclick_set where adid = '1' ");

$adtoday = date("Y-m-d");
$adtoday_cnt = sql_fetch(" select count(*) as cnt from a_adclick_log_b where datetime like '{$adtoday}%'  "); //and gglkeyword = '{$row['gglkeyword']}'
$adtotal_cnt = sql_fetch(" select count(*) as cnt from a_adclick_log_b where 1 ");// gglkeyword = '{$row['gglkeyword']}'
if ($adrow['gglkeyword'])  $adgkar = explode(",",$adrow['gglkeyword']);

for($i=0;$i<count($adgkar);$i++){
    $adkcnt[$i]['gglkeyword'] = $adgkar[$i];
    $adkcnt_tmp = sql_fetch("select count(*) as cnt from a_adclick_log_b where gglkeyword = '{$adgkar[$i]}' and  datetime like '{$adtoday}%' ");    
    $adkcnt[$i]['cnt'] = $adkcnt_tmp['cnt'];
}

//$adlog_sql = sql_query(" select turl , count(*) as cnt from a_adclick_log_b where 1 group by gglekeyword, turl order by cnt desc limit 0, 20"); //image.png
?>

<div class="">
<div class="section text-center">
	<h2><a href="./project_form.php" class="btn btn-lg btn-primary">오토튜브 메인서버</a> 
    <?php
        $vpns_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_server_b where vpn_status = '1'  " ));
        #$nets_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(DISTINCT CONCAT(computer,'-',profile)) as cnt from a_error_log_b where message = 'neterror'  " ));
        #$ids_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(DISTINCT CONCAT(computer,'-',profile)) as cnt from a_error_log_b where message = 'iderror'  " ));
        $nets_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_error_log_b where message = 'neterror'  " ));
        $ids_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_error_log_b where message = 'iderror'  " ));
        $pids_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_server where loginstatus = 'off'  " ));

        ?>
        <span id="vpn_list" class="btn " style="color:white;border:2px;background:red;">VPN 에러(<?php echo $vpns_cnt['cnt'];?>)</span>
        <span id="net_list" class="btn " style="color:white;border:2px;background:green;">NET 에러(<?php echo $nets_cnt['cnt'];?>)</span>
        <span id="filter_list" class="btn " style="color:white;border:2px;background:skyblue;">M계정 에러(<?php echo $ids_cnt['cnt'];?>)</span>
        <span id="pfilter_list" class="btn " style="color:white;border:2px;background:hotpink;">P계정 에러(<?php echo $pids_cnt['cnt'];?>)</span> 
        <a href="./adclick_form.php" class="btn btn-md btn-primary">광고클릭페이지 
            <font class="badge badge-secondary">ADS <?php echo $adrow['status'] ;?></font>
            <br>
            오늘 : <?php echo $adtoday_cnt['cnt'];?> 누적 : <?php echo $adtotal_cnt['cnt'];?>
            <br>
            <?php for($i=0;$i<count($adgkar);$i++){
                echo $adkcnt[$i]['gglkeyword'];            
                echo " ( ".$adkcnt[$i]['cnt'].") " ;
            }?>            
            
        </a>
    </h2>

    



<div id="vpn_error" class="vpn_error_lists" style="text-align:left;position:absolute;z-index:1;background:#e7e7e7;border-radius  :15px;top:70px;left:calc( 50% - 200px);padding:10px 20px;display:none;">  
<div class="vpn_error" style="padding:10px;background:white;border-radius  :15px;border:1px solid #000;">
<h5>유튜브 vpn 에러목록</h5>
<?php
    $vpn_sql = mysqli_query($sub_site['proxy'],"select * from a_server_b where vpn_status = '1' order by computer asc , profile asc " );
    $co = '';
    for($i=0;$vpn_error=mysqli_fetch_array($vpn_sql);$i++){
        if($co != $vpn_error['computer'] && $i > 0 ) {
            $co = $vpn_error['computer'];
            echo '<br>';
        }
		echo '<span class="btn btn-xs btn-danger" style="margin-right:5px;">'.$vpn_error['computer'].' - '.$vpn_error['profile'].'</span>';
    }
?>
</div>
</div>  

<div id="net_error" class="net_error_lists" style="text-align:left;position:absolute;z-index:1;background:#e7e7e7;border-radius  :15px;top:120px;left:calc( 50% - 80px);padding:10px 20px;display:none;">  
    <div class="net_error" style="padding:10px;background:white;border-radius  :15px;border:1px solid #000;">
    <h5>NET 에러목록 <span class="btn btn-sm btn-warning" onclick="fcheck('all','net');">리셋</span></h5>
    <?php
        $net_sql = mysqli_query($sub_site['proxy'],"select * from a_error_log_b where message='neterror' order by computer asc , profile asc " );
        $nco = '';
        $npro = '';
        for($i=0;$net_error=mysqli_fetch_array($net_sql);$i++){            
            if($nco != $net_error['computer'] ) {
                $nco = $net_error['computer'];
                $npro = '';
            #3 $fpro = '';
                echo '<br><br>';
            }
            /*
            if($npro != $net_error['profile'] || 1==true){
                #$npro = $net_error['profile'];
                $ncnt = mysqli_fetch_array( mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_error_log_b 
                            where message='neterror' 
                            and computer = '{$net_error['computer']}' 
                            and profile = '{$net_error['profile']}'  " ));
                            $nid = $net_error['computer'].'-'.$net_error['profile'];
                            echo '<span class="btn btn-xs btn-';
                            if ($ncnt['cnt']>30) echo "danger"; else  echo "info";
                            echo ' info-nidrs" id="n'.$nid.'" onclick="fcheck(\''.$nid.'\',\'net\');" style="margin-right:5px;">';
                            echo $net_error['computer'].' - '.$net_error['profile'].' ['.$ncnt['cnt'].']</span>';
            
            }
            */
            $nid = $net_error['computer'].'-'.$net_error['profile'];
            echo '<span class="btn btn-xs btn-danger';
            echo ' info-nidrs" id="n'.$nid.'" onclick="fcheck(\''.$nid.'\',\'net\');" style="margin-right:5px;">';
            echo $net_error['computer'].' - '.$net_error['profile'].'</span>';    
        }
    ?>
    </div>
</div>

<div id="filter_error" class="filter_error_lists" style="text-align:left;position:absolute;z-index:1;background:#e7e7e7;border-radius  :15px;top:120px;left:calc( 50% - 80px);padding:10px 20px;display:none;">  
    <div class="filter_error" style="padding:10px;background:white;border-radius  :15px;border:1px solid #000;">
    <h5>mobile 계정 에러목록 <span class="btn btn-sm btn-warning" onclick="fcheck('all','filter');">리셋</span></h5>
    <?php
        $filter_sql = mysqli_query($sub_site['proxy'],"select * from a_error_log_b where message='iderror' order by computer asc , profile asc " );
        $fco = '';
        $fpro = '';
        for($i=0;$filter_error=mysqli_fetch_array($filter_sql);$i++){
            if($fco != $filter_error['computer'] ) {
                $fco = $filter_error['computer'];
                $fpro = '';
            #3 $fpro = '';
                echo '<br><br>';
            }
            /*
            if($fpro != $filter_error['profile'] ){
                $fpro = $filter_error['profile'];
                $fcnt = mysqli_fetch_array( mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_error_log_b 
                            where message='iderror' 
                            and computer = '{$filter_error['computer']}' 
                            and profile = '{$filter_error['profile']}'  " ));
                            $fid = $filter_error['computer'].'-'.$filter_error['profile'];
                            echo '<span class="btn btn-xs btn-';
                            if ($fcnt['cnt']>30) echo "danger"; else  echo "info";
                            echo ' info-idrs" id="'.$fid.'" onclick="fcheck(\''.$fid.'\',\'filter\');" style="margin-right:5px;">';
                            echo $filter_error['computer'].' - '.$filter_error['profile'].' ['.$fcnt['cnt'].']</span>';
            
            }
            */
            $fid = $filter_error['computer'].'-'.$filter_error['profile'];
            echo '<span class="btn btn-xs btn-danger';
            echo ' info-idrs" id="'.$fid.'" onclick="fcheck(\''.$fid.'\',\'filter\');" style="margin-right:5px;">';
            echo $filter_error['computer'].' - '.$filter_error['profile'].'</span>';
           

        }
    ?>
    </div>
</div>


<div id="pfilter_error" class="pfilter_error_lists" style="text-align:left;position:absolute;z-index:1;background:#e7e7e7;border-radius  :15px;top:120px;left:calc( 50% - 80px);padding:10px 20px;display:none;">  
    <div class="pfilter_error" style="padding:10px;background:white;border-radius  :15px;border:1px solid #000;">
    <h5>proxy 계정 에러목록 <span class="btn btn-sm btn-warning" onclick="fpcheck('all','filter');">리셋</span></h5>
    <?php
        $pfilter_sql = mysqli_query($sub_site['proxy'],"select * from a_server where loginstatus = 'off' order by computer asc , profile asc " );
        $pfco = '';
     
        for($i=0;$pfilter_error=mysqli_fetch_array($pfilter_sql);$i++){
            if($pfco != $pfilter_error['computer'] && $i >0 ) {
                $pfco = $pfilter_error['computer'];                
                echo '<br>';
            }

    
            $pfid = $pfilter_error['computer'].'-'.$pfilter_error['profile'];
            echo '<span class="btn btn-xs btn-danger';
            echo ' info-pidrs" id="'.$pfid.'" onclick="fpcheck(\''.$pfid.'\',\'filter\');" style="margin-right:5px;">';
            echo $pfilter_error['computer'].' - '.$pfilter_error['profile'].'</span>';
           

        }
    ?>
    </div>
</div>

<?php if(1==false) {//테스트 젠피씨 사용안함?>    
    <?php 		
	if($_GET['test_set'] == "test" && isset($_GET['test_set'])) {
		if(isset($_POST['test00']) && $_POST['test00'])  $test00 = trim(strip_tags(clean_xss_attributes( $_POST['test00'])));
		if(isset($_POST['test01']) && $_POST['test01'])  $test01 = trim(strip_tags(clean_xss_attributes( $_POST['test01'])));
		if(!$test01 ) $test01 = $test00;
		if($test00 && $test01 ) mysqli_query($sub_site['zenpc'],"update a_serverconfig set test00 = '{$test00}' , test01 = '{$test01}' where id = '1' " );
		unset($_POST['test00']);
		unset($_POST['test01']);
	}
	$test_project = mysqli_fetch_array(mysqli_query($sub_site['zenpc'],"select * from a_serverconfig where id = '1' " ));	
	$test00 = $test_project['test00'] ;
	$test01 = $test_project['test01'] ;
	?>
 
<form name="test-setting" action="./project_form.php?test_set=test" method="post" style="display:inline-flex;">
1~200젠컴퓨터 00x브라우저 프로젝트id값 <input style="width:60px;" class="form-control" type="text" name="test00" value="<?php echo $test00;?>"/> 
테스트01x브라우저 프로젝트id값 <input style="width:60px;" class="form-control" type="text" name="test01" value="<?php echo $test01;?>"/> 
<button class="submit-btn btn-success">테스트프로젝트설정</button>
</form>               
</div>
<?php } ?>

<div id="booking" class="section">
    <div class="col-sm-4">
 <?php
	######### 프록시 정보
 	if($project_id && $log_q  &&  1==true) {
		
		
		$psperm_log_q_total = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select  count(*)  as cnt from a_server where q = '{$log_q}' "));
		$pmsgtype['total'] = $psperm_log_q_total['cnt'];
		$psperm_msg_sql = mysqli_query($sub_site['proxy']," select message , count(message) as cnt from a_server where q = '{$log_q}' group by message ");
	} else if (!$project_id) {
		$psperm_q_total = mysqli_fetch_array(mysqli_query($sub_site['proxy']," SELECT SUM(c.cnt) AS live from (SELECT a.id, a.project_id ,a.psperms , COUNT(b.q) AS cnt, a.q , a.datetime   FROM `a_serverset` a 
											LEFT JOIN a_server b ON (b.q = a.q  ) 
											WHERE a.status = 'healthy'											
											and a.q <> '' 
											and b.message <> ''
											GROUP BY a.q, a.id 
											ORDER BY cnt
                                                  ) c "));
		$pmsgtype['total'] = $psperm_q_total['live'];		
		$psperm_msg_sql = mysqli_query($sub_site['proxy'],"select message , count(message) as cnt from a_server a 
								left join a_serverset b ON (a.q=b.q)
								where b.status = 'healthy'
								and b.q <> ''
								group by a.message
								");
	}
	if($pmsgtype['total']){
			for($i=0;$pmsg_type=mysqli_fetch_array($psperm_msg_sql);$i++){
			$pmsgtype[$pmsg_type['message']] =  $pmsg_type['cnt'];
		}
		
	}
	?>
<?php if(1==true){?> 
        <div class="log-wrap proxy-log-wrap">
            <h3>
                <a href="./project_form.php?project_id=<?php echo $project_id;?>" class="btn btn-lg btn-primary"><?php if(!$project['project_name']) echo '활동 프록시';else echo trim($project['project_name'])." P : ";?>
                	<span class="badge badge-secondary"><?php echo $pmsgtype['total'];?></span>
                </a>
                <div class="viewstart btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=viewstart">시청 <span class="badge badge-secondary"><?php echo $pmsgtype['viewstart'];?></span></a></div>
                <div class="parking btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=parking">여유 <span class="badge badge-secondary"><?php echo $pmsgtype['parking'];?></span></a></div>
                <div class="servercheck btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=servercheck">대기 <span class="badge badge-secondary"><?php echo $pmsgtype['servercheck'];?></span></a></div>
                
                <div class="endview btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=endview">완료 <span class="badge badge-secondary"><?php echo $pmsgtype['endview'];?></span></a></div>
                <div class="break btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=break">탈출 <span class="badge badge-secondary"><?php echo $pmsgtype['break'];?></span></a></div>
                        
                <div class="nofindtarget btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=nofindtarget">미노출 <span class="badge badge-secondary"><?php echo $pmsgtype['nofindtarget'];?></span></a></div>
                <div class="failfindkeyword btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=failfindkeyword">미검색 <span class="badge badge-secondary"><?php echo $pmsgtype['failfindkeyword'];?></span></a></div>
                <div class="iderror btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=iderror">계정빠짐 <span class="badge badge-secondary"><?php echo $pmsgtype['iderror'];?></span></a></div>
                <div class="neterror btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=neterror">vpn <span class="badge badge-secondary"><?php echo $pmsgtype['neterror'];?></span></a></div>
                <div class="notinteract btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=notinteract">미클릭 <span class="badge badge-secondary"><?php echo $pmsgtype['notinteract'];?></span></a></div>
                <div class="seterror btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=seterror">미설정 <span class="badge badge-secondary"><?php echo $pmsgtype['seterror'];?></span></a></div>        
                <div class="critical btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=critical">통과 <span class="badge badge-secondary"><?php echo $pmsgtype['critical'];?></span></a></div>
                <div class="etc btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=etc">불일치 <span class="badge badge-secondary"><?php echo $pmsgtype['etc'];?></span></a></div>
            </h3>
        </div>   
<?php } ?>         
 <?php
	######### 모바일 정보
 	if($project_id && $log_q  &&  1==true) {
		
		
		$msperm_log_q_total = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select  count(*)  as cnt from a_server_b where q = '{$log_q}' "));
		$mmsgtype['total'] = $msperm_log_q_total['cnt'];
		$msperm_msg_sql = mysqli_query($sub_site['proxy']," select message , count(message) as cnt from a_server_b where q = '{$log_q}' group by message ");
	} else if (!$project_id) {
		$msperm_q_total = mysqli_fetch_array(mysqli_query($sub_site['proxy']," SELECT SUM(c.cnt) AS live from (SELECT a.id, a.project_id ,a.psperms , COUNT(b.q) AS cnt, a.q , a.datetime   FROM `a_serverset` a 
											LEFT JOIN a_server_b b ON (b.q = a.q  ) 
											WHERE a.status = 'healthy'											
											and a.q <> '' 
											and b.message <> ''
											GROUP BY a.q, a.id 
											ORDER BY cnt
                                                  ) c "));
		$mmsgtype['total'] = $msperm_q_total['live'];		
		$msperm_msg_sql = mysqli_query($sub_site['proxy'],"select message , count(message) as cnt from a_server_b a 
								left join a_serverset b ON (a.q=b.q)
								where b.status = 'healthy'
								and b.q <> ''
								group by a.message
								");
	}
	if($mmsgtype['total']){
			for($i=0;$mmsg_type=mysqli_fetch_array($msperm_msg_sql);$i++){
			$mmsgtype[$mmsg_type['message']] =  $mmsg_type['cnt'];
		}
		
	}
	?>
<?php if(1==true){?> 
        <div class="log-wrap mobile-log-wrap">
            <h3>
                <a href="./project_form.php?project_id=<?php echo $project_id;?>" class="btn btn-lg btn-primary"><?php if(!$project['project_name']) echo '활동 모바일';else echo trim($project['project_name'])." M : ";?>
                	<span class="badge badge-secondary"><?php echo $mmsgtype['total'];?></span>
                </a>
                <div class="viewstart btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=viewstart">시청 <span class="badge badge-secondary"><?php echo $mmsgtype['viewstart'];?></span></a></div>
                <div class="parking btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=parking">여유 <span class="badge badge-secondary"><?php echo $mmsgtype['parking'];?></span></a></div>
                <div class="servercheck btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=servercheck">대기 <span class="badge badge-secondary"><?php echo $mmsgtype['servercheck'];?></span></a></div>
                
                <div class="endview btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=endview">완료 <span class="badge badge-secondary"><?php echo $mmsgtype['endview'];?></span></a></div>
                <div class="break btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=break">탈출 <span class="badge badge-secondary"><?php echo $mmsgtype['break'];?></span></a></div>
                        
                <div class="nofindtarget btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=nofindtarget">미노출 <span class="badge badge-secondary"><?php echo $mmsgtype['nofindtarget'];?></span></a></div>
                <div class="failfindkeyword btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=failfindkeyword">미검색 <span class="badge badge-secondary"><?php echo $mmsgtype['failfindkeyword'];?></span></a></div>
                <div class="iderror btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=iderror">계정빠짐 <span class="badge badge-secondary"><?php echo $mmsgtype['iderror'];?></span></a></div>
                <div class="neterror btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=neterror">vpn <span class="badge badge-secondary"><?php echo $mmsgtype['neterror'];?></span></a></div>                
                <div class="notinteract btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=notinteract">미클릭 <span class="badge badge-secondary"><?php echo $mmsgtype['notinteract'];?></span></a></div>
                <div class="seterror btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=seterror">미설정 <span class="badge badge-secondary"><?php echo $mmsgtype['seterror'];?></span></a></div>        
                <div class="critical btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=critical">통과 <span class="badge badge-secondary"><?php echo $mmsgtype['critical'];?></span></a></div>
                <div class="etc btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=etc">불일치 <span class="badge badge-secondary"><?php echo $mmsgtype['etc'];?></span></a></div>
            </h3>
        </div>   
<?php } ?>
<?php if(1==false){?> 
        <div class="log-wrap zenpc-log-wrap">
            <h3>
                <a href="./project_form.php?project_id=<?php echo $project_id;?>" class="btn btn-lg btn-primary"><?php if(!$project['project_name']) echo '활동 젠피씨';else echo trim($project['project_name'])." Z: ";?>
                	<span class="badge badge-secondary"><?php echo $msgtype['total'];?></span>
                </a>
                <div class="viewstart btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=viewstart">시청 <span class="badge badge-secondary"><?php echo $msgtype['viewstart'];?></span></a></div>
                <div class="parking btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=parking">여유 <span class="badge badge-secondary"><?php echo $msgtype['parking'];?></span></a></div>
                <div class="servercheck btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=servercheck">대기 <span class="badge badge-secondary"><?php echo $msgtype['servercheck'];?></span></a></div>
                
                <div class="endview btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=endview">완료 <span class="badge badge-secondary"><?php echo $msgtype['endview'];?></span></a></div>
                <div class="break btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=break">탈출 <span class="badge badge-secondary"><?php echo $msgtype['break'];?></span></a></div>
                        
                <div class="nofindtarget btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=nofindtarget">미노출 <span class="badge badge-secondary"><?php echo $msgtype['nofindtarget'];?></span></a></div>
                <div class="failfindkeyword btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=failfindkeyword">미검색 <span class="badge badge-secondary"><?php echo $msgtype['failfindkeyword'];?></span></a></div>
                <div class="iderror btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=iderror">계정빠짐 <span class="badge badge-secondary"><?php echo $msgtype['iderror'];?></span></a></div>
                <div class="neterror btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=neterror">vpn <span class="badge badge-secondary"><?php echo $msgtype['neterror'];?></span></a></div>                
                <div class="notinteract btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=notinteract">미클릭 <span class="badge badge-secondary"><?php echo $msgtype['notinteract'];?></span></a></div>
                <div class="seterror btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=seterror">미설정 <span class="badge badge-secondary"><?php echo $msgtype['seterror'];?></span></a></div>        
                <div class="critical btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=critical">통과 <span class="badge badge-secondary"><?php echo $msgtype['critical'];?></span></a></div>
                <div class="etc btn btn-xs"><a href="./project_form.php?project_id=<?php echo $project_id;?>&spermtype=etc">불일치 <span class="badge badge-secondary"><?php echo $msgtype['etc'];?></span></a></div>
            </h3>
        </div>
<?php } ?>
         
        
        
        
        
        
<?php if(isset($_GET['computer_id']) && $_GET['computer_id'] ) {
		$computer_id = $_GET['computer_id'];
		if(isset($_GET['type']) && $_GET['type'] == 'zenpc') {//젠피씨컴퓨터
			$bm_sql = "";
			$computer_type = $_GET['type'];
		} else if (isset($_GET['type']) && $_GET['type'] == 'proxy') {//프록시컴퓨터
			 $computer_type = "proxy";
			 $bm_sql = "";
		} else if (isset($_GET['type']) && $_GET['type'] == 'mobile') {//모바일컴퓨터 
			 $computer_type = "proxy";
			 $bm_sql = "_b";
		}
		//$computer_type = 'zenpc';
		$computer_q_total = mysqli_fetch_array(mysqli_query($sub_site[$computer_type]," SELECT count(*) as cnt from a_server{$bm_sql} where computer = '{$computer_id}' "));
		$computer_msg_sql = mysqli_query($sub_site[$computer_type],"select message , count(message) as cnt from a_server{$bm_sql} where computer = '{$computer_id}' group by message	");	
		for($i=0;$computer_msg=mysqli_fetch_array($computer_msg_sql);$i++){
			if(!$computer_msg['message']) $computer['mia'] =  $computer_msg['cnt'];
			$computer[$computer_msg['message']] =  $computer_msg['cnt'];
		}	
		
		
?>
        <div class="log-body log-computer">
        	<h5>
            	<a href="./project_form.php?computer_id=<?php echo $computer_id;?>" class="btn btn-md btn-primary"><?php echo $computer_id;?> 등록 <span class="badge badge-secondary"><?php echo $computer_q_total['cnt'];?></span></a>

                <div class="viewstart btn btn-xs">시청 <span class="badge badge-secondary"><?php echo $computer['viewstart'];?></span></div>
                <div class="parking btn btn-xs">여유 <span class="badge badge-secondary"><?php echo $computer['parking'];?></span></div>
                <div class="servercheck btn btn-xs">대기 <span class="badge badge-secondary"><?php echo $computer['servercheck'];?></span></div>
                <div class="newsperm btn btn-xs">신규 <span class="badge badge-secondary"><?php echo $computer['newsperm'];?></span></div>
                <div class="endview btn btn-xs">완료 <span class="badge badge-secondary"><?php echo $computer['endview'];?></span></div>
                <div class="break btn btn-xs">탈출 <span class="badge badge-secondary"><?php echo $computer['break'];?></span></div>
                <div class="seterror btn btn-xs">미설정 <span class="badge badge-secondary"><?php echo $computer['seterror'];?></span></div>        
       
                <div class="nofindtarget btn btn-xs">미노출 <span class="badge badge-secondary"><?php echo $computer['nofindtarget'];?></span></div>
                <div class="failfindkeyword btn btn-xs">미검색 <span class="badge badge-secondary"><?php echo $computer['failfindkeyword'];?></span></div>
                <div class="iderror btn btn-xs">계정빠짐 <span class="badge badge-secondary"><?php echo $computer['iderror'];?></span></div>
                <div class="neterror btn btn-xs">vpn <span class="badge badge-secondary"><?php echo $computer['neterror'];?></span></div>
                <div class="notinteract btn btn-xs">미클릭 <span class="badge badge-secondary"><?php echo $computer['notinteract'];?></span></div>        
                <div class="critical btn btn-xs">통과 <span class="badge badge-secondary"><?php echo $computer['critical'];?></span></div>
                <div class="etc btn btn-xs">불일치 <span class="badge badge-secondary"><?php echo $computer['etc'];?></span></div>
				<div class="etc btn btn-xs">미아 <span class="badge badge-warning"><?php echo $computer['mia'];?></span></div>
                <?php if( $bm_sql == "_b" || 1 == true) {?>
                    <div class="computer_reset">
                       <span onclick="computer_reset('<?php echo $computer_id;?>','<?php echo $_GET['type'];?>');" class="viewerreset btn btn-xs">뷰어시청초기화</span>
                       <span onclick="computer_log('<?php echo $computer_id;?>','<?php echo $_GET['type'];?>');"class="viewerlog btn btn-xs">뷰어세부로그</span></a>
                    </div>
                <?php } ?>
            </h5>
<?php	
		$computer_sperm_sql = mysqli_query($sub_site[$computer_type],"select * from a_server{$bm_sql} where computer = '{$computer_id}'  order by log_time desc ");	
		for($w=0;$computer_sperm = mysqli_fetch_array($computer_sperm_sql);$w++){
			if(!$computer_sperm['message']) $computer_sperm['message'] = 'mia';
			if($computer_sperm['message'] == "nofindtaget") $computer_sperm['message'] = "nofindtarget";
			if($computer_sperm['message'] == "nointeract") $computer_sperm['message'] = "notinteract";			
?>
        
                        
            <div class="info <?php echo $computer_sperm['message'];?> rankinfo">
                <?php if( $bm_sql == "_b" || 1 == true) { echo $computer_sperm['computer']." : ".$computer_sperm['profile']; } ?>
                <?php echo $computer_sperm['proxy'];?> <?php if(strpos($computer_sperm['proxy'],"x") !==false) echo '<span class="zenpc"></span>';?>
                        <?php if($computer_sperm['loginstatus'] == "off") echo '<span class="loginoff"></span>';?>
                        <?php if($computer_sperm['uilog']>0) echo '<span class="uilog">'.$computer_sperm['uilog'].'</span>';?>
                        <span class="s<?php echo $computer_sperm['message'];?> rankinfo-text " >
                            <?php echo $computer_sperm['computer'];?> <?php echo $computer_sperm['profile'];?> 
                            <?php echo get_date_diff($computer_sperm['log_time']);?> <?php echo $computer_sperm['q'];?>
                            <br>
                                사이즈 :  <?php echo $computer_sperm['size'];?><br>
                                모델 : <?php echo $computer_sperm['model'];?><br>
                                시리얼 : <?php echo $computer_sperm['serial'];?>
                        </span>
                        
                    </div>
        <?php } ?>
        <div id="computer_log_view"></div>
		</div>
<?php } ?>            


<?php 
function message_to_kor($m){
	switch($m){
		case "viewstart":$k="시청";break;
		case "parking":$k="여유";break;
		case "servercheck":$k="대기";break;
		case "endview":$k="완료";break;
		case "break":$k="탈출";break;
		case "nofindtarget":$k="미노출";break;
		case "failfindkeyword":$k="미검색";break;
		case "nointeract":$k="미클릭";break;
		case "notinteract":$k="미클릭";break;
		case "critical":$k="통과";break;
		case "etc":$k="불일치";break;
		case "mia":$k="미아";break;
        case "iderror":$k="계정빠짐";break;
		case "neterror":$k="VPN";break;
		case "seterror":$k="설정";break;			
		case "newsperm":$k="신규";break;
		default:$k="기타";break;		
	}
	return $k;
}
?>
        <div class="log-body col-sm-6 proxy-viewers log-pb80">
        
        <?php if($project_id ){ //&& !$msgtype['total'] ) {//프로젝트는 출력 메인전체는 출력안한다.}

				?>
                <h4> 프록시 뷰어</h4>
                <?php 
				for($l=0;$log = mysqli_fetch_array($log_sql_proxy);$l++){?>
               
                <div class="info <?php echo $log['message'];?> rankinfo"> <?php echo message_to_kor($log['message']);?> <?php if(strpos($log['proxy'],"x") !==false) echo '<span class="zenpc"></span>';?>
                <?php if($log['loginstatus'] == "off") echo '<span class="loginoff"></span>';?>
                    <span class="s<?php echo $log['message'];?> rankinfo-text " >
                    <?php echo $log['proxy'];?> 
						<a href="./project_form.php?computer_id=<?php echo $log['computer'];?>">
                        <?php echo $log['computer'];?> <?php echo $log['profile'];?>
                        </a> 
						 <?php echo get_date_diff($log['log_time']);?> <?php echo $log['q'];?>
                    </span>
                </div>
                       	
		<?php } 		
		 } else echo "<span>전체보기에서는 속도문제로 프록시 뷰어별 출력은 하지 않는다. </span>";//if false
		?>
        </div>
        
        <div class="log-body col-sm-6 mobile-viewers log-pb80">
        
        <?php if($project_id ){ //&& !$msgtype['total'] ) {//프로젝트는 출력 메인전체는 출력안한다.}
				?>
                <h4> 모바일 뷰어</h4>
                <?php               
                		
				for($l=0;$log = mysqli_fetch_array($log_sql_mobile);$l++){?>
               
                <div class="info <?php echo $log['message'];?> rankinfo"> <?php echo message_to_kor($log['message']);?> <?php if(strpos($log['proxy'],"x") !==false) echo '<span class="zenpc"></span>';?>
                <?php if($log['loginstatus'] == "off") echo '<span class="loginoff"></span>';?>
                    <span class="s<?php echo $log['message'];?> rankinfo-text " >
                    <?php echo $log['proxy'];?> 
						<a href="./project_form.php?computer_id=<?php echo $log['computer'];?>"><?php echo $log['computer'];?> <?php echo $log['profile'];?></a> 
						 <?php echo get_date_diff($log['log_time']);?> <?php echo $log['q'];?>
                        <br>
                        사이즈 :  <?php echo $log['size'];?><br>
                        모델 : <?php echo $log['model'];?><br>
                        시리얼 : <?php echo $log['serial'];?>

                    </span>
                </div>
                       	
		<?php } 		
		 } else echo "<span>전체보기에서는 속도문제로 모바일 뷰어별 출력은 하지 않는다. </span>";//if false
		?>
        </div>
        

        <div class="log-body col-sm-4 zenpc-viewers log-pb80">
        
        <?php if($project_id ){ //&& !$msgtype['total'] ) {//프로젝트는 출력 메인전체는 출력안한다.}
				?>
                <h4> 젠PC 뷰어</h4>
                <?php 
		
				for($l=0;$log = sql_fetch_array($log_sql);$l++){
					
					
					if($log['message'] == "nofindtaget") $log['message'] = "nofindtarget";
					if($log['message'] == "nointeract") $log['message'] = "notinteract";	
			?>
               
                <div class="info <?php echo $log['message'];?> rankinfo"> <?php echo message_to_kor($log['message']);?>
                <?php if($log['loginstatus'] == "off") echo '<span class="loginoff"></span>';?>
                    <span class="s<?php echo $log['message'];?> rankinfo-text " >
                    <?php echo $log['proxy'];?> 
						<a href="./project_form.php?computer_id=<?php echo $log['computer'];?>"><?php echo $log['computer'];?> <?php echo $log['profile'];?></a> 
						 <?php echo get_date_diff($log['log_time']);?> <?php echo $log['q'];?>
                    </span>
                </div>
                       	
		<?php } 		
		 } else echo "<span>전체보기에서는 속도문제로 젠피씨 뷰어별 출력은 하지 않는다. </span>";//if false
		?>
        </div>
        
        
        <div class="clear-fix"></div>
    </div>
    
    
	<div class="col-sm-8">
		<div class="project_monitor">
            <?php $total_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) as cnt from a_server where q <> '' and type = 'zenpc' "));
				   $total_cnt = $total_cnt_a['cnt'];
				   
				$current_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) AS current from a_server where q in (select q from a_serverset where q <> '' and status = 'healthy')  and type = 'zenpc' "));
				$current_cnt = $current_cnt_a['current'];				   
				   
				   $live_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," SELECT SUM(c.cnt) AS live from (SELECT a.id, a.project_id , COUNT(b.q) AS cnt, a.q , a.datetime   FROM `a_serverset` a 
											LEFT JOIN a_server b ON (b.q = a.q  ) 
											WHERE a.status = 'healthy'											
											and a.q <> ''
											and b.message <> ''
                                            and b.message = 'viewstart'
											and b.type = 'zenpc'
											GROUP BY a.q, a.id 
											ORDER BY cnt
                                                  ) c "));
				$live_cnt = $live_cnt_a['live'];		

				$parking_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," SELECT count(*) AS parking from a_server WHERE message = 'parking' "));
				$parking_cnt = $parking_cnt_a['parking'];
                
				$escaping_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) AS escaping from a_server where q not in (select q from a_serverset where q <> '' and status = 'healthy')  "));
				$escaping_cnt = $escaping_cnt_a['escaping'];
				
				$mung_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['zenpc'],"  select count(*) as cnt from a_server where q = ''  "));
				$mung_cnt = $mung_cnt_a['cnt'];

				//프록시
				
				$ptotal_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) as cnt from a_server where q <> '' "));
				$ptotal_cnt = $ptotal_cnt_a['cnt'];
				
				$pcurrent_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) AS current from a_server where q in (select q from a_serverset where q <> '' and status = 'healthy') "));
				$pcurrent_cnt = $pcurrent_cnt_a['current'];				   

				
				$plive_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy']," SELECT SUM(c.cnt) AS live from (SELECT a.id, a.project_id , COUNT(b.q) AS cnt, a.q , a.datetime   FROM `a_serverset` a 
											LEFT JOIN a_server b ON (b.q = a.q  ) 
											WHERE a.status = 'healthy'											
											and a.q <> ''
											and b.message <> ''
                                            and b.message = 'viewstart'
											GROUP BY a.q, a.id 
											ORDER BY cnt
                                                  ) c "));
				$plive_cnt = $plive_cnt_a['live'];	
                
				$pparking_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy']," SELECT count(*) AS parking from a_server WHERE message = 'parking' "));
				$pparking_cnt = $pparking_cnt_a['parking'];	

				$pescaping_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) AS escaping from a_server where q not in (select q from a_serverset where q <> '' and status = 'healthy') "));
				$pescaping_cnt = $pescaping_cnt_a['escaping'];
																		  
				$pmung_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"  select count(*) as cnt from a_server where q = '' "));
				$pmung_cnt = $pmung_cnt_a['cnt'];
				
								
				
				//모바일
				
				$mtotal_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) as cnt from a_server_b where q <> '' "));
				$mtotal_cnt = $mtotal_cnt_a['cnt'];
				
				$mcurrent_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) AS current from a_server_b where q in (select q from a_serverset where q <> '' and status = 'healthy') "));
				$mcurrent_cnt = $mcurrent_cnt_a['current'];				   

				
				$mlive_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy']," SELECT SUM(c.cnt) AS live from (SELECT a.id, a.project_id , COUNT(b.q) AS cnt, a.q , a.datetime   FROM `a_serverset` a 
											LEFT JOIN a_server_b b ON (b.q = a.q  ) 
											WHERE a.status = 'healthy'											
											and a.q <> ''
											and b.message <> ''
                                            and b.message = 'viewstart'
											GROUP BY a.q, a.id 
											ORDER BY cnt
                                                  ) c "));
				$mlive_cnt = $mlive_cnt_a['live'];	
                
				$mparking_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy']," SELECT count(*) AS servercheck from a_server_b WHERE message = 'servercheck' "));
				$mparking_cnt = $mparking_cnt_a['servercheck'];	

				$mescaping_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) AS escaping from a_server_b where q not in (select q from a_serverset where q <> '' and status = 'healthy') "));
				$mescaping_cnt = $mescaping_cnt_a['escaping'];
																		  
				$mmung_cnt_a = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"  select count(*) as cnt from a_server_b where q = '' "));
				$mmung_cnt = $mmung_cnt_a['cnt'];
				
								
			?>        
        	<h4>
            	<a href="./project_form.php" class="btn btn-md btn-primary">총등록수 <span class="badge badge-secondary"><?php echo $total_cnt+$ptotal_cnt+$mtotal_cnt;?></span> <br />
                	<span class="badge badge-secondary">P : <?php echo $ptotal_cnt;?></span>  <span class="badge badge-secondary">M : <?php echo $mtotal_cnt;?></span> <span class="badge badge-secondary">Z : <?php echo $total_cnt;?></span> 
                </a>
                <a id="current-com" class="btn btn-md btn-<?php if($project_id == "") echo "danger ";else echo "info";?>">현프로젝트 <span class="badge badge-secondary"><?php echo $current_cnt+$pcurrent_cnt+$mcurrent_cnt;?></span> <br />
                	<span class="badge badge-secondary">P : <?php echo $pcurrent_cnt;?></span> <span class="badge badge-secondary">M : <?php echo $mcurrent_cnt;?></span> <span class="badge badge-secondary">Z : <?php echo $current_cnt;?></span>
                </a>                
                <a id="current-com" class="btn btn-md btn-success">시청수 <span class="badge badge-secondary"><?php echo $live_cnt+$plive_cnt+$mlive_cnt;?></span> <br />
                	<span class="badge badge-secondary">P : <?php echo $plive_cnt;?></span> <span class="badge badge-secondary">M : <?php echo $mlive_cnt;?></span> <span class="badge badge-secondary">Z : <?php echo $live_cnt;?></span>
                </a>
                <a id="current-com" class="btn btn-md btn-<?php if($project_id == "") echo "danger ";else echo "info";?>">대기수 <span class="badge badge-secondary"><?php echo $parking_cnt+$pparking_cnt+$mparking_cnt;?></span> <br />
                	<span class="badge badge-secondary">P : <?php echo $pparking_cnt;?></span> <span class="badge badge-secondary">M : <?php echo $mparking_cnt;?></span> <span class="badge badge-secondary">Z : <?php echo $parking_cnt;?></span>
                </a>
                
				<a id="escaping-com" class="btn btn-md btn-<?php if($project_id == "") echo "danger ";else echo "info";?>">미복귀 <span class="badge badge-secondary"><?php echo $escaping_cnt+$pescaping_cnt+$mescaping_cnt;?></span> <br />
                <span class="badge badge-secondary">P : <?php echo $pescaping_cnt;?></span> <span class="badge badge-secondary">M : <?php echo $mescaping_cnt;?></span> <span class="badge badge-secondary">Z : <?php echo $escaping_cnt;?></span>
                </a>                                
                <a id="mia-com" class="btn btn-xs btn-warning">미아 <span class="badge badge-secondary"><?php echo $mung_cnt+$pmung_cnt+$mmung_cnt;?></span><br />
                	<span class="badge badge-secondary">P : <?php echo $pmung_cnt;?></span> <span class="badge badge-secondary">M : <?php echo $mmung_cnt;?></span> <span class="badge badge-secondary">Z : <?php echo $mung_cnt;?></span> 
                </a>
                <a href="./project_form.php?clear=day" onclick="confirm('활동없는지 4일이 지난 뷰어들을 지우겠습니까?할당된 구글계정은 다시 초기화됩니다'):return true;" class="btn btn-xs btn-info">뷰청소 <span class="badge badge-secondary">4ㅊ일전</span></a>
                <a href="./log_view.php" class="btn btn-xs btn-info" target="_blank">구글리스트</a>
                <a href="<?php echo G5_BBS_URL ?>/logout.php" class="btn btn-xs btn-default">로그아웃</a>
                <?php if($is_admin){?><a href="/adm/" class="btn btn-default btn-xs ">관리자설정</a><?php } ?>
                <a href="http://61.109.34.246/" class="btn btn-xs btn-default">프록시서버</a>
                <a href="http://3.38.193.2/" class="btn btn-xs btn-default">젠서버</a>
                
                
            </h4>

            <div class="current-computer row" id="current-computer">
            	<div class="col-sm-4 viewers border-right proxy_" >
				<?php
				
                $current_com = mysqli_query($sub_site['proxy']," select computer , version, count(computer) as cnt from a_server group by computer order by computer asc ");
				$latest_version = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select version from a_server order by version DESC limit 0,1"));
                for($t=0;$current_row = mysqli_fetch_array($current_com);$t++){?>		
                    <div class="btn btn-xs btn-<?php if($current_row['cnt'] !== '35') echo 'danger';else echo 'info';?>">
                        <a href="./project_form.php?type=&type=proxy&computer_id=<?php echo $current_row['computer'];?>"><?php echo $current_row['computer'];?> 
                            <span class="badge badge-secondary"><?php echo $current_row['cnt'];?></span>
                            <?php if($current_row['version']) {?>
                                <span class="badge badge-danger" <?php if($current_row['version'] == $latest_version['0']) echo 'style="background-color: #0027ff;color: #fff;"';?>><?php echo $current_row['version'];?> </span>
                            <?php } ?>
                        </a>
                    </div>			
                <?php }
				
                ?>
           		</div>
            	<div class="col-sm-4 viewers border-right" >
				<?php
				
                $current_com = mysqli_query($sub_site['proxy']," select computer , version, reboottime, size, count(computer) as cnt from a_server_b group by computer order by computer asc ");
				$latest_version = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select version from a_server_b order by version DESC limit 0,1"));
                for($t=0;$current_row = mysqli_fetch_array($current_com);$t++){?>		
                    <div class="btn btn-xs btn-<?php if($current_row['cnt'] !== '40') echo 'danger';else echo 'info';?>">                        
                        <a href="./project_form.php?type=mobile&computer_id=<?php echo $current_row['computer'];?>"><?php echo $current_row['computer'];?> 
                            <span class="badge badge-secondary"><?php echo $current_row['cnt'];?></span>
                            <?php if($current_row['version']) {?>
                                <span class="badge badge-danger" <?php if($current_row['version'] == $latest_version['0']) echo 'style="background-color: #0027ff;color: #fff;"';?>><?php echo $current_row['version'];?></span>
                                <?php } ?>
                            <span class="badge badge-info"><?php echo get_date_diff($current_row['reboottime']);?></span>
                            <span class="badge badge-info"><?php echo trim($current_row['size']);?></span>
                        </a>
                    </div>			
                <?php }
				
                ?>
           		</div>                
            	<div class="col-sm-4 viewers">
				<?php
                $current_com = mysqli_query($sub_site['zenpc']," select computer , Max(version) as version , count(*) as cnt from a_server group by computer order by computer asc ");
				$latest_version = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select version from a_server order by version DESC limit 0,1"));
                for($t=0;$current_row = mysqli_fetch_array($current_com);$t++){?>		
                    <div class="btn btn-xs btn-<?php if($current_row['cnt'] !== '2') echo 'danger';else echo 'info';?>">
                        <a href="./project_form.php?type=zenpc&computer_id=<?php echo $current_row['computer'];?>"><?php echo $current_row['computer'];?> 
                            <span class="badge badge-secondary"><?php  echo $current_row['cnt'];?></span>
                            <?php if($current_row['version']) {?><span class="badge badge-danger" <?php if($current_row['version'] == $latest_version['0']) echo 'style="background-color: #0027ff;color: #fff;"';?>><?php echo $current_row['version'];?> </span><?php } ?>
                        </a>
                    </div>			
                <?php }
                ?>
           		</div>                 
			</div>   
             
 
            <div class="current-computer row" id="escaping-computer">
                <div class="col-xs-12 text-center"><span class="btn btn-md btn-info" id="escaping-toggle">미복귀 전체 보기</span></div>
            	<div class="col-sm-4 viewers border-right proxy_" >
                    
                    <div class="btn btn-xs btn-info" style="background-color:#b7e36961;">
                        <a>컴이름
                            <span class="badge badge-secondary" style="font-size:14px;color:red;">6시간이상</span>
                            <span class="badge badge-secondary" style="font-size:14px;color:red;">재부팅</span>
                            <span class="badge badge-secondary" >미복귀</span>
                            <span class="badge badge-secondary">오래된</span>
                            <span class="badge badge-secondary">최신</span>                           
                        </a>
                    </div>
                
				<?php
				$pescaping_computer = mysqli_query($sub_site['proxy']," select computer, reboottime , Min(log_time) as minlog_time  , count(*) as cnt  from a_server 
                where q not in (select q from a_serverset where q <> '' and status = 'healthy') group by computer order by cnt desc , minlog_time asc
                ");
                
                
                for($t=0;$pescaping_row = mysqli_fetch_array($pescaping_computer);$t++){
                    
                    $xpescaping_cnt =  mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) as cnt  from a_server 
                where q not in (select q from a_serverset where q <> '' and status = 'healthy') and computer = '{$pescaping_row['computer']}' and now() > DATE_ADD(log_time,interval 21600 SECOND)   
                "));                
                    $pxescaping_log_time =  mysqli_fetch_array(mysqli_query($sub_site['proxy']," select log_time from a_server where computer = '{$pescaping_row['computer']}' order by log_time desc "));
                    ?>		
                    <div class="btn btn-xs btn-info <?php if($xpescaping_cnt['cnt'] ) echo "btn-escaping"; else echo "btn-allescaping";?> ">
                        <a href="./project_form.php?type=&type=proxy&computer_id=<?php echo $pescaping_row['computer'];?>"><?php echo $pescaping_row['computer'];?> 
                            <span class="badge badge-secondary" style="font-size:14px;color:red;"><?php echo $xpescaping_cnt['cnt'];?></span>
                            <span class="badge badge-secondary" style="font-size:14px;color:red;"><?php echo get_date_diff($pescaping_row['reboottime']);?></span>
                            <span class="badge badge-secondary" ><?php echo $pescaping_row['cnt'];?></span>
                            <span class="badge badge-secondary"><?php echo get_date_diff($pescaping_row['minlog_time']);?></span>
                            <span class="badge badge-secondary"><?php echo get_date_diff($pxescaping_log_time['log_time']);?></span>                           
                        </a>
                    </div>			
                <?php }		
                ?>
           		</div>
                <div class="col-sm-4 viewers border-right" >
                    <div class="btn btn-xs btn-info" style="background-color:#b7e36961;">
                        <a>컴이름
                            <span class="badge badge-secondary" style="font-size:14px;color:red;">3시간이상</span>
                            <span class="badge badge-secondary" style="font-size:14px;color:red;">재부팅</span>
                            <span class="badge badge-secondary" >미복귀</span>
                            <span class="badge badge-secondary">오래된</span>
                            <span class="badge badge-secondary">최신</span>                           
                      </a>
                    </div>
				<?php
				$mescaping_computer = mysqli_query($sub_site['proxy']," select computer, reboottime, Min(log_time) as minlog_time  ,  count(*) as cnt  from a_server_b 
                where q not in (select q from a_serverset where q <> '' and status = 'healthy')  group by computer order by cnt desc , minlog_time asc
                ");
                
                for($t=0;$mescaping_row = mysqli_fetch_array($mescaping_computer);$t++){
                    $xmescaping_cnt =  mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) as cnt  from a_server_b 
                where q not in (select q from a_serverset where q <> '' and status = 'healthy') and computer = '{$mescaping_row['computer']}' and now() > DATE_ADD(log_time,interval 10800 SECOND) 
                "));
                    $mxescaping_log_time =  mysqli_fetch_array(mysqli_query($sub_site['proxy']," select log_time from a_server_b where computer = '{$mescaping_row['computer']}' order by log_time desc "));
                    
                    ?>		
                    <div class="btn btn-xs btn-info <?php if($xmescaping_cnt['cnt'] ) echo "btn-escaping"; else echo "btn-allescaping";?>">
                        <a href="./project_form.php?type=&type=mobile&computer_id=<?php echo $mescaping_row['computer'];?>"><?php echo $mescaping_row['computer'];?>
                            <span class="badge badge-secondary" style="font-size:14px;color:red;"><?php echo $xmescaping_cnt['cnt'];?></span>
                            <span class="badge badge-secondary" style="font-size:14px;color:red;"><?php echo get_date_diff($mescaping_row['reboottime']);?></span>
                            <span class="badge badge-secondary" style="font-size:10px;"><?php echo $mescaping_row['cnt'];?></span>
                            <span class="badge badge-secondary"><?php echo get_date_diff($mescaping_row['minlog_time']);?></span>
                            <span class="badge badge-secondary"><?php echo get_date_diff($mxescaping_log_time['log_time']);?></span>                              
                        </a>
                    </div>			
                <?php }		
                ?>                    
                </div>
                <div class="col-sm-4 viewers">
                    <div class="btn btn-xs btn-info" style="background-color:#b7e36961;">
                        <a>컴이름
                            <span class="badge badge-secondary" style="font-size:14px;color:red;">6시간이상</span>
                            <span class="badge badge-secondary" style="font-size:14px;color:red;">재부팅</span>
                            <span class="badge badge-secondary" >미복귀</span>
                            <span class="badge badge-secondary">오래된</span>
                            <span class="badge badge-secondary">최신</span>                           
                        </a>
                    </div>                    
				<?php
				$zescaping_computer = mysqli_query($sub_site['zenpc']," select computer, Min(log_time) as log_time  ,  count(*) as cnt  from a_server 
                where q not in (select q from a_serverset where q <> '' and status = 'healthy') group by computer order by  log_time asc ,cnt desc 
                ");
                
                for($t=0;$zescaping_row = mysqli_fetch_array($zescaping_computer);$t++){?>		
                    <div class="btn btn-xs btn-info">
                        <a href="./project_form.php?type=&type=zenpc&computer_id=<?php echo $zescaping_row['computer'];?>"><?php echo $zescaping_row['computer'];?> 
                            <span class="badge badge-secondary"><?php echo $zescaping_row['cnt'];?></span><span class="badge badge-secondary"><?php echo $zescaping_row['log_time'];?></span>                            
                        </a>
                    </div>			
                <?php }		
                ?>                    
                </div>

			</div>   

            <div class="mia-computer row" id="mia-computer">
            	<div class="col-sm-4 viewers border-right">            
				<?php
				
                $mia_com = mysqli_query($sub_site['proxy']," select computer , count(computer) as cnt from a_server where q = '' group by computer order by cnt DESC ");
                for($m=0;$mia_row = mysqli_fetch_array($mia_com);$m++){?>		
                    <div class="btn btn-xs btn-warning">
                        <a href="./project_form.php?type=proxy&computer_id=<?php echo $mia_row['computer'];?>"><?php echo $mia_row['computer'];?> 
                            <span class="badge badge-secondary"><?php echo $mia_row['cnt'];?></span>
                        </a>
                    </div>			
                <?php }
				
                ?>
                </div>            
            	<div class="col-sm-4 viewers border-right">            
				<?php
				
                $mia_com = mysqli_query($sub_site['proxy']," select computer , count(computer) as cnt from a_server_b where q = '' group by computer order by cnt DESC ");
                for($m=0;$mia_row = mysqli_fetch_array($mia_com);$m++){?>		
                    <div class="btn btn-xs btn-warning">
                        <a href="./project_form.php?type=mobile&computer_id=<?php echo $mia_row['computer'];?>"><?php echo $mia_row['computer'];?> 
                            <span class="badge badge-secondary"><?php echo $mia_row['cnt'];?></span>
                        </a>
                    </div>			
                <?php }
				
                ?>
                </div>
            	<div class="col-sm-4 viewers">            
				<?php
                $mia_com = mysqli_query($sub_site['zenpc']," select computer , count(computer) as cnt from a_server where q = ''  group by computer order by cnt DESC ");
                for($m=0;$mia_row = mysqli_fetch_array($mia_com);$m++){?>		
                    <div class="btn btn-xs btn-warning">
                        <a href="./project_form.php?type=zenpc&computer_id=<?php echo $mia_row['computer'];?>"><?php echo $mia_row['computer'];?> 
                            <span class="badge badge-secondary"><?php echo $mia_row['cnt'];?></span>
                        </a>
                    </div>			
                <?php }
                ?>
                </div>                
           </div> 
 

       <script>
        //href="../requestrankss.php?cid=<?php echo $set['cid'];?>" >
        function request_rank(cid,project_id){
            $.ajax({
        		url: "<?php echo G5_URL;?>/requestrankss.php?cid="+cid,
        		type: 'GET',                
                dataType: 'json',
                async: false,
                success: function(data, textStatus) {
        	        if (data.error) {
        	            alert(data.error);
        	                return false;
        	        } else {                        
                        rank_str = '';                                              
                        $.each(data['item'],function(i){
                            console.log(data['item'][i]);
                            rank_str += '<h5><a href="'+ data['item'][i]['que'] + '" target="_blank">'+data['item'][i]['room'] +" "+ data['item'][i]['name'] + "</a></h5>";
                            rank_str += 
                            '<span class="rank_line">'
                            + data['item'][i]['key2'] 
                            + " pc : [" + data['item'][i]['pc']  
                            + "] pchour : [" + data['item'][i]['pchour'] 
                            + "] pcon : [" + data['item'][i]['pcon'] 
                            + "] mobile : [" + data['item'][i]['mobile'] 
                            + "]"
                            + "시간 : " +  data['item'][i]['gettime'] 
                            +"</span><br/>"                            
                            ;
                        });
                        rank_str += '<div class="project_rank_close" onclick="close_rank_wrap(\''+cid+'\',\''+project_id+'\')">X</div>';
                        console.log(rank_str);
        				$('#'+cid+"-"+project_id).html(rank_str);
                        $('#'+cid+"-"+project_id).show();       				
        			}
        		}
        	});            
        }
        function close_rank_wrap(cid,project_id){
            $('#'+cid+"-"+project_id).html('');
            $('#'+cid+"-"+project_id).hide();

        }
       </script>
       <style>
        .project_rank_view{display:none;position:absolute;top:80px;left:0px;background:aliceblue;z-index:1;padding:5px 10px;border:1px solid;border-radius:10px;}
        .project_rank_view h5 a{font-weight:bold;color:red;}
        .project_rank_close{position:absolute;top:0px;right:10px;font-size:20px;font-weight:bold;color:white;border-radius:5px;background:black;width:30px;text-align:center;} 
       </style>                      
            <?php 
			#### -- 시청수 집계 			
			$set_sql = mysqli_query($sub_site['zenpc']," select  a.cid , a.parkingurl1, a.id , a.project_id, a.qname , a.status , a.project_name, a.q , COUNT(b.q) AS cnt , count(CASE when b.message = 'viewstart' then 1 end) as viewcnt , a.datetime FROM  `a_serverset` a LEFT JOIN a_server b ON (b.q = a.q and b.q <> '' and b.type='zenpc')  GROUP BY a.q, a.id  order by a.id");
			$project_class ="sm";			
			 	for($i=0;$set = mysqli_fetch_array($set_sql);$i++){	
                    if($member['mb_id']=="main" && $i>7) continue;
					$pset = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select a.id , a.project_id, a.status , a.project_name, a.q , COUNT(b.q) AS cnt , count(CASE when b.message = 'viewstart' then 1 end) as viewcnt , a.datetime FROM  `a_serverset` a LEFT JOIN a_server b ON (b.q = a.q and b.q <> '' ) where a.project_name =  '{$set['project_name']}'  GROUP BY a.q, a.id   order by a.id "));
					
					$mset = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select a.id , a.project_id, a.status , a.project_name, a.q , COUNT(b.q) AS cnt , count(CASE when b.message = 'viewstart' then 1 end) as viewcnt , a.datetime FROM  `a_serverset` a LEFT JOIN a_server_b b ON (b.q = a.q and b.q <> '' ) where a.project_name =  '{$set['project_name']}'  GROUP BY a.q, a.id   order by a.id "))
					
					#### 테스트 셋팅용
					//$testset = mysqli_fetch_array(mysqli_query($sub_site['zenpc'],"select a.id , a.project_id, a.status , a.project_name, a.q , COUNT(b.q) AS cnt , count(CASE when b.message = 'viewstart' then 1 end) as viewcnt , a.datetime FROM  `a_serverset` a LEFT JOIN a_server b ON (b.q = a.q and b.q <> '' and b.type = 'testzenpc' ) where a.project_name =  '{$set['project_name']}'  GROUP BY a.q, a.id   order by a.id"));
					?>
                    <div class="project_wrap">
                       <?php if($set['status']=='healthy' && (strpos($set['project_name'],"팀") !== false || strpos($set['project_name'],"테스트") !== false)) {?>
                        <div class="project_rank">                            
                                <span  onclick="request_rank('<?php echo $set['cid'];?>','<?php echo $set['project_id'];?>');" class="btn btn-sm btn-success">RANK</span>                            
                        </div>
                        <div class="project_rank_view" id="<?php echo $set['cid']."-".$set['project_id'];?>"></div>                        
                        <?php } ?>
	            <a href="./project_form.php?project_id=<?php echo $set['project_id'];?>" class="project-tab btn btn-<?php echo $project_class;?> btn-<?php if($project_id ==  $set['project_id']) echo "danger ";else {
					if($i<=14 && $set['status'] == "healthy") echo "primary";
					else if($i>14 && $set['status'] == "healthy") echo "info";
					else echo "default";
				}?>">
					<?php echo  $set['project_name'];?> - <?php echo  $set['qname'];?> 
                    <?php 
					$diff_datetime = '';
					if($set['datetime']) $diff_datetime =  trim(get_date_diff($set['datetime']));?>
                    <span class="badge" style="<?php if(strpos($diff_datetime,"분") !==false || strpos($diff_datetime,"초") !==false ) echo 'color:red;';?>"><?php echo $diff_datetime ;?></span><br />
                    <span class="badge badge-secondary">
                    <?php if($set['viewcnt']+$pset['viewcnt']) echo '시청 합계: ';?><front style="font-size:18px;"><?php echo $set['viewcnt']+$pset['viewcnt']+$mset['viewcnt'];//+$testset['viewcnt']?></front> <?php  echo " / "; echo $set['cnt']+$pset['cnt']+$mset['cnt'];?></span><br />
                    
                    <span class="badge badge-secondary">P : <?php  echo $pset['viewcnt'];?>  <?php echo " / ". $pset['cnt'];?></span> 
                    <span class="badge badge-secondary">M : <front style="font-size:18px;"><?php  echo $mset['viewcnt'];?></front>  <?php echo " / ". $mset['cnt'];?></span> 
                    <span class="badge badge-secondary">Z : <?php echo $set['viewcnt'];?><?php  echo " / ".$set['cnt'];?></span>
                    <br>
                    <?php                                         
                    $adb_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) as cnt from a_server_b where q = '{$mset['q']}' and message = 'viewstart' and version like 'b%' "));
                    $kor_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) as cnt from a_server_b where q = '{$mset['q']}' and message = 'viewstart'  and version not like 'b%' "));
                    ?>
                    <span class="badge badge-secondary">M-B : <?php echo $adb_cnt['cnt'];?> / M-K <?php echo $kor_cnt['cnt']; ?></span>
                    

                    <!--<span class="badge badge-secondary">T : <?php //echo $testset['viewcnt'];?><?php // " / ".echo $set['cnt'];?></span> -->
                    
                    <?php if($set['project_id'] == "xxx-c"){
						
											
			$all_channel_url_sql = mysqli_query($sub_site['zenpc'],	" select parkingurl1  from a_serverset where  parkingurl1 <> '' order by rand() ");
			$all_channel_url = array();
			for($ix=0;$all_channel_url_tmp=mysqli_fetch_array($all_channel_url_sql);$ix++){
				$all_channel_url[$ix] = $all_channel_url_tmp['parkingurl1'];
			}
			
			if($all_channel_url) {
				$all_channel_urls = implode("\n",$all_channel_url);			
				$ch_sql_string = '';
				
				if(strpos($all_channel_urls,"\n") !==false){
					$channel_urls= explode("\n",$all_channel_urls);
						
					for($ix=0;$ix<count($channel_urls);$ix++){
					
						$tmp_ch_array = explode(",",$channel_urls[$ix]);
						$tmp_ch_array[0] = trim($tmp_ch_array[0]);	
						if($ix>0) $ch_sql_string = $ch_sql_string.",";
						$ch_sql_string =$ch_sql_string."'".$tmp_ch_array[0]."'";
						if($tmp_ch_array[0]) {
							$ch_cnt[$ix] =  mysqli_fetch_array(mysqli_query($sub_site['zenpc'],	" select count(*) as cnt from a_server where  q = '{$tmp_ch_array[0]}' "));
							$pch_cnt[$ix] =  mysqli_fetch_array(mysqli_query($sub_site['proxy'],	" select count(*) as cnt from a_server where  q = '{$tmp_ch_array[0]}' "));							
							echo "채널q값 :".$ch_cnt[$ix]['q'] = $tmp_ch_array[0];
							echo " P : ".$pch_cnt[$ix]['cnt'];
							echo " Z : ".$ch_cnt[$ix]['cnt'];
							echo "<br>";
						}					
					}				
				} else {
					$tmp_ch_array = explode(",",$parkingurl);
					$tmp_ch_array[0] = trim($tmp_ch_array[0]);
					$ch_sql_string =  "'".$tmp_ch_array['0']."'";
					if($tmp_ch_array[0]) {
						$ch_cnt['0'] =  mysqli_fetch_array(mysqli_query($sub_site['zenpc'],	" select count(*) as cnt from a_server where  q = '{$tmp_ch_array[0]}' "));
						$pch_cnt['0'] =  mysqli_fetch_array(mysqli_query($sub_site['proxy'],	" select count(*) as cnt from a_server where  q = '{$tmp_ch_array[0]}' "));						
						if($ch_cnt['0']['cnt'] < $project_channel['psperms']) {
								echo "채널값 :".$ch_cnt['0']['q'] = $tmp_ch_array[0];
								 echo " P : ".$pch_cnt['0']['cnt'];
								 echo " Z : ".$ch_cnt['0']['cnt'];
								 echo "<br>";
						}
					}				
				}
		
			}
						
						
						
						
						
						
					}
					?>
                </a>
                	
                    <?php 
							$set_channel ='';
							if( $set['parkingurl1']) {
								if(  strpos($set['parkingurl1'],"\n") !==false) {
									$set_channel_urls = explode("\n",$set['parkingurl1']);
									for($c=0;$c<count($set_channel_urls);$c++){
										$set_channel_tmps =  explode(",",$set_channel_urls[$c]);
										if($c>0) $set_channel = $set_channel.",";
										$set_channel = $set_channel."'".str_replace("\r",'',$set_channel_tmps['0'])."'";
									}						
								} else {
									$set_channel_tmps =  explode(",",$set['parkingurl1']);
									$set_channel = "'".str_replace("\r",'',$set_channel_tmps['0'])."'";
								}										
								if($set_channel) {
									$set_channel_cnt = mysqli_fetch_array(mysqli_query($sub_site['zenpc'],"select count(q) as cnt from a_server where q in({$set_channel}) "));
									$pset_channel_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(q) as cnt from a_server where q in({$set_channel}) "));
								}
								else {
									$set_channel_cnt['cnt'] = $pset_channel_cnt['cnt'] = '';
								}
								?>
                                <?php if($set_channel_cnt ){?><div class="channel_url btn btn-xs btn-info">P : <?php echo $pset_channel_cnt['cnt'];?> Z : <?php echo $set_channel_cnt['cnt'];?></div><?php } ?>
					<?php							
							}
					?>
                    
                </div>
			<? 
			
				if($i==13 || $i == 7 || $i == 11) {
					$project_class="xs";
					echo '<div style="clear:both;"></div>';
				}			
				
			} ?>

<?php if($project_id ){         ?>  

<?php
								$zenviews_cnt = mysqli_fetch_array(mysqli_query($sub_site['zenpc'],"select count(*) as cnt from a_server where q = '{$project['q']}' and type = 'zenpc' "));
								$zenviews=$zenviews_cnt['cnt'];								
								$zensperms_cnt = mysqli_fetch_array(mysqli_query($sub_site['zenpc'],"select count(*) as cnt from a_server where q = '{$project['q']}' and type = 'zenpc' and message = 'viewstart' "));
								$zensperms=$zensperms_cnt['cnt'];
								
								
								
function channel_park_url($parkingurl) {
	if(strpos($parkingurl,"\n") !==false){
		$park_urls = explode("\n",$parkingurl);
		$park_rand = array_rand($park_urls);
		$result_string =  $park_urls[$park_rand];
		$result =  $park_urls[$park_rand];
		$tmp_array = explode(",",$result_string);
		$result = $tmp_array[0];
		$result = str_replace("\r",'',$result);
		return $result;
	} else {		
		$pur_parkingurl = explode(",",$parkingurl);
		return $pure_parkingurl['0'];		
	}
}						
								
								$zenviews_cnt = mysqli_fetch_array(mysqli_query($sub_site['zenpc'],"select count(*) as cnt from a_server where q = '{$project['q']}' and type = 'zenpc' "));		
?>	
               <div class="row">
            	<?php //print_r($project);?>
            </div>                                                                    
  
			<div class="booking-form" style="margin-top:30px;padding-right: 50px;">
                <form name="project-setting" action="./project_form.php?project_id=<?php echo $project_id;?>" method="post">
                    <input type="hidden" name="project_id" value="<?php echo $project_id;?>"/>
                    <input type="hidden" name="pcusr" value="mod"/>                 
                    <div class="row" style="background-color: #c0e8da;">
                        <div class="col-sm-2"">
                            <div class="form-group">
                                <span class="form-label">팀명</span>
                                <input class="form-control" type="text" placeholder="프로젝트이름" name="project_name" value="<?php echo trim($project['project_name']);?>" <?php if(!$is_admin) echo 'readonly="readonly"';?> >
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">상태</span>
                                <select class="form-control"name="status" >
                                    <option class="form-control" value="healthy" <?php echo get_selected($project['status'], 'healthy') ?>>ON상태</option>
                                    <option class="form-control" value="spoil" <?php echo get_selected($project['status'], 'spoil') ?>>OFF상태</option>                
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">매칭단어 </span>
                                <input class="form-control" type="text" placeholder="스샷 매칭단어" name="qname" value="<?php echo trim($project['qname']);?>" <?php if(!$is_admin) echo 'readonly="readonly"';?>>
                            </div>
                        </div>                           
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">채널ID </span>
                                <input class="form-control" type="text" placeholder="키워드 ,로 구분합니다" name="cid" value="<?php echo trim($project['cid']);?>" <?php if(!$is_admin) echo 'readonly="readonly"';?>>
                            </div>
                        </div>                                                 
                        <div class="col-sm-2">     
                            <div class="form-group">
                                <span class="form-label">셋팅시간  *<font style="color:red;"><?php if($project['datetime']) echo trim(get_date_diff($project['datetime']));?></font></span>
                                <input class="form-control" type="text" placeholder="셋팅시간"  name="datetime" value="<?php echo trim($project['datetime']);?>" readonly="readonly">
                            </div>
                        </div> 
						<div class="col-sm-2">    
                            <div class="form-group">                           
                            <button class="form-control submit-btn btn-success">저장하기</button>
                            <br>
                            <span class="form-control submit-btn btn-info" id="optimizer" onclick="optimizer_sperms('<?php $project['q'] = trim($project['q']); echo $project['q'];?>')">M할당리셋</span>
                            </div>
                        </div>                            
                    </div>
                    <div class="row" style="background-color: #c0e8da;">                        
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">영상(채널)id값 <font class="btn btn-xs btn-info" onclick="get_now_act('<?php $project['q'] = trim($project['q']); echo $project['q'];?>');"> 시청수(2분전.간격10초)</font><font id="tube_value"></font></span>
                                
                                <input class="form-control" type="text" placeholder="유튜브/채널영상 ID 값" name="q" value="<?php $project['q'] = trim($project['q']); echo $project['q'];?>" >
                            </div>
                        </div>
                      
                        <div class="col-sm-4">
                            <div class="form-group">
                                <span class="form-label">키워드 <font style="color:red;">,로 구분 맨뒤,* 자동 키워드+q값 생성</font></span>
                                <input class="form-control" type="text" placeholder="키워드 ,로 구분합니다" name="keyword" id="keyword" value="<?php echo trim($project['keyword']);?>" >
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">자동키 
                                <?php 
								if($project['autokey']){
									$pk = explode(",",$project['keyword']);?>
                                    <font class="badge badge-secondary" style="font-size:10px;"><?php echo $pk['0']." ".$project['q'];?></font>
								<?php } 	?>
                                </span>
                               	<select class="form-control" name="autokey" >
									<option class="form-control" value="" <?php echo get_selected($project['autokey'], '') ?>>OFF</option>                                      
                                    <option class="form-control" value="1" <?php echo get_selected($project['autokey'], '1') ?>>ON</option>
                                              
                                </select>
                            </div>
                        </div>                        
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">키워드별사용비율
                                <?php 
								if($project['autokey']){?>
                                    <font class="badge badge-secondary" style="font-size:10px;">,1</font>
								<?php } 	?>
                                </span>
                                <input class="form-control" type="text" placeholder="확률 ,로 구분" name="keywordper" id="keywordper" value="<?php echo trim($project['keywordper']);?>" >
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">M키워드+매칭단어                                
                                </span>
                                <input class="form-control" type="text" placeholder="시청수" name="keyqname" id="keyqname" value="<?php  echo $project['keyqname'];?>" >
                            </div>
                        </div>                        
                                               
                    </div>
                    
                    
                    <div class="row" style="background-color: aliceblue;display:none;">
                       <div class="col-sm-2">     
                            <div class="form-group">
							                            
                                <span class="form-label">Zen 뷰어수</span><span class="badge badge-secondary" style="background-color: #337ab7;
    border-color: #2e6da4;"><?php echo $zensperms;?></span>/<span class="badge btn-primary"><?php echo $zenviews;?></span>
                                <input class="form-control" type="text" placeholder="ZEN뷰어수" name="zsperms" value="<?php echo trim($project['zsperms']);?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">키워드</font></span>
                                <input class="form-control" type="text" placeholder=",로 구분" name="zkeyword" value="<?php echo trim($project['zkeyword']);?>" >
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <span class="form-label">비율</span>
                                <input class="form-control" type="text" placeholder="확률" name="zkeywordper" value="<?php echo trim($project['zkeywordper']);?>" >
                            </div>
                        </div>                        
                        <div class="col-sm-1">     
                            <div class="form-group">
                                <span class="form-label">시청시간</span>
                                <input class="form-control" type="text" placeholder="시청시간" name="zfireout" value="<?php echo trim($project['zfireout']);?>">
                            </div>
                        </div>                            
						
                        <div class="col-sm-1">    
                            <div class="form-group">
                                <span class="form-label">필터사용</span>
                                <select class="form-control" name="zmode" >
                                    <option class="form-control" value="basic" <?php echo get_selected($project['zmode'], 'basic') ?>>기본사용</option>
                                    <option class="form-control" value="realtime" <?php echo get_selected($project['zmode'], 'realtime') ?>>실시간</option> 
                                    <option class="form-control" value="creat" <?php echo get_selected($project['zmode'], 'creat') ?>>크리에이티브</option>                
                                </select>
                            </div>
                        </div>
 						<div class="col-sm-1">    
                            <div class="form-group">
                                <span class="form-label">풀방시간</span>
                                <input class="form-control" type="text" placeholder="100%시간" name="zmaxtime" value="<?php echo trim($project['zmaxtime']);?>">
                            </div>
                        </div>                       
 						<div class="col-sm-1">     
                            <div class="form-group">
                                <span class="form-label">직접%</span>
                                <input class="form-control" type="text" placeholder="직접%" name="zpercent" value="<?php echo trim($project['zpercent']);?>">
                            </div>
                       </div> 
						<div class="col-sm-1">    
                            <div class="form-group">
                                <span class="form-label">구독</span>
                                <input class="form-control" type="text" placeholder="Z구독" name="zscrapper" value="<?php echo trim($project['zscrapper']);?>">
                            </div>
                        </div>                       
						<div class="col-sm-1">    
                            <div class="form-group">
                                <span class="form-label">좋아요</span>
                                <input class="form-control" type="text" placeholder="Z좋아요" name="zlike" value="<?php echo trim($project['zlike']);?>">
                            </div>
                        </div>
                       <div class="col-sm-1">     
                            <div class="form-group">
                                <span class="form-label">부스터</span>
                                <input class="form-control" type="text" placeholder="부스터" name="booster" value="<?php echo trim($project['booster']);?>">
                            </div>
                        </div>                                                  
                              
                    </div>
                    
                    
                    
                    
                    
                    <div class="row" style="background-color: #d2e5f6;">
                       <div class="col-sm-2">     
                            <div class="form-group">
                            	<?php 	
								//프록시 뷰어													
								$mviews_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_server where q = '{$project['q']}' "));
								$mviews=$mviews_cnt['cnt'];
								$msperms_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_server where q = '{$project['q']}' and message = 'viewstart' "));
								$msperms=$msperms_cnt['cnt'];
								?>
                                <span class="form-label">P뷰어수</span><span class="badge badge-secondary" style="background-color: #337ab7;
    border-color: #2e6da4;"><?php echo $psperms;?></span>/<span class="badge btn-primary"><?php echo $pviews;?></span>
                                <input class="form-control" type="text" placeholder="P뷰어수" name="psperms" value="<?php echo trim($project['psperms']);?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">키워드</font></span>
                                <input class="form-control" type="text" placeholder=",로 구분" name="pkeyword" value="<?php echo trim($project['pkeyword']);?>" >
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <span class="form-label">비율</span>
                                <input class="form-control" type="text" placeholder="확률" name="pkeywordper" value="<?php echo trim($project['pkeywordper']);?>" >
                            </div>
                        </div>                           
                        <div class="col-sm-2">     
                            <div class="form-group">
                                <span class="form-label">시청시간</span>
                                <input class="form-control" type="text" placeholder="시청시간" name="pfireout" value="<?php echo trim($project['pfireout']);?>">
                            </div>
                        </div>                            
						
                        <div class="col-sm-2">    
                            <div class="form-group">
                                <span class="form-label">필터사용</span>
                                <select class="form-control" name="pmode" >
                                    <option class="form-control" value="basic" <?php echo get_selected($project['pmode'], 'basic') ?>>기본사용</option>
                                    <option class="form-control" value="realtime" <?php echo get_selected($project['pmode'], 'realtime') ?>>실시간</option>
                                    <option class="form-control" value="creat" <?php echo get_selected($project['pmode'], 'creat') ?>>크리에이티브</option>                
                                </select>
                            </div>
                        </div>
 						<div class="col-sm-2">    
                            <div class="form-group">
                                <span class="form-label">풀방시간</span>
                                <input class="form-control" type="text" placeholder="P100%시간" name="pmaxtime" value="<?php echo trim($project['pmaxtime']);?>">
                            </div>
                        </div>                       
 						<div class="col-sm-1">     
                            <div class="form-group">
                                <span class="form-label">직접%</span>
                                <input class="form-control" type="text" placeholder="직접%" name="ppercent" value="<?php echo trim($project['ppercent']);?>">
                            </div>
                       </div> 
                       <div class="col-sm-2">     
                            <div class="form-group">
                                <span class="form-label">p랜덤탈출시작</span>
                                <input class="form-control" type="text" placeholder="p랜덤탈출시작시간" name="pbreakin" value="<?php echo trim($project['pbreakin']);?>">
                            </div>
                        </div>
                        <div class="col-sm-2">     
                            <div class="form-group">
                                <span class="form-label">p랜덤탈출비율%</span>
                                <input class="form-control" type="text" placeholder="탈출비율" name="pbreakout" value="<?php echo trim($project['pbreakout']);?>">
                            </div>
                        </div>                                               
						<div class="col-sm-1">    
                            <div class="form-group">
                                <span class="form-label">구독</span>
                                <input class="form-control" type="text" placeholder="P구독" name="pscrapper" value="<?php echo trim($project['pscrapper']);?>">
                            </div>
                        </div>                       
						<div class="col-sm-1">    
                            <div class="form-group">
                                <span class="form-label">좋아요</span>
                                <input class="form-control" type="text" placeholder="P좋아요" name="plike" value="<?php echo trim($project['plike']);?>">
                            </div>
                        </div>
                       <div class="col-sm-1">     
                            <div class="form-group">
                                <span class="form-label">부스터</span>
                                <input class="form-control" type="text" placeholder="부스터" name="pbooster" value="<?php echo trim($project['pbooster']);?>">
                            </div>
                        </div>                                                  
                              
                    </div>
                    <div class="row" style="background-color: #b2f0f5;">
                        <div class="col-sm-8">     
                            <div class="form-group">
                                <span class="form-label">P프록시 할당 컴퓨터 , 로 구분</span>
                                <input class="form-control" type="text" placeholder="직접할당" name="pcontrole" value="<?php echo trim($project['pcontrole']);?>">
                            </div>
                        </div>                   
                     </div>                     
                    
                    
                    <div class="row" style="background-color: #d2e5f6;">
                       <div class="col-sm-2">     
                            <div class="form-group">
                            	<?php 	
								//모바일 뷰어													
								$mviews_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_server_b where q = '{$project['q']}' "));
								$mviews=$mviews_cnt['cnt'];
								$msperms_cnt = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_server_b where q = '{$project['q']}' and message = 'viewstart' "));
								$msperms=$msperms_cnt['cnt'];
								?>
                                <span class="form-label">M뷰어</span><br><span class="badge badge-secondary" style="background-color: #337ab7;
    border-color: #2e6da4;"><?php echo $msperms;?></span>/<span class="badge btn-primary"><?php echo $mviews;?></span>
                                <input class="form-control" type="text" placeholder="M뷰어수" name="msperms" value="<?php echo trim($project['msperms']);?>">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">키워드</span>
                                <input class="form-control" type="text" placeholder=",로 구분" name="mkeyword" value="<?php echo trim($project['mkeyword']);?>" >
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-group">
                                <span class="form-label">비율</span>
                                <input class="form-control" type="text" placeholder="확률" name="mkeywordper" value="<?php echo trim($project['mkeywordper']);?>"  >
                            </div>
                        </div>                           
                        <div class="col-sm-2">     
                            <div class="form-group">
                                <span class="form-label">시청시간</span>
                                <input class="form-control" type="text" placeholder="시청시간" name="mfireout" value="<?php echo trim($project['mfireout']);?>" >
                            </div>
                        </div>                            
						
                        <div class="col-sm-2">    
                            <div class="form-group">
                                <span class="form-label">필터사용</span>
                                <select class="form-control" name="mmode" >                                   
                                    <option class="form-control" value="realtime" <?php echo get_selected($project['mmode'], 'realtime') ?>>실시간</option>
                                    <option class="form-control" value="creat" <?php echo get_selected($project['mmode'], 'creat') ?>>크리에이티브</option>                
                                </select>
                                
                            </div>
                        </div>
 						<div class="col-sm-2">    
                            <div class="form-group">
                                <span class="form-label">풀방시간</span>
                                <input class="form-control" type="text" placeholder="M100%시간" name="mmaxtime" value="<?php echo trim($project['mmaxtime']);?>" >
                            </div>
                        </div>                       
 						<div class="col-sm-1">     
                            <div class="form-group">
                                <span class="form-label">직접%</span>
                                <input class="form-control" type="text" placeholder="직접%" name="mpercent" value="<?php echo trim($project['mpercent']);?>"  readonly="readonly" >
                            </div>
                       </div> 
						
                       <div class="col-sm-1">     
                            <div class="form-group">
                                <span class="form-label">부스터</span>
                                <input class="form-control" type="text" placeholder="부스터" name="mbooster" value="<?php echo trim($project['mbooster']);?>">
                            </div>
                        </div>
                        <div class="col-sm-2">     
                            <div class="form-group">
                                <span class="form-label">랜덤탈출시작</span>
                                <input class="form-control" type="text" placeholder="랜덤탈출시작시간" name="mbreakin" value="<?php echo trim($project['mbreakin']);?>">
                            </div>
                        </div>
                        <div class="col-sm-2">     
                            <div class="form-group">
                                <span class="form-label">기타(여분)</span>
                                <input class="form-control" type="text" placeholder="기타" name="mbreakout" value="<?php echo trim($project['mbreakout']);?>">
                            </div>
                        </div>                                                

                    </div>
                    <div class="row" style="background-color: #a4d78c;">
                        <div class="col-sm-2">
                          
                            <div class="form-group">
                                <span class="form-label">M구독</span>
                                <input class="form-control" type="text" placeholder="M구독" name="mscrapper" value="<?php echo trim($project['mscrapper']);?>">
                            </div>
                        </div>                       
						<div class="col-sm-10">   
                        <span class="form-label">
                                    <?php 
                                    $mscrapcnt_sql = mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_likescrap_b where mscrapper = '{$project['q']}' ");
                                    if( $mscrapcnt_sql)  {
                                        $mscrapcnt = mysqli_fetch_array($mscrapcnt_sql);
                                        if($mscrapcnt) echo "총 : ".$mscrapcnt['cnt']."<br>";
                                    }
                                    ?>
                                </span>                             
                            <div class="form-group">
                            <?php 
                                $mscrap_log = mysqli_query($sub_site['proxy'],"select * from a_likescrap_b
                                 where  mscrapper = '{$project['q']}' 
                                    order by datetime desc" );
                                if($mscrap_log){
                                    for($i=0;$mlrow=mysqli_fetch_array($mscrap_log);$i++){
                                        echo '<span class="btn btn-xs btn-success">';
                                        echo $mlrow['computer']."-".$mlrow['profile'];
                                        echo ' ('.get_date_diff($mlrow['datetime']).')';
                                        echo '</span>';
                                    }

                                }
                                ?>
                              </div>
                        </div>
                                         
                    </div>
                    <div class="row" style="background-color: #d2e666;">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">M좋아요</span>
                                <input class="form-control" type="text" placeholder="M좋아요" name="mlike" value="<?php echo trim($project['mlike']);?>">
                            </div>
                        </div>
                        <div class="col-sm-10"> 
                            <div class="form-group">
                                <span class="form-label">
                                    <?php 
                                    $mlikecnt_sql = mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_likescrap_b where mlike = '{$project['q']}' ");
                                    if( $mlikecnt_sql)  {
                                        $mlikecnt = mysqli_fetch_array($mlikecnt_sql);
                                        if($mlikecnt) echo "총 : ".$mlikecnt['cnt']."<br>";
                                    }
                                    ?>
                                </span>
                                <?php 
                                $mlike_log = mysqli_query($sub_site['proxy'],"select * from a_likescrap_b where mlike = '{$project['q']}' 
                                order by datetime desc ");
                                if($mlike_log){
                                    for($i=0;$mlrow=mysqli_fetch_array($mlike_log);$i++){
                                        echo '<span class="btn btn-xs btn-success">';
                                        echo $mlrow['computer']."-".$mlrow['profile'];
                                        echo ' ('.get_date_diff($mlrow['datetime']).')';
                                        echo '</span>';
                                    }

                                }
                                ?>
                            </div>
                        </div>                           
                    </div>                    
                    <div class="row" style="background-color: #fdd0d8; margin-top::10px;">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <span class="form-label">채팅</span>
                                <select class="form-control" name="msgonoff" >
                                    <option class="form-control" value="" <?php echo get_selected($project['msgonoff'], '') ?>>OFF상태</option>
                                    <option class="form-control" value="on" <?php echo get_selected($project['msgonoff'], 'on') ?>>ON상태</option>
                                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">   
                            <div class="form-group">
                                <span class="form-label">채팅닉(정확하게)</span>
                                <input class="form-control" type="text" placeholder="닉네임" name="mchatnick" value="<?php echo trim($project['mchatnick']);?>">
                            </div>
                        </div>
                        <div class="col-sm-2">      
                            <div class="form-group">
                                <span class="form-label">감지수</span>
                                <input class="form-control" type="text" placeholder="감지수" name="mchatmaster" value="<?php echo trim($project['mchatmaster']);?>">
                            </div>  
                        </div>
                        <div class="col-sm-2">      
                            <div class="form-group">
                                <span class="form-label">채팅수</span>
                                <input class="form-control" type="text" placeholder="채팅수" name="mchatslave" value="<?php echo trim($project['mchatslave']);?>">
                            </div>                                                                                      
                        </div>
                    </div>  
                    <div class="row" style="background-color: #fdd0d8; margin-top::10px;">
                        <div class="col-sm-6">     
                            <div class="form-group">
                                <span class="form-label">감지할 채팅 명령어 ,로 구분</span>
                                <input class="form-control" type="text" placeholder="채팅감지" name="msgset" value="<?php echo trim($project['msgset']);?>"> 
                            </div>
                            <div class="form-group">                                
                                <span class="form-label">채팅마스터</span><br>
                                <?php
                                $sql_mchatmaster =  mysqli_query($sub_site['proxy'],"select * from a_chat_b 
                                where q = '{$project['q']}' and controle = 'master' order by datetime desc ");
                                if($sql_mchatmaster){
                                    for($i=0;$row=mysqli_fetch_array($sql_mchatmaster);$i++){
                                        echo '<span class="btn master-info btn-md btn-success">'.$row['computer']."-".$row['profile']."<br>";
                                        echo '<font class="btn-xs">'.get_date_diff($row['datetime']).' ['.get_date_diff($row['sdatetime']).']</font>';
                                        echo "</span>";
                                    }
                                }
                                ?>
                                 
                            </div>
                            <div class="form-group">                                
                                <span class="form-label">채팅슬레이브</span>
                                <br>
                                <?php
                                $sql_mchatslave =  mysqli_query($sub_site['proxy'],"select * from a_chat_b 
                                where q = '{$project['q']}' and controle = 'slave' order by datetime desc ");
                                if($sql_mchatslave){
                                    for($i=0;$row=mysqli_fetch_array($sql_mchatslave);$i++){
                                        echo '<span class="btn btn-sm slave-info btn-primary" style="margin-right:5px;">'.$row['computer']."-".$row['profile']."<br>";
                                        echo '<font class="btn-xs">'.get_date_diff($row['datetime']).'</font>';
                                        echo "</span>";
                                    }
                                }
                                ?>
                                 
                            </div>                            
                        </div>
                        <div class="col-sm-6">     
                            <div class="form-group">
                                <span class="form-label">보낼 메세지 분류는@ 단어는,로 구분</span>
                                <textarea class="form-control" rows="10" type="text" placeholder="채팅전송" name="msgsend"><?php echo trim($project['msgsend']);?></textarea>      
                            </div>
                        </div>                        
                     </div>  
                    <div class="row" style="background-color: #b2f0f5;">
                        <div class="col-sm-4">     
                            <div class="form-group">
                                <span class="form-label">모바일할당 컴퓨터 , 로 구분</span>
                                <input class="form-control" type="text" placeholder="직접할당" name="mcontrole" value="<?php echo trim($project['mcontrole']);?>">
                            </div>
                        </div>
                        <div class="col-sm-8">     
                            <div class="form-group">
                                <span class="form-label">유튜브채널영상키워드 ,로 구분(적으면 채널영상으로만 작동)</span>
                                <input class="form-control" type="text" placeholder="직접할당" name="mchannelkeyword" value="<?php echo trim($project['mchannelkeyword']);?>">
                            </div>
                        </div>                        
						<div class="col-sm-9">     
                                <div class="form-group">
                                    <span class="form-label">채널url *<font style="color:red;">,로 구별(세부항목 설정은 채널프로젝트설정에서)</font></span>
                                    <textarea class="form-control" rows="8" type="text" placeholder="채널url" name="parkingurl1"><?php echo trim($project['parkingurl1']);?></textarea>
                                </div>
                            </div>
                     </div>                    
<?php } ?>                    
<?php if(!$project_id &&  !$spermtype)   { 
		$project = sql_fetch(" select * from a_serverset where project_id = 'xxx' ");?>    
        	
            <div style="clear:both;"></div>
            
			<div class="booking-form">
                <form name="project-setting" action="./project_form.php" method="post">                                     
                    <div class="row bg-success">
                        <div class="col-sm-12">
                            <h3 id="common-set" class="display-4">서버 공통 셋팅</h5>
                        </div>
                    </div>  
                                        
                    <div class="common-wrap"> 
                         <div class="row bg-danger">
                           <div class="col-sm-2">     
                                <div class="form-group">
                                    <span class="form-label">크롬창수</span>
                                    <input class="form-control" type="text" placeholder="크롬창수" name="browsercnt" value="<?php echo trim($project['browsercnt']);?>">
                                   
                                </div>
                           </div>
                           <div class="col-sm-6">     
                                <div class="form-group">
                                    <span class="form-label">크롬버전</span>
                                    <input class="form-control" type="text" placeholder="크롬버전" name="chromeversion" value="<?php echo trim($project['chromeversion']);?>">
                                   
                                </div>
                           </div>
                           <div class="col-sm-2">     
                                <div class="form-group">
                                    <span class="form-label">zen창수</span>
                                    <input class="form-control" type="text" placeholder="zen창수" name="zbrowsercnt" value="<?php echo trim($project['zbrowsercnt']);?>">
                                   
                                </div>
                           </div>
                           <div class="col-sm-2">     
                                <div class="form-group">
                                    <span class="form-label">모바일창수</span>
                                    <input class="form-control" type="text" placeholder="모바일창수" name="mbrowsercnt" value="<?php echo trim($project['mbrowsercnt']);?>">
                                   
                                </div>
                           </div>                                               
                           <div class="col-sm-2">     
                                <div class="form-group">
                                    <span class="form-label">M시작간격(T>M)</span>
                                    <input class="form-control" type="text" placeholder="M시작간격(T>M)" name="browserinterval" value="<?php echo trim($project['browserinterval']);?>">
                                   
                                </div>
                            </div>
                          <div class="col-sm-2">     
                                <div class="form-group">
                                    <span class="form-label">Z확인간격</span>
                                    <input class="form-control" type="text" placeholder="Z확인간격" name="zbrowserinterval" value="<?php echo trim($project['zbrowserinterval']);?>">
                                   
                                </div>
                            </div>                            

                           <div class="col-sm-2">     
                                <div class="form-group">
                                    <span class="form-label">P주차시간</span>
                                    <input class="form-control" type="text" placeholder="P주차시간" name="parkingtime" value="<?php echo trim($project['parkingtime']);?>">
                                   
                                </div>
                            </div>    
                           <div class="col-sm-2">     
                                <div class="form-group">
                                    <span class="form-label">Z주차시간</span>
                                    <input class="form-control" type="text" placeholder="Z주차시간" name="zparkingtime" value="<?php echo trim($project['zparkingtime']);?>">
                                   
                                </div>
                            </div>        
                                                                               
                        </div>
                        
                        <div class="row bg-danger">
                           <div class="col-sm-3">     
                                <div class="form-group">
                                    <span class="form-label">1줄,2줄 개수*<font style="color:red;">,구분</font></span>
                                    <input class="form-control" type="text" placeholder="줄당 갯수" name="linecnt" value="<?php echo trim($project['linecnt']);?>">
                                   
                                </div>
                            </div>
                            <div class="col-sm-2">     
                                <div class="form-group">
                                    <span class="form-label">뷰할당%</span>
                                    <input class="form-control" type="text" placeholder="숫자적은뷰우선할당%" name="viewpercent" value="<?php echo trim($project['viewpercent']);?>">
                                   
                                </div>
                            </div>
                            <div class="col-sm-2">     
                                <div class="form-group">
                                    <span class="form-label">크레이오토</span>
                                    <input class="form-control" type="text" placeholder="입력시 초후부터 실시간" name="creative" value="<?php echo trim($project['creative']);?>">
                                   
                                </div>
                            </div>                                 
                            <div class="col-sm-2">    
                                <div class="form-group">
                                    <span class="form-label">Z시간간격(1>2) *<font style="color:red;">10이상</font></span>
                                    <input class="form-control" type="text" placeholder="Z시간간격(1>2)" name="intervals" value="<?php echo trim($project['intervals']);?>">
                                </div>
                            </div>
                            <div class="col-sm-3">     
                                <div class="form-group">
                                    <span class="form-label">P시작간격(1>35) *<font style="color:red;">1이상</font></span>
                                    <input class="form-control" type="text" placeholder="P시작간격(1>35)"  name="delaytime" value="<?php echo trim($project['delaytime']);?>">
                                </div>
                            </div>
                       
                        </div>                   
                           
                        <div class="row bg-danger">
                           <div class="col-sm-3">     
                                <div class="form-group">
                                    <span class="form-label">가로 사이즈</span>
                                    <input class="form-control" type="text" placeholder="가로사이즈" name="width" value="<?php echo trim($project['width']);?>">
                                   
                                </div>
                            </div>
                            <div class="col-sm-3">     
                                <div class="form-group">
                                    <span class="form-label">세로 사이즈</span>
                                    <input class="form-control" type="text" placeholder="세로사이즈" name="height" value="<?php echo trim($project['height']);?>"> 
                                </div>
                            </div>
                            <div class="col-sm-3">    
                                <div class="form-group">
                                    <span class="form-label">가로 간격</span>
                                    <input class="form-control" type="text" placeholder="가로간격" name="wposition" value="<?php echo trim($project['wposition']);?>"> 
                                </div>
                            </div>
                            <div class="col-sm-3">     
                                  <div class="form-group">
                                    <span class="form-label">세로 간격</span>
                                    <input class="form-control" type="text" placeholder="세로간격" name="hposition" value="<?php echo trim($project['hposition']);?>"> 
                                </div>
                            </div>
                        </div>
                        <div class="row bg-danger">
                            <div class="col-sm-9">     
                                <div class="form-group">
                                    <span class="form-label">여유url *<font style="color:red;">,로 구별</font></span>
                                    <textarea id="parktextarea" class="form-control" rows="8" type="text" placeholder="여유url" name="parkingurl"><?php echo trim($project['parkingurl']);?></textarea>
                                </div>
                            </div>
                            <div class="col-sm-3">     
                                <div class="form-group">
                                    <span id="daumnews" class="btn btn-info btn-lg" onclick="get_today_news('daumnews');">다음+네이버 뉴스 수집</span>
                                    
                                </div>
                            </div>
                            <div class="col-sm-3">     
                                <div class="form-group">
                                    <span class="form-label">프록시 몇주차</span>
                                <input class="form-control" type="text" placeholder="몇주차(숫자만)" name="weekpath" value="<?php echo trim($project['weekpath']);?>"> 
                                    
                                </div>
                            </div>                                                           
    
                        </div>
                        
                        <div class="row bg-danger">
                           <div class="col-sm-2">     
                                <div class="form-group">
                                    <span class="form-label">구글계정화면대기시간</span>
                                    <input class="form-control" type="text" placeholder="구글계정화면대기시간" name="zgtime" value="<?php echo trim($project['zgtime']);?>">
                                   
                                </div>
                            </div>
                           <div class="col-sm-2">     
                                <div class="form-group">
                                    <span class="form-label">팀변경시간</span>
                                    <input class="form-control" type="text" placeholder="팀변경시간" name="setdatetime" value="<?php echo trim($project['setdatetime']);?>">
                                   
                                </div>
                            </div>                            
                        
                            <div class="col-sm-1">    
                                <div class="form-group">
                                <button class="submit-btn btn-success">설정</button>
                                </div>
                            </div>
                            <div class="col-sm-2">    
                                <div class="form-group">
                                <a id="gid-excel" class="btn btn-lg btn-info">아이디/비번/엑셀</a>
                                </div>
                            </div>
                        </div>                        

                    </div>
<?php } ?>
                 
				</form>
<?php if(!$project_id &&  !$spermtype ) { //계정목록 우선 가려놓기 (나중에 사용하게 되면 열기)?>    
	
 
                <div id="input-excel" class="row bg-warning">
	                <form name="fitemexcelup" id="fitemexcelup" method="POST" action="./excel_up.php" enctype="MULTIPART/FORM-DATA" autocomplete="off">                        
                        <div class="col-sm-6" > 
                          <span style="color:red;">** 현재 엑셀 파일을 다운받은 후 계정을 추가한후 다른이름저장으로  csv로 변환해서 업로드 해줘야합니다.<br>
                                첫줄에 아이디,비번,복구메일 이라고 한글로 적어야합니다.        </span><br>
                            <a href="./zen.xls">참고 엑셀 다운로드 <span class="btn btn-md btn-info">Click</span></a><br>
                            <a href="./zen.csv">참고 CSV 다운로드 <span class="btn btn-md btn-info">Click</span></a><br>
                            <!--<a href="./com.xls">컴퓨터지정 계정추가 다운로드 <span class="btn btn-md btn-info">Click</span></a>-->
                        </div>
                        <div class="col-sm-3">
                            <div id="excelfile_upload" class="form-group">                                
                                <select name="type" id="type">
                                	<option value="">선택</option>                                    
                                    <option value="zenpc">젠피씨CSV</option>
									<option value="proxy">프록시CSV</option>
                                </select>
                                <input type="hidden" name="ex_type" id="ex_type" value="2">
                                <!--<select name="ex_type" id="ex_type">
                                	<option value="">선택</option> 
                                    <option value="2">새계정추가</option>                                      
                                    <option value="1">컴퓨터지정추가</option>                                   
                                </select>-->
                                <input type="file" name="excelfile" id="excelfile">                               
                            </div>
                        </div>
                        <div class="col-sm-3">     
                            <div class="form-group btn_confirm01 btn_confirm">
                                <input type="submit" value="엑셀을 csv로 전환해서  등록" class="btn_submit">
                                <a href="<?php echo G5_URL?>/server/excel.php" class="btn_submit" style="color:#FFF; text-decoration:none;">계정목록게시판</a>
                            </div>
                        </div>           
	                </form> 
                </div>                        



			</div>
        </div>
        
        
        
        
 
        <div class="row">
        <?php 
        if (1 == false) { //계정목록 우선 가려놓기 (나중에 사용하게 되면 열기)  
		$tmp_clear_bar = '';
        $sql = mysqli_query($sub_site['zenpc']," select * from a_gidpwrepair where status = 'clear' order by status DESC , proxydatetime DESC");
        for($i=0;$row=mysqli_fetch_array($sql);$i++){
			 //$sql_t = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select * from a_server where proxy like '%x{$row['proxy']}' order by log_time asc limit 0, 1"));
			
        ?>
            <div class="col-sm-3 gidpw-info <?php echo $row['status'];?>">
            	<?php if($row['status']=='act') {?><span id="status-<?php echo $i;?>" class="btn btn-xs btn-primary">사용중</span>
				<?php } else if($row['status']=='clear') {?><span id="status-<?php echo $i;?>" class="btn btn-xs btn-primary">젠PC창고</span>
				<?php } else { ?><span id="status-<?php echo $i;?>" class="btn btn-xs btn-primary">미사용</span><?php } ?>
                <a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $row['proxy'];?> <?php echo $row['gid'];?> <?php echo $row['pwd'];?> <?php echo $row['repair'];?>');">복사</a>
                <span id="proxy-<?php echo $i;?>" class="proxy"><?php echo $row['proxy'];?> <?php echo $sql_t['computer'];?> <?php echo $sql_t['profile'];?></span>
                <br />
                <span id="gid-<?php echo $i;?>" class="gid"><?php echo $row['gid'];?></span><br />
                <span id="pwd-<?php echo $i;?>" class="pwd"><?php echo $row['pwd'];?></span><br />
                <span id="repair-<?php echo $i;?>" class="repair"><?php echo $row['repair'];?></span>
                <br />
                <?php if($row['proxydatetime']) {?><span id="pdate-<?php echo $i;?>" class="pdate btn-xs btn-success">등록 : <?php echo get_date_diff($row['proxydatetime']);?></span><?php } ?>              
                <span id="id-memo-<?php echo $i;?>" class="idmemo"><?php echo $row['memo'];?></span>
            </div>
		<?php if($row['status'] != $tmp_clear_bar && $tmp_clear_bar) {?>
        	</div><div class="row">
        <?php 
			  $tmp_clear_bar = $row['status'];
		}?>
        <?php } ?>
        </div>        
         <div class="row">
        <?php 
		
		$tmp_clear_bar = '';
        $sql = mysqli_query($sub_site['proxy']," select * from a_gidpwrepair where status = 'clear' order by status DESC , proxydatetime DESC");
        for($i=0;$row=mysqli_fetch_array($sql);$i++){
			 //$sql_t = sql_fetch(" select * from a_server where proxy like '%x{$row['proxy']}' order by log_time asc limit 0, 1");
			
        ?>
            <div class="col-sm-3 gidpw-info <?php echo $row['status'];?>">
            	<?php if($row['status']=='act') {?><span id="status-<?php echo $i;?>" class="btn btn-xs btn-primary">사용중</span>
				<?php } else if($row['status']=='clear') {?><span id="status-<?php echo $i;?>" class="btn btn-xs btn-primary">프록시창고</span>
				<?php } else { ?><span id="status-<?php echo $i;?>" class="btn btn-xs btn-primary">미사용</span><?php } ?>
                <a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $row['proxy'];?> <?php echo $row['gid'];?> <?php echo $row['pwd'];?> <?php echo $row['repair'];?>');">복사</a>
                <span id="proxy-<?php echo $i;?>" class="proxy"><?php echo $row['proxy'];?> <?php echo $sql_t['computer'];?> <?php  echo $sql_t['profile'];?></span>
                <br />
                <span id="gid-<?php echo $i;?>" class="gid"><?php echo $row['gid'];?></span><br />
                <span id="pwd-<?php echo $i;?>" class="pwd"><?php echo $row['pwd'];?></span><br />
                <span id="repair-<?php echo $i;?>" class="repair"><?php echo $row['repair'];?></span>
                <br />
                <?php if($row['proxydatetime']) {?><span id="pdate-<?php echo $i;?>" class="pdate btn-xs btn-success">등록 : <?php echo get_date_diff($row['proxydatetime']);?></span><?php } ?>              
                <span id="id-memo-<?php echo $i;?>" class="idmemo"><?php echo $row['memo'];?></span>
            </div>
		<?php if($row['status'] != $tmp_clear_bar && $tmp_clear_bar) {?>
        	</div><div class="row">
        <?php 
			  $tmp_clear_bar = $row['status'];
		}?>
        <?php }
		 ?>
        </div>           
        
   <?php } //if( 1 == false) { //계정목록 우선 가려놓기 (나중에 사용하게 되면 열기)
    } 
    ?>     
        
        
    </div>    
</div>

<script>
function copyToClipboard(text) {
    var sampleTextarea = document.createElement("textarea");
    document.body.appendChild(sampleTextarea);
    sampleTextarea.value = text; //save main text in it
    sampleTextarea.select(); //select textarea contenrs
    document.execCommand("copy");
    document.body.removeChild(sampleTextarea);
}
var q_cnt = 0;
function optimizer_sperms(q){
	$.ajax({
		url: "<?php echo G5_URL;?>/server/test_api.php",
		type: 'GET',
        data: {
     		'optimizer': q
        },
        dataType: 'html',
        async: false,
        success: function(data, textStatus) {
	        if (data.error) {
	            alert(data.error);
	                return false;
	        } else {
				$('#optimizer').css('background-color','#d50aa0');				
			}
		}
	});	
}

function get_now_act(q){
	$.ajax({
		url: "<?php echo G5_URL;?>/server/test_api.php",
		type: 'POST',
        data: {
     		'q': q
        },
        dataType: 'json',
        async: false,
        success: function(data, textStatus) {
	        if (data.error) {
	            alert(data.error);
	                return false;
	        } else {
				$('#tube_value').html(data);
				q_cnt = trim(data);
			}
		}
	});	
}

function get_today_news(site){
	$.ajax({
		url: "<?php echo G5_URL;?>/server/test_api.php",
		type: 'GET',
        data: {
     		'todaynews': site
        },
        dataType: 'html',
        async: false,
        success: function(data, textStatus) {
	        if (data.error) {
	            alert(data.error);
	                return false;
	        } else {
               
				//$('#'+site).css("background-color","red");	
                if(site != "on"){
                    console.log(data);
                    $('#parktextarea').val(data);
                }
               // window.location.reload();
			}
		}
	});	
}


function fcheck(fid,t){
   if(confirm(fid+" 뷰어필터정보를 초기화 하시겠습니까?")){
	$.ajax({
		url: "<?php echo G5_URL;?>/server/filter_reset.php",
		type: 'POST',
        data: {
     		'fid': fid, 
            't':t,
        },
        dataType: 'json',
        async: false,
        success: function(data) {
            console.log(data);
            alert("success");
            if(t=='filter'){
                if(fid=="all" && data.status=="all") {
                    $('.info-idrs').remove();
                } else $('#'+fid).remove();
            } else if (t=='net'){
                if(fid=="all" && data.status=="all") {
                    $('.info-nidrs').remove();
                } else $('#n'+fid).remove();                
            }
		}
	});	    
   } else {
   }

 
}



function fpcheck(fid,t){
   if(confirm(fid+" 뷰어(p)) 필터정보를 초기화 하시겠습니까?")){
	$.ajax({
		url: "<?php echo G5_URL;?>/server/filter_preset.php",
		type: 'POST',
        data: {
     		'fid': fid, 
            't':t,
        },
        dataType: 'json',
        async: false,
        success: function(data) {
            console.log(data);
            alert("success");
            if(t=='filter'){
                if(fid=="all" && data.status=="all") {
                    $('.info-pidrs').remove();
                } else $('#'+fid).remove();
            } else if (t=='net'){
                if(fid=="all" && data.status=="all") {
                    $('.info-pnidrs').remove();
                } else $('#n'+fid).remove();                
            }
		}
	});	    
   } else {
   }

 
}



function computer_reset(computer,type){
   if(confirm(computer+" 컴퓨터의 뷰어정보를 초기화 하시겠습니까?")){
	$.ajax({
		url: "<?php echo G5_URL;?>/server/computer_reset.php",
		type: 'POST',
        data: {
     		'computer': computer,
            'type': type,
        },
        dataType: 'json',
        async: false,
        success: function(data) {
            alert("success");

	        location.reload();
		}
	});	    
   } else {
   }

 
}

function computer_log(computer,type){   
	$.ajax({
		url: "<?php echo G5_URL;?>/server/computer_log_view.php",
		type: 'POST',
        data: {
     		'computer': computer,
            'type': type,
        },
        dataType: 'html',
        async: false,
        success: function(result) {
            $('#computer_log_view').html(result);
		}
	});	    
}
</script>


</div>
<div class="goto_top">
<a href="#booking" class="btn btn-lg btn-default">위로</a>
<a href="#gotobottom" class="btn btn-lg btn-default">아래로</a>
</div>
</div>
<div id="gotobottom"></div>
<?

include_once(G5_PATH.'/tail.sub.php');
?>