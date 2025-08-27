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

function hotlink_protect_theme(){
	
global $theme, $globals, $error, $softpanel , $user, $done, $hotlink_protect_status, $preHotlinkValues, $WE;

	$hotlink_protect_status = !empty($WE->user['hotlink_protect']) ? 1 : 0;
	
	softheader(__('Hotlink Protection'));

	$urls = getRandomUrl();
	$urls = (!empty($preHotlinkValues['urls'])) ? implode(" \n", $preHotlinkValues['urls']) :implode(" \n", $urls);
	$extensionlist = (!empty($preHotlinkValues['fileformats'])) ? $preHotlinkValues['fileformats'] : "jpg,jpeg,gif,png,bmp";


	$current_status = $hotlink_protect_status == 1 ? __('Hotlink protection is currently enabled') : __('Hotlink protection is currently disabled');

	$btnValue = ($hotlink_protect_status == 1) ? __('Disable') : __('Enable');

	echo '
<div class="card soft-card p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'hotlink.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Hotlink Protection').'</h5>
	</div>
</div>
<div class="card soft-card p-4 mt-4">
	<div class="alert alert-info p-2">
		<label class="me-3">
			<i class="fas fa-info-circle me-2"></i>'.$current_status.'
		</label>
		<input type="button" class="flat-butt" onclick=\'return submitform(this);\' data-donereload="1" value="';
		echo $btnValue;
		echo '" data-status="'.($hotlink_protect_status == 1 ? 'enable' : 'disable').'"/>
	</div>
	<form accept-charset="'.$globals['charset'].'" action=" " method="post" name="hotlinkprotecturl" id="hotlinkprotecturl" data-donereload="1" class="form-horizontal" onsubmit="return submitit(this)">
		<div class="row mt-4">
			<div class="col-12 col-lg-6">	
				<label class="sai_head me-1" for="urllist">'.__('Configure Hotlink Protection').'</label>
				<i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('URLs to allow access').'"></i>
				<textarea name="urllist" id="urllist" class="form-control" rows="12">'.$urls.'</textarea>				
			</div>
			<div class="col-12 col-lg-6">	
				<label class="sai_head" for="extensionlist">'.__('Block direct access for the following extensions (comma-separated)').'</label>
				<input type="text" name="extensionlist" id="extensionlist" class="form-control mb-3" placeholder=".png,.jpb,etc" value="'.POSTval('extensionlist', $extensionlist).'">
				<label class="sai_head d-inline-block" for="directaccess">
					<input class="me-1" type="checkbox" id="directaccess" name="directaccess" '.POSTchecked('directaccess', $preHotlinkValues['allowDirect']).'>'.__('Allow direct requests (for example, when you enter the URL of an image in a browser)').'
				</label>
				<span class="d-block mb-3" >'.__('NOTE: You must select the "Allow direct requests" checkbox when you use hotlink protection for files that you want visitors to view in QuickTime (for example, Mac Users)').'</span>	
				<label class="sai_head"  for="redirecturl">'.__('Redirect the request to the following URL').'</label>
				<input type="text" name="redirecturl" id="redirecturl" class="form-control" value="'.POSTval('redirecturl', $preHotlinkValues['redirecturl']).'" >
			</div>
		</div>
		<div class="text-center mt-4 mb-3">
			<input type="submit" value="'.__('Submit').'" name="hotlink_enable" class="flat-butt" id="create_hotlink"/>
			<img id="createhotlink" src="'.$theme['images'].'progress.gif" style="display:none">
		</div>
	</form>
</div>
<script>

function submitform(ele){
	var jEle = $(ele);
	var d = jEle.data();
	if(d.status === "disable"){
		d = $("#hotlinkprotecturl")[0];
	}else{
		d.hotlink_disable = 1;
	}
	
	submitit(d, {
		done_reload: window.location
	});
}

</script>';

	softfooter();
}

function getRandomUrl(){
	
global $softpanel, $WE;
	
	$domains_list = $WE->domains();
	$primary_domain = $WE->getPrimaryDomain();
	
	if(empty($primary_domain)){
		return [];
	}
	
	$urls = make_random($primary_domain);
	
	foreach ($domains_list as $dom => $opt) {
		$urls = make_random($dom, $urls);
	}
	
	return $urls;
	// echo "<pre>";print_r($urls);exit;
}

function make_random($domain, $arr = []){

	$arr[] = 'http://'.$domain;
	$arr[] = 'https://'.$domain;
	$arr[] = 'http://aliases.'.$domain;
	$arr[] = 'https://aliases.'.$domain;

	return $arr;
}
