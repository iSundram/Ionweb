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

function add_server_theme(){

global $globals, $user, $theme, $error, $ips, $done, $uuid, $server, $servergroups;

	softheader(__('Add Server'));

	echo '
<div class="soft-smbox col-12 col-md-11 p-3 mx-auto">
	<div class="sai_main_head text-center">
		<i class="fa fa-sitemap me-2"></i>'.__('Add Server').'
	</div><hr>
	<form accept-charset="'.$globals['charset'].'" name="add_server" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1" data-doneredirect="'.$globals['admin_index'].'act=servers">
	<div class="row mx-auto w-100 my-3">
		<div class="col-sm-4">	
			<label class="control-label">'.__('Server Name').'&nbsp;</label>
		</div>
		<div class="col-sm-4">
			<input type="text" class="form-control" name="server_name" size="30" value="'.POSTval('server_name', @$server['server_name']).'" />
		</div>
	</div>

	<div class="row mx-auto w-100 my-3">
		<div class="col-sm-4">
			<label class="control-label">'.__('Server IP').'&nbsp;</label>
		</div>
		<div class="col-sm-4">
			<input type="text" class="form-control" name="ip" size="30" value="'.POSTval('ip', @$server['ip']).'" />
		</div>
	</div>

	<div class="row mx-auto w-100 my-3">
		<div class="col-sm-4">
			<label class="control-label">'.__('Internal IP (Optional)').'&nbsp;</label>
		</div>
		<div class="col-sm-4">
			<input type="text" class="form-control" name="internal_ip" id="internal_ip" size="30" value="'.POSTval('internal_ip', @$server['internal_ip']).'" />
		</div>
	</div>

	<div class="row mx-auto w-100 my-3">
		<div class="col-sm-4">
			<label class="control-label">'.__('API Key').'&nbsp;</label>
		</div>
		<div class="col-sm-4">
			<input type="text" class="form-control" name="apikey" size="30" value="'.POSTval('apikey', @$server['apikey']).'" />
		</div>
	</div>
	
	<div class="row mx-auto w-100 my-3">
		<div class="col-sm-4">
			<label class="control-label">'.__('Server Group').'</label>
		</div>
		<div class="col-sm-4">
			<select class="form-select" name="sgid" id="sgid" />';
			foreach($servergroups as $k => $v){
				echo '<option '.POSTselect('sgid', $k, ($server['sgid'] == $k)).' value="'.$k.'">'.$v['name'].'</option>';
			}
			
		echo'<br/></select><span class="help-block"></span>
		</div>
	</div>

	<div class="row mx-auto w-100 my-3">
		<div class="col-4">
			<label class="control-label">'.__('Lock Server').'&nbsp;</label>
		</div>
		<div class="col-4">
			<input class="mb-2 mt-2" type="checkbox" name="locked" '.POSTchecked('locked', $server['locked']).' value="1">
		</div>
	</div>
	
	<center><input type="submit" name="add_server" class="flat-butt btn btn-primary" value="'.__('Submit').'" /></center>
	</form>
</div>';

	softfooter();

}
