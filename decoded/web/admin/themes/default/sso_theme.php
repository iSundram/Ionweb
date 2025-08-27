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

if(!defined('SOFTACULOUS')){
	die('Hacking Attempt');
}

function sso_theme(){

global $theme, $globals, $user, $langs, $error, $saved, $done, $filename, $iapps, $done;

	if(optREQ('loginAs') && !empty($done['loginAs'])){
		redirect($done['loginAs'], true, true);
		die();
	}
	
	
	softheader(__('Admin SSO'));
	
	error_handle($error);

	softfooter();
}

