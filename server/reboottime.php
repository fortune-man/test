<?php
include_once('./_common.php');

if(isset($_GET['x']) && $_GET['x']) $x = strip_tags(clean_xss_attributes($_GET['x']));
if(!$x) exit;

$var['a'] = $var['b'] = '';

$xx = explode(",",$config['cf_10']);

$var['a'] = $xx['0'];
$var['b'] = $xx['1'];

header('Content-type: application/json');
echo json_encode($var);