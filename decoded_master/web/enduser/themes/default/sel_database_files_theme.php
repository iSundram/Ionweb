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

function sel_database_files_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error, $done, $wbackup_path, $wbackup_log, $json_files, $backup_path, $type, $dir_check, $database_backup_list, $backup_data, $full_data, $dbbackups;
		
	if(optGET('refreshTable') && !empty($done) && optGET('refreshTable') == 'refresh_dataBackup'){
		show_table_database('databases', '.tar.gz');
		return true;
	}	
	
	softheader(__('Backuply Databases'));

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
			// console.log(output)
			$("#"+backup_id+"Display").html(output);	
		}
	});
};

function basename(path) {
	var ret = path.split("/").pop();
	return ret;
}

function dirname(path) {
    var ret = path.split("/").slice(0, -1).join("/");
    return ret;
}


$("#checkAllFiles").change(function () {
	$(".check").prop("checked", $(this).prop("checked"));
});

</script>
<button type="button" id="selection-modal-btn" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#file_selection_modal"></button>

<div class="file_selection_modal modal fade" id="file_selection_modal" tabindex="-1" aria-labelledby="add-spfLabel" aria-hidden="true" aria-hidden="true"></div>

<div class="card soft-card p-4">';
	backuply_tabs('backupdata');
	echo '
	<div class="tab-content" id="pills-tabContent">
		
		<!-- Database Now-->
		<div class="tab-pane fade show active" id="backupdata" role="tabpanel" aria-labelledby="backupdata_a">
			<div class="sai_main_head mt-3 mb-4">
				<i class="fas fa-database expander fa-2x"></i>
				<h5 class="d-inline-block">'. __('Database Restore').'</h5>
				<span type="button" id="refresh_dataBackup" onclick="rotate_img(this);" data-tab="databases" class="refresh-icon float-end me-3 mt-3" title="'.__('Refresh Table').'">
					<i class="fas fa-sync-alt"></i>
				</span>
			</div>
			<!--
				<div class="row col-12 col-md-4 justify-content-center">
				<label class="sai_head text-center">'.__('Search By Database Name').'</label><br/>
				<select class="form-select make-select2" s2-placeholder="'.__('Select Database').'" s2-ajaxurl="'.$globals['index'].'act=sel_database_files&api=json" s2-query="search" s2-data-key="dbbackups" s2-data-subkey="databases" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" id="db_search" name="db_search">
					<option value="'.optREQ('search').'" selected="selected">'.optREQ('search').'</option>
				</select>
			</div> -->
			<div class="table-responsive databases_table" id="refresh_dataBackupDisplay">';
			// if(!empty(optGET('table')) && optGET('table') == 'databases'){
				show_table_database('databases', '.tar.gz');
			// }
			echo '
			</div>
			<div class="text-center" id="msg_backup_data"></div>
		</div>
		<!-- Database Now end-->
		
	</div>
</div>
<div class="row">
	<div class="sai_popup"></div>
</div>';
	
	softfooter();
	
}

