<?php

include_once(dirname(__DIR__).'/enduser.php');

$res = $webuzo->list_domains();

// Done/Error
if(!empty($res['error'])){
	echo 'Error while performing action : ';
	print_r($res['error']);
	exit(1);
}else{
	echo 'Domain Alias List: '.PHP_EOL;
	print_r($res);
}

// If successful
exit(0);