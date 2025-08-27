<?php

//////////////////////////////////////////////////////////////
//===========================================================
// WEBUZO CONTROL PANEL
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit
// Date:       10th Jan 2009
// Time:       21:00 hrs
// Site:       https://webuzo.com/ (WEBUZO)
// ----------------------------------------------------------
// Please Read the Terms of Use at https://webuzo.com/terms
// ----------------------------------------------------------
//===========================================================
// (c) Softaculous Ltd.
//===========================================================
//////////////////////////////////////////////////////////////



/**
 * Webuzo enduser SDK
 *
 * @category	 SDK  
 * @param        string $user The username to LOGIN
 * @param        string $pass The password
 * @param        string $host The host to perform actions
 * @param        string $sess_cookie The sess_cookie of the user
 */
class Webuzo_SDK{

	// The Login URL
	var $login = '';
	
	var $debug = 0;
	
	var $error = array();

	// THE POST DATA
	var $data = array();
	
	var $apps = array(); // List of Apps
	
	var $installed_apps = array(); // List of Installed Apps
	
	/**
	 * Initalize API login
	 *
	 * @category	 Login  
	 * @param        string $user The username to LOGIN
	 * @param        string $pass The password
	 * @param        string $host The host to perform actions
	 * @return       void
	 */
	function __construct($user = '', $pass = '', $host = '', $method = ''){
		
		$this->curl_log = fopen('php://temp', 'wb');
		
		// API Method
		if(!empty($method) && is_string($method) && $method == 'api'){
			
			$this->login = 'https://'.$host.':2003/index.php?apiuser='.$user.'&apikey='.$pass.'&';
		
		// API Method via array
		}elseif(!empty($method) && is_array($method) && $method['type'] == 'api'){
			
			$_user = $user;
			if(!empty($method['loginAs'])){
				$_user = 'root';
				$loginAs = $user;
			}
			
			$this->login = 'https://'.$host.':2003/index.php?apiuser='.$_user.'&apikey='.$pass.'&'.(!empty($method['loginAs']) ? 'loginAs='.$user.'&' : '');
		
		// Backward Compat : Session Cookies
		}elseif(!empty($method)){
			
			$this->sess_cookie = $method;
			$url_token = 'sess'.substr(current($method), 0, 16);
			$this->login = 'https://'.$host.':2003/'.$url_token.'/index.php';
			
		}else{
		
			$this->login = 'https://'.rawurlencode($user).':'.rawurlencode($pass).'@'.$host.':2003/index.php';
		
		}
	
	}
	
	/**
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
	
		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_STDERR, $this->curl_log);

		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		// Follow redirects
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		
		if(!empty($this->curl_timeout)){
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
			$this->curl_timeout = 0;
		}
		
		if(!empty($post)){ 
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		
		// Session cookie
		if(!empty($this->sess_cookie)){
			$cookies = array_merge($cookies, $this->sess_cookie);
		}
		
		// Is there a Cookie
		if(!empty($cookies)){
			curl_setopt($ch, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch, CURLOPT_COOKIE, http_build_query($cookies, null, ';'));
		}
		
		// We ONLY need this for directadmin to get the session cookie else we need the Header DISABLED
		if(!empty($header)){
			curl_setopt($ch, CURLOPT_HEADER, 1);
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// Get response from the server.
		$resp = curl_exec($ch);
		
		// Did we get the file ?
		if($resp === false){
			$this->error[] = 'cURL Error : '.curl_error($ch);
		}
		
		curl_close($ch);
		
		return $resp;
	}
	
	/**
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
		
		// Add the ?
		if(!strstr($url, '?')){
			$url = $url.'?';
		}
		
		// Login Page with Softaculous Parameters
		$url = $url.$act;
		
		// Set the API mode
		if(!strstr($url, 'api=')){
			$url = $url.'&api='.$this->format;
		}
		
		$resp = $this->curl($url, $post);
		
		return $resp;
		
	}
	
	/**
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
		
		// Add the ?
		if(!strstr($url, '?')){
			$url = $url.'?';
		}
		
		// Login Page with Softaculous Parameters
		$url = $url.$act;
		
		// Set the API mode
		if(!strstr($url, 'api=')){
			$url = $url.'&api=serialize';
		}
		
		$resp = $this->curl($url, $post);
		
		//echo $resp;
		
		if(!empty($resp)){
			$resp = unserialize(trim($resp));
		}
		
		return $resp;
		
	}
	
	/**
	 * Check login error
	 *
	 * @category	 error
	 * @return       array
	 */
	function chk_error(){
		if(!empty($this->error)){
			return $this->r_print($this->error[0]);
		}		
	}
	
