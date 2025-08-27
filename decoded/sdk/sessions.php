<?php
class Webuzo_Sessions{
var $sess_path = '/var/webuzo/sessions';
var $users_path = '/var/webuzo/users';
var $user = [];
var $globals = [];
var $SESS = [];
function __construct() {
}
function __destruct() {
}
}
* Generate a random string for the given length
*
* @package      string
* @author       Pulkit Gupta
* @param        int $length The number of charactes that should be returned
* @return       string Randomly geterated string of the given number of charaters
* @since     	 1.0
*/
function generateRandStr() {
}
}
return str_shuffle($randstr);
}
* Create a Session KEY and register the COOKIE
*
* @category	 Session
* @return       void
* @version    	 1.0
*/
function make_session() {
}
$this->SESS = $SESS;
}
*  Logs out a USER !
*
* @category	 User
* @return       array
* @version    	 1.0
*/
function doLogout() {
}
* Check Session
*
* @category	 Session
* @return       int Returns $id if session available else 0
* @version    	 1.0
*/
function check_session_key() {
}$~', $id) == 0){
return 0;
}else{
return $id;
}
}elseif(isset($_COOKIE[$globals['cookie_name'].'_sid']) &&
strlen(trim($_COOKIE[$globals['cookie_name'].'_sid'])) == 32){
$id = trim($_COOKIE[$globals['cookie_name'].'_sid']);
if(preg_match('~^[A-Za-z0-9]{32}$~', $id) == 0){
return 0;
}else{
return $id;
}
}else{
return 0;
}
}
function show_login() {
}
function is_admin_panel() {
}
if(isset($globals['admin_port_nonssl']) && !empty($globals['admin_port_nonssl'])){
$panel_ports[] = $globals['admin_port_nonssl'];
}
return in_array($_SERVER['SERVER_PORT'], $panel_ports);
}
* Is the user logged in ?
*
* @category	 User
* @return       boolean
* @version    	 1.0
*/
function isLogin() {
}
}
$sess_file = $this->sess_path.'/sess_'.$this->SESS['sid'];
if(!file_exists($sess_file)){
return false;
}
$data = json_decode(file_get_contents($sess_file), true);
if($_COOKIE['self_sess_key'] != $data['self_sess_key']){
if($data['ip'] != $_SERVER['REMOTE_ADDR'] && empty($this->globals['no_session_ip'])){
unlink($sess_file);
return '';
}
}
touch($sess_file);
$username = $data['user'];
if(empty($username)){
return false;
}
$this->SESS['self_sess_key'] = $data['self_sess_key'];
if(!$this->is_admin_panel()){
if(!empty($_COOKIE['loginAs'])){
$loginAs = $_COOKIE['loginAs'];
}
if(!empty($_POST['loginAs'])){
$loginAs = $_POST['loginAs'];
}
if(!empty($_GET['loginAs'])){
$loginAs = $_GET['loginAs'];
}
if(!empty($loginAs) && file_exists($this->users_path.'/'.$loginAs.'/info')){
if($username == 'root'){
$this->orig_user = $username;
$username = $loginAs;
}else{
$prospect = @json_decode(file_get_contents($this->users_path.'/'.$loginAs.'/info'), true);
if($prospect['owner'] == $username){
$this->orig_user = $username;
$username = $loginAs;
}
}
}
if($username == 'root'){
return false;
}
}
if($username == 'root'){
$info = array();
$info['user'] = 'root';
}else{
$info = $this->users_path.'/'.$username.'/info';
if(!file_exists($info)){
return false;
}
$info = file_get_contents($info);
$info = json_decode($info, true);
}
if(empty($info)){
return false;
}
if($this->orig_user != 'root' && !empty($info['status']) && $info['status'] == 'suspended'){
return false;
}
$this->user = $info;
return $info;
}
function get_session_cookie_array() {
}
return $r;
}
}