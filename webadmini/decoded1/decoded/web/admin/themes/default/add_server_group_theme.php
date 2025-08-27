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

function add_server_group_theme(){

global $globals, $user, $theme, $error, $ips, $done, $servergroups, $sg_selection, $uuid, $edit_group;

	softheader(__('Server Group / Regions'));

	error_handle($error);

	echo '
<div class="soft-smbox col-12 col-md-11 p-3 mx-auto">
	<div class="sai_main_head text-center">
		<i class="fa fa-sitemap me-2"></i>'.__('Server Group / Regions').'
	</div><hr>
	<form accept-charset="'.$globals['charset'].'" name="add_group" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1" data-doneredirect="'.$globals['admin_index'].'act=servergroups">
	<div class="row" id="import_form">
		<div class="col-12 col-md-5 mb-3">
			<label for="group_name" class="sai_head">'.__('Server Group Name').'</label>
		</div>
		<div class="col-12 col-md-5 mb-3">
			<input type="text" name="name" id="group_name" value="'.POSTval('name', $edit_group['name']).'" class="form-control" />
		</div>
		<div class="col-12 col-md-5 mb-3">
			<label for="res_region" class="sai_head">'.__('Region Name').'</label>
		</div>
		<div class="col-12 col-md-5 mb-3">
			<input type="text" name="region" id="res_region" value="'.POSTval('region', $edit_group['region']).'" class="form-control" />
		</div>
			
		<div class="col-12 col-md-5 mb-2">
			<label for="group_desc" class="sai_head">'.__('Server Group Description').'</label>		
		</div>
		<div class="col-12 col-md-5 mb-2">
			<textarea class="form-control" name="desc" rows="5" cols="40" >'.POSTval('desc', $edit_group['desc']).'</textarea>
		</div>
		<div class="col-12 col-md-5 mb-2">
			<label for="dns_role" class="sai_head">'.__('Server Selection').'</label>		
		</div>
		<div class="col-12 col-md-5 mb-2">
			<input class="mb-2 mt-2" type="radio" name="server_selection" '.POSTradio('server_selection', '0', $edit_group['server_selection']).' value="0"> '.__('Least Number of Accounts').' <br>
			<input class="mb-2 mt-2" type="radio" name="server_selection" '.POSTradio('server_selection', '1', $edit_group['server_selection']).' value="1"> '.__('Most Available Space').' <br>
			<input class="mb-2 mt-2" type="radio" name="server_selection" '.POSTradio('server_selection', '2', $edit_group['server_selection']).' value="2"> '.__('Most Available Ram').' <br>
			<input class="mb-2 mt-2" type="radio" name="server_selection" '.POSTradio('server_selection', '3', $edit_group['server_selection']).' value="3"> '.__('Most Available Cores').' <br>
			<input class="mb-2 mt-2" type="radio" name="server_selection" '.POSTradio('server_selection', '4', $edit_group['server_selection']).' value="4"> '.__('Most Available IPv4').' <br>
			<input class="mb-2 mt-2" type="radio" name="server_selection" '.POSTradio('server_selection', '5', $edit_group['server_selection']).' value="5"> '.__('Most Available IPv6').' <br>
			<input class="mb-2 mt-2" type="radio" name="server_selection" '.POSTradio('server_selection', '6', $edit_group['server_selection']).' value="6"> '.__('Least System Load').' <br>
		</div>
				
		<div class="text-center my-3">
			<input type="submit" name="add_group" id="add_group" value="Submit" class="flat-butt btn btn-primary"/>
		</div>
	</div>
	</form>
</div>
<script>
	
	$("#connect_server").click(function(){
		var server_id = $("#server_id").val();
		var dns_role = $("#dns_role").val();
		var d = {"add_cluster": 1, "server_id" : server_id, "dns_role" : dns_role};
		submitit(d, {done_reload : window.location.href});
	});
</script>
';

	softfooter();

}

