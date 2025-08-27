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

function ipdelegation_theme(){

global $theme, $globals, $user, $error, $done, $_user, $ips;

	softheader(__('Reseller\'s IP Delegation'));
	
	error_handle($error);
		
	echo '
<div class="soft-smbox p-3 col-12 col-lg-10 col-md-8  mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-users fa-lg me-2"></i>'.__('Reseller\'s IP Delegation').'
	</div>
</div>
<div class="soft-smbox p-4 col-12 col-lg-10 col-md-8 mx-auto mt-4">';
	
	error_handle($error);
	
	echo '
	<div class="sai_sub_head mb-4 position-relative row">
		<div class="col-md-3">
			'.__('Select Reseller').' : 
		</div>
		<div class="col-md-5">
			<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="resellers" style="width: 100%" id="user_search" name="user_search">
				<option value="'.optGET('user', $_user).'" selected="selected">'.optGET('user', $_user).'</option>
			</select>
		</div>
	</div>';
	
	if(!empty($_user['reseller'])){
		echo '
		<div class="sai_form" id="formcontent">
			<div class="row">
				<table class="table sai_form webuzo-table">
					<tbody>
						<tr>
							<td>'.__('Account User Name').'</td>
							<td>'.$_user['user'].'</td>
						</tr>
						<tr>
							<td>'.__('Primary Domain Name').'</td>
							<td>'.$_user['domain'].'</td>
						</tr>
						<tr>
							<td>'.__('Current Home Directory').'</td>
							<td>'.$_user['homedir'].'</td>
						</tr>
						<tr>
							<td>'.__('Email').'</td>
							<td>'.$_user['email'].'</td>
						</tr>
						<tr>
							<td>'.__('IP Address').'</td>
							<td>'.$_user['ip'].'</td>
						</tr>
						<tr>
							<td>'.__('Delegation Type').'</td>
							<td>
								<select class="form-select" id="select">
									<option value="open" name="open">'.__('Open Delegation').'</option>
									<option value="restricted" name="restricted" '.(array_key_exists('delegated_ips', $_user) ? 'selected' : '').'>'.__('Restricted Delegation').'</option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="p-2">
				<h6>'.__('* Open delegation allows the selected reseller account to dedicate any available IP address to the accounts they own').'</h6>
				<h6>'.__('* Restricted delegation restricts the selected reseller account to only be able to dedicate one of the selected IP addresses below to the accounts they own').'</h6>
			</div>
			
			<div class="p-2" id="checkboxes" '.(array_key_exists('delegated_ips', $_user) ? '' : 'style="display:none"').'>
				<span>'.__('Select the IP addresses the selected reseller account may use : ').'</span>';
				
				$i = 1;
				foreach($ips as $k => $v){
					echo '<div class="p-1">
							<input class="me-3" type="checkbox" name="checked_ip" id="ip_'.$i.'" value="'.$k.'" '.(in_array($k, $_user['delegated_ips']) ? 'checked' : '').'>
							<label for="ip_'.$i.'">'.$k.'</label>
						</div>';
					$i++;
				}
			echo '	
			</div>
			<div class="text-center">
				<input type="submit" data-save=1 id="save" class="btn btn-primary" value="'.__('Save').'" onclick="save(this)">
			</div>
		</div>';
	}
	echo '
</div>

<script>

$("#user_search").on("select2:select", function(e, u = {}){	
	let user;
	if("user" in u){
		user = u.user;
	}else{
		user = $("#user_search option:selected").val();
	}
	window.location = "'.$globals['index'].'act=ipdelegation&user="+user;
});

$("#select").change(function(){
	var val = $("select#select option").filter(":selected").val();
	if(val == "open"){
		$("#checkboxes").hide();
	}else{
		$("#checkboxes").show();
	}
});

function save(el){
	var jEle = $(el);
	
	var arr = [];
	$("input:checkbox[name=checked_ip]:checked").each(function(){
		arr.push($(this).val());
	});
	
	jEle.data("ips", arr);
	var val = $("select#select option").filter(":selected").val();
	jEle.data("delegation", val);
	var d = jEle.data();

	// Submit the data
	submitit(d,{
		done_reload: window.location
	});
}

</script>';

	softfooter();

}