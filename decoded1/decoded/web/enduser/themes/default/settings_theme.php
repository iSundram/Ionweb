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

function settings_theme(){

global $user, $globals, $theme, $softpanel, $WE, $catwise, $error;
global $installations, $langs, $saved, $deleted, $timezone_list, $email_saved, $email_sent;

softheader(__('User Settings'));

$data = $WE->user_prefs;

echo '
<div class="card soft-card p-4 col-12 col-md-10 col-lg-6 mx-auto">
	<div class="sai_main_head mb-4">
		<i class="fas fa-user-cog fa-2x webu_head_img me-2" style="color:#00A0D2;"></i>
		<h5 class="d-inline-block">'.__('User Settings').'</h5>
	</div>

	<form accept-charset="'.$globals['charset'].'" name="editsettings" method="post" action="" id="editsettings" class="form-horizontal">';
	error_handle($error, '100%');
	
	if(!empty($email_saved)){
		echo '<div class="alert alert-success" style="padding:10px;font-size:15px;"><center><img src="'.$theme['images'].'message_success.gif">&nbsp;&nbsp;'.__('Your mail is saved successfully').'</div></center>';
	}
	
	if(!empty($saved)){
		
		if(!empty($email_sent)){
			echo '<div class="alert alert-success" style="padding:10px;font-size:15px;"><center><img src="'.$theme['images'].'message_success.gif">&nbsp;&nbsp;'.__('We have sent you the verification mail. Please check your mail and verify.').'</div></center>';
		}else{
			echo '<div class="alert alert-success" style="padding:10px;font-size:15px;"><center><img src="'.$theme['images'].'message_success.gif">&nbsp;&nbsp;'.__('Your settings were saved successfully').'</div></center>';
		}
	}
		
	if(!empty($deleted)){
		echo '<div class="alert alert-warning" style="padding:10px;font-size:15px;"><center><a href="#close" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.__('Backup location was deleted successfully').'</center></div>';
	}
	
	echo '
		<label  for="email" class="form-label">'.__('Email Address').'
			<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('The email address to send mails to').'"></i>
		</label>
		<input type="text" id="email" name="email" class="form-control mb-4" size="30" value="'.POSTval('email', $user['email']).'" softmail="true">
		<label  for="choose_lang" class="form-label">'.__('Choose Language').'
			<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Choose your preferred language').'"></i>
		</label>		
		<select name="language" id="choose_lang" class="form-select mb-4">';
			foreach($langs as $k => $v){
				echo '
				<option value="'.$v.'" '.(empty($_POST['language']) && (empty($WE->user['P']['lang']) ? $globals['language'] : $WE->user['P']['lang']) == $v ? 'selected="selected"' : (trim($_POST['language']) == $v ? 'selected="selected"' : '') ).'>'._ucfirst($k).'</option>';

			}
		
	echo '
		</select>
		<label for="timezone" class="form-label">'.__('Timezone').'</label>
		<select name="timezone" id="timezone" class="form-select mb-3">
			<option value="0" '.(empty($_POST['timezone']) && empty($data['timezone']) ? 'selected="selected"' : '').' >'.__('Default').'</option>';
			
			foreach($timezone_list as $zone => $zone_details){
				echo '
			<option value="'.$zone.'" '.(POSTval('timezone', $data['timezone']) === $zone ? 'selected="selected"' : '').' >('.$zone_details['pretty_offset'].') '.$zone_details['display_timezone'].'</option>';
			
			}

	echo '
		</select>		
		<label class="form-label mb-4 d-block">
			<input type="checkbox" name="arrange_domain" id="arrange_domain" '.POSTchecked('arrange_domain', $data['arrange_domain']).' />
			'.__('Sort domains alphabetically').'
			<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('If selected then the list of domains under the <b>Choose Domain</b> drop down menu on the install form will be sorted alphabetically').'"></i>
		</label>';
	
	$globals['off_customize_theme'] = 1;
	
	if($theme['this_theme'] == 'modern' && empty($globals['off_customize_theme'])){
		echo '
		<label class="form-label d-block mb-4">
			<input type="checkbox" name="user_defined_color" id="user_defined_color" '.POSTchecked('user_defined_color', $data['user_defined_color']).' onchange="change_div(this.id);" />
			'.__('Customize theme').'
			<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('If checked then '.APP.' will use the theme colors selected. If you want to reset the theme to default just uncheck the checkbox and save the settings').'"></i>
		</label>				
		<div class="row" id="show_color_option" '.(!empty($data['user_defined_color']) ? '' : 'style="display:none;"').'>
			<div class="col-12 col-md-6 mb-4">
				<label class="form-label d-block">'.__('Choose Left Panel color').'
					<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('This will change the background color of the Left Panel and Top Bar').'"></i>
				</label>
				<input type="color" name="color" id="left_panel_bg" class="changecolor me-2"/>
				<input type="hidden" name="left_panel_bg" value="'.aPOSTval('left_panel_bg', $data['color_theme']['left_panel_bg']).'" id="hidden_left_panel_bg">
				<input type="color" name="color" id="left_panel_cathead_hover" class="changecolor" />
				<input type="hidden" name="left_panel_cathead_hover" value="'.aPOSTval('left_panel_cathead_hover', $data['color_theme']['left_panel_cathead_hover']).'" id="hidden_left_panel_cathead_hover">
			</div>
			<div class="col-12 col-md-6 mb-4">
				<label class="form-label d-block">'.__('Choose Left Panel Text color').'
					<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('This will change the text color of the Left Panel and Top Bar').'"></i>
				</label>
				<input type="color" name="color" id="left_panel_cathead" class="changecolor me-2"/>
				<input type="hidden" name="left_panel_cathead" value="'.aPOSTval('left_panel_cathead', $data['color_theme']['left_panel_cathead']).'" id="hidden_left_panel_cathead">
				<input type="color" name="color" id="left_panel_scriptname" class="changecolor"/>
				<input type="hidden" name="left_panel_scriptname" value="'.aPOSTval('left_panel_scriptname', $data['color_theme']['left_panel_scriptname']).'" id="hidden_left_panel_scriptname">
			</div>			
		</div>';
	}

	echo '
		<div class="text-center my-3">
			<input type="submit" class="flat-butt" name="editsettings" value="'.__('Edit Settings').'" /><br /><img id="waiting" src="'.$theme['images'].'progress.gif" style="display:none">
		</div>
	</form>
</div><!--end of card -->
'.csrf_display().'
';

softfooter();

}
