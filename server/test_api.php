<?php
include_once('./_common.php');
include_once('./sub_site.php');
//error_reporting(E_ALL);
//ini_set("display_errors",1);

if(isset($_POST['q']) && $_POST['q']) $q = trim($_POST['q']);

if(isset($_GET['chkttl']) && $_GET['chkttl']) $chkttl = strip_tags(clean_xss_attributes($_GET['chkttl']));

if(isset($_GET['todaynews']) && $_GET['todaynews']) $todaynews = strip_tags(clean_xss_attributes($_GET['todaynews']));
if(isset($_GET['set']) && $_GET['set']) $set = strip_tags(clean_xss_attributes($_GET['set']));

if(isset($_GET['optimizer']) && $_GET['optimizer']) $optimizer = trim($_GET['optimizer']);


if($chkttl == "on"){
   //https://pwa.pe.kr/server/test_api.php?chkttl=on&q=@https://youtu.be/DKxNly5hI_8@4%ED%8C%80s
   
   $request = array();
   $datetime = date("Y-m-d H:i:s");
   if($q) {
     $que = find_mb_nick($q,1);
      $q_mb_nick = find_mb_nick($q,2);
      $q_mb_key = find_mb_nick($q,3);      
      $youtubecontent = curl_get_content("https://www.youtube.com/watch?v=".$que);      
      
      //echo $youtubecontent;
      //<meta name="twitter:title" content="[파워볼 실시간] 파워볼 파이리 동행 스피드키노 혁명의 시작 모두 입장 하세요">
      preg_match_all('/<meta name="twitter:title" content="([^"]+)">/is', $youtubecontent,$get_youtube);//watch?v=p8VYPqnih5U
      


      //$get_youtube[1][0] = addslashes($get_youtube[1][0]);
      //echo "update a_serverset set project_title = '{$get_youtube[1][0]}' where project_name = '{$q_mb_nick}' ";
      sql_query("update a_serverset set project_title = '{$get_youtube[1][0]}' where project_name = '{$q_mb_nick}' ");
      
         //}
      
   }
   #header('Content-type: application/json');
   #echo json_encode($request,true);
   exit;
   
} else if($todaynews == "on" || $todaynews == "daumnews") {
   //https://pwa.pe.kr/server/test_api.php?todaynews=on

   if($todaynews=="on") {   
      $parkset = sql_fetch(" select * from a_serverset where parksettime + interval 6 hour < now() and project_id = 'xxx' ");
      if(!$parkset) {
         echo '..';
         exit;         
      }
   }
   

   $request = array();
   $datetime = date("Y-m-d H:i:s");
   $todaycontent = curl_get_content("https://news.daum.net");      
      //echo $youtubecontent;
      //<meta name="twitter:title" content="[파워볼 실시간] 파워볼 파이리 동행 스피드키노 혁명의 시작 모두 입장 하세요">
   preg_match_all('/(https:\/\/v.daum.net\/v\/[0-9]+)/is', $todaycontent,$get_today);//watch?v=p8VYPqnih5U      
      
   

   
   $todaycontent = curl_get_content("https://news.naver.com/main/main.naver?mode=LSD&mid=shm&sid1=102");      
      //echo $youtubecontent;
      //<meta name="twitter:title" content="[파워볼 실시간] 파워볼 파이리 동행 스피드키노 혁명의 시작 모두 입장 하세요">   
   preg_match_all('/(https:\/\/n.news.naver.com\/mnews\/article\/[^"]+)/is', $todaycontent,$get_today2);//watch?v=p8VYPqnih5U      
      
   $array_news = array();
   $array_news  = array_merge($get_today[1],$get_today2[1])  ;
   $get_today_content = implode("\n",$array_news);

   sql_query("update a_serverset set parkingurl = '{$get_today_content}' ,  parksettime = now()  ");   
   print($get_today_content);
   #header('Content-type: application/json');
   #echo json_encode($request,true);
   exit;


} else if($optimizer){
   mysqli_query($sub_site ['proxy'],"update `a_server_b` set q = '1111' WHERE `q` LIKE '{$optimizer}' AND ( `message` LIKE 'seterror' or `message` LIKE 'critical' ) ");

}else {


   if($q){
      if(strpos($q,"@") !==false) {
         $youtubecontent = curl_get_content("https://www.youtube.com/".$q."/videos");
         echo $youtubecontent;
         preg_match_all('/<meta itemprop="interactionCount" content="([0-9]+)">/is', $youtubecontent,$get_youtube);//watch?v=p8VYPqnih5U

      }
   
      else {
         $youtubecontent = curl_get_content("https://www.youtube.com/watch?v=".$q);
      
      
      //echo $youtubecontent;
      //<meta name="twitter:title" content="[파워볼 실시간] 파워볼 파이리 동행 스피드키노 혁명의 시작 모두 입장 하세요">
      preg_match_all('/<meta itemprop="interactionCount" content="([0-9]+)">/is', $youtubecontent,$get_youtube);//watch?v=p8VYPqnih5U
      preg_match_all('/<link itemprop="name" content="([^"]+)">/is',$youtubecontent,$get_youtubenick);//watch?v=p8VYPqnih5U
      //<link itemprop="name" content="파워볼 감자514">
      echo "---".$get_youtubenick[1][0];
      echo $get_youtube[1][0];
      
      //	$youtube_link = "https://youtube.com".$get_youtube[1][0];
      //	$first_youtube = "https://youtube.com/embed".str_replace("watch?v=","",$get_youtube[1][0])."?autohide=1&vq=hd720&wmode=opaque";
      }
   }
}
//echo $rrr['parkingurl'];

function find_mb_nick($q,$b){
	$tmp_q = explode("@",$q);	
	if($b=='1') {
      $tmp_q['1'] = str_replace("https://youtu.be/","",$tmp_q['1']);
		$tmp_q['1'] = str_replace("?feature=share","",$tmp_q['1']);
		$tmp_q['1'] = str_replace("https://www.youtube.com/watch?v=","",$tmp_q['1']);
      $tmp_q['1'] = str_replace("https://youtube.com/watch?v=","",$tmp_q['1']);
		$tmp_q['1'] = str_replace("https://www.youtube.com/live/","",$tmp_q['1']);
      $tmp_q['1'] = str_replace("https://youtube.com/live/","",$tmp_q['1']);
		return $tmp_q['1'];
	}
	else if($b=='2') return $tmp_q['2'];
	else if($b=='3') return $tmp_q['3'];
	else return false;	
}

function curl_get_content($url) {
	//$filename = 'c:/AutoSet9/public_html/pas/cookie.txt';
$ch = curl_init(); //curl 로딩
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($ch, CURLOPT_RANGE, '0-100');
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   $content = curl_exec($ch);
   curl_close($ch);
   return $content;
}

?>