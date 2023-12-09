<?php
include_once('./_common.php');

error_reporting(E_ALL);
ini_set("display_errors",1);


$gidcheck = '';

//http://3.34.42.218/server/zenpc.php?ip=00x124.5.185.244&com=PC-yRVtpHrl&usr=00
if(isset($_POST['gidcheck']) && $_POST['gidcheck']) $gidcheck =  trim(strip_tags(clean_xss_attributes($_POST['gidcheck'])));
if(isset($_POST['proxy']) && $_POST['proxy']) $proxy = trim(strip_tags(clean_xss_attributes($_POST['proxy'])));
if(isset($_POST['gid']) && $_POST['gid']) $gid = trim(strip_tags(clean_xss_attributes($_POST['gid'])));
if(isset($_POST['pwd']) && $_POST['pwd']) $pwd = trim(strip_tags(clean_xss_attributes($_POST['pwd'])));
if(isset($_POST['repair']) && $_POST['repair']) $repair = trim(strip_tags(clean_xss_attributes($_POST['repair'])));
if(isset($_POST['id-memo']) && $_POST['id-memo']) $idmemo = trim(strip_tags(clean_xss_attributes($_POST['id-memo'])));

if(isset($_POST['com']) && $_POST['com']) $com = trim(strip_tags(clean_xss_attributes($_POST['com'])));
if(isset($_POST['usr']) && $_POST['usr']) $usr = trim(strip_tags(clean_xss_attributes($_POST['usr'])));
if($gidcheck == 'g' && $gid && $proxy ) {
			$gdatetime = date("Y-m-d H:i:s");
			sql_query(" update a_gidpwrepair set proxy = '{$proxy}' , proxydatetime = '{$gdatetime}' , status='act' , type = '' where gid = '{$gid}'  ");
			sql_query(" update a_server set gid = '{$gid}' , pwd = '{$pwd}' , repair = '{$repair}'  where proxy = '{$proxy}' ");
			alert("구글게정이 등록 되었습니다",G5_URL."/server/proxy.php?type=&ip=".$proxy."&com=".$com."&usr=".$usr);			
} else {
	unset($gid);
	unset($proxy);
	unset($gidcheck);
	unset($com);
	unset($usr);
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

$set = '';
$proxy = '';
//http://3.34.42.218/server/zenpc.php?ip=00x124.5.185.244&com=PC-yRVtpHrl&usr=00
if(isset($_GET['ip']) && $_GET['ip']) $proxy =  trim(strip_tags(clean_xss_attributes($_GET['ip'])));
if(isset($_GET['com']) && $_GET['com']) $computer = trim(strip_tags(clean_xss_attributes($_GET['com'])));
if(isset($_GET['usr']) && $_GET['usr']) $profile = trim(strip_tags(clean_xss_attributes($_GET['usr'])));
if(isset($_GET['set']) && $_GET['set']) $set = trim(strip_tags(clean_xss_attributes($_GET['set'])));
if(isset($_GET['msg']) && $_GET['msg']) $message = trim(strip_tags(clean_xss_attributes($_GET['msg'])));

if(isset($_GET['mode']) && $_GET['mode']) $mode =  trim(strip_tags(clean_xss_attributes($_GET['mode'])));

if(!$proxy) $proxy = $_SERVER['REMOTE_ADDR'];


$proxydatetime = date("Y-m-d H:i:s");
$gid = '';
$pwd = '';
$rid = '';
$memo = '';
$pdatetime =  '';
$udatetime =  '';
$scrapstatus = '';
$loginstatus = '';

if($set == "clear" ) {
	//echo " update a_gidpwrepair set proxy = '' , exid = '{$real_proxy}' , proxydatetime = '{$proxydatetime}' , status = 'clear' where proxy = '{$real_proxy}'  ";
	//echo "<br>";
	//echo " update a_server set gid = '' , pwd = '' , rid = '' where proxy like '%x{$real_proxy}' ";
	sql_query(" update a_gidpwrepair set proxy = '' , exid = '{$proxy}' , proxydatetime = '{$proxydatetime}' , status = 'clear', type='' where proxy = '{$proxy}'  ");
	
	sql_query(" update a_server set gid = '' , pwd = '' , repair = '' where proxy = '{$proxy}' ");
	alert("초기화 되었습니다",G5_URL."/server/proxy.php?type=ip=".$proxy);
}
if( $set == "publish" ) {//구글아이디비번 새로발급
	$old_res = sql_fetch(" select * from a_gidpwrepair where proxy = '{$proxy}' limit 0 , 1 " );
	if(!$old_res){
		$new_res = sql_fetch(" select * from a_gidpwrepair where proxy = '' and exid <> '{$proxy}'  and status = '' order by rand() limit 0,1 ");
		if($new_res['gid'] && $new_res['pwd'] ) {
			$gid = $new_res['gid'];
			$pwd = $new_res['pwd'];
			$rid = $new_res['repair'];
			$memo = $new_res['memo'];
			$pdatetime =  $new_res['proxydatetime'];
			$udatetime =  $new_res['updatetime'];		
			
			sql_query(" update a_gidpwrepair set proxy = '{$proxy}' , proxydatetime = '{$proxydatetime}' , status='act' , type = '' where id = '{$new_res['id']}'  ");
			sql_query(" update a_server set gid = '{$gid}' , pwd = '{$pwd}' , repair = '{$rid}'  where proxy = '{$proxy}' ");
			alert("발급 되었습니다",G5_URL."/server/proxy.php?type=&ip=".$proxy);		
		}
	}
	else alert("발급실패.(계정에 여유가 있는지 확인해보세요)",G5_URL."/server/proxy.php?type=ip=".$proxy);		
}


$old_res = sql_fetch(" select * from a_gidpwrepair where proxy = '{$proxy}' " );

if($old_res['gid'] && $old_res['pwd']  ) {
	$gid = $old_res['gid'];
	$pwd = $old_res['pwd'];
	$rid = $old_res['repair'];
//	$memo_t = sql_fetch( " select * from a_gidpwrepair where proxy = '{$real_proxy}' ");
	$memo = $old_res['memo'];
	$pdatetime =  $old_res['proxydatetime'];
	$udatetime =  $old_res['updatetime'];
	$title_h1 = "발급정보";
	$scrapstatus = '';//$old_res['scrapstatus'];
	$loginstatus = '';//$old_res['loginstatus'];
	$datetime = date("Y-m-d H:i:s");

	sql_query(" update a_server set gid = '{$gid}'  , pwd = '{$pwd}' , repair = '{$rid}'  where proxy = '{$proxy}' ");
	sql_query(" update a_gidpwrepair set proxydatetime = '{$datetime}'  where proxy = '{$proxy}' ");
} 



//data 정리

$gid = trim($gid);
$pwd = trim($pwd);
$rid = trim($rid);



if($set =="json"){
	$var['gid'] = $gid;
	$var['pwd'] = $pwd;
	$var['rid'] = $rid;
	$gtime_t = sql_fetch ( " select zgtime from a_serverset where project_id = 'xxx' ");
	$var['gtime'] = $gtime_t['zgtime'];
	//$var['scrapstatus'] = $scrapstatus;//on 이면 파이썬에서 실행
header('Content-type: application/json');
echo json_encode($var);
exit;
}

include_once(G5_PATH.'/head.sub.php');

$proxy_info = sql_fetch( "select * from a_server where proxy = '{$proxy}' ");

?>
<style>
<?php if($gid) {?>
#proxy {background-color: #c8dafa;}
<?php } else {?>
#proxy {background-color: #ff7777; color: white;}
<?php } ?>
</style>
<div id="proxy" class="container">
	<div class="">
    	<h4><?php echo $proxy;?> <?php echo $proxy_info['computer'];?> <?php if($proxy) echo $proxy_info['profile'];?></h4>
        	<a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $proxy;?>');">IP복사</a>
			<a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $proxy;?>');">COM복사</a>
            <?php $proxy_no = sql_fetch(" select count(*) as cnt  from a_gidpwrepair where status = ''  and type='' "); ?>
            <a class="btn btn-xs btn-warning">미사용<span class="badge badge-secondary"><?php echo  $proxy_no['cnt'];?></span></a>
           <?php if($old_res['gid']) {?>
				<a href="./proxy.php?set=clear&ip=<?php echo $proxy;?>" onclick="confirm('계정에 문제가 있거나 브라우저에 연결된 계정과 다름을 확인 하셨습니까?');return true;" class="btn btn-xs btn-info" >초기화</a>
            <?php } else { ?>            
                <a href="./proxy.php?set=publish&ip=<?php echo $proxy;?>" onclick="confirm('브라우저에 연결된 계정 상태를 확인하셨습니까? 연결 된 계정이 없는 경우 자동발급을 진행하세요');return true;" class="btn btn-xs btn-danger" >자동발급</a>
            <?php } ?>
   				<a class="btn btn-xs btn-danger" target="_blank" href="https://accounts.google.com/signin/v2/identifier?service=youtube&uilel=3&passive=true&continue=https%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26app%3Ddesktop%26hl%3Dko%26next%3Dhttps%253A%252F%252Fwww.youtube.com%252F&hl=ko&ec=65620&flowName=GlifWebSignIn&flowEntry=ServiceLogin">로그인주소</a>
    
                             
	</div>  
    <form name="project-setting" action="./proxy.php" method="post" onsubmit="return proxy_submit(this);">
    <input type="hidden" name="proxy" value="<?php echo $proxy;?>"/>
    <input type="hidden" name="com" value="<?php echo $proxy_info['computer'];?>"/>
    <input type="hidden" name="usr" value="<?php echo $proxy_info['profile'];?>"/>
    <input type="hidden" name="gidcheck" id="gidcheck" />
	<div class="">
		<div class="col-sm-3">     
			<div class="form-group">
	            <span class="form-label">구글아이디 
                <?php if($gid) {?><a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $gid;?>');">복사</a></span>
				<?php } else if(!$gid) {?>
                	<a class="btn btn-xs btn-info" onclick="checkgid();">검증</a></span>
                    
				<?php } ?>
	            <input class="form-control" type="text" placeholder="구글아이디" id="gid" name="gid" value="<?php echo $gid;?>"<?php if($gid) echo 'readonly="readonly"'; ?> >
                <span id="exinfo"></span>
            </div>
        </div>
        <div class="col-sm-3">     
	        <div class="form-group">
	            <?php if($pwd) {?><span class="form-label">비밀번호 <a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $pwd;?>');">복사</a></span><? } ?>
				<input class="form-control" id="pwd" type="text" placeholder="비밀번호" name="pwd" value="<?php echo $pwd;?>" readonly="readonly">                    
            </div>
        </div>
        <div class="col-sm-3">     
	        <div class="form-group">
	            <?php if($rid) {?><span class="form-label">복구메일 <a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $rid;?>');">복사</a></span><? } ?>
		            <input class="form-control" id="repair" type="text" placeholder="복구메일" name="repair" value="<?php echo $rid;?>" readonly="readonly">                    
            </div>
        </div>
        <div class="col-sm-3">     
	        <div class="form-group">        				
                <span class="btn-xs btn-warning">최근활동 <?php if($udatetime) echo get_date_diff($proxy_info['gidtime']);?> </span>
			</div>
        </div>
        
	</div>
	<div class="">
		<div class="col-sm-3">

                <div class="form-group">
                    <span class="form-label">메모</span>
                    <textarea name="id-memo" id="id-memo"><?php echo $memo;?></textarea>
                </div> 
        </div>
        <div class="col-sm-3">     
	        <div class="form-group">
            	<?php if(!$gid && !$pwd) {?><button  class="submit-btn btn btn-xs btn-info" >뷰어계정등록</button><? } ?>
            	<?php if($loginstatus) {?><span class="btn btn-lg btn-info"><?php echo $loginstatus;?></span><? } ?>
                <?php if($scrapstatus) {?><span class="btn btn-lg btn-info"><?php echo $scrapstatus;?></span><? } ?>
            </div>
        </div>                
        <div class="col-sm-3">     
	        <div class="form-group">

            </div>
        </div>                      
	</div>
</form>

	<div class="">
		<div class="col-sm-6">
   			<form action="./proxyup.php" enctype="multipart/form-data" method="post"  >
               	<input type="file" name="proxyfile" />
                <button class="btn btn-md btn-success">보내기</button>
            </form>
        </div>
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
	alert(text + " 복사하였습니다");
}
function checkgid(){
	var chkgid = $("#gid").val();
	if(!chkgid){
		alert("구글계정을 입력하세요");
		return false;
	}
	$("#exinfo").html('');
	$("#gidcheck").val('');
	$.ajax({
			type: "post",
			url : "./ajax_gid.php",
			data: {chkgid:chkgid},
			dataType:"json",
			async:false,
			success : function(data, status, xhr) {
				console.log(data);
				if(data.condition =="exist"){
					$("#exinfo").html("사용중인 뷰어 : "+data.proxy);					
					alert("사용중으로 나옵니다.");
				}else if(data.condition =="clear"){
					$("#exinfo").html("사용했던 뷰어 : "+data.exid);							
					alert("점검중으로 나옵니다");	
				} else if(data.condition =="available"){				
					if(data.proxy) $("#exinfo").html("사용중인 뷰어 : " + data.proxy + "  최근 활동( "+ date.latest + " ) 등록가능");
					else $("#exinfo").html("등록가능합니다.");
					$("#pwd").val(data.pwd);
					$("#repair").val(data.repair);
					$("#id-memo").val(data.memo);
					$("#gidcheck").val('g');	
					alert("등록가능.");					
				} else if(data.condition =="none"){
					$("#exinfo").html("계정이 없습니다.");					
					alert("계정이 없습니다.다시 확인 하세요");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(jqXHR.responseText);
			}
		});	
	
}
function proxy_submit(f){
	checkgid();
	if($("#gidcheck").val() == 'g'){
		return true;
	} else {
		return false;
	}
}
</script>


<?
include_once(G5_PATH.'/tail.sub.php');