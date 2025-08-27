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

function ssh_access_theme(){

	global $user, $globals, $theme, $softpanel, $catwise, $error;
	global $installations, $langs, $saved;

	softheader(__('SSH Access'));

	echo '
<div class="soft-smbox p-3 col-12 col-md-8 mx-auto">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'ssh_login.png" />
		'.__('SSH Access').'
	</div>
</div>
<div class="soft-smbox p-3 col-12 col-md-8 mx-auto mt-4">';
	
	if($globals['DISABLE_SSH']){
		echo '
	<div class="alert alert-danger text-center">
		'.__('You do not have permission to access this page').'
	</div>';
	
	}else{

		echo '
	<form accept-charset="'.$globals['charset'].'" name="editsettings" method="post" action="" class="form-horizontal" id="editform" onsubmit="return submitit(this)"><br />';
		error_handle($error, "100%");
		echo '
		<div class="row mb-3">
			<div class="col-8 col-md-6 col-lg-5">
				<label class="sai_head">'.__('Enable SSH/SFTP').'</label>
				<span class="sai_exp">'.__('Check if you want SSH/SFTP enabled').'</span>
			</div>
			<div class="col-4 col-md-6 col-lg-7">
				<input type="checkbox" name="sshon" id="sshon" '.POSTchecked('sshon', ($softpanel->getssh())).' ">
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-lg-5">
				<label class="sai_head">'.__('Change SSH Port').'</label>
				<span class="sai_exp">'.__('Set the desired SSH port on your Server').'</span>
			</div>
			<div class="col-12 col-lg-7">			
				<input type="text" class="form-control" name="ssh_port" id="ssh_port" value="'.POSTval('ssh_port', trim(($softpanel->getssh_port()))).'">
			</div>
		</div>
		<div class="text-center my-3">	
			<input type="submit" class="btn btn-primary" name="editsshsettings" value="'.__('Edit SSH/SFTP Settings').'" id="submitset"/>
		</div>
	</form>';
		}
		
	echo '
</div>';

	softfooter();
}

