<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
global $globals, $theme, $logo_src;
$logo_src = $theme['images'].'webuzo_light.png';
if(!empty($globals['logo_url'])){
$logo_src = $globals['logo_url'];
}
function login_theme() {
}
if(empty($globals['disable_forgot_username'])){
echo '
<span class="fpass-link float-end">
<a href="index.php?act=login&sa=fuser">'.__('Forgot Username').'</a>
</span>';
}
echo '
</div>
</div>
</div>
</div>';
login_footer();
}
function fpass_theme() {
}else{
echo '
<div class="row justify-content-center">
<div class="col-sm-8 col-md-4 col-xs-12 pt-5">
<div class="login-form text-center bg-transparent px-0 py-3">
<img class="img-fluid center-block"
src="'.$logo_src.'" alt="Panel Logo">
</div>
<div class="login-form p-4 rounded">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="loginform">
<div class="form-group">
<input type="text" name="email" autofocus="on" class="form-control" value="'.POSTval('email', '').'"  id="email" placeholder="'.__('Enter your username / email address').'">
</div>
<div class="form-group d-grid gap-2 mx-auto">
<button type="submit" name="submitemail" value="'.__('Submit').'" class="btn btn-primary p-3" >'.__('Submit').'</button>
</div>
</form>
</div>
</div>
</div>';
}
echo '
</div>';
login_footer();
}
function fuser_theme() {
}else{
echo '
<div class="row justify-content-center">
<div class="col-sm-8 col-md-4 col-xs-12 pt-5">
<div class="login-form text-center bg-transparent px-0 py-3">
<img class="img-fluid center-block"
src="'.$logo_src.'" alt="Panel Logo">
</div>
<div class="login-form rounded">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="loginform">
<div class="form-group">
<input type="email" name="email" autofocus="on" class="form-control" value="'.POSTval('email', '').'"  id="email" placeholder="'.__('Enter your email address').'">
</div>
<div class="form-group d-grid gap-2 mx-auto">
<button type="submit" name="submitemailuser" value="'.__('Submit').'" class="btn btn-primary p-3" >'.__('Submit').'</button>
</div>
</form>
</div>
</div>
</div>';
}
echo '
</div>';
login_footer();
}
function resetpass_theme() {
}else{
echo '
<div class="row justify-content-center">
<div class="col-sm-8 col-md-4 col-xs-12 pt-5">
<div class="login-form text-center bg-transparent px-0 py-3">
<img class="img-fluid center-block"
src="'.$logo_src.'" alt="Panel Logo">
</div>
<div class="login-form rounded">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="loginform">
<div class="input-group password-field">
<input type="password" name="newpass" autofocus="on" class="form-control" value=""  id="newpass" onkeyup="check_pass_strength($(\'#newpass\'), $(\'#pass-prog-bar\'))" placeholder="'.__('New password').'">
</div>
<div class="progress pass-progress mb-2">
<div class="progress-bar bg-danger" id="pass-prog-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
<span>0</span>
</div>
</div>
<div class="form-group">
<input type="password" name="reppass" class="form-control" value="" placeholder="'.__('Confirm Password').'">
</div>
<div class="form-group d-grid gap-2 mx-auto">
<button type="submit" name="changepass" value="'.__('Change Password').'" class="btn btn-primary p-3" >'.__('Change Password').'</button>
</div>
</form>
</div>
</div>
</div>';
}
echo '
</div>';
login_footer();
}
function tfa_theme() {
}
if(@$user_2fa['method'] == 'email'){
echo '
<div class="form-group">
<span>'.__('Please enter the OTP emailed to you').'</span><br /><br />
</div>
<div class="form-group">
<input type="text" name="otp_value" id="otp_value" class="form-control" value="" placeholder="'.__('One Time Password').'">
</div>';
}
if(@$user_2fa['method'] == 'app'){
echo '
<div class="form-group">
<span>'.__('Please enter the OTP as seen in your App').'</span><br /><br />
</div>
<div class="form-group">
<input type="text" name="otp_value" id="otp_value" class="form-control" value="" placeholder="'.__('One Time Password').'">
</div>';
}
echo '
<div class="form-group d-grid gap-2 mx-auto">
<button type="submit" name="otp_submit" id="otp_submit" value="'.__('Submit').'" class="btn btn-primary p-3" >'.__('Login').'</button>
</div>
</div>
</div>
</div>
</form>';
}
echo '
</div>';
login_footer();
}
function login_header() {
}
function login_footer() {
}
function login_error() {
}
echo "<script>document.getElementsByClassName('login-error')[0].style.display='block'</script>";
}
echo '
</ul>
</div>
</div>
</div>';
}