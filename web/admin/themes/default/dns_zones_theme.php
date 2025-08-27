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

function dns_zones_theme(){

global  $globals, $theme, $error, $dns_zones, $domains;
	
	softheader(__('DNS Zone'));

	error_handle($error, "100%");

	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-project-diagram me-1"></i>
		<h5 class="d-inline-block">'.__('DNS Zone').'</h5>
		<span class="search_btn float-end mx-2">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
		<button type="button" class="btn btn-primary float-end" id="add_new_button" onclick="add_dns_modal()">'.__('Add DNS Zone').'</button>
	</div>
	<div style="background-color:#e9ecef;">
		<div class="collapse '.(!empty(optREQ('user_search')) || !empty(optREQ('dom_search')) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
			<div class="mt-1 row p-3 col-md-12 d-flex">
				<div class="col-12 col-md-6">
					<label class="sai_head">'.__('Search By Domain Name').'</label>
					<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act=dns_zones&api=json" s2-query="dom_search" s2-data-key="dns_zones" style="width: 100%" name="dom_search" id="dom_search">
						<option value="'.optREQ('dom_search').'" selected="selected">'.optREQ('dom_search').'</option>
					</select>
				</div>
				<div class="col-12 col-md-6">
					<label class="sai_head">'.__('Search By User Name').'</label><br/>
					<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="user_search" s2-data-key="users" s2-result-add="'.htmlentities(json_encode([['text' => 'root', 'id' => 'root', 'value' => 'root']])).'"  style="width: 100%" id="user_search" name="user_search">
						<option value="'.optREQ('user_search').'" selected="selected">'.optREQ('user_search').'</option>
					</select>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>

<div class="soft-smbox p-4 mt-4">
	<div class="modal fade" id="add_dns" tabindex="-1" aria-labelledby="add-aliasesLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title action-type" id="add-aliasesLabel">'.__('Add DNS Zone').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="'.__('Close').'"></button>
				</div>
				<div class="modal-body p-2">
					<form accept-charset="'.$globals['charset'].'" name="add_zone_form" method="post" class="form-horizontal"  onsubmit="return submitit(this)" data-donereload="1">
						<div class="row p-3 col-md-12 d-flex">
							<div class="col-12 col-md-12 m-2">
								<label for="domain" class="form-label">'.__('Domain').'</label>
								<input type="text" name="domain" style="width:100%" >
							</div>
							<div class="col-12 col-md-12 m-2">
								<label for="ipv4" class="form-label">'.__('IPv4 Address').'</label>
								<input type="text" name="ipv4" style="width:100%" >
							</div>
							<div class="col-12 col-md-12 m-2">
								<label class="form-label">'.__('User').'</label>
								<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-dropdownparent="#add_dns" style="width: 100%" id="dns_zone_user" name="user">
									<option value="'.optREQ('user_search').'" selected="selected">'.optREQ('user_search').'</option>
								</select>
							</div>
							<center>
								<button type="submit" id="save_dns_zone" name="add" value="1" class="save btn btn-primary" title="'.__('Save').'">'.__('Add DNS Zone').'</button>
							</center>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="showrectab" class="">
		<div class="row">
			<div class="col-12 col-sm-4 py-2">
				<span class="form-label">'.__('Total Zones').': </span>
				<span class="tot_count">'.$globals['total_dns_zones'].'</span>
			</div>
			<div class="col-12 col-sm-4">
				<nav aria-label="Page navigation example">
					<ul class="pagination pager myPager justify-content-end">
					</ul>
				</nav>
			</div>
			<div class="col-12 col-sm-4">
				<div class="sai_sub_head record-table mb-2 position-relative" style="text-align:right;">
					<input type="button" class="btn btn-danger" value="'.__('Delete Selected').'" name="del_selected" id="del_selected" onclick="multi_delete(this)" disabled >
				</div>
			</div>
		</div>';
		page_links();
		echo '
		<div class="table-responsive">
			<table class="table webuzo-table" >			
				<thead>
					<tr>
						<th width="1%"><input type="checkbox" id="checkAll"></th>
						<th width="10%">'.__('Name').'</th>
						<th width="10%">'.__('User').'</th>
						<th width="40%">'.__('Actions').'</th>
						<th width="1%">'.__('Option').'</th>
					</tr>
				</thead>
				<tbody>';
					if(empty($dns_zones)){
						echo '
						<tr class="text-center">
							<td colspan=4>
								<span>'.__('No DNS record(s) found').'</span>
							</td>
						</<tr>';
					}else{
						foreach ($dns_zones as $key => $val){	
							echo '
							<tr id="tr'.$key.'">
								<td>
									<input type="checkbox" class="check" name="checked_dns" id="check'.$key.'" value="'.$key.'" >
								</td>
								<td>
									<span><a href="'.$globals['index'].'act=advancedns&domain='.$key.'">'.$key.'</a></span>
								</td>
								<td>
									<span >'.$val.'</span>
								</td>
								<td>
									<a href="'.$globals['index'].'act=advancedns&domain='.$key.'#add_record&type=A"><button type="button" class="btn btn-secondary"><i class="fa fa-plus"></i> '.__('A Record').'</button></a>
									<a href="'.$globals['index'].'act=advancedns&domain='.$key.'#add_record&type=CNAME"><button type="button" class="btn btn-secondary"><i class="fa fa-plus"></i> '.__('CNAME Record').'</button></a>
									<a href="'.$globals['index'].'act=mxentry&domain='.$key.'#add-MX"><button type="button" class="btn btn-secondary"><i class="fa fa-plus"></i> '.__('MX Record').'</button></a>
									<a href="'.$globals['index'].'act=advancedns&domain='.$key.'"><button type="button" class="btn btn-secondary"><i class="fa fa-wrench"></i> '.__('Manage').'</button></a>
								</td>
								<td class="text-center">
									<i class="fas fa-trash delete delete-icon delzone" id="did'.$key.'" title="'.__('Delete').'" data-donereload="1" data-delete="1" data-domain="'.$key.'" onclick="delete_record(this)"></i>
								</td>		
							</tr>';
						}
					}		
				echo '
				<tbody>
			</table>
		</div>
	</div>
</div>

<script>
$(document).ready(function () {
	$(window).on("hashchange", add_dns_hash);
	add_dns_hash();
	
	$("#user_search").on("select2:select", function(){	
		user = $("#user_search option:selected").val();	
		window.location = "'.$globals['index'].'act=dns_zones&user_search="+user;
	});
	
	$("#dom_search").on("select2:select", function(){
		var domain_selected = $("#dom_search option:selected").val();		
		window.location = "'.$globals['index'].'act=dns_zones&dom_search="+domain_selected;		
	});
});

function add_dns_hash(){
	var hashval = window.location.hash.substr(1);
	if(hashval == "add_dns"){
		window.location.hash = "";
		add_dns_modal();
	}
}

function add_dns_modal(){
	var bm = bootstrap.Modal.getOrCreateInstance($("#add_dns"));	
	bm.show();
}

$("#checkAll").change(function () {
	$(".check").prop("checked", $(this).prop("checked"));
});

$("input:checkbox").change(function() {
	if($(".check:checked").length){
		$("#del_selected").removeAttr("disabled");
	}else{
		$("#del_selected").prop("disabled", true);
	}
});

// Multi Delete
function multi_delete(el){
	var a;
	var jEle = $(el);
	
	var arr = [];	
	
	$("input:checkbox[name=checked_dns]:checked").each(function(){
		arr.push($(this).val());
	});
		
	var lang = "'.__js('Are you sure you want to delete the selected DNS zone(s) ?').'";
	a = show_message_r("'.__js('Warning').'", lang);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		
		var d = {"delete" : 1, "domain" : arr.join(",")};
		
		// Submit the data
		submitit(d, {
			done_reload: window.location
		});
	});
	
	show_message(a);

}

</script>';

	softfooter();
	
}
