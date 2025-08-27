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

global $theme, $globals, $user, $langs, $skins, $error, $done, $softpanel, $protocols, $timezone_list, $demo_rebuild_options;

	//Is suPHP installed ?
	$apache_modules = (function_exists('apache_get_modules') ? apache_get_modules() : '');
	if(is_array($apache_modules)){
		foreach($apache_modules as $av){
			if(strtolower($av) == 'mod_suphp'){
				$suphp = true;
			}
		}
	}

	softheader(__('Settings Center'));
	
	echo '
<link rel="stylesheet" type="text/css" href="'.$theme['url'].'/css/spectrum.css?'.$globals['version'].'" />
<script src="'.$theme['url'].'/js/spectrum.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function show_ud(id){
	var val = id.value;
	if(val == "user_defined"){
		$("#admin_prefix_ud").css("display", "");
	}else{
		$("#admin_prefix_ud").css("display", "none");
	}
}

$(document).ready(function() {
	$("#off_sitepad").change(function() {
		if($(this).prop("checked")){
			alert("'.__js('Are you sure you want to disable SitePad Website Builder ? Contact us for more details about SitePad at sales@sitepad.com').'");
		}
	});
});
</script>';

	echo '
<div class="soft-smbox col-12 col-md-11 p-3 mb-4 m-auto">
	<div class="sai_main_head" >
		<i class="fas fa-cog me-2 fa-xl"></i>'.APP.' - '.__(' Settings').'
	</div>
</div>

