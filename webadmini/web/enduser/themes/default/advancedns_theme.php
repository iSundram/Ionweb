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

function advancedns_theme(){

	global $user, $globals, $theme, $softpanel, $WE, $catwise, $error, $done, $domain_list, $dns_list, $domain_name, $is_running;
	
	// Since we have created DNS record, we need to update the new DNS list
	if(!empty($done)){
		$dns_list = $WE->readdnsrecord($domain_name);
	}
	
	// For update
	if(optGET('ajaxup')){	
				
		if(!empty($error)){				
			echo '0'.current($error);
			return false;
		}
		if(!empty($done)){
			echo '1'.__('Record edited successfully');	
			return true;			
		}
	}
	
	// To update domains links
	if(optGET('ajaxdom')){			
		showdns();
		return true;
	}
	
	softheader(__('Advance DNS Settings'));	
	echo '
<div class="card soft-card p-3 col-12">
	<div class="sai_main_head ">
		<img src="'.$theme['images'].'advancedns.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Advance DNS Settings').'</h5>
		<button type="button" class="flat-butt float-end" data-bs-toggle="modal" data-bs-target="#add-DNS">'.__('Add Record').'</button>
		<form method="post" name="dnsexport" onsubmit="return exportdnsfile(this)">
			<input type="submit" class="flat-butt float-end mx-2" name="exportdns" value="'.__('Export DNS File').'">
		</form>
	</div>
</div>
<div class="card soft-card p-4 col-12 mt-4">
	<div class="modal fade" id="add-DNS" tabindex="-1" aria-labelledby="add-dnsLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="add-dnsLabel">'.__('Add New Record').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
				<form accept-charset="'.$globals['charset'].'" action="" method="post" name="advancedns" id="advancedns_add" class="form-horizontal" onsubmit="return submitit(this)">
					<label for="addnsdomain" class="sai_head">'.__('Select Domain').'</label>
					<select class="form-select mb-3" id="addnsdomain" name="domain" onchange="$(\'#domainname\').html(this.value);">';
						foreach ($domain_list as $key => $value){
							if($domain_name == $key){			
								echo '<option value='.$key.' selected=selected>'.$key.'</option>';
							}else{
								echo '<option value='.$key.'>'.$key.'</option>';
							}
						}
					echo '
					</select>

					<label for="name" class="sai_head">'.__('Name').'</label>
					<div class="input-group mb-3">
						<input type="text" name="name" id="name" class="form-control" />
						<span class="input-group-text" id="domainname">.'.$domain_name.'</span>
					</div>
					<label for="ttl" class="sai_head">'.__('TTL').'</label>
					<input type="text" name="ttl" id="ttl" class="form-control mb-3" value="14400" />
					<label for="selecttype" class="sai_head">'.__('Type').'</label>
					<select name="selecttype" id="selecttype" class="form-select mb-3" onchange="disp_type(this.value)">
						<option value="A">A</option>
						<option value="AAAA">AAAA</option>
						<option value="CNAME">CNAME</option>
						<option value="TXT">TXT</option>
						<option value="PTR">PTR</option>
						<option value="SRV">SRV</option>
						<option value="CAA">CAA</option>
					</select>
					<div class="srv-form">
						<label for="srv_priority" class="sai_head" id="srv_priority_lbl">'.__('Priority').'</label>
						<input type="number" id="srv_priority" name="srv_address[priority]" class="form-control" value="" />
						<label for="srv_weight" class="sai_head" id="srv_weight_lbl">'.__('Weight').'</label>
						<input type="number" id="srv_weight" name="srv_address[weight]" class="form-control" value="" />
						<label for="srv_port" class="sai_head" id="srv_port_lbl">'.__('Port').'</label>
						<input type="number" id="srv_port" name="srv_address[port]" class="form-control" value="" />
						<label for="srv_target" class="sai_head" id="srv_target_lbl">'.__('Target').'</label>
						<input type="text" id="srv_target" name="srv_address[target]" class="form-control" value="" />
					</div>
					<div class="caa-form">
						<label for="caa_flag" class="sai_head" id="caa_flag_lbl">'.__('Issuer Critical Flag').' : 
							<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('If 1, the certificate issuer must understand the tag in order to correctly process the CAA record.').'"></i>
						</label>
						</br>
						<label class="form-label me-2">
							<input type="radio" id="caa_flag" name="caa_address[flag]" value="0"  checked/>
							'.__('0').'
						</label>
						
						<label class="form-label me-2">
							<input type="radio" id="caa_flag" name="caa_address[flag]" value="128"/>
							'.__('1').'
						</label>
						</br>
						<label for="caa_tag" class="sai_head mt-2" id="caa_tag_lbl">'.__('Tag').' : 
							<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('The type of CAA record that this DNS entry represents. The issue and issuewild represent the certificate authority’s domain name. The iodef value is the email to which the authority reports exceptions.').'"></i>
						</label>
						</br>
						<label class="form-label me-2">
							<input type="radio" id="caa_tag" name="caa_address[tag]" value="issue" checked onclick="caa_exp(\'issue\')"/>
							'.__('Issue').'
						</label>
						<label class="form-label me-2">
							<input type="radio" id="caa_tag" name="caa_address[tag]" value="issuewild" onclick="caa_exp(\'issuewild\')" />
							'.__('Issuewild').'
						</label>
						<label class="form-label me-2">
							<input type="radio" id="caa_tag" name="caa_address[tag]" value="iodef" onclick="caa_exp(\'iodef\')"/>
							'.__('Iodef').'
						</label>
						
						</br>
						<label for="caa_value" class="sai_head mt-2" id="caa_value_lbl">'.__('Value').' : 
							<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('Certificate Authority\'s Domain Name').'"></i></label>
						<input type="text" id="caa_value" name="caa_address[value]" class="form-control" value=""/>
						<label class="sai_exp2" id="caaval_exp"></label>
						
					</div>
					<div class="other-form">
						<label for="address" class="sai_head" id="type">'.__('Address').'</label>
						<input type="text" id="address" name="address" class="form-control mb-3" value="" />
					</div>
					<div class="text-center my-3">
						<center><input type="submit" class="flat-butt me-2" value="'.__('Add Record').'" name="add" id="submitdns" /></center>
					</div>';
					echo '
				</form>
				</div>
			</div>
		</div>
	</div>		
	<div class="record-table mb-3">
		<center>
			<h4 class="sai_sub_head d-inline-block">'.__('Zone File records of').'</h4>	
			<select id="selectdomain">';
				foreach ($domain_list as $key => $value){
					if($domain_name == $key){			
						echo '<option value='.$key.' selected=selected >'.$key.'</option>';
					}else{
						echo '<option value='.$key.'>'.$key.'</option>';
					}
				}
			echo '
			</select>
		</center>
	</div>
	<div id="showrectab" class="table-responsive">';
		showdns();
echo '</div>
</div>

<script language="javascript" type="text/javascript"><!-- // --><![CDATA[	

$("#addnsdomain").val($("#selectdomain").val())
$("#selectdomain").change(function(){
	$(".loading").show();
	var domain = $(this).val();
	
	var url = window.location.href;
				
	if (url.indexOf("&domain=") !== -1) {
	  url = url.replace(/&domain=[^&]*/g, "");
	}
	
	if (url.indexOf("&domain=") === -1) {
	  url += "&domain=" + domain;
	}
	
	window.history.pushState({path: url}, "", url);
	
	$.ajax({
		type: "POST",				
		url: window.location+"&ajaxdom=1&domain="+domain,
		success: function(data){
			$(".loading").hide();
			$("#showrectab").html(data);
		}													  
	});	
});

$("#selectdomain").change(function(){
	$("#addnsdomain").val($("#selectdomain").val());
	$("#domainname").html($("#selectdomain").val());
});

// Reload the data on add
$("#advancedns_add").on( "done", function(){
	$("#selectdomain").val($("#addnsdomain").val()).trigger("change");
});

$(document).ready(function() {
	var disptype = $("#selecttype").val();
	disp_type(disptype);
})

function disp_type(str){
	$("#type").html(str);
	$(".srv-form").addClass("d-none");
	$(".caa-form").addClass("d-none");
	$(".other-form").removeClass("d-none");	
	
	if(str == "SRV"){
		$(".other-form").addClass("d-none");
		$(".caa-form").addClass("d-none");
		$(".srv-form").removeClass("d-none");
	}
	
	if(str == "CAA"){
		$(".other-form").addClass("d-none");
		$(".srv-form").addClass("d-none");
		$(".caa-form").removeClass("d-none");
	}
}

// ]]></script>';

	softfooter();
}


