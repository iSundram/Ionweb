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

function modsec_tools_theme(){

global $theme, $globals, $user, $langs, $error, $W, $saved, $done, $hitlist, $vendor_info, $modsec_conf, $start_date, $end_date, $modsec_conf_notice;
		
	softheader(__('ModSecurity Tools'));
	
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head mb-4">
		<i class="fas fa-tools me-2"></i> '.__('ModSecurity Tools').'
		<span class="search_btn float-end">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'">
				<i class="fas fa-search"></i>
			</a>
		</span>
	</div>
	<div class="show p-2 mt-2" id="search_queue" style="background-color:#e9ecef;">
	<form accept-charset="'.$globals['charset'].'" name="modsec_search" method="POST" >
		<input type="hidden" name="act" value="modsec_tools">
		<div class="row">
			<div class="col-md-4">
				<label>'.__('Select type').' : </label>
						<select class="form-control" name="searchin" id="searchin" onchange="show_search()">
							<option value="" '.REQselect('searchin', '').'>'.__('All').'</option>
							<option value="host" '.REQselect('searchin', 'host').'>'.__('Host').'</option>
							<option value="ip" '.REQselect('searchin', 'ip').'>'.__('Source').'</option>
							<option value="meta_severity" '.REQselect('searchin', 'meta_severity').'>'.__('	Severity').'</option>
							<option value="http_status" '.REQselect('searchin', 'http_status').'>'.__('Status').'</option>
							<option value="meta_id" '.REQselect('searchin', 'meta_id').'>'.__('Rule ID').'</option>
							<option value="meta_msg" '.REQselect('searchin', 'meta_msg').'>'.__('Rule Message').'</option>
							<option value="http_method" '.REQselect('searchin', 'http_method').'>'.__('Request').'</option>
							<option value="action_desc" '.REQselect('searchin', 'action_desc').'>'.__('Action Description').'</option>
							<option value="justification" '.REQselect('searchin', 'justification').'>'.__('Justification').'</option>
							<option value="domain" '.REQselect('searchin', 'domain').'>'.__('Domain').'</option>
							<option value="timestamp" '.REQselect('searchin', 'timestamp').'>'.__('Date').'</option>
						</select>
			</div>
			<div class="col-md-4" id="searchbox">				
				<label>'.__('Search').':</label>
				<input class="form-control" name="search" id="search" value="'.optREQ('search').'" placeholder="'.__('Search').'">			
			</div>
			<div class="col-md-2 date">
				<label>'.__('Start Date').' :</label>
				<input type="datetime-local" class="form-control" name="start_date" id="start_date" value="'.date("Y-m-d\TH:i", $start_date).'" max="'.date("Y-m-d\TH:i").'">
			</div>
			<div class="col-md-2 date">
				<label>'.__('End Date').' :</label>
				<input type="datetime-local" class="form-control" name="end_date" id="end_date" value="'.date("Y-m-d\TH:i", $end_date).'" max="'.date("Y-m-d\TH:i").'">
			</div>
			<div class="col-md-4">
				<br>
				<button class="btn btn-primary float-end" type="submit" name="run_report">'.__('Search').'</button>
			</div>
		</div>
	</form>
	</div>
</div>
<div class="soft-smbox p-4 mt-4">
	<form accept-charset="'.$globals['charset'].'" name="modsec_tools" id="modsec_tools" method="post" action=""; class="form-horizontal">';
	$count = 0;
	foreach($vendor_info as $key => $val){
		if(empty($val["installed"])){
			continue;
		}
		$count = 1;
	}
	if(empty($count)){
		echo '
		<div class="alert alert-danger d-flex justify-content-center"" role="alert">
			'.__('There is no vendor installed.').'&nbsp; &nbsp;
			<a href="'.$globals['ind'].'act=modsec_vendors">'.__('Vendor Manager').'</a>
		</div>';
	}else{
		echo '				
		<div class="row">
			<div class="col-md-6">
				<h5>'.__('Hits List').'</h5>
			</div>
			<div class="col-md-6">
				<a class="btn btn-primary float-end" href="'.$globals['ind'].'act=modsec_rules">'.__('Rules List').'</a>
			</div>
		</div>';
		page_links();
		
		// If ModSec is in DetectionOnly mode, show notice
		if(!empty($modsec_conf_notice)){
			echo '<div class="alert alert-warning text-center">
				<img src="'.$theme['images'].'notice.gif" />'.__(' Note: $2 are set to DetectionOnly mode. ModSecurity will NOT block requests that match the configured rules but will only log them. You can change this preference from $0 ModSecurity Configuration $1', ['<a href="'.$globals['index'].'act=modsec_conf">', '</a>', $modsec_conf_notice['engine']]).'</span>
		</div>';
		}
		
		echo '
		<div class="table-responsive mt-4">
			<table class="table sai_form webuzo-table">
			<thead>
				<tr>
					<th>'.__('Date').'</th>
					<th>'.__('Host').'</th>
					<th>'.__('Source').'</th>
					<th>'.__('Severity').'</th>
					<th>'.__('Status').'</th>
					<th colspan="2">'.__('Rule ID').'</th>
				</tr>
			</thead>
			<tbody>';
			if(empty($hitlist)){
				echo '
				<tr>
					<td colspan="100" class="text-center">
						<span>'.__('No Data Found').'</span>
					</td>
				</<tr>';
			}else{
				foreach($hitlist as $key => $value){
				echo '
				<tr>
					<td>'.date('Y-m-d h:i:s', $value['timestamp']).'</td>
					<td>'.$value['host'].'</td>
					<td>'.$value['ip'].'</td>
					<td>'.$value['meta_severity'].'</td>
					<td>'.$value['http_status'].'</td>
					<td width="25%">
						<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#edit_rules" data-id="'.$value['meta_id'].'" data-filename="'.$value['meta_file'].'" onclick="edit_rule(this)">'.$value['meta_id'].': '.$value['meta_msg'].'</a>
					</td>
					<td class="pe-auto">
						<a href="javascript:void(0);" id="td_'.$value['id'].'" data-bs-toggle="collapse" data-bs-target="#tr_'.$value['id'].'" aria-expanded="false" aria-controls="tr_'.$value['id'].'">'.__('More Info').'
						</a>
					</td>						
				</tr>
				<tr class="table-info pt-0 mt-0">
					<td colspan="100" class="p-0">
						<div class="accordian-body collapse" id="tr_'.$value['id'].'">
							<div class="p-2">
								<b>Request:</b> '.$value['http_method'].' '.$value['path'].'</br>
								<b>Action Description:</b> '.$value['action_desc'].'</br>
								<b>Justification:</b> '.htmlentities($value['justification'], ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML401).'
							</div>
						</div>
					</td>
				</tr>';
				}
			}
			echo '
			</tbody>
			</table>
		</div>';
	}
	echo '
	</form>
</div>

<div class="modal fade" id="edit_rules" tabindex="-1" aria-labelledby="edit_rules" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">				
				<h5 class="modal-title">'.__('Edit Rule').'</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="alert alert-warning p-1 mb-0" role="alert">'.__('You cannot edit vendor rules. You can enable or disable this rule.').'</div>
			<div class="modal-body">
				<form accept-charset="'.$globals['charset'].'" action="" method="post" name="modsec_hitlist" id="editform" class="form-horizontal">
					<div id="rule_id_div">
						<label class="form-label me-2">'.__('Original ID').' </label>
						<input type="text" id="rule_id" name="rule_id" class="form-control mb-3" value="" readonly/>
					</div>
					<div>
						<label class="form-label me-2">'.__('Rule Text').'</label>
						<textarea class="form-control mb-3" id="rule_text" name="rule_text" rows="10" cols="50" readonly></textarea>
					</div>
					<div class="form-check">						
						<label class="form-check-label" for="rule_status">'.__('Enable Rule').'</label>
						<input class="form-check-input" type="checkbox" name="rule_status" id="rule_status">
					</div>
					<input type="hidden" id="config_file" name="config_file" val=""/>
					<center>	
						<input type="submit" class="btn btn-primary me-2" value="'.__('Save').'" id="save_btn" name="edit_rule" onclick="return submitit(this)" data-donereload="1" />
						<img id="createimg" src="'.$theme['images'].'progress.gif" style="display:none">
					</center>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
function edit_rule(jEle){
	var id = $(jEle).data("id");
	var filename = $(jEle).data("filename");
	$("#rule_id").val(id);
	d = {rule_id : id, filename : filename, get_rule_text : 1};
	submitit(d,{
		handle:function(data, p){
			$("#rule_text").val(data.config.rule_text);
			if(data.config.active === 0){
				$("#rule_status").attr("checked", true);	
			}
			$("#config_file").val(data.config.filename);
		}
	});
}

$(document).ready(function () { 
	$(document).click(function () {
		$(".accordian-body ").collapse("hide");
	});
	show_search();
});

function show_search(){
	
	var searchin = $("#searchin").val();
	
	if(searchin == "timestamp"){
		$(".date").show();
		$("#searchbox").hide();
	}else{
		$("#searchbox").show();
		$(".date").hide();
	}
};

</script>';

	softfooter();

}
