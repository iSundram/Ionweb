<?php
if(empty($webuzo_sess)){
die('Webuzo Session data missing');
}
if(posix_getuid() != 0 || $webuzo_sess->user['user'] != 'root'){
if($webuzo_sess->orig_user != 'root'){
die('Could not find root session');
}
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
function admin_access() {
}
function admin_validName() {
}
return true;
}
$opts = array(
'uploadTempPath' => '/root',
'commonTempPath' => '/root',
'roots' => array(
array(
'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
'path'          => '/',                 // path to files (REQUIRED)
'statOwner' => true,
'uploadAllow'    => array('all'),                // All Mimetypes not allowed to upload
'uploadOrder'   => array('allow', 'deny'),      // allowed Mimetype `image` and `text/plain` only
'acceptedName' => 'admin_validName',
'tmbPath' => '',
'maxArcFilesSize' => 'unlimited',
),
),
);
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();