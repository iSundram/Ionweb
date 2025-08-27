<?php
* This class describes elFinder plugin window remove tail dots.
* This plugin is automatically loaded on the Windows server
* and enabled in the LocalFileSystem driver.
*/
class elFinderPluginWinRemoveTailDots extends elFinderPlugin
{
private $replaced = array();
private $keyMap = array(
'ls' => 'intersect',
'upload' => 'renames',
'mkdir' => array('name', 'dirs')
);
public function __construct() {
}
public function cmdPreprocess() {
}
$this->replaced[$cmd] = array();
$key = (isset($this->keyMap[$cmd])) ? $this->keyMap[$cmd] : 'name';
if (is_array($key)) {
$keys = $key;
} else {
$keys = array($key);
}
foreach ($keys as $key) {
if (isset($args[$key])) {
if (is_array($args[$key])) {
foreach ($args[$key] as $i => $name) {
if ($cmd === 'mkdir' && $key === 'dirs') {
$name = '/' . ltrim($name, '/');
$_names = explode('/', $name);
$_res = array();
foreach ($_names as $_name) {
$_res[] = $this->normalize($_name, $opts);
}
$this->replaced[$cmd][$name] = $args[$key][$i] = join('/', $_res);
} else {
$this->replaced[$cmd][$name] = $args[$key][$i] = $this->normalize($name, $opts);
}
}
} else if ($args[$key] !== '') {
$name = $args[$key];
$this->replaced[$cmd][$name] = $args[$key] = $this->normalize($name, $opts);
}
}
}
if ($cmd === 'ls' || $cmd === 'mkdir') {
if (!empty($this->replaced[$cmd])) {
$elfinder->unbind($cmd, array($this, 'cmdPostprocess'));
$elfinder->bind($cmd, array($this, 'cmdPostprocess'));
}
}
return true;
}
public function cmdPostprocess() {
} else {
$result['list'][$hash] = $keys;
}
}
}
}
} else if ($cmd === 'mkdir') {
if (!empty($result['hashes']) && !empty($this->replaced['mkdir'])) {
foreach ($result['hashes'] as $name => $hash) {
if ($keys = array_keys($this->replaced['mkdir'], $name)) {
$result['hashes'][$keys[0]] = $hash;
}
}
}
}
}
public function onUpLoadPreSave() {
}
$name = $this->normalize($name, $opts);
return true;
}
protected function normalize() {
}
} // END class elFinderPluginWinRemoveTailDots