<?php

//https://pwa.pe.kr/chat/?mode=master&com=k001&usr=1&sm=aaa1&q=wX6mzdcHZkQ//
$mode = $com = $usr = $q = $sm = '';
  if(isset($_GET['mode']) && $_GET['mode']) $mode =  trim(strip_tags($_GET['mode']));
  if(isset($_GET['com']) && $_GET['com']) $com =  trim(strip_tags($_GET['com']));
  if(isset($_GET['usr']) && $_GET['usr']) $usr =  trim(strip_tags($_GET['usr']));
  if(isset($_GET['q']) && $_GET['q']) $q =  trim(strip_tags($_GET['q']));
  if(isset($_GET['sm']) && $_GET['sm']) $sm =  trim(strip_tags($_GET['sm']));else $sm = '';
  
  set_time_limit(3);

  $logvar = array();

  if(!$mode || !$com || !$usr || !$q) {
    $logvar['controle'] = 'break';
    header('Content-type: application/json');
    echo json_encode($logvar);	 
    exit;
  }

  include_once('../sub_site.php');

  $datetime = date("Y-m-d H:i:s");

  
  $logvar['controle'] = $logvar['tomsg'] =  $logvar['nick'] = '';
  ### 활동이 3분 지난 채팅마스터/슬레이브는 디비삭제
  @mysqli_query($sub_site['proxy'],"delete from a_chat_b where datetime + interval 90 second < now()");
  //감지된 명령은 10초 지나면 지워버린다.. 
  @mysqli_query($sub_site['proxy']," update a_chat_b set tomsg = '' where controle = 'master' and 
          checkdatetime  + interval 4 second < now()");

  ##### 랜덤하게 1시간에서 2시간 사이에 master 감지를 교체해준다.. 
  if ($mode=="master" && !$sm) { 
    $rand_master_break = rand(3600,7200);
    @mysqli_query($sub_site['proxy'],"delete from a_chat_b where controle = 'master' and sdatetime + interval {$rand_master_break} second < now()");
  }
  # 존재하지 않는 채팅마스터/슬레이브는 브레이크로 빠져나오게 한다.
  $check_chat_sql = mysqli_query($sub_site['proxy'],"select * from a_chat_b where computer ='{$com}' AND profile = '{$usr}' "); 
  if($check_chat_sql) {
    $check_chat =  mysqli_fetch_array($check_chat_sql);
    if(!$check_chat ){     
      $logvar['controle'] = 'break';
      header('Content-type: application/json');
      echo json_encode($logvar);
      exit;
    } 
  }

  //현재 프로젝트이면서 채팅이 on 
  $check_q_sql = mysqli_query($sub_site['proxy'],"select * from a_serverset where q = '{$q}' and status = 'healthy' and msgonoff = 'on' ");  
  if ($check_q_sql){
    $check_q = mysqli_fetch_array($check_q_sql);
    #$logvar['mchatmaster'] = $check_q['mchatmaster'] ;
    #$logvar['mchatslave'] = $check_q['mchatslave'] ;
    $logvar['setmsg'] = explode(",",$check_q['msgset']);	
    $logvar['nick'] = $check_q['mchatnick']; 


    $to_msg_sql = '';

    if ( $check_q['msgonoff']=="on" ) {  

      if ($mode=="master" && !$sm)  $to_msg_sql  = " , tomsg = '' ";
      else  if ($mode=="master" && $sm)   {
        $exists_sql = mysqli_query($sub_site['proxy'],"select * from a_chat_b where q = '{$q}'and controle = 'master' 
        and checkdatetime  + interval 60 second > now() ");
        if($exists_sql) {
          $exists_check = mysqli_fetch_array($exists_sql);
          if($exists_check) $sm = '';      
        } 
      }

      mysqli_query($sub_site['proxy']," update a_chat_b set 
      datetime = now() , checkmsg = '{$sm}' , setmsg = '{$check_q['msgset']}' {$to_msg_sql}
      where computer ='{$com}' AND profile = '{$usr}' ");// 
		  	
      if($mode=="master" ){            
        $logvar['controle'] = "master";           
        $logvar['sm'] = $sm;   
        $logvar['tomsg'] = '';
        $to_msg_sql = '';
        if($sm){
          $setmsg =  explode(",",$check_q['msgset']);
          $tmp_tomsgr = explode("@",$check_q['msgsend']);		
          for($i=0;$i<count($tmp_tomsgr);$i++){	
            if ($sm ==  $setmsg[$i]){
              $tmp_tomsg = explode(",",$tmp_tomsgr[$i]);						                      
              $logvar['tomsg'] = $tmp_tomsgr[$i];
              $to_msg_sql = " , tomsg = '{$tmp_tomsgr[$i]}' ";            
              mysqli_query($sub_site['proxy']," update a_chat_b set  tomsg = '{$tmp_tomsgr[$i]}' , checkdatetime = now() 
                where controle = 'master' and computer ='{$com}' AND profile = '{$usr}' 
                AND checkdatetime  + interval 120 second < now() ");//       감지는 80초 지나야 다시 감지체크가 된다.      
            }
          }
        }
        

      } else if ($mode=="slave" ){
        $logvar['controle'] = "slave";  
        
        $logvar['sendmsg'] = '';  
       
          $chat_sql = mysqli_query($sub_site['proxy'],"select * from a_chat_b 
          where q = '{$q}' and checkmsg <> '' and controle = 'master'  and tomsg <> '' 
          and checkdatetime + interval 5 second > now() 
          order by checkdatetime asc ");     //datetime   asc     
          if($chat_sql) {           //감지된 명령어가 있다면 
            $chat_f = mysqli_fetch_array($chat_sql);
            if ($chat_f){               
              $tmp_tomsg = explode(",",$chat_f['tomsg']);
              $tmp_rand_tomsg = array_rand($tmp_tomsg);           
              $logvar['sendmsg'] = $tmp_tomsg[$tmp_rand_tomsg];				
              unset($tmp_tomsg[$tmp_rand_tomsg]);
              $tmp_save_tomsg = implode(",",$tmp_tomsg);            
              mysqli_query($sub_site['proxy']," update a_chat_b set tomsg = '{$tmp_save_tomsg}' where id = '{$chat_f['id']}' ");
              mysqli_query($sub_site['proxy']," update a_chat_b set senddatetime = now() where computer ='{$com}' AND profile = '{$usr}' ");//            
            }                
          } 
        
      } else if ($mode == 'break') $logvar['controle'] = 'break';
    } else  $logvar['controle'] = 'break';
  }  else $logvar['controle'] = 'break';


  
header('Content-type: application/json');
echo json_encode($logvar);	  
exit;
?>  