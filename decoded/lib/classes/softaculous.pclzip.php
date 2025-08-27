<?php
if(!class_exists('PclZip')){
include_once($globals['path'].'/lib/classes/pclzip.php');
}
class softpclzip extends PclZip{
function __construct() {
}
function restore_list() {
}
$p_list = array();
$this->privDisableMagicQuotes();
if (($this->zip_fd = @fopen($this->zipname, 'rb')) == 0)
{
$this->privSwapBackMagicQuotes();
PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open archive \''.$this->zipname.'\' in binary read mode');
return PclZip::errorCode();
}
if (($v_result = $this->restore_privReadEndCentralDir($p_list)) != 1)
{
$this->privSwapBackMagicQuotes();
return $v_result;
}
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $p_list;
}
function restore_privReadEndCentralDir() {
}
$v_found = 0;
if ($v_size > 26) {
@fseek($this->zip_fd, $v_size-22);
if (($v_pos = @ftell($this->zip_fd)) != ($v_size-22))
{
PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');
return PclZip::errorCode();
}
$v_binary_data = @fread($this->zip_fd, 4);
$v_data = @unpack('Vid', $v_binary_data);
if ($v_data['id'] == 0x06054b50) {
$v_found = 1;
}
$v_pos = ftell($this->zip_fd);
}
if (!$v_found) {
$v_maximum_size = 65557; // 0xFFFF + 22;
if ($v_maximum_size > $v_size)
$v_maximum_size = $v_size;
@fseek($this->zip_fd, $v_size-$v_maximum_size);
if (@ftell($this->zip_fd) != ($v_size-$v_maximum_size))
{
PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');
return PclZip::errorCode();
}
$v_pos = ftell($this->zip_fd);
$v_bytes = 0x00000000;
while ($v_pos < $v_size)
{
$v_byte = @fread($this->zip_fd, 1);
$v_bytes = ( ($v_bytes & 0xFFFFFF) << 8) | Ord($v_byte);
if ($v_bytes == 0x504b0506)
{
$v_pos++;
break;
}
$v_pos++;
}
if ($v_pos == $v_size)
{
PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Unable to find End of Central Dir Record signature");
return PclZip::errorCode();
}
}
$v_binary_data = fread($this->zip_fd, 18);
if (strlen($v_binary_data) != 18)
{
PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid End of Central Dir Record size : ".strlen($v_binary_data));
return PclZip::errorCode();
}
$v_data = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', $v_binary_data);
if (($v_pos + $v_data['comment_size'] + 18) != $v_size) {
if (0) {
PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT,
'The central dir is not at the end of the archive.'
.' Some trailing bytes exists after the archive.');
return PclZip::errorCode();
}
}
if ($v_data['comment_size'] != 0) {
$p_central_dir['comment'] = fread($this->zip_fd, $v_data['comment_size']);
}
else
$p_central_dir['comment'] = '';
$p_central_dir['entries'] = $v_data['entries'];
$p_central_dir['disk_entries'] = $v_data['disk_entries'];
$p_central_dir['offset'] = $v_data['offset'];
$p_central_dir['size'] = $v_data['size'];
$p_central_dir['disk'] = $v_data['disk'];
$p_central_dir['disk_start'] = $v_data['disk_start'];
@rewind($this->zip_fd);
if (@fseek($this->zip_fd, $p_central_dir['offset'])){
return $v_result; // Return the original result
}
for($i=1; $i<=200000; $i++){
$binary_data = @fread($this->zip_fd, 4);
$_data = unpack('Vid', $binary_data);
if ($_data['id'] != 0x02014b50){
if($_data['id'] == 0x06054b50){
$total = $i - 1;
}
break;
}
fread($this->zip_fd, 24);
$binary_data = fread($this->zip_fd, 6);
$_header = unpack('vfilename_len/vextra_len/vcomment_len', $binary_data);
fread($this->zip_fd, 12);
if ($_header['filename_len'] != 0){
$filename = fread($this->zip_fd, $_header['filename_len']);
$vv = trim(trim($filename), '/');
if(substr_count($vv, '/') || empty($vv)){
}else{
$p_list[] = $vv;
}
}
if ($_header['extra_len'] != 0)
fread($this->zip_fd, $_header['extra_len']);
if ($_header['comment_len'] != 0)
fread($this->zip_fd, $_header['comment_len']);
unset($_header);
}// End of for loop
if(!empty($total)){
$p_central_dir['entries'] = $total;
$p_central_dir['disk_entries'] = $total;
}
return $v_result;
}
function _extract() {
}
$v_options = array();
$v_path = '';
$v_remove_path = "";
$v_remove_all_path = false;
$v_size = func_num_args();
$v_options[PCLZIP_OPT_EXTRACT_AS_STRING] = FALSE;
if ($v_size > 0) {
$v_arg_list = func_get_args();
if ((is_integer($v_arg_list[0])) && ($v_arg_list[0] > 77000)) {
$v_result = $this->privParseOptions($v_arg_list, $v_size, $v_options,
array (PCLZIP_OPT_PATH => 'optional',
PCLZIP_OPT_REMOVE_PATH => 'optional',
PCLZIP_OPT_REMOVE_ALL_PATH => 'optional',
PCLZIP_OPT_ADD_PATH => 'optional',
PCLZIP_CB_PRE_EXTRACT => 'optional',
PCLZIP_CB_POST_EXTRACT => 'optional',
PCLZIP_OPT_SET_CHMOD => 'optional',
PCLZIP_OPT_BY_NAME => 'optional',
PCLZIP_OPT_BY_EREG => 'optional',
PCLZIP_OPT_BY_PREG => 'optional',
PCLZIP_OPT_BY_INDEX => 'optional',
PCLZIP_OPT_EXTRACT_AS_STRING => 'optional',
PCLZIP_OPT_EXTRACT_IN_OUTPUT => 'optional',
PCLZIP_OPT_REPLACE_NEWER => 'optional'
,PCLZIP_OPT_STOP_ON_ERROR => 'optional'
,PCLZIP_OPT_EXTRACT_DIR_RESTRICTION => 'optional',
PCLZIP_OPT_TEMP_FILE_THRESHOLD => 'optional',
PCLZIP_OPT_TEMP_FILE_ON => 'optional',
PCLZIP_OPT_TEMP_FILE_OFF => 'optional'
));
if ($v_result != 1) {
return 0;
}
if (isset($v_options[PCLZIP_OPT_PATH])) {
$v_path = $v_options[PCLZIP_OPT_PATH];
}
if (isset($v_options[PCLZIP_OPT_REMOVE_PATH])) {
$v_remove_path = $v_options[PCLZIP_OPT_REMOVE_PATH];
}
if (isset($v_options[PCLZIP_OPT_REMOVE_ALL_PATH])) {
$v_remove_all_path = $v_options[PCLZIP_OPT_REMOVE_ALL_PATH];
}
if (isset($v_options[PCLZIP_OPT_ADD_PATH])) {
if ((strlen($v_path) > 0) && (substr($v_path, -1) != '/')) {
$v_path .= '/';
}
$v_path .= $v_options[PCLZIP_OPT_ADD_PATH];
}
}
else {
$v_path = $v_arg_list[0];
if ($v_size == 2) {
$v_remove_path = $v_arg_list[1];
}
else if ($v_size > 2) {
PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");
return 0;
}
}
}
$this->privOptionDefaultThreshold($v_options);
$p_list = array();
$v_result = $this->_privExtractByRule($p_list, $v_path, $v_remove_path,
$v_remove_all_path, $v_options);
if ($v_result < 1) {
unset($p_list);
return(0);
}
return $p_list;
}
function _privExtractByRule() {
}
}
if (($p_remove_path != "") && (substr($p_remove_path, -1) != '/'))
{
$p_remove_path .= '/';
}
$p_remove_path_size = strlen($p_remove_path);
if (($v_result = $this->privOpenFd('rb')) != 1)
{
$this->privSwapBackMagicQuotes();
return $v_result;
}
$v_central_dir = array();
if (($v_result = $this->_privReadEndCentralDir($v_central_dir)) != 1)
{
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $v_result;
}
$v_pos_entry = $v_central_dir['offset'];
$j_start = 0;
for ($i=0, $v_nb_extracted=0; $i<$v_central_dir['entries']; $i++)
{
@rewind($this->zip_fd);
if (@fseek($this->zip_fd, $v_pos_entry))
{
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');
return PclZip::errorCode();
}
$v_header = array();
if (($v_result = $this->privReadCentralFileHeader($v_header)) != 1)
{
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $v_result;
}
$v_header['index'] = $i;
$v_pos_entry = ftell($this->zip_fd);
$v_extract = false;
if (   (isset($p_options[PCLZIP_OPT_BY_NAME]))
&& ($p_options[PCLZIP_OPT_BY_NAME] != 0)) {
for ($j=0; ($j<sizeof($p_options[PCLZIP_OPT_BY_NAME])) && (!$v_extract); $j++) {
if (substr($p_options[PCLZIP_OPT_BY_NAME][$j], -1) == "/") {
if (   (strlen($v_header['stored_filename']) > strlen($p_options[PCLZIP_OPT_BY_NAME][$j]))
&& (substr($v_header['stored_filename'], 0, strlen($p_options[PCLZIP_OPT_BY_NAME][$j])) == $p_options[PCLZIP_OPT_BY_NAME][$j])) {
$v_extract = true;
}
}
elseif ($v_header['stored_filename'] == $p_options[PCLZIP_OPT_BY_NAME][$j]) {
$v_extract = true;
}
}
}
else if (   (isset($p_options[PCLZIP_OPT_BY_EREG]))
&& ($p_options[PCLZIP_OPT_BY_EREG] != "")) {
if (ereg($p_options[PCLZIP_OPT_BY_EREG], $v_header['stored_filename'])) {
$v_extract = true;
}
}
*/
else if (   (isset($p_options[PCLZIP_OPT_BY_PREG]))
&& ($p_options[PCLZIP_OPT_BY_PREG] != "")) {
if (preg_match($p_options[PCLZIP_OPT_BY_PREG], $v_header['stored_filename'])) {
$v_extract = true;
}
}
else if (   (isset($p_options[PCLZIP_OPT_BY_INDEX]))
&& ($p_options[PCLZIP_OPT_BY_INDEX] != 0)) {
for ($j=$j_start; ($j<sizeof($p_options[PCLZIP_OPT_BY_INDEX])) && (!$v_extract); $j++) {
if (($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['start']) && ($i<=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end'])) {
$v_extract = true;
}
if ($i>=$p_options[PCLZIP_OPT_BY_INDEX][$j]['end']) {
$j_start = $j+1;
}
if ($p_options[PCLZIP_OPT_BY_INDEX][$j]['start']>$i) {
break;
}
}
}
else {
$v_extract = true;
}
if (   ($v_extract)
&& (   ($v_header['compression'] != 8)
&& ($v_header['compression'] != 0))) {
$v_header['status'] = 'unsupported_compression';
if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
&& ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
$this->privSwapBackMagicQuotes();
PclZip::privErrorLog(PCLZIP_ERR_UNSUPPORTED_COMPRESSION,
"Filename '".$v_header['stored_filename']."' is "
."compressed by an unsupported compression "
."method (".$v_header['compression'].") ");
return PclZip::errorCode();
}
}
if (($v_extract) && (($v_header['flag'] & 1) == 1)) {
$v_header['status'] = 'unsupported_encryption';
if (   (isset($p_options[PCLZIP_OPT_STOP_ON_ERROR]))
&& ($p_options[PCLZIP_OPT_STOP_ON_ERROR]===true)) {
$this->privSwapBackMagicQuotes();
PclZip::privErrorLog(PCLZIP_ERR_UNSUPPORTED_ENCRYPTION,
"Unsupported encryption for "
." filename '".$v_header['stored_filename']
."'");
return PclZip::errorCode();
}
}
if (($v_extract) && ($v_header['status'] != 'ok')) {
$v_result = $this->privConvertHeader2FileInfo($v_header,
$p_file_list[$v_nb_extracted++]);
if ($v_result != 1) {
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $v_result;
}
$v_extract = false;
}
if ($v_extract)
{
@rewind($this->zip_fd);
if (@fseek($this->zip_fd, $v_header['offset']))
{
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
PclZip::privErrorLog(PCLZIP_ERR_INVALID_ARCHIVE_ZIP, 'Invalid archive size');
return PclZip::errorCode();
}
if ($p_options[PCLZIP_OPT_EXTRACT_AS_STRING]) {
$v_string = '';
$v_result1 = $this->privExtractFileAsString($v_header, $v_string, $p_options);
if ($v_result1 < 1) {
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $v_result1;
}
if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted])) != 1)
{
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $v_result;
}
$p_file_list[$v_nb_extracted]['content'] = $v_string;
$v_nb_extracted++;
if ($v_result1 == 2) {
break;
}
}
elseif (   (isset($p_options[PCLZIP_OPT_EXTRACT_IN_OUTPUT]))
&& ($p_options[PCLZIP_OPT_EXTRACT_IN_OUTPUT])) {
$v_result1 = $this->privExtractFileInOutput($v_header, $p_options);
if ($v_result1 < 1) {
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $v_result1;
}
if (($v_result = $this->privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++])) != 1) {
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $v_result;
}
if ($v_result1 == 2) {
break;
}
}
else {
$v_result1 = $this->privExtractFile($v_header,
$p_path, $p_remove_path,
$p_remove_all_path,
$p_options);
if ($v_result1 < 1) {
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $v_result1;
}
$v_nb_extracted++;
{
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $v_result;
}*/
if ($v_result1 == 2) {
break;
}
}
}
}
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $v_result;
}
function _privReadEndCentralDir() {
}
$v_found = 0;
if ($v_size > 26) {
@fseek($this->zip_fd, $v_size-22);
if (($v_pos = @ftell($this->zip_fd)) != ($v_size-22))
{
PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');
return PclZip::errorCode();
}
$v_binary_data = @fread($this->zip_fd, 4);
$v_data = @unpack('Vid', $v_binary_data);
if ($v_data['id'] == 0x06054b50) {
$v_found = 1;
}
$v_pos = ftell($this->zip_fd);
}
if (!$v_found) {
$v_maximum_size = 65557; // 0xFFFF + 22;
if ($v_maximum_size > $v_size)
$v_maximum_size = $v_size;
@fseek($this->zip_fd, $v_size-$v_maximum_size);
if (@ftell($this->zip_fd) != ($v_size-$v_maximum_size))
{
PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, 'Unable to seek back to the middle of the archive \''.$this->zipname.'\'');
return PclZip::errorCode();
}
$v_pos = ftell($this->zip_fd);
$v_bytes = 0x00000000;
while ($v_pos < $v_size)
{
$v_byte = @fread($this->zip_fd, 1);
$v_bytes = ( ($v_bytes & 0xFFFFFF) << 8) | Ord($v_byte);
if ($v_bytes == 0x504b0506)
{
$v_pos++;
break;
}
$v_pos++;
}
if ($v_pos == $v_size)
{
PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Unable to find End of Central Dir Record signature");
return PclZip::errorCode();
}
}
$v_binary_data = fread($this->zip_fd, 18);
if (strlen($v_binary_data) != 18)
{
PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT, "Invalid End of Central Dir Record size : ".strlen($v_binary_data));
return PclZip::errorCode();
}
$v_data = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', $v_binary_data);
if (($v_pos + $v_data['comment_size'] + 18) != $v_size) {
if (0) {
PclZip::privErrorLog(PCLZIP_ERR_BAD_FORMAT,
'The central dir is not at the end of the archive.'
.' Some trailing bytes exists after the archive.');
return PclZip::errorCode();
}
}
if ($v_data['comment_size'] != 0) {
$p_central_dir['comment'] = fread($this->zip_fd, $v_data['comment_size']);
}
else
$p_central_dir['comment'] = '';
$p_central_dir['entries'] = $v_data['entries'];
$p_central_dir['disk_entries'] = $v_data['disk_entries'];
$p_central_dir['offset'] = $v_data['offset'];
$p_central_dir['size'] = $v_data['size'];
$p_central_dir['disk'] = $v_data['disk'];
$p_central_dir['disk_start'] = $v_data['disk_start'];
@rewind($this->zip_fd);
if (@fseek($this->zip_fd, $p_central_dir['offset'])){
return $v_result; // Return the original result
}
for($i=1; $i<=200000; $i++){
$binary_data = @fread($this->zip_fd, 4);
$_data = unpack('Vid', $binary_data);
if ($_data['id'] != 0x02014b50){
if($_data['id'] == 0x06054b50){
$total = $i - 1;
}
break;
}
fread($this->zip_fd, 24);
$binary_data = fread($this->zip_fd, 6);
$_header = unpack('vfilename_len/vextra_len/vcomment_len', $binary_data);
fread($this->zip_fd, 12);
if ($_header['filename_len'] != 0)
fread($this->zip_fd, $_header['filename_len']);
if ($_header['extra_len'] != 0)
fread($this->zip_fd, $_header['extra_len']);
if ($_header['comment_len'] != 0)
fread($this->zip_fd, $_header['comment_len']);
unset($_header);
}// End of for loop
if(!empty($total)){
$p_central_dir['entries'] = $total;
$p_central_dir['disk_entries'] = $total;
}
return $v_result;
}
function _add() {
}
}
else {
$v_options[PCLZIP_OPT_ADD_PATH] = $v_add_path = $v_arg_list[0];
if ($v_size == 2) {
$v_options[PCLZIP_OPT_REMOVE_PATH] = $v_arg_list[1];
}
else if ($v_size > 2) {
PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid number / type of arguments");
return 0;
}
}
}
$this->privOptionDefaultThreshold($v_options);
$v_string_list = array();
$v_att_list = array();
$v_filedescr_list = array();
$p_result_list = array();
if (is_array($p_filelist)) {
if (isset($p_filelist[0]) && is_array($p_filelist[0])) {
$v_att_list = $p_filelist;
}
else {
$v_string_list = $p_filelist;
}
}
else if (is_string($p_filelist)) {
$v_string_list = explode(PCLZIP_SEPARATOR, $p_filelist);
}
else {
PclZip::privErrorLog(PCLZIP_ERR_INVALID_PARAMETER, "Invalid variable type '".gettype($p_filelist)."' for p_filelist");
return 0;
}
if (sizeof($v_string_list) != 0) {
foreach ($v_string_list as $v_string) {
$v_att_list[][PCLZIP_ATT_FILE_NAME] = $v_string;
}
}
$v_supported_attributes
= array ( PCLZIP_ATT_FILE_NAME => 'mandatory'
,PCLZIP_ATT_FILE_NEW_SHORT_NAME => 'optional'
,PCLZIP_ATT_FILE_NEW_FULL_NAME => 'optional'
,PCLZIP_ATT_FILE_MTIME => 'optional'
,PCLZIP_ATT_FILE_CONTENT => 'optional'
,PCLZIP_ATT_FILE_COMMENT => 'optional'
);
foreach ($v_att_list as $v_entry) {
$v_result = $this->privFileDescrParseAtt($v_entry,
$v_filedescr_list[],
$v_options,
$v_supported_attributes);
if ($v_result != 1) {
return 0;
}
}
$v_result = $this->privFileDescrExpand($v_filedescr_list, $v_options);
if ($v_result != 1) {
return 0;
}*/
$v_result = $this->_privAdd($v_filedescr_list, $p_result_list, $v_options);
if ($v_result != 1) {
return 0;
}
return $p_result_list;
}
function _privAdd() {
}
$v_result = $this->privCreate($p_filedescr_list, $p_result_list, $p_options);
return $v_result;
}
$this->privDisableMagicQuotes();
if (($v_result=$this->privOpenFd('rb')) != 1)
{
$this->privSwapBackMagicQuotes();
return $v_result;
}
$v_central_dir = array();
if (($v_result = $this->privReadEndCentralDir($v_central_dir)) != 1)
{
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
return $v_result;
}
@rewind($this->zip_fd);
$v_zip_temp_name = PCLZIP_TEMPORARY_DIR.uniqid('pclzip-').'.tmp';
if (($v_zip_temp_fd = @fopen($v_zip_temp_name, 'wb')) == 0)
{
$this->privCloseFd();
$this->privSwapBackMagicQuotes();
PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open temporary file \''.$v_zip_temp_name.'\' in binary write mode');
return PclZip::errorCode();
}
$v_size = $v_central_dir['offset'];
while ($v_size != 0)
{
$v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
$v_buffer = fread($this->zip_fd, $v_read_size);
@fwrite($v_zip_temp_fd, $v_buffer, $v_read_size);
$v_size -= $v_read_size;
}
$v_swap = $this->zip_fd;
$this->zip_fd = $v_zip_temp_fd;
$v_zip_temp_fd = $v_swap;
global $tempheader;
$tempheader = '';
$tempheaderfile = PCLZIP_TEMPORARY_DIR.uniqid('softaculous').'.tmp';
if(($tempheader = @fopen($tempheaderfile, 'wb')) == 0){
fclose($v_zip_temp_fd);
$this->privCloseFd();
@unlink($v_zip_temp_name);
$this->privSwapBackMagicQuotes();
PclZip::privErrorLog(PCLZIP_ERR_READ_OPEN_FAIL, 'Unable to open temporary file \''.$tempheaderfile.'\' in binary write mode');
return 88888;
}
if (($v_result = $this->_privAddFileList($p_filedescr_list, $p_result_list, $p_options)) != 1)
{
fclose($v_zip_temp_fd);
$this->privCloseFd();
@unlink($v_zip_temp_name);
$this->privSwapBackMagicQuotes();
return $v_result;
}
$v_offset = @ftell($this->zip_fd);
$v_size = $v_central_dir['size'];
while ($v_size != 0)
{
$v_read_size = ($v_size < PCLZIP_READ_BLOCK_SIZE ? $v_size : PCLZIP_READ_BLOCK_SIZE);
$v_buffer = @fread($v_zip_temp_fd, $v_read_size);
@fwrite($this->zip_fd, $v_buffer, $v_read_size);
$v_size -= $v_read_size;
}
$v_count = $p_result_list;
@fclose($tempheader);
if(($tempfd = @fopen($tempheaderfile, "rb")) == 0){
fclose($v_zip_temp_fd);
$this->privCloseFd();
@unlink($v_zip_temp_name);
$this->privSwapBackMagicQuotes();
return 88888;
}
$t_size = filesize($tempheaderfile);
while ($t_size != 0)
{
$t_read_size = ($t_size < PCLZIP_READ_BLOCK_SIZE ? $t_size : PCLZIP_READ_BLOCK_SIZE);
$t_buffer = @fread($tempfd, $t_read_size);
@fwrite($this->zip_fd, $t_buffer, $t_read_size);
$t_size -= $t_read_size;
}
@fclose($tempfd);
@unlink($tempheaderfile);
$v_comment = $v_central_dir['comment'];
if (isset($p_options[PCLZIP_OPT_COMMENT])) {
$v_comment = $p_options[PCLZIP_OPT_COMMENT];
}
if (isset($p_options[PCLZIP_OPT_ADD_COMMENT])) {
$v_comment = $v_comment.$p_options[PCLZIP_OPT_ADD_COMMENT];
}
if (isset($p_options[PCLZIP_OPT_PREPEND_COMMENT])) {
$v_comment = $p_options[PCLZIP_OPT_PREPEND_COMMENT].$v_comment;
}
$v_size = @ftell($this->zip_fd)-$v_offset;
if (($v_result = $this->privWriteCentralHeader($v_count+$v_central_dir['entries'], $v_size, $v_offset, $v_comment)) != 1)
{
$this->privSwapBackMagicQuotes();
return $v_result;
}
$v_swap = $this->zip_fd;
$this->zip_fd = $v_zip_temp_fd;
$v_zip_temp_fd = $v_swap;
$this->privCloseFd();
@fclose($v_zip_temp_fd);
$this->privSwapBackMagicQuotes();
@unlink($this->zipname);
PclZipUtilRename($v_zip_temp_name, $this->zipname);
return $v_result;
}
function _privAddFileList() {
}
$p_result_list = $_soft_nb;
return $v_result;
}
function _privFileDescrExpand() {
}
else if (@is_dir($v_descr['filename'])) {
$v_descr['type'] = 'folder';
}
else if (@is_link($v_descr['filename'])) {
continue;
}
else {
continue;
}
}
else if (isset($v_descr['content'])) {
$v_descr['type'] = 'virtual_file';
}
else {
$error['read'] = lang_vars($l['could_not_read'], array($v_descr['filename']));
PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "File '".$v_descr['filename']."' does not exist");
return PclZip::errorCode();
}
$this->privCalculateStoredFilename($v_descr, $p_options);
global $_soft_result_list, $_soft_nb;
$_file = $v_descr;
$_file['filename'] = PclZipUtilTranslateWinPath($_file['filename'], false);
if ($_file['filename'] == "") {
continue;
}
if (   ($_file['type'] != 'virtual_file')
&& (!file_exists($_file['filename']))) {
PclZip::privErrorLog(PCLZIP_ERR_MISSING_FILE, "File '".$_file['filename']."' does not exist");
return PclZip::errorCode();
}
if (   ($_file['type'] == 'file')
|| ($_file['type'] == 'virtual_file')
|| (   ($_file['type'] == 'folder')
&& (   !isset($p_options[PCLZIP_OPT_REMOVE_ALL_PATH])
|| !$p_options[PCLZIP_OPT_REMOVE_ALL_PATH]))
) {
$v_header = array();
$v_result = $this->privAddFile($_file, $v_header, $p_options);
if ($v_result != 1) {
return $v_result;
}
if ($v_header['status'] == 'ok') {
if (($v_result = $this->_privWriteCentralFileHeader($v_header)) != 1) {
return $v_result;
}
$_soft_nb++;
}
unset($v_header);
}
$v_result_list[sizeof($v_result_list)] = $v_descr;
if ($v_descr['type'] == 'folder') {
$v_dirlist_descr = array();
$v_dirlist_nb = 0;
if (($v_folder_handler = @opendir($v_descr['filename']))) {
while (($v_item_handler = @readdir($v_folder_handler)) !== false) {
if (($v_item_handler == '.') || ($v_item_handler == '..')) {
continue;
}
$v_dirlist_descr[$v_dirlist_nb]['filename'] = $v_descr['filename'].'/'.$v_item_handler;
if (($v_descr['stored_filename'] != $v_descr['filename'])
&& (!isset($p_options[PCLZIP_OPT_REMOVE_ALL_PATH]))) {
if ($v_descr['stored_filename'] != '') {
$v_dirlist_descr[$v_dirlist_nb]['new_full_name'] = $v_descr['stored_filename'].'/'.$v_item_handler;
}
else {
$v_dirlist_descr[$v_dirlist_nb]['new_full_name'] = $v_item_handler;
}
}
$v_dirlist_nb++;
}
@closedir($v_folder_handler);
}
else {
}
if ($v_dirlist_nb != 0) {
if (($v_result = $this->_privFileDescrExpand($v_dirlist_descr, $p_options)) != 1) {
return $v_result;
}
}
else {
}
unset($v_dirlist_descr);
}
}
return $v_result;
}
function _privWriteCentralFileHeader() {
}
$v_date = getdate($p_header['mtime']);
$v_mtime = ($v_date['hours']<<11) + ($v_date['minutes']<<5) + $v_date['seconds']/2;
$v_mdate = (($v_date['year']-1980)<<9) + ($v_date['mon']<<5) + $v_date['mday'];
$v_binary_data = pack("VvvvvvvVVVvvvvvVV", 0x02014b50,
$p_header['version'], $p_header['version_extracted'],
$p_header['flag'], $p_header['compression'],
$v_mtime, $v_mdate, $p_header['crc'],
$p_header['compressed_size'], $p_header['size'],
strlen($p_header['stored_filename']),
$p_header['extra_len'], $p_header['comment_len'],
$p_header['disk'], $p_header['internal'],
$p_header['external'], $p_header['offset']);
fputs($tempheader, $v_binary_data, 46);
if (strlen($p_header['stored_filename']) != 0)
{
fputs($tempheader, $p_header['stored_filename'], strlen($p_header['stored_filename']));
}
if ($p_header['extra_len'] != 0)
{
fputs($tempheader, $p_header['extra'], $p_header['extra_len']);
}
if ($p_header['comment_len'] != 0)
{
fputs($tempheader, $p_header['comment'], $p_header['comment_len']);
}
return $v_result;
}
}