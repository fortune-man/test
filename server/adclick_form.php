<?php
include_once('./_common.php');
include_once(G5_PATH.'/head.sub.php');
include_once('./sub_site.php');



if(isset($_POST['gglkeyword']) && $_POST['gglkeyword']) $gglkeyword =  trim(strip_tags(clean_xss_attributes($_POST['gglkeyword'])));
if(isset($_POST['status']) && $_POST['status']) $status =  trim(strip_tags(clean_xss_attributes($_POST['status'])));
if(isset($_POST['mysite']) && $_POST['mysite']) $mysite =  trim(strip_tags(clean_xss_attributes($_POST['mysite'])));
if(isset($_POST['terms']) && $_POST['terms']) $terms =  trim(strip_tags(clean_xss_attributes($_POST['terms'])));


if(isset($_POST['reportmessage']) && $_POST['reportmessage']) $reportmessage =  trim(strip_tags(clean_xss_attributes($_POST['reportmessage'])));
if(isset($_POST['reportviewtime']) && $_POST['reportviewtime']) $reportviewtime =  trim(strip_tags(clean_xss_attributes($_POST['reportviewtime'])));
if(isset($_POST['reportaftertime']) && $_POST['reportaftertime']) $reportaftertime =  trim(strip_tags(clean_xss_attributes($_POST['reportaftertime'])));
if(isset($_POST['reportafterview']) && $_POST['reportafterview']) $reportafterview =  trim(strip_tags(clean_xss_attributes($_POST['reportafterview'])));

