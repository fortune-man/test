<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/tail.php');
    return;
}
?>



<!-- Footer Section -->
<?php if($is_member && 1==false) {?>
<footer>
     <div class="container">
          <div class="row">

               <div class="col-md-5 col-md-offset-1 col-sm-6">
                    <h3>공지사항</h3>
                    <p>
						<?php
						// 이 함수가 바로 최신글을 추출하는 역할을 합니다.
						// 사용방법 : latest(스킨, 게시판아이디, 출력라인, 글자수);
						// 테마의 스킨을 사용하려면 theme/basic 과 같이 지정
						echo latest('theme/basic', "notice", 1,18);
						?>					
					</p>
                    <div class="footer-copyright">
                         <p>CopyRight &copy; Group By EYE.</p>
                    </div>
               </div>

               <div class="col-md-4 col-md-offset-1 col-sm-6">
                    <h3>SiteInfo</h3>
                    <p><i class="fa fa-globe"></i> 주소정보.</p>
                    <p><i class="fa fa-phone"></i> 연락처</p>
                    <p><i class="fa fa-save"></i> 메일주소</p>
               </div>

               <div class="clearfix col-md-12 col-sm-12">
               </div>
              
          </div>
     </div>
</footer>
<?php } ?>
<!-- Back top -->
<a href="#back-top" class="go-top"><i class="fa fa-angle-up"></i></a>

<!-- SCRIPTS -->


<script src="<?php echo G5_THEME_JS_URL ?>/bootstrap.min.js"></script>
<?php if (defined('_INDEX_') && $is_member) {?>
<script src="<?php echo G5_THEME_JS_URL ?>/particles.min.js"></script>
<script src="<?php echo G5_THEME_JS_URL ?>/app.js"></script>
<?php } ?>
<script src="<?php echo G5_THEME_JS_URL ?>/jquery.parallax.js"></script>
<script src="<?php echo G5_THEME_JS_URL ?>/smoothscroll.js"></script>
<script src="<?php echo G5_THEME_JS_URL ?>/custom.js"></script>


<?php
include_once(G5_THEME_PATH."/tail.sub.php");
?>