<div class="col-12 col-md-11 mx-auto">
<form name="editsettings" method="post" action="" class="form-horizontal" data-donereload=1 onsubmit="return submitit(this)">
	<div id="stooltip" style="display:none; position:absolute; top: 0px; left: 0px; border: 1px solid #CCC; padding: 8px; background: #FFF; z-index:1000;"></div>';

	error_handle($error);

	echo '
	<div class="row">
	<div class=" mb-4 col-12 col-md-6">
		<div class="soft-smbox">
			<div class="sai_form_head">'.__('General Settings').'</div>
			<div class="sai_form p-3">';
		
	if(!defined('WEBUZO_RESELLER')){				
		echo '
				<div class="row">
					<div class="col-12 mb-3">
						<label for="is_vps" class="sai_head">
							'.__('Is VPS').'
							<span class="sai_exp">'.__('If this server is a VPS then please check this box.').'</span>
						</label>
						<input type="checkbox" name="is_vps" id="is_vps" '.POSTchecked('is_vps', $globals['is_vps']).' />						
					</div>
					<div class="col-12 mb-3">
						<label for="cookie_name" class="sai_head">
							'.__('Cookie Name').'
							<span class="sai_exp">'.__('The name of the cookie that will be stored on browsers.').'</span>
						</label>
						<input type="text" name="cookie_name" id="cookie_name" class="form-control" value="'.aPOSTval('cookie_name', $globals['cookie_name']).'" />
					</div>
					<div class="col-12 mb-3">
						<label for="soft_email" class="sai_head">'.__('Admin Email Address').'
							<span class="sai_exp">'.__('The email address to which the CRON activities and other admin related emails are sent to.').'</span>
						</label>
						<input type="text" name="soft_email" id="soft_email" class="form-control" size="30" value="'.aPOSTval('soft_email', $globals['soft_email']).'" />
					</div>';
	}
		
	echo '
					<div class="col-12 mb-3">
						<label for="from_email" class="sai_head">'.__('From Email Address').'
							<span class="sai_exp">'.__('The email address for the FROM headers.').'</span>
						</label>
						<input type="text" name="from_email" id="from_email" size="30" class="form-control" value="'.aPOSTval('from_email', $globals['from_email']).'" />
					</div>
					<div class="col-12 mb-3">
						<label class="sai_head">'.__('Choose Language').'
							<span class="sai_exp">'.__('Choose your preferred language').'</span>
						</label>
						<select name="language" class="form-select">';
					foreach($langs as $k => $v){
						echo '
							<option value="'.$v.'" '.(empty($_POST['language']) && $globals['language'] == $v ? 'selected="selected"' : (@trim($_POST['language']) == $v ? 'selected="selected"' : '') ).'>'._ucfirst($k).'</option>';
					}
							
	echo '
						</select>
					</div>
					<div class="col-12 mb-3">
						<label class="sai_head">'.__('Choose Theme').'
							<span class="sai_exp">'.__('The selected theme will be the default theme throughout').' '.APP.'</span>
						</label>
						<select name="theme_folder" class="form-select">';
					foreach($skins as $k => $v){
						echo '
							<option value="'.$v.'" '.(empty($_POST['theme_folder']) && $globals['theme_folder'] == $v ? 'selected="selected"' : (trim($_POST['theme_folder']) == $v ? 'selected="selected"' : '') ).'>'._ucfirst($v).'</option>';	
					}
	echo '
						</select>
					</div>';
					
	if(!defined('WEBUZO_RESELLER')){
			echo '
					<div class="col-12 mb-3">
						<label class="sai_head">'.__('Network Interface').'
							<span class="sai_exp">'.__('The public network interface of the server. Leave blank if you dont know').'</span>
						</label>
						<input type="text" name="network_interface" class="form-control" size="30" value="'.aPOSTval('network_interface', $globals['network_interface']).'" />
					</div>';
								
		echo '
					<div class="col-12 mb-3">
						<label class="sai_head">'.__('Default Time format').'
							<span class="sai_exp">'.__('Choose the default time format. Default : ').'<b>F j, Y, g:i a</b>'.'</span>
						</label>
						<input type="text" name="time_format" class="form-control" size="30" value="'.aPOSTval('time_format', $globals['time_format']).'" />
					</div>
					<div class="col-12 mb-3">
						<label class="sai_head">'.__('Timezone').'</label>
						<select name="timezone" id = "timezone" class="form-select">
							<option value="0" '.(empty($_POST['timezone']) && empty($globals['timezone']) ? 'selected="selected"' : '').' >'.__('Server Default').'</option>';
						foreach($timezone_list as $zone => $zone_details){
							echo '
							<option value="'.$zone.'" '.(POSTval('timezone', $globals['timezone']) === $zone ? 'selected="selected"' : '').' >('.$zone_details['pretty_offset'].') '.$zone_details['display_timezone'].'</option>';
						}
		echo '
						</select>
					</div>
					<div class="col-12 mb-3">
						<label class="sai_head">'.__('Home Directory for users').'
							<span class="sai_exp">'.__('Enter the home directory, if your home directory is a custom path e.g. /home2').'</span>
						</label>
						<select name="home" id="home" class="form-select">';
						foreach($globals['storage'] as $k => $v){
							echo '
							<option value="'.$k.'" '.POSTselect('home', $k, $globals['home'] == $k).'>'.$k.' ('.__('Free').' '.$v['free'].'GB)</option>';
					}
		echo '
						</select>
					</div>
					<div class="col-12 mb-3">
						<label class="sai_head">'.APP.__(' Logs Level').'
							<span class="sai_exp">'.__('Selected logs level will be used for error/debug logs.').'<br />'.__('Logs level 4 might contain passwords').'<br />'.__('$0 Note $1: Setting Log Level to OFF stops logging to webuzo.log and hides logs from the Admin Panel Error Log Wizard.', ['<b>', '</b>']).'</span>
						</label>
						<select name="logs_level" class="form-select">
							<option value="0" '.POSTselect('logs_level', 0, (empty($globals['logs_level']) ? '1' : '0')).'>'.__('Off').'</option>
							<option value="1" '.POSTselect('logs_level', 1, ($globals['logs_level'] == '1' ? '1' : '0')).'>1</option>
							<option value="2" '.POSTselect('logs_level', 2, ($globals['logs_level'] == '2' ? '1' : '0')).'>2</option>
							<option value="3" '.POSTselect('logs_level', 3, ($globals['logs_level'] == '3' ? '1' : '0')).'>3</option>
							<option value="4" '.POSTselect('logs_level', 4, ($globals['logs_level'] == '4' ? '1' : '0')).'>4 ('.__('Detailed Logs').')</option>
						</select>
					</div>
					
					<div class="col-12 my-3">
						<label class="sai_head">'.__('Disable DB Prefix').'
							<span class="sai_exp">'.__('If selected, all database and database users will be created without a prefix.').'</span>
						</label>
						<input type="checkbox" name="disable_dbprefix" '.POSTchecked('disable_dbprefix', @$globals['disable_dbprefix']).' class="mx-3" />
					</div>
					
					<div class="col-12 my-3">
						<label class="sai_head">'.__('Allow Session on IP Change').'
							<span class="sai_exp">'.__('If enabled the Webuzo panel session will not be destroyed if the client IP changes').'</span>
						</label>
						<input type="checkbox" name="no_session_ip" '.POSTchecked('no_session_ip', @$globals['no_session_ip']).' class="mx-3" />
					</div>
					<div class="col-12 my-3">
						<label class="sai_head">'.__('Storage Calculation Base').'
							<span class="sai_exp">'.__('Calculation Base: Choose the unit base for storage calculations. Default is 1024, offering binary prefix (KiB, MiB), or select 1000 for decimal prefix (KB, MB). Ensure compatibility with your preferred storage convention.').'</span>
						</label>
						<input type="radio" id="1024" name="storage_cal_base" '.POSTradio('storage_cal_base', 1024, @$globals['storage_cal_base']).' class="mx-1" value="1024" /><label for="1024" class="me-3">1024</label>
						<input type="radio" id="1000" name="storage_cal_base" '.POSTradio('storage_cal_base', 1000, @$globals['storage_cal_base']).' class="mx-1" value="1000" /><label for="1000">1000</label>
					</div>';
		}
		
			echo '	<div class="col-12 text-center my-3">
						<input type="submit" class="btn btn-primary" name="editsettings" value="'.__('Edit Settings').'"/>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-6 mb-4">
		<div class="soft-smbox mb-3">
			<div class="sai_form_head">'.__('End User Panel Settings').'</div>
			<div class="sai_form p-3">';	

	if(!defined('WEBUZO_RESELLER')){
		echo '
				<label for="dbpass_len" class="sai_head">'.__('Length for random generated MySQL database user password').'
					<span class="sai_exp">'.__('Choose the length of random generated database user password. If left blank $0 will use default: 10', [APP]).'</span>
				</label>
				<input type="text" id="dbpass_len" name="dbpass_len" class="form-control mb-3" value="'.aPOSTval('dbpass_len', @$globals['dbpass_len']).'" onblur="isNumber(this)"/>
				<label for="session_timeout" class="sai_head">'.__('Session Timeout').'
					<span class="sai_exp">'.APP.' '.__('Session will remain active for the time specified.').'<br />'.__('Time should be in minutes. Default: 15').'</span>
				</label>
				<input type="text" id="session_timeout" name="session_timeout" class="form-control mb-3" value="'.aPOSTval('session_timeout', @$globals['session_timeout']).'" onblur="isNumber(this)"/>
				<label for="soa_expire_val" class="sai_head">'.__('Set SOA Expire Value').'
					<span class="sai_exp">'.__('Set a numeric SOA expire value for all the domains. Minimum Value : 86400').'</span>
				</label>
				<input type="text" id="soa_expire_val" name="soa_expire_val" class="form-control mb-3" value="'.(!empty($globals['soa_expire_val']) ? POSTval('soa_expire_val', @$globals['soa_expire_val']) : POSTval('soa_expire_val', 3600000)).'" />
					
				<label class="sai_head mb-3">
					'.__('Disable CronJob Email').'
					<span class="sai_exp">'.__('If checked, cronjob notifications will not be send to Email Address given').'</span>
					
				</label>
				<input type="checkbox" name="disable_cronupdate_email" '.POSTchecked('disable_cronupdate_email', @$globals['disable_cronupdate_email']).' /><br/>
				<label class="sai_head">
					'.__('Compress Output').'
					<span class="sai_exp">'.__('This will compress output and it saves a lot of bandwidth.').'</span>	
				</label>
				<input type="checkbox" name="gzip" '.POSTchecked('gzip', $globals['gzip']).' class="mb-4" /><br/>
				<label class="sai_head">
					'.__('Disable Resource Usage Stats').'
					<span class="sai_exp">'.__('This will restrict Resource Usage Stats from displaying to users in their enduser panels.').'</span>	
				</label>
				<input type="checkbox" name="disable_resource_stats" '.POSTchecked('disable_resource_stats', $globals['disable_resource_stats']).' class="me-1" /><br>
				<div class="mt-2" style="display: flex; align-items: center; gap: 10px;">
					<label class="sai_head mt-1">
						'.__('Disable Forgot Username').'
						<span class="sai_exp">'.__('This will disable the Forgot Username button on Enduser login panel').'</span>	
					</label>
					<input type="checkbox" name="disable_forgot_username" '.POSTchecked('disable_forgot_username', $globals['disable_forgot_username']).' class="me-1" />
					
					<label class="sai_head mt-1">
						'.__('Disable Forgot Password').'
						<span class="sai_exp">'.__('This will disable the Forgot Password button on Enduser login panel.').'</span>	
					</label>
					<input type="checkbox" name="disable_forgot_password" '.POSTchecked('disable_forgot_password', $globals['disable_forgot_password']).' class="me-1" /><br>
				</div>';
	}

	echo '
				<div class="text-center my-3">
					<input type="submit" class="btn btn-primary" name="editsettings" value="'.__('Edit Settings').'"/>
				</div>
			</div>
		</div>';
								
	if(!defined('WEBUZO_RESELLER')){
		
		echo '
		<div class="soft-smbox">
			<div class="sai_form_head">'.__('Disable').' '.APP.'</div>
			<div class="sai_form p-3">
				<div class="row mb-3">
					<div class="col-12">
						<label class="sai_head mb-3">
							'.__('Turn').' '.APP.' '.__('Off').'
							<span class="sai_exp">'.__('This will disable').' '.APP.' '.__('and users will not be able to use it!').'</span>
						</label>
						<input type="checkbox" name="off" '.POSTchecked('off', $globals['off']).' /><br/>
						<label for="off_subject" class="sai_head">'.__('Switch Off Subject').'</label>
						<input type="text" name="off_subject" class="form-control mb-3" id="off_subject" size="30" value="'.aPOSTval('off_subject', $globals['off_subject']).'" />
						<label for="off_message" class="sai_head">'.__('Switch Off Message').'</label>
						<textarea name="off_message" class="form-control mb-3" cols="30" rows="6">'.aPOSTval('off_message', $globals['off_message']).'</textarea>
					</div>
				</div>
				<div class="text-center my-3">
					<input type="submit" class="btn btn-primary" name="editsettings" value="'.__('Edit Settings').'"/>
				</div>
			</div>
		</div>';
	}
	
	echo '
	</div>
	<div class="col-12 col-md-6 mb-3">
		<div class="soft-smbox">
			<div class="sai_form_head">'.__('Demo User Settings').'</div>
			<div class="sai_form p-3">
				<label class="sai_head mb-3">
					'.__('Enable Root Demo').'
					<span class="sai_exp">'.__('If enabled, you will no longer be able to use this server to do anything. Do this when you are absolutely sure !').'</span>
				</label>
				<input type="checkbox" name="demo" onchange="demo_confirm(this)" value="1" '.POSTchecked('demo', $globals['demo']).' /><br/>
				<label class="sai_head mb-3">
					'.__('Enable $_POST').'
					<span class="sai_exp">'.__('We disable $_POST and $_REQUEST for demo users').'</span>
				</label>
				<input type="checkbox" name="demo_enable_post" value="1" '.POSTchecked('demo_enable_post', $globals['demo_enable_post']).' /><br/>
				<label class="sai_head">'.__('Rebuild Demo Users').'
					<span class="sai_exp">'.__('If enabled, the user will be rebuilt and all data will be reset for the demo users').'</span>
				</label>
				<select name="demo_rebuild" class="form-select">';
					foreach($demo_rebuild_options as $k => $v){
						echo '<option value="'.$k.'" '.POSTselect('demo_rebuild', $k, $globals['demo_rebuild'] === (string)$k).'>'.$v.'</option>';
					}
					
					echo '
				</select>
					
				<div class="text-center my-3">
					<input type="submit" class="btn btn-primary" name="editsettings" value="'.__('Edit Settings').'"/>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-6 mb-3">
		<div class="soft-smbox">
			<div class="sai_form_head">'.__('Choose SSL CA').'</div>
			<div class="sai_form p-3">
				
				<label class="sai_head">'.__('Select your SSL CA').'</label>
				<select name="ssl_ca" class="form-select">';
					
					foreach($globals['ssl_ca_options'] as $k => $v){
						echo '<option value="'.$k.'" '.POSTselect('ssl_ca', $k, $globals['SSL_CA'] === (string)$k).'>'.$v.'</option>';
					}
					
					echo '
				</select><br/>			
				<label class="sai_head mb-3" for="disable_ssl_mail">
					'.__('Disable Certificate Renewal Email to users').'
					<span class="sai_exp">'.__('If this is selected users will not receive email on failure of Auto Renew SSL certificates').'</span>
				</label>
				<input type="checkbox" name="disable_ssl_mail" id="disable_ssl_mail" value="1" '.POSTchecked('disable_ssl_mail', $globals['disable_ssl_mail']).' />
				<br/>
				<label class="sai_head mb-3" for="disable_auto_ssl">
					'.__('Disable Default Automatic SSL').'
					<span class="sai_exp">'.__('By default, we create and install SSL certificates for newly added domains. If checked, this will disable the default behaviour. User will still be able to use the AutoSSL utility to issue certs !').'</span>
				</label>
				<input type="checkbox" name="disable_auto_ssl" id="disable_auto_ssl" value="1" '.POSTchecked('disable_auto_ssl', (!empty($globals['disable_auto_ssl']) ? 1 : 0)).' />
				<div class="text-center my-3">
					<input type="submit" class="btn btn-primary" name="editsettings" value="'.__('Edit Settings').'"/>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-6 mb-3">
		<div class="soft-smbox mt-3">
			<div class="sai_form_head">'.__('Enduser Application(s) Settings').'</div>
			<div class="sai_form p-3">
				<div class="row mb-3">
					<div class="col-12 mb-3">
						<label for="from_email" class="sai_head">'.__('Port ranges').'
							<span class="sai_exp">'.__('Port ranges (,) separated. E.g. 10001-10100').'</span>
						</label>
						<input type="text" name="eapps_port_range" id="eapps_port_range" size="30" class="form-control" value="'.aPOSTval('eapps_port_range', $globals['eapps_port_range']).'" />
					</div>
				</div>
				<div class="text-center my-3">
					<input type="submit" class="btn btn-primary" name="editsettings" value="'.__('Edit Settings').'"/>
				</div>
			</div>
		</div>
	</div>';
	
	echo '
