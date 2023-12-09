<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<!-- 게시판 목록 시작 { -->



<section id="blog">
     <div class="container">
          <div class="row">

               <div class="col-md-offset-1 col-md-10 col-sm-12">

			<?php for ($i=0; $i<count($list); $i++) {
				if($i>0 && ($i % $bo_gallery_cols == 0))
					$style = 'clear:both;';
				else
					$style = '';
				if ($i == 0) $k = 0;
				$k += 1;
				if ($k % $bo_gallery_cols == 0) $style .= "margin:0 !important;";
			 ?>

                    <div class="blog-post-thumb">
                         <div class="blog-post-image">
                              <a href="<?php echo $list[$i]['href'] ?>">

								<?php
								
									$thumb = get_list_thumbnail($board['bo_table'], $list[$i]['wr_id'], $board['bo_gallery_width'], $board['bo_gallery_height']);

									if($thumb['src']) {
										$img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'"  class="img-responsive" alt="Blog Image" >';
									} else {
										$img_content = '<span style="width:'.$board['bo_gallery_width'].'px;height:'.$board['bo_gallery_height'].'px">no image</span>';
									}

									echo $img_content;
								
								 ?>

                              </a>
                         </div>
                         <div class="blog-post-title">
                              <h3>
								<a href="<?php echo $list[$i]['href'] ?>">
									<?php echo $list[$i]['subject'] ?>
								</a>							  
							  </h3>
                         </div>
                         <div class="blog-post-format">
                              <span><?php echo $list[$i]['name'] ?></span>
                              <span><i class="fa fa-date"></i> <?php echo $list[$i]['datetime'] ?></span>
                              <span><i class="fa fa-comment-o"></i> <?=number_format($list[$i]['comment_cnt'])?> Comments</span>
                         </div>
                         <div class="blog-post-des">
                              <p><?=cut_str(strip_tags($list[$i]['wr_content']),200,'...')?></p>
                              <a href="<?php echo $list[$i]['href'] ?>" class="btn btn-default">Continue Reading</a>
                         </div>
                    </div>

				<?}?>
				<?php if (count($list) == 0) {?>

                    <div class="blog-post-thumb">
                         <div class="blog-post-title">
                              <h3>등록된 글이 없습니다.</h3>
                         </div>
                    </div>

				<? } ?>
 

					<?php if ($list_href || $is_checkbox || $write_href) { ?>
					<div class="bo_fx">
						<?php if ($is_checkbox) { ?>
						<ul class="btn_bo_adm">
							<li><input type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value"></li>
							<li><input type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value"></li>
							<li><input type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value"></li>
						</ul>
						<?php } ?>

						<?php if ($list_href || $write_href) { ?>
						<ul class="btn_bo_user">
							<?php if ($list_href) { ?><li><a href="<?php echo $list_href ?>" class="btn_b01">목록</a></li><?php } ?>
							<?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02">글쓰기</a></li><?php } ?>
						</ul>
						<?php } ?>
					</div>
					<?php } ?>		
					



					<?php if($is_checkbox) { ?>
					<noscript>
					<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
					</noscript>
					<?php } ?>

					<!-- 페이지 -->
					<?php echo $write_pages;  ?>

					<!-- 게시물 검색 시작 { -->
					<fieldset id="bo_sch">
						<legend>게시물 검색</legend>

						<form name="fsearch" method="get">
						<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
						<input type="hidden" name="sca" value="<?php echo $sca ?>">
						<input type="hidden" name="sop" value="and">
						<label for="sfl" class="sound_only">검색대상</label>
						<select name="sfl" id="sfl" style="width:30%;">
							<option value="wr_subject"<?php echo get_selected($sfl, 'wr_subject', true); ?>>제목</option>
							<option value="wr_content"<?php echo get_selected($sfl, 'wr_content'); ?>>내용</option>
							<option value="wr_subject||wr_content"<?php echo get_selected($sfl, 'wr_subject||wr_content'); ?>>제목+내용</option>
							<option value="mb_id,1"<?php echo get_selected($sfl, 'mb_id,1'); ?>>회원아이디</option>
							<option value="mb_id,0"<?php echo get_selected($sfl, 'mb_id,0'); ?>>회원아이디(코)</option>
							<option value="wr_name,1"<?php echo get_selected($sfl, 'wr_name,1'); ?>>글쓴이</option>
							<option value="wr_name,0"<?php echo get_selected($sfl, 'wr_name,0'); ?>>글쓴이(코)</option>
						</select>
						<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
						<input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" required id="stx" class="frm_input required" size="15"  style="width:30%;" maxlength="20">
						<input type="submit" value="검색" class="btn_submit">
						</form>
					</fieldset>
					<!-- } 게시물 검색 끝 -->

               </div>


          </div>
     </div>
</section>





<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = "./board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == 'copy')
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->
          </div>
     </div>
</section>