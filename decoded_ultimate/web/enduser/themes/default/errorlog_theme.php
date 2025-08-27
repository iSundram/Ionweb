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

global $theme, $globals, $error, $domain_list, $error_logs, $domain, $hitlist;
	
	softheader(__('Error Log'));
	echo '	
<div class="card soft-card p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'error_log.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Error Log').'</h5>
	</div>
</div>
<div class="card soft-card p-4 mt-4">';	
	error_handle($error, '100%');

	echo '
	<div class="record-table mb-3 text-center">
		<h4 class="sai_sub_head d-inline-block">'.__('View Domain Specific Error Logs : ').'</h4>				
		<select name="selectdomain" id="selectdomain" class="me-1">';
			
		foreach ($domain_list as $key => $value){	
			echo '
			<option value='.$key.' '.($domain == $key ? 'selected' : '').'>'.$key.'</option>';
		}
				
	echo '
		</select> <img id="create_err_log" src="'.$theme['images'].'progress.gif" style="display:none">
	</div>';
		
	foreach($error_logs as $k => $v){
		
		echo '
	<div class="row text-center mb-4">
		<div class="col-12 col-md-3 col-lg-2 mb-3">
			<label class="form-label d-block mb-3">'.$k.'</label>
			<input type="button" class="flat-butt clear_butt" id="did"'.$k.'" style="cursor:pointer" value="'.__('Clear Logs').'" onclick="delete_record(this)" data-domain_log="'.$domain.'" data-clearlog="'.$k.'" />
		</div>
		<div class="col-12 col-md-9 col-lg-10" nowrap="nowrap">
			<textarea class="form-control" readonly="readonly"; style="height:400px; width:100%; overflow:auto;  resize: none;" wrap="off"; >'.str_replace(['>', '<'], ['&gt;', '&lt;'], $v).'</textarea>
		</div>
	</div>';
		
	}		
	echo '	
</div>';
if(!empty($hitlist)){
echo '
<div class="card soft-card p-4">
	<div class="sai_main_head mb-5">
		<img src="'.$theme['images'].'error_log.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Modsecurity Logs').'</h5>
	</div>';
	page_links();
	
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
			foreach($hitlist as $key => $value){
			echo '
			<tr>
				<td>'.date('Y-m-d h:i:s', $value['timestamp']).'</td>
				<td>'.$value['host'].'</td>
				<td>'.$value['ip'].'</td>
				<td>'.$value['meta_severity'].'</td>
				<td>'.$value['http_status'].'</td>
				<td>'.$value['meta_id'].'</td>
				<td class="pe-auto">
					<a href="javascript:void(0);" id="td_'.$value['id'].'" data-bs-toggle="collapse" data-bs-target="#tr_'.$value['id'].'" aria-expanded="false" aria-controls="tr_'.$value['id'].'">'.__('More Info').'
					</a>
				</td>						
			</tr>
			<tr class="table-info pt-0 mt-0">
				<td colspan="100" style="padding: 0px !important;">
					<div class="accordian-body collapse" id="tr_'.$value['id'].'">
						<div class="p-2">
							<b>'.__('Request:').'</b> '.$value['http_method'].' '.$value['path'].'</br>
							<b>'.__('Message:').'</b> '.$value['meta_msg'].'</br>
							<b>'.__('Action Description:').'</b> '.$value['action_desc'].'</br>
							<b>'.__('Justification:').'</b> '.$value['justification'].'
						</div>
					</div>
				</td>
			</tr>';
			}
		echo '
		</tbody>
		</table>
	</div>
</div>';
}

echo '
<script language="javascript" type="text/javascript">

//To change the logs as per selected domain
$("#selectdomain").change( function() {
	window.location = "'.$globals['index'].'act=errorlog&domain_log="+$(this).val();
});

$(document).ready(function () { 
	$(document).click(function () {
		$(".accordian-body").collapse("hide");
	});
});
</script>';
		
	softfooter();

}
