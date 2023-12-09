<?php
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($member['mb_id']=="7979"||$member['mb_id']=="1212") goto_url(G5_URL."/server/adclick_form.php");
if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');
include_once(G5_THEME_PATH.'/rank.php');
		  if(isset($_POST['ytx']) && $_POST['ytx']) $ytx = clean_xss_tags(trim($_POST['ytx']));
		  if(isset($_POST['mb_3']) && $_POST['mb_3']) $mb_3 = clean_xss_tags(trim($_POST['mb_3']));
		   if(isset($_POST['sid']) && $_POST['sid']) $sid = clean_xss_tags(trim($_POST['sid']));
		   if($mb_3) $mb_3 = 'on'; else $mb_3='off';
		  if($ytx){
			  if(!$is_admin && $mb_3){
				 // $sqlytx = " update {$g5['member_table']} set mb_1 = '".time()."', mb_2 = '{$ytx}'  , mb_3 = '{$mb_3}' where mb_id = '{$member['mb_id']}' ";
				 // sql_query($sqlytx);
			  } else if($is_member && $sid ) {
					$sqlytx = " update {$g5['member_table']} set mb_1 = '".time()."', mb_2 = '{$ytx}' , mb_3 = '{$mb_3}' where mb_id = '{$sid}' ";
				  sql_query($sqlytx);
			  } else if($is_member && !$sid ) {
				  $sqlytx = " update {$g5['member_table']} set mb_1 = '".time()."', mb_2 = '{$ytx}'  , mb_3 = '{$mb_3}' where mb_id = '{$member['mb_id']}' ";
				  sql_query($sqlytx);				  
			  }
		  }
?>

 


