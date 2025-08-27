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

function ssh_generate_keys_theme(){

	global $user, $globals, $theme, $softpanel, $W, $error, $keytype, $keysize, $fingerprint, $done;

	if(optGET('ajaxgenkey')){
	
		if(!empty($error)){			
			echo '0'.current($error);
			return false;
		}
		if(!empty($done)){
			echo '1'.$fingerprint;
			return true;
		}
	}

	softheader(__('SSH Generate Keys'));
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'ssh_login.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('SSH Generate Keys').'</h5>
	</div>
</div>
<div class="soft-smbox p-3 mt-4">
	<h3>'.__('Generating a Public Key').'</h3>
	<p class="ssh_desc">'.__('A number of cryptographic algorithms can be used to generate SSH keys, including RSA, DSA, and ECDSA. RSA keys are generally preferred and are the default key type.').'</p>
	<form accept-charset="'.$globals['charset'].'" action="" method="post" id="ssh_generatekey" class="form-horizontal" onsubmit="return submitit(this)" data-doneredirect="'.$globals['admin_index'].'act=manage_root_ssh_keys">
		<div class="row">
			<div class="col-sm-5">
				<label for="keyname" class="sai_head control-label">'.__('Key Name (This value defaults to "id_rsa")').':</label>
			</div>
			<div class="col-sm-7">
				<input type="text" id="keyname" name="keyname" class="form-control" value="id_rsa" required/>
			</div>
		</div></br>
		<div class="row">
			<div class="col-sm-5">
				<label class="sai_head" for="newpass">'.__('Key Password').':</label>
				<span class="sai_exp">'.__('Password strength must be greater than or equal to $0', [pass_score_val('ssh')]).'</span>
			</div>
			<div class="col-sm-6">
				<div class="input-group">
					<input type="password" name="keypass" id="keypass" class="form-control" onkeyup="check_pass_strength($(\'#keypass\'), $(\'#pass-prog-bar\'))" value="" />
					<span class="input-group-addon" style="padding: 4px 12px" onclick="change_image(this, \'keypass\')">
					<img src="'.$theme['images'].'eye.png">
					</span>
				</div>
				<div class="progress pass-progress">
					<div class="progress-bar bg-danger" id="pass-prog-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
						<span> 0 </span>
					</div>
				</div>
			</div>
			<div class="col-sm-1">
				<a href="javascript: void(0);" onclick="rand_val=randstr(10, '.pass_score_val('ssh').');$_(\'keypass\').value=rand_val;$_(\'repass\').value=rand_val;check_pass_strength($(\'#keypass\'), $(\'#pass-prog-bar\'));return false;" title="'.__('Generate a Random Password').'"><img src="'.$theme['images'].'key.png" style="padding-top:9px;"/></a>
			</div>
		</div></br>
		<div class="row">
			<div class="col-sm-5">
				<label for="repass" class="sai_head control-label">'.__('Re-enter Password').':</label>
			</div>
			<div class="col-sm-7">
				<input type="password" id="repass" name="repass" class="form-control" autocomplete="Off"/>
			</div>
		</div></br>
		<div class="row">
			<div class="col-sm-5">
				<label  for="keytype" class="sai_head">'.__('Key Type').':</label>
			</div>
			<div class="col-sm-7">
				<select name="keytype" id="keytype" class="form-control">';
					foreach ($keytype as $value) {
						echo '<option value="'.$value.'" '.($value == "RSA" ? "selected" : "").'>'.$value.'</option>';
					}
				echo '
				</select>
			</div>
		</div><br /><!---row end--->
		<div class="row">
			<div class="col-sm-5">
				<label for="timezone" class="sai_head">'.__('Key Size').':</label>
			</div>
			<div class="col-sm-7">
				<select name="keysize" id="keysize" class="form-control">';
					foreach ($keysize as $value) {							
						echo '<option value="'.$value.'" '.($value == "2048" ? "selected" : "").'>'.$value.'</option>';
					}
				echo '
				</select>
			</div>			
		</div><br />
		<center>
			<input type="submit" class="btn btn-primary" value="'.__('Generate Key').'" name="generatekey" id="generatekey" />
		</center>
	</form>	
</div>

<script>
$(document).ready(function(){
	$("#keytype").change(function(){
		var type = $("#keytype").val();
		if(type == "DSA"){
			$("#keysize").val("1024");
			$("#keysize").attr("disabled", true); 
		}else{
			$("#keysize").val("2048");
			$("#keysize").attr("disabled", false); 
		}
	})	
});
</script>
';	
	softfooter();
}