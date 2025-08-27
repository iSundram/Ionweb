<?php
* Class for working with MO files
*
* @version $Id: mo.php 1157 2015-11-20 04:30:11Z dd32 $
* @package pomo
* @subpackage mo
*/
require_once __DIR__ . '/translations.php';
require_once __DIR__ . '/streams.php';
if ( ! class_exists( 'MO', false ) ) :
class MO extends Gettext_Translations {
* Number of plural forms.
*
* @var int
*/
public $_nplurals = 2;
* Loaded MO file.
*
* @var string
*/
private $filename = '';
* Returns the loaded MO file.
*
* @return string The loaded MO file.
*/
public function get_filename() {
}
* Fills up with the entries from MO file $filename
*
* @param string $filename MO file to load
* @return bool True if the import from file was successful, otherwise false.
*/
public function import_from_file() {
}
$this->filename = (string) $filename;
return $this->import_from_reader( $reader );
}
* @param string $filename
* @return bool
*/
public function export_to_file() {
}
$res = $this->export_to_file_handle( $fh );
fclose( $fh );
return $res;
}
* @return string|false
*/
public function export() {
}
$this->export_to_file_handle( $tmp_fh );
rewind( $tmp_fh );
return stream_get_contents( $tmp_fh );
}
* @param Translation_Entry $entry
* @return bool
*/
public function is_entry_good_for_export() {
}
if ( ! array_filter( $entry->translations ) ) {
return false;
}
return true;
}
* @param resource $fh
* @return true
*/
public function export_to_file_handle() {
}
$exported_headers = $this->export_headers();
fwrite( $fh, pack( 'VV', $reader->strlen( $exported_headers ), $current_addr ) );
$current_addr      += strlen( $exported_headers ) + 1;
$translations_table = $exported_headers . "\0";
foreach ( $entries as $entry ) {
$translations_table .= $this->export_translations( $entry ) . "\0";
$length              = $reader->strlen( $this->export_translations( $entry ) );
fwrite( $fh, pack( 'VV', $length, $current_addr ) );
$current_addr += $length + 1;
}
fwrite( $fh, $originals_table );
fwrite( $fh, $translations_table );
return true;
}
* @param Translation_Entry $entry
* @return string
*/
public function export_original() {
}
if ( $entry->context ) {
$exported = $entry->context . "\4" . $exported;
}
return $exported;
}
* @param Translation_Entry $entry
* @return string
*/
public function export_translations() {
}
* @return string
*/
public function export_headers() {
}
return $exported;
}
* @param int $magic
* @return string|false
*/
public function get_byteorder() {
} elseif ( $magic_big === $magic ) {
return 'big';
} else {
return false;
}
}
* @param POMO_FileReader $reader
* @return bool True if the import was successful, otherwise false.
*/
public function import_from_reader() {
}
$reader->setEndian( $endian_string );
$endian = ( 'big' === $endian_string ) ? 'N' : 'V';
$header = $reader->read( 24 );
if ( $reader->strlen( $header ) !== 24 ) {
return false;
}
$header = unpack( "{$endian}revision/{$endian}total/{$endian}originals_lengths_addr/{$endian}translations_lengths_addr/{$endian}hash_length/{$endian}hash_addr", $header );
if ( ! is_array( $header ) ) {
return false;
}
if ( 0 !== $header['revision'] ) {
return false;
}
$reader->seekto( $header['originals_lengths_addr'] );
$originals_lengths_length = $header['translations_lengths_addr'] - $header['originals_lengths_addr'];
if ( $originals_lengths_length !== $header['total'] * 8 ) {
return false;
}
$originals = $reader->read( $originals_lengths_length );
if ( $reader->strlen( $originals ) !== $originals_lengths_length ) {
return false;
}
$translations_lengths_length = $header['hash_addr'] - $header['translations_lengths_addr'];
if ( $translations_lengths_length !== $header['total'] * 8 ) {
return false;
}
$translations = $reader->read( $translations_lengths_length );
if ( $reader->strlen( $translations ) !== $translations_lengths_length ) {
return false;
}
$originals    = $reader->str_split( $originals, 8 );
$translations = $reader->str_split( $translations, 8 );
$strings_addr = $header['hash_addr'] + $header['hash_length'] * 4;
$reader->seekto( $strings_addr );
$strings = $reader->read_all();
$reader->close();
for ( $i = 0; $i < $header['total']; $i++ ) {
$o = unpack( "{$endian}length/{$endian}pos", $originals[ $i ] );
$t = unpack( "{$endian}length/{$endian}pos", $translations[ $i ] );
if ( ! $o || ! $t ) {
return false;
}
$o['pos'] -= $strings_addr;
$t['pos'] -= $strings_addr;
$original    = $reader->substr( $strings, $o['pos'], $o['length'] );
$translation = $reader->substr( $strings, $t['pos'], $t['length'] );
if ( '' === $original ) {
$this->set_headers( $this->make_headers( $translation ) );
} else {
$entry                          = &$this->make_entry( $original, $translation );
$this->entries[ $entry->key() ] = &$entry;
}
}
return true;
}
* Build a Translation_Entry from original string and translation strings,
* found in a MO file
*
* @static
* @param string $original original string to translate from MO file. Might contain
*  0x04 as context separator or 0x00 as singular/plural separator
* @param string $translation translation string from MO file. Might contain
*  0x00 as a plural translations separator
* @return Translation_Entry Entry instance.
*/
public function &make_entry( $original, $translation ) {
$entry = new Translation_Entry();
$parts = explode( "\4", $original );
if ( isset( $parts[1] ) ) {
$original       = $parts[1];
$entry->context = $parts[0];
}
$parts           = explode( "\0", $original );
$entry->singular = $parts[0];
if ( isset( $parts[1] ) ) {
$entry->is_plural = true;
$entry->plural    = $parts[1];
}
$entry->translations = explode( "\0", $translation );
return $entry;
}
* @param int $count
* @return string
*/
public function select_plural_form() {
}
* @return int
*/
public function get_plural_forms_count() {
}
}
endif;