<?php
* Pure-PHP implementation of SSHv1.
*
* PHP versions 4 and 5
*
* Here's a short example of how to use this library:
* <code>
* <?php
*    include 'Net/SSH1.php';
*
*    $ssh = new Net_SSH1('www.domain.tld');
*    if (!$ssh->login('username', 'password')) {
*        exit('Login Failed');
*    }
*
*    echo $ssh->exec('ls -la');
* ?>
* </code>
*
* Here's another short example:
* <code>
* <?php
*    include 'Net/SSH1.php';
*
*    $ssh = new Net_SSH1('www.domain.tld');
*    if (!$ssh->login('username', 'password')) {
*        exit('Login Failed');
*    }
*
*    echo $ssh->read('username@username:~$');
*    $ssh->write("ls -la\n");
*    echo $ssh->read('username@username:~$');
* ?>
* </code>
*
* More information on the SSHv1 specification can be found by reading
* {@link http://www.snailbook.com/docs/protocol-1.5.txt protocol-1.5.txt}.
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
* @category  Net
* @package   Net_SSH1
* @author    Jim Wigginton <terrafrost@php.net>
* @copyright 2007 Jim Wigginton
* @license   http://www.opensource.org/licenses/mit-license.html  MIT License
* @link      http://phpseclib.sourceforge.net
*/
* Encryption Methods
*
* @see self::getSupportedCiphers()
* @access public
*/
* No encryption
*
* Not supported.
*/
define('NET_SSH1_CIPHER_NONE',       0);
* IDEA in CFB mode
*
* Not supported.
*/
define('NET_SSH1_CIPHER_IDEA',       1);
* DES in CBC mode
*/
define('NET_SSH1_CIPHER_DES',        2);
* Triple-DES in CBC mode
*
* All implementations are required to support this
*/
define('NET_SSH1_CIPHER_3DES',       3);
* TRI's Simple Stream encryption CBC
*
* Not supported nor is it defined in the official SSH1 specs.  OpenSSH, however, does define it (see cipher.h),
* although it doesn't use it (see cipher.c)
*/
define('NET_SSH1_CIPHER_BROKEN_TSS', 4);
* RC4
*
* Not supported.
*
* @internal According to the SSH1 specs:
*
*        "The first 16 bytes of the session key are used as the key for
*         the server to client direction.  The remaining 16 bytes are used
*         as the key for the client to server direction.  This gives
*         independent 128-bit keys for each direction."
*
*     This library currently only supports encryption when the same key is being used for both directions.  This is
*     because there's only one $crypto object.  Two could be added ($encrypt and $decrypt, perhaps).
*/
define('NET_SSH1_CIPHER_RC4',        5);
* Blowfish
*
* Not supported nor is it defined in the official SSH1 specs.  OpenSSH, however, defines it (see cipher.h) and
* uses it (see cipher.c)
*/
define('NET_SSH1_CIPHER_BLOWFISH',   6);
* Authentication Methods
*
* @see self::getSupportedAuthentications()
* @access public
*/
* .rhosts or /etc/hosts.equiv
*/
define('NET_SSH1_AUTH_RHOSTS',     1);
* pure RSA authentication
*/
define('NET_SSH1_AUTH_RSA',        2);
* password authentication
*
* This is the only method that is supported by this library.
*/
define('NET_SSH1_AUTH_PASSWORD',   3);
* .rhosts with RSA host authentication
*/
define('NET_SSH1_AUTH_RHOSTS_RSA', 4);
* Terminal Modes
*
* @link http://3sp.com/content/developer/maverick-net/docs/Maverick.SSH.PseudoTerminalModesMembers.html
* @access private
*/
define('NET_SSH1_TTY_OP_END',  0);
* The Response Type
*
* @see self::_get_binary_packet()
* @access private
*/
define('NET_SSH1_RESPONSE_TYPE', 1);
* The Response Data
*
* @see self::_get_binary_packet()
* @access private
*/
define('NET_SSH1_RESPONSE_DATA', 2);
* Execution Bitmap Masks
*
* @see self::bitmap
* @access private
*/
define('NET_SSH1_MASK_CONSTRUCTOR', 0x00000001);
define('NET_SSH1_MASK_CONNECTED',   0x00000002);
define('NET_SSH1_MASK_LOGIN',       0x00000004);
define('NET_SSH1_MASK_SHELL',       0x00000008);
* @access public
* @see self::getLog()
*/
* Returns the message numbers
*/
define('NET_SSH1_LOG_SIMPLE',  1);
* Returns the message content
*/
define('NET_SSH1_LOG_COMPLEX', 2);
* Outputs the content real-time
*/
define('NET_SSH1_LOG_REALTIME', 3);
* Dumps the content real-time to a file
*/
define('NET_SSH1_LOG_REALTIME_FILE', 4);
* @access public
* @see self::read()
*/
* Returns when a string matching $expect exactly is found
*/
define('NET_SSH1_READ_SIMPLE',  1);
* Returns when a string matching the regular expression $expect is found
*/
define('NET_SSH1_READ_REGEX', 2);
* Pure-PHP implementation of SSHv1.
*
* @package Net_SSH1
* @author  Jim Wigginton <terrafrost@php.net>
* @access  public
*/
class Net_SSH1
{
* The SSH identifier
*
* @var string
* @access private
*/
var $identifier = 'SSH-1.5-phpseclib';
* The Socket Object
*
* @var object
* @access private
*/
var $fsock;
* The cryptography object
*
* @var object
* @access private
*/
var $crypto = false;
* Execution Bitmap
*
* The bits that are set represent functions that have been called already.  This is used to determine
* if a requisite function has been successfully executed.  If not, an error should be thrown.
*
* @var int
* @access private
*/
var $bitmap = 0;
* The Server Key Public Exponent
*
* Logged for debug purposes
*
* @see self::getServerKeyPublicExponent()
* @var string
* @access private
*/
var $server_key_public_exponent;
* The Server Key Public Modulus
*
* Logged for debug purposes
*
* @see self::getServerKeyPublicModulus()
* @var string
* @access private
*/
var $server_key_public_modulus;
* The Host Key Public Exponent
*
* Logged for debug purposes
*
* @see self::getHostKeyPublicExponent()
* @var string
* @access private
*/
var $host_key_public_exponent;
* The Host Key Public Modulus
*
* Logged for debug purposes
*
* @see self::getHostKeyPublicModulus()
* @var string
* @access private
*/
var $host_key_public_modulus;
* Supported Ciphers
*
* Logged for debug purposes
*
* @see self::getSupportedCiphers()
* @var array
* @access private
*/
var $supported_ciphers = array(
NET_SSH1_CIPHER_NONE       => 'No encryption',
NET_SSH1_CIPHER_IDEA       => 'IDEA in CFB mode',
NET_SSH1_CIPHER_DES        => 'DES in CBC mode',
NET_SSH1_CIPHER_3DES       => 'Triple-DES in CBC mode',
NET_SSH1_CIPHER_BROKEN_TSS => 'TRI\'s Simple Stream encryption CBC',
NET_SSH1_CIPHER_RC4        => 'RC4',
NET_SSH1_CIPHER_BLOWFISH   => 'Blowfish'
);
* Supported Authentications
*
* Logged for debug purposes
*
* @see self::getSupportedAuthentications()
* @var array
* @access private
*/
var $supported_authentications = array(
NET_SSH1_AUTH_RHOSTS     => '.rhosts or /etc/hosts.equiv',
NET_SSH1_AUTH_RSA        => 'pure RSA authentication',
NET_SSH1_AUTH_PASSWORD   => 'password authentication',
NET_SSH1_AUTH_RHOSTS_RSA => '.rhosts with RSA host authentication'
);
* Server Identification
*
* @see self::getServerIdentification()
* @var string
* @access private
*/
var $server_identification = '';
* Protocol Flags
*
* @see self::Net_SSH1()
* @var array
* @access private
*/
var $protocol_flags = array();
* Protocol Flag Log
*
* @see self::getLog()
* @var array
* @access private
*/
var $protocol_flag_log = array();
* Message Log
*
* @see self::getLog()
* @var array
* @access private
*/
var $message_log = array();
* Real-time log file pointer
*
* @see self::_append_log()
* @var resource
* @access private
*/
var $realtime_log_file;
* Real-time log file size
*
* @see self::_append_log()
* @var int
* @access private
*/
var $realtime_log_size;
* Real-time log file wrap boolean
*
* @see self::_append_log()
* @var bool
* @access private
*/
var $realtime_log_wrap;
* Interactive Buffer
*
* @see self::read()
* @var array
* @access private
*/
var $interactiveBuffer = '';
* Timeout
*
* @see self::setTimeout()
* @access private
*/
var $timeout;
* Current Timeout
*
* @see self::_get_channel_packet()
* @access private
*/
var $curTimeout;
* Log Boundary
*
* @see self::_format_log()
* @access private
*/
var $log_boundary = ':';
* Log Long Width
*
* @see self::_format_log()
* @access private
*/
var $log_long_width = 65;
* Log Short Width
*
* @see self::_format_log()
* @access private
*/
var $log_short_width = 16;
* Hostname
*
* @see self::Net_SSH1()
* @see self::_connect()
* @var string
* @access private
*/
var $host;
* Port Number
*
* @see self::Net_SSH1()
* @see self::_connect()
* @var int
* @access private
*/
var $port;
* Timeout for initial connection
*
* Set by the constructor call. Calling setTimeout() is optional. If it's not called functions like
* exec() won't timeout unless some PHP setting forces it too. The timeout specified in the constructor,
* however, is non-optional. There will be a timeout, whether or not you set it. If you don't it'll be
* 10 seconds. It is used by fsockopen() in that function.
*
* @see self::Net_SSH1()
* @see self::_connect()
* @var int
* @access private
*/
var $connectionTimeout;
* Default cipher
*
* @see self::Net_SSH1()
* @see self::_connect()
* @var int
* @access private
*/
var $cipher;
* Default Constructor.
*
* Connects to an SSHv1 server
*
* @param string $host
* @param int $port
* @param int $timeout
* @param int $cipher
* @return Net_SSH1
* @access public
*/
function __construct() {
}
if (!function_exists('crypt_random_string') && !class_exists('Crypt_Random') && !function_exists('crypt_random_string')) {
include_once 'Crypt/Random.php';
}
$this->protocol_flags = array(
1  => 'NET_SSH1_MSG_DISCONNECT',
2  => 'NET_SSH1_SMSG_PUBLIC_KEY',
3  => 'NET_SSH1_CMSG_SESSION_KEY',
4  => 'NET_SSH1_CMSG_USER',
9  => 'NET_SSH1_CMSG_AUTH_PASSWORD',
10 => 'NET_SSH1_CMSG_REQUEST_PTY',
12 => 'NET_SSH1_CMSG_EXEC_SHELL',
13 => 'NET_SSH1_CMSG_EXEC_CMD',
14 => 'NET_SSH1_SMSG_SUCCESS',
15 => 'NET_SSH1_SMSG_FAILURE',
16 => 'NET_SSH1_CMSG_STDIN_DATA',
17 => 'NET_SSH1_SMSG_STDOUT_DATA',
18 => 'NET_SSH1_SMSG_STDERR_DATA',
19 => 'NET_SSH1_CMSG_EOF',
20 => 'NET_SSH1_SMSG_EXITSTATUS',
33 => 'NET_SSH1_CMSG_EXIT_CONFIRMATION'
);
$this->_define_array($this->protocol_flags);
$this->host = $host;
$this->port = $port;
$this->connectionTimeout = $timeout;
$this->cipher = $cipher;
}
* PHP4 compatible Default Constructor.
*
* @see self::__construct()
* @param string $host
* @param int $port
* @param int $timeout
* @param int $cipher
* @access public
*/
function Net_SSH1() {
}
* Connect to an SSHv1 server
*
* @return bool
* @access private
*/
function _connect() {
}:{$this->port}. Error $errno. $errstr"));
return false;
}
$this->server_identification = $init_line = fgets($this->fsock, 255);
if (defined('NET_SSH1_LOGGING')) {
$this->_append_log('<-', $this->server_identification);
$this->_append_log('->', $this->identifier . "\r\n");
}
if (!preg_match('#SSH-([0-9\.]+)-(.+)#', $init_line, $parts)) {
user_error('Can only connect to SSH servers');
return false;
}
if ($parts[1][0] != 1) {
user_error("Cannot connect to SSH $parts[1] servers");
return false;
}
fputs($this->fsock, $this->identifier."\r\n");
$response = $this->_get_binary_packet();
if ($response[NET_SSH1_RESPONSE_TYPE] != NET_SSH1_SMSG_PUBLIC_KEY) {
user_error('Expected SSH_SMSG_PUBLIC_KEY');
return false;
}
$anti_spoofing_cookie = $this->_string_shift($response[NET_SSH1_RESPONSE_DATA], 8);
$this->_string_shift($response[NET_SSH1_RESPONSE_DATA], 4);
if (strlen($response[NET_SSH1_RESPONSE_DATA]) < 2) {
return false;
}
$temp = unpack('nlen', $this->_string_shift($response[NET_SSH1_RESPONSE_DATA], 2));
$server_key_public_exponent = new Math_BigInteger($this->_string_shift($response[NET_SSH1_RESPONSE_DATA], ceil($temp['len'] / 8)), 256);
$this->server_key_public_exponent = $server_key_public_exponent;
if (strlen($response[NET_SSH1_RESPONSE_DATA]) < 2) {
return false;
}
$temp = unpack('nlen', $this->_string_shift($response[NET_SSH1_RESPONSE_DATA], 2));
$server_key_public_modulus = new Math_BigInteger($this->_string_shift($response[NET_SSH1_RESPONSE_DATA], ceil($temp['len'] / 8)), 256);
$this->server_key_public_modulus = $server_key_public_modulus;
$this->_string_shift($response[NET_SSH1_RESPONSE_DATA], 4);
if (strlen($response[NET_SSH1_RESPONSE_DATA]) < 2) {
return false;
}
$temp = unpack('nlen', $this->_string_shift($response[NET_SSH1_RESPONSE_DATA], 2));
$host_key_public_exponent = new Math_BigInteger($this->_string_shift($response[NET_SSH1_RESPONSE_DATA], ceil($temp['len'] / 8)), 256);
$this->host_key_public_exponent = $host_key_public_exponent;
if (strlen($response[NET_SSH1_RESPONSE_DATA]) < 2) {
return false;
}
$temp = unpack('nlen', $this->_string_shift($response[NET_SSH1_RESPONSE_DATA], 2));
$host_key_public_modulus = new Math_BigInteger($this->_string_shift($response[NET_SSH1_RESPONSE_DATA], ceil($temp['len'] / 8)), 256);
$this->host_key_public_modulus = $host_key_public_modulus;
$this->_string_shift($response[NET_SSH1_RESPONSE_DATA], 4);
if (strlen($response[NET_SSH1_RESPONSE_DATA]) < 4) {
return false;
}
extract(unpack('Nsupported_ciphers_mask', $this->_string_shift($response[NET_SSH1_RESPONSE_DATA], 4)));
foreach ($this->supported_ciphers as $mask => $name) {
if (($supported_ciphers_mask & (1 << $mask)) == 0) {
unset($this->supported_ciphers[$mask]);
}
}
if (strlen($response[NET_SSH1_RESPONSE_DATA]) < 4) {
return false;
}
extract(unpack('Nsupported_authentications_mask', $this->_string_shift($response[NET_SSH1_RESPONSE_DATA], 4)));
foreach ($this->supported_authentications as $mask => $name) {
if (($supported_authentications_mask & (1 << $mask)) == 0) {
unset($this->supported_authentications[$mask]);
}
}
$session_id = pack('H*', md5($host_key_public_modulus->toBytes() . $server_key_public_modulus->toBytes() . $anti_spoofing_cookie));
$session_key = crypt_random_string(32);
$double_encrypted_session_key = $session_key ^ str_pad($session_id, 32, chr(0));
if ($server_key_public_modulus->compare($host_key_public_modulus) < 0) {
$double_encrypted_session_key = $this->_rsa_crypt(
$double_encrypted_session_key,
array(
$server_key_public_exponent,
$server_key_public_modulus
)
);
$double_encrypted_session_key = $this->_rsa_crypt(
$double_encrypted_session_key,
array(
$host_key_public_exponent,
$host_key_public_modulus
)
);
} else {
$double_encrypted_session_key = $this->_rsa_crypt(
$double_encrypted_session_key,
array(
$host_key_public_exponent,
$host_key_public_modulus
)
);
$double_encrypted_session_key = $this->_rsa_crypt(
$double_encrypted_session_key,
array(
$server_key_public_exponent,
$server_key_public_modulus
)
);
}
$cipher = isset($this->supported_ciphers[$this->cipher]) ? $this->cipher : NET_SSH1_CIPHER_3DES;
$data = pack('C2a*na*N', NET_SSH1_CMSG_SESSION_KEY, $cipher, $anti_spoofing_cookie, 8 * strlen($double_encrypted_session_key), $double_encrypted_session_key, 0);
if (!$this->_send_binary_packet($data)) {
user_error('Error sending SSH_CMSG_SESSION_KEY');
return false;
}
switch ($cipher) {
case NET_SSH1_CIPHER_DES:
if (!class_exists('Crypt_DES')) {
include_once 'Crypt/DES.php';
}
$this->crypto = new Crypt_DES();
$this->crypto->disablePadding();
$this->crypto->enableContinuousBuffer();
$this->crypto->setKey(substr($session_key, 0, 8));
break;
case NET_SSH1_CIPHER_3DES:
if (!class_exists('Crypt_TripleDES')) {
include_once 'Crypt/TripleDES.php';
}
$this->crypto = new Crypt_TripleDES(CRYPT_DES_MODE_3CBC);
$this->crypto->disablePadding();
$this->crypto->enableContinuousBuffer();
$this->crypto->setKey(substr($session_key, 0, 24));
break;
}
$response = $this->_get_binary_packet();
if ($response[NET_SSH1_RESPONSE_TYPE] != NET_SSH1_SMSG_SUCCESS) {
user_error('Expected SSH_SMSG_SUCCESS');
return false;
}
$this->bitmap = NET_SSH1_MASK_CONNECTED;
return true;
}
* Login
*
* @param string $username
* @param string $password
* @return bool
* @access public
*/
function login() {
}
}
if (!($this->bitmap & NET_SSH1_MASK_CONNECTED)) {
return false;
}
$data = pack('CNa*', NET_SSH1_CMSG_USER, strlen($username), $username);
if (!$this->_send_binary_packet($data)) {
user_error('Error sending SSH_CMSG_USER');
return false;
}
$response = $this->_get_binary_packet();
if ($response === true) {
return false;
}
if ($response[NET_SSH1_RESPONSE_TYPE] == NET_SSH1_SMSG_SUCCESS) {
$this->bitmap |= NET_SSH1_MASK_LOGIN;
return true;
} elseif ($response[NET_SSH1_RESPONSE_TYPE] != NET_SSH1_SMSG_FAILURE) {
user_error('Expected SSH_SMSG_SUCCESS or SSH_SMSG_FAILURE');
return false;
}
$data = pack('CNa*', NET_SSH1_CMSG_AUTH_PASSWORD, strlen($password), $password);
if (!$this->_send_binary_packet($data)) {
user_error('Error sending SSH_CMSG_AUTH_PASSWORD');
return false;
}
if (defined('NET_SSH1_LOGGING') && NET_SSH1_LOGGING == NET_SSH1_LOG_COMPLEX) {
$data = pack('CNa*', NET_SSH1_CMSG_AUTH_PASSWORD, strlen('password'), 'password');
$this->message_log[count($this->message_log) - 1] = $data;
}
$response = $this->_get_binary_packet();
if ($response === true) {
return false;
}
if ($response[NET_SSH1_RESPONSE_TYPE] == NET_SSH1_SMSG_SUCCESS) {
$this->bitmap |= NET_SSH1_MASK_LOGIN;
return true;
} elseif ($response[NET_SSH1_RESPONSE_TYPE] == NET_SSH1_SMSG_FAILURE) {
return false;
} else {
user_error('Expected SSH_SMSG_SUCCESS or SSH_SMSG_FAILURE');
return false;
}
}
* Set Timeout
*
* $ssh->exec('ping 127.0.0.1'); on a Linux host will never return and will run indefinitely.  setTimeout() makes it so it'll timeout.
* Setting $timeout to false or 0 will mean there is no timeout.
*
* @param mixed $timeout
*/
function setTimeout() {
}
* Executes a command on a non-interactive shell, returns the output, and quits.
*
* An SSH1 server will close the connection after a command has been executed on a non-interactive shell.  SSH2
* servers don't, however, this isn't an SSH2 client.  The way this works, on the server, is by initiating a
* shell with the -s option, as discussed in the following links:
*
* {@link http://www.faqs.org/docs/bashman/bashref_65.html http://www.faqs.org/docs/bashman/bashref_65.html}
* {@link http://www.faqs.org/docs/bashman/bashref_62.html http://www.faqs.org/docs/bashman/bashref_62.html}
*
* To execute further commands, a new Net_SSH1 object will need to be created.
*
* Returns false on failure and the output, otherwise.
*
* @see self::interactiveRead()
* @see self::interactiveWrite()
* @param string $cmd
* @return mixed
* @access public
*/
function exec() {
}
$data = pack('CNa*', NET_SSH1_CMSG_EXEC_CMD, strlen($cmd), $cmd);
if (!$this->_send_binary_packet($data)) {
user_error('Error sending SSH_CMSG_EXEC_CMD');
return false;
}
if (!$block) {
return true;
}
$output = '';
$response = $this->_get_binary_packet();
if ($response !== false) {
do {
$output.= substr($response[NET_SSH1_RESPONSE_DATA], 4);
$response = $this->_get_binary_packet();
} while (is_array($response) && $response[NET_SSH1_RESPONSE_TYPE] != NET_SSH1_SMSG_EXITSTATUS);
}
$data = pack('C', NET_SSH1_CMSG_EXIT_CONFIRMATION);
$this->_send_binary_packet($data);
fclose($this->fsock);
$this->bitmap = 0;
return $output;
}
* Creates an interactive shell
*
* @see self::interactiveRead()
* @see self::interactiveWrite()
* @return bool
* @access private
*/
function _initShell() {
}
$response = $this->_get_binary_packet();
if ($response === true) {
return false;
}
if ($response[NET_SSH1_RESPONSE_TYPE] != NET_SSH1_SMSG_SUCCESS) {
user_error('Expected SSH_SMSG_SUCCESS');
return false;
}
$data = pack('C', NET_SSH1_CMSG_EXEC_SHELL);
if (!$this->_send_binary_packet($data)) {
user_error('Error sending SSH_CMSG_EXEC_SHELL');
return false;
}
$this->bitmap |= NET_SSH1_MASK_SHELL;
return true;
}
* Inputs a command into an interactive shell.
*
* @see self::interactiveWrite()
* @param string $cmd
* @return bool
* @access public
*/
function write() {
}
* Returns the output of an interactive shell when there's a match for $expect
*
* $expect can take the form of a string literal or, if $mode == NET_SSH1_READ_REGEX,
* a regular expression.
*
* @see self::write()
* @param string $expect
* @param int $mode
* @return bool
* @access public
*/
function read() {
}
if (!($this->bitmap & NET_SSH1_MASK_SHELL) && !$this->_initShell()) {
user_error('Unable to initiate an interactive shell session');
return false;
}
$match = $expect;
while (true) {
if ($mode == NET_SSH1_READ_REGEX) {
preg_match($expect, $this->interactiveBuffer, $matches);
$match = isset($matches[0]) ? $matches[0] : '';
}
$pos = strlen($match) ? strpos($this->interactiveBuffer, $match) : false;
if ($pos !== false) {
return $this->_string_shift($this->interactiveBuffer, $pos + strlen($match));
}
$response = $this->_get_binary_packet();
if ($response === true) {
return $this->_string_shift($this->interactiveBuffer, strlen($this->interactiveBuffer));
}
$this->interactiveBuffer.= substr($response[NET_SSH1_RESPONSE_DATA], 4);
}
}
* Inputs a command into an interactive shell.
*
* @see self::interactiveRead()
* @param string $cmd
* @return bool
* @access public
*/
function interactiveWrite() {
}
if (!($this->bitmap & NET_SSH1_MASK_SHELL) && !$this->_initShell()) {
user_error('Unable to initiate an interactive shell session');
return false;
}
$data = pack('CNa*', NET_SSH1_CMSG_STDIN_DATA, strlen($cmd), $cmd);
if (!$this->_send_binary_packet($data)) {
user_error('Error sending SSH_CMSG_STDIN');
return false;
}
return true;
}
* Returns the output of an interactive shell when no more output is available.
*
* Requires PHP 4.3.0 or later due to the use of the stream_select() function.  If you see stuff like
* "^[[00m", you're seeing ANSI escape codes.  According to
* {@link http://support.microsoft.com/kb/101875 How to Enable ANSI.SYS in a Command Window}, "Windows NT
* does not support ANSI escape sequences in Win32 Console applications", so if you're a Windows user,
* there's not going to be much recourse.
*
* @see self::interactiveRead()
* @return string
* @access public
*/
function interactiveRead() {
}
if (!($this->bitmap & NET_SSH1_MASK_SHELL) && !$this->_initShell()) {
user_error('Unable to initiate an interactive shell session');
return false;
}
$read = array($this->fsock);
$write = $except = null;
if (stream_select($read, $write, $except, 0)) {
$response = $this->_get_binary_packet();
return substr($response[NET_SSH1_RESPONSE_DATA], 4);
} else {
return '';
}
}
* Disconnect
*
* @access public
*/
function disconnect() {
}
* Destructor.
*
* Will be called, automatically, if you're supporting just PHP5.  If you're supporting PHP4, you'll need to call
* disconnect().
*
* @access public
*/
function __destruct() {
}
* Disconnect
*
* @param string $msg
* @access private
*/
function _disconnect() {
}
switch ($response[NET_SSH1_RESPONSE_TYPE]) {
case NET_SSH1_SMSG_EXITSTATUS:
$data = pack('C', NET_SSH1_CMSG_EXIT_CONFIRMATION);
break;
default:
$data = pack('CNa*', NET_SSH1_MSG_DISCONNECT, strlen($msg), $msg);
}
*/
$data = pack('CNa*', NET_SSH1_MSG_DISCONNECT, strlen($msg), $msg);
$this->_send_binary_packet($data);
fclose($this->fsock);
$this->bitmap = 0;
}
}
* Gets Binary Packets
*
* See 'The Binary Packet Protocol' of protocol-1.5.txt for more info.
*
* Also, this function could be improved upon by adding detection for the following exploit:
* http://www.securiteam.com/securitynews/5LP042K3FY.html
*
* @see self::_send_binary_packet()
* @return array
* @access private
*/
function _get_binary_packet() {
}
if ($this->curTimeout) {
$read = array($this->fsock);
$write = $except = null;
$start = strtok(microtime(), ' ') + strtok(''); // http://php.net/microtime#61838
$sec = floor($this->curTimeout);
$usec = 1000000 * ($this->curTimeout - $sec);
if (!@stream_select($read, $write, $except, $sec, $usec) && !count($read)) {
return true;
}
$elapsed = strtok(microtime(), ' ') + strtok('') - $start;
$this->curTimeout-= $elapsed;
}
$start = strtok(microtime(), ' ') + strtok(''); // http://php.net/microtime#61838
$data = fread($this->fsock, 4);
if (strlen($data) < 4) {
return false;
}
$temp = unpack('Nlength', $data);
$padding_length = 8 - ($temp['length'] & 7);
$length = $temp['length'] + $padding_length;
$raw = '';
while ($length > 0) {
$temp = fread($this->fsock, $length);
$raw.= $temp;
$length-= strlen($temp);
}
$stop = strtok(microtime(), ' ') + strtok('');
if (strlen($raw) && $this->crypto !== false) {
$raw = $this->crypto->decrypt($raw);
}
$padding = substr($raw, 0, $padding_length);
$type = $raw[$padding_length];
$data = substr($raw, $padding_length + 1, -4);
if (strlen($raw) < 4) {
return false;
}
$temp = unpack('Ncrc', substr($raw, -4));
$type = ord($type);
if (defined('NET_SSH1_LOGGING')) {
$temp = isset($this->protocol_flags[$type]) ? $this->protocol_flags[$type] : 'UNKNOWN';
$temp = '<- ' . $temp .
' (' . round($stop - $start, 4) . 's)';
$this->_append_log($temp, $data);
}
return array(
NET_SSH1_RESPONSE_TYPE => $type,
NET_SSH1_RESPONSE_DATA => $data
);
}
* Sends Binary Packets
*
* Returns true on success, false on failure.
*
* @see self::_get_binary_packet()
* @param string $data
* @return bool
* @access private
*/
function _send_binary_packet() {
}
$length = strlen($data) + 4;
$padding = crypt_random_string(8 - ($length & 7));
$orig = $data;
$data = $padding . $data;
$data.= pack('N', $this->_crc($data));
if ($this->crypto !== false) {
$data = $this->crypto->encrypt($data);
}
$packet = pack('Na*', $length, $data);
$start = strtok(microtime(), ' ') + strtok(''); // http://php.net/microtime#61838
$result = strlen($packet) == fputs($this->fsock, $packet);
$stop = strtok(microtime(), ' ') + strtok('');
if (defined('NET_SSH1_LOGGING')) {
$temp = isset($this->protocol_flags[ord($orig[0])]) ? $this->protocol_flags[ord($orig[0])] : 'UNKNOWN';
$temp = '-> ' . $temp .
' (' . round($stop - $start, 4) . 's)';
$this->_append_log($temp, $orig);
}
return $result;
}
* Cyclic Redundancy Check (CRC)
*
* PHP's crc32 function is implemented slightly differently than the one that SSH v1 uses, so
* we've reimplemented it. A more detailed discussion of the differences can be found after
* $crc_lookup_table's initialization.
*
* @see self::_get_binary_packet()
* @see self::_send_binary_packet()
* @param string $data
* @return int
* @access private
*/
function _crc() {
}
return $crc;
}
* String Shift
*
* Inspired by array_shift
*
* @param string $string
* @param int $index
* @return string
* @access private
*/
function _string_shift() {
}
* RSA Encrypt
*
* Returns mod(pow($m, $e), $n), where $n should be the product of two (large) primes $p and $q and where $e
* should be a number with the property that gcd($e, ($p - 1) * ($q - 1)) == 1.  Could just make anything that
* calls this call modexp, instead, but I think this makes things clearer, maybe...
*
* @see self::Net_SSH1()
* @param Math_BigInteger $m
* @param array $key
* @return Math_BigInteger
* @access private
*/
function _rsa_crypt() {
}
$rsa = new Crypt_RSA();
$rsa->loadKey($key, CRYPT_RSA_PUBLIC_FORMAT_RAW);
$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
return $rsa->encrypt($m);
*/
$modulus = $key[1]->toBytes();
$length = strlen($modulus) - strlen($m) - 3;
$random = '';
while (strlen($random) != $length) {
$block = crypt_random_string($length - strlen($random));
$block = str_replace(" ", '', $block);
$random.= $block;
}
$temp = chr(0) . chr(2) . $random . chr(0) . $m;
$m = new Math_BigInteger($temp, 256);
$m = $m->modPow($key[0], $key[1]);
return $m->toBytes();
}
* Define Array
*
* Takes any number of arrays whose indices are integers and whose values are strings and defines a bunch of
* named constants from it, using the value as the name of the constant and the index as the value of the constant.
* If any of the constants that would be defined already exists, none of the constants will be defined.
*
* @param array $array
* @access private
*/
function _define_array() {
} else {
break 2;
}
}
}
}
* Returns a log of the packets that have been sent and received.
*
* Returns a string if NET_SSH1_LOGGING == NET_SSH1_LOG_COMPLEX, an array if NET_SSH1_LOGGING == NET_SSH1_LOG_SIMPLE and false if !defined('NET_SSH1_LOGGING')
*
* @access public
* @return array|false|string
*/
function getLog() {
}
switch (NET_SSH1_LOGGING) {
case NET_SSH1_LOG_SIMPLE:
return $this->message_number_log;
break;
case NET_SSH1_LOG_COMPLEX:
return $this->_format_log($this->message_log, $this->protocol_flags_log);
break;
default:
return false;
}
}
* Formats a log for printing
*
* @param array $message_log
* @param array $message_number_log
* @access private
* @return string
*/
function _format_log() {
}
$fragment = $this->_string_shift($current_log, $this->log_short_width);
$hex = substr(preg_replace_callback('#.#s', array($this, '_format_log_helper'), $fragment), strlen($this->log_boundary));
$raw = preg_replace('#[^ -~]|<#', '.', $fragment);
$output.= str_pad($hex, $this->log_long_width - $this->log_short_width, ' ') . $raw . "\r\n";
$j++;
} while (strlen($current_log));
$output.= "\r\n";
}
return $output;
}
* Helper function for _format_log
*
* For use with preg_replace_callback()
*
* @param array $matches
* @access private
* @return string
*/
function _format_log_helper() {
}
* Return the server key public exponent
*
* Returns, by default, the base-10 representation.  If $raw_output is set to true, returns, instead,
* the raw bytes.  This behavior is similar to PHP's md5() function.
*
* @param bool $raw_output
* @return string
* @access public
*/
function getServerKeyPublicExponent() {
}
* Return the server key public modulus
*
* Returns, by default, the base-10 representation.  If $raw_output is set to true, returns, instead,
* the raw bytes.  This behavior is similar to PHP's md5() function.
*
* @param bool $raw_output
* @return string
* @access public
*/
function getServerKeyPublicModulus() {
}
* Return the host key public exponent
*
* Returns, by default, the base-10 representation.  If $raw_output is set to true, returns, instead,
* the raw bytes.  This behavior is similar to PHP's md5() function.
*
* @param bool $raw_output
* @return string
* @access public
*/
function getHostKeyPublicExponent() {
}
* Return the host key public modulus
*
* Returns, by default, the base-10 representation.  If $raw_output is set to true, returns, instead,
* the raw bytes.  This behavior is similar to PHP's md5() function.
*
* @param bool $raw_output
* @return string
* @access public
*/
function getHostKeyPublicModulus() {
}
* Return a list of ciphers supported by SSH1 server.
*
* Just because a cipher is supported by an SSH1 server doesn't mean it's supported by this library. If $raw_output
* is set to true, returns, instead, an array of constants.  ie. instead of array('Triple-DES in CBC mode'), you'll
* get array(NET_SSH1_CIPHER_3DES).
*
* @param bool $raw_output
* @return array
* @access public
*/
function getSupportedCiphers() {
}
* Return a list of authentications supported by SSH1 server.
*
* Just because a cipher is supported by an SSH1 server doesn't mean it's supported by this library. If $raw_output
* is set to true, returns, instead, an array of constants.  ie. instead of array('password authentication'), you'll
* get array(NET_SSH1_AUTH_PASSWORD).
*
* @param bool $raw_output
* @return array
* @access public
*/
function getSupportedAuthentications() {
}
* Return the server identification.
*
* @return string
* @access public
*/
function getServerIdentification() {
}
* Logs data packets
*
* Makes sure that only the last 1MB worth of packets will be logged
*
* @param string $data
* @access private
*/
function _append_log() {
}
break;
case NET_SSH1_LOG_REALTIME:
echo "<pre>\r\n" . $this->_format_log(array($message), array($protocol_flags)) . "\r\n</pre>\r\n";
@flush();
@ob_flush();
break;
case NET_SSH1_LOG_REALTIME_FILE:
if (!isset($this->realtime_log_file)) {
$filename = NET_SSH1_LOG_REALTIME_FILE;
$fp = fopen($filename, 'w');
$this->realtime_log_file = $fp;
}
if (!is_resource($this->realtime_log_file)) {
break;
}
$entry = $this->_format_log(array($message), array($protocol_flags));
if ($this->realtime_log_wrap) {
$temp = "<<< START >>>\r\n";
$entry.= $temp;
fseek($this->realtime_log_file, ftell($this->realtime_log_file) - strlen($temp));
}
$this->realtime_log_size+= strlen($entry);
if ($this->realtime_log_size > NET_SSH1_LOG_MAX_SIZE) {
fseek($this->realtime_log_file, 0);
$this->realtime_log_size = strlen($entry);
$this->realtime_log_wrap = true;
}
fputs($this->realtime_log_file, $entry);
}
}
}