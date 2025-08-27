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

function sel_databaseuser_files_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error, $done, $wbackup_path, $wbackup_log, $json_files, $backup_path, $type, $dir_check, $database_backup_list, $backup_data, $full_data, $dbbackups;
		
	if(optGET('refreshTable') && !empty($done) && optGET('refreshTable') == 'refresh_datausersBackup'){
		show_table_databaseusers('databaseusers', '.tar.gz');
		return true;
	}	
	
	softheader(__('Backuply Database Users'));

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

// Remove hash from URL before making ajax request
function remove_hash(w_l){
	if(w_l.indexOf("#") > 0){
		var unhashed_w_l = w_l.substring(0, w_l.indexOf("#"));
		return unhashed_w_l;
	}

	return w_l;
}
	
function restore_backup(dbu){
	
	var id = $("#restore_"+dbu)
	// console.log(id.data("filename"))
	
	var filename = id.data("filename")
	var server_id = id.data("bsid")
	var backup_method = id.data("bmethod")
	var backup_location = id.data("bpath")
	
	var d = show_message_r(l.warning, "'.__js('Are you sure you want to restore selected DB user(s) ?').'");
	d.alert = "alert-warning";
	d.confirm.push(function(){
		var da = "&restore_dbuser=1&sel_restore=1&backup_filename="+filename+"&bak_server_id="+server_id+"&backup_method="+backup_method+"&backup_location="+backup_location+"&selecetd_files_arr="+dbu;
		
		// console.log(da); return false;			
		submitit(da, {
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
	backuply_tabs('backupdatausers');
	echo '
	<div class="tab-content" id="pills-tabContent">
		
		<!-- Database Users Now-->
		<div class="tab-pane fade show active" id="backupdatausers" role="tabpanel" aria-labelledby="backupdatausers_a">
			<div class="sai_main_head mb-4 mt-3">
				<i class="fas fa-user-tie expander fa-2x"></i>
				<h5 class="d-inline-block">'. __('Database Users Restore').'</h5>				
				<span type="button" id="refresh_datausersBackup" onclick="rotate_img(this);" data-tab="databaseusers" class="refresh-icon float-end mt-3 me-3" title="'.__('Refresh Table').'">
					<i class="fas fa-sync-alt"></i>
				</span>
			</div>
			<div class="table-responsive databaseusers_table" id="refresh_datausersBackupDisplay">';
				show_table_databaseusers('databaseusers', '.tar.gz');
			echo '
			</div>
			<div class="text-center" id="msg_backup_datausers"></div>
		</div>
		<!-- Database Users Now end-->
		
	</div>
</div>
<div class="row">
	<div class="sai_popup"></div>
</div>';
	
	softfooter();
	
}

function show_table_databaseusers($type, $file_type){

global $globals, $theme, $error, $l, $softpanel, $backup_path, $dbbackups, $WE;
	
	error_handle($error);

	echo '
	<script language="javascript" type="text/javascript">
		var filename;
	</script>
<table border="0" cellpadding="6" cellspacing="1" width="100%" class="table align-middle table-nowrap mb-0 webuzo-table">
	<thead class="sai_head2">
		<tr>
			<th class="align-middle" width="25%">'.__('Database User').'</th>
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
		foreach($dbbackups[$type] as $dbu => $dbinfo){
			
			$db_backups = explode(',', $dbinfo['backup_files']);
			
			$bconut = count($db_backups);
			
			$file_data = backup_file_data($WE->user['user'], 'full', current($db_backups));
			// r_print($file_data);
			// r_print($dbinfo);
			
			echo '
		<tr id="'.$dbu.'" class="select_hide">
			<td>'.$dbu.'</td>
			<td>
			<span id="'.$dbu.'_filespan">'.$file_data['filename'].'</span><br>
			<span id="'.$dbu.'_filecount">'.__('Total of $0 backups found', [$bconut]).'</span><br>
			'.('<button class="btn btn-primary btn-sm change_file_of_db" id="'.$dbu.'_selbutton" onclick="show_select(\'backup_file_'.$dbu.'\', \''.$dbu.'\')" title="'.__('Select other backup').'">Select Other Backup</button>').'
			<script>
			
			</script>
			<select name="backup_file_'.$dbu.'" id="backup_file_'.$dbu.'" onchange="backup_file(\'backup_file_'.$dbu.'\', \''.$dbu.'\')" class="form-select form-select-sm sel_backups d-none">';
			
			foreach($db_backups as $kk => $file){
					echo '<option val="'.$file.'">'.$file.'</option>';		
				}
			echo '
			</select></td>';
			echo $_COOKIE["fcookie"];	
			echo '
			<td id="'.$dbu.'_filemethod">'.(!empty($file_data['backup_method'])?$file_data['backup_method']:'compressed').'</td>
			<td id="'.$dbu.'_fileserver">'.$file_data['server_name'].'</td>
			<td id="'.$dbu.'_filenote"><span id="note_span_'.preg_replace("~[.]~", "_", basename($file_data['filename'])).'">'.(!empty($file_data['backup_notes']) ? $file_data['backup_notes'] : '--').'</td>
			<td id="'.$dbu.'_filesize">'.(!empty($dbinfo['size']) ? trim(round(($dbinfo['size']/1024)/1024, 2).' M') : "--").'</td>
			
			<td onclick="restore_backup(\''.$dbu.'\');" data-filename="'. basename($file_data['filename']).'" data-bsid="'.$file_data['backup_server_id'].'" id="restore_'.$dbu.'" data-bmethod="'.$file_data['backup_method'].'" data-bpath="'.$file_data['backup_location'].'" data-btype="'.$file_data['type'].'" name="selected_dbs[]" value="'.$dbu.'">
				<i class="fas fa-undo restore edit-icon" title="'.__('Restore').'"></i>
			</td>'
			.($file_data['backup_server_id'] == -1 ? '<td>
				<a href="'.$globals['index'].'act=sel_databaseuser_files&download='.$file_data['filename'].'&download_db='.$dbu.'" id="'.$dbu.'_downloadlink" title="'.__('Download').'" >
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

	function show_select(id, dbu){
		// console.log(id)
		$("#"+id).removeClass("d-none");
		
		$("#"+dbu+"_filespan").hide();
		$("#"+dbu+"_selbutton").hide();
		
	}
	
	function hide_select(id, dbu){
		// console.log(id)
		$("#"+id).addClass("d-none");
		
		$("#"+dbu+"_filespan").show();
		$("#"+dbu+"_selbutton").show();
		
	}
	
	$(".select_hide").click(function(e){
		
		var dbu = $(this).attr("id")
		var id = "backup_file_"+dbu;
		
		if($("#"+id).is(":visible")){
			
			// console.log($(e.target))
			
			// Check if the target element is a button or any other HTML element
			if ($(e.target).is("button") || $(e.target).is("select") || $(e.target).is("a") || $(e.target).is("i")) {
				return;
			}
			
			hide_select(id, dbu);
		}
	})
	
	function backup_file(dbu, dbuname) {
		
		$(".loading").show();
		
		var file = $("#"+dbu).val();
		
		var d ="bfilename="+file+"&get_file_data=1";
		var w_l = remove_hash(window.location.toString());
		
		var spinner = `<span class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></span>`;
		
		$("#"+dbuname+"_filemethod").html(spinner);
		$("#"+dbuname+"_fileserver").html(spinner);
		$("#"+dbuname+"_filenote").html(spinner);
		
		// Make an ajax call
		$.ajax({
			type: "POST",
			url: w_l+"&api=json",
			data: d,
			dataType: "json",
			success: function(data){
				$(".loading").hide();
				
				console.log(data.filedata)
				
				$("#"+dbuname+"_filespan").show();
				$("#"+dbuname+"_filespan").text(file);
				
				$("#"+dbuname+"_selbutton").show();
				
				// Update Row with new backup info
				$("#"+dbuname+"_filemethod").text(data.filedata.backup_method);
				$("#"+dbuname+"_fileserver").text(data.filedata.server_name);
				$("#"+dbuname+"_filenote").text(data.filedata.backup_notes);
				
				// if(!empty(data.filedata.backup_size)){
					// $("#"+dbuname+"_filesize").text(data.filedata.backup_size);
				// }else{
					// $("#"+dbuname+"_filesize").text("--");
				// }
				
				$("#restore_"+dbuname).attr("data-filename", data.filedata.filename);
				$("#restore_"+dbuname).attr("data-bmethod", data.filedata.backup_method);
				$("#restore_"+dbuname).attr("data-bpath", data.filedata.backup_location);
				$("#restore_"+dbuname).attr("data-bsid", data.filedata.backup_server_id);
				$("#restore_"+dbuname).attr("data-btype", data.filedata.type);
				
				$("#"+dbuname+"_downloadlink").attr("href", "'.$globals['index'].'act=sel_databaseuser_files&download="+data.filedata.filename+"&download_db="+dbuname);
				
			},
			error: function() {
				
				$(".loading").hide();
				
				var a = show_message_r(l.error, l.r_connect_error);
				a.alert = "alert-danger";
				
				show_message(a);
			}
		});
		
		
		$("#"+dbu).addClass("d-none")
		
		// document.cookie="fcookie="+$("#backup_file_"+dbu).val();
	}
</script>
';	
}