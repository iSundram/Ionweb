<?php
class onedrive{
var $access_token;
var $refresh_token;
var $path;
var $filename;
var $filesize = 0;
var $complete = 0;
var $offset = 0;
var $tmpsize = 0;
var $chunk = 4194304;
var $range_lower_limit = 0;
var $range_upper_limit = 0;
var $bytes_remaining = 0;
var $num_bytes = 0;
var $num_fragments = 0;
var $chunk_size = 0;
var $onedrive_file_id = '';
var $mode = '';
var $wp = NULL; // Memory Write Pointer
var $upload_url = '';
var $local_dest = '';
var $root_folder_path = 'root:';
var $graph_api_url = 'https://graph.microsoft.com/v1.0/me/drive/';
var $app_key = '31422ea4-40e4-4f0a-ab63-0324fe586c62';
var $app_secret = 'PDK8Q~tlOFNlVzR8CAKC8mg._bvzShbBEVBm6dtY';
var $app_dir = 'Softaculous Auto Installer';
var $redirect_uri = 'https://s2.softaculous.com/onedrive/callback.php';
var $scopes = 'files.Read Files.ReadWrite offline_access';
function stream_open() {
}
function stream_write() {
}
$data_size = strlen($data);
fwrite($this->wp, $data);
return $data_size;
}
function stream_close() {
}
return true;
}
function create_upload_session() {
}';
$response = $this->__curl($url, $headers, $data);
if(!empty($response['error'])){
$error[] = $response['error'];
return false;
}
$resp_data = json_decode($response['result'], true);
$this->upload_url = $resp_data['uploadUrl'];
if(empty($this->upload_url)){
$error[] = 'Failed to generate OneDrive Upload URL';
return false;
}
$this->upload_start();
return true;
}
function upload_start() {
}
if ($stream = fopen($this->local_dest, 'rb')) {
$data = stream_get_contents($stream, $this->chunk_size, $this->offset);
fclose($stream);
}
$retcode = $this->upload_append($this->upload_url, $data, $this->filesize);
if($retcode == '201 Created'){
$this->complete = 1;
}
$this->bytes_remaining = $this->bytes_remaining - $this->chunk_size;
$this->tmpsize++;
}
if(empty($this->complete)){
$error[] = 'There were some errors while uploading backup to your OneDrive account!';
return false;
}
return true;
}
function upload_append() {
}
$resp_obj= json_decode($response['result'], true);
$retcode = '404';
if (array_key_exists("nextExpectedRanges",$resp_obj)){
$retcode = '308 Resume Incomplete';
}else if(array_key_exists("id",$resp_obj)){
$retcode = '201 Created';
}else{
$retcode = '416 Requested Range Not Satisfiable';
}
if($retcode == '416 Requested Range Not Satisfiable' && !$retry){
return $this->upload_append($upload_url, $data, $final_size, true);
}
if(!empty($resp_obj['error'])){
$error[] =  'OneDrive Response : '.(!empty($resp_obj['error']) && is_array($resp_obj['error']) ? implode('. ', $resp_obj['error']) : $resp_obj['error']);
return false;
}
if($retcode != '308 Resume Incomplete' && $retcode != '201 Created'){
$error[] = $retcode;
$error[] = 'OneDrive Response : '.(!empty($resp_obj['error']) && is_array($resp_obj['error']) ? implode('. ', $resp_obj['error']) : 'OneDriveError');
return false;
}
if($retcode == '308 Resume Incomplete'){
$this->range_lower_limit = $this->range_upper_limit + 1;
$this->offset = $this->range_upper_limit + 1;
}elseif($retcode == '201 Created'){
$this->onedrive_file_id = $resp_obj['id'];
}
return $retcode;
}
function url_stat() {
}
if(!empty($filename)){
$url=$this->graph_api_url.$this->root_folder_path.rawurlencode($file_path).':';
$headers = array('Content-Type: application/json',
"Cache-Control: no-cache",
"Pragma: no-cache",
"Authorization: bearer ".$this->access_token);
$resp = $this->__curl($url, $headers, '', 'GET');
$data = json_decode($resp['result'], true);
if(array_key_exists("folder",$data)){
$mode = 0040000;	//For DIR
}else{
$mode = 0100000;	//For File
}
if(!empty($data['id'])){
$stat = array('dev' => 0,
'ino' => 0,
'mode' => $mode,
'nlink' => 0,
'uid' => 0,
'gid' => 0,
'rdev' => 0,
'size' => $data['size'],
'atime' => strtotime($data['createdDateTime']),
'mtime' => strtotime($data['fileSystemInfo']['lastModifiedDateTime']),
'ctime' => strtotime($data['fileSystemInfo']['createdDateTime']),
'blksize' => 0,
'blocks' => 0);
$this->filesize = $stat['size'];
return $stat;
}
}
return false;
}
function get_onedrive_file_id() {
}
if(empty($this->access_token)){
$this->access_token = $this->refresh_token_func($this->refresh_token);
}
$url=$this->graph_api_url.$this->root_folder_path.rawurlencode($filename).':';
$headers = array('Content-Type: application/json',
"Cache-Control: no-cache",
"Pragma: no-cache",
"Authorization: bearer ".$this->access_token);
$response = $this->__curl($url, $headers, '', 'GET');
if(!empty($response['error'])){
return false;
}
$data = json_decode($response['result'], true);
if(!empty($data['error'])){
return false;
}
$this->onedrive_file_id = $data['id'];
return $this->onedrive_file_id;
}
function download_file() {
}
$this->get_onedrive_file_id($path);
$file_stats = $this->url_stat($source);
$this->filesize = $file_stats['size'];
$url = $this->graph_api_url.$this->root_folder_path.rawurlencode($path).':';
$headers = array('Content-Type: application/json',
"Cache-Control: no-cache",
"Pragma: no-cache",
"Authorization: bearer ".$this->access_token);
$resp = $this->__curl($url, $headers, '', 'GET');
$object = json_decode($resp['result'], true);
$download_url = $object['@microsoft.graph.downloadUrl'];
$this->range_lower_limit = 0;
$this->range_upper_limit = $this->chunk - 1;
$fp = @fopen($dest, "wb");
while(!$this->__eof()){
$block = $this->__read($download_url, $this->range_lower_limit, $this->range_upper_limit);
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
function unlink() {
}
if(empty($this->onedrive_file_id)){
$this->get_onedrive_file_id($file_path);
}
$url=$this->graph_api_url.'items/'.$this->onedrive_file_id.':';
$headers = array('Content-Type: application/json',
"Cache-Control: no-cache",
"Pragma: no-cache",
"Authorization: bearer ".$this->access_token);
$resp = $this->__curl($url, $headers, '', 'DELETE');
if(!empty($resp['error'])){
$error[] = $resp['error'];
return false;
}
return true;
}
function rename() {
}
$this->get_onedrive_file_id($from_path);
$url = $url=$this->graph_api_url.'items/'.$this->onedrive_file_id.':';
$data= '{
"name": "'.$to_file.'"
}';
$headers = array('Content-Type: application/json',
"Cache-Control: no-cache",
"Pragma: no-cache",
"Authorization: bearer ".$this->access_token);
$resp = $this->__curl($url, $headers, $data, 'PATCH');
if(!empty($resp['error'])){
$error[] = $resp['error'];
return false;
}
return $resp['result'];
}
* Generate One Drive Refresh and Access Token from the Authorization Code provided
*
* @package	softaculous
* @author	Pratik Jaiswal
* @param	string $auth_code The authorization code generated by user during access grant process
* @return	string $data One Drive Refresh and Access Token which we can use to create backup files
* @since	5.7.1
*/
function generate_onedrive_token() {
}
$data = json_decode($resp['result'], true);
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
* Generate a new Access Token or Refresh Token from the previous Refresh Token.
*
* @package	softaculous
* @author	Pratik Jaiswal
* @param	string $refresh_token The refresh token generated by user during access grant process
* @return	string $token One Drive Access Token which we can use for authentication in behalf of user
* @since	5.7.1
*/
function refresh_token_func() {
}
$data = json_decode($resp['result'], true);
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
* Create Softaculous App Directory in user's One Drive account
*
* @package	softaculous
* @author	Pratik jaiswal
* @param	string $refresh_token Refresh Token of user's One Drive account to generate the access token
* @since	5.7.1
*/
function create_onedrive_app_dir() {
}
}
function create_dir() {
}
}';
$headers = array('Content-Type: application/json',
"Cache-Control: no-cache",
"Pragma: no-cache",
"Authorization: bearer ".$this->access_token);
$resp = $this->__curl($url, $headers, $data, 'POST');
if(!empty($resp['error'])){
$error[] = $resp['error'];
return false;
}
$data = json_decode($resp['result'], true);
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
function __curl() {
}
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request_type);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
if(!empty($post)){
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
}
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$resp = array();
$resp['result'] = curl_exec($ch);
$resp['error'] = curl_error($ch);
curl_close($ch);
return $resp;
}
}
?>