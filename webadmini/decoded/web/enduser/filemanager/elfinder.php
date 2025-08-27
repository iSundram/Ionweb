<?php

error_reporting(0); // Set E_ALL for debuging

// Include our sessions file
include_once('/usr/local/webuzo/sdk/sessions.php');

$webuzo_sess = new Webuzo_Sessions();
$logged_in = $webuzo_sess->isLogin();
//print_r($webuzo_sess);

// Send to login URL
if(empty($logged_in)){
	$webuzo_sess->show_login();
}

// Check if it is from admin panel
$admin_ports = [2004, 2005];

if(!empty($webuzo_sess->globals['admin_port_ssl'])){
	$admin_ports[] = $webuzo_sess->globals['admin_port_ssl'];
}
if(!empty($webuzo_sess->globals['admin_port_nonssl'])){
	$admin_ports[] = $webuzo_sess->globals['admin_port_nonssl'];
}

if(in_array($_SERVER['SERVER_PORT'], $admin_ports)){
	include_once(__DIR__ .'/elfinder_admin.php');
	exit(0);
}

//echo posix_getuid();
if(posix_getuid() == 0){
	$u = posix_getpwnam($webuzo_sess->user['user']);
	//r_print($u);

	// Are there any files ?
	if(!empty($_FILES)){
		
		foreach($_FILES as $k => $v){
			
			if(empty($v['tmp_name'])){
				continue;
			}
			
			$r = is_array($v['tmp_name']) ? $v['tmp_name'] : array($v['tmp_name']);
			
			foreach($r as $kk => $vv){
				if(file_exists($vv)){
					chown($vv, $u['uid']);
					chgrp($vv, $u['gid']);
				}
			}
			
		}
		
	}
	
	posix_setgid($u['gid']);
	posix_setuid($u['uid']);
}

// Set the uid and gid
if(posix_getuid() == 0 || $u['uid'] !== posix_getuid()){
	die('Could not shift to the user level');
}

// Creating .trash directory
if(!is_dir($webuzo_sess->user['homedir'].'/.trash/')){
	mkdir($webuzo_sess->user['homedir'].'/.trash/');
}

// // Optional exec path settings (Default is called with command name only)
// define('ELFINDER_TAR_PATH',      '/PATH/TO/tar');
// define('ELFINDER_GZIP_PATH',     '/PATH/TO/gzip');
// define('ELFINDER_BZIP2_PATH',    '/PATH/TO/bzip2');
// define('ELFINDER_XZ_PATH',       '/PATH/TO/xz');
// define('ELFINDER_ZIP_PATH',      '/PATH/TO/zip');
// define('ELFINDER_UNZIP_PATH',    '/PATH/TO/unzip');
// define('ELFINDER_RAR_PATH',      '/PATH/TO/rar');
// define('ELFINDER_UNRAR_PATH',    '/PATH/TO/unrar');
// define('ELFINDER_7Z_PATH',       '/PATH/TO/7za');
// define('ELFINDER_CONVERT_PATH',  '/PATH/TO/convert');
// define('ELFINDER_IDENTIFY_PATH', '/PATH/TO/identify');
// define('ELFINDER_EXIFTRAN_PATH', '/PATH/TO/exiftran');
// define('ELFINDER_JPEGTRAN_PATH', '/PATH/TO/jpegtran');
// define('ELFINDER_FFMPEG_PATH',   '/PATH/TO/ffmpeg');

// define('ELFINDER_CONNECTOR_URL', 'URL to this connector script');  // see elFinder::getConnectorUrl()

// define('ELFINDER_DEBUG_ERRORLEVEL', -1); // Error reporting level of debug mode

// // load composer autoload before load elFinder autoload If you need composer
// // You need to run the composer command in the php directory.
is_readable('./vendor/autoload.php') && require './vendor/autoload.php';

// // elFinder autoload
require './php/autoload.php';
// ===============================================

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from '.' (dot)
 *
 * @param  string    $attr    attribute name (read|write|locked|hidden)
 * @param  string    $path    absolute file path
 * @param  string    $data    value of volume option `accessControlData`
 * @param  object    $volume  elFinder volume driver object
 * @param  bool|null $isDir   path is directory (true: directory, false: file, null: unknown)
 * @param  string    $relpath file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume, $isDir, $relpath) {
	$basename = basename($path);
	return $basename[0] === '.'                  // if file/folder begins with '.' (dot)
			 && strlen($relpath) !== 1           // but with out volume root
		? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
		:  null;                                 // else elFinder decide it itself
}

function validName($name){
	if(preg_match('/[\/\\\]/is', $name)){
		return false;
	}
	return true;
}

//error_reporting(E_ALL);

// Documentation for connector options:
// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
$opts = array(
	//'debug' => true,
	'uploadTempPath' => $webuzo_sess->user['homedir'],
	'commonTempPath' => $webuzo_sess->user['homedir'],
	'maxTargets' => 0, // 0 means unlimited
	'roots' => array(
		// Items volume
		array(
			'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
			'path'          => $webuzo_sess->user['homedir'],                 // path to files (REQUIRED)
			//'URL'           => dirname($_SERVER['PHP_SELF']) . '/../files/', // URL to files (REQUIRED)
			'trashHash'     => 't1_Lw',                     // elFinder's hash of trash folder
			'statOwner' => true,
			//'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
			'uploadAllow'    => array('all'),                // All Mimetypes not allowed to upload
			//'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'text/plain'), // Mimetype `image` and `text/plain` allowed to upload
			'uploadOrder'   => array('allow', 'deny'),      // allowed Mimetype `image` and `text/plain` only
			//'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
			'acceptedName' => 'validName',
			'tmbPath' => '',
			'tmbPathMode' => 0700,
			'maxArcFilesSize' => 'unlimited',
			'check_archive_symlinks' => 0,			// Custome Webuzo Setting
			'attributes'    => array(
				array(
					'pattern' => '/^\/\.(trash)$/',
					'hidden'  => true,
				),
			),
		),
		
		// Trash volume
		array(
			'id'            => '1',
			'driver'        => 'Trash',
			'path'          => $webuzo_sess->user['homedir'].'/.trash/',
			'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
			// 'uploadDeny'    => array('all'),             // Recomend the same settings as the original volume that uses the trash
			'uploadAllow'    => array('all'),			
			// 'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'text/plain'),					// Same as above
			'uploadOrder'   => array('deny', 'allow'),  	 // Same as above
			// 'accessControl' => 'access',           		 // Same as above
			'tmbPath' => '',
			'tmbPathMode' => 0700,
			'acceptedName' => 'validName',
		),
	),
);


if(!file_put_contents($webuzo_sess->user['homedir'].'/.webuzo_file_manager', 1)){
	$opts['commonTempPath'] = '/dev/shm';
	unset($opts['roots'][0]['trashHash']);	
	
	// If Inodes or disk is reached then we will deny upload for all kind of files
	unset($opts['roots'][0]['uploadAllow']);	
	$opts['roots'][0]['uploadDeny'] = array('ALL');
	
	unset($opts['roots'][1]['uploadAllow']);	
	$opts['roots'][1]['uploadDeny'] = array('ALL');
}

unlink($webuzo_sess->user['homedir'].'/.webuzo_file_manager');

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

