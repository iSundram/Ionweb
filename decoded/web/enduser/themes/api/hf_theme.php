<?php
if(!defined('WEBUZO')){
die('Hacking Attempt');
}
function softheader() {
}
function softfooter() {
}
if(!empty($globals['num_res'])){
$GLOBALS['_api']['num_res'] = $globals['num_res'];
$GLOBALS['_api']['reslen'] = $globals['reslen'];
$GLOBALS['_api']['cur_page'] = $globals['cur_page'];
}
if(!empty($globals['hide_api_pass'])){
}
if($_GET['api'] == 'serialize'){
echo serialize($GLOBALS['_api']);
}elseif($_GET['api'] == 'json'){
header('Content-Type: application/json');
$json = json_encode($GLOBALS['_api'], JSON_PARTIAL_OUTPUT_ON_ERROR);
if ($json === false) {
echo json_last_error_msg();
} else {
echo $json;
}
}else{
echo ArrayToXML::toXML($GLOBALS['_api'], 'xml');
}
}
function error_handle() {
}
}
function success_message() {
}
}
function majorerror() {
}
function message() {
}