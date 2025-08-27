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

$l['user_data_missing'] = 'The username or password field was empty';
$l['invalid_username'] = 'The username or password you entered is incorrect';
$l['invalid_password'] = 'The username or password you entered is incorrect';
$l['invalid_user'] = 'The username or password you entered is incorrect';
$l['no_username'] = 'The Username field was empty';
$l['no_password'] = 'The Password field was empty';
$l['disable_user'] = 'You cannot login as root user in the Enduser Panel. Please login to the <a href="'.$globals['admin_url'].'">Admin Panel</a>';
$l['l_<title>'] = 'Login';
$l['sign_in'] = 'Sign in';
$l['log_user'] = 'Username';
$l['log_pass'] = 'Password';
$l['sub_but'] = 'Login';
$l['login_suspended'] = 'Your account is suspended !';
$l['<title_fpass>'] = 'Forgot Password';
$l['pass_nomatch'] = 'The username or password you entered is incorrect.';
$l['forgotpass'] = 'Forgot Password';
$l['emailuser'] = 'Email Address';
$l['user_or_email'] = 'Enter your username / email address';
$l['enteremail'] = 'Enter your email address';
$l['l_no_user'] = 'No user was found with the submitted details';
$l['sub_email'] = 'Submit';
$l['l_no_email'] = 'You did not submit your email address';
$l['invalidemail'] = 'The email address you submitted is invalid';
$l['mail_sub'] = 'Reset Password';
$l['mail_body'] = 'Hi,

A request to reset your password has been made.
If you did not request the password reset, then please ignore this email.

If you would like to reset your password, then please click the URL below :
https://'.$globals['WU_PRIMARY_DOMAIN'].':2003/index.php?act=login&sa=resetpass&key=&soft-1;&user=&soft-2;

Regards,
'.$globals['sn'].'';
$l['mail_done'] = 'A mail has been sent with the details to reset your password';
$l['fuser_mail_sub'] = 'Webuzo Username';
$l['fuser_mail_body'] = 'Hi,

A request to fetch the Webuzo Username has been made.
If you did not request the Webuzo Username, then please ignore this email.

Login to the URL below :
https://'.$globals['WU_PRIMARY_DOMAIN'].':2003/index.php?act=login

Username : &soft-1;

Regards,
'.$globals['sn'].'';
$l['fuser_mail_done'] = 'An email has been sent with the Webuzo Username';
$l['forgotuser'] = 'Forgot Username';
$l['back_to_login'] = 'Back to Login';
$l['<title_reset>'] = 'Reset Password';
$l['resetpass'] = 'Reset Password';
$l['log_new_pass'] = 'New password';
$l['log_reppass'] = 'Confirm Password';
$l['changepass'] = 'Change Password';
$l['l_no_key'] = 'No Reset Key was submitted';
$l['invalidkey'] = 'You specified an invalid key';
$l['l_no_new'] = 'Please enter valid Password';
$l['no_reppass'] = 'Please enter confirm password';
$l['l_no_match'] = 'The passwords you gave do not match';
$l['keyexpire'] = 'Key is no longer valid';
$l['passchanged'] = 'Password Changed Successfully. Please proceed to <a href="'.$globals['index'].'act=login">Login</a>';
$l['forgot_pass'] = 'Forgot Password';

$l['l_2fa_token_invalid'] = 'The token is invalid or has expired. Please login again by clicking <a href="'.$globals['index'].'act=login">here</a>.';
$l['l_2fa_otp_app'] = 'Please enter the OTP as seen in your App';
$l['l_2fa_otp_email'] = 'Please enter the OTP emailed to you';
$l['l_2fa_otp_field'] = 'One Time Password';
$l['l_2fa_otp_question'] = 'Please answer your security question';
$l['l_2fa_otp_answer'] = 'Your Answer';
$l['l_2fa_otp_answer_fail'] = 'The answer is incorrect !';
$l['l_2fa_otp_incorrect'] = 'The OTP is incorrect !';
