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

function update_settings_theme(){

global $theme, $globals, $user, $langs, $skins, $error, $done, $softpanel, $protocols, $timezone_list;

	softheader(__('Update Settings Center'));

	echo '	
<div id="stooltip" style="display:none; position:absolute; top: 0px; left: 0px; border: 1px solid #CCC; padding: 8px; background: #FFF; z-index:1000;"></div>

<form accept-charset="'.$globals['charset'].'" name="editsettings" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)">
<div class="soft-smbox p-3 col-12 col-md-10 mx-auto mb-4">
	<div class="sai_main_head">
		<i class="fas fa-sync-alt me-2"></i>'.__('Update Settings').'
	</div>
</div>

<div class="soft-smbox p-3 col-12 col-md-10 mx-auto">
	<div id="stooltip" style="display:none; position:absolute; top: 0px; left: 0px; border: 1px solid #CCC; padding: 8px; background: #FFF; z-index:1000;"></div>';

	error_handle($error);
	
	echo '
	<div class="sai_form">
		<div class="row mb-4">
			<div class="col-12 col-md-6">
				<label class="sai_head">'.__('Auto Update').' '.APP.'
					<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('If enabled').' '.APP.' '.__('will automatically update itself to the latest version. $0 The $1 Stable $2 branch is launched after the Release Candidate has been thorughly tested. $0 The $1 Release Candidate $2 branch contains the latest version and features.', ['<br />', '<b>', '</b>']).'"></i>
				</label>
				<label class="d-block ms-2">
					<input type="radio" name="update" value="0" '.POSTradio('update', 0, $globals['update']).' class="me-1"/> '.__('Never Update').'
				</label>
				<label class="d-block ms-2">
					<input type="radio" name="update" value="1" '.POSTradio('update', 1, $globals['update']).' class="me-1"/> '.__('Stable (Recommended)').'
				</label>
				<label class="d-block ms-2">
					<input type="radio" name="update" value="2" '.POSTradio('update', 2, $globals['update']).' class="me-1"/> '.__('Release Candidate').'
				</label>
			</div>
			<div class="col-12 col-md-6">
				<label class="sai_head d-block mb-3">
					<input type="checkbox" class="me-1" name="no_auto_update_system" '.POSTchecked('no_auto_update_system', $globals['no_auto_update_system']).' />
					'.__('Disable Auto Update OS').'
					<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('If disabled, the Operating System will not be updated using Yum or Apt-get').'"></i>
				</label>
				<label class="sai_head d-block mb-3">
					<input type="checkbox" class="me-1" name="email_update" '.POSTchecked('email_update', $globals['email_update']).' />
					'.__('Notify Updates via Email').'
					<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('Will send emails when').' '.APP.' '.__('upgrades are available or Auto Upgrade is performed').'"></i>
				</label>
				<label class="sai_head d-block mb-3">
					<input type="checkbox" class="me-1" name="email_update_apps" '.POSTchecked('email_update_apps', $globals['email_update_apps']).' />
					'.__('Notify Application Updates').'
					<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('If enabled, emails will be sent when updates for installed Application(s) are available').'"></i>
				</label>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-6">
				<label for="cron_time" class="sai_head">'.__('Updates Cron Job').'
					<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('The cron job time to check for available updates. Don\'t change this if you are unaware of what cron jobs are').'"></i>
				</label>
				<input type="text" name="cron_time" id="cron_time" class="form-control" size="30" value="'.aPOSTval('cron_time', $globals['cron_time']).'" />
			</div>
			<div class="col-12 col-md-6">
				<label for="php_bin" class="sai_head">'.__('PHP Binary').'
					<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('This is the binary that will be used for the CRON Job and also other purposes. If empty then $0 /usr/bin/php $1 will be used. Please note that the $0 PHP binary should be a CLI binary $1.', ['<b>', '</b>']).'"></i>
				</label>
				<input type="text" class="form-control" name="php_bin" size="30" value="'.aPOSTval('php_bin', htmlizer($globals['php_bin'])).'" />
			</div>
		</div>
		<div class="text-center mt-4 mb-3">
			<input type="submit" class="btn btn-primary" name="editsettings" value="'.__('Edit Settings').'"/>
		</div>
	</div>
	
'.csrf_display().'
</div>
</form>
';

	softfooter();
}

