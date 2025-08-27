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

function manage_wheel_theme(){

global $theme, $globals, $users, $langs, $error, $done, $softpanel, $wheel_users, $group;

	softheader(__('Manage Wheel Group'));
	
	echo '
	
	<div class="soft-smbox p-3 col-12 col-md-12 mx-auto">
		<div class="sai_main_head">
			<i class="fas fa-user-lock fa-lg me-2"></i>'.__('Manage Wheel Group').'
		</div>
	</div>
	<div class="soft-smbox p-4 col-12 col-md-12 mx-auto mt-4">';
	error_handle($error);
	
	//r_print($users);
	//r_print($wheel_users);
	
	echo'
	<div class="alert alert-info">
		<i class="fas fa-info-circle"></i> <span>'.__('To allow user to access the sudo command you have to move the user into the $group. $0 Make sure to uncomment this line: %$group ALL=(ALL) ALL from /etc/sudoers file.', ['group' => $group, '<br>']).'</span> 
	</div>
	<div class="row">
		<div class="col-md-5 col-sm-5 mb-4">
			<div class="soft-smbox mb-3">
				<div class="sai_form_head">'.__('List system users').'</div>
				<div class="sai_form p-3">
					<select name="add_grp" id="add_grp" class="form-select form-unlimited" size="8" id="">';
					foreach($users as $key => $val){
						
						if(empty($val)){
							continue;
						}
						
						if(in_array($val, $wheel_users)){
							continue;
						}
						
						echo '<option value="'.$val.'">'.$val.'</option>';
					}
					
					echo'
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-2 col-sm-2" style="margin-top: 5.5rem;margin-bottom: 5.5rem;">
			<div class="text-center mb-3">
				<button class="btn btn-primary w-100" id="add_group" name="add_group" data-action="add" onclick="toggleAction(this)">'.__('Add').'<i class="fas fa-angle-double-right ps-2"></i></buuton>
			</div>
			<div class="text-center">
				<button class="btn btn-primary w-100" id="remove_group" name="remove_group" data-action="remove" onclick="toggleAction(this)"><i class="fas fa-angle-double-left pe-2"></i>'.__('Remove').'</buuton>
			</div>
		</div>
		<div class="col-md-5 col-sm-5 mb-4">
			<div class="soft-smbox mb-3">
				<div class="sai_form_head">'.__('List wheel group users').'</div>
				<div class="sai_form p-3">
					<select name="remove_grp" id="remove_grp" class="form-select form-unlimited" size="8" id="">';
					foreach($wheel_users as $k => $v){
						if(!empty($v)){
							echo'<option value="'.$v.'">'.$v.'</option>';
						}
					}
					echo'
					</select>
				</div>
			</div>
		</div>
	</div>
<script>
$(document).ready(function(){
	$("#add_grp").change(function(){
		$("#remove_group").attr("disabled", true);
		$("#add_group").attr("disabled", false);
		$("#remove_grp").val("");
	});
	$("#remove_grp").change(function(){
		$("#remove_group").attr("disabled", false);
		$("#add_group").attr("disabled", true);
		$("#add_grp").val("");
	});
});
function toggleAction(jEle){
	var user = "";
	var action = $(jEle).data("action");
	if(action == "add"){
		user = $("#add_grp option:selected").val();
	}else{
		user = $("#remove_grp option:selected").val();
	}
	var d = {action:action, user:user}
	submitit(d, {
		done_reload : window.location.href
		
	});
}
</script>';

	softfooter();

}