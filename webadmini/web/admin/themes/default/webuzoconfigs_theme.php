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

function webuzoconfigs_theme(){

global $theme, $globals, $user, $langs, $skins, $error, $done, $webuzoconfigs, $panel_settings;

	softheader(__('Webuzo Configuration'));
	error_handle($error);

	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-cog me-2"></i>'.__('Panel Configuration').'
	</div>
</div>
<div class="soft-smbox p-4 col-12 col-md-12 mx-auto mb-2 mt-4">
	<div class="sai_main_head text-center">
		<i class="fas fa-cog me-2"></i>'.__('Update Webuzo Configuration').'
		<div class="float-end">
			<div style="display:inline;margin-right:20px">
				<input type="submit" id="reconfigure" name="reconfigure" value="'.__('Reconfigure').'" class="btn btn-primary" onclick="return submitit(this)" data-donereload="1" />
				<span class="sai_exp">'.__('Webuzo will remove all IP(s) and fetch the Primary IP and Hostname and update it.').'</span>
			</div>
		</div>
	</div>
	<hr>
	<form accept-charset="'.$globals['charset'].'" name="editconfigs" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)">
	<div class="sai_form">
	<div class="row">
		<div class="col-12 col-md-6">
			<label for="mail" class="sai_head">'.__('Hostname / Panel Domain').'
				<span class="sai_exp">'.__('This is used as the HOSTNAME on your server as well as panel domain for Webuzo').'</span>
			</label>
			<a name="hostname"></a>
			<input class="form-control mb-3" id="priamry_domain" type="text" name="WU_PRIMARY_DOMAIN" size="30" value="'.aPOSTval('WU_PRIMARY_DOMAIN', $globals['WU_PRIMARY_DOMAIN']).'" />
		</div>
		<div class="col-12 col-md-6">
		<label for="mail" class="sai_head">'.__('Enable Quota').'</label>
			<div class="">
				<label class="switch">				
				<input type="checkbox" name="quota" value="1" '.POSTchecked('quota', $globals['quota']).' />
				<span class="slider"></span>
				</label>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<label for="mail" class="sai_head">'.__('Primary IP').' 
				<span class="sai_exp">'.__('Can be IPv4 or IPv6').'</span>
			</label>
			<input class="form-control mb-3" type="text" name="WU_PRIMARY_IP" size="30" value="'.aPOSTval('WU_PRIMARY_IP', $globals['WU_PRIMARY_IP']).'" />
		</div>
		<div class="col-12 col-md-6 mb-3">
			<label for="mail" class="sai_head">'.__('Primary IPV6').'
				<span class="sai_exp">'.__('(Optional) If your Primary IP is IPv4, you can specify an additional IPv6 for your domains and services').'</span>
			</label>
			<input class="form-control mb-3" type="text" name="WU_PRIMARY_IPV6" size="30" value="'.aPOSTval('WU_PRIMARY_IPV6', $globals['WU_PRIMARY_IPV6']).'" />
		</div>
		<div class="col-12 col-md-6">		
			<label for="mail" class="sai_head">'.__('NS1').'</label>
			<input class="form-control mb-3" type="text" name="WU_NS1" size="30" value="'.aPOSTval('WU_NS1', $globals['WU_NS1']).'" />
		</div>
		<div class="col-12 col-md-6">
			<label for="mail" class="sai_head">'.__('NS2').'</label>
			<input class="form-control mb-3" type="text" name="WU_NS2" size="30" value="'.aPOSTval('WU_NS2', $globals['WU_NS2']).'" />		
			
		</div>
		<div class="col-12 col-md-6">		
			<label for="soft_email" class="sai_head">'.__('Admin Email Address').' <span class="sai_exp">'.__('The email address to which the CRON activities and other admin related emails are sent to.').'</span></label>
			<input type="text" name="soft_email" id="soft_email" class="form-control" size="30" value="'.aPOSTval('soft_email', $globals['soft_email']).'" />
		</div>
		<div class="col-12 col-md-6">
			<label for="from_email" class="sai_head">'.__('From Email Address').' <span class="sai_exp">'.__('The email address for the FROM headers.').'</span></label>
			<input type="text" name="from_email" id="from_email" size="30" class="form-control" value="'.aPOSTval('from_email', $globals['from_email']).'" />		
			
		</div>
	</div><br/>
	<div class="text-center">
		<input type="submit" name="editconfigs" value="'.__('Update').'" class="btn btn-primary" />
	</div>
	</div>
