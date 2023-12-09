<?php
include_once('./_common.php');
$g5['title'] = "엑셀 업로드(Excel Upload)";
include_once(G5_PATH.'/_head.php');
?>
<style>
.new_excel{border:3px solid #ccc; padding:0 20px 20px 20px; margin-top:20px;}
.new_excel h1{margin:10px 0;}
.excel_info {margin-bottom:10px; line-height:18px;}
.btn_confirm {margin-top:15px;}
</style>

<div class="new_excel">
    <h4><?php echo $g5['title']?></h4>

    <div class="excel_info">
        <p>
			엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong> 로 저장하셔야 합니다.
        </p>
		 <p>
			<a href="<?php echo $board_skin_url?>/zen.xls">신규등록 엑셀파일 샘플 다운로드 Click</a>.
        </p>
	</div>

    <form name="fitemexcelup" id="fitemexcelup" method="post" action="./excel_up.php" enctype="MULTIPART/FORM-DATA" autocomplete="off">
	<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <div id="excelfile_upload" style="text-align:center;">
        <input type="hidden" name="ex_type" value="2"> 
       <input type="file" name="excelfile" id="excelfile">
    </div>

    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="엑셀파일 등록" class="btn_submit">
		 <a href="<?php echo G5_URL?>/server/excel.php" class="btn_submit" style="color:#FFF; text-decoration:none;">엑셀목록게시판</a>
    </div>

    </form>

</div>
<div class="row">
<?php 
$sql = sql_query(" select * from a_gidpwrepair where 1 ");
for($i=0;$row=sql_fetch_array($sql);$i++){	
?>
<div class="col-sm-3">
	<span id="proxy-<?php echo $i;?>" class="proxy"><?php echo $row['proxy'];?></span>
    <span id="gid-<?php echo $i;?>" class="gid"><?php echo $row['gid'];?></span>
    <span id="pw-<?php echo $i;?>" class="pw"><?php echo $row['pw'];?></span>
    <span id="repair-<?php echo $i;?>" class="repair"><?php echo $row['repair'];?></span>
</div>
<?php } ?>
</div>


<?
include_once(G5_PATH.'/_tail.php');
?>