function showdns(){

global $user, $globals, $theme, $softpanel, $iscripts, $catwise, $error, $done, $domain_list, $dns_list, $domain_name;
	
	error_handle($error, "100%");
	
	echo '
<div class="sai_sub_head record-table mb-2 position-relative" style="text-align: right; display: flex; justify-content: space-between;">
	<input type="button" class="btn btn-danger delete_selected" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="delete_dns(this)" disabled>
	<button type="button" class="btn btn-danger reset_zone" id="reset_zone" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Click here to reset DNS zone" onclick="reset_dns()">'.__('Reset DNS').'</button>
</div>
		
<table class="table align-middle table-nowrap mb-0 webuzo-table" >			
<thead class="sai_head2" style="background-color: #EFEFEF;">
	<tr>
		<th><input type="checkbox" id="checkall"></th>
		<th class="align-middle">'.__('Name').'</th>
		<th class="align-middle">'.__('TTL').'</th>
		<th class="align-middle">'.__('Class').'</th>
		<th class="align-middle">'.__('Type').'</th>
		<th class="align-middle">'.__('Record').'</th>
		<th class="align-middle" colspan="3">'.__('Option').'</th>
	</tr>			
</thead>
<tbody>';
	
	if(empty($dns_list)){
	
		echo '
	<tr>
		<td class="text-center" colspan=6><span>'.__('No DNS record(s) found').'</span></td>
	</tr>';
	
	}else{
	
		foreach ($dns_list as $key => $value){
			
			if(in_array($value['type'], ['SOA', 'DNAME', 'NS', 'HINFO', 'DNAME', 'DS', 'RP', 'AFSDB'])){
				continue;
			}
			
			// check if domain name is in record 
			if(!preg_match('/'.$domain_name.'/is', $dns_list[$key]['name'])){		
				$dns_list[$key]['name'] = $dns_list[$key]['name'].'.'.$domain_name.'.';
			}
			
			echo '
		<tr id="tr'.$key.'">
			<td><input type="checkbox" class="check_dns" name="check_dns" value="'.$key.'" data-domain="'.$domain_name.'"></td>
			<td>
				 <span id="name'.$key.'">'.$dns_list[$key]['name'].'</span>
				 <input type="text" name="name" id="name_entry'.$key.'" value="'.$dns_list[$key]['name'].'" style="display:none;" />
				 <input type="hidden" name="domain" value="'.$domain_name.'" />
				 <input type="hidden" name="edit" value="'.$key.'" />
			</td>
			<td>
				<span id="ttl'.$key.'">'.$dns_list[$key]['ttl'].'</span>
				<input type="text" name="ttl" id="ttl_entry'.$key.'" style="display:none;" value="'.$dns_list[$key]['ttl'].'" size="3">
			</td>
			<td>'.$dns_list[$key]['class'].'</td>					
			<td>
				<span id="type'.$key.'">'.$dns_list[$key]['type'].'</span>			
				<select class="input" name="type" id="type_entry'.$key.'" style="display:none;">
					<option value="A" '.($dns_list[$key]['type'] == 'A' ? 'selected=selected' : '').'>A</option>
					<option value="AAAA" '.($dns_list[$key]['type'] == 'AAAA' ? 'selected=selected' : '').'>AAAA</option>
					<option value="CNAME" '.($dns_list[$key]['type'] == 'CNAME' ? 'selected=selected' : '').'>CNAME</option>
					<option value="TXT" '.($dns_list[$key]['type'] == 'TXT' ? 'selected=selected' : '').'>TXT</option>
					<option value="PTR" '.($dns_list[$key]['type'] == 'PTR' ? 'selected=selected' : '').'>PTR</option>
					<option value="SRV" '.($dns_list[$key]['type'] == 'SRV' ? 'selected=selected' : '').'>SRV</option>
					<option value="CAA" '.($dns_list[$key]['type'] == 'CAA' ? 'selected=selected' : '').'>CAA</option>
				</select>
			</td>
			<td style="word-break: break-word; max-width: 350px;" id="recordtd'.$key.'">';
			if($dns_list[$key]['type'] == 'SRV'){
				echo '<div class="srv-form'.$key.'">';
				foreach($dns_list[$key]['record'] as $srk => $srv){
					echo '<b>'.ucfirst($srk).'</b> : <span class="mt-2" id="record_entry'.$srk.'span'.$key.'">'.$srv.'</span>
					<input type="'.(in_array($srk, ['priority', 'weight', 'port']) ? 'number' : 'text').'" name="record['.$srk.']" id="record_entry'.$srk.$key.'" data-dtype="srv" class="mt-2" style="display:none;"  value="'.$srv.'" size="10"><br>';
				}
				echo '</div>';
			}elseif($dns_list[$key]['type'] == 'CAA'){
				echo '<div class="caa-form'.$key.'">';
				foreach($dns_list[$key]['record'] as $crk => $crv){
					if($crk == 'flag'){
						//echo $crv;
						 //$crv = 128;
						echo '<b>'.ucfirst($crk).'</b> : <span class="mt-2" id="record_entry'.$key.$crk.'span">'.$crv.'</span>
						<label class="form-label me-2" style="display:none;">
							<input type="radio" name="record'.$key.'['.$crk.']" id="record_entry'.$key.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="0" '.($crv == 0 ? 'checked' : '').'>0
						</label>
						<label class="form-label me-2" style="display:none;">
							<input type="radio" name="record'.$key.'['.$crk.']" id="record_entry'.$key.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="128" '.($crv == 128 ? 'checked' : '').'>1
						</label><br>';
					}elseif($crk == 'tag'){
						// $crv = 128;
						echo '<b>'.ucfirst($crk).'</b> : <span class="mt-2" id="record_entry'.$key.$crk.'span">'.$crv.'</span>
						<label class="form-label me-2" style="display:none;">
							<input type="radio" name="record'.$key.'['.$crk.']" class="record_entry'.$key.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="issue" '.($crv == 'issue' ? 'checked' : '').'>'.__('Issue').'
						</label>
						<label class="form-label me-2" style="display:none;">
							<input type="radio" name="record'.$key.'['.$crk.']" class="record_entry'.$key.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="issuewild" '.($crv == 'issuewild' ? 'checked' : '').'>'.__('Issuewild').'
						</label>
						<label class="form-label me-2" style="display:none;">
							<input type="radio" name="record'.$key.'['.$crk.']" class="record_entry'.$key.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="iodef" '.($crv == 'iodef' ? 'checked' : '').'>'.__('Iodef').'
						</label><br>';
					}else{
						echo '<b>'.ucfirst($crk).'</b> : <span class="mt-2" id="record_entry'.$key.$crk.'span">'.$crv.'</span>
						<input type="text" name="record'.$key.'['.$crk.']" id="record_entry'.$key.''.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="'.$crv.'" size="10"><br>
						<label class="sai_exp2 mt-1 me-3" id="'.$key.'val_exp"></label>';
					}
				}
				echo '</div>';
			}else{
				echo '
				<div class="other-form'.$key.'">
					<span id="record'.$key.'">'.$dns_list[$key]['record'].'</span>
					<input type="text" name="record" id="record_entry'.$key.'" style="display:none;" value="'.$dns_list[$key]['record'].'" size="10">
				</div>';
			}
			echo '
			</td>
			<td width="2%">
				<i class="fas fa-undo cancel cancel-icon" title="'.__('Cancel').'" id="cid'.$key.'" style="display:none;"></i>
			</td>
			<td width="2%">
				<i class="fas fa-pen edit edit-icon" title="'.__('Edit').'" id="eid'.$key.'"></i>
			</td>
			<td width="2%">
				<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$key.'" onclick="delete_record(this)" data-domain="'.$domain_name.'" data-delete="'.$key.'"></i>
			</td>					
		</tr>';
		
		}
	}
	
	echo '
<tbody>
</table>
	
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[


function reset_dns(){
	
	var rdomain = $("#selectdomain").val();
	
	var d = {"reset_dns" : 1, "domain" : rdomain};
	
	var a, lan;
	lan = "'.__js('Are you sure you want to $0 reset DNS $1  of ', ['<b>', '</b>']).'<b>"+d.domain+"</b>";
	
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
	
	// Submit the data
	a.confirm.push(function(){
		submitit(d, {done_reload : window.location.href});
	});
	
	show_message(a);	
}

function caa_exp(id){
	
	var tag_v = $(".record_entry"+id+"tag:checked").val()
	
	if(id == "issue" || id == "issuewild" || id == "iodef"){
		tag_v = id;
		id = "caa";
	}
	
	if(tag_v == "iodef"){
		$("#"+id+"val_exp").html("'.__js('The location to which the certificate authority will report exceptions. Either a mailto or standard URL <br> For example : mailto:test@example.com , https://domain.com').'");
	}else{
		// console.log($("#"+id+"val_exp"))
		$("#"+id+"val_exp").html("'.__js('The ceritficate authorities domain name.<br> For example : buypass.com , letsencrypt.org').'");
	}
}

$(document).ready(function(){
	
	// For cancel
	$(".cancel").click(function() {
		var id = $(this).attr("id");
		id = id.substr(3);
		$("#cid"+id).hide();
		$("#eid"+id).removeClass("fa-save").addClass("fa-edit");
		$("#tr"+id).find("span").show();
		$("#tr"+id).find("input[type!=\"checkbox\"],.input").hide();
		$("#tr"+id).find("label,.label").hide();
		$("#tr"+id).css("background-color","");
	});
	
	// for editing record
	$(".edit").click(function() {
		var id = $(this).attr("id");
		id = id.substr(3);
		$("#cid"+id).show();
		$("#tr"+id).css("background-color","#d7edf9");
		
		var current_type = $("#type_entry"+id).val()
		var current_html = $("#recordtd"+id).children()
		
		caa_exp(id);
		
		$(".record_entry"+id+"tag").click(function(){
			caa_exp(id);
		})
		
		$("#type_entry"+id).change(function(){
		
			var rec_type = $(this).val();
			
			$(".other-form"+id).remove()
			$(".srv-form"+id).remove()
			$(".caa-form"+id).remove()
			
			var tmphtml = \'<div class="other-form\'+id+\'"><span id="record\'+id+\'"></span><input type="text" name="record" id="record_entry\'+id+\'" style=""  value="" size="10"></div>\'; 
			
			
			if(rec_type == "SRV"){
				var tmphtml = \'<div class="srv-form\'+id+\'"><b>Priority</b> : <span class="mt-2" id="record_entrypriorityspan\'+id+\'"></span><input type="text" name="record[priority]" id="record_entrypriority\'+id+\'" data-dtype="srv" class="mt-2" style=""  value="" size="10"></br>\'; 
				
				tmphtml += \'<b>Weight</b> : <span class="mt-2" id="record_entryweightspan\'+id+\'"></span><input type="text" name="record[weight]" id="record_entryweight\'+id+\'" data-dtype="srv" class="mt-2" style=""  value="" size="10"></br>\'; 
				
				tmphtml += \'<b>Port</b> : <span class="mt-2" id="record_entryportspan\'+id+\'"></span><input type="text" name="record[port]" id="record_entryport\'+id+\'" data-dtype="srv" class="mt-2" style=""  value="" size="10"></br>\'; 
				
				tmphtml += \'<b>Target</b> : <span class="mt-2" id="record_entrytargetspan\'+id+\'"></span><input type="text" name="record[target]" id="record_entrytarget\'+id+\'" data-dtype="srv" class="mt-2" style=""  value="" size="10"></br></div>\'; 
			}
			
			if(rec_type == "CAA"){ 
				var tmphtml = \'<div class="caa-form\'+id+\'"><b>Flag</b> : <span class="mt-2" id="record_entry\'+id+\'flagspan"></span><label class="form-label me-2" style=""><input type="radio" name="record\'+id+\'[flag]" id="record_entry\'+id+\'flag" data-dtype="caa" class="mt-2" style="" value="0" checked />0</label><label class="form-label me-2" style=""><input type="radio" name="record\'+id+\'[flag]" id="record_entry\'+id+\'flag" data-dtype="caa" class="mt-2" style="" value="128" />1</label><br>\';
				
				tmphtml += \'<b>Tag</b> : <span class="mt-2" id="record_entry\'+id+\'tagspan"></span><label class="form-label me-2"><input type="radio" name="record\'+id+\'[tag]" class="record_entry\'+id+\'tag" data-dtype="caa" value="issue" checked />issue&nbsp;</label><label class="form-label me-2"><input type="radio" name="record\'+id+\'[tag]" class="record_entry\'+id+\'tag" data-dtype="caa" value="issuewild" />issuewild&nbsp;</label><label class="form-label me-2"><input type="radio" name="record\'+id+\'[tag]" class="record_entry\'+id+\'tag" data-dtype="caa" value="iodef" />iodef&nbsp;</label><br>\';
				
				tmphtml += \'<b>Value</b> : <span class="mt-2" id="record_entry\'+id+\'valuespan"></span><input type="text" name="record\'+id+\'[value]" id="record_entry\'+id+\'value" data-dtype="caa" class="mt-2" style=""  value="" size="10"></br><label class="sai_exp2 mt-1 me-3" id="\'+id+\'val_exp"></label></div>\'; 	
			}
			

			if(rec_type == current_type){
				$("#recordtd"+id).append(current_html);
			}else{
				$("#recordtd"+id).append(tmphtml);
			}
			
			caa_exp(id);
			$(".record_entry"+id+"tag").click(function(){
				caa_exp(id);
			})
			
			$("#cid"+id).click(function(){
				$("#recordtd"+id).children().remove();
				$("#recordtd"+id).append(current_html);
				$("#type_entry"+id).val(current_type);
				$("#tr"+id).find("span").show();
				$("#tr"+id).find("input[type!=\"checkbox\"],.input").hide();
			})
		})
		
		// Submit the form
		if($("#eid"+id).hasClass("fa-save")){
			
			var d = $("#tr"+id).find("input, textarea, select").serialize();
			// console.log(d);return;
			
			submitit(d, {
				done: function(){
					var tr = $("#tr"+id);
					tr.find(".cancel").click();// Revert showing the inputs
					var select_field = tr.find("select");
					
					tr.find("input, textarea, select").each(function(){
						var jE = $(this);
						var record_type = select_field.closest("td").find("span").html();
						
						if(jE.attr("type") == "hidden"){
							return;
						}
						
						if(jE.attr("name") == "record"){
							var value = jE.val();
							value = value.split("<").join("&lt;").split(">").join("&gt;");
							if(record_type == "CNAME"){
								jE.closest("td").find("span").html(value+".");
							}else{
								jE.closest("td").find("span").html(value);
							}
						}
						
					});
				},
				sm_done_onclose: function(){			
					$("#tr"+id).find("span").show();
					$("#tr"+id).find("input[type!=\"checkbox\"],.input").hide();
					$("#tr"+id).css("background-color","");	
					
					var url = window.location.href;
					window.location.href = url
				}
			});
			
		}else{
			$("#eid"+id).addClass("fa-save").removeClass("fa-edit");
			
			$("#tr"+id).find("span").hide();
			$("#tr"+id).find("input,.input").show();
			$("#tr"+id).find("label,.label").show();
			
			if($("#type"+id).text() == "CNAME" || $("#type"+id).text() == "PTR"){
				$("#record_entry"+id).val($("#record"+id).text().substring(0, $("#record"+id).text().length - 1));
			}else if($("#type"+id).text() == "SRV"){
				$("#record_entrytarget"+id).val($("#record_entrytargetspan"+id).text().substring(0, $("#record_entrytargetspan"+id).text().length - 1));
			}else{
				$("#record_entry"+id).val($("#record"+id).text());
			}
			$("#record_entry"+id).show().focus();
		}
	});			
});

$(document).ready(function(){
	
	$("#checkall").change(function(){
		$(".check_dns").prop("checked",$(this).prop("checked"))
	});
	
	$("input:checkbox").change(function(){
		if($(".check_dns:checked").length){
			$("#delete_selected").removeAttr("disabled");
		}else{
			$("#delete_selected").prop("disabled",true);
		}
	});
	
});

function exportdnsfile(){
	let dom = $("#selectdomain").val();
	window.location = "'.$globals['index'].'act=advancedns&exportdns=1&dom="+dom;
	return false;
}

function delete_dns(el){
	var a;
	var jEle = $(el);
	var arr = [];
	
	$("input:checkbox[name=check_dns]:checked").each(function(){
		var dns = $(this).val();
		arr.push(dns);
	});
	var dom = $("#addnsdomain").val();
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete this selected DNS(s) ?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"delete" : arr.join(), "domain" : dom};
		submitit(d,{
			sm_done_onclose: function(){			
				$("#selectdomain").trigger("change");			
			}
		});
	});
	show_message(a);
}
// ]]></script>';

}
