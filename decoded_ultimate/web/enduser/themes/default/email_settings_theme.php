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

function email_settings_theme(){

global $U, $globals, $theme, $softpanel, $iscripts, $catwise, $error, $done;

	softheader(__('Email Settings'));

	echo '
<div class="card soft-card p-4 col-12 mx-auto col-md-10 col-lg-6">
	<div class="sai_main_head mb-4">
		<i class="fas fa-envelope fa-2x me-2" style="color:#00A0D2;"></i>
		<h5 class="d-inline-block mt-0">'.__('Email Settings').'</h5>
	</div>
	<form accept-charset="'.$globals['charset'].'" name="editemailsettings" method="post" action="" id="editform" class="form-horizontal" onsubmit="return submitit(this);" data-donereload=1>
		<div class="row">
			<div class="col-sm-5">
				<label class="sai_head">'.__('Don\'t Send Backup Emails').'</label>
				<span class="sai_exp">'.__('Do not send any email when you do backups of your account').'</span>
			</div>
			<div class="col-sm-7">
				<input type="checkbox" id="backup_email" name="backup_email" '.POSTchecked('backup_email', $U['disable_backup_email']).' />
			</div>
		</div><br />
		<div class="row">
			<div class="col-sm-5">
				<label class="sai_head">'.__('Don\'t Send Restore Emails').'</label>
				<span class="sai_exp">'.__('Do not send any email when you restore the backups').'</span>
			</div>
			<div class="col-sm-7">
				<input type="checkbox" id="restore_email" name="restore_email" '.POSTchecked('restore_email', $U['disable_restore_email']).' />
			</div>
		</div><br>
		<p align="center">
			<input type="submit" name="editemailsettings" value="'.__('Edit Settings').'" class="flat-butt" />
		</p>
	</form>
</div><!--end of card class-->';

	softfooter();

}