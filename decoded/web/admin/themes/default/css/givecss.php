<?php
if(!empty($_REQUEST['test'])){
echo 1;
die();
}
$data = '';
$self_path = dirname(__FILE__);
include_once(dirname(dirname(dirname(dirname(dirname($self_path))))).'/universal.php');
if(empty($globals['dev'])){
die('// Dev mode is off !');
}
$give = @$_REQUEST['files'];
if(!empty($give)){
$give = explode(',', $give);
foreach($give as $file){
if(preg_match('/\.\./is', $file)){
continue;
}
if(preg_match('/\//is', $file)){
$file = dirname($self_path).'/'.$file;
}else{
$file = $self_path.'/'.$file;
}
$final[md5($file)] = $file;
}
}
if(empty($final)){
die('// No files were given !');
}
foreach($final as $k => $v){
$data .= file_get_contents($v)."\n\n";
}
if(!empty($_REQUEST['target'])){
$ret = file_put_contents($self_path.'/'.$_REQUEST['target'], $data);
if($ret === false || !file_exists($self_path.'/'.$_REQUEST['target'])){
echo 'Could not write the file';
die();
}
}
if(function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')){
ob_start('ob_gzhandler');
}
header("Content-type: text/css; charset: UTF-8");
$filetime = filemtime($self_path.'/style.css');
header("Cache-Control: must-revalidate");
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $filetime)) {
header('Last-Modified: '.gmdate('D, d M Y H:i:s', $filetime).' GMT', true, 304);
return;
}else{
header('Last-Modified: '.gmdate('D, d M Y H:i:s', $filetime).' GMT', true, 200);
}
echo $data;