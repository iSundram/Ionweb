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

global $user, $globals, $theme, $softpanel, $error, $done, $apikeys, $SESS, $_admin_menu;

	softheader(__('Admin API Keys'));
	
	echo '
<div class="col-12 col-md-12 mx-auto soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-list me-2"></i>'.__('API Keys').'
		<a class="btn btn-primary text-decoration-none float-end" id="add_new_button" onclick="open_add_api_modal()">'.__('Add New').'</a>
	</div>
</div>
<div class="col-12 col-md-12 mx-auto soft-smbox p-3 mt-4">
<form accept-charset="'.$globals['charset'].'" name="editplanform" id="editplanform" method="post" action=""; class="form-horizontal">';
	error_handle($error);
	
	echo'
	<div class="modal fade" id="add_key" tabindex="-1" aria-labelledby="add-aliasesLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title action-type" id="add-aliasesLabel">'.__('Add API Key').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="'.__('Close').'"></button>
				</div>
				<div class="modal-body p-2">
					<form accept-charset="'.$globals['charset'].'" name="add_zone_form" method="post" class="form-horizontal">
						<div class="row p-3 col-md-12 d-flex">
							<div class="col-12 col-md-12 m-2">
								<label for="api_key_ip" class="form-label">'.__('Allow IP Addresses (Optional)').'</label>
								<input type="text" name="ip" id="api_key_ip" class="form-control" style="width:100%" >
								<input type="hidden" name="key" id="api_key" style="width:100%" >
							</div>
							<div class="col-12 col-md-12 m-2">
								<label for="api_key_notes" class="form-label">'.__('Notes (Optional)').'</label>
								<textarea type="text" name="notes" id="api_key_notes" style="width:100%"></textarea>
							</div>							
							<div class="col-12 col-md-12 m-2">
								<label for="api_key_notes" class="form-label">'.__('Allowed Acts').'</label>
								<div class="row">
									<div class="col-12 col-md-3 mb-3">
										<label class="checkbox-inline">
											<input type="checkbox" name="allow_all" id="allow_all" checked="checked" value="" class="me-2"> '.__('All Acts').'
										</label>
									</div>
								</div>
								<div class="row" id="acts_list" style="display:none;">';
								
								foreach($_admin_menu as $k => $v){
									
									echo '<div class="col-12 col-md-3 mb-3">
										<label class="checkbox-inline api-acts label-secondary p-2" style="width:100%; height:100%;">
											<input type="checkbox" name="acts['.$k.']" act="'.$k.'" value="1" class="me-2"> '.$v.'
										</label>
									</div>';
									
								}
								
								echo '</div>
							</div>
							<center>
								<input type="submit" class="btn btn-primary text-decoration-none" name="save" id="api_key_add" value="'.__('Save Key').'" onclick="return submitit(this)" data-donereload="1" />
							</center>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="the_list" class="table-responsive">
		<div class="col-6">
			<input type="button" class="btn btn-danger mb-3" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_apikeys(this)" style="float: left;" disabled>
		</div>
		<table class="table sai_form webuzo-table" id="the_list_table">
			<thead>	
				<tr>
					<th class="align-middle"><input type="checkbox" id="checkAll"></th>
					<th>'.__('User').'</th>
					<th>'.__('API Key').'</th>
					<th width="25%">'.__('IP Address').'</th>
					<th width="20%">'.__('Acts').'</th>
					<th width="20%">'.__('Notes').'</th>
					<th width="15%">'.__('Created').'</th>
					<th colspan="2" width="1%">'.__('Options').'</th>
				</tr>
			</thead>
			<tbody>';
	
	if(empty($apikeys)){
		echo '
				<tr class="text-center">
					<td colspan="9">
						<span>'.__('There are no API Keys !').'</span>
						<a class="btn text-decoration-none"  onclick="open_add_api_modal()">'.__('Please create one').'</a>
					</td>
				</<tr>';
	
	}else{
	
		foreach ($apikeys as $key => $v){
			echo '
			<tr id="tr'.$key.'">
				<td>
					<input type="checkbox" name="api_checkbox" class="api_checkbox" value="'.$key.'">
				</td>
				<td>'.$SESS['user'].'</td>
				<td>'.$key.'</td>
				<td>'.(empty($v['ips']) ? __('All IP Addresses') : implode(', ', $v['ips'])).'</td>
				<td>';
				
				if(empty($v['acts'])){
					echo 'All';
				}else{
					
					$tmp = [];
					foreach($v['acts'] as $kk => $vv){
						$tmp[$vv] = $_admin_menu[$vv];
					}
					
					echo implode(', ', $tmp);
					
				}
					
				echo '</td>
				<td>'.(empty($v['notes']) ? 'NA' : $v['notes']).'</td>
				<td>'.datify($v['created']).'</td>
				<td width="1%" class="text-center">
					<a href="javascript:open_add_api_modal(\''.$key.'\', \''.implode(', ', (array)$v['ips']).'\', \''.$v['notes'].'\', JSON.parse(\''.str_replace('"', '&quot;', json_encode($v['acts'])).'\'))" id="edit'.$key.'" title="'.__('Edit').'">
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
</form>
</div>';

	echo '
<script language="javascript" type="text/javascript">

$(document).ready(function(){
	
	$("#checkAll").change(function () {
		$(".api_checkbox").prop("checked", $(this).prop("checked"));
	});
	
	$("input:checkbox").change(function() {
		if($(".api_checkbox:checked").length){
			$("#delete_selected").removeAttr("disabled");
		}else{
			$("#delete_selected").prop("disabled", true);
		}
	});
});

$("#allow_all").click(function(){
	$("#acts_list").toggle();
});

function del_apikeys(el){
	
	var a;
	var jEle = $(el);
	var confirmbox = "'.__js('Are you sure you want to delete the selected API key(s) ?').'";
	
	var arr = [];
		
	$("input:checkbox[name=api_checkbox]:checked").each(function(){
		var api_key = $(this).val();
		arr.push(api_key);
	});
	
	jEle.data("del", arr.join());
	
	var lang = confirmbox;
	a = show_message_r("'.__js('Warning').'", lang);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		
		var d = jEle.data();
		
		// Submit the data
		submitit(d, {
			handle:function(data){
				if(data.done){
					var d = show_message_r("'.__js('Done').'", data.done.msg);
					d.alert = "alert-success";
					d.ok.push(function(){
						location.reload(true);
					});
					show_message(d);
				}
			}
		});
	});
	
	show_message(a);
}

function open_add_api_modal(key, ips, notes, acts){
	var bm = bootstrap.Modal.getOrCreateInstance($("#add_key")[0]);	
	bm.show();
	key = key || "";
	ips = ips || "";
	notes = notes || "";
	acts = acts || [];
	
	if(acts != ""){
		$("#allow_all").prop("checked", false);
		$("#acts_list").show();
	}else{
		$("#allow_all").prop("checked", true);
		$("#acts_list").hide();
	}
	
	$("#api_key_ip").val(ips);
	$("#api_key_notes").val(notes);
	$("#api_key").val(key);
	$(".api-acts input").each(function(){			
		if(in_array($(this).attr("act"), acts)){
			$(this).prop("checked", true);
		}else{
			$(this).prop("checked", false);
		}
	});
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

</script>';

softfooter();

}

