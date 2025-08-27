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

function apikey_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $apikeys, $SESS, $WE;

	softheader(__('Enduser API Keys'));
	
	echo '
<div class="card soft-card p-3 col-12">
<form accept-charset="'.$globals['charset'].'" name="editplanform" id="editplanform" method="post" action=""; class="form-horizontal">
	<div class="sai_main_head ">
		<img src="'.$theme['images'].'sslkey.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('API Keys').'</h3>
	</div>
</div>
<div class="card soft-card p-4 col-12 mt-4">';
	error_handle($error);
	
	echo'
	<div class="modal fade" id="add_key" tabindex="-1" aria-labelledby="add-aliasesLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title action-type" id="add-aliasesLabel">'.__('Add API Key').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="'.__('Close').'"></button>
				</div>
				<div class="modal-body p-2">
					<form accept-charset="'.$globals['charset'].'" name="add_zone_form" method="post" class="form-horizontal"  onsubmit="return submitit(this)" data-donereload="1">
						<div class="row p-3 col-md-12 d-flex">
							<div class="col-12 col-md-12 m-2">
								<label for="api_key_ip" class="form-label">'.__('IP Address (Optional)').'</label>
								<input type="text" name="api_key_ip" id="api_key_ip" style="width:100%" >
								<input type="hidden" name="api_key" id="api_key" style="width:100%" >
							</div>
							<div class="col-12 col-md-12 m-2">
								<label for="api_key_notes" class="form-label">'.__('Notes (Optional)').'</label>
								<textarea type="text" name="api_key_notes" id="api_key_notes" style="width:100%"></textarea>
							</div>
							<center class="mt-3">
								<a class="flat-butt text-decoration-none"  id="api_key_add" onclick="add_key()">'.__('Add Key').'</a>
							</center>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div id="the_list">
		<div class="sai_sub_head record-table mb-2 position-relative">
			<input type="button" class="btn btn-danger mb-2" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_apikey(this)" disabled>
			<a class="btn flat-butt float-end mb-1" onclick="open_add_api_modal()">'.__('Add New').'</a>
		</div>

		<div class="table-responsive w-100">
		<table class="table sai_form webuzo-table" id="the_list_table">
			<thead>	
				<tr>
					<th><input type="checkbox" id="checkall"></th>
					<th>'.__('User').'</th>
					<th>'.__('API Key').'</th>
					<th width="25%">'.__('IP Address').'</th>
					<th width="20%">'.__('Notes').'</th>
					<th width="15%">'.__('Created').'</th>
					<th colspan="2" width="1%">'.__('Options').'</th>
				</tr>
			</thead>
			<tbody>';
	
	if(empty($apikeys)){
		echo '
		<tr class="text-center">
			<td colspan=7>
				<span>'.__('There are no API Keys !').'</span>
				<a class="primary" onclick="open_add_api_modal()">'.__('Please create one').'</a>
			</td>
		</<tr>';
	
	}else{
	
		foreach ($apikeys as $key => $v){
			
			echo '
				<tr id="tr'.$key.'">
					<td><input type="checkbox" class="check_apikey" name="check_apikey" value="'.$key.'"></td>
					<td>'.$WE->user['user'].'</td>
					<td>'.$key.'</td>
					<td>'.(empty($v['ips']) ? __('All IPs') : implode(', ', $v['ips'])).'</td>
					<td>'.(empty($v['notes']) ? 'NA' : $v['notes']).'</td>
					<td>'.datify($v['created']).'</td>
					<td width="1%" class="text-center">
						<a href="javascript:open_add_api_modal(\''.$key.'\', \''.implode(',', (array)$v['ips']).'\', \''.$v['notes'].'\')" id="edit'.$key.'" title="'.__('Edit').'">
							<i class="fas fa-pencil-alt edit-icon"></i>
						</a>
					</td>
					<td width="1%" class="text-center">
						<i class="fas fa-trash delete-icon" data-del="'.$key.'" title="Delete" id="did'.$key.'" onclick="delete_record(this)""></i>
					</td>
				</tr>';
		}
	}

	echo '
			</tbody>
		</table>
	</div>	
</div>	
</form>
</div>';

	echo '
<script language="javascript" type="text/javascript">

function open_add_api_modal(key, ips, notes){
	var bm = bootstrap.Modal.getOrCreateInstance($("#add_key")[0]);	
	bm.show();

	if(!empty(ips) || !empty(notes) || !empty(key)){
		$("#api_key_ip").val(ips);
		$("#api_key_notes").val(notes);
		$("#api_key").val(key);	
	}
}

function add_key(){
	var ip = $("#api_key_ip").val();
	var notes = $("#api_key_notes").val();
	var key = $("#api_key").val();
	if(!empty(ip)){
		ip = ip.split(",");
		ip = ip.map(function (el) {
			return el.trim();
		}); 
	}
	var data = {"do" : 1, "ip" : ip, "notes" : notes, "key" : key};
	submitit(data, {
		done_reload:window.location
	});
}

$(document).ready(function(){
	
	$("#checkall").change(function(){
		$(".check_apikey").prop("checked", $(this).prop("checked"));
	});
	
	$("input:checkbox").change(function(){
		if($(".check_apikey:checked").length){
			$("#delete_selected").removeAttr("disabled");
		}else{
			$("#delete_selected").prop("disabled",true);
		}
	});
});

function del_apikey(el){
	var a ;
	var jEle = $(el);
	var arr = [];
	
	$("input:checkbox[name=check_apikey]:checked").each(function(){
		var api = $(this).val();
		arr.push(api);
	});
	
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete this selected API Key(s) ?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"del" : arr.join()};
		console.log(d);
		submitit(d,{done_reload : window.location.href});
		
	});
	show_message(a);
}
</script>';

softfooter();

}

