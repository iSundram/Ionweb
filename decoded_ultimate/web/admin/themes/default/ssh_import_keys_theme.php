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

function ssh_import_keys_theme(){

	global $user, $globals, $theme, $softpanel, $W, $error, $done, $success;
	softheader(__('SSH Import Keys'));
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'ssh_login.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('SSH Generate Keys').'</h5>
	</div>
</div>
<div class="soft-smbox p-3 mt-4">
	<h3>'.__('Import SSH Key').'</h3>
	<p class="ssh_desc">'.__('You may have already generated an SSH public private key pair. If so, you can import them here, simply paste the keys into fields below.').'</p></br>
	<form accept-charset="'.$globals['charset'].'" action="" method="post" id="ssh_importkey" enctype="multipart/form-data" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
		<div class="row mb-4">
			<div class="col-12 col-md-4 col-lg-3">
				<label class="form-label d-block" for="keyname">'.__('Choose a name for this key (defaults to id_dsa)').':</label>
			</div>
			<div class="col-12 col-md-8 col-lg-9">
				<input type="text" id="keyname" name="keyname" class="form-control mb-3" value="" />
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-4 col-lg-3">
				<label class="form-label" for="private_key">'.__('Paste the private key into the following text box').':</label>
			</div>
			<div class="col-12 col-md-8 col-lg-9">
				<textarea name="private_key" id="private_key" rows="10" cols="70" class="form-control mb-3" wrap="off"></textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-4 col-lg-3">
				<label class="form-label" for="passphrase">'.__('Passphrase').':</label>
			</div>
			<div class="col-12 col-md-8 col-lg-9">
				<input type="password" id="passphrase" name="passphrase" class="form-control mb-3" value=""/>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-4 col-lg-3">
				<label class="form-label" for="public_key">'.__('Paste the public key into the following text box').':</label>
			</div>
			<div class="col-12 col-md-8 col-lg-9">
				<textarea name="public_key" id="public_key" rows="10" cols="70" class="form-control mb-3" wrap="off"></textarea>
			</div>
		</div>			
		<center>
			<input type="submit" class="btn btn-primary" value="'.__('Import').'" name="importkey" id="importkey" />
		</center>
	</form>
</div>
<script type="text/javascript">
	$( document ).ready(function() {
		$("#keyname").focus();
	});
</script>';
	softfooter();
}
