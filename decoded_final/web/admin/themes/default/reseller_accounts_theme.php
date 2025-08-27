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

function reseller_accounts_theme(){

global $globals, $theme, $softpanel, $resellers;	

	softheader(__('Show Reseller Accounts'));
	
	echo '
	<style>
		td{
			border-bottom-color: lightgrey !important;
		}
	</style>
	
	<div class="soft-smbox p-3">
		<div class="sai_main_head">
			<i class="fas fa-th-list fa-xl me-2"></i>'.__('Show Reseller Accounts').'
		</div>
	</div>
	<div class="soft-smbox p-3 mt-4">';
		
		foreach($resellers as $owner => $value){
			echo '
			<div class="table-responsive">
				<table class="table sai_form webuzo-table">
				<div class="soft-smbox table-responsive sai_form_head mt-4" style="text-align:center">
					'.__('Owner').' : '.$owner.'
				</div>
				<thead>
					<tr>
						<th width="15%">'.__('Users').'</th>
						<th width="15%">'.__('Email').'</th>
						<th width="15%">'.__('Domain').'</th>
						<th width="15%">'.__('IP Address').'</th>
						<th width="15%">'.__('Type').'</th>
						<th width="15%">'.__('Owner').'</th>
						<th width="10%">'.__('Plan').'</th>
					</tr>
				</thead>';
								
			$count=0;
			foreach($value as $k => $v){
				echo '
				<tbody>	
					<tr>
						<td>'.$v['user'].'</td>
						<td>'.$v['email'].'</td>
						<td>'.$v['domain'].'</td>
						<td>'.$v['ip'].'</td>
						<td>'.($v['type'] == 1 ? __('User') : __('Reseller')).'</td>
						<td>'.$v['owner'].'</td>
						<td>'.$v['plan'].'</td>
					</tr>';
				$count++;
			}
			echo '<tr><td colspan=7><b>'.$count.' '.($count == 1 ? 'Account' : 'Accounts').'</b></td></tr>';
		}
			
			echo '
				</tbody>
			</table>
		</div>	
	</div>';
		
	softfooter();
	
}