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

function ips_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $ips, $SESS, $title;
	
	softheader($title);

	echo '
<script src="https://iamdanfox.github.io/anno.js/dist/anno.js" type="text/javascript"></script>
<link href="https://iamdanfox.github.io/anno.js/dist/anno.css" rel="stylesheet" type="text/css" />
	
<form accept-charset="'.$globals['charset'].'" name="usersform" id="usersform" method="post" action=""; class="form-horizontal">
<div class="soft-smbox p-3 col-12 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-list-alt me-2"></i> '.$title.'
		<div class="float-end">
			<div style="display:inline;margin-right:10px">
				<input type="submit" id="add_allips" name="add_allips" value="'.__('Add all IPs to System').'" class="btn btn-primary" onclick="return add_all_ip(this)" />
			</div>
			<span class="ml-5 search_btn float-end">
				<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
			</span>
		</div>
	</div>

	<div class="mt-2" style="background-color:#e9ecef;">
		<div class="collapse '.(!empty(optREQ('ip')) || !empty(optREQ('user')) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
			<div class="row p-3 col-md-12 d-flex justify-content-center">
				<div class="col-12 col-md-6">
					<label class="sai_head text-center">'.__('Search by IPs').'</label><br/>
					<select class="form-select make-select2" s2-placeholder="Select ip" s2-ajaxurl="'.$globals['index'].'act=ips&api=json'.(!empty(optREQ('used')) ? '&used=1' : '').''.(!empty(optREQ('reserved')) ? '&reserved=1' : '').'" s2-query="ip" s2-data-key="ips" s2-data-subkey="ip" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="ip_search" name="ip_search">
						<option value="'.optREQ('ip').'" selected="selected">'.optREQ('ip').'</option>
					</select>
				</div>
				<div class="col-12 col-md-6">
					<label class="sai_head">'.__('Search By User Name').'</label><br/>
					<select class="form-select make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="user_search" name="user_search">
						<option value="'.optREQ('user').'" selected="selected">'.optREQ('user').'</option>
					</select>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="soft-smbox p-3 col-12 mt-4">';	
	error_handle($error);
		
	page_links();
	
	echo '
	<div class="col-6">
		<input type="button" class="btn btn-danger" value="Delete Selected" name="delete_selected" id="delete_selected" data-do="delete" onclick="del_ips(this)" disabled>
	</div>
	<div id="div_iplist" class="table-responsive mt-3">
		<table class="table sai_form webuzo-table">
		<thead>
			<tr>
				<th class="align-middle"><input type="checkbox" id="checkAll"></th>
				<th width="10%">'.__('ID').'</th>
				<th width="15%">'.__('IP Address').'</th>
				<th width="10%">'.__('Type').'</th>
				<th width="10%">'.__('IP Type').'</th>
				<th>'.__('Users').'</th>
				<th width="10%">'.__('Status').'</th>
				<th>'.__('Created').'</th>
				<th colspan="4" width="5%">'.__('Options').'</th>
			</tr>
		</thead>
		<tbody id="iplist">';

	if(empty($ips)){
		echo '
			<tr class="text-center">
				<td colspan=100>
					'.__('No IPs were submitted').'
				</td>
			</tr>';
	}else{
		
		foreach($ips as $key => $v){
			echo '
			<tr id="tr'.$v['uuid'].'" ip="'.$v['ip'].'" '.(!empty($v['lock']) ? 'style="background-color: #F7F7F7;"' : '').'>
				<td>
					<input type="checkbox" class="check" name="checked_ips" data-ips="'.$v['ip'].'" '.((in_array($v['ip'], array($globals['WU_PRIMARY_IP'], $globals['WU_PRIMARY_IPV6']))) ? 'disabled' : '').'>
				</td>
				<td>
					<span>'.$v['uuid'].'</span>
				</td>
				<td>
					<span>'.$v['ip'].'</span>'.(!empty($v['lock']) ? ' 
					<span>
						<i class="fas fa-lock locked-icon"></i>
					</span> ' : '').(!empty($v['note']) ? ' 
					<span>
						<i class="fas fa-sticky-note text-warning" title="'.$v['note'].'"></i>
					</span>' : '').'
				</td>
				<td>
					'.__('IPv$0', [$v['type']]).'
				</td>
				<td>
					'.((in_array($v['ip'], array($globals['WU_PRIMARY_IP'], $globals['WU_PRIMARY_IPV6']))) ? __('Shared (Main)') : (!empty($v['shared']) ? __('Shared') : __('Dedicated'))).'
				</td>
				<td>
					<span>'.str_replace(',', ', ', $v['users']).'</span>
				</td>
				<td>
					<label class="switch">
						<input type="checkbox" class="checkbox"  data-donereload="1" data-active_ip="1" data-ip_add="'.$v['ip'].'" data-status="'.(empty($v['inactive_ip']) ? '0' : '1').'" '.(empty($v['inactive_ip']) ? 'checked' : '').' onclick="return active_ip(this)">
						<span class="slider" '.(empty($v['inactive_ip']) ? 'title="Active"' : 'title="Inactive"').'></span>
					</label>
				</td> 
				<td>
					<span>'.datify($v['created']).'</span>
				</td>
				<td>
					'.(!empty($v['lock']) ? '
					<i class="fas fa-unlock locked-icon" title="'.__('Unlock IP').'" onclick="unlock_ip(this)" data-do="unlock" data-ips="'.$v['ip'].'"></i>' : '
					<i class="fas fa-lock locked-icon" title="'.__('Lock IP').'" onclick="lock_ip(this)" data-do="lock" data-ips="'.$v['ip'].'"></i>').'
				</td>	
				<td>
					<a href="'.$globals['admin_url'].'act=editip&ip='.$v['ip'].'" title="'.__('Edit').'">
						<i class="fas fa-pencil-alt edit-icon"></i>
					</a>
				</td>
				<td>
					<i class="fas fa-trash delete-icon" onclick="delete_record(this)" id="did'.$v['uuid'].'" data-do="delete" data-ips="'.$v['ip'].'" title="'.__('Delete').'"></i>
				</td>
			</tr>';
		}
	}
	
	echo '
		</tbody>
		</table>
	</div>';
	page_links();
	echo '
