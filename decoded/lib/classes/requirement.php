<?php
class requirement{
var $req = array();
var $mariadb = array('5.6' => array('10.0.0', '10.1.0'),
'5.7' => array('10.2.0', '10.3.0', '10.4.0'));
var $optr = array(
'gt' => 'greater than';
'lt' => 'less than';
'ge' => 'greater than equal to';
'le' => 'less than equal to';
'eq' => 'is';
'ne' => 'should not be equal to';
);
function replace_callback() {
}
function __construct() {
}
if(method_exists($softpanel, 'soft_req_check') && !defined('SOFTADMIN')){
$req = $softpanel->soft_req_check($req);
}
foreach($req as $k => $v){
if($v['type'] == 'extension'){
$ext_res = $this->req_loaded_extension($v);
if($ext_res){
if(!$this->req_loaded_extension_version($v)){
}
}else{
}
}
if($v['type'] == 'function_exists'){
if(!$this->req_function_exists($v)){
}
}
if($v['type'] == 'version'){
if(!$this->req_version($v)){
}
}
}
}// End of constructor
function parse_requirements() {
}
}
return $tmp_req;
}
function aefer_req_check() {
}
$ftp_softpath = npath($dom, $dom['softpath']);
$_file = implode('', file($globals['path'].'/lib/aefer/sreq.php'));
$_file = aefer_dump($params, '[[[sreq]]]', $_file);
if($_GET['debug'] == 'fetch'){
$conn->Verbose = true;
$conn->LocalEcho = true;
}
if(!$conn->softput($ftp_softpath.'/sreq.php', $_file)){
$error[] = 'Error in uploading requirements checker';
aefer_cleanup();
return false;
}
soft_log(1, 'sreq is uploaded successfully');
rename_htaccess($dom, 1);
soft_log(2, 'renamed .htaccess');
$result = aefer_response($dom['softurl'].'/sreq.php');
soft_log(3, 'aefer response for sreq:'.var_export($result, 1));
rename_htaccess($dom, 0);// Just to be safe. This should already be done by sreq.php
soft_log(2, 'reverted .htaccess');
$conn->delete($ftp_softpath.'/sreq.php');
if(aefer_was_successful($result)){
soft_log(1, 'aefer was successful...DONE');
return $result['sreq'];
}else{
$error['no_response'] = $l['no_aefer_resp'];
aefer_cleanup();
return false;
}
return $params;
}
function req_function_exists() {
}
if(!aefer() && !isset($param['result'])){
$command = eu_php_bin().' -r "if (function_exists(\''.$param['name'].'\')) exit(0); else exit(1);"';
@exec($command, $out, $ret);
if(!empty($_GET['debug'])){
echo $command;
r_print($out);
r_print($ret);
}
$output = implode('', $out);
}
if(!empty($ret) || !empty($param['result'])){
if(empty($param['err'])){
$param['err_key'] = 'req_func_nf_'.$param['name'];
$param['err_val'] = __('Required $0 function not found', array(strtoupper($param['check']))).' : <b>'.$param['name'].'</b>';
}else{
$param['err_key'] = $param['err'];
$param['err_val'] = '{{'.$param['err'].'}}';
}
$error[$param['err_key']] = $param['err_val'];
return false;
}
return true;
}
function req_loaded_extension() {
}
if(defined('PHP_EXT_EXHAUSTIVE') && !isset($param['result'])){
$ret = 1;
}
if(empty($param['err'])){
$param['err_key'] = 'req_ext_nf_'.$param['name'];
$param['err_val'] = __('Required $0 extension not found :', array(strtoupper($param['check']))).' <b>'.$param['name'].'</b>';
}else{
$param['err_key'] = $param['err'];
$param['err_val'] = '{{'.$param['err'].'}}';
}
if(!empty($param['name'])){
if(!aefer() && !defined('PHP_EXT_EXHAUSTIVE') && !isset($param['result'])){
if(in_array($param['name'], array('mysql', 'mysqli', 'pdo_mysql'))){
$command = sphpbin().' -r "if (extension_loaded(\''.$param['name'].'\') || extension_loaded(\'nd_'.$param['name'].'\')) exit(0); else exit(1);" 2>&1';
}else{
$command = sphpbin().' -r "if (extension_loaded(\''.$param['name'].'\')) exit(0); else exit(1);" 2>&1';
}
@exec($command, $out, $ret);
if(!empty($_GET['debug'])){
echo $command;
r_print($out);
r_print($ret);
}
}
if(!empty($ret) || !empty($param['result'])){
$error[$param['err_key']] = $param['err_val'].($_GET['debug'] == 'showcmd' ? $command : '');
return false;
}
}
return true;
}
function req_loaded_extension_version() {
}
if(!empty($param['version']) && !empty($param['operator'])){
if(!version_compare($found, $param['version'], $param['operator'])){
$error[$param['err_key']] = __('Required $0 $1 extension version $2 $3 BUT found $4', array(strtoupper($param['check']), $param['name'], $this->optr[$param['operator']], $param['version'], $found));
return false;
}
}
}
return true;
}
function req_version() {
}else{
if($param['check'] == 'mysql'){
if(empty($softpanel->mysql_path)) return;
$command = (!empty($softpanel->mysql_path) ? $softpanel->mysql_path : '/usr/bin/mysql').' -V';
}elseif($param['check'] == 'perl'){
if(empty($softpanel->perl_bin)) return;
$command = $softpanel->perl_bin.' -v';
}elseif($param['check'] == 'php'){
$command = '';
}
@exec($command, $out, $ret);
$output = implode('', $out);
if($param['check'] == 'php'){
$output = sphpversion();
}
if($param['check'] == 'perl'){
soft_preg_replace('/This is perl,(.*?)\(\*\)/is', $output, $ver, 1);
$output = trim($ver,'v');
}
if($param['check'] == 'mysql'){
if(preg_match('/(.*?)Distrib(\s*?)(.*?)-MariaDB/is', $output)){
soft_preg_replace('/(.*?)Distrib(\s*?)(.*?)-MariaDB/is', $output, $ver, 3);
if(!empty($param['mariadb'])){
$param['version'] = $param['mariadb'];
}else{
$short_ver = substr($param['version'], 0, (strrpos($param['version'], '.')));
if(!empty($this->mariadb[$short_ver])){
$param['version'] = current($this->mariadb[$short_ver]);
}
}
}else{
soft_preg_replace('/(.*?)Distrib(.*?)\,/is', $output, $ver, 2);
if(empty($ver)){
soft_preg_replace('/(.*?)Ver (.*?) /is', $output, $ver, 2);
}
}
$output = trim($ver);
}
}
if($param['check'] == 'php'){
if(defined('NO_PHP_VER_REQ')) return true;
if(defined('php_version')){
$output = php_version;
}
if(!defined('php_version')){
define('php_version', $output);
}
}
if($param['check'] == 'perl'){
if(aefer()){ // At the moment we are skipping this for PERL
return true;
}
if(defined('NO_PERL_VER_REQ')) return true;
if(defined('perl_version')){
$output = perl_version;
}
}
if($param['check'] == 'mysql'){
if(aefer()){ // At the moment we are skipping this for MySQL
return true;
}
if(defined('NO_MYSQL_VER_REQ')) return true;
if(defined('mysql_version')){
$output = mysql_version;
}
if(is_remote_dbhost($__settings['softdbhost']) && !defined('mysql_version') && !defined('NO_REQUIREMENT')){
if(is_array($GLOBALS['remote_mysql_ver_check'])){
$GLOBALS['remote_mysql_ver_check'][] = $param;
}else{
$GLOBALS['remote_mysql_ver_check'] = array($param);
}
return true;
}
}
if(empty($param['err'])){
$param['err_key'] = 'req_ver_nf_'.$param['check'];
$param['err_val'] = __('Required $0 version $1 $2 AND found version is : ', array(strtoupper($param['check']), $this->optr[$param['operator']], $param['version']));
}else{
$param['err_key'] = $param['err'];
$param['err_val'] = '{{'.$param['err'].'}}';
}
if(!version_compare($output, $param['version'], $param['operator']) && !defined('NO_REQUIREMENT')){
$error[$param['err_key']] = $param['err_val'].$output;
}
}
function remote_mysql_version_check() {
}else{
$param['err_key'] = $param['err'];
$param['err_val'] = '{{'.$param['err'].'}}';
}
$conn = @soft_mysql_connect($__settings['softdbhost'], $__settings['softdbuser'], $__settings['softdbpass'], true);
$output = soft_mysql_get_server_info($conn);
if(preg_match('/(.*?)-(.*?)-MariaDB/is', $output)){
soft_preg_replace('/(.*?)-(.*?)-MariaDB/is', $output, $ver, 2);
if(!empty($ver)){
$output = trim($ver);
}
}
if(!version_compare($output, $param['version'], $param['operator']) && !defined('NO_REQUIREMENT')){
$error[$param['err_key']] = $param['err_val'].$output;
}
}
if(!empty($error)){
return false;
}
}
}