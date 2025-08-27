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

function two_factor_auth_theme(){

global $theme, $softpanel, $error, $done, $globals, $ca_users, $users, $roles_2fa, $app2fa;

	softheader(__('Two-Factor Authentication'));
		echo '
<div class="soft-smbox p-3 col-12 mx-auto">
	<div class="sai_main_head">
			<i class="fas fa-user-shield fa-lg me-2"></i> '.__('Two-Factor Authentication').'
	</div>
</div>
<div class="soft-smbox col-12 p-4 mx-auto mt-4">
	<div class="row mb-3">
		<h4 class="sai_sub_head d-inline-block">'.__('Please select the Two-Factor Authentication methods. Each user can choose any one method from the ones enabled by you. You can enable all or selected ones that you would like.').'</h4>
	</div>';
	
	if(!empty($globals['2fa']['status'])){
		echo '
		<div class="alert alert-success">
			<i class="fas fa-check"></i>&nbsp;&nbsp;<span>'.__('Two-Factor Authentication is enabled').'</span>
		</div>';
	}else{
		echo '
		<div class="alert alert-danger">
			<i class="fas fa-exclamation-circle"></i>&nbsp;&nbsp;<span>'.__('Two-Factor Authentication is disabled').'</span>
		</div>';
	}

	echo '
	<style>
	label{
		cursor:pointer;
	}
	</style>
	
	<form accept-charset="'.$globals['charset'].'" name="editform" method="post" action="" class="form-horizontal" id="editform" onsubmit="return submitit(this)" data-donereload="1"><br />';
		error_handle($error, "100%");

		if(!empty($done)){
			echo '
			<div class="alert alert-success alert-dismissible fade show" role="alert">
			  '.$done['msg'].'
			   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
		}
		
		echo '
		<div class="sai_sub_head mb-4">'.__('Settings').'</div>
		<hr />
		<div class="row mb-3">
			<div class="col-6">
				<input type="checkbox" name="enable" id="enable" '.POSTchecked('enable', $globals['2fa']['status']).'> &nbsp;
				<label class="sai_head" for="enable">'.__('Enable Two-Factor Authentication').'</label> &nbsp;
				<span class="sai_exp">'.__('If checked, users will be able to configure Two-Factor authentication for their accounts').'</span>
			</div>
			
			<div class="col-6">
				<input type="checkbox" name="otp_email" id="otp_email" '.POSTchecked('otp_email', $globals['2fa']['otp_email']).'> &nbsp;
				<label class="sai_head" for="otp_email">'.__('OTP via Email').'</label> &nbsp;
				<span class="sai_exp">'.__('After entering the correct login credentials, the user will be asked for the OTP. The OTP will be emailed to the user').'</span>
			</div>
		</div>
		
		<div class="row mb-3">
			<div class="col-6">
				<input type="checkbox" name="otp_app" id="otp_app" '.POSTchecked('otp_app', $globals['2fa']['otp_app']).'> &nbsp;
				<label class="sai_head" for="otp_app">'.__('OTP via App').'</label> &nbsp;
				<span class="sai_exp">'.__('After entering the correct login credentials, the user will be asked for the OTP. The OTP will be obtained from the users mobile app e.g. Google Authenticator, Authy, etc.').'</span>
			</div>
			
			<div class="col-6">
				<input type="checkbox" name="force_otp_email" id="force_otp_email" '.POSTchecked('force_otp_email', $globals['2fa']['force_otp_email']).'> &nbsp;
				<label class="sai_head" for="force_otp_email">'.__('Force OTP via Email').'</label> &nbsp;
				<span class="sai_exp">'.__('If the user does not have any 2FA method selected, this will enforce the OTP via Email for the users').'</span>
			</div>
		</div>
		
		<div class="row mb-3">
			<div class="col-6">
				<input type="checkbox" name="roles_all" id="roles_all" '.POSTchecked('roles_all', (empty($globals['2fa']['roles'] ? 1 : 0))).' onchange="roles_handle();"> &nbsp;
				<label class="sai_head" for="roles_all">'.__('Apply 2FA to All Roles').'</label> &nbsp;
				<span class="sai_exp">'.__('Select the Roles to which 2FA should be applied').'</span>
			</div>
			<div class="col-6">
				<input type="checkbox" name="question" id="question" '.POSTchecked('question', $globals['2fa']['question']).'> &nbsp;
				<label class="sai_head" for="question">'.__('User Defined Question & Answer').'</label> &nbsp;
				<span class="sai_exp">'.__('In this method the user will be asked to set a secret personal question and answer. After entering the correct login credentials, the user will be asked to answer the question set by them, thus increasing the security').'</span>
			</div>
		</div>
		
		<div class="row mb-3">
			<div class="col-6 role_options">';
		
				foreach($roles_2fa as $level){
					echo '
					<input type="checkbox" name="roles_'.$level.'" id="roles_'.$level.'" '.POSTchecked('roles_'.$level, $globals['2fa']['roles'][$level]).'> &nbsp; <label for="roles_'.$level.'">'.$level.'</label>&nbsp;&nbsp;&nbsp;';
				}
		
			echo '
			</div>
		</div>';
		
		echo '
		<div class="row mb-3">
			<div class="text-center mb-3">
				<input type="submit" class="btn btn-primary" name="editsettings" value="'.__('Save Settings').'" id="editsettings"/>
			</div>
		</div>
	</form>';
	
	if(!empty($globals['2fa']['status'])){
		
		echo '
		
	<script language="javascript" src="'.$theme['url'].'/js/jquery.qrcode.min.js?'.$globals['version'].'" type="text/javascript"></script>
	<div class="sai_sub_head mb-4">'.__('Configure 2FA Settings for root').'</div>
	<hr />
	
	<form accept-charset="'.$globals['charset'].'" action="" method="post" name="configure2fa" id="configure2fa" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">';
		
		echo '
		<div class="row">
			<div class="col-12 col-lg-6">
				
				<div class="mb-3">
					<label class="sai_head" for="2fa_user_choice">'.__('Choose 2FA Preference').'
						<span class="sai_exp">'.__('Please choose the method you prefer to use for Two-Factor Authentication').'</span>
					</label>
					
					<select name="2fa_user_choice" id="2fa_user_choice" class="form-select" onchange="pref_2fa_handle();">
						<option value="none" '.((!empty($_REQUEST['2fa_user_choice']) && $_REQUEST['2fa_user_choice'] == 'none') || (empty($globals['2fa']['root']['method']) || $globals['2fa']['root']['method'] == 'none') ? 'selected="selected"' : '').'>'.__('None (Not Recommended !)').'</option>
						'.(empty($globals['2fa']['otp_app']) ? '' : '<option value="app" '.((!empty($_REQUEST['2fa_user_choice']) && $_REQUEST['2fa_user_choice'] == 'app') || $globals['2fa']['root']['method'] == 'app' ? 'selected="selected"' : '').'>'.__('2FA : Google Authenticator, Authy, etc').'</option>').'
						'.(empty($globals['2fa']['otp_email']) ? '' : '<option value="email" '.((!empty($_REQUEST['2fa_user_choice']) && $_REQUEST['2fa_user_choice'] == 'email') || ($globals['2fa']['root']['method'] == 'email') || ((empty($globals['2fa']['root']['method']) || $globals['2fa']['root']['method'] == 'none') && !empty($globals['2fa']['force_otp_email'])) ? 'selected="selected"' : '').'>'.__('2FA : Email Auth Code').'</option>').'
						'.(empty($globals['2fa']['question']) ? '' : '<option value="question" '.((!empty($_REQUEST['2fa_user_choice']) && $_REQUEST['2fa_user_choice'] == 'question') || ($globals['2fa']['root']['method'] == 'question') ? 'selected="selected"' : '').'>'.__('Solve Security Question').'</option>').'
					</select>
				</div>
			
				<div class="mb-3">
					<span class="sai_exp2">'.__('These are your personal security settings and will not affect other users').'</span>
				</div>
				
			</div>
		</div>
		
		<div class="row 2fa_options" id="2fa_app">
			
			<div class="col-12 col-lg-6">
			
				<div class="mb-3">
					<input type="checkbox" name="2fa_app_enable" id="2fa_app_enable" '.POSTchecked('2fa_app_enable', (empty($globals['2fa']['root']['app']['enable']) ? false : true)).'> &nbsp;
					<label class="sai_head" for="2fa_app_enable">'.__('Enable').'</label>
				</div>
			
				<div class="mb-4">
					<label class="sai_head" for="2fa_app_secret">'.__('Secret Key').'</label> &nbsp;
					<a href="javascript:load_2fa_app(1);">'.__('Reset Secret Key').'</a>
					<input type="text" class="form-control" name="2fa_app_secret" id="2fa_app_secret" value="'.$app2fa['2fa_key'].'" disabled="disabled" />
				</div>
			
				<div class="mb-4">
					<label class="sai_head" for="2fa_app_key32">'.__('Secret Key (Base32)').'
						<span class="sai_exp">'.__('Used by Google Authenticator, Authy, etc.').'</span>
					</label>
					<input type="text" class="form-control" name="2fa_app_key32" id="2fa_app_key32" value="'.$app2fa['2fa_key32'].'" disabled="disabled" />
				</div>
			
				<div class="mb-4">
					<label class="sai_head" for="2fa_app_emergency">'.__('One Time Emergency Codes (Optional)').'
						<span class="sai_exp">'.__('You can specify 6 digit emergency codes seperated by a comma. Each can be used only once. You can specify upto 10 codes.').'</span>
					</label>
					<input type="text" class="form-control" name="2fa_app_emergency" id="2fa_app_emergency" value="'.POSTval('2fa_app_emergency', (empty($globals['2fa']['root']['app']['emergency']) ? '' : implode(', ', $globals['2fa']['root']['app']['emergency']) ) ).'" placeholder="'.__('e.g. 124667, 976493, 644335').'" />
				</div>
			
			</div>
			
			<div class="col-12 col-lg-6">
			
				<div class="mb-4">
					<label class="sai_head" for="2fa_app_current_otp">'.__('Current OTP').'</label> &nbsp;
					<a href="javascript:load_2fa_app(2);">'.__('Refresh').'</a>
					<h3 id="2fa_app_current_otp">'.generate_2fa_app_otp($app2fa).'</h3>
				</div>
			
				<div class="mb-4">
					<label class="sai_head" for="2fa_app_qrcode">'.__('QR Code').'</label>
					<div id="2fa_app_qrcode" data-qrcode="'.$app2fa['2fa_qr'].'"></div>
				</div>
				
			</div>
			
			<div class="col-12">
				<span class="sai_exp2">
				'.__('$0 NOTE : $1 Generating two-factor codes depends upon your web-server and your device agreeing upon the time.', ['<b>', '</b>']).' <br />
				'.__('The current UTC time according to this server when this page loaded').': <b id="2fa_app_time">'.$app2fa['2fa_server_time'].'</b> <br /><br />
				'.__('Please verify that your application is showing the same One Time Password (OTP) as shown on this page before you log out.').'
				</span>
			</div>
			
		</div>
		
		<div class="row 2fa_options" id="2fa_email">
			<div class="col-12 col-lg-12">
				<div class="mb-3">
					<span class="sai_sub_head d-inline-block">'.__('A One Time Password (OTP) will be asked after successful login. The OTP will be emailed to your email address').' :</span> <br /><br />
					<h5>'.$globals['soft_email'].'</h5>
				</div>
			</div>
		</div>
		
		<div class="row 2fa_options" id="2fa_question">
			
			<div class="col-12 col-lg-12">			
				<div class="mb-3">
					<span class="sai_sub_head d-inline-block">'.__('A secondary question set by you will be asked after successful login').'</span>
				</div>
			</div>
			
			<div class="col-12 col-lg-6">
				<div class="mb-3">
					<label class="sai_head" for="2fa_question_field">'.__('Question').'</label>
					<input type="text" class="form-control" name="2fa_question" id="2fa_question_field" value="'.POSTval('2fa_question', (empty($globals['2fa']['root']['question']['question']) ? '' : $globals['2fa']['root']['question']['question'])).'" placeholder="'.__('e.g. What is the name of your pet ?').'" />
				</div>
				<div class="mb-3">
					<label class="sai_head" for="2fa_answer">'.__('Answer').'</label>
					<span class="sai_exp2">'.__('Case sensitive').'</span>
					<input type="text" class="form-control" name="2fa_answer" id="2fa_answer" value="'.POSTval('2fa_answer', (empty($globals['2fa']['root']['question']['answer']) ? '' : base64_decode($globals['2fa']['root']['question']['answer']))).'" placeholder="'.__('e.g. Charlie').'" />
				</div>
			</div>
		</div>
		
		<br /><br />
		<div class="row">
		<div class="col-12">
		<center>
			<input type="submit" class="btn btn-primary" value="'.__('Save').'" name="2fasettings" id="submit"/>
		</center>
		</div>
		</div>
	</form>

	<script>
		
	// Handle on change
	function pref_2fa_handle(){
			
		// Get the value
		var current = $("#2fa_user_choice").val();
		$(".2fa_options").each(function(){
			if($(this).attr("id") == "2fa_"+current){
				$(this).show();
			}else{
				$(this).hide();
			}
		});
		
		// Are we to show the QR Code ?
		if(current == "app"){			
			load_2fa_app();
		}
	};

	// Show the QR Code and stuff
	function load_2fa_app(reset){
		
		reset = reset || 0;
		
		// Remove existing QRCode
		$("#2fa_app_qrcode").html("");
			
		// Refresh OTP
		if(reset == 2){
			
			var ajaxurl = "index.php?act=two_factor_auth&api=json";
			var data = new Object();
			
			// AJAX and on success function
			$.post(ajaxurl, data, function(response){
				
				$("#2fa_app_time").html(response["app2fa"]["2fa_server_time"]);
				$("#2fa_app_secret").val(response["app2fa"]["2fa_key"]);
				$("#2fa_app_key32").val(response["app2fa"]["2fa_key32"]);
				$("#2fa_app_current_otp").html(response["app2fa"]["2fa_otp"]);
				$("#2fa_app_qrcode").attr("data-qrcode", response["app2fa"]["2fa_qr"]);
			});
			
		}
		
		// Reset code
		if(reset == 1){
			
			var confirmed = confirm("'.__js('Warning: If you reset the secret key you will have to update your app with the new one. Are you sure you want to continue ?').'");
			
			if(confirmed){
			
				var ajaxurl = "index.php?act=two_factor_auth&api=json";
				
				var data = new Object();
				data["reset_2fa_key"] = 1;
				
				// AJAX and on success function
				$.post(ajaxurl, data, function(response){
					$("#2fa_app_time").html(response["app2fa"]["2fa_server_time"]);
					$("#2fa_app_secret").val(response["app2fa"]["2fa_key"]);
					$("#2fa_app_key32").val(response["app2fa"]["2fa_key32"]);
					$("#2fa_app_current_otp").html(response["app2fa"]["2fa_otp"]);
					$("#2fa_app_qrcode").attr("data-qrcode", response["app2fa"]["2fa_qr"]);
					
					// The below code for updating qr does not work when key is reset because it executes before this ajax post returns
					$("#2fa_app_qrcode").html("");
					$("#2fa_app_qrcode").qrcode({"text" : response["app2fa"]["2fa_qr"]});
				});
				
			}else{
				return;
			}
		
		}
		
		var qrtext = $("#2fa_app_qrcode").attr("data-qrcode");
		$("#2fa_app_qrcode").qrcode({"text" : qrtext});
		
		return;
		
	};
		
	// Onload stuff
	$(document).ready(function(){
		pref_2fa_handle();
	});

	</script>

	<style>
	.2fa_options{
		display: none;
	}
	</style>
	
	<form accept-charset="'.$globals['charset'].'" name="reset2fa" method="post" action="" class="form-horizontal" id="reset2fa" onsubmit="return submitit(this)" data-donereload="1">
	<div class="row">
	<div class="col-12">
	<div class="sai_sub_head my-4">'.__('Users with 2FA configured').'</div>
	<hr />
	<div class="mb-3">
		<label class="sai_head" for="user_search">'.__('Reset 2FA for').' :</label>
		<select class="form-select ms-1" id="reset_username" name="username">
			<option value="0" selected>'.__('Select User').'</option>';
		
		foreach($users as $userdata){
			echo '<option value="'.$userdata['username'].'">'.$userdata['username'].'</option>';
		}
		
		echo '
		</select>
		<button class="btn btn-primary ms-3" type="submit" name="reset_2fa" value="1" id="reset_2fa">'.__('Reset').'</button>
	</div>
	<div class="table-responsive">
		<table class="table sai_form webuzo-table">
			<thead>
				<tr>
					<th>'.__('Username').'</th>
					<th>'.__('Domain').'</th>
					<th>'.__('Type').'</th>
					<th style="text-align:center">'.__('Options').'</th>
				</tr>
			</thead>
			<tbody>';

			if(empty($users)){
			
				echo '
				<tr>
					<td colspan="4" class="text-center">'.__('No users have configured 2FA yet').'</td>
				</tr>';
				
			}else{
				$type = array('2fa_question' => __('Question'),
						'2fa_app' => __('App'),
						'2fa_email' => __('Email')
				);
				foreach($users as $k => $v){
					echo '
					<tr id="tr'.$k.'">
						<td>'.$k.'</td>
						<td>'.$v['domain'].'</td>
						<td>'.(!empty($v['2fa']['method']) ? $type['2fa_'.$v['2fa']['method']] : __('None')).'</td>
						<td style="text-align:center;"><a style="cursor:pointer; color:red;" onclick="reset_2fa(this);" id="reset-'.$k.'">'.__('Reset').'</a></td>
					</tr>';
				
				}
			}
			
			echo '
			</tbody>
		</table>
	</div>
	</div>
	</div>
	</form>';
	
	}
	
	echo '
</div>

<script>

function roles_handle(){
	
	var obj = jQuery("#roles_all")[0];
	
	if(obj.checked){
		jQuery(".role_options").hide();
	}else{
		jQuery(".role_options").show();
	}
	
}

roles_handle();

$("#reset_username").select2();
$(".select2-container").css("width","250px");

function reset_2fa(e){
	
	if(e){
		var reset_data = e.id;
		var reset_username = reset_data.replace("reset-", "");
		$("#reset_username").val(reset_username);
	}
	
	$("#reset_2fa").click();
	
}
</script>';

	softfooter();
}