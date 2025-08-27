<?php
define('ELFINDER_PHP_ROOT_PATH', dirname(__FILE__));
function elFinderAutoloader() {
}
$prefix = substr($name, 0, 14);
if (substr($prefix, 0, 8) === 'elFinder') {
if ($prefix === 'elFinderVolume') {
$file = ELFINDER_PHP_ROOT_PATH . '/' . $name . '.class.php';
return (is_file($file) && include_once($file));
} else if ($prefix === 'elFinderPlugin') {
$file = ELFINDER_PHP_ROOT_PATH . '/plugins/' . substr($name, 14) . '/plugin.php';
return (is_file($file) && include_once($file));
} else if ($prefix === 'elFinderEditor') {
$file = ELFINDER_PHP_ROOT_PATH . '/editors/' . substr($name, 14) . '/editor.php';
return (is_file($file) && include_once($file));
}
}
return false;
}
if (version_compare(PHP_VERSION, '5.3', '<')) {
spl_autoload_register('elFinderAutoloader');
} else {
spl_autoload_register('elFinderAutoloader', true, true);
}