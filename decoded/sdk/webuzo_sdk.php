<?php
include_once(dirname(__FILE__).'/sdk.php');
class Webuzo_SDK extends Softaculous_SDK{
var $login = '';
var $debug = 0;
var $error = array();
var $data = array();
var $apps = array(); // List of Apps
var $installed_apps = array(); // List of Installed Apps
* Initalize API login
*
* @category	 Login
* @param        string $user The username to LOGIN
* @param        string $pass The password
* @param        string $host The host to perform actions
* @return       void
*/
function __construct() {
}else{
$this->login = 'https://'.$user.':'.$pass.'@'.$host.':2003/index.php';
}
}
* A Function to Login with Softaculous Parameters.
*
* @package      API
* @author       Pulkit Gupta
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
if(!empty($this->sess_cookie)){
$cookies = array_merge($cookies, $this->sess_cookie);
}
if(!empty($cookies)){
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIE, http_build_query($cookies, null, ';'));
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
* Configure webuzo
*
* @category	 Configure
* @param        string $ip The IP Address on configure webuzo
* @param        string $user The username for webuzo
* @param        string $email The email for webuzo
* @param        string $pass The password
* @param        string $host The Primary domain
* @param        string $ns1 The nameserver
* @param        string $ns2 The nameserver
* @param        string $license The License Key
* @return       void
*/
function webuzo_configure($ip, $user, $email, $pass, $host, $ns1 = '', $ns2 ='', $license = '', $data = array()){
$data['uname'] = $user;
$data['email'] = $email;
$data['pass'] = $pass;
$data['rpass'] = $pass;
$data['domain'] = $host;
$data['ns1'] = $ns1;
$data['ns2'] = $ns2;
$data['lic'] = $license;
$data['submit'] = 1;
$data['api'] = 1;
$this->login = 'http://'.$ip.':2004/install.php?';
$return = $this->curl($this->login, $data);
$this->chk_error();
return $return;
}
* A Function that will INSTALL apps. If the DATA is empty script information is retured
*
* @package		API
* @author		Jigar Dhulla
* @param		int $sid Script ID
* @param		array $data DATA to POST
* @return		string $resp Response of Action. Default: Serialize
* @since		4.1.3
*/
function install_app() {
}
$act = 'act=apps&app='.$appid.'&install=1';
$resp = $this->curl_call($act);
$this->chk_error();
return $resp;
}
* List Services
*
* @category	 Database
* @return		 string $resp Response of Action. Default: Serialize
*/
function list_services() {
}
* A Function that will REMOVE apps. If the DATA is empty script information is retured
*
* @package		API
* @author		Jigar Dhulla
* @param		int $sid Script ID
* @param		array $data DATA to POST
* @return		string $resp Response of Action. Default: Serialize
* @since		4.1.3
*/
function remove_app() {
}
$act = 'act=apps&app='.$appid.'&remove=1';
$resp = $this->curl_call($act);
$this->chk_error();
return $resp;
}
* A Function that will list scripts
*
* @package		API
* @author		Jigar Dhulla
* @return		array $scripts List of Softaculous Scripts
* @since		4.1.3
*/
function list_apps() {
}
$data = $this->curl_unserialize('');
$this->apps = $data['apps'];
if(empty($this->apps)){
$this->error[] = 'Apps were not loaded.';
return false;
}else{
return true;
}
}
* A Function that will list installed scripts
*
* @package		API
* @author		Jigar Dhulla
* @return		array $scripts List of Installed Softaculous Scripts
* @since		4.1.3
*/
function list_installed_apps() {
}
$resp = $this->curl_unserialize('act=apps_installations');
$this->installed_apps = $resp['apps_ins'];
return $resp['apps_ins'];
}
* Check login error
*
* @category	 error
* @return       array
*/
function chk_error() {
}
}
* List Domains
*
* @category	 Domain
* @return		string $resp Response of Action. Default: Serialize
*/
function list_domains() {
}
* Add Domain
*
* @category	 Domain
* @param        string $domain The domain to add
* @param		 (Optional) string $domainpath The path for an ADD-ON domain
* @param		 (Optional) string $ip Different IP Address for domain
* @return		 string $resp Response of Action. Default: Serialize
*/
function add_domain() {
}
* Delete Domain
*
* @category	 Domain
* @param        string $domain The domain to delete
* @return		 string $resp Response of Action. Default: Serialize
*/
function delete_domain() {
}
* Change ROOT/Endusers's Password
*
* @category	 Password
* @param        string $pass The NEW password for the USER
* @return		 string $resp Response of Action. Default: Serialize
*/
function change_password() {
}
$data['newpass'] = $data['conf'] = $pass;
$data['changepass'] = 1;
$resp = $this->curl_call($act, $data);
$this->chk_error();
return $resp;
}
* Change File Manager Password
*
* @category	 Password
* @param        string $pass The NEW password for the File manager
* @return		 string $resp Response of Action. Default: Serialize
*/
function change_fileman_pwd() {
}
* Change Apache Tomcat Manager's Password
*
* @category	 Password
* @param        string $pass The NEW password for the Apache Tomcat
* @return		 string $resp Response of Action. Default: Serialize
*/
function change_tomcat_pwd() {
}
* List FTP users
*
* @category	 FTP
* @return       array
*/
function list_ftpuser() {
}
* Add FTP user
*
* @category	 FTP
* @param        string $user The FTP username
* @param        string $pass The password for the FTP user
* @param        string $directory The Directory path for the FTP users relative to /HOME/USER
* @param        string $quota_limit (Optional) Define a quota for the user
* @return		 string $resp Response of Action. Default: Serialize
*/
function add_ftpuser() {
}else{
$data['quota'] = 'unlimited';
}
$data['create_acc'] = 1;
$resp = $this->curl_call($act, $data);
$this->chk_error();
return $resp;
}
* Edit FTP user
*
* @category	 FTP
* @param        string $user FTP user to EDIT data
* @param        string $quota_limit (Optional) Specify quota limit to the user
* @return		 string $resp Response of Action. Default: Serialize
*/
function edit_ftpuser() {
}else{
$data['quota'] = 'unlimited';
}
$data['edit_record'] = 1;
$resp = $this->curl_call($act, $data);
$this->chk_error();
return $resp;
}
* Change FTP User's Password
*
* @category	 FTP
* @param        string $user FTP user to change Password
* @param        string $pass New password for the FTP user
* @return		 string $resp Response of Action. Default: Serialize
*/
function change_ftpuser_pass() {
}
* Delete FTP user
*
* @category	 FTP
* @param        string $user FTP user to delete
* @return		 string $resp Response of Action. Default: Serialize
*/
function delete_ftpuser() {
}
* List FTP Connections
*
* @category	 FTP
* @return       array
*/
function list_ftp_connections() {
}
* Delete FTP Connection
*
* @category	 FTP
* @param        string		$ftp_connection_id	FTP Connection Process ID
* @return		 string		$resp				Response of Action. Default: Serialize
*/
function delete_ftp_connection() {
}
* List Database with its size and users
*
* @category	 Database
* @return		 string $resp Response of Action. Default: Serialize
*/
function list_database() {
}
* Add Database
*
* @category	 database
* @param        string $db_name Database name to create
* @return		 string $resp Response of Action. Default: Serialize
*/
function add_database() {
}
* Delete Database
*
* @category	 database
* @param        string $db_name Database name to delete
* @return		 string $resp Response of Action. Default: Serialize
*/
function delete_database() {
}
* List Database Users
*
* @category	 Database
* @return		 string $resp Response of Action. Default: Serialize
*/
function list_db_user() {
}
* Add Database User
*
* @category	 database
* @param        string $db_user Database username to ADD
* @param        string $pass Password for the database user
* @return		 string $resp Response of Action. Default: Serialize
*/
function add_db_user() {
}
* Delete Database user
*
* @category	 database
* @param        string $db_user Database user to delete
* @return		 string $resp Response of Action. Default: Serialize
*/
function delete_db_user() {
}
* Set Privileges for a User to a specific database
*
* @category	 database
* @param        string $database Database name to ADD privileges
* @param        string $db_user Database users name to ADD privileges
* @param        string $host Database host
* @param        string $prilist Set of privileges to be given to the User
* @return		 string $resp Response of Action. Default: Serialize
*/
function set_privileges() {
}
* Edit settings
*
* @category	 Advance settings
* @param        string $email Specify email address to SET
* @param        int $ins_email (Optional) Set 1 to receive installation emails, otherwise 0
* @param        int $rem_email (Optional) Set 1 to receive installations removal email,
otherwise 0
* @param        int $edit_email (Optional) Set 1 to receive installations editting email,
otherwise 0
* @return		 string $resp Response of Action. Default: Serialize
*/
function edit_settings() {
}
* Manage Services
*
* @category	 Advanced Settings
* @param        string $service_name Specify the service to restart
E.g exim, dovecot, tomcat, httpd, named, pure-ftpd, mysqld
* @return		 string $resp Response of Action. Default: Serialize
*/
function manage_service() {
}
* Enable/Disable suPHP
*
* @category	 Security
* @param        string $action Specify on/off to START/STOP suPHP respectively
* @return		 string $resp Response of Action. Default: Serialize
*/
function manage_suphp() {
}else{
$data['suphpon'] = NULL;
}
$data['editapachesettings'] = 1;
$resp = $this->curl_call($act, $data);
$this->chk_error();
return $resp;
}
* Enable NGINX Proxy
*
* @category	 SystemApps
* @param        Integer $port Port for Proxy Server
* @param        Integer $htaccess  - 0 to enable .htaccess
*									- 1 to disable .htaccess
* @param        String $proxy_server - Either "httpd" or "httpd2"
* @return		 array $resp
*/
function enable_proxy() {
}
* Disable NGINX Proxy
*
* @category	 SystemApps
* @param        String $proxy_server - Default Webserver to be set - "httpd,httpd2,nginx,lighttpd"
* @return		 array $resp
*/
function disable_proxy() {
}
* List DNS Record
*
* @category	 Server Settings
* @param        string $domain Specify domain to list DNS records
* @return		 string $resp Response of Action. Default: Serialize
*/
function list_dns_record() {
}
* Add DNS Record
*
* @category	 Server Settings
* @param        string $domain Specify domain to ADD DNS record
* @param        string $name Specify record name
* @param        string $ttl Specify TTL
* @param        string $type Specify TYPE of record
* @param        string $address Specify destination address
* @return		 string $resp Response of Action. Default: Serialize
*/
function add_dns_record() {
}
* Edit DNS Record
*
* @category	 Server Settings
* @param        string $id Specify ID of record to EDIT
* @param        string $domain Specify domain to ADD DNS record
* @param        string $name Specify record name
* @param        string $ttl Specify TTL
* @param        string $type Specify TYPE of record
* @param        string $address Specify destination address
* @return		 string $resp Response of Action. Default: Serialize
* @return       array
*/
function edit_dns_record() {
}
* Delete DNS Record
*
* @category	 Server Settings
* @param        string $id ID of Dns record for delete
* @param        string $domain Domain for the DNS record for delete
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_dns_record() {
}
* List CRON
*
* @category	 Server Settings
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_cron() {
}
* Add a CRON
*
* @category	 Server Settings
* @param        string $minute Minute of the cron part
* @param        string $hour Hour of the cron part
* @param        string $day Day of the cron part
* @param        string $month Month of the cron part
* @param        string $weekday Weekend of the cron part
* @param        string $cmd Command of the cron part
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_cron() {
}
* Edit CRON
*
* @category	 Server Settings
* @param        string $id ID of the cron record. Get from the list of cron
* @param        string $minute Minute of the cron part
* @param        string $hour Hour of the cron part
* @param        string $day Day of the cron part
* @param        string $month Month of the cron part
* @param        string $weekday Weekend of the cron part
* @param        string $cmd Command of the cron part
* @return		 string $resp Response of Actions. Default: Serialize
*/
function edit_cron() {
}
* Delete CRON
*
* @category	 Server Settings
* @param        string $id ID of the cron record. Get from the list of cron
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_cron() {
}
* List SSL Key
*
* @category	 Security
* @return		string $resp Response of Actions. Default: Serialize
*/
function list_ssl_key() {
}
* Create SSL Key
*
* @category	 Security
* @param        string $description Domain name or any name for the SSL Key
* @param        string $keysize Size of the SSl Key
* @return		 string $resp Response of Actions. Default: Serialize
*/
function create_ssl_key() {
}
* Upload SSL Key
*
* @category	 Security
* @param        string $description Domain name or any name for the SSL Key
* @param        string $keypaste Entire SSL Key
* @return		 string $resp Response of Actions. Default: Serialize
*/
function upload_ssl_key() {
}
* Detail SSL Key
*
* @category	 Security
* @param        string $domain Specify domain name to detail view of SSL Key
* @return		 string $resp Response of Actions. Default: Serialize
*/
function detail_ssl_key() {
}
* Delete SSL Key
*
* @category	 Security
* @param        string $domain Specify domain name to delete SSL Key
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_ssl_key() {
}
* List SSL CSR
*
* @category	 Security
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_ssl_csr() {
}
* Create SSL CSR
*
* @category	 Security
* @param        string $domain Domain name for the CSR
* @param        string $country_code Two latter Country Code
* @param        string $state Name of the State
* @param        string $locality Name of the Location
* @param        string $org Name of the Organitaion
* @param        string $org_unit Name of the Organitaion unit
* @param        string $passphrase Password prase
* @param        string $email Email address
* @param        string $key KEY use for creating new csr. if you want to generate new key then pass "newkey" as argument.
* @return		 string $resp Response of Actions. Default: Serialize
*/
function create_ssl_csr() {
}
* Detail SSL CSR
*
* @category	 Security
* @param        string $domain Specify domain name to detail view of SSL CSR
* @return		 string $resp Response of Actions. Default: Serialize
*/
function detail_ssl_csr() {
}
* Delete SSL CSR
*
* @category	 Security
* @param        string $domain Specify domain name to delete SSL CSR
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_ssl_csr() {
}
* List SSL Certificate
*
* @category	 Security
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_ssl_crt() {
}
* Create SSL Certificate
*
* @category	 Security
* @param        string $domain Domain for the Certificate
* @param        string $country_code Two latter Country Code
* @param        string $state Name of the State
* @param        string $locality Name of the Location
* @param        string $org Name of the Organitaion
* @param        string $org_unit Name of the Organitaion unit
* @param        string $email Email address
* @param        string $key KEY use for creating new csr. if you want to generate new key then pass "newkey" as argument.
* @return		 string $resp Response of Actions. Default: Serialize
*/
function create_ssl_crt() {
}
* Upload SSL Certificate
*
* @category	 Security
* @param        string $keypaste Entire certificate.
* @return		 string $resp Response of Actions. Default: Serialize
*/
function upload_ssl_crt() {
}
* Detail SSL Certificate
*
* @category	 Security
* @param        string $domain Specify domain name to detail view of SSL Certificat
* @return		 string $resp Response of Actions. Default: Serialize
*/
function detail_ssl_crt() {
}
* Delete SSL Certificate
*
* @category	 Security
* @param        string $domain Specify domain name to delete SSL Certificat
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_ssl_crt() {
}
* List Blocked IP.
* @return		string $resp Response of Actions. Default: Serialize
*/
function list_ipblock() {
}
* Block IP
*
* @category	 Security
* @param        string $ip IP Address for block
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_ipblock() {
}
* Delete Blocked IP
*
* @category	 Security
* @param        string $ip IP Address for unblock
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_ipblock() {
}
* Enable/Disable SSH Access
*
* @category	 Security
* @param        string $action Action should be on or off
* @return		 string $resp Response of Actions. Default: Serialize
*/
function ssh_access() {
}else{
$data['sshon'] = 1;
}
$data['editsshsettings'] = 1;
$resp = $this->curl_call($act, $data);
$this->chk_error();
return $resp;
}
* List Email Users
*
* @category	 Email
* @param        string $domain Specify domain name for the Email User Account list
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_emailuser() {
}
* Add Email User
*
* @category	 Email
* @param        string $domain Domain for the Email User Account to add
* @param        string $emailuser Email user name for add
* @param        string $password Password for user
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_emailuser() {
}
* Change Email Users' Password
*
* @category	 Email
* @param        string $domain Domain for the Email User Account for change passsword
* @param        string $emailuser Email user name for change passsword
* @param        string $password New password for user
* @return		 string $resp Response of Actions. Default: Serialize
*/
function change_email_user_pass() {
}
* Delete Email Users
*
* @category	 Email
* @param        string $domain Domain for the Email User Account for delete
* @param        string $emailuser Email user name for delete
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_email_user() {
}
* List Email Forwarder
*
* @category	 Email
* @param        string $domain Domain for the Email Forwarder list
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_emailforward() {
}
* Add Email Forwarder
*
* @category	 Email
* @param        string $domain Domain for the Email Forwarder add
* @param        string $forward_address Forwarder name to add
* @param        string $forward_to To whome it is forwarded
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_emailforward() {
}
* Delete Email Forwarder
*
* @category	 Email
* @param        string $domain Domain for the Email Forwarder delete
* @param        string $forward_address Forwarder name
* @param        string $forward_to To whome it is forwarded
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_email_forward() {
}
* List MX record
*
* @category	 Email Server
* @param        string $domain Domain for the MX Record list
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_mx_entry() {
}
* Add MX record
*
* @category	 Email Server
* @param        string $domain Domain for the MX Record add
* @param        string $priority Priority for the MX Record Entry
* @param        string $destination Destination address
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_mx_entry() {
}
* Edit MX record
*
* @category	 Email Server
* @param        string $domain Domain for the MX Record edit
* @param        string $record Record no of the Entry
* @param        string $priority Priority for the MX Record Entry
* @param        string $destination Destination address
* @return		 string $resp Response of Actions. Default: Serialize
*/
function edit_mx_entry() {
}
* Delete MX record
*
* @category	 Email Server
* @param        string $domain Domain for the MX Record delete
* @param        string $record Record no of the Entry
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_mx_entry() {
}
* Set Default
*
* @category	 Server
* @param        string $service Set the Default Service - php53/php54/nginx/httpd
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_defaults() {
}
if(in_array($service, $server)){
$data['webserver'] = $service;
}
$data['service_manager'] = 1;
$resp = $this->curl_call($act, $data);
$this->chk_error();
return $resp;
}
* Show Error Log
*
* @category	 Server Info
* @param        string $domain Domain for the error log (Opional)
* @return		 string $resp Response of Actions. Default: Serialize
*/
function show_error_log() {
}else{
$data['domain_log'] = $domain .'.err';
}
$resp = $this->curl_call($act, $data);
$this->chk_error();
return $resp;
}
* Enable / Disable PHP Extensions
*
* @category	 Configuration
* @param        string (Optional) $extensions Extensions to enable
(Empty results in list of Extensions and their status)
* @return		 array	$resp Response of Action. Default: Serialize
*/
function handle_php_ext() {
}
$resp = $this->curl_call($act, $data);
$this->chk_error();
return trim($resp);
}
* Networking Tools
*
* @category	 DNS
* @param        string (Optional) $action Lookup by default
(Available options are 'lookup' & 'traceroute')
* @return		 array	$resp Response of Action. Default: Serialize
*/
function dns_lookup() {
}
* Prints result
*
* @category	 Debug
* @param        Array $data
* @return       array
*/
function r_print() {
}
* Set Bandwidth Limit
*
* @category	 Bandwidth
* @param        string $total_bandwidth Set your total available bandwidth in GB(Set 0 for unlimited)
* @param        string $bandwiwdth_email_alert Email alert limit value in GB
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_bandwidth() {
}
* Reset Bandwidth Limit
*
* @category	 Bandwidth
* @return		 string $resp Response of Actions. Default: Serialize
*/
function reset_bandwidth() {
}
* List Login Logs
*
* @category	 Login
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_login_logs() {
}
* Delete all Login Logs
*
* @category	 Login
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_login_logs() {
}
* List Protected Users
*
* @category	 Apache
* @return		 Array	$resp	Response of Actions. Default: Serialize
*/
function list_protected_users() {
}
*	----------------------------------
*	Password Protect Directory
*	----------------------------------
*
*	@category	Apache
*	@param		String	$path - Path to the directory to be password protected
*	@param		String	$uname - User to be added for the directory
*	@param		String	$pass - Password to the user for the directory
*	@param		String	[OPTIONAL] $name - Alias Name for the directory.
*	@return     Boolean
*	@version    2.2.0
*
*/
function add_pass_protect_dir() {
}
$data['add_pass_protect'] = 1;
$resp = $this->curl_call($act, $data);
$this->chk_error();
return $resp;
}
*	-------------------------------------------
*	Delete Password Protected Directory User
*	-------------------------------------------
*
*	@category	Apache
*	@param		String	$uname - User to be deleted
*	@param		String	$path - Path to the password protected directory
*	@return		Boolean
*	@version    2.2.0
*
*/
function delete_pass_protected_user() {
}
*	-------------------------------------------
*	Read extra configuration file path
*	-------------------------------------------
*
*	@category	Configuration
*	@param		String	$domain Domain for the extra path
*	@return		array
*	@version    2.2.6
*
*/
function read_extra_conf() {
}
*	-------------------------------------------
*	Add extra configuration file path
*	-------------------------------------------
*
*	@category	Configuration
*	@param		String	$domain Domain for the extra path
*	@param		String	$path Path of your extra conf
*	@param		String	$webserver Webserver ID for whic you want to add, you will get it from w_list array key
*	@return		string $resp Response of Actions. Default: Serialize
*	@version    2.2.6
*
*/
function add_extra_conf() {
}
*	-------------------------------------------
*	Delete extra configuration file path
*	-------------------------------------------
*
*	@category	Configuration
*	@param		String	$domain Domain for the extra path
*	@param		String	$path Path of your extra conf
*	@param		String	$webserver Webserver ID for whic you want to delete, you will get it from w_list array key
*	@return		string $resp Response of Actions. Default: Serialize
*	@version    2.2.6
*
*/
function delete_extra_conf() {
}
*	-------------------------------------------
*	Edit extra configuration file path
*	-------------------------------------------
*
*	@category	Configuration
*	@param		String	$domain Domain for the extra path
*	@param		String	$path Path of your extra conf
*	@param		String	$webserver Webserver ID for whic you want to delete, you will get it from w_list array key
*  @param      String  $id $id ID of the cron record. Get from the list of extra conf
*	@return		string $resp Response of Actions. Default: Serialize
*	@version    2.2.6
*
*/
function edit_extra_conf() {
}
}
class Webuzo_API extends Webuzo_SDK{
}
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = unserialize($test->list_domains());
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing domain<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$res = $test->add_domain($domain, $domainpath, $ip);
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Domain added';
}else{
echo 'Error while adding domain<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$res = $test->delete_domain($domain);
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Domain Deleted';
}else{
echo 'Error while deleting Domain<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->change_password($webuzo_password, $webuzo_user);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Password changed';
}else{
echo 'Error while changing Password<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$res = $test->change_fileman_pwd($pass);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Password changed for File Manager';
}else{
echo 'Error while changing password for File Manager<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->change_tomcat_pwd($pass);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Password changed for Apache Tomcat Manager';
}else{
echo 'Error while changing Password for Apache Tomcat Manager<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = unserialize($test->list_ftpuser());
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing FTP User<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->add_ftpuser($user, $pass, $directory, $quota_limit);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'FTP user added';
}else{
echo 'Error while adding FTP user<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->edit_ftpuser($user, $quota_limit);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'FTP user\' quota edited';
}else{
echo 'Error while editing FTP user\'s quota<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ftpuser($ftp_user);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'FTP user Deleted';
}else{
echo 'Error while deleting FTP user<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = unserialize($test->list_ftp_connections());
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing FTP Connections<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ftp_connection($ftp_connection_id);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'FTP connection disconnected';
}else{
echo 'Error while disconnecting FTP connection<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->change_ftpuser_pass($ftpuser, $pass);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'FTP user\'s password changed';
}else{
echo 'Error while changing FTP user\'s password<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = unserialize($test->list_database());
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing Databases<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->add_database($db_name);
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Database created';
}else{
echo 'Error while creating database<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = trim($test->delete_database($db_name));
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Database Deleted';
}else{
echo 'Error while deleting Database<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_db_user();
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing Database User<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->add_db_user($db_user, $pass);
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Database user created';
}else{
echo 'Error while creating database user<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->delete_db_user($db_user);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Database user Deleted';
}else{
echo 'Error while deleting Database user<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->set_privileges($database, $db_user, $host, $prilist);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Privileges set successfully';
}else{
echo 'Error while setting privileges<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->edit_settings($email, $ins_email, $rem_email, $edit_email);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Email settings editted successfully';
}else{
echo 'Error while editing Email settings<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$service_name = 'mysqld';
$action = 'restart';
$res = $test->manage_service($service_name, $action);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Service '.$service_name.' '.$action.'ed successfully';
}else{
echo 'Error while '.$action.'ing '.$service_name.' service<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->manage_suphp($status);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Settings saved successfully';
}else{
echo 'Error while saving settings<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->enable_proxy($port, $htaccess, $proxy_server);
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Settings saved successfully';
}else{
echo 'Error while saving settings<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->disable_proxy($set_default_webserver);
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Settings saved successfully';
}else{
echo 'Error while saving settings<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_dns_record($domain);
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing DNS Record<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->add_dns_record($domain, $name, $ttl, $type, $address);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'DNS record added successfully';
}else{
echo 'Error while adding DNS record<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->edit_dns_record($id, $domain, $name, $ttl, $type, $address);
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'DNS Record record editted successfully';
}else{
echo 'Error while editing DNS Record<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->delete_dns_record($id, $domain);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'DNS Record deleted';
}else{
echo 'Error while deleting DNS Record<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_cron();
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing cron<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->add_cron($minute, $hour, $day, $month, $weekday, $cmd);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'CRON added successfully';
}else{
echo 'Error while adding CRON<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->edit_cron($id, $minute, $hour, $day, $month, $weekday, $cmd);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'CRON editted successfully';
}else{
echo 'Error while editing CRON<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->delete_cron($id);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'CRON Deleted';
}else{
echo 'Error while deleting CRON<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_ssl_key();
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing SSL key<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->create_ssl_key($description, $keysize);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'SSL key created';
}else{
echo 'Error while creating ssl key<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->upload_ssl_key($description, $keypaste);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'SSL key uploaded';
}else{
echo 'Error while uploading SSL key<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->detail_ssl_key($domain);
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while showing details for SSL key<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_ssl_key();
$res = $test->delete_ssl_key($domain_key);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'SSL key deleted';
}else{
echo 'Error while deleting SSL key<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_ssl_csr();
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing SSL CSR<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->create_ssl_csr($domain, $country_code, $state, $locality, $org, $org_unit, $passphrase, $email, $key);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'SSL CSR created';
}else{
echo 'Error while creating SSL CSR<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->detail_ssl_csr($domain);
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while showing details for SSL CSR<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_ssl_csr();
$res = $test->delete_ssl_csr($domain_key);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'SSL CSR deleted';
}else{
echo 'Error while deleting SSL CSR<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_ssl_crt();
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing SSL Certificate<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->create_ssl_crt($domain, $country_code, $state, $locality, $org, $org_unit, $email, $key);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'SSL Certificate created';
}else{
echo 'Error while creating SSL Certificate<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->upload_ssl_crt($keypaste);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'SSL certificate uploaded';
}else{
echo 'Error while uploading SSL certificate<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->detail_ssl_crt($domain);
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while showing details for SSL Certificate<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_ssl_crt();
$res = $test->delete_ssl_crt($domain_key);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'SSL Certificate deleted';
}else{
echo 'Error while deleting SSL Certificate<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_ipblock();
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing Blocked IPs<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->add_ipblock($ip);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'IP Blocked';
}else{
echo 'Error while Blocking IP<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ipblock($ip);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'IP Unblocked successfully';
}else{
echo 'Error while Unblocking IP<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->ssh_access($action);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Settings saved successfully';
}else{
echo 'Error while saving settings<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_emailuser($domain);
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing Email User<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->add_emailuser($domain, $emailuser, $password);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Email user added';
}else{
echo 'Error while adding Email user<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->change_email_user_pass($domain, $emailuser, $password);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Email user\'s password changed';
}else{
echo 'Error while changing Email user\'s password<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->delete_email_user($domain, $emailuser);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Email user Deleted';
}else{
echo 'Error while deleting Email user<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_emailforward($domain);
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing Email Forwarders<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->add_emailforward($domain, $forward_address, $forward_to);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Email Forwarder added';
}else{
echo 'Error while adding Email Forwarder<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->delete_email_forward($domain, $forward_address, $forward_to);
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Email Forwarder Deleted';
}else{
echo 'Error while deleting Email Forwarder<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_mx_entry($domain);
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing MX Records<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->add_mx_entry($domain, $priority, $destination);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'MX record added successfully';
}else{
echo 'Error while adding MX record<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->edit_mx_entry($domain, $record, $priority, $destination);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'MX Entry record editted successfully';
}else{
echo 'Error while editing MX Entry<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->delete_mx_entry($domain, $record);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'MX Record deleted';
}else{
echo 'Error while deleting MX Record<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->set_defaults('php53');
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Default Set';
}else{
echo 'Error while setting defaults<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->show_error_log();
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while showing Error Log<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API();
$res = $test->webuzo_configure($ip, $user, $email, $pass, $host, $ns1 = '', $ns2 ='', $license = '' );
$res = unserialize($res);
$test->r_print($res);
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$appid = 30; // ID of the Application to install.
$res = $test->install_app($appid);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Done';
}else{
echo 'Failed<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$appid = 30; // ID of the Application to install.
$res = $test->remove_app($appid);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Done';
}else{
echo 'Failed<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_services();
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Done';
}else{
echo 'Failed<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$extensions = 'curl.so,calendar.so'; // Extensions to Enable.
$res = $test->handle_php_ext($extensions);
$res = unserialize($res);
if(!empty($res['done'])){
echo 'Done';
}else{
echo 'Failed<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->dns_lookup($domain_name);
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Done';
}else{
echo 'Failed<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->set_bandwidth($total_bandwidth, $bandwiwdth_email_alert);
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Done';
}else{
echo 'Failed<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->reset_bandwidth();
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Done';
}else{
echo 'Failed<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->list_login_logs();
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Done';
}else{
echo 'Failed<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->delete_login_logs();
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'Done';
}else{
echo 'Failed<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = unserialize($test->list_protected_users());
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing password protected directories<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->add_pass_protect_dir('public_html/test', 'testuser', 'testuser', 'Test Account');
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'User added successfully';
}else{
echo 'Error while adding user<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->delete_pass_protected_user('testuser', '/home/soft/public_html/test');
$res = unserialize($res);
$test->r_print($res);
if(!empty($res['done'])){
echo 'User deleted successfully';
}else{
echo 'Error while deleting user<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);
$res = $test->read_extra_conf($host);
$res = unserialize($res);
$test->r_print($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing Conf file<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);;
$res = $test->add_extra_conf($domain, $path, $webserver);
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing Conf file<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);;
$res = $test->delete_extra_conf($domain, $path, $webserver);
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing Conf file<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}
*/
$test = new Webuzo_API($webuzo_user, $webuzo_password, $host);;
$res = $test->edit_extra_conf($domain, $path, $webserver, $id);
$res = unserialize($res);
if(empty($res['error'])){
$test->r_print($res);
}else{
echo 'Error while listing Conf file<br/>';
if(!empty($res['error'])){
print_r($res['error']);
}
}*/
?>