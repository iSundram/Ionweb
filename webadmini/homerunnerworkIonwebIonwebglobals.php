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

@ini_set('safe_mode', '0');

$globals['docs'] = 'https://webuzo.com/docs/';
$globals['webuzo_user'] = 'webuzo'; // To be made WEBUZO in future
$globals['hf_loaded'] = 0;
$globals['charset'] = 'UTF-8';
$globals['showntimetaken'] = 1;
$globals['ins_num'] = 0; 
$globals['version'] = '4.5.3';
$globals['webuzo_version'] = $globals['version'];
$globals['license'] = (!empty($globals['license']) ? $globals['license'] : '00000-00000-00000-00000-00000');//Dummy License
$globals['mysqlpath'] = '/usr/bin/mysql';
$globals['dirchmod'] = 0755;
$globals['php_bin'] = '/usr/local/emps/bin/php';
$globals['reslen'] = empty($globals['reslen']) ? 50 : $globals['reslen'];
$globals['notupdated_task'] = 1*24*60*60;
$globals['ttl'] = empty($globals['ttl']) ? 14400 : $globals['ttl'];
$globals['pass_score']['default'] = (isset($globals['pass_score']['default']) && is_numeric($globals['pass_score']['default']) ? $globals['pass_score']['default'] : 65);
$globals['dkim_selector'] = empty($globals['dkim_selector']) ? 'default' : $globals['dkim_selector'];
$globals['storage_cal_base'] = empty($globals['storage_cal_base']) ? 1024 : $globals['storage_cal_base'];

// The default path for Softimages
$globals['softimages_path'] = $globals['path'].'/web/enduser/softimages';

// Load the override images mirror (if any)
if(!empty($globals['override_mirror_images'])){
	$globals['mirror_images'] = $globals['override_mirror_images'];
}else{
	$globals['mirror_images'] = 'https://images.softaculous.com/'; // Images URL
}

$globals['sitepad_editor_path'] = empty($globals['sitepad_editor_path']) ? $globals['softscripts'].'/sitepad/editor' : $globals['sitepad_editor_path'];

// This is for extraordinary write permissions like making a Directory Writable
// If no suPHP is detected write permissions would be 0777
// If suPHP is detected we change it to 0755
$globals['odc'] = 0777;//Octal Directory CHMOD
$globals['sdc'] = '0777';//String Directory CHMOD
$globals['ofc'] = 0777;//Octal File CHMOD
$globals['sfc'] = '0777';//String File CHMOD
$globals['ocfc'] = 0644;//Octal Config File CHMOD
$globals['scfc'] = '0644';//String Config File CHMOD
$globals['efc'] = 0755;//Octal Executable File CHMOD
$globals['os'] = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? 'windows' : (strtoupper(substr(PHP_OS, 0, 3)) == 'DAR' ? 'darwin' : 'linux'));

// This is added here because the correct permissions should be loaded when in CLI mode
// Config file CHMOD
if(strlen($globals['chmod_conf_file']) > 0){
	$globals['ocfc'] = octdec($globals['chmod_conf_file']);//Octal
	$globals['scfc'] = $globals['chmod_conf_file'];//String
}

// Is there a manual overide for files
if(strlen($globals['chmod_files']) > 0){
	$globals['ofc'] = octdec($globals['chmod_files']);//Octal
	$globals['sfc'] = $globals['chmod_files'];//String
}

// Is there a manual overide for directories
if(strlen($globals['chmod_dir']) > 0){
	$globals['odc'] = octdec($globals['chmod_dir']);//Octal
	$globals['sdc'] = $globals['chmod_dir'];//String
}

// Disable error reporting if not debug mode
if(empty($globals['debug_mode'])){
	error_reporting(0);
	if(function_exists('ini_set')){
		ini_set('display_errors', 'Off');
		ini_set('error_reporting', 'E_ALL & ~E_NOTICE & ~E_DEPRECATED');
	}
}

if(strtoupper(substr(PHP_OS, 0, 3)) != 'WIN'){
	putenv('PATH=/usr/kerberos/sbin:/usr/kerberos/bin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/root/bin:/usr/local/emps/bin:/usr/local/emps/sbin');
}

