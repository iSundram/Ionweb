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

function resource_limits_theme(){

global $globals, $SESS, $theme, $softpanel, $error, $done, $limits, $v2_support;
	
	softheader(__('Resource Limits'));
	
	echo '
<style>
.select2-container--open .select2-dropdown{
    z-index: 999999 !important;
}

.modal-content{
    z-index: 9999 !important;
}

.di_item{
	list-style-type:none;
	color: #000;
	display: inline-block;
	margin-bottom: 3px;
	background: #e3e5ef;
	border-radius: 3px;
	align-items: center;
	justify-content: space-between;
	cursor: pointer;
	max-width: fit-content;
}

.user_button{
    background-color: transparent;
    border: none;
    border-right: 1px solid #aaa;
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
    color: #999;
    cursor: pointer;
    font-size: 1em;
    font-weight: bold;
    padding: 0 4px;
    left: 0;
    top: 0;
}
</style>
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-th-list fa-xl me-2"></i>'.__('Resource Limits');
		
		if(empty($v2_support)){
			if($globals['cgroup_version'] != 'cgroup2fs'){
				echo '
			<input type="button" data-enable_v2=1 onclick="enable_v2(this)" class="btn btn-primary float-end" value="'.__('Enable cGroups v2').'">';
			
			}elseif($SESS['user'] == 'root'){
				echo '
			<span class="search_btn float-end">
				<a type="button" class="float-end" data-bs-toggle="modal" data-bs-target="#settings_conf"><i class="fas fa-cogs"></i></a>
			</span>';
			}
		}
		
		echo '
	</div>
</div>
<div class="soft-smbox p-4 mt-4">';
	
	error_handle($error);
	
	if(empty($error)){
		echo '
	<div class="modal fade" id="settings_conf" tabindex="-1" aria-labelledby="atop_confLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="atop_conf_label">'.__('Settings').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
				<form action="" method="POST" name="settings_config" id="settings_config" class="form-horizontal" onsubmit="return submitit(this)">
					<div class="row mb-3">
						<div class="col-12 col-md-3 col-lg-3">
							<label class="sai_head" for="disable_res">'.__('Disable reseller limits').'
								<span class="sai_exp" id="disable_res">'.__('Disabling reseller limits will restrict the Resource Limits feature in the reseller\'s panel.').'</span>
							</label>
						</div>
						<div class="col-12 col-md-7 mb-1">
							<input type="checkbox" name="disable_reseller_cgroup_limit" '.POSTchecked('disable_reseller_cgroup_limit', $globals['disable_reseller_cgroup_limit']).' class="me-1" />
						</div>
					</div>
					<div class="row mb-2 text-center">
						<div class="col-12">
							<input class="btn btn-primary" type="submit" name="settings_conf" id="settings_conf" value="'.__('Save').'">
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordLabel" aria-hidden="true">
		<div class="modal-dialog" style="max-width:800px;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addRecordLabel">'.__('Add/Edit Resource Limit Plan').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('IO Read Bandwidth Max, IOPS Read Max, IO Write Bandwidth Max, IOPS Write Max, Memory Max and Memory High values will be considered in bytes if K, M, G or T are missing at the end of their values.').'"></i></h5>
					<button type="button" class="btn-close add_record_close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<center><b>Note</b>: '.__('Blank fields will be considered as unlimited values.').'</center>
					<div class="table-responsive mb-3">
						<table width="100%" class="table align-middle table-nowrap mb-0 webuzo-table">
						<tbody>
							<tr>
								<td>
									'.__('Resource Limit Plan Name').'
								</td>
								<td>
									<input type="text" id="plan" class="form-control textbox-class">
								</td>
							</tr>
							<tr>
								<td>
									'.__('CPU Quota (in %)').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Percentage of CPU limit (100% refers to 1 core. >100% for more cpu cores).').'"></i>
								</td>
								<td>
									<input type="number" id="cpuquota" class="form-control textbox-class" placeholder="'.__('Value must be greater than 25').'">
								</td>
							</tr>
							<tr>
								<td>
									'.__('IO Read Bandwidth Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max rate data can be read from a disk.').'"></i>
								</td>
								<td>
									<input type="text" id="read_bw" class="form-control textbox-class" placeholder="'.__('Example: 256K, 256M, 256G, 256T').'">
								</td>
							</tr>
							<tr>
								<td>
									'.__('IO Write Bandwidth Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max rate data can be written to a disk.').'"></i>
								</td>
								<td>
									<input type="text" id="write_bw" class="form-control textbox-class"  placeholder="'.__('Example: 256K, 256M, 256G, 256T').'">
								</td>
							</tr>
							<tr>
								<td>
									'.__('IOPS Read Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max disk read operations per second.').'"></i>
								</td>
								<td>
									<input type="text" id="diskread" class="form-control textbox-class"  placeholder="'.__('Value must be greater than 256').'">
								</td>
							</tr>
							<tr>
								<td>
									'.__('IOPS Write Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max disk write operations per second.').'"></i>
								</td>
								<td>
									<input type="text" id="diskwrite" class="form-control textbox-class"  placeholder="'.__('Value must be greater than 256').'">
								</td>
							</tr>
							<tr>
								<td>
									'.__('Memory Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Absolute Memory Limit. Triggers Out-Of-Memory Killer.').'"></i>
								</td>
								<td>
									<input type="text" id="mem_max" class="form-control textbox-class"  placeholder="'.__('Example: 128M, 128G, 128T').'">
								</td>
							</tr>
							<tr>
								<td>
									'.__('Memory High').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Throttle Memory above this Limit.').'"></i>
								</td>
								<td>
									<input type="text" id="memhigh" class="form-control textbox-class"  placeholder="'.__('Example: 128M, 128G, 128T').'">
								</td>
							</tr>
							<tr>
								<td>
									'.__('Tasks Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max Tasks that may be created in the unit').'"></i>
								</td>
								<td>
									<input type="number" id="maxtask" class="form-control textbox-class"  placeholder="'.__('Value must be greater than 10').'">
								</td>
							</tr>
						</tbody>
						</table>
					</div>
					<div class="text-center my-3">
						<input type="button" class="btn btn-primary" value="'.__('Save').'" data-save=1 onclick="save_plan(this, edit)" id="save"> 
					</div>';
						
					echo '		
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addUserLabel">'.__('Assign Plan to Users').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Old plans\' limits of the selected users will be replaced with the newly assigned plan\'s limits.').'"></i></h5>
					<button type="button" class="btn-close add_user_close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="table-responsive mb-3">
						<table width="100%" class="table align-middle table-nowrap mb-0 webuzo-table">
						<tbody>
							<tr>
								<td>
									'.__('Plans').'
								</td>
								<td>
									<select class="form-select" id="select_plan">
										<option value="">'.__('[No Plan]').'</option>';
									
									foreach($limits as $key => $val){
										echo '<option value="'.$key.'">'.$key.'</option>';
									}
									
									echo '
									</select>
								</td>
							</tr>
							<tr>
								<td>
									'.__('Users').'
								</td>
								<td>
									<input type="checkbox" id="allusers">	
									<label class="form-label" for="allusers">'.__('All Users').'</label><br>
									<select id="select_users" class="form-select make-select2" s2-placeholder="'.__('All Users').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" style="width:100%" multiple>
									</select>
								</td>
							</tr>
						</tbody>
						</table>
					</div>
					<div class="text-center my-3">
						<input type="button" class="btn btn-primary" value="'.__('Assign').'" data-assign_plan=1 onclick="assign_plan(this)"> 
					</div>';
						
					echo '		
				</div>
			</div>
		</div>
	</div>
	<div class="row my-2">
		<div>
			<button type="button" class="btn btn-primary add_record mb-1" data-bs-toggle="modal" data-bs-target="#addRecordModal" id="add_plan">'.__('Add Resource Limit Plan').'</button>
			<button type="button" class="btn btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#addUserModal">'.__('Assign Plan to Users').'</button>
		</div>
	</div>
	<div id="showcgroups" class="table-responsive">
		<table class="table sai_form webuzo-table" id="planlisttable">
			<thead>	
				<tr>
					<th width="20%">'.__('Resource Limit Plan').'</th>
					<th width="10%">'.__('Owner').'</th>
					<th width="50%">'.__('Users').'</th>
					<th width="10%">'.__('Num Users').'</th>
					<th colspan="2" width="10%">'.__('Options').'</th>
				</tr>
			</thead>
			<tbody>';
			
			if(!empty($limits)){
				foreach($limits as $key => $val){
					echo '
				<tr>
					<td>'.$key.'</td>
					<td>'.$val['owner'].'</td>
					<td><ul class="nav">';
					
					$tmp_holder = [];
					foreach($val['users'] as $user){
						$tmp_holder[] = '<li class="di_item p-1 m-1" id="li_'.$user.'"><button type="button" id="remove_'.$user.'" value="'.$user.'" data-plan="'.$key.'" data-user="'.$user.'" class="user_button" onclick="remove_user(this)"><span aria-hidden="true" style="color:red">×</span></button><span> '.$user.'</span></li>';
					}
					echo implode(' ', $tmp_holder).'</ul></td>
					
					<td>'.count($val['users']).'</td>
					<td><button type="button" class="btn btn-primary add_record edit" data-bs-toggle="modal" data-bs-target="#addRecordModal" id="edit_plan" data-edit="'.$key.'" onclick="edit_plan(this)">'.__('Edit').'</button></td>
					<td><input type="button" class="btn btn-primary" data-delete="'.$key.'" onclick="delete_cgroup(this)" class="form-control" value="'.__('Delete').'"></td>
				</tr>';
				}
				
			}else{
				echo '<tr><td colspan=8><h3 style="text-align: center">'.__('No Record Found').'</h3></td></tr>';
			}
				
			echo '
			</tbody>
		</table>
	</div>';
	}
	
	echo '
