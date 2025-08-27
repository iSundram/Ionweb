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

function import_cpanel_theme(){

global $user, $globals, $theme, $softpanel, $WE, $catwise, $error, $done, $import_log;


	softheader(__('Import From cPanel'));
	
echo'
<div class="card soft-card p-3 mb-4">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'import_cpanel.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Import From cPanel').'</h5>
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
	<div class="tab-content">
		<div class="tab-pane fade show active" id="import" role="tabpanel" aria-labelledby="import_a">
			<form accept-charset="'.$globals['charset'].'" name="editsettings" method="post" action="" id="editsettings" class="form-horizontal mb-3" onsubmit="return submitit(this)">
				<div class="row mb-3">
					<div class="col-lg-6 col-sm-12">
						<input type="checkbox" id="isbackup" name="isbackup" '.POSTchecked('isbackup', false).'" />	
						<label for="isbackup" class="sai_head control-label">'.__('I have a backup file (.tar.gz) saved in home directory').'
							<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Check for backup saved locally & uncheck for Remote Import').'"></i>
						</label><br />
						<div class="with-pass mt-3">
							<label for="r_domain" class="sai_head">'.__('cPanel Server Address (Required)').'
								<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('IP address or FQDN').'"></i>
							</label>
							<input type="text" name="r_domain" id="r_domain" class="form-control mb-3" value="'.POSTval('r_domain', '').'" />
							<label for="r_user" class="sai_head">'.__('User Name (Required).').'
								<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Same as Webuzo User Name').'"></i>
							</label>
							<input type="text" name="r_user" id="r_user" class="form-control mb-3" value="'.POSTval('r_user', '').'" />
							<label for="r_pass" class="sai_head ">'.__('Password').'
								<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('cPanel user password. If specified, the process will import data directly from cPanel.').'"></i>
							</label>
							<div class="input-group password-field mb-3 w-100">
								<input type="password" name="r_pass" id="r_pass" class="form-control" value="'.POSTval('r_pass', '').'" />
								<span class="input-group-text" onclick="change_image(this, \'r_pass\')">
									<i class="fas fa-eye"></i>
								</span>
							</div>
						</div>
						
						<div id="backup" style="display:none;">
							<label for="r_backup" class="sai_head ">'.__('cPanel backup file').'</label><br />
							<span class="sai_exp2">'.__('If specified, the process will import data from this file.').'<br/>'.__('The file should be stored locally inside $0 $2 $1 directory', ['<b>', '</b>', $WE->user['homedir']]).'</span>
							<div class="input-group mb-3">
								<span class="input-group-text" id="user_dir">'.$WE->user['homedir'].'/</span>
								<input type="text" name="r_backup" id="r_backup" class="form-control" value="'.POSTval('r_backup', '').'" />
							</div>
						</div>
						<input type="hidden" name="create_acc" value="1" /><br />
						<input type="submit" id="submitftp" name="create_acc" class="flat-butt" value="'.__('Submit').'" />
						<div class="text-center sai_notice mt-4">
							<b>'.__('Note:').'</b> '.__('This utility will import your data from cPanel. $0 Here $1 is the guide for the same.', ['<a href="https://webuzo.com/docs/endusers-website-owners/import-from-cpanel/" target="_blank" >', '</a>']).'<br><b style="color:red">'.__('This will overwite your all existing data.').'</b>
						</div>
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

$(document).ready(function(){				
	// Refresh the logs
	refreshInterval = setInterval(refresh_logs, 3000);
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
					m = show_message_r("Delete", "'.__js('Logs Cleared').'");
					m.alert = "alert-warning";
					show_message(m);
				}
				
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
