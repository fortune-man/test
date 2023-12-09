<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$content_skin_url.'/style.css">', 0);
?>
<section id="home" class="main-about parallax-section" style="background:url('<? echo G5_THEME_IMG_URL; ?>/about-bg.jpg');">
     <div class="overlay"></div>
     <div class="container">
          <div class="row">

               <div class="col-md-12 col-sm-12">
                    <h1><?php echo $g5['title']; ?></h1>
               </div>

          </div>
     </div>
</section>

<section id="about">
     <div class="container">
          <div class="row">

               <div class="col-md-offset-1 col-md-10 col-sm-12">
                    <h3><?php echo $g5['title']; ?></h3>
                    <p><?php echo $str; ?></p>
               </div>

          </div>
     </div>
</section>
