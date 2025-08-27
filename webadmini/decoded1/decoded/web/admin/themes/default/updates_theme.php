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

function updates_theme(){

global $theme, $globals, $user, $error, $done, $info, $report;

	softheader(__('Update Center'));

	echo '
<div class="soft-smbox p-3 col-12 col-md-10 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-xl fa-sync-alt me-1" aria-hidden="true"></i>&nbsp;'.__('Update $0', [APP]).'
	</div>
</div>

<div class="soft-smbox p-3 col-12 col-md-10 mx-auto mt-4">
	<div class="sai_form">';

	error_handle($error);

	if(!empty($report)){
		echo '
		<div class="table-responsive">
			<table width="100%" cellpadding="1" cellspacing="0" border="0" class="table table-striped">
				<tr>
					<td colspan="2" class="sai_head">'.__('Update Logs').'</td>
				</tr>
				<tr>
					<td valign="top">'.implode('<br />', $report['log']).'</td>
					<td width="20%" style="vertical-align:middle;"><img src="'.$theme['images'].'admin/'.(empty($report['status']) ? 'softerror.gif' : 'softok.gif').'" /></td>
				</tr>
			</table>
		</div>';
	}

	$curr_version = (!empty($done) ? $report['version'] : $globals['version']);
	$latest_version = (empty($info['version']) ? __('Could not connect to $0', [APP]) : $info['version']);

	echo '
		<form accept-charset="'.$globals['charset'].'" name="updatesoftaculous" method="post" action="" onsubmit="return submitit(this)">
		<label class="sai_head d-block">
			'.__('Current Version').': 
			<span class="ms-1">'.$curr_version.'</span>
		</label>
		<label class="sai_head">'.__('Latest Version').': '.($curr_version != $latest_version ? '
			<span class="ms-1" style="color:#FF0033;">' : '<span class="ms-1">').$latest_version.'</span>
		</label>
			
		<div class="sai_bboxtxt mt-3" style="color:#767676;">'.$info['message'].'</div>

		'.($curr_version != $latest_version ? '
			<div class="text-center">
				<input type="submit" name="update" value="'.__('Update $0', [APP]).'" '.(empty($info['link']) ? 'disabled="disabled"' : '').' class="btn btn-primary" />
			</div>' : '').'
		'.csrf_display().'
		
		</form>
	</div>
</div>';

	softfooter();

}

