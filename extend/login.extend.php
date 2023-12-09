<?php
if (!defined('_GNUBOARD_')) exit;
//로그아웃 상태(비회원)이면 로그인 또는 특정 페이지로 강제이동시키기
//echo $_SERVER['SCRIPT_FILENAME'];
if($is_guest && ($_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/index.php" && $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/log.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/project.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/zenpc.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/zenpc_html.php" 
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/zenpcup.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/zenup.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/proxy.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/proxy_html.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/proxyup.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/ajax_gid.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/computer_reset.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/filter_reset.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/computer_log_view.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/interact.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/test_api.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/proxy/index.php"

&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/rt.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/server/reboottime.php"

&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/requestrankss.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/rts.php"

&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/adclick/index.php"
&& $_SERVER['SCRIPT_FILENAME'] !="C:/AutoSet10/ranking/reportclick/index.php"

) ) {

	//로그인, 회원가입, 캡챠, 본인확인 등 적용 제외 페이지
	$except_page_arr = array('login.php',
							'latestrank.php',
							'latestranks.php',
							'requestrank.php',
							'requestranks.php',
							
							'login_check.php',
							
							'kcaptcha_result.php',
							'kcaptcha_image.php',
							'kcaptcha_session.php',
							'kcaptcha_mp3.php',
							'kcpcert_form.php',
							'kcpcert_result.php',
							'AuthOnlyReq.php',
							'AuthOnlyRes.php',
							'hpcert1.php',
							'hpcert2.php',
							'ipin1.php',
							'ipin2.php',
							'email_certify.php',
							'ajax.mb_email.php',
							'ajax.mb_hp.php',
							'ajax.mb_id.php',
							'ajax.mb_nick.php',
							'ajax.mb_recommend.php',
							'alert.php',
							'alert_close.php',
							'sns_send.php',
							'write_token.php',
							'ping.php',
							'rss.php');
	//
	/*'password_lost.php',
							'password_lost_certify.php',
							'password_lost2.php',
							'register.php',
							'register_email.php',
							'register_email_update.php',
							'register_form.php',
							'register_form_update.php',
							'register_result.php',
*/

	//그누보드루트/rss/index.php 파일을 열어 제일 상단에 $except_page = true; 라고 등록해 주셔야 통합 RSS가 제외 됩니다.
	//추가로 제외하고 싶은 페이지가 있으면, $except_page_arr 에 등록 또는 해당 페이지의 최상단(common.php 파일보다 먼저)에 $except_page = true; 라고 등록해 주세요.

	//현재 페이지가 제외 페이지에 해당되지 않으면 이동시키기
	if(!in_array(basename($_SERVER['PHP_SELF']), $except_page_arr) && !$except_page) {
		//로그인 페이지(login.php) 이외의 다른 곳으로 이동시킬려면 해당 url을 등록해 주세요.
		goto_url(G5_BBS_URL.'/login.php');
	}
}

// 회원이면...
if($member['mb_level'] >= 3) {
	; // 승인완료된 3등급 이상인 회원은 통과
} else {
	//회원가입 후 승인나지 않은 회원(2등급) 이동페이지 ex) 승인심사 중 안내페이지 등
	//해당 페이지의 게시판아이디 또는 페이지아이디 지정
	if($bo_table != "aaa" || $pid != "bbb") {
		//goto_url('이동주소'); // 적용시 주석 풀어 주세요.
	}
}

?>