<?php
include_once('./_common.php');

error_reporting(E_ALL);
ini_set("display_errors", 1);

  // 첨부파일이 존재한다면 실행
if($_FILES['proxyfile']['name']) {
    $baseDownFolder = "../download/";

    // 실제 파일명
    $real_filename = $_FILES['proxyfile']['name'];	

    // 파일 확장자 체크
    $nameArr = explode(".",  $real_filename);
    $extension = $nameArr[sizeof($nameArr) - 1];	

    // 임시 파일명 (현재시간_랜덤수.파일 확장자) - 파일명 중복될 경우를 대비해 임시파일명을 덧붙여 저장하려함
    $tmp_filename = time() . '_p_' . mt_rand(0,99999) . '.' . strtolower($extension);

    // 저장 파일명 (실제파일명@@@임시파일명)
    $thumbnail_file = $real_filename . '---' . $tmp_filename;

    if( !move_uploaded_file($_FILES["proxyfile"]["tmp_name"], $baseDownFolder.$tmp_filename) ) {
    	echo 'upload error';
    }

    // 파일 권한 변경 (생략가능_추후 변경할 수 있게 권한 변경함)
   //chmod($baseDownFolder.$tmp_filename, 0755);

 alert("업로드 되었습니다",G5_URL."/server/proxy.php");   
  }
?>
