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

function domain_errorlog_theme(){

global $theme, $globals, $error, $domain_list, $error_logs, $domain;
	
	softheader(__('Error Log'));
	
	echo '
<script language="javascript" type="text/javascript">

function clear_error_logs(dname, clearlog){
	
	var d = {domain_log:dname,clearlog:clearlog};
	submitit(d, {
		done_reload : window.location,
	});
}

$(document).ready(function(){	
	//To change the logs as per selected domain
	$("#selectdomain").change( function() {
		window.location = "'.$globals['index'].'act=domain_errorlog&domain_log="+$(this).val();
	});
});
</script>
	
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="far fa-file-alt fa-xl me-1"></i>'.__('Domain Error Log').'
	</div>
</div>

<div class="soft-smbox p-3 mt-4">';

	error_handle($error, '100%');
	echo '
	<div class="sai_form row">
		<div class="col-12 col-md-4">
			<label class="sai_head me-1" for="selectdomain">'.__('View Domain Specific Error Logs : ').'</label>
		</div>
		<div class="col-12 col-md-8">
			<select class="form-select make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act=domains&api=json" s2-query="dom_search" s2-data-key="domains" s2-data-subkey="domain" style="width:auto" name="selectdomain" id="selectdomain">
				<option value="'.$domain.'" selected="selected">'.$domain.'</option>
			</select>
		</div>
	</div>';
		
	foreach($error_logs as $k => $v){
		
		echo '
	<div class="row text-center my-3">
		<div class="col-12 col-md-2 mb-3">
			<label class="form-label d-block">'.$k.'</label>
			<input type="button" class="btn btn-primary" value="'.__('Clear Logs').'" onclick="clear_error_logs(selectdomain.value, \''.$k.'\');" />
		</div>
		<div class="col-12 col-md-10">
			<textarea class="form-control log" readonly="readonly" style="height:400px;" wrap="off"; >'.$v.'</textarea>
		</div>
	</div>';
		
	}
		
	echo '	
</div>';
		
softfooter();

}