<!-- Home Section -->
<?php if($is_member) {?>
<script>
$( document ).ready(function() {


    console.log( "ready!" );
});
function rank_reset(){
	console.log('reset');
	
	$("#sid option:selected").removeAttr("selected");
	$("#mb_3").removeAttr("checked");
	$("#ytx").removeAttr("value");
 
}
function ajax_alarm(){
	var mb_3 ='';
	if($("#sid").val()) {console.log('sid');} 
	else{
		if($("#mb_3").is(":checked") ==true) mb_3 = "on";else mb_3 = "off";
		console.log(mb_3);
		$.ajax({
			type:'POST',
			url : "<?php echo G5_URL;?>/ajax_alarm.php",
			data : {"mb_3":mb_3},
			success:function(data){
				console.log('success');
			}
		});
	}
}

</script>
<section id="home" class="main-home parallax-section">
	<div class="overlay"></div>
	<div id="particles-js"></div>
	<div class="container">
		<div class="row head_position">
			<div class="col-md-12 col-sm-12">
            	<h1><?php echo $config['cf_title']?></h1>
                
			</div>                    
		</div>
		<div class="row">
        	<form name="fhsearch" method="post" role="form" class="form" action="./">
            	<div class="col-sm-8">
					<div class="form-group">
						<div class="form-group">
							<label for="stx" class="sound_only">검색어</label>
							<input type="text" name="ytx"  id="ytx" class="form-control input-sm" maxlength="255" <?php if ($is_guest) echo 'data-toggle="modal" data-target="#myModal"  READONLY  placeholder="로그인후,댓글 남기기와 게시물 스크랩이 가능합니다"' ; else echo 'placeholder="검색어"' ;?>  value="<?php echo $ytx; ?>"  />
                            <span>알림신청을 선택하신 후에 검색하시면 알림 검색어로 등록됩니다<?php if($is_admin){?><br />관리자는 회원검색선택하면 회원 검색어 등록, 회원의 알림이 on으로 됩니다.<?php } ?></span>
					</div>
					</div>
				</div>              
               <div class="col-sm-2">
					<button type="submit" class="form-control btn-default btn-sm btn-block"><?php if($ytx) echo "재";?>검색</button>
                                 
                	<input type="checkbox" name="mb_3" id="mb_3" onclick="ajax_alarm();" class="form-control btn-default btn-sm btn-block" <?php if($member['mb_3']=="on") echo 'checked="checked"'; ?>><span class="form-control btn-default btn-sm btn-block">알림신청</span>          
                      <?php if($is_admin){?><a href="/adm/" class="form-control btn-default btn-sm btn-block">관리자설정</a><?php } ?>
                      <a href="<?php echo G5_BBS_URL ?>/logout.php" class="form-control btn-default btn-sm btn-block">로그아웃</a>
				</div> 
                <?php if($is_member){
						echo '<div class="col-sm-2">';
							echo '<select name="sid" id="sid" class="form-control btn-default btn-sm btn-block">';
							echo '<option value="">회원선택</option>';
							$ssql = sql_query("select * from {$g5['member_table']} where mb_id != 'admin' order by mb_id ");
			                for($i=0;$row=sql_fetch_array($ssql);$i++){
								if($sid==$row['mb_id']) $ssid = " selected ";
								else $ssid = '';
								echo '<option value="'.$row['mb_id'].'" '.$ssid.'>'.$row['mb_nick'].'</option>';
	               			}
						echo '</select>';
						echo '<button type="reset" onclick="rank_reset();" class="form-control btn-default btn-sm btn-block">초기화</button>  ';
						echo '<a href="/server/project_form.php" class="form-control btn-default btn-sm btn-block">프로젝트설정</a>  ';
						echo '</div>';
					}
				?>      
                                   
                </form>    
          </div>
<?php if($is_member){?>
<div class="project_wrap">
<?php 
//include_once(G5_THEME_PATH."/project_form.php");
?>
</div>
<? } ?>        
          <?php 

          if( $ytx ) {
error_reporting(E_ALL);
ini_set("display_errors", 1);


?>
<?php
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
			   $pconline_type  = " and type='pcon' ";
			   $mobileonline_type  = " online = '실시간' ";
			   $sql_tail = " order by ranking ASC  ";
 ?>
 <style>
 .tr-position{position:relative;}
 .btn-position {position:absolute;top:5px;left:2px;font-weight:900;}
 .btn-name{background: #ffffff;
 	border: 2px solid #ffffff;
    border-radius: 100px;
    color: #444;
    font-family: "Noto Sans KR", sans-serif;
    font-size: 10px;
    font-weight: bold;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 4px 10px;
    transition: all 0.4s ease-in-out;
	}
 </style>
        	<div class="row">
            	<div class="col-sm-12 ">
                <h1><span class="btn-lg btn-info"><?php echo stripslashes($ytx)." 검색결과  ";?></span></h1>
               </div>
            </div>
            <div class="row">  
               <div class="col-sm-3" >
                	<table class="table">
                        <thead class="table-dark"><tr class="table-success"><td colspan="2"> PC 순위 결과</td></tr></thead><tbody>               
						<?php						
                        $pcsql = sql_query($sql_head.$pctime.$and.$wheresql.$pctype.$sql_tail);
						for($i=0;$pc_rank=sql_fetch_array($pcsql);$i++){
					   ?>                            	
     					<tr class="tr-position">
				        	<td><?php echo '<span class="btn-position btn-sm btn-danger">'.$pc_rank['ranking'].'</span><span class="btn-name">'.$pc_rank['name'].'</span>';?><br />
							<?php echo '<a class="h6" href="'.$pc_rank['url_link'].'" target="_blank"><span class="h6">'.$pc_rank['title'].'</span></a>'; ?></td>
				       </tr>
			   <? } ?>
					</tbody></table>
				</div>
				<div class="col-sm-3">
                	<table class="table">
                        <thead class="table-dark"><tr class="table-success"><td colspan="2"> PC 실시간 순위 결과</td></tr></thead><tbody>   
						<?php						
						$pconlinesql = sql_query($sql_head.$pcontime.$and.$wheresql.$pconline_type.$sql_tail);
						for($i=0;$pc_online_rank=sql_fetch_array($pconlinesql);$i++){
						?>
     					<tr class="tr-position">
				        	<td><?php echo '<span class="btn-position btn-sm btn-danger">'.$pc_online_rank['ranking'].'</span><span class="btn-name">'.$pc_online_rank['name'].'</span>';?><br />                   
					        <?php echo '<a class="h6" href="'.$pc_online_rank['url_link'].'" target="_blank"><span class="h6">'.$pc_online_rank['title'].'</span></a>'; ?></td>
				      </tr>
			  <? } ?></tbody></table>
				</div>
                <div class="col-sm-3">
                	<table class="table">
                        <thead class="table-dark"><tr class="table-success"><td colspan="2"> Mobile 순위 결과</td></tr></thead><tbody>   
						<?php   
						$mobilesql = sql_query($sql_head.$mobiletime.$and.$wheresql.$mobiletype.$sql_tail);
						for($i=0;$mobile_rank = sql_fetch_array($mobilesql);$i++){
						?>
     					<tr class="tr-position">
				   	    	<td><?php echo '<span class="btn-position btn-sm btn-danger">'.$mobile_rank['ranking'].'</span><span class="btn-name">'.$mobile_rank['name'].'</span>';?><br />                       
				      		<?php echo '<a class="h6" href="'.$mobile_rank['url_link'].'" target="_blank"><span class="h6">'.$mobile_rank['title'].'</span></a>'; ?></td>
				      </tr>
			  <? } ?></tbody></table>
				</div>
                <div class="col-sm-3">
                	<table class="table">
                        <thead class="table-dark"><tr class="table-success"><td colspan="2">  Mobile 실시간 순위 결과</td></tr></thead><tbody>   
						<?php				
			 			$mobileonlinesql = sql_query("select *, a.mobile_on_rank from (select * , RANK() OVER(ORDER BY ranking asc) as mobile_on_rank from a_rank where ". $mobileonline_type.$mobiletype." and gettime = '{$chk_mobilegettime['gettime']}' ) a where ".$wheresql);			   
						for($i=0;$mobile_online_rank = sql_fetch_array($mobileonlinesql);$i++){
						?>
     					<tr class="tr-position">
				        	<td><?php echo '<span class="btn-position btn-sm btn-danger">'.$mobile_online_rank['ranking'].'</span><span class="btn-name">'.$mobile_online_rank['name'].'</span>';?><br />
				   	     <?php echo '<a class="h6" href="'.$mobile_online_rank['url_link'].'" target="_blank"><span class="h6">'.$mobile_online_rank['title'].'</span></a>'; ?></td>
				      </tr>
			  <? } ?></tbody></table>
				</div>                                 
          </div>  
		  <?php } ?>     
     </div>
</section>
<?php } ?>
<?php if($is_guest) {?>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModal').modal('show');
    });
