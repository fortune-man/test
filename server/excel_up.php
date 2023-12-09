<?php
include_once('./_common.php');
include_once('./sub_site.php');
use PhpOffice\PhpSpreadsheet\Spreadsheet;    //처음 선언해야 함.
use PhpOffice\PhpSpreadsheet\Reader\Xls;    //처음 선언해야 함.

#error_reporting(E_ALL);
#ini_set("display_errors", 1);

if( isset($_POST['type']) && $_POST['type'] ) $type= $_POST['type'];
if( isset($_POST['ex_type']) && $_POST['ex_type'] ) $ex_type= $_POST['ex_type'];

$ex_type = 2;
if(!$type) alert("파일유형과 엑셀파일을 확인하세요",G5_URL."/server/project_form.php");

// 상품이 많을 경우 대비 설정변경
set_time_limit ( 0 );
ini_set('memory_limit', '50M');

if(!$_FILES['excelfile']['tmp_name']) {
	alert("등록하실 파일이 없습니다");
}
function time_convert($time) {  //45563 날짜값을 날짜포맷변경
    $t = ( $time - 25568 ) * 86400-60*60*9;  // 25569 : -1 daty 
    $t = round($t*10)/10;
    $t = date('Y-m-d',$t);
    return $t;
}


if($_FILES['excelfile']['tmp_name']) {

    $inputFileName = $_FILES['excelfile']['tmp_name'];
    #CSV 파일의 경우를 추가해줘버렸음
    if($_FILES['excelfile']['type'] == "text/csv") { 
        $handle = fopen($inputFileName,"r");
        $linenumber = 0;
        while(($raw_string = fgets($handle)) !== false){
            $row[$linenumber] = str_getcsv($raw_string);        
            #var_dump($row);
            $data[$linenumber]['A'] = $row[$linenumber]['0'];
            $data[$linenumber]['B'] = $row[$linenumber]['1'];
            $data[$linenumber]['C'] = $row[$linenumber]['2'];
            $data[$linenumber]['D'] = $row[$linenumber]['3'];
            if( $row[$linenumber]['4']) $data[$linenumber]['E'] = $row[$linenumber]['4'];
            if( $row[$linenumber]['5']) $data[$linenumber]['F'] = $row[$linenumber]['5'];            
            $linenumber++;            
        }
        fclose($handle);
      
    }
    else {
        require_once(G5_THEME_PATH.'/lib/PhpOffice/Psr/autoloader.php');                    //설치폴더 변경시 G5_THEME_PATH 수정요함
        require_once(G5_THEME_PATH.'/lib/PhpOffice/PhpSpreadsheet/autoloader.php');    //설치폴더 변경시 G5_THEME_PATH 수정요함

        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xls();

        $reader->setReadDataOnly(true);  //데이터가 있는 행까지만 읽음

        $spreadsheet = $reader->load($inputFileName);

        $data = $spreadsheet->getSheet(0)->toArray(null,true,true,true);   // >getSheet(0) 첫번째 시트 /  두번째는 getSheet(1)
        //print_r2($data);
    }
    
    $total_line = 0;  //건수 초기화 
    $dup_line = 0;   //중복체크

    if($ex_type == "1" && $data['1']['E'] && $data['1']['F']){//프로필 컴터 아이디가 있어야된다.

        if ( !empty($data) ) {

            for ($i=1; $i<=count($data);$i++ ){   //시작은 1부터.. 

                $gid = trim($data[$i]['A']);        //구글메일
                $pwd  = trim($data[$i]['B']);                       //비번
                $repair   = trim($data[$i]['C']);         //수정메일
				$memo   = trim($data[$i]['D']); //메모
				$profile   = trim($data[$i]['E']);//프로필
				$computer   = trim($data[$i]['F']);//컴퓨터
                $updatetime      = date("Y-m-d H:i:s");  //업로드시간

                //중복체크 - 계정테이블
                $sql = "select count(gid) as cnt from a_gidpwrepair  where gid = '{$gid}' "; 
                $row = mysqli_fetch_array(mysqli_query($sub_site[$type],$sql));
                $dupcount = $row['cnt'];		

                if($dupcount) {
                    //중복인경우에 계정에 중복이라고 체크한다.
					//echo " update a_gidpwrepair set gidstatus = 'duplicate' where gid = '{$gid}' "; echo "<br>";
					
                    mysqli_query($sub_site[$type]," update a_gidpwrepair set gidstatus = 'duplicate' where gid = '{$gid}' ");
					//뷰어체크 - 뷰어테이블에서
					//뷰어에 구글 계정을 지운다음에 
					//echo "update a_server set gidstatus = 'duplicate' , gidtime = '{$datetime}' where gid = '{$gid}'";echo "<br>";
					mysqli_query($sub_site[$type],"update a_server set gidstatus = 'duplicate' , gidtime = '{$datetime}' where gid = '{$gid}' ");					
					//중복건수 증가
                    $dup_line++;
                } else {
                    $sql = " insert into  a_gidpwrepair
                            set gid = '$gid',
                                pwd = '$pwd',
                                repair = '$repair',
								memo = '$memo' ,
								updatetime = '$updatetime'						
								";
                    mysqli_query($sub_site[$type],$sql);
					//echo "<br>";
					$sql1 = "update a_server set gid = '{$gid}',
                                pwd = '{$pwd}',
                                repair = '{$repair}',
								gidtime = '{$updatetime}',
								gidstatus = 'add'
								where computer = '{$computer}' and profile = '{$profile}'								
								";
                    
                    mysqli_query($sub_site[$type],$sql1);
					 
					$find_proxy = mysqli_fetch_array(mysqli_query($sub_site[$type],"select proxy from a_server where 	computer = '{$computer}' and profile = '{$profile}' "));
					if($find_proxy) mysqli_query($sub_site[$type]," update a_gidpwrepair set proxy = '{$find_proxy['proxy']}'  where 	computer = '{$computer}' and profile = '{$profile}' ");
                        $total_line++;
                }

            } //for end

        } //data check
        if ( $dup_line > 0 ) {
            alert("$computer 뷰어서버에 $total_line 건 성공(중복 $dup_line 건)되었습니다",G5_URL."/server/project_form.php");
        }else{
            alert("$computer 뷰어서버에 $total_line 건 성공 되었습니다",G5_URL."/server/project_form.php");
        }		

    }//file check
	else  if($ex_type == "2" && !$data['1']['E']){//프로필 값이 없어야된다.

        if ( !empty($data) ) {  
            for ($i=2; $i<=count($data);$i++ ){   //시작은 1부터.. 
                $gid = $data[$i]['A'];        //구글메일
                $pwd  = $data[$i]['B'];                       //비번
                $repair   = $data[$i]['C'];         //수정메일
				$memo   = $data[$i]['D'];
                $updatetime      = date("Y-m-d H:i:s");  //업로드시간

                //중복체크
                $sql = "select count(gid) as cnt from a_gidpwrepair where gid = '$gid' "; 
                $row = mysqli_fetch_array(mysqli_query($sub_site[$type],$sql));
                $dupcount = $row['cnt'];

                if($dupcount) {
                    //중복인경우에 계정에 중복이라고 체크한다.
					//echo " update a_gidpwrepair set gidstatus = 'duplicate' where gid = '{$gid}' "; echo "<br>";
					mysqli_query($sub_site[$type], " update a_gidpwrepair set gidstatus = 'duplicate' where gid = '{$gid}' ");
					//뷰어체크 - 뷰어테이블에서
					//뷰어에 구글 계정을 지운다음에 
					//echo "update a_server set gidstatus = 'duplicate' , gidtime = '{$datetime}' where gid = '{$gid}'";echo "<br>";
					mysqli_query($sub_site[$type], "update a_server set gidstatus = 'duplicate' , gidtime = '{$datetime}' where gid = '{$gid}' ");					
					//중복건수 증가					
                    $dup_line++;
                } else {
                    $sql = " insert into  a_gidpwrepair
                            set gid = '{$gid}',
                                pwd = '{$pwd}',
                                repair = '{$repair}',
								memo = '{$memo}' ,
								updatetime = '{$updatetime}'
								";
                    mysqli_query($sub_site[$type],$sql);
                        $total_line++;
                }

            } //for end

        } //data check
        if ( $dup_line > 0 ) {
            alert("$total_line 건 성공(중복 $dup_line 건)되었습니다",G5_URL."/server/project_form.php");
        }else{
            alert("$total_line 건 성공 되었습니다",G5_URL."/server/project_form.php");
        }		

    }//file check
	else alert("파일유형과 엑셀파일을 확인하세요",G5_URL."/server/project_form.php");
}//upload file check



	echo $total_line ;
	echo " --- ";
	echo $dup_line;
	exit;
?>