// Array of Timezone to map with our previous option which were OFFSET only.
// This chnages we have made to adjuts the timezone with DST
$mapped_timezones = array('-12' => 'Pacific/Kwajalein',
				'-11' => 'Pacific/Pago_Pago',
				'-10' => 'Pacific/Tahiti',
				'-9' => 'Pacific/Gambier',
				'-8' => 'Pacific/Pitcairn',
				'-7' => 'America/Whitehorse',
				'-6' => 'Pacific/Galapagos',
				'-5' => 'Pacific/Easter',
				'-4' => 'America/Tortola',
				'-3.5' => 'America/St_Johns',
				'-3' => 'Atlantic/Stanley',
				'-2' => 'Atlantic/South_Georgia',
				'-1' => 'Atlantic/Cape_Verde',
				'0' => 'UTC',
				'1' => 'Europe/London',
				'2' => 'Europe/Zurich',
				'3' => 'Indian/Mayotte',
				'3.5' => 'Asia/Tehran',
				'4' => 'Indian/Reunion',
				'4.5' => 'Asia/Kabul',
				'5' => 'Indian/Maldives',
				'5.5' => 'Asia/Kolkata',
				'6' => 'Indian/Chagos',
				'6.5' => 'Indian/Cocos',
				'7' => 'Indian/Christmas',
				'8' => 'Australia/Perth',
				'9' => 'Pacific/Palau',
				'9.5' => 'Australia/Darwin',
				'10' => 'Pacific/Saipan',
				'11' => 'Pacific/Pohnpei',
				'12' => 'Pacific/Wallis');

// Possibilities			
// 1. Timezone selected by admin before upgrade - Should take that timezone using Mapper
// 2. Timezone not selected by admin before upgrade i.e. 0
// 		2.1 If PHP timezone set - Use that
// 		2.1 If no PHP timezone set - Use UTC
// 3. Timezone selected by admin after upgrade
$globals['timezone'] = empty($globals['timezone']) ? 'UTC' : $globals['timezone'];

// Update the includes path as per the current PHP version and it respective encoded files

if(version_compare(PHP_VERSION, '7.1.0', '>=') && is_dir($globals['path'].'/includes71')){
	$includes_path = 'includes71';
}elseif(version_compare(PHP_VERSION, '5.6.0', '>=') && is_dir($globals['path'].'/includes56')){
	$includes_path = 'includes56';
}elseif(version_compare(PHP_VERSION, '5.3.0', '>=') && is_dir($globals['path'].'/includes53')){
	$includes_path = 'includes53';
}elseif(is_dir($globals['path'].'/includes52')){
	$includes_path = 'includes52';
}else{
	$includes_path = 'includes';
}

$globals['enduser'] = $globals['path'].'/enduser';

$globals['softscripts'] = '/var/softaculous';
$globals['includes_path'] = $globals['path'].'/'.$includes_path;
$globals['mainfiles'] = $globals['includes_path'];
$globals['adminfiles'] = $globals['includes_path'].'/admin';
$globals['enduserfiles'] = $globals['includes_path'].'/enduser';
$globals['clifiles'] = $globals['includes_path'].'/cli';
$globals['bin'] = $globals['path'].'/bin';
$globals['webfiles'] = $globals['path'].'/web';
$globals['web_enduser'] = $globals['webfiles'].'/enduser';
$globals['web_admin'] = $globals['webfiles'].'/admin';
$globals['euthemes'] = defined('SOFTADMIN') ? $globals['web_admin'].'/themes' : $globals['web_enduser'].'/themes';
$globals['appspath'] = $globals['softscripts'].'/apps';
$globals['plugins_path'] = $globals['path'].'/plugins';
$globals['plugins_list_file'] = '/var/webuzo/plugins.json';
$globals['var_path'] = '/var/webuzo';
$globals['data_path'] = '/var/webuzo-data';
$globals['var_tmp'] = '/usr/local/webuzo/tmp';
$globals['apikey_path'] = '/var/webuzo/apikey';
$globals['plans_path'] = '/var/webuzo/plans';
$globals['users_path'] = '/var/webuzo/users';
$globals['sess_path'] = '/var/webuzo/sessions';
$globals['cache_path'] = '/var/webuzo/cache/';
$globals['backup_path'] = '/var/webuzo/backup';
$globals['dbs_path'] = '/var/webuzo/db';
$globals['home'] = !empty($globals['home']) ? $globals['home'] : '/home';
$globals['panel_user'] = 'webuzo';
$globals['LE_PATH'] = '/var/webuzo/lets_encrypt';
$globals['certs_path'] = '/var/webuzo/certs/';
$globals['logs_path'] = '/var/webuzo/logs';
$globals['features_path'] = '/var/webuzo/features';
$globals['pear_path'] = '/var/webuzo/pear';
$globals['var_conf'] = '/var/webuzo/conf'; // Dir is webuzo owned and can be used by any apps or plugins
$globals['pureftpd_path'] = '/var/webuzo/conf/pureftpd';

if(empty($globals['WU_DEFAULT_MPM'])){
	$globals['WU_DEFAULT_MPM'] = 'prefork';
}

// In cli mode
if(php_sapi_name() === 'cli'){
	$clean_forced_vars = 1;
	$_SERVER['HTTPS'] = 'on';
	$_SERVER['HTTP_HOST'] = $globals['WU_PRIMARY_IP'].':2003';
	$_SERVER['REQUEST_URI'] = '/index.php';
}

