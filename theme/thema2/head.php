<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/head.php');
    return;
}

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
?>

<!-- PRE LOADER -->

<div class="preloader">
     <div class="sk-spinner sk-spinner-wordpress">
          <span class="sk-inner-circle"></span>
     </div>
</div>

<?php if($is_member && 1==false) {?>
<!-- Navigation section  -->

<div class="navbar navbar-default navbar-static-top" role="navigation">
     <div class="container">
          <div class="navbar-header">
               <button class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
                    <span class="icon icon-bar"></span>
               </button>
               <a href="<? echo G5_URL ?>" class="navbar-brand"><img src="<? echo G5_THEME_IMG_URL; ?>/logo.png" style="width:100%;"></a>
          </div>       
          <div class="collapse navbar-collapse">
               <ul class="nav navbar-nav navbar-right">
                    <li class="active">
						<a href="<? echo G5_URL ?>" >HOME</a>
					</li>
                    <?php if($is_admin) {?>
                    <li>
						<a href="<? echo G5_ADMIN_URL ?>" >ADMIN</a>
					</li>
                    <li>
						<a href="<? echo G5_BBS_URL ?>/board.php?bo_table=info01" >DB</a>
					</li>                    
                    <?php } ?>
					<?php if ($is_member) {  ?>
					<?php
					$sql = " select *
								from {$g5['menu_table']}
								where me_use = '1'
								  and length(me_code) = '2'
								order by me_order, me_id ";
					$result = sql_query($sql, false);
					$gnb_zindex = 999; // gnb_1dli z-index 값 설정용

					for ($i=0; $row=sql_fetch_array($result); $i++) {
					?>
                    <li>
						<a href="<?php echo $row['me_link']; ?>" target="_<?php echo $row['me_target']; ?>" ><?php echo $row['me_name'] ?></a>
					</li>
					<?php }?>                    
                    <li><a href="<?php echo G5_BBS_URL ?>/logout.php" >Logout</a></li>
			        <li><a href="<?php echo G5_BBS_URL ?>/scrap.php" target="_blank" id="ol_after_scrap" class="win_scrap">스크랩</a></li>
                    <li><a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=<?php echo G5_BBS_URL ?>/register_form.php" >My page</a></li>                    
					<?php } else {?>
                    <li><a href="<?php echo G5_BBS_URL ?>/login.php" >Login</a></li>
                    <li><a href="<?php echo G5_BBS_URL ?>/register.php" >Register</a></li>
					<?}?>


               </ul>
          </div>
	</div>
</div>
<?php }?>  
<!-- Home Section -->