</div>

<script>
var edit;
$("#add_plan").click(function(){
	edit = 0;
});

$(".edit").click(function(){
	edit = $("#plan").val();
});

$("#allusers").click(function(){
	if($("#allusers").is(":checked")){
		$("#select_users").prop("disabled", true);
	}else{
		$("#select_users").prop("disabled", false);
	}		
});

function enable_v2(el){
	var jEle = $(el);
	submitit(jEle.data(), {done_reload: window.location});
}

function assign_plan(el){
	var jEle = $(el);
	jEle.data("plan", $("#select_plan").val());
	jEle.data("users", $("#select_users").val());
	jEle.data("allusers", $("#allusers").is(":checked"));

	var d = jEle.data();
	submitit(d, {done_reload: window.location});
}
	
function save_plan(el, edit){
	var jEle = $(el);
	
	jEle.data("edit", edit);
	jEle.data("plan", $("#plan").val());
	jEle.data("cpuquota", $("#cpuquota").val());
	jEle.data("read_bw", $("#read_bw").val());
	jEle.data("write_bw", $("#write_bw").val());
	jEle.data("diskread", $("#diskread").val());
	jEle.data("diskwrite", $("#diskwrite").val());
	jEle.data("mem_max", $("#mem_max").val());
	jEle.data("memhigh", $("#memhigh").val());
	jEle.data("maxtask", $("#maxtask").val());
	
	var d = jEle.data();
	submitit(d, {done_reload: window.location});
}

