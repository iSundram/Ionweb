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

function compiler_access_theme(){

global $theme, $softpanel, $error, $done, $globals, $ca_users, $users;

	softheader(__('Compiler Access'));
	
		echo '
<div class=" soft-smbox col-12 mx-auto p-3">
	<div class="sai_main_head">
			<i class="fas fa-laptop-code fa-xl"></i> '.__('Compiler Access').'
	</div>
</div>
<div class=" soft-smbox col-12 mx-auto p-4 mt-4">
	<div class="row mb-3 text-center">
		<h4 class="sai_sub_head d-inline-block">'.__('Many utilities require C compiler on system. This utility allows you to manage compiler access of users. Users not added here will not have access to compiling utilities for security').'</h4>
	</div>
	<div class="alert alert-info">
		<i class="fas fa-info-circle"></i> <span>'.(empty($globals['compiler_access']) ? __('Compiler Access is enabled for all users') : __('Compiler Access is disabled for users who are not in the $0 compiler $1 group', ['<b>', '</b>'])).'</span> <button class="btn btn-primary ms-3" data-do = "'.(empty($globals['compiler_access']) ? 1 : 0).'" onclick="manage_access(this)">'.(empty($globals['compiler_access']) ? __('Disable for user(s) not in <b>compiler</b> group') : __('Enable for all user(s)')).'</button>
	</div>
	<div class="text-center mb-4">
		<label class="sai_head" for="user_search">'.__('Add a user into the compiler group').' :-</label>
		<select class="form-select ms-1 make-select2" style="width: 250px" id="user">
			<option value="0" selected>'.__('Select User').'</option>';
		
		foreach($users as $ca_user){
			echo '<option value="'.$ca_user.'">'.$ca_user.'</option>';
		}
		
		echo '
		</select>
		<button class="btn btn-primary ms-3" onclick="return add_ca_user()">'.__('Add into the Group').'</button>
	</div>
	<div class="table-responsive mt-4">
		<table class="table sai_form webuzo-table">
			<thead>
				<tr>
					<th>#</th>
					<th>'.__('Compiler group user(s)').'</th>
					<th style="text-align:right">'.__('Options').'</th>
				</tr>
			</thead>
			<tbody>';

			if(empty($ca_users)){
				
				echo '
				<tr>
					<td colspan="3" class="text-center">'.__('There is no user in the Compiler group yet !').'</td>
				</tr>';
				
			}else{
				
				foreach($ca_users as $k => $v){
				
					echo '
				<tr id="tr'.$v.'">
					<td>'.$k.'</td>
					<td>'.$v.'</td>
					<td style="text-align:right"><i class="fas fa-trash delete delete-icon" title="Delete" id="did'.$v.'" data-remove=1 data-user="'.$v.'" onclick="delete_record(this)"></i></td>
				</tr>';				
				
				}
			}
			
			echo '
			</tbody>
		</table>
	</div>
</div>

<script>

function manage_access(ele){
	var jEle = $(ele);
	var d = jEle.data();
	d.manage_access = 1;
	var lang = "";
	if(d.do == "0"){
		lang = "'.__js('Are you sure you want to enable compiler access for all users ?').'";
	}else{
		lang = "'.__js('Are you sure you want to disable compiler access for users not in the compiler group ?').'";
	}
	
	var a = show_message_r("'.__js('Warning').'", lang);
	a.alert = "alert-warning";
	a.confirm.push(function(){
		submitit(d, {
			done_reload : window.location.href
		});
	});
	show_message(a);
}

function add_ca_user(){
	var d  = {user : $("#user").val(), add : 1};
	var a = show_message_r("'.__js('Warning').'", "'.__js('Do you want to add the user to the compiler group ?').'");
	a.alert = "alert-warning";
	a.confirm.push(function(){
		submitit(d, {
			done_reload : window.location.href
		});
	});
	show_message(a);
}
</script>';

	softfooter();
}