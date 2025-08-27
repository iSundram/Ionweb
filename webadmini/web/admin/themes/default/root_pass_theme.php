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

function root_pass_theme(){
	
global $theme, $globals, $kernel, $user, $error, $done, $onboot, $user_name, $iapps;
 
	softheader(__('Change Root Password'));
	error_handle($error);

	echo '
<div class="soft-smbox p-3 col-12 col-md-10 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-lock fa-xl me-2"></i>'.__('Change Root Password').'
	</div>
</div>

<div class="soft-smbox p-3 col-12 col-md-10 mx-auto mt-4">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="changepass" id="editform" class="form-horizontal" onsubmit="return submitit(this)">	
	<div class="sai_form">
		<div class="row">
			<div class="col-12 col-md-5">
				<label for="mail" class="sai_head">'.__('New Password').'</label>
				<span class="sai_exp">'.__('Password strength must be greater than or equal to $0', [pass_score_val('user')]).'</span>
			</div>
			<div class="col-12 col-md-7">
				<div class="input-group password-field">
					<input class="form-control " type="password" name="newpass" id="newpass" size="30" onkeyup="check_pass_strength($(\'#newpass\'), $(\'#pass-prog-bar\'))" value="" />
					<span class="input-group-text" onclick="change_image(this, \'newpass\')">
						<i class="fas fa-eye"></i>
					</span>
					<a href="javascript: void(0);" onclick="rand_val=randstr(10, '.pass_score_val('user').');$_(\'newpass\').value=rand_val;$_(\'conf\').value=rand_val;check_pass_strength($(\'#newpass\'), $(\'#pass-prog-bar\'), \''.$globals['password_strength'].'\');return false;" title="'.__('Generate a Random Password').'" class="random-pass">
						<i class="fas fa-key fa-lg"></i>
					</a>
				</div>
				<div class="progress pass-progress mb-2">
					<div class="progress-bar bg-danger" id="pass-prog-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
						<span>0</span>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-5">
				<label for="mail" class="sai_head">'.__('Retype New Password').'</label>
			</div>
			<div class="col-12 col-md-7 mb-3">
				<input class="form-control" type="password" name="conf" id="conf" size="30" value="" />
			</div>
		</div>
		<div class="text-center my-3">
			<input class="btn btn-primary" type="submit" value="'.__('Change Password').'" name="changepass" id="submitpass"/>
		</div>
	</div>	
</form>
</div>';
	softfooter();
}

