<?php
* Webuzo enduser SDK
*
* @category	 SDK
* @param        string $user The username to LOGIN
* @param        string $pass The password
* @param        string $host The host to perform actions
* @param        string $sess_cookie The sess_cookie of the user
*/
class Webuzo_SDK{
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
}elseif(!empty($method) && is_array($method) && $method['type'] == 'api'){
$_user = $user;
if(!empty($method['loginAs'])){
$_user = 'root';
$loginAs = $user;
}
$this->login = 'https://'.$host.':2003/index.php?apiuser='.$_user.'&apikey='.$pass.'&'.(!empty($method['loginAs']) ? 'loginAs='.$user.'&' : '');
}elseif(!empty($method)){
$this->sess_cookie = $method;
$url_token = 'sess'.substr(current($method), 0, 16);
$this->login = 'https://'.$host.':2003/'.$url_token.'/index.php';
}else{
$this->login = 'https://'.rawurlencode($user).':'.rawurlencode($pass).'@'.$host.':2003/index.php';
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
curl_setopt($ch, CURLOPT_STDERR, $this->curl_log);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
if(!empty($this->curl_timeout)){
curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
$this->curl_timeout = 0;
}
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
* A Function to Login with Softaculous Parameters.
*
* @package      API
* @author       Pulkit Gupta
* @param        string $act Actions
* @param        array $post POST DATA
* @return       string $resp Response of Actions
* @since     	 4.1.3
*/
function curl_call($act, $post = array(), $cookies = array(), $header = 0){
$url = $this->login;
$tmp_url = parse_url($url);
if(!strstr($url, '?')){
$url = $url.'?';
}
$url = $url.$act;
if(!strstr($url, 'api=')){
$url = $url.'&api='.$this->format;
}
$resp = $this->curl($url, $post);
return $resp;
}
* A Function to Login with Softaculous Parameters.
*
* @package      API
* @author       Pulkit Gupta
* @param        string $act Actions
* @param        array $post POST DATA
* @return       string $resp Response of Actions
* @since     	 4.1.3
*/
function eapi_call($act, $post = array(), $cookies = array(), $header = 0){
$url = $this->login;
$tmp_url = parse_url($url);
if(!strstr($url, '?')){
$url = $url.'?';
}
$url = $url.$act;
if(!strstr($url, 'api=')){
$url = $url.'&api=serialize';
}
$resp = $this->curl($url, $post);
if(!empty($resp)){
$resp = unserialize(trim($resp));
}
return $resp;
}
* Check login error
*
* @category	 error
* @return       array
*/
function chk_error() {
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
* @param		 string $domainpath The path for an ADD-ON domain
* @param		 (Optional) string $domain_type The type of domain you want to add ['parked', 'addon', 'subdomain']
* @param		 (Optional) string $subdomain specify subdomain of main domain i.e webuzo.com [subdomain would be "test" final will be test.webuzo.com ]
* @param		 (Optional) string $validate_mails If you validate mails on this domain
* @param		 (Optional) string $issue_lecert Check if you want to issue LE-CERT
* @param		 (Optional) string $wildcard Check for wildcard entry i.e. *.webuzo.com
* @param		 (Optional) string $ip Different IPv4 Address for domain
* @param		 (Optional) string $ipv6 Different IPv6 Address for domain
* @return		 string $resp Response of Action. Default: Serialize
*/
function add_domain() {
}
$data['domain_type'] = $domain_type;
if(!empty($validate_mails)){
$data['validate_mails'] = $validate_mails;
}
if(!empty($issue_lecert)){
$data['issue_lecert'] = $issue_lecert;
}
if(!empty($subdomain)){
$data['subdomain'] = $subdomain;
}
if(!empty($ip)){
$data['ip'] = $ip;
}
if(!empty($issue_lecert)){
$data['ipv6'] = $ipv6;
}
$data['domainpath'] = $domainpath;
$data['add'] = 1;
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Edit Domain
*
* @category	 Domain
* @param        string $domain The domain to edit
* @param		 string $domainpath The path for an ADD-ON domain
* @param		 (Optional) string $domain_type The type of domain you want to add ['parked', 'addon', 'subdomain']
* @param		 (Optional) string $subdomain specify subdomain of main domain i.e webuzo.com [subdomain would be "test" final will be test.webuzo.com ]
* @param		 (Optional) string $validate_mails If you validate mails on this domain
* @param		 (Optional) string $issue_lecert Check if you want to issue LE-CERT
* @param		 (Optional) string $wildcard Check for wildcard entry i.e. *.webuzo.com
* @param		 (Optional) string $ip Different IPv4 Address for domain
* @param		 (Optional) string $ipv6 Different IPv6 Address for domain
* @return		 string $resp Response of Action. Default: Serialize
*/
function edit_domain() {
}
if(!empty($validate_mails)){
$data['validate_mails'] = $validate_mails;
}
if(!empty($issue_lecert)){
$data['issue_lecert'] = $issue_lecert;
}
if(!empty($subdomain)){
$data['subdomain'] = $subdomain;
}
if(!empty($ip)){
$data['ip'] = $ip;
}
if(!empty($issue_lecert)){
$data['ipv6'] = $ipv6;
}
$data['domainpath'] = $domainpath;
$data['edit'] = 1;
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Delete Domain
*
* @category	 Domain
* @param        string $domain The domain to delete
* @return		 string $resp Response of Action. Default: Serialize
*/
function delete_domain() {
}
* List Domains alias
*
* @category	 Domain
* @return		 string $resp Response of Action. Default: Serialize
*/
function list_domains_alias() {
}
* Add/Edit Domain Alias
*
* @category	 Domain
* @param        string $domain The domain to add (if domain exist it will edited);
* @param		 string $redirect_to The redirected path
* @return		 string $resp Response of Action. Default: Serialize
*/
function add_domain_alias() {
}
* Delete Domain alias
*
* @category	 Domain
* @param        string $domain The domain to delete
* @return		 string $resp Response of Action. Default: Serialize
*/
function delete_domain_alias() {
}
* List DNS Record
*
* @category	 Server Settings
* @param        string $domain [OPTIONAL] Specify domain to list DNS records ( BY default it will take primary domain)
* @return		 string $resp Response of Action. Default: Serialize
*/
function list_dns_record() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
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
* @param        string $point Specify point of record to EDIT
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
* Networking Tools
*
* @category	 DNS
* @param		 @param $domain domain on which you want to lookup
* @param		 @param (Optional)$traceroute it traceroute the domain by default it * lookup action is called.
*					(Available options are 'lookup' & 'traceroute')
* @return		 array	$resp Response of Action. Default: Serialize
*/
function dns_lookup() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
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
* @param        string $quota_limit (Optional) Define a quota for the user (in MB)
* @return		 string $resp Response of Action. Default: Serialize
*/
function add_ftpuser() {
}else{
$data['quota'] = 'unlimited';
}
$data['create_acc'] = 1;
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Edit FTP user
*
* @category	 FTP
* @param        string $user FTP user to EDIT data
* @param        string $quota_limit (Optional) Specify quota limit to the user (in MB)
* @return		 string $resp Response of Action. Default: Serialize
*/
function edit_ftpuser() {
}else{
$data['quota'] = 'unlimited';
}
$data['edit_record'] = 1;
$resp = $this->eapi_call($act, $data);
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
$data['delete_fuser_id'] = 1;
$resp = $this->eapi_call($act, $data);
return $resp;
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
* Change Database user Password
*
* @category	 database
* @param        string $db_user Database user
* @param        string $db_pass Database user password
* @return		 string $resp Response of Action. Default: Serialize
*/
function change_db_user_pass() {
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
* List Mysql Remote Access
*
* @category	 database
* @return		 string $resp Response of Action. Default: Serialize
*/
function list_remote_mysql_access() {
}
* Add Mysql Remote Access
*
* @category	 database
* @param        string $dbuser Database user
* @param        string $dbhost Database host
* @return		 string $resp Response of Action. Default: Serialize
*/
function add_remote_mysql_access() {
}
* Delete Mysql Remote Access
*
* @category	 database
* @param        string $dbuser Database user
* @param        string $dbhost Database host
* @return		 string $resp Response of Action. Default: Serialize
*/
function delete_remote_mysql_access() {
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
* @category	 SSL
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_ssl_crt() {
}
* Create SSL Certificate
*
* @category	 SSL
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
* @category	 SSL
* @param        string $keypaste Entire certificate.
* @return		 string $resp Response of Actions. Default: Serialize
*/
function upload_ssl_crt() {
}
* Detail SSL Certificate
*
* @category	 SSL
* @param        string $domain Specify domain name to detail view of SSL Certificat
* @return		 string $resp Response of Actions. Default: Serialize
*/
function detail_ssl_crt() {
}
* Delete SSL Certificate
*
* @category	 SSL
* @param        string $domain Specify domain name to delete SSL Certificat
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_ssl_crt() {
}
* List install certificate
*
* @category	 SSL
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_install_cert() {
}
* install_cert
*
* @category	 SSL
* @param		 $domain domain on which you want to install certificate
* @param		 $kpaste Paste your key
* @param		 $cpaste Paste your Certificate
* @param		 $bpaste Paste the CA bundle. (Optional)
* @return		 string $resp Response of Actions. Default: Serialize
*/
function install_cert() {
}
$data['install_key'] = 1;
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Delete cert
*
* @category	 SSL
* @param		 $record which record you want to delete
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_install_cert() {
}
* Detail cert
*
* @category	 SSL
* @param		 $record which record you want to detail
* @return		 string $resp Response of Actions. Default: Serialize
*/
function detail_install_cert() {
}
* List le certificate
*
* @category	 SSL
* @param	 	 $clear_log [OPTIONAL]  clear LE log
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_le() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* install le certificate
*
* @category	 SSL
* @param		 $domain domain on which you want to install certificate
* @return		 string $resp Response of Actions. Default: Serialize
*/
function install_le_cert() {
}
* revoke LE certificate
*
* @category	 SSL
* @param		 $domain domain on which you want to revoke certificate
* @return		 string $resp Response of Actions. Default: Serialize
*/
function revoke_le_cert() {
}
* renew LE certificate
*
* @category	 SSL
* @param		 $domain domain on which you want to renew certificate
* @return		 string $resp Response of Actions. Default: Serialize
*/
function renew_le_cert() {
}
* List Email Users
*
* @category	 Email
* @param        string $email_or_domain Specify Email or domain ( It search with email account )
* @param		 string $reslen pass how much record you want to display per page (pass 'all' if you want to diplay all results)
* @param		 string $page pass page number
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_emailuser() {
}
if(!empty($reslen)){
$act = $act.'&reslen='.$reslen;
}
if(!empty($page)){
$act = $act.'&page='.$page;
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Add Email User
*
* @category	 Email
* @param        string $domain Domain for the Email User Account to add
* @param        string $emailuser Specify email user to create
* @param        string $password Specfy PASSWORD
* @param        string $quota_limit [OPTIONAL] Specify Quota limit of email in MB
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_emailuser() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Change Email Users' Password
*
* @category	 Email
* @param        string $domain Domain for the Email User Account for change passsword
* @param        string $emailuser Email user name for change passsword
* @param        string $password New password for user
* @param        string $quota_limit [OPTIONAL] Specify Quota limit of email
* @return		 string $resp Response of Actions. Default: Serialize
*/
function change_email_user_pass() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
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
* @param        string $domain [OPTIONAL] Domain for the Email Forwarder list
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_emailforward() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Add Email Forwarder
*
* @category	 Email
* @param        string $domain Domain for the Email Forwarder add | Specify DOMAIN
* @param        string $forward_address email user to Forward | Specify Senders Email Address
* @param        string $forward_to Forwarded to address | Specify Email Address to be Forwarded TO
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_emailforward() {
}
* Delete Email Forwarder
*
* @category	 Email
* @param        string $domain Domain for the Email Forwarder delete | Specify DOMAIN
* @param        string $forward_address Forwarder name | Specify Forwarders Name
* @param        string $forward_to To whome it is forwarded | Specify Recepients Name
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_email_forward() {
}
* List MX record
*
* @category	 Email Server
* @param        string $domain[OPTIONAL] Domain for the MX Record list (BY default it will use primary domain)
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_mx_entry() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Add MX record
*
* @category	 Email Server
* @param        string $priority Priority for the MX Record Entry
* @param        string $destination Destination address
* @param        string $domain[OPTIONAL] Domain for the MX Record add (BY default it will use primary domain of the webuzo user)
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_mx_entry() {
}
$data['priority'] = $priority;
$data['destination'] = $destination;
$data['add'] = 1;
$resp = $this->eapi_call($act, $data);
return $resp;
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
* List Email Autoresponder
*
* @category	 Email
* @param        string $domain[OPTIONAL] Domain email autoresponder | Specify the DOMAIN ( BY DEFAUL IT WILL TAKE PRIMARY DOMAIN OF USER
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_emailautoresponder() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Add Email Autoresponder
*
* @category	 Email
* @param        string $euser Email user for autoresponder | Specify the EMAILUSER
* @param        string $mail_subject Email subject for autoresponder | Specify the Autoresponder subject
* @param        string $mail_body Email body for autoresponder | Specify the Autoresponder Body
* @param        string $domain[OPTIONAL] Domain email autoresponder | Specify the DOMAIN ( BY DEFAUL IT WILL TAKE PRIMARY DOMAIN OF USER
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_emailautoresponder() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Delete Email Autoresponder
*
* @category	 Email
* @param        string $euser Email user for autoresponder | Specify the EMAILUSER
* @param        string $domain[OPTIONAL] Domain email autoresponder | Specify the DOMAIN ( BY DEFAUL IT WILL TAKE PRIMARY DOMAIN OF USER
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_emailautoresponder() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Edit Email Autoresponder
*
* @category	 Email
* @param        string $domain Domain for add autoresponder | Specify the DOMAIN
* @param        string $euser Email user for autoresponder | Specify the EMAILUSER
* @param        string $mail_subject Email subject for autoresponder | Specify the Autoresponder subject
* @param        string $mail_body Email body for autoresponder | Specify the Autoresponder Body
* @return		 string $resp Response of Actions. Default: Serialize
*/
function edit_emailautoresponder() {
}
* List Email Queue
*
* @category	 Email
* @param        string $euser [OPTIONAL] specify email user
* @param        string $domain [OPTIONAL] Specify the DOMAIN
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_email_queue() {
}
if(!empty($domain)){
$data['domain'] = $domain;
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* List Spam Assassin
*
* @category	 Email
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_spam_assassin() {
}
* Add Spam Assassin
*
* @category	 Email
* @param        string $email_ids specify email ids e.g. user1@domain.com, user2@domain.com
* @param        string $type specify spam assassin type  ['blacklist', 'whitelist']
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_spam_assassin() {
}
* Delete Spam Assassin
*
* @category	 Email
* @param        string $email_ids specify email ids (you can also provide multiple email ids in array)
* @param        string $type specify spam assassin type  ['blacklist', 'whitelist']
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_spam_assassin() {
}
* Set php version of domain
*
* @category	 Configuration
* @param	 	 $domain Domain ( you have to give array of domain and then php version) e.g $data[$domain] = $php_version
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_multi_php() {
}
$data['submitphp'] = 1;
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Get PHP ini (main as well as user ini file)
*
* @category	 Configuration
* @author		 Vasu
* @param	 	 $domain Domain got their local as well as original PHP ini( If you want to get PHP INI  of user just pass "home")
* @return		 string $resp Response of Actions. Default: Serialize
*/
function get_php_ini() {
}
* Save PHP ini (main as well as user ini file)
*
* @category	 Configuration
* @author		 Vasu
* @param	 	 string $domain Domain save their local as well as original PHP ini( If you want to save PHP INI  of user just pass "home")
* @param	 	 string $ini_content pass ini string.
* @return		 string $resp Response of Actions. Default: Serialize
*/
function save_php_ini() {
}
* Get installed pear list of user as well as admin (system installed pear)
* It use Default php and pear version for getting system installed pear LIST
*
* @category	 Configuration
* @author		 Vasu
* @return		 string $resp Response of Actions. Default: Serialize
*/
function get_installed_PEAR() {
}
* Get all available PEAR list
*
* @category	 Configuration
* @author		 Vasu
* @param		 string $type ['all', 'q'] for all pass all for search pass q;
* @param		 string $q pass this for the search string if $type == 'q'
* @param		 string $reslen pass how much record you want to display per page (pass 'all' if you want to diplay all results)
* @param		 string $page pass page number
* @return		 string $resp Response of Actions. Default: Serialize
*/
function get_PEAR_list() {
}
if(!empty($reslen)){
$act = $act.'&reslen='.$reslen;
}
if(!empty($page)){
$act = $act.'&page='.$page;
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Install/update/unistall/reinstall PEAR package
*
* @category	 Configuration
* @author		 Vasu
* @param		 string $mod_name pass PEAR module name (you can also pass particular module version which you want to install i.e. "Auth-1.0.2")(You can also pass PEAR package prefer stability e.g "Auth-stable" available PEAR stability option ["stable", "devel", "alpha", "beta"])
* @param		 string $mod_action pass action you want to perform on PEAR available actions are ['install', 'reinstall', 'uninstall', 'upgrade'];
* @return		 string $resp Response of Actions. Default: Serialize
*/
function action_PEAR() {
}
* Change ROOT/Endusers's Password (It will change filemanager password too)
*
* @category	 Security
* @param        string $pass The NEW password for the USER
* @return		 string $resp Response of Action. Default: Serialize
*/
function change_password() {
}
* Change Apache Tomcat Manager's Password
*
* @category	 Security
* @param        string $pass The NEW password for the Apache Tomcat
* @return		 string $resp Response of Action. Default: Serialize
*/
function change_tomcat_pwd() {
}
* List Blocked IP.
* @return		string $resp Response of Actions. Default: Serialize
*/
function list_ipblock() {
}
* Block IP
*
* @category	 Security
* @param        string $ip IP Address for block (NOTE: You can specify the IP Address in the following format
Single IP or Domain : 192.168.0.1 or example.com
IP Range : 192.168.0.1 - 192.168.0.50
CIDR Format : 192.168.0.1/20)
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
* List SSH access
*
* @category	 Security
* @return       string $resp Response of Actions.
*/
function list_ssh_access() {
}
* SSH key auth
*
* @category	 Security
* @param		 $ssh_keyname keyname of ssh
* @param		 $ssh_auth authentication string i.e. ["Deauthorize", "Authorize"]
* @return       string $resp Response of Actions.
*/
function ssh_key_auth() {
}
* SSH key auth
*
* @category	 Security
* @param		 $delete_ssh_key keyname of ssh to delete
* @return       string $resp Response of Actions.
*/
function delete_ssh_key() {
}
* VIEW SSH key auth
*
* @category	 Security
* @param		 $keyname keyname of ssh to view ssh key
* @return       string $resp Response of Actions.
*/
function view_ssh_key() {
}
* DELETE SSH key auth
*
* @category	 Security
* @param		 $keyname keyname of ssh to view ssh key
* @return       string $resp Response of Actions.
*/
function download_ssh_key() {
}
* Convert key to PPK
*
* @category	 Security
* @param		 $key_name keyname of ssh to generate ppk
* @param		 $passphrase password phrase
* @return       string $resp Response of Actions.
*/
function generate_ssh_ppk() {
}
* Generate SSH Key
*
* @category	 Security
* @param		 $keyname keyname of ssh
* @param		 $keypass password for ssh key
* @param		 $keytype type of key for ssh | options ["DSA", "RSA"]
* @param		 $keysize size of key for ssh | options ["1024", "2048", "4096"]
* @return       string $resp Response of Actions.
*/
function ssh_generate_keys() {
}
* IMPORT SSH Key
*
* @category	 Security
* @param		 $keyname keyname of ssh
* @param		 $private_key paste private key
* @param		 $passphrase Passphrase
* @param		 $public_key Paste the public key
* @return       string $resp Response of Actions.
*/
function ssh_import_keys() {
}
* List Protected Users
*
* @category	 SECURITY
* @return		 Array	$resp	Response of Actions. Default: Serialize
*/
function list_pass_protect_dir() {
}
*	----------------------------------
*	Password Protect Directory
*	----------------------------------
*
*	@category	SECURITY
*	@param		String	$path - Path to the directory to be password protected
*	@param		String	$uname - User to be added for the directory
*	@param		String	$pass - Password to the user for the directory
*	@param		String	[OPTIONAL] $name - Alias Name for the directory.
*	@return     Boolean
*	@version    3.0.0
*
*/
function add_pass_protect_dir() {
}
$data['add_pass_protect'] = 1;
$resp = $this->eapi_call($act, $data);
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
function delete_pass_protect_dir() {
}
* List Hotlink Protection values
* @return		string $resp Response of Actions. Default: Serialize
*/
function list_hotlink_protect() {
}
* Enable HOTLINK Protection
*
* @category	 Security
* @param        string $urllist list the url in array i.e. [ $url1, $url2 ];
* @param        string $extensionlist list the extention that you want to allow in comma seperated value i.e. ( ext1, ext2 )
* @param        string $directaccess if you want directaccess for your resource i.e. true or false;
* @param        string $redirecturl url that you want to redirect e.g. http://www.webuzo.com
* @return		 string $resp Response of Actions. Default: Serialize
*/
function enable_hotlink_protect() {
}
if(!empty($extensionlist)){
$data['extensionlist'] = $extensionlist;
}
if(!empty($directaccess)){
$data['directaccess'] = $directaccess;
}
if(!empty($redirecturl)){
$data['redirecturl'] = $redirecturl;
}
$data['hotlink_enable'] = 1;
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Disable HOTLINK Protection
*
*	@return		 string $resp Response of Actions. Default: Serialize
*/
function disable_hotlink_protect() {
}
* List Mod Security values
* @return		string $resp Response of Actions. Default: Serialize
*/
function list_mod_security() {
}
* Change mode SECURITY
*
* @param		string $mod_security Give mod security value the option [1/0- for single domain enable/disable it require domain, 2 - For enabling mod security on all domain, 3 - For disabling mod security on all domain
* @param		string $domain[OPTIONAL] it only require for mod value 1/0 i.e. for enable/disable single domaid
* @return		string $resp Response of Actions. Default: Serialize
*/
function change_mod_security() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* list backup
*
* @category	 ServerUtilities
* @param	 	 $clearlog [OPTIONAL] clear backup log of the user
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_backup() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Add/Edit backup server
*
* @category	 ServerUtilities
* @param		 $name [OPTIONAL (if you are checking only connection)] Name of backup Server
* @param		 $type type of backup Server i.e. ["SSH", "FTP"]
* @param		 $username Username of the server
* @param		 $hostname Hostname of the server i.e. valid TLD and IP address of the server
* @param		 $password Password of your user on server
* @param		 $port Connection port
* @param		 $backup_location Backup Location
* @param		 $server_id [OPTIONAL] It is provide for the edit backup server
* @param		 $conn_check [OPTIONAL] You can test your connection
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_backup_server() {
}
if(!empty($conn_check)){
$data['conn_check'] = $conn_check;
}else{
$data['name'] = $name;
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Remove Backup server
*
* @category	 ServerUtilities
* @param	 	 @param $server_ids You can send multiple server for multiple delete i.e ["1", "2"] => for multiple delete | "1" => for single delete
* @return		 string $resp Response of Actions. Default: Serialize
*/
function remove_backup_server() {
}else{
$data['server_ids'] = [$server_ids];
$data['remove_backup_server'] = 1;
}
$data['server_ids'] = json_encode($data['server_ids']);
$resp = $this->eapi_call($act, $data);
return $resp;
}
* List Login Logs
*
* @category	ServerUtilities
* @param	 	delete_all [OPTIONAL] if you pass it will  Delete all Login Logs
* @return		string $resp Response of Actions. Default: Serialize
*/
function list_login_logs() {
}
$resp = $this->eapi_call($act);
return $resp;
}
* List CRON
*
* @category	 ServerUtilities
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_cron() {
}
* Add / Edit a CRON
*
* @category	 ServerUtilities
* @param        string $cmd Command of the cron part
* @param        string $minute Minute of the cron part
* @param        string $hour Hour of the cron part
* @param        string $day Day of the cron part
* @param        string $month Month of the cron part
* @param        string $weekday Weekend of the cron part
* @param        string $edit_cronjob [OPTIONAL] If you want to update cron use this
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_cron() {
}else{
$data['create_record'] = 1;
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Delete CRON
*
* @category	 ServerUtilities
* @param        string $id ID of the cron record. Get from the list of cron
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_cron() {
}
* import From Cpanel
*
* @category	 ServerUtilities
* @param		 $r_host cPanel Server Address (Required)
* @param		 $r_user cPanel User Name (Required)
* @param		 $r_host cPanel User Name Password(Required)
* @param		 $r_backup [OPTIONAL] If specified, the process will import data from this file. The file should be stored locally inside /home/webuzo-username directory
* @return		 string $resp Response of Actions. Default: Serialize
*/
function import_cpanel() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Get / Delete import from cpanel log
*
* @category	 ServerUtilities
* @param        string $clearlog [OPTIONAL] clear log import log
* @return		 string $resp Response of Actions. Default: Serialize
*/
function import_cpanel_logs() {
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Show Error Log
*
* @category	 ServerUtilities
* @param        string $domain Domain for the error log (Opional) (BY default it will take primary domain of user)
* @param        boolean [OPTIONAL]$clearlog pass 1 if you want to clear domain log
* @return		 string $resp Response of Actions. Default: Serialize
*/
function show_error_log() {
}
if(!empty($clearlog)){
$data['clearlog'] = 1;
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* SHOW BANDWIDTH
*
* @category	 ServerUtilities
* @param	     @param [OPTIONAL] $year Year of bandwidth
* @param	     @param [OPTIONAL] $month Month of bandwidth
* @param	     @param [OPTIONAL] $type TYPE of bandwidth
* @param	     @param [OPTIONAL] $day DAY of bandwidth
* @param	     @param [OPTIONAL] $domain DOMAIN of bandwidth
* @return		 string $resp Response of Actions. Default: Serialize
*/
function show_bandwidth() {
}
if(!empty($month)){
$data['month'] = $month;
}
if(!empty($type)){
$data['type'] = $type;
}
if(!empty($day)){
$data['day'] = $day;
}
if(!empty($domain)){
$data['domain'] = $day;
}
$resp = $this->eapi_call($act, $data);
return $resp;
}
* Show Disk Usage
*
* @category	 ServerUtilities
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_disk_usage() {
}
* Set
*
* @category	 Settings
* @parm	 	 $email update user email
* @parm	 	 $language update user language
* @parm	 	 $arrange_domain check if you want to list domain in accedding order (alphabetically)
* @return		 string $resp Response of Actions. Default: Serialize
*/
function user_settings() {
}
$resp = $this->eapi_call($act);
return $resp;
}
}
* Webuzo ADMIN SDK
*
* @category	 SDK
* @param        string $user The ADMIN username to LOGIN
* @param        string $pass The password
* @param        string $host The host to perform actions
* @param        string $sess_cookie The sess_cookie of the user
*/
class Webuzo_Admin_SDK{
var $login = '';
var $debug = 0;
var $error = array();
var $data = array();
var $apps = array(); // List of Apps
var $apps_catwise = array(); // List of Apps with cat
var $installed_apps = array(); // List of Installed Apps
* Initalize API login
*
* @category	 Login
* @param        string $user The root username to LOGIN
* @param        string $pass The root password
* @param        string $host The host to perform actions
* @param        string $sess_cookie
* @return       void
*/
function __construct() {
}elseif(!empty($method)){
$this->sess_cookie = $method;
$url_token = 'sess'.substr(current($method), 0, 16);
$this->login = 'https://'.$host.':2005/'.$url_token.'/index.php';
}else{
$this->login = 'https://'.rawurlencode($user).':'.rawurlencode($pass).'@'.$host.':2005/index.php';
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
curl_setopt($ch, CURLOPT_STDERR, $this->curl_log);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
if(!empty($this->curl_timeout)){
curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
$this->curl_timeout = 0;
}
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
* A Function to Login with Softaculous Parameters.
*
* @package      API
* @author       Pulkit Gupta
* @param        string $act Actions
* @param        array $post POST DATA
* @return       string $resp Response of Actions
* @since     	 4.1.3
*/
function curl_call($act, $post = array(), $cookies = array(), $header = 0){
$url = $this->login;
$tmp_url = parse_url($url);
if(!strstr($url, '?')){
$url = $url.'?';
}
$url = $url.$act;
if(!strstr($url, 'api=')){
$url = $url.'&api='.$this->format;
}
$resp = $this->curl($url, $post);
return $resp;
}
* A Function to Login with Softaculous Parameters.
*
* @package      API
* @author       Pulkit Gupta
* @param        string $act Actions
* @param        array $post POST DATA
* @return       string $resp Response of Actions
* @since     	 4.1.3
*/
function aapi_call($act, $post = array(), $cookies = array(), $header = 0){
$url = $this->login;
$tmp_url = parse_url($url);
if(!strstr($url, '?')){
$url = $url.'?';
}
$url = $url.$act;
if(!strstr($url, 'api=')){
$url = $url.'&api=serialize';
}
$resp = $this->curl($url, $post);
if(!empty($resp)){
$resp = unserialize(trim($resp));
}
return $resp;
}
* Check login error
*
* @category	 error
* @return       array
*/
function chk_error() {
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
* Plan Fields keys
* --------------------------------------------------------
* 		Resource
* --------------------------------------------------------
*	max_disk_limit 				-  Disk Space Quota (MB) options:[unlimited, 10240(in MB)]
*	max_inode	 				-  Max Inodes options:[unlimited, 300000(pass to set inode value on user)]
*	max_bandwidth_limit	 		-  Monthly Bandwidth Limit (MB) options:[unlimited, 1048576(pass value)]
*	max_ftp_account		 		-  Max FTP Accounts options:[unlimited, 0(pass value)]
*	max_email_account		 	-  Max Email Accounts options:[unlimited, 0(pass value)]
*	max_quota_email			 	-  Max Quota per Email Address (MB) options:[unlimited, 0(pass value)]
*	max_database			 	-  Max SQL Databases options:[unlimited, 0(pass value)]
*	max_subdomain			 	-  Max Sub Domains options:[unlimited, 0(pass value)]
*	max_parked_domain			-  Max Parked Domains options:[unlimited, 0(pass value)]
*	max_addon_domain			-  Max Addon Domains options:[unlimited, 0(pass value)]
*	max_hourly_email			-  Max Hourly Email by Domain Relayed options:[unlimited, 0(pass value)]
*	max_percent_failed			-  Max percentage of failed or deferred messages a domain may send per hour options:[unlimited, 0(pass value)]
*
*----------------------------------------------------------------
* 			Settings
*----------------------------------------------------------------
*	// muticheckbox
*	options['dedicated_ip'] 			-	Dedicated IPv4
*	options['dedicated_ipv6'] 			-	Dedicated IPv6
*	options['shell'] 					-	Shell Access
*
*	home								-	Home Directory
*	theme								-	Webuzo Theme
*	lang								-	Choose Language
*	reseller							-	Make Reseller
*
*------------------------------------------------------------------
*				Features
*------------------------------------------------------------------
*	feature_sets						- 	Choose Features Sets
*	features['$feature_name']			- 	Manage Features $feature_name will be ['domainmanage', 'domainadd', 'addon_domain', 'sub_domain', 'aliases', 'redirects', 'advancedns', 'network_tools', 'dbmanage', 'phpmyadmin', 'remote_mysql_access', 'ftp', 'ftp_account', 'ftp_connections', 'sslkey', 'sslcsr', 'sslcrt', 'install_cert', 'lets_encrypt', 'wp_manager_cname', 'softaculous', 'email_account', 'email_forward', 'add_email_autoresponder', 'email_queue', 'mxentry', 'webuzo_rainloop', 'spam_assassin', 'multi_php', 'changepassword', 'tomcat_changepassword', 'ipblock', 'ssh_access', 'pass_protect_dir', 'hotlink_protect', 'mod_security', 'webuzo_backup', 'filemanager', 'awstats', 'login_logs', 'cronjob', 'import_cpanel', 'errorlog', 'bandwidth', 'disk_usage']
*/
* Add user
*
* @category	 USER MANAGEMENT
* @param		 string $user Pass user name
* @param		 string $domain User primary domain
* @param		 string $user_passwd user password
* @param		 string $email user Email
* @param		 string $plan [OPTIONAL] user Plan
* @param		 array $pData [OPTIONAL] User plan data (see above plane field for this)
* @return		 array $resp Response of Actions. Default: Serialize
*/
function create_user() {
}
if(empty($pData) && empty($plan)){
$data['prefill_default'] = 1;
}elseif(!empty($plan)){
$data['plan_default_reseller'] = 1;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Edit user
*
* @category	 USER MANAGEMENT
* @param		 string $user Pass user name
* @param		 string $domain User primary domain
* @param		 string $user_passwd user password
* @param		 string $email user Email
* @param		 string $plan [OPTIONAL] user Plan
* @param		 array $pData [OPTIONAL] User plan data
* @return		 array $resp Response of Actions. Default: Serialize
*/
function edit_user() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List users
*
* @category	 USER MANAGEMENT
* @param		 string $searchUser Search User
* @param		 string $reslen pass how much record you want to display per page (pass 'all' if you want to diplay all results)
* @param		 string $page pass page number
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_users() {
}
if(!empty($reslen)){
$act = $act.'&reslen='.$reslen;
}
if(!empty($page)){
$act = $act.'&page='.$page;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Delete User
*
* @category	 USER MANAGEMENT
* @param		 string $userName User name which you want to delete
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_user() {
}
* Suspend User
*
* @category	 USER MANAGEMENT
* @param		 string $userName User name which you want to suspend
* @return		 string $resp Response of Actions. Default: Serialize
*/
function suspend_user() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* UnSuspend User
*
* @category	 USER MANAGEMENT
* @param		 string $userName User name which you want to suspend
* @return		 string $resp Response of Actions. Default: Serialize
*/
function unsuspend_user() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Reset bandwidth limit of User as per plan assigned.
*
* @category	 USER MANAGEMENT
* @param		 array $userName Users names which you want to reset
* @return		 string $resp Response of Actions. Default: Serialize
*/
function reset_bandwidth_user() {
}
* List domains of all users
*
* @category	 USER MANAGEMENT
* @param		 string $user_search Search domain under user
* @param		 string $domain Domain Search param
* @param		 string $reslen pass how much record you want to display per page *(pass 'all' if you want to diplay all results)
* @param		 string $page pass page number
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_domains() {
}
if(!empty($user_search)){
$data['user_search'] = $user_search;
}
if(!empty($reslen)){
$act = $act.'&reslen='.$reslen;
}
if(!empty($page)){
$act = $act.'&page='.$page;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Add Plan
*
* @category	 PLANS
* @param		 string $plan_name Pass plan name
* @param		 array $pData [OPTIONAL] User plan data (see above plan data);
* @return		 array $resp Response of Actions. Default: Serialize
*/
function create_plan() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Edit Plan
*
* @category	 PLANS
* @param		 string $plan_name Pass plan name
* @param		 string $plan_slug Pass plan name slug
* @param		 array $pData [OPTIONAL] User plan data (see above plan data);
* @return		 array $resp Response of Actions. Default: Serialize
*/
function edit_plan() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List All Plans
*
* @category	 PLANS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_plans() {
}
* Delete Plan
*
* @category		PLANS
* @param			string $plan_slug Give plan name that you want to delete
* @return		 	string $resp Response of Actions. Default: Serialize
*/
function delete_plan() {
}
* Add Feature Sets
*
* @category	 PLANS
* @param		 string $features_name Pass Features Set Name
* @param		 array $pData [OPTIONAL] User plan data (See above plan data [feture section] );
* @return		 array $resp Response of Actions. Default: Serialize
*/
function create_feature_sets() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Edit Features Sets
*
* @category	 PLANS
* @param		 string $feature_name Pass Features Set Name
* @param		 string $features_slug Pass Features Set Slug Name
* @param		 array $pData [OPTIONAL] User plan data (See above plan data [feture section] );
* @return		 array $resp Response of Actions. Default: Serialize
*/
function edit_feature_sets() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List All Features Sets
*
* @category	 PLANS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_feature_sets() {
}
* Delete Feature Set
*
* @category	PLANS
* @param		string $feature_set_slug Need to pass slug feature which you want to delete ( slug name of the feature sets )
* @return		string $resp Response of Actions. Default: Serialize
*/
function delete_feature_sets() {
}
* Disable Feature Set
*
* @category	PLANS
* @param		array $DFdata [OPTIONAL] pass feature list which you want to disable e.g ['domainmanage', 'ftp',....,etc.], (See above plan data [feture section] for more options);
* @return		string $resp Response of Actions. Default: Serialize
*/
function disable_feature() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Reseller Fields keys
* --------------------------------------------------------
* 		Account Creation Limits
* --------------------------------------------------------
*	reseller_tot_acc_limit		-  Limit the total number of accounts reseller can create options:[unlimited, 10240(in MB)]
*	reseller_max_disk_limit		-  Disk Space Quota (MB) options:[unlimited, 300000(pass to set inode value on user)]
*	reseller_max_bandwidth_limit-  Monthly Bandwidth Limit (MB) options:[unlimited, 1048576(pass value)]
*	reseller_allow_overselling	-  Allow Overselling
*
*----------------------------------------------------------------
* 			Account Management
*----------------------------------------------------------------
*	// muticheckbox
*	options_cat['disable_terminate'] 	-	Disable Terminate Accounts
*	options_cat['disable_suspend']		-	Disable Suspend/Unsuspend Accounts
*	options_cat['disable_change_pass'] 	-	Disable Change Passwords
*	options_cat['disable_user_shell']	-	Disable Creation of Accounts with Shell Access
*	options_cat['disable_plans'] 		-	Disable Plans
*
*
*/
* Reseller Privileges
*
* @category	 RESELLER
* @param		 array $rData [OPTIONAL] Reseller plan (reference above reseller field);
* @return		 array $resp Response of Actions. Default: Serialize
*/
function reseller_privileges() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Email All Reseller
*
* @category	RESELLER
* @param		string $from_email Email from which you want to send email.
* @return		string $resp Response of Actions. Default: Serialize
*/
function email_all_reseller() {
}
* Set Panel Configuration
*
* @category	 SETTINGS
* @param        string $primary_ip Server ip address
* @param        string $primary_domain panel Domain on which you want to use panel
* @param        string $primary_ip_v6[OPTIONAL] IPv6 of your server
* @param        string $ns1 Name server of your server e.g. 'ns1.example.com'
* @param        string $ns2 Name server of your server e.g. 'ns1.example.com'
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_panel_config() {
}
$data['WU_NS1'] = $ns1;
$data['WU_NS2'] = $ns2;
if(!empty($quota)){
$data['quota'] = $quota;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List Admin settings
*
* @category	 SETTINGS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_settings() {
}
* List Admin Backup Settings
*
* @category	 SETTINGS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_backup_settings() {
}
* List Admin Rebranding Settings
*
* @category	 SETTINGS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_rebranding_settings() {
}
* Set Admin Rebranding Settings
*
* @category		SETTINGS
* @param			string $sn Set site name (The name of the server or company using Webuzo. It will appear in many pages of the Webuzo installer)
* @param			string $logo_url Set Logo URL (If empty the Webuzo logo will be shown.)
* @param			string $favicon_logo Set Favicon logo URL (If empty the Webuzo favicon will be shown in Enduser Panel)
* @return		 	string $resp Response of Actions. Default: Serialize
*/
function set_rebranding_settings() {
}
if(!empty($favicon_logo)){
$data['favicon_logo'] = $favicon_logo;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List Admin Emailtemp Settings
*
* @category	 SETTINGS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_emailtemp() {
}
* Set Admin Email Settings
*
* @category		SETTINGS
* @param			string $etitle Set Subject
* @param			string $econtent Set Content
* @param			string $lang [OPTIONAL] Set Language (If empty it take default user setting)
* @param			string $ishtml [OPTIONAL] Set content is html
* @param			boolean $reset [OPTIONAL] Set if you want to reset email setting
* @return		 	string $resp Response of Actions. Default: Serialize
*/
function set_emailtemp() {
}
if(!empty($reset)){
$data['reset'] = $reset;
}
if(!empty($ishtml)){
$data['ishtml'] = $ishtml;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List Update Settings
*
* @category	 SETTINGS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_update_settings() {
}
* Set Update Settings
*
* @category		SETTINGS
* @param			int $update Auto Update Webuzo options [ 0 => Never Update, 1 =>  Stable (Recommended) , 3 => Release Candidate (Experimental!) ];
* @param			boolean $email_update_apps [OPTIONAL] Notify Application Updates(if enabled, emails will be sent when updates for installed Application(s) are available)[checkbox]
* @param			boolean $no_auto_update_system [OPTIONAL] Disable Auto Update OS(If disabled, the Operating System will not be updated using Yum or Apt-get)
* @param			boolean $email_update [OPTIONAL] Notify Updates via Email (Will send emails when Webuzo upgrades are available or Auto Upgrade is performed)
* @param			string $cron_time Updates Cron Job (The cron job time to check for available updates. Don't change this if you are unaware of what cron jobs area)
* @param			string $php_bin PHP Binary (This is the binary that will be used for the CRON Job and also other purposes. If empty then /usr/bin/php will be used. Please note that the PHP binary should be a CLI binary.)
* @return			string $resp Response of Actions. Default: Serialize
*/
function set_update_settings() {
}
if(!empty($no_auto_update_system)){
$data['no_auto_update_system'] = $no_auto_update_system;
}
if(!empty($email_update)){
$data['email_update'] = $email_update;
}
$data['cron_time'] = $cron_time;
$data['php_bin'] = $php_bin;
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Set root SSH pass
*
* @category	 SETTINGS
* @param        string $pass New password to change root(admin) password
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_root_pass() {
}
* List Webuzo ACL
*
* @category	 SETTINGS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_webuzo_acl() {
}
* Set Webuzo ACL
*
* @category	 SETTINGS
* @param        boolean $disable_domainadd Disable addition of new Domains password
* @param        boolean $max_addon Max. limit of Addon Domain creations
* @param        boolean $disable_email Disable Emails
* @param        boolean $disable_emailadd Disbale addition of mail accounts
* @param        boolean $disable_ssh Disable SSH options
* @param        boolean $disable_sysapps Disable System Applications Installations / Configuration
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_webuzo_acl() {
}
* List Admin Email Settings
*
* @category	 SETTINGS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_email_setting() {
}
* Set Admin Email Settings
*
* @category	 SETTINGS
* @param        int $mail Mailing Method options = [ 1 => PHP Mail, 0 => SMTP ]
* @param        boolean $send_test_mail If you want to send test mail check this to 1.
* @param        boolean $enc_mail_pass Save SMTP Password in Encrypted format (if yes pass 1).
* @param        boolean $off_email_link Turn off all Emails sent to endusers (if yes pass 1).
* @param        string $mail_authtype SMTP Authtype options [ 0 => Default, CRAM-MD5 => CRAM-MD5, noauth => No Authentication].
* @param        string $mail_server SMTP Server (REQUIRED WHEN YOU SELECT MAILING METHOD SMTP).
* @param        string $mail_port SMTP Port (REQUIRED WHEN YOU SELECT MAILING METHOD SMTP).
* @param        string $mail_user SMTP Username (REQUIRED WHEN YOU SELECT MAILING METHOD SMTP).
* @param        string $mail_pass SMTP Password (REQUIRED WHEN YOU SELECT MAILING METHOD SMTP).
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_email_settings() {
}
* Export Webuzo setting
*
* @category	 SETTINGS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function export_webuzo_settings() {
}
* Import Webuzo setting
*
* @category	 SETTINGS
* @param		 array $file pass $_FILES array (it must be .zip extention)
* @return		 string $resp Response of Actions. Default: Serialize
*/
function import_webuzo_settings() {
}
* Load Your License
*
* @category	 SETTINGS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function load_licence() {
}
* Manage your webuzo license
*
* @category	 SETTINGS
* @param		 array $enter_license Enter your License Key
* @param		 array $email your valid Email Address
* @return		 string $resp Response of Actions. Default: Serialize
*/
function manage_licensekey() {
}
* Load Api Keys
*
* @category	 SETTINGS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_apikey() {
}
* Add Api Keys
*
* @category	 SETTINGS
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_apikey() {
}
* Delete Api Keys
*
* @category	 SETTINGS
* @parm	 	 string $api_key pass api key that you want to delete
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_apikey() {
}
* Add ips
*
* @category	 NETWORKING
* @parm	 	 array $ips add IPv4 ips e.g. [192.168.1.11, 192.168.1.12, 192.168.1.13, 192.168.1.14]
* @parm	 	 string $firstip [OPTIONAL] add first ip if you want ip range
* @parm	 	 string $lastip [OPTIONAL] add last ip if you want ip range
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_ipv4() {
}
if(!empty($lastip)){
$data['lastip'] = $lastip;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Add ips IPv6
*
* @category	 NETWORKING
* @parm	 	 array $ips6 [OPTIONAL] add IPv6 ips e.g. [0 => [ 1 => '2001', 2 => '0db8',...,8=>'56cf'], 1 => [ 1 => '2001', 2 => '0db8',...,8=>'56cf']]
* @parm	 	 array $gen_ipv6 [OPTIONAL] add 6 pair of hexdec bytes e.g ['0db8', '56cf', ...,'2001']
* @parm	 	 string $ipv6_num Number of IPv6 you want to generate it can't exceed 5000
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_ipv6() {
}
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List ips
*
* @category	 NETWORKING
* @param		 string $ip search with ip address
* @param		 string $user search by user
* @param		 boolean $lock search by lock and unlocked ip address options=[0 => 'For unlocked ip addresses', 1 => 'Locked ip address' ]
* @param		 string $reslen pass how much record you want to display per page (pass 'all' if you want to diplay all results)
* @param		 int $page pass page number
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_ips() {
}
if(!empty($user)){
$data['user'] = $user;
}
if(!is_null($lock)){
$data['lock'] = $lock;
}
if(!empty($reslen)){
$act = $act.'&reslen='.$reslen;
}
if(!empty($page)){
$act = $act.'&page='.$page;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Delete ips
*
* @category	 NETWORKING
* @param		 string $ips give ip address that you want to delete for multiple delte you can give comm seperate ip e.g. '192.168.1.1, 192.168.1.2, 192.168.1.3'
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_ips() {
}
* Lock ips
*
* @category	 NETWORKING
* @param		 string $ips give ip address that you want to lock for multiple lock you can give comm seperate ip e.g. '192.168.1.1, 192.168.1.2, 192.168.1.3'
* @return		 string $resp Response of Actions. Default: Serialize
*/
function lock_ips() {
}
* Unlock ips
*
* @category	 NETWORKING
* @param		 string $ips give ip address that you want to unlock for multiple unlock you can give comm seperate ip e.g. '192.168.1.1, 192.168.1.2, 192.168.1.3'
* @return		 string $resp Response of Actions. Default: Serialize
*/
function unlock_ips() {
}
* Edit ip
*
* @category	 NETWORKING
* @param		 string $ips give ip address that you want to unlock for multiple unlock you can give comm seperate ip e.g. '192.168.1.1, 192.168.1.2, 192.168.1.3'
* @return		 string $resp Response of Actions. Default: Serialize
*/
function edit_ip() {
}
* List Storage
*
* @category	 STORAGE
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_storage() {
}
* Add Storage
*
* @category	 STORAGE
* @param		 string $name The name of the storage
* @param		 string $path Path to the mount point
* @param		 string $type The type of storage options ['file', 'ext4', 'xfs', 'btrfs', 'zfs thin block', 'zfs thin block compressed', 'ceph block']
* @param		 string $alert If the used size crosses this percentage, an email will be sent to the Admin
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_storage() {
}
* Edit Storage
*
* @category	 STORAGE
* @param		 string $path Path to the mount point
* @param		 string $name The name of the storage
* @param		 string $type The type of storage options ['file', 'ext4', 'xfs', 'btrfs', 'zfs thin block', 'zfs thin block compressed', 'ceph block']
* @param		 string $alert If the used size crosses this percentage, an email will be sent to the Admin
* @return		 string $resp Response of Actions. Default: Serialize
*/
function edit_storage() {
}
* Delete Storage
*
* @category	 STORAGE
* @param		 string $storage Path to the mount point e.g. /home, /home2
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_storage() {
}
* List Email queue by domain or email
*
* @category	 EMAIL
* @param		 string $domain Enter domain if you want email queue by domain
* @param		 string $euser Enter domain email user if you want single email account emal queue
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_email_queue() {
}
if(!empty($euser)){
$data['euser'] = $euser;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List emails of all users of all domain
*
* @category	 EMAIL
* @param		 string $email Search By email account
* @param		 string $user Search by System/webuzo user
* @param		 string $reslen pass how much record you want to display per page (pass 'all' if you want to diplay all results)
* @param		 int $page pass page number
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_emails() {
}
if(!empty($user)){
$data['user'] = $user;
}
if(!empty($reslen)){
$act = $act.'&reslen='.$reslen;
}
if(!empty($page)){
$act = $act.'&page='.$page;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Task list
*
* @category	 TASKS
* @param		 string $user Enter system/webuzo user to search task list
* @param		 string $actid Search by task id
* @param		 string $reslen pass how much record you want to display per page (pass 'all' if you want to diplay all results)
* @param		 int $page pass page number
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_tasks() {
}
if(!empty($actid)){
$data['actid'] = $actid;
}
if(!empty($reslen)){
$act = $act.'&reslen='.$reslen;
}
if(!empty($page)){
$act = $act.'&page='.$page;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Add Ip-Block
*
* @category	 SECURITY
* @param		 string $ip IP Address/Domain You can specify the IP Address in the following format [ single ip/domain => 192.168.0.1 or example.com, Ip range => 192.168.0.1 - 192.168.0.50, CIDR Format => 192.168.0.1/20 ]
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_ipblock() {
}
* List IP-BLOCK
*
* @category	 SECURITY
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_ipblock() {
}
* Delete IP-BLOCK
*
* @category	 SECURITY
* @param		 string $record_id Give record id of ipblock list
* @param		 string $ip [OPTIONAL] give ip address to delete
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_ipblock() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Read CSF configuration
*
* @category	 SECURITY
* @return		 string $resp Response of Actions. Default: Serialize
*/
function read_csf_conf() {
}
* Enable/Disable SSH Access
*
* @category	 SECURITY
* @param        boolean $sshon Enable SSH/SFTP (1/0)
* @param        int $ssh_port Change SSH Port (default 22)
* @return		 string $resp Response of Actions. Default: Serialize
*/
function ssh_access() {
}
* List application category wise
*
* @category	 APPS
* @param		 string $cat give category of application e.g ['server_side_scripting', 'stacks', 'web_servers', 'databases',.....,'message_queue']
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_apps() {
}
if(!empty($cat)){
$act = $act.'&cat='.$cat;
}else{
$act = $act.'&cat=server_side_scripting';
}
$resp = $this->aapi_call($act);
$this->apps = $resp['apps'];
$this->apps_catwise[$cat] = $res['apps_catwise'];
return $resp['apps'];
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
$resp = $this->aapi_call($act);
return $resp;
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
$resp = $this->aapi_call($act);
return $resp;
}
* A Function that will list installed scripts
*
* @category	APPS
* @author		Vasu
* @return		array $scripts List of Installed Softaculous Scripts
* @since		3.0.0
*/
function list_installed_apps() {
}
$resp = $this->aapi_call($act);
$this->installed_apps = $resp['apps_ins'];
return $resp['apps_ins'];
}
* A Function that will list available update for apps
*
* @category	APPS
* @author		Vasu
* @return		array $scripts list available update for softaculous script
* @since		3.0.0
*/
function list_apps_updates() {
}
* A Function that will list available update for apps
*
* @category	APPS
* @param		int $app_id Pass app Id that you want to update
* @author		Vasu
* @return		array $resp
*/
function update_apps() {
}
* Remove backup file that create while updating app
*
* @category	APPS
* @param		int $binsid give backup file id that you want to remove
* @author		Vasu
* @return		array $resp
*/
function remove_app_backfile() {
}
* List Services
*
* @category	 APP
* @author		 Vasu
* @return		 string $resp Response of Action. Default: Serialize
*/
function list_services() {
}
* Manage Services
*
* @category	 APP
* @author		 Vasu
* @param        string $service_name Specify the service to restart
E.g exim, dovecot, tomcat, httpd, named, pure-ftpd, mysqld, php7.3
* @return		 string $resp Response of Action. Default: Serialize
*/
function manage_service() {
}
* List DNS record for all domain
*
* @category	 APP
* @author		 Vasu
* @param		 string $domain [OPTIONAL] Pass domain which you want DNS record
* @return		 string $resp Response of Action. Default: Serialize
*/
function list_dns() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
*	Add DNS record
*
* @category	 APP
* @author		 Vasu
* @param		 string $domain Pass domain which you want to add DNS record
* @param		 string $name name of DNS record (it will append with domain e.g $name'.'$domain)
* @param		 string $address Address of you dns record
* @param		 string $ttl Time to live of record
* @param		 string $type Type of recor you want (it is select type options are ['A' => 'A', 'CNAME' => 'CNAME', 'TXT' => 'TXT'] )
* @return		 string $resp Response of Action. Default: Serialize
*/
function create_dns() {
}
*	Edit DNS record
*
* @category	 APP
* @author		 Vasu
* @param		 int $point Pass the location of dns record which you want to edit
* @param		 string $domain Pass domain which you want to add DNS record
* @param		 string $name name of DNS record (it will append with domain e.g $name'.'$domain)
* @param		 string $address Address of you dns record
* @param		 int $ttl Time to live of record
* @param		 string $type Type of recor you want (it is select type options are ['A' => 'A', 'CNAME' => 'CNAME', 'TXT' => 'TXT'] )
* @return		 string $resp Response of Action. Default: Serialize
*/
function edit_dns() {
}
* List DNS record for all domain
*
* @category	 APP
* @author		 Vasu
* @param		 int $point Pass the location of dns record which you want to edit
* @param		 string $domain Pass domain which you want to delete DNS record
* @return		 string $resp Response of Action. Default: Serialize
*/
function delete_dns() {
}
* Add DNS Zone
*
* @category	APP
* @author	Tejas
* @param	string $domain Pass domain which you want to add DNS zone
* @param	string $address IP Address of your DNS zone
* @param	string $user user to which DNS zone will belong. Default: root.
* @return	string $resp Response of Action. Default: Serialize
*/
function add_dns_zone() {
}
* Delete DNS Zone
*
* @category	DNS Functions
* @author	Vikas
* @param	string $domain Pass domain (domains) to delete DNS zone
*/
function delete_dns_zone() {
}
* Create DNS templates
*
* @category	 DNS Functions
* @author		 Vikas
* @param		 string $name Pass dns name for a domain
* @param		 string $type DNS type
* @param		 string $ttl DNS TTL value
* @param		 string $address DNS Record / content
*/
function create_dns_template() {
}
* Edit DNS templates
*
* @category	 DNS Functions
* @author		 Vikas
* @param		 int $point Pass array key to edit
* @param		 string $name Pass dns name for a domain
* @param		 string $type DNS type
* @param		 string $ttl DNS TTL value
* @param		 string $address DNS Record / content
*/
function edit_dns_template() {
}
* read DNS templates
*
* @category	 DNS Functions
* @author		 Vikas
*/
function read_dns_template() {
}
* Set DNS TTL
*
* @category	 DNS Functions
* @author		 Vikas
*/
function set_dns_ttl() {
}
* Set DNS TTL
*
* @category	 DNS Functions
* @author		 Vikas
*/
function sso() {
}
* Set root MySQL password
*
* @category	 APP
* @author		 Vasu
* @param        string $pass New password to change root(MySQL) password
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_root_mysql_pass() {
}
* Enable/Disable fastcgi of apache
*
* @category	 APP
* @author		 Vasu
* @param        boolean $fastcgi pass 1 if you want to enable Fast CGI
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_fastcgi() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Extra configuration for apache
*
* @category	 APP
* @author		 Vasu
* @param        boolean $http2 Enable HTTP/2 protocol
* @param        boolean $gzip Enable Gzip Compression
* @param        boolean $user_mod Enable USER MOD DIR
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_http2settings() {
}
if(!empty($gzip)){
$data['gzipon'] = $gzip;
}
if(!empty($user_mod)){
$data['user_mod_dir'] = $user_mod;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Enable NGINX Proxy
*
* @category	 APP
* @author		 Vasu
* @param        int $port Port for running Apache Service
* @param        string $webserver Select the Proxy server to be used. (apache or apache2).
* @param        boolean $ht_check Check if you want to allow .htaccess files.
* @return		 string $resp Response of Actions. Default: Serialize
*/
function enable_proxy() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Disable NGINX Proxy
*
* @category	 APP
* @param        String $proxy_server - Default Webserver to be set - "httpd,apache2,nginx,lighttpd"
* @return		 array $resp
*/
function disable_proxy() {
}
* Turn on varnish if varnish installed
*
* @category	 APP
* @author		 Vasu
* @param        boolean $varnish Enable/Disable Varninsh
* @param        int $port Port on which varnish enable
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_varnish() {
}
$data['varnish_port'] = $port;
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Set Default
*
* @category	 APP
* @param        string $service Set the Default Service - php53/php54/nginx/httpd
* @return		 string $resp Response of Actions. Default: Serialize
*/
function set_defaults() {
}
if(in_array($service, $server)){
$data['webserver'] = $service;
}
$data['service_manager'] = 1;
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List PHP Extensions
*
* @category	 APP
* @param        int $php [OPTIONAL] list extension by php app id
* @return		 array	$resp Response of Action. Default: Serialize
*/
function list_php_ext() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Enable / Disable PHP Extensions
*
* @category	 APP
* @param        string $php pass php app id on which extention want to save e.g. 146_1
* @param        string $extensions give extention list in comma seperated valu e.g.  bcmath.so,bz2.so,calendar.so,ctype.so,....,etc (if you want to disable all extension just pass "disableall" string)
* @return		 array	$resp Response of Action. Default: Serialize
*/
function handle_php_ext() {
}
* List Service TLS
*
* @category	 APP
* @param        string $user [optional] search cert by system/webuzo user
* @return		 array	$resp Response of Action. Default: Serialize
*/
function list_service_cert() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Apply domain certificate on services
*
* @category	 APP
* @param        array $services list of service name that you want to install cert e.g. ['webuzo', 'exim', ...., 'pureftpd',];
* @param        string $domain domain cert to install on servies
* @return		 array	$resp Response of Action. Default: Serialize
*/
function apply_dom_cert() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Reset Certificate on service
*
* @category	 APP
* @param        string $service pass name of service to remove cert
* @return		 array	$resp Response of Action. Default: Serialize
*/
function reset_cert() {
}
* Reset Certificate on service
*
* @category	 APP
* @author		 Vasu
* @param        array $services list of service name that you want to install cert e.g. ['webuzo', 'exim', ...., 'pureftpd',];
* @param        string $kpaste private key of certificate
* @param        string $cpaste certificate
* @param        string $bpaste [OPTIONAL] certificate buddle
* @return		 array	$resp Response of Action. Default: Serialize
*/
function apply_cert_mannually() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Get CPU infomation
*
* @category	 SERVERUTILITIES
* @author		 Vasu
* @return		 array	$resp Response of Action. Default: Serialize
*/
function cpu_info() {
}
* Get RAM infomation (in MB)
*
* @category	 SERVERUTILITIES
* @author		 Vasu
* @return		 array	$resp Response of Action. Default: Serialize
*/
function ram_info() {
}
* Get Disk info
*
* @category	 SERVERUTILITIES
* @author		 Vasu
* @return		 array	$resp Response of Action. Default: Serialize
*/
function disk_info() {
}
* Reboot the server
*
* @category	 SERVERUTILITIES
* @author		 Vasu
* @param		 string $type type of reboot options ['grace', 'force']
* @return		 array	$resp Response of Action. Default: Serialize
*/
function server_reboot() {
}
* List Error Log file wise
*
* @category	 SERVERUTILITIES
* @author		 Vasu
* @param		 string $logfile [OPTIONAL] pass the log file default it will list webuzo log
* @param		 string $clearlog [OPTIONAL] pass if you want to clear log
* @return		 array	$resp Response of Action. Default: Serialize
*/
function list_errorlog() {
}
if(!empty($clearlog)){
$data['clear_log'] = $clearlog;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Show Error Log domain wise
*
* @category	 SERVERUTILITIES
* @author		 Vasu
* @param        string $domain Domain for the error log (Opional)
* @param        boolean $clearlog [OPTIONAL] if clear the log
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_domain_errorlog() {
}
if(!empty($clearlog)){
$data['clearlog'] = 1;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List Admin CRON
*
* @category	 SERVERUTILITIES
* @author		 Vasu
* @param        string $user [OPTIONAL] if it pass it get perticular user cron
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_cron() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Add / Edit a Admin CRON
*
* @category	 SERVERUTILITIES
* @author		 Vasu
* @param        string $cmd Command of the cron part
* @param        string $minute Minute of the cron part
* @param        string $hour Hour of the cron part
* @param        string $day Day of the cron part
* @param        string $month Month of the cron part
* @param        string $weekday Weekend of the cron part
* @param        string $edit_cronjob [OPTIONAL] If you want to update cron use this, pass record key
* @param        string $user [OPTIONAL] if not pass by default it will take logged in user
* @return		 string $resp Response of Actions. Default: Serialize
*/
function add_cron() {
}
if(!empty($edit_cronjob)){
$data['edit_record'] = 'c'.$edit_cronjob;
}else{
$data['create_record'] = 1;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* Delete Admin CRON
*
* @category	 SERVERUTILITIES
* @author		 Vasu
* @param        string $id ID of the cron record. Get from the list of cron
* @param        string $user [OPTIONAL] if not pass by default it will take logged in user
* @return		 string $resp Response of Actions. Default: Serialize
*/
function delete_cron() {
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List Admin Login Logs
*
* @category	SERVERUTILITIES
* @author		Vasu
* @param	 	string $ip [OPTIONAL] if you pass it will  Delete all Login Logs
* @param	 	delete_all [OPTIONAL] if you pass it will  Delete all Login Logs
* @param		 string $reslen pass how much record you want to display per page (pass 'all' if you want to diplay all results)
* @param		 string $page pass page number
* @return		string $resp Response of Actions. Default: Serialize
*/
function list_login_logs() {
}
if(!empty($user)){
$data['user'] = $user;
}
if(!empty($owner)){
$data['owner'] = $owner;
}
if(!empty($delete_all)){
$data['delete_all'] = 1;
}
if(!empty($reslen)){
$act = $act.'&reslen='.$reslen;
}
if(!empty($page)){
$act = $act.'&page='.$page;
}
$resp = $this->aapi_call($act, $data);
return $resp;
}
* List Installed Plugin
*
* @category	 PLUGIN
* @author		 Vasu
* @return		 string $resp Response of Actions. Default: Serialize
*/
function list_plugin() {
}
* Update the webuzo
*
* @category	 UPDATE
* @author		 Vasu
* @return		 string $resp Response of Actions. Default: Serialize
*/
function update_webuzo() {
}
}
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_domains();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Domain List: '.PHP_EOL;
$test->r_print($res);
} */
$res = $test->add_domain('example.com', 'public_html/example', 'addon');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->edit_domain('example.com', 'public_html/example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$res = $test->delete_domain('example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
} */
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_domains_alias();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Domain Alias List: '.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_domain_alias('dom1.com', 'dom2.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
} */
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_domain_alias('dom1.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_dns_record();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'DNS List: '.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_dns_record('example.com', 'test', '14400', 'A', '192.168.14.129');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->edit_dns_record( 7, 'example.com', 'test1', '14400', 'A', '192.168.14.129');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_dns_record(7, 'example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->dns_lookup('example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Lookup/Traceroute Log:';
echo $res['done']['lookup'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_ftpuser();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'FTP accout list:';
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_ftpuser('test', 'pass', 'test', 100);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->edit_ftpuser('test_primary.domain');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->change_ftpuser_pass('test_primary.vasu.com', 'password');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ftpuser('test_primary.domain', 1);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_ftp_connections();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Active FTP connections'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ftp_connection($ftp_connection_pid);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_database();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Database List:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_database('test');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_database('user_test');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_db_user();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Database User List:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_db_user('testuser', 'passs');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_db_user('user_testuser');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->change_db_user_pass('vasu_test', 'pass');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->set_privileges('user_test1', 'user_test', 'localhost', 'SELECT,CREATE,INSERT,UPDATE,ALTER,DELETE,INDEX,CREATE_TEMPORARY_TABLES,EXECUTE,DROP,LOCK_TABLES,REFERENCES,CREATE_ROUTINE,CREATE_VIEW,SHOW_VIEW');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_remote_mysql_access();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Remote Access List: '.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_remote_mysql_access('vasu_test1');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_remote_mysql_access('test1');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_ssl_key();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List SSL key: '.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->create_ssl_key('Description');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->upload_ssl_key($description, $keypaste);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->detail_ssl_key($key_name);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Key Detail:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ssl_key($key_path);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_ssl_csr();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List SSL CSR:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->create_ssl_csr($domain, $country_code, $state, $locality, $org, $org_unit,  $passphrase, $email, $key);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->detail_ssl_csr($domain);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Key Detail:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ssl_csr($path);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_ssl_crt();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'LIST of SSL Certificates:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->create_ssl_crt($domain, $country_code, $state, $locality, $org, $org_unit, $email, $key);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->upload_ssl_crt($keypaste);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->detail_ssl_crt($domain);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Certificates Detail:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ssl_crt($domain_key);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_install_cert();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Installed Certificates:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->install_cert($domain, $kpaste, $cpaste);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_install_cert($record_key);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->detail_install_cert($record_key);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Detail Certificate:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_le();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List LE Certificates :'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->install_le_cert($domain);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->revoke_le_cert($domain);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->renew_le_cert($domain);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_emailuser();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List Email Accounts :'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_emailuser('example.com', 'emailuser', 'password');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->change_email_user_pass('example.com', 'emailuser', 'password', 10);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_email_user('example.com', 'emailuser');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_emailforward('example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List Email Forwards :'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_emailforward('example.com', 'sdk1', 'test@example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_email_forward('example.com', 'sdk1', 'test@example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_mx_entry('example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List MX Entry :'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_mx_entry(20, 'example.com', 'example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->edit_mx_entry('example.com', 7, 22, 'example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_mx_entry('example.com', 7);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_emailautoresponder('example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List Email Autoresponder :'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_emailautoresponder('test', 'testing subject', 'testing body', 'example.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->edit_emailautoresponder('example.com', 'test4', 'testing subject Edit', 'testing body Edit');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_emailautoresponder('test4');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_email_queue('test4');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Email Queue :'.PHP_EOL;
$test->r_print($res['mailq']);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_spam_assassin('user1@domain.com', 'blacklist');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_spam_assassin();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Email SPAM List :'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_spam_assassin('user1@domain.com', 'blacklist');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$data['example.com'] = 'php74';
$res = $test->set_multi_php($data);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$ini = '; WEBUZO-generated php ini directives, do not edit
;Manual editing of this file may result in unexpected behavior.
; To make changes to this file, use the WEBUZO PHP INI Editor (Home >> Configuration >> MultiPHP INI Editor)
display_errors = on
max_execution_time = 600
max_input_time = 60
memory_limit = 800M
post_max_size = 800M
session.gc_maxlifetime = 1440
session.save_path = /tmp
upload_max_filesize = 200M
zlib.output_compression = on
max_input_vars = 1100';
$res = $test->save_php_ini('primary.domain.com', $ini);
if(!empty($res['done'])){
echo !empty($res['done']['msg']) ? $res['done']['msg'] : 'Succefully call save_php_ini()';
}else{
echo 'Error while adding domain<br/>';
$test->r_print($res['error']);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->get_php_ini('example.com');
if(!empty($res['done'])){
echo 'Local PHP INI content:'.PHP_EOL;
$test->r_print($res['done']['user_ini_content']);
}else{
echo 'Error while performing action<br/>';
$test->r_print($res['error']);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->get_installed_PEAR();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'User Installed Pear Dir : '.PHP_EOL;
echo $res['user_pear']['dir'].PHP_EOL;
echo 'User Installed Pear List: '.PHP_EOL;
$test->r_print($res['user_pear']['data']);
echo 'System Installed Pear: '.PHP_EOL;
$test->r_print($res['system_pear']['data']);
} */
$res = $test->get_PEAR_list();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Available Pear list : '.PHP_EOL;
$test->r_print($res['pearlist']);
} */
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->action_PEAR('Config', 'install');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Pear Action Log : '.PHP_EOL;
$test->r_print($res['done']['log']);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->change_password($webuzo_password);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->change_tomcat_pwd($pass);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_ipblock($ip);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_ipblock();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List Blocked IP\'s : '.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ipblock('192.168.12.1/32');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->ssh_generate_keys('test', 'test123');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_ssh_access('test', 'test123');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List SSH access key : '.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->ssh_key_auth('test.pub', 'Deauthorize');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->view_ssh_key('test.pub');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'SSH private/public keys : '.PHP_EOL;
$test->r_print($res['view_content']);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ssh_key('test.pub');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->ssh_import_keys($keyname, $private_key, $passphrase, $public_key);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_pass_protect_dir('test', 'testuser', 'testuser', 'Test Account');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_pass_protect_dir();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List password Protected User : '.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_pass_protect_dir('test', 'test');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_hotlink_protect();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$urllist = 'http://example1.com
https://example2.com
';
$res = $test->enable_hotlink_protect($urllist, 'jpg,jpeg,gif,png,bmp', true, 'http://google.com');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->disable_hotlink_protect();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_mod_security();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Mod Security list : '.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->change_mod_security(3);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_backup_server('test', $type, $remote_username, $remote_ip, $remote_user_pass, $remote_port, $remote_location);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_backup();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List backup server:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->remove_backup_server(3);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
#to clear login log pass 1 as parameters
$res = $test->list_login_logs();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List login\'s logs:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
#to clear login log pass 1 as parameters
$res = $test->list_cron();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'List Crons:'.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_cron('*', '*', '*', '3', '*', 'php -v');
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_cron('*', '*', '*', '3', '3', 'php -v', 2);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_cron(2);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->show_error_log();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Error log '.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->show_bandwidth();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'BANDWIDTH usage : '.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_disk_usage();
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo 'Disk usage : '.PHP_EOL;
$test->r_print($res);
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->user_settings($email, $language, $arrange_domain);
if(!empty($res['error'])){
echo 'Error while performing action : ';
$test->r_print($res['error']);
}else{
echo $res['done']['msg'];
}
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK();
$res = $test->webuzo_configure($ip, $user, $email, $pass, $host, $ns1 = '', $ns2 ='', $license = '' );
$res = unserialize($res);
$test->r_print($res);
*/
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
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