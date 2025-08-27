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

function editip_theme(){	

global $theme, $globals, $cluster, $servers, $user, $error, $ippools, $ip, $ipid, $done;

	softheader(__('Admin Panel'));

	echo '
<div class="soft-smbox p-3 col-12 col-md-10 col-lg-6 mx-auto">
	<div class="sai_main_head text-dark">
		<i class="fas fa-edit fa-xl me-1"></i> '.__('Edit IP').'	
	</div>
</div>
<div class="soft-smbox p-4 col-12 col-md-10 col-lg-6 mx-auto mt-4">';

	error_handle($error);
	
	echo '
	<form accept-charset="'.$globals['charset'].'" class="row" name="editip" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-doneredirect="'.$globals['admin_index'].'act=ips">
		<label class="form-label">'.__('Enter IP').'
			<span class="sai_exp">'.__('Enter single IPs or create a IP Range').'</span>
		</label>
		<input type="text" class="form-control mb-3" name="newip"  size="30" '.(empty($ip['vpsid']) ? ' value="'.POSTval('ip' , $ip['ip']).'"' : 'value="'.$ip['ip'].'" disabled="disabled" ').'/>
		
		<label class="form-label">'.__('Netmask').'
		</label>
		<input type="text" class="form-control mb-3" name="netmask"  size="30" value="'.POSTval('netmask' , $ip['netmask']).'" />
		
		<label class="form-label d-block mb-3 col-md-6">
			'.__('Lock IP').'
			'.get_checkbox('locked', '', '', array('chk_it' => 1, 'val' => $ip['locked'])).'	
		</label>
		<label class="form-label d-block mb-3 col-md-2">'.__('Status').'
			<span class="sai_exp">'.__('If disabled IP will not be added to the system.').'</span>
		</label>
		<div class="col-md-4">
			<label class="switch">				
				<input type="checkbox" name="status" value="1" '.POSTchecked('status', empty($ip['inactive_ip'])).' />
				<span class="slider"></span>
			</label>
		</div>';
		
		if(!in_array($ip['ip'], array($globals['WU_PRIMARY_IP'], $globals['WU_PRIMARY_IPV6']))){
			echo '
			<label class="form-label">'.__('Type').'</label>
			<select name="shared" class="form-select">
				<option value="0" '.POSTselect('shared', 0, empty($ip['shared'])).'>'.__('Dedicated').'</option>
				<option value="1" '.POSTselect('shared', 1, !empty($ip['shared'])).'>'.__('Shared').'</option>
			</select>';
		}
		
		echo '<label class="form-label">'.__('Note').'
			<sapn class="sai_exp ms-1">'.__('Any notes for your reference').'</span>	
		</label>
		<textarea class="form-control mb-3" name="note" rows="4">'.POSTval('note', $ip['note']).'</textarea>
		<label class="form-label">'.__('Users').'
			<span class="sai_exp">'.__('Users assign to this IP').'</span> :
		</label>
		<span class="ms-3">'.(empty($ip['users']) ? __('None') : implode(', ', $ip['users'])).'</span>
		<span class="help-block"></span>
		<div class="text-center">
			<input type="submit" name="editip" class="btn btn-primary" value="'.__('Edit').'"/>
		</div>
	</form>
</div>';

	softfooter();

}

