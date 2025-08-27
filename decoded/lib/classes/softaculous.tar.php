<?php
* File::CSV
*
* PHP versions 4 and 5
*
* Copyright (c) 1997-2008,
* Vincent Blavet <vincent@phpconcept.net>
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*
*     * Redistributions of source code must retain the above copyright notice,
*       this list of conditions and the following disclaimer.
*     * Redistributions in binary form must reproduce the above copyright
*       notice, this list of conditions and the following disclaimer in the
*       documentation and/or other materials provided with the distribution.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
* AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
* IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
* DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE
* FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
* DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
* SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
* CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
* OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
* OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
* @category  File_Formats
* @package   Archive_Tar
* @author    Vincent Blavet <vincent@phpconcept.net>
* @copyright 1997-2010 The Authors
* @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
* @version   CVS: $Id: Tar.php 324840 2012-04-05 08:44:41Z mrook $
* @link      http://pear.php.net/package/Archive_Tar
*/
define('ARCHIVE_TAR_ATT_SEPARATOR', 90001);
define('ARCHIVE_TAR_END_BLOCK', pack("a512", ''));
* Creates a (compressed) Tar archive
*
* @package Archive_Tar
* @author  Vincent Blavet <vincent@phpconcept.net>
* @license http://www.opensource.org/licenses/bsd-license.php New BSD License
* @version $Revision: 324840 $
*/
class softtar
{
* @var string Name of the Tar
*/
var $_tarname='';
* @var string In case of FTP / Dropbox / etc, we will write locally and after hitting 10MB upload it
*/
var $_local_tar=''; // The local file
var $_orig_tar=''; // The remote file
var $remote_fp=''; // The remote file pointer
var $remote_fp_filter = NULL;
var $remote_hctx = NULL;
var $remote_content_size = 0;
* @var boolean if true, the Tar file will be gzipped
*/
var $_compress=false;
* @var string Type of compression : 'none', 'gz' or 'bz2'
*/
var $_compress_type='none';
* @var string Explode separator
*/
var $_separator=',';
* @var file descriptor
*/
var $_file=0;
* @var string Local Tar name of a remote Tar (http:// or ftp://)
*/
var $_temp_tarname='';
* @var string regular expression for ignoring files or directories
*/
var $_ignore_regexp='';
* @var object PEAR_Error object
*/
var $error_object=null;
function softtar() {
}
* Archive_Tar Class constructor. This flavour of the constructor only
* declare a new Archive_Tar object, identifying it by the name of the
* tar file.
* If the compress argument is set the tar will be read or created as a
* gzip or bz2 compressed TAR file.
*
* @param string $p_tarname  The name of the tar archive to create
* @param string $p_compress can be null, 'gz' or 'bz2'. This
*               parameter indicates if gzip or bz2 compression
*               is required.  For compatibility reason the
*               boolean value 'true' means 'gz'.
*
* @access public
*/
function __construct() {
}
$this->_compress = false;
$this->_compress_type = 'none';
if (($p_compress === null) || ($p_compress == '')) {
if (@file_exists($p_tarname)) {
if ($fp = @fopen($p_tarname, "rb")) {
$data = fread($fp, 2);
fclose($fp);
if ($data == "\37\213") {
$this->_compress = true;
$this->_compress_type = 'gz';
} elseif ($data == "BZ") {
$this->_compress = true;
$this->_compress_type = 'bz2';
}
}
} else {
if (substr($p_tarname, -2) == 'gz') {
$this->_compress = true;
$this->_compress_type = 'gz';
} elseif ((substr($p_tarname, -3) == 'bz2') ||
(substr($p_tarname, -2) == 'bz')) {
$this->_compress = true;
$this->_compress_type = 'bz2';
}
}
} else {
if (($p_compress === true) || ($p_compress == 'gz')) {
$this->_compress = true;
$this->_compress_type = 'gz';
} else if ($p_compress == 'bz2') {
$this->_compress = true;
$this->_compress_type = 'bz2';
} else {
$this->_error("Unsupported compression type '$p_compress'\n".
"Supported types are 'gz' and 'bz2'.\n");
return false;
}
}
$this->_tarname = $p_tarname;
if ($this->_compress) { // assert zlib or bz2 extension support
if ($this->_compress_type == 'gz')
$extname = 'zlib';
else if ($this->_compress_type == 'bz2')
$extname = 'bz2';
if (!extension_loaded($extname)) {
PEAR::loadExtension($extname);
}
if (!extension_loaded($extname)) {
$this->_error("The extension '$extname' couldn't be found.\n".
"Please make sure your version of PHP was built ".
"with '$extname' support.\n");
return false;
}
}
}
function _softtar() {
}
}
function __destruct() {
}
* This method creates the archive file and add the files / directories
* that are listed in $p_filelist.
* If a file with the same name exist and is writable, it is replaced
* by the new tar.
* The method return false and a PEAR error text.
* The $p_filelist parameter can be an array of string, each string
* representing a filename or a directory name with their path if
* needed. It can also be a single string with names separated by a
* single blank.
* For each directory added in the archive, the files and
* sub-directories are also added.
* See also createModify() method for more details.
*
* @param array $p_filelist An array of filenames and directory names, or a
*              single string with names separated by a single
*              blank space.
*
* @return true on success, false on error.
* @see    createModify()
* @access public
*/
function create() {
}
* This method add the files / directories that are listed in $p_filelist in
* the archive. If the archive does not exist it is created.
* The method return false and a PEAR error text.
* The files and directories listed are only added at the end of the archive,
* even if a file with the same name is already archived.
* See also createModify() method for more details.
*
* @param array $p_filelist An array of filenames and directory names, or a
*              single string with names separated by a single
*              blank space.
*
* @return true on success, false on error.
* @see    createModify()
* @access public
*/
function add() {
}
function extract() {
}
function listContent() {
}
$this->_close();
}
return $v_list_detail;
}
* This method creates the archive file and add the files / directories
* that are listed in $p_filelist.
* If the file already exists and is writable, it is replaced by the
* new tar. It is a create and not an add. If the file exists and is
* read-only or is a directory it is not replaced. The method return
* false and a PEAR error text.
* The $p_filelist parameter can be an array of string, each string
* representing a filename or a directory name with their path if
* needed. It can also be a single string with names separated by a
* single blank.
* The path indicated in $p_remove_dir will be removed from the
* memorized path of each file / directory listed when this path
* exists. By default nothing is removed (empty path '')
* The path indicated in $p_add_dir will be added at the beginning of
* the memorized path of each file / directory listed. However it can
* be set to empty ''. The adding of a path is done after the removing
* of path.
* The path add/remove ability enables the user to prepare an archive
* for extraction in a different path than the origin files are.
* See also addModify() method for file adding properties.
*
* @param array  $p_filelist   An array of filenames and directory names,
*                             or a single string with names separated by
*                             a single blank space.
* @param string $p_add_dir    A string which contains a path to be added
*                             to the memorized path of each element in
*                             the list.
* @param string $p_remove_dir A string which contains a path to be
*                             removed from the memorized path of each
*                             element in the list, when relevant.
*
* @return boolean true on success, false on error.
* @access public
* @see addModify()
*/
function createModify() {
}
$v_result = $this->_addList($v_list, $p_add_dir, $p_remove_dir);
}
if ($v_result) {
$this->_writeFooter();
$this->_close();
} else
$this->_cleanFile();
return $v_result;
}
* This method add the files / directories listed in $p_filelist at the
* end of the existing archive. If the archive does not yet exists it
* is created.
* The $p_filelist parameter can be an array of string, each string
* representing a filename or a directory name with their path if
* needed. It can also be a single string with names separated by a
* single blank.
* The path indicated in $p_remove_dir will be removed from the
* memorized path of each file / directory listed when this path
* exists. By default nothing is removed (empty path '')
* The path indicated in $p_add_dir will be added at the beginning of
* the memorized path of each file / directory listed. However it can
* be set to empty ''. The adding of a path is done after the removing
* of path.
* The path add/remove ability enables the user to prepare an archive
* for extraction in a different path than the origin files are.
* If a file/dir is already in the archive it will only be added at the
* end of the archive. There is no update of the existing archived
* file/dir. However while extracting the archive, the last file will
* replace the first one. This results in a none optimization of the
* archive size.
* If a file/dir does not exist the file/dir is ignored. However an
* error text is send to PEAR error.
* If a file/dir is not readable the file/dir is ignored. However an
* error text is send to PEAR error.
*
* @param array  $p_filelist   An array of filenames and directory
*                             names, or a single string with names
*                             separated by a single blank space.
* @param string $p_add_dir    A string which contains a path to be
*                             added to the memorized path of each
*                             element in the list.
* @param string $p_remove_dir A string which contains a path to be
*                             removed from the memorized path of
*                             each element in the list, when
*                             relevant.
*
* @return true on success, false on error.
* @access public
*/
function addModify() {
}
$v_result = $this->_append($v_list, $p_add_dir, $p_remove_dir);
}
return $v_result;
}
* This method add a single string as a file at the
* end of the existing archive. If the archive does not yet exists it
* is created.
*
* @param string $p_filename A string which contains the full
*                           filename path that will be associated
*                           with the string.
* @param string $p_string   The content of the file added in
*                           the archive.
*
* @return true on success, false on error.
* @access public
*/
function addString() {
}
$this->_close();
}
if (!$this->_openAppend())
return false;
$v_result = $this->_addString($p_filename, $p_string);
$this->_writeFooter();
$this->_close();
return $v_result;
}
* This method extract all the content of the archive in the directory
* indicated by $p_path. When relevant the memorized path of the
* files/dir can be modified by removing the $p_remove_path path at the
* beginning of the file/dir path.
* While extracting a file, if the directory path does not exists it is
* created.
* While extracting a file, if the file already exists it is replaced
* without looking for last modification date.
* While extracting a file, if the file already exists and is write
* protected, the extraction is aborted.
* While extracting a file, if a directory with the same name already
* exists, the extraction is aborted.
* While extracting a directory, if a file with the same name already
* exists, the extraction is aborted.
* While extracting a file/directory if the destination directory exist
* and is write protected, or does not exist but can not be created,
* the extraction is aborted.
* If after extraction an extracted file does not show the correct
* stored file size, the extraction is aborted.
* When the extraction is aborted, a PEAR error text is set and false
* is returned. However the result can be a partial extraction that may
* need to be manually cleaned.
*
* @param string  $p_path        The path of the directory where the
*                               files/dir need to by extracted.
* @param string  $p_remove_path Part of the memorized path that can be
*                               removed if present at the beginning of
*                               the file/dir path.
* @param boolean $p_preserve    Preserve user/group ownership of files
*
* @return boolean true on success, false on error.
* @access public
* @see    extractList()
*/
function extractModify() {
}
if ($v_result = $this->_openRead()) {
$v_result = $this->_extractList($p_path, $v_list_detail,
"complete", 0, $p_remove_path, $p_preserve);
$this->_close();
}
return $v_result;
}
function remote_archive_download() {
}
clearstatcache();
if(substr($this->_orig_tar, -2) == 'gz'){
$this->_compress = true;
$this->_compress_type = 'gz';
}
$url = parse_url($this->_orig_tar);
if(method_exists($url['scheme'], 'download_file')){
$obj = new $url['scheme'];
$obj->download_file($this->_orig_tar, $this->_local_tar);
}else{
$this->remote_fp = @fopen($this->_orig_tar, "rb");
$fp = @fopen($this->_local_tar, "wb");
while(!feof($this->remote_fp)){
$block = @fread($this->remote_fp, 8192);
@fwrite($fp, $block);
}
@fclose($this->remote_fp);
@fclose($fp);
}
}
* This method extract from the archive one file identified by $p_filename.
* The return value is a string with the file content, or NULL on error.
*
* @param string $p_filename The path of the file to extract in a string.
*
* @return a string with the file content or NULL.
* @access public
*/
function extractInString() {
} else {
$v_result = null;
}
return $v_result;
}
* This method extract from the archive only the files indicated in the
* $p_filelist. These files are extracted in the current directory or
* in the directory indicated by the optional $p_path parameter.
* If indicated the $p_remove_path can be used in the same way as it is
* used in extractModify() method.
*
* @param array   $p_filelist    An array of filenames and directory names,
*                               or a single string with names separated
*                               by a single blank space.
* @param string  $p_path        The path of the directory where the
*                               files/dir need to by extracted.
* @param string  $p_remove_path Part of the memorized path that can be
*                               removed if present at the beginning of
*                               the file/dir path.
* @param boolean $p_preserve    Preserve user/group ownership of files
*
* @return true on success, false on error.
* @access public
* @see    extractModify()
*/
function extractList() {
}
if(!empty($this->_orig_tar)){
$this->remote_archive_download();
}
if ($v_result = $this->_openRead()) {
$v_result = $this->_extractList($p_path, $v_list_detail, "partial",
$v_list, $p_remove_path, $p_preserve);
$this->_close();
}
return $v_result;
}
* This method set specific attributes of the archive. It uses a variable
* list of parameters, in the format attribute code + attribute values :
* $arch->setAttribute(ARCHIVE_TAR_ATT_SEPARATOR, ',');
*
* @param mixed $argv variable list of attributes and values
*
* @return true on success, false on error.
* @access public
*/
function setAttribute() {
}
$v_att_list = func_get_args();
$i=0;
while ($i<$v_size) {
switch ($v_att_list[$i]) {
case ARCHIVE_TAR_ATT_SEPARATOR :
if (($i+1) >= $v_size) {
$this->_error('Invalid number of parameters for '
.'attribute ARCHIVE_TAR_ATT_SEPARATOR');
return false;
}
$this->_separator = $v_att_list[$i+1];
$i++;
break;
default :
$this->_error('Unknow attribute code '.$v_att_list[$i].'');
return false;
}
$i++;
}
return $v_result;
}
* This method sets the regular expression for ignoring files and directories
* at import, for example:
* $arch->setIgnoreRegexp("#CVS|\.svn#");
*
* @param string $regexp regular expression defining which files or directories to ignore
*
* @access public
*/
function setIgnoreRegexp() {
}
* This method sets the regular expression for ignoring all files and directories
* matching the filenames in the array list at import, for example:
* $arch->setIgnoreList(array('CVS', '.svn', 'bin/tool'));
*
* @param array $list a list of file or directory names to ignore
*
* @access public
*/
function setIgnoreList() {
}
function _error() {
}
function _warning() {
}
function _isArchive() {
}
clearstatcache();
return @is_file($p_filename) && !@is_link($p_filename);
}
function _openWrite() {
}
return true;
}
function _openRead() {
}
if (!$v_file_to = @fopen($this->_temp_tarname, 'wb')) {
$this->_error('Unable to open in write mode \''
.$this->_temp_tarname.'\'');
$this->_temp_tarname = '';
return false;
}
while ($v_data = @fread($v_file_from, 1024))
@fwrite($v_file_to, $v_data);
@fclose($v_file_from);
@fclose($v_file_to);
}
$v_filename = $this->_temp_tarname;
} else
$v_filename = $this->_tarname;
if ($this->_compress_type == 'gz')
$this->_file = @gzopen($v_filename, "rb");
else if ($this->_compress_type == 'bz2')
$this->_file = @bzopen($v_filename, "r");
else if ($this->_compress_type == 'none')
$this->_file = @fopen($v_filename, "rb");
else
$this->_error('Unknown or missing compression type ('
.$this->_compress_type.')');
if ($this->_file == 0) {
$this->_error('Unable to open in read mode \''.$v_filename.'\'');
return false;
}
return true;
}
function _openReadWrite() {
} else if ($this->_compress_type == 'none')
$this->_file = @fopen($this->_tarname, "r+b");
else
$this->_error('Unknown or missing compression type ('
.$this->_compress_type.')');
if ($this->_file == 0) {
$this->_error('Unable to open in read/write mode \''
.$this->_tarname.'\'');
return false;
}
return true;
}
function _close() {
}
if ($this->_temp_tarname != '') {
@unlink($this->_temp_tarname);
$this->_temp_tarname = '';
}
return true;
}
function _cleanFile() {
} else {
@unlink($this->_tarname);
}
$this->_tarname = '';
return true;
}
function _writeBlock() {
} else {
if ($this->_compress_type == 'gz')
$write = @gzputs($this->_file, $p_binary_data, $p_len);
else if ($this->_compress_type == 'bz2')
$write = @bzwrite($this->_file, $p_binary_data, $p_len);
else if ($this->_compress_type == 'none')
$write = @fputs($this->_file, $p_binary_data, $p_len);
else
$this->_error('Unknown or missing compression type ('
.$this->_compress_type.')');
}
if(empty($write)){
$this->_error('Failed to write to the backup file. Please check you have enough disk quota available.');
return false;
}
$this->remote_write_handle($finished);
}
return true;
}
function remote_write_handle() {
}
clearstatcache();
if(!$finished && filesize($this->_local_tar) < 2000000){
return false;
}
if(!is_resource($this->remote_fp)){
$this->remote_fp = @fopen($this->_orig_tar, "wb");
@fputs($this->remote_fp, "".pack("V", time())."\0ÿ");
$oname = str_replace("\0", '', ltrim(basename($this->_orig_tar, '.gz'), '.'));
fwrite($this->remote_fp, $oname."\0", 1+strlen($oname));
$this->remote_fp_filter = stream_filter_append($this->remote_fp, "zlib.deflate", STREAM_FILTER_WRITE, -1);
$this->remote_hctx = hash_init('crc32b');
$this->remote_content_size = 0;
}
$this->_close();
$content = file_get_contents($this->_local_tar);
$clen = strlen($content);
if(!empty($content)){
hash_update($this->remote_hctx, $content); // Update Hash
$this->remote_content_size += $clen; // Update Length
@fwrite($this->remote_fp, $content, $clen); // Write to the stream
}
$content = '';
@unlink($this->_local_tar);
$this->_openWrite();
if($finished){
stream_filter_remove($this->remote_fp_filter);
$crc = hash_final($this->remote_hctx, true);
@fwrite($this->remote_fp, $crc[3].$crc[2].$crc[1].$crc[0], 4);
@fwrite($this->remote_fp, pack("V", $this->remote_content_size), 4);
@fclose($this->remote_fp);
}
}
function _readBlock() {
}else if ($this->_compress_type == 'bz2'){
$v_block = @bzread($this->_file, 512);
}else if ($this->_compress_type == 'none'){
$cur = ftell($this->_file);
$v_block = @fread($this->_file, 512);
$retry = 0;
$read = strlen($v_block); // How much did we read ?
while($read != 512 && !feof($this->_file)){
if($retry >= 3){ // We can try max for 3 times
break;
}
$toread = 512 - $read; // How many blocks did we miss ?
$tmp_block = @fread($this->_file, $toread); // Read the missing blocks
$v_block .= $tmp_block; // Add it to the existing content
$read = strlen($v_block); // Update the length of updated content
$retry++;
}
}else{
$this->_error('Unknown or missing compression type ('
.$this->_compress_type.')');
}
}
return $v_block;
}
function _jumpBlock() {
}
else if ($this->_compress_type == 'bz2') {
for ($i=0; $i<$p_len; $i++)
$this->_readBlock();
} else if ($this->_compress_type == 'none')
@fseek($this->_file, $p_len*512, SEEK_CUR);
else
$this->_error('Unknown or missing compression type ('
.$this->_compress_type.')');
}
return true;
}
function _writeFooter() {
}
}
return true;
}
function _addList() {
}
if (sizeof($p_list) == 0)
return true;
foreach ($p_list as $v_filename) {
if (!$v_result) {
break;
}
if ($v_filename == $this->_tarname)
continue;
if ($v_filename == '')
continue;
if ($this->_ignore_regexp && preg_match($this->_ignore_regexp, '/'.$v_filename)) {
$this->_warning("File '$v_filename' ignored");
continue;
}
if (!file_exists($v_filename) && !is_link($v_filename)) {
$this->_warning("File '$v_filename' does not exist");
continue;
}
if (!$this->_addFile($v_filename, $v_header, $p_add_dir, $p_remove_dir))
return false;
if (@is_dir($v_filename) && !@is_link($v_filename)) {
if (!($p_hdir = opendir($v_filename))) {
$this->_warning("Directory '$v_filename' can not be read");
continue;
}
$p_temp_list = array();
while (false !== ($p_hitem = readdir($p_hdir))) {
if (($p_hitem != '.') && ($p_hitem != '..')) {
if ($v_filename != ".")
$p_temp_list[0] = $v_filename.'/'.$p_hitem;
else
$p_temp_list[0] = $p_hitem;
$v_result = $this->_addList($p_temp_list,
$p_add_dir,
$p_remove_dir);
}
}
unset($p_temp_list);
unset($p_hdir);
unset($p_hitem);
}
}
return $v_result;
}
function _addFile() {
}
if ($p_filename == '') {
$this->_error('Invalid file name');
return false;
}
$p_filename = $this->_translateWinPath($p_filename, false);
$v_stored_filename = $p_filename;
if (strcmp($p_filename, $p_remove_dir) == 0) {
return true;
}
if(!empty($GLOBALS['__settings']['include_files'])){
$include = 0;
foreach($GLOBALS['__settings']['include_files'] as $include_file){
if(preg_match('/'.preg_quote($include_file, '/').'/is', $p_filename)){
$include = 1;
}elseif(preg_match('/'.preg_quote($p_filename, '/').'/is', $include_file)){
$include = 1;
}
}
if(empty($include)){
return true;
}
}
foreach($GLOBALS['__settings']['exclude_files'] as $ek => $ev){
if(preg_match('#'.$ev.'#', $p_filename)){
return true;
}
}
if ($p_remove_dir != '') {
if (substr($p_remove_dir, -1) != '/')
$p_remove_dir .= '/';
if (substr($p_filename, 0, strlen($p_remove_dir)) == $p_remove_dir)
$v_stored_filename = substr($p_filename, strlen($p_remove_dir));
}
$v_stored_filename = $this->_translateWinPath($v_stored_filename);
if ($p_add_dir != '') {
if (substr($p_add_dir, -1) == '/')
$v_stored_filename = $p_add_dir.$v_stored_filename;
else
$v_stored_filename = $p_add_dir.'/'.$v_stored_filename;
}
$v_stored_filename = $this->_pathReduction($v_stored_filename);
if ($this->_isArchive($p_filename)) {
if (($v_file = @fopen($p_filename, "rb")) == 0) {
$this->_warning("Unable to open file '".$p_filename
."' in binary read mode");
return true;
}
if (!$this->_writeHeader($p_filename, $v_stored_filename))
return false;
while (($v_buffer = fread($v_file, 512)) != '') {
$v_binary_data = pack("a512", "$v_buffer");
if(!$this->_writeBlock($v_binary_data)){
return false;
}
}
fclose($v_file);
} else {
if (!$this->_writeHeader($p_filename, $v_stored_filename))
return false;
}
return true;
}
function _addString() {
}
if ($p_filename == '') {
$this->_error('Invalid file name');
return false;
}
$p_filename = $this->_translateWinPath($p_filename, false);
if (!$this->_writeHeaderBlock($p_filename, strlen($p_string),
time(), 384, "", 0, 0))
return false;
$i=0;
while (($v_buffer = substr($p_string, (($i++)*512), 512)) != '') {
$v_binary_data = pack("a512", $v_buffer);
if(!$this->_writeBlock($v_binary_data)){
return false;
}
}
return true;
}
function _writeHeader() {
}
}
$v_reduce_filename = str_replace($GLOBALS['replace']['from'], $GLOBALS['replace']['to'], $v_reduce_filename);
if (strlen($v_reduce_filename) > 99) {
if (!$this->_writeLongHeader($v_reduce_filename))
return false;
}
if (isset($GLOBALS['bfh']['datadir_softperms']) && preg_match('/'.preg_quote($GLOBALS['replace']['from']['softdatadir'], '/').'/is', $p_filename)) {
fwrite($GLOBALS['bfh']['datadir_softperms'], trim(substr($v_reduce_filename, 12), '/')." ". (substr(sprintf('%o', fileperms($p_filename)), -4)) ."\n");
}elseif (isset($GLOBALS['bfh']['wwwdir_softperms']) && preg_match('/'.preg_quote($GLOBALS['replace']['from']['wwwdir'], '/').'/is', $p_filename)) {
fwrite($GLOBALS['bfh']['wwwdir_softperms'], trim(substr($v_reduce_filename, 7), '/')." ". (substr(sprintf('%o', fileperms($p_filename)), -4)) ."\n");
}elseif (isset($GLOBALS['bfh']['softperms']) && preg_match('/'.preg_quote($GLOBALS['replace']['from']['softpath'], '/').'/is', $p_filename)) {
fwrite($GLOBALS['bfh']['softperms'], trim($v_reduce_filename, '/')." ".(!empty($v_linkname) ? "linkto=".rtrim($v_linkname, '/')." " : ""). (substr(sprintf('%o', fileperms($p_filename)), -4)) ."\n");
}
clearstatcache();
$v_info = lstat($p_filename);
$v_uid = sprintf("%07s", DecOct($v_info[4]));
$v_gid = sprintf("%07s", DecOct($v_info[5]));
$v_perms = sprintf("%07s", DecOct($v_info['mode'] & 000777));
$v_mtime = sprintf("%011s", DecOct($v_info['mtime']));
$v_linkname = '';
if (@is_link($p_filename)) {
$v_typeflag = '2';
$v_linkname = readlink($p_filename);
$v_size = sprintf("%011s", DecOct(0));
} elseif (@is_dir($p_filename)) {
$v_typeflag = "5";
$v_size = sprintf("%011s", DecOct(0));
} else {
$v_typeflag = '0';
clearstatcache();
$v_size = sprintf("%011s", DecOct($v_info['size']));
}
$v_magic = 'ustar ';
$v_version = ' ';
if (function_exists('posix_getpwuid'))
{
$userinfo = posix_getpwuid($v_info[4]);
$groupinfo = posix_getgrgid($v_info[5]);
$v_uname = $userinfo['name'];
$v_gname = $groupinfo['name'];
}
else
{
$v_uname = '';
$v_gname = '';
}
$v_devmajor = '';
$v_devminor = '';
$v_prefix = '';
$v_binary_data_first = pack("a100a8a8a8a12a12",
$v_reduce_filename, $v_perms, $v_uid,
$v_gid, $v_size, $v_mtime);
$v_binary_data_last = pack("a1a100a6a2a32a32a8a8a155a12",
$v_typeflag, $v_linkname, $v_magic,
$v_version, $v_uname, $v_gname,
$v_devmajor, $v_devminor, $v_prefix, '');
$v_checksum = 0;
for ($i=0; $i<148; $i++)
$v_checksum += ord(substr($v_binary_data_first,$i,1));
for ($i=148; $i<156; $i++)
$v_checksum += ord(' ');
for ($i=156, $j=0; $i<512; $i++, $j++)
$v_checksum += ord(substr($v_binary_data_last,$j,1));
if(!$this->_writeBlock($v_binary_data_first, 148)){
return false;
}
$v_checksum = sprintf("%06s ", DecOct($v_checksum));
$v_binary_data = pack("a8", $v_checksum);
if(!$this->_writeBlock($v_binary_data, 8)){
return false;
}
if(!$this->_writeBlock($v_binary_data_last, 356)){
return false;
}
return true;
}
function _writeHeaderBlock() {
}
if ($p_type == "5") {
$v_size = sprintf("%011s", DecOct(0));
} else {
$v_size = sprintf("%011s", DecOct($p_size));
}
$v_uid = sprintf("%07s", DecOct($p_uid));
$v_gid = sprintf("%07s", DecOct($p_gid));
$v_perms = sprintf("%07s", DecOct($p_perms & 000777));
$v_mtime = sprintf("%11s", DecOct($p_mtime));
$v_linkname = '';
$v_magic = 'ustar ';
$v_version = ' ';
if (function_exists('posix_getpwuid'))
{
$userinfo = posix_getpwuid($p_uid);
$groupinfo = posix_getgrgid($p_gid);
$v_uname = $userinfo['name'];
$v_gname = $groupinfo['name'];
}
else
{
$v_uname = '';
$v_gname = '';
}
$v_devmajor = '';
$v_devminor = '';
$v_prefix = '';
$v_binary_data_first = pack("a100a8a8a8a12A12",
$p_filename, $v_perms, $v_uid, $v_gid,
$v_size, $v_mtime);
$v_binary_data_last = pack("a1a100a6a2a32a32a8a8a155a12",
$p_type, $v_linkname, $v_magic,
$v_version, $v_uname, $v_gname,
$v_devmajor, $v_devminor, $v_prefix, '');
$v_checksum = 0;
for ($i=0; $i<148; $i++)
$v_checksum += ord(substr($v_binary_data_first,$i,1));
for ($i=148; $i<156; $i++)
$v_checksum += ord(' ');
for ($i=156, $j=0; $i<512; $i++, $j++)
$v_checksum += ord(substr($v_binary_data_last,$j,1));
if(!$this->_writeBlock($v_binary_data_first, 148)){
return false;
}
$v_checksum = sprintf("%06s ", DecOct($v_checksum));
$v_binary_data = pack("a8", $v_checksum);
if(!$this->_writeBlock($v_binary_data, 8)){
return false;
}
if(!$this->_writeBlock($v_binary_data_last, 356)){
return false;
}
return true;
}
function _writeLongHeader() {
}
$v_checksum = sprintf("%06s ", DecOct($v_checksum));
$v_binary_data = pack("a8", $v_checksum);
if(!$this->_writeBlock($v_binary_data, 8)){
return false;
}
if(!$this->_writeBlock($v_binary_data_last, 356)){
return false;
}
$i=0;
while (($v_buffer = substr($p_filename, (($i++)*512), 512)) != '') {
$v_binary_data = pack("a512", "$v_buffer");
if(!$this->_writeBlock($v_binary_data)){
return false;
}
}
return true;
}
function _readHeader() {
}
if (strlen($v_binary_data) != 512) {
$v_header['filename'] = '';
$this->_error('Invalid block size : '.strlen($v_binary_data));
return false;
}
if (!is_array($v_header)) {
$v_header = array();
}
$v_checksum = 0;
for ($i=0; $i<148; $i++)
$v_checksum+=ord(substr($v_binary_data,$i,1));
for ($i=148; $i<156; $i++)
$v_checksum += ord(' ');
for ($i=156; $i<512; $i++)
$v_checksum+=ord(substr($v_binary_data,$i,1));
if (version_compare(PHP_VERSION, "5.5.0-dev") < 0) {
$fmt = "a100filename/a8mode/a8uid/a8gid/a12size/a12mtime/" .
"a8checksum/a1typeflag/a100link/a6magic/a2version/" .
"a32uname/a32gname/a8devmajor/a8devminor/a131prefix";
} else {
$fmt = "Z100filename/Z8mode/Z8uid/Z8gid/Z12size/Z12mtime/" .
"Z8checksum/Z1typeflag/Z100link/Z6magic/Z2version/" .
"Z32uname/Z32gname/Z8devmajor/Z8devminor/Z131prefix";
}
$v_data = unpack($fmt, $v_binary_data);
if (strlen($v_data["prefix"]) > 0) {
$v_data["filename"] = "$v_data[prefix]/$v_data[filename]";
}
$v_header['checksum'] = OctDec(trim($v_data['checksum']));
if ($v_header['checksum'] != $v_checksum) {
$v_header['filename'] = '';
if (($v_checksum == 256) && ($v_header['checksum'] == 0))
return true;
if(preg_match('/softperms\.txt/is', $v_data["filename"])){
$v_binary_data = $this->_readBlock();
if(strlen($v_binary_data) != 0){
return $this->_readHeader($v_binary_data, $v_header);
}else{
return true;
}
}
$this->_error('Invalid checksum for file "'.$v_data['filename']
.'" : '.$v_checksum.' calculated, '
.$v_header['checksum'].' expected');
return false;
}
$v_header['filename'] = $v_data['filename'];
if ($this->_maliciousFilename($v_header['filename'])) {
$this->_error('Malicious .tar detected, file "' . $v_header['filename'] .
'" will not install in desired directory tree');
return false;
}
$v_header['mode'] = OctDec(trim($v_data['mode']));
$v_header['uid'] = OctDec(trim($v_data['uid']));
$v_header['gid'] = OctDec(trim($v_data['gid']));
$v_header['size'] = OctDec(trim($v_data['size']));
$v_header['mtime'] = OctDec(trim($v_data['mtime']));
if (($v_header['typeflag'] = $v_data['typeflag']) == "5") {
$v_header['size'] = 0;
}
$v_header['link'] = trim($v_data['link']);
they do not carry interesting info
$v_header[magic] = trim($v_data[magic]);
$v_header[version] = trim($v_data[version]);
$v_header[uname] = trim($v_data[uname]);
$v_header[gname] = trim($v_data[gname]);
$v_header[devmajor] = trim($v_data[devmajor]);
$v_header[devminor] = trim($v_data[devminor]);
*/
return true;
}
* Detect and report a malicious file name
*
* @param string $file
*
* @return bool
* @access private
*/
function _maliciousFilename() {
}
if (strpos($file, '../') === 0) {
return true;
}
return false;
}
function _readLongHeader() {
}
if (($v_header['size'] % 512) != 0) {
$v_content = $this->_readBlock();
$v_filename .= trim($v_content);
}
$v_binary_data = $this->_readBlock();
if (!$this->_readHeader($v_binary_data, $v_header))
return false;
$v_filename = trim($v_filename);
$v_header['filename'] = $v_filename;
if ($this->_maliciousFilename($v_filename)) {
$this->_error('Malicious .tar detected, file "' . $v_filename .
'" will not install in desired directory tree');
return false;
}
return true;
}
* This method extract from the archive one file identified by $p_filename.
* The return value is a string with the file content, or null on error.
*
* @param string $p_filename The path of the file to extract in a string.
*
* @return a string with the file content or null.
* @access private
*/
function _extractInString() {
}
if ($v_header['filename'] == $p_filename) {
if ($v_header['typeflag'] == "5") {
$this->_error('Unable to extract in string a directory '
.'entry {'.$v_header['filename'].'}');
return null;
} else {
$n = floor($v_header['size']/512);
for ($i=0; $i<$n; $i++) {
$v_result_str .= $this->_readBlock();
}
if (($v_header['size'] % 512) != 0) {
$v_content = $this->_readBlock();
$v_result_str .= substr($v_content, 0,
($v_header['size'] % 512));
}
return $v_result_str;
}
} else {
$this->_jumpBlock(ceil(($v_header['size']/512)));
}
}
return null;
}
function _extractList() {
}
$p_remove_path = $this->_translateWinPath($p_remove_path);
if (($p_remove_path != '') && (substr($p_remove_path, -1) != '/'))
$p_remove_path .= '/';
$p_remove_path_size = strlen($p_remove_path);
switch ($p_mode) {
case "complete" :
$v_extract_all = true;
$v_listing = false;
break;
case "partial" :
$v_extract_all = false;
$v_listing = false;
break;
case "list" :
$v_extract_all = false;
$v_listing = true;
break;
default :
$this->_error('Invalid extract mode ('.$p_mode.')');
return false;
}
clearstatcache();
while (strlen($v_binary_data = $this->_readBlock()) != 0)
{
$v_extract_file = FALSE;
$v_extraction_stopped = 0;
if (!$this->_readHeader($v_binary_data, $v_header))
return false;
if ($v_header['filename'] == '') {
continue;
}
if ($v_header['typeflag'] == 'L') {
if (!$this->_readLongHeader($v_header))
return false;
}
if ((!$v_extract_all) && (is_array($p_file_list))) {
$v_extract_file = false;
for ($i=0; $i<sizeof($p_file_list); $i++) {
if (substr($p_file_list[$i], -1) == '/') {
if ((strlen($v_header['filename']) > strlen($p_file_list[$i]))
&& (substr($v_header['filename'], 0, strlen($p_file_list[$i]))
== $p_file_list[$i])) {
$v_extract_file = true;
break;
}
}
elseif ($p_file_list[$i] == $v_header['filename']) {
$v_extract_file = true;
break;
}
}
} else {
$v_extract_file = true;
}
if (($v_extract_file) && (!$v_listing))
{
if (($p_remove_path != '')
&& (substr($v_header['filename'], 0, $p_remove_path_size)
== $p_remove_path))
$v_header['filename'] = substr($v_header['filename'],
$p_remove_path_size);
if (($p_path != './') && ($p_path != '/')) {
while (substr($p_path, -1) == '/')
$p_path = substr($p_path, 0, strlen($p_path)-1);
if (substr($v_header['filename'], 0, 1) == '/')
$v_header['filename'] = $p_path.$v_header['filename'];
else
$v_header['filename'] = $p_path.'/'.$v_header['filename'];
}
if (file_exists($v_header['filename'])) {
if (   (@is_dir($v_header['filename']))
&& ($v_header['typeflag'] == '')) {
$this->_error('File '.$v_header['filename']
.' already exists as a directory');
return false;
}
if (   ($this->_isArchive($v_header['filename']))
&& ($v_header['typeflag'] == "5")) {
$this->_error('Directory '.$v_header['filename']
.' already exists as a file');
return false;
}
if (!is_writeable($v_header['filename'])) {
if(is_dir($v_header['filename'])){
$chmod = chmod($v_header['filename'], 0755);
}else{
$chmod = chmod($v_header['filename'], 0644);
}
if (!is_writeable($v_header['filename'])) {
$this->_error('File '.$v_header['filename']
.' already exists and is write protected');
return false;
}
}
if (filemtime($v_header['filename']) > $v_header['mtime']) {
}
}
elseif (($v_result
= $this->_dirCheck(($v_header['typeflag'] == "5"
?$v_header['filename']
:dirname($v_header['filename'])))) != 1) {
$this->_error('Unable to create path for '.$v_header['filename']);
return false;
}
if ($v_extract_file) {
if ($v_header['typeflag'] == "5") {
if (!@file_exists($v_header['filename'])) {
if (!@mkdir($v_header['filename'], 0777)) {
$this->_error('Unable to create directory {'
.$v_header['filename'].'}');
return false;
}
}
} elseif ($v_header['typeflag'] == "2") {
if (@file_exists($v_header['filename'])) {
@unlink($v_header['filename']);
}
$this->_error('Unable to extract symbolic link {'
.$v_header['filename'].'}');
return false;
} */
} else {
if (($v_dest_file = @fopen($v_header['filename'], "wb")) == 0) {
$this->_error('Error while opening {'.$v_header['filename']
.'} in write binary mode');
return false;
} else {
$n = floor($v_header['size']/512);
for ($i=0; $i<$n; $i++) {
$v_content = $this->_readBlock();
fwrite($v_dest_file, $v_content, 512);
}
if (($v_header['size'] % 512) != 0) {
$v_content = $this->_readBlock();
fwrite($v_dest_file, $v_content, ($v_header['size'] % 512));
}
@fclose($v_dest_file);
if ($p_preserve) {
@chown($v_header['filename'], $v_header['uid']);
@chgrp($v_header['filename'], $v_header['gid']);
}
@touch($v_header['filename'], $v_header['mtime']);
if ($v_header['mode'] & 0111) {
$mode = fileperms($v_header['filename']) | (~umask() & 0111);
@chmod($v_header['filename'], $mode);
}
}
clearstatcache();
if (!is_file($v_header['filename'])) {
$this->_error('Extracted file '.$v_header['filename']
.'does not exist. Archive may be corrupted.');
return false;
}
$filesize = filesize($v_header['filename']);
if ($filesize != $v_header['size']) {
$this->_error('Extracted file '.$v_header['filename']
.' does not have the correct file size \''
.$filesize
.'\' ('.$v_header['size']
.' expected). Archive may be corrupted.');
return false;
}
}
} else {
$this->_jumpBlock(ceil(($v_header['size']/512)));
}
} else {
$this->_jumpBlock(ceil(($v_header['size']/512)));
}
if ($this->_compress)
$v_end_of_file = @gzeof($this->_file);
else
$v_end_of_file = @feof($this->_file);
*/
if ($v_listing || $v_extract_file || $v_extraction_stopped) {
if (($v_file_dir = dirname($v_header['filename']))
== $v_header['filename'])
$v_file_dir = '';
if ((substr($v_header['filename'], 0, 1) == '/') && ($v_file_dir == ''))
$v_file_dir = '/';
$p_list_detail[$v_nb++] = (!empty($v_listing) ? $v_header : '');
if (is_array($p_file_list) && (count($p_list_detail) == count($p_file_list))) {
return true;
}
}
}
return true;
}
function _openAppend() {
}
if ($this->_compress_type == 'gz')
$v_temp_tar = @gzopen($this->_tarname.".tmp", "rb");
elseif ($this->_compress_type == 'bz2')
$v_temp_tar = @bzopen($this->_tarname.".tmp", "r");
if ($v_temp_tar == 0) {
$this->_error('Unable to open file \''.$this->_tarname
.'.tmp\' in binary read mode');
@rename($this->_tarname.".tmp", $this->_tarname);
return false;
}
if (!$this->_openWrite()) {
@rename($this->_tarname.".tmp", $this->_tarname);
return false;
}
if ($this->_compress_type == 'gz') {
$end_blocks = 0;
while (!@gzeof($v_temp_tar)) {
$v_buffer = @gzread($v_temp_tar, 512);
if ($v_buffer == ARCHIVE_TAR_END_BLOCK || strlen($v_buffer) == 0) {
$end_blocks++;
continue;
} elseif ($end_blocks > 0) {
for ($i = 0; $i < $end_blocks; $i++) {
if(!$this->_writeBlock(ARCHIVE_TAR_END_BLOCK)){
return false;
}
}
$end_blocks = 0;
}
$v_binary_data = pack("a512", $v_buffer);
if(!$this->_writeBlock($v_binary_data)){
return false;
}
}
@gzclose($v_temp_tar);
}
elseif ($this->_compress_type == 'bz2') {
$end_blocks = 0;
while (strlen($v_buffer = @bzread($v_temp_tar, 512)) > 0) {
if ($v_buffer == ARCHIVE_TAR_END_BLOCK || strlen($v_buffer) == 0) {
$end_blocks++;
continue;
} elseif ($end_blocks > 0) {
for ($i = 0; $i < $end_blocks; $i++) {
if(!$this->_writeBlock(ARCHIVE_TAR_END_BLOCK)){
return false;
}
}
$end_blocks = 0;
}
$v_binary_data = pack("a512", $v_buffer);
if(!$this->_writeBlock($v_binary_data)){
return false;
}
}
@bzclose($v_temp_tar);
}
if (!@unlink($this->_tarname.".tmp")) {
$this->_error('Error while deleting temporary file \''
.$this->_tarname.'.tmp\'');
}
} else {
if (!$this->_openReadWrite())
return false;
clearstatcache();
$v_size = filesize($this->_tarname);
fseek($this->_file, $v_size - 1024);
if (fread($this->_file, 512) == ARCHIVE_TAR_END_BLOCK) {
fseek($this->_file, $v_size - 1024);
}
elseif (fread($this->_file, 512) == ARCHIVE_TAR_END_BLOCK) {
fseek($this->_file, $v_size - 512);
}
}
return true;
}
function _append() {
}
* Check if a directory exists and create it (including parent
* dirs) if not.
*
* @param string $p_dir directory to check
*
* @return bool true if the directory exists or was created
*/
function _dirCheck() {
}
return true;
}
* Compress path by changing for example "/dir/foo/../bar" to "/dir/bar",
* rand emove double slashes.
*
* @param string $p_dir path to reduce
*
* @return string reduced path
*
* @access private
*
*/
function _pathReduction() {
}
else if ($v_list[$i] == "..") {
$i--;
}
else if (   ($v_list[$i] == '')
&& ($i!=(sizeof($v_list)-1))
&& ($i!=0)) {
} else {
$v_result = $v_list[$i].($i!=(sizeof($v_list)-1)?'/'
.$v_result:'');
}
}
}
if (defined('OS_WINDOWS') && OS_WINDOWS) {
$v_result = strtr($v_result, '\\', '/');
}
return $v_result;
}
function _translateWinPath() {
}
if ((strpos($p_path, '\\') > 0) || (substr($p_path, 0,1) == '\\')) {
$p_path = strtr($p_path, '\\', '/');
}
}
return $p_path;
}
}
if (!class_exists("Archive_Tar")) {
class Archive_Tar extends softtar {
function __construct() {
}
function _Archive_Tar() {
}
}
}