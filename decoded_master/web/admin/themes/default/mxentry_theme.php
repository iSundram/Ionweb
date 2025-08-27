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

function mxentry_theme(){

	global $user, $globals, $theme, $softpanel, $WE, $catwise, $error, $done, $domains_list, $dns_list, $domain_name;
	
	// To update domains links
	if(optGET('ajaxdom')){			
		showmx();
		return true;
	}
		
	softheader(__('MX Entry'));
		
	echo '
<div class="soft-smbox p-3 mb-4">
	<div class="sai_main_head">
		<i class="fa fa-solid fa-at fa-xl"></i>
		<h5 class="d-inline-block">'.__('MX Entry').'</h5>
	</div>
</div>
<div class="soft-smbox p-4 mt-4">
	<div class="modal fade" id="add-MX" tabindex="-1" aria-labelledby="add-mxLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="add-dnsLabel">'.__('Add New MX Record').'</h5>
					<button type="button" class="btn-close add_mx_close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<form accept-charset="'.$globals['charset'].'" action="" method="post" name="mxentry" id="mxentry" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
						<label for="addmxdomain" class="sai_head">'.__('Select Domain').'</label>
						<select class="form-select search_val mb-5 make-select2" s2-placeholder="'.__('Select Domain').'" s2-ajaxurl="'.$globals['index'].'act=dns_zones&api=json" s2-query="dom_search" s2-data-key="dns_zones" s2-dropdownparent="#add-MX" style="width: 100%" name="domain" id="f_dom_search">
							<option value="'.$domain_name.'">'.$domain_name.'</option>
						</select>
						<label for="name" class="sai_head">'.__('Name').'</label>
						<input type="text" name="name" id="_name" class="form-control" value="@" />
						<label class="sai_exp2 mb-3">'.__('@ symbol is used to represent "the current domain"').'</label></br>
						<label for="priority" class="sai_head mt-3">'.__('Priority').'</label>
						<input type="text" name="priority" id="priority" class="form-control mb-3" />
						<label for="destination" class="sai_head">'.__('Destination').'</label>
						<input type="text" name="destination" id="destination" class="form-control mb-3" />
						<center>
							<input type="submit" class="btn btn-primary me-2" value="'.__('Add Record').'" name="add" id="submitmx" />
						</center>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="record-table mb-5 mt-5 position-relative">
		<div class="row">
			<div class="col-5">
				<button type="button" class="btn btn-primary me-2 external_mx" data-mxtemplate="office365" data-bs-html="true" data-bs-toggle="tooltip" title="'.__('Click here to update MX records of the selected domain to the default Microsoft 365 MX values').'">'.__('Microsoft 365 MX').'</button>
				<button type="button" class="btn btn-primary me-2 external_mx" data-mxtemplate="google" data-bs-html="true" data-bs-toggle="tooltip" title="'.__('Click here to update MX records of the selected domain to the Google MX record values').'">'.__('Google Suite MX').'</button>
			</div>
			<div class="col-4">
				<h4 class="sai_sub_head d-inline-block">'.__('MX Records of'). '</h4>
				<select class="form-select dom_search my-3 make-select2" s2-placeholder="'.__('Select Domain').'" s2-ajaxurl="'.$globals['index'].'act=dns_zones&api=json" s2-query="dom_search" s2-data-key="dns_zones" style="width:auto" name="dom_search" id="dom_search">
					<option value="'.$domain_name.'">'.$domain_name.'</option>
				</select>
			</div>
			<div class="col-3">
				<button type="button" class="btn btn-primary float-end add-mx" data-bs-toggle="modal" data-bs-target="#add-MX">'.__('Add Record').'</button>
			</div>
		</div>
		<div class="col-6 pb-2 pt-3">
			<input type="button" class="btn btn-primary" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="delete_mxentry(this)" style="float: left;" disabled>
		</div>
	</div>
	<div id="showrectab" class="table-responsive">';
		showmx();
	echo '
	</div>
</div>
	
<script language="javascript" type="text/javascript">
$(document).ready(function () {
	$(window).on("hashchange", add_mx_hash);
	add_mx_hash();

});

function add_mx_hash(){
	var hashval = window.location.hash.substr(1);
	if(hashval == "add-MX"){
		$(".add-mx").click();
	}
}

$("#dom_search").on("select2:select", function(e, dom = {}){
	
	var domain;

	if("domain" in dom){
		domain = dom.domain; 
	}else{
		domain = $("#dom_search option:selected").val();
	}
	// console.log(domain);return false;
	
	$.ajax({				
		type: "POST",	
		url: "'.$globals['admin_url'].'act=mxentry&ajaxdom=1&domain="+domain,	
		success: function(data){
			$("#showrectab").html(data);
			
			// Set the value, creating a new option if necessary
			if ($("#f_dom_search").find("option[value=\'" + domain + "\']").length) {
				$("#f_dom_search").val(domain).trigger("change");
			} else { 
				// Create a DOM Option and pre-select by default
				var newOption = new Option(domain, domain, true, true);
				// Append it to the select
				$("#f_dom_search").append(newOption).trigger("change");
			}
			
		}
	});
});

$("#f_dom_search").on("change", function(){
	$("#_name").val("@");
});

$("#mxentry").on("done", function(){
	$("#dom_search").trigger("select2:select", {domain:$("#f_dom_search").val()});
});

// Reload the data on add
$(".external_mx").on("click", function(){
	
	var dom = $("#dom_search").val();
	var template = $(this).data("mxtemplate");
	
	var lan = "'.__js('Are you sure that you want to update the MX records ?').'";
	
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
	
	// Submit the data
	a.confirm.push(function(){
		var d = {"mxhandler" : template, "mxtemplate" : 1, "domain" : dom};
		submitit(d,{
			sm_done_onclose: function(){			
				$("#dom_search").trigger("select2:select", {domain:dom});				
			}
		});
	});	
		
	show_message(a);
	
});
</script>';
	
	softfooter();

}

