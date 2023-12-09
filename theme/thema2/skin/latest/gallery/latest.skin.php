<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
?>

    <?php for ($i=0; $i<count($list); $i++) {  ?>

		<div class="blog-post-thumb">
			 <div class="blog-post-image">
				  <a href="<?=$list[$i]['href']?>" ><img src="<?=$list[$i]['file'][0]['path']?>/<?=$list[$i]['file'][0]['file']?>" class="img-responsive" alt="Blog Image" /></a>
			 </div>
			 <div class="blog-post-title">
				  <h3><a href="<?=$list[$i]['href']?>"><?=$list[$i]['subject']?></a></h3>
			 </div>
			 <div class="blog-post-format">
				  <span><?=$list[$i]['name']?></span>
				  <span><i class="fa fa-date"></i> <?=$list[$i]['datetime']?></span>
				  <span><i class="fa fa-comment-o"></i> <?=$list[$i]['comment_cnt']?> Comments</span>
			 </div>
			 <div class="blog-post-des">
				  <p><?=cut_str(strip_tags($list[$i]['wr_content']),200,'...')?></p>
				  <a href="<?=$list[$i]['href']?>" class="btn btn-default">Continue Reading</a>
			 </div>
		</div>


    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    게시물이 없습니다.
    <?php }  ?>
