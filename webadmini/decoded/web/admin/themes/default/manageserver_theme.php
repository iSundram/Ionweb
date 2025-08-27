<?php

//////////////////////////////////////////////////////////////
//===========================================================
// dashboard_theme.php
//===========================================================
// SOFTACULOUS VIRTUALIZOR
// Version : 1.0
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Alons
// Date:       8th Mar 2010
// Time:       23:00 hrs
// Site:       https://www.virtualizor.com/ (SOFTACULOUS VIRTUALIZOR)
// ----------------------------------------------------------
// Please Read the Terms of use at https://www.virtualizor.com
// ----------------------------------------------------------
//===========================================================
// (c)Softaculous Ltd.
//===========================================================
//////////////////////////////////////////////////////////////

if(!defined('VIRTUALIZOR')){

	die('Hacking Attempt');

}

function manageserver_theme(){

global $theme, $error, $globals, $cluster, $user, $usage, $info;

softheader(__('Manage Server'));

echo '
<div class="bg" >
<div class="row mx-auto my-3">
	<div class="col-12">
		<h4 class="text-dark"><i class="icon icon-servers"></i>&nbsp; '.__('<title>').'</h4>
	</div>
</div>';

error_handle($error); 

// Is it offline ?
$hypervisor_status = $cluster->statewise($globals['server']);
if($hypervisor_status == 0 || $hypervisor_status == 2){

	echo '<div class="e_notice"><b>'.__('note').' : </b> &nbsp; '.__('server_status_'.$hypervisor_status).'</div>';
	
}else{

// Is it loaded into the correct kernel
if(strlen($info['check_kernel']) > 1){
	show_alert($info['check_kernel'] ,"1");
	echo '<div class="e_notice"><b>'.__('note').' : </b> &nbsp; '.$info['check_kernel'].'</div>';
}

if(!empty($GLOBALS['rebooted'])){
	show_alert($info['check_kernel'] ,"2");
	echo '<div class="notice"><b>'.__('note').' : </b> &nbsp; '.__('server_rebooting').'</div>';
}
echo '<style type="text/css">
.card {
	border: none;
	box-shadow: 0px 1px 15px 1px rgba(69, 65, 78, 0.08);
  }
.card-footer{
	border: none;
}
.card-header{
	border: none;
	height: 45px !important;
}

</style>';
echo '
<script src="'.$theme['url'].'/js/chart.js"> </script>
<script language="javascript" type="text/javascript">';

if(is_allowed('server_statistics')){

echo '

function conf_reboot(){
	showalert("'.__('conf_reboot').'" ,"3" ,function(confirm){
		if(confirm == false){
			return false;
		}else{
			$("#progress_bar").show();
	
			$.ajax({
				type: "POST",
				url: "'.$globals['index'].'act=manageserver&reboot=1",
				success: function(data){
						setTimeout(function(){showalert(\''.__('server_rebooting').'\');
						$("#alert-modal").on("hidden.bs.modal", function (e) {
						location.reload(true);
						});} ,200);
				},
				error: function(data) {
					$("#progress_bar").hide();
					return false;
				}
			});
		}
	});
};

// ]]></script>';

if(is_allowed('server_statistics')){
	echo '
	<div class= "row w-100 mx-auto d-flex justify-content-center">
		<div col-sm-12>
			<div class="card w-100 text-center">
				<div class="card-header text-muted">
					<h6>'.$info['hostname'].' | <img src="'.$theme['images'].'/'.$info['os']['distro'].'.png" width="30" />&nbsp;&nbsp;'.$info['os']['name'].'</span></h6>
				</div>
				<div class="card-body">
					<h5 class="text-admin"><img src="'.$theme['images'].$usage['cpu']['manu'].'.png" /></h5>
					<h5 class="card-title">'.__('ipaddr').'&nbsp;'.$info['ips'][0].'</h5>
					<div class="row px-4">
						<div class="col-sm-6">
							<ul class="list-group list-group-flush">
								<li class="list-group-item text-left">'.__('numvps').'&nbsp;'.$info['details']['numvps'].'</li>
								<li class="list-group-item text-left">'.__('alloc_cpu').'&nbsp;'.$info['details']['alloc_cpu'].'</li>
								<li class="list-group-item text-left">'.__('alloc_space').'&nbsp;'.$info['details']['alloc_space'].' GB</li>
								<li class="list-group-item text-left">'.__('alloc_ram').'&nbsp;'.$info['details']['alloc_ram'].' MB</li>
							</ul>
						</div>
						<div class="col-sm-6">
							<ul class="list-group list-group-flush">
								<li class="list-group-item text-left">'.__('cpumodel').'&nbsp;'.$usage['cpu']['cpumodel'].'</li>
								<li class="list-group-item text-left">'.__('servstatus').'&nbsp;'.($info['status'] == 1 ? '<i class="fas fa-running fa-2x text-success"></i>&nbsp;&nbsp;'.__('online').'' : '<i class="fas fa-times-circle fa-2x text-danger"></i>&nbsp;&nbsp;'.__('offline').'').'</li>
								<li class="list-group-item text-left" id="uptime_data">'.__('uptime').'&nbsp;'.$info['uptime'].'</li>
								<li class="list-group-item text-left">'.__('kernel').'&nbsp;'.$info['kernel_name'].'</li>
							</ul>
						</div>
					</div>
					</br>
					<hr>
					<h5>'.__('server_resources').'</h5>
					</br>
					<div class="row w-100 mx-auto">
						<div class = "col-sm-6 col-md-4 col-lg-2">
							<div class="card text-center">
								<div class="card-header text-muted">
								<h6>'._strtoupper(__('cpuinfo')).'</h6>
								</div>
								<div class="card-body">
								<h5 class="card-title" id="cpu_percent"></h5>
								<div class="row mx-auto w-100">
									<div class="col-sm-12 d-flex justify-content-center">
										<canvas id="cpuholder" style="width:100px; height:100px;"></canvas>
									</div>
								</div>
									</div>
								<div class="card-footer text-muted" id="cpu_model">
								</div>
							</div>
						</div>
						<div class = "col-sm-6 col-md-4 col-lg-2">
							<div class="card text-center">
								<div class="card-header text-muted">
								<h6>'._strtoupper(__('raminfo')).'</h6>
								</div>
								<div class="card-body">
								<h5 class="card-title" id="ram_percent"></h5>
								<div class="row w-100 mx-auto">
									<div class="col-sm-12 d-flex justify-content-center">
										<canvas id="ramholder" style="width:100px; height:100px;"></canvas>
									</div>
								</div>
									</div>
								<div class="card-footer text-muted" id="ram_model">
								</div>
							</div>
						</div>
						<div class = "col-sm-6 col-md-4 col-lg-2">
							<div class="card text-center">
								<div class="card-header text-muted">
								<h6>'._strtoupper(__('diskinfo')).'</h6>
								</div>
								<div class="card-body">
								<h5 class="card-title" id="disk_percent"></h5>
								<div class="row mx-auto">
									<div class="col-sm-12 d-flex justify-content-center">
										<canvas id="diskholder" style="width:100px; height:100px;"></canvas>
									</div>
								</div>
									</div>
								<div class="card-footer text-muted" id="disk_model">
								</div>
							</div>
						</div>
						<div class = "col-sm-6 col-md-4 col-lg-2">
							<div class="card text-center">
								<div class="card-header text-muted">
								<h6>'._strtoupper(__('storageinfo')).'</h6>
								</div>
								<div class="card-body">
								<h5 class="card-title" id="storage_percent"></h5>
								<div class="row mx-auto">
									<div class="col-sm-12 d-flex justify-content-center">
										<canvas id="storageholder" style="width:100px; height:100px;"></canvas>
									</div>
								</div>
									</div>
								<div class="card-footer text-muted" id="storage_model">
								</div>
							</div>
						</div>
						<div class = "col-sm-6 col-md-4 col-lg-2">
							<div class="card text-center">
								<div class="card-header text-muted">
								<h6>'._strtoupper(__('bandwidthinfo')).'</h6>
								</div>
								<div class="card-body">
								<h5 class="card-title" id="bandwidth_percent"></h5>
								<div class="row mx-auto">
									<div class="col-sm-12 d-flex justify-content-center">
										<canvas id="bandwidthholder" style="width:100px; height:100px;"></canvas>
									</div>
								</div>
									</div>
								<div class="card-footer text-muted" id="bandwidth_model">
								</div>
							</div>
						</div>
						<div class = "col-sm-6 col-md-4 col-lg-2">
							<div class="card text-center">
								<div class="card-header text-muted">
								<h6>'._strtoupper(__('iowait')).'</h6>
								</div>
								<div class="card-body">
								<h5 class="card-title" id="iowait_percent"></h5>
								<div class="row mx-auto">
									<div class="col-sm-12 d-flex justify-content-center">
										<canvas id="iowaitholder" style="width:100px; height:100px;"></canvas>
									</div>
								</div>
									</div>
								<div class="card-footer text-muted" id="iowait_model">
								</div>
							</div>
						</div>
					</div>
					</br>
					<hr>
					<h5>'.__('server_options').'</h5>
					</br>
					<div class="row mx-auto w-100 text-center">
						<div class="col-sm-12  d-flex justify-content-center">
							<div class="serv_op">
								<a href="'.$globals['index'].'act=config" class="micons">
								<img src="'.$theme['images'].'admin/settingsdp.gif" /><br /><div class="pt-2">'.__('settings').'</div>
								</a>
							</div>
							<div class="serv_op">
								<a href="'.$globals['index'].'act=vscpu" class="micons">
								<img src="'.$theme['images'].'cpu.png" /><br /><div class="pt-2">'.__('cpu_usage').'</div>
								</a>
							</div>
							<div class="serv_op">
								<a href="'.$globals['index'].'act=vsram" class="micons">
								<img src="'.$theme['images'].'ram.png" /><br /><div class="pt-2">'.__('ram_usage').'</div>
								</a>
							</div>
							<div class="serv_op">
								<a href="'.$globals['index'].'act=vsbandwidth" class="micons">
								<img src="'.$theme['images'].'bandwidth.png" /><br /><div class="pt-2">'.__('bandwidth_usage').'</div>
								</a>
							</div>
							<div class="serv_op">
								<a href="javascript:void(0);" class="micons" onclick="conf_reboot();">
								<img src="'.$theme['images'].'restart.png" /><br /><div class="pt-2">'.__('reboot').'</div>
								</a>
							</div>
							<div class="serv_op">
								<a href="'.$globals['index'].'act=backup_plans" class="micons">
								<img src="'.$theme['images'].'admin/backups.png" /><br /><div class="pt-2">'.__('backups').'</div>
								</a>
							</div>
							<div class="serv_op">
								<a href="'.$globals['index'].'act=vpsrestore" class="micons">
								<img src="'.$theme['images'].'admin/restore.png" /><br /><div class="pt-2">'.__('list_backups').'</div>
								</a>
							</div>
						</div>					
					
					</div>
				</div>
			</div>
		</div>
	</div>';
	echo '';
}

echo '
<script>
$(document).ready(function(){

	var xValues = ["Used","Free"];
	var yValues = ["0","100"];
	var barColors = ["#8fafdd","#eee"];
	const cpu_config = {
			type: "doughnut",
			data: {
			labels: xValues,
			datasets: [{
				backgroundColor: barColors,
				data: yValues,
				hoverOffset: 4
			}]
		},
		options: {
			responsive: false,
			maintainAspectRatio: false,
			plugins: { legend: { display: false, }, }
		}
	};

	var cpu_pie = document.getElementById("cpuholder");
	cpuchart = new Chart(cpu_pie, cpu_config );


	const ram_config = {
			type: "doughnut",
			data: {
			labels: xValues,
			datasets: [{
				backgroundColor: barColors,
				data: yValues,
				hoverOffset: 4
			}]
		},
		options: {
			responsive: false,
			maintainAspectRatio: false,
			plugins: { legend: { display: false, }, }
		}
	};

	var ram_pie = document.getElementById("ramholder");
	ramchart = new Chart(ram_pie, ram_config );

	const disk_config = {
			type: "doughnut",
			data: {
			labels: xValues,
			datasets: [{
				backgroundColor: barColors,
				data: yValues,
				hoverOffset: 4
			}]
		},
		options: {
			responsive: false,
			maintainAspectRatio: false,
			plugins: { legend: { display: false, }, }
		}
	};

	var disk_pie = document.getElementById("diskholder");
	diskchart = new Chart(disk_pie, disk_config );

	const storage_config = {
			type: "doughnut",
			data: {
			labels: xValues,
			datasets: [{
				backgroundColor: barColors,
				data: yValues,
				hoverOffset: 4
			}]
		},
		options: {
			responsive: false,
			maintainAspectRatio: false,
			plugins: { legend: { display: false, }, }
		}
	};

	var storage_pie = document.getElementById("storageholder");
	storagechart = new Chart(storage_pie, storage_config );

	const bandwidth_config = {
			type: "doughnut",
			data: {
			labels: xValues,
			datasets: [{
				backgroundColor: barColors,
				data: yValues,
				hoverOffset: 4
			}]
		},
		options: {
			responsive: false,
			maintainAspectRatio: false,
			plugins: { legend: { display: false, }, }
		}
	};

	var bandwidth_pie = document.getElementById("bandwidthholder");
	bandwidthchart = new Chart(bandwidth_pie, bandwidth_config );

	const iowait_config = {
			type: "doughnut",
			data: {
			labels: xValues,
			datasets: [{
				backgroundColor: barColors,
				data: yValues,
				hoverOffset: 4
			}]
		},
		options: {
			responsive: false,
			maintainAspectRatio: false,
			plugins: { legend: { display: false, }, }
		},
		centerText: {
			display: true,
			text: "280"
		}
	};

	var iowait_pie = document.getElementById("iowaitholder");
	iowaitchart = new Chart(iowait_pie, iowait_config );


	getserverstats('.$globals['server'].');
	startusage();
});
</script>

';

}

echo '
</br>
<p class="notice">'.__('trademarks').'</p>
</div>';


echo '
	<script>
	
	function startusage(){
		ajaxtimer = setInterval("getserverstats('.$globals['server'].')", 5000);
	};

	function card_graph(percent_name,model_name,progress_name,percent,bottom_text){
		percent = percent.toFixed(2);
		$("#"+percent_name).html(percent+"%");
		console.log(bottom_text);
		$("#"+model_name).html(bottom_text);
	}

	function getserverstats(serid){
		
		serid = serid || 0;
		
		$.getJSON("'.$globals['index'].'act=manageserver&changeserid="+serid+"&api=json", function(data) {
			card_graph("cpu_percent","cpu_model","cpu_progress",data["usage"]["cpu"]["percent"],"Free: "+data["usage"]["cpu"]["percent_free"]+"%");
			card_graph("ram_percent","ram_model","ram_progress",data["usage"]["ram"]["percent"],"Free: "+data["usage"]["ram"]["percent_free"]+"%");
			card_graph("disk_percent","disk_model","disk_progress",data["usage"]["disk"]["/"]["percent"],"Free: "+data["usage"]["disk"]["/"]["percent_free"]+"%");
			var sto_total = data["info"]["resources"]["total_space"];
			var sto_used = (sto_total - data["info"]["resources"]["space"]).toFixed(2);
			var sto_per = (sto_used / sto_total)*100;
			
			card_graph("storage_percent","storage_model","storage_progress",sto_per,"Free: "+ (100 - sto_per).toFixed(2)+"%");
			card_graph("bandwidth_percent","bandwidth_model","bandwidth_progress",(data["usage"]["bandwidth"]["percent"] == 0)? 0.1 : data["usage"]["bandwidth"]["percent"],"Free: "+data["usage"]["bandwidth"]["percent_free"]+"%");

			if(!empty(data["usage"]["io"])){
				var iowait = data["usage"]["io"]["avg_cpu"]["iowait"];
				var iowait_f = 100 - iowait;
			}else{
				var iowait = "NA";
				var iowait_f = "NA";
			}
			card_graph("iowait_percent","iowait_model","iowait_progress",iowait,"Free: "+iowait_f+"%");

			// Check if chart exist
			let chartcpu = Chart.getChart("cpuholder");
			let chartram = Chart.getChart("ramholder");
			let chartdisk = Chart.getChart("diskholder");
			let chartstorage = Chart.getChart("storageholder");
			let chartbandwidth = Chart.getChart("bandwidthholder");
			let chartiowait = Chart.getChart("iowaitholder");

			if (chartcpu != undefined) {
				cpuchart.data.datasets[0].data = [data["usage"]["cpu"]["percent"] ,data["usage"]["cpu"]["percent_free"]];
				cpuchart.update();
			}
			if (chartram != undefined) {
				ramchart.data.datasets[0].data = [data["usage"]["ram"]["percent"] ,data["usage"]["ram"]["percent_free"]];
				ramchart.update();
			}
			if (chartdisk != undefined) {
				diskchart.data.datasets[0].data = [data["usage"]["disk"]["/"]["percent"] ,data["usage"]["disk"]["/"]["percent_free"]];
				diskchart.update();
			}
			if (chartstorage != undefined) {
				storagechart.data.datasets[0].data = [sto_per ,100 - sto_per.toFixed(2)];
				storagechart.update();
			}
			if (chartbandwidth != undefined) {
				bandwidthchart.data.datasets[0].data = [(data["usage"]["bandwidth"]["percent"] == 0)? 0.1 : data["usage"]["bandwidth"]["percent"] ,data["usage"]["bandwidth"]["percent_free"]];
				bandwidthchart.update();
			}
			if (chartiowait != undefined) {
				iowaitchart.data.datasets[0].data = [iowait,iowait_f];
				iowaitchart.update();
			}

			// Update Server Load Average
			$("#uptime_data").html(data["info"]["uptime"]);
			
		});
	}
	</script>
	';
}
softfooter();

}
