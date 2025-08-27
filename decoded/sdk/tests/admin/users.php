<?php
include_once(dirname(__DIR__).'/admin.php');
$res = $webuzo->list_users();
if(!empty($res['error'])){
echo 'Error while performing action : ';
print_r($res['error']);
exit(1);
}else{
echo 'Domain Alias List: '.PHP_EOL;
print_r($res);
}
exit(0);