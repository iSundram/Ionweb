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

function disk_theme(){

global $theme, $globals, $user, $disk, $current_usage;



softheader(__('Disk Information'));

echo '
<div class="col-12 col-md-12 mx-auto soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'disk.png" class="webu_head_img"/>
		'.__('Disk').'
	</div>
</div>
	
<div class="col-12 col-md-12 mx-auto soft-smbox p-3 mt-4">
	<div class="row">
	<!---- DiskInfo Info Block Starts ----->
		<div class="col-12 col-md-6">
			<div class="sai_graph_head">'.__('Disk Information').'</div>
			<div class="p-3">
				<label class="form-label label_si">'.__('Total Disk :').'</label>				
				<span class="val value_si">'.$disk['disk']['/']['limit_gb'].' GB</span><br/>
				<label class="form-label label_si">'.__('Utilised : ').'</label> 				
				<span class="val value_si">'.$disk['disk']['/']['used_gb'].' GB </span><br/>
			</div>
			<div class="sai_graph_head">'.__('Disk Utilization').'</div>
			<div class="p-3">
				<div id="server_disk" class="server_graph" style="margin: auto;"></div><br>
				<div id="server_disk_text" class="text-center value_si">&nbsp;</div>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="sai_graph_head">'.__('Inodes Information').'</div>
			<div class="p-3">
				<label class="form-label label_si">'.__( 'Total Inodes :').'</label> 
				<span class="val value_si">'.$disk['inodes']['/']['limit'].'</span><br/>
				<label class="form-label label_si">'.__('Utilised : ').'</label> 
				<span class="val value_si">'.$disk['inodes']['/']['used'].'</span>
			</div>
			<div class="sai_graph_head">'.__('Inodes Utilization').'</div>
			<div class="p-3">
				<div id="server_inodes" class="server_graph" style="margin: auto;"></div><br/>
				<div id="server_inodes_text" class="text-center value_si">&nbsp;</div>
			</div>
		</div>
		<div class="col-12 ">
			<div class="sai_main_head text-center mt-5">
				<img src="'.$theme['images'].'disk.png" width="40" height="40" /> '.__('Disk Usage').'
			</div>
			<div id="" class="table-responsive mt-4 ">
				<table id="" border="0" cellpadding="8" cellspacing="1"  class="table sai_form webuzo-table">
					<thead>
					<tr>
						<th>'.__('FILE SYSTEM').'</th>
						<th>'.__('SIZE').'</th>
						<th>'.__('USED').'</th>
						<th>'.__('AVAILABLE').'</th>
						<th>'.__('USE %').'</th>
						<th width="">'.__('MOUNT POINT').'</th>				
					</tr>
					</thead>
					<tbody>';
				if (count($current_usage['output']) > 0) {
					foreach ($current_usage['output'] as $k => $du) {
						if ($k === 0 ) continue;
						$du = preg_split('/ +/', $du);
						$fs = $du[0];
						$sz = $du[1];
						$used = $du[2];
						$available = $du[3];
						$usedpercent = $du[4];					
						$mount = $du[5];
				
						echo '
						<tr>					
							<td>'. $fs .'&nbsp;</td>
							<td>'. $sz .'</td>
							<td>'. $used .'</td>
							<td>'. $available .'</td>
							<td>'. $usedpercent .'</td>
							<td>'. $mount .'</td>
						</tr>';
					}
				}
				
				echo '
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.min.js" type="text/javascript"></script>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
function server_graph_data(){	
var server_disk = [
	{ label: "Used",  data: '.$disk['disk']['/']['used_gb'].'},
	{ label: "Free",  data: '.$disk['disk']['/']['free_gb'].'}
];

var server_inodes = [
	{ label: "Used",  data: '.$disk['inodes']['/']['used'].'},
	{ label: "Free",  data: '.$disk['inodes']['/']['free'].'}
];

// Fill in the Text
$_("server_disk_text").innerHTML = server_disk[0].data+" GB / "+Math.round((server_disk[0].data+server_disk[1].data)*100)/100+" GB";

$_("server_inodes_text").innerHTML = server_inodes[0].data+"  / "+Math.round((server_inodes[0].data+server_inodes[1].data)*100)/100;	

// disk
server_graph("server_disk", server_disk);

server_graph("server_inodes", server_inodes);
}

// Draw a Server Resource Graph
function server_graph(id, data){		

	$.plot($("#"+id), data, 
	{
		series: {
			pie: { 
				innerRadius: 0.7,
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

$(document).ready(function(){
	server_graph_data();
});
</script>';

	softfooter();

}