function showmx(){

	global $globals, $softpanel, $WE, $error, $dns_list, $domain_name, $theme;
	
	echo '
<table class="table webuzo-table">
	<thead class="sai_head2">
		<tr>
			<th class="align-middle"><input type="checkbox" id="checkAll"></th>
			<th class="align-middle">'.__('Name').'</th>
			<th class="align-middle">'.__('Priority').'</th>
			<th class="align-middle">'.__('Destination').'</th>
			<th class="align-middle" colspan="3">'.__('Option').'</th>
		</tr>
	</thead>
	<tbody>';
	// r_print($dns_list);
	if(empty($dns_list)){
		
		echo '
	<tr>
		<td class="text-center" colspan=5><span>'.__('No MX Entry Found').'</span></td>
	<tr>';
		
	}else{
		
		foreach ($dns_list as $key => $value){
			
			echo '
	<tr id="tr'.$key.'" >
		<td>
			<input type="checkbox" name="check_mxentry" class="check_mxentry" value="'.$key.'" data-domain="'.$domain_name.'">
		</td>
		<td>
			<span id="name'.$key.'">'.$dns_list[$key]['name'].'</span>
			<input type="text" name="name" id="name_entry'.$key.'" value="'.$dns_list[$key]['name'].'" disabled=disabled style="display:none;">
			<input type="hidden" name="edit" value="'.$key.'" />
		</td>
		<td>
			<span id="priority'.$key.'">'.$dns_list[$key]['priority'].'</span>
			<input type="text" name="priority" id="priority_entry'.$key.'"  value="'.$dns_list[$key]['priority'].'" style="display:none;">
		</td>
		<td>
			<span id="destination'.$key.'">'.rtrim($dns_list[$key]['destination'], '.').'.</span>
			 <input type="text" name="destination" value="'.rtrim($dns_list[$key]['destination'], '.').'" id="destination_entry'.$key.'" style="display:none;">
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
	</tbody>
</table>
<script>
$("#checkAll").change(function () {
	$(".check_mxentry").prop("checked", $(this).prop("checked"));
});

$("input:checkbox").change(function() {
	if($(".check_mxentry:checked").length){
		$("#delete_selected").removeAttr("disabled");
	}else{
		$("#delete_selected").prop("disabled", true);
	}
});

function delete_mxentry(el){
	var a;
	var jEle = $(el);
	var arr = [];
	
	$("input:checkbox[name=check_mxentry]:checked").each(function(){
		var mxentry = $(this).val();
		arr.push(mxentry);
	});
	var dom = $("#f_dom_search").val();
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete this selected MX Entry(s) ?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"delete" : arr.join(), "domain" : dom};
		submitit(d,{
			sm_done_onclose: function(){
				$("#dom_search").trigger("select2:select");
			}
		});
	});
	show_message(a);
}
	
// For cancel
$(".cancel").click(function() {
	var id = $(this).attr("id");
	id = id.substr(3);
	$("#cid"+id).hide();
	$("#eid"+id).removeClass("fa-save").addClass("fa-edit");
	$("#tr"+id).find("span").show();
	$("#tr"+id).find("input,.input").hide();
});
	
// For editing record
$(".edit").click(function() {
	var id = $(this).attr("id");
	id = id.substr(3);
	$("#cid"+id).show();
		
	// Submit the form
	if($("#eid"+id).hasClass("fa-save")){
		var d = $("#tr"+id).find("input, textarea, select").serialize();
		d += "&domain="+$("#dom_search").val();
		
		// console.log(id, "d", d);return;
		submitit(d, {
			done: function(){
				var tr = $("#tr"+id);
				tr.find(".cancel").click();// Revert showing the inputs
					
				tr.find("input, textarea, select").each(function(){
					var jE = $(this);
					if(jE.attr("type") == "hidden"){
						return;
					}
					if(jE.attr("name") == "destination"){
						jE.closest("td").find("span").html(jE.val()+".");
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
		$("#destination_entry"+id).show().focus();
	}
});

</script>';

}

