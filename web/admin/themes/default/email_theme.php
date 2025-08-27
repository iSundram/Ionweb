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

function email_theme(){

global $theme, $globals, $user, $langs, $skins, $error, $done;

	softheader(__('Email Settings'));

	error_handle($error);

	echo '
<div class="soft-smbox col-12 col-md-11 p-3 mx-auto">
	<div class="sai_main_head">
		<i class="fa fa-envelope fa-xl me-2"></i>'.__('Email Settings').'
	</div>
</div>
<form accept-charset="'.$globals['charset'].'" name="editemailsettings" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)">
<div class="soft-smbox col-12 col-md-11 p-4 mx-auto mt-4">
	<div class="sai_form">';

	if(!empty($globals['enc_mail_pass']) && !empty($globals['mail_pass'])){
		$globals['mail_pass'] = smart_pass_decrypt($globals['mail_pass'], $globals['enc_mail_pass']);
	}
		
	echo '
	<div class="row">
		<div class="col-12 col-md-6 mb-3">
			<label for="mail" class="sai_head d-block">'.__('Mailing Method').'
				<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('Send mail using PHP mail() function or your SMTP server').'"></i>
			</label>
			<select name="mail" id="mail" class="form-select">
				<option value="1" '.(isset($_POST['mail']) && $_POST['mail'] == '1' ? 'selected="selected"' : ($globals['mail'] == 1 ? 'selected="selected"' : '')).'>PHP Mail</option>
				<option value="0" '.(isset($_POST['mail']) && $_POST['mail'] == '0' ? 'selected="selected"' : ($globals['mail'] == 0 ? 'selected="selected"' : '')).'>SMTP</option>
			</select>
		</div>
		
		 <div class="smtp_field col-12 col-md-6 mb-3">
			<label for="mail_authtype" class="sai_head d-block">'.__('SMTP Authtype').'
				<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('Choose the Authtype for the emails sent using SMTP').'"></i>
			</label>
			<select name="mail_authtype" id="mail_authtype" class="form-select">
				<option value="0" '.(isset($_POST['mail_authtype']) && $_POST['mail_authtype'] == '0' ? 'selected="selected"' : ($globals['mail_authtype'] == 0 ? 'selected="selected"' : '')).'>Default</option>
				<option value="CRAM-MD5" '.(isset($_POST['mail_authtype']) && $_POST['mail_authtype'] == 'CRAM-MD5' ? 'selected="selected"' : ($globals['mail_authtype'] == 'CRAM-MD5' ? 'selected="selected"' : '')).'>CRAM-MD5</option>
				<option value="noauth" '.(isset($_POST['mail_authtype']) && $_POST['mail_authtype'] == 'noauth' ? 'selected="selected"' : ($globals['mail_authtype'] == 'noauth' ? 'selected="selected"' : '')).'>No Authentication</option>
			</select>
		</div>
		<div class="smtp_field col-12 col-md-6 mb-3">
			<label for="mail_server" class="sai_head">'.__('SMTP Server').'</label>
			<input type="text" name="mail_server" class="form-control" id="mail_server" size="30" value="'.aPOSTval('mail_server', $globals['mail_server']).'" />
		</div>
		<div class="smtp_field col-12 col-md-6 mb-3">
			<label for="mail_port" class="sai_head">'.__('SMTP Port').'</label>
			<input type="text" name="mail_port" class="form-control" id="mail_port" size="30" value="'.aPOSTval('mail_port', $globals['mail_port']).'" />
		</div>
		<div class="smtp_field col-12 col-md-6 mb-3">
			<label for="mail_server" class="sai_head">'.__('SMTP Username').'</label>
			<input type="text" name="mail_user" class="form-control mb-3" id="mail_user" size="30" value="'.aPOSTval('mail_user', $globals['mail_user']).'" />
			<label for="mail_pass" class="sai_head">'.__('SMTP Password').'</label>
			<input type="password" name="mail_pass" class="form-control" id="mail_pass" size="30" value="'.aPOSTval('mail_pass', $globals['mail_pass']).'" />
			<input id="toggle_pass_mail_pass" type="checkbox" style="display:none;" onclick="toggle_pass(\'show_hide_mail_pass\', \'mail_pass\');"/><label for="toggle_pass_mail_pass" style="margin-top:6px; cursor:pointer;"><span id="show_hide_mail_pass">'.__('Show').'</span></label>
		</div>
		<div class="col-12 col-md-6">
			<label for="mail_send" class="sai_head mb-3 d-block">
				<input type="checkbox" name="mail_send" id="mail_send" class="me-1" size="30" '.POSTchecked('mail_send').' />
				'.__('Send Test Email ?').'
			</label>
			<label for="enc_mail_pass" class="smtp_field sai_head mb-3 ">
				<input type="checkbox" class="me-1" name="enc_mail_pass" '.POSTchecked('enc_mail_pass', @$globals['enc_mail_pass']).' />
				'.__('Save SMTP Password in Encrypted format ?').'
			</label>
			<label class="sai_head">
				<input type="checkbox" class="me-1" name="off_email_link" '.POSTchecked('off_email_link', $globals['off_email_link']).' />
				'.__('Turn off all Emails sent to endusers').'
				<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('This will turn off the Email Link for the endusers.').'"></i>
			</label>
			<span class="sai_exp2"></span>
		</div>
	</div>
	<div class="text-center">
		'.csrf_display().'
		<input type="submit" name="editemailsettings" class="btn btn-primary" value="'.__('Edit Settings').'"/>
	</div>
</div>
</form>
<script>
$(document).ready(function () {
    updateSmtpFields();

    $("#mail").on("change", function () {
        updateSmtpFields();
    });

    function updateSmtpFields() {
        var method = '.$globals['mail'].';
        var val = $("#mail").val();

        $(".smtp_field").toggle(method !== "1" && val !== "1");
    }
});

</script>';
	softfooter();

}

