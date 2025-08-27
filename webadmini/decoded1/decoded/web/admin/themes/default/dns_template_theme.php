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

function dns_template_theme(){

global $user, $globals, $theme, $softpanel, $catwise, $error, $done, $tmpl_list, $domain, $is_running;

	softheader(__('DNS Templates'));
	
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'advancedns.png" class="webu_head_img me-1"/>'.__('DNS Templates').'
		<span class="search_btn float-end mt-1">
			<a type="button" class="float-end" data-bs-toggle="modal" data-bs-target="#dns_soa_settings"><i class="fas fa-cogs"></i></a>
		</span>
	</div>
</div>
	
<div class="soft-smbox p-3 mt-4">';

	// Check for the service working or not
	if(empty($is_running)){
		echo '
		<div class="alert alert-danger p-2 text-center">
			<a href="'.$globals['ind'].'act=services" class="mt-1 text-decoration-none" style="font-size:15px;">'.__('Your Bind service is not running currently. Click here to start.').'</a>
		</div>';
	}
	
	$dns_settings = $globals['dns_conf'];
	
	echo '
	<div class="modal fade" id="dns_soa_settings" tabindex="-1" aria-labelledby="dns_soa" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="dns_soa_label">'.__('DNS Zone Settings').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
				<form action="" method="POST" name="zone_defaults" id="zone_defaults" class="form-horizontal" onsubmit="return submitit(this)">

					<div class="row mb-3 justify-content-center p-2">
						
						<div class="col-12 col-md-12 col-lg-12">
							<label class="form-label mt-3 mb-3"><h5>Zone defaults</h5></label>
						</div>
						
						<div class="col-12 col-md-4 col-lg-4">
							<label class="sai_head" for="TTL">'.__('TTL (in seconds)').'
								<span class="sai_exp">'.__('Defailt TTL value for zone').'</span>
							</label>
						</div>
						<div class="col-12 col-md-6 mb-1">
							<input type="number" name="ttl" class="form-control" value="'.aPOSTval('ttl', (empty($dns_settings['ttl']) ? '' : $dns_settings['ttl'])).'" />
							<label class="sai_exp2 mb-3" id="">Default:  14400</label>
						</div>
						
						<div class="col-12 col-md-12 col-lg-12">
							<label class="form-label mt-3 mb-3"><h5>SOA record</h5></label>
						</div>
						
						<div class="col-12 col-md-4 col-lg-4">
							<label class="sai_head" for="refresh">'.__('Refresh (in seconds)').'
								<span class="sai_exp">'.__('This field indicates how often, in seconds, a slave name server should check with the master name server to see if an update is needed. For example, 7200 indicates a period of two hours').'</span>
							</label>
						</div>
						<div class="col-12 col-md-6 mb-1">
							<input type="number" name="refresh" class="form-control" value="'.aPOSTval('refresh', (empty($dns_settings['refresh']) ? '' : $dns_settings['refresh'])).'" />
							<label class="sai_exp2 mb-3" id="">Default:  86400</label>
						</div>
						
						<div class="col-12 col-md-4 col-lg-4">
							<label class="sai_head" for="retry">'.__('Retry (in seconds)').'
								<span class="sai_exp">'.__('This field indicates how long, in seconds, a slave server is to retry after a failure to check for a refresh').'</span>
							</label>
						</div>
						<div class="col-12 col-md-6 mb-1">
							<input type="number" name="retry" class="form-control" value="'.aPOSTval('retry', (empty($dns_settings['retry']) ? '' : $dns_settings['retry'])).'" />
							<label class="sai_exp2 mb-3" id="">Default:  7200</label>
						</div>
						
						<div class="col-12 col-md-4 col-lg-4">
							<label class="sai_head" for="expire">'.__('Expire (in seconds)').'
								<span class="sai_exp">'.__('This field is the upper limit, in seconds, that a slave name server is to use the data before it expires for lack of getting a refresh').'</span>
							</label>
						</div>
						<div class="col-12 col-md-6 mb-1">
							<input type="number" name="expire" class="form-control" value="'.aPOSTval('expire', (empty($dns_settings['expire']) ? '' : $dns_settings['expire'])).'" />
							<label class="sai_exp2 mb-3" id="">Default:  3600000</label>
						</div>
						
						<div class="col-12 col-md-4 col-lg-4">
							<label class="sai_head" for="minimum">'.__('Minimum (in seconds)').'
								<span class="sai_exp">'.__('Used in calculating the time to live for purposes of negative caching. Authoritative name servers take the smaller of the SOA TTL and the SOA MINIMUM to send as the SOA TTL in negative responses').'</span>
							</label>
						</div>
						<div class="col-12 col-md-6 mb-1">
							<input type="number" name="minimum" class="form-control" value="'.aPOSTval('minimum', (empty($dns_settings['minimum']) ? '' : $dns_settings['minimum'])).'" />
							<label class="sai_exp2 mb-3" id="">Default:  86400</label>
						</div>
						
						<div class="col-12 col-md-4 col-lg-4">
							<label class="sai_head" for="minimum">'.__('Rname').'
								<span class="sai_exp">'.__('The name email of the party responsible for the DNS zone.').'</span>
							</label>
						</div>
						
						<div class="col-12 col-md-6 mb-1">
							<select class="form-control" id="rname_" name="rname">
								<option value="default" '.POSTSelect('rname', 'default', $dns_settings['rname'] == 'default').'>'.__('Default').'</option>
								<option value="umail" '.POSTSelect('rname', 'umail', $dns_settings['rname'] == 'umail').'>'.__('Users Email').'</option>
								<option value="custom" '.POSTSelect('rname', 'custom', $dns_settings['rname'] == 'custom').'>'.__('Custom').'</option>
							</select>
							<input type="email" id="rname_id" name="rname_val" '.($dns_settings['rname'] == 'custom' ? 'class="form-control mt-2" value="'.$dns_settings['soa_email'].'"' : 'class="form-control mt-2 d-none"').'/>
							<label class="sai_exp2 mb-3" id="">Default:  user.'.$globals['WU_PRIMARY_DOMAIN'].'</label>
						</div>
						
					</div>
					
					<div class="row mb-2 text-center">
						<div class="col-12">
							<input class="btn btn-primary" type="submit" name="zone_defaults" id="zone_defaults" value="'.__('Save').'">
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addRecordLabel">'.__('Add Record').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="alert alert-info p-1 text-center">
						This record will be applied to all newly created domains.
					</div>
					<form accept-charset="'.$globals['charset'].'" action="" method="post" name="dnstemplate" id="dnstemplate" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
						<label for="name" class="sai_head d-block mt-3">'.__('Name').'</label>
						<div class="input-group mb-3">
							<input type="text" name="name" id="name" class="form-control" />
							<span class="input-group-text" id="domainname">.domain_name.</span>
						</div>
						<label for="ttl" class="sai_head">'.__('TTL').'</label>
						<input type="text" name="ttl" id="ttl" class="form-control mb-3" size="30" value="14400" />
						<label for="selecttype" class="sai_head">'.__('Type').'</label>
						<select name="selecttype" id="selecttype" class="form-select mb-3" onchange="disp_type(this.value)">
							<option value="A">A</option>
							<option value="AAAA">AAAA</option>
							<option value="CNAME">CNAME</option>
							<option value="TXT">TXT</option>
							<option value="PTR">PTR</option>
							<option value="MX">MX</option>
						</select>
						<label for="priority" class="sai_head d-none" id="priority_lbl">Priority</label>
						<input type="text" id="priority" name="priority" class="form-control d-none" value="" />
						<label for="address" class="sai_head" id="type">'.__('Type').'</label>
						<input type="text" id="address" name="address" class="form-control" value="" />
						<label class="sai_exp2 mb-3" id="exp">'.__('You can use $0 $tp $1 which is a variable and will be replaced with the real $tp1 when the DNS record is being added', ['tp'=>'ipv_4', 'tp1'=>'IPv4', '<b>', '</b>']).'</label>
						<div class="text-center my-3">
							<input type="submit" class="btn btn-primary" value="'.__('Add Record').'" name="create_record" id="submitdns" /> 
						</div>';
						echo '		
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row mb-4 mt-4">
		<div class="col-12 p-2">
			<lable class="form-label">Total DNS records : '.count($tmpl_list).'</label>
			
			<span data-bs-toggle="modal" data-bs-target="#addRecordModal">
				<button type="button" class="btn btn-primary btn-sm float-end me-2 open_modal" data-bs-html="true" data-bs-toggle="tooltip" title="'.__('Click here to add record to DNS zone template').'">'.__('Add Record').'</button>
			</span>
			
			<button type="button" class="btn btn-danger btn-sm float-end me-2 reset_dns_template" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Click here to restore the default DNS Zone template configuration').'">'.__('Reset to default').'</button>
			
			<button type="button" class="btn btn-primary btn-sm float-end me-2 modify_existing_dns" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Click here to apply the DNS Zone template changes to existing DNS zones').'">'.__('Apply DNS Template changes').'</button>
		</div>
	</div>
	
	<div id="showrectab" class="table-responsive">';
		showdnstemplate();
	echo ' 
	</div>
</div>
	
<script language="javascript" type="text/javascript">

// For cancel
$(document).on("click", ".cancel", function() {
	var id = $(this).attr("id");
	id = id.substr(3);
	$("#cid"+id).hide();
	$("#eid"+id).removeClass("fa-save").addClass("fa-edit");
	$("#tr"+id).find("span").show();
	$("#tr"+id).find("input,.input").hide();
});

// for editing record
$(document).on("click", ".edit", function() {
	var id = $(this).attr("id");
	id = id.substr(3);
	$("#cid"+id).show();
	
	// Submit the form
	if($("#eid"+id).hasClass("fa-save")){
		
		var d = $("#tr"+id).find("input, textarea, select").serialize();
		
		submitit(d, {
			done: function(){
				var tr = $("#tr"+id);
				tr.find(".cancel").click();// Revert showing the inputs
				
				tr.find("input, textarea, select").each(function(){
					var jE = $(this);
					
					if(jE.attr("type") == "hidden"){
						return;
					}
					
					if(jE.attr("name") == "record"){
						if(jE.closest("td").find("span").length > 1){
							jE.closest("td").find("span:last").html(jE.val()+".");
						}else{
							jE.closest("td").find("span").html(jE.val()+".");
						}
						return;
					}
					
					jE.closest("td").find("span").html(jE.val());
					
				});
			},
			sm_done_onclose: function(){
				$("#tr"+id).find("span").show();
				$("#tr"+id).find("input,.input").hide();					
			}
		});
		
	}else{
		$("#eid"+id).addClass("fa-save").removeClass("fa-edit");
		
		$("#tr"+id).find("span").hide();
		$("#tr"+id).find("input,.input").show();
		
		if($("#type"+id).text() == "CNAME" || $("#type"+id).text() == "MX" || $("#type"+id).text() == "PTR"){
			$("#record_entry"+id).val($("#record"+id).text().substring(0, $("#record"+id).text().length - 1));
		}else{
			$("#record_entry"+id).val($("#record"+id).text());
		}
		$("#record_entry"+id).show().focus();
	}
});

$(".reset_dns_template").click(function(){
	
	var lan = "'.__js('Are you sure that you want to restore the default configuration of the DNS zone template ?').'";
	
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
	
	// Submit the data
	a.confirm.push(function(){
		var d = {"reset_template" : 1};
		submitit(d, {done_reload : window.location.href});
	});	
		
	show_message(a);
});

$(".modify_existing_dns").click(function(){
	
	var lan = \'<div class="form-check"> <input class="form-check-input" type="radio" name="modify" id="radio1" value="1">  <label class="form-check-label" for="radio1">\'+"'.__js('Apply the changes to unaltered zones. If a zone was customized (new records were added or existing ones were changed), Webuzo will not apply the changes from the template to such zones.').'"+\'  </label></div><div class="form-check">  <input class="form-check-input" type="radio" name="modify" id="radio2" value="0"> <label class="form-check-label" for="radio2">	\'+"'.__js('Apply the changes to all zones. Webuzo will apply changes from the template to all DNS zones including the customized ones. Note that user-modified records always remain intact. For example, if the template contains a new record that was already added by a customer, Webuzo will keep the customer\'s record.').'"+\'</label></div>\'
	
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
	
	// Submit the data
	a.confirm.push(function(){
		
		var d = {"modify_all" : 1, "m_option": $("input[name=modify]:checked").val()};
		submitit(d);
		// submitit(d, {done_reload : window.location.href});
	});	
		
	show_message(a);
});

$("#rname_").change(function(){
	if(this.value == "custom"){
		$("#rname_id").removeClass("d-none");
	}else{
		$("#rname_id").addClass("d-none");
	}
})

</script>';

	softfooter();
}

