<?php
* Simple elFinder driver for FTP
*
* @author Dmitry (dio) Levashov
* @author Cem (discofever)
**/
class elFinderVolumeFTP extends elFinderVolumeDriver
{
* Driver id
* Must be started from letter and contains [a-z0-9]
* Used as part of volume id
*
* @var string
**/
protected $driverId = 'f';
* FTP Connection Instance
*
* @var resource a FTP stream
**/
protected $connect = null;
* Directory for tmp files
* If not set driver will try to use tmbDir as tmpDir
*
* @var string
**/
protected $tmpPath = '';
* Last FTP error message
*
* @var string
**/
protected $ftpError = '';
* FTP server output list as ftp on linux
*
* @var bool
**/
protected $ftpOsUnix;
* FTP LIST command option
*
* @var string
*/
protected $ftpListOption = '-al';
* Is connected server Pure FTPd?
*
* @var bool
*/
protected $isPureFtpd = false;
* Is connected server with FTPS?
*
* @var bool
*/
protected $isFTPS = false;
* Tmp folder path
*
* @var string
**/
protected $tmp = '';
* FTP command `MLST` support
*
* @var bool
*/
private $MLSTsupprt = false;
* Calling cacheDir() target path with non-MLST
*
* @var string
*/
private $cacheDirTarget = '';
* Constructor
* Extend options with required fields
*
* @author Dmitry (dio) Levashov
* @author Cem (DiscoFever)
*/
public function __construct() {
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
}
if (!empty($_REQUEST['FTPS'])) {
$options['ssl'] = true;
}
$options['statOwner'] = true;
$options['allowChmodReadOnly'] = true;
$options['acceptedName'] = '#^[^/\\?*:|"<>]*[^./\\?*:|"<>]$#';
return $options;
}
* Prepare FTP connection
* Connect to remote server and check if credentials are correct, if so, store the connection id in $ftp_conn
*
* @return bool
* @author Dmitry (dio) Levashov
* @author Cem (DiscoFever)
**/
protected function init() {
}
if (!$this->options['user']) {
$this->options['user'] = 'anonymous';
$this->options['pass'] = '';
}
if (!$this->options['path']) {
$this->options['path'] = '/';
}
$this->netMountKey = md5(join('-', array('ftp', $this->options['host'], $this->options['port'], $this->options['path'], $this->options['user'])));
if (!function_exists('ftp_connect')) {
return $this->setError('FTP extension not loaded.');
}
$scheme = parse_url($this->options['host'], PHP_URL_SCHEME);
if ($scheme) {
$this->options['host'] = substr($this->options['host'], strlen($scheme) + 3);
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
if (isset($this->options['ftpListOption'])) {
$this->ftpListOption = $this->options['ftpListOption'];
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
}
if (!$this->tmp && ($tmp = elFinder::getStaticVar('commonTempPath'))) {
$this->tmp = $tmp;
}
if (!$this->tmp && $this->tmbPathWritable) {
$this->tmp = $this->tmbPath;
}
if (!$this->tmp) {
$this->disabled[] = 'mkfile';
$this->disabled[] = 'paste';
$this->disabled[] = 'duplicate';
$this->disabled[] = 'upload';
$this->disabled[] = 'edit';
$this->disabled[] = 'archive';
$this->disabled[] = 'extract';
}
}
* Connect to ftp server
*
* @return bool
* @author Dmitry (dio) Levashov
**/
protected function connect() {
}
$this->isFTPS = true;
} else {
if (!($this->connect = ftp_connect($this->options['host'], $this->options['port'], $this->options['timeout']))) {
return $this->setError('Unable to connect to FTP server ' . $this->options['host']);
}
}
if (!ftp_login($this->connect, $this->options['user'], $this->options['pass'])) {
$this->umount();
return $this->setError('Unable to login into ' . $this->options['host'] . $withSSL);
}
if ($this->encoding) {
ftp_raw($this->connect, 'OPTS UTF8 OFF');
} else {
ftp_raw($this->connect, 'OPTS UTF8 ON');
}
$help = ftp_raw($this->connect, 'HELP');
$this->isPureFtpd = stripos(implode(' ', $help), 'Pure-FTPd') !== false;
if (!$this->isPureFtpd) {
ftp_raw($this->connect, 'epsv4 off');
}
$pasv = ($this->options['mode'] == 'passive');
if (!ftp_pasv($this->connect, $pasv)) {
if ($pasv) {
$this->options['mode'] = 'active';
}
}
if (!ftp_chdir($this->connect, $this->root)
|| $this->root != ftp_pwd($this->connect)) {
$this->umount();
return $this->setError('Unable to open root folder.');
}
$features = ftp_raw($this->connect, 'FEAT');
if (!is_array($features)) {
$this->umount();
return $this->setError('Server does not support command FEAT.');
}
foreach ($features as $feat) {
if (strpos(trim($feat), 'MLST') === 0) {
$this->MLSTsupprt = true;
break;
}
}
return true;
}
* Call ftp_rawlist with option prefix
*
* @param string $path
*
* @return array
*/
protected function ftpRawList() {
}
if ($this->ftpListOption) {
$path = $this->ftpListOption . ' ' . $path;
}
$res = ftp_rawlist($this->connect, $path);
if ($res === false) {
$res = array();
}
return $res;
}
* Close opened connection
*
* @return void
* @author Dmitry (dio) Levashov
**/
public function umount() {
}
* Parse line from ftp_rawlist() output and return file stat (array)
*
* @param  string $raw line from ftp_rawlist() output
* @param         $base
* @param bool    $nameOnly
*
* @return array
* @author Dmitry Levashov
*/
protected function parseRaw() {
}
$info = preg_split("/\s+/", $raw, 8);
if (isset($info[7])) {
list($info[7], $info[8]) = explode(' ', $info[7], 2);
}
$stat = array();
if (!isset($this->ftpOsUnix)) {
$this->ftpOsUnix = !preg_match('/\d/', substr($info[0], 0, 1));
}
if (!$this->ftpOsUnix) {
$info = $this->normalizeRawWindows($raw);
}
if (count($info) < 9 || $info[8] == '.' || $info[8] == '..') {
return false;
}
$name = $info[8];
if (preg_match('|(.+)\-\>(.+)|', $name, $m)) {
$name = trim($m[1]);
if ($this->cacheDirTarget && $this->_joinPath($base, $name) !== $this->cacheDirTarget) {
return array();
}
if (!$nameOnly) {
$target = trim($m[2]);
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
if (is_numeric($info[5]) && !$info[6] && !$info[7]) {
$stat['ts'] = $info[5];
} else {
$stat['ts'] = strtotime($info[5] . ' ' . $info[6] . ' ' . $info[7]);
if ($stat['ts'] && $stat['ts'] > $now && strpos($info[7], ':') !== false) {
$stat['ts'] = strtotime($info[5] . ' ' . $info[6] . ' ' . $lastyear . ' ' . $info[7]);
}
if (empty($stat['ts'])) {
$stat['ts'] = strtotime($info[6] . ' ' . $info[5] . ' ' . $info[7]);
if ($stat['ts'] && $stat['ts'] > $now && strpos($info[7], ':') !== false) {
$stat['ts'] = strtotime($info[6] . ' ' . $info[5] . ' ' . $lastyear . ' ' . $info[7]);
}
}
}
if ($this->options['statOwner']) {
$stat['owner'] = $info[2];
$stat['group'] = $info[3];
$stat['perm'] = substr($info[0], 1);
$stat['isowner'] = isset($stat['owner']) ? ($this->options['owner'] ? true : ($stat['owner'] == $this->options['user'])) : true;
}
$owner_computed = isset($stat['isowner']) ? $stat['isowner'] : $this->options['owner'];
$perm = $this->parsePermissions($info[0], $owner_computed);
$stat['name'] = $name;
$stat['mime'] = substr(strtolower($info[0]), 0, 1) == 'd' ? 'directory' : $this->mimetype($stat['name'], true);
$stat['size'] = $stat['mime'] == 'directory' ? 0 : $info[4];
$stat['read'] = $perm['read'];
$stat['write'] = $perm['write'];
return $stat;
}
* Normalize MS-DOS style FTP LIST Raw line
*
* @param  string $raw line from FTP LIST (MS-DOS style)
*
* @return array
* @author Naoki Sawada
**/
protected function normalizeRawWindows() {
} else {
$info[4] = (int)$size;
$info[0] = '-rw-r--r--';
}
return $info;
}
* Parse permissions string. Return array(read => true/false, write => true/false)
*
* @param  string $perm                        permissions string   'rwx' + 'rwx' + 'rwx'
*                                             ^       ^       ^
*                                             |       |       +->   others
*                                             |       +--------->   group
*                                             +----------------->   owner
*                                             The isowner parameter is computed by the caller.
*                                             If the owner parameter in the options is true, the user is the actual owner of all objects even if che user used in the ftp Login
*                                             is different from the file owner id.
*                                             If the owner parameter is false to understand if the user is the file owner we compare the ftp user with the file owner id.
* @param Boolean $isowner                     . Tell if the current user is the owner of the object.
*
* @return array
* @author Dmitry (dio) Levashov
* @author Ugo Vierucci
*/
protected function parsePermissions() {
}
$read = ($isowner && $parts[1] == 'r') || $parts[4] == 'r' || $parts[7] == 'r';
return array(
'read' => $parts[0] == 'd' ? $read && (($isowner && $parts[3] == 'x') || $parts[6] == 'x' || $parts[9] == 'x') : $read,
'write' => ($isowner && $parts[2] == 'w') || $parts[5] == 'w' || $parts[8] == 'w'
);
}
* Cache dir contents
*
* @param  string $path dir path
*
* @return void
* @author Dmitry Levashov
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
* Return ftp transfer mode for file
*
* @param  string $path file path
*
* @return string
* @author Dmitry (dio) Levashov
**/
protected function ftpMode() {
}
* Return parent directory path
*
* @param  string $path file path
*
* @return string
* @author Naoki Sawada
**/
protected function _dirname() {
}
* Return file name
*
* @param  string $path file path
*
* @return string
* @author Naoki Sawada
**/
protected function _basename() {
}
* Join dir name and file name and retur full path
*
* @param  string $dir
* @param  string $name
*
* @return string
* @author Dmitry (dio) Levashov
**/
protected function _joinPath() {
}
* Return normalized path, this works the same as os.path.normpath() in Python
*
* @param  string $path path
*
* @return string
* @author Troex Nevelin
**/
protected function _normpath() {
}
$path = preg_replace('|^\.\/?|', $this->separator, $path);
$path = preg_replace('/^([^\/])/', "/$1", $path);
if ($path[0] === $this->separator) {
$initial_slashes = true;
} else {
$initial_slashes = false;
}
if (($initial_slashes)
&& (strpos($path, '//') === 0)
&& (strpos($path, '///') === false)) {
$initial_slashes = 2;
}
$initial_slashes = (int)$initial_slashes;
$comps = explode($this->separator, $path);
$new_comps = array();
foreach ($comps as $comp) {
if (in_array($comp, array('', '.'))) {
continue;
}
if (($comp != '..')
|| (!$initial_slashes && !$new_comps)
|| ($new_comps && (end($new_comps) == '..'))) {
array_push($new_comps, $comp);
} elseif ($new_comps) {
array_pop($new_comps);
}
}
$comps = $new_comps;
$path = implode($this->separator, $comps);
if ($initial_slashes) {
$path = str_repeat($this->separator, $initial_slashes) . $path;
}
return $path ? $path : '.';
}
* Return file path related to root dir
*
* @param  string $path file path
*
* @return string
* @author Dmitry (dio) Levashov
**/
protected function _relpath() {
} else {
if (strpos($path, $this->root) === 0) {
return ltrim(substr($path, strlen($this->root)), $this->separator);
} else {
return $path;
}
}
}
* Convert path related to root dir into real path
*
* @param  string $path file path
*
* @return string
* @author Dmitry (dio) Levashov
**/
protected function _abspath() {
} else {
if ($path[0] === $this->separator) {
return $path;
} else {
return $this->_joinPath($this->root, $path);
}
}
}
* Return fake path started from root dir
*
* @param  string $path file path
*
* @return string
* @author Dmitry (dio) Levashov
**/
protected function _path() {
}
* Return true if $path is children of $parent
*
* @param  string $path   path to check
* @param  string $parent parent path
*
* @return bool
* @author Dmitry (dio) Levashov
**/
protected function _inpath() {
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
if (!$this->MLSTsupprt) {
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
foreach ($this->ftpRawList($path) as $str) {
$info = preg_split('/\s+/', $str, 9);
if ($info[8] === '.') {
$info[8] = 'root';
if ($stat = $this->parseRaw(join(' ', $info), $path)) {
unset($stat['name']);
$res = array_merge($res, $stat);
if ($res['ts']) {
$ts = 0;
unset($check['ts']);
}
}
}
if ($check && ($stat = $this->parseRaw($str, $path))) {
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
$raw = ftp_raw($this->connect, 'MLST ' . $path);
if (is_array($raw) && count($raw) > 1 && substr(trim($raw[0]), 0, 1) == 2) {
$parts = explode(';', trim($raw[1]));
array_pop($parts);
$parts = array_map('strtolower', $parts);
$stat = array();
$mode = '';
foreach ($parts as $part) {
list($key, $val) = explode('=', $part, 2);
switch ($key) {
case 'type':
if (strpos($val, 'dir') !== false) {
$stat['mime'] = 'directory';
} else if (strpos($val, 'link') !== false) {
$stat['mime'] = 'symlink';
break(2);
} else {
$stat['mime'] = $this->mimetype($path);
}
break;
case 'size':
$stat['size'] = $val;
break;
case 'modify':
$ts = mktime(intval(substr($val, 8, 2)), intval(substr($val, 10, 2)), intval(substr($val, 12, 2)), intval(substr($val, 4, 2)), intval(substr($val, 6, 2)), substr($val, 0, 4));
$stat['ts'] = $ts;
break;
case 'unix.mode':
$mode = strval($val);
break;
case 'unix.uid':
$stat['owner'] = $val;
break;
case 'unix.gid':
$stat['group'] = $val;
break;
case 'perm':
$val = strtolower($val);
$stat['read'] = (int)preg_match('/e|l|r/', $val);
$stat['write'] = (int)preg_match('/w|m|c/', $val);
if (!preg_match('/f|d/', $val)) {
$stat['locked'] = 1;
}
break;
}
}
if (empty($stat['mime'])) {
return array();
}
if ($stat['mime'] === 'symlink') {
$this->MLSTsupprt = false;
$res = $this->_stat($path);
$this->MLSTsupprt = true;
return $res;
}
if ($stat['mime'] === 'directory') {
$stat['size'] = 0;
}
if ($mode) {
$stat['perm'] = '';
if ($mode[0] === '0') {
$mode = substr($mode, 1);
}
$perm = array();
for ($i = 0; $i <= 2; $i++) {
$perm[$i] = array(false, false, false);
$n = isset($mode[$i]) ? $mode[$i] : 0;
if ($n - 4 >= 0) {
$perm[$i][0] = true;
$n = $n - 4;
$stat['perm'] .= 'r';
} else {
$stat['perm'] .= '-';
}
if ($n - 2 >= 0) {
$perm[$i][1] = true;
$n = $n - 2;
$stat['perm'] .= 'w';
} else {
$stat['perm'] .= '-';
}
if ($n - 1 == 0) {
$perm[$i][2] = true;
$stat['perm'] .= 'x';
} else {
$stat['perm'] .= '-';
}
}
$stat['perm'] = trim($stat['perm']);
$owner_computed = isset($stat['owner']) ? ($this->options['owner'] ? true : ($stat['owner'] == $this->options['user'])) : true;
$read = ($owner_computed && $perm[0][0]) || $perm[1][0] || $perm[2][0];
$stat['read'] = $stat['mime'] == 'directory' ? $read && (($owner_computed && $perm[0][2]) || $perm[1][2] || $perm[2][2]) : $read;
$stat['write'] = ($owner_computed && $perm[0][1]) || $perm[1][1] || $perm[2][1];
if ($this->options['statOwner']) {
$stat['isowner'] = $owner_computed;
} else {
unset($stat['owner'], $stat['group'], $stat['perm']);
}
}
return $stat;
}
return array();
}
* Return true if path is dir and has at least one childs directory
*
* @param  string $path dir path
*
* @return bool
* @author Dmitry (dio) Levashov
**/
protected function _subdirs() {
}
if (!$this->ftpOsUnix) {
$info = $this->normalizeRawWindows($str);
}
$name = isset($info[8]) ? trim($info[8]) : '';
if ($name && $name !== '.' && $name !== '..' && substr(strtolower($info[0]), 0, 1) === 'd') {
return true;
}
}
return false;
}
* Return object width and height
* Ususaly used for images, but can be realize for video etc...
*
* @param  string $path file path
* @param  string $mime file mime type
*
* @return string|false
* @throws ImagickException
* @throws elFinderAbortException
* @author Dmitry (dio) Levashov
*/
protected function _dimensions() {
}
}
return $ret;
}
* Return files list in directory.
*
* @param  string $path dir path
*
* @return array
* @author Dmitry (dio) Levashov
* @author Cem (DiscoFever)
**/
protected function _scandir() {
}
}
return $files;
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
} else {
$fp = fopen($url, $mode);
}
if ($fp) {
return $fp;
}
}
if ($this->tmp) {
$local = $this->getTempFile($path);
$fp = fopen($local, 'wb');
$ret = ftp_nb_fget($this->connect, $fp, $path, FTP_BINARY);
while ($ret === FTP_MOREDATA) {
elFinder::extendTimeLimit();
$ret = ftp_nb_continue($this->connect);
}
if ($ret === FTP_FINISHED) {
fclose($fp);
$fp = fopen($local, $mode);
return $fp;
}
fclose($fp);
is_file($local) && unlink($local);
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
$this->options['dirMode'] && ftp_chmod($this->connect, $this->options['dirMode'], $path);
return $path;
}
* Create file and return it's path or false on failed
*
* @param  string $path parent dir path
* @param string  $name new file name
*
* @return string|bool
* @author Dmitry (dio) Levashov
**/
protected function _mkfile() {
}
return false;
}
* Create symlink. FTP driver does not support symlinks.
*
* @param  string $target link target
* @param  string $path   symlink path
* @param string  $name
*
* @return bool
* @author Dmitry (dio) Levashov
*/
protected function _symlink() {
}
* Copy file into another file
*
* @param  string $source    source file path
* @param  string $targetDir target directory path
* @param  string $name      new file name
*
* @return bool
* @author Dmitry (dio) Levashov
**/
protected function _copy() {
}
unlink($local);
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
$this->_fclose($fp, $path);
return $contents;
}
return false;
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
clearstatcache();
$res = ftp_fput($this->connect, $path, $fp, $this->ftpMode($path));
fclose($fp);
}
file_exists($local) && unlink($local);
}
return $res;
}
* Detect available archivers
*
* @return void
* @throws elFinderAbortException
*/
protected function _checkArchivers() {
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
$basename = $this->_basename($path);
$localPath = $dir . DIRECTORY_SEPARATOR . $basename;
if (!ftp_get($this->connect, $localPath, $path, FTP_BINARY)) {
$this->rmdirRecursive($dir);
return false;
}
$this->unpackArchive($localPath, $arc);
$this->archiveSize = 0;
$checkRes = $this->checkExtractItems($dir);
if ($checkRes['symlinks']) {
$this->rmdirRecursive($dir);
return $this->setError(array_merge($this->error, array(elFinder::ERROR_ARC_SYMLINKS)));
}
$this->archiveSize = $checkRes['totalSize'];
if ($checkRes['rmNames']) {
foreach ($checkRes['rmNames'] as $name) {
$this->addError(elFinder::ERROR_SAVE, $name);
}
}
$filesToProcess = self::listFilesInDirectory($dir, true);
if (empty($filesToProcess)) {
$this->rmdirRecursive($dir);
return false;
}
if ($this->options['maxArcFilesSize'] > 0 && $this->options['maxArcFilesSize'] < $this->archiveSize) {
$this->rmdirRecursive($dir);
return $this->setError(elFinder::ERROR_ARC_MAXSIZE);
}
$extractTo = $this->extractToNewdir; // 'auto', ture or false
$name = '';
$src = $dir . DIRECTORY_SEPARATOR . $filesToProcess[0];
if (($extractTo === 'auto' || !$extractTo) && count($filesToProcess) === 1 && is_file($src)) {
$name = $filesToProcess[0];
} else if ($extractTo === 'auto' || $extractTo) {
$src = $dir;
$splits = elFinder::splitFileExtention(basename($path));
$name = $splits[0];
$test = $this->_joinPath(dirname($path), $name);
if ($this->stat($test)) {
$name = $this->uniqueName(dirname($path), $name, '-', false);
}
}
if ($name !== '' && is_file($src)) {
$result = $this->_joinPath(dirname($path), $name);
if (!ftp_put($this->connect, $result, $src, FTP_BINARY)) {
$this->rmdirRecursive($dir);
return false;
}
} else {
$dstDir = $this->_dirname($path);
$result = array();
if (is_dir($src) && $name) {
$target = $this->_joinPath($dstDir, $name);
$_stat = $this->_stat($target);
if ($_stat) {
if (!$this->options['copyJoin']) {
if ($_stat['mime'] === 'directory') {
$this->delTree($target);
} else {
$this->_unlink($target);
}
$_stat = false;
} else {
$dstDir = $target;
}
}
if (!$_stat && (!$dstDir = $this->_mkdir($dstDir, $name))) {
$this->rmdirRecursive($dir);
return false;
}
$result[] = $dstDir;
}
foreach ($filesToProcess as $name) {
$name = rtrim($name, DIRECTORY_SEPARATOR);
$src = $dir . DIRECTORY_SEPARATOR . $name;
if (is_dir($src)) {
$p = dirname($name);
if ($p === '.') {
$p = '';
}
$name = basename($name);
$target = $this->_joinPath($this->_joinPath($dstDir, $p), $name);
$_stat = $this->_stat($target);
if ($_stat) {
if (!$this->options['copyJoin']) {
if ($_stat['mime'] === 'directory') {
$this->delTree($target);
} else {
$this->_unlink($target);
}
$_stat = false;
}
}
if (!$_stat && (!$target = $this->_mkdir($this->_joinPath($dstDir, $p), $name))) {
$this->rmdirRecursive($dir);
return false;
}
} else {
$target = $this->_joinPath($dstDir, $name);
if (!ftp_put($this->connect, $target, $src, FTP_BINARY)) {
$this->rmdirRecursive($dir);
return false;
}
}
$result[] = $target;
}
if (!$result) {
$this->rmdirRecursive($dir);
return false;
}
}
is_dir($dir) && $this->rmdirRecursive($dir);
$this->clearcache();
return $result ? $result : false;
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
if (!$this->ftp_download_files($dir, $files, $tmpDir)) {
$this->rmdirRecursive($tmpDir);
return false;
}
$remoteArchiveFile = false;
if ($path = $this->makeArchive($tmpDir, $files, $name, $arc)) {
$remoteArchiveFile = $this->_joinPath($dir, $name);
if (!ftp_put($this->connect, $remoteArchiveFile, $path, FTP_BINARY)) {
$remoteArchiveFile = false;
}
}
if (!$this->rmdirRecursive($tmpDir)) {
return false;
}
return $remoteArchiveFile;
}
* Create writable temporary directory and return path to it.
*
* @return string path to the new temporary directory or false in case of error.
*/
private function tempDir() {
}
$success = unlink($tempPath);
if (!$success) {
$this->setError(elFinder::ERROR_CREATING_TEMP_DIR, $this->tmp);
return false;
}
$success = mkdir($tempPath, 0700, true);
if (!$success) {
$this->setError(elFinder::ERROR_CREATING_TEMP_DIR, $this->tmp);
return false;
}
return $tempPath;
}
* Gets an array of absolute remote FTP paths of files and
* folders in $remote_directory omitting symbolic links.
*
* @param $remote_directory string remote FTP path to scan for file and folders recursively
* @param $targets          array  Array of target item. `null` is to get all of items
*
* @return array of elements each of which is an array of two elements:
* <ul>
* <li>$item['path'] - absolute remote FTP path</li>
* <li>$item['type'] - either 'f' for file or 'd' for directory</li>
* </ul>
*/
protected function ftp_scan_dir() {
} else {
$targets = false;
}
foreach ($buff as $str) {
$info = preg_split("/\s+/", $str, 9);
if (!isset($this->ftpOsUnix)) {
$this->ftpOsUnix = !preg_match('/\d/', substr($info[0], 0, 1));
}
if (!$this->ftpOsUnix) {
$info = $this->normalizeRawWindows($str);
}
$type = substr($info[0], 0, 1);
$name = trim($info[8]);
if ($name !== '.' && $name !== '..' && (!$targets || isset($targets[$name]))) {
switch ($type) {
case 'l' : //omit symbolic links
case 'd' :
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
* Downloads specified files from remote directory
* if there is a directory among files it is downloaded recursively (omitting symbolic links).
*
* @param       $remote_directory     string remote FTP path to a source directory to download from.
* @param array $files                list of files to download from remote directory.
* @param       $dest_local_directory string destination folder to store downloaded files.
*
* @return bool true on success and false on failure.
*/
private function ftp_download_files() {
}
$remoteDirLen = strlen($remote_directory);
foreach ($contents as $item) {
$relative_path = substr($item['path'], $remoteDirLen);
$local_path = $dest_local_directory . DIRECTORY_SEPARATOR . $relative_path;
switch ($item['type']) {
case 'd':
$success = mkdir($local_path);
break;
case 'f':
$success = ftp_get($this->connect, $local_path, $item['path'], FTP_BINARY);
break;
default:
$success = true;
}
if (!$success) {
$this->setError(elFinder::ERROR_FTP_DOWNLOAD_FILE, $remote_directory);
return false;
}
}
return true;
}
* Delete local directory recursively.
*
* @param $dirPath string to directory to be erased.
*
* @return bool true on success and false on failure.
* @throws Exception
*/
private function deleteDir() {
} else {
$success = true;
foreach (array_reverse(elFinderVolumeFTP::listFilesInDirectory($dirPath, false)) as $path) {
$path = $dirPath . DIRECTORY_SEPARATOR . $path;
if (is_link($path)) {
unlink($path);
} else if (is_dir($path)) {
$success = rmdir($path);
} else {
$success = unlink($path);
}
if (!$success) {
break;
}
}
if ($success) {
$success = rmdir($dirPath);
}
}
if (!$success) {
$this->setError(elFinder::ERROR_RM, $dirPath);
return false;
}
return $success;
}
* Returns array of strings containing all files and folders in the specified local directory.
*
* @param        $dir
* @param        $omitSymlinks
* @param string $prefix
*
* @return array array of files and folders names relative to the $path
* or an empty array if the directory $path is empty,
* <br />
* false if $path is not a directory or does not exist.
* @throws Exception
* @internal param string $path path to directory to scan.
*/
private static function listFilesInDirectory() {
}
$excludes = array(".", "..");
$result = array();
$files = self::localScandir($dir);
if (!$files) {
return array();
}
foreach ($files as $file) {
if (!in_array($file, $excludes)) {
$path = $dir . DIRECTORY_SEPARATOR . $file;
if (is_link($path)) {
if ($omitSymlinks) {
continue;
} else {
$result[] = $prefix . $file;
}
} else if (is_dir($path)) {
$result[] = $prefix . $file . DIRECTORY_SEPARATOR;
$subs = elFinderVolumeFTP::listFilesInDirectory($path, $omitSymlinks, $prefix . $file . DIRECTORY_SEPARATOR);
if ($subs) {
$result = array_merge($result, $subs);
}
} else {
$result[] = $prefix . $file;
}
}
}
return $result;
}
} // END class