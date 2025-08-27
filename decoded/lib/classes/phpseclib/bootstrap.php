<?php
* Bootstrapping File for phpseclib
*
* @license http://www.opensource.org/licenses/mit-license.html MIT License
*/
if (extension_loaded('mbstring')) {
if (ini_get('mbstring.func_overload') & 2) {
throw new \UnexpectedValueException(
'Overloading of string functions using mbstring.func_overload ' .
'is not supported by phpseclib.'
);
}
}