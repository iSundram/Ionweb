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

function userindex_theme(){

global $theme, $globals, $softpanel, $WE, $user, $updates_available, $info, $usage, $band, $icons;
global $apps, $iapps, $last_login_ips, $list_users, $SESS, $stats;

	$__tmp = explode(':', (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost'));
	$globals['HTTP_HOST'] = $__tmp[0];
	
	$panel_order_array = json_encode($WE->user_prefs['panel_order']);
	
	// $iapps is loaded in $softpanel->__custruct(). Might as well use that to save a call !
	if(empty($iapps)){
		$ins_apps = loadinsapps();
	}else{
		$ins_apps = $iapps;
	}
	
	$bandwidth = $usage['bandwidth'];
	
	$def_mysql = $globals['WU_DEFAULT_MYSQL'];
	
	$mysql = (!empty($def_mysql) ? get_app_record($def_mysql) : $default_mysql);
	
	$def_web_server = $globals['WU_DEFAULT_SERVER'];
	$def_php = $globals['WU_DEFAULT_PHP'];
	
	$primary_domain = $WE->getPrimaryDomain();
	$domains_list = $WE->domains();
	
	$udef_php = (!empty($domains_list[$primary_domain]['php_version']) ? $domains_list[$primary_domain]['php_version'] : '');
	if(!empty($udef_php) && get_app_record($udef_php)){
		$def_php = $udef_php;
	}
	
	$web_server = (!empty($def_web_server) ? get_app_record($def_web_server) : 3);
	$def_php = (!empty($def_php) ? get_app_record($def_php) : 124);
	$disable_sysapps = $globals['DISABLE_SYSAPPS'];
	$domain_count = $WE->domain_count();
	$db_count = $WE->get_db_count();
	
	/// FOR APPS UPDATE LIST
	$installed_apps = loadinsapps();
	$apps_updates_available = 0; 

	if(empty($disable_sysapps)){
		foreach($installed_apps as $k => $v){
			if(!empty($v['aid'])){
				if(is_app_upgradable($v['aid'], $v['mod']) && (($apps[$v['aid']]['ins'] == 1 && empty($globals['lictype'])) || !empty($globals['lictype']))){
					$apps_updates_available++;
				}
			}
		}
	}

	softheader(__('Control Panel'), 'js');

	apply_filters('userindex_theme_start');
	
	$colorcode = ['#ff0000', '#ffff00', '#aaff00', '#ff00bf', '#5500ff', '#669999', '#cc0000', '#ff00ff'];

	$jsfile = empty($globals['dev']) ? 'js/flot.js?' : 'js/givejs.php?files=&target=flot.js';
	
	echo js_url(['jquery.flot.min.js', 'jquery.flot.pie.min.js', 'jquery.flot.resize.min.js', 'jquery.flot.time.min.js', 'jquery.flot.tooltip.min.js', 'jquery.flot.stack.min.js', 'jquery.flot.symbol.min.js', 'jquery.flot.axislabels.js'], 'flot.combined.js').'
<script type="text/javascript" src="'.$theme['url'].'/js/jquery-ui.min.js"></script>

<script language="javascript" type="text/javascript"><!-- // --><![CDATA[

function panel_collapse(Id){
	var panel_collapsed = "panel_"+Id;
	$.ajax({
		type: "POST",
		url: "'.$globals['current_url'].'api=json",
		data: {"panel_collapsed": panel_collapsed},
		success: function(){
			//console.log("success");
		},
		error: function(){
			//console.log("error");
		}
	});
	$("#"+Id).trigger("t:accordian");
};

// Search the icons
$("#inputs_searchs").bind("keyup",function(){
		
	var selfVal = $(this).val().toLowerCase();
	
	// Hide icons not matching
	$(".webuzo_icons").each(function(i, icon){
		
		var val = $(icon).attr("value");
		//console.log(val);
		val = val.toLowerCase();
		
		if (selfVal.length > 0 && val.indexOf(selfVal) < 0){
			$(icon).parent().hide();
		}else{
			$(icon).parent().show();
		}
		
	});
	
	$(".panel-row").show();
	
	// When we are not searching show all rows
	if(selfVal.length < 1){
		$(".panel-row .accordion-button").each(function(){
			if($(this).hasClass("collapsed")){
				$(this).closest(".panel-row").find(".accordion-collapse").removeClass("show");
			}
		});
		return;
	}
	
	// Categories not matching
	$(".accordion-collapse").each(function(i, div){
		$(this).addClass("show");
		var jEle = $(this).find(".accordion-body .row");
		
		if(jEle.children(":visible").length < 1){
			jEle.closest(".panel-row").hide();
		}else{
			jEle.closest(".panel-row").show();
		}
	});
});
		
function getusage(){
	let cgroup_version = "'.$globals['cgroup_version'].'";
	let cgroup_plan = "'.$user['resource_limit'].'";
	let disable_resource_stats = "'.$globals['disable_resource_stats'].'";
		
	$.getJSON("'.$globals['index'].'api=json&usage_only=1", function(data, textStatus, jqXHR) {

		server_graph_data(data["usage"]);
		
		if(data.stats && cgroup_version == "cgroup2fs" && cgroup_plan && disable_resource_stats == 0){
			usagestats(data.stats);
			
			let cpugraph = [
				{label: "Used",  data: data.stats.cpu ? data.stats.cpu : 0.1},
				{label: "Free",  data: 100 - data.stats.cpu}
			];
			
			let memorygraph = [
				{label: "Used",  data: data.stats.mem_percent ? data.stats.mem_percent : 0.1},
				{label: "Free",  data: 100 - data.stats.mem_percent}
			];
			
			let diskgraph = [
				{label: "Used",  data: data.stats.disk ? data.stats.disk : 0.1},
				{label: "Free",  data: 100 - data.stats.disk}
			];
			
			cgroup_graph("cpugraph", cpugraph);
			cgroup_graph("memorygraph", memorygraph);
			cgroup_graph("diskgraph", diskgraph);
		}
	});
	
};

function usagestats(data){
	$("#cpu").text(data.cpu);
	$("#memory").text(data.memory);
	$("#read_bw").text(data.read_bw);
	$("#write_bw").text(data.write_bw);
	$("#tasks").text(data.tasks);
}
		
function startusage(){
	ajaxtimer = setInterval("getusage()", updateInterval);
};
		
function server_graph_data(data){		
	
	var data = data || false;
	// console.log("data", data, typeof data);
	
	if(data == false){
		
		data = '.json_encode($usage).';
		// console.log(data);
		var tmphtml = "";
		$.each( data, function( key, value ){
		  tmphtml +=\'<div class="col-sm-12 col-xs-12">\';
		  
		  tmphtml += \'<div><label class="medium" style="display: inline-block">\'+value.label+\'</label> &nbsp; - &nbsp; <span id="\'+key+\'_text">\'+value.used+" / "+(value.limit != "unlimited" ? value.limit : "∞")+\'</span></div>\';

		  if(value.percent >= 80 && value.percent < 90){
			var progress_bar_class = "progress-bar bg-warning";
		  }else if(value.percent >= 90){
			var progress_bar_class = "progress-bar bg-danger";
		  }else{
			var progress_bar_class = "progress-bar prog-blue";
		  }

		  tmphtml += \'<div class="progress disk-bar"><div style="cursor:pointer;width:\'+value.percent+\'%;" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="\'+progress_bar_class+\'"  data-placement="right" data-toggle="tooltip" id="\'+key+\'_bar">\';
		  
		  tmphtml += \'</div></div></div>\';
		  
		});
		
		$("#user_resource .accordion-body").html(tmphtml);
	}else{
		$.each(data, function( key, value ){
			// console.log( key + ": " + value );
			$_(key+"_text").innerHTML = value.used+" / "+(value.limit != "unlimited" ? value.limit : "∞");
			$("#"+key+"_bar").css("width", value.percent+"%");
		});
	}
};

var color_code = '.json_encode($colorcode).';

/*---------- Start Day wise graph ------------*/
var y_m_data = '.json_encode($band['band_mon']).';

var y_m_dataset = [], i=0;

$.each(y_m_data, function(key, value){
	y_m_dataset.push([key, value]);
	i++;
});

var x = '.date('t', time()).', x_data = [];
for (let i = 1; i <= x; i++) {
	x_data.push(i);
}

var data_set_daywise = [{data:y_m_dataset, color:color_code[0]}];

var daywise_options = {
	series:{
		stack: true,
		bars: {
			show: true,
			fill: true,
			barWidth: 0.6,
			lineWidth: 0.6
		},
	},
	legend: {
		show: true,
		noColumns: 4,
		container: $("#bw_chartLegend")
	},
	xaxis:{
		mode: "categorical",	
		show:true,
		tickLength: 0,
		color:\'white\',
		axisLabelFontSizePixels: 15,
		axisLabelFontFamily: \'Verdana, Arial\',
		tick: x_data,
		showticklabels: true,
		dtick: 1,
		axisLabel: "Days",
	},
	yaxis:{
		min:0,
		color:\'white\',
		axisLabelFontSizePixels: 15,
		axisLabelFontFamily: \'Verdana, Arial\',
		tickFormatter: function (v) {
			return human_readable_bytes(v);
		}
	},
	grid: {
		borderWidth: 0,
		borderColor: \'#fff\',
		hoverable: true,
	},
	tooltip: {
		show: true,
		content: function(label, x, y, flotItem){
				return "Day: "+ (Number(x)) + ", Bandwidth: " +human_readable_bytes(y);
			}
	}
};

/*---------- Start yearly graph (Month wise)------------*/
var y_band = '.json_encode($band['band_year']).', y_axis = [];

$.each( y_band, function( key, value ) {
	y_axis.push([key, value]);
});	

var tick_labels = [
	[1, "Jan"], [2, "Feb"], [3, "Mar"], [4, "Apr"],
	[5, "May"], [6, "Jun"], [7, "Jul"], [8, "Aug"],
	[9, "Sep"], [10, "Oct"], [11, "Nov"], [12, "Dec"]
];
	
/* Monthly graph options */	
var monthly_options = {
	series:{
		stack: true,
		bars: {
			show: true,
			fill: true,
			barWidth: 0.6,
			lineWidth: 0.6
		},
	},
	legend: {
			show: true,
			noColumns: 2,
			container: $("#bw_monthly_chartLegend")
	},
	xaxis:{
		show: true,
		tickLength: 0,
		color: "white",
		axisLabel: "Months",
		axisLabelFontSizePixels: 15,
		axisLabelFontFamily: \'Verdana, Arial\',
		ticks: tick_labels,
	},
	yaxis:{
		min:0,
		color: "white",
		axisLabelUseCanvas: true,
		axisLabelFontSizePixels: 15,
		axisLabelFontFamily: \'Verdana, Arial\',
		tickFormatter: function (v) {
			return human_readable_bytes(v);
		}
	},
	grid:{
		borderWidth: 0,
		borderColor: "#fff",
		hoverable: true,
	},
	tooltip: {
		show: true,
		content: function(label, x, y, flotItem){
				return "Month: "+ tick_labels[(Number(x))-1][1] + ", Bandwidth:" +human_readable_bytes(y);
			}
	}
};

var m_dataset = [
	{data:y_axis, color: color_code[0]}
];

$(document).ready(function() {

	updateInterval = 5000;
	t_daywise_bw_chart();
	t_monthly_bw_chart();
	
	function human_readable_bytes(v){
		return (v >= 1073741824 ? Math.round(v / 1073741824)+" G" : (v > 1048576 ? Math.round(v / 1048576)+" M" : (v > 1024 ? Math.round(v / 1024)+" K" : Math.round(v)+" B")))
	}
	
	server_graph_data(); 
	startusage();
	
	$("#monthly-chart").on("t:accordian", function(e){
		let show = $(this).data("show") ? false : true;
		t_monthly_bw_chart(show);
	});
	
	$("#bandwidth").on("t:accordian", function(e){
		let show = $(this).data("show") ? false : true;
		t_daywise_bw_chart(show);
	});
	
	let cgroup_version = "'.$globals['cgroup_version'].'";
	let cgroup_plan = "'.$user['resource_limit'].'";
	let disable_resource_stats = "'.$globals['disable_resource_stats'].'";
	let stats = '.json_encode($stats).';
	
	if(cgroup_version == "cgroup2fs" && cgroup_plan && stats && disable_resource_stats == 0){
		let cpugraph = [
			{label: "Used",  data: stats.cpu},
			{label: "Free",  data: 100 - stats.cpu}
		];
	
		let memorygraph = [
			{label: "Used",  data: stats.mem_percent},
			{label: "Free",  data: 100 - stats.mem_percent}
		];
		
		let diskgraph = [
			{label: "Used",  data: stats.disk},
			{label: "Free",  data: 100 - stats.disk}
		];	
		
		cgroup_graph("cpugraph", cpugraph);
		cgroup_graph("memorygraph", memorygraph);
		cgroup_graph("diskgraph", diskgraph);
	}
});

// Draw a Server Resource Graph
function cgroup_graph(id, data){		
	$.plot($("#"+id), data, 
	{
		series: {
			pie: { 
				innerRadius: 0.8,
				radius: 1,
				show: true,
				label: {
					show: true,
					radius: 0,
					formatter: function(label, series){
						if(label != "Used") return "";
						return \'<div style="font-size:13px;"><b>\'+Math.round(series.percent)+\'%</b></div><div style="font-size:9px;">\'+label+\'</div>\';	
					}
				}
			}
		},
		legend: {
			show: false
		}
	});
}

function t_monthly_bw_chart(state = false){
	
	if($("#monthly-chart").data("show") || state){
		$.plot($("#bw_monthly_body"), m_dataset, monthly_options);
	}else{
		$("#monthly-chart").attr("data-show", false);
	}
}

function t_daywise_bw_chart(state = false){
	
	if($("#bandwidth").data("show") || state){
		$.plot($("#bwband_holder"), data_set_daywise, daywise_options);
	}else{
		$("#bandwidth").attr("data-show", false);
	}
}

ids = ["'.implode('", "', array_keys($ins_apps)).'"];
tools = ["phpmyadmin","rockmongo","rainloop", "tomcat", "monsta"];
		
function in_array(val, arr){
			
	for (var i in arr) {
		var tmp_val = arr[i].split("_");
		if(tmp_val[0] == val){
			return true;
		}
	}
	return false;
}

$( document ).ready(function() {
	var panelList = $(\'#draggablePanelList\');
	var panel_order_array = '.$panel_order_array.';
	if(panel_order_array != null){
		$.each(panel_order_array, function(key, value){
			var row_name = value.substring(6);
			$("#row_"+row_name).appendTo("#draggablePanelList");
		});
	}

	panelList.sortable({
		// Only make the .panel-heading child elements support dragging.
		handle: ".panel-heading",
		axis: "y",		
		placeholder: "highlight_placeholder",
		update: function() {
			var panel_order = [];
			$(".accordion-item", panelList).each(function(index, elem) {
				var elem_id = $(this).attr(\'id\');
				var panel_id = "panel_"+elem_id.substring(11);
				panel_order.push(panel_id);		 
			});
			
			$.ajax({
				type: "POST",
				url: "'.$globals['current_url'].'api=json",
				data: {"panel_order": panel_order},
				success: function(){
					console.log("success");
				},
				error: function(){
					console.log("error");
				}
			});
		}
	});
	
	$("#login_to").on("select2:select", function(){		
		user = $("#login_to option:selected").val();
		window.location = "'.$globals['enduser_url'].'&loginAs="+user;
	});
});

// ]]></script>';

	$news = loaddata($globals['var_path'].'/news.json');
	$nstr = '';
	
	$news_lang =array(
		'global_news' => __('Global News'),
		'owner_account_news' => __('Owner News'),
		'reseller_account_news' => __('Reseller News'),
		'reseller_news' => __('Admin News'),
	);
	
	foreach($news as $k => $v){
	
		$owner = empty($WE->user['owner']) ? 'root' : $WE->user['owner'];
		if(empty($v['message']) || $v['on'] == 'admin'){
			continue;
		}
		
		if($k == 'owner_account'){
			if($owner != $v['owner']){
				continue;
			}
		}
		
		if($k == 'reseller_account'){
			if($owner == 'root'){
				continue;
			}
		}
		
		// $v['message'] = str_ireplace("<script>", "<span>", $v['message']);
		$v['message'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $v['message']);
		
		$nstr .= '
	<div class="accordion mb-2 col-6" id="news_accordion'.$k.'">
		<div class="accordion-item">
			<h2 class="accordion-header" id="heading'.$k.'">
				<button class="accordion-button collapsed bg-'.($v['alert'] == 'none' ? 'info' : $v['alert']).'" type="button" data-bs-toggle="collapse" data-bs-target="#collapse'.$k.'" aria-expanded="false" aria-controls="collapse'.$k.'">
					'.$news_lang[$k.'_news'].'
				</button>
			</h2>
			<div id="collapse'.$k.'" class="accordion-collapse collapse" aria-labelledby="heading'.$k.'" data-bs-parent="#news_accordion'.$k.'">
			  <div class="accordion-body '.($v['alert'] == 'none' ? '' : 'alert alert-'.$v['alert']).' mb-0" style="overflow-y: scroll;height:30vh">
				'.nl2br(html_entity_decode($v['message'], ENT_HTML5)).'
			  </div>
			</div>
		</div>
	</div>';

	}
	
	if(!empty($nstr)){
		echo '
<div class="row">'.$nstr.'</div>';
	}
	
	echo '
<div class="row mb-3">
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb-3 ">
		<div class="card soft-card p-2 user_card">
			<div class="d-flex">
				<div class="flex-shrink-0 align-self-center">
					<div class="card-icon px-3">
						<i class="fas fa-user"></i>
					</div>
				</div>
				<div class="flex-grow-1 mt-2 ps-1">
					<span class="soft-card-desc my-1 d-block">'.__('Current User').'</span>
					<h4 class="soft-card-val">'.$WE->user['user'].'</h4>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-md-6 col-sm-6 col-xs-12 mb-3">
		<div class="card soft-card p-2 user_card">
			<div class="d-flex">
				<div class="flex-shrink-0 align-self-center">
					<div class="card-icon" style="background-color:#23B7E5;">
						<i class="fas fa-globe"></i>
					</div>
				</div>
				<div class="flex-grow-1 mt-2 ps-1">
					<span class="soft-card-desc mb-1 d-block">'.__('Primary Domain').'</span>
					<h4 class="soft-card-val">'.$WE->getPrimaryDomain().'</h4>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb-3">
		<div class="card soft-card p-2 user_card">
			<div class="d-flex">
				<div class="flex-shrink-0 align-self-center">
					<div class="card-icon" style="padding: 15px 21px; background-color:#26BF94;">
						<i class="fas fa-info"></i>
					</div>
				</div>
				<div class="flex-grow-1 mt-2 ps-1">
					<span class="soft-card-desc mb-1 d-block">'.__('IP Address').'</span>
					<h4 class="soft-card-val">'.get_user_IP($WE->user).'</h4>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb-3">
		<div class="card soft-card p-2 user_card">
			<div class="d-flex">
				<div class="flex-shrink-0 align-self-center">
					<div class="card-icon" style="padding: 15px 13px; background-color:#FF747A;">
						<i class="fas fa-home"></i>
					</div>
				</div>
				<div class="flex-grow-1 mt-2 ps-1">
					<span class="soft-card-desc mb-1 d-block">'.__('Home Directory').'</span>
					<h4 class="soft-card-val">'.$WE->user['homedir'].'</h4>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb-3">
		<div class="card soft-card p-2 user_card">
			<div class="d-flex">
				<div class="flex-shrink-0 align-self-center">
					<div class="card-icon" style="background-color:#41B5C7;">
						<i class="fas fa-sign-in-alt"></i>
					</div>
				</div>
				<div class="flex-grow-1 mt-2 ps-1">
					<span class="soft-card-desc mb-1 d-block">'.__('Last Login IP Address').'</span>
					<h4 class="soft-card-val">'.(empty($last_login_ips[1]) ? 'NA' : $last_login_ips[1]['ip']).'</h4>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb-3">
		<div class="card soft-card p-2 user_card">
			<div class="d-flex">
				<div class="flex-shrink-0 align-self-center">
					<div class="card-icon" style="background-color:#F4B567;"> 
						<i class="fas fa-list-ul"></i>
					</div>
				</div>
				<div class="flex-grow-1 mt-2 ps-1">
					<span class="soft-card-desc mb-1 d-block">'. __('Total Domains').'</span>
					<h4 class="soft-card-val">'.(!empty($domain_count['total']) ? $domain_count['total'] : 0).'</h4>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb-3">
		<div class="card soft-card p-2 user_card">
			<div class="d-flex">
				<div class="flex-shrink-0 align-self-center">
					<div class="card-icon" style="background-color:#3D8FEE;">
						<i class="fas fa-database"></i>
					</div>
				</div>
				<div class="flex-grow-1 mt-2 ps-1">
					<span class="soft-card-desc mb-1 d-block">'.__('Total Databases').'</span>
					<h4 class="soft-card-val">'.(!empty($usage['db']['used']) ? $usage['db']['used'] : 0).'</h4>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 mb-3">
		<div class="card soft-card p-2 user_card">
			<div class="d-flex">
				<div class="flex-shrink-0 align-self-center">
					<div class="card-icon" style="background-color:#3D8FEE;">
						<i class="fas fa-chart-area"></i>
					</div>
				</div>
				<div class="flex-grow-1 mt-2 ps-1">
					<span class="soft-card-desc mb-1 d-block">'.__('Total Bandwidth').'</span>
					<h4 class="soft-card-val">'.(!empty($usage['bandwidth']['limit']) ? $usage['bandwidth']['limit'] : 'Unlimited').'</h4>
				</div>
			</div>
		</div>
	</div>
</div>';

	if(!empty($apps_updates_available) && is_single_user()){
		$col_size = !empty($apps_updates_available) && !empty($updates_available) ? 'col-md-6 col-xs-12' : 'col-xs-12';
		echo '
		<div class="row"><!----#Update---->';
			echo (!empty($apps_updates_available) ? '
			<div class="'.$col_size.'">
				<div class="alert alert-warning" style="padding:8px">
					<center>
						<img src="'.$theme['images'].'notice.gif" /> &nbsp;					
						<a href="'.$globals['admin_url'].'act=apps_updates" alt="" style="text-decoration:none;">'.__('There are $0$1$2 Apps Update(s) available.', array('<b>', $apps_updates_available, '</b>')).'</a>
					</center>
				</div>
			</div>' : '');
			echo (!empty($updates_available) ? '
			<div class="'.$col_size.'">
				<div class="alert alert-warning" style="padding:8px">
					<center>
						<img src="'.$theme['images'].'notice.gif" /> &nbsp;
						<a href="'.$globals['ind'].'act=installations&showupdates=true" alt="" style="text-decoration:none;">'.__('There are $0$1$2 Update(s) available.', array('<b>', $updates_available, '</b>')).'</a>			
					</center>
				</div>
			</div>' : '');
		echo '
		</div>';
	}
	
	// If Storage exceeds for user
	if($usage['disk']['limit'] != 'unlimited' && $usage['disk']['limit_bytes'] < $usage['disk']['used_bytes']){
		echo '<div class="row">
			<div class="col-xs-12">
				<div class="alert alert-danger" style="padding:8px">
					<center>
						<img src="'.$theme['images'].'notice.gif" /> &nbsp;					
						'.__('You have exceeded the maximum disk space allocated to your account !').'
					</center>
				</div>
			</div>
		</div>';
	}
	
	echo '
<div class="row">
	<div class="col-xxl-8 col-xl-8 col-lg-8 col-md-12 col-12" id="draggablePanelList">';
	
	foreach ($icons as $key => $value) {
		
		if(empty($value['icons']) && empty($value['html'])){
			continue;
		}
		
		// Collapsed
		if(!empty($WE->user_prefs['collapsed_panels']['panel_'.$key])){
			$glyph = 'collapsed';
			$collapsed = '';
		
		// Opened
		}else{
			$glyph = '';
			$collapsed = 'show';
		}
		
		echo '
		<div class="card soft-card mb-4 panel-row" id="row_'.$key.'">
			<div class="accordion panel-default" id="main_div_'.$key.'">
				<div class="accordion-item border-0" id="main_table_'.$key.'">
					<div class="accordion-header" id="panel_'.$key.'_heading" >
						<div class="row align-items-center panel-heading px-4 ">
							<div class="col-10 ">							
								<i class="'.$value['icon'].' panel-icon"></i>
								<label class="panel-head d-inline-block mb-0">'.$value['name'].'</label>
							</div>
							<div class="col-2">
								<div class="accordion-button '.$glyph.'" type="button" data-bs-toggle="collapse" data-bs-target="#panel_'.$key.'" aria-controls="panel_'.$key.'" onclick="panel_collapse(\''.$key.'\');"></div>
							</div>
						</div>
					</div>
					<div id="panel_'.$key.'" class="accordion-collapse collapse '.$collapsed.'" aria-labelledby="panel_'.$key.'_heading" data-bs-parent="#main_div_'.$key.'">
						<div class="accordion-body p-1">
							<div class="row">';
							if(!empty($value['icons'])){
							foreach ($value['icons'] as $k => $v) {
								
								if(!empty($v['hidden'])){
									continue;
								}
								
								echo '
								<div class="col-xs-6 col-sm-6 col-md-5 col-lg-2 col-xl-2 col-xxl-2 ms-3 accordion_item">
									<a href="'.$v['href'].'" onclick="'.$v['onclick'].'" '.( $v['atts'] ? $v['atts'] : "").' class="webuzo_icons '.( $v['class'] ? $v['class'] : "").'" value="'.$v['name'].'" '.( !empty($v['target']) ? 'target="'.$v['target'].'"' : '').'>
										<div id="'.$v['id'].'" class="pan-button">
											<span class="image_wrapper">'.(empty($v['fa-icon']) ? '<img src="'.$v['icon'].'" alt="" />' : '<i class="'.$v['fa-icon'].'"></i>').'</span><br>
											<span class="medium">'.$v['name'].'</span>
										</div>
									</a>
								</div>';
								}
							}
							if(!empty($value['html'])){
								echo $value['html'];
							}
							
						echo '
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>';
	}
	
	echo '	
	</div>
	
	<div class="info-column col-xxl-4 col-xl-4 col-lg-4 col-md-12 col-12">';
		
		if(!empty($list_users) && !is_single_user()){
			
			$switch_users = '';
			foreach($list_users as $user){
				$switch_users .= '<option value="'.$user['user'].'" '.POSTselect('login_to', $user['user'], $SESS['user'] == $user['user']).'>'.$user['user'].' ['.$user['domain'].']</option>';
			}
			
			$userindex_right_menu['switch_user'] = '<!--Switch User-->
		
		<div class=" accordion panel-default mb-4" id="switchuser-accordian">
			<div class="soft-card accordion-item mb-4">
				<h2 class=" accordion-header panel-heading" id="switchuser-panel">
					<button class=" accordion-button '.(!empty($WE->user_prefs['collapsed_panels']['panel_switchuser']) ? 'collapsed' : '').'" type="button" data-bs-toggle="collapse" data-bs-target="#switchuser" aria-expanded="'.(!empty($WE->user_prefs['collapsed_panels']['panel_switchuser']) ? 'false' : 'true').'" aria-controls="switchuser" onclick="panel_collapse(\'switchuser\')">
						<span class="panel-head d-inline-block mb-0">'.__('Switch User').'</span>
					</button>
				</h2>
				<div id="switchuser" class="accordion-collapse collapse '.(!empty($WE->user_prefs['collapsed_panels']['panel_switchuser']) ? '' : 'show').'" data-show='.(!empty($WE->user_prefs['collapsed_panels']['panel_switchuser']) ? 'false' : 'true').' aria-labelledby="switchuser-panel" data-bs-parent="#switchuser-accordian">
					<div class="accordion-body">
						<select class="form-select make-select2" s2-placeholder="'.__('Select User').'" id="login_to" name="login_to" style="width: 100%;">
							'.$switch_users.'
						</select>
					</div>
				</div>
			</div>
		</div>';
		
		}
		
		$userindex_right_menu['bandwidth_statistics'] = '<!--Bandwidth Statistics-->
		<div class="accordion panel-default mb-4" id="bandwidth-accordian">
			<div class=" soft-card accordion-item mb-4 border-0">
				<h2 class="accordion-header panel-heading" id="bandwidth-panel">
					<button class="accordion-button '.(!empty($WE->user_prefs['collapsed_panels']['panel_bandwidth']) ? 'collapsed' : '').'" type="button" data-bs-toggle="collapse" data-bs-target="#bandwidth" aria-expanded="'.(!empty($WE->user_prefs['collapsed_panels']['panel_bandwidth']) ? 'false' : 'true').'" aria-controls="bandwidth" onclick="panel_collapse(\'bandwidth\')">
						<span class="panel-head d-inline-block mb-0">'.__('Bandwidth Statistics').'</span>
					</button>
				</h2>
				<div id="bandwidth" class="accordion-collapse collapse '.(!empty($WE->user_prefs['collapsed_panels']['panel_bandwidth']) ? '' : 'show').'" data-show='.(!empty($WE->user_prefs['collapsed_panels']['panel_bandwidth']) ? 'false' : 'true').' aria-labelledby="bandwidth-panel" data-bs-parent="#bandwidth-accordian">
					<div class="accordion-body">
						<div style="margin-left:20px;" id="bw_chartLegend"></div>
						<div id="graph_td"  class="col-sm-12 col-xs-12">';
							$month['yr'] = datify(time(), 0, 1, 'Y');
							$month['month'] = datify(time(), 0, 1, 'm');
							$tmp = mktime(1, 1, 1, $month['month'], 1, $month['yr']);
							$month['mth_txt'] = date('M', $tmp);
							$userindex_right_menu['bandwidth_statistics'] .= '<label class="sai_tit mb-4">'.gmdate('M Y', time()).'</label>
							<div class="float-end">';
							$mon = gmdate('M Y', strtotime('-1 month'));
							$mon = explode(' ', $mon);
							$userindex_right_menu['bandwidth_statistics'] .= '<a href="'.$globals['ind'].'act=bandwidth&month='.$mon[0].'&year='.$mon[1].'&type=all" style="text-decoration:none"><input type="button" class="btn btn-outline-primary btn-sm" id="prev_month" value="'.__('Prev Month').'"/></a>
						</div>';
							$userindex_right_menu['bandwidth_statistics'] .= '<div style="width:100%; height:300px; padding: 2px;" id="bwband_holder"></div>
						</div>
						
					</div>
				</div>
			</div>
		</div>';
		
	// Show Resource Usage Stats if not disabled from General Settings, cGroups v2 is enabled and user is assigned some resource limit plan
	if(empty($globals['disable_resource_stats']) && $globals['cgroup_version'] == 'cgroup2fs' && !empty($WE->user['resource_limit']) && !empty($stats)){
		
		$userindex_right_menu['cgroup_graph'] = '<!--Info Panel-->
		<div class="accordion panel-default mb-4" id="cgroup_graph-accordian">
			<div class=" soft-card accordion-item mb-4 border-0">
				<h2 class="accordion-header panel-heading" id="cgroup_graph-panel">
					<button class="accordion-button '.(!empty($WE->user_prefs['collapsed_panels']['panel_webuzohead']) ? 'collapsed' : '').'" type="button" data-bs-toggle="collapse" data-bs-target="#cgrouphead" aria-expanded="'.(!empty($WE->user_prefs['collapsed_panels']['panel_webuzohead']) ? 'false' : 'true').'" aria-controls="cgrouphead" onclick="panel_collapse(\'cgrouphead\')">
						<span class="panel-head d-inline-block mb-0">'.__('Resource Stats Graph').'</span>
					</button>
				</h2>
				<div id="cgrouphead" class="accordion-collapse collapse '.(!empty($WE->user_prefs['collapsed_panels']['panel_webuzohead']) ? '' : 'show').'" aria-labelledby="cgroup_graph-panel" data-bs-parent="#cgroup_graph-accordian">
					<div class="accordion-body">
						<div class="row">
							<div class="col sai_smbox">
								<center><div class="cpugraph server_graph" id="cpugraph"></div></center>
								<span class="soft-smbox-title">'.__('CPU').'</span>								
							</div>
							<div class="col sai_smbox">
								<center><div class="memorygraph server_graph" id="memorygraph"></div></center>
								<span class="soft-smbox-title">'.__('Memory').'</span>
							</div>
							<div class="col sai_smbox">
								<center><div class="diskgraph server_graph" id="diskgraph"></div></center>
								<span class="soft-smbox-title">'.__('Disk').'</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
		
		$userindex_right_menu['cgroup'] = '<!--Resource Usage Stats-->
		<div class="accordion panel-default mb-4" id="cgroup-accordian">
			<div class=" soft-card accordion-item mb-4 border-0">
				<h2 class="accordion-header panel-heading" id="cgroup-panel">
					<button class="accordion-button '.(!empty($WE->user_prefs['collapsed_panels']['panel_cgroup']) ? 'collapsed' : '').'" type="button" data-bs-toggle="collapse" data-bs-target="#cgroup" aria-expanded="'.(!empty($WE->user_prefs['collapsed_panels']['panel_cgroup']) ? 'false' : 'true').'" aria-controls="cgroup" onclick="panel_collapse(\'cgroup\')">
						<span class="panel-head d-inline-block mb-0">'.__('Resource Usage Stats').'</span>
					</button>
				</h2>
				<div id="cgroup" class="accordion-collapse collapse '.(!empty($WE->user_prefs['collapsed_panels']['panel_cgroup']) ? '' : 'show').'" aria-labelledby="cgroup-panel" data-bs-parent="#cgroup-accordian">
					<div class="accordion-body" id="cgroup_body">
						<div class="col-sm-12 col-xs-12 mb-4">
							<div>
								<label class="medium" style="display: inline-block">'.__('CPU Usage (in %)').'</label> &nbsp; - &nbsp;
								<span id="cpu">'.$stats['cpu'].'</span> / '.(empty($stats['assigned']['cpuquota']) ? '∞' : $stats['assigned']['cpuquota']).'
							</div>
						</div>
						<div class="col-sm-12 col-xs-12 mb-4">
							<div>
								<label class="medium" style="display: inline-block">'.__('Total Disk Read').'</label> &nbsp; - &nbsp; <span id="read_bw">'.$stats['read_bw'].'</span>
							</div>
						</div>
						<div class="col-sm-12 col-xs-12 mb-4">
							<div>
								<label class="medium" style="display: inline-block">'.__('Total Disk Write').'</label> &nbsp; - &nbsp; <span id="write_bw">'.$stats['write_bw'].'</span>
							</div>
						</div>
						<div class="col-sm-12 col-xs-12 mb-4">
							<div>
								<label class="medium" style="display: inline-block">'.__('Memory Usage').'</label> &nbsp; - &nbsp;
								<span id="memory">'.$stats['memory'].'</span> / '.(empty($stats['assigned']['mem_max']) ? '∞' : $stats['assigned']['mem_max']).'
							</div>
						</div>
						<div class="col-sm-12 col-xs-12">
							<div>
								<label class="medium" style="display: inline-block">'.__('Active Tasks').'</label> &nbsp; - &nbsp;
								<span id="tasks">'.$stats['tasks'].'</span> / '.(empty($stats['assigned']['maxtask']) ? '∞' : $stats['assigned']['maxtask']).'
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	
		$userindex_right_menu['server_status'] = '<!--Server Status-->
		<div class="accordion panel-default mb-4" id="resource-accordian">
			<div class=" soft-card accordion-item mb-4 border-0">
				<h2 class="accordion-header panel-heading" id="resource-panel">
					<button class="accordion-button '.(!empty($WE->user_prefs['collapsed_panels']['panel_user_resource']) ? 'collapsed' : '').'" type="button" data-bs-toggle="collapse" data-bs-target="#user_resource" aria-expanded="'.(!empty($WE->user_prefs['collapsed_panels']['panel_user_resource']) ? 'false' : 'true').'" aria-controls="user_resource" onclick="panel_collapse(\'user_resource\')">
						<span class="panel-head d-inline-block mb-0">'.__('Server Status').'</span>
					</button>
				</h2>
				<div id="user_resource" class="accordion-collapse collapse '.(!empty($WE->user_prefs['collapsed_panels']['panel_user_resource']) ? '' : 'show').'" aria-labelledby="resource-panel" data-bs-parent="#resource-accordian">
					<div class="accordion-body" id="user_resource_body"></div>
				</div>
			</div>
		</div>';
		
		$userindex_right_menu['info_panel'] = '<!--Info Panel-->
		<div class="accordion panel-default mb-4" id="info-accordian">
			<div class=" soft-card accordion-item mb-4 border-0">
				<h2 class="accordion-header panel-heading" id="webuzohead-panel">
					<button class="accordion-button '.(!empty($WE->user_prefs['collapsed_panels']['panel_webuzohead']) ? 'collapsed' : '').'" type="button" data-bs-toggle="collapse" data-bs-target="#webuzohead" aria-expanded="'.(!empty($WE->user_prefs['collapsed_panels']['panel_webuzohead']) ? 'false' : 'true').'" aria-controls="webuzohead" onclick="panel_collapse(\'webuzohead\')">
						<span class="panel-head d-inline-block mb-0">'.__('Server Info').'</span>
					</button>
				</h2>
				<div id="webuzohead" class="accordion-collapse collapse '.(!empty($WE->user_prefs['collapsed_panels']['panel_webuzohead']) ? '' : 'show').'" aria-labelledby="webuzohead-panel" data-bs-parent="#info-accordian">
					<div class="accordion-body">
						<div class="row">';
							if(!empty($installed_apps[$web_server."_1"])){
							$userindex_right_menu['info_panel'] .= '
							<div class="col sai_smbox">
								<p><i class="fas fa-server fa-2x soft-icon"></i></p>
								<h5 class="medium">'.$installed_apps[$web_server."_1"]["name"].'</h5>								
							</div>';
							}
							if(!empty($installed_apps[$mysql."_1"])){
							$userindex_right_menu['info_panel'] .= '
							<div class="col sai_smbox">
								<p><i class="fas fa-database fa-2x soft-icon"></i></p>
								<h5 class="medium">'.$installed_apps[$mysql."_1"]["name"].'</h5>								
							</div>';
							}
						$userindex_right_menu['info_panel'] .= '
						</div>
						<div class="row">';
							if(!empty($installed_apps[$def_php."_1"])){
							$userindex_right_menu['info_panel'] .= '
							<div class="col sai_smbox">
								<p><i class="fab fa-php fa-2x soft-icon"></i></p>
								<h5 class="medium">'.$installed_apps[$def_php."_1"]["name"].'</h5>
							</div>';
							}
							$userindex_right_menu['info_panel'] .= '
							<div class="col sai_smbox">
								<p><i class="fab fa-linux fa-2x soft-icon"></i></p>
								<h5 class="medium">'.getDistro().'</h5>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>';
		
		$userindex_right_menu['monthly_chart'] = '<!--Monthly Chart-->
		<div class="accordion panel-default mb-4" id="monthly-chart-accordian">
			<div class=" soft-card accordion-item mb-4 border-0">
				<h2 class="accordion-header panel-heading" id="monthly-chart-panel">
					<button class="accordion-button '.(!empty($WE->user_prefs['collapsed_panels']['panel_monthly-chart']) ? 'collapsed' : '').'" type="button" data-bs-toggle="collapse" data-bs-target="#monthly-chart" aria-expanded="'.(!empty($WE->user_prefs['collapsed_panels']['panel_monthly-chart']) ? 'false' : 'true').'" aria-controls="monthly-chart" onclick="panel_collapse(\'monthly-chart\')">
						<span class="panel-head d-inline-block mb-0">'.__('Yearly Bandwidth Statistics').'</span>
					</button>
				</h2>
				<div id="monthly-chart" class="accordion-collapse collapse '.(!empty($WE->user_prefs['collapsed_panels']['panel_monthly-chart']) ? '' : 'show').'" aria-labelledby="monthly-chart-panel" data-show = "'.(!empty($WE->user_prefs['collapsed_panels']['panel_monthly-chart']) ? 'false' : 'true').'"  data-bs-parent="#monthly-chart-accordian">
					<div class="accordion-body">
						<div id="bw_monthly_chartLegend"></div>
						<div id="bw_monthly_body" style="height:300px; margin: 0 auto; font-size:12px;"></div>
					</div>
				</div>
			</div>
		</div>';
	
		$userindex_right_menu = apply_filters('userindex_right_menu', $userindex_right_menu);
		
		foreach($userindex_right_menu as $k => $v){
			echo $v;
		}
		
	echo '
	</div>
</div>';

apply_filters('userindex_theme_end');

softfooter();

}
