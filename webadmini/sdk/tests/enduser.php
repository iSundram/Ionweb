<?php

include_once(dirname(__DIR__).'/webuzo_sdk_v2.php');

unset($argv[0]);

if(empty($argv[1])){
	echo 'Please provide some input.'."\n";
	exit(1);
}

// Get the parameters for data
foreach($argv as $k => $v){
	$v = ltrim($v, '-');
	$temp = explode('=', $v);
	$args[trim($temp[0])] = trim($temp[1]);
	if(empty($first_arg)){
		$first_arg = trim($temp[0]);
	}
}

$webuzo = new Webuzo_Admin_SDK($args['user'], $args['password'], $args['host']);