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

global $globals, $theme, $logo_src;
$logo_src = $theme['images'].'webuzo_light.png';
	
if(!empty($globals['logo_url'])){
	$logo_src = $globals['logo_url'];
}

function login_theme(){

global $user, $globals, $theme, $softpanel, $WE, $catwise, $error;
global $insid, $backed, $software, $soft, $logo_src;

$current_user = $softpanel->getCurrentUser();

	login_header(__('Login'));

	echo '
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
	<div class="container" style="opacity:1;">';
	
	login_error();
	
	echo '
		<div class="row mt-5" >
			<div class="col-md-4 col-xs-12 mx-auto ">
				<div class="login-form text-center bg-transparent px-0 py-2">
					<img class="img-fluid center-block"
						src="'.$logo_src.'" alt="Panel Logo" style="width: 180px;">
				</div>
			</div>
		</div>
			<div class="row login_form_img" >	
			<div class="col-md-4 col-xs-12 mx-auto ">
				<div class="login-form py-5">
					<form accept-charset="'.$globals['charset'].'" action="" method="post" name="loginform" class="mb-2">
						<div class="form-group mb-4">
							<label class="mb-1">'.__('Username').'</label>
							<input type="text" name="username" autofocus="on" class="form-control input-tag" value="'.POSTval('username', '').'" id="username" placeholder="'.__('Enter Username or Email').'">
							
						</div>
						<div class="form-group">
							<label class="mb-1">'.__('Password').'</label>
							<input type="password" name="password" class="form-control" id="password" placeholder="'.__('Password').'">
						</div>
						<div class="form-group d-grid gap-2 mx-auto">
							<button type="submit" name="login" value="'.__('Login').'" class="btn btn-primary p-3" style="border-radius:10px;" >'.__('Login').'</button>  
						</div>
					</form>';
					
					if(empty($globals['disable_forgot_password'])){
						echo '
						<span class="fpass-link" >
							<a href="index.php?act=login&sa=fpass">'.__('Forgot Password').'</a>	
						</span>';
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

// Forgot Password theme
function fpass_theme(){
	
global $user, $globals, $theme, $softpanel, $WE, $catwise, $error;
global $insid, $backed, $software, $soft, $done, $show, $logo_src;
	
	login_header(__('Login'));

	echo '
	<div class="container" style="opacity:1;">';
	
	login_error();
	
	if(!empty($done)){
		
		echo '
		<div class="row justify-content-center">
			<div class="col-xs-12 col-md-6 mx-auto fpass-success">
				<div class="alert alert-success d-flex text-center p-2" role="alert">
					<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg> 
					<label>'.__('A mail has been sent with the details to reset your password').'</label>
				</div>
				<div class="text-center mt-3">
					<span class="login-link">
						<a href="index.php?act=login"><strong><</strong>&nbsp;&nbsp;'.__('Back to Login').'</a>	
					</span>
				</div>
			</div>
		</div>';
		
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

function fuser_theme(){
	
global $user, $globals, $theme, $softpanel, $WE, $catwise, $error;
global $insid, $backed, $software, $soft, $done, $show, $logo_src;

	login_header(__('Login'));

	echo '
	<div class="container" style="opacity:1;">';
	
	login_error();
	
	if(!empty($done)){
		echo '
		<div class="row justify-content-center">
			<div class="col-xs-12 col-sm-6 fpass-success">
				<div class="alert alert-success d-flex align-items-center" role="alert">
					<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg> 
					<div>
						'.__('An email has been sent with the Webuzo Username').'
					</div>
				</div>
				<div>
					<div class="col-sm-12 text-center">
						<span class="login-link">
							<a href="index.php?act=login"><strong><</strong>&nbsp;&nbsp;'.__('Back to Login').'</a>	
						</span>
					</div>
				</div>
			</div>
		</div>';
		
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

//Reset password
function resetpass_theme(){
	
	global $user, $globals, $theme, $softpanel, $WE, $catwise, $error;
    global $insid, $backed, $software, $soft, $done, $show, $logo_src;

	login_header(__('Login'));

	echo '
	<div class="container" style="opacity:1;">';
	
	login_error();
		
	if(!empty($done)){
		
		echo '
		<div class="row justify-content-center">
			<div class="col-xs-12 col-sm-6 fpass-success">
				<div class="alert alert-success d-flex align-items-center" role="alert">
					<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:">
						<use xlink:href="#check-circle-fill"/>
					</svg> 
					<div>
						'.__('Password Changed Successfully. Please proceed to $0 Login $1',['<a href="'.$globals['index'].'act=login">', '</a>']).'
					</div>
				</div>
				<div class="text-center">
					<span class="login-link">
						<a href="index.php?act=login"><strong><</strong>&nbsp;&nbsp;'.__('Back to Login').'</a>	
					</span>
				</div>				
			</div>
		</div>';
		
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

// 2FA theme
function tfa_theme(){
	
global $user, $globals, $theme, $softpanel, $WE, $catwise, $error;
global $insid, $backed, $software, $soft, $done, $show, $user_2fa, $logo_src;
	
	login_header(__('Login'));

	echo '
<div class="container" style="opacity:1;">';
	
	login_error();
	
	if(!empty($user_2fa)){
	
		echo '
	<form action="" accept-charset="'.$globals['charset'].'" method="post" autocomplete="new-password">
	<div class="row justify-content-center">
		<div class="col-sm-8 col-md-4 col-xs-12 pt-5">
			<div class="login-form text-center bg-transparent px-0 py-3">
				<img class="img-fluid center-block"
				src="'.$logo_src.'" alt="Panel Logo">
			</div>
			<div class="login-form rounded">';
		
		// Are we to ask a question
		if(@$user_2fa['method'] == 'question'){
		
			echo '
				<div class="form-group">
					<span>'.__('Please answer your security question').'</span><br /><br />
					<span title="" style="color:#444; font-size:16px">
						'.$user_2fa['question']['question'].'<br /><br />
					</span>
				</div>
				<div class="form-group">
					<input type="text" name="otp_value" id="otp_value" class="form-control" value="" placeholder="'.__('Your Answer').'">
				</div>';
		
		}
		
		// Its a 2fa email
		if(@$user_2fa['method'] == 'email'){
		
			echo '
				<div class="form-group">
					<span>'.__('Please enter the OTP emailed to you').'</span><br /><br />
				</div>
				<div class="form-group">
					<input type="text" name="otp_value" id="otp_value" class="form-control" value="" placeholder="'.__('One Time Password').'">
				</div>';
		
		}
		
		// Its a 2fa app ?
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

function login_header($title){
	
	global $user, $globals, $theme, $softpanel, $WE, $catwise, $error;
    global $insid, $backed, $software, $soft, $done, $show;

    echo '
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset='.$globals['charset'].'" />
	<meta name="keywords" content="softaculous, software, Webuzo" />
	<link rel="shortcut icon" href="'.$theme['images'].'favicon.ico" />
	<title>'.$title.'</title>
	<link rel="stylesheet" type="text/css" href="'.$theme['url'].'/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="'.$theme['url'].'/css/login.css?'.$globals['version'].'" />
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	'.js_url(['jquery.js','universal.js'], 'combined.js').'
</head>
<body>
	<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
		<symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
			<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
		</symbol>
		<symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
			<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
		</symbol>
		<symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
			<path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
		</symbol>
	</svg>

	<div class="trail">
		<canvas id="world"></canvas>
	</div>';
}

function login_footer(){
	
	global $user, $globals, $theme, $softpanel, $WE, $catwise, $error;
    global $insid, $backed, $software, $soft, $done, $show;

    echo '
    		<script src="'.$theme['url'].'/js/trail.js?'.$globals['version'].'"></script>
		</body>
	</html>';
}

function login_error(){

	global $user, $globals, $theme, $softpanel, $WE, $catwise, $error;
	
	echo '
	<div class="row login-error mx-auto">
		<div class="col-12 mx-auto">
			<div class="p-3 align-items-center" role="alert">	
				<div class="d-block">
				<i class="fa-solid fa-circle-exclamation" style="color: #DE3F44; vertical-align: middle;"></i>
				<span class="alert-title ms-1">'.__('The following errors were found').'</span>
				</div>
				<ul class="error-list ms-2" type="square">';
	
    if (!empty($error)) {
    	foreach ($error as $value) {
    		echo '<li>'.$value.'</li>';
    	}
    	//show the error div
    	echo "<script>document.getElementsByClassName('login-error')[0].style.display='block'</script>";
    }
	
	echo '
					</ul>
				</div>
			</div>
		</div>';
}

