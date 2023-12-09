<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
?>

    <?php for ($i=0; $i<count($list); $i++) {  ?>
        <a href="<?=$list[$i]['href']?>" ><?=cut_str(strip_tags($list[$i]['wr_subject']),25,'...')?></a>
    <?php }  ?>
    <?php if (count($list) == 0) { //게시물이 없을 때  ?>
    게시물이 없습니다.
    <?php }  ?>
