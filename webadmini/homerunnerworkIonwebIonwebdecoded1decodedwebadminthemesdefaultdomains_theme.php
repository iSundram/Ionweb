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

function domains_theme(){

global $user, $globals, $theme, $error, $saved, $domains;
	
	softheader(__('Domain list'));
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
	<i class="fa-solid fa-list fa-xl me-2"></i>'.__('Domain List').'
		<span class="search_btn float-end">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div class="mt-2" style="background-color:#e9ecef;">
		<div class="collapse '.(!empty(optREQ('user_search')) || !empty(optREQ('dom_search')) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
			<div class="row p-3 col-md-12 d-flex">
				<div class="col-12 col-md-6">
					<label class="sai_head">'.__('Search By Domain Name').'</label>
					<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act='.$GLOBALS['act'].'&api=json" s2-query="dom_search" s2-data-key="domains" s2-data-subkey="domain" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" name="dom_search" id="dom_search">
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
<div class="soft-smbox p-4 mt-4">';
	error_handle($error, "100%");
	
	echo '	
	<div class="row">
		<div class="col-12 col-sm-4">
			<span class="form-label">'.__('Total Domains').': </span>
			<span class="tot_count">'.$globals['total_domains'].'</span>
		</div>
		<div class="col-12 col-sm-8">
			<nav aria-label="Page navigation example">
				<ul class="pagination pager myPager justify-content-end">
				</ul>
			</nav>
		</div>
	</div>';
	page_links();
	
	echo '
	<div class="row mt-2">
		<div class="col-6">
			<input type="button" class="btn btn-danger m-1" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_domain(this)" style="float: left;" disabled>
		</div>
		<div class="col-6 sai_sub_head record-table position-relative" style="text-align:right" >
			<a class="btn btn-primary m-1 '.(empty($domains) ? 'dis_link' : '' ).'" target="_blank" href="'.$globals['request_url'].'&export=csv">'.__('Export CSV').'</a>
		</div>
	</div>
	<div class="table-responsive mt-2">
		<table border="0" cellpadding="8" cellspacing="1" class="table table-hover-moz webuzo-table td_font">
			<thead class="sai_head2">
				<tr>
					<th class="align-middle"><input type="checkbox" id="checkAll"></th>
					<th width="5%">#</th>
					<th width="20%">'.__('Domain Name').'<i id="dom_order" class="fa fa-sort m-1" style="cursor:pointer;"></i></th>
					<th width="5%">'.__('Login').'</th>
					<th width="10%">'.__('User Name').'</th>
					<th width="10%">'.__('Owner').'</th>
					<th width="10%">'.__('IP Address').'</th>
					<th width="35%">'.__('Path').'</th>
					<th colspan="2">'.__('Type').'</th>
				</tr>
			</thead>
			<tbody id="dom_list">';
			
			if(!empty($domains)){
				foreach ($domains as $key => $value){
					echo'
				<tr>
					<td>
						<input type="checkbox" class="check" name="checked_domain" value="'.$value['domain'].'" '.(($value['type'] == 'primary') ? 'disabled' : '').'>
					</td>
					<td><span>'.$value['domid'].'</span></td>
					<td><span>'.$value['domain'].'</span></td>
					<td><span><a href="'.$globals['enduser_url'].'&loginAs='.$value['user'].'&act=domainmanage" style="text-decoration:none;" target="blank"><img src="'.$theme['images'].'webuzo_icon_64.svg" width="32" height="32" title="'.__('Enduser Panel').'" /></a></span></td>
					<td><span>'.$value['user'].'</span></td>
					<td><span>'.$value['owner'].'</span></td>
					<td>'.$value['ip'].(!empty($value['ipv6']) ? '<br> '.$value['ipv6'] : '').'</td>
					<td class="text-start"><span>'.$value['path'].''.(!empty($value['alias']) ? '<div>Alias : '.$value['alias'].'</div>' : '').'</span></td>
					<td><span>'.ucfirst($value['type']).'</span></td>
					<td class="text-end">
						'.(($value['type'] != 'primary') ? '
						<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$value['domain'].'" data-donereload="1" data-delete="'.$value['domain'].'" onclick="delete_record(this)"></i>' : '').'
					</td>
				</tr>';	
				}
			}else{
				echo '
				<tr><td colspan=8><h3 style="text-align: center">No Record Found</h3></td></tr>';
				
			}
			
			echo '
			</tbody>
		</table>
	</div>';
	
	page_links();
	
	echo '
	<nav aria-label="Page navigation">
		<ul class="pagination pager myPager justify-content-end">
		</ul>
	</nav>
</div>

<script>
$(document).ready(function () {
	
	$("#checkAll").change(function () {
		$(".check:enabled").prop("checked", $(this).prop("checked"));		
	});
	
	$("input:checkbox").change(function() {
		if($(".check:checked").length){
			$("#delete_selected").removeAttr("disabled");
		}else{
			$("#delete_selected").prop("disabled", true);
		}
	});
	
	$("#user_search").on("select2:select", function(){	
		user = $("#user_search option:selected").val();
		if(user == "all"){
			window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'";
		}else{
			window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'&user_search="+user;
		}
	});
	
	$("#dom_search").on("select2:select", function(){
		var domain_selected = $("#dom_search option:selected").val();
		if(domain_selected == "all"){
			window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'";
		}else{
			window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'&dom_search="+domain_selected;
		}
	});
});

function del_domain(el){
	
	var a;
	var jEle = $(el);
	var confirmbox = "'.__js('Are you sure you want to delete the selected domain(s) ?').'";
	
	var domains = [];
		
	$("input:checkbox[name=checked_domain]:checked").each(function(){
		var domain = $(this).val();
		domains.push(domain);
	});
	
	jEle.data("delete", domains.join());
	
	var lang = confirmbox;
	a = show_message_r("'.__js('Warning').'", lang);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		
		var d = jEle.data();
		
		// Submit the data
		submitit(d, {
			handle:function(data, p){
				if(data.error){
					var err = Object.values(data.error);
					var e = show_message_r("'.__js('Error').'", err);
					e.alert = "alert-danger"
					show_message(e);
					return false;
				}
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

$("#dom_order").click(function(){

	order_by = "ASC&by=domain";	

	if($("#dom_order").hasClass("fa-sort-up")){
		order_by = "DESC&by=domain";	
	}	
	
	url = window.location.toString();
	url = url.replace(/[\?&]order=[^&]+/, "").replace(/[\?&]by=[^&]+/, "");
	
	window.location = url+"&order="+order_by;
});

var order = "'.optGET('order').'";

if(order == "ASC"){
	$("#dom_order").removeClass("fa-sort");
	$("#dom_order").addClass("fa-sort-up");
}

if(order == "DESC"){
	$("#dom_order").removeClass("fa-sort");
	$("#dom_order").addClass("fa-sort-down");
}

</script>';

	softfooter();
	
}