// request_url = currently requested URL
$globals['request_url'] = 'http'.(!empty($_SERVER['HTTPS']) ? 's' : '').'://'.str_replace('//', '/', $_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI']);

$globals['current_url'] = $_SERVER['HTTP_HOST'].'/'.parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$globals['current_url'] = 'http'.(!empty($_SERVER['HTTPS']) ? 's' : '').'://'.str_replace('//', '/', $globals['current_url']);

// current_url = http(s)://HOSTNAME:PORT/sessIONxxxxxxxxxx/FILENAME.php? (NO vars)
$globals['current_url'] .= preg_match('/\?/is', $globals['current_url']) ? '' : '?';

// panel_url = http(s)://HOSTNAME:PORT/sessIONxxxxxxxxxx/ (No vars and no filename)
if(preg_match('/sess([\w]*)\//is', $_SERVER['REQUEST_URI'])){
	$globals['panel_url'] = preg_replace('/(.*)(sess([\w]*)\/)(.*)/is', '$1$2', $globals['current_url']);
}else{
	$globals['panel_url'] = preg_replace('/(.*)\/([^\?]*)\?(.*)/is', '$1/', $globals['current_url']);
}

$globals['admin_url'] = str_replace([':2003', ':2002'], [':2005', ':2004'], $globals['panel_url'].'?');
$globals['enduser_url'] = str_replace([':2005', ':2004'], [':2003', ':2002'], $globals['panel_url'].'?');

$parsed_url = parse_url($globals['panel_url']);

// Check if custom admin/enduser ports are set and we are logged in using a custom port
if(!in_array($parsed_url['port'], array('2002', '2003', '2004', '2005')) && (!empty($globals['admin_port_ssl']) || !empty($globals['admin_port_nonssl']) || !empty($globals['enduser_port_ssl']) || !empty($globals['enduser_port_nonssl']))){

	$admin_port_ssl = !empty($globals['admin_port_ssl']) ? ':'.$globals['admin_port_ssl'] : ':2005' ;
	$admin_port_nonssl = !empty($globals['admin_port_nonssl']) ? ':'.$globals['admin_port_nonssl'] : ':2004' ;
	$enduser_port_ssl = !empty($globals['enduser_port_ssl']) ? ':'.$globals['enduser_port_ssl'] : ':2003' ;
	$enduser_port_nonssl = !empty($globals['enduser_port_nonssl']) ? ':'.$globals['enduser_port_nonssl'] : ':2002' ;

	// Need to reconfigure urls
	$globals['admin_url'] = str_replace([$enduser_port_ssl, $enduser_port_nonssl], [$admin_port_ssl, $admin_port_nonssl], $globals['panel_url'].'?');
	$globals['enduser_url'] = str_replace([$admin_port_ssl, $admin_port_nonssl], [$enduser_port_ssl, $enduser_port_nonssl], $globals['panel_url'].'?');
}

// INDEX Files need to be defined as per the Control Panel
$globals['index'] = 'index.php?';
$globals['admin_index'] = 'index.php?';

// Theme Vars - $globals['theme_url'] are loaded by sdk/sessions.php as well. So any changes here has repurcussions everywhere
$globals['theme_folder'] = empty($globals['theme_folder']) ? 'default' : $globals['theme_folder']; // Theme folder
$globals['_theme_url'] = $globals['panel_url'].'themes/';// Without the selected theme folder
$globals['theme_url'] = $globals['panel_url'].'themes/'.$globals['theme_folder'];// With the selected theme folder
$theme['url'] = $globals['theme_url'];
$theme['this_url'] = $globals['theme_url'];
$theme['images'] = $theme['url'].'/images/';//Has a Trailing slash
$globals['runtime_theme_folder'] = $globals['theme_folder'];

//print_r($globals);

if(!empty($clean_forced_vars)){
	unset($_SERVER['HTTPS'], $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
}

// For old compatibility only
// Otherwise default structure will be WU_DEFAULT_GROUPTYPE
$globals['default_app_type_keys']['webserver'] = 'WU_DEFAULT_SERVER';
$globals['mysql_new_method'] = 1;
$globals['list_languages'] = [
    'english' => 'en',
    'hindi' => 'hi',
    'spanish' => 'es',
    'french' => 'fr',
    'german' => 'de',
    'chinese' => 'zh',
    'russian' => 'ru',
    'japanese' => 'ja',
    'portuguese' => 'pt',
    'bulgarian' => 'bg',
    'czech' => 'cs',
    'dutch' => 'nl',
    'hungarian' => 'hu',
    'polish' => 'pl',
    'slovak' => 'sk',
    'italian' => 'it',
    'turkish' => 'tr',
];

define('OPENSSL_BIN', '/usr/local/apps/openssl-30/bin/openssl');
