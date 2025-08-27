<?php
class ftps{
var $ftp_conn;
var $stream = 'ftps';
var $error = array();
var $log_on = 1;
private $host = '';
private $port = '';
private $user = '';
private $pass = '';
function __construct() {
}
function __destruct() {
}
}
function connect() {
}
$this->ftp_conn = @ftp_ssl_connect($host, $port, 90);
if(!$this->ftp_conn){
$this->error[] = 'SSL connection not found';
return false;
}
$login_result = @ftp_login($this->ftp_conn, $user, $pass);
if(!$login_result){
$this->error[] = 'Please enter correct login details';
return false;
}
ftp_pasv($this->ftp_conn,true);
$this->host = $host;
$this->port = $port;
$this->user = $user;
$this->pass = $pass;
return true;
}
function delete() {
}
return true;
}
function is_dir() {
}
$this->chdir($old);
return true;
}
function chdir() {
}
return true;
}
function pwd() {
}
function chmod() {
}
function file_exists() {
}
if(!in_array($filename, $list) && !in_array($file, $list)){
return false;
}
return true;
}
function rename() {
}
return true;
}
function rmdir() {
}
return true;
}
function mkdir() {
}
$this->chmod($dirname, $mode);
return true;
}
function mmkdir() {
}
}
$this->chdir($pwd);
return $ret;
}
function exec() {
}
function is_exists() {
}
return false;
}
function _parselisting() {
})-([0-9]{2})-([0-9]{2}) +([0-9]{2}):([0-9]{2})(AM|PM) +([0-9]+|<DIR>) +(.+)/",$line,$lucifer)) {
$b = array();
if ($lucifer[3]<70) { $lucifer[3]+=2000; } else { $lucifer[3]+=1900; } // 4digit year fix
$b['isdir'] = ($lucifer[7]=="<DIR>");
if ( $b['isdir'] )
$b['type'] = 'd';
else
$b['type'] = 'f';
$b['size'] = $lucifer[7];
$b['month'] = $lucifer[1];
$b['day'] = $lucifer[2];
$b['year'] = $lucifer[3];
$b['hour'] = $lucifer[4];
$b['minute'] = $lucifer[5];
$b['time'] = @mktime($lucifer[4]+(strcasecmp($lucifer[6],"PM")==0?12:0),$lucifer[5],0,$lucifer[1],$lucifer[2],$lucifer[3]);
$b['am/pm'] = $lucifer[6];
$b['name'] = $lucifer[8];
} else if (!$is_windows && $lucifer=preg_split("/[ ]/",$line,9,PREG_SPLIT_NO_EMPTY)) {
$lcount=count($lucifer);
if ($lcount<8) return '';
$b = array();
$b['isdir'] = $lucifer[0]{0} === "d";
$b['islink'] = $lucifer[0]{0} === "l";
if ( $b['isdir'] )
$b['type'] = 'd';
elseif ( $b['islink'] )
$b['type'] = 'l';
else
$b['type'] = 'f';
$b['perms'] = $lucifer[0];
$b['number'] = $lucifer[1];
$b['owner'] = $lucifer[2];
$b['group'] = $lucifer[3];
$b['size'] = $lucifer[4];
if ($lcount==8) {
sscanf($lucifer[5],"%d-%d-%d",$b['year'],$b['month'],$b['day']);
sscanf($lucifer[6],"%d:%d",$b['hour'],$b['minute']);
$b['time'] = @mktime($b['hour'],$b['minute'],0,$b['month'],$b['day'],$b['year']);
$b['name'] = $lucifer[7];
} else {
$b['month'] = $lucifer[5];
$b['day'] = $lucifer[6];
if (preg_match("/([0-9]{2}):([0-9]{2})/",$lucifer[7],$l2)) {
$b['year'] = date("Y");
$b['hour'] = $l2[1];
$b['minute'] = $l2[2];
if(strtotime(sprintf("%d %s %d %02d:%02d",$b['day'],$b['month'],$b['year'],$b['hour'],$b['minute'])) > time()){
$b['year'] = $b['year'] - 1;
}
} else {
$b['year'] = $lucifer[7];
$b['hour'] = 0;
$b['minute'] = 0;
}
$b['time'] = strtotime(sprintf("%d %s %d %02d:%02d",$b['day'],$b['month'],$b['year'],$b['hour'],$b['minute']));
$b['name'] = $lucifer[8];
}
}
$bad=array("(?)");
if(in_array($b["owner"], $bad)) $b["owner"]=NULL;
if(in_array($b["group"], $bad)) $b["group"]=NULL;
$orig_perm = substr($b["perms"], 1);
$b["perms"] = 0;
$b["perms"]+=00400*(int)($orig_perm{0}=="r");
$b["perms"]+=00200*(int)($orig_perm{1}=="w");
$b["perms"]+=00100*(int)in_array($orig_perm{2}, array("x","s"));
$b["perms"]+=00040*(int)($orig_perm{3}=="r");
$b["perms"]+=00020*(int)($orig_perm{4}=="w");
$b["perms"]+=00010*(int)in_array($orig_perm{5}, array("x","s"));
$b["perms"]+=00004*(int)($orig_perm{6}=="r");
$b["perms"]+=00002*(int)($orig_perm{7}=="w");
$b["perms"]+=00001*(int)in_array($orig_perm{8}, array("x","t"));
$b["perms"]+=04000*(int)in_array($orig_perm{2}, array("S","s"));
$b["perms"]+=02000*(int)in_array($orig_perm{5}, array("S","s"));
$b["perms"]+=01000*(int)in_array($orig_perm{8}, array("T","t"));
return $b;
}
function filelist() {
}
return $list;
}
function rawlist() {
}
return $list;
}
function put() {
}
}
if(defined('SOFTACULOUS_FILE_CHMOD')){
$this->chmod($remote_file, SOFTACULOUS_FILE_CHMOD);
}
return true;
}
function get() {
}
if(file_exists($local_file)){
return true;
}
$this->error[] = "Could not fetch the file $remote_file";
return false;
}
function mput() {
}
if(!is_dir($local)) return $this->put($local, $remote);
if(empty($remote)) $remote=".";
elseif(!$this->file_exists($remote) and !$this->mkdir($remote)) return FALSE;
if($handle = opendir($local)) {
$list=array();
while (false !== ($file = readdir($handle))) {
if ($file != "." && $file != "..") $list[]=$file;
}
closedir($handle);
} else {
$this->error[] = "Can't open local folder $local";
return FALSE;
}
if(empty($list)) return TRUE;
$ret=true;
foreach($list as $el) {
if(is_dir($local."/".$el)) $t=$this->mput($local."/".$el, $remote."/".$el);
else $t=$this->put($local."/".$el, $remote."/".$el);
if(!$t) {
$ret=FALSE;
if(!$continious) break;
}
}
return $ret;
}
function mget($remote, $local=".", $continious=false, $include_only=array()) {
global $__settings;
if(!@file_exists($local)) {
if(!@mkdir($local, 0755, true)) {
$this->error[] = "Cannot create folder \"".$local."\"";
return FALSE;
}
}
$list = $this->rawlist($remote);
if($list===false) {
$this->error[] = "Can't read remote folder \"".$remote."\" contents";
return FALSE;
}
if(empty($list)) return true;
foreach($list as $k=>$v) {
$list[$k] = $this->_parselisting($v);
if($list[$k]["name"]=="." or $list[$k]["name"]=="..") unset($list[$k]);
}
$ret=true;
foreach($list as $el) {
if($el['size'] == '0'){
if($el['type'] == 'f'){
$empty_file = @fopen($local.'/'.$el['name'], "w");
@fclose($empty_file);
}elseif($el['type'] == 'd'){
mkdir($local.'/'.$el['name']);
}
@chmod($local.'/'.$el['name'], $el['perms']);
$t=$el["time"];
if($t!==-1 and $t!==false) @touch($local."/".$el["name"], $t);
unset($list[$el]);
continue;
}
$extension = get_extension($el['name']);
if(!empty($include_only) && !in_array($el['name'], $include_only) && $extension !== 'sql'){
unset($list[$el]);
continue;
}
$current_file = $local.'/'.$el['name'];
if(!empty($__settings['exclude_files']) && in_array($current_file, $__settings['exclude_files'])){
unset($list[$el]);
continue;
}
if($el["type"]=="d") {
if(!$this->mget($remote."/".$el["name"], $local."/".$el["name"], $continious)) {
$this->error[] = "Can't copy remote folder \"".$remote."/".$el["name"]."\" to local \"".$local."/".$el["name"]."\"";
$ret=false;
if(!$continious) break;
}
} else {
if(!empty($__settings['exclude_ext']) && in_array($extension, $__settings['exclude_ext'])){
unset($list[$el]);
continue;
}
if(!$this->get($remote."/".$el["name"], $local."/".$el["name"])) {
$this->error[] = "Can't copy remote file \"".$remote."/".$el["name"]."\" to local \"".$local."/".$el["name"]."\"";
$ret=false;
if(!$continious) break;
}
}
@chmod($local."/".$el["name"], $el["perms"]);
$t=$el["time"];
if($t!==-1 and $t!==false) @touch($local."/".$el["name"], $t);
}
return $ret;
}
function __softput() {
}
fwrite($file, $softdata);
fclose($file);
if($this->file_exists('/'.ltrim($remotefile, '/'))){
return true;
}
$this->error[] = "Could not put the file $remotefile";
return false;
}
function __softget() {
}
$contents = "";
while(!feof($file)){
$contents .= @fread($file, 4096);
}
return $contents;
}
function softput() {
}
if($this->file_exists('/'.ltrim($remotefile, '/'))){
return true;
}
if(defined('SOFTACULOUS_FILE_CHMOD')){
$this->chmod($remotefile, SOFTACULOUS_FILE_CHMOD);
}
$this->error[] = "Could not put the file $remotefile";
return false;
}
function softget() {
}
$contents = '';
rewind($fp);
while (!feof($fp)) {
$contents .= fread($fp, 8192);
}
fclose($fp);
return $contents;
}
function nlist() {
}
function backup_softput() {
}
fclose($fp);
return true;
}
}
error_reporting(E_ALL);
$new = new ftps();
$ret = $new->connect('127.0.0.1', 21, 'soft', 'server1234');
$ret1 = $new->pwd();
$ret2 = $new->mput('/home/soft/public_html/wp754', '/public_html/test123_enduser');
$ret3 = $new->pwd();
echo '<pre>';
var_dump($ret);
var_dump($ret1);
var_dump($ret2);
var_dump($ret3);
print_r($new->error);*/
$mode = '0755';
$copy = $new->mkdir("/home/soft/public_html/aef245/test123", $mode, 0);
var_dump($copy);
echo $new->pwd();
echo '<pre>';
print_r($new->error);*/