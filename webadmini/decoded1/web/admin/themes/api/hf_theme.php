<?php

//////////////////////////////////////////////////////////////
//===========================================================
// WEBUZO CONTROL PANEL
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit
// Date:       10th Jan 2009
// Time:       21:00 hrs
// Site:       https://webuzo.com/ (WEBUZO)
// ----------------------------------------------------------
// Please Read the Terms of Use at https://webuzo.com/terms
// ----------------------------------------------------------
//===========================================================
// (c) Softaculous Ltd.
//===========================================================
//////////////////////////////////////////////////////////////

if(!defined('WEBUZO')){
	die('Hacking Attempt');
}

function softheader($title = '', $leftbody = true){

global $theme, $globals, $kernel, $user, $l, $error;
	
	$GLOBALS['_api']['title'] = ((empty($title)) ? $globals['sn'] : $title);
	
}


function softfooter(){

global $theme, $globals, $kernel, $user, $l, $error, $end_time, $start_time;

	$GLOBALS['_api']['timenow'] = time();
	
	if(!empty($globals['showntimetaken'])){	
		$GLOBALS['_api']['time_taken'] = substr($end_time-$start_time,0,5);	
	}
	
	// Pagination in API
	if(!empty($globals['num_res'])){		
		$GLOBALS['_api']['num_res'] = $globals['num_res'];
		$GLOBALS['_api']['reslen'] = $globals['reslen'];	
		$GLOBALS['_api']['cur_page'] = $globals['cur_page'];
	}
	
	// Send the debug info - Removed for the time being as it is visible to the API User and can leak commands !
	//$GLOBALS['_api']['logr'] = $GLOBALS['logr'];

	//r_print($GLOBALS['_api']);
	
	// Remove the db passwords and admin pass
	if(!empty($globals['hide_api_pass'])){
	}

	// Return Serialize
	if($_GET['api'] == 'serialize'){
		
		echo serialize($GLOBALS['_api']);
	
	// Return JSON String
	}elseif($_GET['api'] == 'json'){
		
		header('Content-Type: application/json');
		$json = json_encode($GLOBALS['_api'], JSON_PARTIAL_OUTPUT_ON_ERROR);
	    
		if ($json === false) {
			echo json_last_error_msg();
		} else {
			echo $json;
		}
	
	// Return XML by default
	}else{
		
		echo ArrayToXML::toXML($GLOBALS['_api'], 'xml');
		
	}

}


function error_handle($error, $table_width = '100%', $center = false, $ret = false){

global $l;
	
	$str = '';
	
	//on error call the form
	if(!empty($error)){
		
		$error = apply_filters('error_handle', $error);
		
		$GLOBALS['_api']['error'] = $error;
		
	}

}


//This will just echo that everything went fine
function success_message($message, $table_width = '100%', $center = false){

global $l;

	//on error call the form
	if(!empty($message)){
		
		$GLOBALS['_api']['message'] = $message;
		
	}

}


function majorerror($title, $text, $heading = ''){

global $theme, $globals, $user, $l, $error;

softheader(((empty($title)) ? $l['fatal_error'] : $title), false);

$GLOBALS['_api']['fatal_error_heading'] = $heading;

$GLOBALS['_api']['fatal_error_text'] = $text;

// Also fill up error - this is for api
$error['fatal_error_text'] = $text;
error_handle($error);

softfooter();

//We must return
return true;

}

function message($title, $heading = '', $icon, $text){

global $theme, $globals, $user, $l;

softheader(((empty($title)) ? $l['soft_message'] : $title), false);

$GLOBALS['_api']['message_heading'] = $heading;

$GLOBALS['_api']['message_text'] = $text;

softfooter();

//We must return
return true;

}