if($reportmessage||$reportviewtime||$reportaftertime||$reportafterview) {    
    sql_query( " update a_serverset set reportmessage = '{$reportmessage}'  ,
    reportviewtime = '{$reportviewtime}',
    reportaftertime = '{$reportaftertime}',
    reportafterview = '{$reportafterview}'

    ");
    mysqli_query($sub_site['zenpc']," update a_serverset set reportmessage = '{$reportmessage}',
    reportviewtime = '{$reportviewtime}',
    reportaftertime = '{$reportaftertime}',
    reportafterview = '{$reportafterview}'
      ");
}

if($status && $gglkeyword ){
		$sql_adclickset = " update a_adclick_set
                        set 
						gglkeyword = '{$gglkeyword}',
						status = '{$status}',
                        mysite = '{$mysite}',
                        terms = '{$terms}' 
                        where adid = '1' ";
        sql_query($sql_adclickset);
		unset($_POST);
}
$row = sql_fetch(" select * from a_adclick_set where adid = '1' ");

$today = date("Y-m-d");
$today_cnt = sql_fetch(" select count(*) as cnt from a_adclick_log_b where datetime like '{$today}%'  "); //and gglkeyword = '{$row['gglkeyword']}'
$total_cnt = sql_fetch(" select count(*) as cnt from a_adclick_log_b where 1 "); //where gglkeyword = '{$row['gglkeyword']}'
$log_today_sql = sql_query(" select turl , gglkeyword,  count(*) as cnt from a_adclick_log_b where datetime like '{$today}%'  group by turl order by gglkeyword, cnt desc ");//gglkeyword = '{$row['gglkeyword']}'
$log_sql = sql_query(" select turl , gglkeyword,  count(*) as cnt from a_adclick_log_b where  datetime not like '{$today}%'  group by turl order by gglkeyword, cnt desc ");//gglkeyword = '{$row['gglkeyword']}'
?>

<div class="">
<div class="section text-center">
	<h2>
        <a href="./adclick_form.php" class="btn btn-lg btn-primary">광고클릭페이지</a> 
    <?php if($is_admin) {?>
        <a href="./project_form.php" class="btn btn-md btn-info">오토튜브 메인서버</a> 
    <?php } ?>
    <a href="<?php echo G5_BBS_URL ?>/logout.php" class="btn btn-md btn-info">로그아웃</a>

    </h2>

    <form name="project-setting" action="./adclick_form.php" method="post">
        <div class="row" style="background-color: #c0e8da;">
            <div class="col-sm-2">
                <div class="form-group">                    
                    <input class="form-control" type="text" placeholder="구글검색어" name="gglkeyword" value="<?php echo $row['gglkeyword'];?>">
                </div>
            </div>
            <div class="col-sm-1">
                <div class="form-group">   
                <?php if($is_admin){?>           
                    <select class="form-control" name="status">
                       
                        <option class="form-control" value="on" <?php if($row['status'] == "on") echo 'selected="selected"';?>>ON</option>
                        <option class="form-control" value="off" <?php if($row['status'] == "off") echo 'selected="selected"';?>>OFF</option>
                    </select>
                <?php } else { ?>
                    <sapn class="form-label"><input type="hidden" name="status" value="<?php echo $row['status'];?>"><?php if($row['status'] == "on") echo 'ON';  else if($row['status']=="off") echo "OFF";?></label>
                <?php } ?>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">                    
                    <input class="form-control" type="text" placeholder="제외주소,구분" name="mysite" value="<?php echo $row['mysite'];?>">
                </div>
            </div>
            <div class="col-sm-1">
                <div class="form-group">
                <?php if($is_admin){?>                    
                    <input class="form-control" type="number" placeholder="간격(초)" name="terms" value="<?php echo $row['terms'];?>"> s
                <?php } else { ?>
                    <sapn class="form-label"><input type="hidden" name="terms" value="<?php echo $row['terms'];?>"><?php echo $row['terms'];?> s</label>
                <?php } ?>    
                </div>
            </div>            
            <div class="col-sm-2">
                <div class="form-group">
                    <span class="form-label">오늘 : <?php echo $today_cnt['cnt'];?></span> <span class="form-label">누적 : <?php echo $total_cnt['cnt'];?></span>
                </div>
            </div>
            <button type="submit" class="submit-btn btn-success">설정</button>          
        </div>
        <div class="row" style="background-color: #d6d9d8;">
            <div class="col-sm-3 text-left">    
            <?php for($i=0;$logtocnt = sql_fetch_array($log_today_sql);$i++){
                if($i==0) $line_tokeyword = $logtocnt['gglkeyword'];
                if ($line_tokeyword != $logtocnt['gglkeyword']) {
                echo '</div><div class="col-sm-3 text-left"><h5>'.$logtocnt['gglkeyword'].'('.$today.')</h5>';
                
                $line_tokeyword = $logtocnt['gglkeyword'];
                }
                ?>
                <?php if($i==0) {?><h5><?php echo $logtocnt['gglkeyword'];?> (<?php echo $today;?>)</h5><?php }?>
                <span class="btn btn-md btn-success"><?php echo $logtocnt['turl'];?>
                <font class="badge badge-info"><?php echo $logtocnt['cnt'];?></font>
                </span>
                <br>
            <?php }?>
            </div>
        </div>        
        <div class="row" style="background-color: #35cd98;">
            <div class="col-sm-3 text-left">    
            <?php for($i=0;$logcnt = sql_fetch_array($log_sql);$i++){
                if($i==0) $line_keyword = $logcnt['gglkeyword'];
                if ($line_keyword != $logcnt['gglkeyword']) {
                echo '</div><div class="col-sm-3 text-left"><h5>'.$logcnt['gglkeyword'].'</h5>';
                
                $line_keyword = $logcnt['gglkeyword'];
                }
                ?>
                <?php if($i==0) {?><h5><?php echo $logcnt['gglkeyword'];?></h5><?php }?>
                <span class="btn btn-md btn-success"><?php echo $logcnt['turl'];?>
                <font class="badge badge-info"><?php echo $logcnt['cnt'];?></font>
                </span>
                <br>
            <?php }?>
            </div>
        </div>

 

        <br>

<?php 
$serverset = sql_fetch("select * from a_serverset where project_id = 'xxx' ");
?>    
           
        <div class="row" style="background-color: #e3dcfc;">
           <div class="col-sm-12">
                <div class="form-group">
                    <span> 사이트 신고 문구 ,로 구문</span>                   
                    <input class="form-control" type="text" placeholder="신고문구 , 로 구분 " name="reportmessage" value="<?php echo $serverset['reportmessage'];?>">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group"> 
                    <span> 신고시청 최소,최대(초)</span>                        
                    <input class="form-control" type="text" placeholder="신고시청 최소,최대(초) " name="reportviewtime" value="<?php echo $serverset['reportviewtime'];?>">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">  
                    <span> 신고후 이동 최소,최대(초)</span>                      
                    <input class="form-control" type="text" placeholder="신고후 이동 최소,최대(초) " name="reportaftertime" value="<?php echo $serverset['reportaftertime'];?>">
                </div>
            </div>             
            <div class="col-sm-2">
                <div class="form-group">   
                    <span > 프로젝트영상 시청 최소,최대(초)</span>                     
                    <input class="form-control" type="text" placeholder="프로젝트영상 시청 최소,최대(초) " name="reportafterview" value="<?php echo $serverset['reportafterview'];?>">
                </div>
            </div>                        
        
        </div>
    </form>






<div class="goto_top">
<a href="#booking" class="btn btn-lg btn-default">위로</a>
<a href="#gotobottom" class="btn btn-lg btn-default">아래로</a>
</div>
</div>
<div id="gotobottom"></div>
<?

include_once(G5_PATH.'/tail.sub.php');
?>