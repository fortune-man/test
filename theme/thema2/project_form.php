<?php


if(isset($_POST['pcusr']) && $_POST['pcusr']) $pcusr = $_POST['pcusr'];

if(isset($_POST['project_name']) && $_POST['project_name']) $project_name = $_POST['project_name'];
if(isset($_POST['project_id']) && $_POST['project_id']) $project_id = $_POST['project_id'];
if(isset($_POST['q']) && $_POST['q']) $q = $_POST['q'];
if(isset($_POST['keyword']) && $_POST['keyword']) $keyword = $_POST['keyword'];
if(isset($_POST['status']) && $_POST['status']) $status = $_POST['status'];
if(isset($_POST['percent']) && $_POST['percent']) $percent = $_POST['percent'];
if(isset($_POST['sperms']) && $_POST['sperms']) $sperms = $_POST['sperms'];
if(isset($_POST['intervals']) && $_POST['intervals']) $intervals = $_POST['intervals'];
if(isset($_POST['intertime']) && $_POST['intertime']) $intertime = $_POST['intertime'];
if(isset($_POST['time']) && $_POST['time']) $time = $_POST['time'];
if(isset($_POST['requests']) && $_POST['requests']) $requests = $_POST['requests'];
if(isset($_POST['fireout']) && $_POST['fireout']) $fireout = $_POST['fireout'];
if(isset($_POST['mode']) && $_POST['mode']) $mode = $_POST['mode'];
if(isset($_POST['delaytime']) && $_POST['delaytime']) $delaytime = $_POST['delaytime'];


if(isset($_POST['width']) && $_POST['width']) $width = $_POST['width'];
if(isset($_POST['height']) && $_POST['height']) $height = $_POST['height'];
if(isset($_POST['wposition']) && $_POST['wposition']) $wposition = $_POST['wposition'];
if(isset($_POST['hposition']) && $_POST['hposition']) $hposition = $_POST['hposition'];


if($pcusr =="mod"&& $project_id && $project_name && $percent && $sperms && $status && $keyword && $delaytime && $intervals && $fireout){
	$datetime = date("Y-m-d H:i:s");
	sql_query("update a_serverset set 
					project_name = '{$project_name}',
					q = '{$q}',
					keyword = '{$keyword}',
					status = '{$status}',
					percent = '{$percent}',
					sperms = '{$sperms}',
					intervals = '{$intervals}',
					intertime = '{$intertime}',
					time = '{$time}',
					requests = '{$request}',
					mode = '{$mode}',
					delaytime = '{$delaytime}',
					datetime = '{$datetime}',
					width = '{$width}',
					height = '{$height}',
					wposition = '{$wposition}',
					hposition = '{$hposition}'
					where project_id = 	'{$project_id}'
					");	
unset($_POST['project_id']);
unset($_POST['pcusr']);
unset($pcusr);
unset($datetime);
}

if(isset($_GET['project_id']) && $_GET['project_id']) $project_id = $_GET['project_id'];
$project_id = "xxx";


$project = sql_fetch(" select * from a_serverset where project_id = '{$project_id}' ");
?>

<style>
.overlay , #particles-js {height:34%;}

#booking {    border-radius: 20px;
    background-color: #98ddf4;
    padding: 20px;}
