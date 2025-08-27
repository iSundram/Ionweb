<?php

// Send to login URL
if(empty($webuzo_sess)){
	die('Webuzo Session data missing');
}
	
//echo posix_getuid();
if(posix_getuid() != 0 || $webuzo_sess->user['user'] != 'root'){
	if($webuzo_sess->orig_user != 'root'){
		die('Could not find root session');
	}
}

// if(posix_getuid() == 0 || $u['uid'] !== posix_getuid()){
// Set the uid and gid
	// die('Could not shift to the user level');
// }

// Creating .trash directory
// if(!is_dir($webuzo_sess->user['homedir'].'/.trash/')){
	// mkdir($webuzo_sess->user['homedir'].'/.trash/');
// }

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
function admin_access($attr, $path, $data, $volume, $isDir, $relpath) {
	$basename = basename($path);
	return $basename[0] === '.'                  // if file/folder begins with '.' (dot)
			 && strlen($relpath) !== 1           // but with out volume root
		? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
		:  null;                                 // else elFinder decide it itself
}


function admin_validName($name){
	if(preg_match('/[\/\\\]/is', $name)){
		return false;
	}
	return true;
}

//error_reporting(E_ALL);

// Documentation for connector options:
// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
$opts = array(
	// 'debug' => true,
	'uploadTempPath' => '/root',
	'commonTempPath' => '/root',
	'roots' => array(
		// Items volume
		array(
		
			'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
			'path'          => '/',                 // path to files (REQUIRED)
			//'URL'           => dirname($_SERVER['PHP_SELF']) . '/../files/', // URL to files (REQUIRED)
			// 'trashHash'     => 't1_Lw',                     // elFinder's hash of trash folder
			'statOwner' => true,
			//'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
			'uploadAllow'    => array('all'),                // All Mimetypes not allowed to upload
			//'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'text/plain'), // Mimetype `image` and `text/plain` allowed to upload
			'uploadOrder'   => array('allow', 'deny'),      // allowed Mimetype `image` and `text/plain` only
			// 'accessControl' => 'admin_access',                   // disable and hide dot starting files (OPTIONAL)
			'acceptedName' => 'admin_validName',
			'tmbPath' => '',
			'maxArcFilesSize' => 'unlimited',
		),
	),
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