'.csrf_display().'
	</div>
</form>
</div>

<script>


// Warn before demo
function demo_confirm(ele){
	
	var a = show_message_r("'.__js('Warning').'", "'.__js('If you enable Demo Mode, you will not be able to do anything further and anyone will be able to login as root user. All post data will also be disabled for the demo mode to prevent exploitation of the server. Please enable Demo mode at your own risk ! Are you sure you want to enable Demo Mode ?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){});
	
	a.no.push(function(){
		$(ele).prop("checked", false);
	});
	
	show_message(a);

}

function updateColor(color, id) {
	
	var hexColor = "transparent";
	if(color) {
		hexColor = color.toHexString();
	}
	if(hexColor == "transparent") return;
	
	// Set the hidden value so that we can save the settings
	$("#default_hf_bg").val("1");
	
	// For changing text colors of category heading
	if(id == "default_hf_text"){
		$(".soft_cathead a").css("color", hexColor);
		$(".soft_nav a").css("color", hexColor);
		$(".fa").css("color", hexColor);
		
		// set the hidden value so that it can be saved
		$("#hidden_default_hf_text").val(hexColor);
	}
	
	// For changing text colors of category heading hover color
	if(id == "default_cat_hover"){
		try{
			$(".soft_cathead:hover, .soft_cathead_slide:hover").css("background-color", hexColor);
			//$(".soft_cathead_slide:hover").css("background-color", hexColor);
		
			// set the hidden value so that it can be saved
			$("#hidden_default_cat_hover").val(hexColor);
		}catch(e){
			// do nothing
		}
	}
	
	// For changing text colors of script names
	if(id == "default_scriptname_text"){
		$(".softlinks li a").css("color", hexColor);
		
		// set the hidden value so that it can be saved
		$("#hidden_default_scriptname_text").val(hexColor);
	}
	
	// For changing background color
	if(id == "default_hf_bg"){
		$(".soft_nav").css("background", hexColor);
		
		// set the hidden value so that it can be saved
		$("#hidden_default_hf_bg").val(hexColor);
	}
	
}

var pallete_array = [
		["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", /*"rgb(153, 153, 153)","rgb(183, 183, 183)",*/
		"rgb(204, 204, 204)", "rgb(217, 217, 217)", /*"rgb(239, 239, 239)", "rgb(243, 243, 243)",*/ "rgb(255, 255, 255)"],
		["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
		"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
		["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
		"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
		"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
		"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
		"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
		"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
		"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
		"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
		/*"rgb(133, 32, 12)", "rgb(153, 0, 0)", "rgb(180, 95, 6)", "rgb(191, 144, 0)", "rgb(56, 118, 29)",
		"rgb(19, 79, 92)", "rgb(17, 85, 204)", "rgb(11, 83, 148)", "rgb(53, 28, 117)", "rgb(116, 27, 71)",*/
		"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
		"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
	];

$(function() {
	$("#default_hf_bg").spectrum({
		allowEmpty:true,
		color : "'.(!empty($globals['default_hf_bg']) ? $globals['default_hf_bg'] : '#333333').'",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		localStorageKey: "soft.demo",
		move: function (color) {
			updateColor(color, this.id);
		},
		hide: function (color) {
			updateColor(color, this.id);
		},
		palette: pallete_array
	});
});

$(function() {
	$("#default_cat_hover").spectrum({
		allowEmpty:true,
		color : "'.(!empty($globals['default_cat_hover']) ? $globals['default_cat_hover'] : '#4096ee').'",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		localStorageKey: "soft.demo",
		move: function (color) {
			updateColor(color, this.id);
		},
		hide: function (color) {
			updateColor(color, this.id);
		},
		palette: pallete_array
	});
});

$(function() {
	$("#default_hf_text").spectrum({
		allowEmpty:true,
		color : "'.(!empty($globals['default_hf_text']) ? $globals['default_hf_text'] : '#F2F2F2').'",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		localStorageKey: "soft.demo",
		move: function (color) {
			updateColor(color, this.id);
		},
		hide: function (color) {
			updateColor(color, this.id);
		},
		palette: pallete_array
	});
});

$(function() {
	$("#default_scriptname_text").spectrum({
		allowEmpty:true,
		color : "'.(!empty($globals['default_scriptname_text']) ? $globals['default_scriptname_text'] : '#FFFFFF').'",
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		localStorageKey: "soft.demo",
		move: function (color) {
			updateColor(color, this.id);
		},
		hide: function (color) {
			updateColor(color, this.id);
		},
		palette: pallete_array
	});
});

$(document).ready(function(){
	var f = function(){
		var type = window.location.hash.substr(1);
		if(type == "timezone"){
			scrollTo("timezone");
			$("#timezone").focus();
			
			
			
		}
	}
	
	f();
	
	$(window).on("hashchange", f);
});

function scrollTo(id) {
   $("html,body").animate({scrollTop: $("#"+id).offset().top - $(window).height()/2}, 1000);
}
</script>';

	softfooter();

}

