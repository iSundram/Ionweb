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

function sharedip_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $_user, $SESS, $ips;
	
	softheader(__('Reseller\'s Shared IP'));
	
	echo '
<div class="soft-smbox p-4 col-12 col-md-8 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-th-list fa-xl me-2"></i>'.__('Resellers Shared IPs').'
	</div>
</div>
<div class="soft-smbox p-4 col-12 col-md-8 mx-auto mt-4">';
	
	error_handle($error);
	
	echo '<div class="sai_sub_head mb-4 position-relative row">
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
							<td>'.__('Shared IP Address').'</td>
							<td>
								<select class="form-select" id="select" name="select_user">';
									$selected = empty($_user['shared_ip']) ? $globals['WU_PRIMARY_IP'] : $_user['shared_ip'];
									
									foreach($ips as $key => $value){
										echo '
										<option value="'.$key.'" '.POSTselect('select_user', $key, ($selected == $key)).'>'.$key.'</option>';
									}
								echo '
								</select>
							</td>
						</tr>
					</tbody>
				</table>
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
	window.location = "'.$globals['index'].'act=sharedip&user="+user;
});

function save(el){
	var jEle = $(el);

	var val = $("select#select option").filter(":selected").val();
	jEle.data("ip", val);	
	var d = jEle.data();
	
	// Submit the data
	submitit(d,{
		done_reload : window.location
	});
}

</script>';

	softfooter();
	
}