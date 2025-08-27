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

class Webuzo_Sessions{
	
	var $sess_path = '/var/webuzo/sessions';
	var $users_path = '/var/webuzo/users';
	var $user = [];
	var $globals = [];
	var $SESS = [];
	
	function __construct(){
		
		// Load universal
		include_once(dirname(dirname(__FILE__)).'/universal.php');
		include_once(dirname(dirname(__FILE__)).'/globals.php');
		
		$this->globals = $globals;
		
		// Make session
		$this->make_session();
		
	}
	
	function __destruct(){
		
		// API Calls must destroy sessions
		if(!empty($_SERVER['PHP_AUTH_PW'])){
			$this->doLogout();
		}
		
	}

	/**
	 * Generate a random string for the given length
	 *
	 * @package      string
	 * @author       Pulkit Gupta
	 * @param        int $length The number of charactes that should be returned
	 * @return       string Randomly geterated string of the given number of charaters
	 * @since     	 1.0
	 */
	function generateRandStr($length, $special = 0){
		
		$randstack = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		
		$specialchars = array('!', '[', ']', '(', ')', '.', '-', '@');
		
		$randstr = '';

		while(strlen($randstr) < $length){
			
			$randstr .= $randstack[array_rand($randstack)];
			
			if(!empty($special) && strlen($randstr) < $length && (strlen($randstr)%2 == 0)){
				$randstr .= $specialchars[array_rand($specialchars)];
			}
			
		}
		
		return str_shuffle($randstr);

	}

	/**
	 * Create a Session KEY and register the COOKIE
	 *
	 * @category	 Session 
	 * @return       void
	 * @version    	 1.0
	 */
	function make_session(){
		
		$globals = $this->globals;
		$SESS = $this->SESS;
		
		$key = $this->check_session_key();
		
		$SESS['sid'] = empty($key) ? $this->generateRandStr(32) : $key;
		$SESS['token'] = 'sess'.substr($SESS['sid'], 0, 16);
		$SESS['url'] = 'http'.(!empty($_SERVER['HTTPS']) ? 's' : '').'://'.$_SERVER['HTTP_HOST'].'/'.$SESS['token'].'/';
		@setcookie($globals['cookie_name'].'_sid', $SESS['sid'], 0, '/'.$SESS['token'].'/');
		
		if(!empty($_SERVER['PHP_AUTH_PW'])){
			$r = [];
			$r['username'] = $_SERVER['PHP_AUTH_USER'];
			$r['password'] = $_SERVER['PHP_AUTH_PW'];
			$r['ip'] = $_SERVER['REMOTE_ADDR'];
			$r['sid'] = $SESS['sid'];
			exec('/usr/local/emps/bin/php /usr/local/webuzo/cli.php --checklogin '.escapeshellarg(base64_encode(json_encode($r))), $o, $ret);
			//print_r($o);
		}
		
		$this->SESS = $SESS;
		
	}
	
	/**
	 *  Logs out a USER !
	 *
	 * @category	 User  
	 * @return       array
	 * @version    	 1.0
	 */	
	function doLogout(){
		
		unlink($this->sess_path.'/sess_'.$this->SESS['sid']);
		
		@setcookie($this->globals['cookie_name'].'_sid', '', time() - 3600, '/'.$this->SESS['token'].'/');
		
		return true;
		
	}

	/**
	 * Check Session  
	 *
	 * @category	 Session  
	 * @return       int Returns $id if session available else 0
	 * @version    	 1.0
	 */
	function check_session_key(){
		
		$globals = $this->globals;

		//May be in the GET
		//'as' - Session Key
		if(isset($_GET['as'])){

			$id = trim($_GET['as']);

			if(preg_match('~^[A-Za-z0-9]{32}$~', $id) == 0){
				 
				//Return False
				return 0;
				 
			}else{

				//Return Session ID
				return $id;

			}

		// Cookies
		}elseif(isset($_COOKIE[$globals['cookie_name'].'_sid']) &&
		strlen(trim($_COOKIE[$globals['cookie_name'].'_sid'])) == 32){

			$id = trim($_COOKIE[$globals['cookie_name'].'_sid']);

			if(preg_match('~^[A-Za-z0-9]{32}$~', $id) == 0){
				 
				//Return False
				return 0;
				 
			}else{

				//Return Session ID
				return $id;

			}
			
		//No Session found
		}else{

			//Return False
			return 0;

		}

	}
	
