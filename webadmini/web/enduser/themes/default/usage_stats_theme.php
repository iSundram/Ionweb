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

function usage_stats_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error, $done, $stats;

	softheader(__('Resource Usage Stats'));
	
	error_handle($error);

	echo '
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.min.js" type="text/javascript"></script>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script type="text/javascript" src="'.$theme['url'].'/js/jquery-ui.min.js"></script>
<div class="card col-12 soft-card p-3 mb-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'disk.png" class="webu_head_img me-2"/> 
		<h5 class="d-inline-block">'.__('Resource Usage Stats').'</h5>
	</div>
</div>
<div class="row mb-4">
	<div class="col-12 col-lg-6">
		<div class="card soft-card p-4 mb-3 h-100">
			<h6>'.__('Live Stats').'</h6>
			<hr>
			<div class="table-responsive">
				<table class="table align-middle table-nowrap mb-0 webuzo-table">
					<tbody>
						<tr>
							<td>'.__('CPU Usage (in %)').'</td>
							<td><span id="cpu">'.$stats['cpu'].'</span> / '.(empty($stats['assigned']['cpuquota']) ? '∞' : $stats['assigned']['cpuquota']).'</td>
						</tr>
						<tr>
							<td>'.__('Total Disk Read').'</td>
							<td><span id="read_bw">'.$stats['read_bw'].'</span></td>
						</tr>
						<tr>
							<td>'.__('Total Disk Write').'</td>
							<td><span id="write_bw">'.$stats['write_bw'].'</span></td>
						</tr>
						<tr>
							<td>'.__('Memory Usage').'</td>
							<td><span id="memory">'.$stats['memory'].'</span> / '.(empty($stats['assigned']['mem_max']) ? '∞' : $stats['assigned']['mem_max']).'</td>
						</tr>
						<tr>
							<td>'.__('Tasks').'</td>
							<td><span id="tasks">'.$stats['tasks'].'</span> / '.(empty($stats['assigned']['maxtask']) ? '∞' : $stats['assigned']['maxtask']).'</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="row mt-1" >
				<div class="col-12 col-md-4 p-2">
					<div class ="text-center">
						<center><div class="cpugraph server_graph " id="cpugraph"></div></center>
						<span class="soft-smbox-title">'.__('CPU').'</span>
					</div>
				</div>
				<div class="col-12 col-md-4 p-2">
					<div  class ="text-center" style="border-left: 1px solid #E7EAF3; padding-left: 12px;">
						<center><div class="memorygraph server_graph " id="memorygraph"></div></center>
						<span class="soft-smbox-title">'.__('Memory').'</span>
					</div>
				</div>	
				<div class="col-12 col-md-4 p-2">
					<div  class ="text-center" style="border-left: 1px solid #E7EAF3; padding-left: 12px;">
						<center><div class="diskgraph server_graph " id="diskgraph"></div></center>
						<span class="soft-smbox-title">'.__('Disk').'</span>
					</div>
				</div>	
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-6">
		<div class="card soft-card p-4 mb-3 h-100">
			<div class="table-responsive">
				<table class="table align-middle table-nowrap mb-0 webuzo-table">
					<thead>
						<th>'.__('Limits').'</th>
						<th>'.__('Limit Assigned').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('cGroups v2 should be enabled in the OS to assign limits using Resource Limits feature').'"></i></th>
					</thead>
					<tbody>
						<tr>
							<td>'.__('CPU Quota (in %)').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Percentage of CPU limit (100% refers to 1 core. >100% for more cpu cores).').'"></i></td>
							<td>'.$stats['assigned']['cpuquota'].'</td>
						</tr>
						<tr>
							<td>'.__('IO Read Bandwidth Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max rate data can be read from a disk.').'"></i></td>
							<td>'.$stats['assigned']['read_bw'].'</td>
						</tr>
						<tr>
							<td>'.__('IO Write Bandwidth Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max rate data can be written to a disk.').'"></i></td>
							<td>'.$stats['assigned']['write_bw'].'</td>
						</tr>
						<tr>
							<td>'.__('IOPS Read Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max disk read operations per second.').'"></i></td>
							<td>'.$stats['assigned']['diskread'].'</td>
						</tr>
						<tr>
							<td>'.__('IOPS Write Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max disk write operations per second.').'"></i></td>
							<td>'.$stats['assigned']['diskwrite'].'</td>
						</tr>
						<tr>
							<td>'.__('Memory Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Absolute Memory Limit. Triggers Out-Of-Memory Killer.').'"></i></td>
							<td>'.$stats['assigned']['mem_max'].'</td>
						</tr>
						<tr>
							<td>'.__('Memory High').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Throttle Memory above this Limit.').'"></i></td>
							<td>'.$stats['assigned']['memhigh'].'</td>
						</tr>
						<tr>
							<td>'.__('Tasks Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max Tasks that may be created in the unit').'"></i></td>
							<td>'.$stats['assigned']['maxtask'].'</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
	let cpugraph = [
		{label: "Used",  data: '.$stats['cpu'].'},
		{label: "Free",  data: 100 - '.$stats['cpu'].'}
	];
	
	let memorygraph = [
		{label: "Used",  data: '.$stats['mem_percent'].'},
		{label: "Free",  data: 100 - '.$stats['mem_percent'].'}
	];
	
	let diskgraph = [
		{label: "Used",  data: '.$stats['disk'].'},
		{label: "Free",  data: 100 - '.$stats['disk'].'}
	];
	
	server_graph("cpugraph", cpugraph);
	server_graph("memorygraph", memorygraph);
	server_graph("diskgraph", diskgraph);
});

setInterval(function(){
	$.get("'.$globals['request_url'].'&api=json", {}, function(data){
		$("#cpu").text(data.stats.cpu);
		$("#read_bw").text(data.stats.read_bw);
		$("#write_bw").text(data.stats.write_bw);
		$("#memory").text(data.stats.memory);
		$("#tasks").text(data.stats.tasks);
		
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
		
		server_graph("cpugraph", cpugraph);
		server_graph("memorygraph", memorygraph);
		server_graph("diskgraph", diskgraph);
	});
}, 5000);

// Draw a Server Resource Graph
function server_graph(id, data){		
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
</script>';

	softfooter();
}