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

function services_theme(){

global $theme, $globals, $user, $error, $updated, $info, $services, $done, $softpanel, $proxy;

	// Retrieve apache status/info using curl
	if(optREQ('chk_status')){
		$buttonId = optREQ('buttonId');
		
		// When nginx proxy is on we have to passs porxy port to find server status/info
		$localhost = !empty($proxy) ? '127.0.0.1:'.$globals['WU_PROXY_PORT'] : '127.0.0.1';
		if($buttonId == 'status'){
			echo curl_call('http://'.$localhost.'/Webuzo-server-status', 0, 5);
		}else{
			echo curl_call('http://'.$localhost.'/webuzo-server-info', 0, 5);
		}
		die();
	}

	softheader(__('Services'));
	
	echo '
<script src="https://iamdanfox.github.io/anno.js/dist/anno.js" type="text/javascript"></script>
<link href="https://iamdanfox.github.io/anno.js/dist/anno.css" rel="stylesheet" type="text/css" />

<style type="text/css">
.close {
	float:right;
	position:relative;
	z-index:99999;
	margin:3px 6px 0;
}
</style>

<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'services.png"  alt="" />&nbsp;'.__('Services').'
		<span class="search_btn float-end mt-2">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div class="mt-2 px-2" style="background-color:#e9ecef;">
		<div class="collapse" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >	
			<div class="row d-flex">
				<div class="col-12 col-md-6 mb-4 mt-2">
					<label class="sai_head">'.__('Search By Service Name').'</label>
					<input type="text" class="form-control search_val" name="search" id="service_search" value="'.aPOSTval('search').'">
				</div>
				<div class="col-12 col-md-6 mb-4 mt-2">
					<label class="sai_head">'.__('Search By Service Status').'</label>
					<select id="service_status" class="form-select">
					  <option value="all">'.__('All').'</option>
					  <option value="running">'.__('Running').'</option>
					  <option value="stop">'.__('Stop').'</option>
					</select>
				</div>
			</div>
		</form>
		</div>
	</div>
</div>
<div class="soft-smbox p-3 mt-4">';

	if($services['valid'] == true){
			
		echo '
	<div class="table-responsive">
	<table border="0" cellpadding="8" cellspacing="1" class="table webuzo-table td_font">
		<thead>
		<tr>
			<th width="50%">'.__('Service Name').'</th>
			<th width="10%" class="text-center">'.__('Enable / Disable').'</th>
			<th width="10%" class="text-center">'.__('Status').'</th>
			<th width="10%" class="text-center">'.__('Restart').'</th>
			<th width="10%" class="text-center">'.__('Start / Stop').'</th>
		</tr>
		</thead>
		<tbody>';
				
		foreach($services['services'] as $key => $service){
				
			echo '
		<tr>
			<td class="sai_head service_name">'.$service['name'].' ('.$key.')</td>
			<td>
				<label class="switch" style="margin-left:20px;">
					<input type="checkbox" class="checkbox" data-donereload="1" data-status="'.$service['enable_status'].'" data-action="'.($service['enable_status'] == 'enabled' ? 'disable' : 'enable').'" data-service="'.$service['name'].'" data-service_name="'.$key.'" '.($service['enable_status'] == 'enabled' ? 'checked' : '').'  onclick="return enable_disable_toggle(this)">
					<span class="slider" '.($service['enable_status'] == 'enabled' ? 'title="'.__('Service Enabled').'"' : 'title="'.__('Service Disabled').'"').'></span>
				</label>
			</td>
			<td align="center">
				<i class="run_status fas fa-'.($service['status'] == 'running' ? 'running active-icon running' : 'power-off inactive-icon stop').'" id="status'.$key.'" title="'.($service['status'] == 'running' ?__('Running') : __('Stopped')).'"></i>
			</td>
			<td align="center" style="position:relative">
				<i class="fas fa-sync-alt restart restart-icon" id="'.$key.'" title="'.__('Restart').'" data-service_name="'.$key.'" data-sname="'.$service['name'].'" data-action="restart"></i>
			</td>
			<td align="center">
				<i class="fas fa-power-off '.($service['status'] == 'running' ? 'inactive-icon' : ' active-icon').' startstop '.$key.'_startstop" id="'.$key.'_'.$service['status'].'" data-status="'.$service['status'].'" data-service_name="'.$key.'" '.($service['status'] == 'running' ? 'title="'.__('Stop').'"' : 'title="'.__('Start').'"').'></i>
			</td>
		</tr>';
		
		}
			
		echo '
		</tbody>
	</table>
	</div>';
			
		// If Apache service is installed (not default) and Proxy not enabled 
		// $services['services'] lists only the default one though
		if(($services['services']['httpd']['status'] == 'running')){
				
			echo '
			<div class="text-center my-5">
				<button id="status" class="btn btn-primary" onclick="server_status(this.id)">'.__('Click here for detailed Apache Server Status').'</button>
				<button id="info" class="btn btn-info" onclick="server_status(this.id)">'.__('Click here for detailed Apache Server Info').'</button>
			</div>';
			
			$show = 1;
				
		}
	}
		
	if(!empty($show)){
		echo '
		<div id="show_status" style="display:none; overflow:auto;" class="sai_divroundshad container_class mb-3 p-3">
			<i class="fas fa-times delete-icon close" alt="close" height="20" width="20" title="Close"></i>
		</div>
		<div id="show_info" style="display:none; overflow:auto;" class="sai_divroundshad container_class mb-3 p-3">
			<i class="fas fa-times delete-icon close" alt="close" height="20" width="20" title="Close"></i>
		</div>';
	}
	
	echo '
</div>

<script>


function apache_status_info(){
	var hashval = window.location.hash.substr(1);
	server_status(hashval);
	
	if(hashval == "status" || hashval == "info"){
		window.location.hash = "";
		$("html,body").animate({scrollTop: $("#"+hashval).offset().top}, 0);
		$(".container_class").hide(); // Close the other container
		$("#show_"+hashval).show("slow");
	}
}

$(document).ready(function(){

	apache_status_info();

	$(window).on("hashchange", apache_status_info);
	
	// Server status display
	$("#status, #info").click(function() {
		$(".container_class").hide(); // Close the other container
		$("#show_"+this.id).show();
	});
	
	// Close the <DIV>
	$(".close").click(function () {
		$(this).parent().hide();
	});
	
	var f = function(){		
		var type = window.location.hash.substr(1);
		if(!empty(type) && type !="status" && type != "info"){
			var intro = new Anno({ 
				target:$("#"+type).parent("td"),
				content: "'.__js('Click here to restart ').'"+"<b>"+$("#"+type).data("sname")+"</b>",
				onShow: function () {
					$(".anno-btn").hide();
				}
			});
			intro.show();
			window.location.hash = "";
		}
	}
	f();
	$(window).on("hashchange", f);	
});

$(".restart").click(function(){
	var d, jEle = $(this);
	d = jEle.data();
	// console.log(d);return false;
	submitit(d, {
		after_handle:function(data, p){
			// console.log(data, p);return false;
			if(typeof(data["done"]) != "undefined"){
				var sjEle = $("#status"+d.service_name);
				var tjEle = jEle.closest("tr").find(".startstop").first();

				if(sjEle.hasClass("inactive-icon")){
					sjEle.removeClass("inactive-icon fas fa-power-off inactive-icon").addClass("fas fa-running active-icon");
				}
				
				if(tjEle.hasClass("active-icon")){
					tjEle.removeClass("active-icon").addClass("inactive-icon");
				}
				
			}
			
		}
	});
});

$(".startstop").click(function(){
	
	var jEle = $(this);
	var d = jEle.data();
	d.status = jEle.attr("data-status");
	if(d.status == "stop"){
		d.action = "start";
	}else if(d.status == "running"){
		d.action = "stop";
	}
	
	submitit(d, {
		after_handle:function(data, p){
			// console.log(data, p);return false;
			if(typeof(data["done"]) != "undefined"){
				var sjEle = $("#status"+d.service_name);
				if(d.action === "start"){
					sjEle.removeClass("inactive-icon fas fa-power-off inactive-icon").addClass("fas fa-running active-icon");
					
					jEle.removeClass("fas fa-power-off inactive-icon active-icon").addClass("fas fa-power-off inactive-icon");
					
					jEle.attr("data-status", "running");
					jEle.attr("title", "'.__js('Stop').'");
				}else if(d.action === "stop"){
					sjEle.removeClass("fas fa-power-off inactive-icon fas fa-running").addClass("fas fa-power-off inactive-icon");
					
					jEle.removeClass("fas fa-power-off inactive-icon active-icon").addClass("fas fa-power-off active-icon");
					
					jEle.attr("data-status", "stop");
					jEle.attr("title", "'.__js('Start').'");
				}
			}
			
		}
	});
	
});

function enable_disable_toggle(ele){
	var jEle = $(ele);
	var d = jEle.data();
	var a, lan;
	if(d.status == "disabled"){
		lan = "'.__js('Do you want to enable').'"+" "+d.service;
	}else{
		lan = "'.__js('Do you want to disable').'"+" "+d.service;
	}
	
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
	
	var no = function(){
		var status = d.status == "disabled" ? false : true;
		jEle.prop("checked", status);
	}
	
	// Submit the data
	a.confirm.push(function(){
		submitit(d, {done_reload : "'.$globals['index'].'act=services", error: no});
	});
	
	// If user closes or chooses no
	a.no.push(no);
	a.onclose.push(no);
	
	//console.log(a);//return;
	show_message(a);
}

$(".enable_disable").click(function(){
	
	var jEle = $(this);
	var d = jEle.data();
	d.status = jEle.attr("data-status");
	
	if(d.status == "enabled"){
		d.action = "disable";
	}else if(d.status == "disabled"){
		d.action = "enable";
	}
	
	
	submitit(d, {
		after_handle:function(data, p){
			
			//console.log(data, p);return false;
			if(typeof(data["done"]) != "undefined"){
				var sjEle = $("#status"+d.service_name);
				if(d.action === "enable"){
					sjEle.removeClass("inactive-icon fas fa-power-off inactive-icon").addClass("fas fa-running active-icon");
					
					jEle.removeClass("fas fa-power-off inactive-icon active-icon").addClass("fas fa-power-off inactive-icon");
					
					jEle.attr("data-status", "enabled");
					jEle.attr("title", "'.__js('Disable').'");
				}else if(d.action === "disable"){
					sjEle.removeClass("fas fa-power-off inactive-icon fas fa-running").addClass("fas fa-power-off inactive-icon");
					
					jEle.removeClass("fas fa-power-off inactive-icon active-icon").addClass("fas fa-power-off active-icon");
					
					jEle.attr("data-status", "disabled");
					jEle.attr("title", "'.__js('Enable').'");
				}
			}
			
		}
	});
	
});

var name_s = function(){
	var search_name = $("#service_search").val();
	search_name = search_name.toLowerCase();
	$(".service_name").each(function(key, tr){
		var service = $(this).text();
		service = service.toLowerCase();	
		if($(this).parent().is(":visible")){
			if(service.match(search_name)){
				$(this).parent().show();
			}else{
				$(this).parent().hide();
			}
		}
	})
}

var status_s = function(){
	var status = $("#service_status").val();	
	$(".run_status").each(function(key, tr){
		$(this).parent().parent().show();
		if(status == "running" && $(this).hasClass("stop")){
			$(this).parent().parent().hide();
		}else if(status == "stop" && $(this).hasClass("running")){
			$(this).parent().parent().hide();
		}
	})
}

$("#service_search").keyup(function(){
	status_s();
	name_s();	
});

$("#service_status").change(function(){	
	status_s();
	name_s();
});

function server_status(Id){

	if(Id !== "status" && Id !== "info"){
		return;
	}
	
	$(".loading").show();
	
	$.ajax({
	  type: "GET",
	  url: "'.$globals['admin_url'].'act=services&chk_status=1&buttonId=" + Id,
	  
	  success: function(data) {
		$(".loading").hide();
		$("#show_"+Id).append(data);
	  },
	  error: function(xhr, status, error) {
		$(".loading").hide();
	  }
	});

}

</script>';
	
	softfooter();
	
}