</div>
</form>
<script>

$(document).ready(function(){
	
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
	
	var f = function(){		
		var type = window.location.hash.substr(1);		
		if(!empty(type)){
			var intro = new Anno([
			{ 
				target:"#"+type,
				content: "'.__js('Click here to rebuild IP\'s').'",
				onShow: function () {
					$(".anno-btn").hide();
				}
			},
            ]);	
			intro.show();
			window.location.hash = "";			
		}		
	}
	f();
	$(window).on("hashchange", f);	
});

function del_ips(el){
	var a;
	var jEle = $(el);
	var confirmbox = "'.__js('Are you sure you want to delete the selected IP(s) ?').'";
	
	var ips = [];
		
	$("input:checkbox[name=checked_ips]:checked").each(function(){
		var ip = $(this).data("ips");
		ips.push(ip);
	});
	console.log(ips);
	// return;
	jEle.data("ips", ips.join());
	
	var lang = confirmbox;
	a = show_message_r("'.__js('Warning').'", lang);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		
		var d = jEle.data();
		
		// Submit the data
		submitit(d, {done_reload : window.location.href});
	});
	
	
	show_message(a);
}

function lock_ip(el){
	
	var jEle = $(el);
	var a,lang = "'.__js('Are you sure you want to lock selected IP(s) ?').'";
	a = show_message_r(l.warning, lang);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = jEle.data();
		// Submit the data
		submitit(d,{
			done_reload: window.location
		});
		
	});
	
	show_message(a);	
}

function unlock_ip(el){
	
	var jEle = $(el);
	var a,lang = "'.__('Are you sure you want to unlock selected IP(s) ?').'";
	a = show_message_r(l.warning, lang);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = jEle.data();
		// Submit the data
		submitit(d, {
			done_reload: window.location
		});
		
	});
	
	show_message(a);
}

function active_ip(ele){
	var jEle = $(ele);
	var d = jEle.data();
	var a, lan;
	if(!empty(d.status)){
		lan = "'.__('Do you want to active the IP : $0 $ip $1?', ['ip' => '"+d.ip_add+"', '<b>', '</b>']).'";
	}else{
		lan = "'.__('Do you want to inactive the IP : $0 $ip $1?', ['ip' => '"+d.ip_add+"', '<b>', '</b>']).'";
	}
	
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
	
	var no = function(){
		var status = d.status ? false : true;
		jEle.prop("checked", status);
	}
	
	// Submit the data
	a.confirm.push(function(){
		submitit(d, {done_reload : window.location.href, error: no});
	});
	
	// If user closes or chooses no
	a.no.push(no);
	a.onclose.push(no);
	
	//console.log(a);//return;
	show_message(a);
}

function add_all_ip(el){
	var jEle = $(el);
	var a,lang = "'.__js('Are you sure you want to Add All IP(s) to the system ?').'";
	a = show_message_r(l.warning, lang);
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"add_allips" :  1};
		// Submit the data
		submitit(d,{done_reload: window.location});
	});
	
	show_message(a);
	
	return false;	
}

$("#ip_search").on("select2:select", function(e, u = {}){		
	ip = $("#ip_search option:selected").val();
	if(ip == "all"){
		window.location = "'.$globals['index'].'act=ips'.(!empty(optREQ('used')) ? '&used=1' : '').''.(!empty(optREQ('reserved')) ? '&reserved=1' : '').'";
	}else{
		window.location = "'.$globals['index'].'act=ips&ip="+ip+"'.(!empty(optREQ('used')) ? '&used=1' : '').''.(!empty(optREQ('reserved')) ? '&reserved=1' : '').'";
	}
});

$("#user_search").on("select2:select", function(e, u = {}){		
	user = $("#user_search option:selected").val();
	if(user == "all"){
		window.location = "'.$globals['index'].'act=ips'.(!empty(optREQ('used')) ? '&used=1' : '').''.(!empty(optREQ('reserved')) ? '&reserved=1' : '').'";
	}else{
		window.location = "'.$globals['index'].'act=ips&user="+user+"'.(!empty(optREQ('used')) ? '&used=1' : '').''.(!empty(optREQ('reserved')) ? '&reserved=1' : '').'";
	}	
});

</script>';

	softfooter();
	
}

