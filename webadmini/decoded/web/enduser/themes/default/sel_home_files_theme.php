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

function sel_home_files_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error, $done, $wbackup_path, $wbackup_log, $json_files, $backup_path, $type, $dir_check, $database_backup_list, $backup_homepath;

	if(optGET('show_files')){
		
		echo '
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title">'.__('Select Files').'</h5>
			<button type="button" class="btn-close file_selection" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body p-4 ">';
			if(!empty($error)){
				echo '<div class="alert alert-warning" role="alert">'.implode("<br>", $error).'</div>';
			}else{
		echo '
			<p class="modal_file_name" ></p>
			<div class="sai_main_head mb-4 row">
				<div class=" py-3 d-inline-block col-9">
					<i class="fa fa-arrow-circle-left" title="back" onclick="showSubs(\''.$backup_path.'\', \''.basename($backup_path).'\', \''.NULL.'\', 1)" id="back_path"></i>
					<span id="files_route"> <i class="fas fa-home home-directory" > '.$backup_homepath.'</i></span>
				</div>
				<div class="col-3">
					<button class="float-end border-0 p-2">
						<input type="checkbox" name="show_hidden_files" id="show_hidden_files">
						<label for="show_hidden_files" >'.__('Show Hidden Files').'</label>
					</button>
				</div>
			</div>
			<div id="div1">';
				echo'<table border="0" cellpadding="6" cellspacing="1" width="100%" class="table align-middle table-nowrap mb-0 webuzo-table">';
				// echo '<table class="table align-middle table-nowrap mb-0 webuzo-table">';
				echo '
					<thead class="sai_head2">
						<tr>
							<th width="1%"><input type="checkbox" id="checkAllFiles"></th>
							<th class="align-middle" width="35%">'.__('Filename').'</th>
							<th class="align-middle" width="10%">'.__('size').'</th>
							<th class="align-middle" width="10%">'.__('Type').'</th>
							<th class="align-middle" width="20%">'.__('Created').'</th>
						</tr>
					</thead>
						<tbody id="full_show_table">';
							listDir($backup_path);
						echo'
						</tbody>
					</table>
					<div class="col-12">
						<div class="sai_sub_head record-table m-3 position-relative" style="text-align:center;">
							<input type="button" class="btn btn-primary" value="'.__('Restore Selection').'" name="restore_selected" id="restore_selected" onclick="" >
						</div>
					</div>
				</div>
			</div>';
			}
		echo '
		</div>
		</div>
	</div>
</div>
	
	
<script language="javascript" type="text/javascript">

$("#show_hidden_files").change(function() {
	if($("#show_hidden_files").is(":checked")){
		$(".selection_files_tr").show();
	} else{
		$(".selection_files_tr").hide();
	}
});

$("input:checkbox[name=checked_file]").each(function(){
	// back directory add check for selected files
	if(sel_files_val.includes($(this).val())){
		var checkfile = $(this).val();
		checkfile = checkfile.replace(/[./]/g, "_");
		$("#check_"+checkfile).prop("checked", true);
	}
});

$("#checkAllFiles").change(function () {
	$(".check").prop("checked", $(this).prop("checked"));
});

$("#restore_selected").click(function(){
	
	$(".loading").show();
	
	$("input:checkbox[name=checked_file]:checked").each(function(){

		if($(this).is(":visible") == true && !sel_files_val.includes("'.$backup_path.'") && !sel_files_val.includes($(this).val())){
			selecetd_files.push($(this).val());
			sel_files_val.push($(this).val());
			sel_files_key.push("'.$backup_path.'");
		}
			
	});
	
	selecetd_files_arr = JSON.stringify(selecetd_files);
	
	// console.log(selecetd_files_arr);
	
	var d = "&show_files=1&restore=1&sel_restore=1&selecetd_files_arr="+selecetd_files_arr+"&backup_filename="+backup_filename

	// var dw = show_message_r(l.warning, "'.__js('Are you sure you want to restore the selected files ?').'"+selecetd_files_arr);

	// dw.alert = "alert-warning";
	// dw.confirm.push(function(){	
		submitit(d, {
			sm_done_onclose: function(){
				
				// Close File Selection modal
				$(".file_selection").click();
				
				// Hide loading
				$(".loading").hide();
				
				// Reload the page
				location.reload(true);
			}
		});
	// })

	// show_message(dw);
});

</script>';

		return true;
	}
	
	if(optGET('refreshTable') && !empty($done) && optGET('refreshTable') == 'refresh_homeBackup'){
		show_table('full', '.tar.gz');
		return true;
	}	
	
	softheader(__('Backuply Home'));
	
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

function onChange() {
	return $("#backup_file").val();
}

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
		var d = "&restore_home=1&backup_filename="+filename+"&bak_server_id="+server_id+"&backup_method="+backup_method+"&backup_path="+backup_location;
				
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

