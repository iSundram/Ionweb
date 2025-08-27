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

function server_reboot_theme(){

	global $user, $globals, $theme, $softpanel, $error, $done;

	softheader(__('Server Reboot'));

	echo '
<style>
.img_server_reboot {
	cursor:pointer;
	width:48px;
	height:48px;
}
.img_server_reboot_txt {
	padding:5px;
	font-size:15px;
}
</style>

<div class="soft-smbox col-12 col-lg-10 p-3 mx-auto">
	<div class="sai_main_head">
		<img width="48" height="48" src="'.$theme['images'].'server.png" class=""/>&nbsp;'.__('Server Reboot').'
	</div>
</div>
<div class="soft-smbox col-12 col-lg-10 p-3 mx-auto mt-4">
	<div class="alert alert-success text-center msg_banner" style="display:none">
		<a href="#close" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		'.__('Reboot Command sent to the Server').'
	</div>
	<div class="row">
		<div class="col text-center p-3 align-self-center">
			<img id="reboot_grace" class="img_server_reboot reboot" src="'.$theme['images'].'reboot_grace.png" data-reboot_type="grace" data-reboot=1>
			<div class="img_server_reboot_txt">'.__('Graceful Server Reboot').'</div>
		</div>
		<div class="col text-center p-3 align-self-center">
			<img id="reboot_force" class="img_server_reboot reboot" src="'.$theme['images'].'reboot_force.png" data-reboot_type="force" data-reboot=1>
			<div class="img_server_reboot_txt">'.__('Forceful Server Reboot').'</div>
		</div>
	</div>';

	echo '
</div>

<script language="javascript" type="text/javascript">

$(".reboot").click(function(){
	var jEle = $(this);
	d = jEle.data();
	a = show_message_r("'.__js('Warning').'", d.reboot_type == "grace" ? "'.__js('Are you sure you want to Reboot your server ?').'" : "'.__js('Are you sure you want to Reboot your server Forcefully ?').'");
	a.alert = "alert-warning";
	a.confirm.push(function(){
		// console.log(d);return;
		submitit(d, {
			done_reload:window.location
		});
	});
	
	show_message(a);
});

</script>';

	softfooter();

}

