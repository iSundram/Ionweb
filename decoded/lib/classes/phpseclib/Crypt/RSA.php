<?php
* Pure-PHP PKCS#1 (v2.1) compliant implementation of RSA.
*
* PHP versions 4 and 5
*
* Here's an example of how to encrypt and decrypt text with this library:
* <code>
* <?php
*    include 'Crypt/RSA.php';
*
*    $rsa = new Crypt_RSA();
*    extract($rsa->createKey());
*
*    $plaintext = 'terrafrost';
*
*    $rsa->loadKey($privatekey);
*    $ciphertext = $rsa->encrypt($plaintext);
*
*    $rsa->loadKey($publickey);
*    echo $rsa->decrypt($ciphertext);
* ?>
* </code>
*
* Here's an example of how to create signatures and verify signatures with this library:
* <code>
* <?php
*    include 'Crypt/RSA.php';
*
*    $rsa = new Crypt_RSA();
*    extract($rsa->createKey());
*
*    $plaintext = 'terrafrost';
*
*    $rsa->loadKey($privatekey);
*    $signature = $rsa->sign($plaintext);
*
*    $rsa->loadKey($publickey);
*    echo $rsa->verify($plaintext, $signature) ? 'verified' : 'unverified';
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
* @package   Crypt_RSA
* @author    Jim Wigginton <terrafrost@php.net>
* @copyright 2009 Jim Wigginton
* @license   http://www.opensource.org/licenses/mit-license.html  MIT License
* @link      http://phpseclib.sourceforge.net
*/
* Include Crypt_Random
*/
if (!function_exists('crypt_random_string')) {
include_once 'Random.php';
}
* Include Crypt_Hash
*/
if (!class_exists('Crypt_Hash')) {
include_once 'Hash.php';
}
* @access public
* @see self::encrypt()
* @see self::decrypt()
*/
* Use {@link http://en.wikipedia.org/wiki/Optimal_Asymmetric_Encryption_Padding Optimal Asymmetric Encryption Padding}
* (OAEP) for encryption / decryption.
*
* Uses sha1 by default.
*
* @see self::setHash()
* @see self::setMGFHash()
*/
define('CRYPT_RSA_ENCRYPTION_OAEP',  1);
* Use PKCS#1 padding.
*
* Although CRYPT_RSA_ENCRYPTION_OAEP offers more security, including PKCS#1 padding is necessary for purposes of backwards
* compatibility with protocols (like SSH-1) written before OAEP's introduction.
*/
define('CRYPT_RSA_ENCRYPTION_PKCS1', 2);
* Do not use any padding
*
* Although this method is not recommended it can none-the-less sometimes be useful if you're trying to decrypt some legacy
* stuff, if you're trying to diagnose why an encrypted message isn't decrypting, etc.
*/
define('CRYPT_RSA_ENCRYPTION_NONE', 3);
* @access public
* @see self::sign()
* @see self::verify()
* @see self::setHash()
*/
* Use the Probabilistic Signature Scheme for signing
*
* Uses sha1 by default.
*
* @see self::setSaltLength()
* @see self::setMGFHash()
*/
define('CRYPT_RSA_SIGNATURE_PSS',  1);
* Use the PKCS#1 scheme by default.
*
* Although CRYPT_RSA_SIGNATURE_PSS offers more security, including PKCS#1 signing is necessary for purposes of backwards
* compatibility with protocols (like SSH-2) written before PSS's introduction.
*/
define('CRYPT_RSA_SIGNATURE_PKCS1', 2);
* @access private
* @see self::createKey()
*/
* ASN1 Integer
*/
define('CRYPT_RSA_ASN1_INTEGER',     2);
* ASN1 Bit String
*/
define('CRYPT_RSA_ASN1_BITSTRING',   3);
* ASN1 Octet String
*/
define('CRYPT_RSA_ASN1_OCTETSTRING', 4);
* ASN1 Object Identifier
*/
define('CRYPT_RSA_ASN1_OBJECT',      6);
* ASN1 Sequence (with the constucted bit set)
*/
define('CRYPT_RSA_ASN1_SEQUENCE',   48);
* @access private
* @see self::Crypt_RSA()
*/
* To use the pure-PHP implementation
*/
define('CRYPT_RSA_MODE_INTERNAL', 1);
* To use the OpenSSL library
*
* (if enabled; otherwise, the internal implementation will be used)
*/
define('CRYPT_RSA_MODE_OPENSSL', 2);
* Default openSSL configuration file.
*/
define('CRYPT_RSA_OPENSSL_CONFIG', dirname(__FILE__) . '/../openssl.cnf');
* @access public
* @see self::createKey()
* @see self::setPrivateKeyFormat()
*/
* PKCS#1 formatted private key
*
* Used by OpenSSH
*/
define('CRYPT_RSA_PRIVATE_FORMAT_PKCS1', 0);
* PuTTY formatted private key
*/
define('CRYPT_RSA_PRIVATE_FORMAT_PUTTY', 1);
* XML formatted private key
*/
define('CRYPT_RSA_PRIVATE_FORMAT_XML', 2);
* PKCS#8 formatted private key
*/
define('CRYPT_RSA_PRIVATE_FORMAT_PKCS8', 8);
* OpenSSH formatted private key
*/
define('CRYPT_RSA_PRIVATE_FORMAT_OPENSSH', 9);
* @access public
* @see self::createKey()
* @see self::setPublicKeyFormat()
*/
* Raw public key
*
* An array containing two Math_BigInteger objects.
*
* The exponent can be indexed with any of the following:
*
* 0, e, exponent, publicExponent
*
* The modulus can be indexed with any of the following:
*
* 1, n, modulo, modulus
*/
define('CRYPT_RSA_PUBLIC_FORMAT_RAW', 3);
* PKCS#1 formatted public key (raw)
*
* Used by File/X509.php
*
* Has the following header:
*
* -----BEGIN RSA PUBLIC KEY-----
*
* Analogous to ssh-keygen's pem format (as specified by -m)
*/
define('CRYPT_RSA_PUBLIC_FORMAT_PKCS1', 4);
define('CRYPT_RSA_PUBLIC_FORMAT_PKCS1_RAW', 4);
* XML formatted public key
*/
define('CRYPT_RSA_PUBLIC_FORMAT_XML', 5);
* OpenSSH formatted public key
*
* Place in $HOME/.ssh/authorized_keys
*/
define('CRYPT_RSA_PUBLIC_FORMAT_OPENSSH', 6);
* PKCS#1 formatted public key (encapsulated)
*
* Used by PHP's openssl_public_encrypt() and openssl's rsautl (when -pubin is set)
*
* Has the following header:
*
* -----BEGIN PUBLIC KEY-----
*
* Analogous to ssh-keygen's pkcs8 format (as specified by -m). Although PKCS8
* is specific to private keys it's basically creating a DER-encoded wrapper
* for keys. This just extends that same concept to public keys (much like ssh-keygen)
*/
define('CRYPT_RSA_PUBLIC_FORMAT_PKCS8', 7);
* Pure-PHP PKCS#1 compliant implementation of RSA.
*
* @package Crypt_RSA
* @author  Jim Wigginton <terrafrost@php.net>
* @access  public
*/
class Crypt_RSA
{
* Precomputed Zero
*
* @var Math_BigInteger
* @access private
*/
var $zero;
* Precomputed One
*
* @var Math_BigInteger
* @access private
*/
var $one;
* Private Key Format
*
* @var int
* @access private
*/
var $privateKeyFormat = CRYPT_RSA_PRIVATE_FORMAT_PKCS1;
* Public Key Format
*
* @var int
* @access public
*/
var $publicKeyFormat = CRYPT_RSA_PUBLIC_FORMAT_PKCS8;
* Modulus (ie. n)
*
* @var Math_BigInteger
* @access private
*/
var $modulus;
* Modulus length
*
* @var Math_BigInteger
* @access private
*/
var $k;
* Exponent (ie. e or d)
*
* @var Math_BigInteger
* @access private
*/
var $exponent;
* Primes for Chinese Remainder Theorem (ie. p and q)
*
* @var array
* @access private
*/
var $primes;
* Exponents for Chinese Remainder Theorem (ie. dP and dQ)
*
* @var array
* @access private
*/
var $exponents;
* Coefficients for Chinese Remainder Theorem (ie. qInv)
*
* @var array
* @access private
*/
var $coefficients;
* Hash name
*
* @var string
* @access private
*/
var $hashName;
* Hash function
*
* @var Crypt_Hash
* @access private
*/
var $hash;
* Length of hash function output
*
* @var int
* @access private
*/
var $hLen;
* Length of salt
*
* @var int
* @access private
*/
var $sLen;
* Hash function for the Mask Generation Function
*
* @var Crypt_Hash
* @access private
*/
var $mgfHash;
* Length of MGF hash function output
*
* @var int
* @access private
*/
var $mgfHLen;
* Encryption mode
*
* @var int
* @access private
*/
var $encryptionMode = CRYPT_RSA_ENCRYPTION_OAEP;
* Signature mode
*
* @var int
* @access private
*/
var $signatureMode = CRYPT_RSA_SIGNATURE_PSS;
* Public Exponent
*
* @var mixed
* @access private
*/
var $publicExponent = false;
* Password
*
* @var string
* @access private
*/
var $password = false;
* Components
*
* For use with parsing XML formatted keys.  PHP's XML Parser functions use utilized - instead of PHP's DOM functions -
* because PHP's XML Parser functions work on PHP4 whereas PHP's DOM functions - although surperior - don't.
*
* @see self::_start_element_handler()
* @var array
* @access private
*/
var $components = array();
* Current String
*
* For use with parsing XML formatted keys.
*
* @see self::_character_handler()
* @see self::_stop_element_handler()
* @var mixed
* @access private
*/
var $current;
* OpenSSL configuration file name.
*
* Set to null to use system configuration file.
* @see self::createKey()
* @var mixed
* @Access public
*/
var $configFile;
* Public key comment field.
*
* @var string
* @access private
*/
var $comment = 'phpseclib-generated-key';
* The constructor
*
* If you want to make use of the openssl extension, you'll need to set the mode manually, yourself.  The reason
* Crypt_RSA doesn't do it is because OpenSSL doesn't fail gracefully.  openssl_pkey_new(), in particular, requires
* openssl.cnf be present somewhere and, unfortunately, the only real way to find out is too late.
*
* @return Crypt_RSA
* @access public
*/
function __construct() {
}
$this->configFile = CRYPT_RSA_OPENSSL_CONFIG;
if (!defined('CRYPT_RSA_MODE')) {
switch (true) {
case defined('MATH_BIGINTEGER_OPENSSL_DISABLE'):
define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_INTERNAL);
break;
case !function_exists('openssl_pkey_get_details'):
define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_INTERNAL);
break;
case extension_loaded('openssl') && version_compare(PHP_VERSION, '4.2.0', '>=') && file_exists($this->configFile):
ob_start();
@phpinfo();
$content = ob_get_contents();
ob_end_clean();
preg_match_all('#OpenSSL (Header|Library) Version(.*)#im', $content, $matches);
$versions = array();
if (!empty($matches[1])) {
for ($i = 0; $i < count($matches[1]); $i++) {
$fullVersion = trim(str_replace('=>', '', strip_tags($matches[2][$i])));
if (!preg_match('/(\d+\.\d+\.\d+)/i', $fullVersion, $m)) {
$versions[$matches[1][$i]] = $fullVersion;
} else {
$versions[$matches[1][$i]] = $m[0];
}
}
}
switch (true) {
case !isset($versions['Header']):
case !isset($versions['Library']):
case $versions['Header'] == $versions['Library']:
case version_compare($versions['Header'], '1.0.0') >= 0 && version_compare($versions['Library'], '1.0.0') >= 0:
define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_OPENSSL);
break;
default:
define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_INTERNAL);
define('MATH_BIGINTEGER_OPENSSL_DISABLE', true);
}
break;
default:
define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_INTERNAL);
}
}
$this->zero = new Math_BigInteger();
$this->one = new Math_BigInteger(1);
$this->hash = new Crypt_Hash('sha1');
$this->hLen = $this->hash->getLength();
$this->hashName = 'sha1';
$this->mgfHash = new Crypt_Hash('sha1');
$this->mgfHLen = $this->mgfHash->getLength();
}
* PHP4 compatible Default Constructor.
*
* @see self::__construct()
* @access public
*/
function Crypt_RSA() {
}
* Create public / private key pair
*
* Returns an array with the following three elements:
*  - 'privatekey': The private key.
*  - 'publickey':  The public key.
*  - 'partialkey': A partially computed key (if the execution time exceeded $timeout).
*                  Will need to be passed back to Crypt_RSA::createKey() as the third parameter for further processing.
*
* @access public
* @param int $bits
* @param int $timeout
* @param Math_BigInteger $p
*/
function createKey($bits = 1024, $timeout = false, $partial = array())
{
if (!defined('CRYPT_RSA_EXPONENT')) {
define('CRYPT_RSA_EXPONENT', '65537');
}
if (!defined('CRYPT_RSA_SMALLEST_PRIME')) {
define('CRYPT_RSA_SMALLEST_PRIME', 4096);
}
if (CRYPT_RSA_MODE == CRYPT_RSA_MODE_OPENSSL && $bits >= 384 && CRYPT_RSA_EXPONENT == 65537) {
$config = array();
if (isset($this->configFile)) {
$config['config'] = $this->configFile;
}
$rsa = openssl_pkey_new(array('private_key_bits' => $bits) + $config);
openssl_pkey_export($rsa, $privatekey, null, $config);
$publickey = openssl_pkey_get_details($rsa);
$publickey = $publickey['key'];
$privatekey = call_user_func_array(array($this, '_convertPrivateKey'), array_values($this->_parseKey($privatekey, CRYPT_RSA_PRIVATE_FORMAT_PKCS1)));
$publickey = call_user_func_array(array($this, '_convertPublicKey'), array_values($this->_parseKey($publickey, CRYPT_RSA_PUBLIC_FORMAT_PKCS1)));
while (openssl_error_string() !== false) {
}
return array(
'privatekey' => $privatekey,
'publickey' => $publickey,
'partialkey' => false
);
}
static $e;
if (!isset($e)) {
$e = new Math_BigInteger(CRYPT_RSA_EXPONENT);
}
extract($this->_generateMinMax($bits));
$absoluteMin = $min;
$temp = $bits >> 1; // divide by two to see how many bits P and Q would be
if ($temp > CRYPT_RSA_SMALLEST_PRIME) {
$num_primes = floor($bits / CRYPT_RSA_SMALLEST_PRIME);
$temp = CRYPT_RSA_SMALLEST_PRIME;
} else {
$num_primes = 2;
}
extract($this->_generateMinMax($temp + $bits % $temp));
$finalMax = $max;
extract($this->_generateMinMax($temp));
$generator = new Math_BigInteger();
$n = $this->one->copy();
if (!empty($partial)) {
extract(unserialize($partial));
} else {
$exponents = $coefficients = $primes = array();
$lcm = array(
'top' => $this->one->copy(),
'bottom' => false
);
}
$start = time();
$i0 = count($primes) + 1;
do {
for ($i = $i0; $i <= $num_primes; $i++) {
if ($timeout !== false) {
$timeout-= time() - $start;
$start = time();
if ($timeout <= 0) {
return array(
'privatekey' => '',
'publickey'  => '',
'partialkey' => serialize(array(
'primes' => $primes,
'coefficients' => $coefficients,
'lcm' => $lcm,
'exponents' => $exponents
))
);
}
}
if ($i == $num_primes) {
list($min, $temp) = $absoluteMin->divide($n);
if (!$temp->equals($this->zero)) {
$min = $min->add($this->one); // ie. ceil()
}
$primes[$i] = $generator->randomPrime($min, $finalMax, $timeout);
} else {
$primes[$i] = $generator->randomPrime($min, $max, $timeout);
}
if ($primes[$i] === false) { // if we've reached the timeout
if (count($primes) > 1) {
$partialkey = '';
} else {
array_pop($primes);
$partialkey = serialize(array(
'primes' => $primes,
'coefficients' => $coefficients,
'lcm' => $lcm,
'exponents' => $exponents
));
}
return array(
'privatekey' => '',
'publickey'  => '',
'partialkey' => $partialkey
);
}
if ($i > 2) {
$coefficients[$i] = $n->modInverse($primes[$i]);
}
$n = $n->multiply($primes[$i]);
$temp = $primes[$i]->subtract($this->one);
$lcm['top'] = $lcm['top']->multiply($temp);
$lcm['bottom'] = $lcm['bottom'] === false ? $temp : $lcm['bottom']->gcd($temp);
$exponents[$i] = $e->modInverse($temp);
}
list($temp) = $lcm['top']->divide($lcm['bottom']);
$gcd = $temp->gcd($e);
$i0 = 1;
} while (!$gcd->equals($this->one));
$d = $e->modInverse($temp);
$coefficients[2] = $primes[2]->modInverse($primes[1]);
return array(
'privatekey' => $this->_convertPrivateKey($n, $e, $d, $primes, $exponents, $coefficients),
'publickey'  => $this->_convertPublicKey($n, $e),
'partialkey' => false
);
}
* Convert a private key to the appropriate format.
*
* @access private
* @see self::setPrivateKeyFormat()
* @param string $RSAPrivateKey
* @return string
*/
function _convertPrivateKey() {
}
return "<RSAKeyValue>\r\n" .
'  <Modulus>' . base64_encode($raw['modulus']) . "</Modulus>\r\n" .
'  <Exponent>' . base64_encode($raw['publicExponent']) . "</Exponent>\r\n" .
'  <P>' . base64_encode($raw['prime1']) . "</P>\r\n" .
'  <Q>' . base64_encode($raw['prime2']) . "</Q>\r\n" .
'  <DP>' . base64_encode($raw['exponent1']) . "</DP>\r\n" .
'  <DQ>' . base64_encode($raw['exponent2']) . "</DQ>\r\n" .
'  <InverseQ>' . base64_encode($raw['coefficient']) . "</InverseQ>\r\n" .
'  <D>' . base64_encode($raw['privateExponent']) . "</D>\r\n" .
'</RSAKeyValue>';
break;
case CRYPT_RSA_PRIVATE_FORMAT_PUTTY:
if ($num_primes != 2) {
return false;
}
$key = "PuTTY-User-Key-File-2: ssh-rsa\r\nEncryption: ";
$encryption = (!empty($this->password) || is_string($this->password)) ? 'aes256-cbc' : 'none';
$key.= $encryption;
$key.= "\r\nComment: " . $this->comment . "\r\n";
$public = pack(
'Na*Na*Na*',
strlen('ssh-rsa'),
'ssh-rsa',
strlen($raw['publicExponent']),
$raw['publicExponent'],
strlen($raw['modulus']),
$raw['modulus']
);
$source = pack(
'Na*Na*Na*Na*',
strlen('ssh-rsa'),
'ssh-rsa',
strlen($encryption),
$encryption,
strlen($this->comment),
$this->comment,
strlen($public),
$public
);
$public = base64_encode($public);
$key.= "Public-Lines: " . ((strlen($public) + 63) >> 6) . "\r\n";
$key.= chunk_split($public, 64);
$private = pack(
'Na*Na*Na*Na*',
strlen($raw['privateExponent']),
$raw['privateExponent'],
strlen($raw['prime1']),
$raw['prime1'],
strlen($raw['prime2']),
$raw['prime2'],
strlen($raw['coefficient']),
$raw['coefficient']
);
if (empty($this->password) && !is_string($this->password)) {
$source.= pack('Na*', strlen($private), $private);
$hashkey = 'putty-private-key-file-mac-key';
} else {
$private.= crypt_random_string(16 - (strlen($private) & 15));
$source.= pack('Na*', strlen($private), $private);
if (!class_exists('Crypt_AES')) {
include_once 'Crypt/AES.php';
}
$sequence = 0;
$symkey = '';
while (strlen($symkey) < 32) {
$temp = pack('Na*', $sequence++, $this->password);
$symkey.= pack('H*', sha1($temp));
}
$symkey = substr($symkey, 0, 32);
$crypto = new Crypt_AES();
$crypto->setKey($symkey);
$crypto->disablePadding();
$private = $crypto->encrypt($private);
$hashkey = 'putty-private-key-file-mac-key' . $this->password;
}
$private = base64_encode($private);
$key.= 'Private-Lines: ' . ((strlen($private) + 63) >> 6) . "\r\n";
$key.= chunk_split($private, 64);
if (!class_exists('Crypt_Hash')) {
include_once 'Crypt/Hash.php';
}
$hash = new Crypt_Hash('sha1');
$hash->setKey(pack('H*', sha1($hashkey)));
$key.= 'Private-MAC: ' . bin2hex($hash->hash($source)) . "\r\n";
return $key;
case CRYPT_RSA_PRIVATE_FORMAT_OPENSSH:
if ($num_primes != 2) {
return false;
}
$publicKey = pack('Na*Na*Na*', strlen('ssh-rsa'), 'ssh-rsa', strlen($raw['publicExponent']), $raw['publicExponent'], strlen($raw['modulus']), $raw['modulus']);
$privateKey = pack(
'Na*Na*Na*Na*Na*Na*Na*',
strlen('ssh-rsa'),
'ssh-rsa',
strlen($raw['modulus']),
$raw['modulus'],
strlen($raw['publicExponent']),
$raw['publicExponent'],
strlen($raw['privateExponent']),
$raw['privateExponent'],
strlen($raw['coefficient']),
$raw['coefficient'],
strlen($raw['prime1']),
$raw['prime1'],
strlen($raw['prime2']),
$raw['prime2']
);
$checkint = crypt_random_string(4);
$paddedKey = pack(
'a*Na*',
$checkint . $checkint . $privateKey,
strlen($this->comment),
$this->comment
);
$paddingLength = (7 * strlen($paddedKey)) % 8;
for ($i = 1; $i <= $paddingLength; $i++) {
$paddedKey.= chr($i);
}
$key = pack(
'Na*Na*Na*NNa*Na*',
strlen('none'),
'none',
strlen('none'),
'none',
0,
'',
1,
strlen($publicKey),
$publicKey,
strlen($paddedKey),
$paddedKey
);
$key = "openssh-key-v1\0$key";
return "-----BEGIN OPENSSH PRIVATE KEY-----\r\n" .
chunk_split(base64_encode($key), 70) .
"-----END OPENSSH PRIVATE KEY-----";
default: // eg. CRYPT_RSA_PRIVATE_FORMAT_PKCS1
$components = array();
foreach ($raw as $name => $value) {
$components[$name] = pack('Ca*a*', CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($value)), $value);
}
$RSAPrivateKey = implode('', $components);
if ($num_primes > 2) {
$OtherPrimeInfos = '';
for ($i = 3; $i <= $num_primes; $i++) {
$OtherPrimeInfo = pack('Ca*a*', CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($primes[$i]->toBytes(true))), $primes[$i]->toBytes(true));
$OtherPrimeInfo.= pack('Ca*a*', CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($exponents[$i]->toBytes(true))), $exponents[$i]->toBytes(true));
$OtherPrimeInfo.= pack('Ca*a*', CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($coefficients[$i]->toBytes(true))), $coefficients[$i]->toBytes(true));
$OtherPrimeInfos.= pack('Ca*a*', CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($OtherPrimeInfo)), $OtherPrimeInfo);
}
$RSAPrivateKey.= pack('Ca*a*', CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($OtherPrimeInfos)), $OtherPrimeInfos);
}
$RSAPrivateKey = pack('Ca*a*', CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($RSAPrivateKey)), $RSAPrivateKey);
if ($this->privateKeyFormat == CRYPT_RSA_PRIVATE_FORMAT_PKCS8) {
$rsaOID = pack('H*', '300d06092a864886f70d0101010500'); // hex version of MA0GCSqGSIb3DQEBAQUA
$RSAPrivateKey = pack(
'Ca*a*Ca*a*',
CRYPT_RSA_ASN1_INTEGER,
"\01\00",
$rsaOID,
4,
$this->_encodeLength(strlen($RSAPrivateKey)),
$RSAPrivateKey
);
$RSAPrivateKey = pack('Ca*a*', CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($RSAPrivateKey)), $RSAPrivateKey);
if (!empty($this->password) || is_string($this->password)) {
$salt = crypt_random_string(8);
$iterationCount = 2048;
if (!class_exists('Crypt_DES')) {
include_once 'Crypt/DES.php';
}
$crypto = new Crypt_DES();
$crypto->setPassword($this->password, 'pbkdf1', 'md5', $salt, $iterationCount);
$RSAPrivateKey = $crypto->encrypt($RSAPrivateKey);
$parameters = pack(
'Ca*a*Ca*N',
CRYPT_RSA_ASN1_OCTETSTRING,
$this->_encodeLength(strlen($salt)),
$salt,
CRYPT_RSA_ASN1_INTEGER,
$this->_encodeLength(4),
$iterationCount
);
$pbeWithMD5AndDES_CBC = "*H÷";
$encryptionAlgorithm = pack(
'Ca*a*Ca*a*',
CRYPT_RSA_ASN1_OBJECT,
$this->_encodeLength(strlen($pbeWithMD5AndDES_CBC)),
$pbeWithMD5AndDES_CBC,
CRYPT_RSA_ASN1_SEQUENCE,
$this->_encodeLength(strlen($parameters)),
$parameters
);
$RSAPrivateKey = pack(
'Ca*a*Ca*a*',
CRYPT_RSA_ASN1_SEQUENCE,
$this->_encodeLength(strlen($encryptionAlgorithm)),
$encryptionAlgorithm,
CRYPT_RSA_ASN1_OCTETSTRING,
$this->_encodeLength(strlen($RSAPrivateKey)),
$RSAPrivateKey
);
$RSAPrivateKey = pack('Ca*a*', CRYPT_RSA_ASN1_SEQUENCE, $this->_encodeLength(strlen($RSAPrivateKey)), $RSAPrivateKey);
$RSAPrivateKey = "-----BEGIN ENCRYPTED PRIVATE KEY-----\r\n" .
chunk_split(base64_encode($RSAPrivateKey), 64) .
'-----END ENCRYPTED PRIVATE KEY-----';
} else {
$RSAPrivateKey = "-----BEGIN PRIVATE KEY-----\r\n" .
chunk_split(base64_encode($RSAPrivateKey), 64) .
'-----END PRIVATE KEY-----';
}
return $RSAPrivateKey;
}
if (!empty($this->password) || is_string($this->password)) {
$iv = crypt_random_string(8);
$symkey = pack('H*', md5($this->password . $iv)); // symkey is short for symmetric key
$symkey.= substr(pack('H*', md5($symkey . $this->password . $iv)), 0, 8);
if (!class_exists('Crypt_TripleDES')) {
include_once 'Crypt/TripleDES.php';
}
$des = new Crypt_TripleDES();
$des->setKey($symkey);
$des->setIV($iv);
$iv = strtoupper(bin2hex($iv));
$RSAPrivateKey = "-----BEGIN RSA PRIVATE KEY-----\r\n" .
"Proc-Type: 4,ENCRYPTED\r\n" .
"DEK-Info: DES-EDE3-CBC,$iv\r\n" .
"\r\n" .
chunk_split(base64_encode($des->encrypt($RSAPrivateKey)), 64) .
'-----END RSA PRIVATE KEY-----';
} else {
$RSAPrivateKey = "-----BEGIN RSA PRIVATE KEY-----\r\n" .
chunk_split(base64_encode($RSAPrivateKey), 64) .
'-----END RSA PRIVATE KEY-----';
}
return $RSAPrivateKey;
}
}
* Convert a public key to the appropriate format
*
* @access private
* @see self::setPublicKeyFormat()
* @param string $RSAPrivateKey
* @return string
*/
function _convertPublicKey() {
}
$components = array(
'modulus' => pack('Ca*a*', CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($modulus)), $modulus),
'publicExponent' => pack('Ca*a*', CRYPT_RSA_ASN1_INTEGER, $this->_encodeLength(strlen($publicExponent)), $publicExponent)
);
$RSAPublicKey = pack(
'Ca*a*a*',
CRYPT_RSA_ASN1_SEQUENCE,
$this->_encodeLength(strlen($components['modulus']) + strlen($components['publicExponent'])),
$components['modulus'],
$components['publicExponent']
);
if ($this->publicKeyFormat == CRYPT_RSA_PUBLIC_FORMAT_PKCS1_RAW) {
$RSAPublicKey = "-----BEGIN RSA PUBLIC KEY-----\r\n" .
chunk_split(base64_encode($RSAPublicKey), 64) .
'-----END RSA PUBLIC KEY-----';
} else {
$rsaOID = pack('H*', '300d06092a864886f70d0101010500'); // hex version of MA0GCSqGSIb3DQEBAQUA
$RSAPublicKey = chr(0) . $RSAPublicKey;
$RSAPublicKey = chr(3) . $this->_encodeLength(strlen($RSAPublicKey)) . $RSAPublicKey;
$RSAPublicKey = pack(
'Ca*a*',
CRYPT_RSA_ASN1_SEQUENCE,
$this->_encodeLength(strlen($rsaOID . $RSAPublicKey)),
$rsaOID . $RSAPublicKey
);
$RSAPublicKey = "-----BEGIN PUBLIC KEY-----\r\n" .
chunk_split(base64_encode($RSAPublicKey), 64) .
'-----END PUBLIC KEY-----';
}
return $RSAPublicKey;
}
}
* Break a public or private key down into its constituant components
*
* @access private
* @see self::_convertPublicKey()
* @see self::_convertPrivateKey()
* @param string $key
* @param int $type
* @return array
*/
function _parseKey() {
}
switch ($type) {
case CRYPT_RSA_PUBLIC_FORMAT_RAW:
if (!is_array($key)) {
return false;
}
$components = array();
switch (true) {
case isset($key['e']):
$components['publicExponent'] = $key['e']->copy();
break;
case isset($key['exponent']):
$components['publicExponent'] = $key['exponent']->copy();
break;
case isset($key['publicExponent']):
$components['publicExponent'] = $key['publicExponent']->copy();
break;
case isset($key[0]):
$components['publicExponent'] = $key[0]->copy();
}
switch (true) {
case isset($key['n']):
$components['modulus'] = $key['n']->copy();
break;
case isset($key['modulo']):
$components['modulus'] = $key['modulo']->copy();
break;
case isset($key['modulus']):
$components['modulus'] = $key['modulus']->copy();
break;
case isset($key[1]):
$components['modulus'] = $key[1]->copy();
}
return isset($components['modulus']) && isset($components['publicExponent']) ? $components : false;
case CRYPT_RSA_PRIVATE_FORMAT_PKCS1:
case CRYPT_RSA_PRIVATE_FORMAT_PKCS8:
case CRYPT_RSA_PUBLIC_FORMAT_PKCS1:
"outside the scope" of PKCS#1.  PKCS#1 then refers you to PKCS#12 and PKCS#15 if you're wanting to
protect private keys, however, that's not what OpenSSL* does.  OpenSSL protects private keys by adding
two new "fields" to the key - DEK-Info and Proc-Type.  These fields are discussed here:
http://tools.ietf.org/html/rfc1421#section-4.6.1.1
http://tools.ietf.org/html/rfc1421#section-4.6.1.3
DES-EDE3-CBC as an algorithm, however, is not discussed anywhere, near as I can tell.
DES-CBC and DES-EDE are discussed in RFC1423, however, DES-EDE3-CBC isn't, nor is its key derivation
function.  As is, the definitive authority on this encoding scheme isn't the IETF but rather OpenSSL's
own implementation.  ie. the implementation *is* the standard and any bugs that may exist in that
implementation are part of the standard, as well.
* OpenSSL is the de facto standard.  It's utilized by OpenSSH and other projects */
if (preg_match('#DEK-Info: (.+),(.+)#', $key, $matches)) {
$iv = pack('H*', trim($matches[2]));
$symkey = pack('H*', md5($this->password . substr($iv, 0, 8))); // symkey is short for symmetric key
$symkey.= pack('H*', md5($symkey . $this->password . substr($iv, 0, 8)));
$key = preg_replace('#^(?:Proc-Type|DEK-Info): .*#m', '', $key);
$ciphertext = $this->_extractBER($key);
if ($ciphertext === false) {
$ciphertext = $key;
}
switch ($matches[1]) {
case 'AES-256-CBC':
if (!class_exists('Crypt_AES')) {
include_once 'Crypt/AES.php';
}
$crypto = new Crypt_AES();
break;
case 'AES-128-CBC':
if (!class_exists('Crypt_AES')) {
include_once 'Crypt/AES.php';
}
$symkey = substr($symkey, 0, 16);
$crypto = new Crypt_AES();
break;
case 'DES-EDE3-CFB':
if (!class_exists('Crypt_TripleDES')) {
include_once 'Crypt/TripleDES.php';
}
$crypto = new Crypt_TripleDES(CRYPT_DES_MODE_CFB);
break;
case 'DES-EDE3-CBC':
if (!class_exists('Crypt_TripleDES')) {
include_once 'Crypt/TripleDES.php';
}
$symkey = substr($symkey, 0, 24);
$crypto = new Crypt_TripleDES();
break;
case 'DES-CBC':
if (!class_exists('Crypt_DES')) {
include_once 'Crypt/DES.php';
}
$crypto = new Crypt_DES();
break;
default:
return false;
}
$crypto->setKey($symkey);
$crypto->setIV($iv);
$decoded = $crypto->decrypt($ciphertext);
} else {
$decoded = $this->_extractBER($key);
}
if ($decoded !== false) {
$key = $decoded;
}
$components = array();
if (ord($this->_string_shift($key)) != CRYPT_RSA_ASN1_SEQUENCE) {
return false;
}
if ($this->_decodeLength($key) != strlen($key)) {
return false;
}
$tag = ord($this->_string_shift($key));
0:d=0  hl=4 l= 631 cons: SEQUENCE
4:d=1  hl=2 l=   1 prim:  INTEGER           :00
7:d=1  hl=2 l=  13 cons:  SEQUENCE
9:d=2  hl=2 l=   9 prim:   OBJECT            :rsaEncryption
20:d=2  hl=2 l=   0 prim:   NULL
22:d=1  hl=4 l= 609 prim:  OCTET STRING
ie. PKCS8 keys*/
if ($tag == CRYPT_RSA_ASN1_INTEGER && substr($key, 0, 3) == " 0") {
$this->_string_shift($key, 3);
$tag = CRYPT_RSA_ASN1_SEQUENCE;
}
if ($tag == CRYPT_RSA_ASN1_SEQUENCE) {
$temp = $this->_string_shift($key, $this->_decodeLength($key));
if (ord($this->_string_shift($temp)) != CRYPT_RSA_ASN1_OBJECT) {
return false;
}
$length = $this->_decodeLength($temp);
switch ($this->_string_shift($temp, $length)) {
case "*H÷": // rsaEncryption
break;
case "*H÷": // pbeWithMD5AndDES-CBC
PBEParameter ::= SEQUENCE {
salt OCTET STRING (SIZE(8)),
iterationCount INTEGER }
*/
if (ord($this->_string_shift($temp)) != CRYPT_RSA_ASN1_SEQUENCE) {
return false;
}
if ($this->_decodeLength($temp) != strlen($temp)) {
return false;
}
$this->_string_shift($temp); // assume it's an octet string
$salt = $this->_string_shift($temp, $this->_decodeLength($temp));
if (ord($this->_string_shift($temp)) != CRYPT_RSA_ASN1_INTEGER) {
return false;
}
$this->_decodeLength($temp);
list(, $iterationCount) = unpack('N', str_pad($temp, 4, chr(0), STR_PAD_LEFT));
$this->_string_shift($key); // assume it's an octet string
$length = $this->_decodeLength($key);
if (strlen($key) != $length) {
return false;
}
if (!class_exists('Crypt_DES')) {
include_once 'Crypt/DES.php';
}
$crypto = new Crypt_DES();
$crypto->setPassword($this->password, 'pbkdf1', 'md5', $salt, $iterationCount);
$key = $crypto->decrypt($key);
if ($key === false) {
return false;
}
return $this->_parseKey($key, CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
default:
return false;
}
0:d=0  hl=4 l= 290 cons: SEQUENCE
4:d=1  hl=2 l=  13 cons:  SEQUENCE
6:d=2  hl=2 l=   9 prim:   OBJECT            :rsaEncryption
17:d=2  hl=2 l=   0 prim:   NULL
19:d=1  hl=4 l= 271 prim:  BIT STRING */
$tag = ord($this->_string_shift($key)); // skip over the BIT STRING / OCTET STRING tag
$this->_decodeLength($key); // skip over the BIT STRING / OCTET STRING length
if ($tag == CRYPT_RSA_ASN1_BITSTRING) {
$this->_string_shift($key);
}
if (ord($this->_string_shift($key)) != CRYPT_RSA_ASN1_SEQUENCE) {
return false;
}
if ($this->_decodeLength($key) != strlen($key)) {
return false;
}
$tag = ord($this->_string_shift($key));
}
if ($tag != CRYPT_RSA_ASN1_INTEGER) {
return false;
}
$length = $this->_decodeLength($key);
$temp = $this->_string_shift($key, $length);
if (strlen($temp) != 1 || ord($temp) > 2) {
$components['modulus'] = new Math_BigInteger($temp, 256);
$this->_string_shift($key); // skip over CRYPT_RSA_ASN1_INTEGER
$length = $this->_decodeLength($key);
$components[$type == CRYPT_RSA_PUBLIC_FORMAT_PKCS1 ? 'publicExponent' : 'privateExponent'] = new Math_BigInteger($this->_string_shift($key, $length), 256);
return $components;
}
if (ord($this->_string_shift($key)) != CRYPT_RSA_ASN1_INTEGER) {
return false;
}
$length = $this->_decodeLength($key);
$components['modulus'] = new Math_BigInteger($this->_string_shift($key, $length), 256);
$this->_string_shift($key);
$length = $this->_decodeLength($key);
$components['publicExponent'] = new Math_BigInteger($this->_string_shift($key, $length), 256);
$this->_string_shift($key);
$length = $this->_decodeLength($key);
$components['privateExponent'] = new Math_BigInteger($this->_string_shift($key, $length), 256);
$this->_string_shift($key);
$length = $this->_decodeLength($key);
$components['primes'] = array(1 => new Math_BigInteger($this->_string_shift($key, $length), 256));
$this->_string_shift($key);
$length = $this->_decodeLength($key);
$components['primes'][] = new Math_BigInteger($this->_string_shift($key, $length), 256);
$this->_string_shift($key);
$length = $this->_decodeLength($key);
$components['exponents'] = array(1 => new Math_BigInteger($this->_string_shift($key, $length), 256));
$this->_string_shift($key);
$length = $this->_decodeLength($key);
$components['exponents'][] = new Math_BigInteger($this->_string_shift($key, $length), 256);
$this->_string_shift($key);
$length = $this->_decodeLength($key);
$components['coefficients'] = array(2 => new Math_BigInteger($this->_string_shift($key, $length), 256));
if (!empty($key)) {
if (ord($this->_string_shift($key)) != CRYPT_RSA_ASN1_SEQUENCE) {
return false;
}
$this->_decodeLength($key);
while (!empty($key)) {
if (ord($this->_string_shift($key)) != CRYPT_RSA_ASN1_SEQUENCE) {
return false;
}
$this->_decodeLength($key);
$key = substr($key, 1);
$length = $this->_decodeLength($key);
$components['primes'][] = new Math_BigInteger($this->_string_shift($key, $length), 256);
$this->_string_shift($key);
$length = $this->_decodeLength($key);
$components['exponents'][] = new Math_BigInteger($this->_string_shift($key, $length), 256);
$this->_string_shift($key);
$length = $this->_decodeLength($key);
$components['coefficients'][] = new Math_BigInteger($this->_string_shift($key, $length), 256);
}
}
return $components;
case CRYPT_RSA_PUBLIC_FORMAT_OPENSSH:
$parts = explode(' ', $key, 3);
$key = isset($parts[1]) ? base64_decode($parts[1]) : false;
if ($key === false) {
return false;
}
$comment = isset($parts[2]) ? $parts[2] : false;
$cleanup = substr($key, 0, 11) == "\0\0\0\7ssh-rsa";
if (strlen($key) <= 4) {
return false;
}
extract(unpack('Nlength', $this->_string_shift($key, 4)));
$publicExponent = new Math_BigInteger($this->_string_shift($key, $length), -256);
if (strlen($key) <= 4) {
return false;
}
extract(unpack('Nlength', $this->_string_shift($key, 4)));
$modulus = new Math_BigInteger($this->_string_shift($key, $length), -256);
if ($cleanup && strlen($key)) {
if (strlen($key) <= 4) {
return false;
}
extract(unpack('Nlength', $this->_string_shift($key, 4)));
$realModulus = new Math_BigInteger($this->_string_shift($key, $length), -256);
return strlen($key) ? false : array(
'modulus' => $realModulus,
'publicExponent' => $modulus,
'comment' => $comment
);
} else {
return strlen($key) ? false : array(
'modulus' => $modulus,
'publicExponent' => $publicExponent,
'comment' => $comment
);
}
case CRYPT_RSA_PRIVATE_FORMAT_XML:
case CRYPT_RSA_PUBLIC_FORMAT_XML:
$this->components = array();
$xml = xml_parser_create('UTF-8');
xml_set_object($xml, $this);
xml_set_element_handler($xml, '_start_element_handler', '_stop_element_handler');
xml_set_character_data_handler($xml, '_data_handler');
if (!xml_parse($xml, '<xml>' . $key . '</xml>')) {
xml_parser_free($xml);
unset($xml);
return false;
}
xml_parser_free($xml);
unset($xml);
return isset($this->components['modulus']) && isset($this->components['publicExponent']) ? $this->components : false;
case CRYPT_RSA_PRIVATE_FORMAT_PUTTY:
$components = array();
$key = preg_split('#\r\n|\r|\n#', $key);
$type = trim(preg_replace('#PuTTY-User-Key-File-2: (.+)#', '$1', $key[0]));
if ($type != 'ssh-rsa') {
return false;
}
$encryption = trim(preg_replace('#Encryption: (.+)#', '$1', $key[1]));
$comment = trim(preg_replace('#Comment: (.+)#', '$1', $key[2]));
$publicLength = trim(preg_replace('#Public-Lines: (\d+)#', '$1', $key[3]));
$public = base64_decode(implode('', array_map('trim', array_slice($key, 4, $publicLength))));
$public = substr($public, 11);
extract(unpack('Nlength', $this->_string_shift($public, 4)));
$components['publicExponent'] = new Math_BigInteger($this->_string_shift($public, $length), -256);
extract(unpack('Nlength', $this->_string_shift($public, 4)));
$components['modulus'] = new Math_BigInteger($this->_string_shift($public, $length), -256);
$privateLength = trim(preg_replace('#Private-Lines: (\d+)#', '$1', $key[$publicLength + 4]));
$private = base64_decode(implode('', array_map('trim', array_slice($key, $publicLength + 5, $privateLength))));
switch ($encryption) {
case 'aes256-cbc':
if (!class_exists('Crypt_AES')) {
include_once 'Crypt/AES.php';
}
$symkey = '';
$sequence = 0;
while (strlen($symkey) < 32) {
$temp = pack('Na*', $sequence++, $this->password);
$symkey.= pack('H*', sha1($temp));
}
$symkey = substr($symkey, 0, 32);
$crypto = new Crypt_AES();
}
if ($encryption != 'none') {
$crypto->setKey($symkey);
$crypto->disablePadding();
$private = $crypto->decrypt($private);
if ($private === false) {
return false;
}
}
extract(unpack('Nlength', $this->_string_shift($private, 4)));
if (strlen($private) < $length) {
return false;
}
$components['privateExponent'] = new Math_BigInteger($this->_string_shift($private, $length), -256);
extract(unpack('Nlength', $this->_string_shift($private, 4)));
if (strlen($private) < $length) {
return false;
}
$components['primes'] = array(1 => new Math_BigInteger($this->_string_shift($private, $length), -256));
extract(unpack('Nlength', $this->_string_shift($private, 4)));
if (strlen($private) < $length) {
return false;
}
$components['primes'][] = new Math_BigInteger($this->_string_shift($private, $length), -256);
$temp = $components['primes'][1]->subtract($this->one);
$components['exponents'] = array(1 => $components['publicExponent']->modInverse($temp));
$temp = $components['primes'][2]->subtract($this->one);
$components['exponents'][] = $components['publicExponent']->modInverse($temp);
extract(unpack('Nlength', $this->_string_shift($private, 4)));
if (strlen($private) < $length) {
return false;
}
$components['coefficients'] = array(2 => new Math_BigInteger($this->_string_shift($private, $length), -256));
return $components;
case CRYPT_RSA_PRIVATE_FORMAT_OPENSSH:
$components = array();
$decoded = $this->_extractBER($key);
$magic = $this->_string_shift($decoded, 15);
if ($magic !== "openssh-key-v1\0") {
return false;
}
$options = $this->_string_shift($decoded, 24);
if ($options != "\0\0\0\4none\0\0\0\4none\0\0\0\0\0\0\0\1") {
return false;
}
extract(unpack('Nlength', $this->_string_shift($decoded, 4)));
if (strlen($decoded) < $length) {
return false;
}
$publicKey = $this->_string_shift($decoded, $length);
extract(unpack('Nlength', $this->_string_shift($decoded, 4)));
if (strlen($decoded) < $length) {
return false;
}
$paddedKey = $this->_string_shift($decoded, $length);
if ($this->_string_shift($publicKey, 11) !== "\0\0\0\7ssh-rsa") {
return false;
}
$checkint1 = $this->_string_shift($paddedKey, 4);
$checkint2 = $this->_string_shift($paddedKey, 4);
if (strlen($checkint1) != 4 || $checkint1 !== $checkint2) {
return false;
}
if ($this->_string_shift($paddedKey, 11) !== "\0\0\0\7ssh-rsa") {
return false;
}
$values = array(
&$components['modulus'],
&$components['publicExponent'],
&$components['privateExponent'],
&$components['coefficients'][2],
&$components['primes'][1],
&$components['primes'][2]
);
foreach ($values as &$value) {
extract(unpack('Nlength', $this->_string_shift($paddedKey, 4)));
if (strlen($paddedKey) < $length) {
return false;
}
$value = new Math_BigInteger($this->_string_shift($paddedKey, $length), -256);
}
extract(unpack('Nlength', $this->_string_shift($paddedKey, 4)));
if (strlen($paddedKey) < $length) {
return false;
}
$components['comment'] = $this->_string_shift($decoded, $length);
$temp = $components['primes'][1]->subtract($this->one);
$components['exponents'] = array(1 => $components['publicExponent']->modInverse($temp));
$temp = $components['primes'][2]->subtract($this->one);
$components['exponents'][] = $components['publicExponent']->modInverse($temp);
return $components;
}
}
* Returns the key size
*
* More specifically, this returns the size of the modulo in bits.
*
* @access public
* @return int
*/
function getSize() {
}
* Start Element Handler
*
* Called by xml_set_element_handler()
*
* @access private
* @param resource $parser
* @param string $name
* @param array $attribs
*/
function _start_element_handler() {
}
$this->current = '';
}
* Stop Element Handler
*
* Called by xml_set_element_handler()
*
* @access private
* @param resource $parser
* @param string $name
*/
function _stop_element_handler() {
}
}
* Data Handler
*
* Called by xml_set_character_data_handler()
*
* @access private
* @param resource $parser
* @param string $data
*/
function _data_handler() {
}
$this->current.= trim($data);
}
* Loads a public or private key
*
* Returns true on success and false on failure (ie. an incorrect password was provided or the key was malformed)
*
* @access public
* @param string $key
* @param int $type optional
*/
function loadKey() {
}
if (is_object($key->mgfHash)) {
$this->mgfHash = new Crypt_Hash($key->mgfHash->getHash());
}
if (is_object($key->modulus)) {
$this->modulus = $key->modulus->copy();
}
if (is_object($key->exponent)) {
$this->exponent = $key->exponent->copy();
}
if (is_object($key->publicExponent)) {
$this->publicExponent = $key->publicExponent->copy();
}
$this->primes = array();
$this->exponents = array();
$this->coefficients = array();
foreach ($this->primes as $prime) {
$this->primes[] = $prime->copy();
}
foreach ($this->exponents as $exponent) {
$this->exponents[] = $exponent->copy();
}
foreach ($this->coefficients as $coefficient) {
$this->coefficients[] = $coefficient->copy();
}
return true;
}
if ($type === false) {
$types = array(
CRYPT_RSA_PUBLIC_FORMAT_RAW,
CRYPT_RSA_PRIVATE_FORMAT_PKCS1,
CRYPT_RSA_PRIVATE_FORMAT_XML,
CRYPT_RSA_PRIVATE_FORMAT_PUTTY,
CRYPT_RSA_PUBLIC_FORMAT_OPENSSH,
CRYPT_RSA_PRIVATE_FORMAT_OPENSSH
);
foreach ($types as $type) {
$components = $this->_parseKey($key, $type);
if ($components !== false) {
break;
}
}
} else {
$components = $this->_parseKey($key, $type);
}
if ($components === false) {
$this->comment = null;
$this->modulus = null;
$this->k = null;
$this->exponent = null;
$this->primes = null;
$this->exponents = null;
$this->coefficients = null;
$this->publicExponent = null;
return false;
}
if (isset($components['comment']) && $components['comment'] !== false) {
$this->comment = $components['comment'];
}
$this->modulus = $components['modulus'];
$this->k = strlen($this->modulus->toBytes());
$this->exponent = isset($components['privateExponent']) ? $components['privateExponent'] : $components['publicExponent'];
if (isset($components['primes'])) {
$this->primes = $components['primes'];
$this->exponents = $components['exponents'];
$this->coefficients = $components['coefficients'];
$this->publicExponent = $components['publicExponent'];
} else {
$this->primes = array();
$this->exponents = array();
$this->coefficients = array();
$this->publicExponent = false;
}
switch ($type) {
case CRYPT_RSA_PUBLIC_FORMAT_OPENSSH:
case CRYPT_RSA_PUBLIC_FORMAT_RAW:
$this->setPublicKey();
break;
case CRYPT_RSA_PRIVATE_FORMAT_PKCS1:
switch (true) {
case strpos($key, '-BEGIN PUBLIC KEY-') !== false:
case strpos($key, '-BEGIN RSA PUBLIC KEY-') !== false:
$this->setPublicKey();
}
}
return true;
}
* Sets the password
*
* Private keys can be encrypted with a password.  To unset the password, pass in the empty string or false.
* Or rather, pass in $password such that empty($password) && !is_string($password) is true.
*
* @see self::createKey()
* @see self::loadKey()
* @access public
* @param string $password
*/
function setPassword() {
}
* Defines the public key
*
* Some private key formats define the public exponent and some don't.  Those that don't define it are problematic when
* used in certain contexts.  For example, in SSH-2, RSA authentication works by sending the public key along with a
* message signed by the private key to the server.  The SSH-2 server looks the public key up in an index of public keys
* and if it's present then proceeds to verify the signature.  Problem is, if your private key doesn't include the public
* exponent this won't work unless you manually add the public exponent. phpseclib tries to guess if the key being used
* is the public key but in the event that it guesses incorrectly you might still want to explicitly set the key as being
* public.
*
* Do note that when a new key is loaded the index will be cleared.
*
* Returns true on success, false on failure
*
* @see self::getPublicKey()
* @access public
* @param string $key optional
* @param int $type optional
* @return bool
*/
function setPublicKey() {
}
if ($key === false && !empty($this->modulus)) {
$this->publicExponent = $this->exponent;
return true;
}
if ($type === false) {
$types = array(
CRYPT_RSA_PUBLIC_FORMAT_RAW,
CRYPT_RSA_PUBLIC_FORMAT_PKCS1,
CRYPT_RSA_PUBLIC_FORMAT_XML,
CRYPT_RSA_PUBLIC_FORMAT_OPENSSH
);
foreach ($types as $type) {
$components = $this->_parseKey($key, $type);
if ($components !== false) {
break;
}
}
} else {
$components = $this->_parseKey($key, $type);
}
if ($components === false) {
return false;
}
if (empty($this->modulus) || !$this->modulus->equals($components['modulus'])) {
$this->modulus = $components['modulus'];
$this->exponent = $this->publicExponent = $components['publicExponent'];
return true;
}
$this->publicExponent = $components['publicExponent'];
return true;
}
* Defines the private key
*
* If phpseclib guessed a private key was a public key and loaded it as such it might be desirable to force
* phpseclib to treat the key as a private key. This function will do that.
*
* Do note that when a new key is loaded the index will be cleared.
*
* Returns true on success, false on failure
*
* @see self::getPublicKey()
* @access public
* @param string $key optional
* @param int $type optional
* @return bool
*/
function setPrivateKey() {
}
$rsa = new Crypt_RSA();
if (!$rsa->loadKey($key, $type)) {
return false;
}
$rsa->publicExponent = false;
$this->loadKey($rsa);
return true;
}
* Returns the public key
*
* The public key is only returned under two circumstances - if the private key had the public key embedded within it
* or if the public key was set via setPublicKey().  If the currently loaded key is supposed to be the public key this
* function won't return it since this library, for the most part, doesn't distinguish between public and private keys.
*
* @see self::getPublicKey()
* @access public
* @param string $key
* @param int $type optional
*/
function getPublicKey() {
}
$oldFormat = $this->publicKeyFormat;
$this->publicKeyFormat = $type;
$temp = $this->_convertPublicKey($this->modulus, $this->publicExponent);
$this->publicKeyFormat = $oldFormat;
return $temp;
}
* Returns the public key's fingerprint
*
* The public key's fingerprint is returned, which is equivalent to running `ssh-keygen -lf rsa.pub`. If there is
* no public key currently loaded, false is returned.
* Example output (md5): "c1:b1:30:29:d7:b8:de:6c:97:77:10:d7:46:41:63:87" (as specified by RFC 4716)
*
* @access public
* @param string $algorithm The hashing algorithm to be used. Valid options are 'md5' and 'sha256'. False is returned
* for invalid values.
* @return mixed
*/
function getPublicKeyFingerprint() {
}
$modulus = $this->modulus->toBytes(true);
$publicExponent = $this->publicExponent->toBytes(true);
$RSAPublicKey = pack('Na*Na*Na*', strlen('ssh-rsa'), 'ssh-rsa', strlen($publicExponent), $publicExponent, strlen($modulus), $modulus);
switch ($algorithm) {
case 'sha256':
$hash = new Crypt_Hash('sha256');
$base = base64_encode($hash->hash($RSAPublicKey));
return substr($base, 0, strlen($base) - 1);
case 'md5':
return substr(chunk_split(md5($RSAPublicKey), 2, ':'), 0, -1);
default:
return false;
}
}
* Returns the private key
*
* The private key is only returned if the currently loaded key contains the constituent prime numbers.
*
* @see self::getPublicKey()
* @access public
* @param string $key
* @param int $type optional
* @return mixed
*/
function getPrivateKey() {
}
$oldFormat = $this->privateKeyFormat;
$this->privateKeyFormat = $type;
$temp = $this->_convertPrivateKey($this->modulus, $this->publicExponent, $this->exponent, $this->primes, $this->exponents, $this->coefficients);
$this->privateKeyFormat = $oldFormat;
return $temp;
}
* Returns a minimalistic private key
*
* Returns the private key without the prime number constituants.  Structurally identical to a public key that
* hasn't been set as the public key
*
* @see self::getPrivateKey()
* @access private
* @param string $key
* @param int $type optional
*/
function _getPrivatePublicKey() {
}
$oldFormat = $this->publicKeyFormat;
$this->publicKeyFormat = $mode;
$temp = $this->_convertPublicKey($this->modulus, $this->exponent);
$this->publicKeyFormat = $oldFormat;
return $temp;
}
*  __toString() magic method
*
* @access public
* @return string
*/
function __toString() {
}
$key = $this->_getPrivatePublicKey($this->publicKeyFormat);
return $key !== false ? $key : '';
}
*  __clone() magic method
*
* @access public
* @return Crypt_RSA
*/
function __clone() {
}
* Generates the smallest and largest numbers requiring $bits bits
*
* @access private
* @param int $bits
* @return array
*/
function _generateMinMax() {
} else {
$min[0] = chr(0x80);
}
return array(
'min' => new Math_BigInteger($min, 256),
'max' => new Math_BigInteger($max, 256)
);
}
* DER-decode the length
*
* DER supports lengths up to (2**8)**127, however, we'll only support lengths up to (2**8)**4.  See
* {@link http://itu.int/ITU-T/studygroups/com17/languages/X.690-0207.pdf#p=13 X.690 paragraph 8.1.3} for more information.
*
* @access private
* @param string $string
* @return int
*/
function _decodeLength() {
}
return $length;
}
* DER-encode the length
*
* DER supports lengths up to (2**8)**127, however, we'll only support lengths up to (2**8)**4.  See
* {@link http://itu.int/ITU-T/studygroups/com17/languages/X.690-0207.pdf#p=13 X.690 paragraph 8.1.3} for more information.
*
* @access private
* @param int $length
* @return string
*/
function _encodeLength() {
}
$temp = ltrim(pack('N', $length), chr(0));
return pack('Ca*', 0x80 | strlen($temp), $temp);
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
* Determines the private key format
*
* @see self::createKey()
* @access public
* @param int $format
*/
function setPrivateKeyFormat() {
}
* Determines the public key format
*
* @see self::createKey()
* @access public
* @param int $format
*/
function setPublicKeyFormat() {
}
* Determines which hashing function should be used
*
* Used with signature production / verification and (if the encryption mode is CRYPT_RSA_ENCRYPTION_OAEP) encryption and
* decryption.  If $hash isn't supported, sha1 is used.
*
* @access public
* @param string $hash
*/
function setHash() {
}
$this->hLen = $this->hash->getLength();
}
* Determines which hashing function should be used for the mask generation function
*
* The mask generation function is used by CRYPT_RSA_ENCRYPTION_OAEP and CRYPT_RSA_SIGNATURE_PSS and although it's
* best if Hash and MGFHash are set to the same thing this is not a requirement.
*
* @access public
* @param string $hash
*/
function setMGFHash() {
}
$this->mgfHLen = $this->mgfHash->getLength();
}
* Determines the salt length
*
* To quote from {@link http://tools.ietf.org/html/rfc3447#page-38 RFC3447#page-38}:
*
*    Typical salt lengths in octets are hLen (the length of the output
*    of the hash function Hash) and 0.
*
* @access public
* @param int $format
*/
function setSaltLength() {
}
* Integer-to-Octet-String primitive
*
* See {@link http://tools.ietf.org/html/rfc3447#section-4.1 RFC3447#section-4.1}.
*
* @access private
* @param Math_BigInteger $x
* @param int $xLen
* @return string
*/
function _i2osp() {
}
return str_pad($x, $xLen, chr(0), STR_PAD_LEFT);
}
* Octet-String-to-Integer primitive
*
* See {@link http://tools.ietf.org/html/rfc3447#section-4.2 RFC3447#section-4.2}.
*
* @access private
* @param string $x
* @return Math_BigInteger
*/
function _os2ip() {
}
* Exponentiate with or without Chinese Remainder Theorem
*
* See {@link http://tools.ietf.org/html/rfc3447#section-5.1.1 RFC3447#section-5.1.2}.
*
* @access private
* @param Math_BigInteger $x
* @return Math_BigInteger
*/
function _exponentiate() {
}
$num_primes = count($this->primes);
if (defined('CRYPT_RSA_DISABLE_BLINDING')) {
$m_i = array(
1 => $x->modPow($this->exponents[1], $this->primes[1]),
2 => $x->modPow($this->exponents[2], $this->primes[2])
);
$h = $m_i[1]->subtract($m_i[2]);
$h = $h->multiply($this->coefficients[2]);
list(, $h) = $h->divide($this->primes[1]);
$m = $m_i[2]->add($h->multiply($this->primes[2]));
$r = $this->primes[1];
for ($i = 3; $i <= $num_primes; $i++) {
$m_i = $x->modPow($this->exponents[$i], $this->primes[$i]);
$r = $r->multiply($this->primes[$i - 1]);
$h = $m_i->subtract($m);
$h = $h->multiply($this->coefficients[$i]);
list(, $h) = $h->divide($this->primes[$i]);
$m = $m->add($r->multiply($h));
}
} else {
$smallest = $this->primes[1];
for ($i = 2; $i <= $num_primes; $i++) {
if ($smallest->compare($this->primes[$i]) > 0) {
$smallest = $this->primes[$i];
}
}
$one = new Math_BigInteger(1);
$r = $one->random($one, $smallest->subtract($one));
$m_i = array(
1 => $this->_blind($x, $r, 1),
2 => $this->_blind($x, $r, 2)
);
$h = $m_i[1]->subtract($m_i[2]);
$h = $h->multiply($this->coefficients[2]);
list(, $h) = $h->divide($this->primes[1]);
$m = $m_i[2]->add($h->multiply($this->primes[2]));
$r = $this->primes[1];
for ($i = 3; $i <= $num_primes; $i++) {
$m_i = $this->_blind($x, $r, $i);
$r = $r->multiply($this->primes[$i - 1]);
$h = $m_i->subtract($m);
$h = $h->multiply($this->coefficients[$i]);
list(, $h) = $h->divide($this->primes[$i]);
$m = $m->add($r->multiply($h));
}
}
return $m;
}
* Performs RSA Blinding
*
* Protects against timing attacks by employing RSA Blinding.
* Returns $x->modPow($this->exponents[$i], $this->primes[$i])
*
* @access private
* @param Math_BigInteger $x
* @param Math_BigInteger $r
* @param int $i
* @return Math_BigInteger
*/
function _blind() {
}
* Performs blinded RSA equality testing
*
* Protects against a particular type of timing attack described.
*
* See {@link http://codahale.com/a-lesson-in-timing-attacks/ A Lesson In Timing Attacks (or, Don't use MessageDigest.isEquals)}
*
* Thanks for the heads up singpolyma!
*
* @access private
* @param string $x
* @param string $y
* @return bool
*/
function _equals() {
}
if (strlen($x) != strlen($y)) {
return false;
}
$result = "\0";
$x^= $y;
for ($i = 0; $i < strlen($x); $i++) {
$result|= $x[$i];
}
return $result === "\0";
}
* RSAEP
*
* See {@link http://tools.ietf.org/html/rfc3447#section-5.1.1 RFC3447#section-5.1.1}.
*
* @access private
* @param Math_BigInteger $m
* @return Math_BigInteger
*/
function _rsaep() {
}
return $this->_exponentiate($m);
}
* RSADP
*
* See {@link http://tools.ietf.org/html/rfc3447#section-5.1.2 RFC3447#section-5.1.2}.
*
* @access private
* @param Math_BigInteger $c
* @return Math_BigInteger
*/
function _rsadp() {
}
return $this->_exponentiate($c);
}
* RSASP1
*
* See {@link http://tools.ietf.org/html/rfc3447#section-5.2.1 RFC3447#section-5.2.1}.
*
* @access private
* @param Math_BigInteger $m
* @return Math_BigInteger
*/
function _rsasp1() {
}
return $this->_exponentiate($m);
}
* RSAVP1
*
* See {@link http://tools.ietf.org/html/rfc3447#section-5.2.2 RFC3447#section-5.2.2}.
*
* @access private
* @param Math_BigInteger $s
* @return Math_BigInteger
*/
function _rsavp1() {
}
return $this->_exponentiate($s);
}
* MGF1
*
* See {@link http://tools.ietf.org/html/rfc3447#appendix-B.2.1 RFC3447#appendix-B.2.1}.
*
* @access private
* @param string $mgfSeed
* @param int $mgfLen
* @return string
*/
function _mgf1() {
}
return substr($t, 0, $maskLen);
}
* RSAES-OAEP-ENCRYPT
*
* See {@link http://tools.ietf.org/html/rfc3447#section-7.1.1 RFC3447#section-7.1.1} and
* {http://en.wikipedia.org/wiki/Optimal_Asymmetric_Encryption_Padding OAES}.
*
* @access private
* @param string $m
* @param string $l
* @return string
*/
function _rsaes_oaep_encrypt() {
}
$lHash = $this->hash->hash($l);
$ps = str_repeat(chr(0), $this->k - $mLen - 2 * $this->hLen - 2);
$db = $lHash . $ps . chr(1) . $m;
$seed = crypt_random_string($this->hLen);
$dbMask = $this->_mgf1($seed, $this->k - $this->hLen - 1);
$maskedDB = $db ^ $dbMask;
$seedMask = $this->_mgf1($maskedDB, $this->hLen);
$maskedSeed = $seed ^ $seedMask;
$em = chr(0) . $maskedSeed . $maskedDB;
$m = $this->_os2ip($em);
$c = $this->_rsaep($m);
$c = $this->_i2osp($c, $this->k);
return $c;
}
* RSAES-OAEP-DECRYPT
*
* See {@link http://tools.ietf.org/html/rfc3447#section-7.1.2 RFC3447#section-7.1.2}.  The fact that the error
* messages aren't distinguishable from one another hinders debugging, but, to quote from RFC3447#section-7.1.2:
*
*    Note.  Care must be taken to ensure that an opponent cannot
*    distinguish the different error conditions in Step 3.g, whether by
*    error message or timing, or, more generally, learn partial
*    information about the encoded message EM.  Otherwise an opponent may
*    be able to obtain useful information about the decryption of the
*    ciphertext C, leading to a chosen-ciphertext attack such as the one
*    observed by Manger [36].
*
* As for $l...  to quote from {@link http://tools.ietf.org/html/rfc3447#page-17 RFC3447#page-17}:
*
*    Both the encryption and the decryption operations of RSAES-OAEP take
*    the value of a label L as input.  In this version of PKCS #1, L is
*    the empty string; other uses of the label are outside the scope of
*    this document.
*
* @access private
* @param string $c
* @param string $l
* @return string
*/
function _rsaes_oaep_decrypt() {
}
$c = $this->_os2ip($c);
$m = $this->_rsadp($c);
if ($m === false) {
user_error('Decryption error');
return false;
}
$em = $this->_i2osp($m, $this->k);
$lHash = $this->hash->hash($l);
$y = ord($em[0]);
$maskedSeed = substr($em, 1, $this->hLen);
$maskedDB = substr($em, $this->hLen + 1);
$seedMask = $this->_mgf1($maskedDB, $this->hLen);
$seed = $maskedSeed ^ $seedMask;
$dbMask = $this->_mgf1($seed, $this->k - $this->hLen - 1);
$db = $maskedDB ^ $dbMask;
$lHash2 = substr($db, 0, $this->hLen);
$m = substr($db, $this->hLen);
$hashesMatch = $this->_equals($lHash, $lHash2);
$leadingZeros = 1;
$patternMatch = 0;
$offset = 0;
for ($i = 0; $i < strlen($m); $i++) {
$patternMatch|= $leadingZeros & ($m[$i] === "\1");
$leadingZeros&= $m[$i] === "\0";
$offset+= $patternMatch ? 0 : 1;
}
if (!$hashesMatch & !$patternMatch) {
user_error('Decryption error');
return false;
}
return substr($m, $offset + 1);
}
* Raw Encryption / Decryption
*
* Doesn't use padding and is not recommended.
*
* @access private
* @param string $m
* @return string
*/
function _raw_encrypt() {
}
* RSAES-PKCS1-V1_5-ENCRYPT
*
* See {@link http://tools.ietf.org/html/rfc3447#section-7.2.1 RFC3447#section-7.2.1}.
*
* @access private
* @param string $m
* @return string
*/
function _rsaes_pkcs1_v1_5_encrypt() {
}
$psLen = $this->k - $mLen - 3;
$ps = '';
while (strlen($ps) != $psLen) {
$temp = crypt_random_string($psLen - strlen($ps));
$temp = str_replace(" ", '', $temp);
$ps.= $temp;
}
$type = 2;
if (defined('CRYPT_RSA_PKCS15_COMPAT') && (!isset($this->publicExponent) || $this->exponent !== $this->publicExponent)) {
$type = 1;
$ps = str_repeat("ÿ", $psLen);
}
$em = chr(0) . chr($type) . $ps . chr(0) . $m;
$m = $this->_os2ip($em);
$c = $this->_rsaep($m);
$c = $this->_i2osp($c, $this->k);
return $c;
}
* RSAES-PKCS1-V1_5-DECRYPT
*
* See {@link http://tools.ietf.org/html/rfc3447#section-7.2.2 RFC3447#section-7.2.2}.
*
* For compatibility purposes, this function departs slightly from the description given in RFC3447.
* The reason being that RFC2313#section-8.1 (PKCS#1 v1.5) states that ciphertext's encrypted by the
* private key should have the second byte set to either 0 or 1 and that ciphertext's encrypted by the
* public key should have the second byte set to 2.  In RFC3447 (PKCS#1 v2.1), the second byte is supposed
* to be 2 regardless of which key is used.  For compatibility purposes, we'll just check to make sure the
* second byte is 2 or less.  If it is, we'll accept the decrypted string as valid.
*
* As a consequence of this, a private key encrypted ciphertext produced with Crypt_RSA may not decrypt
* with a strictly PKCS#1 v1.5 compliant RSA implementation.  Public key encrypted ciphertext's should but
* not private key encrypted ciphertext's.
*
* @access private
* @param string $c
* @return string
*/
function _rsaes_pkcs1_v1_5_decrypt() {
}
$c = $this->_os2ip($c);
$m = $this->_rsadp($c);
if ($m === false) {
user_error('Decryption error');
return false;
}
$em = $this->_i2osp($m, $this->k);
if (ord($em[0]) != 0 || ord($em[1]) > 2) {
user_error('Decryption error');
return false;
}
$ps = substr($em, 2, strpos($em, chr(0), 2) - 2);
$m = substr($em, strlen($ps) + 3);
if (strlen($ps) < 8) {
user_error('Decryption error');
return false;
}
return $m;
}
* EMSA-PSS-ENCODE
*
* See {@link http://tools.ietf.org/html/rfc3447#section-9.1.1 RFC3447#section-9.1.1}.
*
* @access private
* @param string $m
* @param int $emBits
*/
function _emsa_pss_encode() {
}
$salt = crypt_random_string($sLen);
$m2 = "\0\0\0\0\0\0\0\0" . $mHash . $salt;
$h = $this->hash->hash($m2);
$ps = str_repeat(chr(0), $emLen - $sLen - $this->hLen - 2);
$db = $ps . chr(1) . $salt;
$dbMask = $this->_mgf1($h, $emLen - $this->hLen - 1);
$maskedDB = $db ^ $dbMask;
$maskedDB[0] = ~chr(0xFF << ($emBits & 7)) & $maskedDB[0];
$em = $maskedDB . $h . chr(0xBC);
return $em;
}
* EMSA-PSS-VERIFY
*
* See {@link http://tools.ietf.org/html/rfc3447#section-9.1.2 RFC3447#section-9.1.2}.
*
* @access private
* @param string $m
* @param string $em
* @param int $emBits
* @return string
*/
function _emsa_pss_verify() {
}
if ($em[strlen($em) - 1] != chr(0xBC)) {
return false;
}
$maskedDB = substr($em, 0, -$this->hLen - 1);
$h = substr($em, -$this->hLen - 1, $this->hLen);
$temp = chr(0xFF << ($emBits & 7));
if ((~$maskedDB[0] & $temp) != $temp) {
return false;
}
$dbMask = $this->_mgf1($h, $emLen - $this->hLen - 1);
$db = $maskedDB ^ $dbMask;
$db[0] = ~chr(0xFF << ($emBits & 7)) & $db[0];
$temp = $emLen - $this->hLen - $sLen - 2;
if (substr($db, 0, $temp) != str_repeat(chr(0), $temp) || ord($db[$temp]) != 1) {
return false;
}
$salt = substr($db, $temp + 1); // should be $sLen long
$m2 = "\0\0\0\0\0\0\0\0" . $mHash . $salt;
$h2 = $this->hash->hash($m2);
return $this->_equals($h, $h2);
}
* RSASSA-PSS-SIGN
*
* See {@link http://tools.ietf.org/html/rfc3447#section-8.1.1 RFC3447#section-8.1.1}.
*
* @access private
* @param string $m
* @return string
*/
function _rsassa_pss_sign() {
}
* RSASSA-PSS-VERIFY
*
* See {@link http://tools.ietf.org/html/rfc3447#section-8.1.2 RFC3447#section-8.1.2}.
*
* @access private
* @param string $m
* @param string $s
* @return string
*/
function _rsassa_pss_verify() {
}
$modBits = 8 * $this->k;
$s2 = $this->_os2ip($s);
$m2 = $this->_rsavp1($s2);
if ($m2 === false) {
user_error('Invalid signature');
return false;
}
$em = $this->_i2osp($m2, $modBits >> 3);
if ($em === false) {
user_error('Invalid signature');
return false;
}
return $this->_emsa_pss_verify($m, $em, $modBits - 1);
}
* EMSA-PKCS1-V1_5-ENCODE
*
* See {@link http://tools.ietf.org/html/rfc3447#section-9.2 RFC3447#section-9.2}.
*
* @access private
* @param string $m
* @param int $emLen
* @return string
*/
function _emsa_pkcs1_v1_5_encode() {
}
switch ($this->hashName) {
case 'md2':
$t = pack('H*', '3020300c06082a864886f70d020205000410');
break;
case 'md5':
$t = pack('H*', '3020300c06082a864886f70d020505000410');
break;
case 'sha1':
$t = pack('H*', '3021300906052b0e03021a05000414');
break;
case 'sha256':
$t = pack('H*', '3031300d060960864801650304020105000420');
break;
case 'sha384':
$t = pack('H*', '3041300d060960864801650304020205000430');
break;
case 'sha512':
$t = pack('H*', '3051300d060960864801650304020305000440');
}
$t.= $h;
$tLen = strlen($t);
if ($emLen < $tLen + 11) {
user_error('Intended encoded message length too short');
return false;
}
$ps = str_repeat(chr(0xFF), $emLen - $tLen - 3);
$em = "\0\1$ps\0$t";
return $em;
}
* RSASSA-PKCS1-V1_5-SIGN
*
* See {@link http://tools.ietf.org/html/rfc3447#section-8.2.1 RFC3447#section-8.2.1}.
*
* @access private
* @param string $m
* @return string
*/
function _rsassa_pkcs1_v1_5_sign() {
}
$m = $this->_os2ip($em);
$s = $this->_rsasp1($m);
$s = $this->_i2osp($s, $this->k);
return $s;
}
* RSASSA-PKCS1-V1_5-VERIFY
*
* See {@link http://tools.ietf.org/html/rfc3447#section-8.2.2 RFC3447#section-8.2.2}.
*
* @access private
* @param string $m
* @return string
*/
function _rsassa_pkcs1_v1_5_verify() {
}
$s = $this->_os2ip($s);
$m2 = $this->_rsavp1($s);
if ($m2 === false) {
user_error('Invalid signature');
return false;
}
$em = $this->_i2osp($m2, $this->k);
if ($em === false) {
user_error('Invalid signature');
return false;
}
$em2 = $this->_emsa_pkcs1_v1_5_encode($m, $this->k);
if ($em2 === false) {
user_error('RSA modulus too short');
return false;
}
return $this->_equals($em, $em2);
}
* Set Encryption Mode
*
* Valid values include CRYPT_RSA_ENCRYPTION_OAEP and CRYPT_RSA_ENCRYPTION_PKCS1.
*
* @access public
* @param int $mode
*/
function setEncryptionMode() {
}
* Set Signature Mode
*
* Valid values include CRYPT_RSA_SIGNATURE_PSS and CRYPT_RSA_SIGNATURE_PKCS1
*
* @access public
* @param int $mode
*/
function setSignatureMode() {
}
* Set public key comment.
*
* @access public
* @param string $comment
*/
function setComment() {
}
* Get public key comment.
*
* @access public
* @return string
*/
function getComment() {
}
* Encryption
*
* Both CRYPT_RSA_ENCRYPTION_OAEP and CRYPT_RSA_ENCRYPTION_PKCS1 both place limits on how long $plaintext can be.
* If $plaintext exceeds those limits it will be broken up so that it does and the resultant ciphertext's will
* be concatenated together.
*
* @see self::decrypt()
* @access public
* @param string $plaintext
* @return string
*/
function encrypt() {
}
return $ciphertext;
case CRYPT_RSA_ENCRYPTION_PKCS1:
$length = $this->k - 11;
if ($length <= 0) {
return false;
}
$plaintext = str_split($plaintext, $length);
$ciphertext = '';
foreach ($plaintext as $m) {
$ciphertext.= $this->_rsaes_pkcs1_v1_5_encrypt($m);
}
return $ciphertext;
default:
$length = $this->k - 2 * $this->hLen - 2;
if ($length <= 0) {
return false;
}
$plaintext = str_split($plaintext, $length);
$ciphertext = '';
foreach ($plaintext as $m) {
$ciphertext.= $this->_rsaes_oaep_encrypt($m);
}
return $ciphertext;
}
}
* Decryption
*
* @see self::encrypt()
* @access public
* @param string $plaintext
* @return string
*/
function decrypt() {
}
$ciphertext = str_split($ciphertext, $this->k);
$ciphertext[count($ciphertext) - 1] = str_pad($ciphertext[count($ciphertext) - 1], $this->k, chr(0), STR_PAD_LEFT);
$plaintext = '';
switch ($this->encryptionMode) {
case CRYPT_RSA_ENCRYPTION_NONE:
$decrypt = '_raw_encrypt';
break;
case CRYPT_RSA_ENCRYPTION_PKCS1:
$decrypt = '_rsaes_pkcs1_v1_5_decrypt';
break;
default:
$decrypt = '_rsaes_oaep_decrypt';
}
foreach ($ciphertext as $c) {
$temp = $this->$decrypt($c);
if ($temp === false) {
return false;
}
$plaintext.= $temp;
}
return $plaintext;
}
* Create a signature
*
* @see self::verify()
* @access public
* @param string $message
* @return string
*/
function sign() {
}
switch ($this->signatureMode) {
case CRYPT_RSA_SIGNATURE_PKCS1:
return $this->_rsassa_pkcs1_v1_5_sign($message);
default:
return $this->_rsassa_pss_sign($message);
}
}
* Verifies a signature
*
* @see self::sign()
* @access public
* @param string $message
* @param string $signature
* @return bool
*/
function verify() {
}
switch ($this->signatureMode) {
case CRYPT_RSA_SIGNATURE_PKCS1:
return $this->_rsassa_pkcs1_v1_5_verify($message, $signature);
default:
return $this->_rsassa_pss_verify($message, $signature);
}
}
* Extract raw BER from Base64 encoding
*
* @access private
* @param string $str
* @return string
*/
function _extractBER() {
}$#', $temp) ? base64_decode($temp) : false;
return $temp != false ? $temp : $str;
}
}