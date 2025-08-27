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

function rebranding_settings_theme(){

global $theme, $globals, $user, $langs, $skins, $error, $done, $softpanel, $protocols, $timezone_list;

	softheader(__('Rebranding Settings Center'));

	echo '
<div id="stooltip" style="display:none; position:absolute; top: 0px; left: 0px; border: 1px solid #CCC; padding: 8px; background: #FFF; z-index:1000;"></div>
<div class="soft-smbox p-3 col-12 col-md-8 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-paint-roller fa-xl me-2"></i>'.__('Rebranding Settings').'
	</div>
</div>
<div class="soft-smbox p-3 col-12 col-md-8 mx-auto mt-4">
	<form accept-charset="'.$globals['charset'].'" name="editconfigs" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)">
		<div id="stooltip" style="display:none; position:absolute; top: 0px; left: 0px; border: 1px solid #CCC; padding: 8px; background: #FFF; z-index:1000;"></div>';
		
	error_handle($error);
	
		echo '
		<div class="sai_form">
			<label for="sn" class="sai_head">'.__('Site Name').'
				<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('The name of the server or company using $0. It will appear in many pages of the $0 installer', [APP]).'"></i>
			</label>
			<input type="text" name="sn" id="sn" class="form-control mb-3" value="'.aPOSTval('sn', $globals['sn']).'" />
			
			<label for="logo_url" class="sai_head">'.__('Logo URL').'
				<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Recommended dimensions (400 x 140) If empty the $0 logo will be shown.', [APP]).'"></i>
			</label>			
			<input type="text" name="logo_url" class="form-control mb-3" id="logo_url" value="'.aPOSTval('logo_url', $globals['logo_url']).'" />
			
			<label for="dark_logo_url" class="sai_head">'.__('Dark Logo URL').'
				<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Recommended dimensions (400 x 140) If empty the $0 logo will be shown.', [APP]).'"></i>
			</label>			
			<input type="text" name="dark_logo_url" class="form-control" id="dark_logo_url" value="'.aPOSTval('dark_logo_url', $globals['dark_logo_url']).'" />
			<label class="sai_exp2 mb-3" id="c_type_exp"> <i> * '.__('Specifically for transparent background pages. For example: Login page').' </i></label></br>
			
			<label for="webmail_logo_url" class="sai_head">'.__('Webmail Logo URL').' ('.__('Optional').')'.'
				<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Recommended dimensions (400 x 140) If empty the logo entered in the above field will be shown.').'"></i>
			</label>			
			<input type="text" name="webmail_logo_url" class="form-control mb-3" id="webmail_logo_url" value="'.aPOSTval('webmail_logo_url', $globals['webmail_logo_url']).'" />
		
			<label for="favicon_logo" class="sai_head">'.__('Favicon logo URL').'
				<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('If empty the $0 favicon will be shown in Enduser Panel', [APP]).'"></i>
			</label>			
			<input type="text" name="favicon_logo" class="form-control mb-3" id="favicon_logo" value="'.aPOSTval('favicon_logo', $globals['favicon_logo']).'" />
			
			<label for="footer_link" class="sai_head">'.__('Footer Link').'
				<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('You can change the footer copyright here').'"></i>
			</label>			
			<input type="text" name="footer_link" class="form-control mb-3" id="footer_link" value="'.aPOSTval('footer_link', $globals['footer_link']).'" />
			
			<label for="webmail_name" class="sai_head">'.__('Webmail name').'
				<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('If empty then webmail name will be the Webuzo').'"></i>
			</label>			
			<input type="text" name="webmail_name" class="form-control mb-3" id="webmail_name" value="'.aPOSTval('webmail_name', $globals['webmail_name']).'" />
		
			<label for="webmail_support_link" class="sai_head">'.__('Webmail support url').'
				<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('If empty then webmail support url will be the Webuzo support').'"></i>
			</label>			
			<input type="text" name="webmail_support_link" class="form-control mb-3" id="webmail_support_link" value="'.aPOSTval('webmail_support_link', $globals['webmail_support_link']).'" />
		
			<div class="text-center">
				<input type="submit" class="btn btn-primary" name="editsettings" value="'.__('Edit Settings').'"/>
			</div>
		</div>';

		echo csrf_display().'
	</form>
</div>';

	softfooter();

}