function showdnstemplate(){

	global $user, $globals, $theme, $softpanel, $catwise, $error, $done, $tmpl_list, $domain, $domains;
	
	echo '
	<table class="table webuzo-table" >			
		<thead>
			<tr>
				<th>'.__('Name').'</th>
				<th>'.__('TTL').'</th>
				<th>'.__('Class').'</th>
				<th>'.__('Type').'</th>
				<th>'.__('Record').'</th>
				<th colspan="3" style="text-align:right">'.__('Option').'</th>
			</tr>
		</thead>
		<tbody>';
		
		if(empty($tmpl_list)){
			echo '
			<tr class="text-center">
				<td colspan=6>
					<span>'.__('No DNS record(s) found').'</span>
				</td>
			</<tr>';
		}else{
			
			foreach($tmpl_list as $key => $value){
				
				if(!preg_match('/domain_name/is', $value['name'])){		
					$value['name'] = $value['name'].'.domain_name.';
				}
				
				echo '
				<tr id="tr'.$key.'">
					<td>
						 <span id="name'.$key.'">'.$value['name'].'</span>
						 <input type="text" id="name_entry'.$key.'" style="display:none;" name="name" value="'.$value['name'].'">
						 <input type="hidden" name="edit_record" value="'.$key.'" />
					</td>
					<td>
						<span id="ttl'.$key.'">'.$value['ttl'].'</span>
						<input type="text" name="ttl" id="ttl_entry'.$key.'" style="display:none;" size="3" value="'.$value['ttl'].'">
					</td>
					<td>
						'.$value['class'].'
					</td>
					
					<td>
						<span id="type'.$key.'">'.$value['type'].'</span>			
						<select class="input" name="type" id="type_entry'.$key.'" style="display:none;">';
						if($value['type'] != 'MX'){
							echo '
							<option value="A" '.($value['type'] == 'A' ? 'selected=selected' : '').'>A</option>
							<option value="AAAA" '.($value['type'] == 'AAAA' ? 'selected=selected' : '').'>AAAA</option>
							<option value="CNAME" '.($value['type'] == 'CNAME' ? 'selected=selected' : '').'>CNAME</option>
							<option value="PTR" '.($value['type'] == 'PTR' ? 'selected=selected' : '').'>PTR</option>
							<option value="TXT" '.($value['type'] == 'TXT' ? 'selected=selected' : '').'>TXT</option>';
							
						}else{
							echo '<option value="MX" '.($value['type'] == 'MX' ? 'selected=selected' : '').'>MX</option>';
						}
						echo '
						</select>
					</td> 
					
					<td style="word-break: break-word; max-width: 350px;">';
						if($value['type'] == 'MX'){
							echo '<span id="priority'.$key.'">'.$value['priority'].'</span>
							<input type="text" name="priority" id="priority_entry'.$key.'" style="display:none;"  value="'.$value['priority'].'" size="5">';
						}
						echo '
						<span id="record'.$key.'">'.$value['record'].'</span>
						<input type="text" name="record" id="record_entry'.$key.'" style="display:none;"  value="'.$value['record'].'" size="10">
					</td>
					
					<td width="2%">
						<i class="fas fa-undo-alt cancel cancel-icon" title="Cancel" id="cid'.$key.'" style="display:none;"></i>
					</td>
					<td width="2%">
						<i class="fas fa-pencil-alt edit edit-icon fa-edit" id="eid'.$key.'" title="Edit"></i>
					</td>
					<td width="2%">
						<i class="fas fa-trash delete delete-icon" title="Delete" id="did'.$key.'" data-delete_record="'.$key.'" onclick="delete_record(this)"></i>
					</td>
				</tr>';
				
			}
		}		
	echo '
		<tbody>
	</table>
	
<script language="javascript" type="text/javascript">
function disp_type(str){
	$_("type").innerHTML = str;
	
	$("#priority_lbl").addClass("d-none");
	$("#priority").addClass("d-none");
	$("#name").removeClass("d-none");
	$("#domainname").html(".domain_name.");
	
	if(str == "A"){
		$("#exp").html("'.__js('You can use $0 $tp $1 which is a variable and will be replaced with the real $tp1 when the DNS record is being added', ['tp'=>'ipv_4', 'tp1'=>'IPv4', '<b>', '</b>']).'").show();
	}else if(str == "AAAA"){
		$("#exp").html("'.__js('You can use $0 $tp $1 which is a variable and will be replaced with the real $tp1 when the DNS record is being added', ['tp'=>'ipv_6', 'tp1'=>'IPv6', '<b>', '</b>']).'").show();
	}else if(str == "CNAME" || str == "PTR"){
		$("#exp").html("'.__js('You can use $0 $tp $1 which is a variable and will be replaced with the real $tp1 when the DNS record is being added', ['tp'=>'domain_name', 'tp1'=>'Domain name', '<b>', '</b>']).'").show();
	}else if(str == "MX"){
		$("#domainname").html("domain_name.");
		$("#priority_lbl").removeClass("d-none");
		$("#priority").removeClass("d-none");
		$("#type").html("Destination");
		$("#exp").html("'.__js('You can use $0 $tp $1 which is a variable and will be replaced with the real $tp1 when the DNS record is being added', ['tp'=>'domain_name', 'tp1'=>'Domain name', '<b>', '</b>']).'").show();
	}else{
		$("#exp").hide();
	}
}
</script>';

}