function remove_user(el){
	var a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to remove plan for the user?').'");
	a.alert = "alert-warning";
	
	var jEle = $(el);
	jEle.data("remove_user", 1);
	var d = jEle.data();
	
	a.confirm.push(function(){
		submitit(d, {done_reload : window.location});
	});
	
	show_message(a);
}

function edit_plan(el){
	var jEle = $(el);
	var limits = '.json_encode($limits).';

	$("#plan").val(jEle.data().edit);
	$("#cpuquota").val(limits[jEle.data().edit]["cpuquota"]);
	$("#read_bw").val(limits[jEle.data().edit]["read_bw"]);
	$("#diskread").val(limits[jEle.data().edit]["diskread"]);
	$("#write_bw").val(limits[jEle.data().edit]["write_bw"]);
	$("#diskwrite").val(limits[jEle.data().edit]["diskwrite"]);
	$("#mem_max").val(limits[jEle.data().edit]["mem_max"]);
	$("#memhigh").val(limits[jEle.data().edit]["memhigh"]);
	$("#maxtask").val(limits[jEle.data().edit]["maxtask"]);
}

$("#add_plan").click(function(){
	$(".textbox-class").val("");
});

function delete_cgroup(el){
	var a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete the plan?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		submitit($(el).data(), {done_reload: window.location});
	});
	
	show_message(a);
}
</script>';

	softfooter();
}