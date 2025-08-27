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

function reseller_privileges_theme(){
global $user, $globals, $theme, $softpanel, $error ,$done, $langs, $reseller_privileges_fields, $tags , $users, $_user;

	softheader(__('Reseller Privileges'));
	
	form_js();
	
	echo '
	<div class="soft-smbox p-3">
		<div class="sai_main_head">
			<i class="fas fa-sitemap fa-xl me-2"></i>'.(!empty($_user) ? __('Reseller Privileges and Nameservers') : __('Global Reseller Privileges')).'	
		</div>
	</div>
	
<div class="col-12 col-md-12 mx-auto mt-4">
<form accept-charset="'.$globals['charset'].'" name="resetresller" id="resetresller" method="post" action=""; class="form-horizontal" onsubmit="return submitit(this)" data-donereload>';
	
	error_handle($error);
	
	echo '
	<div class="soft-smbox mb-3">
		<div class="sai_form_head">'.__('Select Reseller').'</div>
		<div class="sai_form p-3">
			<div class="row d-flex align-items-center">
				<div class="col-12">
					<span class="sai_exp2">'.__('Select a reseller if you want to edit privileges for selected user. If not selected the Privileges will be applicable for all resellers.').'<br /><br /></span>
				</div>
				<div class="col-12 col-md-3">
					<label for="reseller_user" class="sai_head">'.__('Choose Reseller').'</label>
				</div>
				<div class="col-12 col-md-7">
					<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select Reseller').'" s2-ajaxurl="'.$globals['index'].'act=users&type=2&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" style="width: 60%" id="user_search" name="user_search">
					<option value="'.optGET('user_name', $_user).'" selected="selected">'.optGET('user_name', $_user).'</option>
					</select>
				</div>
				<div class="col-12 col-md-2">';
					if(!empty($_user)){
						echo '<input type="button" class="btn btn-danger" value="'.__('Reset Privileges').'" onclick="reset_reseller()" '.(empty($_user['reseller_privileges']) ? 'disabled' : '').'>';
					}
				echo'
				</div>		
			</div>
		</div>
	</div>';
	
	if(empty($_user) || !empty($_user['reseller'])){
		
		$rp = $_user['reseller_privileges'];
		foreach($reseller_privileges_fields as $cat => $c){
			echo '
			<div class="soft-smbox mb-3">
				<div class="sai_form_head">'.$c['name'].'</div>
				<div class="sai_form">
					<div class=" row">';
					foreach($c['list'] as $key => $props){
				
						// Handle multicheckbox values
						if($props['type'] == 'multicheckbox' && !empty($_user['reseller']) && isset($rp[$key]) && empty($rp[$key])){
							$rp[$key]['dummy'] = 1;
						}
						echo call_user_func_array('form_type_'.$props['type'], array($key, $props, &$rp[$key]));
					}
					echo '
					</div>
				</div>	
			</div>';
		}
				
		if(!empty($_user)){
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
					<div id="ns_form" style="display:none;" >
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
		}
		
		echo'	
		<div class="text-center">
			<input type="submit" class="btn btn-primary" id="save_privileges" name="save_privileges" value="'.__('Save Settings').'"/>
		</div>';	
		
	}else{
		echo ' 
		<div>
			<center><p class="alert alert-danger">'.__('Please select a valid user').'</p></center>
		</div>';
	}					
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

function show_root_plans(){
	var choose_root_plans = document.getElementById("choose_root_plans");
	
	if(choose_root_plans.checked){
		$("[key=root_plans]").show();
	}else{
		$("[key=root_plans]").hide();
	}
}

$(document).ready(function (){
	show_custom_ns();
	show_root_plans();	
	
	$("input[name=\"inherit_ns\"]").click(function(){
		show_custom_ns();
	});
	
	$("#choose_root_plans").click(function(){
		show_root_plans();
	});
});

$("#user_search").on("select2:select", function(e, u = {}){	
	let user_name;
	if("user_name" in u){
		user_name = u.user_name;
	}else{
		user_name = $("#user_search option:selected").val();
	}
	window.location = "'.$globals['index'].'act=reseller_privileges&user_name="+user_name;
});

function reset_reseller(){
	var a;	
	var arr = [];
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to Reset Privileges for the selected reseller?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"reset" :  1};
		// Submit the data
		submitit(d, {done_reload : window.location.href});
	});
	
	show_message(a);
}

</script>';
	
	softfooter();
	
}
