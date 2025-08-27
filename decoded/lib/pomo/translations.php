<?php
* Class for a set of entries for translation and their associated headers
*
* @version $Id: translations.php 1157 2015-11-20 04:30:11Z dd32 $
* @package pomo
* @subpackage translations
*/
require_once __DIR__ . '/plural-forms.php';
require_once __DIR__ . '/entry.php';
if ( ! class_exists( 'Translations', false ) ) :
#[AllowDynamicProperties]
class Translations {
public $entries = array();
public $headers = array();
* Add entry to the PO structure
*
* @param array|Translation_Entry $entry
* @return bool true on success, false if the entry doesn't have a key
*/
public function add_entry() {
}
$key = $entry->key();
if ( false === $key ) {
return false;
}
$this->entries[ $key ] = &$entry;
return true;
}
* @param array|Translation_Entry $entry
* @return bool
*/
public function add_entry_or_merge() {
}
$key = $entry->key();
if ( false === $key ) {
return false;
}
if ( isset( $this->entries[ $key ] ) ) {
$this->entries[ $key ]->merge_with( $entry );
} else {
$this->entries[ $key ] = &$entry;
}
return true;
}
* Sets $header PO header to $value
*
* If the header already exists, it will be overwritten
*
* TODO: this should be out of this class, it is gettext specific
*
* @param string $header header name, without trailing :
* @param string $value header value, without trailing \n
*/
public function set_header() {
}
* @param array $headers
*/
public function set_headers() {
}
}
* @param string $header
*/
public function get_header() {
}
* @param Translation_Entry $entry
*/
public function translate_entry() {
}
* @param string $singular
* @param string $context
* @return string
*/
public function translate() {
}
* Given the number of items, returns the 0-based index of the plural form to use
*
* Here, in the base Translations class, the common logic for English is implemented:
*  0 if there is one element, 1 otherwise
*
* This function should be overridden by the subclasses. For example MO/PO can derive the logic
* from their headers.
*
* @param int $count number of items
*/
public function select_plural_form() {
}
* @return int
*/
public function get_plural_forms_count() {
}
* @param string $singular
* @param string $plural
* @param int    $count
* @param string $context
*/
public function translate_plural() {
} else {
return 1 === (int) $count ? $singular : $plural;
}
}
* Merge $other in the current object.
*
* @param Object $other Another Translation object, whose translations will be merged in this one (passed by reference).
*/
public function merge_with() {
}
}
* @param object $other
*/
public function merge_originals_with() {
} else {
$this->entries[ $entry->key() ]->merge_with( $entry );
}
}
}
}
class Gettext_Translations extends Translations {
* Number of plural forms.
*
* @var int
*/
public $_nplurals;
* Callback to retrieve the plural form.
*
* @var callable
*/
public $_gettext_select_plural_form;
* The gettext implementation of select_plural_form.
*
* It lives in this class, because there are more than one descendand, which will use it and
* they can't share it effectively.
*
* @param int $count
*/
public function gettext_select_plural_form() {
}
return call_user_func( $this->_gettext_select_plural_form, $count );
}
* @param string $header
* @return array
*/
public function nplurals_and_expression_from_header() {
} else {
return array( 2, 'n != 1' );
}
}
* Makes a function, which will return the right translation index, according to the
* plural forms header
*
* @param int    $nplurals
* @param string $expression
*/
public function make_plural_form_function() {
} catch ( Exception $e ) {
return $this->make_plural_form_function( 2, 'n != 1' );
}
}
* Adds parentheses to the inner parts of ternary operators in
* plural expressions, because PHP evaluates ternary oerators from left to right
*
* @param string $expression the expression without parentheses
* @return string the expression with parentheses added
*/
public function parenthesize_plural_exression() {
}
}
return rtrim( $res, ';' );
}
* @param string $translation
* @return array
*/
public function make_headers() {
}
$headers[ trim( $parts[0] ) ] = trim( $parts[1] );
}
return $headers;
}
* @param string $header
* @param string $value
*/
public function set_header() {
}
}
}
endif;
if ( ! class_exists( 'NOOP_Translations', false ) ) :
* Provides the same interface as Translations, but doesn't do anything
*/
#[AllowDynamicProperties]
class NOOP_Translations {
public $entries = array();
public $headers = array();
public function add_entry() {
}
* @param string $header
* @param string $value
*/
public function set_header() {
}
* @param array $headers
*/
public function set_headers() {
}
* @param string $header
* @return false
*/
public function get_header() {
}
* @param Translation_Entry $entry
* @return false
*/
public function translate_entry() {
}
* @param string $singular
* @param string $context
*/
public function translate() {
}
* @param int $count
* @return bool
*/
public function select_plural_form() {
}
* @return int
*/
public function get_plural_forms_count() {
}
* @param string $singular
* @param string $plural
* @param int    $count
* @param string $context
*/
public function translate_plural() {
}
* @param object $other
*/
public function merge_with() {
}
}
endif;