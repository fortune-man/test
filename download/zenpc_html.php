<?php
include_once('../_common.php');

//error_reporting(E_ALL);
//ini_set("display_erross",1);


$gidcheck = '';

$set = '';
$proxy = '';

?>
			<div class="col-sm-6">
				<form action="./zenpcup.php" enctype="multipart/form-data" method="post"  >
					<input type="file" name="zenfile" />
					<button class="btn btn-md btn-success">보내기</button>
				</form>
			</div>

