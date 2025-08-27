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

function terminal_theme(){

global $user, $globals, $theme, $error, $done;
	
	softheader(__('Terminal'));
	
	echo '<div class="sai_main_head text-center">
		<i class="fas fa-terminal me-1"></i>'.__('Terminal').'<br>
	</div>';
	
	error_handle($error, '100%');
	
	/*if(!empty($done)){
	
		$url = 'http'.(!empty($_SERVER['HTTPS']) ? 's' : '').'://'.$done['user'].':'.$done['password'].'@'.str_replace('//', '/', $_SERVER['HTTP_HOST'].'/tty/');

		echo '<iframe style="width:100%; height: 100%; min-height: 500px" src="'.$url.'"></iframe>';
	
	}*/
	
	softfooter();

}
