<?php
* Default elFinder connector
*
* @author Dmitry (dio) Levashov
**/
class elFinderConnector
{
* elFinder instance
*
* @var elFinder
**/
protected $elFinder;
* Options
*
* @var array
**/
protected $options = array();
* Must be use output($data) $data['header']
*
* @var string
* @deprecated
**/
protected $header = '';
* HTTP request method
*
* @var string
*/
protected $reqMethod = '';
* Content type of output JSON
*
* @var string
*/
protected static $contentType = 'Content-Type: application/json; charset=utf-8';
* Constructor
*
* @param      $elFinder
* @param bool $debug
*
* @author Dmitry (dio) Levashov
*/
public function __construct() {
}
}
* Execute elFinder command and output result
*
* @return void
* @throws Exception
* @author Dmitry (dio) Levashov
*/
public function run() {
}
if ($idx) {
$src[$key][$idx] = rawurldecode($value);
} else {
$src[$key][] = rawurldecode($value);
}
} else {
$src[$key] = rawurldecode($value);
}
}
$_POST = $this->input_filter($src);
$_REQUEST = $this->input_filter(array_merge_recursive($src, $_REQUEST));
}
}
if (isset($src['targets']) && $this->elFinder->maxTargets && count($src['targets']) > $this->elFinder->maxTargets) {
$this->output(array('error' => $this->elFinder->error(elFinder::ERROR_MAX_TARGTES)));
}
$cmd = isset($src['cmd']) ? $src['cmd'] : '';
$args = array();
if (!function_exists('json_encode')) {
$error = $this->elFinder->error(elFinder::ERROR_CONF, elFinder::ERROR_CONF_NO_JSON);
$this->output(array('error' => '{"error":["' . implode('","', $error) . '"]}', 'raw' => true));
}
if (!$this->elFinder->loaded()) {
$this->output(array('error' => $this->elFinder->error(elFinder::ERROR_CONF, elFinder::ERROR_CONF_NO_VOL), 'debug' => $this->elFinder->mountErrors));
}
if (!$cmd && $isPost) {
$this->output(array('error' => $this->elFinder->error(elFinder::ERROR_UPLOAD, elFinder::ERROR_UPLOAD_TOTAL_SIZE), 'header' => 'Content-Type: text/html'));
}
if (!$this->elFinder->commandExists($cmd)) {
$this->output(array('error' => $this->elFinder->error(elFinder::ERROR_UNKNOWN_CMD)));
}
$hasFiles = false;
foreach ($this->elFinder->commandArgsList($cmd) as $name => $req) {
if ($name === 'FILES') {
if (isset($_FILES)) {
$hasFiles = true;
} elseif ($req) {
$this->output(array('error' => $this->elFinder->error(elFinder::ERROR_INV_PARAMS, $cmd)));
}
} else {
$arg = isset($src[$name]) ? $src[$name] : '';
if (!is_array($arg) && $req !== '') {
$arg = trim($arg);
}
if ($req && $arg === '') {
$this->output(array('error' => $this->elFinder->error(elFinder::ERROR_INV_PARAMS, $cmd)));
}
$args[$name] = $arg;
}
}
$args['debug'] = isset($src['debug']) ? !!$src['debug'] : false;
$args = $this->input_filter($args);
if ($hasFiles) {
$args['FILES'] = $_FILES;
}
try {
$this->output($this->elFinder->exec($cmd, $args));
} catch (elFinderAbortException $e) {
$this->elFinder->getSession()->close();
header('HTTP/1.0 204 No Content');
while (ob_get_level() && ob_end_clean()) {
}
exit();
}
}
* Sets the header.
*
* @param array|string  $value HTTP header(s)
*/
public function setHeader() {
}
* Output json
*
* @param  array  data to output
*
* @return void
* @throws elFinderAbortException
* @author Dmitry (dio) Levashov
*/
protected function output() {
}
if (isset($data['pointer'])) {
elFinder::extendTimeLimit(0);
if (!empty($data['header'])) {
self::sendHeader($data['header']);
}
while (ob_get_level() && ob_end_clean()) {
}
$toEnd = true;
$fp = $data['pointer'];
$sendData = !($this->reqMethod === 'HEAD' || !empty($data['info']['xsendfile']));
$psize = null;
if (($this->reqMethod === 'GET' || !$sendData)
&& (elFinder::isSeekableStream($fp) || elFinder::isSeekableUrl($fp))
&& (array_search('Accept-Ranges: none', headers_list()) === false)) {
header('Accept-Ranges: bytes');
if (!empty($_SERVER['HTTP_RANGE'])) {
$size = $data['info']['size'];
$end = $size - 1;
if (preg_match('/bytes=(\d*)-(\d*)(,?)/i', $_SERVER['HTTP_RANGE'], $matches)) {
if (empty($matches[3])) {
if (empty($matches[1]) && $matches[1] !== '0') {
$start = $size - $matches[2];
} else {
$start = intval($matches[1]);
if (!empty($matches[2])) {
$end = intval($matches[2]);
if ($end >= $size) {
$end = $size - 1;
}
$toEnd = ($end == ($size - 1));
}
}
$psize = $end - $start + 1;
header('HTTP/1.1 206 Partial Content');
header('Content-Length: ' . $psize);
header('Content-Range: bytes ' . $start . '-' . $end . '/' . $size);
if (isset($data['info']['xsendfile']) && strtolower($data['info']['xsendfile']) === 'x-sendfile') {
if (function_exists('header_remove')) {
header_remove($data['info']['xsendfile']);
} else {
header($data['info']['xsendfile'] . ':');
}
unset($data['info']['xsendfile']);
if ($this->reqMethod !== 'HEAD') {
$sendData = true;
}
}
$sendData && !elFinder::isSeekableUrl($fp) && fseek($fp, $start);
}
}
}
if ($sendData && is_null($psize)) {
elFinder::rewind($fp);
}
} else {
header('Accept-Ranges: none');
if (isset($data['info']) && !$data['info']['size']) {
if (function_exists('header_remove')) {
header_remove('Content-Length');
} else {
header('Content-Length:');
}
}
}
if ($sendData) {
if ($toEnd || elFinder::isSeekableUrl($fp)) {
if (version_compare(PHP_VERSION, '5.6', '<')) {
file_put_contents('php://output', $fp);
} else {
fpassthru($fp);
}
} else {
$out = fopen('php://output', 'wb');
stream_copy_to_stream($fp, $out, $psize);
fclose($out);
}
}
if (!empty($data['volume'])) {
$data['volume']->close($fp, $data['info']['hash']);
} else {
fclose($fp);
}
exit();
} else {
self::outputJson($data);
exit(0);
}
}
* Remove null & stripslashes applies on "magic_quotes_gpc"
*
* @param  mixed $args
*
* @return mixed
* @author Naoki Sawada
*/
protected function input_filter() {
}
$res = str_replace("\0", '', $args);
$magic_quotes_gpc && ($res = stripslashes($res));
return $res;
}
* Send HTTP header
*
* @param string|array $header optional header
*/
protected static function sendHeader() {
}
} else {
header($header);
}
}
}
* Output JSON
*
* @param array $data
*/
public static function outputJson() {
} else {
if (isset($data['debug']) && isset($data['debug']['backendErrors'])) {
$data['debug']['backendErrors'] = array_merge($data['debug']['backendErrors'], elFinder::$phpErrors);
}
$out = json_encode($data);
}
while (ob_get_level() && ob_end_clean()) {
}
header('Content-Length: ' . strlen($out));
echo $out;
flush();
}
}// END class