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
function fpass_theme() {
}else{
echo '
<div class="row">
<div class="col-12 col-md-8 col-lg-4 login-form mx-auto login-form-div">
<div class="logo-login-form text-center">
<img class="img-fluid" src="'.$logo_src.'" alt="Panel Logo">
</div>
<div class="login-form rounded">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="loginform">
<div class="form-group">
<input type="email" name="email" autofocus="on" class="form-control" value="'.POSTval('email', '').'"  id="email" placeholder="'.__('Enter your email address').'">
</div>
<div class="form-group">
<button type="submit" name="submitemail" value="'.__('Submit').'" class="btn btn-dark primary w-100 p-3" >'.__('Submit').'</button>
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
<div class="row">
<div class="col-12 col-md-8 col-lg-4 login-form mx-auto login-form-div">
<div class="logo-login-form text-center">
<img class="img-fluid" src="'.$logo_src.'" alt="Panel Logo">
</div>
<div class="login-form rounded">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="loginform">
<div class="form-group">
<input type="email" name="email" autofocus="on" class="form-control" value="'.POSTval('email', '').'"  id="email" placeholder="'.__('Enter your email address').'">
</div>
<div class="form-group">
<button type="submit" name="submitemailuser" value="'.__('Submit').'" class="btn btn-dark primary w-100 p-3" >'.__('Submit').'</button>
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
<div class="row">
<div class="col-12 col-md-8 col-lg-4 login-form mx-auto login-form-div">
<div class="logo-login-form text-center">
<img class="img-fluid" src="'.$logo_src.'" alt="Panel Logo">
</div>
<div class="login-form rounded">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="loginform">
<div class="form-group">
<input type="password" name="newpass" autofocus="on" class="form-control" value="" id="newpass" placeholder="'.__('New password').'">
</div>
<div class="form-group">
<input type="password" name="reppass" class="form-control" value="" placeholder="'.__('Confirm Password').'">
</div>
<div class="form-group">
<button type="submit" name="changepass" value="'.__('Change Password').'" class="btn btn-dark primary w-100 p-3" >'.__('Change Password').'</button>
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