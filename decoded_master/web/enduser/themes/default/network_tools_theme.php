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

function network_tools_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error , $done, $domain, $lookup, $action;

	softheader(__('Network Tools'));

	echo '
<div class="col-12 col-lg-8 mx-auto card soft-card p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'network_tools.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Network Tools').'</h5>
	</div>
</div>
<div class="col-12 col-lg-8 mx-auto card soft-card p-4 mt-4">
	<form accept-charset="'.$globals['charset'].'" name="domain_lookup" method="post" action="" id="domain_lookup" class="form-horizontal">	
		<label for="domain_name" class="sai_head">'.__('Domain Lookup / Trace Route').'</label>
		<span class="sai_exp">'.__('The Domain Lookup tool allows you to find out the IP address of any domain, as well as DNS information about that domain.').'</span>
		<input type="text" name="domain_name" id="domain_name" class="form-control" value="'.POSTval('domain_name', '').'" />

		<div class="row my-4">
			<div class="col-12 col-lg-8 mx-auto text-center">
				<input type="submit" class="flat-butt" name="lookup" value="'.__('Domain Lookup').'" onclick="return this_submitit(this)" />';
				if(!empty($globals['trace_route_enabled'])){				
				echo '
				<input type="submit" class="flat-butt" name="traceroute" value="'.__('Trace Route').'" onclick="return this_submitit(this)" />';
				}
				echo'
			</div>
		</div>
	</form>
	<div id="domain_lookup_tab" style="display:none">
		<div class="card soft-card p-4 shadow-none">
			<div>
				<span class="sai_head" id="lookup_title"></span>
				<button class="btn dclose btn-secondary float-end">'.__('Close').'</button>
			</div>
			<div class="my-3">
				<textarea class="form-control" name="kpaste" rows="18" readonly="readonly" id="lookup" wrap="off"></textarea>
			</div>
		</div>
	</div>	
</div>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[

function this_submitit(ele){
	
	$(".dclose").click();
	
	return submitit(ele, {
		success: function(data){
			if("lookup" in data){
				$("#lookup_title").html(data["action"]+" '.__js('for').' "+data["domain"]);
				$("#lookup").val(data["lookup"]);
				$("#domain_lookup_tab").slideDown("slide", "", 5000);
			}
		}
	});
}
// For close details
$(".dclose").click(function(){	
	$("#domain_lookup_tab").slideUp("slide", "", 1000);
});	

// ]]></script>';

	softfooter();

}


