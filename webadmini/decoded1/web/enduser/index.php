<?php

/**
 * It all starts from the index.php
 */

//////////////////////////////////////////////////////////////
//===========================================================
// index.php
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
 
@define('SOFTACULOUS', 1);

// Set custom header
// NOTE : Never ever remove this as its checked by our Service Check script
header('Server:Webuzo');

// Enduser Panel
include_once('../../includes/enduser.php');

