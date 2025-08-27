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

function trace_route_theme(){

global $theme, $globals, $user, $langs, $skins, $error, $done, $binpath1, $binpath2, $is_traceintsall;

	softheader(__('Trace route'));

	error_handle($error);

	echo '
<div class="soft-smbox col-12 col-md-11 p-3 mx-auto">
	<div class="sai_main_head">
			<i class="fa fa-sitemap fa-lg me-2"></i>'.__('Traceroute Enable/Disable').'
	</div>
</div>
<form accept-charset="'.$globals['charset'].'" name="trace_route_action" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1>
<div class="soft-smbox col-12 col-md-11 p-4 mx-auto mt-4">';

	if(!empty($is_traceintsall) && !empty($globals['trace_route_enabled'])){	
		echo '	
	<div class="alert alert-info d-flex justify-content-center" role="alert">
		'.__('Traceroute is currently enabled.').'
	</div>';
	}else{
		echo '	
	<div class="alert alert-danger d-flex justify-content-center" role="alert">
		'.__('Traceroute is currently disabled.').'
	</div>';
	}
	
	echo '
	<div class="sai_form">';
	if(!empty($is_traceintsall)){		
		echo '
		<div class="row">
			<table class="table sai_form webuzo-table" id="traceroute_table">
				<thead>	
					<tr>
						<th width="50%">'.__('Binary').'</th>
						<th width="50%">'.__('Permissions').'</th>
					</tr>
				</thead>
				<tbody>
					<tr id="">
						<td>
							'.$binpath1.'
						</td>
						<td>
							'.($globals['trace_route_enabled'] == 1 ? '755' : '700').'
						</td>
					</tr>
					<tr id="">
						<td>
							'.$binpath2.'
						</td>
						<td>
							'.($globals['trace_route_enabled'] == 1 ? '755' : '700').'
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="text-center">
			<input type="submit" name="trace_route_action" class="btn btn-primary" value="'.($globals['trace_route_enabled'] == 1 ? __('Disable') : __('Enable')).'"/>
		</div>';
	}else{
		echo '
		<div class="text-center">
			<p>'.__('Trace Route not installed.').'</p>
		</div>
		<div class="text-center">
			<input type="submit" name="trace_route_action" class="btn btn-primary" value="'.__('Install').'"/>
		</div>';
	}
	echo '
	</div>
</div>
</form>';

	softfooter();

}

