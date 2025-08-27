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

function errorlog_theme(){

global $theme, $globals, $user, $langs, $skins, $error, $saved, $list, $done, $filename, $softlog, $error_log_data, $error_softlog_data;

	softheader(__('Error Logs'));

	echo '
<script language="javascript" type="text/javascript">
function confirm_reset(){

	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to Reset the log ?').'");
	a.alert = "alert-warning";
	a.confirm.push(function(){
		var d = {};
		if($("#errorLogType").val() == "soft_logfile"){
			d.clear_log = "'.basename($softlog).'";
		}else{
			d.clear_log = "'.basename($filename).'";			
		}
		submitit(d, {
			sm_done_onclose:function(){
				location.reload();
			}
		});
	});
	
	show_message(a);
}

var refreshInterval;

$( document ).ready(function(){
	// Refresh the logs
	refreshInterval = setInterval(get_logs, 5000);
	
    $("#errorlog_data").text('.json_encode($error_log_data['error_data']).');
});

function changelogs(e){
	var optionVal = e.value;
	var eld = document.getElementById("errorlog_data");
	if(optionVal == "soft_logfile"){
		eld.textContent = '.json_encode($error_softlog_data['error_data']).';
	}
	if(optionVal == "error_logfile"){
		eld.textContent = '.json_encode($error_log_data['error_data']).';
	}
}


function get_logs(){
	
	AJAX({
		url: window.location.toString()+"&api=json",
		dataType: "json"
	}, function(data){
		var selectedValue = $("#errorLogType").val();
		if(selectedValue == "error_logfile"){
			$("#errorlog_data").text(data.error_log_data.error_data); return;
		}
		
		if(selectedValue == "soft_logfile"){
			$("#errorlog_data").text(data.error_softlog_data.error_data); return;
		}
	});
}
</script>';

	echo '
<div class="soft-smbox p-3 col-12 mx-auto">
	<div class="sai_main_head">
		<i class="far fa-file-alt me-1"></i>'.__('Error Logs').'
	</div>
</div>
<div class="soft-smbox p-3 col-12 mx-auto mt-4">
	<form accept-charset="'.$globals['charset'].'" name="errorlogform" method="post" action="" id="errorlogform" class="form-horizontal">
		<div class="sai_form">';
	
	error_handle($error);
	echo '		
			<p class="alert alert-warning">'.__('Note: Latest logs appear at the top. Scroll down to see older entries.').'</p>
			<div class="sai_reviewform mb-3">
				<select class="form-select" name="errorLogType" onChange="changelogs(this);" id="errorLogType">
					<option value="error_logfile">'.$filename.(!empty($error_log_data['file_size']) ? ' ('.$error_log_data['file_size'].')' : '').'</option>
					'.($globals['logs_level'] > 0  ? '<option value="soft_logfile">'.$softlog.(!empty($error_softlog_data['file_size']) ? ' ('.$error_softlog_data['file_size'].')' : '').'</option>' : '').'
				</select>
			</div>
			<textarea class="form-control log" name="errorlog_data" id="errorlog_data" rows="20" cols="250" readonly="readonly"></textarea>
			<div class="text-center my-3">
				<input type="button" value="'.__('Clear Logs').'" name="clear_log" class="btn btn-primary" onClick="confirm_reset();"/>
			</div>
		</div>
	</form>
</div>';

	softfooter();

}

