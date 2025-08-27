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

function servers_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $servers, $SESS, $server_groups, $title;

	softheader(__('Servers'));
	
	echo js_url(['jquery.flot.min.js', 'jquery.flot.pie.min.js', 'jquery.flot.resize.min.js', 'jquery.flot.time.min.js', 'jquery.flot.tooltip.min.js', 'jquery.flot.stack.min.js', 'jquery.flot.symbol.min.js', 'jquery.flot.axislabels.js'], 'flot.combined.js');
	
	echo '


<div class="soft-smbox p-3 pb-2">
	<div class="sai_main_head">
		<i class="fas fa-servers me-1"></i>'.__('Servers').'
		<span class="search_btn float-end">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div class="mt-2" style="background-color:#e9ecef;">
		<div class="collapse '.(!empty(optREQ('search')) || !empty(optREQ('domain')) || !empty(optREQ('owner')) || !empty(optREQ('email')) || !empty(optREQ('ip')) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
			<div class="row p-3 col-md-12 d-flex">
				<div class="col-12 col-md-4">
					<label class="sai_head">'.__('Search By User Name').'</label><br/>
					<select class="form-select make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=servers&api=json'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'" s2-query="search" s2-data-key="servers" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="user_search" name="user_search">
						<option value="'.optREQ('search').'" selected="selected">'.optREQ('search').'</option>
					</select>
				</div>
				<div class="col-12 col-md-4">
					<label class="sai_head">'.__('Search By Domain Name').'</label>
					<select class="form-select make-select2" s2-placeholder="'.__('Select Domain').'" s2-ajaxurl="'.$globals['index'].'act=domains&api=json" s2-query="dom_search" s2-data-key="domains" s2-data-subkey="domain" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" name="dom_search" id="dom_search">
						<option value="'.optREQ('domain').'" selected="selected">'.optREQ('domain').'</option>
					</select>
				</div>
				<div class="col-12 col-md-4">
					<label class="sai_head text-center">'.__('Search By Owner Name').'</label><br/>
					<select class="form-select make-select2" s2-placeholder="'.__('Select Owner').'" s2-ajaxurl="'.$globals['index'].'act=servers&type=2&api=json'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'" s2-query="search" s2-data-key="servers" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'root', 'id' => 'root', 'value' => 'root'],['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="owner_search" name="owner_search">
						<option value="'.optREQ('owner').'" selected="selected">'.optREQ('owner').'</option>
					</select>
				</div>
			</div>
			<div class="row p-3 col-md-12 d-flex">
				<div class="col-12 col-md-6">
					<label class="sai_head me-1">'.__('Search By Email Account').'</label>	
					<select class="form-select make-select2" s2-placeholder="'.__('Select Email').'" s2-ajaxurl="'.$globals['index'].'act=servers&api=json'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'" s2-query="email" s2-data-key="servers" s2-data-subkey="email" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="email_search" name="email">
						<option value="'.optREQ('email').'" selected="selected">'.optREQ('email').'</option>
					</select>
				</div>
				<div class="col-12 col-md-6">
					<label class="sai_head text-center">'.__('Search by IP').'</label><br/>
					<select class="form-select make-select2" s2-placeholder="Select ip" s2-ajaxurl="'.$globals['index'].'act=ips&api=json" s2-query="ip" s2-data-key="ips" s2-data-subkey="ip" s2-result-add="'.htmlentities(json_encode([['text' => 'Default', 'id' => 'default', 'value' => 'default'],['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="ip_search" name="ip_search">
						<option value="'.optREQ('ip').'" selected="selected">'.optREQ('ip').'</option>
					</select>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="soft-smbox p-3 mt-4">
	<form accept-charset="'.$globals['charset'].'" name="serversform" id="serversform" method="post" action=""; class="form-horizontal">';		
	page_links();
	
	echo '
		<div class="row mt-1">
			<div class="col-6">
				<input type="button" class="btn btn-danger" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_user(this)" disabled>
			</div>
			<div class="col-6 sai_sub_head record-table position-relative" style="text-align:right;">
				<a type="button" class="btn btn-primary text-decoration-none" href="'.$globals['index'].'act=add_server">'.__('Add Server').'</a>
			</div>
		</div>
		<div id="showserverslist" class="table-responsive mt-3">
			<table class="table sai_form webuzo-table">
			<thead>
				<tr>
					<th width="1%"><input type="checkbox" id="checkAll"></th>
					<th width="15%">'.__('Hostname').'</th>
					<th width="5%">'.__('OS').'</th>
					<th width="15%">'.__('IP').'</th>
					<th width="4%">'.__('Group').'</th>
					<th width="10%">'.__('Ram').'</th>
					<th width="7%">'.__('Space').'</th>
					<th width="8%">'.__('Cores').'</th>
					<th width="6%">'.__('Users').'</th>
					<th width="4%">'.__('Expiry').'</th>
					<th width="4%">'.__('Version').'</th>
					<th colspan="2" width="1%">'.__('Manage').'</th>
				</tr>
			</thead>
			<tbody id="serverslist">';
				
	if(empty($servers)){
		echo '
				<tr>
					<td colspan="100" class="text-center">
						<span>'.__('No servers found. Please add a server.').'</span>
					</td>
				</tr>';
	}else{
		foreach($servers as $key => $value){
			
			$space = json_decode($value['space'], true);
			
			// Disk data
			$temp[$value['user']]['disk'] = [
				[
					'label' => __('Used Disk'), 
					'data' => (empty($servers[$value['user']]['resource']['disk']['used_bytes']) ? 0 : (($servers[$value['user']]['resource']['disk']['limit_bytes']) != 'unlimited' ?  $servers[$value['user']]['resource']['disk']['used_bytes'] : 0)),
					'color' => (($servers[$value['user']]['resource']['disk']['used_bytes']) > ($servers[$value['user']]['resource']['disk']['limit_bytes'])? '#dc3545' : '#111738')
				],
				[
					'label' => __('Free Disk'), 
					'data' => (($servers[$value['user']]['resource']['disk']['limit_bytes']) != 'unlimited' ?  ((($servers[$value['user']]['resource']['disk']['used_bytes']) > ($servers[$value['user']]['resource']['disk']['limit_bytes']))? 0 : $servers[$value['user']]['resource']['disk']['limit_bytes']) : 100),
					'color' => '#e91e63'
				]
			];
			
			// Bandwidth data 
			$temp[$value['user']]['bandwidth'] = [
				[
					'label' => __('Used Bandwidth'),
					'data' => (empty($servers[$value['user']]['resource']['bandwidth']['used_bytes'])? 0: (($servers[$value['user']]['resource']['bandwidth']['limit_bytes']) != 'unlimited' ? $servers[$value['user']]['resource']['bandwidth']['used_bytes'] : 0)),
					'color' => (($servers[$value['user']]['resource']['bandwidth']['used_bytes']) > ($servers[$value['user']]['resource']['bandwidth']['limit_bytes']) ? '#dc3545' : '#111738') 
				],
				[
					'label' => __('Free Bandwidth'),
					'data' => (($servers[$value['user']]['resource']['bandwidth']['limit_bytes']) != 'unlimited' ? ((($servers[$value['user']]['resource']['bandwidth']['used_bytes']) > ($servers[$value['user']]['resource']['bandwidth']['limit_bytes']))? 0 : ($servers[$value['user']]['resource']['bandwidth']['limit_bytes'])) : 100),
					'color' => '#9c27b0'
				]
			];
			
			echo '
				<tr server="'.$value['uuid'].'" '.($value['status'] == 'locked' ? 'style="background-color: #ffdbdb;"' : '').'>
					<td>
						<input type="checkbox" class="check" name="checked" value="'.$value['uuid'].'">
					</td>
					<td>
						<a href="'.$globals['index'].'act=manageserver&'.$value['uuid'].'" class="text-decoration-none;" target="blank">'.$value['server_name'].'</a><br>
						'.__('UUID').' : '.$value['uuid'].'
					</td>
					<td>'.$value['os'].'</td>
					<td>'.$value['ip'].'</td>
					<td>'.$server_groups[$value['sgid']]['name'].'</td>
					<td>'.$value['ram'].'</td>
					<td>'.$value['space'].'</td>
					<td>'.$value['cores'].'</td>
					<td>'.$value['num_users'].'</td>
					<td>'.$value['lic_expires'].'</td>
					<td>'.$value['version'].'</td>
					<td class="dropdown dropstart" style="position:relative">
						<a href="dropdown-toggle" href="#" class="suspension" role="button" id="id'.$key.'" data-bs-toggle="dropdown">
							<i class="fas fa-cog text-primary"></i>
						</a>
						<ul class="dropdown-menu" aria-labelledby="id'.$key.'">
							<li class="dropdown-item cursor-pointer" title="'.__('Edit Reseller Privileges').'">
								<a href="'.$globals['admin_url'].'act=reseller_privileges&user_name='.$value['user'].'" style="color:#454545">
									<i class="fas fa-sitemap" mb-2" style="color:blue;"></i>&nbsp;'.__('Edit Privileges').'
								</a>
							</li>
							<li class="dropdown-item cursor-pointer" title="'.__('Force Password').'" data-force_pass="1" data-do="'.(empty($value['force_password']) ? 1 : 0).'" data-user='.$value['user'].' onclick="return force_pass_toggle(this)">
								<input type="checkbox" '.(empty($value['force_password']) ? "" : "checked").' disabled="disabled"> &nbsp;'.__('Force Password').'
							</li>
						</ul>
					</td>
					<td>
						<a href="'.$globals['admin_url'].'act=edit_server&uuid='.$value['uuid'].'">
							<i class="fas fa-pencil-alt edit-icon" title="'.__('Edit').'"></i>
						</a>
					</td>';
					
					
			echo '
				</tr>';
		}
		
	}
	
	echo '
				</tbody>
			</table>
		</div>';
		page_links();
		echo'
	</form>
</div>

<script>
$(document).ready(function (){
	
	$("#checkAll").change(function () {
		$(".check").prop("checked", $(this).prop("checked"));
	});
	
	$("input:checkbox").change(function() {
		if($(".check:checked").length){
			$("#delete_selected").removeAttr("disabled");
		}else{
			$("#delete_selected").prop("disabled", true);
		}
	});
	
	var graph_data = \''.json_encode($temp).'\';
	var graph_data = JSON.parse(graph_data);
	var pie_option = {
		series: {
			pie: { 
			show: true,
				label: {
					show: true,
					radius: 0.5,
					formatter: function(label, series){
						if(label != "used bandwidth" || label != "used disk") return "";
						return \'<div style="font-size:13px;">\'+series.data+\'</div><div style="font-size:10px; color:#333;">\'+label+\'</div>\';	
					}
				}
			}
		},
		tooltip: {
			show: true,
			content: "%p.0% %s",
			shifts: {
				x: 20,
				y: 0
			}
		},
		legend: {
			show: false
		},
		grid: {
			hoverable: true
		},
	};
	$.each(graph_data, function(key, value){
		var disk = value["disk"];
		var bandwidth = value["bandwidth"];		
		$.plot($("#disk_"+key), disk, pie_option);
		$.plot($("#bandwidth_"+key), bandwidth, pie_option); 		
	});
	
	var f = function(){		
			
		var type = window.location.hash.substr(1);
		if(!empty(type)){
			var anno1 = new Anno({
				target : "."+type,
				position : "left",
				content: "'.__js('Click Here to Manage Suspension of Users').'",
				onShow: function () {
					$(".anno-btn").hide();
				}
			})
				
			anno1.show();
			$("."+type).click(function(){
				anno1.hide();
			})
				
			window.location.hash = "";
		}
	}
	f();
	$(window).on("hashchange", f);
	
});

function del_user(el){
	var a;
	var jEle = $(el);
	var confirmbox = "'.__js('Please type $0 I Confirm $1 in the input box and then click on Yes to delete the selected user(s)', ['<b>', '</b>']).'"+"<br><input type=\"text\" name=\"confirmbox\" id=\"confirmbox\" class=\"form-control mt-2\"><br>";
	
	var arr = [];
	
	var reseller_exist = "";
	var server_groups = ["'.implode('", "', $server_groups).'"];
		
	$("input:checkbox[name=checked_user]:checked").each(function(){
		var user = $(this).val();
		arr.push($(this).val());
		
		// Check if selected servers has server_groups
		if(in_array(user, server_groups)){
			reseller_exist = 1;
		}
	});
	
	jEle.data("delete_user", arr.join());
	
	if(reseller_exist){
		var del_sub_acc = "<br /><br /><input type=\"checkbox\" name=\"del_sub_servers\" id=\"del_sub_servers\"/>&nbsp;&nbsp;"+"'.__js('Do you want to delete the reseller\'s sub accounts as well ?').'";
	}else{
		var del_sub_acc = "";
	}
	
	var lang = confirmbox + "'.__js('Are you sure you want to delete the selected user(s) ?').'" + del_sub_acc;
	a = show_message_r("'.__js('Warning').'", lang);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		
		var cboxValue = document.getElementById("confirmbox").value;
		var cbox = cboxValue.trim();
		var cbox = cbox.toLowerCase();
		if(cbox != "i confirm"){
			alert("'.__js('Confirmation message is invalid!').'");
			return false;
		}
		
		var sub_servers_checked = $("#del_sub_servers").is(":checked");
		
		if(sub_servers_checked){
			jEle.data("del_sub_acc", 1);
		}
		
		var d = jEle.data();
		
		// Submit the data
		submitit(d, {
			handle:function(data, p){
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

function force_pass_toggle(ele){
	var jEle = $(ele);
	var d = jEle.data();
	var a, lan;
	
	if(empty(d.do)){
		lan = "'.__js('This will cancel the password change requirement for the user. Are you sure ?').'";
	}else{
		lan = "'.__js('Are you sure you want to force the password change for this user ?').'";
	}
		
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
		
	// Submit the data
	a.confirm.push(function(){
		submitit(d, {
			success : function(){
				jEle.find("input").prop("checked", d.do);
				jEle.data("do", d.do ? 0 : 1);
			}
		});
	});
		
	//console.log(a);//return;
	show_message(a);
}

function suspend_user(el){
	var jEle = $(el);
	var a;
	var suspend_check = "<br /><input type=\"checkbox\" name=\"suspend_main\" id=\"suspend_main\" checked />&nbsp;&nbsp;<b>Suspend reseller main account</b>";
	// console.log(d);return false;
	if(jEle.data().resl == 1){
		var lang = "'.__js('Are you sure you want to suspend this user ?').'" + suspend_check;
	}else{
		var lang = "'.__js('Are you sure you want to suspend this user ?').'";
	}
	a = show_message_r("'.__js('Warning').'", lang);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		
		jEle.data("skip", 0);
		
		if(jEle.data().resl == 1){
			if($("#suspend_main").prop("checked") != true){
				jEle.data("skip", 1);
			}
		}

		var d = jEle.data();
		// console.log(d);exit;
		// Submit the data
		submitit(d, {
			done_reload : window.location
		});		
	});
	
	show_message(a);
}

function unsuspend_user(el){
	
	var a;
	var jEle = $(el);
	var unsuspend_check = "<br /><input type=\"checkbox\" name=\"unsuspend_main\" id=\"unsuspend_main\" checked/>&nbsp;&nbsp;<b>Unsuspend reseller main account</b>";
	// console.log(d);return false;
	if(jEle.data().resl == 1){
		var lang = "'.__js('Are you sure you want to unsuspend this user ?').'" + unsuspend_check;
	}else{
		var lang = "'.__js('Are you sure you want to unsuspend this user ?').'";
	}
	a = show_message_r("'.__js('Warning').'", lang);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
	
		jEle.data("skip", 0);
		
		if(jEle.data().resl == 1){
			if($("#unsuspend_main").prop("checked") != true){
				jEle.data("skip", 1);
			}
		}
		
		var d = jEle.data();
		// Submit the data
		submitit(d, {
			done_reload : window.location
		});		
	});
	
	show_message(a);
	
}

function delete_reseller(el){
	
	var a;
	var jEle = $(el);
	// console.log(d);return false;
	
	var del_res_main_check = "<br /><input type=\"checkbox\" name=\"del_r_main\" id=\"del_r_main\" checked />&nbsp;&nbsp;<b>Delete reseller main account</b>";
	var del_res_user_check = "<br /><input type=\"checkbox\" name=\"del_r_servers\" id=\"del_r_servers\" checked />&nbsp;&nbsp;<b>Delete all servers account of reseller</b>";
	
	var lang = "'.__js('Are you sure you want to delete this user ?').'" + del_res_main_check + del_res_user_check;
	a = show_message_r("'.__js('Warning').'", lang);
	a.alert = "alert-warning";
	a.confirm.push(function(){
		jEle.data("skip_reseller", 0);
		jEle.data("del_sub_acc", 0);
		
		if($("#del_r_main").prop("checked") != true){
			jEle.data("skip_reseller", 1);
		}
		
		if($("#del_r_servers").prop("checked") == true){
			jEle.data("del_sub_acc", 1);
		}
		
		var d = jEle.data();
		// Submit the data
		submitit(d, {
			done_reload : window.location
		});
	});
	
	show_message(a);
	
}

$("#user_search").on("select2:select", function(e, u = {}){		
	user = $("#user_search option:selected").val();
	if(user == "all"){
		window.location = "'.$globals['index'].'act=servers'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}else{
		window.location = "'.$globals['index'].'act=servers&search="+user+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}
});
	
$("#dom_search").on("select2:select", function(){
	var domain_selected = $("#dom_search option:selected").val();
	if(domain_selected == "all"){
		window.location = "'.$globals['index'].'act=servers'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}else{
		window.location = "'.$globals['index'].'act=servers&domain="+domain_selected+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}		
});

$("#owner_search").on("select2:select", function(e, u = {}){		
	owner = $("#owner_search option:selected").val();
	if(owner == "all"){
		window.location = "'.$globals['index'].'act=servers'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}else{	
		window.location = "'.$globals['index'].'act=servers&owner="+owner+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}
});

$("#email_search").on("select2:select", function(e, u = {}){		
	email = $("#email_search option:selected").val();
	if(email == "all"){
		window.location = "'.$globals['index'].'act=servers'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}else{	
		window.location = "'.$globals['index'].'act=servers&email="+email+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}
});

$("#ip_search").on("select2:select", function(e, u = {}){		
	ip = $("#ip_search option:selected").val();
	if(ip == "all"){
		window.location = "'.$globals['index'].'act=servers'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}else if(ip == "default"){
		window.location = "'.$globals['index'].'act=servers&ip='.$globals['WU_PRIMARY_IP'].''.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}else{
		window.location = "'.$globals['index'].'act=servers&ip="+ip+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}
});

</script>';

	softfooter();
	
}

