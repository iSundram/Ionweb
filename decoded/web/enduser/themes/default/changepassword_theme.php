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

function changepassword_theme(){
	
global $theme, $globals, $kernel, $user, $error, $done, $onboot, $user_name, $iapps, $isql, $softpanel, $WE;
	
	softheader(__('Change Password'));	
	echo '
<div class="card soft-card p-3 col-12 col-md-8 col-lg-5 mx-auto">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'changepassword.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Change Password').'</h5>
	</div>
</div>
<div class="card soft-card p-4 col-12 col-md-8 col-lg-5 mx-auto mt-4">
	<form accept-charset="'.$globals['charset'].'" action="" method="post" name="changepass" id="editform" class="form-horizontal" onsubmit="return submitit(this)" data-doneredirect="'.$globals['enduser_url'].'act=login">
		<div>
			<label class="sai_head" for="newpass">'.__('New Password').'</label>
			<span class="sai_exp">'.__('Password strength must be greater than or equal to $0', [pass_score_val('user')]).'</span>
		</div>
		<div class="input-group password-field">
			<input type="password" name="newpass" id="newpass" class="form-control" onkeyup="check_pass_strength($(\'#newpass\'), $(\'#pass-prog-bar\'))" value="" />
			<span class="input-group-text" onclick="change_image(this, \'newpass\')">
				<i class="fas fa-eye"></i>
			</span>
			<a class="random-pass" href="javascript: void(0);" onclick="rand_val=randstr(10, '.pass_score_val('user').');$_(\'newpass\').value=rand_val;$_(\'conf\').value=rand_val;check_pass_strength($(\'#newpass\'), $(\'#pass-prog-bar\'));return false;" title="'.__('Generate a Random Password').'">
				<i class="fas fa-key"></i>
			</a>
		</div>
		<div class="progress pass-progress mb-3">
			<div class="progress-bar bg-danger" id="pass-prog-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
				<span>0</span>
			</div>
		</div>
		<label class="sai_head" for="conf">'.__('Retype New Password').'</label>
		<input type="password" name="conf" id="conf" class="form-control mb-3" value="" />
		<center>
			<input type="submit" class="flat-butt me-2" value="'.__('Change Password').'" name="changepass" id="submitpass"/>
			<img id="createimg" src="'.$theme['images'].'progress.gif" style="display:none">
		</center>
	</form>
</div>';

	softfooter();
	
}


