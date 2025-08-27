<?php
** Softaculous SDK
** Refer the following guide for examples :
** http://www.softaculous.com/docs/SDK
*/
class Softaculous_SDK{
var $login = '';
var $debug = 0;
var $error = array();
var $data = array();
var $scripts = array();
var $iscripts = array();
var $cookie;
var $format = 'serialize';
* A Function to Login with Softaculous Parameters.
*
* @package      API
* @author       Jigar Dhulla
* @param        string $url URL of which response is needed
* @param        array $post POST DATA
* @return       string $resp Response of URL
* @since     	 4.1.3
*/
function curl($url, $post = array(), $cookies = array(), $header = 0){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
if(!empty($post)){
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
}
if(!empty($this->cookie)){
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
}
if(!empty($header)){
curl_setopt($ch, CURLOPT_HEADER, 1);
}
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$resp = curl_exec($ch);
if($resp === false){
$this->error[] = 'cURL Error : '.curl_error($ch);
}
curl_close($ch);
return $resp;
}
* A Function to Login with Softaculous Parameters.
*
* @package      API
* @author       Jigar Dhulla
* @param        string $act Actions
* @param        array $post POST DATA
* @return       string $resp Response of Actions
* @since     	 4.1.3
*/
function curl_call($act, $post = array()){
$url = $this->login;
$tmp_url = parse_url($url);
if($tmp_url['port'] == '2222' && empty($this->cookie)){
$cmd_login = $tmp_url['scheme'].'://'.$tmp_url['host'].':'.$tmp_url['port'].'/CMD_LOGIN';
$cmd_post = array('username' => $tmp_url['user'],
'password' => $tmp_url['pass'],
'referer' => '/');
$res = $this->curl($cmd_login, $cmd_post, array(), 1);
$res = explode("\n", $res);
foreach($res as $k => $v){
if(preg_match('/^'.preg_quote('set-cookie:', '/').'(.*?)$/is', $v, $mat) && empty($this->cookie)){
$this->cookie = trim($mat[1]);
}
}
}
if(!strstr($url, '?')){
$url = $url.'?';
}
$url = $url.$act;
if(!strstr($url, 'api=')){
$url = $url.'&api='.$this->format;
}
return $this->curl($url, $post);
}
* A Function to Login with Softaculous Parameters.
*
* @package      API
* @author       Jigar Dhulla
* @param        string $act Actions
* @param        array $post POST DATA
* @return       string $resp Response of Actions
* @since     	 4.1.3
*/
function curl_unserialize($act, $post = array()){
$resp = $this->curl_call($act, $post);
return $this->format_decode($resp);
}
* A Function that will INSTALL scripts. If the DATA is empty script information is retured
*
* @package		API
* @author		Jigar Dhulla
* @param		int $sid Script ID
* @param		array $data DATA to POST
* @return		string $resp Response of Action. Default: Serialize
* @since		4.1.3
*/
function install($sid, $data = array(), $autoinstall = array()){
$this->list_installed_scripts();
if(empty($this->iscripts[$sid])){
$this->error[] = 'Script Not Found';
return false;
}
if($this->iscripts[$sid]['type'] == 'js'){
$act = '&act=js&soft='.$sid;
}elseif($this->iscripts[$sid]['type'] == 'perl'){
$act = '&act=perl&soft='.$sid;
}elseif($this->iscripts[$sid]['type'] == 'java'){
$act = '&act=java&soft='.$sid;
}elseif($this->iscripts[$sid]['type'] == 'python'){
$act = 'act=python&soft='.$sid;
}else{
$act = '&act=software&soft='.$sid;
}
if(!empty($autoinstall)){
$act = $act.'&autoinstall='.rawurlencode(base64_encode(serialize($autoinstall)));
}
if(!empty($data)){ // If empty DATA, return script information
$data['softsubmit'] = 1;
}
return $this->curl_call($act, $data);
}
* A Function that will IMPORT existing installations in Softaculous
*
* @package		API
* @author		Jigar Dhulla
* @param		int $sid Script ID
* @param		array $data DATA to POST
* @return		string $resp Response of Actions. Default: Serialize
* @since		4.1.3
*/
function import($sid, $data = array()){
$this->list_installed_scripts();
if(empty($this->iscripts[$sid])){
$this->error[] = 'Script Not Found';
return false;
}
$act = '&act=import&soft='.$sid;
$data['softsubmit'] = 1;
return $this->curl_call($act, $data);
}
* A Function that will UPDATE scripts
*
* @package		API
* @author		Jigar Dhulla
* @param		string $insid Installation ID
* @param		array $data DATA to POST
* @return		string $resp Response of Actions. Default: Serialize
* @since		4.1.3
*/
function upgrade($insid, $data = array()){
$act = '&act=upgrade&insid='.$insid;
if(!empty($data)){ // If empty DATA, return upgrade information of the installation
$data['softsubmit'] = 1;
}
return $this->curl_call($act, $data);
}
* A Function that will Restore the Backup
*
* @package		API
* @author		Jigar Dhulla
* @param		string $name Backup File Name
* @param		array $data DATA to POST
* @return		string $resp Response of Actions. Default: Serialize
* @since		4.1.3
*/
function restore($name, $data = array()){
$act = '&act=restore&restore='.$name;
$data['restore_ins'] = 1;
return $this->curl_call($act, $data);
}
* A Function that will Remove the Installation
*
* @package		API
* @author		Jigar Dhulla
* @param		string $insid Installation ID
* @param		array $data DATA to POST
* @return		string $resp Response of Actions. Default: Serialize
* @since		4.1.3
*/
function remove($insid, $data = array()){
$act = '&act=remove&insid='.$insid;
$data['removeins'] = 1;
return $this->curl_call($act, $data);
}
* A Function that will Backup the Installation. Backup process will go in background.
* You will receive an email in case of any error
*
* @package		API
* @author		Jigar Dhulla
* @param		string $insid Installation ID
* @param		array $data DATA to POST
* @return		string $resp Response of Actions. Default: Serialize
* @since		4.1.3
*/
function backup($insid, $data = array()){
$act = '&act=backup&insid='.$insid;
$data['backupins'] = 1;
return $this->curl_call($act, $data);
}
* A Function that will remove the Backup of the Installation. Remove Backup process will go in background.
* You will receive an email in case of any error
*
* @package		API
* @author		Divij Satra
* @param		string $backup_file Backup File Name e.g webmail.376_48118.2013-01-23_23-11-41.tar.gz
* @return		string $resp Response of Actions. Default: Serialize
* @since		4.1.9
*/
function remove_backup() {
}
* A Function that will save the Backup File of the Installation at given path.
*
* @package		API
* @author		Divij Satra
* @param		string $download_file Backup File Name e.g webmail.376_48118.2013-01-23_23-11-41.tar.gz
* @param		string $path Path where Backup File wiil be saved e.g '/opt'
* @return		void
* @since		4.1.9
*/
function download_backup() {
}else{
$chk = substr($path , -1);
if($chk != '/'){
$path = $path.'/';
}
}
}else{
$path = '';
}
$resp = $this->curl_call($act);
$fp = fopen($path.$download_file, 'w+');
fwrite($fp, $resp);
fclose($fp);
echo "File saved at ".$path.$download_file;
}
* A Function that will list installations
*
* @package		API
* @author		Jigar Dhulla
* @param		bool $showupdates. [True : Show only installations with update.]
* @return		array $resp Installations
* @since		4.1.3
*/
function installations() {
}
* A Function that will list scripts
*
* @package		API
* @author		Jigar Dhulla
* @return		array $scripts List of Softaculous Scripts
* @since		4.1.3
*/
function list_scripts() {
}
$file = $this->curl('http://api.softaculous.com/scripts.php?in=serialize');
$this->scripts = unserialize($file);
if(empty($this->scripts)){
$this->error[] = 'Scripts were not loaded.';
return false;
}else{
return true;
}
}
* A Function that will list Backups
*
* @package		API
* @author		Jigar Dhulla
* @return		array $resp Backups
* @since		4.1.3
*/
function list_backups() {
}
* A Function that will list installed scripts
*
* @package		API
* @author		Jigar Dhulla
* @return		array $scripts List of Installed Softaculous Scripts
* @since		4.1.3
*/
function list_installed_scripts() {
}
$resp = $this->curl_call('act=home');
$resp = $this->format_decode(trim($resp));
$this->iscripts = $resp['iscripts'];
if(empty($this->iscripts)){
$this->error[] = 'Installed Scripts were not loaded.';
return false;
}else{
return true;
}
}
* Prints result
*
* @category	 Debug
* @param        Array $data
* @return       array
*/
function r_print() {
}
function format_decode() {
}elseif($this->format == 'xml'){
return $data;
}else{
return unserialize($data);
}
}
}
class Softaculous_API extends Softaculous_SDK{
}
@set_time_limit(100);
include_once('sdk.php');
$new = new Softaculous_SDK();
$new->login = 'https://user:password@domain.com:2083/frontend/paper_lantern/softaculous/index.live.php';
if(isset($_POST['domain'])){
$data['softdomain'] = $_POST['domain']; // Domain Name
}
if(isset($_POST['directory'])){
$data['softdirectory'] = $_POST['directory']; // Directory of the installation
}
if(isset($_POST['submit'])){
$res = $new->import($_POST['scripts'], $data); // Import Function
$res = $new->format_decode($res); // Unserialize the serialized array
if(!empty($res['done'])){
echo 'Imported';
}else{
print_r($res['error']); // Reason why Import was not successful
}
}
if(empty($res)){
$new->list_installed_scripts();
echo '<form action="" method="post">Select script you want to import : <select name="scripts">';
foreach($new->iscripts as $sk => $sv){
echo '<option value="'.$sk.'">'.$sv['name'].'</option>';
}
echo '</select><br />
<tr>
<td>Enter the Domain : <input type="text" name="domain" value=""></td>
<tr><br />
<tr>
<td>Enter the Directory : <input type="text" name="directory" value=""></td>
<tr><br/>
<input type="submit" name="submit" value="submit">
</form>';
}
?>*/
@set_time_limit(100);
include_once('sdk.php');
$new = new Softaculous_SDK();
$new->login = 'https://user:password@domain.com:2083/frontend/paper_lantern/softaculous/index.live.php';
$data['softdomain'] = 'domain.com'; // OPTIONAL - By Default the primary domain will be used
$data['softdirectory'] = 'wp887'; // OPTIONAL - By default it will be installed in the /public_html folder
$data['admin_username'] = 'admin';
$data['admin_pass'] = 'pass';
$data['admin_email'] = 'admin@domain.com';
$data['softdb'] = 'wp887';
$data['dbusername'] = 'wp887';
$data['dbuserpass'] = 'wp887';
$data['language'] = 'en';
$data['site_name'] = 'Wordpess wp887';
$data['site_desc'] = 'WordPress API Test';
$res = $new->install(26, $data); // Will install WordPress(26 is its script ID)
$res = $new->format_decode($res);
if(!empty($res['done'])){
echo 'Installed';
}else{
echo 'Installation Failed<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
?>*/
?>