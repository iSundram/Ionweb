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

function quota_modification_theme(){

global $theme, $globals, $user, $error, $done, $_user, $ips, $users;

	softheader(__('Quota Modification'));

	error_handle($error);

	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-th-list me-2"></i>'.__('Quota Modification').'
		<span class="search_btn float-end">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div class="mt-2" style="background-color:#e9ecef;">
		<div class="collapse '.(!empty(optREQ('user_search')) || !empty(optREQ('dom_search')) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal">
				<div class="row p-3 col-md-12 d-flex">
					<div class="col-12 col-md-6">
						<label class="sai_head">'.__('Search By Domain Name').'</label>
						<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="domain" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" name="dom_search" id="dom_search">
							<option value="'.optREQ('dom_search').'" selected="selected">'.optREQ('dom_search').'</option>
						</select>
					</div>
					<div class="col-12 col-md-6">
						<label class="sai_head">'.__('Search By User Name').'</label><br/>
						<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="user_search" name="user_search">
							<option value="'.optREQ('user_search').'" selected="selected">'.optREQ('user_search').'</option>
						</select>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="soft-smbox p-3 mt-4">
	<div class="alert alert-warning">
		<ul style="margin-bottom:0px">
			<li><b>'.__('Quotas of only the selected users will be modified.').'</b></li>
			<li><b>'.__('A user will be automatically selected if you change its quota.').'</b></li>
		</ul>
	</div>';
	
	page_links();

	echo '
	<div class="sai_sub_head record-table mb-2 position-relative row">
		<div class="col-md-3">
			<select class="form-select" id="mainselect" disabled>
				<option selected disabled>'.__('Set Quota').'</option>
				<option value="unlimited">'.__('Unlimited').'</option>
				<option value="plan_default">'.__('Use Plan Default').'</option>
				<option value="custom">'.__('Custom').'</option>
			</select>
		</div>
		<div class="col-md-3">
			<input type="number" class="form-control" value="" id="mainbox" style="display:none">
		</div>
	</div>
	<div>
		<form accept-charset="'.$globals['charset'].'" name="quotamodification" method="post" action="" role="form" class="form-horizontal">
			<div class="table-responsive">
			<table border="0" cellpadding="8" cellspacing="1" class="table table-hover-moz webuzo-table td_font">
				<thead class="sai_head2">
					<tr>
						<th><input type="checkbox" id="checkAll"></th>
						<th>'.__('User').'</th>
						<th>'.__('Domain').'</th>
						<th>'.__('Plan').'</th>
						<th>'.__('Disk Space Used').'</th>
						<th colspan=2>'.__('Quota').'</th>
					</tr>
				</thead>
				<tbody id="dom_list">';

				if(!empty($users)){
					foreach ($users as $key => $value){
						echo'
					<tr id="tr'.$value['id'].'">
						<td>
							<input type="checkbox" class="check" id="chk'.$value['id'].'" plan="'.(!empty($value['plan']) ? 1 : 0).'">
						<td>
							<span>'.$key.'</span>
							<input type="hidden" class="user" value="'.$key.'" id="usr'.$value['id'].'">
						</td>
						<td>
							<span>'.$value['domain'].'</span>
						</td>
						<td>
							<span>'.$value['plan'].'</span>
						</td>
						<td>
							<span>'.$value['resource']['disk']['used'].'</span>
						</td>
						<td width="20%" style="min-width:110px;">
							<select class="form-select quotaselect" id="sel'.$value['id'].'">
								<option value="unlimited">'.__('Unlimited').'</option>
								<option value="custom" '.(($value['info']['p']['max_disk_limit'] != 'unlimited') ? 'selected' : '').'>'.__('Custom').' (in MB)</option>';
									
								if(!empty($value['plan'])){
									echo '
									<option value="plan_default" '.((!empty($value['plan']) && !array_key_exists('max_disk_limit', $value['info']['p'])) ? 'selected' : '').'>'.__('Use Plan Default').'</option>';
								}
									
								echo '
							</select>
							<div class="mt-2"><input type="checkbox" id="dis_hard_'.$value['id'].'" value="1" '.(!empty($value['info']['P']['disable_hard_limit']) ? 'checked' : '').' ><span> Disable hard limit for quota and inodes</span></div>
							<input type="hidden" class="user" value="'.$value['info']['P']['max_inode'].'" id="ind'.$value['id'].'">
						</td>
						<td width="20%" style="min-width:110px;">
							<input type="number" class="form-control quotabox" value="'.(($value['info']['P']['max_disk_limit'] != 'unlimited') ? $value['info']['P']['max_disk_limit'] : '10240').'" id="box'.$value['id'].'" '.(($value['info']['P']['max_disk_limit'] == 'unlimited' || (!empty($value['plan']) && !array_key_exists('max_disk_limit', $value['info']['p']))) ? 'style="display:none"' : '').' title="'.__('Set limit in MB').'">
						</td>
					</tr>';			
					}
				}else{
					echo '<tr><td colspan=9><h3 style="text-align: center">'.__('No Record found').'</h3></td></tr>';
				}
					
				echo '
				</tbody>
			</table>
			</div>
			<div class="text-center mt-2">
				<input type="button" class="btn btn-primary" value="'.__('Modify').'" data-modify="1" onclick="modify_limit(this)">
			</div>
		</form>
	</div>';
	
	page_links();

	echo '
<script>

$(".quotabox, .quotaselect").change(function(){
	var id = $(this).attr("id");
	id = id.substr(3);
	$("#chk"+id).prop("checked", true);
})

function modify_limit(el){
	var jEle = $(el);
	
	var arr = {};
	$(".check:checked").each(function(key,tr){
		var id = $(this).attr("id");
		id = id.substr(3);
		arr[$("#usr"+id).val()] = {quota: $("#sel"+id).val(), limit: ($("#sel"+id).val() == "unlimited" ? "unlimited" : $("#box"+id).val()), inode: $("#ind"+id).val(), disable_hard_limit: $("#dis_hard_"+id).is(":checked") ? 1 : 0};
	});
	
	jEle.data("main_array", arr);
	var d = jEle.data();

	submitit(d,{
		done_reload: window.location
	});
}

$("#user_search").on("select2:select", function(){	
	user = $("#user_search option:selected").val();
	if(user == "all"){
		window.location = "'.$globals['index'].'act=quota_modification";
	}else{
		window.location = "'.$globals['index'].'act=quota_modification&user_search="+user;
	}
});

$("#dom_search").on("select2:select", function(){
	var domain = $("#dom_search option:selected").val();
	if(domain == "all"){
		window.location = "'.$globals['index'].'act=quota_modification";
	}else{
		window.location = "'.$globals['index'].'act=quota_modification&dom_search="+domain;		
	}	
});

$("#checkAll").change(function(){
	$(".check").prop("checked", $(this).prop("checked"));
});
	
$("input:checkbox").change(function(){
	if($(".check:checked").length){
		$("#mainselect").removeAttr("disabled");
	}else{
		$("#mainselect").prop("disabled", true);
		$("#mainbox").hide();
	}
});

$("#mainselect").change(function(){
	var val = $(this).val();
	if(val == "custom"){
		$("#mainbox").show();
	}else{
		$("#mainbox").hide();
	}
	
	$(".check").each(function(key, tr){
		var id = $(this).attr("id");
		id = id.substr(3);
		
		if(document.getElementById("chk"+id).checked){
			if(val == "plan_default"){
				var plan = $(this).attr("plan");
				if(!empty(plan)){
					$("#sel"+id).val(val);
				}
			}else{
				$("#sel"+id).val(val);
			}
			
			
			if($("#sel"+id).val() == "custom"){
				$("#box"+id).show();
			}else{
				$("#box"+id).hide();
			}
		}
	});
});

$(".quotaselect").change(function(){
	var id = $(this).attr("id");
	id = id.substr(3);
	if($(this).val() == "custom"){
		$("#box"+id).show();
	}else{
		$("#box"+id).hide();
	}
});

$("#mainbox").on("keyup", function(){
	var val = $(this).val();
	
	$(".check").each(function(key, tr){
		var id = $(this).attr("id");
		id = id.substr(3);
		
		if(document.getElementById("chk"+id).checked){
			$("#box"+id).val(val);
		}
	});
});
</script>';

	softfooter();

}