</div>
</form>

<form accept-charset="'.$globals['charset'].'" name="webuzophpsettings" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
<div class="soft-smbox p-4 col-12 col-md-12 mx-auto mb-2">
	<div class="sai_main_head text-center">
		<i class="fas fa-cog me-2"></i>'.__('Webuzo Panel Settings').'
	</div>
	<hr>
	<div class="sai_form">
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo PHP Max Execution Time').'
			</label><br/>
			<span class="sai_exp2">'.
				__('Minimum').' : 30
			</span>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="max_execution_time" data-id="maxexecutiontime" data-val="0" onchange="enableTextBox(this);" checked value="'.$panel_settings['webuzo_php']['max_execution_time'].'" /> '.(!empty($panel_settings['webuzo_php']['max_execution_time']) ? $panel_settings['webuzo_php']['max_execution_time'] : '90').' <span>seconds</span>
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="max_execution_time" id="maxexecutiontime" data-id="maxexecutiontime" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="maxexecutiontime_text" type="number" min="30" value="90" class="form-control" disabled=""><span style="margin: 9px;">'.__('Sec').'</span>
			</div>
		</div>
	</div><br/>
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo PHP Max POST Size').'
			</label><br/>
			<span class="sai_exp2">'.
				__('Minimum').' : 128 </br>'.
				__('Maximum').' : 16384
			</span>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="post_max_size" data-id="maxpostsize" data-val="0" onchange="enableTextBox(this);" checked value="'.rtrim($panel_settings['webuzo_php']['post_max_size'], 'M').'" /> '.(!empty($panel_settings['webuzo_php']['post_max_size']) ? rtrim($panel_settings['webuzo_php']['post_max_size'], 'M') : '128').' <span>'.__('MB').'</span>
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="post_max_size" id="maxpostsize" data-id="maxpostsize" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="maxpostsize_text" type="number" min="128" max="16384" value="128" class="form-control" disabled=""><span style="margin: 9px;">'.__('MB').'</span>
			</div>
		</div>
	</div><br/>
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo PHP Max Upload Size').'
			</label><br/>
			<span class="sai_exp2">'.
				__('Minimum').' : 128 </br>'.
				__('Maximum').' : 16384
			</span><br/>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="upload_max_filesize" data-id="maxuploadsize" data-val="0" onchange="enableTextBox(this);" checked value="'.rtrim($panel_settings['webuzo_php']['upload_max_filesize'], 'M').'" /> '.(!empty($panel_settings['webuzo_php']['upload_max_filesize']) ? rtrim($panel_settings['webuzo_php']['upload_max_filesize'], 'M') : '128').' <span>'.__('MB').'</span>
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="upload_max_filesize" id="maxuploadsize" data-id="maxuploadsize" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="maxuploadsize_text" type="number" min="128" max="16384" value="128" class="form-control" disabled=""><span style="margin: 9px;">'.__('MB').'</span>
			</div>
		</div>
	</div><br/>
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo PHP Max Input Variables').'
			</label><br/>
			<span class="sai_exp2">'.
				__('Minimum').' : 1000
			</span>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="max_input_vars" data-id="maxinputvars" data-val="0" onchange="enableTextBox(this);" checked value="'.$panel_settings['webuzo_php']['max_input_vars'].'" /> '.(!empty($panel_settings['webuzo_php']['max_input_vars']) ? $panel_settings['webuzo_php']['max_input_vars'] : '1000').'
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="max_input_vars" id="maxinputvars" data-id="maxinputvars" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="maxinputvars_text" type="number" min="1000" value="1000" class="form-control" disabled="">
			</div>
		</div>
	</div><br/>
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo Service Client Max Body Size').'
			</label><br/>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="client_max_body_size" data-id="webuzo_maxpostsize" data-val="0" onchange="enableTextBox(this);" checked value="'.trim($panel_settings['webuzo_nginx']['client_max_body_size'], 'M').'" /> '.(!empty($panel_settings['webuzo_nginx']['client_max_body_size']) ? trim($panel_settings['webuzo_nginx']['client_max_body_size'], 'M') : '128').' <span>'.__('MB').'</span>
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="client_max_body_size" id="webuzo_maxpostsize" data-id="webuzo_maxpostsize" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="webuzo_maxpostsize_text" type="number" value="128" class="form-control" disabled=""><span style="margin: 9px;">'.__('MB').'</span>
			</div>
		</div>
	</div><br/>
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo PHP-FPM Max Children').'
			</label><br/>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="pm_max_children" data-id="pm_maxchildren" data-val="0" onchange="enableTextBox(this);" checked value="'.$panel_settings['webuzo_php_fpm']['pm.max_children'].'" /> '.(!empty($panel_settings['webuzo_php_fpm']['pm.max_children']) ? $panel_settings['webuzo_php_fpm']['pm.max_children'] : '10').'
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="pm_max_children" id="pm_maxchildren" data-id="pm_maxchildren" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="pm_maxchildren_text" type="number" value="10" class="form-control" disabled="">
			</div>
		</div>
	</div><br/>
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo PHP-FPM Start Servers').'
			</label><br/>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="pm_start_servers" data-id="pm_startservers" data-val="0" onchange="enableTextBox(this);" checked value="'.$panel_settings['webuzo_php_fpm']['pm.start_servers'].'" /> '.(!empty($panel_settings['webuzo_php_fpm']['pm.start_servers']) ? $panel_settings['webuzo_php_fpm']['pm.start_servers'] : '3').'
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="pm_start_servers" id="pm_startservers" data-id="pm_startservers" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="pm_startservers_text" type="number" value="3" class="form-control" disabled="">
			</div>
		</div>
	</div><br/>
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo PHP-FPM Min Spare Servers').'
			</label><br/>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="pm_min_spare_servers" data-id="pm_minspareservers" data-val="0" onchange="enableTextBox(this);" checked value="'.$panel_settings['webuzo_php_fpm']['pm.min_spare_servers'].'" /> '.(!empty($panel_settings['webuzo_php_fpm']['pm.min_spare_servers']) ? $panel_settings['webuzo_php_fpm']['pm.min_spare_servers'] : '2').'
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="pm_min_spare_servers" id="pm_minspareservers" data-id="pm_minspareservers" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="pm_minspareservers_text" type="number" value="2" class="form-control" disabled="">
			</div>
		</div>
	</div><br/>
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo PHP-FPM Max Spare Servers').'
			</label><br/>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="pm_max_spare_servers" data-id="pm_maxspareservers" data-val="0" onchange="enableTextBox(this);" checked value="'.$panel_settings['webuzo_php_fpm']['pm.max_spare_servers'].'" /> '.(!empty($panel_settings['webuzo_php_fpm']['pm.max_spare_servers']) ? $panel_settings['webuzo_php_fpm']['pm.max_spare_servers'] : '4').'
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="pm_max_spare_servers" id="pm_maxspareservers" data-id="pm_maxspareservers" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="pm_maxspareservers_text" type="number" value="4" class="form-control" disabled="">
			</div>
		</div>
	</div><br/>
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo PHP-FPM Max Request').'
			</label><br/>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="pm_max_requests" data-id="pm_maxrequests" data-val="0" onchange="enableTextBox(this);" checked value="'.$panel_settings['webuzo_php_fpm']['pm.max_requests'].'" /> '.(!empty($panel_settings['webuzo_php_fpm']['pm.max_requests']) ? $panel_settings['webuzo_php_fpm']['pm.max_requests'] : '500').'
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="pm_max_requests" id="pm_maxrequests" data-id="pm_maxrequests" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="pm_maxrequests_text" type="number" value="500" class="form-control" disabled="">
			</div>
		</div>
	</div><br/>
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo PHP-FPM Request Terminate Timeout').'
			</label><br/>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="request_terminate_timeout" data-id="req_ter_timeout" data-val="0" onchange="enableTextBox(this);" checked value="'.rtrim($panel_settings['webuzo_php_fpm']['request_terminate_timeout'], 'M').'" /> '.(!empty($panel_settings['webuzo_php_fpm']['request_terminate_timeout']) ? rtrim($panel_settings['webuzo_php_fpm']['request_terminate_timeout'], 's') : '120').' <span>seconds</span>
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="request_terminate_timeout" id="req_ter_timeout" data-id="req_ter_timeout" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="req_ter_timeout_text" type="number" value="120" class="form-control" disabled=""><span style="margin: 9px;">'.__('Sec').'</span>
			</div>
		</div>
	</div><br/>
	
	<div class="row">
		<div class="col-12 col-lg-8 col-md-6 col-sm-12">
			<label class="sai_head">'.
				__('Webuzo PHP Memory Limit').'
			</label><br/>
			<span class="sai_exp2">'.
				__('Minimum').' : 128
			</span>
		</div>
		<div class="col-12 col-lg-4 col-md-6 col-sm-12">
			<label>
				<input type="radio" name="memory_limit" data-id="memorylimit" data-val="0" onchange="enableTextBox(this);" checked value="'.rtrim($panel_settings['webuzo_php']['memory_limit'], 'M').'" /> '.(!empty($panel_settings['webuzo_php']['memory_limit']) ? rtrim($panel_settings['webuzo_php']['memory_limit'], 'M') : '128').' <span>'.__('MB').'</span>
			</label>
			<div class="input-group">
				<div class="input-group-addon">
					<div class="input-group-text-custom">
						<input type="radio" name="memory_limit" id="memorylimit" data-id="memorylimit" data-val="1" onchange="enableTextBox(this);">
					</div>
				</div>
				<input id="memorylimit_text" type="number" min="128" max="16384" value="128" class="form-control" disabled=""><span style="margin: 9px;">'.__('MB').'</span>
			</div>
		</div>
	</div><br/>
	
	<div class="text-center">
		<input type="submit" name="webuzophpsettings" value="'.__('Update').'" class="btn btn-primary" />
	</div>
	</div>
