<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

<!-- 스크랩 시작 { -->
<div id="scrap_do" class="new_win mbskin">
    <h1 id="win_title">스크랩하기</h1>

    <form name="f_scrap_popin" action="./scrap_popin_update.php" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">

    <div class="tbl_frm01 tbl_wrap">
        <table>
        <caption>제목 확인 및 댓글 쓰기</caption>
        <tbody>
        <tr>
            <th scope="row">제목</th>
            <th scope="row">이름</th>
            <th scope="row">생년월일</th>
            <th scope="row">아이디</th>
            <th scope="row">주소</th>
            <th scope="row">기타정보</th>
        </tr>
        <tr>
            <td><?php echo get_text(cut_str($write['wr_subject'], 255)) ?></td>
            <td><?php echo get_text(cut_str($write['wr_1'], 255)) ?></td>            
            <td><?php echo get_text(cut_str($write['wr_2'], 255)) ?></td>
            <td><?php echo get_text(cut_str($write['wr_3'], 255)) ?></td>
            <td><?php echo get_text(cut_str($write['wr_4'], 255)) ?></td>
            <td><?php echo get_text(cut_str($write['wr_5'], 255)) ?></td>
        </tr>                
        </tbody>
        </table>
    </div>

    <p class="win_desc">
    	스크랩내용<br />
<textarea name="wr_content" id="wr_content"></textarea>
    </p>


    <div class="win_btn">
        <input type="submit" value="확인" class="btn_submit">
    </div>
    </form>
</div>
<!-- } 스크랩 끝 -->