	/**
	 * Prints result
	 *
	 * @category	 Debug
	 * @param        Array $data
	 * @return       array
	 */
	function r_print($data){
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//							CATEGORY : ENDUSER DOMAIN						 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * List Domains
	 *
	 * @category	 Domain
	 * @return		string $resp Response of Action. Default: Serialize
	 */
	function list_domains(){
		$act = 'act=domainmanage';
		$resp = $this->eapi_call($act);
				
		return $resp;
	}
	
	/**
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
	function add_domain($domain, $domainpath, $domain_type = 'parked', $subdomain = '', $validate_mails = '', $issue_lecert = '', $wildcard = '', $ip = '', $ipv6 = ''){
		
		// The act
		$act = 'act=domainadd';

		$data['domain'] = $domain;
		
		if(!empty($wildcard)){
			$data['domain'] = '*.'.$domain;
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
	
	/**
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
	function edit_domain($domain, $domainpath, $subdomain = '', $validate_mails = '', $issue_lecert = '', $wildcard = '', $ip = '', $ipv6 = ''){
		
		// The act
		$act = 'act=domainedit';

		$data['domain'] = $domain;
		
		if(!empty($wildcard)){
			$data['wildcard'] = 1;
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
	
	/**
	 * Delete Domain
	 *
	 * @category	 Domain
	 * @param        string $domain The domain to delete
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function delete_domain($domain){
		
		// The act
		$act = 'act=domainmanage';	
		
		$data['delete'] = $domain;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Domains alias
	 *
	 * @category	 Domain
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function list_domains_alias(){
		$act = 'act=aliases';
		$resp = $this->eapi_call($act);
				
		return $resp;
	}
	
	/**
	 * Add/Edit Domain Alias
	 *
	 * @category	 Domain
	 * @param        string $domain The domain to add (if domain exist it will edited);
	 * @param		 string $redirect_to The redirected path
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function add_domain_alias($domain, $redirect_to){
		
		// The act
		$act = 'act=aliases';

		$data['domain'] = $domain;

		$data['redirect_to'] = $redirect_to;

		$data['add'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Delete Domain alias
	 *
	 * @category	 Domain
	 * @param        string $domain The domain to delete
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function delete_domain_alias($domain){
		
		// The act
		$act = 'act=aliases';	
		
		$data['delete'] = $domain;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List DNS Record
	 *
	 * @category	 Server Settings
	 * @param        string $domain [OPTIONAL] Specify domain to list DNS records ( BY default it will take primary domain)
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function list_dns_record($domain = ''){
		
		$act = 'act=advancedns';
		
		if(!empty($domain)){
			$data['domain'] = $domain;
		}

		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	
	/**
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
	function add_dns_record($domain, $name, $ttl, $type, $address){
		
		$act = 'act=advancedns';
				
		$data['domain'] = $domain;
		$data['name'] = $name;
		$data['ttl'] =  $ttl;
		$data['selecttype'] = $type;
		$data['address'] = $address;
		$data['add'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
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
	function edit_dns_record($point, $domain, $name, $ttl, $type, $address){
		
		$act = 'act=advancedns';
		
		$data['edit'] = $point;
		$data['domain'] = $domain;
		$data['name'] = $name;
		$data['ttl'] = $ttl;
		$data['type'] = $type;
		$data['record'] = $address;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete DNS Record
	 *
	 * @category	 Server Settings
	 * @param        string $id ID of Dns record for delete
	 * @param        string $domain Domain for the DNS record for delete
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_dns_record($id, $domain){
		
		$act = 'act=advancedns';
		
		$data['delete'] = $id;
		$data['domain'] = $domain;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Networking Tools
	 *
	 * @category	 DNS
	 * @param		 @param $domain domain on which you want to lookup
	 * @param		 @param (Optional)$traceroute it traceroute the domain by default it * lookup action is called.
	 *					(Available options are 'lookup' & 'traceroute')
	 * @return		 array	$resp Response of Action. Default: Serialize
	 */
	function dns_lookup($domain, $traceroute = ''){
		
		$act = 'act=network_tools';
		
		$data['domain_name'] = $domain;
		
		if(!empty($traceroute)){
			$data['traceroute'] = 1;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	///////////////////////////////////////////////////////////////////////////////////
	//						 		CATEGORY: FTP						 			 //
	///////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * List FTP users
	 *
	 * @category	 FTP
	 * @return       array
	 */
	function list_ftpuser(){
		$act = 'act=ftp';
		$resp = $this->eapi_call($act);
		
		return $resp;		
	}
	
	/**
	 * Add FTP user
	 *
	 * @category	 FTP
	 * @param        string $user The FTP username
	 * @param        string $pass The password for the FTP user
	 * @param        string $directory The Directory path for the FTP users relative to /HOME/USER
	 * @param        string $quota_limit (Optional) Define a quota for the user (in MB)
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function add_ftpuser($user, $pass, $domain, $directory, $quota_limit = ''){
		
		$act = 'act=ftp_account';
		
		$data['login'] = $user;
		$data['newpass'] = $data['conf'] = $pass;
		$data['dir'] = $directory;
		$data['ftpdomain'] = $domain;
		
		if(!empty($quota_limit)){
			$data['quota'] = 'limited';
			$data['quota_limit'] = $quota_limit;
		}else{
			$data['quota'] = 'unlimited';
		}
		
		$data['create_acc'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Edit FTP user
	 *
	 * @category	 FTP
	 * @param        string $user FTP user to EDIT data
	 * @param        string $quota_limit (Optional) Specify quota limit to the user (in MB)
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function edit_ftpuser($user, $quota_limit = ''){
		
		$act = 'act=ftp';
		
		$data['edit_ftp_user'] = $user;
		if(!empty($quota_limit)){
			$data['quota'] = 'limited';
			$data['quota_limit'] = $quota_limit;
		}else{
			$data['quota'] = 'unlimited';
		}
		$data['edit_record'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Change FTP User's Password
	 *
	 * @category	 FTP
	 * @param        string $user FTP user to change Password
	 * @param        string $pass New password for the FTP user
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function change_ftpuser_pass($user, $pass){
		
		$act = 'act=editftp';
		
		$data['fuser'] = $user;
		$data['newpass'] = $data['conf'] = $pass;
		$data['changepass'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete FTP user
	 *
	 * @category	 FTP
	 * @param        string $user FTP user to delete
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function delete_ftpuser($user, $home_dir = ''){
		
		$act = 'act=ftp';
		
		$data['delete_ftp_user'] = $user;
		
		if(!empty($home_dir)){
			$data['delete_home_dir'] = $home_dir;
		}
		
		$data['delete_fuser_id'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List FTP Connections
	 *
	 * @category	 FTP
	 * @return       array
	 */
	function list_ftp_connections(){
		
		$act = 'act=ftp_connections';
		
		$resp = $this->eapi_call($act);		
		
		return $resp;
		
	}
	
	/**
	 * Delete FTP Connection
	 *
	 * @category	 FTP
	 * @param        string		$ftp_connection_id	FTP Connection Process ID
	 * @return		 string		$resp				Response of Action. Default: Serialize
	 */
	function delete_ftp_connection($ftp_connection_id){
		
		$act = 'act=ftp_connections';
		
		$data['ftp_connection_pid'] = $ftp_connection_id;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//							CATEGORY : DATABASE								 //
	///////////////////////////////////////////////////////////////////////////////
		
	/**
	 * List Database with its size and users
	 *
	 * @category	 Database
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function list_database(){
		
		$act = 'act=dbmanage';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/**
	 * Add Database
	 *
	 * @category	 database
	 * @param        string $db_name Database name to create
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function add_database($db_name){
		
		$act = 'act=dbmanage';
		
		$data['db'] = $db_name;
		$data['submitdb'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete Database
	 *
	 * @category	 database
	 * @param        string $db_name Database name to delete
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function delete_database($db_name){
		
		$act = 'act=dbmanage';
		
		$data['delete_db'] = $db_name;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	
	/**
	 * List Database Users
	 *
	 * @category	 Database
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function list_db_user(){
		
		$act = 'act=dbmanage';		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/**
	 * Add Database User
	 *
	 * @category	 database
	 * @param        string $db_user Database username to ADD
	 * @param        string $pass Password for the database user
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function add_db_user($db_user, $pass){
		
		$act = 'act=dbmanage';
		
		$data['dbuser'] = $db_user;
		$data['dbpassword'] = $pass;
		$data['submituserdb'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete Database user
	 *
	 * @category	 database
	 * @param        string $db_user Database user to delete
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function delete_db_user($db_user){
		
		$act = 'act=dbmanage';
		
		$data['delete_dbuser'] = $db_user;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Change Database user Password
	 *
	 * @category	 database
	 * @param        string $db_user Database user
	 * @param        string $db_pass Database user password
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function change_db_user_pass($db_user, $db_pass){
		
		$act = 'act=dbmanage';
		
		$data['db_user'] = $db_user;
		$data['dbpass'] = $db_pass;
		$data['change_user_pass'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Set Privileges for a User to a specific database
	 *
	 * @category	 database
	 * @param        string $database Database name to ADD privileges
	 * @param        string $db_user Database users name to ADD privileges
	 * @param        string $host Database host
	 * @param        string $prilist Set of privileges to be given to the User
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function set_privileges($database, $db_user, $host, $prilist){
		
		$act = 'act=dbmanage';
		
		$data['dbname'] = $database;
		$data['dbuser'] = $db_user;
		$data['host'] = $host;
		$data['pri'] = $prilist;
		$data['submitpri'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List Mysql Remote Access
	 *
	 * @category	 database
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function list_remote_mysql_access(){
		
		$act = 'act=remote_mysql_access';
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Add Mysql Remote Access
	 *
	 * @category	 database
	 * @param        string $dbuser Database user
	 * @param        string $dbhost Database host
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function add_remote_mysql_access($dbuser, $dbhost = 'localhost' ){
		
		$act = 'act=remote_mysql_access';
		
		$data['host'] = $dbuser;
		$data['user'] = $dbhost;
		$data['add'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete Mysql Remote Access
	 *
	 * @category	 database
	 * @param        string $dbuser Database user
	 * @param        string $dbhost Database host
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function delete_remote_mysql_access($dbuser, $dbhost = 'localhost' ){
		
		$act = 'act=remote_mysql_access';
		
		$data['host'] = $dbuser;
		$data['user'] = $dbhost;
		$data['delete'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	//////////////////////////////////////////////////////////////////////////
	//					   		CATEGORY : Enduser SSL						//
	//////////////////////////////////////////////////////////////////////////
	
	/**
	 * List SSL Key
	 *
	 * @category	 Security
	  * @return		string $resp Response of Actions. Default: Serialize
	 */
	function list_ssl_key(){
		
		$act = 'act=sslkey';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/**
	 * Create SSL Key
	 *
	 * @category	 Security
	 * @param        string $description Domain name or any name for the SSL Key
	 * @param        string $keysize Size of the SSl Key
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function create_ssl_key($description, $keysize = ''){
		
		$act = 'act=sslkey';
		
		$data['selectdom'] = $description;	// Specify DOMAIN
		$data['keysize'] = (empty($keysize) ? '1024' : $keysize);	// Specify Key size
		$data['create_key'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Upload SSL Key
	 *
	 * @category	 Security
	 * @param        string $description Domain name or any name for the SSL Key
	 * @param        string $keypaste Entire SSL Key
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function upload_ssl_key($description, $keypaste){
		
		$act = 'act=sslkey';
		
		$data['selectdom'] = $description;	// Specify DOMAIN
		$data['kpaste'] = $keypaste;		// Specify KEY contents
		$data['install_key'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Detail SSL Key
	 *
	 * @category	 Security
	 * @param        string $domain Specify domain name to detail view of SSL Key
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function detail_ssl_key($domain){
		
		$act = 'act=sslkey';
		
		$data['detail_record'] = $domain;		// Specify DOMAIN
		
	    $resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete SSL Key
	 *
	 * @category	 Security
	 * @param        string $domain Specify domain name to delete SSL Key
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_ssl_key($path){
		
		$act = 'act=sslkey';
		
		$data['delete_record'] = $path;		// Specify DOMAIN
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List SSL CSR
	 *
	 * @category	 Security	 
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_ssl_csr(){
		
		$act = 'act=sslcsr';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/**
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
	function create_ssl_csr($domain, $country_code, $state, $locality, $org, $org_unit,  $passphrase, $email, $key){
		
		$act = 'act=sslcsr';
		
		$data['domain'] = $domain;	// Specify DOMAIN - Note : Domain should have a Private KEY
		$data['country'] = $country_code;	// Specify Country Code
		$data['state'] = $state;			// Specify State
		$data['locality'] = $locality;		// Specify Locality
		$data['organisation'] = $org;		// Specify Organization
		$data['orgunit'] = $org_unit;		// Specify Organization Unit
		$data['pass'] = $passphrase;		// Specify PASSPHRASE
		$data['email'] = $email;			// Specify Email
		$data['selectkey'] = $key;		    // Specify key. if you want to generate new key then pass "newkey" as argument.
		$data['createcsr'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Detail SSL CSR
	 *
	 * @category	 Security
	 * @param        string $domain Specify domain name to detail view of SSL CSR
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function detail_ssl_csr($domain){
		
		$act = 'act=sslcsr';
		
		$data['detail_record'] = $domain;	// Specify DOMAIN
		
	    $resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete SSL CSR
	 *
	 * @category	 Security
	 * @param        string $domain Specify domain name to delete SSL CSR
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_ssl_csr($path){
		
		$act = 'act=sslcsr';
		
		$data['delete_record'] = $path;	// Specify DOMAIN
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List SSL Certificate
	 *
	 * @category	 SSL
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_ssl_crt(){
		
		$act = 'act=sslcrt';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/**
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
	function create_ssl_crt($domain, $country_code, $state, $locality, $org, $org_unit, $email, $key){
		
		$act = 'act=sslcrt';
		
		$data['domain'] = $domain;			// Specify DOMAIN - Note : Domain should have a KEY
		$data['country'] = $country_code;	// Specify Country Code
		$data['state'] = $state;			// Specify State
		$data['locality'] = $locality;		// Specify Locality
		$data['organisation'] = $org;		// Specify Organization
		$data['orgunit'] = $org_unit;		// Specify Organization Unit
		$data['email'] = $email;			// Specify Email
		$data['selectkey'] = $key;		    // Specify key. if you want to generate new key then pass "newkey" as argument.
		$data['create_crt'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Upload SSL Certificate
	 *
	 * @category	 SSL
	 * @param        string $keypaste Entire certificate.
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function upload_ssl_crt($keypaste){
		
		$act = 'act=sslcrt';
		
		$data['kpaste'] = $keypaste;			// Specify Certificatae Contents
		$data['install_crt'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Detail SSL Certificate
	 *
	 * @category	 SSL
	 * @param        string $domain Specify domain name to detail view of SSL Certificat
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function detail_ssl_crt($domain){
		
		$act = 'act=sslcrt';
		
		$data['detail_record'] = $domain;	// Specify DOMAIN
		
	    $resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete SSL Certificate
	 *
	 * @category	 SSL
	 * @param        string $domain Specify domain name to delete SSL Certificat
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_ssl_crt($domain){
		
		$act = 'act=sslcrt';
		
		$data['delete_record'] = $domain;	// Specify DOMAIN
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List install certificate
	 *
	 * @category	 SSL
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_install_cert(){
		
		$act = 'act=install_cert';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/**
	 * install_cert
	 *
	 * @category	 SSL
	 * @param		 $domain domain on which you want to install certificate
	 * @param		 $kpaste Paste your key
	 * @param		 $cpaste Paste your Certificate
	 * @param		 $bpaste Paste the CA bundle. (Optional)
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function install_cert($domain, $kpaste, $cpaste, $bpaste = ''){
		
		$act = 'act=install_cert';
		
		$data['selectdomain'] = $domain;
		$data['kpaste'] = $kpaste;
		$data['cpaste'] = $cpaste;
		
		if(!empty($bpaste)){
			$data['bpaste'] = $bpaste;
		}
		$data['install_key'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete cert
	 *
	 * @category	 SSL
	 * @param		 $record which record you want to delete
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_install_cert($record){
		
		$act = 'act=install_cert';
		
		$data['delete_record'] = $record;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Detail cert
	 *
	 * @category	 SSL
	 * @param		 $record which record you want to detail
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function detail_install_cert($record){
		
		$act = 'act=install_cert';
		
		$data['detail_record'] = $record;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List le certificate
	 *
	 * @category	 SSL
	 * @param	 	 $clear_log [OPTIONAL]  clear LE log
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_le($clear_log = ''){
		
		$act = 'act=lets_encrypt';
		
		$data = [];
		if(!empty($clear_log)){
			$data['clearlog'] = 1;
		}
		
		$resp = $this->eapi_call($act, $data);		
		
		return $resp;
		
	}
	
	/**
	 * install le certificate
	 *
	 * @category	 SSL
	 * @param		 $domain domain on which you want to install certificate
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function install_le_cert($domain){
		
		$act = 'act=lets_encrypt';
		
		$data['domain'] = $domain;
		$data['install_cert'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * revoke LE certificate
	 *
	 * @category	 SSL
	 * @param		 $domain domain on which you want to revoke certificate
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function revoke_le_cert($domain){
		
		$act = 'act=lets_encrypt';
		
		$data['domain'] = $domain;
		$data['revoke_cert'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * renew LE certificate
	 *
	 * @category	 SSL
	 * @param		 $domain domain on which you want to renew certificate
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function renew_le_cert($domain){
		
		$act = 'act=lets_encrypt';
		
		$data['domain'] = $domain;
		$data['renew_cert'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	//////////////////////////////////////////////////////////////////////////////
	//					   		CATEGORY : Enduser Email						//
	//////////////////////////////////////////////////////////////////////////////	
		
	/**
	 * List Email Users
	 *
	 * @category	 Email
	 * @param        string $email_or_domain Specify Email or domain ( It search with email account )
	 * @param		 string $reslen pass how much record you want to display per page (pass 'all' if you want to diplay all results)
	 * @param		 string $page pass page number
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_emailuser($email_or_domain = '', $reslen = 50, $page = 0){
		
		$act = 'act=email_account';
		
		if(!empty($email_or_domain)){
			// Specify Email ( It search with email account )
			$data['email'] = $email_or_domain;
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
	
	/**
	 * Add Email User
	 *
	 * @category	 Email
	 * @param        string $domain Domain for the Email User Account to add
	 * @param        string $emailuser Specify email user to create
	 * @param        string $password Specfy PASSWORD
	 * @param        string $quota_limit [OPTIONAL] Specify Quota limit of email in MB
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function add_emailuser($domain, $emailuser, $password, $quota_limit = ''){
		
		$act = 'act=add_email_account';
		
		$data['domain'] = $domain;
		$data['login'] = $emailuser;
		$data['newpass'] = $data['conf'] = $password;
		$data['add'] = 1;
		
		if(!empty($quota_limit)){
			$data['quota_limit'] = $quota_limit;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Change Email Users' Password
	 *
	 * @category	 Email
	 * @param        string $domain Domain for the Email User Account for change passsword
	 * @param        string $emailuser Email user name for change passsword
	 * @param        string $password New password for user 
	 * @param        string $quota_limit [OPTIONAL] Specify Quota limit of email
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function change_email_user_pass($domain, $emailuser, $password, $quota_limit = ''){
		
		$act = 'act=add_email_account';
		
		$data['domain'] = $domain;
		$data['email'] = $emailuser;
		$data['newpass'] = $password;
		$data['conf'] = $password;
		$data['add'] = 1;
		
		if(!empty($quota_limit)){
			$data['quota_limit'] = $quota_limit;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete Email Users
	 *
	 * @category	 Email
	 * @param        string $domain Domain for the Email User Account for delete
	 * @param        string $emailuser Email user name for delete
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_email_user($domain, $emailuser){
		
		$act = 'act=email_account';
		
		$data['domain'] = $domain;
		$data['delete'] = $emailuser;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List Email Forwarder
	 *
	 * @category	 Email
	 * @param        string $domain [OPTIONAL] Domain for the Email Forwarder list	
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_emailforward($domain = ''){
		
		$act = 'act=email_forward';
		
		if(!empty($domain)){
			$data['domain'] = $domain;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Add Email Forwarder
	 *
	 * @category	 Email
	 * @param        string $domain Domain for the Email Forwarder add | Specify DOMAIN
	 * @param        string $forward_address email user to Forward | Specify Senders Email Address
	 * @param        string $forward_to Forwarded to address | Specify Email Address to be Forwarded TO
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function add_emailforward($domain, $forward_address, $forward_to){
		
		$act = 'act=email_forward';
		
		$data['domain'] = $domain;
		$data['euser'] = $forward_address;
		$data['femail'] = $forward_to;
		$data['add'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete Email Forwarder
	 *
	 * @category	 Email
	 * @param        string $domain Domain for the Email Forwarder delete | Specify DOMAIN
	 * @param        string $forward_address Forwarder name | Specify Forwarders Name
	 * @param        string $forward_to To whome it is forwarded | Specify Recepients Name
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_email_forward($domain, $forward_address, $forward_to){
		
		$act = 'act=email_forward';
		
		$data['domain'] = $domain;
		$data['euser'] = $forward_address;
		$data['delete'] = $forward_to;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List MX record
	 *
	 * @category	 Email Server
	 * @param        string $domain[OPTIONAL] Domain for the MX Record list (BY default it will use primary domain)
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_mx_entry($domain = ''){
		
		$act = 'act=mxentry';
		
		$data = [];
		if(!empty($domain)){
			$data['domain'] = $domain;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Add MX record
	 *
	 * @category	 Email Server
	 * @param        string $priority Priority for the MX Record Entry
	 * @param        string $destination Destination address	 
	 * @param        string $domain[OPTIONAL] Domain for the MX Record add (BY default it will use primary domain of the webuzo user)
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function add_mx_entry($priority, $destination, $domain = ''){
		
		$act = 'act=mxentry';
		
		if(!empty($domain)){
			$data['domain'] = $domain;
		}
		
		$data['priority'] = $priority;
		$data['destination'] = $destination;
		$data['add'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Edit MX record
	 *
	 * @category	 Email Server
	 * @param        string $domain Domain for the MX Record edit
	 * @param        string $record Record no of the Entry	 
	 * @param        string $priority Priority for the MX Record Entry
	 * @param        string $destination Destination address	 
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function edit_mx_entry($domain, $record, $priority, $destination){
		
		$act = 'act=mxentry';
		
		$data['domain'] = $domain;
		$data['edit'] = $record;
		$data['priority'] = $priority;
		$data['destination'] = $destination;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete MX record
	 *
	 * @category	 Email Server
	 * @param        string $domain Domain for the MX Record delete
	 * @param        string $record Record no of the Entry	 
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_mx_entry($domain, $record){
		
		$act = 'act=mxentry';
		
		$data['domain'] = $domain;
		$data['delete'] = $record;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List Email Autoresponder
	 *
	 * @category	 Email
	 * @param        string $domain[OPTIONAL] Domain email autoresponder | Specify the DOMAIN ( BY DEFAUL IT WILL TAKE PRIMARY DOMAIN OF USER
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_emailautoresponder($domain = ''){
		
		$act = 'act=add_email_autoresponder';
		
		if(!empty($domain)){
			$data['domain'] = $domain;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Add Email Autoresponder
	 *
	 * @category	 Email
	 * @param        string $euser Email user for autoresponder | Specify the EMAILUSER
	 * @param        string $mail_subject Email subject for autoresponder | Specify the Autoresponder subject
	 * @param        string $mail_body Email body for autoresponder | Specify the Autoresponder Body
	 * @param        string $domain[OPTIONAL] Domain email autoresponder | Specify the DOMAIN ( BY DEFAUL IT WILL TAKE PRIMARY DOMAIN OF USER
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function add_emailautoresponder($euser, $mail_subject, $mail_body, $domain = ''){
		
		$act = 'act=add_email_autoresponder';
		
		$data['euser'] = $euser;
		$data['mail_subject'] = $mail_subject;
		$data['mail_body'] = $mail_body;
		$data['add'] = 1;
		
		if(!empty($domain)){
			$data['domain'] = $domain;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete Email Autoresponder
	 *
	 * @category	 Email
	 * @param        string $euser Email user for autoresponder | Specify the EMAILUSER
	 * @param        string $domain[OPTIONAL] Domain email autoresponder | Specify the DOMAIN ( BY DEFAUL IT WILL TAKE PRIMARY DOMAIN OF USER
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_emailautoresponder($euser, $domain = ''){
		
		$act = 'act=add_email_autoresponder';
		
		$data['delete'] = $euser;
		
		if(!empty($domain)){
			$data['domain'] = $domain;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Edit Email Autoresponder
	 *
	 * @category	 Email
	 * @param        string $domain Domain for add autoresponder | Specify the DOMAIN
	 * @param        string $euser Email user for autoresponder | Specify the EMAILUSER
	 * @param        string $mail_subject Email subject for autoresponder | Specify the Autoresponder subject
	 * @param        string $mail_body Email body for autoresponder | Specify the Autoresponder Body
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function edit_emailautoresponder($domain, $euser, $mail_subject, $mail_body){
		
		$act = 'act=edit_email_autoresponder';
		
		$data['domain'] = $domain;
		$data['user'] = $euser;
		$data['mail_subject'] = $mail_subject;
		$data['mail_body'] = $mail_body;
		$data['edit'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List Email Queue
	 *
	 * @category	 Email
	 * @param        string $euser [OPTIONAL] specify email user
	 * @param        string $domain [OPTIONAL] Specify the DOMAIN
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_email_queue($euser = '', $domain=''){
		
		$act = 'act=email_queue';
		
		$data = [];
		if(!empty($euser)){
			$data['euser'] = $euser;
		}
		
		if(!empty($domain)){
			$data['domain'] = $domain;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Spam Assassin
	 *
	 * @category	 Email
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_spam_assassin(){
		$act = 'act=spam_assassin';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Add Spam Assassin
	 *
	 * @category	 Email
	 * @param        string $email_ids specify email ids e.g. user1@domain.com, user2@domain.com
	 * @param        string $type specify spam assassin type  ['blacklist', 'whitelist']
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	 
	function add_spam_assassin($email_ids, $type){
		$act = 'act=spam_assassin';

		$data['email_id'] = $email_ids;
		$data['type'] = $type;
		$data['add_email'] = 1;

		$resp = $this->eapi_call($act, $data);

		return $resp;
	}
	
	/**
	 * Delete Spam Assassin
	 *
	 * @category	 Email
	 * @param        string $email_ids specify email ids (you can also provide multiple email ids in array)
	 * @param        string $type specify spam assassin type  ['blacklist', 'whitelist']
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	 
	function delete_spam_assassin($email_ids, $type){
		$act = 'act=spam_assassin';

		$data['delete'] = $email_ids;
		$data['type'] = $type;

		$resp = $this->eapi_call($act, $data);

		return $resp;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//							CATEGORY : Configuration						 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Set php version of domain
	 *
	 * @category	 Configuration
	 * @param	 	 $domain Domain ( you have to give array of domain and then php version) e.g $data[$domain] = $php_version
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function set_multi_php($domain){
		$act = 'act=multi_php';

		$data = [];
		foreach($domain as $k => $v){
			$data[$k] = $k;
			$data['php_version_'.$k] = $v;
		}
		
		$data['submitphp'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Get PHP ini (main as well as user ini file)
	 *
	 * @category	 Configuration
	 * @author		 Vasu
	 * @param	 	 $domain Domain got their local as well as original PHP ini( If you want to get PHP INI  of user just pass "home")
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function get_php_ini($domain = 'home'){
		
		$act = 'act=multiphp_ini_editor';
		
		$data['loc_change'] = 1;
		$data['domain'] = $domain;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Save PHP ini (main as well as user ini file)
	 *
	 * @category	 Configuration
	 * @author		 Vasu
	 * @param	 	 string $domain Domain save their local as well as original PHP ini( If you want to save PHP INI  of user just pass "home")
	 * @param	 	 string $ini_content pass ini string.
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function save_php_ini($domain, $ini_content){
		
		$act = 'act=multiphp_ini_editor';
		
		$data['domain'] = $domain;
		$data['e_cont'] = $ini_content;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Get installed pear list of user as well as admin (system installed pear)
	 * It use Default php and pear version for getting system installed pear LIST
	 *
	 * @category	 Configuration
	 * @author		 Vasu
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function get_installed_PEAR(){
		
		$act = 'act=php_pear';
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
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
	function get_PEAR_list($type = 'all', $q = '', $reslen = 50, $page=0){
		$act = 'act=pear_module';
		
		$data = [];
		$data['type'] = $type;
		
		if(!empty($q)){
			$data['q'] = $q;
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
	
	/**
	 * Install/update/unistall/reinstall PEAR package
	 *
	 * @category	 Configuration
	 * @author		 Vasu
	 * @param		 string $mod_name pass PEAR module name (you can also pass particular module version which you want to install i.e. "Auth-1.0.2")(You can also pass PEAR package prefer stability e.g "Auth-stable" available PEAR stability option ["stable", "devel", "alpha", "beta"])
	 * @param		 string $mod_action pass action you want to perform on PEAR available actions are ['install', 'reinstall', 'uninstall', 'upgrade'];
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function action_PEAR($mod_name, $mod_action = 'install'){
		$act = 'act=php_pear';
		
		$data = [];
		$data['mod_name'] = $mod_name;
		$data['action'] = $mod_action;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//						 CATEGORY: SECURITY						 			 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Change ROOT/Endusers's Password (It will change filemanager password too)
	 *
	 * @category	 Security
	 * @param        string $pass The NEW password for the USER
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function change_password($pass){
		
		// The act
		$act = 'act=changepassword';
		
		$data['newpass'] = $data['conf'] = $pass;
		$data['changepass'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Change Apache Tomcat Manager's Password
	 *
	 * @category	 Security
	 * @param        string $pass The NEW password for the Apache Tomcat
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function change_tomcat_pwd($pass){
		
		// The act
		$act = 'act=tomcat_changepassword';
		$data['tomcatpass'] = $data['conf'] = $pass;
		$data['changetomcatpass'] = 1;
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Blocked IP.
	 * @return		string $resp Response of Actions. Default: Serialize
	 */
	function list_ipblock(){
		
		$act = 'act=ipblock';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/**
	 * Block IP
	 *
	 * @category	 Security
	 * @param        string $ip IP Address for block (NOTE: You can specify the IP Address in the following format
					Single IP or Domain : 192.168.0.1 or example.com
					IP Range : 192.168.0.1 - 192.168.0.50
					CIDR Format : 192.168.0.1/20)
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function add_ipblock($ip){
		
		$act = 'act=ipblock';
		
		$data['dip'] = $ip;		// Specify IP to block
		$data['add_ip'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete Blocked IP
	 *
	 * @category	 Security
	 * @param        string $ip IP Address for unblock
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_ipblock($ip){
		
		$act = 'act=ipblock';
		
		$data['ip'] = $ip;	// Specify IP to unblock
		$data['delete'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List SSH access
	 *
	 * @category	 Security
	 * @return       string $resp Response of Actions.
	 */
	function list_ssh_access(){
		$act = 'act=ssh_access';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
	}
	
	/**
	 * SSH key auth
	 *
	 * @category	 Security
	 * @param		 $ssh_keyname keyname of ssh
	 * @param		 $ssh_auth authentication string i.e. ["Deauthorize", "Authorize"]
	 * @return       string $resp Response of Actions.
	 */
	function ssh_key_auth($ssh_keyname, $ssh_auth){
		$act = 'act=ssh_access';
		
		$data['ssh_keyname'] = $ssh_keyname;
		$data['ssh_auth'] = $ssh_auth;
		$data['ssh_key_auth'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * SSH key auth
	 *
	 * @category	 Security
	 * @param		 $delete_ssh_key keyname of ssh to delete
	 * @return       string $resp Response of Actions.
	 */
	function delete_ssh_key($delete_ssh_key){
		$act = 'act=ssh_access';
		
		$data['delete_ssh_key'] = $delete_ssh_key;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * VIEW SSH key auth
	 *
	 * @category	 Security
	 * @param		 $keyname keyname of ssh to view ssh key
	 * @return       string $resp Response of Actions.
	 */
	function view_ssh_key($keyname){
		$act = 'act=ssh_access';
		
		$data['keyname'] = $keyname;
		$data['view_key'] = 1;
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * DELETE SSH key auth
	 *
	 * @category	 Security
	 * @param		 $keyname keyname of ssh to view ssh key
	 * @return       string $resp Response of Actions.
	 */
	function download_ssh_key($keyname){
		
		$act = 'act=ssh_access&download='.$keyname;
		
		$resp = $this->eapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Convert key to PPK
	 *
	 * @category	 Security
	 * @param		 $key_name keyname of ssh to generate ppk
	 * @param		 $passphrase password phrase
	 * @return       string $resp Response of Actions.
	 */
	function generate_ssh_ppk($key_name, $passphrase){
		$act = 'act=ssh_access';

		$data['key_name'] = $key_name;
		$data['passphrase'] = $passphrase;
		$data['ppk_gen'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Generate SSH Key
	 *
	 * @category	 Security
	 * @param		 $keyname keyname of ssh
	 * @param		 $keypass password for ssh key
	 * @param		 $keytype type of key for ssh | options ["DSA", "RSA"]
	 * @param		 $keysize size of key for ssh | options ["1024", "2048", "4096"]
	 * @return       string $resp Response of Actions.
	 */
	function ssh_generate_keys($keyname, $keypass, $keytype = "RSA", $keysize = "2048"){
		$act = 'act=ssh_generate_keys';

		$data['keyname'] = $keyname;
		$data['keypass'] = $data['repass'] = $keypass;
		$data['keytype'] = $keytype;
		$data['keysize'] = $keysize;
		$data['generatekey'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * IMPORT SSH Key
	 *
	 * @category	 Security
	 * @param		 $keyname keyname of ssh
	 * @param		 $private_key paste private key
	 * @param		 $passphrase Passphrase
	 * @param		 $public_key Paste the public key
	 * @return       string $resp Response of Actions.
	 */
	function ssh_import_keys($keyname, $private_key, $passphrase, $public_key){
		
		$act = 'act=ssh_import_keys';
		
		$data['importkey'] = 1;
		$data['keyname'] = $keyname;
		$data['private_key'] = $private_key;
		$data['passphrase'] = $passphrase;
		$data['public_key'] = $public_key;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Protected Users
	 *
	 * @category	 SECURITY
	 * @return		 Array	$resp	Response of Actions. Default: Serialize
	 */
	function list_pass_protect_dir(){
		
		$act = 'act=pass_protect_dir';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/*
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
	function add_pass_protect_dir($path, $uname, $pass, $name = ''){
		
		$act = 'act=pass_protect_dir';
		
		$data['dir_path'] = $path;	// Path to password protect (No leading slashes)
		$data['username'] = $uname;	// Alphanumeric Username
		$data['password'] = $data['re_password'] = $pass;	// Password should not be less than 5 characters
		
		if(!empty($name)){ // Alias name
			$data['dir_name'] = $name;
		}
		$data['add_pass_protect'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/*
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
	function delete_pass_protect_dir($uname, $path){
		
		$act = 'act=pass_protect_dir';
		
		$data['user'] = $uname;
		$data['path'] = $path;
		$data['delete'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List Hotlink Protection values
	 * @return		string $resp Response of Actions. Default: Serialize
	 */
	function list_hotlink_protect(){
		
		$act = 'act=hotlink_protect';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/**
	 * Enable HOTLINK Protection
	 *
	 * @category	 Security
	 * @param        string $urllist list the url in array i.e. [ $url1, $url2 ];
	 * @param        string $extensionlist list the extention that you want to allow in comma seperated value i.e. ( ext1, ext2 )
	 * @param        string $directaccess if you want directaccess for your resource i.e. true or false;
	 * @param        string $redirecturl url that you want to redirect e.g. http://www.webuzo.com
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function enable_hotlink_protect($urllist = '', $extensionlist = '', $directaccess = false, $redirecturl = ''){
		
		$act = 'act=hotlink_protect';
		
		$data = [];
		if(!empty($urllist)){
			$data['urllist'] = $urllist;
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
	
	/**
	 * Disable HOTLINK Protection
	 *		
	 *	@return		 string $resp Response of Actions. Default: Serialize
	 */
	function disable_hotlink_protect(){
		$act = 'act=hotlink_protect';
		
		$data['hotlink_disable'] = 1;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Mod Security values
	 * @return		string $resp Response of Actions. Default: Serialize
	 */
	function list_mod_security(){
		
		$act = 'act=mod_security';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/**
	 * Change mode SECURITY
	 *
	 * @param		string $mod_security Give mod security value the option [1/0- for single domain enable/disable it require domain, 2 - For enabling mod security on all domain, 3 - For disabling mod security on all domain
	 * @param		string $domain[OPTIONAL] it only require for mod value 1/0 i.e. for enable/disable single domaid
	 * @return		string $resp Response of Actions. Default: Serialize
	 */
	function change_mod_security($mod_security = 3, $domain = ''){
		
		$act = 'act=mod_security';
		
		$data['mod_security'] = $mod_security;
		$data['mod'] = 1;
		
		if(!empty($domain)){
			$data['domain'] = $domain;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	///////////////////////////////////////////////////////////////////////////////////
	//						 		CATEGORY: Server Utilities						 //
	///////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * list backup
	 *
	 * @category	 ServerUtilities
	 * @param	 	 $clearlog [OPTIONAL] clear backup log of the user
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_backup($clearlog = ''){
		
		$act = 'act=webuzo_backup';
		
		$data = [];
		
		if(!empty($clearlog)){
			$data['clearlog'] = 1;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
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
	function add_backup_server($name, $type, $username, $hostname, $password, $port, $backup_location, $server_id = '', $conn_check = ''){
		$act = 'act=add_backup_server';
		
		$data['type'] = $type;
		$data['username'] = $username;
		$data['hostname'] = $hostname;
		$data['password'] = $password;
		$data['port'] = $port;
		$data['backup_location'] = $backup_location;
		
		if(!empty($server_id)){
			$data['server_id'] = $server_id;
			$act = $act.'&edit_backup_server='.$server_id;
		}
		
		if(!empty($conn_check)){
			$data['conn_check'] = $conn_check;
		}else{
			$data['name'] = $name;
		}
		
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Remove Backup server
	 *
	 * @category	 ServerUtilities
	 * @param	 	 @param $server_ids You can send multiple server for multiple delete i.e ["1", "2"] => for multiple delete | "1" => for single delete
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function remove_backup_server($server_ids){
		
		$act = 'act=webuzo_backup';
		
		$data = [];
		if(is_array($server_ids)){
			$data['server_ids'] = $server_ids;
			$data['remove_multiple_backup_server'] = 1;
		}else{
			$data['server_ids'] = [$server_ids];
			$data['remove_backup_server'] = 1;
		}
		
		$data['server_ids'] = json_encode($data['server_ids']);
		
		// print_r($data);exit;
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Login Logs
	 *
	 * @category	ServerUtilities
	 * @param	 	delete_all [OPTIONAL] if you pass it will  Delete all Login Logs
	 * @return		string $resp Response of Actions. Default: Serialize
	 */
	function list_login_logs($delete_all = ''){
		
		$act = 'act=login_logs';
		
		if(!empty($delete_all)){
			$act = $act.'&delete_all=1';
		}
		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/**
	 * List CRON
	 *
	 * @category	 ServerUtilities
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_cron(){
		
		$act = 'act=cronjob';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
		
	}
	
	/**
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
	function add_cron($minute = '*', $hour = '*', $day = '*', $month = '*', $weekday = '*', $cmd, $edit_cronjob = ''){
		
		$act = 'act=cronjob';
		
		$data['minute'] = $minute;
		$data['hour'] = $hour;
		$data['day'] = $day;
		$data['month'] = $month;
		$data['weekday'] = $weekday;
		$data['cmd'] = $cmd;
		
		if(!empty($edit_cronjob)){
			$data['edit_record'] = $edit_cronjob;
		}else{
			$data['create_record'] = 1;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete CRON
	 *
	 * @category	 ServerUtilities
	 * @param        string $id ID of the cron record. Get from the list of cron
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_cron($id){
		
		$act = 'act=cronjob';
		
		$data['delete_record'] = $id;
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * import From Cpanel
	 *
	 * @category	 ServerUtilities
	 * @param		 $r_host cPanel Server Address (Required)
	 * @param		 $r_user cPanel User Name (Required)
	 * @param		 $r_host cPanel User Name Password(Required)
	 * @param		 $r_backup [OPTIONAL] If specified, the process will import data from this file. The file should be stored locally inside /home/webuzo-username directory
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function import_cpanel($r_host, $r_user, $r_pass, $r_backup = ''){
		
		$act = 'act=import_cpanel';
		
		$data['create_acc'] = 1;
		$data['r_domain'] = $r_host;
		$data['r_user'] = $r_user;
		$data['r_pass'] = $r_pass;
		
		if(!empty($r_backup)){
			$data['r_backup'] = $r_backup;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Get / Delete import from cpanel log
	 *
	 * @category	 ServerUtilities
	 * @param        string $clearlog [OPTIONAL] clear log import log 
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function import_cpanel_logs($clearlog = ''){
		$act = 'act=import_cpanel';
		
		$data = [];
		
		if(!empty($clearlog)){
			$data['clearlog'] = 1;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Show Error Log
	 *
	 * @category	 ServerUtilities
	 * @param        string $domain Domain for the error log (Opional) (BY default it will take primary domain of user)
	 * @param        boolean [OPTIONAL]$clearlog pass 1 if you want to clear domain log
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function show_error_log($domain = '', $clearlog = ''){
		
		$act = 'act=errorlog';
		
		$data = [];
		if(!empty($domain)){
			$data['domain_log'] = $domain .'.err';
		}
		
		if(!empty($clearlog)){
			$data['clearlog'] = 1;
		}
		
		$resp = $this->eapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
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
	function show_bandwidth($year = '', $month = '', $type = '', $day = '', $domain = ''){
		
		$act = 'act=bandwidth';
		
		$data = [];
		
		if(!empty($year)){
			$data['year'] = $year;
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
	
	/**
	 * Show Disk Usage
	 *
	 * @category	 ServerUtilities
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_disk_usage(){
		
		$act = 'act=disk_usage';
		
		$resp = $this->eapi_call($act);
		
		return $resp;
	}
	
	///////////////////////////////////////////////////////////////////////////////////
	//						 		CATEGORY: Advance Settings						 //
	///////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Set
	 *
	 * @category	 Settings
	 * @parm	 	 $email update user email
	 * @parm	 	 $language update user language
	 * @parm	 	 $arrange_domain check if you want to list domain in accedding order (alphabetically)
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function user_settings($email, $language = 'english', $arrange_domain = ''){
		
		$act = 'act=settings';
		
		$data['editsettings'] = 1;
		$data['email'] = $email;
		$data['language'] = $language;
		
		if(!empty($arrange_domain)){
			$data['arrange_domain'] = $arrange_domain;
		}
		
		$resp = $this->eapi_call($act);
		
		return $resp;
	}
}

/**
 * Webuzo ADMIN SDK
 *
 * @category	 SDK  
 * @param        string $user The ADMIN username to LOGIN
 * @param        string $pass The password
 * @param        string $host The host to perform actions
 * @param        string $sess_cookie The sess_cookie of the user
 */
class Webuzo_Admin_SDK{

	// The Login URL
	var $login = '';
	
	var $debug = 0;
	
	var $error = array();

	// THE POST DATA
	var $data = array();
	
	var $apps = array(); // List of Apps
	
	var $apps_catwise = array(); // List of Apps with cat
	
	var $installed_apps = array(); // List of Installed Apps
	
	/**
	 * Initalize API login
	 *
	 * @category	 Login  
	 * @param        string $user The root username to LOGIN
	 * @param        string $pass The root password
	 * @param        string $host The host to perform actions
	 * @param        string $sess_cookie
	 * @return       void
	 */
	function __construct($user = 'root', $pass = '', $host = '', $method = ''){
		
		$this->curl_log = fopen('php://temp', 'wb');
		
		// API Method
		if(!empty($method) && $method == 'api'){
			
			$this->login = 'https://'.$host.':2005/index.php?apiuser='.$user.'&apikey='.$pass.'&';
		
		// Backward Compat : Session Cookies
		}elseif(!empty($method)){
			
			$this->sess_cookie = $method;
			$url_token = 'sess'.substr(current($method), 0, 16);
			$this->login = 'https://'.$host.':2005/'.$url_token.'/index.php';
			
		}else{
		
			$this->login = 'https://'.rawurlencode($user).':'.rawurlencode($pass).'@'.$host.':2005/index.php';
		
		}
	}
	
	/**
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
	
		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_STDERR, $this->curl_log);

		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		// Follow redirects
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		
		if(!empty($this->curl_timeout)){
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
			$this->curl_timeout = 0;
		}
		
		if(!empty($post)){ 
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		}
		
		// Session cookie
		if(!empty($this->sess_cookie)){
			$cookies = array_merge($cookies, $this->sess_cookie);
		}
		
		// Is there a Cookie
		if(!empty($cookies)){
			curl_setopt($ch, CURLOPT_COOKIESESSION, true);
			curl_setopt($ch, CURLOPT_COOKIE, http_build_query($cookies, null, ';'));
		}
		
		// We ONLY need this for directadmin to get the session cookie else we need the Header DISABLED
		if(!empty($header)){
			curl_setopt($ch, CURLOPT_HEADER, 1);
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// Get response from the server.
		$resp = curl_exec($ch);
		
		// Did we get the file ?
		if($resp === false){
			$this->error[] = 'cURL Error : '.curl_error($ch);
		}
		
		curl_close($ch);
		
		return $resp;
	}
	
	/**
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
		
		// Add the ?
		if(!strstr($url, '?')){
			$url = $url.'?';
		}
		
		// Login Page with Softaculous Parameters
		$url = $url.$act;
		
		// Set the API mode
		if(!strstr($url, 'api=')){
			$url = $url.'&api='.$this->format;
		}
		
		$resp = $this->curl($url, $post);
		
		return $resp;
		
	}
	
	/**
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
		
		// Add the ?
		if(!strstr($url, '?')){
			$url = $url.'?';
		}
		
		// Login Page with Softaculous Parameters
		$url = $url.$act;
		
		// Set the API mode
		if(!strstr($url, 'api=')){
			$url = $url.'&api=serialize';
		}
		
		$resp = $this->curl($url, $post);
		
		//echo $resp;
		
		if(!empty($resp)){
			$resp = unserialize(trim($resp));
		}
		
		return $resp;
		
	}
	
	/**
	 * Check login error
	 *
	 * @category	 error
	 * @return       array
	 */
	function chk_error(){
		if(!empty($this->error)){
			return $this->r_print($this->error[0]);
		}		
	}
	
	/**
	 * Prints result
	 *
	 * @category	 Debug
	 * @param        Array $data
	 * @return       array
	 */
	function r_print($data){
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN USER MANAGEMENT						 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
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
	
	
	
	/**
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
	function create_user($user, $domain, $user_passwd, $email, $plan = '', $pData = []){
		$act = 'act=add_user';
		
		$data['create_user'] = 1;
		$data['user'] = $user;
		$data['domain'] = $domain;
		$data['user_passwd'] = $data['cnf_user_passwd'] = $user_passwd;
		$data['email'] = $email;
		$data['plan'] = $plan;
		
		if(!empty($pData)){
			$data = array_merge($data, $pData);
		}
		
		if(empty($pData) && empty($plan)){
			$data['prefill_default'] = 1;
		}elseif(!empty($plan)){
			$data['plan_default_reseller'] = 1;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
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
	function edit_user($user, $domain, $user_passwd, $email, $plan = '', $pData = []){
		$act = 'act=add_user';
		
		$data['edit_user'] = 1;
		$data['user_name'] = $user;
		$data['user'] = $user;
		$data['domain'] = $domain;
		$data['user_passwd'] = $data['cnf_user_passwd'] = $user_passwd;
		$data['email'] = $email;
		$data['plan'] = $plan;
		
		if(!empty($pData)){
			$data = array_merge($data, $pData);
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List users
	 *
	 * @category	 USER MANAGEMENT
	 * @param		 string $searchUser Search User
	 * @param		 string $reslen pass how much record you want to display per page (pass 'all' if you want to diplay all results)
	 * @param		 string $page pass page number
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_users($searchUser = '', $reslen = 50, $page = 0){
		
		$act = 'act=users';
		
		$data = [];
		if(!empty($searchUser)){
			$data['user'] = $searchUser;
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
	
	/**
	 * Delete User
	 *
	 * @category	 USER MANAGEMENT
	 * @param		 string $userName User name which you want to delete
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_user($userName, $del_sub_acc = 1, $skip_reseller = ''){
		
		$act = 'act=users';
		
		$data['delete_user'] = $userName;
		$data['del_sub_acc'] = $del_sub_acc;
		$data['skip_reseller'] = $skip_reseller;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Suspend User
	 *
	 * @category	 USER MANAGEMENT
	 * @param		 string $userName User name which you want to suspend
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function suspend_user($userName, $skip = ''){
		
		$act = 'act=users';
		
		$data['suspend'] = $userName;
		if(!empty($skip)){
			$data['skip'] = $skip;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * UnSuspend User
	 *
	 * @category	 USER MANAGEMENT
	 * @param		 string $userName User name which you want to suspend
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function unsuspend_user($userName, $skip = ''){
		
		$act = 'act=users';
		
		$data['unsuspend'] = $userName;
		if(!empty($skip)){
			$data['skip'] = $skip;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Reset bandwidth limit of User as per plan assigned.
	 *
	 * @category	 USER MANAGEMENT
	 * @param		 array $userName Users names which you want to reset
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function reset_bandwidth_user($userName){
		
		$act = 'act=reset_bandwidth';
		
		$data['reset'] = 1;
		$data['user'] = $userName;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List domains of all users
	 *
	 * @category	 USER MANAGEMENT
	 * @param		 string $user_search Search domain under user
	 * @param		 string $domain Domain Search param
	 * @param		 string $reslen pass how much record you want to display per page *(pass 'all' if you want to diplay all results)
	 * @param		 string $page pass page number
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_domains($user_search = '', $domain = '',  $reslen = 'all', $page = 0){
		
		$act = 'act=domains';
		
		$data = [];
		if(!empty($domain)){
			$data['dom_search'] = $domain;
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
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN PLANS									 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Add Plan
	 *
	 * @category	 PLANS
	 * @param		 string $plan_name Pass plan name
	 * @param		 array $pData [OPTIONAL] User plan data (see above plan data);
	 * @return		 array $resp Response of Actions. Default: Serialize
	 */
	function create_plan($plan_name, $pData = []){
		
		$act = 'act=add_plans';
		
		$data['create_plan'] = 1;
		$data['plan_name'] = $plan_name;
		
		if(!empty($pData)){
			$data = array_merge($data, $pData);
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Edit Plan
	 *
	 * @category	 PLANS
	 * @param		 string $plan_name Pass plan name
	 * @param		 string $plan_slug Pass plan name slug
	 * @param		 array $pData [OPTIONAL] User plan data (see above plan data);
	 * @return		 array $resp Response of Actions. Default: Serialize
	 */
	function edit_plan($plan_name, $plan_slug, $pData = []){
		
		$act = 'act=add_plans';
		
		$data['create_plan'] = 1;
		$data['plan'] = $plan_slug;
		$data['plan_name'] = $plan_slug;
		
		if(!empty($pData)){
			$data = array_merge($data, $pData);
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List All Plans
	 *
	 * @category	 PLANS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_plans(){
		$act = 'act=plans';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Delete Plan
	 *
	 * @category		PLANS
	 * @param			string $plan_slug Give plan name that you want to delete		
	 * @return		 	string $resp Response of Actions. Default: Serialize
	 */
	function delete_plan($plan_slug){
		$act = 'act=plans';
		
		$data['delete_plan'] = $plan_slug;
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Add Feature Sets
	 *
	 * @category	 PLANS
	 * @param		 string $features_name Pass Features Set Name
	 * @param		 array $pData [OPTIONAL] User plan data (See above plan data [feture section] );
	 * @return		 array $resp Response of Actions. Default: Serialize
	 */
	function create_feature_sets($features_name, $pData = []){
		
		$act = 'act=add_feature_sets';
		
		$data['create_features'] = 1;
		$data['features_name'] = $features_name;
		
		if(!empty($pData)){
			$data = array_merge($data, $pData);
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Edit Features Sets
	 *
	 * @category	 PLANS
	 * @param		 string $feature_name Pass Features Set Name
	 * @param		 string $features_slug Pass Features Set Slug Name
	 * @param		 array $pData [OPTIONAL] User plan data (See above plan data [feture section] );
	 * @return		 array $resp Response of Actions. Default: Serialize
	 */
	function edit_feature_sets($features_name, $features_slug, $pData = []){
		
		$act = 'act=add_feature_sets';
		
		$data['create_features'] = 1;
		$data['features_name'] = $features_name;
		$data['feature'] = $features_slug;
		
		if(!empty($pData)){
			$data = array_merge($data, $pData);
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List All Features Sets
	 *
	 * @category	 PLANS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_feature_sets(){
		$act = 'act=feature_sets';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Delete Feature Set
	 *
	 * @category	PLANS
	 * @param		string $feature_set_slug Need to pass slug feature which you want to delete ( slug name of the feature sets )
	 * @return		string $resp Response of Actions. Default: Serialize
	 */
	function delete_feature_sets($feature_set_slug){
		$act = 'act=feature_sets';
		
		$data['delete_feature'] = $feature_set_slug;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Disable Feature Set
	 *
	 * @category	PLANS
	 * @param		array $DFdata [OPTIONAL] pass feature list which you want to disable e.g ['domainmanage', 'ftp',....,etc.], (See above plan data [feture section] for more options);
	 * @return		string $resp Response of Actions. Default: Serialize
	 */
	function disable_feature($DFdata = []){
		$act = 'act=features';
		
		$data['save'] = 1;
		
		foreach($DFdata as $val){
			$data['disable_'.$val] = 1;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN RESELLER								 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
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
	
	/**
	 * Reseller Privileges
	 *
	 * @category	 RESELLER
	 * @param		 array $rData [OPTIONAL] Reseller plan (reference above reseller field);
	 * @return		 array $resp Response of Actions. Default: Serialize
	 */
	function reseller_privileges($rData = []){
		
		$act = 'act=reseller_privileges';
		
		$data['create_plan'] = 1;
		
		if(!empty($rData)){
			$data = array_merge($data, $rData);
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Email All Reseller
	 *
	 * @category	RESELLER
	 * @param		string $from_email Email from which you want to send email.
	 * @return		string $resp Response of Actions. Default: Serialize
	 */
	function email_all_reseller($from_email, $subject, $message_body){
		$act = 'act=email_reseller';
		
		$data['savechanges'] = 1;
		$data['from_email'] = $from_email;
		$data['subject'] = $subject;
		$data['message_body'] = $message_body;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN SETTINGS								 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
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
	function set_panel_config($primary_ip, $primary_domain, $primary_ip_v6 = '', $ns1 = 'ns1.example.com', $ns2 = 'ns2.example.com', $quota =false){
		
		$act = 'act=webuzoconfigs';
		
		$data['editconfigs'] = 1;
		
		$data['WU_PRIMARY_IP'] = $primary_ip;
		$data['WU_PRIMARY_DOMAIN'] = $primary_domain;
		
		if(!empty($primary_ip_v6)){
			$data['WU_PRIMARY_IPV6'] = $primary_ip;
		}
		
		$data['WU_NS1'] = $ns1;
		$data['WU_NS2'] = $ns2;
		
		if(!empty($quota)){
			$data['quota'] = $quota;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Admin settings
	 *
	 * @category	 SETTINGS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_settings(){
		
		$act = 'act=settings';
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Admin Backup Settings
	 *
	 * @category	 SETTINGS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_backup_settings(){
		
		$act = 'act=backup_settings';
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Admin Rebranding Settings
	 *
	 * @category	 SETTINGS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_rebranding_settings(){
		
		$act = 'act=rebranding_settings';
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Set Admin Rebranding Settings
	 *
	 * @category		SETTINGS
	 * @param			string $sn Set site name (The name of the server or company using Webuzo. It will appear in many pages of the Webuzo installer)		
	 * @param			string $logo_url Set Logo URL (If empty the Webuzo logo will be shown.)		
	 * @param			string $favicon_logo Set Favicon logo URL (If empty the Webuzo favicon will be shown in Enduser Panel)		
	 * @return		 	string $resp Response of Actions. Default: Serialize
	 */
	function set_rebranding_settings($sn = 'Webuzo', $logo_url = '', $favicon_logo = ''){
		$act = 'act=rebranding_settings';
		
		$data['editsettings'] = 1;
		$data['sn'] = $sn;
		
		if(!empty($logo_url)){
			$data['logo_url'] = $logo_url;
		}

		if(!empty($favicon_logo)){
			$data['favicon_logo'] = $favicon_logo;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Admin Emailtemp Settings
	 *
	 * @category	 SETTINGS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_emailtemp(){
		
		$act = 'act=emailtemp';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
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
	function set_emailtemp($etitle, $econtent, $lang = '', $ishtml = '', $reset = ''){
		$act = 'act=editemailtemp';
		
		$data['savetemplate'] = 1;
		$data['tempsub'] = $etitle;
		$data['tempcontent'] = $econtent;
		
		if(!empty($lang)){
			$data['editlang'] = $lang;
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
	
	/**
	 * List Update Settings
	 *
	 * @category	 SETTINGS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_update_settings(){
		
		$act = 'act=update_settings';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
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
	function set_update_settings($update = 0, $email_update_apps = 0, $no_auto_update_system = 0, $email_update = 1, $cron_time = '14 17 * * *', $php_bin = '/usr/local/emps/bin/php' ){
		
		$act = 'act=update_settings';
		
		$data['editsettings'] = 1;
		$data['update'] = $update;
		if(!empty($email_update_apps)){
			$data['email_update_apps'] = $email_update_apps;
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
	
	/**
	 * Set root SSH pass
	 *
	 * @category	 SETTINGS
	 * @param        string $pass New password to change root(admin) password
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function set_root_pass($pass){
		
		$act = 'act=root_pass';
		
		$data['newpass'] = $data['conf'] = $pass;
		$data['changepass'] = 1;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Webuzo ACL
	 *
	 * @category	 SETTINGS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_webuzo_acl(){
		
		$act = 'act=webuzo_acl';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
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
	function set_webuzo_acl($disable_domainadd = 0, $max_addon = 0, $disable_email = 0, $disable_emailadd = 0, $disable_ssh = 0, $disable_sysapps = 0){
		
		$act = 'act=webuzo_acl';
		
		$data['savechanges'] = 1;
		$data['disable_domainadd'] =  $disable_domainadd;
		$data['disable_addon'] =  $max_addon;
		$data['disable_email'] =  $disable_email;
		$data['disable_emailadd'] =  $disable_emailadd;
		$data['disable_ssh'] =  $disable_ssh;
		$data['disable_sysapps'] =  $disable_sysapps;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Admin Email Settings
	 *
	 * @category	 SETTINGS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_email_setting(){
		
		$act = 'act=email';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
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
	function set_email_settings($mail = 1, $send_test_mail = 0, $enc_mail_pass = 0, $off_email_link = 0, $mail_authtype = 0, $mail_server ='', $mail_port ='', $mail_user = '', $mail_pass = ''){
		
		$act = 'act=email';
		
		$data['editemailsettings'] = 1;
		$data['mail'] = $mail;
		$data['mail_send'] = $send_test_mail;
		$data['enc_mail_pass'] = $enc_mail_pass;
		$data['off_email_link'] = $off_email_link;
		$data['mail_authtype'] = $mail_authtype;
		$data['mail_server'] = $mail_server;
		$data['mail_port'] = $mail_port;
		$data['mail_user'] = $mail_user;
		$data['mail_pass'] = $mail_pass;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Export Webuzo setting
	 *
	 * @category	 SETTINGS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function export_webuzo_settings(){
		
		$act = 'act=import_export&download=softaculous_settings.zip';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Import Webuzo setting
	 *
	 * @category	 SETTINGS
	 * @param		 array $file pass $_FILES array (it must be .zip extention)
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function import_webuzo_settings($file){
		
		$act = 'act=import_export';
		
		$data['import_setting'] = 1;
		$data['import_file'] = $file;
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Load Your License
	 *
	 * @category	 SETTINGS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function load_licence(){
		
		$act = 'act=licensekey';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Manage your webuzo license
	 *
	 * @category	 SETTINGS
	 * @param		 array $enter_license Enter your License Key
	 * @param		 array $email your valid Email Address
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function manage_licensekey($enter_license, $email){
		
		$act = 'act=licensekey';
		
		$data['import_setting'] = 1;
		$data['enter_license'] = $enter_license;
		$data['enter_email'] = $email;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Load Api Keys
	 *
	 * @category	 SETTINGS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_apikey(){
		
		$act = 'act=apikey';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Add Api Keys
	 *
	 * @category	 SETTINGS
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function add_apikey(){
		
		$act = 'act=apikey';
		
		$data['do'] = 'add';
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Delete Api Keys
	 *
	 * @category	 SETTINGS
	 * @parm	 	 string $api_key pass api key that you want to delete
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_apikey($api_key){
		
		$act = 'act=apikey';
		
		$data['del'] = $api_key;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN Networking								 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Add ips
	 *
	 * @category	 NETWORKING
	 * @parm	 	 array $ips add IPv4 ips e.g. [192.168.1.11, 192.168.1.12, 192.168.1.13, 192.168.1.14]
	 * @parm	 	 string $firstip [OPTIONAL] add first ip if you want ip range
	 * @parm	 	 string $lastip [OPTIONAL] add last ip if you want ip range
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function add_ipv4($ips = [], $firstip = '', $lastip = ''){
		
		$act = 'act=addips';
		
		$data['addip'] = 1;
		$data['iptype'] = 4;
		$data['ips'] = $ips;
		
		if(!empty($firstip)){
			$data['firstip'] = $firstip;
		}
		
		if(!empty($lastip)){
			$data['lastip'] = $lastip;
		}

		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Add ips IPv6
	 *
	 * @category	 NETWORKING
	 * @parm	 	 array $ips6 [OPTIONAL] add IPv6 ips e.g. [0 => [ 1 => '2001', 2 => '0db8',...,8=>'56cf'], 1 => [ 1 => '2001', 2 => '0db8',...,8=>'56cf']]
	 * @parm	 	 array $gen_ipv6 [OPTIONAL] add 6 pair of hexdec bytes e.g ['0db8', '56cf', ...,'2001']
	 * @parm	 	 string $ipv6_num Number of IPv6 you want to generate it can't exceed 5000
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function add_ipv6($ips6 = [], $gen_ipv6 = [], $ipv6_num = 50){
		
		$act = 'act=addips';
		
		$data['addip'] = 1;
		$data['iptype'] = 6;
		$data['ips6'] = $ips6;
		$data['ipv6_num'] = $ipv6_num;
		
		$i = 1;
		foreach($gen_ipv6 as $ipv6){
			$data['ipv6_'.$i] = $ipv6;
			$i++;
			
			if($i > 6){
				break;
			}
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
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
	function list_ips($ip='', $user='', $lock = null, $reslen = '50', $page = 0){
		$act = 'act=ips';
		
		$data = [];
		
		if(!empty($ip)){
			$data['ip'] = $ip;
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
	
	/**
	 * Delete ips
	 *
	 * @category	 NETWORKING
	 * @param		 string $ips give ip address that you want to delete for multiple delte you can give comm seperate ip e.g. '192.168.1.1, 192.168.1.2, 192.168.1.3'
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_ips($ips){
		$act = 'act=ips';
		
		$data['ips'] = $ips;
		$data['do'] = 'delete';
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Lock ips
	 *
	 * @category	 NETWORKING
	 * @param		 string $ips give ip address that you want to lock for multiple lock you can give comm seperate ip e.g. '192.168.1.1, 192.168.1.2, 192.168.1.3'
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function lock_ips($ips){
		$act = 'act=ips';
		
		$data['ips'] = $ips;
		$data['do'] = 'lock';
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Unlock ips
	 *
	 * @category	 NETWORKING
	 * @param		 string $ips give ip address that you want to unlock for multiple unlock you can give comm seperate ip e.g. '192.168.1.1, 192.168.1.2, 192.168.1.3'
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function unlock_ips($ips){
		$act = 'act=ips';
		
		$data['ips'] = $ips;
		$data['do'] = 'unlock';
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Edit ip
	 *
	 * @category	 NETWORKING
	 * @param		 string $ips give ip address that you want to unlock for multiple unlock you can give comm seperate ip e.g. '192.168.1.1, 192.168.1.2, 192.168.1.3'
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function edit_ip($ip, $note = '', $locked = 0){
		$act = 'act=editip';
		
		$data['editip'] = 1;
		$data['ip'] = $ip;
		$data['note'] = $note;
		$data['locked'] = $locked;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN Storage								 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * List Storage
	 *
	 * @category	 STORAGE
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_storage(){
		$act = 'act=storage';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Add Storage
	 *
	 * @category	 STORAGE
	 * @param		 string $name The name of the storage
	 * @param		 string $path Path to the mount point
	 * @param		 string $type The type of storage options ['file', 'ext4', 'xfs', 'btrfs', 'zfs thin block', 'zfs thin block compressed', 'ceph block']
	 * @param		 string $alert If the used size crosses this percentage, an email will be sent to the Admin
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function add_storage($name, $path, $type = 'file', $alert){
		$act = 'act=addstorage';
		
		$data['addstorage'] = 1;
		$data['name'] = $name;
		$data['path'] = $path;
		$data['type'] = $type;
		$data['alert'] = $alert;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Edit Storage
	 *
	 * @category	 STORAGE
	 * @param		 string $path Path to the mount point
	 * @param		 string $name The name of the storage
	 * @param		 string $type The type of storage options ['file', 'ext4', 'xfs', 'btrfs', 'zfs thin block', 'zfs thin block compressed', 'ceph block']
	 * @param		 string $alert If the used size crosses this percentage, an email will be sent to the Admin
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function edit_storage($path, $name, $type = 'file', $alert){
		$act = 'act=addstorage&storage='.$path;
		
		$data['addstorage'] = 1;
		$data['name'] = $name;
		$data['path'] = $path;
		$data['type'] = $type;
		$data['alert'] = $alert;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Delete Storage
	 *
	 * @category	 STORAGE
	 * @param		 string $storage Path to the mount point e.g. /home, /home2
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_storage($storage){
		$act = 'act=storage';
		
		$data['delete'] = $storage;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN Email								 	 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * List Email queue by domain or email
	 *
	 * @category	 EMAIL
	 * @param		 string $domain Enter domain if you want email queue by domain
	 * @param		 string $euser Enter domain email user if you want single email account emal queue
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_email_queue($domain = '', $euser = ''){
		$act = 'act=email_queue';
		
		$data = [];
		if(!empty($domain)){
			$data['domain'] = $domain;
		}
		
		if(!empty($euser)){
			$data['euser'] = $euser;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List emails of all users of all domain
	 *
	 * @category	 EMAIL
	 * @param		 string $email Search By email account
	 * @param		 string $user Search by System/webuzo user
	 * @param		 string $reslen pass how much record you want to display per page (pass 'all' if you want to diplay all results)
	 * @param		 int $page pass page number
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_emails($email = '', $user = '', $reslen = 50, $page = 0){
		$act = 'act=emails';
		
		$data = [];
		
		if(!empty($email)){
			$data['email'] = $email;
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
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN Tasks								 	 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Task list
	 *
	 * @category	 TASKS
	 * @param		 string $user Enter system/webuzo user to search task list
	 * @param		 string $actid Search by task id
	 * @param		 string $reslen pass how much record you want to display per page (pass 'all' if you want to diplay all results)
	 * @param		 int $page pass page number
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_tasks($user = '', $actid = '', $reslen = 50, $page = 0){
		$act = 'act=tasks';
		
		$data = [];
		if(!empty($user)){
			$data['user'] = $user;
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
	
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN Security								 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Add Ip-Block
	 *
	 * @category	 SECURITY
	 * @param		 string $ip IP Address/Domain You can specify the IP Address in the following format [ single ip/domain => 192.168.0.1 or example.com, Ip range => 192.168.0.1 - 192.168.0.50, CIDR Format => 192.168.0.1/20 ]
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function add_ipblock($ip){
		$act = 'act=ipblock';
		
		$data['add_ip'] = 1;
		$data['dip'] = $ip;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List IP-BLOCK
	 *
	 * @category	 SECURITY
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_ipblock(){
		$act = 'act=ipblock';
		
		$data = [];
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Delete IP-BLOCK
	 *
	 * @category	 SECURITY
	 * @param		 string $record_id Give record id of ipblock list
	 * @param		 string $ip [OPTIONAL] give ip address to delete
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_ipblock($record_id = 1, $ip = ''){
		$act = 'act=ipblock';
		
		$data['delete_record'] = $record_id;
		
		if(!empty($ip)){
			$data['delete_ip'] = $ip;
		}
		// print_r($data);exit;
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Read CSF configuration
	 *
	 * @category	 SECURITY
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function read_csf_conf(){
		$act = 'act=csf_conf';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Enable/Disable SSH Access
	 *
	 * @category	 SECURITY
	 * @param        boolean $sshon Enable SSH/SFTP (1/0)
	 * @param        int $ssh_port Change SSH Port (default 22)
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function ssh_access($sshon = 1, $ssh_port = 22){
		
		$act = 'act=ssh_access';
		
		$data['editsshsettings'] = 1;
		
		$data['sshon'] = $sshon;
		$data['ssh_port'] = $ssh_port;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN APPS									 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * List application category wise
	 *
	 * @category	 APPS
	 * @param		 string $cat give category of application e.g ['server_side_scripting', 'stacks', 'web_servers', 'databases',.....,'message_queue']
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_apps($cat = 'server_side_scripting'){
		$act = 'act=listapps';
		
		if(!empty($this->apps)){
			return $this->apps;
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
	
	/**
	 * A Function that will INSTALL apps. If the DATA is empty script information is retured
	 *
	 * @package		API 
	 * @author		Jigar Dhulla
	 * @param		int $sid Script ID
	 * @param		array $data DATA to POST
	 * @return		string $resp Response of Action. Default: Serialize
	 * @since		4.1.3
	 */	
	function install_app($appid){
	
		// Get the Scripts List
		$this->list_apps();
		
		// Script present ?
		if(empty($this->apps[$appid])){
			$this->error[] = 'App Not Found';
			return false;
		}
		
		$act = 'act=apps&app='.$appid.'&install=1';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
		
	}
	
	/**
	 * A Function that will REMOVE apps. If the DATA is empty script information is retured
	 *
	 * @package		API 
	 * @author		Jigar Dhulla
	 * @param		int $sid Script ID
	 * @param		array $data DATA to POST
	 * @return		string $resp Response of Action. Default: Serialize
	 * @since		4.1.3
	 */	
	function remove_app($appid){
	
		// Get the Scripts List
		$iapps = $this->list_installed_apps();
		
		// Script present ?
		if(empty($iapps[$appid])){
			$this->error[] = 'App Not Found';
			return false;
		}
		
		$act = 'act=apps&app='.$appid.'&remove=1';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
		
	}
	
	/**
	 * A Function that will list installed scripts
	 *
	 * @category	APPS 
	 * @author		Vasu
	 * @return		array $scripts List of Installed Softaculous Scripts
	 * @since		3.0.0
	 */	
	function list_installed_apps(){
		$act = 'act=apps_installations';
		
		if(!empty($this->installed_apps)){
			return $this->installed_apps;
		}
		
		// Get response from the server.
		$resp = $this->aapi_call($act);
		
		$this->installed_apps = $resp['apps_ins'];
		
		return $resp['apps_ins'];
	}
	
	/**
	 * A Function that will list available update for apps
	 *
	 * @category	APPS 
	 * @author		Vasu
	 * @return		array $scripts list available update for softaculous script
	 * @since		3.0.0
	 */	
	function list_apps_updates(){
		$act = 'act=apps_updates';

		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * A Function that will list available update for apps
	 *
	 * @category	APPS
	 * @param		int $app_id Pass app Id that you want to update
	 * @author		Vasu
	 * @return		array $resp
	 */	
	function update_apps($app_id){
		$act = 'act=apps_updates';
		
		$data['update'] = 1;
		$data['app'] = $app_id;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Remove backup file that create while updating app
	 *
	 * @category	APPS
	 * @param		int $binsid give backup file id that you want to remove
	 * @author		Vasu
	 * @return		array $resp
	 */	
	function remove_app_backfile($binsid){
		$act = 'act=apps_updates';
		
		$data['backup_remove'] = 1;
		$data['backup_file'] = $binsid;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Services
	 *
	 * @category	 APP
	 * @author		 Vasu
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function list_services(){
		
		$act = 'act=services';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
		
	}
	
	/**
	 * Manage Services
	 *
	 * @category	 APP
	 * @author		 Vasu
	 * @param        string $service_name Specify the service to restart
	 				 E.g exim, dovecot, tomcat, httpd, named, pure-ftpd, mysqld, php7.3
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function manage_service($service_name, $action = 'restart'){
		
		$act = 'act=services';
		
		$data['service_name'] = $service_name;
		$data['action'] = $action;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * List DNS record for all domain
	 *
	 * @category	 APP
	 * @author		 Vasu
	 * @param		 string $domain [OPTIONAL] Pass domain which you want DNS record
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function list_dns($domain = ''){
		$act = 'act=advancedns';
		
		$data = [];
		if(!empty($domain)){
			$data['domain'] = $domain;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
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
	function create_dns($domain, $name, $address, $ttl = 14400, $type = 'A'){
		$act = 'act=advancedns';
		
		$data['create_record'] = 1;
		$data['domain'] = $domain;
		$data['name'] = $name;
		$data['address'] = $address;
		$data['ttl'] = $ttl;
		$data['selecttype'] = $type;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
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
	function edit_dns($point, $domain, $name, $address, $ttl = 14400, $type = 'A'){
		$act = 'act=advancedns';
		
		$data['edit_record'] = $point;
		$data['domain'] = $domain;
		$data['name'] = $name;
		$data['record'] = base64_encode($address);
		$data['ttl'] = $ttl;
		$data['type'] = $type;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List DNS record for all domain
	 *
	 * @category	 APP
	 * @author		 Vasu
	 * @param		 int $point Pass the location of dns record which you want to edit 
	 * @param		 string $domain Pass domain which you want to delete DNS record
	 * @return		 string $resp Response of Action. Default: Serialize
	 */
	function delete_dns($point, $domain){
		$act = 'act=advancedns';
		
		$data = [];
		$data['delete_record'] = $point;
		$data['domain'] = $domain;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Add DNS Zone
	 *
	 * @category	APP
	 * @author	Tejas	 
	 * @param	string $domain Pass domain which you want to add DNS zone
	 * @param	string $address IP Address of your DNS zone
	 * @param	string $user user to which DNS zone will belong. Default: root.
	 * @return	string $resp Response of Action. Default: Serialize
	 */
	function add_dns_zone($domain, $address, $user = 'root'){
		$act = 'act=dns_zones';
		
		$data['add'] = 1;
		$data['domain'] = $domain;
		$data['user'] = $user;
		$data['ipv4'] = $address;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Delete DNS Zone
	 *
	 * @category	DNS Functions
	 * @author	Vikas	 
	 * @param	string $domain Pass domain (domains) to delete DNS zone
	 */
	function delete_dns_zone($domain){
		$act = 'act=dns_zones';
		
		$data['delete'] = 1;
		$data['domain'] = $domain;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	
	/**
	 * Create DNS templates
	 *
	 * @category	 DNS Functions
	 * @author		 Vikas	
	 * @param		 string $name Pass dns name for a domain
	 * @param		 string $type DNS type
	 * @param		 string $ttl DNS TTL value 
	 * @param		 string $address DNS Record / content
	 */
	function create_dns_template($name, $type, $ttl, $address){
		$act = 'act=dns_template';
		
		$data['create_record'] = 1;
		$data['name'] = $name;
		$data['type'] = $type;
		$data['ttl'] = $ttl;
		$data['address'] = $address;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
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
	function edit_dns_template($point, $name, $type, $ttl, $address){
		$act = 'act=dns_template';
		
		$data['edit_record'] = $point;
		$data['name'] = $name;
		$data['type'] = $type;
		$data['ttl'] = $ttl;
		$data['address'] = $address;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * read DNS templates
	 *
	 * @category	 DNS Functions
	 * @author		 Vikas
	 */
	function read_dns_template(){
		$act = 'act=dns_template';		
		$data = [];
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Set DNS TTL 
	 *
	 * @category	 DNS Functions
	 * @author		 Vikas
	 */
	function set_dns_ttl($domains, $ttl){
		$act = 'act=set_ttl';		
		
		$data = [];
		
		$data['edit_ttl'] = $domains;
		$data['ttl'] = $ttl;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Set DNS TTL 
	 *
	 * @category	 DNS Functions
	 * @author		 Vikas
	 */
	function sso($no_ip = 0){
		$act = 'act=sso';		
		
		$data = [];
		
		$data['no_ip'] = $no_ip;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Set root MySQL password
	 *
	 * @category	 APP
	 * @author		 Vasu
	 * @param        string $pass New password to change root(MySQL) password
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function set_root_mysql_pass($pass){
		
		$act = 'act=mysql_root_pass';
		
		$data['newpass'] = $data['conf'] = $pass;
		$data['changepass'] = 1;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}

	/**
	 * Enable/Disable fastcgi of apache
	 *
	 * @category	 APP
	 * @author		 Vasu
	 * @param        boolean $fastcgi pass 1 if you want to enable Fast CGI
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function set_fastcgi($fastcgi = 1){
		
		$act = 'act=apache_settings';
		
		$data['editapachesettings'] = 1;
		
		if(!empty($fastcgi)){
			$data['fastcgion'] = $fastcgi;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}

	/**
	 * Extra configuration for apache
	 *
	 * @category	 APP
	 * @author		 Vasu
	 * @param        boolean $http2 Enable HTTP/2 protocol
	 * @param        boolean $gzip Enable Gzip Compression
	 * @param        boolean $user_mod Enable USER MOD DIR
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function set_http2settings($http2 = 0, $gzip = 1, $user_mod = 0){
		
		$act = 'act=apache_settings';
		
		$data['edithttp2settings'] = 1;
		
		if(!empty($http2)){
			$data['http2on'] = $http2;
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

	/**
	 * Enable NGINX Proxy
	 *
	 * @category	 APP
	 * @author		 Vasu
	 * @param        int $port Port for running Apache Service
	 * @param        string $webserver Select the Proxy server to be used. (apache or apache2).
	 * @param        boolean $ht_check Check if you want to allow .htaccess files.
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function enable_proxy($port, $webserver, $ht_check = 0){
		
		$act = 'act=apache_settings';
		
		$data['enable_proxy'] = 1;
		$data['port'] = $port;
		$data['webserver'] = $webserver;
		
		if(!empty($ht_check)){
			$data['ht_check'] = $ht_check;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Disable NGINX Proxy
	 *
	 * @category	 APP
	 * @param        String $proxy_server - Default Webserver to be set - "httpd,apache2,nginx,lighttpd"
	 * @return		 array $resp 
	 */
	function disable_proxy($set_default_webserver){
		
		$act = 'act=apache_settings';
		
		$data['webserver'] = $set_default_webserver;
		$data['disable_proxy'] = 1;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Turn on varnish if varnish installed
	 *
	 * @category	 APP
	 * @author		 Vasu
	 * @param        boolean $varnish Enable/Disable Varninsh
	 * @param        int $port Port on which varnish enable
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function set_varnish($varnish = 0, $port){
		
		$act = 'act=apache_settings';
		
		$data['editvarnishsettings'] = 1;
		
		if(!empty($varnish)){
			$data['varnishon'] = $varnish;
		}
		
		$data['varnish_port'] = $port;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Set Default
	 *
	 * @category	 APP
	 * @param        string $service Set the Default Service - php53/php54/nginx/httpd
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function set_defaults($service){
		
		$act = 'act=service_manager';
		
		$php = array('php53', 'php54', 'php55', 'php56', 'php70', 'php71', 'php72', 'php73', 'php74', 'php80');
		$server = array('apache2', 'httpd', 'nginx', 'lighttpd');
		
		if(in_array($service, $php)){
			$data['default_php'] = $service;
		}
		
		if(in_array($service, $server)){
			$data['webserver'] = $service;
		}
		
		$data['service_manager'] = 1;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List PHP Extensions
	 *
	 * @category	 APP
	 * @param        int $php [OPTIONAL] list extension by php app id
	 * @return		 array	$resp Response of Action. Default: Serialize
	 */
	function list_php_ext($php = ''){
		
		$act = 'act=php_ext';
		
		$data = [];
		
		if(!empty($php)){
			$data['php'] = $php;
			$act .= '&ext_list=1';
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Enable / Disable PHP Extensions
	 *
	 * @category	 APP
	 * @param        string $php pass php app id on which extention want to save e.g. 146_1
	 * @param        string $extensions give extention list in comma seperated valu e.g.  bcmath.so,bz2.so,calendar.so,ctype.so,....,etc (if you want to disable all extension just pass "disableall" string)
	 * @return		 array	$resp Response of Action. Default: Serialize
	 */
	function handle_php_ext($php, $extensions){
		
		$act = 'act=php_ext';
		
		$data['save_ext'] = 1;
		$data['php'] = $php;
		$data['extensions'] = $extensions;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Service TLS
	 *
	 * @category	 APP
	 * @param        string $user [optional] search cert by system/webuzo user
	 * @return		 array	$resp Response of Action. Default: Serialize
	 */
	function list_service_cert($user = ''){
		
		$act = 'act=service_tls';
		
		$data = [];
		if(!empty($user)){
			$data['browse_crt'] = 1;
			$data['user'] = $user;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Apply domain certificate on services
	 *
	 * @category	 APP
	 * @param        array $services list of service name that you want to install cert e.g. ['webuzo', 'exim', ...., 'pureftpd',];
	 * @param        string $domain domain cert to install on servies
	 * @return		 array	$resp Response of Action. Default: Serialize
	 */
	function apply_dom_cert($services, $domain){
		$act = 'act=service_tls';
		
		$data['apply_dom_cert'] = 1;
		$data['domain'] = $domain;
		
		foreach($services as $v){
			$data[$v.'_service'] = 1;
		}

		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Reset Certificate on service
	 *
	 * @category	 APP
	 * @param        string $service pass name of service to remove cert
	 * @return		 array	$resp Response of Action. Default: Serialize
	 */
	function reset_cert($service){
		$act = 'act=service_tls';
		
		$data['reset_cert'] = $service;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
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
	function apply_cert_mannually($services, $kpaste, $cpaste, $bpaste = ''){
		$act = 'act=service_tls';
		
		$data['install_manually'] = 1;
		$data['kpaste'] = $kpaste;
		$data['cpaste'] = $cpaste;
		if(!empty($bpaste)){
			$data['bpaste'] = $bpaste;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN ServerUtilities						 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Get CPU infomation
	 *
	 * @category	 SERVERUTILITIES
	 * @author		 Vasu
	 * @return		 array	$resp Response of Action. Default: Serialize
	 */
	function cpu_info(){
		$act = 'act=cpu';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Get RAM infomation (in MB)
	 *
	 * @category	 SERVERUTILITIES
	 * @author		 Vasu
	 * @return		 array	$resp Response of Action. Default: Serialize
	 */
	function ram_info(){
		$act = 'act=ram';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Get Disk info
	 *
	 * @category	 SERVERUTILITIES
	 * @author		 Vasu
	 * @return		 array	$resp Response of Action. Default: Serialize
	 */
	function disk_info(){
		$act = 'act=disk';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
	}
	
	/**
	 * Reboot the server
	 *
	 * @category	 SERVERUTILITIES
	 * @author		 Vasu
	 * @param		 string $type type of reboot options ['grace', 'force']
	 * @return		 array	$resp Response of Action. Default: Serialize
	 */
	function server_reboot($type = 'grace'){
		$act = 'act=disk';
		
		$data['reboot'] = 1;
		$data['type'] = $type;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * List Error Log file wise
	 *
	 * @category	 SERVERUTILITIES
	 * @author		 Vasu
	 * @param		 string $logfile [OPTIONAL] pass the log file default it will list webuzo log
	 * @param		 string $clearlog [OPTIONAL] pass if you want to clear log
	 * @return		 array	$resp Response of Action. Default: Serialize
	 */
	function list_errorlog($logfile = '', $clearlog = ''){
		$act = 'act=errorlog';
		
		$data = [];
		if(!empty($logfile)){
			$data['logfile'] = $logfile;
		}
		
		if(!empty($clearlog)){
			$data['clear_log'] = $clearlog;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
	
	/**
	 * Show Error Log domain wise
	 *
	 * @category	 SERVERUTILITIES
	 * @author		 Vasu
	 * @param        string $domain Domain for the error log (Opional)
	 * @param        boolean $clearlog [OPTIONAL] if clear the log
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_domain_errorlog($domain = '', $clearlog = ''){
		
		$act = 'act=domain_errorlog';
		
		$data = [];
		
		if(!empty($domain)){
			$data['domain_log'] = $domain;
		}
		
		if(!empty($clearlog)){
			$data['clearlog'] = 1;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;	
	}
	
	/**
	 * List Admin CRON
	 *
	 * @category	 SERVERUTILITIES
	 * @author		 Vasu
	 * @param        string $user [OPTIONAL] if it pass it get perticular user cron
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_cron($user = ''){
		
		$act = 'act=cronjob';
		
		$data = [];
		if(!empty($user)){
			$data['user'] = $user;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
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
	function add_cron($minute = '*', $hour = '*', $day = '*', $month = '*', $weekday = '*', $cmd, $edit_cronjob = '', $user = ''){
		
		$act = 'act=cronjob';
		
		$data['minute'] = $minute;
		$data['hour'] = $hour;
		$data['day'] = $day;
		$data['month'] = $month;
		$data['weekday'] = $weekday;
		$data['cmd'] = $cmd;
		
		if(!empty($user)){
			$data['user'] = $user;
		}
		
		if(!empty($edit_cronjob)){
			$data['edit_record'] = 'c'.$edit_cronjob;
		}else{
			$data['create_record'] = 1;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
	 * Delete Admin CRON
	 *
	 * @category	 SERVERUTILITIES
	 * @author		 Vasu
	 * @param        string $id ID of the cron record. Get from the list of cron
	 * @param        string $user [OPTIONAL] if not pass by default it will take logged in user
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function delete_cron($id, $user = ''){
		
		$act = 'act=cronjob';
		
		$data['delete_record'] = 'c'.$id;
		
		if(!empty($user)){
			$data['user'] = $user;
		}
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
		
	}
	
	/**
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
	function list_login_logs($ip = '', $user = '', $owner ='', $delete_all = '', $reslen = 50, $page = 0){
		
		$act = 'act=login_logs';
		
		$data = [];
		
		if(!empty($ip)){
			$data['ip'] = $ip;
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
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN Plugin's								 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * List Installed Plugin
	 *
	 * @category	 PLUGIN
	 * @author		 Vasu
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function list_plugin($user = ''){
		
		$act = 'act=plugins';
		
		$resp = $this->aapi_call($act);
		
		return $resp;
		
	}
	
	///////////////////////////////////////////////////////////////////////////////
	//					CATEGORY : ADMIN Updates								 //
	///////////////////////////////////////////////////////////////////////////////
	
	/**
	 * Update the webuzo
	 *
	 * @category	 UPDATE
	 * @author		 Vasu
	 * @return		 string $resp Response of Actions. Default: Serialize
	 */
	function update_webuzo(){
		$act = 'act=updates';
		
		$data['update'] = 1;
		
		$resp = $this->aapi_call($act, $data);
		
		return $resp;
	}
}


// **************************************************************************************
// 											END OF FILE
// **************************************************************************************

//////////////////////////////////////////////////////////////////////
//							EXAMPLES								//
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
//							Enduser: Examples						//
//////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
//							CATEGORY : ENDUSER DOMAIN						 //
///////////////////////////////////////////////////////////////////////////////
	
////////////////////////////
//		List Domains       
////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

// Get the list of domains
$res = $test->list_domains();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Domain List: '.PHP_EOL;
	$test->r_print($res);
} */

///////////////////////////
//		Add Domain       //
///////////////////////////
/* $test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_domain('example.com', 'public_html/example', 'addon');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
 
///////////////////////////
//		Edit Domain       
////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->edit_domain('example.com', 'public_html/example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
//////////////////////////////
//		Delete Domain       //
//////////////////////////////

/* $test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host );

$res = $test->delete_domain('example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
} */

////////////////////////////////////////////////////////////////////////////////////
//		List Domain  Alias
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_domains_alias();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Domain Alias List: '.PHP_EOL;
	$test->r_print($res);
}
 */
 
 ////////////////////////////////////////////////////////////////////////////////////
//		Add Domain Alias
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_domain_alias('dom1.com', 'dom2.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
} */

////////////////////////////////////////////////////////////////////////////////////
//		Delete Domain Alias
/////////////////////////////////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_domain_alias('dom1.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
/////////////////////////////////////////////////////////////////////////////////////
//		List DNS record of user
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_dns_record();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'DNS List: '.PHP_EOL;
	$test->r_print($res);
}
 */
/////////////////////////////////////////////////////////////////////////////////////
//		Add DNS record of user
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_dns_record('example.com', 'test', '14400', 'A', '192.168.14.129');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
/////////////////////////////////////////////////////////////////////////////////////
//		Edit DNS record of user
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->edit_dns_record( 7, 'example.com', 'test1', '14400', 'A', '192.168.14.129');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		Delete DNS record of user
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_dns_record(7, 'example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
  */
/////////////////////////////////////////////////////////////////////////////////////
//		Network Tools : trace/lookup domain
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->dns_lookup('example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Lookup/Traceroute Log:';
	echo $res['done']['lookup'];
}
 */

///////////////////////////////////////////////////////////////////////////////////
//						 		CATEGORY: FTP						 			 //
///////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////
//		List FTP account of a user
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_ftpuser();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'FTP accout list:';
	$test->r_print($res);
}
 */
 
/////////////////////////////////////////////////////////////////////////////////////
//		Add FTP account of a user
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_ftpuser('test', 'pass', 'test', 100);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
 
/////////////////////////////////////////////////////////////////////////////////////
//		Edit FTP account of a user
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->edit_ftpuser('test_primary.domain');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		change FTP account password of a user
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->change_ftpuser_pass('test_primary.vasu.com', 'password');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		Delete FTP user
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_ftpuser('test_primary.domain', 1);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
 
/////////////////////////////////////////////////////////////////////////////////////
//		List FTP active connection
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_ftp_connections();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Active FTP connections'.PHP_EOL;
	$test->r_print($res);
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		Delete FTP active connection
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_ftp_connection($ftp_connection_pid);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

///////////////////////////////////////////////////////////////////////////////
//							CATEGORY : DATABASE								 //
///////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////
//		List database of user
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_database();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Database List:'.PHP_EOL;
	$test->r_print($res);
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		Add database
/////////////////////////////////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_database('test');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
  */
 
/////////////////////////////////////////////////////////////////////////////////////
//		Delete database
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_database('user_test');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		List database User
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_db_user();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Database User List:'.PHP_EOL;
	$test->r_print($res);
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		Add database User
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_db_user('testuser', 'passs');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
 
/////////////////////////////////////////////////////////////////////////////////////
//		Delete database User
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_db_user('user_testuser');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
 
/////////////////////////////////////////////////////////////////////////////////////
//		change database User PASSWORD
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->change_db_user_pass('vasu_test', 'pass');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
 
/////////////////////////////////////////////////////////////////////////////////////
//		Set user into database with privileges
/////////////////////////////////////////////////////////////////////////////////////

// Specify Database name in the following format:
// FORMAT : webuzo-username_databasename
// E.g : soft_test
// Specify Database user in the following format:
// FORMAT : webuzo-username_databaseuser
// E.g : soft_test


// Specify the Databas HOST
// Set $data['host'] = 'localhost'; for localhost
// Set $data['host'] = 'any host'; for Remote Host
// Set $data['host'] = 'example.com'; for your HOST(example.com)
// Set the privileges. Leave blank to restrict privileges
// 'SELECT,CREATE,INSERT,UPDATE,ALTER,DELETE,INDEX,CREATE_TEMPORARY_TABLES,EXECUTE,DROP,LOCK_TABLES,REFERENCES,CREATE_ROUTINE,CREATE_VIEW,SHOW_VIEW' 

/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->set_privileges('user_test1', 'user_test', 'localhost', 'SELECT,CREATE,INSERT,UPDATE,ALTER,DELETE,INDEX,CREATE_TEMPORARY_TABLES,EXECUTE,DROP,LOCK_TABLES,REFERENCES,CREATE_ROUTINE,CREATE_VIEW,SHOW_VIEW');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		List remote access
/////////////////////////////////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_remote_mysql_access();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Remote Access List: '.PHP_EOL;
	$test->r_print($res);
} 
*/

/////////////////////////////////////////////////////////////////////////////////////
//		Add remote access
/////////////////////////////////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_remote_mysql_access('vasu_test1');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		Delete remote access
/////////////////////////////////////////////////////////////////////////////////////
/*   
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_remote_mysql_access('test1');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////////////////////////////////////
//					   		CATEGORY : Enduser SSL						//
//////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////
//		List SSL key
/////////////////////////////////////////////////////////////////////////////////////
/*   
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_ssl_key();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List SSL key: '.PHP_EOL;
	$test->r_print($res);
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		Create SSL key
/////////////////////////////////////////////////////////////////////////////////////
 /*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->create_ssl_key('Description');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
 
/////////////////////////////////////////////////////////////////////////////////////
//		Upload SSL key
/////////////////////////////////////////////////////////////////////////////////////
 /*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->upload_ssl_key($description, $keypaste);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
 
/////////////////////////////////////////////////////////////////////////////////////
//		Detail SSL key
/////////////////////////////////////////////////////////////////////////////////////
/*   
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->detail_ssl_key($key_name);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Key Detail:'.PHP_EOL;
	$test->r_print($res);
}
*/

/////////////////////////////////////////////////////////////////////////////////////
//		Delete SSL key
/////////////////////////////////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_ssl_key($key_path);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		List SSL CSR
/////////////////////////////////////////////////////////////////////////////////////
/*   
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_ssl_csr();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List SSL CSR:'.PHP_EOL;
	$test->r_print($res);
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		Create SSL CSR
/////////////////////////////////////////////////////////////////////////////////////
/*   
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->create_ssl_csr($domain, $country_code, $state, $locality, $org, $org_unit,  $passphrase, $email, $key);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		Detail SSL CSR
/////////////////////////////////////////////////////////////////////////////////////
/*   
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->detail_ssl_csr($domain);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Key Detail:'.PHP_EOL;
	$test->r_print($res);
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		Delete SSL CSR
/////////////////////////////////////////////////////////////////////////////////////
/*   
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_ssl_csr($path);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

/////////////////////////////////////////////////////////////////////////////////////
//		LIST SSL certificates
/////////////////////////////////////////////////////////////////////////////////////
/*   
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_ssl_crt();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'LIST of SSL Certificates:'.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////
//		 Create SSL Certificate			
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->create_ssl_crt($domain, $country_code, $state, $locality, $org, $org_unit, $email, $key);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/

//////////////////////////////////////////
//		 UPload SSL Certificate			
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->upload_ssl_crt($keypaste);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/

//////////////////////////////////////////
//		 Detail SSL Certificate			
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->detail_ssl_crt($domain);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Certificates Detail:'.PHP_EOL;
	$test->r_print($res);
}
*/

//////////////////////////////////////////
//		 Delete SSL Certificate			
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_ssl_crt($domain_key);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/


//////////////////////////////////////////
//		 List Installed Certificates		
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_install_cert();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Installed Certificates:'.PHP_EOL;
	$test->r_print($res);
}
*/

//////////////////////////////////////////
//		 Install Certificate
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->install_cert($domain, $kpaste, $cpaste);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/

//////////////////////////////////////////
//		 Delete Install Certificate
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_install_cert($record_key);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/

//////////////////////////////////////////
//		 Detail Install Certificate
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->detail_install_cert($record_key);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Detail Certificate:'.PHP_EOL;
	$test->r_print($res);
}
*/

//////////////////////////////////////////
//		List LET'S Encrupt Certificates
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_le();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List LE Certificates :'.PHP_EOL;
	$test->r_print($res);
}
*/

//////////////////////////////////////////
//		install LE Certificates
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->install_le_cert($domain);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/

//////////////////////////////////////////
//		Revoke LE Certificates
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->revoke_le_cert($domain);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/

//////////////////////////////////////////
//		Renew LE Certificates
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->renew_le_cert($domain);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/

//////////////////////////////////////////////////////////////////////////////
//					   		CATEGORY : Enduser Email						//
//////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////
//		List Email Accounts
//////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_emailuser();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List Email Accounts :'.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////
//		Add Email Account
//////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_emailuser('example.com', 'emailuser', 'password');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////
//		change Email Account PASSWORD
//////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->change_email_user_pass('example.com', 'emailuser', 'password', 10);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////
//		Delete Email Account 
//////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_email_user('example.com', 'emailuser');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////
//		List Email Forward 
//////////////////////////////////////////
 /*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_emailforward('example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List Email Forwards :'.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////
//		Add Email Forward 
//////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_emailforward('example.com', 'sdk1', 'test@example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/

//////////////////////////////////////////
//		Delete Email Forward 
//////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_email_forward('example.com', 'sdk1', 'test@example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////
//		List MX entry 
//////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_mx_entry('example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List MX Entry :'.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////
//		Add MX entry 
//////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_mx_entry(20, 'example.com', 'example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
 
//////////////////////////////////////////
//		Edit MX entry 
//////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->edit_mx_entry('example.com', 7, 22, 'example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////
//		Delete MX entry 
//////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_mx_entry('example.com', 7);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////
//		List Email Autoresponder 
//////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_emailautoresponder('example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List Email Autoresponder :'.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////
//		Add Email Autoresponder 
//////////////////////////////////////////
/*   
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_emailautoresponder('test', 'testing subject', 'testing body', 'example.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////
//		Edit Email Autoresponder 
//////////////////////////////////////////
/*   
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->edit_emailautoresponder('example.com', 'test4', 'testing subject Edit', 'testing body Edit');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////
//		Delete Email Autoresponder 
//////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_emailautoresponder('test4');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////
//		List Email Queue of domain 
//////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_email_queue('test4');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Email Queue :'.PHP_EOL;
	$test->r_print($res['mailq']);
}
 */

//////////////////////////////////////////
//		Add email into spam
//////////////////////////////////////////
 /* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_spam_assassin('user1@domain.com', 'blacklist');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
 
//////////////////////////////////////////
//		Read spam emails
//////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_spam_assassin();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Email SPAM List :'.PHP_EOL;
	$test->r_print($res);
}
 */
 
//////////////////////////////////////////
//		Delete spam emails
//////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->delete_spam_assassin('user1@domain.com', 'blacklist');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

///////////////////////////////////////////////////////////////////////////////
//							CATEGORY : Configuration						 //
///////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////
//		Set PHP version on a domain
//////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$data['example.com'] = 'php74';

$res = $test->set_multi_php($data);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////
//		Eduser: Save PHP ini
//////////////////////////////////////////

/*
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

// Done/Error
if(!empty($res['done'])){
	echo !empty($res['done']['msg']) ? $res['done']['msg'] : 'Succefully call save_php_ini()';
}else{
	echo 'Error while adding domain<br/>';
	$test->r_print($res['error']);
}
*/

//////////////////////////////////////////////
//				Eduser: Get PHPini			//
//////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->get_php_ini('example.com');
// Done/Error
if(!empty($res['done'])){
	echo 'Local PHP INI content:'.PHP_EOL;
	$test->r_print($res['done']['user_ini_content']);
}else{
	echo 'Error while performing action<br/>';
	$test->r_print($res['error']);
}
 */
///////////////////////////////////////////////////////////////////////
//				Get Installed Pear package list				 		 //
///////////////////////////////////////////////////////////////////////

/* 
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

///////////////////////////////////////////////////////////////////////
//				Get Available Pear package list				 		 //
///////////////////////////////////////////////////////////////////////

/* $test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->get_PEAR_list();

if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Available Pear list : '.PHP_EOL;
	$test->r_print($res['pearlist']);
} */

///////////////////////////////////////////////////////////////////////
//				Install/update/unistall/reinstall PEAR module
///////////////////////////////////////////////////////////////////////
/* 
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
 
///////////////////////////////////////////////////////////////////////////////
//							CATEGORY : SECURITY						 		 //
///////////////////////////////////////////////////////////////////////////////

////////////////////////////////
//		Change Password       //
////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->change_password($webuzo_password);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/

//////////////////////////////////////////////////////////
//		Change Apache Tomcat Manager's Password
///////////////////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->change_tomcat_pwd($pass);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/

//////////////////////////////////////////////////////////
//		Block an IP
///////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_ipblock($ip);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////////////////////
//		List Blocked IP'S
///////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_ipblock();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List Blocked IP\'s : '.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////////////////////
//		Delete/Unblock Block IP
///////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ipblock('192.168.12.1/32');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */


//////////////////////////////////////////////////////////
//		Generating a Public Key
///////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->ssh_generate_keys('test', 'test123');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
//////////////////////////////////////////////////////////
//		List SSH access key
///////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_ssh_access('test', 'test123');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List SSH access key : '.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////////////////////
//		Authorize/Deauthorize SSH access key
//////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->ssh_key_auth('test.pub', 'Deauthorize');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////////////////////
//		View SSH access key
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->view_ssh_key('test.pub');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'SSH private/public keys : '.PHP_EOL;
	$test->r_print($res['view_content']);
}
 */

//////////////////////////////////////////////////////////
//		Delete SSH access key
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_ssh_key('test.pub');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////////////////////
//		Import SSH access key
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->ssh_import_keys($keyname, $private_key, $passphrase, $public_key);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////////////////////
//		Add Password Protected User
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_pass_protect_dir('test', 'testuser', 'testuser', 'Test Account');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////////////////////
//		List Password Protected User
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_pass_protect_dir();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List password Protected User : '.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////////////////////
//		Delete Password Protected User
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_pass_protect_dir('test', 'test');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////////////////////
//		List HOTLINK previous conf
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_hotlink_protect();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	$test->r_print($res);
}
 */

//////////////////////////////////////////////////////////
//		Enable HOTLINK protection
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$urllist = 'http://example1.com 
https://example2.com
';
$res = $test->enable_hotlink_protect($urllist, 'jpg,jpeg,gif,png,bmp', true, 'http://google.com');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////////////////////
//		Disable HOTLINK protection
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->disable_hotlink_protect();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/


//////////////////////////////////////////////////////////
//		List MOD security 
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_mod_security();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Mod Security list : '.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////////////////////
//		Change Mod security
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->change_mod_security(3);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

	///////////////////////////////////////////////////////////////////////////////////
	//						 		CATEGORY: Server Utilities						 //
	///////////////////////////////////////////////////////////////////////////////////
	
//////////////////////////////////////////////////////////
//		Add backup server
//////////////////////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_backup_server('test', $type, $remote_username, $remote_ip, $remote_user_pass, $remote_port, $remote_location);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */
 	
//////////////////////////////////////////////////////////
//		List backup server
//////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->list_backup();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List backup server:'.PHP_EOL;
	$test->r_print($res);
}
 */
 	
//////////////////////////////////////////////////////////
//		REMOVE backup server
//////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->remove_backup_server(3);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */ 

//////////////////////////////////////////////////////////
//		List Login Logs
//////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
#to clear login log pass 1 as parameters
$res = $test->list_login_logs();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List login\'s logs:'.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////////////////////
//		List Crons
//////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
#to clear login log pass 1 as parameters
$res = $test->list_cron();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'List Crons:'.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////////////////////
//		Add Crons
//////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_cron('*', '*', '*', '3', '*', 'php -v');

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////////////////////
//		Edit Crons
//////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->add_cron('*', '*', '*', '3', '3', 'php -v', 2);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */

//////////////////////////////////////////////////////////
//		Delte Cron
//////////////////////////////////////////////////////////
/*  
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);
$res = $test->delete_cron(2);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
 */


////////////////////////////////////////////
//		Show Error Logs OF DOMAIN     
///////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->show_error_log();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Error log '.PHP_EOL;
	$test->r_print($res);
}
 */

////////////////////////////////////////////
//		Show bandwidth usage of the user    
///////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->show_bandwidth();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'BANDWIDTH usage : '.PHP_EOL;
	$test->r_print($res);
}
 */

////////////////////////////////////////////
//		List disk usage of the user    
///////////////////////////////////////////
/* 
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_disk_usage();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo 'Disk usage : '.PHP_EOL;
	$test->r_print($res);
}
 */

//////////////////////////////////////////////////////////////////////////////
//					   CATEGORY : Advance Settings							//
//////////////////////////////////////////////////////////////////////////////


///////////////////////////////
//	 User Settings		     
///////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->user_settings($email, $language, $arrange_domain);

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	$test->r_print($res['error']);
}else{
	echo $res['done']['msg'];
}
*/

//////////////////////////////////////////////////////////////////////
//							Admin: Examples							//
//////////////////////////////////////////////////////////////////////

////////////////////////////////
//		Manage Services       //
////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

// Specify the services to be restarted
$service_name = 'mysqld';
$action = 'restart';

$res = $test->manage_service($service_name, $action);
$res = unserialize($res);
// $test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Service '.$service_name.' '.$action.'ed successfully';
}else{
	echo 'Error while '.$action.'ing '.$service_name.' service<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}
*/

/////////////////////////////////////
//		Enable/Disable suPHP       //
/////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

// Set on and off to enable and disable suPHP respectively
$res = $test->manage_suphp($status);
$res = unserialize($res);
// $test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Settings saved successfully';
}else{
	echo 'Error while saving settings<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}
*/

/*
///////////////////////////////////
//		Enable NGINX Proxy       //
///////////////////////////////////
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

// Set on and off to enable and disable suPHP respectively
$res = $test->enable_proxy($port, $htaccess, $proxy_server);
$res = unserialize($res);
$test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Settings saved successfully';
}else{
	echo 'Error while saving settings<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}*/

/*
///////////////////////////////////
//		Disable NGINX Proxy      //
///////////////////////////////////
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

// Set on and off to enable and disable suPHP respectively
$res = $test->disable_proxy($set_default_webserver);
$res = unserialize($res);
$test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Settings saved successfully';
}else{
	echo 'Error while saving settings<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}
*/

//////////////////////////////////////////////////////////////////////////////
//					   CATEGORY : Server Settings							//
//////////////////////////////////////////////////////////////////////////////

////////////////////////
//		Add CRON      //
////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->add_cron($minute, $hour, $day, $month, $weekday, $cmd);
$res = unserialize($res);
// $test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'CRON added successfully';
}else{
	echo 'Error while adding CRON<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}
*/

//////////////////////////////////////////////////////////////////////////
//					   		CATEGORY : Security							//
//////////////////////////////////////////////////////////////////////////

///////////////////////////////////
//		Enable/Disable SSH       //
///////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->ssh_access($action);
$res = unserialize($res);
// $test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Settings saved successfully';
}else{
	echo 'Error while saving settings<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}
*/


//////////////////////////////////////////////////////////////////////////////
//					   		CATEGORY : Email Server							//
//////////////////////////////////////////////////////////////////////////////

////////////////////////////
//		Set Defaults      //
////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->set_defaults('php53');
$res = unserialize($res);
//$test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Default Set';
}else{
	echo 'Error while setting defaults<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}
*/


//////////////////////////////////////////////////////////////////////////
//					   CATEGORY : Server Info							//
//////////////////////////////////////////////////////////////////////////

///////////////////////////////
//		Configure Webuzo     //
///////////////////////////////
/*
$test = new Webuzo_SDK();

$res = $test->webuzo_configure($ip, $user, $email, $pass, $host, $ns1 = '', $ns2 ='', $license = '' );
$res = unserialize($res);
$test->r_print($res);
*/

///////////////////////////////////////////
//		Install System Application       //
///////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$appid = 30; // ID of the Application to install.

$res = $test->install_app($appid);
//$res = unserialize($res);

$test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Done';
}else{
	echo 'Failed<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}
*/

///////////////////////////////////////////
//		Remove System Application       //
///////////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$appid = 30; // ID of the Application to install.

$res = $test->remove_app($appid);
//$res = unserialize($res);

$test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Done';
}else{
	echo 'Failed<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}
*/

//////////////////////////////////
//		List Services			//
//////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->list_services();
$res = unserialize($res);

$test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Done';
}else{
	echo 'Failed<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}
*/

///////////////////////////////////////
//	Enable / Disable PHP Extensions  //
///////////////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$extensions = 'curl.so,calendar.so'; // Extensions to Enable.

$res = $test->handle_php_ext($extensions);
$res = unserialize($res);
//$test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Done';
}else{
	echo 'Failed<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}
*/

///////////////////////////
//	Set BAndwidth Limit  //
//////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->set_bandwidth($total_bandwidth, $bandwiwdth_email_alert);
$res = unserialize($res);
$test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Done';
}else{
	echo 'Failed<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}
*/
/////////////////////////////
//	Reset BAndwidth Limit  //
////////////////////////////
/*
$test = new Webuzo_SDK($webuzo_user, $webuzo_password, $host);

$res = $test->reset_bandwidth();
$res = unserialize($res);
$test->r_print($res);

// Done/Error
if(!empty($res['done'])){
	echo 'Done';
}else{
	echo 'Failed<br/>';
	if(!empty($res['error'])){
		print_r($res['error']);
	}
}

*/