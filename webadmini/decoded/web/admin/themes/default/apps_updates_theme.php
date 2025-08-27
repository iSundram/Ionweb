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

function apps_updates_theme(){

global $globals, $theme, $softpanel, $error, $backups, $updates, $apps, $iapps, $result, $done;
	
	// For updating entire file
	if(optGET('ajax_remove')){
		
		$soft = (int) optGET('app');
		
		if(!empty($error)){			
			echo '0'.current($error);
			return false;
		}
	}

	softheader(__('Update System Applications'));
	
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-sync-alt me-1"></i>'.__('Application Updates').'
	</div>
</div>	

<div class="soft-smbox p-3 mt-4">';
if(!empty($globals['DISABLE_SYSAPPS'])){
	echo '
	<div class="alert alert-danger">
		<center style="margin-top:4px; font-size:16px;">'.__('You do not have permission to access this page').'</center>
	</div>';
}else{

	echo '
<script language="javascript" type="text/javascript">
ainsids = new Array();
updated = new Object();

$(document).ready(function(){
	$(".sai_altrowstable tr").mouseover(function(){
		var old_class = $(this).attr("class");
		$(this).attr("class", "sai_tr_bgcolor");
		$(this).mouseout(function(){
			$(this).attr("class", old_class);
		});
	});
});

function show_confirm_apps(){

	ainsids = new Array();
	updated = new Object();		

	// Build the list of Installations to update
	var field = document.getElementsByName(\'ainsids[]\');
	ainsids = new Array();
	var c = 0;
	for(i = 0; i < field.length; i++){
		if(field[i].checked == true){
			ainsids[c] = field[i].value;
			c++;
		}		
	}
	
	// console.log(ainsids);return false;

	var a = {};
	
	if(c == 0){
		a = show_message_r("'.__js('Info').'", "'.__js('No installation(s) selected to update.').'");
		a.alert = "alert-info";
		a.ok.push(function(){
			
		});
		show_message(a);
		return false;
	}
	
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you wish to update the selected installations ? The action will be irreversible. \\nNo further confirmations will be asked !').'");
	a.alert = "alert-warning";
	a.confirm.push(function(){
		update_by_id_apps(ainsids[0], "", 0);
	});
	
	show_message(a);
}

function update_by_id_apps(ainsid, re, oldainsid){

	updated[ainsid] = false;

	if(re.length > 0 && oldainsid > 0){

		var result = re.substring(0,1);		
		var msg = re.substring(1);		
		if(result == "0"){
			alert(msg);
			updated[ainsid] = false;
			$_("rem_div_apps").innerHTML = "";
			return false;			
		}
		if(result == "1"){			
			updated[ainsid] = true;					
		}
	}

	nextainsid = 0;

	// Find the next INSTALLATION to update
	for(i = 0; i < ainsids.length; i++){
		if(typeof(updated[ainsids[i]]) != "undefined"){
			continue;
		}
		nextainsid = ainsids[i];
		break;
	}

	// If there is something left to be updated
	if(ainsid != 0){
		try{
			AJAX({
				url:window.location+"&app="+ainsid+"&update=1&api=json&random="+Math.random(),
				dataType: "json"
			}, 
			function(data){
				//""
				
				if(!empty(data.done)){
				
					$_("rem_div_apps").innerHTML = "<br /><br /><p align=\"center\"><img src=\"' . $theme['images'] . 'ajax_remove.gif\"> <br />'.__js('Updating Installation - '). ' ID: " +ainsid+ " - <a href=\'javascript:loadlogs("+data.done.actid+")\'>Task Logs</a>" +"<br /></p>";
					
					var check_task_status = function(){
						AJAX({
							url:"'.$globals['ind'].'act=tasks&api=json",
							data: "actid="+data.done.actid,
							dataType: "json"
						}, 
						function(d){
							//console.log(d);
							
							// Task ended
							if(!empty(d.task.ended)){
								clearInterval(interval);
								update_by_id_apps(nextainsid, "", ainsid);
							}
							
						});
					}
				
					var interval = setInterval(check_task_status, 3000);
				
				}
			});
			
			return true;
		}catch(e){
			alert(e.description);
			return false;
		}
	}
	
	a = show_message_r("'.__js('Info').'", "'.__js('The selected installation(s) have been updated. The page will now be reloaded !').'");
	a.alert = "alert-info";
	a.ok.push(function(){
		location.reload(true);
	});
	show_message(a);
	
	return true;
}

binsids = new Array();
backuped = new Object();

function show_confirm_backup(){

	binsids = new Array();
	backuped = new Object();		

	// Build the list of Installations to update
	var field = document.getElementsByName(\'binsids[]\');
	binsids = new Array();
	var c = 0;
	for(i = 0; i < field.length; i++){
		if(field[i].checked == true){
			binsids[c] = field[i].value;
			c++;
		}		
	}
	
	var a = {};
	if(c == 0){
		a = show_message_r("'.__js('Info').'", "'.__js('No backup(s) selected to remove.').'");
		a.alert = "alert-info";
		a.ok.push(function(){
			
		});
		show_message(a);
		return false;
	}
	
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you wish to delete the selected backup ? The action will be irreversible. \\nNo further confirmations will be asked !').'");
	a.alert = "alert-warning";
	a.confirm.push(function(){
		backup_remove_by_id_apps(binsids[0], "", 0);
	});
	
	show_message(a);	
}

function backup_remove_by_id_apps(binsid, re, oldbinsid){

	backuped[binsid] = false;

	if(re.length > 0 && oldbinsid > 0){

		var result = re.substring(0,1);		
		var msg = re.substring(1);		
		if(result == "0"){
			alert(msg);
			backuped[binsid] = false;
			$_("rem_div_back").innerHTML = "";
			return false;			
		}
		if(result == "1"){			
			backuped[binsid] = true;					
		}
	}

	nextbinsid = 0;

	// Find the next Backup to remove
	for(i = 0; i < binsids.length; i++){
		if(typeof(backuped[binsids[i]]) != "undefined"){
			continue;
		}
		nextbinsid = binsids[i];
		break;
	}

	// If there is something left to be backuped
	if(binsid != 0){
		try{
			AJAX(window.location+"&backup_file="+binsid+"&backup_remove=1&ajax_remove=1&random="+Math.random(), "backup_remove_by_id_apps(\'"+nextbinsid+"\', re, \'"+binsid+"\')");
			$_("rem_div_back").innerHTML = "<br /><br /><p align=\"center\"><img src=\"' . $theme['images'] . 'ajax_remove.gif\"> <br />'.__js('Removing :'). ' ID: " +binsid+ "<br /></p>";
			return true;
		}catch(e){
			alert(e.description);
			return false;
		}
	}
	a = show_message_r("'.__js('Info').'", "'.__js('The selected backup(s) have been deleted. The page will now be reloaded !').'");
	a.alert = "alert-info";
	a.ok.push(function(){
		location.reload(true);
	});
	show_message(a);
	
	return true;
}	
</script>';

	// For APPS INSTALLATION LIST
	ksort($updates);

	echo '
	<link rel="stylesheet" type="text/css" href="https://images.softaculous.com/webuzo/sprites/20.css" />';

	error_handle($error, '100%');

	if(count($updates) > 0){
	
		echo '
	<div class="alert alert-warning">'.__('- There might be version update available or changes in the related configurations. Hence, it will be displayed here. $0 - Related services will be shut to perform the upgrade, which will be started over on completion of the process. $0 - Backups for updated system applications are stored (datewise) in /usr/local/apps/backup directory. $0 - Backups for the Mariadb/Mysql will be stored at /var/webuzo/backup/upgrade.', ['<br />']).'</div>';	
	
		$show_head = '
		<!--th-->
	<div class="table-responsive">
		<table border="0" cellpadding="5" cellspacing="0" width="100%" class="table webuzo-table td_font mb-3">
			<thead>
			<tr>
				<th width="15%">'.__('Application').'</th>
				<th width ="45%">Path</th>
				<th width="20%">'.__('Installation Time').'</th>
				<th width="10%">'.__('Installed Version').'</th>
				<th width="10%">'.__('Latest Version').'</th>
				<th width="10%" class="text-end">'.__('Choose').'</th>
			</tr>
			</thead>
			<tbody>';

		foreach($updates as $ik => $iv){			
	
			if($iv['type'] != 'library'){
				
				$sid = array_key_first($updates[$iv['aid']]['upgradable']);
	
				$app_update .= '
			<!--apps installed -->
			<tr>
				<td>
					<a href="'.$globals['ind'].'act=apps&app='.$ik.'"><span class="sp20_'.$apps[$ik]['softname'].' d-inline-block me-1"></span> '.$apps[$ik]['name'].'</a>
				</td>
				<td align="left">'.(empty($iv['path']['base']) ? __('/usr/local/apps') : $iv['path']['base']).'</td>
				<td>'.datify($iv['itime']).'</td>
				<td>'.$iv['version'].'</td>
				<td>'.$apps[$sid]['version'].'</td>
				<td align="center"><input type="checkbox" name="ainsids[]" value="'.$ik.'"></td>
			</tr>';	

			}else{
				// check for update	
				$n_info[$ik] = appinfo($ik);	
		
				$lib_update .= '
			<tr>
				<td width="15%">
					<span class="sp20_'.$apps[$ik]['softname'].'"></span>'.$apps[$ik]['name'].'
				</td>
				<td align="left">'.(empty($iv['path']['base']) ? __('/usr/local/apps') : $iv['path']['base']).'</td>
				<td>'.datify($iv['itime']).'</td>
				<td>'.$iv['version'].'</td>
				<td>'.$n_info[$iv['aid']]['version'].'</td>
				<td align="center"><input type="checkbox" name="ainsids[]" value="'.$ik.'"></td>
			</tr>';	

			}
				
		}
		

		if(!empty($app_update)){
		
			echo $show_head;
			
			echo '<td colspan="6" class="sai_sub_head text-center">'.__('Application').'</td>';
			$set_head = 1;
			
			echo $app_update;
			
		}
		
		if(!empty($lib_update)){
			
			if($set_head != 1){
				echo $show_head;
			}
			
			echo '<td colspan="6" class="sai_sub_head text-center">'.__('Library').'</td>';
			echo $lib_update;
			
		}
		
		echo '
			</tbody>
		</table>
	</div>';
	
		echo '
		<div id="rem_div_apps"></div>
		<div class="text-center">
			<input class="btn btn-primary" type="button" value="'.__('Update').'" onclick="show_confirm_apps()">
		</div>';

	}else{
		echo '
		<div class="alert alert-warning">
			'.__('All System Application are latest on your server').'
		</div>';
	}
	
	echo '
</div>
		
<div class="soft-smbox p-3 my-3">
	<div class="text-center">
		<label class="sai_sub_head">'.__('List of Apps Backup').'</label>
		<hr>
	</div>';
			
	if(count($backups) > 0){
	
		echo '
	<div class="table-responsive">
		<table border="0" cellpadding="5" cellspacing="0" width="100%" class="table table-hover table-hover-moz td_font">
			<thead>
				<tr>
					<th width="70%" style="border:none">'.__('Application').'</th>
					<th width="1%" style="border:none">'.__('Choose').'</th>
				</tr>
			</thead>
			<tbody>';
			$i =1;
			foreach($backups as $ik => $iv){

				echo '
				<tr>
					<td align="left">'.$iv['name'].'</td>
					<td align="center"><input type="checkbox" name="binsids[]" value="'.$iv['name'].'"></td>		
				</tr>';
				$i++;		
			}
			echo '
			</tbody>
		</table>
	</div>
	<div id="rem_div_back"></div>
	<div class="text-center">
		<input class="btn btn-danger" type="button" value="'.__('Remove').'" onclick="show_confirm_backup()">
	</div>';
	
	}else{
	
		echo '<div class="alert alert-warning text-center">'.__('No System Application Backup available on your server').'</div>';
	
	}
}

	echo '
</div>';

	softfooter();
}

