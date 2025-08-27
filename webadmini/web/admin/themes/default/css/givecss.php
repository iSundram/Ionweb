<?php

//////////////////////////////////////////////////////////////
//===========================================================
// givejs.php
//===========================================================
// PAGELAYER
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit Gupta
// Date:	   23rd Jan 2017
// Time:	   23:00 hrs
// Site:	   http://pagelayer.com/wordpress (PAGELAYER)
// ----------------------------------------------------------
// Please Read the Terms of use at http://pagelayer.com/tos
// ----------------------------------------------------------
//===========================================================
// (c)Pagelayer Team
//===========================================================
//////////////////////////////////////////////////////////////

if(!empty($_REQUEST['test'])){
	echo 1;
	die();
}

// Read the file
$data = '';
$self_path = dirname(__FILE__);

include_once(dirname(dirname(dirname(dirname(dirname($self_path))))).'/universal.php');

if(empty($globals['dev'])){
	die('// Dev mode is off !');
}

// What files to give		
$give = @$_REQUEST['files'];

if(!empty($give)){
	
	$give = explode(',', $give);
	
	// Check all files are in the supported list
	foreach($give as $file){
		
		if(preg_match('/\.\./is', $file)){
			continue;
		}
		
		if(preg_match('/\//is', $file)){
			$file = dirname($self_path).'/'.$file;
		}else{
			$file = $self_path.'/'.$file;
		}
		
		$final[md5($file)] = $file;
	}
	
}

// Give all
if(empty($final)){
	die('// No files were given !');
}

foreach($final as $k => $v){
	//echo $k.'<br>';
	$data .= file_get_contents($v)."\n\n";
}

// Write if we are front-end only then
if(!empty($_REQUEST['target'])){
	$ret = file_put_contents($self_path.'/'.$_REQUEST['target'], $data);
	
	if($ret === false || !file_exists($self_path.'/'.$_REQUEST['target'])){
		echo 'Could not write the file';
		die();
	}
}

// We are zipping if possible
if(function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')){
	ob_start('ob_gzhandler');
}

// Type CSS
header("Content-type: text/css; charset: UTF-8");

// Set a zero Mtime
$filetime = filemtime($self_path.'/style.css');

// Cache Control
header("Cache-Control: must-revalidate");

// Checking if the client is validating his cache and if it is current.
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $filetime)) {
	
	// Client's cache IS current, so we just respond '304 Not Modified'.
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $filetime).' GMT', true, 304);
	
	return;
	
}else{
	
	// Image not cached or cache outdated, we respond '200 OK' and output the image.
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $filetime).' GMT', true, 200);
	
}

echo $data;