</script>
<div align="center" style="margin:100px auto;font-weight:bold;">
<a data-toggle="modal" data-target="#myModal"><h1>Log In</h1></a>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-body">
<div id="mb_login" class="mbskin">
    <h1><?php echo $config['cf_title']?>로그인</h1>

    <form name="flogin" action="<?php echo G5_BBS_URL?>/login_check.php" onsubmit="return flogin_submit(this);" method="post">
    <input type="hidden" name="url" value="<?php echo G5_URL ?>">

    <fieldset id="login_fs">
        <legend>회원로그인</legend>
        <label for="login_id" class="sound_only">회원아이디<strong class="sound_only"> 필수</strong></label>
        <input type="text" name="mb_id" id="login_id" required="" class="frm_input required" placeholder="로그인아이디" size="20" maxlength="20">
        <label for="login_pw" class="sound_only">비밀번호<strong class="sound_only"> 필수</strong></label>
        <input type="password" name="mb_password" id="login_pw" required="" class="frm_input required" placeholder="비밀번호" size="20" maxlength="20">
        <input type="submit" value="로그인" class="btn_submit">
        <input type="checkbox" name="auto_login" id="login_auto_login" style="display:none;">
        <label for="login_auto_login">자동로그인</label>
    </fieldset>

    <aside id="login_info">
        <h3>회원로그인 안내</h3>
        <p>
            회원아이디 및 비밀번호가 기억 안나실 때는 아이디/비밀번호 찾기를 이용하십시오.<br>
            아직 회원이 아니시라면 회원으로 가입 후 이용해 주십시오.
        </p>
        <div>
            <a href="<?php echo G5_BBS_URL?>/password_lost.php" target="_blank" id="login_password_lost" class="btn02">아이디 비밀번호 찾기 </a> 
            <a href="<?php echo G5_BBS_URL?>/register.php" class="btn01"> 회원 가입</a>
        </div>
    </aside>
    </form>
<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function flogin_submit(f)
{
    return true;
}
</script>    
</div>
     </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?php } ?>


<?php
include_once(G5_THEME_PATH.'/tail.php');
?>