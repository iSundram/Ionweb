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

function login_logs_theme(){
	
global $user, $globals, $theme, $softpanel, $WE, $error, $logs, $done;

	softheader(__('Login Logs'));
	
	echo '
<div class="card soft-card p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'login_logs.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Login Logs').'</h5>
		<input type="submit" value="'.__('Delete All Records').'" name="delete" id="delete" class="btn btn-danger float-end" onclick="delete_record(this)" data-delete_all="1"  />
	</div>
</div>
<div class="card soft-card p-4 mt-4">
	<div id="show_log">
		<div id="display_tab" class="table-responsive">
			<table class="table align-middle table-nowrap mb-0 webuzo-table">
				<thead class="sai_head2">  
					<tr>
						<th>'.__('Date').'</th>
						<th>'.__('User').'</th>
						<th>'.__('IP Address').'</th>
						<th width="10%">'.__('Status').'</th>			
					</tr>
				</thead>
				<tbody>';
			
	page_links();
	
	if(!empty($logs)){
		foreach ($logs as $key => $value){
			echo '
					<tr id="tr'.$key.'">
						<td>'.datify($value['time'], 1, 1, 0).'</td>
						<td>'.$value['user'].'</td>
						<td>'.$value['ip'].'</td>			
						<td>'.(empty($value['status']) ? '<font color="#FF0000">'.__('Failed').'</font>' : '<font color="#009900">'.($value['status'] == 2 ? '2FA -' : '').__('Successful').'</font>').'</td>
					</tr>';
		}
	}else{
		echo '
					<tr class="text-center">
						<td colspan=4>
							<span>'.__('There are no login logs as of now').'</span>
						</td>
					</tr>';
	}
	
	echo '			</tbody>
			</table>
		</div>
	</div>
</div>';
		
	softfooter();
}

