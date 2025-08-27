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

function ram_theme(){

global $theme, $globals, $user, $ram;

	if(optGET('ajax')){	

		echo 'var server_ram = [
			{ label: "Used",  data: '.$ram['used'].'},
			{ label: "Free",  data: '.$ram['free'].'}
		];';
			
				return true;
	}

	softheader(__('RAM'));

	echo '
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.min.js" type="text/javascript"></script>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function server_graph_data(re){		

	var server_ram = [
		{ label: "Used",  data: '.$ram['used'].'},
		{ label: "Free",  data: '.$ram['free'].'}
	];	
	if(re.length > 0){
		try{
			eval(re);
		}catch(e){ }
	}	
	
	// Fill in the Text
	$_("server_ram_text").innerHTML = server_ram[0].data+" MB / "+(server_ram[0].data+server_ram[1].data)+" MB";
		
	server_graph("server_ram", server_ram);
	
	
};
																			   
function getusage(){
	if(AJAX("'.$globals['index'].'act=ram&ajax=true", "server_graph_data(re)")){
		return false;
	}else{
		return true;	
	}
};

function startusage(){
	ajaxtimer = setInterval("getusage()", 5000);
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
</script>';

	echo '
<div class="col-12 col-md-11 mx-auto soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'ram.png" class="webu_head_img"/>'.__('RAM').'
	</div>
</div>
<div class="col-12 col-md-11 mx-auto soft-smbox p-4 mt-4">
	<div class="row">
		<div class="col-12 col-md-6">
			<div class="sai_graph_head">'.__('RAM Information').'</div>
			<div class="p-3">
				<label class="form-label label_si">'.__('Total RAM ').': </label>
				<span class="val value_si">'.$ram['limit'].__(' MB').'</span><br/>
				<label class="form-label label_si">'.(isset($ram['swap']) ? __('SWAP') : __('Burstable')).' : </label> 
				<span class="val value_si">'.(isset($ram['swap']) ? $ram['swap'] : $ram['burst']).__(' MB').'</span><br/>
				<label class="label_si form-label">'.__('Utilised ').':</label>
				<span class="val value_si">'.$ram['used'].' MB</span>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="sai_graph_head">'.__('RAM Utilization').'</div>
			<div class="p-3">
				<div id="server_ram" class="server_graph" style="margin: auto;"></div><br>
				<div id="server_ram_text" class="text-center value_si">&nbsp;</div>		
			</div>
		</div>
	</div>
</div>';

	softfooter();

}

