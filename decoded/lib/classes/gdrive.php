<?php
class gdrive{
var $access_token;
var $refresh_token;
var $backup_loc;
var $path;
var $filename;
var $filesize = 0;
var $init_url = '';
var $complete = 0;
var $offset = 0;
var $tmpsize = 0;
var $chunk = 4194304;
var $range_lower_limit = 0;
var $range_upper_limit = 0;
var $tpfile = '';
var $mode = '';
var $gdrive_fileid = '';
var $wp = NULL; // Memory Write Pointer
var $parents = array();
var $app_key = '391014249634-irv3vtkqkvh2dfp86ph6jdf86vc1tnkl.apps.googleusercontent.com';
var $app_secret = '9HI34jRjS-w_3Qs4aIs-Nk2B';
var $app_dir = 'Softaculous Auto Installer';
var $redirect_uri = 'https://s2.softaculous.com/gdrive/callback.php';
function stream_open() {
}
if(!empty($parent_dir) && empty($parentdir_id)){
$parentdir_id = $this->get_gdrive_fileid($parent_dir);
}
$subdir_id = $this->get_gdrive_fileid($subdir, '', $parentdir_id);
$this->parents[] = $subdir_id;
$parent_dir = $subdir;
$parentdir_id = $subdir_id;
}
$this->filename = $pathinfo['basename'];
$this->tpfile = 'php://temp';
$ret = false;
if(preg_match('/w/is', $this->mode)){
$this->offset = 0;
$this->range_lower_limit = 0;
$ret = $this->upload_start();
}
return $ret;
}
function dir_opendir() {
}
preg_match('/{(.*?)}$/is', $resp['result'], $matches);
$this->filelist = json_decode($matches[0], true);
if(empty($this->filelist['files'])){
$error[] = 'Google Drive : No File Found';
return false;
}
$this->filelist = $this->filelist['files'];
foreach($this->filelist as $i => $file) {
$this->filelist[$i] = $file['name'];
}
return true;
}
function dir_readdir() {
}
$val = $this->filelist[$key];
unset($this->filelist[$key]);
return pathinfo($val, PATHINFO_BASENAME);
}
function upload_start() {
}
$op = explode("\r\n", $resp['result']);
foreach($op as $ok => $ov){
if(preg_match('/HTTP\/1.1(\s*?)(.*?)$/is', $ov)){
soft_preg_replace('/HTTP\/1.1(\s*?)(.*?)$/is', $ov, $retcode, 2);
}
if(preg_match('/Location:(\s*?)(.*?)$/is', $ov)){
soft_preg_replace('/Location:(\s*?)(.*?)$/is', $ov, $init_url, 2);
}
}
if($retcode != '200 OK'){
$error[] = $retcode;
return false;
}
if(empty($init_url)){
$error['gdrive_err_init'] = __('There were some errors while initiating the backup on Google Drive!!');
return false;
}
$this->init_url = $init_url;
return true;
}
function stream_write() {
}
fwrite($this->wp, $data);
$this->tmpsize += strlen($data);
$data_size = strlen($data);
$lower_limit = $this->range_lower_limit;
if($this->tmpsize >= $this->chunk){
$this->range_upper_limit = $this->range_lower_limit + $this->chunk - 1;
$rem_data = '';
$rem_size = $this->tmpsize - $this->chunk;
$this->tmpsize = $rem_size;
rewind($this->wp);
if($rem_size > 0){
$append_data = fread($this->wp, $this->chunk);
$rem_data = fread($this->wp, $rem_size);
fclose($this->wp);
$this->wp = NULL;
$this->wp = fopen($this->tpfile, 'w+');
fwrite($this->wp, $append_data);
$append_data = '';
rewind($this->wp);
}
$retcode = $this->upload_append($this->init_url, $this->wp, $this->chunk);
if($retcode == '200 OK' || $retcode == '201 Created'){
$this->complete = 1;
}
fclose($this->wp);
$this->wp = NULL;
if(empty($retcode)){
$error[] = $retcode;
return false;
}
if(!empty($rem_data)){
$this->wp = fopen($this->tpfile, 'w+');
fwrite($this->wp, $rem_data);
}
}
return $data_size;
}
function upload_append() {
}
$op = explode("\r\n", $resp['result']);
soft_preg_replace('/HTTP\/1.1(\s*?)(.*?)$/is', $op[2], $retcode, 2);
if($retcode != '308 Resume Incomplete' && $retcode != '200 OK' && $retcode != '201 Created'){
$error[] = $retcode;
return false;
}
if($retcode == '308 Resume Incomplete'){
foreach($op as $ok => $ov){
if(preg_match('/Range:(\s*?)bytes=0-(.*?)$/is', $ov)){
soft_preg_replace('/Range:(\s*?)bytes=0-(.*?)$/is', $ov, $urange, 2);
}
}
if(!empty($urange)){
$this->range_lower_limit = $urange + 1;
$this->offset = $urange + 1;
}
}elseif($retcode == '200 OK' || $retcode == '201 Created'){
preg_match('/{(.*?)}$/is', $resp['result'], $matches);
$data = json_decode($matches[0], true);
$this->gdrive_fileid = $data['id'];
}
return $retcode;
}
function stream_close() {
}
$this->range_upper_limit = $this->range_lower_limit + $this->tmpsize - 1;
$this->offset += $this->tmpsize;
rewind($this->wp);
$retcode = $this->upload_append($this->init_url, $this->wp, $this->tmpsize, $this->offset);
fclose($this->wp);
$this->wp = NULL;
$this->tmpsize = 0;
if(empty($retcode)){
return false;
}
}
}
return true;
}
function url_stat() {
}
$sub_dirs = explode('/', $stream['path']);
$not_exists = 0;
$parentdir_id = '';
$list_dirs = array();
foreach($sub_dirs as $sk => $subdir){
if(empty($subdir)){
continue;
}
if(!empty($parent_dir) && empty($parentdir_id)){
$parentdir_id = $this->get_gdrive_fileid($parent_dir);
if(empty($parentdir_id)){
$not_exists = 1;
break;
}
}
$subdir_id = $this->get_gdrive_fileid($subdir, '', $parentdir_id);
if(empty($subdir_id)){
$not_exists = 1;
break;
}
$list_dirs[$subdir] = $subdir_id;
$parent_dir = $subdir;
$parentdir_id = $subdir_id;
}
if(!empty($not_exists)){
return false;
}
$url = 'https://www.googleapis.com/drive/v3/files/'.$list_dirs[$filename].'?fields=kind,name,size,createdTime,modifiedTime,mimeType,explicitlyTrashed';
$headers = array('Authorization: Bearer '.$this->access_token);
$resp = $this->__curl($url, $headers, '', 0, '', '', 'GET');
if(!empty($resp['error'])){
$error[] = $resp['error'];
return false;
}
preg_match('/{(.*?)}$/is', $resp['result'], $matches);
$data = json_decode($matches[0], true);
soft_preg_replace('/drive#(.*?)$/is', $data['kind'], $filetype, 1);
if($data['mimeType'] == 'application/vnd.google-apps.folder'){
$mode = 0040000;	//For DIR
}else{
$mode = 0100000;	//For File
}
if(!empty($data['name']) && empty($data['explicitlyTrashed'])){
$stat = array('dev' => 0,
'ino' => 0,
'mode' => $mode,
'nlink' => 0,
'uid' => 0,
'gid' => 0,
'rdev' => 0,
'size' => $data['size'],
'atime' => $data['createdTime'],
'mtime' => $data['modifiedTime'],
'ctime' => $data['createdTime'],
'blksize' => 0,
'blocks' => 0);
return $stat;
}
return false;
}
function mkdir() {
}
$not_exists = 0;
$parentdir_id = '';
foreach($sub_dirs as $sk => $subdir){
if(empty($subdir)){
continue;
}
if(!empty($parent_dir) && empty($parentdir_id)){
$parentdir_id = $this->get_gdrive_fileid($parent_dir);
if(empty($parentdir_id)){
$parentdir_id = $this->create_dir($parent_dir);
}
}
$subdir_id = $this->get_gdrive_fileid($subdir, '', $parentdir_id);
if(empty($subdir_id)){
$create = $this->create_dir($subdir, array($parentdir_id));
if(empty($create)){
break;
}
}
$parent_dir = $subdir;
$parentdir_id = $subdir_id;
}
return true;
}
function create_dir($dirname, $parents = array()){
global $error, $gdrive;
$url = 'https://www.googleapis.com/drive/v3/files';
$headers = array('Authorization: Bearer '.$this->access_token,
'Accept: application/json',
'Content-Type: application/json');
$parent_val = end($parents);
if(!empty($parent_val)){
$post = json_encode(array("name" => $dirname, "mimeType" => "application/vnd.google-apps.folder", "parents" => array($parent_val)));
}else{
$post = json_encode(array("name" => $dirname, "mimeType" => "application/vnd.google-apps.folder"));
}
$resp = $this->__curl($url, $headers, '', 0, $post, '', 'POST');
if(!empty($resp['error'])){
$error[] = $resp['error'];
return false;
}
preg_match('/{(.*?)}$/is', $resp['result'], $matches);
$data = json_decode($matches[0], true);
if(!empty($data['error'])){
if(is_array($data['error'])){
$error[] = $data['error']['code'].' : '.$data['error']['message'];
}else{
$error[] = $data['error'].' : '.$data['error_description'];
}
return false;
}
return $data['id'];
}
function refresh_token_func() {
}
preg_match('/{(.*?)}$/is', $resp['result'], $matches);
$data = json_decode($matches[0], true);
if(!empty($data['error'])){
if(is_array($data['error'])){
$error[] = $data['error']['code'].' : '.$data['error']['message'];
}else{
$error[] = $data['error'].' : '.$data['error_description'];
}
return false;
}
return $data['access_token'];
}
function rename() {
}
$this->get_gdrive_fileid($from_file);
$post = json_encode(array("name" => $to_file));
$url = 'https://www.googleapis.com/drive/v3/files/'.$this->gdrive_fileid;
$headers = array('Authorization: Bearer '.$this->access_token,
'Content-Type: application/json; charset=UTF-8',
'X-Upload-Content-Type: application/x-gzip');
$resp = $this->__curl($url, $headers, '', 0, $post, '', 'PATCH');
if(!empty($resp['error'])){
$error[] = $resp['error'];
return false;
}
return true;
}
function download_file() {
}
$pathinfo = pathinfo($stream['path']);
$src_file = $pathinfo['basename'];
$this->get_gdrive_fileid($src_file);
$file_stats = $this->url_stat($source);
$this->filesize = $file_stats['size'];
$this->range_lower_limit = 0;
$this->range_upper_limit = $this->chunk - 1;
$fp = @fopen($dest, "wb");
while(!$this->__eof()){
$block = $this->__read($this->range_lower_limit, $this->range_upper_limit);
fwrite($fp, $block);
$this->offset = $this->range_upper_limit + 1;
$this->range_lower_limit = $this->range_upper_limit + 1;
$this->range_upper_limit = ($this->range_lower_limit + $this->chunk) - 1;
if($this->range_upper_limit >= $this->filesize){
$this->range_upper_limit = $this->filesize - 1;
}
}
fclose($fp);
return true;
}
function __read() {
}
return $resp['result'];
}
function __eof() {
}
return true;
}
function get_gdrive_fileid() {
}
if(empty($this->access_token)){
$this->access_token = $this->refresh_token_func($this->refresh_token);
}
$url = 'https://www.googleapis.com/drive/v3/files?q=name=%27'.rawurlencode($filename).'%27%20and%20trashed=false'.(!empty($parent_id) ? '%20and%20parents=%27'.$parent_id.'%27' : '');
$headers = array('Authorization: Bearer '.$this->access_token);
$resp = $this->__curl($url, $headers, '', 0, '', '', 'GET');
if(!empty($resp['error'])){
$error[] = $resp['error'];
return false;
}
preg_match('/{(.*?)}$/is', $resp['result'], $matches);
$data = json_decode($matches[0], true);
if(!empty($data['error'])){
if(is_array($data['error'])){
$error[] = $data['error']['message'];
}else{
$error[] = $data['error'];
}
return false;
}
$this->gdrive_fileid = $data['files'][0]['id'];
return $this->gdrive_fileid;
}
function unlink() {
}
if(empty($this->gdrive_fileid)){
$this->get_gdrive_fileid($filename);
}
$url = 'https://www.googleapis.com/drive/v3/files/'.$this->gdrive_fileid;
$headers = array('Authorization: Bearer '.$this->access_token);
$resp = $this->__curl($url, $headers, '', 0, '', '', 'DELETE');
if(!empty($resp['error'])){
$error[] = $resp['error'];
return false;
}
return true;
}
* Generate Google Drive Refresh and Access Token from the Authorization Code provided
*
* @package	softaculous
* @author	Priya Mittal
* @param	string $auth_code The authorization code generated by user during access grant process
* @return	string $data Google Drive Refresh and Access Token which we can use to create backup files
* @since	5.0.0
*/
function generate_gdrive_token() {
}
preg_match('/{(.*?)}$/is', $resp['result'], $matches);
$data = json_decode($matches[0], true);
if(!empty($data['error'])){
if(is_array($data['error'])){
$error[] = $data['error']['code'].' : '.$data['error']['message'];
}else{
$error[] = $data['error'].' : '.$data['error_description'];
}
return false;
}
return $data;
}
* Create Softaculous App Directory in user's Google Drive account
*
* @package	softaculous
* @author	Priya Mittal
* @param	string $refresh_token Refresh Token of user's Google Drive account to generate the access token
* @since	5.0.0
*/
function create_gdrive_app_dir() {
}
}
function __curl() {
}
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
}
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request_type);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
if(!empty($filepointer)){
curl_setopt($ch, CURLOPT_UPLOAD, 1);
curl_setopt($ch, CURLOPT_INFILE, $filepointer);
curl_setopt($ch, CURLOPT_INFILESIZE, $upload_size);
}
if(!empty($post)){
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
}
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$resp = array();
$resp['result'] = curl_exec($ch);
$resp['error'] = curl_error($ch);
r_print($resp);
$errno = curl_errno($ch);
r_print($errno);
var_dump(curl_getinfo($ch, CURLINFO_HTTP_CODE)); */
curl_close($ch);
return $resp;
}
}