<?php
* Classes, which help reading streams of data from files.
* Based on the classes from Danilo Segan <danilo@kvota.net>
*
* @version $Id: streams.php 1157 2015-11-20 04:30:11Z dd32 $
* @package pomo
* @subpackage streams
*/
if ( ! class_exists( 'POMO_Reader', false ) ) :
#[AllowDynamicProperties]
class POMO_Reader {
public $endian = 'little';
public $_pos;
public $is_overloaded;
* PHP5 constructor.
*/
public function __construct() {
} else {
$this->is_overloaded = false;
}
$this->_pos = 0;
}
* PHP4 constructor.
*
* @deprecated 5.4.0 Use __construct() instead.
*
* @see POMO_Reader::__construct()
*/
public function POMO_Reader() {
}
* Sets the endianness of the file.
*
* @param string $endian Set the endianness of the file. Accepts 'big', or 'little'.
*/
public function setEndian() {
}
* Reads a 32bit Integer from the Stream
*
* @return mixed The integer, corresponding to the next 32 bits from
*  the stream of false if there are not enough bytes or on error
*/
public function readint32() {
}
$endian_letter = ( 'big' === $this->endian ) ? 'N' : 'V';
$int           = unpack( $endian_letter, $bytes );
return reset( $int );
}
* Reads an array of 32-bit Integers from the Stream
*
* @param int $count How many elements should be read
* @return mixed Array of integers or false if there isn't
*  enough data or on error
*/
public function readint32array() {
}
$endian_letter = ( 'big' === $this->endian ) ? 'N' : 'V';
return unpack( $endian_letter . $count, $bytes );
}
* @param string $input_string
* @param int    $start
* @param int    $length
* @return string
*/
public function substr() {
} else {
return substr( $input_string, $start, $length );
}
}
* @param string $input_string
* @return int
*/
public function strlen() {
} else {
return strlen( $input_string );
}
}
* @param string $input_string
* @param int    $chunk_size
* @return array
*/
public function str_split() {
}
return $out;
} else {
return str_split( $input_string, $chunk_size );
}
}
* @return int
*/
public function pos() {
}
* @return true
*/
public function is_resource() {
}
* @return true
*/
public function close() {
}
}
endif;
if ( ! class_exists( 'POMO_FileReader', false ) ) :
class POMO_FileReader extends POMO_Reader {
* File pointer resource.
*
* @var resource|false
*/
public $_f;
* @param string $filename
*/
public function __construct() {
}
* PHP4 constructor.
*
* @deprecated 5.4.0 Use __construct() instead.
*
* @see POMO_FileReader::__construct()
*/
public function POMO_FileReader() {
}
* @param int $bytes
* @return string|false Returns read string, otherwise false.
*/
public function read() {
}
* @param int $pos
* @return bool
*/
public function seekto() {
}
$this->_pos = $pos;
return true;
}
* @return bool
*/
public function is_resource() {
}
* @return bool
*/
public function feof() {
}
* @return bool
*/
public function close() {
}
* @return string
*/
public function read_all() {
}
}
endif;
if ( ! class_exists( 'POMO_StringReader', false ) ) :
* Provides file-like methods for manipulating a string instead
* of a physical file.
*/
class POMO_StringReader extends POMO_Reader {
public $_str = '';
* PHP5 constructor.
*/
public function __construct() {
}
* PHP4 constructor.
*
* @deprecated 5.4.0 Use __construct() instead.
*
* @see POMO_StringReader::__construct()
*/
public function POMO_StringReader() {
}
* @param string $bytes
* @return string
*/
public function read() {
}
return $data;
}
* @param int $pos
* @return int
*/
public function seekto() {
}
return $this->_pos;
}
* @return int
*/
public function length() {
}
* @return string
*/
public function read_all() {
}
}
endif;
if ( ! class_exists( 'POMO_CachedFileReader', false ) ) :
* Reads the contents of the file in the beginning.
*/
class POMO_CachedFileReader extends POMO_StringReader {
* PHP5 constructor.
*/
public function __construct() {
}
$this->_pos = 0;
}
* PHP4 constructor.
*
* @deprecated 5.4.0 Use __construct() instead.
*
* @see POMO_CachedFileReader::__construct()
*/
public function POMO_CachedFileReader() {
}
}
endif;
if ( ! class_exists( 'POMO_CachedIntFileReader', false ) ) :
* Reads the contents of the file in the beginning.
*/
class POMO_CachedIntFileReader extends POMO_CachedFileReader {
* PHP5 constructor.
*/
public function __construct() {
}
* PHP4 constructor.
*
* @deprecated 5.4.0 Use __construct() instead.
*
* @see POMO_CachedIntFileReader::__construct()
*/
public function POMO_CachedIntFileReader() {
}
}
endif;