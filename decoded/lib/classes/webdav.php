<?php
class webdav{
var $path;
var $filename;
var $filesize = 0;
var $session_id = '';
var $offset = 0;
var $tmpsize = 0;
var $tpfile = 'php://temp'; //php://memory not working on localhost
var $mode = '';
var $wp = NULL; // Memory Write Pointer
var $credentials = array(); // The credentials
var $url = ''; // The URL to make curl call
var $chunk_url = ''; // The chunk URL to upload chunks
var $chunk_counter = 0; // The chunk counter which will be used as file names
function init() {
}else{
$_stream['scheme'] = 'https';
}
$this->url = soft_unparse_url($_stream);
if(strpos($this->url, 'remote.php/dav/files')){
$temp = explode('remote.php/dav/files', $this->url);
$temp2 = explode('/', $temp[1]);
if(!empty($temp[0]) && !empty($temp2[1])){
$this->chunk_url = $temp[0].'remote.php/dav/uploads/'.$temp2[1].'/'.md5($this->url);
}
}
return $this->url;
}
function stream_open() {
}
return $ret;
}
function upload_start() {
}
return true;
}
function stream_write() {
}
fwrite($this->wp, $data);
$this->tmpsize += strlen($data);
$data_size = strlen($data);
if($this->tmpsize >= 4194304){
rewind($this->wp);
$this->upload_append($this->wp, $this->tmpsize);
fclose($this->wp);
$this->wp = NULL;
$this->tmpsize = 0;
}
return $data_size;
}
function upload_append() {
}else{
$headers = array('Content-type: application/octet-stream',
'Content-Length: '.$data_size,
'Content-Range: bytes '.$range_start.'-'.$range_end.'/'.$total_size);
$resp = $this->request($this->url, $headers, 'PUT', $filep, $data_size);
}
if(in_array($resp['code'], array('201', '204'))){
$this->offset += $data_size;
$this->chunk_counter++;
return $data_size;
}
return false;
}
function stream_close() {
}
if(!empty($this->chunk_url)){
$headers = array('Destination: '.$this->url);
$resp = $this->request($this->chunk_url.'/.file', $headers, 'MOVE');
if($resp['code'] == 504){
if(!empty($globals['webdav_timeout']) && $globals['webdav_timeout'] > 120){
$time = (int) $globals['webdav_timeout'];
}else{
$time = 120;
}
$max_time = time() + $time;
do{
sleep(2);
$headers = array('Depth: 0');
$resp = $this->request($this->chunk_url.'/.file', $headers, 'PROPFIND');
}while($resp['code'] == 207 && time() < $max_time);
}
}
}
return true;
}
function mkdir() {
}
return false;
}
function url_stat() {
}
soft_preg_replace('/<(.*?):creationdate>(.*?)<\/(.*?):creationdate>/is', $resp['response'], $creation_date, 2);
soft_preg_replace('/<(.*?):getlastmodified>(.*?)<\/(.*?):getlastmodified>/is', $resp['response'], $last_modified, 2);
soft_preg_replace('/<(.*?):getcontentlength>(.*?)<\/(.*?):getcontentlength>/is', $resp['response'], $size, 2);
if(preg_match('/<D:getcontenttype>(.*?)directory(.*?)<\/D:getcontenttype>/is', $resp['response'])){
$mode = 0040000;	//For DIR
}else{
$mode = 0100000;	//For File
}
if(!empty($resp['response'])){
$stat = array('dev' => 0,
'ino' => 0,
'mode' => $mode,
'nlink' => 0,
'uid' => 0,
'gid' => 0,
'rdev' => 0,
'size' => $size,
'atime' => strtotime($last_modified),
'mtime' => strtotime($last_modified),
'ctime' => strtotime($creation_date),
'blksize' => 0,
'blocks' => 0);
$this->filesize = $stat['size'];
return $stat;
}
return false;
}
function stream_read() {
}
$data = json_encode(array("path" => $this->path));
$url = 'https://content.dropboxapi.com/2/files/download';
$headers = array('Authorization: Bearer '.$this->access_token,
'Dropbox-API-Arg: '.$data,
'Range:bytes = 0-'.$count,
'Content-Type:');
$sfp = fopen($GLOBALS['backup_local_path'], 'wb');
$resp = $this->__curl($url, $headers, '', 0, '', $sfp);
fclose($sfp);
return $resp;
}*/
function unlink() {
}
return false;
}
function rename() {
}
return false;
}
function download_file() {
}
function request($url, $headers = array(), $method = '', $filepointer = '', $upload_size = 0, $post = '', $download_file = '', $retry = 0){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FAILONERROR, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERPWD, implode(':', $this->credentials));
if(!empty($this->authtype)){
curl_setopt($ch, CURLOPT_HTTPAUTH, $this->authtype);
}
if(!empty($headers)){
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
}
if(!empty($method)){
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
}
if(!empty($filepointer)){
curl_setopt($ch, CURLOPT_PUT, true);
curl_setopt($ch, CURLOPT_INFILE, $filepointer);
curl_setopt($ch, CURLOPT_INFILESIZE, $upload_size);
}
if(!empty($download_file)){
curl_setopt($ch, CURLOPT_FILE, $download_file);
}
$response = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
$curl_info = curl_getinfo($ch);
curl_close($ch);
if($statusCode == 301 && !empty($curl_info['redirect_url']) && empty($retry)){
return $this->request($curl_info['redirect_url'], $headers, $method, $filepointer, $upload_size, $post, $download_file, 1);
}
$result = array();
$result['response'] = $response;
$result['code'] = $statusCode;
$result['curl_error'] = $curl_error;
$result['curl_info'] = $curl_info;
return $result;
}
}