	// Redirect to login form
	function show_login(){
		//print_r($_SERVER);
		
		$this->doLogout();
		
		$server_url = 'http'.(!empty($_SERVER['HTTPS']) ? 's' : '').'://'.$_SERVER['HTTP_HOST'].'/';
	
		header('Location: '.$server_url.'?'.(empty($_SERVER['REQUEST_URI']) && $_REQUEST['act'] != 'logout' ? '' : '&redirect='.rawurlencode($_SERVER['REQUEST_URI'])));
		
		exit();		
	}
	
	// Is it admin panel ?
	function is_admin_panel(){
		
		$globals = $this->globals;
		
		$panel_ports = [2004, 2005];
		
		// Custom Admin Panel SSL port
		if(isset($globals['admin_port_ssl']) && !empty($globals['admin_port_ssl'])){
			$panel_ports[] = $globals['admin_port_ssl'];
		}
		
		// Custom Admin Panel Non-SSL port
		if(isset($globals['admin_port_nonssl']) && !empty($globals['admin_port_nonssl'])){
			$panel_ports[] = $globals['admin_port_nonssl'];
		}
		
		return in_array($_SERVER['SERVER_PORT'], $panel_ports);
	}

	/**
	 * Is the user logged in ?
	 *
	 * @category	 User   
	 * @return       boolean
	 * @version    	 1.0
	 */
	function isLogin(){

		// Check for SESSION TOKEN except API sessions
		if(empty($_SERVER['PHP_AUTH_PW'])){
			$url_ses = explode('/', $_SERVER['REQUEST_URI']);	
			if($url_ses[1] != $this->SESS['token']){
				$this->show_login();
			}
		}
		
		$sess_file = $this->sess_path.'/sess_'.$this->SESS['sid'];
		
		// Do we have the SESSION File ?
		if(!file_exists($sess_file)){
			return false;
		}
		
		$data = json_decode(file_get_contents($sess_file), true);
	
		// Check IP address is correct ONLY if self_sess_key is not matching which comes in the case of API calls internally
		if($_COOKIE['self_sess_key'] != $data['self_sess_key']){
			if($data['ip'] != $_SERVER['REMOTE_ADDR'] && empty($this->globals['no_session_ip'])){
				unlink($sess_file);
				return '';
			}
		}
		
		// Keep the session alive
		touch($sess_file);
		
		$username = $data['user'];
		
		// Get username
		if(empty($username)){
			return false;
		}
		
		// Load the IP check key
		$this->SESS['self_sess_key'] = $data['self_sess_key'];
	
		// For enduser panel are we logging in as a user ?
		if(!$this->is_admin_panel()){
			
			if(!empty($_COOKIE['loginAs'])){
				$loginAs = $_COOKIE['loginAs'];
			}
			if(!empty($_POST['loginAs'])){
				$loginAs = $_POST['loginAs'];
			}
			if(!empty($_GET['loginAs'])){
				$loginAs = $_GET['loginAs'];
			}
			
			// We need to loginAs
			if(!empty($loginAs) && file_exists($this->users_path.'/'.$loginAs.'/info')){
			
				// Are you the root or a special user ?
				if($username == 'root'){
					
					$this->orig_user = $username;
					$username = $loginAs;
				
				// Could be a reseller
				}else{
					
					$prospect = @json_decode(file_get_contents($this->users_path.'/'.$loginAs.'/info'), true);
					
					if($prospect['owner'] == $username){
						$this->orig_user = $username;
						$username = $loginAs;
					}
					
				}
				
			}
			
			// If you are still root, then its a problem
			if($username == 'root'){
				return false;
			}
			
		}
		
		// Root user
		if($username == 'root'){
			
			$info = array();
			$info['user'] = 'root';
			
		// Normal user
		}else{
		
			$info = $this->users_path.'/'.$username.'/info';
			
			// User file found ?
			if(!file_exists($info)){
				return false;
			}
			
			$info = file_get_contents($info);
			$info = json_decode($info, true);
		
		}
		
		// Unserialize
		if(empty($info)){
			return false;
		}
		
		// If user is suspended
		if($this->orig_user != 'root' && !empty($info['status']) && $info['status'] == 'suspended'){
			return false;
		}
		
		$this->user = $info;
		
		return $info;
		
	}
	
	// Returns the session cookie array that can be passed to webuzo sdk
	function get_session_cookie_array(){
		
		// The first cookie should ALWAYS be the session cookie
		$r[$this->globals['cookie_name'].'_sid'] = $this->SESS['sid'];
		$r['self_sess_key'] = $this->SESS['self_sess_key'];
		
		foreach($_COOKIE as $k => $v){
			$r[$k] = $v;
		}
		
		return $r;
		
	}
	
}
