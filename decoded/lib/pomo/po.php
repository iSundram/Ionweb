<?php
* Class for working with PO files
*
* @version $Id: po.php 1158 2015-11-20 04:31:23Z dd32 $
* @package pomo
* @subpackage po
*/
require_once __DIR__ . '/translations.php';
if ( ! defined( 'PO_MAX_LINE_LEN' ) ) {
define( 'PO_MAX_LINE_LEN', 79 );
}
* The `auto_detect_line_endings` setting has been deprecated in PHP 8.1,
* but will continue to work until PHP 9.0.
* For now, we're silencing the deprecation notice as there may still be
* translation files around which haven't been updated in a long time and
* which still use the old MacOS standalone `\r` as a line ending.
* This fix should be revisited when PHP 9.0 is in alpha/beta.
*/
@ini_set( 'auto_detect_line_endings', 1 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
* Routines for working with PO files
*/
if ( ! class_exists( 'PO', false ) ) :
class PO extends Gettext_Translations {
public $comments_before_headers = '';
* Exports headers to a PO entry
*
* @return string msgid/msgstr PO entry for this PO file headers, doesn't contain newline at the end
*/
public function export_headers() {
}
$poified = PO::poify( $header_string );
if ( $this->comments_before_headers ) {
$before_headers = $this->prepend_each_line( rtrim( $this->comments_before_headers ) . "\n", '# ' );
} else {
$before_headers = '';
}
return rtrim( "{$before_headers}msgid \"\"\nmsgstr $poified" );
}
* Exports all entries to PO format
*
* @return string sequence of mgsgid/msgstr PO strings, doesn't containt newline at the end
*/
public function export_entries() {
}
* Exports the whole PO file as a string
*
* @param bool $include_headers whether to include the headers in the export
* @return string ready for inclusion in PO file string for headers and all the enrtries
*/
public function export() {
}
$res .= $this->export_entries();
return $res;
}
* Same as {@link export}, but writes the result to a file
*
* @param string $filename        Where to write the PO string.
* @param bool   $include_headers Whether to include the headers in the export.
* @return bool true on success, false on error
*/
public function export_to_file() {
}
$export = $this->export( $include_headers );
$res    = fwrite( $fh, $export );
if ( false === $res ) {
return false;
}
return fclose( $fh );
}
* Text to include as a comment before the start of the PO contents
*
* Doesn't need to include # in the beginning of lines, these are added automatically
*
* @param string $text Text to include as a comment.
*/
public function set_comment_before_headers() {
}
* Formats a string in PO-style
*
* @param string $input_string the string to format
* @return string the poified string
*/
public static function poify() {
}n{$quote}{$newline}{$quote}", explode( $newline, $input_string ) ) . $quote;
if ( str_contains( $input_string, $newline ) &&
( substr_count( $input_string, $newline ) > 1 || substr( $input_string, -strlen( $newline ) ) !== $newline ) ) {
$po = "$quote$quote$newline$po";
}
$po = str_replace( "$newline$quote$quote", '', $po );
return $po;
}
* Gives back the original string from a PO-formatted string
*
* @param string $input_string PO-formatted string
* @return string enascaped string
*/
public static function unpoify() {
} else {
$unpoified .= $char;
}
} else {
$previous_is_backslash = false;
$unpoified            .= isset( $escapes[ $char ] ) ? $escapes[ $char ] : $char;
}
}
}
$unpoified = str_replace( array( "\r\n", "\r" ), "\n", $unpoified );
return $unpoified;
}
* Inserts $with in the beginning of every new line of $input_string and
* returns the modified string
*
* @param string $input_string prepend lines in this string
* @param string $with         prepend lines with this string
*/
public static function prepend_each_line() {
}
foreach ( $lines as &$line ) {
$line = $with . $line;
}
unset( $line );
return implode( "\n", $lines ) . $append;
}
* Prepare a text as a comment -- wraps the lines and prepends #
* and a special character to each line
*
* @access private
* @param string $text the comment text
* @param string $char character to denote a special PO comment,
*  like :, default is a space
*/
public static function comment_block() {
}
* Builds a string from the entry for inclusion in PO file
*
* @param Translation_Entry $entry the entry to convert to po string.
* @return string|false PO-style formatted string for the entry or
*  false if the entry is empty
*/
public static function export_entry() {
}
$po = array();
if ( ! empty( $entry->translator_comments ) ) {
$po[] = PO::comment_block( $entry->translator_comments );
}
if ( ! empty( $entry->extracted_comments ) ) {
$po[] = PO::comment_block( $entry->extracted_comments, '.' );
}
if ( ! empty( $entry->references ) ) {
$po[] = PO::comment_block( implode( ' ', $entry->references ), ':' );
}
if ( ! empty( $entry->flags ) ) {
$po[] = PO::comment_block( implode( ', ', $entry->flags ), ',' );
}
if ( $entry->context ) {
$po[] = 'msgctxt ' . PO::poify( $entry->context );
}
$po[] = 'msgid ' . PO::poify( $entry->singular );
if ( ! $entry->is_plural ) {
$translation = empty( $entry->translations ) ? '' : $entry->translations[0];
$translation = PO::match_begin_and_end_newlines( $translation, $entry->singular );
$po[]        = 'msgstr ' . PO::poify( $translation );
} else {
$po[]         = 'msgid_plural ' . PO::poify( $entry->plural );
$translations = empty( $entry->translations ) ? array( '', '' ) : $entry->translations;
foreach ( $translations as $i => $translation ) {
$translation = PO::match_begin_and_end_newlines( $translation, $entry->plural );
$po[]        = "msgstr[$i] " . PO::poify( $translation );
}
}
return implode( "\n", $po );
}
public static function match_begin_and_end_newlines() {
}
$original_begin    = "\n" === substr( $original, 0, 1 );
$original_end      = "\n" === substr( $original, -1 );
$translation_begin = "\n" === substr( $translation, 0, 1 );
$translation_end   = "\n" === substr( $translation, -1 );
if ( $original_begin ) {
if ( ! $translation_begin ) {
$translation = "\n" . $translation;
}
} elseif ( $translation_begin ) {
$translation = ltrim( $translation, "\n" );
}
if ( $original_end ) {
if ( ! $translation_end ) {
$translation .= "\n";
}
} elseif ( $translation_end ) {
$translation = rtrim( $translation, "\n" );
}
return $translation;
}
* @param string $filename
* @return bool
*/
public function import_from_file() {
}
$lineno = 0;
while ( true ) {
$res = $this->read_entry( $f, $lineno );
if ( ! $res ) {
break;
}
if ( '' === $res['entry']->singular ) {
$this->set_headers( $this->make_headers( $res['entry']->translations[0] ) );
} else {
$this->add_entry( $res['entry'] );
}
}
PO::read_line( $f, 'clear' );
if ( false === $res ) {
return false;
}
if ( ! $this->headers && ! $this->entries ) {
return false;
}
return true;
}
* Helper function for read_entry
*
* @param string $context
* @return bool
*/
protected static function is_final() {
}
* @param resource $f
* @param int      $lineno
* @return null|false|array
*/
public function read_entry() {
} elseif ( ! $context ) { // We haven't read a line and EOF came.
return null;
} else {
return false;
}
} else {
return false;
}
}
if ( "\n" === $line ) {
continue;
}
$line = trim( $line );
if ( preg_match( '/^#/', $line, $m ) ) {
if ( self::is_final( $context ) ) {
PO::read_line( $f, 'put-back' );
$lineno--;
break;
}
if ( $context && 'comment' !== $context ) {
return false;
}
$this->add_comment_to_entry( $entry, $line );
} elseif ( preg_match( '/^msgctxt\s+(".*")/', $line, $m ) ) {
if ( self::is_final( $context ) ) {
PO::read_line( $f, 'put-back' );
$lineno--;
break;
}
if ( $context && 'comment' !== $context ) {
return false;
}
$context         = 'msgctxt';
$entry->context .= PO::unpoify( $m[1] );
} elseif ( preg_match( '/^msgid\s+(".*")/', $line, $m ) ) {
if ( self::is_final( $context ) ) {
PO::read_line( $f, 'put-back' );
$lineno--;
break;
}
if ( $context && 'msgctxt' !== $context && 'comment' !== $context ) {
return false;
}
$context          = 'msgid';
$entry->singular .= PO::unpoify( $m[1] );
} elseif ( preg_match( '/^msgid_plural\s+(".*")/', $line, $m ) ) {
if ( 'msgid' !== $context ) {
return false;
}
$context          = 'msgid_plural';
$entry->is_plural = true;
$entry->plural   .= PO::unpoify( $m[1] );
} elseif ( preg_match( '/^msgstr\s+(".*")/', $line, $m ) ) {
if ( 'msgid' !== $context ) {
return false;
}
$context             = 'msgstr';
$entry->translations = array( PO::unpoify( $m[1] ) );
} elseif ( preg_match( '/^msgstr\[(\d+)\]\s+(".*")/', $line, $m ) ) {
if ( 'msgid_plural' !== $context && 'msgstr_plural' !== $context ) {
return false;
}
$context                      = 'msgstr_plural';
$msgstr_index                 = $m[1];
$entry->translations[ $m[1] ] = PO::unpoify( $m[2] );
} elseif ( preg_match( '/^".*"$/', $line ) ) {
$unpoified = PO::unpoify( $line );
switch ( $context ) {
case 'msgid':
$entry->singular .= $unpoified;
break;
case 'msgctxt':
$entry->context .= $unpoified;
break;
case 'msgid_plural':
$entry->plural .= $unpoified;
break;
case 'msgstr':
$entry->translations[0] .= $unpoified;
break;
case 'msgstr_plural':
$entry->translations[ $msgstr_index ] .= $unpoified;
break;
default:
return false;
}
} else {
return false;
}
}
$have_translations = false;
foreach ( $entry->translations as $t ) {
if ( $t || ( '0' === $t ) ) {
$have_translations = true;
break;
}
}
if ( false === $have_translations ) {
$entry->translations = array();
}
return array(
'entry'  => $entry,
'lineno' => $lineno,
);
}
* @param resource $f
* @param string   $action
* @return bool
*/
public function read_line() {
}
if ( 'put-back' === $action ) {
$use_last_line = true;
return true;
}
$line          = $use_last_line ? $last_line : fgets( $f );
$line          = ( "\r\n" === substr( $line, -2 ) ) ? rtrim( $line, "\r\n" ) . "\n" : $line;
$last_line     = $line;
$use_last_line = false;
return $line;
}
* @param Translation_Entry $entry
* @param string            $po_comment_line
*/
public function add_comment_to_entry() {
} elseif ( '#.' === $first_two ) {
$entry->extracted_comments = trim( $entry->extracted_comments . "\n" . $comment );
} elseif ( '#,' === $first_two ) {
$entry->flags = array_merge( $entry->flags, preg_split( '/,\s*/', $comment ) );
} else {
$entry->translator_comments = trim( $entry->translator_comments . "\n" . $comment );
}
}
* @param string $s
* @return string
*/
public static function trim_quotes() {
}
if ( str_ends_with( $s, '"' ) ) {
$s = substr( $s, 0, -1 );
}
return $s;
}
}
endif;