$(".backup_note").click(function(){
	$(".note_span").hide();
	$(".note_input").show();
	
	$(".note_input").change(function(){
		$(".note_span").show();
		$(".note_input").hide();
	});
});

var selecetd_files = [];
var parent_dir = [];
var sel_files_val = [];
var sel_files_key = [];

//open modal and access sub-folders
function showSubs(folder_path="", filename, bak_server_id="", back_path="") {
	
	$(".loading").show();
	
	if(!empty($("#bac_path_"+filename).val())){
		backup_path = ($("#bac_path_"+filename).val());
		backup_filename = ($("#bac_filename_"+filename).val());
		bak_server_id = ($("#bac_server_"+filename).val());
		bak_method = ($("#bac_method_"+filename).val());
	}

	parent_dir = $(".parent_dir").val();

	checkfile = folder_path.replace(/[./:]/g, "_");
	if($("#check_"+checkfile).is(":checked")){
		var dir_check = 1;
	}

	if(!empty(back_path)){
		folder_path = folder_path.substring(0, folder_path.indexOf(filename))
	}
	
	$("input:checkbox[name=checked_file]:checked").each(function(){
		if($(this).is(":visible") == true && !sel_files_val.includes(parent_dir) && !sel_files_val.includes($(this).val())){
			selecetd_files.push($(this).val()
		);
			sel_files_val.push($(this).val());
			sel_files_key.push(parent_dir);
		}	
	});
	
	var w_l = remove_hash(window.location.toString());
	$.ajax({
		type: "POST",
		url: w_l+"&show_files=1",					
		data: "backup_path="+backup_path+"&backup_filename="+backup_filename+"&folder_path="+folder_path+"&filename="+filename+"&bak_server_id="+bak_server_id+"&dir_check="+dir_check+"&backup_method="+bak_method,			
		success: function(data){
			$(".loading").hide();
			$(".file_selection_modal").html(data);
			if(empty(folder_path)){
				$("#selection-modal-btn").trigger("click");				
			}
		}	
	});	
}
</script>

<button type="button" id="selection-modal-btn" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#file_selection_modal"></button>

<div class="file_selection_modal modal fade" id="file_selection_modal" tabindex="-1" aria-labelledby="add-spfLabel" aria-hidden="true"></div>

<div class="card soft-card p-4">';
	backuply_tabs('backuphome');
	echo '
	<div class="tab-content" id="pills-tabContent">
		<!-- Home Backup -->
		<div class="tab-pane fade show active" id="backuphome" role="tabpanel" aria-labelledby="backuphome_a">
			<div class="sai_main_head mb-3 mt-4">
				<i class="fas fa-folder expander fa-2x">
				<h5 class="d-inline-block">'.__('Home Restore').'</h5></i>
				<span type="button" id="refresh_homeBackup" onclick="rotate_img(this);" class="refresh-icon float-end me-3 mt-3" title="'.__('Refresh Table').'">
					<i class="fas fa-sync-alt"></i>
				</span>
			</div>
			<div class="table-responsive mb-4" id="refresh_homeBackupDisplay">';
				show_table('full', '.tar.gz');
			echo '
			</div>
			<div id="msg_backup_home" class="text-center"></div>
		</div>
		<!-- Home Backup end-->
	</div>
</div>
<div class="row">
	<div class="sai_popup"></div>
</div>';
	
	softfooter();
	
}

function show_table($type, $file_type){

global $globals, $theme, $l, $softpanel, $wbackup_list, $backup_path;
	
	echo '	
<table border="0" cellpadding="6" cellspacing="1" width="100%" class="table align-middle table-nowrap mb-0 webuzo-table">
	<thead class="sai_head2">
		<tr>
			<th class="align-middle" width="25%">'.__('Filename').'</th>';
			// if($type == 'home'){
				echo '<th class="align-middle" width="20%">'.__('Selected Files').'</th>';
			// }
			echo'
			<th class="align-middle" width="15%">'.__('Created').'</th>
			<th class="align-middle" width="10%">'.__('Method').'</th>
			<th class="align-middle" width="10%">'.__('Server').'</th>
			<th class="align-middle" width="5%">'.__('Size').'</th>
			<th class="align-middle" width="5%">'.__('Options').'</th>
		</tr>
	</thead>
	<tbody id="'.$type.'_show_table">';
	
	if(empty($wbackup_list[$type])){
		echo '
		<tr class="text-center">
			<td colspan=11>
				<span>'.__('There are no home backup files found').' !</span>
			</td>
		</tr>';
	}else{
	
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
			// if($type == 'home'){
				echo '<td>'.($v['backup_method'] == 'compressed' ? 'All Files/Directories Selected' : 'All Files/Directories Selected<br><a href="#" class="btn btn-primary btn-sm restore_file_select" id="res_sel_'.basename($v['filename']).'" onclick="showSubs(\''.NULL.'\', \''.$v['filename'].'\', \''.$v['backup_server_id'].'\');" title="'.__('Partial Restore').'">Change Files Selection</a>').'</td>';
			// }
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

