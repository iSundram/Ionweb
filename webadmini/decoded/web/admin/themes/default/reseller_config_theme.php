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

function reseller_config_theme(){
	
global $user, $globals, $theme, $softpanel, $error ,$done, $langs,$tags , $users, $_user;

	softheader(__('Webuzo Configuration'));
	
	form_js();
	
	echo '
<div class="soft-smbox col-12 col-md-11 mx-auto mb-4 p-3">
	<div class="sai_main_head">
		<i class="fas fa-cog me-2"></i>'.__('Update Webuzo Configuration').'
	</div>
</div>

<div class="col-12 col-md-11 mx-auto">
<form accept-charset="'.$globals['charset'].'" name="addpackageform" id="addpackageform" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)">';
	
	error_handle($error);
	
	echo'
	<div class="soft-smbox mb-3">
		<div class="sai_form_head">'.__('Nameservers').'</div>
		<div class="sai_form"><br />
		
			<div class="form-check">
				<input class="form-check-input" type="radio" name="inherit_ns" id="inherit_nameservers" value="1" '.(empty($_user['reseller_privileges']['nameservers']) ? 'checked="checked"' : '').'>
				<label class="form-check-label" for="inherit_nameservers">'.__('Inherit from Root').'</label></br>
				<span class="sai_exp2">'.
					__('Nameserver 1').' : '.$globals['WU_NS1'].' </br>'.
					__('Nameserver 2').' : '.$globals['WU_NS2'].'
				</span>
			</div></br>
			
			<div class="form-check">
				<input class="form-check-input" type="radio" name="inherit_ns" id="custom_nameservers" value="0" '.(!empty($_user['reseller_privileges']['nameservers']) ? 'checked="checked"' : '').'>
				<label class="form-check-label" for="custom_nameservers">'.__('Set Custom Nameservers').'
				</label>
			</div>
			
			<div id="ns_form" style="display:none;">
				<div class="col-12 col-md-6">		
					<label for="mail" class="sai_head">'.__('Nameserver 1').'</label>
					<input class="form-control mb-3" type="text" name="NS1" size="30" value="'.aPOSTval('NS1', $_user['reseller_privileges']['nameservers']['WU_NS1']).'" />
				</div>
				
				<div class="col-12 col-md-6">
					<label for="mail" class="sai_head">'.__('Nameserver 2').'</label>
					<input class="form-control mb-3" type="text" name="NS2" size="30" value="'.aPOSTval('NS2', $_user['reseller_privileges']['nameservers']['WU_NS2']).'" />	
				</div>
			</div>
		</div>
	</div>';
	
	
	echo'	
	<div class="text-center">
		<input type="submit" class="btn btn-primary" id="save_config" name="save_config" value="'.__('Save Settings').'"/>
	</div>';
	
echo '
</form>
</div>

<script language="javascript" type="text/javascript">

function show_custom_ns(){
	
	var inherit_ns = $("input[name=\"inherit_ns\"]:checked").val();
	if(inherit_ns == "0"){
		
		$("#ns_form").show();
	}else{
		
		$("#ns_form").hide();
	}
}

$(document).ready(function (){
	show_custom_ns();
	
	$("input[name=\"inherit_ns\"]").click(function(){
		show_custom_ns();
	});
});
	
</script>';
	
	softfooter();
	
}
