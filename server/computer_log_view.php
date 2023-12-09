<?php
include_once('./_common.php');
include_once('./sub_site.php');



if($is_guest) {
	$var['status'] = 'nopermission';
} else {
	if(isset($_POST['computer']) && $_POST['computer']) $computer = $_POST['computer'];
	if(isset($_POST['type']) && $_POST['type']) $type = $_POST['type'];	
	//$computer_type = 'zenpc';
	if($type == 'zenpc') {//젠피씨컴퓨터
		$computer_type = "zenpc";
		$bm_sql = "";
	} else if ($type == 'proxy') {//프록시컴퓨터
		$computer_type = "proxy";
		$bm_sql = "";
	} else if ($type == 'mobile') {//모바일컴퓨터
		$computer_type = "proxy"; 
		$bm_sql = "_b";
	}	

	if($computer_type && $computer){
		$day_3ago = date("Y-m-d H:i:s", strtotime("-3 Day"));
		mysqli_query($sub_site[$computer_type]," DELETE FROM a_error_log{$bm_sql} where datetime < '{$day_3ago}' ");		

		$sql = mysqli_query($sub_site[$computer_type]," SELECT COUNT(profile) AS cnt , profile , computer FROM  a_error_log{$bm_sql}  
							where computer = '{$computer}' 
								and content not in('viewstart') 
								Group by profile HAVING COUNT(profile) > 0 order by cnt desc 
					");	//'viewstart','servercheck'
		?>
		<table>
			<thead>
				<tr>
					<th>컴퓨터</th>
					<th>프로필</th>
					<th>로그기록(시청은 제외, 3일이내)</th>
				</tr>
			</thead>
			<tbody>
		<?php							
		for($i=0;$row=sql_fetch_array($sql);$i++) { ?>
				<tr style="line-height:1px;">
					<td><?php echo $row['computer'] ;?></td>
					<td><?php echo $row['profile'] ;?></td>
					<td><?php echo $row['cnt'];?></td>
				</tr>
		<?php }?>
			</tbody>
		</table>
<?php
	} else {?>
		<span>자료가 없습니다</span>	
<?php
	}
}
?>