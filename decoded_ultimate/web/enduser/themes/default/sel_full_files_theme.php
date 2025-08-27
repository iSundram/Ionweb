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

function sel_full_files_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error, $done, $wbackup_path, $wbackup_log, $json_files, $backup_path, $type, $dir_check, $database_backup_list, $backup_data, $full_data, $dbbackups;
	
	
	if(optGET('refreshTable') && !empty($done) && optGET('refreshTable') == 'refresh_fullBackup'){
		show_table('full', '.tar.gz');
		return true;
	}
	
	softheader(__('Full Restore'));
	
	echo '
<style>
.sai_popup {
position:absolute;
background:#FFFFFF;
border:#666 1px solid;
display:none;
z-index:10000;
min-height:200px;
padding:5px;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
}

.fa-rotate-360{
-webkit-transform: rotate(360deg);
-moz-transform: rotate(360deg);
-ms-transform: rotate(360deg);
-o-transform: rotate(360deg);
transform: rotate(360deg);
transition: .8s;
}

@media screen and (min-width:320px) and (max-width: 560px) {
	.sai_popup{
		top: 50%;
		left: 50%;
		width:80%;
	}
	ul li {
		width:100%;
	}
}

@media screen and (min-width:560px) and (max-width: 1030px) {
	ul li {
		width:50%;
	}
			
}			
</style>

<script language="javascript" type="text/javascript">

$(document).ready(function(){
	
});

// Remove hash from URL before making ajax request
function remove_hash(w_l){
	if(w_l.indexOf("#") > 0){
		var unhashed_w_l = w_l.substring(0, w_l.indexOf("#"));
		return unhashed_w_l;
	}

	return w_l;
}
	
function restore_backup(filename, server_id, backup_type, backup_method, backup_location){
	var d = show_message_r(l.warning, "'.__js('Are you sure you want to restore this file ?').'");
	d.alert = "alert-warning";
	d.confirm.push(function(){	
		var d = "&restore=1&backup_filename="+filename+"&bak_server_id="+server_id+"&backup_method="+backup_method+"&backup_path="+backup_location;
					
		submitit(d, {
			sm_done_onclose: function(){			
				location.reload();					
			}
		});
	});

	show_message(d);
}
	
//get backup logs (and clear them if needed)
function get_logs(id){
	$(".loading").show();
	if(id == "clear_log"){
		dataval = "clearlog=1";
	}else{
		dataval = "";
	}
		
	var w_l = remove_hash(window.location.toString());
	$.ajax({
		type: "POST",
		url: w_l+"&api=json",
		data: dataval,
		dataType : "json",
		// checking for error
		success: function(data){
			$(".loading").hide();
			if("done" in data){
				if(id == "clear_log"){
					var d = show_message_r(l.done, "'.__js('Logs cleared').'");
					d.alert = "alert-success";
					show_message(d);
				}
				$("#showlog").text(data["wbackup_log"]);
			}else{
				var d = show_message_r("'.__js('Error').'", data["error"]);
				d.alert = "alert-danger";
				show_message(d);
			}
		},
		error: function(){
			$(".loading").hide();
			var d = show_message_r("'.__js('Error').'", \''.__js('Unable to connect to the server').'\');
			d.alert = "alert-danger";
			show_message(d);
		}
	});
}
	
function rotate_img(ev){
	$(".loading").show();
	
	var backup_id = ev.id;
	$("#"+backup_id).addClass("fa-rotate-360");	

	var w_l = remove_hash(window.location.toString());
	$.ajax({
		type: "POST",
		url: w_l+"&refreshTable="+backup_id,
		success: function(data){
			// console.log(data)
			$(".loading").hide();
			$("#"+backup_id).removeClass("fa-rotate-360");
			
			var output = data.substring(data.indexOf("<div"));
			$("#"+backup_id+"Display").html(output);	
		}
	});
};

function basename(path) {
	// console.log(path)
	var ret = path.split("/").pop();
	// console.log(ret)
	return ret;
}

function dirname(path) {
    var ret = path.split("/").slice(0, -1).join("/");
	console.log(ret)
    return ret;
}

</script>

<div class="card soft-card p-4">';
	backuply_tabs('backupfull');
	echo '
	<div class="tab-content" id="pills-tabContent">
		<!-- Full Backup -->
		<div class="tab-pane fade show active" id="backupfull" role="tabpanel" aria-labelledby="backupfull_a">	
			<div class="sai_main_head mb-4 mt-3">
				<i class="fas fa-cubes expander fa-2x"></i>
				<h5 class="d-inline-block">'. __('Full Backup') .'</h5>
				<span type="button" id="refresh_fullBackup" onclick="rotate_img(this);" class="refresh-icon float-end me-3 mt-3" title="'.__('Refresh Table').'">
					<i class="fas fa-sync-alt"></i>
				</span>
			</div>
			<div class="table-responsive mb-4" id="refresh_fullBackupDisplay">';
				show_table('full', '.tar.gz');
			echo '
			</div>				
			<div class="text-center" id="msg_backup_full"></div>
		</div>
		<!-- Full Backup end-->
	</div>
</div>
<div class="row">
	<div class="sai_popup"></div>
</div>';
	/* 
	// Restore PopUp
	echo '
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">'.__('Restore Summary').'</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body p-4 ">';
			if(!empty($error)){
				echo '<div class="alert alert-warning" role="alert">'.implode("<br>", $error).'</div>';
			}else{
		echo '
			<p class="modal_file_name" ></p>
			<div class="sai_main_head mb-4 row">
				<div class=" py-3 d-inline-block col-9">
					
				</div>
				<div class="col-3">
					
				</div>
			</div>
			<div id="div1">
				
			</div>';
			}
		echo '
		</div>
	</div>
</div>'; */
	
	softfooter();
	
}

