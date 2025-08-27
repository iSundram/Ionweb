<?php
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to
* deal in the Software without restriction, including without limitation the
* rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
* sell copies of the Software, and to permit persons to whom the Software is
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
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/
if(!class_exists('Math_BigInteger')){
require_once 'BigInteger.php';
}
* Converts human readable representation to a 128 bit int
* which can be stored in MySQL using DECIMAL(39,0).
*
* Requires PHP to be compiled with IPv6 support.
* This could be made to work without IPv6 support but
* I don't think there would be much use for it if PHP
* doesn't support IPv6.
*
* @param string $ip IPv4 or IPv6 address to convert
* @return string 128 bit string that can be used with DECIMNAL(39,0) or false
*/
if(!function_exists('inet_ptoi'))
{
function inet_ptoi() {
}
if (function_exists('bcadd'))
{
$decimal = $parts[4];
$decimal = bcadd($decimal, bcmul($parts[3], '4294967296'));
$decimal = bcadd($decimal, bcmul($parts[2], '18446744073709551616'));
$decimal = bcadd($decimal, bcmul($parts[1], '79228162514264337593543950336'));
}
else
{
$decimal = new Math_BigInteger($parts[4]);
$part3   = new Math_BigInteger($parts[3]);
$part2   = new Math_BigInteger($parts[2]);
$part1   = new Math_BigInteger($parts[1]);
$decimal = $decimal->add($part3->multiply(new Math_BigInteger('4294967296')));
$decimal = $decimal->add($part2->multiply(new Math_BigInteger('18446744073709551616')));
$decimal = $decimal->add($part1->multiply(new Math_BigInteger('79228162514264337593543950336')));
$decimal = $decimal->toString();
}
return $decimal;
}
}
* Converts a 128 bit int to a human readable representation.
*
* Requires PHP to be compiled with IPv6 support.
* This could be made to work without IPv6 support but
* I don't think there would be much use for it if PHP
* doesn't support IPv6.
*
* @param string $decimal 128 bit int
* @return string IPv4 or IPv6
*/
if(!function_exists('inet_itop'))
{
function inet_itop() {
}
else
{
$decimal = new Math_BigInteger($decimal);
list($parts[1],) = $decimal->divide(new Math_BigInteger('79228162514264337593543950336'));
$decimal = $decimal->subtract($parts[1]->multiply(new Math_BigInteger('79228162514264337593543950336')));
list($parts[2],) = $decimal->divide(new Math_BigInteger('18446744073709551616'));
$decimal = $decimal->subtract($parts[2]->multiply(new Math_BigInteger('18446744073709551616')));
list($parts[3],) = $decimal->divide(new Math_BigInteger('4294967296'));
$decimal = $decimal->subtract($parts[3]->multiply(new Math_BigInteger('4294967296')));
$parts[4] = $decimal;
$parts[1] = $parts[1]->toString();
$parts[2] = $parts[2]->toString();
$parts[3] = $parts[3]->toString();
$parts[4] = $parts[4]->toString();
}
foreach ($parts as &$part)
{
if ($part > 2147483647)
$part -= 4294967296;
}
$ip = inet_ntop(pack('N4', $parts[1], $parts[2], $parts[3], $parts[4]));
if (strpos($ip, '.') !== false)
return substr($ip, 2);
return $ip;
}
}
18446744073709551615
4294967295
4294967295
::ffff:ffff:ffff:ffff
255.255.255.255
*/