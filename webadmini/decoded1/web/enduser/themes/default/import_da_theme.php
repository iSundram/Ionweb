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

function import_da_theme(){
	
	global $user, $globals, $theme, $softpanel, $error, $done, $import_log, $local_users;
	
	softheader(__('Import From DirectAdmin'));

echo'
<div class="card soft-card p-3 mb-4">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'import_da.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Import From DirectAdmin').'</h5>
	</div>
</div>
<div class="card soft-card p-4">
	<ul class="nav nav-tabs mb-3 webuzo-tabs" id="panel-heading-part" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active" id="import_a" data-bs-toggle="tab" data-bs-target="#import" type="button" role="tab" aria-controls="import" aria-selected="true">'.__('Import').'</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="import_log_a" data-bs-toggle="tab" data-bs-target="#import_log" type="button" role="tab" aria-controls="import_log" aria-selected="false">'.__('Logs').'</button>
		</li>
	</ul>
	<div class="tab-content" id="panel-body-part">
		<div class="tab-pane fade show active" id="import" role="tabpanel" aria-labelledby="import_a">
			<form accept-charset="'.$globals['charset'].'" name="importform" method="post" action="" id="importform" class="form-horizontal mb-3" onsubmit="return submitit(this)">
				<div class="row mb-3">
					<div class="col-md-6 col-sm-12">
						<input type="checkbox" id="isbackup" name="isbackup" '.POSTchecked('isbackup', false).'" />	
						<label for="isbackup" class="sai_head control-label">'.__('I have a backup file (.tar.zst) saved in home directory').'
							<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Check for backup saved locally & uncheck for Remote Import').'"></i>
						</label><br />
						<div class="with-pass">
							<label for="r_domain" class="sai_head">'.__('DirectAdmin Server Address (Required)').'
								<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('IP address or FQDN').'"></i>
							</label>
							<input type="text" name="r_domain" id="r_domain" class="form-control mb-3" value="'.POSTval('r_domain', '').'" />
							<label for="r_user" class="sai_head">'.__('User Name (Required)').'
								<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Same as Webuzo User Name').'"></i>
							</label>
							<input type="text" name="r_user" id="r_user" class="form-control mb-3" value="'.POSTval('r_user', '').'" />
							<label for="r_pass" class="sai_head ">'.__('Password').'
								<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('DirectAdmin user password. If specified, the process will import data directly from Remote DirectAdmin Server.').'"></i>
							</label>
							<div class="input-group password-field mb-3 w-100">
								<input type="password" name="r_pass" id="r_pass" class="form-control" value="'.POSTval('r_pass', '').'" />
								<span class="input-group-text" onclick="change_image(this, \'r_pass\')">
									<i class="fas fa-eye"></i>
								</span>
							</div>
							<input type="checkbox" id="scan_backups" name="scan_backups" '.POSTchecked('scan_backups', false).'" onclick="e_scan_backups()"/>	
							<label for="scan_backups" class="sai_head control-label">'.__('Scan existing backups (.tar.zst) saved in home directory at DirectAdmin').'
							</label><br />
							<br>
							<div class="input-group scan_backups-field mb-3 w-100">
								
								<div class="scanbackuppath d-none">
									<div class="input-group scanbackuppath-field mb-3 w-100">
										<span class="input-group-text">
											'.__('Backup Directory').'
										</span>
										<input type="text" name="scan_backups_path" id="scan_backups_path" class="form-control" value="'.POSTval('scan_backups_path', 'backups').'" />
										<input type="button" id="enduser_import_scan" name="enduser_import_scan" class="flat-butt btn" value="'.__('Scan').'"/>
									</div>
								</div>
								<div class="col-md-6 w-100" id="backupop_div" style="display:none">
								</div>
							</div>
						</div>
						<div id="backup" style="display:none;">
							<label for="r_backup" class="sai_head ">'.__('DirectAdmin backup file').'</label><br />
							<span class="sai_exp2">'.__('If specified, the process will import data from this file.').'<br/>'.__('The file should be stored locally inside $0 /home/webuzo-username $1 directory', ['<b>', '</b>']).'</span>
							<input type="text" name="r_backup" id="r_backup" class="form-control" value="'.POSTval('r_backup', '').'" />
						</div>
						<input type="hidden" name="create_acc" value="1" /><br />
						<input type="submit" id="submitftp" name="create_acc" class="flat-butt" value="'.__('Submit').'" />
					</div>
				</div>
			</form>
		</div>
		<div class="tab-pane fade" id="import_log" role="tabpanel" aria-labelledby="import_log_a">
			<div class="innertabs mb-3" nowrap="nowrap">
				<textarea class="form-control" id="showlog" readonly="readonly" wrap="off">'.$import_log.'</textarea>
			</div>
			<div class="text-center">
				<input type="button" class="flat-butt" id="get_log" value="'.__('Refresh Logs').'" onclick="get_logs(this);" />
				<input type="button" class="flat-butt" id="clear_log" value="'.__('Clear Logs').'" onclick="get_logs(this);" data-clearlog=1 />
			</div>
		</div>
	</div>
