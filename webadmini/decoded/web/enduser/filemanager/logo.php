<?php
error_reporting(0); // Set E_ALL for debuging

// Include our sessions file
include_once('/usr/local/webuzo/sdk/sessions.php');

$webuzo_sess = new Webuzo_Sessions();

$type = 'logo_url';
$default = 'themes/webuzo/images/header.png';
if(isset($_GET['fav'])){
    $type = 'favicon_logo';
    $default = '../themes/default/images/favicon.ico';
}

// Find logo path
$logo = !empty($webuzo_sess->globals[$type]) ? $webuzo_sess->globals[$type] : $default;

header("Location: ".$logo);
