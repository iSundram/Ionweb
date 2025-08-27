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

function skeleton_directory_theme(){
	
global $globals, $theme, $softpanel, $error, $skel;
	
	softheader(__('Skeleton Directory'));
	
	echo '
	<div class="soft-smbox p-3 col-12 col-md-12 mx-auto">
		<div  class="sai_main_head">
			<i class="fas fa-lg fa-skull"></i>
			<span>'.__('Skeleton Directory').'</span>
		</div>
	</div>
	<div class="soft-smbox p-3 col-12 col-md-12 mx-auto mt-4">
		<div class="row mb-3">
			<label font-size: 20px;"><b>'.$skel.'</b></label>
		</div>
		<p>'.__('The $0 directory contains files and directories that are automatically copied over to a new user home directory. For example if you place an index.html file in $0/public_html, and then setup a new account, that account will have a copy of your index.html in their public_html directory.', [$skel]).'</p>
	</div>';

	softfooter();

}
	