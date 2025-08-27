<?php
error_reporting(0); // Set E_ALL for debuging
include_once('/usr/local/webuzo/sdk/sessions.php');
$webuzo_sess = new Webuzo_Sessions();
$logged_in = $webuzo_sess->isLogin();
if(empty($logged_in)){
$webuzo_sess->show_login();
}
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
if(posix_getuid() == 0){
$u = posix_getpwnam($webuzo_sess->user['user']);
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
if(posix_getuid() == 0 || $u['uid'] !== posix_getuid()){
die('Could not shift to the user level');
}
if(!is_dir($webuzo_sess->user['homedir'].'/.trash/')){
mkdir($webuzo_sess->user['homedir'].'/.trash/');
}
is_readable('./vendor/autoload.php') && require './vendor/autoload.php';
require './php/autoload.php';
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
function access() {
}
function validName() {
}
return true;
}
$opts = array(
'uploadTempPath' => $webuzo_sess->user['homedir'],
'commonTempPath' => $webuzo_sess->user['homedir'],
'maxTargets' => 0, // 0 means unlimited
'roots' => array(
array(
'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
'path'          => $webuzo_sess->user['homedir'],                 // path to files (REQUIRED)
'trashHash'     => 't1_Lw',                     // elFinder's hash of trash folder
'statOwner' => true,
'uploadAllow'    => array('all'),                // All Mimetypes not allowed to upload
'uploadOrder'   => array('allow', 'deny'),      // allowed Mimetype `image` and `text/plain` only
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
array(
'id'            => '1',
'driver'        => 'Trash',
'path'          => $webuzo_sess->user['homedir'].'/.trash/',
'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
'uploadAllow'    => array('all'),
'uploadOrder'   => array('deny', 'allow'),  	 // Same as above
'tmbPath' => '',
'tmbPathMode' => 0700,
'acceptedName' => 'validName',
),
),
);
if(!file_put_contents($webuzo_sess->user['homedir'].'/.webuzo_file_manager', 1)){
$opts['commonTempPath'] = '/dev/shm';
unset($opts['roots'][0]['trashHash']);
unset($opts['roots'][0]['uploadAllow']);
$opts['roots'][0]['uploadDeny'] = array('ALL');
unset($opts['roots'][1]['uploadAllow']);
$opts['roots'][1]['uploadDeny'] = array('ALL');
}
unlink($webuzo_sess->user['homedir'].'/.webuzo_file_manager');
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();