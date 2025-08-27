<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function two_factor_auth_theme() {
}
echo '
<div class="row">
<div class="col-12 col-lg-6">
<div class="mb-4 px-3">
<label class="sai_head" for="2fa_user_choice">'.__('Choose 2FA Preference').'</label>
<span class="sai_exp">'.__('Please choose the method you prefer to use for Two-Factor Authentication').'</span>
<select name="2fa_user_choice" id="2fa_user_choice" class="form-select" onchange="pref_2fa_handle();">
<option value="none" '.((!empty($_REQUEST['2fa_user_choice']) && $_REQUEST['2fa_user_choice'] == 'none') || (empty($user['2fa']['method']) || $user['2fa']['method'] == 'none') ? 'selected="selected"' : '').'>'.__('None (Not Recommended !)').'</option>
'.(empty($globals['2fa']['otp_app']) ? '' : '<option value="app" '.((!empty($_REQUEST['2fa_user_choice']) && $_REQUEST['2fa_user_choice'] == 'app') || $user['2fa']['method'] == 'app' ? 'selected="selected"' : '').'>'.__('2FA : Google Authenticator, Authy, etc').'</option>').'
'.(empty($globals['2fa']['otp_email']) ? '' : '<option value="email" '.((!empty($_REQUEST['2fa_user_choice']) && $_REQUEST['2fa_user_choice'] == 'email') || ($user['2fa']['method'] == 'email') || ((empty($user['2fa']['method']) || $user['2fa']['method'] == 'none') && !empty($globals['2fa']['force_otp_email'])) ? 'selected="selected"' : '').'>'.__('2FA : Email Auth Code').'</option>').'
'.(empty($globals['2fa']['question']) ? '' : '<option value="question" '.((!empty($_REQUEST['2fa_user_choice']) && $_REQUEST['2fa_user_choice'] == 'question') || ($user['2fa']['method'] == 'question') ? 'selected="selected"' : '').'>'.__('Solve Security Question').'</option>').'
</select>
</div>
</div>
</div>
<div class="row 2fa_options" id="2fa_app">
<div class="col-12 col-lg-6">
<div class="mb-3 px-3">
<input type="checkbox" name="2fa_app_enable" id="2fa_app_enable" '.POSTchecked('2fa_app_enable', (empty($user['2fa']['app']['enable']) ? false : true)).'> &nbsp;
<label class="sai_head" for="2fa_app_enable">'.__('Enable').'</label>
</div>
<div class="mb-4 px-3">
<label class="sai_head" for="2fa_app_secret">'.__('Secret Key').'</label> &nbsp;
<a href="javascript:load_2fa_app(1);">'.__('Reset Secret Key').'</a>
<input type="text" class="form-control" name="2fa_app_secret" id="2fa_app_secret" value="'.$app2fa['2fa_key'].'" disabled="disabled" />
</div>
<div class="mb-4 px-3">
<label class="sai_head" for="2fa_app_key32">'.__('Secret Key (Base32)').'</label>
<span class="sai_exp">'.__('Used by Google Authenticator, Authy, etc.').'</span>
<input type="text" class="form-control" name="2fa_app_key32" id="2fa_app_key32" value="'.$app2fa['2fa_key32'].'" disabled="disabled" />
</div>
<div class="mb-4 px-3">
<label class="sai_head" for="2fa_app_emergency">'.__('One Time Emergency Codes (Optional)').'</label>
<span class="sai_exp">'.__('You can specify 6 digit emergency codes seperated by a comma. Each can be used only once. You can specify upto 10 codes.').'</span>
<input type="text" class="form-control" name="2fa_app_emergency" id="2fa_app_emergency" value="'.POSTval('2fa_app_emergency', (empty($user['2fa']['app']['emergency']) ? '' : implode(', ', $user['2fa']['app']['emergency']) ) ).'" placeholder="e.g. 124667, 976493, 644335" />
</div>
</div>
<div class="col-12 col-lg-6">
<div class="mb-4 px-3">
<label class="sai_head" for="2fa_app_current_otp">'.__('Current OTP').'</label> &nbsp;
<a href="javascript:load_2fa_app(2);">'.__('Refresh').'</a>
<h3 id="2fa_app_current_otp">'.generate_2fa_app_otp($app2fa).'</h3>
</div>
<div class="mb-4 px-3">
<label class="sai_head" for="2fa_app_qrcode">'.__('QR Code').'</label>
<div id="2fa_app_qrcode" data-qrcode="'.$app2fa['2fa_qr'].'"></div>
</div>
</div>
<div class="col-12">
<b>'.__('NOTE :').'</b> '.__('Generating two-factor codes depends upon your web-server and your device agreeing upon the time.').' <br />
'.__('The current UTC time according to this server when this page loaded').': <b id="2fa_app_time">'.$app2fa['2fa_server_time'].'</b> <br /><br />
'.__('Please verify that your application is showing the same One Time Password (OTP) as shown on this page before you log out.').'
</div>
</div>
<div class="row 2fa_options" id="2fa_email">
<div class="col-12 col-lg-12">
<div class="mb-3 px-3">
'.__('A One Time Password (OTP) will be asked after successful login. The OTP will be emailed to your email address').' : <br /><br />
<h5>'.$user['email'].'</h5>
</div>
</div>
</div>
<div class="row 2fa_options" id="2fa_question">
<div class="col-12 col-lg-12">
<div class="mb-3 px-3">
'.__('A secondary question set by you will be asked after successful login').'
</div>
</div>
<div class="col-12 col-lg-6">
<div class="mb-3 px-3">
<label class="sai_head" for="2fa_question_field">'.__('Question').'</label>
<input type="text" class="form-control" name="2fa_question" id="2fa_question_field" value="'.POSTval('2fa_question', (empty($user['2fa']['question']['question']) ? '' : $user['2fa']['question']['question'])).'" placeholder="'.__('e.g. What is the name of your pet ?').'" />
</div>
<div class="mb-3 px-3">
<label class="sai_head" for="2fa_answer">'.__('Answer').'</label>
<span class="sai_exp2">'.__('Case sensitive').'</span>
<input type="text" class="form-control" name="2fa_answer" id="2fa_answer" value="'.POSTval('2fa_answer', (empty($user['2fa']['question']['answer']) ? '' : base64_decode($user['2fa']['question']['answer']))).'" placeholder="'.__('e.g. Charlie').'" />
</div>
</div>
</div>
<br /><br />
<div class="row">
<div class="col-12">
<center>
<input type="submit" class="flat-butt" value="'.__('Save').'" name="submit" id="submit"/>
</center>
</div>
</div>
</form>
</div>
<script>
function pref_2fa_handle() {
}else{
$(this).hide();
}
});
if(current == "app"){
load_2fa_app();
}
};
function load_2fa_app() {
});
}
if(reset == 1){
var confirmed = confirm("'.__js('Warning: If you reset the secret key you will have to update your app with the new one. Are you sure you want to continue ?').'");
if(confirmed){
var ajaxurl = "index.php?act=two_factor_auth&api=json";
var data = new Object();
data["reset_2fa_key"] = 1;
$.post(ajaxurl, data, function(response){
$("#2fa_app_time").html(response["app2fa"]["2fa_server_time"]);
$("#2fa_app_secret").val(response["app2fa"]["2fa_key"]);
$("#2fa_app_key32").val(response["app2fa"]["2fa_key32"]);
$("#2fa_app_current_otp").html(response["app2fa"]["2fa_otp"]);
$("#2fa_app_qrcode").attr("data-qrcode", response["app2fa"]["2fa_qr"]);
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
$(document).ready(function(){
pref_2fa_handle();
});
</script>
<style>
.2fa_options{
display: none;
}
</style>
';
softfooter();
}