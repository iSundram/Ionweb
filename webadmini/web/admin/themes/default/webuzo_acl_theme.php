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

function webuzo_acl_theme(){
	
global $theme, $globals, $error, $done, $softpanel;

	softheader(__('Webuzo ACL'));
	
	error_handle($error);

	echo '
<div class="soft-smbox col-12 col-md-8 mx-auto p-3">
	<div class="sai_main_head">
		<i class="fas fa-users-cog me-2"></i>'.__('Webuzo ACL').'
	</div>
</div>

<form accept-charset="'.$globals['charset'].'" action="" method="post" name="changepass" id="editform" class="form-horizontal" onsubmit="return submitit(this)">
<div class="soft-smbox col-12 col-md-8 mx-auto p-3 mt-4">
	<div class="sai_form">
	<div class="row">
		<div class="col-12 col-md-6 mb-3">
			<label for="mail" class="sai_head">
				<input type="checkbox" name="disable_domainadd" '.POSTchecked('disable_domainadd', ($globals['DISABLE_DOMAINADD'])).' />
				'.__('Disable addition of new Domains').'
				<span class="sai_exp">'.__('Disable creation of any new domains from the enduser panel').'"</span>
			</label>
		</div>
					
		<div class="col-12 col-md-6 mb-3">
			<label for="mail" class="sai_head">
				<input type="checkbox" name="disable_email" '.POSTchecked('disable_email', ($globals['DISABLE_EMAIL'])).' />
				'.__('Disable Emails').'
				<span class="sai_exp">'.__('Disable Email and all related activities').'</span>
			</label>
		</div>
		<div class="col-12 col-md-6 mb-3">
			<label for="mail" class="sai_head">
				<input type="checkbox" name="disable_emailadd" '.POSTchecked('disable_emailadd', ($globals['DISABLE_EMAILADD'])).' />
				'.__('Disbale addition of mail accounts').'
				<span class="sai_exp">'.__('Disable creation of any new mail accounts from the enduser panel').'</span>
			</label>
		</div>
		<div class="col-12 col-md-6 mb-3">
			<label for="mail" class="sai_head">
				<input type="checkbox" name="disable_ssh" '.POSTchecked('disable_ssh', ($globals['DISABLE_SSH'])).' />
				'.__('Disable SSH options').'
				<span class="sai_exp">'.__('If enabled, user will not be able to access "SSH Access" page in the enduser panel').'</span>
			</label>
		</div>
		<div class="col-12 mb-3">
			<label for="mail" class="sai_head">
				<input type="checkbox" name="disable_sysapps" '.POSTchecked('disable_sysapps', ($globals['DISABLE_SYSAPPS'])).' />
				'.__('Disable System Applications Installations / Configuration').'
				<span class="sai_exp">'.__( 'Disable installations, updation and removal of system applications from the Webuzo Enduser Panel. Also, user will not be able to edit configurations.').'</span>
			</label>
		</div>
		<div class="col-12 col-md-6 mb-3">
			<label for="mail" class="sai_head">'.__('Max. limit of Addon Domain creations').'
				<span class="sai_exp">'.__('Enter the number of Addon domain creations allowed. For unlimited creation leave blank.').'</span>
			</label>
			<input type="text" name="disable_addon" class="form-control" value="'.POSTval('disable_addon', $globals['DISABLE_ADDON']).'" />
		</div>
		<div class="col-12 text-center">
			<span class="sai_exp2">'.__('$0 Note : $1 On enabling this option only $0 root $1 user will be able to access the Webuzo Admin Panel', ['<strong>', '</strong>']).'</span>
		</div>
	</div>
</div>
<div class="text-center my-3">
	<input type="submit" value="'.__('Save Changes').'" name="savechanges" class="btn btn-primary" />
</div>
</form>';

	softfooter();

}