#booking  span.form-label {color:black;}
</style>
<div id="booking" class="section">
			<div class="booking-form">
                <form name="project-setting" action="./" method="post">
                    <div class="form-header">
                        <h1><input class="form-control" type="text" placeholder="프로젝트이름" name="project_name" value="<?php echo trim($project['project_name']);?>"></h1>
                    </div>
                    <input type="hidden" name="project_id" value="<?php echo $project_id;?>"/>
                    <input type="hidden" name="pcusr" value="mod"/>       
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <span class="form-label">영상ID값 *<font style="color:red;">https://www.youtube.com/watch?v=</font> 뒤에 ID값만 넣으세요 </span>
                                <input class="form-control" type="text" placeholder="유튜브영상 ID 값" name="q" value="<?php echo trim($project['q']);?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <span class="form-label">키워드 *<font style="color:red;">, 로 구분</font></span>
                                <input class="form-control" type="키워드" placeholder="키워드 ,로 구분합니다" name="keyword" value="<?php echo trim($project['keyword']);?>">
                            </div>
                        </div>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <span class="form-label">상태</span>
                                <select class="form-control"name="status" >
                                    <option class="form-control" value="healty" <?php echo get_selected($project['status'], 'healty') ?>>정상상태</option>
                                    <option class="form-control" value="spoil" <?php echo get_selected($project['status'], 'spoil') ?>>맛간상태</option>                
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">    
                            <div class="form-group">
                                <span class="form-label">브라우저구동시간 *<font style="color:red;">기본120이상은</font></span>
                                <input class="form-control" type="text" placeholder="브라우저구동간격" name="intervals" value="<?php echo trim($project['intervals']);?>">
                            </div>
                        </div>
                        <div class="col-sm-3">     
                            <div class="form-group">
                                <span class="form-label">구동딜레이 *<font style="color:red;">기본1은줘야함</font></span>
                                <input class="form-control" type="text" placeholder="구동딜레이"  name="delaytime" value="<?php echo trim($project['delaytime']);?>">
                            </div>
                        </div>
                        <div class="col-sm-3">     
                            <div class="form-group">
                                <span class="form-label">시청시간</span>
                                <input class="form-control" type="text" placeholder="시청시간" name="fireout" value="<?php echo trim($project['fireout']);?>">
                            </div>
                        </div>
                    
                    </div>
                    
                    
                    <div class="row">
                       <div class="col-sm-3">     
                            <div class="form-group">
                                <span class="form-label">접속뷰어수</span>
                                <input class="form-control" type="text" placeholder="접속뷰어수" name="sperms" value="<?php echo trim($project['sperms']);?>">
                            </div>
                        </div>
                        <div class="col-sm-3">     
                            <div class="form-group">
                                <span class="form-label">영상바로접속비율</span>
                                <input class="form-control" type="text" placeholder="접속비율" name="percent" value="<?php echo trim($project['percent']);?>">
                            </div>
                        </div>
                        <div class="col-sm-3">    
                            <div class="form-group">
                                <span class="form-label">검색모드</span>
                                <select class="form-control" name="mode" >
                                    <option class="form-control" value="basic" <?php echo get_selected($project['mode'], 'basic') ?>>기본상태</option>
                                    <option class="form-control" value="realtime" <?php echo get_selected($project['mode'], 'healty') ?>>실시간필터</option>                
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">     
                            <div class="form-group">
                                <span class="form-label">셋팅시간</span>
                                <input class="form-control" type="text" placeholder="셋팅시간"  name="datetime" value="<?php echo trim($project['datetime']);?>" readonly="readonly">
                            </div>
                        </div>
                    </div>
  


                    <div class="row">
                       <div class="col-sm-3">     
                            <div class="form-group">
                                <span class="form-label">가로 사이즈</span>
                                <input class="form-control" type="text" placeholder="가로사이즈" name="width" value="<?php echo trim($project['width']);?>">
                               
                            </div>
                        </div>
                        <div class="col-sm-3">     
                            <div class="form-group">
                                <span class="form-label">세로 사이즈</span>
                                <input class="form-control" type="text" placeholder="세로사이즈" name="height" value="<?php echo trim($project['height']);?>"> 
                            </div>
                        </div>
                        <div class="col-sm-3">    
                            <div class="form-group">
                                <span class="form-label">가로 간격</span>
                                <input class="form-control" type="text" placeholder="가로간격" name="wposition" value="<?php echo trim($project['wposition']);?>"> 
                            </div>
                        </div>
                        <div class="col-sm-3">     
                              <div class="form-group">
                                <span class="form-label">세로 간격</span>
                                <input class="form-control" type="text" placeholder="세로간격" name="hposition" value="<?php echo trim($project['hposition']);?>"> 
                            </div>
                        </div>
                    </div>
                    
                      
  
                    
                    <div class="form-btn">
                    <button class="submit-btn">셋팅</button>
                    </div>
                </form>
			</div>
</div>