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

if(version_compare(PHP_VERSION, '7.1.0', '>=') && file_exists(dirname(__FILE__).'/includes71/'.basename(__FILE__))){
	include_once(dirname(__FILE__).'/includes71/'.basename(__FILE__));
}elseif(version_compare(PHP_VERSION, '5.6.0', '>=') && file_exists(dirname(__FILE__).'/includes56/'.basename(__FILE__))){
	include_once(dirname(__FILE__).'/includes56/'.basename(__FILE__));
}elseif(version_compare(PHP_VERSION, '5.3.0', '>=') && file_exists(dirname(__FILE__).'/includes53/'.basename(__FILE__))){
	include_once(dirname(__FILE__).'/includes53/'.basename(__FILE__));
}elseif(file_exists(dirname(__FILE__).'/includes52/'.basename(__FILE__))){
	include_once(dirname(__FILE__).'/includes52/'.basename(__FILE__));
}else{
	include_once(dirname(__FILE__).'/includes/'.basename(__FILE__));
}