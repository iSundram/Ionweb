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

function service_manager_theme(){

global $user, $globals, $theme, $softpanel, $error, $saved, $iapps, $apps;

	softheader(__('Default Apps'));

	$proxy = $globals['WU_PROXY_PORT'];
	//check if varnish is enabled
	$varnish_port = $globals['VARNISH_PORT'];
	
	// Array of webservers | Apache | NGINX | Lighttpd | Apache2
	foreach($softpanel->wgrp as $webkey => $webvalue){
		if(!empty($iapps[$webkey.'_1'])) $tmp_server[$webkey] = appinfo($webkey);
	}

	echo '
<div class="soft-smbox p-3 col-12 col-lg-8 mx-auto">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'service_manager.png" class="webu_head_img me-2" />'.__('Default Apps').'
	</div>
</div>
<form accept-charset="'.$globals['charset'].'" method="post" action="" class="form-horizontal" name="service_manager" id="editform" onsubmit="return submitit(this)" data-donereload="1">
<div class="soft-smbox col-12 col-lg-8 mx-auto p-3 mt-4">
	<div class="sai_sub_head">
		<img src="'.$theme['images'].'webserver.gif" class="webu_head_img me-2" />&nbsp;'.__('Web Server').'
	</div>
	<hr>';
			
	if(count($tmp_server) > 0 && ((empty($proxy)) && empty($varnish_port) )){
	
		foreach($tmp_server as $key => $value){
		
			echo '
		<div class="row">
			<div class="col-6">
				<label class="sai_head">'.$apps[$key]['name'].'</label>
			</div>
			<div class="col-6">
				<input type="radio" name="webserver" value='.$value['set_default'].' '.POSTradio('webserver', $value['set_default'], $globals['WU_DEFAULT_SERVER']).' />
			</div>
		</div>';
		
		}
	
	}else{
		
		$no_web_server = 1;
		echo '
		<div class="alert alert-warning text-center" style = "font-size: 15px">
			<i class="fa fa-info-circle fa-1x" style="color:#8a6d3b;vertical-align: middle;"></i>&nbsp;&nbsp;'.((empty($proxy) && empty($varnish_port)) ? __('You have No Webserver installed.') : (empty($proxy) ? __('Disable Varnish to change WebServer') :  __('You have Nginx Proxy enabled on your server. Please disable it.'))).'
		</div>';
	}
	
	echo '
</div>
<!--end of first bg class-->

	<form accept-charset="'.$globals['charset'].'" method="post" action="" class="form-horizontal">
	<div class="soft-smbox p-3 col-12 col-lg-8 mx-auto p-3 mt-4">
		<div class="sai_sub_head">
			<img src="'.$theme['images'].'php_conf.png" class="webu_head_img me-2" />'.__('Default PHP').'
		</div>
		<hr>';
				
	foreach($softpanel->phpgrp as $phpkey => $phpvalue){
		if(!empty($iapps[$phpkey.'_1'])) continue;
		unset($softpanel->phpgrp[$phpkey]);
	}
				
	if(count($softpanel->phpgrp) > 0 ){
	
		foreach($softpanel->phpgrp as $key => $value){
			echo '
		<div class="row">
			<div class="col-6">
				<label class="sai_head">'.$apps[$key]['name'].'</label>
			</div>
			<div class="col-6">
				<input type="radio" name="default_php" value='.$apps[$key]['softname'].' '.POSTradio('default_php', $apps[$key]['softname'], $globals['WU_DEFAULT_PHP']).' />
			</div>
		</div>';
		}
		
	}else{
		
		$no_php = 1;
		
		echo '
		<div class="row">
			<div class="alert alert-warning">
				'.__('You have No PHP installed.').'
			</div>
		</div>';
	}

	if(empty($no_web_server) || empty($no_php)){
	
		echo '
		<div class="text-center my-3">
			<input type="submit" style="cursor:pointer" value="'.__('Edit Settings').'" class="btn btn-primary" name="service_manager" id="submitservice" />
		</div>';
				
	}
	
	// Display a NOTE if NGINX is installed
	if(!empty($iapps['18_1'])){
		echo '<br/>
		<div class="row">
			<p align="center">			
			<div class="sai_notice text-center" style="text-decoration:none;">'.__('$0 Note: $1 You will have to handle your .htaccess file in case of Scripts while using Nginx.', ['<b>', '</b>']).'</div>
			</p>
		</div>';
	}
	
	echo '
	</form>
</div><!--end of second bg class-->
</form>';

	softfooter();
	
}

