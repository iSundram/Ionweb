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

function email_reseller_theme(){
	
global $theme, $globals, $error, $done, $softpanel;

	softheader(__('Email Accounts'));
	error_handle($error);

echo '
<div class="soft-smbox p-3 col-12 col-md-8 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-envelope fa-xl me-2"></i>'.__('Send Email To All Resellers').'
	</div>
</div>
<div class="soft-smbox p-4 col-12 col-md-8 mx-auto mt-4">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="changepass" id="editform" class="form-horizontal" onsubmit="return submitit(this)">';

	echo '
	<div class="row">
		<div class="col-12 mb-3">
			<label for="mail" class="sai_head">'.__('Sender Email').'</label>
			<input type="email" required name="from_email" class="form-control" value="'.POSTval('from_email', $globals['from_email']).'" />
		</div>
		<div class="col-12 mb-3">
			<label for="mail" class="sai_head">'.__('Subject').'</label><br />
			<input type="text" required name="subject" class="form-control subject" value="'.POSTval('subject').'" />
		</div>
		<div class="col-12 mb-3">
			<label for="mail" class="sai_head">'.__('Message Body').'</label><br />
			<textarea required class="form-control message_body" name="message_body" rows="10">'.POSTval('message_body').'</textarea>
		</div>
	</div>
	<div class="text-center">
		<input type="submit" value="'.__('Send Email').'" name="savechanges" class="btn btn-primary" />
	</div>
</form>
</div>';

	softfooter();

}

