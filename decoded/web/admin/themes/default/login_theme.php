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

global $user, $globals, $theme, $softpanel, $catwise, $error;
global $insid, $backed, $software, $soft, $logo_src;

	login_header(APP.' - '.__('Admin Panel'));

	echo '
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
	<div class="container" style="opacity:1;">';
	
	login_error();
	
	echo '
		<div class="row mt-3" >
			<div class="col-md-4 col-xs-12 mx-auto ">
				<div class="login-form text-center bg-transparent px-0 py-2">
					<img class="img-fluid center-block" src="'.$logo_src.'" alt="Panel Logo">
			</div>
		</div>
	</div>
			<div class="row">	
			<div class="col-md-4 col-xs-12 mx-auto ">
			
				<div class="login-form py-5">
					<form accept-charset="'.$globals['charset'].'" action="" method="post" name="loginform" class="mb-2">
						<div class="form-group mb-4">
							<label class="mb-1">'.__('Username').'</label>
						<input type="text" name="username" autofocus="on" class="form-control" value="'.POSTval('username', '').'" id="username" placeholder="'.__('Username').'">
					</div>
					<div class="form-group">
							<label class="mb-1">'.__('Password').'</label>
						<input type="password" name="password" class="form-control" id="password" placeholder="'.__('Password').'">
					</div>
						<div class="form-group d-grid gap-2 mx-auto">
							<button type="submit" name="login" value="'.__('Login').'" class="btn btn-primary p-3" style="border-radius:10px;" >'.__('Login').'</button>  
					</div>
				</form>
			</div>
		</div>
	</div>
</div>';

	login_footer();
	
}

// Forgot Password theme
function fpass_theme(){
	
global $user, $globals, $theme, $softpanel, $catwise, $error;
global $insid, $backed, $software, $soft, $done, $show, $logo_src;
	
	login_header(APP.' - '.__('Admin Panel'));

	echo '
<div class="container" style="opacity:1;">
	<div class="row login-error">
		<div class="col-12 col-md-8 mx-auto">
			<div class="alert alert-danger" role="alert">
				<div class="alert-title text-center mb-3">'.__('The following errors were found').'</div>
				<ul class="error-list" type="square">';
				login_error();
				echo '
				</ul>
			</div>
		</div>	
	</div>';

	if(!empty($done)){
		echo '
	<div class="row">
		<div class="col-12 col-md-8 col-md-4 mx-auto fpass-success mt-5">
			<div class="alert alert-fpass text-center" role="alert">
				<label class="d-inline-block">
					Note:
				</label>	
				<span class="note-message">
					'.__('A mail has been sent with the details to reset your password').'
				</span>
			</div>
			<div class="text-center">
				<span class="login-link">
					<a href="index.php?act=login" class="text-decoration-none">
						<strong class="me-1"><</strong>'.__('Back to Login').'
					</a>	
				</span>
			</div>
		</div>
	</div>';
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

function fuser_theme(){
	
global $user, $globals, $theme, $softpanel, $catwise, $error;
global $insid, $backed, $software, $soft, $done, $show, $logo_src;

	login_header(APP.' - '.__('Admin Panel'));

	echo '
<div class="container" style="opacity:1;">
	<div class="row login-error">
		<div class="col-12 col-md-8 mx-auto">
			<div class="alert alert-danger" role="alert">
				<div class="alert-title text-center mb-3">'.__('The following errors were found').'</div>
				<ul class="error-list" type="square">';
				login_error();
				echo '
				</ul>
			</div>
		</div>	
	</div>';

	if(!empty($done)){
		echo '
	<div class="row">
		<div class="col-12 col-md-8 col-md-4 mx-auto fpass-success mt-5">
			<div class="alert alert-fpass text-center" role="alert">
				<label class="d-inline-block">
					Note:
				</label>	
				<span class="note-message">
					'.__('An email has been sent with the Webuzo Username').'
				</span>
			</div>
			<div class="text-center">
				<span class="login-link">
					<a href="index.php?act=login" class="text-decoration-none">
						<strong class="me-1"><</strong>'.__('Back to Login').'
					</a>	
				</span>
			</div>
		</div>
	</div>';

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

//Reset password
function resetpass_theme(){
	
	global $user, $globals, $theme, $softpanel, $catwise, $error;
    global $insid, $backed, $software, $soft, $done, $show, $logo_src;

	login_header(APP.' - '.__('Admin Panel'));

	echo '
<div class="container" style="opacity:1;">
	<div class="row login-error">
		<div class="col-12 col-md-8 mx-auto">
			<div class="alert alert-danger" role="alert">
				<div class="alert-title text-center mb-3">'.__('The following errors were found').'</div>
				<ul class="error-list" type="square">';
				login_error();
				echo '
				</ul>
			</div>
		</div>
	</div>';

	if(!empty($done)){
		echo '
	<div class="row">
		<div class="col-12 col-md-8 col-md-4 mx-auto fpass-success mt-5">
			<div class="alert alert-fpass text-center" role="alert">
				<label class="d-inline-block">
					Note:
				</label>	
				<span class="note-message">
					'.__('Password Changed Successfully. Please proceed to $0 Login $1', ['<a href="'.$globals['index'].'act=login">', '</a>']).'
				</span>
			</div>
			<div class="text-center">
				<span class="login-link">
					<a href="index.php?act=login" class="text-decoration-none">
						<strong class="me-1"><</strong>'.__('Back to Login').'
					</a>	
				</span>
			</div>
		</div>
	</div>';
		
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

// 2FA theme
function tfa_theme(){

global $user, $globals, $theme, $softpanel, $WE, $catwise, $error;
global $insid, $backed, $software, $soft, $done, $show, $user_2fa, $logo_src;
	
	login_header(APP);

	echo '
<div class="container" style="opacity:1;">';
	
	login_error();
			
	if(!empty($user_2fa)){
	
		echo '
	<form action="" accept-charset="'.$globals['charset'].'" method="post" autocomplete="new-password">
	<div class="row justify-content-center">
		<div class="col-sm-8 col-md-4 col-xs-12 pt-5">
			<div class="login-form bg-transparent px-0 py-3 text-center">
				<img class="img-responsive center-block" src="'.$logo_src.'" alt="Panel Logo" >
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
	
	global $user, $globals, $theme, $softpanel, $catwise, $error;
    global $insid, $backed, $software, $soft, $done, $show;

    echo'
    <!DOCTYPE html>
	<html>
		<head>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta http-equiv="Content-Type" content="text/html; charset='.$globals['charset'].'" />
			<meta name="keywords" content="softaculous, software, Webuzo" />
			<link rel="shortcut icon" href="'.$theme['images'].'favicon.ico" />
			<title>'.$title.'</title>
			<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
			<link rel="stylesheet" type="text/css" href="'.$theme['url'].'/bootstrap/css/bootstrap.min.css" />
			<link rel="stylesheet" type="text/css" href="'.$theme['url'].'/css/login.css?'.$globals['version'].'" />
		</head>
		<body>
			<div class="trail">
				<canvas id="world"></canvas>
			</div>';
}

function login_footer(){
	
	global $user, $globals, $theme, $softpanel, $catwise, $error;
    global $insid, $backed, $software, $soft, $done, $show;

    echo'
			<script src="'.$theme['url'].'/js/trail.js?'.$globals['version'].'"></script>
		</body>
	</html>';
}

function login_error(){

global $user, $globals, $theme, $softpanel, $error, $done, $show;

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

