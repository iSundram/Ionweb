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


function hostname_a_theme(){

global $user, $globals, $theme, $softpanel, $SESS, $error, $done, $storage, $ip;

	softheader(__('Add A Record for Hostanme'));

	echo '
<div class="soft-smbox p-3 col-12 col-md-10 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-hdd fa-xl me-1"></i> '.__('Add A Record for Hostname').'
	</div>
</div>
<div class="soft-smbox p-3 col-12 col-md-10 mx-auto mt-4">';
	error_handle($error);

	echo '
	<div id="form-container">
		<div id="showplanlist" class="table-responsive mt-4">
			<table class="table sai_form webuzo-table">
				<thead>
					<tr>
						<th>'.__('Hostname').'</th>
						<th>'.__('Server IP').'</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>'.$globals['WU_PRIMARY_DOMAIN'].'</td>					
						<td>'.(!empty($ip) ? $ip : __('A Entry does not exist !')).'</td>					
					</tr>
				</tbody>
			</table>
		</div>
		<form accept-charset="'.$globals['charset'].'" id="a_record" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
			<div class="row">
				<div class="col-lg-8 col-sm-8  col-md-8 col-xs-12">
					<input class="form-control" type="text" name="ip" value="'.$ip.'"/>		
				</div>
				<div class="col-lg-4 col-sm-4 col-md-8 col-xs-12 text-center mb-3 p-1">
					<input type="submit" name="a_record" class="btn btn-primary" value="'.__('Add Entry').'" />
				</div>
			</div>
		</form>
	</div>
</div>';

softfooter();

}

