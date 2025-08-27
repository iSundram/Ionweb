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

function cpu_theme(){

global $theme, $globals, $user, $cpu;

	if(optGET('ajax')){	
		echo 'var server_cpu = [
			{ label: "Used",  data: '.$cpu['cpu']['percent'].'},
			{ label: "Free",  data: '.$cpu['cpu']['percent_free'].'}
		];';
			
		return true;
	}

	softheader(__('CPU Information'));

	echo '
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.min.js" type="text/javascript"></script>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">												   
function getusage(){	
	if(AJAX("'.$globals['index'].'act=cpu&ajax=true", "server_graph_data(re)")){
		return false;
	}else{
		return true;	
	}
};

function startusage(){
	ajaxtimer = setInterval("getusage()", 5000);
};	

function server_graph_data(re){		

	var server_cpu = [
		{ label: "Used",  data: '.$cpu['cpu']['percent'].'},
		{ label: "Free",  data: '.$cpu['cpu']['percent_free'].'}
	];	
	if(re.length > 0){
		try{
			eval(re);
		}catch(e){ }
	}
	
	// Fill in the Text
	$_("server_cpu_text").innerHTML = server_cpu[0].data+"% / 100%";
		
	// CPU
	server_graph("server_cpu", server_cpu);
	
	
};

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
	server_graph_data(\'void(0);\'); 
	startusage();
});
</script>

<style>
.row-centered{
text-aling: center;
}
.col-centered {
	display:inline-block;
	float:none;
	/* reset the text-align */
	text-align:left;
	/* inline-block space fix */
	margin-right:-4px;
}
</style>';

	echo '
<div class="soft-smbox col-12 col-md-12 mx-auto p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'cpu.png" class="webu_head_img"/>'.__(' CPU').'
	</div>
</div>
<div class="soft-smbox col-12 col-md-12 mx-auto p-3 mt-4">
	<div class="sai_notice">'.__('All respective LOGOS used are trademarks or registered trademarks of their respective companies.').'</div><br>
	<div class="row my-3">
		<div class="col-12 col-md-6">
			<div class="sai_graph_head">'.__('CPU Information').'</div>
			<div class="p-3">
				<label class="label_si form-label">'.__('CPU Model :').'</label>
				<span class="val value_si">'.$cpu['cpu']['model_name'].'</span><br/>
				<label class="label_si form-label">'.__('Max Frequency :').'</label>
				<span class="val value_si">'.$cpu['cpu']['limit'].' MHz</span><br/>
				<label class="label_si form-label">'.__('Cache Size :').'</label>
				<span class="val value_si">'.$cpu['cpu']['cache_size'].'</span><br/>
				<label class="label_si form-label">'.__('Core Count :').'</label>
				<span class="val value_si">'.$cpu['cpu']['core_count'].'</span><br/>
				<label class="form-label label_si">'.__('Powered by : ').'</label>	
				<span class="val value_si pull-left"><img src="'.$theme['images'].$cpu['cpu']['manu'].'.gif" style="width: 100; height: 400;" /></span>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="sai_graph_head">'.__('CPU Utilization').'</div>
			<div class="p-3">
				<div id="server_cpu" class="server_graph" style="margin: auto;"></div>
				<div id="server_cpu_text" class="text-center value_si"></div>
			</div>
		</div>
	</div>	
</div>';

	softfooter();

}