function show_table_database($type, $file_type){

global $globals, $theme, $error, $l, $softpanel, $backup_path, $dbbackups, $WE;
	
	error_handle($error);

	echo '
	<script language="javascript" type="text/javascript">
		var filename;
	</script>
<table border="0" cellpadding="6" cellspacing="1" width="100%" class="table align-middle table-nowrap mb-0 webuzo-table">
	<thead class="sai_head2">
		<tr>
			<th class="align-middle" width="25%">'.ucfirst($type).'</th>
			<th class="align-middle" width="25%">'.__('Backups').'</th>
			<th class="align-middle" width="5%">'.__('Method').'</th>
			<th class="align-middle" width="10%">'.__('Server').'</th>
			<th class="align-middle" width="20%">'.__('Notes').'</th>
			<th class="align-middle" width="8%">'.__('size').'</th>
			<th class="align-middle" width="5%" colspan="2">'.__('Options').'</th>
		</tr>
	</thead>
	<tbody id="'.$type.'_show_table">';
	if(empty($dbbackups[$type])){
		echo '
		<tr class="text-center">
			<td colspan=11>
				<span>'.__('There are no Database backups found').' !</span>
			</td>
		</tr>';
	}else{
		foreach($dbbackups[$type] as $db => $dbinfo){
			
			$db_backups = explode(',', $dbinfo['backup_files']);
			
			$bconut = count($db_backups);
			
			$file_data = backup_file_data($WE->user['user'], 'full', current($db_backups));
			// r_print($file_data);
			// r_print($dbinfo);
			
			echo '
		<tr id="'.$db.'" class="select_hide">
			<td>'.$db.'</td>
			<td>
			<span id="'.$db.'_filespan">'.$file_data['filename'].'</span><br>
			<span id="'.$db.'_filecount">'.__('Total of $0 backups found', [$bconut]).'</span><br>
			'.('<button class="btn btn-primary btn-sm change_file_of_db" data-db="'.$db.'" id="'.$db.'_selbutton" onclick="show_select(\'backup_file_'.$db.'\', \''.$db.'\')" title="'.__('Select other backup').'">Select Other Backup</button>').'
			
			<select name="backup_file_'.$db.'" id="backup_file_'.$db.'" onchange="backup_file(\'backup_file_'.$db.'\', \''.$db.'\')" class="form-select form-select-sm sel_backups d-none">';
			
			foreach($db_backups as $kk => $file){
					echo '<option val="'.$file.'">'.$file.'</option>';		
				}
			echo '
			</select></td>';
			echo $_COOKIE["fcookie"];
			echo '
			<td id="'.$db.'_filemethod">'.(!empty($file_data['backup_method'])?$file_data['backup_method']:'compressed').'</td>
			<td id="'.$db.'_fileserver">'.$file_data['server_name'].'</td>
			<td id="'.$db.'_filenote"><span id="note_span_'.preg_replace("~[.]~", "_", basename($file_data['filename'])).'">'.(!empty($file_data['backup_notes']) ? $file_data['backup_notes'] : '--').'</td>
			<td id="'.$db.'_filesize">'.(!empty($dbinfo['size']) ? trim(round(($dbinfo['size']/1024)/1024, 2).' M') : "--").'</td>
			
			<td onclick="restore_backup(\''.$db.'\');" data-filename="'. basename($file_data['filename']).'" data-bsid="'.$file_data['backup_server_id'].'" id="restore_'.$db.'" data-bmethod="'.$file_data['backup_method'].'" data-bpath="'.$file_data['backup_location'].'" data-btype="'.$file_data['type'].'" name="selected_dbs[]" value="'.$db.'">
				<i class="fas fa-undo restore edit-icon" title="'.__('Restore').'"></i>
			</td>'
			.($file_data['backup_server_id'] == -1 ? '<td>
				<a href="'.$globals['index'].'act=sel_database_files&download='.$file_data['filename'].'&download_db='.$db.'" id="'.$db.'_downloadlink" title="'.__('Download').'" >
					<i class="fas fa-download download edit-icon" title="'.__('Download').'"></i>
				</a>
			</td>' : '<td></td>');
		echo '</tr>';
		}
	}
	echo '
	</tbody>
</table>
<script language="javascript" type="text/javascript">
	
	function restore_backup(db){
		
		var id = $("#restore_"+db)
		// console.log(id.data("filename"))
		
		var filename = id.data("filename")
		var server_id = id.data("bsid")
		var backup_method = id.data("bmethod")
		var backup_location = id.data("bpath")
		
		var d = show_message_r(l.warning, "'.__js('Are you sure you want to restore selected DB file(s) ?').'");
		d.alert = "alert-warning";
		d.confirm.push(function(){	
			var da = "&restore_db=1&sel_restore=1&backup_filename="+filename+"&bak_server_id="+server_id+"&backup_method="+backup_method+"&backup_location="+backup_location+"&selecetd_files_arr="+db;
			
			// console.log(da) return false;
			submitit(da, {
				sm_done_onclose: function(){			
					location.reload();					
				}
			});
		});

		show_message(d);
	}
	
	function show_select(id, db){
		$("#"+id).removeClass("d-none");
		// console.log($("#"+id))
		
		$("#"+db+"_filespan").hide();
		$("#"+db+"_selbutton").hide();
		
	}
	
	function hide_select(id, db){
		// console.log(id)
		$("#"+id).addClass("d-none");
		
		$("#"+db+"_filespan").show();
		$("#"+db+"_selbutton").show();
		
	}
	
	$(".select_hide").click(function(e){
		
		var db = $(this).attr("id")
		var id = "backup_file_"+db;
		
		if($("#"+id).is(":visible")){
			
			// console.log($(e.target))
			
			// Check if the target element is a button or any other HTML element
			if ($(e.target).is("button") || $(e.target).is("select") || $(e.target).is("a") || $(e.target).is("i")) {
				return;
			}
			
			hide_select(id, db);
		}
	})
	
	function backup_file(db, dbname) {
		
		$(".loading").show();
		
		var file = $("#"+db).val();
		
		var d ="bfilename="+file+"&get_file_data=1";
		var w_l = remove_hash(window.location.toString());
		
		var spinner = `<span class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></span>`;
		
		$("#"+dbname+"_filemethod").html(spinner);
		$("#"+dbname+"_fileserver").html(spinner);
		$("#"+dbname+"_filenote").html(spinner);
		
		// console.log(d);
		// Make an ajax call
		$.ajax({
			type: "POST",
			url: w_l+"&api=json",
			data: d,
			dataType: "json",
			success: function(data){
				$(".loading").hide();
				
				// console.log(data.filedata)
				
				$("#"+dbname+"_filespan").show();
				$("#"+dbname+"_filespan").text(file);
				
				$("#"+dbname+"_selbutton").show();
				
				// Update Row with new backup info
				$("#"+dbname+"_filemethod").text(data.filedata.backup_method);
				$("#"+dbname+"_fileserver").text(data.filedata.server_name);
				if(empty(data.filedata.backup_notes)){
					$("#"+dbname+"_filenote").text("--");
				}else{
					$("#"+dbname+"_filenote").text(data.filedata.backup_notes);
				}
				
				// if(!empty(data.filedata.backup_size)){
					// $("#"+dbname+"_filesize").text(data.filedata.backup_size);
				// }else{
					// $("#"+dbname+"_filesize").text("--");
				// }
				
				$("#restore_"+dbname).attr("data-filename", data.filedata.filename);
				$("#restore_"+dbname).attr("data-bmethod", data.filedata.backup_method);
				$("#restore_"+dbname).attr("data-bpath", data.filedata.backup_location);
				$("#restore_"+dbname).attr("data-bsid", data.filedata.backup_server_id);
				$("#restore_"+dbname).attr("data-btype", data.filedata.type);
				
				$("#"+dbname+"_downloadlink").attr("href", "'.$globals['index'].'act=sel_database_files&download="+data.filedata.filename+"&download_db="+dbname);
				
			},
			error: function() {
				
				$(".loading").hide();
				
				var a = show_message_r(l.error, l.r_connect_error);
				a.alert = "alert-danger";
				
				show_message(a);
			}
		});
		
		
		$("#"+db).addClass("d-none")
		
		// document.cookie="fcookie="+$("#backup_file_"+db).val();
	}
</script>
';	
}