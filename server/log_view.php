<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.sub.php');
include_once('./sub_site.php');

function get_project($project_id,$key=''){
	$res = sql_fetch(" select * from a_serverset where project_id = '{$project_id}'" );
	if($key) return $res[$key];
	else return $res;
}


if( isset($_GET['mode']) && $_GET['mode']) $mode = trim(strip_tags(clean_xss_attributes($_GET['mode'])));
if( isset($_GET['type']) && $_GET['type']) $type = trim(strip_tags(clean_xss_attributes($_GET['type'])));
if(isset($_GET['clear']) && $_GET['clear'])  $clear =  trim(strip_tags(clean_xss_attributes($_GET['clear'])));

if($mode == "setting" && 1==false){// 구글아이디 테이블에 프록시가 빠진 것들을 채워넣는다.
	//proxy 체크
	$psql = mysqli_query($sub_site['zenpc'],"select a.proxy, a.gid , b.gid as bgid , b.proxy as bproxy from a_server a
						left join a_gidpwrepair b
						on (a.gid = b.gid and a.proxy != b.proxy )
						where a.gid <> '' 
						and a.type = '' 						
						");
	for($i=0;$prow=mysqli_fetch_array($psql);$i++){
		if($prow['gid'] && $prow['proxy']) {
			mysqli_query($sub_site['proxy'],"update a_gidpwrepair set proxy = '{$grow['proxy']}' , type = '{$type}' where gid = '{$grow['gid']}' ");
		}
	}
	$msg = "프록시 $pi 건 동기화되었습니다";
	
	
	
	$gsql = mysqli_query($sub_site['zenpc']," select * from a_server where computer <> '' and  profile <>'' and gid <> '' and type = 'zenpc'");
	for($i=0;$grow=mysqli_fetch_array($gsql);$i++){
		if($grow['gid'] && $grow['proxy']) {

				$grow['proxy'] = str_replace("00x","",$grow['proxy']);
				$grow['proxy'] = str_replace("01x","",$grow['proxy']);
				mysqli_query($sub_site['zenpc'],"update a_gidpwrepair set proxy = '{$grow['proxy']}' , type = 'zenpc' where gid = '{$grow['gid']}' ");

		}
	}
	alert("젠피씨 $i 건 동기화되었습니다",G5_URL."/server/log_view.php"); 
}


if($mode == "reissuance" && 1 == false){// 구글아이디 테이블에 프록시가 빠진 것들을 채워넣는다.
	$gidtime = date("Y-m-d H:i:s");
	$oriexist = '';
	$reissuance = '';
	if($type) {
		$gsql = mysqli_query($sub_site['zenpc']," select * from a_server where computer <> '' and  profile <>'' and gid = '' and ( type = '{$type}' and proxy like '00x%' )");
		for($i=0;$grow=mysqli_fetch_array($gsql);$i++){
			echo $grow['twin'] = str_replace("00x","01x",$grow['proxy']);
			echo $grow['pure'] = str_replace("00x","",$grow['proxy']);
			$gsql_twin = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select * from a_server where proxy = '{$grow['twin']}' "));
			if($gsql_twin['gid']) {
				mysqli_query($sub_site['zenpc']," update a_server set gid = '{$gsql_twin['gid']}' , pwd = '{$gsql_twin['pwd']}' , repair = '{$gsql_twin['repair']}' , gidstatus = 'add' , gidtime = '{$gidtime}' where proxy = '{$grow['proxy']}'" );
				$oriexist++;
			} else {
				$rrow = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select * from  a_gidpwrepair where proxy = '' and type = '{$type}' order by rand() limit 0,1"));
				mysqli_query($sub_site['zenpc']," update a_server set gid = '{$rrow['gid']}' , pwd = '{$rrow['pwd']}' , repair = '{$rrow['repair']}' , gidstatus = 'add' , gidtime = '{$gidtime}' where proxy = '{$grow['proxy']}'" );	
				mysqli_query($sub_site['zenpc']," update a_server set gid = '{$rrow['gid']}' , pwd = '{$rrow['pwd']}' , repair = '{$rrow['repair']}' , gidstatus = 'add' , gidtime = '{$gidtime}' where proxy = '{$grow['proxy']}'" );
				mysqli_query($sub_site['zenpc']," update a_gidpwrepair set proxy = '{$grow['pure']}' , status = 'act' , gidstatus = 'add' , type = '{$type}' where id = '{$rrow['id']}'" );						
				$reissuance++;
			}								
		}
		alert("기존00x와 01x 맞춤 $oriexist 건 , $reissuance 재발급되었습니다",G5_URL."/server/log_view.php?type=zenpc"); 		
	} else {
		$gsql = sql_query(" select * from a_server where computer <> '' and  profile <>'' and gid = '' and type = '{$type}' ");;
		for($i=0;$grow=sql_fetch_array($gsql);$i++){		
			$rrow = sql_fetch(" select * from  a_gidpwrepair where proxy = '' and type = '{$type}' order by rand() limit 0,1");
			sql_query(" update a_server set gid = '{$rrow['gid']}' , pwd = '{$rrow['pwd']}' , repair = '{$rrow['repair']}' , gidstatus = 'add' , gidtime = '{$gidtime}' where proxy = '{$grow['proxy']}'" );	
			sql_query(" update a_gidpwrepair set proxy = '{$grow['pure']}' , status = 'act' , gidstatus = 'add' , type = '{$type}' where id = '{$rrow['id']}'" );						
			$reissuance++;
		}
		alert("$reissuance 재발급되었습니다",G5_URL."/server/log_view.php"); 		
	}
	
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

.log-computer {padding: 5px;border: 2px solid #f90000;background: #f5fdf0;margin-bottom:20px;max-width: 1600px;margin: 0 auto;}
.log-pb80 {padding-bottom:80px;}
.viewstart {color:blue; border: 2px solid blue;} .viewstart span {background:blue;}
.servercheck {color:#0093ab; border: 2px solid #0093ab;} .servercheck span {background:#0093ab;}
.newsperm{color:#7d00ee; border: 2px solid #7d00ee;} .newsperm span {background:#7d00ee;}
.critical {color:red;border: 2px solid red;} .critical span {background:red;}
.nofindtarget {color:#C60;border: 2px solid #C60;} .nofindtarget span {background:#C60;}
.failfindkeyword {color:#9e33ff;border: 2px solid #9e33ff;} .failfindkeyword span {background:#9e33ff;}
.notinteract{color:#F63;border: 2px solid #F63;} .notinteract span {background:#F63;}
.break {color:#003;border: 2px solid #003;} .break span {background:#003;}
.endview {color:#003;border: 2px solid #003;} .endview span {background:#003;}
.parking {color:#4caf50;border: 2px solid #4caf50;} .parking span {background:#4caf50;}
.seterror {color:#c700ff;border: 2px solid #c700ff;} .seterror span {background:#c700ff;}
.etc {color:#df9932;border: 2px solid #df9932;} .etc span {background:#df9932;}

.mia {color:#f0ad4e;border: 2px solid #f0ad4e;} .mia span {background:#f0ad4e;}
.rankinfo {position:relative;padding: 2px 5px;font-size:12px;display: inline-block;font-weight: bold;}
.rankinfo-text {display: none;position: absolute;min-width: 200px;top:27px;left:5px;border: 1px solid;border-radius: 5px;padding: 5px;font-size: 0.8em;color: white;z-index:3;}
.rankinfo-text a {color:white;}
.rankinfo:hover {background-color:#E4E4E4;}
.rankinfo:hover .rankinfo-text {display: inline-block;}

.common-wrap , #mia-computer , #current-computer , #input-excel {display:none;margin:10px;}
#current-computer {border:1px solid #900;}
#mia-computer {border:1px solid #F90;}
.zenpc {position:absolute;top:0px;left:0px;width:20px;height:5px;text-align:center;}
.loginoff {position:absolute;top:0px;right:0px;width:20px;height:5px;text-align:center;}

.project-tab {margin:3px 2px;}

.gidpw-info  {width:19%;margin:0.5%;height:150px;float:left;font-size:0.7em;    border: 1px solid black;    padding: 10px;}
.idmemo {width: 72%;height: 2.2em;padding: 0.1em 1em;}

.inter-gid {display:inline-block;padding:3px 10px;border: 1px solid black; margin-bottom:3px; font-size:12px;}
.inter-container {padding:5px 100px;}
.log-container {display:inline-block;padding:10px 20px;}

.goto_top {position:fixed;width:80px;bottom:100px;right:50px;}
#gotobottom {display:inline-block;width:2px;height:2px;background-color:red;}
</style>

<?php
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
$inter_cnt = @count($inter_all);

?>
<div class="section text-center" id="booking">
<h4> 
	<a href="./log_view.php" class="btn btn-lg btn-success">구글계정 연동 상황/관리</a>
    <!--<a href="./log_view.php?mode=errorlist" class="btn btn-md btn-success">발급계정오류현황</a>-->
    <a href="./log_view.php?mode=logoff" class="btn btn-md btn-success">로그오프현황</a> 
    <a href="./project_form.php" class="btn btn-xs btn-info" target="_blank">오토튜브 관리/설정</a>
	<a href="<?php echo G5_BBS_URL ?>/logout.php" class="btn btn-xs btn-default">로그아웃</a>
	<?php if($is_admin){?><a href="/adm/" class="btn btn-default btn-xs ">관리자설정</a><?php } ?>

</h4>
</div>
<?php if($inter_cnt) {?>
<div class="inter-container ">
<p class="btn btn-md btn-danger"><?php echo $inter_cnt;?>건 중복계정존재</p><br />
	<?php foreach($inter_all as $key => $value){?>
    <span class="inter-gid"><?php echo $value;?></span>
    <?php }?>
</div>
<?php } ?>
<div class="log-container">
<?php
mysqli_query($sub_site['proxy']," select *  from a_gidpwrepair where status = 'clear' and type='' ")
?>
<div class="col-sm-6" id="proxy-log">
<?php
 $proxy_total = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) as cnt  from a_gidpwrepair "));
 $proxy_clear = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) as cnt  from a_gidpwrepair where status = 'clear' and type='' "));
 $proxy_no = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) as cnt  from a_gidpwrepair where status = ''  and type='' "));
 $proxy_act = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) as cnt  from a_gidpwrepair where status = 'act'  and type='' ")); 
 $proxy_correct = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_gidpwrepair a left join a_server b on a.proxy = b.proxy where a.gid = b.gid"));
 $proxy_missing = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(*) as cnt  from a_gidpwrepair a left join a_server b on a.proxy = b.proxy where b.proxy is null and a.proxy !='' order by a.gid asc"));
 $proxy_dup = mysqli_fetch_array(mysqli_query($sub_site['proxy']," select count(*) as cnt  from a_gidpwrepair where gidstatus = 'duplicate'  and type='' ")); 
 
?>
	<div class="row">
 <h3>
    <a href="./log_view.php" class="btn btn-lg btn-success">프록시서버 <span class="badge badge-secondary"><?php echo  $proxy_total['cnt'];?></span></a>       
    <!--<a href="./log_view.php?mode=setting" onclick="confirm('동기화는 구글계정을 뷰어에 재발급이 아니라 뷰어에 등록된 구글계정 기준으로 업데이트 합니다.');return true;"class="btn btn-xs btn-primary">동기화<span class="badge badge-secondary"></span></a>-->
    <!--<a href="./log_view.php?mode=reissuance" onclick="confirm('랜덤으로 구글아이디가 없는 프록시에 신규 계정을 재발급 합니다.');return true;"class="btn btn-xs btn-primary">재발급<span class="badge badge-secondary"></span></a>-->
	
	
	<a href="./log_excel.php?mode=clear&type=" class="btn btn-xs btn-info">점검엑셀다운</a>
    <a href="./log_excel.php?mode=claer&act=clear&type=" onclick="confirm('점검할 계정을 db삭제합니다. ');return true;"class="btn btn-xs btn-info">점검삭제</a>
	<a href="./log_excel.php?mode=missing&type=" class="btn btn-xs btn-info">미아엑셀다운</a>
    <a href="./log_excel.php?mode=missing&act=clear&type=" onclick="confirm('미아계정을 미사용계정으로 초기화 합니다');return true;"class="btn btn-xs btn-info">미아초기화 <span class="badge badge-secondary">7일전</span></a>      
    <!--<a href="./log_excel.php?mode=errorlis&type=" class="btn btn-xs btn-info">계정오류엑셀</a>        -->
    <!--<a href="./log_view.php?mode=setting" onclick="confirm('동기화는 구글아이디값들과 ip(Proxy)를 맞춰줍니다.');return true;" class="btn btn-xs btn-primary">동기화(미사용)<span class="badge badge-secondary"></span></a>     -->
    <!--<a href="./log_view.php?type=zenpc&mode=reissuance" onclick="confirm('랜덤으로 구글아이디가 없는 프록시에 신규 계정을 재발급 합니다.');return true;"class="btn btn-xs btn-primary">재발급<span class="badge badge-secondary"></span></a>-->     
    <br />
     
     </h3>
	</div>
<?php if($mode == 'logoff'){?>
<div class="tab-menu">
<? $plogoff = mysqli_fetch_array(mysqli_query($sub_site['proxy'],"select count(*) as cnt from a_server where gid <> '' and loginstatus = 'off' and type = '' order by log_time ASC "));?>
	<a href="./log_excel.php?mode=logoff" class="btn btn-xs btn-info">프록시 로그오프 엑셀다운 <span class="badge badge-secondary"><?php echo  $plogoff['cnt'];?></span></a>  
</div>
<div class="tab-content">
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
		<tr>	    
        	<th>뷰어(공통)</th>
            <th>컴퓨터</th>
            <th>프로필</th>
            <th>발급계정</th>
            <th>활동시간</th>
            <th>복사</th>
	    </tr>
       </thead>
       <tbody>
<?php
	$psql = mysqli_query($sub_site['proxy'],"select * from a_server where gid <> '' and loginstatus = 'off' and type = '' order by log_time ASC ");
	for($i=0;$prow=mysqli_fetch_array($psql);$i++){
?>
	<tr>
    <td><?php echo $prow['proxy'];?></td>
    <td><?php echo $prow['computer'];?></td>
    <td><?php echo $prow['profile'];?></td>
    <td><?php echo $prow['gid'];?></td>
    <td><?php echo get_date_diff($prow['log_time']);?></td>
    <td><a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $prow['proxy'];?> <?php echo $prow['gid'];?> <?php echo $prow['pwd'];?> <?php echo $prow['repair'];?>');">복사</a></td>
    </tr>

<?php } ?>
</tbody>
</table>
</div>

<?php } else if($mode == 'errorlist'){?>
<div class="tab-content">
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
		<tr>	    
        	<th>뷰어프록시</th>
            <th>컴퓨터</th>
            <th>프로필</th>
            <th>발급계정</th>
            <th>활동시간</th>
            <th>구글계정</th>
            <th>구글계정등록프록시</th>
            <th>복사</th>
	    </tr>
       </thead>
       <tbody>
<?php
	$psql = mysqli_query($sub_site['proxy'],"select a.* , b.gid as bgid , b.proxy as bproxy from a_server a
						left join a_gidpwrepair b
						on (a.gid = b.gid and a.proxy != b.proxy )
						where a.gid <> '' 
						and a.type = ''
						and b.gid <> ''
						order by a.gid ASC
						");
	for($i=0;$prow=mysqli_fetch_array($psql);$i++){
?>
	<tr>
    <td><?php echo $prow['proxy'];?></td>
    <td><?php echo $prow['computer'];?></td>
    <td><?php echo $prow['profile'];?></td>
    <td><?php echo $prow['gid'];?></td>
    <td><?php echo $prow['log_time'];?></td>
    <td><?php echo $prow['bgid'];?></td>
    <td><?php echo $prow['bproxy'];?></td>
    <td><a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $prow['proxy'];?> <?php echo $prow['gid'];?> <?php echo $prow['pwd'];?> <?php echo $prow['repair'];?>');">복사</a></td>
    </tr>

<?php } ?>
</tbody>
</table>
</div>

<?php }  else { ?>     
        <a class="btn btn-lg btn-primary"  href="./log_view.php?type=&mode=clear">점검<span class="badge badge-secondary"><?php echo  $proxy_clear['cnt'];?></span></a>		   
        <a class="btn btn-lg btn-primary" href="./log_view.php?type=&mode=act">발급<span class="badge badge-secondary"><?php echo  $proxy_act['cnt'];?></span></a>
        <a class="btn btn-lg btn-primary" href="./log_view.php?type=&mode=correct">정상<span class="badge badge-secondary"><?php echo  $proxy_correct['cnt'];?></span></a>
        <a class="btn btn-lg btn-primary" href="./log_view.php?type=&mode=missing">미아<span class="badge badge-secondary"><?php echo  $proxy_missing['cnt'];?></span></a>        
		<a class="btn btn-lg btn-primary" href="./log_view.php?type=&mode=duplicate">기존<span class="badge badge-secondary"><?php echo  $proxy_dup['cnt'];?></span></a>
        <a class="btn btn-lg btn-primary" href="./log_view.php?type=&mode=no">미사용<span class="badge badge-secondary"><?php echo  $proxy_no['cnt'];?></span></a>

	<div class="tab-content">
         
        <?php 
		$ori_mode = $mode;
		if(!$mode) $mode = 'clear';
		if($mode == 'no') $mode = '';
		if(!$mode || $mode=='clear' || $mode == 'act'|| $mode == '1act'|| $mode == '2act'|| $mode == '3act'|| $mode == '4act') $mode_where = " a.status = '{$mode}' ";
		else if($mode == 'duplicate') $mode_where = " a.gidstatus = '{$mode}' ";
        $sql = mysqli_query($sub_site['proxy']," select a.* ,b.computer , b.profile from a_gidpwrepair a left join a_server b on (a.gid = b.gid) where  {$mode_where} and a.type = '' order by a.status DESC , a.updatetime DESC , a.proxydatetime DESC ");
		//
		if($mode == "correct")  $sql = mysqli_query($sub_site['proxy'],"select * from a_gidpwrepair a left join a_server b on a.proxy = b.proxy where a.gid = b.gid order by a.proxydatetime DESC  ");
		else if($mode =="missing") $sql = mysqli_query($sub_site['proxy'],"select a.*  from a_gidpwrepair a left join a_server b on a.proxy = b.proxy where b.proxy is null and a.proxy !='' order by a.proxydatetime DESC ");
        for($i=0;$row=mysqli_fetch_array($sql);$i++){
			// $sql_t = sql_fetch(" select * from a_server where proxy like '%x{$row['proxy']}' order by log_time DESC limit 0, 1");
			
        ?>
        	<div class="gidpw-info <?php echo $row['status'];?>">
				<?php if($row['gidstatus']=='duplicate') {?>
                <span id="dup-<?php echo $i;?>" class="btn btn-xs btn-warning">기존</span>
				<?php } ?>
            	<span id="status-<?php echo $i;?>" class="btn btn-xs btn-primary">
				<?php if($row['status']=='act') {?>사용중
				<?php } else if($row['status']=='clear') {?>점검
				<?php } else { ?>미사용<?php } ?></span>
                
                <a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $row['proxy'];?> <?php echo $row['gid'];?> <?php echo $row['pwd'];?> <?php echo $row['repair'];?>');">복사</a>
                 <?php if($row['updatetime']) {?><span id="pdate-<?php echo $i;?>" class="pdate btn-xs btn-success">활동 : <?php echo get_date_diff($row['proxydatetime']);?></span><?php } ?>
                 <br />
                <span id="proxy-<?php echo $i;?>" class="proxy"><?php echo $row['proxy'];?> <?php echo $row['computer'];?> <?php  echo $row['profile'];?></span>
                 <br />  
                <span id="gid-<?php echo $i;?>" class="gid"><?php echo $row['gid'];?></span>
                <span id="pwd-<?php echo $i;?>" class="pwd"><?php echo $row['pwd'];?></span>
                <span id="repair-<?php echo $i;?>" class="repair"><?php echo $row['repair'];?></span>
               	<span id="id-memo-<?php echo $i;?>" class="idmemo"><?php echo $row['memo'];?></span>
            </div>
        <?php } ?>

	</div>  

	<?php } ?>       
</div>



<div class="col-sm-6" id="zenpc-log">
<?php
 $zenpc_total = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) as cnt  from a_gidpwrepair "));
 $zenpc_clear = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) as cnt  from a_gidpwrepair where status = 'clear'  "));
 $zenpc_no = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) as cnt  from a_gidpwrepair where status = ''  "));

$zenpc_act = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) as cnt  from a_gidpwrepair where status = 'act'   ")); 
$zenpc_1act = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) as cnt  from a_gidpwrepair where status = 'act'  and week = '1' ")); 
$zenpc_2act = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) as cnt  from a_gidpwrepair where status = 'act'   and week = '2'  ")); 
$zenpc_3act = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) as cnt  from a_gidpwrepair where status = 'act'   and week = '3'  ")); 
$zenpc_4act = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) as cnt  from a_gidpwrepair where status = 'act'   and week = '4'  ")); 

 $zenpc_dup = mysqli_fetch_array(mysqli_query($sub_site['zenpc']," select count(*) as cnt  from a_gidpwrepair where gidstatus = 'duplicate'   "));
 $zenpc_correct = mysqli_fetch_array(mysqli_query($sub_site['zenpc'],"select count(*) as cnt from a_gidpwrepair a 
left join a_server b 
on a.gid = b.gid and b.proxy like concat('00x',a.proxy,'%')
left join a_server c 
on a.gid = c.gid and c.proxy like concat('01x',a.proxy,'%')
where a.proxy != '' and a.groupcomputer like 'ZPC%' and b.proxy != '' and c.proxy != '' "));
 $zenpc_missing = mysqli_fetch_array(mysqli_query($sub_site['zenpc'],"select count(*) as cnt from a_gidpwrepair a 
left join a_server b 
on a.gid = b.gid and b.proxy like concat('00x',a.proxy,'%')
left join a_server c 
on a.gid = c.gid and c.proxy like concat('01x',a.proxy,'%')
where a.proxy <>'' and groupcomputer not like 'ZPC%' and ( b.proxy is null or c.proxy is null)")); 
 
?>
    <h3>
    <!--<a href="./log_view.php?mode=setting" onclick="confirm('동기화는 구글계정을 뷰어에 재발급이 아니라 뷰어에 등록된 구글계정 기준으로 업데이트 합니다.');return true;"class="btn btn-xs btn-primary">동기화<span class="badge badge-secondary"></span></a>-->
    <!--<a href="./log_view.php?mode=reissuance" onclick="confirm('랜덤으로 구글아이디가 없는 프록시에 신규 계정을 재발급 합니다.');return true;"class="btn btn-xs btn-primary">재발급<span class="badge badge-secondary"></span></a>-->
    <a href="./log_view.php?type=zenpc" class="btn btn-lg btn-success">ZEN서버 <span class="badge badge-secondary"><?php echo  $zenpc_total['cnt'];?></span></a>

	<a href="./log_excel.php?mode=clear&type=zenpc" class="btn btn-xs btn-info">점검엑셀다운</a>
    <a href="./log_excel.php?mode=clear&act=clear&type=zenpc" onclick="confirm('점검할 계정을 db삭제합니다. ');return true;"class="btn btn-xs btn-info">점검삭제</a>
	<a href="./log_excel.php?mode=missing&type=zenpc" class="btn btn-xs btn-info">미아엑셀다운</a>
    <!--<a href="./log_excel.php?mode=missing&act=clear&type=zenpc" onclick="confirm('미아계정을 미사용계정으로 초기화 합니다');return true;"class="btn btn-xs btn-info">미아초기화 <span class="badge badge-secondary">7일전</span></a>-->
    <!--<a href="./log_excel.php?mode=errorlis&type=zenpc" class="btn btn-xs btn-info">계정오류엑셀</a>        -->
    <!--<a href="./log_view.php?mode=setting" onclick="confirm('동기화는 구글아이디값들과 ip(Proxy)를 맞춰줍니다.');return true;" class="btn btn-xs btn-primary">동기화(미사용)<span class="badge badge-secondary"></span></a>     -->
    <!--<a href="./log_view.php?type=zenpc&mode=reissuance" onclick="confirm('랜덤으로 구글아이디가 없는 프록시에 신규 계정을 재발급 합니다.');return true;"class="btn btn-xs btn-primary">재발급<span class="badge badge-secondary"></span></a>-->     
    <br />
     
     </h3>
<?php if($mode == 'logoff'){?>
<?php $zlogoff = sql_fetch("select count(*) as cnt from a_server where gid <> '' and loginstatus = 'off'  order by log_time ASC ");?>

<div class="tab-menu">
	<a href="./log_excel.php?mode=logoff&type=zenpc" class="btn btn-xs btn-info">젠pc 로그오프 엑셀다운  <span class="badge badge-secondary"><?php echo  $zlogoff['cnt'];?></span></a>     
</div>
<div class="tab-content">
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
		<tr>	    
        	<th>뷰어(공통)</th>
            <th>컴퓨터</th>
            <th>프로필</th>
            <th>발급계정</th>
            <th>활동시간</th>
            <th>복사</th>
	    </tr>
       </thead>
       <tbody>
<?php
	$psql = mysqli_query($sub_site['zenpc'],"select * from a_server where gid <> '' and loginstatus = 'off' order by log_time ASC ");
	for($i=0;$prow=sql_fetch_array($psql);$i++){
?>
	<tr>
    <td><?php echo $prow['proxy'];?></td>
    <td><?php echo $prow['computer'];?></td>
    <td><?php echo $prow['profile'];?></td>
    <td><?php echo $prow['gid'];?></td>
    <td><?php echo get_date_diff($prow['log_time']);?></td>
    <td><a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $prow['proxy'];?> <?php echo $prow['gid'];?> <?php echo $prow['pwd'];?> <?php echo $prow['repair'];?>');">복사</a></td>
    </tr>

<?php } ?>
</tbody>
</table>
</div>

<?php } else if($mode == 'errorlist'){?>
<div class="tab-content">
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
		<tr>	    
        	<th>뷰어프록시</th>
            <th>컴퓨터</th>
            <th>프로필</th>
            <th>발급계정</th>
            <th>활동시간</th>
            <th>구글계정</th>
            <th>구글계정등록프록시</th>
            <th>복사</th>
	    </tr>
       </thead>
       <tbody>
<?php
	$psql = mysqli_query($sub_site['zenpc'],"select a.* , b.gid as bgid , b.proxy as bproxy from a_server a
						left join a_gidpwrepair b
						on (a.gid = b.gid and a.proxy != b.proxy)
						where a.gid <> '' 
						
						and b.gid <> ''						
						order by a.gid ASC
						"); //and a.type = 'zenpc'
	for($i=0;$prow=mysqli_fetch_array($psql);$i++){
?>
	<tr>
    <td><?php echo $prow['proxy'];?></td>
    <td><?php echo $prow['computer'];?></td>
    <td><?php echo $prow['profile'];?></td>
    <td><?php echo $prow['gid'];?></td>
    <td><?php echo $prow['log_time'];?></td>
    <td><?php echo $prow['bgid'];?></td>
    <td><?php echo $prow['bproxy'];?></td>
    <td><a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $prow['proxy'];?> <?php echo $prow['gid'];?> <?php echo $prow['pwd'];?> <?php echo $prow['repair'];?>');">복사</a></td>
    </tr>

<?php } ?>
</tbody>
</table>
</div>

<?php }  else { ?>     
        <a class="btn btn-lg btn-primary"  href="./log_view.php?type=zenpc&mode=clear">점검<span class="badge badge-secondary"><?php echo  $zenpc_clear['cnt'];?></span></a>		   
        <a class="btn btn-lg btn-primary" href="./log_view.php?type=zenpc&mode=act">발급<span class="badge badge-secondary"><?php echo  $zenpc_act['cnt'];?></span></a>
        <a class="btn btn-xs btn-primary" href="./log_view.php?type=zenpc&mode=1act">1주<span class="badge badge-secondary"><?php echo  $zenpc_1act['cnt'];?></span></a>
        <a class="btn btn-xs btn-primary" href="./log_view.php?type=zenpc&mode=2act">2주<span class="badge badge-secondary"><?php echo  $zenpc_2act['cnt'];?></span></a>
        <a class="btn btn-xs btn-primary" href="./log_view.php?type=zenpc&mode=3act">3주<span class="badge badge-secondary"><?php echo  $zenpc_3act['cnt'];?></span></a>
        <a class="btn btn-xs btn-primary" href="./log_view.php?type=zenpc&mode=4act">4주<span class="badge badge-secondary"><?php echo  $zenpc_4act['cnt'];?></span></a>
                                
        <a class="btn btn-lg btn-primary" href="./log_view.php?type=zenpc&mode=correct">정상<span class="badge badge-secondary"><?php echo  $zenpc_correct['cnt'];?></span></a>        
        <a class="btn btn-lg btn-primary" href="./log_view.php?type=zenpc&mode=missing">미아<span class="badge badge-secondary"><?php echo  $zenpc_missing['cnt'];?></span></a>             
		<a class="btn btn-lg btn-primary" href="./log_view.php?type=zenpc&mode=duplicate">기존<span class="badge badge-secondary"><?php echo  $zenpc_dup['cnt'];?></span></a>                
        <a class="btn btn-lg btn-primary" href="./log_view.php?type=zenpc&mode=no">미사용<span class="badge badge-secondary"><?php echo  $zenpc_no['cnt'];?></span></a>

	<div class="tab-content">
         
        <?php 
		if(!$ori_mode) $mode = 'clear';
		if(!$mode || $mode=='clear' || $mode == 'act') $mode_where = " a.status = '{$mode}' ";
		else if($mode == '1act' || $mode == '2act' || $mode == '3act' || $mode == '4act' )  {
			$mode_week = str_replace("act","",$mode);
			$mode_where = " a.week = '{$mode_week}'";
		}
		else if($mode == 'duplicate') $mode_where = " a.gidstatus = '{$mode}' ";
        $sql = mysqli_query($sub_site['zenpc']," select a.* ,b.computer , b.profile from a_gidpwrepair a left join a_server b on (a.gid = b.gid) where  {$mode_where}  order by a.status DESC , a.updatetime DESC , a.proxydatetime DESC ");
	
		if($mode == "correct")  $sql = mysqli_query($sub_site['zenpc']," select a.* , b.proxy as bproxy , c.proxy as cproxy from a_gidpwrepair a left join a_server b on a.gid = b.gid and b.proxy like concat('00x',a.proxy,'%') left join a_server c 
													on a.gid = c.gid and c.proxy like concat('01x',a.proxy,'%') where a.proxy != '' and b.proxy != '' and c.proxy != '' order by  a.proxydatetime DESC  ");
		else if($mode =="missing") $sql = mysqli_query($sub_site['zenpc']," select a.* , b.proxy as bproxy , c.proxy as cproxy from a_gidpwrepair a left join a_server b on a.gid = b.gid and b.proxy like concat('00x',a.proxy,'%') left join a_server c 
													on a.gid = c.gid and c.proxy like concat('01x',a.proxy,'%') where a.proxy <>'' and ( b.proxy is null or c.proxy is null) and a.groupcomputer not like 'ZPC%' order by a.proxydatetime DESC ");
//DELETE FROM `a_gidpwrepair_1029` WHERE computer like 'zpc%' or groupcomputer like 'zpc%'
		if($ori_mode=='no')   $sql = mysqli_query($sub_site['zenpc']," select * from a_gidpwrepair where status = ''  and type='' ");
        for($i=0;$row=mysqli_fetch_array($sql);$i++){
			// $sql_t = sql_fetch(" select * from a_server where proxy like '%x{$row['proxy']}' order by log_time DESC limit 0, 1");
			
        ?>
        	<div class="gidpw-info <?php echo $row['status'];?>">
				<?php if($row['gidstatus']=='duplicate') {?>
                <span id="dup-<?php echo $i;?>" class="btn btn-xs btn-warning">기존</span>
				<?php } ?>
            	<span id="status-<?php echo $i;?>" class="btn btn-xs btn-primary">
				<?php if($row['status']=='act') {?>사용중
				<?php } else if($row['status']=='clear') {?>점검
				<?php } else { ?>미사용<?php } ?></span>
                
                <a class="btn btn-xs btn-info" onclick="copyToClipboard('<?php echo $row['proxy'];?> <?php echo $row['gid'];?> <?php echo $row['pwd'];?> <?php echo $row['repair'];?>');">복사</a>
                 <?php if($row['updatetime']) {?><span id="pdate-<?php echo $i;?>" class="pdate btn-xs btn-success">등록 : <?php echo get_date_diff($row['updatetime']);?></span><?php } ?>
                 <br />
                <span id="proxy-<?php echo $i;?>" class="proxy"><?php echo $row['proxy'];?> <?php echo $row['computer'];?> <?php  echo $row['profile'];?></span>
                 <br />  
                <span id="gid-<?php echo $i;?>" class="gid"><?php echo $row['gid'];?></span>
                <span id="pwd-<?php echo $i;?>" class="pwd"><?php echo $row['pwd'];?></span>
                <span id="repair-<?php echo $i;?>" class="repair"><?php echo $row['repair'];?></span>
                <span id="id-memo-<?php echo $i;?>" class="idmemo"><?php echo $row['memo'];?></span>
            </div>
        <?php } ?>

	</div>  

	<?php } ?>          
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

</script>

<div class="goto_top" >
<a href="#booking" class="btn btn-lg btn-default">위로</a>
<a href="#gotobottom" class="btn btn-lg btn-default">아래로</a>
</div>
<div id="gotobottom"></div>
<?

include_once(G5_PATH.'/tail.sub.php');
?>