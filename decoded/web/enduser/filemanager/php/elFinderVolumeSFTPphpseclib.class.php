<?php
* Simple elFinder driver for SFTP using phpseclib 1
*
* @author Dmitry (dio) Levashov
* @author Cem (discofever), sitecode
* @reference http://phpseclib.sourceforge.net/sftp/2.0/examples.html
**/
class elFinderVolumeSFTPphpseclib extends elFinderVolumeFTP {
* Constructor
* Extend options with required fields
*
* @author Dmitry (dio) Levashov
* @author Cem (DiscoFever)
*/
public function __construct() {
},
'checkSubfolders' => -1,
'dirMode' => 0755,
'fileMode' => 0644,
'rootCssClass' => 'elfinder-navbar-root-ftp',
);
$this->options = array_merge($this->options, $opts);
$this->options['mimeDetect'] = 'internal';
}
* Prepare
* Call from elFinder::netmout() before volume->mount()
*
* @param $options
*
* @return array volume root options
* @author Naoki Sawada
*/
public function netmountPrepare() {
}
* Prepare SFTP connection
* Connect to remote server and check if credentials are correct, if so, store the connection
*
* @return bool
* @author Dmitry (dio) Levashov
* @author Cem (DiscoFever)
**/
protected function init() {
}
if (!$this->options['path']) {
$this->options['path'] = '/';
}
$this->netMountKey = md5(join('-', array('sftpphpseclib', $this->options['host'], $this->options['port'], $this->options['path'], $this->options['user'])));
set_include_path(get_include_path() . PATH_SEPARATOR . getcwd().'/'.$this->options['phpseclibDir']);
include_once('Net/SFTP.php');
if (!class_exists('Net_SFTP')) {
return $this->setError('SFTP extension not loaded. Install phpseclib version 1: http://phpseclib.sourceforge.net/ Set option "phpseclibDir" accordingly.');
}
$scheme = parse_url($this->options['host'], PHP_URL_SCHEME);
if ($scheme) {
$this->options['host'] = substr($this->options['host'], strlen($scheme) + 3);
}
} else {
$this->netMountKey = md5(join('-', array('sftpphpseclib', $this->options['path'])));
}
$this->root = $this->options['path'] = $this->_normpath($this->options['path']);
if (empty($this->options['alias'])) {
$this->options['alias'] = $this->options['user'] . '@' . $this->options['host'];
if (!empty($this->options['netkey'])) {
elFinder::$instance->updateNetVolumeOption($this->options['netkey'], 'alias', $this->options['alias']);
}
}
$this->rootName = $this->options['alias'];
$this->options['separator'] = '/';
if (is_null($this->options['syncChkAsTs'])) {
$this->options['syncChkAsTs'] = true;
}
return $this->needOnline? $this->connect() : true;
}
* Configure after successfull mount.
*
* @return void
* @throws elFinderAbortException
* @author Dmitry (dio) Levashov
*/
protected function configure() {
}
$this->disabled[] = 'archive';
$this->disabled[] = 'extract';
}
* Connect to sftp server
*
* @return bool
* @author sitecode
**/
protected function connect() {
}
return true;
}
try{
$host = $this->options['host'] . ($this->options['port'] != 22 ? ':' . $this->options['port'] : '');
$this->connect = new Net_SFTP($host);
if (!$this->connect->login($this->options['user'], $this->options['pass'])) {
return $this->setError('Unable to connect to SFTP server ' . $host);
}
} catch (Exception $e) {
return $this->setError('Error while connecting to SFTP server '  . $host . ': ' . $e->getMessage());
}
if (!$this->connect->chdir($this->root)
return $this->setError('Unable to open root folder.');
}
return true;
}
* Call rawlist
*
* @param string $path
*
* @return array
*/
protected function ftpRawList() {
}
* Close opened connection
*
* @return void
* @author Dmitry (dio) Levashov
**/
public function umount() {
}
* Parse line from rawlist() output and return file stat (array)
*
* @param array $info from rawlist() output
* @param         $base
* @param bool    $nameOnly
*
* @return array
* @author Dmitry Levashov
*/
protected function parseRaw() {
}
$name = $info['filename'];
if ($info['type'] === 3) {
if ($this->cacheDirTarget && $this->_joinPath($base, $name) !== $this->cacheDirTarget) {
return array();
}
if (!$nameOnly) {
$target = $this->connect->readlink($name);
if (substr($target, 0, 1) !== $this->separator) {
$target = $this->getFullPath($target, $base);
}
$target = $this->_normpath($target);
$stat['name'] = $name;
$stat['target'] = $target;
return $stat;
}
}
if ($nameOnly) {
return array('name' => $name);
}
$stat['ts'] = $info['mtime'];
if ($this->options['statOwner']) {
$stat['owner'] = $info['uid'];
$stat['group'] = $info['gid'];
$stat['perm'] = $info['permissions'];
$stat['isowner'] = isset($stat['owner']) ? ($this->options['owner'] ? true : ($stat['owner'] == $this->options['user'])) : true;
}
$owner_computed = isset($stat['isowner']) ? $stat['isowner'] : $this->options['owner'];
$perm = $this->parsePermissions($info['permissions'], $owner_computed);
$stat['name'] = $name;
if ($info['type'] === NET_SFTP_TYPE_DIRECTORY) {
$stat['mime'] = 'directory';
$stat['size'] = 0;
} elseif ($info['type'] === NET_SFTP_TYPE_SYMLINK) {
$stat['mime'] = 'symlink';
$stat['size'] = 0;
} else {
$stat['mime'] = $this->mimetype($stat['name'], true);
$stat['size'] = $info['size'];
}
$stat['read'] = $perm['read'];
$stat['write'] = $perm['write'];
return $stat;
}
* Parse permissions string. Return array(read => true/false, write => true/false)
*
* @param  int $perm
*                                             The isowner parameter is computed by the caller.
*                                             If the owner parameter in the options is true, the user is the actual owner of all objects even if the user used in the ftp Login
*                                             is different from the file owner id.
*                                             If the owner parameter is false to understand if the user is the file owner we compare the ftp user with the file owner id.
* @param Boolean $isowner                     . Tell if the current user is the owner of the object.
*
* @return array
* @author Dmitry (dio) Levashov
* @author sitecode
*/
protected function parsePermissions() {
}
* Cache dir contents
*
* @param  string $path dir path
*
* @return void
* @author Dmitry Levashov, sitecode
**/
protected function cacheDir() {
}
}
$list = $this->convEncOut($list);
$prefix = ($path === $this->separator) ? $this->separator : $path . $this->separator;
$targets = array();
foreach ($list as $stat) {
$p = $prefix . $stat['name'];
if (isset($stat['target'])) {
$targets[$stat['name']] = $stat['target'];
} else {
$stat = $this->updateCache($p, $stat);
if (empty($stat['hidden'])) {
if (!$hasDir && $stat['mime'] === 'directory') {
$hasDir = true;
} elseif (!$hasDir && $stat['mime'] === 'symlink') {
$hasDir = true;
}
$this->dirsCache[$path][] = $p;
}
}
}
foreach ($targets as $name => $target) {
$stat = array();
$stat['name'] = $name;
$p = $prefix . $name;
$cacheDirTarget = $this->cacheDirTarget;
$this->cacheDirTarget = $this->convEncIn($target, true);
if ($tstat = $this->stat($target)) {
$stat['size'] = $tstat['size'];
$stat['alias'] = $target;
$stat['thash'] = $tstat['hash'];
$stat['mime'] = $tstat['mime'];
$stat['read'] = $tstat['read'];
$stat['write'] = $tstat['write'];
if (isset($tstat['ts'])) {
$stat['ts'] = $tstat['ts'];
}
if (isset($tstat['owner'])) {
$stat['owner'] = $tstat['owner'];
}
if (isset($tstat['group'])) {
$stat['group'] = $tstat['group'];
}
if (isset($tstat['perm'])) {
$stat['perm'] = $tstat['perm'];
}
if (isset($tstat['isowner'])) {
$stat['isowner'] = $tstat['isowner'];
}
} else {
$stat['mime'] = 'symlink-broken';
$stat['read'] = false;
$stat['write'] = false;
$stat['size'] = 0;
}
$this->cacheDirTarget = $cacheDirTarget;
$stat = $this->updateCache($p, $stat);
if (empty($stat['hidden'])) {
if (!$hasDir && $stat['mime'] === 'directory') {
$hasDir = true;
}
$this->dirsCache[$path][] = $p;
}
}
if (isset($this->sessionCache['subdirs'])) {
$this->sessionCache['subdirs'][$path] = $hasDir;
}
}
* Return stat for given path.
* Stat contains following fields:
* - (int)    size    file size in b. required
* - (int)    ts      file modification time in unix time. required
* - (string) mime    mimetype. required for folders, others - optionally
* - (bool)   read    read permissions. required
* - (bool)   write   write permissions. required
* - (bool)   locked  is object locked. optionally
* - (bool)   hidden  is object hidden. optionally
* - (string) alias   for symlinks - link target path relative to root path. optionally
* - (string) target  for symlinks - link target path. optionally
* If file does not exists - returns empty array or false.
*
* @param  string $path file path
*
* @return array|false
* @author Dmitry (dio) Levashov
**/
protected function _stat() {
} else {
$this->convEncIn();
}
if ($path === $this->root) {
$res = array(
'name' => $this->root,
'mime' => 'directory',
'dirs' => -1
);
if ($this->needOnline && (($this->ARGS['cmd'] === 'open' && $this->ARGS['target'] === $this->encode($this->root)) || $this->isMyReload())) {
$check = array(
'ts' => true,
'dirs' => true,
);
$ts = 0;
foreach ($this->ftpRawList($path) as $info) {
if ($info['filename'] === '.') {
$info['filename'] = 'root';
if ($stat = $this->parseRaw($info, $path)) {
unset($stat['name']);
$res = array_merge($res, $stat);
if ($res['ts']) {
$ts = 0;
unset($check['ts']);
}
}
}
if ($check && ($stat = $this->parseRaw($info, $path))) {
if (isset($stat['ts']) && !empty($stat['ts'])) {
$ts = max($ts, $stat['ts']);
}
if (isset($stat['dirs']) && $stat['mime'] === 'directory') {
$res['dirs'] = 1;
unset($stat['dirs']);
}
if (!$check) {
break;
}
}
}
if ($ts) {
$res['ts'] = $ts;
}
$this->cache[$outPath] = $res;
}
return $res;
}
$pPath = $this->_dirname($path);
if ($this->_inPath($pPath, $this->root)) {
$outPPpath = $this->convEncOut($pPath);
if (!isset($this->dirsCache[$outPPpath])) {
$parentSubdirs = null;
if (isset($this->sessionCache['subdirs']) && isset($this->sessionCache['subdirs'][$outPPpath])) {
$parentSubdirs = $this->sessionCache['subdirs'][$outPPpath];
}
$this->cacheDir($outPPpath);
if ($parentSubdirs) {
$this->sessionCache['subdirs'][$outPPpath] = $parentSubdirs;
}
}
}
$stat = $this->convEncIn(isset($this->cache[$outPath]) ? $this->cache[$outPath] : array());
if (!$this->mounted) {
$this->cache = array();
}
return $stat;
}
* Return true if path is dir and has at least one childs directory
*
* @param  string $path dir path
*
* @return bool
* @author Dmitry (dio) Levashov, sitecode
**/
protected function _subdirs() {
}
if ($name && $name !== '.' && $name !== '..' && $info['type'] == NET_SFTP_TYPE_SYMLINK) {
}
}
return false;
}
* Open file and return file pointer
*
* @param  string $path file path
* @param string  $mode
*
* @return false|resource
* @throws elFinderAbortException
* @internal param bool $write open file for writing
* @author   Dmitry (dio) Levashov
*/
protected function _fopen() {
}
return false;
}
* Close opened file
*
* @param  resource $fp file pointer
* @param string    $path
*
* @return void
* @author Dmitry (dio) Levashov
*/
protected function _fclose() {
}
}
* Create dir and return created dir path or false on failed
*
* @param  string $path parent dir path
* @param string  $name new directory name
*
* @return string|bool
* @author Dmitry (dio) Levashov
**/
protected function _mkdir() {
}
$this->options['dirMode'] && $this->connect->chmod($this->options['dirMode'], $path);
return $path;
}
* Create file and return it's path or false on failed
*
* @param  string $path parent dir path
* @param string  $name new file name
*
* @return string|bool
* @author sitecode
**/
protected function _mkfile() {
}
return false;
*/
}
* Copy file into another file
*
* @param  string $source    source file path
* @param  string $targetDir target directory path
* @param  string $name      new file name
*
* @return bool
* @author Dmitry (dio) Levashov, sitecode
**/
protected function _copy() {
}
unlink($local);
} else {
$res = $this->_filePutContents($target, $this->_getContents($source));
}
return $res;
}
* Move file into another parent dir.
* Return new file path or false.
*
* @param  string $source source file path
* @param         $targetDir
* @param  string $name   file name
*
* @return bool|string
* @internal param string $target target dir path
* @author   Dmitry (dio) Levashov
*/
protected function _move() {
}
* Remove file
*
* @param  string $path file path
*
* @return bool
* @author Dmitry (dio) Levashov
**/
protected function _unlink() {
}
* Remove dir
*
* @param  string $path dir path
*
* @return bool
* @author Dmitry (dio) Levashov
**/
protected function _rmdir() {
}
* Create new file and write into it from file pointer.
* Return new file path or false on error.
*
* @param  resource $fp   file pointer
* @param  string   $dir  target dir path
* @param  string   $name file name
* @param  array    $stat file stat (required by some virtual fs)
*
* @return bool|string
* @author Dmitry (dio) Levashov
**/
protected function _save() {
}
* Get file contents
*
* @param  string $path file path
*
* @return string|false
* @throws elFinderAbortException
* @author Dmitry (dio) Levashov
*/
protected function _getContents() {
}
* Write a string to a file
*
* @param  string $path    file path
* @param  string $content new file content
*
* @return bool
* @author Dmitry (dio) Levashov
**/
protected function _filePutContents() {
}
* chmod availability
*
* @param string $path
* @param string $mode
*
* @return bool
*/
protected function _chmod() {
}
* Extract files from archive
*
* @param  string $path archive path
* @param  array  $arc  archiver command and arguments (same as in $this->archivers)
*
* @return true
* @throws elFinderAbortException
* @author Dmitry (dio) Levashov,
* @author Alexey Sukhotin
*/
protected function _extract() {
}
* Create archive and return its path
*
* @param  string $dir   target dir
* @param  array  $files files names list
* @param  string $name  archive name
* @param  array  $arc   archiver options
*
* @return string|bool
* @throws elFinderAbortException
* @author Dmitry (dio) Levashov,
* @author Alexey Sukhotin
*/
protected function _archive() {
}
* Gets an array of absolute remote SFTP paths of files and
* folders in $remote_directory omitting symbolic links.
*
* @param $remote_directory string remote SFTP path to scan for file and folders recursively
* @param $targets          array  Array of target item. `null` is to get all of items
*
* @return array of elements each of which is an array of two elements:
* <ul>
* <li>$item['path'] - absolute remote SFTP path</li>
* <li>$item['type'] - either 'f' for file or 'd' for directory</li>
* </ul>
*/
protected function ftp_scan_dir() {
} else {
$targets = false;
}
foreach ($buff as $info) {
$name = $info['filename'];
if ($name !== '.' && $name !== '..' && (!$targets || isset($targets[$name]))) {
switch ($info['type']) {
case NET_SFTP_TYPE_SYMLINK : //omit symbolic links
case NET_SFTP_TYPE_DIRECTORY :
$remote_file_path = $this->_joinPath($remote_directory, $name);
$item = array();
$item['path'] = $remote_file_path;
$item['type'] = 'd'; // normal file
$items[] = $item;
$items = array_merge($items, $this->ftp_scan_dir($remote_file_path));
break;
default:
$remote_file_path = $this->_joinPath($remote_directory, $name);
$item = array();
$item['path'] = $remote_file_path;
$item['type'] = 'f'; // normal file
$items[] = $item;
}
}
}
return $items;
}
} // END class