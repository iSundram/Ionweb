<?php
include_once('sftp.php');
class softsftp {
var $sftp_conn = false;
var $position;
var $remotefile;
var $readsize = 0;
var $sftp;
var $orig_path = '';
var $tmpsize = 0;
var $tpfile = 'php://memory';
var $writepos = 0;
var $wp = NULL; // Memory Write Pointer
var $mode;
function __construct() {
}
function softsftp() {
}
function __destruct() {
}
function connect() {
}
$this->sftp_conn = $this->sftp->connect($host, $port, $user, $pass, $pri, $passphrase);
return $this->sftp_conn;
}
function init($path, &$url = array()){
global $user;
if(!preg_match('/softsftp:\/\//i', $path)){
return false;
}
$url = parse_url($path);
if(empty($url['port'])){
$url['port'] = 21;
}
if(empty($url['pass'])){
$sftpuser = $user['remote_backup_locs'][$url['user']]['ftp_user'];
$sftppri = $user['remote_backup_locs'][$url['user']]['private_key'];
$sftppassphrase = $user['remote_backup_locs'][$url['user']]['passphrase'];
if(empty($sftppri) && empty($sftppassphrase)){
return false;
}
}
if(empty($this->sftp_conn)){
$this->connect($url['host'], $url['port'], rawurldecode((empty($url['pass']) ? $sftpuser : $url['user'])), rawurldecode($url['pass']), $sftppri, $sftppassphrase);
}
if(empty($this->sftp_conn)){
return false;
}
return $this->sftp_conn;
}
function stream_open() {
}
$this->orig_path = $path;
$this->mode = $mode;
$this->remotefile = $url['path'];
$this->position = 0;
return $this->sftp_conn;
}
function stream_read() {
}
if(empty($this->readsize)){
$this->readsize = filesize($this->orig_path);
}
$this->varname = $this->sftp->backup_softget($this->remotefile, $this->position);
$ret = substr($this->varname, 0, $count);
if(empty($ret)){
return false;
}
$this->position = $this->position + $count;
return $ret;
}*/
function stream_write() {
}
$ret = $strlen;
if(!$this->sftp->backup_softput($this->remotefile, $data)){
$ret = false;
}
return $ret;
}
function stream_close() {
}
$this->writepos += $this->tmpsize;
fclose($this->wp);
$this->wp = NULL;
$this->tmpsize = 0;
}
}
return $ret;
}
function stream_eof() {
}
function stream_tell() {
}
function download_file() {
}
$ret = $this->sftp->get($url['path'], $localfile);
return $ret;
}
function mkdir() {
}
$ret = $this->sftp->mmkdir($url['path'], $mode);
return $ret;
}
function rmdir() {
}
$res = $this->sftp->rmdir($url['path']);
return $res;
}
function url_stat() {
}
$url['path'] = cleanpath($url['path']);
if(empty($url['path'])){
$url['path'] = '/';
}
if($url['path'] == '/'){
$file = '.';
$dir = $url['path'];
}else{
$file = basename($url['path']);
$dir = dirname($url['path']);
}
$dir = cleanpath($dir);
if(empty($dir)){
$dir = '/';
}
$list = $this->sftp->rawlist($dir);
foreach($list as $k => $v){
$list[$k] = $this->sftp->_parselisting($v);
if($k != $file){
continue;
}
$stat = array('dev' => 0,
'ino' => 0,
'mode' => octdec($list[$k]['mode']),
'nlink' => 0,
'uid' => 0,
'gid' => 0,
'rdev' => 0,
'size' => $list[$k]['size'],
'atime' => $list[$k]['time'],
'mtime' => $list[$k]['time'],
'ctime' => $list[$k]['time'],
'blksize' => -1,
'blocks' => -1
);
return $stat + array_values($stat);
}
return false;
}
function unlink() {
}
$res = $this->sftp->delete($url['path']);
return $res;
}
function rename() {
}
$url_from = parse_url($from);
$url_to = parse_url($to);
return $this->sftp->rename($url_from['path'], $url_to['path']);
}
function chdir() {
}
function pwd() {
}
}