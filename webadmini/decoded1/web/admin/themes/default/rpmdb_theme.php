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

function rpmdb_theme(){

global $theme, $globals, $user, $langs, $error, $done, $WE, $sys_pear, $installed_php;

	softheader(__('Rebuild RPM Database'));
	
	echo '
<div class="soft-smbox p-3 col-12">
	<div class="sai_main_head">
		<i class="fas fa-database fa-xl me-1"></i> '.__('Rebuild RPM Database').'
	</div>
</div>
<div class="soft-smbox p-4 col-12 mt-4">
	<div class="section">
		<p>'.__('This will attempt to rebuild your rpm database if it has corrupted. This procedure can take few minutes depending on the resources available on this server.').'</p>
    </div>
	
	<div class="section">
		<div class="row">
			<div class="col-12 col-lg-4">
				<form action="" method="POST" name="rpmdbrebuild_form" id="rpmdbrebuild_form" class="form-horizontal" onsubmit="return submitit(this)">
					<div class="input-group mb-3">
						<input type="hidden" name="action" id="action" value="rebuild">
					    <input class="btn btn-primary rounded" type="submit" name="submit" id="rebuild" value="'.__('Rebuild').'">
					</div>
				</form>
			</div>
		</div>
    </div>
</div>';
	
	softfooter();

}