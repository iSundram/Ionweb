<?php
* Pure-PHP arbitrary precision integer arithmetic library.
*
* Supports base-2, base-10, base-16, and base-256 numbers.  Uses the GMP or BCMath extensions, if available,
* and an internal implementation, otherwise.
*
* PHP versions 4 and 5
*
* {@internal (all DocBlock comments regarding implementation - such as the one that follows - refer to the
* {@link MATH_BIGINTEGER_MODE_INTERNAL MATH_BIGINTEGER_MODE_INTERNAL} mode)
*
* Math_BigInteger uses base-2**26 to perform operations such as multiplication and division and
* base-2**52 (ie. two base 2**26 digits) to perform addition and subtraction.  Because the largest possible
* value when multiplying two base-2**26 numbers together is a base-2**52 number, double precision floating
* point numbers - numbers that should be supported on most hardware and whose significand is 53 bits - are
* used.  As a consequence, bitwise operators such as >> and << cannot be used, nor can the modulo operator %,
* which only supports integers.  Although this fact will slow this library down, the fact that such a high
* base is being used should more than compensate.
*
* Numbers are stored in {@link http://en.wikipedia.org/wiki/Endianness little endian} format.  ie.
* (new Math_BigInteger(pow(2, 26)))->value = array(0, 1)
*
* Useful resources are as follows:
*
*  - {@link http://www.cacr.math.uwaterloo.ca/hac/about/chap14.pdf Handbook of Applied Cryptography (HAC)}
*  - {@link http://math.libtomcrypt.com/files/tommath.pdf Multi-Precision Math (MPM)}
*  - Java's BigInteger classes.  See /j2se/src/share/classes/java/math in jdk-1_5_0-src-jrl.zip
*
* Here's an example of how to use this library:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger(2);
*    $b = new Math_BigInteger(3);
*
*    $c = $a->add($b);
*
*    echo $c->toString(); // outputs 5
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
* @category  Math
* @package   Math_BigInteger
* @author    Jim Wigginton <terrafrost@php.net>
* @copyright 2006 Jim Wigginton
* @license   http://www.opensource.org/licenses/mit-license.html  MIT License
*/
* Reduction constants
*
* @access private
* @see self::_reduce()
*/
* @see self::_montgomery()
* @see self::_prepMontgomery()
*/
define('MATH_BIGINTEGER_MONTGOMERY', 0);
* @see self::_barrett()
*/
define('MATH_BIGINTEGER_BARRETT', 1);
* @see self::_mod2()
*/
define('MATH_BIGINTEGER_POWEROF2', 2);
* @see self::_remainder()
*/
define('MATH_BIGINTEGER_CLASSIC', 3);
* @see self::__clone()
*/
define('MATH_BIGINTEGER_NONE', 4);
* Array constants
*
* Rather than create a thousands and thousands of new Math_BigInteger objects in repeated function calls to add() and
* multiply() or whatever, we'll just work directly on arrays, taking them in as parameters and returning them.
*
* @access private
*/
* $result[MATH_BIGINTEGER_VALUE] contains the value.
*/
define('MATH_BIGINTEGER_VALUE', 0);
* $result[MATH_BIGINTEGER_SIGN] contains the sign.
*/
define('MATH_BIGINTEGER_SIGN', 1);
* @access private
* @see self::_montgomery()
* @see self::_barrett()
*/
* Cache constants
*
* $cache[MATH_BIGINTEGER_VARIABLE] tells us whether or not the cached data is still valid.
*/
define('MATH_BIGINTEGER_VARIABLE', 0);
* $cache[MATH_BIGINTEGER_DATA] contains the cached data.
*/
define('MATH_BIGINTEGER_DATA', 1);
* Mode constants.
*
* @access private
* @see self::Math_BigInteger()
*/
* To use the pure-PHP implementation
*/
define('MATH_BIGINTEGER_MODE_INTERNAL', 1);
* To use the BCMath library
*
* (if enabled; otherwise, the internal implementation will be used)
*/
define('MATH_BIGINTEGER_MODE_BCMATH', 2);
* To use the GMP library
*
* (if present; otherwise, either the BCMath or the internal implementation will be used)
*/
define('MATH_BIGINTEGER_MODE_GMP', 3);
* Karatsuba Cutoff
*
* At what point do we switch between Karatsuba multiplication and schoolbook long multiplication?
*
* @access private
*/
define('MATH_BIGINTEGER_KARATSUBA_CUTOFF', 25);
* Pure-PHP arbitrary precision integer arithmetic library. Supports base-2, base-10, base-16, and base-256
* numbers.
*
* @package Math_BigInteger
* @author  Jim Wigginton <terrafrost@php.net>
* @access  public
*/
class Math_BigInteger
{
* Holds the BigInteger's value.
*
* @var array
* @access private
*/
var $value;
* Holds the BigInteger's magnitude.
*
* @var bool
* @access private
*/
var $is_negative = false;
* Precision
*
* @see self::setPrecision()
* @access private
*/
var $precision = -1;
* Precision Bitmask
*
* @see self::setPrecision()
* @access private
*/
var $bitmask = false;
* Mode independent value used for serialization.
*
* If the bcmath or gmp extensions are installed $this->value will be a non-serializable resource, hence the need for
* a variable that'll be serializable regardless of whether or not extensions are being used.  Unlike $this->value,
* however, $this->hex is only calculated when $this->__sleep() is called.
*
* @see self::__sleep()
* @see self::__wakeup()
* @var string
* @access private
*/
var $hex;
* Converts base-2, base-10, base-16, and binary strings (base-256) to BigIntegers.
*
* If the second parameter - $base - is negative, then it will be assumed that the number's are encoded using
* two's compliment.  The sole exception to this is -10, which is treated the same as 10 is.
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger('0x32', 16); // 50 in base-16
*
*    echo $a->toString(); // outputs 50
* ?>
* </code>
*
* @param $x base-10 number or base-$base number if $base set.
* @param int $base
* @return Math_BigInteger
* @access public
*/
function __construct() {
}
}
if (extension_loaded('openssl') && !defined('MATH_BIGINTEGER_OPENSSL_DISABLE') && !defined('MATH_BIGINTEGER_OPENSSL_ENABLED')) {
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
define('MATH_BIGINTEGER_OPENSSL_ENABLED', true);
break;
default:
define('MATH_BIGINTEGER_OPENSSL_DISABLE', true);
}
}
if (!defined('PHP_INT_SIZE')) {
define('PHP_INT_SIZE', 4);
}
if (!defined('MATH_BIGINTEGER_BASE') && MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_INTERNAL) {
switch (PHP_INT_SIZE) {
case 8: // use 64-bit integers if int size is 8 bytes
define('MATH_BIGINTEGER_BASE',       31);
define('MATH_BIGINTEGER_BASE_FULL',  0x80000000);
define('MATH_BIGINTEGER_MAX_DIGIT',  0x7FFFFFFF);
define('MATH_BIGINTEGER_MSB',        0x40000000);
define('MATH_BIGINTEGER_MAX10',      1000000000);
define('MATH_BIGINTEGER_MAX10_LEN',  9);
define('MATH_BIGINTEGER_MAX_DIGIT2', pow(2, 62));
break;
default:
define('MATH_BIGINTEGER_BASE',       26);
define('MATH_BIGINTEGER_BASE_FULL',  0x4000000);
define('MATH_BIGINTEGER_MAX_DIGIT',  0x3FFFFFF);
define('MATH_BIGINTEGER_MSB',        0x2000000);
define('MATH_BIGINTEGER_MAX10',      10000000);
define('MATH_BIGINTEGER_MAX10_LEN',  7);
define('MATH_BIGINTEGER_MAX_DIGIT2', pow(2, 52));
}
}
switch (MATH_BIGINTEGER_MODE) {
case MATH_BIGINTEGER_MODE_GMP:
switch (true) {
case is_resource($x) && get_resource_type($x) == 'GMP integer':
case is_object($x) && get_class($x) == 'GMP':
$this->value = $x;
return;
}
$this->value = gmp_init(0);
break;
case MATH_BIGINTEGER_MODE_BCMATH:
$this->value = '0';
break;
default:
$this->value = array();
}
if (empty($x) && (abs($base) != 256 || $x !== '0')) {
return;
}
switch ($base) {
case -256:
if (ord($x[0]) & 0x80) {
$x = ~$x;
$this->is_negative = true;
}
case 256:
switch (MATH_BIGINTEGER_MODE) {
case MATH_BIGINTEGER_MODE_GMP:
$this->value = function_exists('gmp_import') ?
gmp_import($x) :
gmp_init('0x' . bin2hex($x));
if ($this->is_negative) {
$this->value = gmp_neg($this->value);
}
break;
case MATH_BIGINTEGER_MODE_BCMATH:
$len = (strlen($x) + 3) & 0xFFFFFFFC;
$x = str_pad($x, $len, chr(0), STR_PAD_LEFT);
for ($i = 0; $i < $len; $i+= 4) {
$this->value = bcmul($this->value, '4294967296', 0); // 4294967296 == 2**32
$this->value = bcadd($this->value, 0x1000000 * ord($x[$i]) + ((ord($x[$i + 1]) << 16) | (ord($x[$i + 2]) << 8) | ord($x[$i + 3])), 0);
}
if ($this->is_negative) {
$this->value = '-' . $this->value;
}
break;
default:
while (strlen($x)) {
$this->value[] = $this->_bytes2int($this->_base256_rshift($x, MATH_BIGINTEGER_BASE));
}
}
if ($this->is_negative) {
if (MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_INTERNAL) {
$this->is_negative = false;
}
$temp = $this->add(new Math_BigInteger('-1'));
$this->value = $temp->value;
}
break;
case 16:
case -16:
if ($base > 0 && $x[0] == '-') {
$this->is_negative = true;
$x = substr($x, 1);
}
$x = preg_replace('#^(?:0x)?([A-Fa-f0-9]*).*#', '$1', $x);
$is_negative = false;
if ($base < 0 && hexdec($x[0]) >= 8) {
$this->is_negative = $is_negative = true;
$x = bin2hex(~pack('H*', $x));
}
switch (MATH_BIGINTEGER_MODE) {
case MATH_BIGINTEGER_MODE_GMP:
$temp = $this->is_negative ? '-0x' . $x : '0x' . $x;
$this->value = gmp_init($temp);
$this->is_negative = false;
break;
case MATH_BIGINTEGER_MODE_BCMATH:
$x = (strlen($x) & 1) ? '0' . $x : $x;
$temp = new Math_BigInteger(pack('H*', $x), 256);
$this->value = $this->is_negative ? '-' . $temp->value : $temp->value;
$this->is_negative = false;
break;
default:
$x = (strlen($x) & 1) ? '0' . $x : $x;
$temp = new Math_BigInteger(pack('H*', $x), 256);
$this->value = $temp->value;
}
if ($is_negative) {
$temp = $this->add(new Math_BigInteger('-1'));
$this->value = $temp->value;
}
break;
case 10:
case -10:
$x = preg_replace('#(?<!^)(?:-).*|(?<=^|-)0*|[^-0-9].*#', '', $x);
if (!strlen($x) || $x == '-') {
$x = '0';
}
switch (MATH_BIGINTEGER_MODE) {
case MATH_BIGINTEGER_MODE_GMP:
$this->value = gmp_init($x);
break;
case MATH_BIGINTEGER_MODE_BCMATH:
$this->value = $x === '-' ? '0' : (string) $x;
break;
default:
$temp = new Math_BigInteger();
$multiplier = new Math_BigInteger();
$multiplier->value = array(MATH_BIGINTEGER_MAX10);
if ($x[0] == '-') {
$this->is_negative = true;
$x = substr($x, 1);
}
$x = str_pad($x, strlen($x) + ((MATH_BIGINTEGER_MAX10_LEN - 1) * strlen($x)) % MATH_BIGINTEGER_MAX10_LEN, 0, STR_PAD_LEFT);
while (strlen($x)) {
$temp = $temp->multiply($multiplier);
$temp = $temp->add(new Math_BigInteger($this->_int2bytes(substr($x, 0, MATH_BIGINTEGER_MAX10_LEN)), 256));
$x = substr($x, MATH_BIGINTEGER_MAX10_LEN);
}
$this->value = $temp->value;
}
break;
case 2: // base-2 support originally implemented by Lluis Pamies - thanks!
case -2:
if ($base > 0 && $x[0] == '-') {
$this->is_negative = true;
$x = substr($x, 1);
}
$x = preg_replace('#^([01]*).*#', '$1', $x);
$x = str_pad($x, strlen($x) + (3 * strlen($x)) % 4, 0, STR_PAD_LEFT);
$str = '0x';
while (strlen($x)) {
$part = substr($x, 0, 4);
$str.= dechex(bindec($part));
$x = substr($x, 4);
}
if ($this->is_negative) {
$str = '-' . $str;
}
$temp = new Math_BigInteger($str, 8 * $base); // ie. either -16 or +16
$this->value = $temp->value;
$this->is_negative = $temp->is_negative;
break;
default:
}
}
* PHP4 compatible Default Constructor.
*
* @see self::__construct()
* @param $x base-10 number or base-$base number if $base set.
* @param int $base
* @access public
*/
function Math_BigInteger() {
}
* Converts a BigInteger to a byte string (eg. base-256).
*
* Negative numbers are saved as positive numbers, unless $twos_compliment is set to true, at which point, they're
* saved as two's compliment.
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger('65');
*
*    echo $a->toBytes(); // outputs chr(65)
* ?>
* </code>
*
* @param bool $twos_compliment
* @return string
* @access public
* @internal Converts a base-2**26 number to base-2**8
*/
function toBytes() {
}
$temp = $comparison < 0 ? $this->add(new Math_BigInteger(1)) : $this->copy();
$bytes = $temp->toBytes();
if (!strlen($bytes)) { // eg. if the number we're trying to convert is -1
$bytes = chr(0);
}
if ($this->precision <= 0 && (ord($bytes[0]) & 0x80)) {
$bytes = chr(0) . $bytes;
}
return $comparison < 0 ? ~$bytes : $bytes;
}
switch (MATH_BIGINTEGER_MODE) {
case MATH_BIGINTEGER_MODE_GMP:
if (gmp_cmp($this->value, gmp_init(0)) == 0) {
return $this->precision > 0 ? str_repeat(chr(0), ($this->precision + 1) >> 3) : '';
}
if (function_exists('gmp_export')) {
$temp = gmp_export($this->value);
} else {
$temp = gmp_strval(gmp_abs($this->value), 16);
$temp = (strlen($temp) & 1) ? '0' . $temp : $temp;
$temp = pack('H*', $temp);
}
return $this->precision > 0 ?
substr(str_pad($temp, $this->precision >> 3, chr(0), STR_PAD_LEFT), -($this->precision >> 3)) :
ltrim($temp, chr(0));
case MATH_BIGINTEGER_MODE_BCMATH:
if ($this->value === '0') {
return $this->precision > 0 ? str_repeat(chr(0), ($this->precision + 1) >> 3) : '';
}
$value = '';
$current = $this->value;
if ($current[0] == '-') {
$current = substr($current, 1);
}
while (bccomp($current, '0', 0) > 0) {
$temp = bcmod($current, '16777216');
$value = chr($temp >> 16) . chr($temp >> 8) . chr($temp) . $value;
$current = bcdiv($current, '16777216', 0);
}
return $this->precision > 0 ?
substr(str_pad($value, $this->precision >> 3, chr(0), STR_PAD_LEFT), -($this->precision >> 3)) :
ltrim($value, chr(0));
}
if (!count($this->value)) {
return $this->precision > 0 ? str_repeat(chr(0), ($this->precision + 1) >> 3) : '';
}
$result = $this->_int2bytes($this->value[count($this->value) - 1]);
$temp = $this->copy();
for ($i = count($temp->value) - 2; $i >= 0; --$i) {
$temp->_base256_lshift($result, MATH_BIGINTEGER_BASE);
$result = $result | str_pad($temp->_int2bytes($temp->value[$i]), strlen($result), chr(0), STR_PAD_LEFT);
}
return $this->precision > 0 ?
str_pad(substr($result, -(($this->precision + 7) >> 3)), ($this->precision + 7) >> 3, chr(0), STR_PAD_LEFT) :
$result;
}
* Converts a BigInteger to a hex string (eg. base-16)).
*
* Negative numbers are saved as positive numbers, unless $twos_compliment is set to true, at which point, they're
* saved as two's compliment.
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger('65');
*
*    echo $a->toHex(); // outputs '41'
* ?>
* </code>
*
* @param bool $twos_compliment
* @return string
* @access public
* @internal Converts a base-2**26 number to base-2**8
*/
function toHex() {
}
* Converts a BigInteger to a bit string (eg. base-2).
*
* Negative numbers are saved as positive numbers, unless $twos_compliment is set to true, at which point, they're
* saved as two's compliment.
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger('65');
*
*    echo $a->toBits(); // outputs '1000001'
* ?>
* </code>
*
* @param bool $twos_compliment
* @return string
* @access public
* @internal Converts a base-2**26 number to base-2**2
*/
function toBits() {
}
if ($start) { // hexdec('') == 0
$bits = str_pad(decbin(hexdec(substr($hex, 0, $start))), 8, '0', STR_PAD_LEFT) . $bits;
}
$result = $this->precision > 0 ? substr($bits, -$this->precision) : ltrim($bits, '0');
if ($twos_compliment && $this->compare(new Math_BigInteger()) > 0 && $this->precision <= 0) {
return '0' . $result;
}
return $result;
}
* Converts a BigInteger to a base-10 number.
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger('50');
*
*    echo $a->toString(); // outputs 50
* ?>
* </code>
*
* @return string
* @access public
* @internal Converts a base-2**26 number to base-10**7 (which is pretty much base-10)
*/
function toString() {
}
return ltrim($this->value, '0');
}
if (!count($this->value)) {
return '0';
}
$temp = $this->copy();
$temp->bitmask = false;
$temp->is_negative = false;
$divisor = new Math_BigInteger();
$divisor->value = array(MATH_BIGINTEGER_MAX10);
$result = '';
while (count($temp->value)) {
list($temp, $mod) = $temp->divide($divisor);
$result = str_pad(isset($mod->value[0]) ? $mod->value[0] : '', MATH_BIGINTEGER_MAX10_LEN, '0', STR_PAD_LEFT) . $result;
}
$result = ltrim($result, '0');
if (empty($result)) {
$result = '0';
}
if ($this->is_negative) {
$result = '-' . $result;
}
return $result;
}
* Copy an object
*
* PHP5 passes objects by reference while PHP4 passes by value.  As such, we need a function to guarantee
* that all objects are passed by value, when appropriate.  More information can be found here:
*
* {@link http://php.net/language.oop5.basic#51624}
*
* @access public
* @see self::__clone()
* @return Math_BigInteger
*/
function copy() {
}
*  __toString() magic method
*
* Will be called, automatically, if you're supporting just PHP5.  If you're supporting PHP4, you'll need to call
* toString().
*
* @access public
* @internal Implemented per a suggestion by Techie-Michael - thanks!
*/
function __toString() {
}
* __clone() magic method
*
* Although you can call Math_BigInteger::__toString() directly in PHP5, you cannot call Math_BigInteger::__clone()
* directly in PHP5.  You can in PHP4 since it's not a magic method, but in PHP5, you have to call it by using the PHP5
* only syntax of $y = clone $x.  As such, if you're trying to write an application that works on both PHP4 and PHP5,
* call Math_BigInteger::copy(), instead.
*
* @access public
* @see self::copy()
* @return Math_BigInteger
*/
function __clone() {
}
*  __sleep() magic method
*
* Will be called, automatically, when serialize() is called on a Math_BigInteger object.
*
* @see self::__wakeup()
* @access public
*/
function __sleep() {
}
return $vars;
}
*  __wakeup() magic method
*
* Will be called, automatically, when unserialize() is called on a Math_BigInteger object.
*
* @see self::__sleep()
* @access public
*/
function __wakeup() {
}
}
*  __debugInfo() magic method
*
* Will be called, automatically, when print_r() or var_dump() are called
*
* @access public
*/
function __debugInfo() {
}
if (MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_GMP && defined('MATH_BIGINTEGER_OPENSSL_ENABLED')) {
$opts[] = 'OpenSSL';
}
if (!empty($opts)) {
$engine.= ' (' . implode('.', $opts) . ')';
}
return array(
'value' => '0x' . $this->toHex(true),
'engine' => $engine
);
}
* Adds two BigIntegers.
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger('10');
*    $b = new Math_BigInteger('20');
*
*    $c = $a->add($b);
*
*    echo $c->toString(); // outputs 30
* ?>
* </code>
*
* @param Math_BigInteger $y
* @return Math_BigInteger
* @access public
* @internal Performs base-2**52 addition
*/
function add() {
}
$temp = $this->_add($this->value, $this->is_negative, $y->value, $y->is_negative);
$result = new Math_BigInteger();
$result->value = $temp[MATH_BIGINTEGER_VALUE];
$result->is_negative = $temp[MATH_BIGINTEGER_SIGN];
return $this->_normalize($result);
}
* Performs addition.
*
* @param array $x_value
* @param bool $x_negative
* @param array $y_value
* @param bool $y_negative
* @return array
* @access private
*/
function _add() {
} elseif ($y_size == 0) {
return array(
MATH_BIGINTEGER_VALUE => $x_value,
MATH_BIGINTEGER_SIGN => $x_negative
);
}
if ($x_negative != $y_negative) {
if ($x_value == $y_value) {
return array(
MATH_BIGINTEGER_VALUE => array(),
MATH_BIGINTEGER_SIGN => false
);
}
$temp = $this->_subtract($x_value, false, $y_value, false);
$temp[MATH_BIGINTEGER_SIGN] = $this->_compare($x_value, false, $y_value, false) > 0 ?
$x_negative : $y_negative;
return $temp;
}
if ($x_size < $y_size) {
$size = $x_size;
$value = $y_value;
} else {
$size = $y_size;
$value = $x_value;
}
$value[count($value)] = 0; // just in case the carry adds an extra digit
$carry = 0;
for ($i = 0, $j = 1; $j < $size; $i+=2, $j+=2) {
$sum = $x_value[$j] * MATH_BIGINTEGER_BASE_FULL + $x_value[$i] + $y_value[$j] * MATH_BIGINTEGER_BASE_FULL + $y_value[$i] + $carry;
$carry = $sum >= MATH_BIGINTEGER_MAX_DIGIT2; // eg. floor($sum / 2**52); only possible values (in any base) are 0 and 1
$sum = $carry ? $sum - MATH_BIGINTEGER_MAX_DIGIT2 : $sum;
$temp = MATH_BIGINTEGER_BASE === 26 ? intval($sum / 0x4000000) : ($sum >> 31);
$value[$i] = (int) ($sum - MATH_BIGINTEGER_BASE_FULL * $temp); // eg. a faster alternative to fmod($sum, 0x4000000)
$value[$j] = $temp;
}
if ($j == $size) { // ie. if $y_size is odd
$sum = $x_value[$i] + $y_value[$i] + $carry;
$carry = $sum >= MATH_BIGINTEGER_BASE_FULL;
$value[$i] = $carry ? $sum - MATH_BIGINTEGER_BASE_FULL : $sum;
++$i; // ie. let $i = $j since we've just done $value[$i]
}
if ($carry) {
for (; $value[$i] == MATH_BIGINTEGER_MAX_DIGIT; ++$i) {
$value[$i] = 0;
}
++$value[$i];
}
return array(
MATH_BIGINTEGER_VALUE => $this->_trim($value),
MATH_BIGINTEGER_SIGN => $x_negative
);
}
* Subtracts two BigIntegers.
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger('10');
*    $b = new Math_BigInteger('20');
*
*    $c = $a->subtract($b);
*
*    echo $c->toString(); // outputs -10
* ?>
* </code>
*
* @param Math_BigInteger $y
* @return Math_BigInteger
* @access public
* @internal Performs base-2**52 subtraction
*/
function subtract() {
}
$temp = $this->_subtract($this->value, $this->is_negative, $y->value, $y->is_negative);
$result = new Math_BigInteger();
$result->value = $temp[MATH_BIGINTEGER_VALUE];
$result->is_negative = $temp[MATH_BIGINTEGER_SIGN];
return $this->_normalize($result);
}
* Performs subtraction.
*
* @param array $x_value
* @param bool $x_negative
* @param array $y_value
* @param bool $y_negative
* @return array
* @access private
*/
function _subtract() {
} elseif ($y_size == 0) {
return array(
MATH_BIGINTEGER_VALUE => $x_value,
MATH_BIGINTEGER_SIGN => $x_negative
);
}
if ($x_negative != $y_negative) {
$temp = $this->_add($x_value, false, $y_value, false);
$temp[MATH_BIGINTEGER_SIGN] = $x_negative;
return $temp;
}
$diff = $this->_compare($x_value, $x_negative, $y_value, $y_negative);
if (!$diff) {
return array(
MATH_BIGINTEGER_VALUE => array(),
MATH_BIGINTEGER_SIGN => false
);
}
if ((!$x_negative && $diff < 0) || ($x_negative && $diff > 0)) {
$temp = $x_value;
$x_value = $y_value;
$y_value = $temp;
$x_negative = !$x_negative;
$x_size = count($x_value);
$y_size = count($y_value);
}
$carry = 0;
for ($i = 0, $j = 1; $j < $y_size; $i+=2, $j+=2) {
$sum = $x_value[$j] * MATH_BIGINTEGER_BASE_FULL + $x_value[$i] - $y_value[$j] * MATH_BIGINTEGER_BASE_FULL - $y_value[$i] - $carry;
$carry = $sum < 0; // eg. floor($sum / 2**52); only possible values (in any base) are 0 and 1
$sum = $carry ? $sum + MATH_BIGINTEGER_MAX_DIGIT2 : $sum;
$temp = MATH_BIGINTEGER_BASE === 26 ? intval($sum / 0x4000000) : ($sum >> 31);
$x_value[$i] = (int) ($sum - MATH_BIGINTEGER_BASE_FULL * $temp);
$x_value[$j] = $temp;
}
if ($j == $y_size) { // ie. if $y_size is odd
$sum = $x_value[$i] - $y_value[$i] - $carry;
$carry = $sum < 0;
$x_value[$i] = $carry ? $sum + MATH_BIGINTEGER_BASE_FULL : $sum;
++$i;
}
if ($carry) {
for (; !$x_value[$i]; ++$i) {
$x_value[$i] = MATH_BIGINTEGER_MAX_DIGIT;
}
--$x_value[$i];
}
return array(
MATH_BIGINTEGER_VALUE => $this->_trim($x_value),
MATH_BIGINTEGER_SIGN => $x_negative
);
}
* Multiplies two BigIntegers
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger('10');
*    $b = new Math_BigInteger('20');
*
*    $c = $a->multiply($b);
*
*    echo $c->toString(); // outputs 200
* ?>
* </code>
*
* @param Math_BigInteger $x
* @return Math_BigInteger
* @access public
*/
function multiply() {
}
$temp = $this->_multiply($this->value, $this->is_negative, $x->value, $x->is_negative);
$product = new Math_BigInteger();
$product->value = $temp[MATH_BIGINTEGER_VALUE];
$product->is_negative = $temp[MATH_BIGINTEGER_SIGN];
return $this->_normalize($product);
}
* Performs multiplication.
*
* @param array $x_value
* @param bool $x_negative
* @param array $y_value
* @param bool $y_negative
* @return array
* @access private
*/
function _multiply() {
}
$x_length = count($x_value);
$y_length = count($y_value);
if (!$x_length || !$y_length) { // a 0 is being multiplied
return array(
MATH_BIGINTEGER_VALUE => array(),
MATH_BIGINTEGER_SIGN => false
);
}
return array(
MATH_BIGINTEGER_VALUE => min($x_length, $y_length) < 2 * MATH_BIGINTEGER_KARATSUBA_CUTOFF ?
$this->_trim($this->_regularMultiply($x_value, $y_value)) :
$this->_trim($this->_karatsuba($x_value, $y_value)),
MATH_BIGINTEGER_SIGN => $x_negative != $y_negative
);
}
* Performs long multiplication on two BigIntegers
*
* Modeled after 'multiply' in MutableBigInteger.java.
*
* @param array $x_value
* @param array $y_value
* @return array
* @access private
*/
function _regularMultiply() {
}
if ($x_length < $y_length) {
$temp = $x_value;
$x_value = $y_value;
$y_value = $temp;
$x_length = count($x_value);
$y_length = count($y_value);
}
$product_value = $this->_array_repeat(0, $x_length + $y_length);
$carry = 0;
for ($j = 0; $j < $x_length; ++$j) { // ie. $i = 0
$temp = $x_value[$j] * $y_value[0] + $carry; // $product_value[$k] == 0
$carry = MATH_BIGINTEGER_BASE === 26 ? intval($temp / 0x4000000) : ($temp >> 31);
$product_value[$j] = (int) ($temp - MATH_BIGINTEGER_BASE_FULL * $carry);
}
$product_value[$j] = $carry;
for ($i = 1; $i < $y_length; ++$i) {
$carry = 0;
for ($j = 0, $k = $i; $j < $x_length; ++$j, ++$k) {
$temp = $product_value[$k] + $x_value[$j] * $y_value[$i] + $carry;
$carry = MATH_BIGINTEGER_BASE === 26 ? intval($temp / 0x4000000) : ($temp >> 31);
$product_value[$k] = (int) ($temp - MATH_BIGINTEGER_BASE_FULL * $carry);
}
$product_value[$k] = $carry;
}
return $product_value;
}
* Performs Karatsuba multiplication on two BigIntegers
*
* See {@link http://en.wikipedia.org/wiki/Karatsuba_algorithm Karatsuba algorithm} and
* {@link http://math.libtomcrypt.com/files/tommath.pdf#page=120 MPM 5.2.3}.
*
* @param array $x_value
* @param array $y_value
* @return array
* @access private
*/
function _karatsuba() {
}
$x1 = array_slice($x_value, $m);
$x0 = array_slice($x_value, 0, $m);
$y1 = array_slice($y_value, $m);
$y0 = array_slice($y_value, 0, $m);
$z2 = $this->_karatsuba($x1, $y1);
$z0 = $this->_karatsuba($x0, $y0);
$z1 = $this->_add($x1, false, $x0, false);
$temp = $this->_add($y1, false, $y0, false);
$z1 = $this->_karatsuba($z1[MATH_BIGINTEGER_VALUE], $temp[MATH_BIGINTEGER_VALUE]);
$temp = $this->_add($z2, false, $z0, false);
$z1 = $this->_subtract($z1, false, $temp[MATH_BIGINTEGER_VALUE], false);
$z2 = array_merge(array_fill(0, 2 * $m, 0), $z2);
$z1[MATH_BIGINTEGER_VALUE] = array_merge(array_fill(0, $m, 0), $z1[MATH_BIGINTEGER_VALUE]);
$xy = $this->_add($z2, false, $z1[MATH_BIGINTEGER_VALUE], $z1[MATH_BIGINTEGER_SIGN]);
$xy = $this->_add($xy[MATH_BIGINTEGER_VALUE], $xy[MATH_BIGINTEGER_SIGN], $z0, false);
return $xy[MATH_BIGINTEGER_VALUE];
}
* Performs squaring
*
* @param array $x
* @return array
* @access private
*/
function _square() {
}
* Performs traditional squaring on two BigIntegers
*
* Squaring can be done faster than multiplying a number by itself can be.  See
* {@link http://www.cacr.math.uwaterloo.ca/hac/about/chap14.pdf#page=7 HAC 14.2.4} /
* {@link http://math.libtomcrypt.com/files/tommath.pdf#page=141 MPM 5.3} for more information.
*
* @param array $value
* @return array
* @access private
*/
function _baseSquare() {
}
$square_value = $this->_array_repeat(0, 2 * count($value));
for ($i = 0, $max_index = count($value) - 1; $i <= $max_index; ++$i) {
$i2 = $i << 1;
$temp = $square_value[$i2] + $value[$i] * $value[$i];
$carry = MATH_BIGINTEGER_BASE === 26 ? intval($temp / 0x4000000) : ($temp >> 31);
$square_value[$i2] = (int) ($temp - MATH_BIGINTEGER_BASE_FULL * $carry);
for ($j = $i + 1, $k = $i2 + 1; $j <= $max_index; ++$j, ++$k) {
$temp = $square_value[$k] + 2 * $value[$j] * $value[$i] + $carry;
$carry = MATH_BIGINTEGER_BASE === 26 ? intval($temp / 0x4000000) : ($temp >> 31);
$square_value[$k] = (int) ($temp - MATH_BIGINTEGER_BASE_FULL * $carry);
}
$square_value[$i + $max_index + 1] = $carry;
}
return $square_value;
}
* Performs Karatsuba "squaring" on two BigIntegers
*
* See {@link http://en.wikipedia.org/wiki/Karatsuba_algorithm Karatsuba algorithm} and
* {@link http://math.libtomcrypt.com/files/tommath.pdf#page=151 MPM 5.3.4}.
*
* @param array $value
* @return array
* @access private
*/
function _karatsubaSquare() {
}
$x1 = array_slice($value, $m);
$x0 = array_slice($value, 0, $m);
$z2 = $this->_karatsubaSquare($x1);
$z0 = $this->_karatsubaSquare($x0);
$z1 = $this->_add($x1, false, $x0, false);
$z1 = $this->_karatsubaSquare($z1[MATH_BIGINTEGER_VALUE]);
$temp = $this->_add($z2, false, $z0, false);
$z1 = $this->_subtract($z1, false, $temp[MATH_BIGINTEGER_VALUE], false);
$z2 = array_merge(array_fill(0, 2 * $m, 0), $z2);
$z1[MATH_BIGINTEGER_VALUE] = array_merge(array_fill(0, $m, 0), $z1[MATH_BIGINTEGER_VALUE]);
$xx = $this->_add($z2, false, $z1[MATH_BIGINTEGER_VALUE], $z1[MATH_BIGINTEGER_SIGN]);
$xx = $this->_add($xx[MATH_BIGINTEGER_VALUE], $xx[MATH_BIGINTEGER_SIGN], $z0, false);
return $xx[MATH_BIGINTEGER_VALUE];
}
* Divides two BigIntegers.
*
* Returns an array whose first element contains the quotient and whose second element contains the
* "common residue".  If the remainder would be positive, the "common residue" and the remainder are the
* same.  If the remainder would be negative, the "common residue" is equal to the sum of the remainder
* and the divisor (basically, the "common residue" is the first positive modulo).
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger('10');
*    $b = new Math_BigInteger('20');
*
*    list($quotient, $remainder) = $a->divide($b);
*
*    echo $quotient->toString(); // outputs 0
*    echo "\r\n";
*    echo $remainder->toString(); // outputs 10
* ?>
* </code>
*
* @param Math_BigInteger $y
* @return array
* @access public
* @internal This function is based off of {@link http://www.cacr.math.uwaterloo.ca/hac/about/chap14.pdf#page=9 HAC 14.20}.
*/
function divide() {
}
return array($this->_normalize($quotient), $this->_normalize($remainder));
case MATH_BIGINTEGER_MODE_BCMATH:
$quotient = new Math_BigInteger();
$remainder = new Math_BigInteger();
$quotient->value = bcdiv($this->value, $y->value, 0);
$remainder->value = bcmod($this->value, $y->value);
if ($remainder->value[0] == '-') {
$remainder->value = bcadd($remainder->value, $y->value[0] == '-' ? substr($y->value, 1) : $y->value, 0);
}
return array($this->_normalize($quotient), $this->_normalize($remainder));
}
if (count($y->value) == 1) {
list($q, $r) = $this->_divide_digit($this->value, $y->value[0]);
$quotient = new Math_BigInteger();
$remainder = new Math_BigInteger();
$quotient->value = $q;
$remainder->value = array($r);
$quotient->is_negative = $this->is_negative != $y->is_negative;
return array($this->_normalize($quotient), $this->_normalize($remainder));
}
static $zero;
if (!isset($zero)) {
$zero = new Math_BigInteger();
}
$x = $this->copy();
$y = $y->copy();
$x_sign = $x->is_negative;
$y_sign = $y->is_negative;
$x->is_negative = $y->is_negative = false;
$diff = $x->compare($y);
if (!$diff) {
$temp = new Math_BigInteger();
$temp->value = array(1);
$temp->is_negative = $x_sign != $y_sign;
return array($this->_normalize($temp), $this->_normalize(new Math_BigInteger()));
}
if ($diff < 0) {
if ($x_sign) {
$x = $y->subtract($x);
}
return array($this->_normalize(new Math_BigInteger()), $this->_normalize($x));
}
$msb = $y->value[count($y->value) - 1];
for ($shift = 0; !($msb & MATH_BIGINTEGER_MSB); ++$shift) {
$msb <<= 1;
}
$x->_lshift($shift);
$y->_lshift($shift);
$y_value = &$y->value;
$x_max = count($x->value) - 1;
$y_max = count($y->value) - 1;
$quotient = new Math_BigInteger();
$quotient_value = &$quotient->value;
$quotient_value = $this->_array_repeat(0, $x_max - $y_max + 1);
static $temp, $lhs, $rhs;
if (!isset($temp)) {
$temp = new Math_BigInteger();
$lhs =  new Math_BigInteger();
$rhs =  new Math_BigInteger();
}
$temp_value = &$temp->value;
$rhs_value =  &$rhs->value;
$temp_value = array_merge($this->_array_repeat(0, $x_max - $y_max), $y_value);
while ($x->compare($temp) >= 0) {
++$quotient_value[$x_max - $y_max];
$x = $x->subtract($temp);
$x_max = count($x->value) - 1;
}
for ($i = $x_max; $i >= $y_max + 1; --$i) {
$x_value = &$x->value;
$x_window = array(
isset($x_value[$i]) ? $x_value[$i] : 0,
isset($x_value[$i - 1]) ? $x_value[$i - 1] : 0,
isset($x_value[$i - 2]) ? $x_value[$i - 2] : 0
);
$y_window = array(
$y_value[$y_max],
($y_max > 0) ? $y_value[$y_max - 1] : 0
);
$q_index = $i - $y_max - 1;
if ($x_window[0] == $y_window[0]) {
$quotient_value[$q_index] = MATH_BIGINTEGER_MAX_DIGIT;
} else {
$quotient_value[$q_index] = $this->_safe_divide(
$x_window[0] * MATH_BIGINTEGER_BASE_FULL + $x_window[1],
$y_window[0]
);
}
$temp_value = array($y_window[1], $y_window[0]);
$lhs->value = array($quotient_value[$q_index]);
$lhs = $lhs->multiply($temp);
$rhs_value = array($x_window[2], $x_window[1], $x_window[0]);
while ($lhs->compare($rhs) > 0) {
--$quotient_value[$q_index];
$lhs->value = array($quotient_value[$q_index]);
$lhs = $lhs->multiply($temp);
}
$adjust = $this->_array_repeat(0, $q_index);
$temp_value = array($quotient_value[$q_index]);
$temp = $temp->multiply($y);
$temp_value = &$temp->value;
if (count($temp_value)) {
$temp_value = array_merge($adjust, $temp_value);
}
$x = $x->subtract($temp);
if ($x->compare($zero) < 0) {
$temp_value = array_merge($adjust, $y_value);
$x = $x->add($temp);
--$quotient_value[$q_index];
}
$x_max = count($x_value) - 1;
}
$x->_rshift($shift);
$quotient->is_negative = $x_sign != $y_sign;
if ($x_sign) {
$y->_rshift($shift);
$x = $y->subtract($x);
}
return array($this->_normalize($quotient), $this->_normalize($x));
}
* Divides a BigInteger by a regular integer
*
* abc / x = a00 / x + b0 / x + c / x
*
* @param array $dividend
* @param array $divisor
* @return array
* @access private
*/
function _divide_digit() {
}
return array($result, $carry);
}
* Performs modular exponentiation.
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger('10');
*    $b = new Math_BigInteger('20');
*    $c = new Math_BigInteger('30');
*
*    $c = $a->modPow($b, $c);
*
*    echo $c->toString(); // outputs 10
* ?>
* </code>
*
* @param Math_BigInteger $e
* @param Math_BigInteger $n
* @return Math_BigInteger
* @access public
* @internal The most naive approach to modular exponentiation has very unreasonable requirements, and
*    and although the approach involving repeated squaring does vastly better, it, too, is impractical
*    for our purposes.  The reason being that division - by far the most complicated and time-consuming
*    of the basic operations (eg. +,-,*,/) - occurs multiple times within it.
*
*    Modular reductions resolve this issue.  Although an individual modular reduction takes more time
*    then an individual division, when performed in succession (with the same modulo), they're a lot faster.
*
*    The two most commonly used modular reductions are Barrett and Montgomery reduction.  Montgomery reduction,
*    although faster, only works when the gcd of the modulo and of the base being used is 1.  In RSA, when the
*    base is a power of two, the modulo - a product of two primes - is always going to have a gcd of 1 (because
*    the product of two odd numbers is odd), but what about when RSA isn't used?
*
*    In contrast, Barrett reduction has no such constraint.  As such, some bigint implementations perform a
*    Barrett reduction after every operation in the modpow function.  Others perform Barrett reductions when the
*    modulo is even and Montgomery reductions when the modulo is odd.  BigInteger.java's modPow method, however,
*    uses a trick involving the Chinese Remainder Theorem to factor the even modulo into two numbers - one odd and
*    the other, a power of two - and recombine them, later.  This is the method that this modPow function uses.
*    {@link http://islab.oregonstate.edu/papers/j34monex.pdf Montgomery Reduction with Even Modulus} elaborates.
*/
function modPow() {
}
return $this->_normalize($temp->modPow($e, $n));
}
if (MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_GMP) {
$temp = new Math_BigInteger();
$temp->value = gmp_powm($this->value, $e->value, $n->value);
return $this->_normalize($temp);
}
if ($this->compare(new Math_BigInteger()) < 0 || $this->compare($n) > 0) {
list(, $temp) = $this->divide($n);
return $temp->modPow($e, $n);
}
if (defined('MATH_BIGINTEGER_OPENSSL_ENABLED')) {
$components = array(
'modulus' => $n->toBytes(true),
'publicExponent' => $e->toBytes(true)
);
$components = array(
'modulus' => pack('Ca*a*', 2, $this->_encodeASN1Length(strlen($components['modulus'])), $components['modulus']),
'publicExponent' => pack('Ca*a*', 2, $this->_encodeASN1Length(strlen($components['publicExponent'])), $components['publicExponent'])
);
$RSAPublicKey = pack(
'Ca*a*a*',
48,
$this->_encodeASN1Length(strlen($components['modulus']) + strlen($components['publicExponent'])),
$components['modulus'],
$components['publicExponent']
);
$rsaOID = pack('H*', '300d06092a864886f70d0101010500'); // hex version of MA0GCSqGSIb3DQEBAQUA
$RSAPublicKey = chr(0) . $RSAPublicKey;
$RSAPublicKey = chr(3) . $this->_encodeASN1Length(strlen($RSAPublicKey)) . $RSAPublicKey;
$encapsulated = pack(
'Ca*a*',
48,
$this->_encodeASN1Length(strlen($rsaOID . $RSAPublicKey)),
$rsaOID . $RSAPublicKey
);
$RSAPublicKey = "-----BEGIN PUBLIC KEY-----\r\n" .
chunk_split(base64_encode($encapsulated)) .
'-----END PUBLIC KEY-----';
$plaintext = str_pad($this->toBytes(), strlen($n->toBytes(true)) - 1, "\0", STR_PAD_LEFT);
if (openssl_public_encrypt($plaintext, $result, $RSAPublicKey, OPENSSL_NO_PADDING)) {
return new Math_BigInteger($result, 256);
}
}
if (MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_BCMATH) {
$temp = new Math_BigInteger();
$temp->value = bcpowmod($this->value, $e->value, $n->value, 0);
return $this->_normalize($temp);
}
if (empty($e->value)) {
$temp = new Math_BigInteger();
$temp->value = array(1);
return $this->_normalize($temp);
}
if ($e->value == array(1)) {
list(, $temp) = $this->divide($n);
return $this->_normalize($temp);
}
if ($e->value == array(2)) {
$temp = new Math_BigInteger();
$temp->value = $this->_square($this->value);
list(, $temp) = $temp->divide($n);
return $this->_normalize($temp);
}
return $this->_normalize($this->_slidingWindow($e, $n, MATH_BIGINTEGER_BARRETT));
if ($n->value[0] & 1) {
return $this->_normalize($this->_slidingWindow($e, $n, MATH_BIGINTEGER_MONTGOMERY));
}
for ($i = 0; $i < count($n->value); ++$i) {
if ($n->value[$i]) {
$temp = decbin($n->value[$i]);
$j = strlen($temp) - strrpos($temp, '1') - 1;
$j+= 26 * $i;
break;
}
}
$mod1 = $n->copy();
$mod1->_rshift($j);
$mod2 = new Math_BigInteger();
$mod2->value = array(1);
$mod2->_lshift($j);
$part1 = ($mod1->value != array(1)) ? $this->_slidingWindow($e, $mod1, MATH_BIGINTEGER_MONTGOMERY) : new Math_BigInteger();
$part2 = $this->_slidingWindow($e, $mod2, MATH_BIGINTEGER_POWEROF2);
$y1 = $mod2->modInverse($mod1);
$y2 = $mod1->modInverse($mod2);
$result = $part1->multiply($mod2);
$result = $result->multiply($y1);
$temp = $part2->multiply($mod1);
$temp = $temp->multiply($y2);
$result = $result->add($temp);
list(, $result) = $result->divide($n);
return $this->_normalize($result);
}
* Performs modular exponentiation.
*
* Alias for Math_BigInteger::modPow()
*
* @param Math_BigInteger $e
* @param Math_BigInteger $n
* @return Math_BigInteger
* @access public
*/
function powMod() {
}
* Sliding Window k-ary Modular Exponentiation
*
* Based on {@link http://www.cacr.math.uwaterloo.ca/hac/about/chap14.pdf#page=27 HAC 14.85} /
* {@link http://math.libtomcrypt.com/files/tommath.pdf#page=210 MPM 7.7}.  In a departure from those algorithims,
* however, this function performs a modular reduction after every multiplication and squaring operation.
* As such, this function has the same preconditions that the reductions being used do.
*
* @param Math_BigInteger $e
* @param Math_BigInteger $n
* @param int $mode
* @return Math_BigInteger
* @access private
*/
function _slidingWindow() {
}
$e_length = strlen($e_bits);
for ($i = 0, $window_size = 1; $i < count($window_ranges) && $e_length > $window_ranges[$i]; ++$window_size, ++$i) {
}
$n_value = $n->value;
$powers = array();
$powers[1] = $this->_prepareReduce($this->value, $n_value, $mode);
$powers[2] = $this->_squareReduce($powers[1], $n_value, $mode);
$temp = 1 << ($window_size - 1);
for ($i = 1; $i < $temp; ++$i) {
$i2 = $i << 1;
$powers[$i2 + 1] = $this->_multiplyReduce($powers[$i2 - 1], $powers[2], $n_value, $mode);
}
$result = array(1);
$result = $this->_prepareReduce($result, $n_value, $mode);
for ($i = 0; $i < $e_length;) {
if (!$e_bits[$i]) {
$result = $this->_squareReduce($result, $n_value, $mode);
++$i;
} else {
for ($j = $window_size - 1; $j > 0; --$j) {
if (!empty($e_bits[$i + $j])) {
break;
}
}
for ($k = 0; $k <= $j; ++$k) {
$result = $this->_squareReduce($result, $n_value, $mode);
}
$result = $this->_multiplyReduce($result, $powers[bindec(substr($e_bits, $i, $j + 1))], $n_value, $mode);
$i += $j + 1;
}
}
$temp = new Math_BigInteger();
$temp->value = $this->_reduce($result, $n_value, $mode);
return $temp;
}
* Modular reduction
*
* For most $modes this will return the remainder.
*
* @see self::_slidingWindow()
* @access private
* @param array $x
* @param array $n
* @param int $mode
* @return array
*/
function _reduce() {
}
}
* Modular reduction preperation
*
* @see self::_slidingWindow()
* @access private
* @param array $x
* @param array $n
* @param int $mode
* @return array
*/
function _prepareReduce() {
}
return $this->_reduce($x, $n, $mode);
}
* Modular multiply
*
* @see self::_slidingWindow()
* @access private
* @param array $x
* @param array $y
* @param array $n
* @param int $mode
* @return array
*/
function _multiplyReduce() {
}
$temp = $this->_multiply($x, false, $y, false);
return $this->_reduce($temp[MATH_BIGINTEGER_VALUE], $n, $mode);
}
* Modular square
*
* @see self::_slidingWindow()
* @access private
* @param array $x
* @param array $n
* @param int $mode
* @return array
*/
function _squareReduce() {
}
return $this->_reduce($this->_square($x), $n, $mode);
}
* Modulos for Powers of Two
*
* Calculates $x%$n, where $n = 2**$e, for some $e.  Since this is basically the same as doing $x & ($n-1),
* we'll just use this function as a wrapper for doing that.
*
* @see self::_slidingWindow()
* @access private
* @param Math_BigInteger
* @return Math_BigInteger
*/
function _mod2() {
}
* Barrett Modular Reduction
*
* See {@link http://www.cacr.math.uwaterloo.ca/hac/about/chap14.pdf#page=14 HAC 14.3.3} /
* {@link http://math.libtomcrypt.com/files/tommath.pdf#page=165 MPM 6.2.5} for more information.  Modified slightly,
* so as not to require negative numbers (initially, this script didn't support negative numbers).
*
* Employs "folding", as described at
* {@link http://www.cosic.esat.kuleuven.be/publications/thesis-149.pdf#page=66 thesis-149.pdf#page=66}.  To quote from
* it, "the idea [behind folding] is to find a value x' such that x (mod m) = x' (mod m), with x' being smaller than x."
*
* Unfortunately, the "Barrett Reduction with Folding" algorithm described in thesis-149.pdf is not, as written, all that
* usable on account of (1) its not using reasonable radix points as discussed in
* {@link http://math.libtomcrypt.com/files/tommath.pdf#page=162 MPM 6.2.2} and (2) the fact that, even with reasonable
* radix points, it only works when there are an even number of digits in the denominator.  The reason for (2) is that
* (x >> 1) + (x >> 1) != x / 2 + x / 2.  If x is even, they're the same, but if x is odd, they're not.  See the in-line
* comments for details.
*
* @see self::_slidingWindow()
* @access private
* @param array $n
* @param array $m
* @return array
*/
function _barrett() {
}
if ($m_length < 5) {
return $this->_regularBarrett($n, $m);
}
if (($key = array_search($m, $cache[MATH_BIGINTEGER_VARIABLE])) === false) {
$key = count($cache[MATH_BIGINTEGER_VARIABLE]);
$cache[MATH_BIGINTEGER_VARIABLE][] = $m;
$lhs = new Math_BigInteger();
$lhs_value = &$lhs->value;
$lhs_value = $this->_array_repeat(0, $m_length + ($m_length >> 1));
$lhs_value[] = 1;
$rhs = new Math_BigInteger();
$rhs->value = $m;
list($u, $m1) = $lhs->divide($rhs);
$u = $u->value;
$m1 = $m1->value;
$cache[MATH_BIGINTEGER_DATA][] = array(
'u' => $u, // m.length >> 1 (technically (m.length >> 1) + 1)
'm1'=> $m1 // m.length
);
} else {
extract($cache[MATH_BIGINTEGER_DATA][$key]);
}
$cutoff = $m_length + ($m_length >> 1);
$lsd = array_slice($n, 0, $cutoff); // m.length + (m.length >> 1)
$msd = array_slice($n, $cutoff);    // m.length >> 1
$lsd = $this->_trim($lsd);
$temp = $this->_multiply($msd, false, $m1, false);
$n = $this->_add($lsd, false, $temp[MATH_BIGINTEGER_VALUE], false); // m.length + (m.length >> 1) + 1
if ($m_length & 1) {
return $this->_regularBarrett($n[MATH_BIGINTEGER_VALUE], $m);
}
$temp = array_slice($n[MATH_BIGINTEGER_VALUE], $m_length - 1);
$temp = $this->_multiply($temp, false, $u, false);
$temp = array_slice($temp[MATH_BIGINTEGER_VALUE], ($m_length >> 1) + 1);
$temp = $this->_multiply($temp, false, $m, false);
$result = $this->_subtract($n[MATH_BIGINTEGER_VALUE], false, $temp[MATH_BIGINTEGER_VALUE], false);
while ($this->_compare($result[MATH_BIGINTEGER_VALUE], $result[MATH_BIGINTEGER_SIGN], $m, false) >= 0) {
$result = $this->_subtract($result[MATH_BIGINTEGER_VALUE], $result[MATH_BIGINTEGER_SIGN], $m, false);
}
return $result[MATH_BIGINTEGER_VALUE];
}
* (Regular) Barrett Modular Reduction
*
* For numbers with more than four digits Math_BigInteger::_barrett() is faster.  The difference between that and this
* is that this function does not fold the denominator into a smaller form.
*
* @see self::_slidingWindow()
* @access private
* @param array $x
* @param array $n
* @return array
*/
function _regularBarrett() {
}
if (($key = array_search($n, $cache[MATH_BIGINTEGER_VARIABLE])) === false) {
$key = count($cache[MATH_BIGINTEGER_VARIABLE]);
$cache[MATH_BIGINTEGER_VARIABLE][] = $n;
$lhs = new Math_BigInteger();
$lhs_value = &$lhs->value;
$lhs_value = $this->_array_repeat(0, 2 * $n_length);
$lhs_value[] = 1;
$rhs = new Math_BigInteger();
$rhs->value = $n;
list($temp, ) = $lhs->divide($rhs); // m.length
$cache[MATH_BIGINTEGER_DATA][] = $temp->value;
}
$temp = array_slice($x, $n_length - 1);
$temp = $this->_multiply($temp, false, $cache[MATH_BIGINTEGER_DATA][$key], false);
$temp = array_slice($temp[MATH_BIGINTEGER_VALUE], $n_length + 1);
$result = array_slice($x, 0, $n_length + 1);
$temp = $this->_multiplyLower($temp, false, $n, false, $n_length + 1);
if ($this->_compare($result, false, $temp[MATH_BIGINTEGER_VALUE], $temp[MATH_BIGINTEGER_SIGN]) < 0) {
$corrector_value = $this->_array_repeat(0, $n_length + 1);
$corrector_value[count($corrector_value)] = 1;
$result = $this->_add($result, false, $corrector_value, false);
$result = $result[MATH_BIGINTEGER_VALUE];
}
$result = $this->_subtract($result, false, $temp[MATH_BIGINTEGER_VALUE], $temp[MATH_BIGINTEGER_SIGN]);
while ($this->_compare($result[MATH_BIGINTEGER_VALUE], $result[MATH_BIGINTEGER_SIGN], $n, false) > 0) {
$result = $this->_subtract($result[MATH_BIGINTEGER_VALUE], $result[MATH_BIGINTEGER_SIGN], $n, false);
}
return $result[MATH_BIGINTEGER_VALUE];
}
* Performs long multiplication up to $stop digits
*
* If you're going to be doing array_slice($product->value, 0, $stop), some cycles can be saved.
*
* @see self::_regularBarrett()
* @param array $x_value
* @param bool $x_negative
* @param array $y_value
* @param bool $y_negative
* @param int $stop
* @return array
* @access private
*/
function _multiplyLower() {
}
if ($x_length < $y_length) {
$temp = $x_value;
$x_value = $y_value;
$y_value = $temp;
$x_length = count($x_value);
$y_length = count($y_value);
}
$product_value = $this->_array_repeat(0, $x_length + $y_length);
$carry = 0;
for ($j = 0; $j < $x_length; ++$j) { // ie. $i = 0, $k = $i
$temp = $x_value[$j] * $y_value[0] + $carry; // $product_value[$k] == 0
$carry = MATH_BIGINTEGER_BASE === 26 ? intval($temp / 0x4000000) : ($temp >> 31);
$product_value[$j] = (int) ($temp - MATH_BIGINTEGER_BASE_FULL * $carry);
}
if ($j < $stop) {
$product_value[$j] = $carry;
}
for ($i = 1; $i < $y_length; ++$i) {
$carry = 0;
for ($j = 0, $k = $i; $j < $x_length && $k < $stop; ++$j, ++$k) {
$temp = $product_value[$k] + $x_value[$j] * $y_value[$i] + $carry;
$carry = MATH_BIGINTEGER_BASE === 26 ? intval($temp / 0x4000000) : ($temp >> 31);
$product_value[$k] = (int) ($temp - MATH_BIGINTEGER_BASE_FULL * $carry);
}
if ($k < $stop) {
$product_value[$k] = $carry;
}
}
return array(
MATH_BIGINTEGER_VALUE => $this->_trim($product_value),
MATH_BIGINTEGER_SIGN => $x_negative != $y_negative
);
}
* Montgomery Modular Reduction
*
* ($x->_prepMontgomery($n))->_montgomery($n) yields $x % $n.
* {@link http://math.libtomcrypt.com/files/tommath.pdf#page=170 MPM 6.3} provides insights on how this can be
* improved upon (basically, by using the comba method).  gcd($n, 2) must be equal to one for this function
* to work correctly.
*
* @see self::_prepMontgomery()
* @see self::_slidingWindow()
* @access private
* @param array $x
* @param array $n
* @return array
*/
function _montgomery() {
}
$k = count($n);
$result = array(MATH_BIGINTEGER_VALUE => $x);
for ($i = 0; $i < $k; ++$i) {
$temp = $result[MATH_BIGINTEGER_VALUE][$i] * $cache[MATH_BIGINTEGER_DATA][$key];
$temp = $temp - MATH_BIGINTEGER_BASE_FULL * (MATH_BIGINTEGER_BASE === 26 ? intval($temp / 0x4000000) : ($temp >> 31));
$temp = $this->_regularMultiply(array($temp), $n);
$temp = array_merge($this->_array_repeat(0, $i), $temp);
$result = $this->_add($result[MATH_BIGINTEGER_VALUE], false, $temp, false);
}
$result[MATH_BIGINTEGER_VALUE] = array_slice($result[MATH_BIGINTEGER_VALUE], $k);
if ($this->_compare($result, false, $n, false) >= 0) {
$result = $this->_subtract($result[MATH_BIGINTEGER_VALUE], false, $n, false);
}
return $result[MATH_BIGINTEGER_VALUE];
}
* Montgomery Multiply
*
* Interleaves the montgomery reduction and long multiplication algorithms together as described in
* {@link http://www.cacr.math.uwaterloo.ca/hac/about/chap14.pdf#page=13 HAC 14.36}
*
* @see self::_prepMontgomery()
* @see self::_montgomery()
* @access private
* @param array $x
* @param array $y
* @param array $m
* @return array
*/
function _montgomeryMultiply() {
}
$n = max(count($x), count($y), count($m));
$x = array_pad($x, $n, 0);
$y = array_pad($y, $n, 0);
$m = array_pad($m, $n, 0);
$a = array(MATH_BIGINTEGER_VALUE => $this->_array_repeat(0, $n + 1));
for ($i = 0; $i < $n; ++$i) {
$temp = $a[MATH_BIGINTEGER_VALUE][0] + $x[$i] * $y[0];
$temp = $temp - MATH_BIGINTEGER_BASE_FULL * (MATH_BIGINTEGER_BASE === 26 ? intval($temp / 0x4000000) : ($temp >> 31));
$temp = $temp * $cache[MATH_BIGINTEGER_DATA][$key];
$temp = $temp - MATH_BIGINTEGER_BASE_FULL * (MATH_BIGINTEGER_BASE === 26 ? intval($temp / 0x4000000) : ($temp >> 31));
$temp = $this->_add($this->_regularMultiply(array($x[$i]), $y), false, $this->_regularMultiply(array($temp), $m), false);
$a = $this->_add($a[MATH_BIGINTEGER_VALUE], false, $temp[MATH_BIGINTEGER_VALUE], false);
$a[MATH_BIGINTEGER_VALUE] = array_slice($a[MATH_BIGINTEGER_VALUE], 1);
}
if ($this->_compare($a[MATH_BIGINTEGER_VALUE], false, $m, false) >= 0) {
$a = $this->_subtract($a[MATH_BIGINTEGER_VALUE], false, $m, false);
}
return $a[MATH_BIGINTEGER_VALUE];
}
* Prepare a number for use in Montgomery Modular Reductions
*
* @see self::_montgomery()
* @see self::_slidingWindow()
* @access private
* @param array $x
* @param array $n
* @return array
*/
function _prepMontgomery() {
}
* Modular Inverse of a number mod 2**26 (eg. 67108864)
*
* Based off of the bnpInvDigit function implemented and justified in the following URL:
*
* {@link http://www-cs-students.stanford.edu/~tjw/jsbn/jsbn.js}
*
* The following URL provides more info:
*
* {@link http://groups.google.com/group/sci.crypt/msg/7a137205c1be7d85}
*
* As for why we do all the bitmasking...  strange things can happen when converting from floats to ints. For
* instance, on some computers, var_dump((int) -4294967297) yields int(-1) and on others, it yields
* int(-2147483648).  To avoid problems stemming from this, we use bitmasks to guarantee that ints aren't
* auto-converted to floats.  The outermost bitmask is present because without it, there's no guarantee that
* the "residue" returned would be the so-called "common residue".  We use fmod, in the last step, because the
* maximum possible $x is 26 bits and the maximum $result is 16 bits.  Thus, we have to be able to handle up to
* 40 bits, which only 64-bit floating points will support.
*
* Thanks to Pedro Gimeno Fortea for input!
*
* @see self::_montgomery()
* @access private
* @param array $x
* @return int
*/
function _modInverse67108864($x) // 2**26 == 67,108,864
{
$x = -$x[0];
$result = $x & 0x3; // x**-1 mod 2**2
$result = ($result * (2 - $x * $result)) & 0xF; // x**-1 mod 2**4
$result = ($result * (2 - ($x & 0xFF) * $result))  & 0xFF; // x**-1 mod 2**8
$result = ($result * ((2 - ($x & 0xFFFF) * $result) & 0xFFFF)) & 0xFFFF; // x**-1 mod 2**16
$result = fmod($result * (2 - fmod($x * $result, MATH_BIGINTEGER_BASE_FULL)), MATH_BIGINTEGER_BASE_FULL); // x**-1 mod 2**26
return $result & MATH_BIGINTEGER_MAX_DIGIT;
}
* Calculates modular inverses.
*
* Say you have (30 mod 17 * x mod 17) mod 17 == 1.  x can be found using modular inverses.
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger(30);
*    $b = new Math_BigInteger(17);
*
*    $c = $a->modInverse($b);
*    echo $c->toString(); // outputs 4
*
*    echo "\r\n";
*
*    $d = $a->multiply($c);
*    list(, $d) = $d->divide($b);
*    echo $d; // outputs 1 (as per the definition of modular inverse)
* ?>
* </code>
*
* @param Math_BigInteger $n
* @return Math_BigInteger|false
* @access public
* @internal See {@link http://www.cacr.math.uwaterloo.ca/hac/about/chap14.pdf#page=21 HAC 14.64} for more information.
*/
function modInverse() {
}
static $zero, $one;
if (!isset($zero)) {
$zero = new Math_BigInteger();
$one = new Math_BigInteger(1);
}
$n = $n->abs();
if ($this->compare($zero) < 0) {
$temp = $this->abs();
$temp = $temp->modInverse($n);
return $this->_normalize($n->subtract($temp));
}
extract($this->extendedGCD($n));
if (!$gcd->equals($one)) {
return false;
}
$x = $x->compare($zero) < 0 ? $x->add($n) : $x;
return $this->compare($zero) < 0 ? $this->_normalize($n->subtract($x)) : $this->_normalize($x);
}
* Calculates the greatest common divisor and Bezout's identity.
*
* Say you have 693 and 609.  The GCD is 21.  Bezout's identity states that there exist integers x and y such that
* 693*x + 609*y == 21.  In point of fact, there are actually an infinite number of x and y combinations and which
* combination is returned is dependent upon which mode is in use.  See
* {@link http://en.wikipedia.org/wiki/B%C3%A9zout%27s_identity Bezout's identity - Wikipedia} for more information.
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger(693);
*    $b = new Math_BigInteger(609);
*
*    extract($a->extendedGCD($b));
*
*    echo $gcd->toString() . "\r\n"; // outputs 21
*    echo $a->toString() * $x->toString() + $b->toString() * $y->toString(); // outputs 21
* ?>
* </code>
*
* @param Math_BigInteger $n
* @return Math_BigInteger
* @access public
* @internal Calculates the GCD using the binary xGCD algorithim described in
*    {@link http://www.cacr.math.uwaterloo.ca/hac/about/chap14.pdf#page=19 HAC 14.61}.  As the text above 14.61 notes,
*    the more traditional algorithim requires "relatively costly multiple-precision divisions".
*/
function extendedGCD() {
}
return array(
'gcd' => $this->_normalize(new Math_BigInteger($u)),
'x'   => $this->_normalize(new Math_BigInteger($a)),
'y'   => $this->_normalize(new Math_BigInteger($b))
);
}
$y = $n->copy();
$x = $this->copy();
$g = new Math_BigInteger();
$g->value = array(1);
while (!(($x->value[0] & 1)|| ($y->value[0] & 1))) {
$x->_rshift(1);
$y->_rshift(1);
$g->_lshift(1);
}
$u = $x->copy();
$v = $y->copy();
$a = new Math_BigInteger();
$b = new Math_BigInteger();
$c = new Math_BigInteger();
$d = new Math_BigInteger();
$a->value = $d->value = $g->value = array(1);
$b->value = $c->value = array();
while (!empty($u->value)) {
while (!($u->value[0] & 1)) {
$u->_rshift(1);
if ((!empty($a->value) && ($a->value[0] & 1)) || (!empty($b->value) && ($b->value[0] & 1))) {
$a = $a->add($y);
$b = $b->subtract($x);
}
$a->_rshift(1);
$b->_rshift(1);
}
while (!($v->value[0] & 1)) {
$v->_rshift(1);
if ((!empty($d->value) && ($d->value[0] & 1)) || (!empty($c->value) && ($c->value[0] & 1))) {
$c = $c->add($y);
$d = $d->subtract($x);
}
$c->_rshift(1);
$d->_rshift(1);
}
if ($u->compare($v) >= 0) {
$u = $u->subtract($v);
$a = $a->subtract($c);
$b = $b->subtract($d);
} else {
$v = $v->subtract($u);
$c = $c->subtract($a);
$d = $d->subtract($b);
}
}
return array(
'gcd' => $this->_normalize($g->multiply($v)),
'x'   => $this->_normalize($c),
'y'   => $this->_normalize($d)
);
}
* Calculates the greatest common divisor
*
* Say you have 693 and 609.  The GCD is 21.
*
* Here's an example:
* <code>
* <?php
*    include 'Math/BigInteger.php';
*
*    $a = new Math_BigInteger(693);
*    $b = new Math_BigInteger(609);
*
*    $gcd = a->extendedGCD($b);
*
*    echo $gcd->toString() . "\r\n"; // outputs 21
* ?>
* </code>
*
* @param Math_BigInteger $n
* @return Math_BigInteger
* @access public
*/
function gcd() {
}
* Absolute value.
*
* @return Math_BigInteger
* @access public
*/
function abs() {
}
return $temp;
}
* Compares two numbers.
*
* Although one might think !$x->compare($y) means $x != $y, it, in fact, means the opposite.  The reason for this is
* demonstrated thusly:
*
* $x  > $y: $x->compare($y)  > 0
* $x  < $y: $x->compare($y)  < 0
* $x == $y: $x->compare($y) == 0
*
* Note how the same comparison operator is used.  If you want to test for equality, use $x->equals($y).
*
* @param Math_BigInteger $y
* @return int < 0 if $this is less than $y; > 0 if $this is greater than $y, and 0 if they are equal.
* @access public
* @see self::equals()
* @internal Could return $this->subtract($x), but that's not as fast as what we do do.
*/
function compare() {
}
if ($r > 1) {
$r = 1;
}
return $r;
case MATH_BIGINTEGER_MODE_BCMATH:
return bccomp($this->value, $y->value, 0);
}
return $this->_compare($this->value, $this->is_negative, $y->value, $y->is_negative);
}
* Compares two numbers.
*
* @param array $x_value
* @param bool $x_negative
* @param array $y_value
* @param bool $y_negative
* @return int
* @see self::compare()
* @access private
*/
function _compare() {
}
$result = $x_negative ? -1 : 1;
if (count($x_value) != count($y_value)) {
return (count($x_value) > count($y_value)) ? $result : -$result;
}
$size = max(count($x_value), count($y_value));
$x_value = array_pad($x_value, $size, 0);
$y_value = array_pad($y_value, $size, 0);
for ($i = count($x_value) - 1; $i >= 0; --$i) {
if ($x_value[$i] != $y_value[$i]) {
return ($x_value[$i] > $y_value[$i]) ? $result : -$result;
}
}
return 0;
}
* Tests the equality of two numbers.
*
* If you need to see if one number is greater than or less than another number, use Math_BigInteger::compare()
*
* @param Math_BigInteger $x
* @return bool
* @access public
* @see self::compare()
*/
function equals() {
}
}
* Set Precision
*
* Some bitwise operations give different results depending on the precision being used.  Examples include left
* shift, not, and rotates.
*
* @param int $bits
* @access public
*/
function setPrecision() {
} else {
$this->bitmask = new Math_BigInteger(bcpow('2', $bits, 0));
}
$temp = $this->_normalize($this);
$this->value = $temp->value;
}
* Logical And
*
* @param Math_BigInteger $x
* @access public
* @internal Implemented per a request by Lluis Pamies i Juarez <lluis _a_ pamies.cat>
* @return Math_BigInteger
*/
function bitwise_and() {
}
$result = $this->copy();
$length = min(count($x->value), count($this->value));
$result->value = array_slice($result->value, 0, $length);
for ($i = 0; $i < $length; ++$i) {
$result->value[$i]&= $x->value[$i];
}
return $this->_normalize($result);
}
* Logical Or
*
* @param Math_BigInteger $x
* @access public
* @internal Implemented per a request by Lluis Pamies i Juarez <lluis _a_ pamies.cat>
* @return Math_BigInteger
*/
function bitwise_or() {
}
$length = max(count($this->value), count($x->value));
$result = $this->copy();
$result->value = array_pad($result->value, $length, 0);
$x->value = array_pad($x->value, $length, 0);
for ($i = 0; $i < $length; ++$i) {
$result->value[$i]|= $x->value[$i];
}
return $this->_normalize($result);
}
* Logical Exclusive-Or
*
* @param Math_BigInteger $x
* @access public
* @internal Implemented per a request by Lluis Pamies i Juarez <lluis _a_ pamies.cat>
* @return Math_BigInteger
*/
function bitwise_xor() {
}
$length = max(count($this->value), count($x->value));
$result = $this->copy();
$result->is_negative = false;
$result->value = array_pad($result->value, $length, 0);
$x->value = array_pad($x->value, $length, 0);
for ($i = 0; $i < $length; ++$i) {
$result->value[$i]^= $x->value[$i];
}
return $this->_normalize($result);
}
* Logical Not
*
* @access public
* @internal Implemented per a request by Lluis Pamies i Juarez <lluis _a_ pamies.cat>
* @return Math_BigInteger
*/
function bitwise_not() {
}
$pre_msb = decbin(ord($temp[0]));
$temp = ~$temp;
$msb = decbin(ord($temp[0]));
if (strlen($msb) == 8) {
$msb = substr($msb, strpos($msb, '0'));
}
$temp[0] = chr(bindec($msb));
$current_bits = strlen($pre_msb) + 8 * strlen($temp) - 8;
$new_bits = $this->precision - $current_bits;
if ($new_bits <= 0) {
return $this->_normalize(new Math_BigInteger($temp, 256));
}
$leading_ones = chr((1 << ($new_bits & 0x7)) - 1) . str_repeat(chr(0xFF), $new_bits >> 3);
$this->_base256_lshift($leading_ones, $current_bits);
$temp = str_pad($temp, strlen($leading_ones), chr(0), STR_PAD_LEFT);
return $this->_normalize(new Math_BigInteger($leading_ones | $temp, 256));
}
* Logical Right Shift
*
* Shifts BigInteger's by $shift bits, effectively dividing by 2**$shift.
*
* @param int $shift
* @return Math_BigInteger
* @access public
* @internal The only version that yields any speed increases is the internal version.
*/
function bitwise_rightShift() {
}
$temp->value = gmp_div_q($this->value, gmp_pow($two, $shift));
break;
case MATH_BIGINTEGER_MODE_BCMATH:
$temp->value = bcdiv($this->value, bcpow('2', $shift, 0), 0);
break;
default: // could just replace _lshift with this, but then all _lshift() calls would need to be rewritten
$temp->value = $this->value;
$temp->_rshift($shift);
}
return $this->_normalize($temp);
}
* Logical Left Shift
*
* Shifts BigInteger's by $shift bits, effectively multiplying by 2**$shift.
*
* @param int $shift
* @return Math_BigInteger
* @access public
* @internal The only version that yields any speed increases is the internal version.
*/
function bitwise_leftShift() {
}
$temp->value = gmp_mul($this->value, gmp_pow($two, $shift));
break;
case MATH_BIGINTEGER_MODE_BCMATH:
$temp->value = bcmul($this->value, bcpow('2', $shift, 0), 0);
break;
default: // could just replace _rshift with this, but then all _lshift() calls would need to be rewritten
$temp->value = $this->value;
$temp->_lshift($shift);
}
return $this->_normalize($temp);
}
* Logical Left Rotate
*
* Instead of the top x bits being dropped they're appended to the shifted bit string.
*
* @param int $shift
* @return Math_BigInteger
* @access public
*/
function bitwise_leftRotate() {
} else {
$mask = $this->bitmask->toBytes();
}
} else {
$temp = ord($bits[0]);
for ($i = 0; $temp >> $i; ++$i) {
}
$precision = 8 * strlen($bits) - 8 + $i;
$mask = chr((1 << ($precision & 0x7)) - 1) . str_repeat(chr(0xFF), $precision >> 3);
}
if ($shift < 0) {
$shift+= $precision;
}
$shift%= $precision;
if (!$shift) {
return $this->copy();
}
$left = $this->bitwise_leftShift($shift);
$left = $left->bitwise_and(new Math_BigInteger($mask, 256));
$right = $this->bitwise_rightShift($precision - $shift);
$result = MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_BCMATH ? $left->bitwise_or($right) : $left->add($right);
return $this->_normalize($result);
}
* Logical Right Rotate
*
* Instead of the bottom x bits being dropped they're prepended to the shifted bit string.
*
* @param int $shift
* @return Math_BigInteger
* @access public
*/
function bitwise_rightRotate() {
}
* Set random number generator function
*
* This function is deprecated.
*
* @param string $generator
* @access public
*/
function setRandomGenerator() {
}
* Generates a random BigInteger
*
* Byte length is equal to $length. Uses crypt_random if it's loaded and mt_rand if it's not.
*
* @param int $length
* @return Math_BigInteger
* @access private
*/
function _random_number_helper() {
} else {
$random = '';
if ($size & 1) {
$random.= chr(mt_rand(0, 255));
}
$blocks = $size >> 1;
for ($i = 0; $i < $blocks; ++$i) {
$random.= pack('n', mt_rand(0, 0xFFFF));
}
}
return new Math_BigInteger($random, 256);
}
* Generate a random number
*
* Returns a random number between $min and $max where $min and $max
* can be defined using one of the two methods:
*
* $min->random($max)
* $max->random($min)
*
* @param Math_BigInteger $arg1
* @param Math_BigInteger $arg2
* @return Math_BigInteger
* @access public
* @internal The API for creating random numbers used to be $a->random($min, $max), where $a was a Math_BigInteger object.
*           That method is still supported for BC purposes.
*/
function random() {
}
if ($arg2 === false) {
$max = $arg1;
$min = $this;
} else {
$min = $arg1;
$max = $arg2;
}
$compare = $max->compare($min);
if (!$compare) {
return $this->_normalize($min);
} elseif ($compare < 0) {
$temp = $max;
$max = $min;
$min = $temp;
}
static $one;
if (!isset($one)) {
$one = new Math_BigInteger(1);
}
$max = $max->subtract($min->subtract($one));
$size = strlen(ltrim($max->toBytes(), chr(0)));
doing $random % $max doesn't work because some numbers will be more likely to occur than others.
eg. if $max is 140 and $random's max is 255 then that'd mean both $random = 5 and $random = 145
would produce 5 whereas the only value of random that could produce 139 would be 139. ie.
not all numbers would be equally likely. some would be more likely than others.
creating a whole new random number until you find one that is within the range doesn't work
because, for sufficiently small ranges, the likelihood that you'd get a number within that range
would be pretty small. eg. with $random's max being 255 and if your $max being 1 the probability
would be pretty high that $random would be greater than $max.
phpseclib works around this using the technique described here:
http://crypto.stackexchange.com/questions/5708/creating-a-small-number-from-a-cryptographically-secure-random-string
*/
$random_max = new Math_BigInteger(chr(1) . str_repeat("\0", $size), 256);
$random = $this->_random_number_helper($size);
list($max_multiple) = $random_max->divide($max);
$max_multiple = $max_multiple->multiply($max);
while ($random->compare($max_multiple) >= 0) {
$random = $random->subtract($max_multiple);
$random_max = $random_max->subtract($max_multiple);
$random = $random->bitwise_leftShift(8);
$random = $random->add($this->_random_number_helper(1));
$random_max = $random_max->bitwise_leftShift(8);
list($max_multiple) = $random_max->divide($max);
$max_multiple = $max_multiple->multiply($max);
}
list(, $random) = $random->divide($max);
return $this->_normalize($random->add($min));
}
* Generate a random prime number.
*
* If there's not a prime within the given range, false will be returned.
* If more than $timeout seconds have elapsed, give up and return false.
*
* @param Math_BigInteger $arg1
* @param Math_BigInteger $arg2
* @param int $timeout
* @return Math_BigInteger|false
* @access public
* @internal See {@link http://www.cacr.math.uwaterloo.ca/hac/about/chap4.pdf#page=15 HAC 4.44}.
*/
function randomPrime() {
}
if ($arg2 === false) {
$max = $arg1;
$min = $this;
} else {
$min = $arg1;
$max = $arg2;
}
$compare = $max->compare($min);
if (!$compare) {
return $min->isPrime() ? $min : false;
} elseif ($compare < 0) {
$temp = $max;
$max = $min;
$min = $temp;
}
static $one, $two;
if (!isset($one)) {
$one = new Math_BigInteger(1);
$two = new Math_BigInteger(2);
}
$start = time();
$x = $this->random($min, $max);
if (MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_GMP && extension_loaded('gmp') && version_compare(PHP_VERSION, '5.2.0', '>=')) {
$p = new Math_BigInteger();
$p->value = gmp_nextprime($x->value);
if ($p->compare($max) <= 0) {
return $p;
}
if (!$min->equals($x)) {
$x = $x->subtract($one);
}
return $x->randomPrime($min, $x);
}
if ($x->equals($two)) {
return $x;
}
$x->_make_odd();
if ($x->compare($max) > 0) {
if ($min->equals($max)) {
return false;
}
$x = $min->copy();
$x->_make_odd();
}
$initial_x = $x->copy();
while (true) {
if ($timeout !== false && time() - $start > $timeout) {
return false;
}
if ($x->isPrime()) {
return $x;
}
$x = $x->add($two);
if ($x->compare($max) > 0) {
$x = $min->copy();
if ($x->equals($two)) {
return $x;
}
$x->_make_odd();
}
if ($x->equals($initial_x)) {
return false;
}
}
}
* Make the current number odd
*
* If the current number is odd it'll be unchanged.  If it's even, one will be added to it.
*
* @see self::randomPrime()
* @access private
*/
function _make_odd() {
}
break;
default:
$this->value[0] |= 1;
}
}
* Checks a numer to see if it's prime
*
* Assuming the $t parameter is not set, this function has an error rate of 2**-80.  The main motivation for the
* $t parameter is distributability.  Math_BigInteger::randomPrime() can be distributed across multiple pageloads
* on a website instead of just one.
*
* @param Math_BigInteger $t
* @return bool
* @access public
* @internal Uses the
*     {@link http://en.wikipedia.org/wiki/Miller%E2%80%93Rabin_primality_test Miller-Rabin primality test}.  See
*     {@link http://www.cacr.math.uwaterloo.ca/hac/about/chap4.pdf#page=8 HAC 4.24}.
*/
function isPrime() {
} // floor(1300 / 8)
else if ($length >= 106) { $t =  3; } // floor( 850 / 8)
else if ($length >= 81 ) { $t =  4; } // floor( 650 / 8)
else if ($length >= 68 ) { $t =  5; } // floor( 550 / 8)
else if ($length >= 56 ) { $t =  6; } // floor( 450 / 8)
else if ($length >= 50 ) { $t =  7; } // floor( 400 / 8)
else if ($length >= 43 ) { $t =  8; } // floor( 350 / 8)
else if ($length >= 37 ) { $t =  9; } // floor( 300 / 8)
else if ($length >= 31 ) { $t = 12; } // floor( 250 / 8)
else if ($length >= 25 ) { $t = 15; } // floor( 200 / 8)
else if ($length >= 18 ) { $t = 18; } // floor( 150 / 8)
else                     { $t = 27; }
}
switch (MATH_BIGINTEGER_MODE) {
case MATH_BIGINTEGER_MODE_GMP:
return gmp_prob_prime($this->value, $t) != 0;
case MATH_BIGINTEGER_MODE_BCMATH:
if ($this->value === '2') {
return true;
}
if ($this->value[strlen($this->value) - 1] % 2 == 0) {
return false;
}
break;
default:
if ($this->value == array(2)) {
return true;
}
if (~$this->value[0] & 1) {
return false;
}
}
static $primes, $zero, $one, $two;
if (!isset($primes)) {
$primes = array(
3,    5,    7,    11,   13,   17,   19,   23,   29,   31,   37,   41,   43,   47,   53,   59,
61,   67,   71,   73,   79,   83,   89,   97,   101,  103,  107,  109,  113,  127,  131,  137,
139,  149,  151,  157,  163,  167,  173,  179,  181,  191,  193,  197,  199,  211,  223,  227,
229,  233,  239,  241,  251,  257,  263,  269,  271,  277,  281,  283,  293,  307,  311,  313,
317,  331,  337,  347,  349,  353,  359,  367,  373,  379,  383,  389,  397,  401,  409,  419,
421,  431,  433,  439,  443,  449,  457,  461,  463,  467,  479,  487,  491,  499,  503,  509,
521,  523,  541,  547,  557,  563,  569,  571,  577,  587,  593,  599,  601,  607,  613,  617,
619,  631,  641,  643,  647,  653,  659,  661,  673,  677,  683,  691,  701,  709,  719,  727,
733,  739,  743,  751,  757,  761,  769,  773,  787,  797,  809,  811,  821,  823,  827,  829,
839,  853,  857,  859,  863,  877,  881,  883,  887,  907,  911,  919,  929,  937,  941,  947,
953,  967,  971,  977,  983,  991,  997
);
if (MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_INTERNAL) {
for ($i = 0; $i < count($primes); ++$i) {
$primes[$i] = new Math_BigInteger($primes[$i]);
}
}
$zero = new Math_BigInteger();
$one = new Math_BigInteger(1);
$two = new Math_BigInteger(2);
}
if ($this->equals($one)) {
return false;
}
if (MATH_BIGINTEGER_MODE != MATH_BIGINTEGER_MODE_INTERNAL) {
foreach ($primes as $prime) {
list(, $r) = $this->divide($prime);
if ($r->equals($zero)) {
return $this->equals($prime);
}
}
} else {
$value = $this->value;
foreach ($primes as $prime) {
list(, $r) = $this->_divide_digit($value, $prime);
if (!$r) {
return count($value) == 1 && $value[0] == $prime;
}
}
}
$n   = $this->copy();
$n_1 = $n->subtract($one);
$n_2 = $n->subtract($two);
$r = $n_1->copy();
$r_value = $r->value;
if (MATH_BIGINTEGER_MODE == MATH_BIGINTEGER_MODE_BCMATH) {
$s = 0;
while ($r->value[strlen($r->value) - 1] % 2 == 0) {
$r->value = bcdiv($r->value, '2', 0);
++$s;
}
} else {
for ($i = 0, $r_length = count($r_value); $i < $r_length; ++$i) {
$temp = ~$r_value[$i] & 0xFFFFFF;
for ($j = 1; ($temp >> $j) & 1; ++$j) {
}
if ($j != 25) {
break;
}
}
$s = 26 * $i + $j;
$r->_rshift($s);
}
for ($i = 0; $i < $t; ++$i) {
$a = $this->random($two, $n_2);
$y = $a->modPow($r, $n);
if (!$y->equals($one) && !$y->equals($n_1)) {
for ($j = 1; $j < $s && !$y->equals($n_1); ++$j) {
$y = $y->modPow($two, $n);
if ($y->equals($one)) {
return false;
}
}
if (!$y->equals($n_1)) {
return false;
}
}
}
return true;
}
* Logical Left Shift
*
* Shifts BigInteger's by $shift bits.
*
* @param int $shift
* @access private
*/
function _lshift() {
}
$num_digits = (int) ($shift / MATH_BIGINTEGER_BASE);
$shift %= MATH_BIGINTEGER_BASE;
$shift = 1 << $shift;
$carry = 0;
for ($i = 0; $i < count($this->value); ++$i) {
$temp = $this->value[$i] * $shift + $carry;
$carry = MATH_BIGINTEGER_BASE === 26 ? intval($temp / 0x4000000) : ($temp >> 31);
$this->value[$i] = (int) ($temp - $carry * MATH_BIGINTEGER_BASE_FULL);
}
if ($carry) {
$this->value[count($this->value)] = $carry;
}
while ($num_digits--) {
array_unshift($this->value, 0);
}
}
* Logical Right Shift
*
* Shifts BigInteger's by $shift bits.
*
* @param int $shift
* @access private
*/
function _rshift() {
}
$num_digits = (int) ($shift / MATH_BIGINTEGER_BASE);
$shift %= MATH_BIGINTEGER_BASE;
$carry_shift = MATH_BIGINTEGER_BASE - $shift;
$carry_mask = (1 << $shift) - 1;
if ($num_digits) {
$this->value = array_slice($this->value, $num_digits);
}
$carry = 0;
for ($i = count($this->value) - 1; $i >= 0; --$i) {
$temp = $this->value[$i] >> $shift | $carry;
$carry = ($this->value[$i] & $carry_mask) << $carry_shift;
$this->value[$i] = $temp;
}
$this->value = $this->_trim($this->value);
}
* Normalize
*
* Removes leading zeros and truncates (if necessary) to maintain the appropriate precision
*
* @param Math_BigInteger
* @return Math_BigInteger
* @see self::_trim()
* @access private
*/
function _normalize() {
}
$result->value = gmp_and($result->value, $result->bitmask->value);
if ($flip) {
$result->value = gmp_neg($result->value);
}
}
return $result;
case MATH_BIGINTEGER_MODE_BCMATH:
if (!empty($result->bitmask->value)) {
$result->value = bcmod($result->value, $result->bitmask->value);
}
return $result;
}
$value = &$result->value;
if (!count($value)) {
$result->is_negative = false;
return $result;
}
$value = $this->_trim($value);
if (!empty($result->bitmask->value)) {
$length = min(count($value), count($this->bitmask->value));
$value = array_slice($value, 0, $length);
for ($i = 0; $i < $length; ++$i) {
$value[$i] = $value[$i] & $this->bitmask->value[$i];
}
}
return $result;
}
* Trim
*
* Removes leading zeros
*
* @param array $value
* @return Math_BigInteger
* @access private
*/
function _trim() {
}
unset($value[$i]);
}
return $value;
}
* Array Repeat
*
* @param $input Array
* @param $multiplier mixed
* @return array
* @access private
*/
function _array_repeat() {
}
* Logical Left Shift
*
* Shifts binary strings $shift bits, essentially multiplying by 2**$shift.
*
* @param $x String
* @param $shift Integer
* @return string
* @access private
*/
function _base256_lshift() {
}
$num_bytes = $shift >> 3; // eg. floor($shift/8)
$shift &= 7; // eg. $shift % 8
$carry = 0;
for ($i = strlen($x) - 1; $i >= 0; --$i) {
$temp = ord($x[$i]) << $shift | $carry;
$x[$i] = chr($temp);
$carry = $temp >> 8;
}
$carry = ($carry != 0) ? chr($carry) : '';
$x = $carry . $x . str_repeat(chr(0), $num_bytes);
}
* Logical Right Shift
*
* Shifts binary strings $shift bits, essentially dividing by 2**$shift and returning the remainder.
*
* @param $x String
* @param $shift Integer
* @return string
* @access private
*/
function _base256_rshift() {
}
$num_bytes = $shift >> 3; // eg. floor($shift/8)
$shift &= 7; // eg. $shift % 8
$remainder = '';
if ($num_bytes) {
$start = $num_bytes > strlen($x) ? -strlen($x) : -$num_bytes;
$remainder = substr($x, $start);
$x = substr($x, 0, -$num_bytes);
}
$carry = 0;
$carry_shift = 8 - $shift;
for ($i = 0; $i < strlen($x); ++$i) {
$temp = (ord($x[$i]) >> $shift) | $carry;
$carry = (ord($x[$i]) << $carry_shift) & 0xFF;
$x[$i] = chr($temp);
}
$x = ltrim($x, chr(0));
$remainder = chr($carry >> $carry_shift) . $remainder;
return ltrim($remainder, chr(0));
}
* Converts 32-bit integers to bytes.
*
* @param int $x
* @return string
* @access private
*/
function _int2bytes() {
}
* Converts bytes to 32-bit integers
*
* @param string $x
* @return int
* @access private
*/
function _bytes2int() {
}
* DER-encode an integer
*
* The ability to DER-encode integers is needed to create RSA public keys for use with OpenSSL
*
* @see self::modPow()
* @access private
* @param int $length
* @return string
*/
function _encodeASN1Length() {
}
$temp = ltrim(pack('N', $length), chr(0));
return pack('Ca*', 0x80 | strlen($temp), $temp);
}
* Single digit division
*
* Even if int64 is being used the division operator will return a float64 value
* if the dividend is not evenly divisible by the divisor. Since a float64 doesn't
* have the precision of int64 this is a problem so, when int64 is being used,
* we'll guarantee that the dividend is divisible by first subtracting the remainder.
*
* @access private
* @param int $x
* @param int $y
* @return int
*/
function _safe_divide() {
}
return ($x - ($x % $y)) / $y;
}
}