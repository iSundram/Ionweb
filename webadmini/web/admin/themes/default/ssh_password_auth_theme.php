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

function ssh_password_auth_theme(){

global $theme, $globals, $user, $langs, $error, $done;

	softheader(__('SSH Password Authorization'));

	error_handle($error);

	echo '
<div class="soft-smbox col-12 col-md-11 p-3 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-terminal fa-lg me-1"></i>'.__('SSH Password Authorization').'
	</div>
</div>
<div class="soft-smbox col-12 col-md-11 p-3 mx-auto mt-4">
<form accept-charset="'.$globals['charset'].'" name="action" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1>';
	if(!empty($globals['ssh_auth_enabled'])){	
		echo '	
	<div class="alert alert-info d-flex justify-content-center" role="alert">
		'.__('SSH Password Authorization is currently enabled').'
	</div>';
	}else{
		echo '	
	<div class="alert alert-danger d-flex justify-content-center" role="alert">
		'.__('SSH Password Authorization is currently disabled').'
	</div>';	
	}
	
	echo '
	<div class="text-center">
		<p class="sai_sub_head d-inline-block">'.__('We recommended you to disable SSH Password Authentication and use the SSH Key to SSH your server. You can generate the auth keys from the $0 Manage Root SSH Keys $1 wizard', ['<a href="'.$globals['index'].'act=manage_root_ssh_keys">', '</a>']).'</p>
	</div>
	<div class="sai_form">';	
		echo '
		<div class="text-center">
			<input type="hidden" name="action" value="'.((int)(!$globals['ssh_auth_enabled'])).'">
			<input type="submit" name="change" class="btn btn-primary" value="'.($globals['ssh_auth_enabled'] == 1 ? __('Disable') : __('Enable')).'"/>
		</div>
	</div>
</form>
</div>';

	softfooter();

}