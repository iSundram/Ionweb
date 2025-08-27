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

function view_bandwidth_theme(){

global $globals, $theme, $softpanel, $error, $done, $users, $month, $user_search, $dom_search;	

	softheader(__('View Bandwidth Usage'));

	echo '
<style>
	.hideselect{
		margin-bottom: 9px;
	}
</style>

<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'bandwidth.png" class="webu_head_img me-2"/>'.__('View Bandwidth Usage').'
		<span class="search_btn float-end">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div style="background-color:#e9ecef;">
		<div class="collapse mt-2 '.(!empty($user_search) || !empty($dom_search) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal">
				<div class="row p-3 col-md-12 d-flex">
					<div class="col-12 col-md-6">
						<label class="sai_head">'.__('Search By Domain Name').'</label>
						<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="domain" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" name="dom_search" id="dom_search">
							<option value="'.$dom_search.'" selected="selected">'.$dom_search.'</option>
						</select>
					</div>
					<div class="col-12 col-md-6">
						<label class="sai_head">'.__('Search By User Name').'</label><br/>
						<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="user_search" name="user_search">
							<option value="'.$user_search.'" selected="selected">'.$user_search.'</option>
						</select>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="soft-smbox p-3 mt-4">';
	
	error_handle($error, "100%");
	
	page_links();
	
	echo '
	<div class="row row-cols-md-3 mb-2">
		<div class="col">
			<button id="prevMonth" onclick="getPrevMonth()" class="btn btn-primary">'.__('Prev Month').'</button>
		</div>
		<div class="col" style="text-align:center;">
			<h4>'.$month['mth_txt'].' '.$month['yr'].'</h4>
		</div>
		<div class="col" style="text-align:right;">
			<button type="button" class="btn btn-primary" onclick="getNextMonth()" '.($month["next"] > date("Ym")?"disabled":"").'>'.__('Next Month').'</button>
		</div>
	</div>
	
	<div class="table-responsive">
		<table class="table sai_form webuzo-table">
			<thead>
				<tr>
					<th>'.__('Users').'</th>
					<th>'.__('Domain').'</th>
					<th>'.__('IP Address').'</th>
					<th>'.__('Type').'</th>
					<th>'.__('Used').'</th>
					<th>'.__('Current Bandwidth Limit').'</th>
					<th>'.__('Usage').'</th>
					<th colspan="2">'.__('Options').'</th>
				</tr>
			</thead>
			<tbody id="userlist">';

		if(!empty($users)){			
			foreach($users as $key => $value){
				
				$color = '';
				if($value['monthly_percent'] >= 70){
					$color = 'style="background-color:#FFFFE0"';
				}
				if($value['monthly_percent'] >= 85){
					$color = 'style="background-color:#ffdbdb"';
				}
				
				echo '
				<tr id="tr'.$value['id'].'" '.$color.'>
					<td>
						<span>'.$value['user'].'</span>
						<input type="hidden" name="user" value="'.$value['user'].'" />
					</td>
					<td>
						<span>'.$value['domain'].'</span>
					</td>
					<td>
						<span>'.$value['ip'].'</span>
					</td>
					<td>
						<span>'.($value['type'] == 1 ? __('User') : __('Reseller')).'</span>
					</td>
					<td>
						<span>'.bytes_to_human($value['bandwidth_data']['monthly_total']).'</span>
						<i class="fas fa-undo reset_usage text-danger mx-2" data-user="'.$value['user'].'" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="'.__('Click here to reset Bandwidth Usage for month of $0 $1 for $2 user', [$month['mth_txt'], $month['yr'], $value['user']]).'" onclick="reset_usage(this)"></i>
					</td>
					<td>
						<span class="limit" >'.$value['resource']['bandwidth']['limit'].'</span>
						<select class="form-select hideselect" style="display: none;" name="limit_option" id="limit_option'.$value['id'].'">
							<option value="unlimited">'.__('Unlimited').'</option>
							<option value="custom" '.(($value['resource']['bandwidth']['limit'] != 'unlimited') ? 'selected' : '').'>'.__('Custom (in MB)').'</option>
						</select>
						<input class="form-control hideinput" type="number" id="limit_input'.$value['id'].'" name="limit_input" value="'.(($value['resource']['bandwidth']['limit'] != 'unlimited') ? $value['resource']['bandwidth']['limit_bytes']/1024/1024 : 1048576).'" style="display:none;" title="Set limit in MB">
					</td>
					<td>
						<span>'.$value['monthly_percent'].' %</span>
					</td>
					<td width="2%">
						<i class="fas fa-undo-alt cancel cancel-icon" title="'.__('Cancel').'" id="cid'.$value['id'].'" style="display:none;"></i>
					</td>
					<td width="2%">
						<i name="editbtn" class="fas fa-pencil-alt edit edit-icon fa-edit" id="eid'.$value['id'].'" title="'.__('Edit').'"></i>
					</td>
				</tr>';
			}
		}else{
			echo '<tr><td colspan=9><h4 style="text-align: center">'.__('No Record found').'</h4></td></tr>';
		}

		echo '
			</tbody>
		</table>
	</div>';
	
	page_links();
			
	echo '
</div>

<script>
$("#user_search").on("select2:select", function(){	
	user = $("#user_search option:selected").val();
	if(user == "all"){
		window.location = "'.$globals['index'].'act=view_bandwidth";		
	}else{
		window.location = "'.$globals['index'].'act=view_bandwidth&show='.$month['current'].'&user_search="+user;		
	}
});

$("#dom_search").on("select2:select", function(){
	var domain = $("#dom_search option:selected").val();
	if(domain == "all"){
		window.location = "'.$globals['index'].'act=view_bandwidth";
	}else{
		window.location = "'.$globals['index'].'act=view_bandwidth&show='.$month['current'].'&dom_search="+domain;
	}
});

function getPrevMonth(){
	window.location.href = "'.$globals['ind'].'act=view_bandwidth&dom_search='.$dom_search.'&user_search='.$user_search.'&show='.$month['prev'].'";
}

function getNextMonth(){
	window.location.href = "'.$globals['ind'].'act=view_bandwidth&dom_search='.$dom_search.'&user_search='.$user_search.'&show='.$month['next'].'";
}

// For cancel
$(document).on("click", ".cancel", function(){
	var id = $(this).attr("id");
	id = id.substr(3);
	$("#cid"+id).hide();
	$("#eid"+id).removeClass("fa-save").addClass("fa-edit");
	$("#tr"+id).find("span").show();
	$("#tr"+id).find(".hideselect").hide();
	$("#tr"+id).find(".hideinput").hide();
});

function reset_usage(ele){
	var jEle = $(ele);
	var user = jEle.data("user");
	
	// console.log(user);
	
	var d = {"reset_usage" : 1, "r_user" : user};
	
	var a, lan;
	lan = "'.__js('Are you sure you want to $0 reset Bandwidth Usage $1 of ', ['<b>', '</b>']).'<b>"+d.r_user+"</b>";
	
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
	
	// Submit the data
	a.confirm.push(function(){
		submitit(d, {done_reload : window.location.href});
	});
	
	show_message(a);	
	
}

// For editing record
$(document).on("click", ".edit", function(){
	var id = $(this).attr("id");
	id = id.substr(3);
	$("#cid"+id).show();

	if($("#limit_option"+id+":visible").length == 0){
		if($("#limit_option"+id).val() == "custom"){
		$("#limit_input"+id).css("display", "block");
		}else{
			$("#limit_option"+id).css("display", "block");
			$("#limit_input"+id).css("display", "none");
		}
	}
	
	// Show/Hide input field
	$("#limit_option"+id).change(function(){
		if($("#limit_option"+id).val() == "custom"){
			$("#limit_input"+id).css("display", "block");
		}else{
			$("#limit_option"+id).css("display", "block");
			$("#limit_input"+id).css("display", "none");
		}
	});
	
	// Submit the form
	if($("#eid"+id).hasClass("fa-save")){
		var d = $("#tr"+id).find("input, select").serialize();
		submitit(d, {
			done: function(){
				var tr = $("#tr"+id);
				tr.find(".cancel").click();// Revert showing the inputs
			},
			sm_done_onclose: function(){
				$("#tr"+id).find("span").show();
				$("#tr"+id).find(".hideselect").hide();
				location.reload();					
			}
		});
	}else{
		$("#eid"+id).addClass("fa-save").removeClass("fa-edit");
		$("#tr"+id).find(".limit").hide();
		$("#tr"+id).find(".hideselect").show();
	}
});

</script>';
	
	softfooter();
	
}