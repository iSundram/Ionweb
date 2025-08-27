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

function bandwidth_theme(){

global $theme, $globals, $kernel, $user, $bandwidth, $month, $band, $done, $error;

	softheader(__('Bandwidth Usage'));

	echo '
<div class="col-12 col-md-12 mx-auto">
	<div class="soft-smbox p-3">
		<div class="sai_main_head">
			<img src="'.$theme['images'].'bandwidth.png" class="webu_head_img me-1"/>'.__('Bandwidth').'
		</div>
	</div>
</div>
<div class="col-12 col-md-12 mx-auto mt-4">
	<div class="soft-smbox p-3 mb-3">
		<form accept-charset="'.$globals['charset'].'" action="" method="post" name="bandwidthedit" id="bandwidthedit" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1>
			<div class="row">
				<div class="col-12 col-md-6">
					<label for="bandwidth_up_limit" class="sai_head control-label">'.__('Total Bandwidth (in GB)').'
						<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('Specify the maximum bandwidth available to calculate the bandwidth graph. Set 0 for unlimited bandwidth. $0 $1 Default graph will be displayed as per the formula by APP $2', ['<br>', '<strong>', '</strong>']).'"></i>
					</label>
					<input type="text"  id="bandwidth_up_limit" name="bandwidth_up_limit" class="form-control" value="'.POSTval('bandwidth_up_limit', '').'" />
				</div>
				<div class="col-12 col-md-6">
					<label for="bandwidth_limit" class="sai_head control-label">'.__('Bandwidth Alert Limit (in GB)').'
						<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('If bandwidth limit exceeds the threshold you will receive an email').'"></i>
					</label>
					<input type="text" id="bandwidth_limit" name="bandwidth_limit" class="form-control" value="'.POSTval('bandwidth_limit', '').'" />
				</div>
			</div>
			<div class="text-center my-5">
				<input type="submit" id="submitbandwidth" name="bandwidth_record" class="btn btn-primary" value="'.__('Submit').'" /> &nbsp; 				
				<input type="button" id="resetbandwidth" name="resetbandwidth" class="btn btn-danger" value="'.__('Reset').'" />
			</div>
		</form>
		<div id="showband">';
		show_band();
		echo '
		</div>
	</div>
</div>
<script>
$("#resetbandwidth").click(function(){
	var d = {bandwidth_reset: 1};
	submitit(d,{
		sm_done_onclose:function(){
			location.reload();
		}
	});
});
</script>';

	softfooter();
	
}

function show_band(){
	
global $theme, $globals, $kernel, $user, $bandwidth, $month, $band;

	echo '
<div class="row">
	<div class="col-12 col-md-6">
		<div class="sai_graph_head">'.__('Bandwidth Information').'</div>
		<div class="p-2">
			<label class="form-label label_si">
				'.__('Total Bandwidth (in GB)').' : 
			</label> 
			<span class="val value_si">
				'.(($bandwidth['limit_gb'] == '999999' || empty($bandwidth['limit_gb']) ) ? __('Unlimited') : $bandwidth['limit_gb'].' GB').'
			</span><br/>
			<label class="form-label label_si">'.__('Utilised : ').'</label> 
			<span class="val value_si">'.$bandwidth['used_gb'].' GB</span><br/>
			<label class="form-label label_si">'.__('Bandwidth Alert Limit (in GB)').' : </label>
			<span class="val value_si">'.(!empty($globals['bandwidth_limit']) ? $globals['bandwidth_limit'].' GB ' : __('NA')).'</span>
		</div>
	</div>
	<div class="col-12 col-md-6">
		<div class="sai_graph_head">'.__('Bandwidth Utilization').'</div>
		<div class="p-3">
			<div id="server_bandwidth" class="server_graph mb-2" style="margin: auto;"></div>
			<div id="server_bandwidth_text" class="text-center value_si">&nbsp;</div>
		</div>
	</div>
</div>
<div class="sai_graph_head text-center">'.$month['mth_txt'].' '.$month['yr'].'</div>
<div class="text-center p-3">
	<div class="col-12 col-md-8 mx-auto">
		<div style="width: 200px; height: 200px;" id="bwband_holder"></div>
	</div>
	<div class="text-center my-3">
		<button id="prevMonth" onclick="getPrevMonth()" class="btn btn-primary">Prev Month</button>
		<button type="button" class="btn btn-primary" onclick="getNextMonth()" '.($month["next"] > date("Ym")?"disabled":"").'>Next Month</button>
	</div>
</div>

<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.min.js" type="text/javascript"></script>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.resize.min.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8"><!-- // --><![CDATA[	

function server_graph_data(){	
var server_bandwidth = [
	{ label: "Used",  data: '.(empty($bandwidth['used_gb']) ? 0.01 : $bandwidth['used_gb']).'},
	{ label: "Free",  data: '.(empty($bandwidth['free_gb']) ? $bandwidth['used_gb']*100 : $bandwidth['free_gb']).'}
];

// Fill in the Text
$_("server_bandwidth_text").innerHTML = server_bandwidth[0].data+" GB";
	
// bandwidth
server_graph("server_bandwidth", server_bandwidth);
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
	
	addjustGraph()
	
	function makedata(data){
	
		var fdata = [];
		i = 0;
		for (x in data){
			fdata.push([i, (data[x])]);
			i++;
		}
	
		return fdata;
		
	}

	var d1 = makedata([0, '.implode(', ', $bandwidth['in']).']);
	var d2 = makedata([0, '.implode(', ', $bandwidth['out']).']);
	
	var bandwidth_graph = [
		{ label: "In",  data: d1},
		{ label: "Out",  data: d2}
	];
	
	$.plot($("#bwband_holder"),  bandwidth_graph, {
		series: {
			stack: true,
            points: { show: true },
			lines: { show: true, fill: true, steps: false }
		},
		yaxis:{
			tickFormatter: function (v) {
				if(v <= 1024)
					return Math.round(v) + " M";
				if(v > 1024 && v < (1024*1024))
					return (v /1024).toFixed(1) + " G";
				if(v > (1024*1024))
					return (v / (1024*1024)).toFixed(2) + " T"
			}
		},
		legend: {
			show: true
		},
        grid: { hoverable: true}
	});
	
	function showTooltip(x, y, contents) {
		$(\'<div id="tooltip">\' + contents + \'</div>\').css( {
			position: "absolute",
			display: "none",
			top: y ,
			left: x + 20,
			border: "1px solid #CCCCCC",
			padding: "2px",
			"background-color": "#EFEFEF",
			"z-index" : 10000,
			opacity: 0.80
		}).appendTo("body").fadeIn(200);
	}

    var previousPoint = null;
    $("#bwband_holder").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

        if (item) {
			
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;
				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
					
				showTooltip(item.pageX, item.pageY,
							"Total : " + parseInt(y) + " MB <br>Day : " + parseInt(x));
			}
		} else {
			$("#tooltip").remove();
			previousPoint = null;
		}
    });
	
	//This function adjust Graph width and height
	function addjustGraph(){
		var w=$(window).width() * 0.50;
		var h=$(window).height() * 0.50;
		$("#bwband_holder").css("width", w);
		$("#bwband_holder").css("height", h);
		
	}
	$(window).resize(function(){
		addjustGraph();
	});
	
	server_graph_data();
	
});
	
	function getPrevMonth(){
	 window.location.href = "'.$globals['ind'].'act=bandwidth&show='.$month['prev'].'";
	}
	function getNextMonth(){
	 window.location.href = "'.$globals['ind'].'act=bandwidth&show='.$month['next'].'";
	}
	
// ]]></script>';

}


