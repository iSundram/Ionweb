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

function domainedit_theme(){
	
global $user, $globals, $theme, $softpanel, $WE, $error, $done, $domains_list, $primary_domain, $domain, $domain_type, $ips;

	softheader(__('Edit Domain'));
	
	echo '
<div class="col-12 col-lg-10 mx-auto card soft-card p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'domains.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Edit Domain').'</h5>
	</div>
</div>
<div class="col-12 col-lg-10 mx-auto card soft-card p-4 mt-4">';
	
	error_handle($error, '100%');

	echo '
	<form accept-charset="'.$globals['charset'].'" action="" method="post" name="editform" id="editform" class="form-horizontal" onsubmit="return submitit(this);" data-doneredirect="'.$globals['index'].'act=domainmanage">
	
	<div class="row">
		<div class="col-12 col-lg-6">
			<div class="mb-4 px-3">
				<label for="domain_type" class="sai_head d-inline-block">'.__('Domain Type').'</label>
				<span class="sai_exp">'.__('Choose the type of domain you want to add').'</span>
				<select name="domain_type" id="domain_type" class="form-select" disabled="disabled">
					<option value="'.$domain_type.'">'.__('$0', [ucfirst($domain_type)]).'</option>
				</select>
			</div>
			<div class="addon parked mb-4 px-3">
				<label for="domain" class="sai_head d-block">'.__('Domain').'</label>
				<input type="text" id="domain" name="domain" class="form-control" disabled="disabled" value="'.$domain.'"/>
			</div>';
			
			if($domain_type == 'subdomain' || $domain_type == 'addon'){
				
				echo '
			<div class="addon subdomain mb-4 px-3">
				<label for="domainpath" class="sai_head d-block">'.__('Domain Path').'</label>
				<div class="input-group">
					<span class="input-group-text" >'.$WE->user['homedir'].'/'.'</span>
					<input type="text" id="domainpath" name="domainpath" class="form-control" value="'.trim(POSTval('domainpath', (substr($domains_list[$domain]['path'], strlen($WE->user['homedir'].'/')) != 'public_html' ? substr($domains_list[$domain]['path'], strlen($WE->user['homedir'].'/')) : 'www').'/'), '/').'" onfocus=""/>
				</div>
			</div>';
			
			}
			
			if($domain_type == 'parked' || $domain_type == 'addon'){
				
				echo '
			<div class="addon parked mb-4 px-3">
				<input type="checkbox" id="wildcard" name="wildcard" '.POSTchecked('wildcard', $domains_list[$domain]['wildcard']).'" />
				<label for="wildcard" class="sai_head d-inline-block">'.__('Allow Wildcard').'	</label>
				<span class="sai_exp2 d-block">'.__('Tick to allow *.domain.com i.e. all subdomains to the domain folder').'</span>
			</div>';
			
			}
			
			echo '
		</div>
		<div class="col-12 col-lg-6">
			<div class="mb-4 px-3">
				<input type="checkbox" id="issue_lecert" name="issue_lecert" '.POSTchecked('issue_lecert', false).'" />
				<label for="issue_lecert" class="sai_head d-inline-block">'.__('Issue Let\'s Encrypt certificate').'</label>
				<span class="sai_exp2 d-block">'.__('Tick to issue SSL certificate from LE for the new domain').'</span>
			</div>';
			
			// Do we have IPs ?
			if(!empty($ips) && (count($ips[4]) > 1 || count($ips[6]) > 1)){
				
				echo '
			<div class="mb-4 px-3">
				<label for="ip" class="sai_head">'.__('IP Address').'</label>
				<span class="sai_exp">'.__('Can be IPv4 or IPv6').'</span>
				<select name="ip" id="ip" class="form-select" onchange="handle_ipv6(this)">
					<option value="">'.__('Default').'</option>';
				foreach ($ips['all'] as $k => $v){
					echo '<option value="'.$k.'" '.POSTselect('ip', $k, ($domains_list[$domain]['ip'] == $k)).' type="'.$v['type'].'">'.$k.'</option>';
				}
				echo '
				</select>
			</div>';
			
				// Do we have any IPv6 ?
				if(!empty($ips[6])){
					
					echo '
			<div class="ipv6 mb-4 px-3">
				<label for="ipv6" class="sai_head">'.__('Select IPv6').'</label>
				<span class="sai_exp">'.__('You can choose an IPv6 for your domain as well').'</span>
				<select name="ipv6" id="ipv6" class="form-select">
					<option value="">'.__('Default').'</option>';
				foreach ($ips[6] as $k => $v){
					echo '<option value="'.$v.'" '.POSTselect('ipv6', $v, ($v == $domains_list[$domain]['ipv6']) ).'>'.$v.'</option>';
				}
				echo '
					<option value="none" '.POSTselect('ipv6', 'none', ('none' == $domains_list[$domain]['ipv6']) ).'>'.__('None').'</option>
				</select>
			</div>';
					
				}
				
			}
			
			echo '
		</div>
	</div>
	<div class="my-4 px-3 text-center">
		<input type="submit" name="edit" class="flat-butt me-2" value="'.__('Edit Domain').'" />
	</div>
	</form>
</div>
	
<script language="javascript" type="text/javascript">

function handle_ipv6(ele){
	var type = $(ele).find(":selected").attr("type");
	
	if(type == 6){
		$(".ipv6").hide();
		$("#ipv6").prop("disabled", true);
	}else{
		$(".ipv6").show();
		$("#ipv6").prop("disabled", false);
	}
}

$(document).ready(function(){
	$("#ip").change();
});

// ]]></script>';

	softfooter();
	
}