function show_table($type, $file_type){

global $globals, $theme, $l, $softpanel, $wbackup_list, $backup_path;
	
	echo '	
<table border="0" cellpadding="6" cellspacing="1" width="100%" class="table align-middle table-nowrap mb-0 webuzo-table">
	<thead class="sai_head2">
		<tr>
			<th class="align-middle" width="25%">'.__('Filename').'</th>';
			if($type == 'home'){
				echo '<th class="align-middle" width="20%">'.__('Selected Files').'</th>';
			}
			echo'
			<th class="align-middle" width="15%">'.__('Created').'</th>
			<th class="align-middle" width="10%">'.__('Method').'</th>
			<th class="align-middle" width="10%">'.__('Server').'</th>
			<th class="align-middle" width="5%">'.__('Size').'</th>
			<th class="align-middle" width="5%">'.__('Options').'</th>
		</tr>
	</thead>
	<tbody id="'.$type.'_show_table">';
	
	if(empty($wbackup_list['full'])){
		echo '
		<tr class="text-center">
			<td colspan=11>
				<span>'.__('There are no backups files found').' !</span>
			</td>
		</tr>';
	}else{
		
		// r_print($wbackup_list);
		foreach($wbackup_list['full'] as $k => $v){
			// r_print($v);
			echo '
		<tr id="'.basename($v['filename']).'">
			<td>'.$v['filename'].'
				<input id="bac_path_'.$v['filename'].'" value="'.$v['backup_location'].'" style="display:none">
				<input id="bac_method_'.$v['filename'].'" value="'.$v['backup_method'].'" style="display:none">
				<input id="bac_filename_'.$v['filename'].'" value="'.$v['filename'].'" style="display:none">
				<input id="bac_server_'.$v['filename'].'" value="'.$v['backup_server_id'].'" style="display:none">
			</td>';
			if($type == 'home'){
				echo '<td>'.($v['backup_method'] == 'compressed' ? 'All Files/Directories Selected' : 'All Files/Directories Selected<br><a href="#" class="btn btn-primary btn-sm restore_file_select" id="res_sel_'.basename($v['filename']).'" onclick="showSubs(\''.NULL.'\', \''.$v['filename'].'\', \''.$v['backup_server_id'].'\');" title="'.__('Partial Remote Restore').'">Change Files Selection</a>').'</td>';
			}
			// echo $v['size'];
			echo '
			<td>'.datify($v['created']).'</td>
			<td>'.ucfirst(!empty($v['backup_method']) ? $v['backup_method'] : 'compressed').'</td>
			<td>'.$v['server_name'].' ('.($v['backup_server_id'] == -1 ? 'Local' : 'Remote').')</td>
			<td>'.(!empty($v['size']) ? trim(round(($v['size']/1024)/1024, 2).' M') : "--").'</td>';
			
			echo '
			<td onclick="restore_backup(this.parentNode.id, '.$v['backup_server_id'].', \''.$v['type'].'\', \''.$v['backup_method'].'\', \''.$v['backup_location'].'\');" class="text-center">
				<i class="fas fa-undo restore edit-icon" title="'.__('Restore').'"></i>
			</td>
			</tr>';
		}
	}
	echo '
	</tbody>
</table>';	
}
