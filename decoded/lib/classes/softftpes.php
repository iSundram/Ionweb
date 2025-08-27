<?php
include_once('ftps.php');
class softftpes {
var $ftps_conn = false;
var $position;
var $remotefile;
var $readsize = 0;
var $ftps;
var $orig_path = '';
var $tmpsize = 0;
var $tpfile = 'php://memory';
var $writepos = 0;
var $wp = NULL; // Memory Write Pointer
var $mode;
function __construct() {
}
function softftpes() {
}
function __destruct() {
}
function connect() {
}
if(!is_object($this->ftps)){
$this->ftps = new ftps();
}
$this->ftps_conn = $this->ftps->connect($host, $port, $user, $pass);
return $this->ftps_conn;
}
function init($path, &$url = array()){
if(!preg_match('/softftpes:\/\//i', $path)){
return false;
}
$url = parse_url($path);
if(empty($url['port'])){
$url['port'] = 21;
}
if(empty($this->ftps_conn)){
$this->connect($url['host'], $url['port'], rawurldecode($url['user']), rawurldecode($url['pass']));
}
if(empty($this->ftps_conn)){
return false;
}
return $this->ftps_conn;
}
function stream_open() {
}
$this->orig_path = $path;
$this->mode = $mode;
$this->remotefile = $url['path'];
$this->position = 0;
return $this->ftps_conn;
}
function stream_read() {
}
if(empty($this->readsize)){
$this->readsize = filesize($this->orig_path);
}
$this->varname = $this->ftps->backup_softget($this->remotefile, $this->position);
$ret = substr($this->varname, 0, $count);
if(empty($ret)){
return false;
}
$this->position = $this->position + $count;
return $ret;
}*/
function stream_write() {
}
if(!is_resource($this->wp)){
$this->wp = fopen($this->tpfile, 'w+');
}
fwrite($this->wp, $data);
$this->tmpsize += strlen($data);
$ret = $strlen;
$this->position += $strlen;
if($this->tmpsize >= 4194304){
rewind($this->wp);
if(!$this->ftps->backup_softput($this->remotefile, $this->wp, $this->writepos)){
$ret = false;
}
$this->writepos += $this->tmpsize;
fclose($this->wp);
$this->wp = NULL;
$this->tmpsize = 0;
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
$ret = $this->ftps->get($url['path'], $localfile);
return $ret;
}
function mkdir() {
}
$ret = $this->ftps->mmkdir($url['path'], $mode);
return $ret;
}
function rmdir() {
}
$res = $this->ftps->rmdir($url['path']);
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
$list = $this->ftps->rawlist($dir);
foreach($list as $k => $v){
$list[$k] = $this->ftps->_parselisting($v);
if($list[$k]['name'] != $file){
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
$res = $this->ftps->delete($url['path']);
return $res;
}
function rename() {
}
$url_from = parse_url($from);
$url_to = parse_url($to);
return $this->ftps->rename($url_from['path'], $url_to['path']);
}
}