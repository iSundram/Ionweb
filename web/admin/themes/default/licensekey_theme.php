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

function licensekey_theme(){

global $theme, $globals, $user, $langs, $skins, $error, $done;

softheader(__('Admin Panel'));			
error_handle($error);

	echo '
<div class="soft-smbox p-3 col-12 col-lg-10 col-md-8 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-id-card fa-lg me-2"></i>'.__('Licensing').'
	</div>
</div>
<form accept-charset="'.$globals['charset'].'" name="licensekey" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)">
<div class="soft-smbox p-3 col-12 col-lg-10 col-md-8 mx-auto mt-4">
	<div class="sai_form">
		<div class="row">
			<div class="col-12 col-md-5">
				<label for="mail" class="sai_head d-block">'.__('License Key').'
					<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('Enter your License Key here.').'"></i>
				</label>
			</div>
			<div class="col-12 col-md-7">
				<input class="form-control mb-3" type="text" name="enter_license" id="enter_license" size="30" value="'.POSTval('enter_license').'" autocomplete=off /> 
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-5">
				<label for="mail" class="sai_head d-block">'.__('Email Address').'
					<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('Enter your valid Email Address here.').'"></i>
				</label>
			</div>
			<div class="col-12 col-md-7">
				<input class="form-control" type="text" name="enter_email" id="enter_email" size="30" value="'.POSTval('enter_email').'" autocomplete=on /> 
			</div>
		</div>
		<div class="text-center my-3">
			<input type="submit" name="submit_license" value="'.__('Submit').'" class="btn btn-primary" />
		</div>
	</div>
</div>
</form>';

	softfooter();
}