</div>';

	echo '		
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[

var refreshInterval;

$("#scan_backups").prop("checked", false);
e_scan_backups();

$(document).ready(function(){
	// Refresh the logs
	refreshInterval = setInterval(refresh_logs, 3000);
});

function e_scan_backups(){
	
	var is_checked = $("#scan_backups").is(":checked");
	// console.log(is_checked)
	if(is_checked){
		$(".scanbackuppath").removeClass("d-none");
	}else{
		$(".scanbackuppath").addClass("d-none");
	}
	
	if($("#backupop_div").is(":visible")){
		$("#backupop_div").hide();
	}
}

$("#enduser_import_scan").click(function(){
		
	$("#backupop_div").hide();
	
	var host = $("#r_domain").val();
	var username = $("#r_user").val();
	var pass = $("#r_pass").val();		
	var backup_path = $("#scan_backups_path").val();		
	// console.log("scanning ...."); return false;
	var d = {"create_acc": 1, "scan_backups": 1, "r_domain" : host, "r_user" : username, "r_pass" : pass, "scan_backups_path" : backup_path};
	submitit(d, {
		handle:function(data, p){
			//console.log(data, p);return false;
			var resp = data;
			
			if(resp.error){
				var err = Object.values(resp.error);
				var a = show_message_r("'.__js('Error').'", err);
				a.alert = "alert-danger"
				show_message(a);
				return false;
			}
			
			// console.log(resp.backups_list);
			
			$("#backupop_div").show();
			if(empty(data.backups_list)){
				$("#backupop_div").html("<label class=\"sai_head alert alert-warning\">'.__js('No Backups found on DirectAdmin').'</label>");
				$(".loading").hide();
				return false;
			}
			
			blist = \'<label for="backupop_name" class="sai_head">'.__js('Select Bakcup to import').'</label><br><span class="sai_exp2">'.__js('This process will import data from the selected backup file.').'<br/>'.'</span><select class="form-control" name="backupop_name" id="backupop_name" >\';
			
			$.each(data.backups_list, function(key,val){
				blist += \'<option value="\'+val+\'">\'+val+\'</option>\';
			})
			
			blist += \'</select>\';
			$("#backupop_div").show();	
			$("#backupop_div").html(blist);
			$(".loading").hide();
			
		}
	});
});

$("#submitftp").click(function(){	
	$("#import_log_a").click();
});	

$("#isbackup").on("change", function(){
	if($("#isbackup").is(":checked")){
		$("#r_pass").val("");
		$("#r_domain").val("");
		$("#r_user").val("");
		$("#no_backup").hide();
		$("#backup").show();
		$(".with-pass").hide();
	}else{
		$("#r_backup").val("");
		$("#backup").hide();
		$("#no_backup").show();
		$(".with-pass").show();
	}
});

// Get cPanel import logs (and clear them if needed)
function get_logs(jEle){
	
	var m;
	var d = $(jEle).data();
	
	$(".loading").show();
	return submitit(d, {
		handle: function(data){
			
			if("done" in data){
				$(".loading").hide();
				if("clearlog" in d){
					m = show_message_r("'.__js('Delete').'", "'.__js('Logs Cleared').'");
					m.alert = "alert-warning";
					show_message(m);
				}
				// console.log(data["import_log"]);
				$("#showlog").text(data["import_log"]);
				
			}else{
				handleResponseData(data);
			}
		}
	});
}

// Refresh the logs automatically
function refresh_logs(){
	
	if($("#showlog").is(":visible")){
		$("#get_log").click();
	}	
};

// ]]></script>';

		
		softfooter();
	
}