</div>
</form>

<form accept-charset="'.$globals['charset'].'" name="webuzo_ports" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1>
<div class="soft-smbox p-4 col-12 col-md-12 mx-auto">
	<div class="sai_main_head text-center">
		<i class="fas fa-cog me-2"></i>'.__('Webuzo Panel Ports').'
	</div>
	<hr>
	<div class="sai_form">
	<div class="row mb-3">
		<div class="col-12 col-md-6">
			<label for="redirect_ssl_ports" class="sai_head">'.__('Redirect to SSL Ports').'
				<span class="sai_exp">'.__('If enabled the Webuzo panel will be redirected to SSL Ports').'</span>
			</label>
			<input type="checkbox" name="redirect_ssl_ports" id="redirect_ssl_ports" '.POSTchecked('redirect_ssl_ports', @$globals['redirect_ssl_ports']).' />
		</div>
		<div class="col-12 col-md-6">
			<label for="redirect_hostname" class="sai_head">'.__('Redirect to Hostname').'
				<span class="sai_exp">'.__('If enabled the Webuzo panel will be redirected to hostname').'</span>
			</label>
			<input type="checkbox" name="redirect_hostname" id="redirect_hostname" '.POSTchecked('redirect_hostname', @$globals['redirect_hostname']).' />
		</div>
	</div>
	<div class="row mb-3">
		<div class="col-12 col-md-6">
			<label for="disable_panel_alias" class="sai_head">'.__('Disable Panel Alias').'
				<span class="sai_exp">'.__('If panel aliases are disabled, you can not access the panel using domain.com/cpanel or domain.com/webuzo.').'</span>
			</label>
			<input type="checkbox" name="disable_panel_alias" id="disable_panel_alias" '.POSTchecked('disable_panel_alias', @$globals['disable_panel_alias']).' />
		</div>
	</div>

		
	<div class="row">
		<div class="col-12 col-md-6 mb-3">		
			<label for="port" class="sai_head">'.__('Custom Admin Port SSL').'
				<span class="sai_exp">'.__('Set Custom Admin Port SSL (Default: 2005 is included by default)').'</span>
			</label>
			<input type="number" name="admin_port_ssl" class="form-control" value="'.aPOSTval('admin_port_ssl', $globals['admin_port_ssl']).'" />
		</div>
		<div class="col-12 col-md-6">
			<label for="port" class="sai_head">'.__('Admin Port Non SSL').'
				<span class="sai_exp">'.__('Set Custom Admin Port Non SSL (Default: 2004 is included by default)').'</span>
			</label>
			<input type="number" name="admin_port_nonssl" class="form-control" value="'.aPOSTval('admin_port_nonssl', $globals['admin_port_nonssl']).'" />
		</div>
		<div class="col-12 col-md-6 mb-3">		
			<label for="port" class="sai_head">'.__('Enduser Port SSL').'
				<span class="sai_exp">'.__('Set Custom Enduser Port SSL (Default: 2003 is included by default)').'</span>
			</label>
			<input type="number" name="enduser_port_ssl" class="form-control" value="'.aPOSTval('enduser_port_ssl', $globals['enduser_port_ssl']).'" />
		</div>
		<div class="col-12 col-md-6 mb-3">
			<label for="port" class="sai_head">'.__('Enduser Port Non SSL').'
				<span class="sai_exp">'.__('Set Custom Enduser Port Non SSL (Default: 2002 is included by default)').'</span>
			</label>
			<input type="number" name="enduser_port_nonssl" class="form-control" value="'.aPOSTval('enduser_port_nonssl', $globals['enduser_port_nonssl']).'" />
		</div>
		<div class="col-12 col-md-6 ">		
			<label for="port" class="sai_head">'.__('Enduser Panel Alias').'
				<span class="sai_exp">'.__('Set multiple aliases by comma-separated to access the end-user panel as domain.com/webuzo, etc.').'</span>
			</label>
			<input type="text" name="vh_panel_alias" class="form-control" value="'.aPOSTval('vh_panel_alias', implode(', ', $globals['vh_panel_alias'])).'" />
		</div>
		<div class="col-12 col-md-6">
			<label for="port" class="sai_head">'.__('Admin Panel Alias').'
				<span class="sai_exp">'.__('Set multiple aliases by comma-separated to access the admin panel as domain.com/whm, etc.').'</span>
			</label>
			<input type="text" name="vh_panel_alias_admin" class="form-control" value="'.aPOSTval('vh_panel_alias_admin', implode(', ', $globals['vh_panel_alias_admin'])).'" />
		</div>
	</div><br/>
	<div class="text-center">
		<input type="submit" name="webuzo_ports" value="'.__('Update').'" class="btn btn-primary" />
	</div>
	</div>
</div>
</form>

<script>
$(document).ready(function(){
	var f = function(){
		var type = window.location.hash.substr(1);
		if(type == "hostname"){
			$("#priamry_domain").focus();
		}
	}
	
	f();
	
	$(window).on("hashchange", f);
});

function enableTextBox(curentEle) {		
	var jEle = $(curentEle).parent().children().first();
	var v = $(curentEle).val();		
	var did = jEle.data("id")+"_text";
	var val = jEle.data("val");
	var txt = $("#"+did);
	if(val) {
		txt.removeAttr("disabled");
		txt.select();
	}else{
		txt.attr("disabled", "disabled"); 
	}
	var id = jEle.attr("id")+"_text";
	$(txt).on("focusout select keyup change click", function() {	
		var id = jEle.attr("id");
		$("#"+id).val(txt.val());
	});
}
</script>';

	softfooter();

}
