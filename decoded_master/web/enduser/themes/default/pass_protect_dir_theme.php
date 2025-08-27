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

function pass_protect_dir_theme(){

global $user, $globals, $theme, $softpanel, $WE, $list_users, $error, $done;

	softheader(__('Password Protect Directory'));

	echo '
<div class="card soft-card p-3">
	<div class="sai_main_head ">
		<img src="'.$theme['images'].'email_account.gif" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Password Protect Directory').'</h5>
	</div>
</div>
<div class="card soft-card p-4 mt-4">
	<div class="modal fade" id="add-passprotected" tabindex="-1" aria-labelledby="add-ppdLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="add-ppdLabel">'.__('Add Password Protected Directory').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<form accept-charset="'.$globals['charset'].'" action="" method="post" id="frm_protect_dir" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
						<label for="dir_path" class="form-label me-1">'.__('Path to Directory').'</label>
						<i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Specify the path to password protect relative to your user directory. For e.g public_html/test_path').'"></i>
						<div class="input-group mb-3">
							<span class="input-group-text">'.$WE->user['homedir'].'</span>
							<input type="text" name="dir_path" id="dir_path" class="form-control" value="'.stripslashes(POSTval('dir_path', 'public_html/')).'" onfocus=""/>
						</div>
						<label for="dir_name" class="form-label me-1" id="type">'.__('Name the protected directory').'</label>
						<i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Alias name for the Password Protected Directory').'"></i>
						<input type="text" id="dir_name" name="dir_name" class="form-control mb-3" value="'.POSTval('dir_name', '').'" />
						<label for="username" class="form-label me-1" id="type">'.__('Username').'</label>
						<i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Username for the Password Protected Directory').'"></i>
						<input type="text" id="username" name="username" class="form-control mb-3" value="'.POSTval('username', '').'" />
						<div>
							<label for="password" class="sai_head form-label me-1" id="type">'.__('Password').'</label>
							<span class="sai_exp">'.__('Password strength must be greater than or equal to $0', [pass_score_val('user')]).'</span>
						</div>
						<div class="input-group password-field">
							<input type="password" id="password" name="password" class="form-control" onkeyup="check_pass_strength($(\'#password\'), $(\'#pass-prog-bar\'))" value="" />
							<span class="input-group-text" onclick="change_image(this, \'password\')">
								<i class="fas fa-eye"></i>
							</span>							
							<a class="random-pass" href="javascript: void(0);" onclick="rand_val=randstr(10,'.pass_score_val('user').');$_(\'password\').value=rand_val;$_(\'re_password\').value=rand_val;check_pass_strength($(\'#password\'), $(\'#pass-prog-bar\'));return false;" title="'.__('Generate a Random Password').'">
								<i class="fas fa-key"></i>
							</a>
						</div>
						<div class="progress pass-progress mb-3">
							<div class="progress-bar bg-danger" id="pass-prog-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
								<span>0</span>
							</div>
						</div>
						<label for="re_password" class="form-label me-1" id="type">'.__('Password (Again)').'</label>
						<i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Confirm Password for the Password Protected Directory').'"></i>
						<input type="password" id="re_password" name="re_password" class="form-control mb-3" value="" />
						<center>
							<input type="submit" class="flat-butt me-2" value="'.__('Save').'" name="add_pass_protect" id="submit_pass_protect" />
							<img id="createimg" src="'.$theme['images'].'progress.gif" style="display:none">
						</center>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="sai_sub_head record-table mb-2 position-relative">
		<button type="button" class="flat-butt" data-bs-toggle="modal" data-bs-target="#add-passprotected">'.__('Add Directory').'</button>
		<input type="button" class="btn btn-danger delete_selected float-end" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_protected_dir(this)" disabled>
	</div>	
	<div id="showrectab" class="table-responsive">	
		<table class="table align-middle table-nowrap mb-0 webuzo-table">			
		<thead class="sai_head2">
			<tr>
				<th><input type="checkbox" id="checkall"></th>
				<th class="align-middle">'.__('Password Protected Directory Path').'</th>
				<th class="align-middle">'.__('Username').'</th>
				<th class="align-middle">'.__('Options').'</th>
			</tr>					
		</thead>
		<tbody>';
		$flag = 0;
		foreach($list_users as $key => $value) {
			if(!empty($value['users'])){
				$flag = 1;
				break;
			}
		}

		$i = 1;

	if(empty($flag)){
		echo '
			<tr>
				<td colspan="100" class="text-center">
					<span>'.__('No password protected directories found').'</span>
				</td>
			</tr>';
	}else{
		foreach ($list_users as $key => $value){
				
			foreach($value['users'] as $kk => $vv){
				echo '
			<tr id="tr'.str_replace("/", "-", $value['spath']).''.$vv.'">
				<td><input type="checkbox" class="check_passpro" name="check_passpro" value="'.$vv.'" data-user="'.$vv.'" data-path="'.$value['spath'].'" data-delete="1"></td>
				<td>'.$value['spath'].'</td>
				<td>'.$vv.'</td>
				<td width="2%" class="text-center">
					<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$kk.'" onclick="delete_record(this)" data-user="'.$vv.'" data-path="'.$value['spath'].'" data-delete="1"></i>
				</td>
			</tr>';
			}
		}
	}
	echo '
		</tbody>
		</table>	
	</div>	
</div>
<script>

$(document).ready(function(){
	
	$("#checkall").change(function(){
		$(".check_passpro").prop("checked", $(this).prop("checked"));
	});
	
	$("input:checkbox").change(function(){
		if($(".check_passpro:checked").length){
			$("#delete_selected").removeAttr("disabled");
		}else{
			$("#delete_selected").prop("disabled",true);
		}
	});
});

function del_protected_dir(el){
	var a;
	var jEle = $(el);
	var arr = [];
	var arr2 = [];
	var arr3 = [];
	
	$("input:checkbox[name=check_passpro]:checked").each(function(){
		var user = $(this).val();
		var path = $(this).attr("data-path");
		var deletes = $(this).attr("data-delete");
		arr.push(user);
		arr2.push(path);
		arr3.push(deletes);
		
	});
	
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete selected password protected Directory(s) ?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"delete" : arr3.join(), "path" : arr2.join(), "user" : arr.join() };
		submitit(d ,{done_reload : window.location.href});
	});
	
	show_message(a);
}
</script>';

	softfooter();
}

