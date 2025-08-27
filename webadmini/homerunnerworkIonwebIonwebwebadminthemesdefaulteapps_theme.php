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

function eapps_theme(){

global $theme, $globals, $user, $error, $updated, $info, $eapps_list, $done, $softpanel, $ports_in_use, $apps;

	softheader(__('Enduser Apps'));
	
	echo '
<style>
.apptype {
    font-size: 10px;
    color: #515151;
}
</style>
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fa fa-th-large fa-xl me-2"></i>&nbsp;'.__('Enduser Apps').'
	</div>
</div>

<div class="soft-smbox p-4 mt-4">
	<div class="table-responsive"> 
		<table border="0" cellpadding="8" cellspacing="1" class="table webuzo-table td_font">
			<thead>
			<tr>
				<th width="10%">'.__('User').'</th>
				<th width="10%">'.__('Application Name').'</th>
				<th width="10%">'.__('Base URL').'</th>
				<th width="10%">'.__('Port').'</th>
				<th width="10%">'.__('Application type').'</th>
				<th width="10%">'.__('Enable/Disable').'</th>
				<th width="5%" class="text-center">'.__('Status').'</th>
				<th width="5%" class="text-center">'.__('Start/Stop').'</th>
				<th width="5%" class="text-center">'.__('Delete').'</th>
			</tr>
			</thead>
			<tbody>';
			if(empty($eapps_list)){
				
				echo '
				<tr>
					<td class="text-center" colspan=9>'.__('No application exist').'</td>
				</tr>';
				
			}else{
			
			foreach ($eapps_list as $key => $value){
			
				$appid = get_app_record($value['app_type']);
				
				echo '
			<tr>
				<td class="sai_head service_name">'.$value['user'].'</td>
				<td class="sai_head service_name">'.$value['name'].'</td>
				<td class="sai_head service_name"><a target="_blank" href="http://'.(!empty($value['base_url']) ? $value['base_url'] : $value['domain']).'">'.(!empty($value['base_url']) ? $value['base_url'] : $value['domain']).'</a></td>
				<td class="sai_head service_name" style="word-break: break-word;max-width: 150px;">'.$value['port'].''.(!empty($value['additional_ports']) ? ', '.$value['additional_ports'] : '').'</td>
				<td class="sai_head service_name">'.$apps[$appid]['name'].' '.($value['type'] == 'passenger' ? '<br><span class="apptype">(Passenger)</span>' : '').'</td>
				<td>
					<label class="switch">
						<input type="checkbox" class="checkbox"  data-donereload="1" data-app="'.$key.'" data-action="'.(!empty($value['enabled'])? 'disable' : 'enable').'" '.(!empty($value['enabled']) ? 'checked' : '').' data-user="'.$value['user'].'" onclick="return access_toggle(this)">
						<span class="slider" '.(!empty($value['enabled']) ? 'title="Enabled"' : 'title="Disabled"').'></span>
					</label>
				</td>
				<td align="center">
					<i class="run_status fas fa-'.(!empty($ports_in_use[$value['port']]) ? 'running active-icon running' : 'power-off inactive-icon stop').'"  title="'.(!empty($ports_in_use[$value['port']]) ? __('Running') : __('Stopped')).'"></i>
				</td>
				<td align="center">
					<i class="fas fa-power-off '.(!empty($ports_in_use[$value['port']]) ? 'inactive-icon' : ' active-icon').' startstop" data-action="'.(!empty($ports_in_use[$value['port']]) ? 'stop' : 'start').'" data-app="'.$key.'" data-user="'.$value['user'].'" onclick="return eapp_start_stop(this)" '.(!empty($ports_in_use[$value['port']]) ? 'title="'.__('Stop').'"' : 'title="'.__('Start').'"').'></i>
				</td>
				<td align="center">
					<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="'.$key.'" onclick="delete_eapps(this)" data-app="'.$key.'" data-action="delete" data-user="'.$value['user'].'"></i>
				</td>
			</tr>';
		
			}
			}
			
			echo '
			</tbody>
		</table>
	</div>
</div>

<script>

function access_toggle(ele){
	var jEle = $(ele);
	var d = jEle.data();
	var a, lan;
	if(d.action){
		lan = "'.__js('Do you want to enable application').'";
	}else{
		lan = "'.__js('Do you want to disable application').'";
	}
	
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
	
	var no = function(){
		var enable = d.action ? false : true;
		jEle.prop("checked", enable);
	}
	
	// Submit the data
	a.confirm.push(function(){
		submitit(d, {done_reload : window.location.href, error: no});
	});
	
	// If user closes or chooses no
	a.no.push(no);
	a.onclose.push(no);
	
	//console.log(a);//return;
	show_message(a);
}

function eapp_start_stop(ele){
	var jEle = $(ele);
	var d = jEle.data();
	
	submitit(d, {done_reload : window.location.href});
}

function delete_eapps(el){
	
	var a;
	var jEle = $(el);
	// console.log(d);return false;
	
	var del_folder = "<br /><input type=\"checkbox\" name=\"del_folder\" id=\"del_folder\" />&nbsp;&nbsp;"+"'.__js('$0 Delete the application folder. $1', ['<b>', '</b>']).'";
	
	var lang = "'.__js('Are you sure you want to delete this ?').'" + del_folder;
	a = show_message_r("'.__js('Warning').'", lang);
	a.alert = "alert-warning";
	a.confirm.push(function(){
		
		if($("#del_folder").prop("checked") == true){
			jEle.data("del_folder", 1);
		}
		
		var d = jEle.data();
		// Submit the data
		submitit(d, {
			done_reload : window.location
		});
	});
	
	show_message(a);
	
}

</script>';
	
	softfooter();
	
}

