<?php
* Simple elFinder driver for MySQL.
*
* @author Dmitry (dio) Levashov
**/
class elFinderVolumeMySQL extends elFinderVolumeDriver
{
* Driver id
* Must be started from letter and contains [a-z0-9]
* Used as part of volume id
*
* @var string
**/
protected $driverId = 'm';
* Database object
*
* @var mysqli
**/
protected $db = null;
* Tables to store files
*
* @var string
**/
protected $tbf = '';
* Directory for tmp files
* If not set driver will try to use tmbDir as tmpDir
*
* @var string
**/
protected $tmpPath = '';
* Numbers of sql requests (for debug)
*
* @var int
**/
protected $sqlCnt = 0;
* Last db error message
*
* @var string
**/
protected $dbError = '';
* This root has parent id
*
* @var        boolean
*/
protected $rootHasParent = false;
* Constructor
* Extend options with required fields
*
* @author Dmitry (dio) Levashov
*/
public function __construct() {
}
* Prepare driver before mount volume.
* Connect to db, check required tables and fetch root path
*
* @return bool
* @author Dmitry (dio) Levashov
**/
protected function init() {
}
$err = null;
if ($this->db = @new mysqli($this->options['host'], $this->options['user'], $this->options['pass'], $this->options['db'], $this->options['port'], $this->options['socket'])) {
if ($this->db && $this->db->connect_error) {
$err = $this->db->connect_error;
}
} else {
$err = mysqli_connect_error();
}
if ($err) {
return $this->setError(array('Unable to connect to MySQL server.', $err));
}
if (!$this->needOnline && empty($this->ARGS['init'])) {
$this->db->close();
$this->db = null;
return true;
}
$this->db->set_charset('utf8');
if ($res = $this->db->query('SHOW TABLES')) {
while ($row = $res->fetch_array()) {
if ($row[0] == $this->options['files_table']) {
$this->tbf = $this->options['files_table'];
break;
}
}
}
if (!$this->tbf) {
return $this->setError('The specified database table cannot be found.');
}
$this->updateCache($this->options['path'], $this->_stat($this->options['path']));
$this->options['useRemoteArchive'] = true;
$this->isLocalhost = $this->options['isLocalhost'] || $this->options['host'] === 'localhost' || $this->options['host'] === '127.0.0.1' || $this->options['host'] === '::1';
return true;
}
* Set tmp path
*
* @return void
* @throws elFinderAbortException
* @author Dmitry (dio) Levashov
*/
protected function configure() {
}
}
$this->tmpPath = is_dir($tmp) && is_writable($tmp) ? $tmp : false;
}
if (!$this->tmpPath && ($tmp = elFinder::getStaticVar('commonTempPath'))) {
$this->tmpPath = $tmp;
}
if (!$this->tmpPath && $this->tmbPathWritable) {
$this->tmpPath = $this->tmbPath;
}
$this->mimeDetect = 'internal';
}
* Close connection
*
* @return void
* @author Dmitry (dio) Levashov
**/
public function umount() {
}
* Return debug info for client
*
* @return array
* @author Dmitry (dio) Levashov
**/
public function debug() {
}
return $debug;
}
* Perform sql query and return result.
* Increase sqlCnt and save error if occured
*
* @param  string $sql query
*
* @return bool|mysqli_result
* @author Dmitry (dio) Levashov
*/
protected function query() {
}
return $res;
}
* Perform sql prepared statement and return result.
* Increase sqlCnt and save error if occurred.
*
* @param mysqli_stmt $stmt
* @return bool
*/
protected function execute() {
}
return $res;
}
* Create empty object with required mimetype
*
* @param  string $path parent dir path
* @param  string $name object name
* @param  string $mime mime type
*
* @return bool
* @author Dmitry (dio) Levashov
**/
protected function make() {
}
* Cache dir contents
*
* @param  string $path dir path
*
* @return string
* @author Dmitry Levashov
**/
protected function cacheDir() {
}
if ($row['mime'] == 'directory') {
unset($row['width']);
unset($row['height']);
$row['size'] = 0;
} else {
unset($row['dirs']);
}
unset($row['id']);
unset($row['parent_id']);
if (($stat = $this->updateCache($id, $row)) && empty($stat['hidden'])) {
$this->dirsCache[$path][] = $id;
}
}
}
return $this->dirsCache[$path];
}
* Return array of parents paths (ids)
*
* @param  int $path file path (id)
*
* @return array
* @author Dmitry (dio) Levashov
**/
protected function getParents() {
}
}
if (count($parents)) {
array_pop($parents);
}
return $parents;
}
* Return correct file path for LOAD_FILE method
*
* @param  string $path file path (id)
*
* @return string
* @author Troex Nevelin
**/
protected function loadFilePath() {
}
return $this->db->real_escape_string($realPath);
}
* Recursive files search
*
* @param  string $path dir path
* @param  string $q    search string
* @param  array  $mimes
*
* @return array
* @throws elFinderAbortException
* @author Dmitry (dio) Levashov
*/
protected function doSearch() {
}
$dirs = array();
$timeout = $this->options['searchTimeout'] ? $this->searchStart + $this->options['searchTimeout'] : 0;
if ($path != $this->root || $this->rootHasParent) {
$dirs = $inpath = array(intval($path));
while ($inpath) {
$in = '(' . join(',', $inpath) . ')';
$inpath = array();
$sql = 'SELECT f.id FROM %s AS f WHERE f.parent_id IN ' . $in . ' AND `mime` = \'directory\'';
$sql = sprintf($sql, $this->tbf);
if ($res = $this->query($sql)) {
$_dir = array();
while ($dat = $res->fetch_assoc()) {
$inpath[] = $dat['id'];
}
$dirs = array_merge($dirs, $inpath);
}
}
}
$result = array();
if ($mimes) {
$whrs = array();
foreach ($mimes as $mime) {
if (strpos($mime, '/') === false) {
$whrs[] = sprintf('f.mime LIKE \'%s/%%\'', $this->db->real_escape_string($mime));
} else {
$whrs[] = sprintf('f.mime = \'%s\'', $this->db->real_escape_string($mime));
}
}
$whr = join(' OR ', $whrs);
} else {
$whr = sprintf('f.name LIKE \'%%%s%%\'', $this->db->real_escape_string($q));
}
if ($dirs) {
$whr = '(' . $whr . ') AND (`parent_id` IN (' . join(',', $dirs) . '))';
}
$sql = 'SELECT f.id, f.parent_id, f.name, f.size, f.mtime AS ts, f.mime, f.read, f.write, f.locked, f.hidden, f.width, f.height, 0 AS dirs
FROM %s AS f
WHERE %s';
$sql = sprintf($sql, $this->tbf, $whr);
if (($res = $this->query($sql))) {
while ($row = $res->fetch_assoc()) {
if ($timeout && $timeout < time()) {
$this->setError(elFinder::ERROR_SEARCH_TIMEOUT, $this->path($this->encode($path)));
break;
}
if (!$this->mimeAccepted($row['mime'], $mimes)) {
continue;
}
$id = $row['id'];
if ($id == $this->root) {
continue;
}
if ($row['parent_id'] && $id != $this->root) {
$row['phash'] = $this->encode($row['parent_id']);
}
$row['path'] = $this->_path($id);
if ($row['mime'] == 'directory') {
unset($row['width']);
unset($row['height']);
} else {
unset($row['dirs']);
}
unset($row['id']);
unset($row['parent_id']);
if (($stat = $this->updateCache($id, $row)) && empty($stat['hidden'])) {
$result[] = $stat;
}
}
}
return $result;
}
* Return parent directory path
*
* @param  string $path file path
*
* @return string
* @author Dmitry (dio) Levashov
**/
protected function _dirname() {
}
* Return file name
*
* @param  string $path file path
*
* @return string
* @author Dmitry (dio) Levashov
**/
protected function _basename() {
}
* Join dir name and file name and return full path
*
* @param  string $dir
* @param  string $name
*
* @return string
* @author Dmitry (dio) Levashov
**/
protected function _joinPath() {
}
return -1;
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
* Return file path related to root dir
*
* @param  string $path file path
*
* @return string
* @author Dmitry (dio) Levashov
**/
protected function _relpath() {
}
* Convert path related to root dir into real path
*
* @param  string $path file path
*
* @return string
* @author Dmitry (dio) Levashov
**/
protected function _abspath() {
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
$parentsIds = $this->getParents($path);
$path = '';
foreach ($parentsIds as $id) {
$dir = $this->stat($id);
$path .= $dir['name'] . $this->separator;
}
return $path . $file['name'];
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
}
if ($stat['parent_id']) {
$stat['phash'] = $this->encode($stat['parent_id']);
}
if ($stat['mime'] == 'directory') {
unset($stat['width']);
unset($stat['height']);
$stat['size'] = 0;
} else {
if (!$stat['mime']) {
unset($stat['mime']);
}
unset($stat['dirs']);
}
unset($stat['id']);
unset($stat['parent_id']);
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
* Return object width and height
* Usualy used for images, but can be realize for video etc...
*
* @param  string $path file path
* @param  string $mime file mime type
*
* @return string
* @author Dmitry (dio) Levashov
**/
protected function _dimensions() {
}
* Return files list in directory.
*
* @param  string $path dir path
*
* @return array
* @author Dmitry (dio) Levashov
**/
protected function _scandir() {
}
* Open file and return file pointer
*
* @param  string $path file path
* @param  string $mode open file mode (ignored in this driver)
*
* @return resource|false
* @author Dmitry (dio) Levashov
**/
protected function _fopen() {
} else {
$this->_fclose($fp, $path);
}
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
* undocumented function
*
* @param $path
* @param $fp
*
* @author Dmitry Levashov
*/
protected function _setContent() {
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
} else {
$size = $stat['size'];
}
if ($this->isLocalhost && ($tmpfile = tempnam($this->tmpPath, $this->id))) {
if (($trgfp = fopen($tmpfile, 'wb')) == false) {
unlink($tmpfile);
} else {
elFinder::rewind($fp);
stream_copy_to_stream($fp, $trgfp);
fclose($trgfp);
chmod($tmpfile, 0644);
$sql = $id > 0
? 'REPLACE INTO %s (id, parent_id, name, content, size, mtime, mime, width, height) VALUES (' . $id . ', ?, ?, LOAD_FILE(?), ?, ?, ?, ?, ?)'
: 'INSERT INTO %s (parent_id, name, content, size, mtime, mime, width, height) VALUES (?, ?, LOAD_FILE(?), ?, ?, ?, ?, ?)';
$stmt = $this->db->prepare(sprintf($sql, $this->tbf));
$path = $this->loadFilePath($tmpfile);
$stmt->bind_param("issiisii", $dir, $name, $path, $size, $ts, $mime, $w, $h);
$res = $this->execute($stmt);
unlink($tmpfile);
if ($res) {
return $id > 0 ? $id : $this->db->insert_id;
}
}
}
$content = '';
elFinder::rewind($fp);
while (!feof($fp)) {
$content .= fread($fp, 8192);
}
$sql = $id > 0
? 'REPLACE INTO %s (id, parent_id, name, content, size, mtime, mime, width, height) VALUES (' . $id . ', ?, ?, ?, ?, ?, ?, ?, ?)'
: 'INSERT INTO %s (parent_id, name, content, size, mtime, mime, width, height) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
$stmt = $this->db->prepare(sprintf($sql, $this->tbf));
$stmt->bind_param("issiisii", $dir, $name, $content, $size, $ts, $mime, $w, $h);
unset($content);
if ($this->execute($stmt)) {
return $id > 0 ? $id : $this->db->insert_id;
}
return false;
}
* Get file contents
*
* @param  string $path file path
*
* @return string|false
* @author Dmitry (dio) Levashov
**/
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
* Detect available archivers
*
* @return void
**/
protected function _checkArchivers() {
}
* chmod implementation
*
* @param string $path
* @param string $mode
*
* @return bool
*/
protected function _chmod() {
}
* Unpack archive
*
* @param  string $path archive path
* @param  array  $arc  archiver command and arguments (same as in $this->archivers)
*
* @return void
* @author Dmitry (dio) Levashov
* @author Alexey Sukhotin
**/
protected function _unpack() {
}
* Extract files from archive
*
* @param  string $path archive path
* @param  array  $arc  archiver command and arguments (same as in $this->archivers)
*
* @return true
* @author Dmitry (dio) Levashov,
* @author Alexey Sukhotin
**/
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
* @author Dmitry (dio) Levashov,
* @author Alexey Sukhotin
**/
protected function _archive() {
}
} // END class