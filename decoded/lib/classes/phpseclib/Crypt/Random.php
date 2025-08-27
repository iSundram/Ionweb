<?php
* Random Number Generator
*
* The idea behind this function is that it can be easily replaced with your own crypt_random_string()
* function. eg. maybe you have a better source of entropy for creating the initial states or whatever.
*
* PHP versions 4 and 5
*
* Here's a short example of how to use this library:
* <code>
* <?php
*    include 'Crypt/Random.php';
*
*    echo bin2hex(crypt_random_string(8));
* ?>
* </code>
*
* LICENSE: Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*
* @category  Crypt
* @package   Crypt_Random
* @author    Jim Wigginton <terrafrost@php.net>
* @copyright 2007 Jim Wigginton
* @license   http://www.opensource.org/licenses/mit-license.html  MIT License
* @link      http://phpseclib.sourceforge.net
*/
if (!function_exists('crypt_random_string')) {
* "Is Windows" test
*
* @access private
*/
define('CRYPT_RANDOM_IS_WINDOWS', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
* Generate a random string.
*
* Although microoptimizations are generally discouraged as they impair readability this function is ripe with
* microoptimizations because this function has the potential of being called a huge number of times.
* eg. for RSA key generation.
*
* @param int $length
* @return string
* @access public
*/
function crypt_random_string() {
}
if (CRYPT_RANDOM_IS_WINDOWS) {
if (extension_loaded('mcrypt') && version_compare(PHP_VERSION, '5.3.0', '>=')) {
return @mcrypt_create_iv($length);
}
if (extension_loaded('openssl') && version_compare(PHP_VERSION, '5.3.4', '>=')) {
return openssl_random_pseudo_bytes($length);
}
} else {
if (extension_loaded('openssl') && version_compare(PHP_VERSION, '5.3.0', '>=')) {
return openssl_random_pseudo_bytes($length);
}
static $fp = true;
if ($fp === true) {
$fp = @fopen('/dev/urandom', 'rb');
}
if ($fp !== true && $fp !== false) { // surprisingly faster than !is_bool() or is_resource()
return fread($fp, $length);
}
if (extension_loaded('mcrypt')) {
return @mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
}
}
static $crypto = false, $v;
if ($crypto === false) {
$old_session_id = session_id();
$old_use_cookies = ini_get('session.use_cookies');
$old_session_cache_limiter = session_cache_limiter();
$_OLD_SESSION = isset($_SESSION) ? $_SESSION : false;
if ($old_session_id != '') {
session_write_close();
}
session_id(1);
ini_set('session.use_cookies', 0);
session_cache_limiter('');
session_start();
$v = $seed = $_SESSION['seed'] = pack('H*', sha1(
(isset($_SERVER) ? phpseclib_safe_serialize($_SERVER) : '') .
(isset($_POST) ? phpseclib_safe_serialize($_POST) : '') .
(isset($_GET) ? phpseclib_safe_serialize($_GET) : '') .
(isset($_COOKIE) ? phpseclib_safe_serialize($_COOKIE) : '') .
phpseclib_safe_serialize($GLOBALS) .
phpseclib_safe_serialize($_SESSION) .
phpseclib_safe_serialize($_OLD_SESSION)
));
if (!isset($_SESSION['count'])) {
$_SESSION['count'] = 0;
}
$_SESSION['count']++;
session_write_close();
if ($old_session_id != '') {
session_id($old_session_id);
session_start();
ini_set('session.use_cookies', $old_use_cookies);
session_cache_limiter($old_session_cache_limiter);
} else {
if ($_OLD_SESSION !== false) {
$_SESSION = $_OLD_SESSION;
unset($_OLD_SESSION);
} else {
unset($_SESSION);
}
}
$key = pack('H*', sha1($seed . 'A'));
$iv = pack('H*', sha1($seed . 'C'));
switch (true) {
case phpseclib_resolve_include_path('Crypt/AES.php'):
if (!class_exists('Crypt_AES')) {
include_once 'AES.php';
}
$crypto = new Crypt_AES(CRYPT_AES_MODE_CTR);
break;
case phpseclib_resolve_include_path('Crypt/Twofish.php'):
if (!class_exists('Crypt_Twofish')) {
include_once 'Twofish.php';
}
$crypto = new Crypt_Twofish(CRYPT_TWOFISH_MODE_CTR);
break;
case phpseclib_resolve_include_path('Crypt/Blowfish.php'):
if (!class_exists('Crypt_Blowfish')) {
include_once 'Blowfish.php';
}
$crypto = new Crypt_Blowfish(CRYPT_BLOWFISH_MODE_CTR);
break;
case phpseclib_resolve_include_path('Crypt/TripleDES.php'):
if (!class_exists('Crypt_TripleDES')) {
include_once 'TripleDES.php';
}
$crypto = new Crypt_TripleDES(CRYPT_DES_MODE_CTR);
break;
case phpseclib_resolve_include_path('Crypt/DES.php'):
if (!class_exists('Crypt_DES')) {
include_once 'DES.php';
}
$crypto = new Crypt_DES(CRYPT_DES_MODE_CTR);
break;
case phpseclib_resolve_include_path('Crypt/RC4.php'):
if (!class_exists('Crypt_RC4')) {
include_once 'RC4.php';
}
$crypto = new Crypt_RC4();
break;
default:
user_error('crypt_random_string requires at least one symmetric cipher be loaded');
return false;
}
$crypto->setKey($key);
$crypto->setIV($iv);
$crypto->enableContinuousBuffer();
}
$result = '';
while (strlen($result) < $length) {
$i = $crypto->encrypt(microtime()); // strlen(microtime()) == 21
$r = $crypto->encrypt($i ^ $v); // strlen($v) == 20
$v = $crypto->encrypt($r ^ $i); // strlen($r) == 20
$result.= $r;
}
return substr($result, 0, $length);
}
}
if (!function_exists('phpseclib_safe_serialize')) {
* Safely serialize variables
*
* If a class has a private __sleep() method it'll give a fatal error on PHP 5.2 and earlier.
* PHP 5.3 will emit a warning.
*
* @param mixed $arr
* @access public
*/
function phpseclib_safe_serialize() {
}
if (!is_array($arr)) {
return serialize($arr);
}
if (isset($arr['__phpseclib_marker'])) {
return '';
}
$safearr = array();
$arr['__phpseclib_marker'] = true;
foreach (array_keys($arr) as $key) {
if ($key !== '__phpseclib_marker') {
$safearr[$key] = phpseclib_safe_serialize($arr[$key]);
}
}
unset($arr['__phpseclib_marker']);
return serialize($safearr);
}
}
if (!function_exists('phpseclib_resolve_include_path')) {
* Resolve filename against the include path.
*
* Wrapper around stream_resolve_include_path() (which was introduced in
* PHP 5.3.2) with fallback implementation for earlier PHP versions.
*
* @param string $filename
* @return string|false
* @access public
*/
function phpseclib_resolve_include_path() {
}
if (file_exists($filename)) {
return realpath($filename);
}
$paths = PATH_SEPARATOR == ':' ?
preg_split('#(?<!phar):#', get_include_path()) :
explode(PATH_SEPARATOR, get_include_path());
foreach ($paths as $prefix) {
$ds = substr($prefix, -1) == DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR;
$file = $prefix . $ds . $filename;
if (file_exists($file)) {
return realpath($file);
}
}
return false;
}
}