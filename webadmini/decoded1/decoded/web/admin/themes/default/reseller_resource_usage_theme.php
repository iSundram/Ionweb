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
function reseller_resource_usage_theme(){

global $globals, $softpanel, $theme, $error, $done, $SESS, $resource_total, $reseller_privileges_fields;
	
	softheader(__('Reseller Resource Usage'));

	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
			<i class="fas fa-th-list fa-xl me-2"></i>'.__(' Reseller Resource Usage').'
	</div>
</div>
<div class="soft-smbox p-3 mt-4">';
	
	if($SESS['user'] == 'root'){
	
		echo '
		<div class="sai_sub_head mb-4 position-relative row">
			<div class="col-md-3">
				'.__('Select Reseller').' : 
			</div>
			
			<div class="col-md-5">
				<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="resellers" style="width: 100%" id="user_search" name="user_search">
					<option value="'.optGET('user').'" selected="selected">'.optGET('user').'</option>
				</select>
			</div>
		</div>';
		
	}
	
	error_handle($error);
	
	if(!empty($resource_total)){
	
		echo '		
		<div class="table-responsive mt-4">
			<table class="table sai_form webuzo-table">				
			<tbody>
			<tr align="center">
				<th  width="30%" >'.__('Resource').'</th>
				<th  width="10%">'.__('Limit').'</th>
				<th  width="10%">'.__('Used').'</th>
				<th  width="10%">'.__('Allocated').'</th>
				<th  width="40%">'.__('Information').'</th>
			</tr>';
				
			foreach($resource_total as $k => $v){
				
				if(empty($reseller_privileges_fields['Total Limits']['list'][$v['key']]['heading'])){
					continue;
				}
				
				$units = empty($reseller_privileges_fields['Total Limits']['list'][$v['key']]['units']) ? '' : ' '.$reseller_privileges_fields['Total Limits']['list'][$v['key']]['units'];
				
				echo '
			<tr>
				<td rowspan="2">'.$reseller_privileges_fields['Total Limits']['list'][$v['key']]['heading'].'</td>
				<td align="center">'.$resource_total[$k]['limit'].$units.'</td>  
				<td align="center">'.$resource_total[$k]['used'].$units.'</td>
				<td align="center">'.$resource_total[$k]['allocated'].$units.'</td>
				<td rowspan="2">'.$reseller_privileges_fields['Total Limits']['list'][$v['key']]['exp'].'</td>						
			</tr>
			<tr>
				<td colspan="3">
					<div class="progress disk-bar " style="height: 10px;">
						<div style="cursor:pointer;width:'.$resource_total[$k]['percent'].'%;" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress-bar '.($resource_total[$k]['percent'] >= 90 ? "bg-danger" : ($resource_total[$k]['percent'] >= 80 ? "bg-warning" : "prog-blue")).' progress-bar-striped progress-bar-animated" data-placement="right" data-toggle="tooltip">
						</div>
					</div>
				</td>
			</tr>';
				
			}
			
			echo'
			</tbody>
			</table>
		</div>';
		
	}
	
	echo'
</div>

<script>
$("#user_search").on("select2:select", function(e, u = {}){	
	let user;
	if("user" in u){
		user = u.user;
	}else{
		user = $("#user_search option:selected").val();
	}
	window.location = "'.$globals['index'].'act=reseller_resource_usage&user="+user;
});
</script>';

	softfooter();
	
}

