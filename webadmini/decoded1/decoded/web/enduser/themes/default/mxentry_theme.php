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

	global $user, $globals, $theme, $softpanel, $WE, $catwise, $error, $done, $domain_list, $dns_list, $domain_name;
	
	// To update domains links
	if(optGET('ajaxdom')){			
		showmx();
		return true;
	}
		
	softheader(__('MX Entry'));

	echo '
<div class="card soft-card p-3">
	<div class="sai_main_head ">
		<img src="'.$theme['images'].'mx_entry.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('MX Entry').'</h5>
		<button type="button" class="flat-butt float-end" data-bs-toggle="modal" data-bs-target="#add-MX">'.__('Add Record').'</button>
	</div>
</div>
<div class="card soft-card p-4 mt-4">
	<div class="modal fade" id="add-MX" tabindex="-1" aria-labelledby="add-mxLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="add-dnsLabel">'.__('Add New MX Record').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<form accept-charset="'.$globals['charset'].'" action="" method="post" name="mxentry" id="mxentry_add" class="form-horizontal" onsubmit="return submitit(this)">
						<label for="addmxdomain" class="sai_head">'.__('Zone').'</label>
						<select class="form-select mb-3" id="addmxdomain" name="domain">';
							foreach ($domain_list as $key => $value){
								if($domain_name == $key){			
									echo '<option value='.$key.' selected=selected >'.$key.'</option>';
								}else{
									echo '<option value='.$key.'>'.$key.'</option>';
								}					
							}
						echo '
						</select>
						<label for="name" class="sai_head">'.__('Name').'</label>
						<input type="text" name="name" id="_name" class="form-control" value="@" />
						<label class="sai_exp2 mb-3">'.__('@ symbol is used to represent "the current domain"').'</label></br>
						<label for="priority" class="sai_head">'.__('Priority').'</label>
						<input type="text" name="priority" id="priority" class="form-control mb-3" />
						<label for="destination" class="sai_head">'.__('Destination').'</label>
						<input type="text" name="destination" id="destination" class="form-control mb-3" />
						<center>
							<input type="submit" class="flat-butt me-2" value="'.__('Add Record').'" name="add" id="submitmx" />
						</center>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="record-table mb-2">
		<div class="row">
			<div class="col-md-5 col-sm-12 mb-2">
				<button type="button" class="flat-butt me-2 mb-1 external_mx" data-mxtemplate="office365" data-bs-html="true" data-bs-toggle="tooltip" title="'.__('Click here to update MX records of the selected domain to the default Microsoft 365 MX record values').'">'.__('Microsoft 365 MX').'</button>
				<button type="button" class="flat-butt me-2 mb-1 external_mx" data-mxtemplate="google" data-bs-html="true" data-bs-toggle="tooltip" title="'.__('Click here to update MX records of the selected domain to the Google MX record values').'">'.__('Google Suite MX').'</button>
			</div>
			<div class="col-md-7 col-sm-12">
				<h4 class="sai_sub_head d-inline-block">'.__('MX Records of').'</h4>
				<select id="selectdomain" class="mw-100">';
					foreach ($domain_list as $key => $value){
						if($domain_name == $key){			
							echo '<option value='.$key.' selected=selected >'.$key.'</option>';
						}else{
							echo '<option value='.$key.'>'.$key.'</option>';
						}
					}
				echo '
				</select>
			</div>
		</div>
	</div>
	<div id="showrectab">';
		showmx();
	echo '
	</div>
</div>
	
<script language="javascript" type="text/javascript">
$(document).ready(function(){	
	$("#addmxdomain").val($("#selectdomain").val());	
	$("#addmxdomain").trigger("change");
	$("#selectdomain").change(function(){
		$(".loading").show();
		var domain = $(this).val();
		$.ajax({				
			type: "POST",				
			url: window.location+"&ajaxdom=1&domain="+domain,
			success: function(data){
				$(".loading").hide();
				$("#showrectab").html(data);
			}  
		});	
	});
});	

$("#selectdomain").change(function(){
	$("#addmxdomain").val($("#selectdomain").val());
	$("#addmxdomain").trigger("change");
});

$("#addmxdomain").change(function(){
	$("#_name").val("@");
});

// Reload the data on add
$("#mxentry_add").on( "done", function(){
	$("#selectdomain").val($("#addmxdomain").val()).trigger("change");
});



// Reload the data on add
$(".external_mx").on("click", function(){
	
	var dom = $("#selectdomain").val();
	var template = $(this).data("mxtemplate");
	
	var lan = "'.__js('Are you sure that you want to update the MX records ?').'";
	
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
	
	// Submit the data
	a.confirm.push(function(){
		var d = {"mxhandler" : template, "mxtemplate" : 1, "domain" : dom};
		submitit(d,{
			sm_done_onclose: function(){			
				$("#selectdomain").trigger("change");				
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
<div class="sai_sub_head record-table mb-2 position-relative" style="text-align:right;">
	<input type="button" class="btn btn-danger delete_selected" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_mx(this)" disabled>
</div>
<div class="table-responsive">
<table class="table align-middle table-nowrap mb-0 webuzo-table">
	<thead class="sai_head2">
		<tr>
			<th><input type="checkbox" id="checkall"></th>
			<th class="align-middle">'.__('Name').'</th>
			<th class="align-middle">'.__('Priority').'</th>
			<th class="align-middle">'.__('Destination').'</th>
			<th class="align-middle" colspan="3">'.__('Option').'</th>
		</tr>
	</thead>
	<tbody>';
	
	if(empty($dns_list)){
		
		echo '
	<tr>
		<td class="text-center" colspan=5><span>'.__('No MX Entry Found').'</span></td>
	<tr>';
		
	}else{
		
		foreach ($dns_list as $key => $value){
			
			echo '
	<tr id="tr'.$key.'" >
		<td><input type="checkbox" class="check_mx" name="check_mx" value="'.$key.'"></td>
		<td>
			<span id="name'.$key.'">'.$dns_list[$key]['name'].'</span>
			<input type="text" name="name" id="name_entry'.$key.'" value="'.$dns_list[$key]['name'].'" disabled=disabled style="display:none;">
			<input type="hidden" name="edit" value="'.$key.'" />
		</td>
		<td>
			<span id="priority'.$key.'">'.$dns_list[$key]['priority'].'</span>
			<input type="text" name="priority" id="priority_entry'.$key.'" value="'.$dns_list[$key]['priority'].'" style="display:none;">
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
</div>
<script>
	
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
		d += "&domain="+$("#selectdomain").val();
			
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

$(document).ready(function(){
	$("#checkall").change(function(){
		$(".check_mx").prop("checked", $(this).prop("checked"));
	});
	
	$("input:checkbox").change(function(){
		if($(".check_mx:checked").length){
			$("#delete_selected").removeAttr("disabled");
		}else{
			$("#delete_selected").prop("disabled",true);
		}
	});
});
function del_mx(el){
	var a;
	var jEle = $(el);
	var arr = [];
	
	$("input:checkbox[name=check_mx]:checked").each(function(){
		var mx = $(this).val();
		arr.push(mx);
		
	});
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete this ?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"delete" : arr.join() ,"domain" : $("#addmxdomain").val()};
		submitit(d,{
			sm_done_onclose: function(){			
				$("#selectdomain").trigger("change");				
			}
		});
	});
	show_message(a);
}
</script>';

}

