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

function disk_usage_theme(){
	
global $globals, $theme, $done, $softpanel, $WE, $user, $disk_list, $usage, $error;
	
	if(optGET('ajaxsort')){
		
		if(empty($disk_list)){
			print_r($error);
			return false;
		}
		
		$path = optGET('path');
		$sorting = optGET('ajaxsort');
		listDir((empty($path) ? $user['homedir'] : $path), $sorting);
		return true;
		
	}
	
	softheader(__('Disk Usage'));
	
	echo '
<div class="card soft-card p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'disk.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Disk Usage').'</h5>
	</div>
</div>
<div class="card soft-card p-4 mt-4">';	
	echo '
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
function showSubs(dir, topicid, ele) {
	var subs = $("#folder" + topicid);
	
	if(subs.html().length < 1){
	
		AJAX("'.$globals['index'].'act=disk_usage&path="+dir+"&ajaxsort="+sortvalue, function(data) {
			subs.html(data);
	
			if (!subs.is(":visible")){
				ele.classList.value = "fas fa-folder-open expander";
				if(subs.html().length < 1){
					subs.removeClass().addClass("row ms-2 list-folder");
				}		
				subs.show();
			}else {
				ele.classList.value = "fas fa-folder expander";
				subs.hide();
			}
			
		});
		
	}
}

sortvalue = 3;

$(document).ready(function(){
	$("#sortname").click(function(){
		sortvalue = 2;
		$.ajax({
			type: "POST",
			url: "'.$globals['index'].'act=disk_usage&ajaxsort=2",
			success: function(data) {
				$("#div1").html(data);	
			}
		});
	});
					
	$("#sortsize").click(function(){
		sortvalue = 1;
		$.ajax({
			type: "POST",
			url: "'.$globals['index'].'act=disk_usage&ajaxsort=1",
			success: function(data) {
				$("#div1").html(data);	
			}					
		});
	});
});

// ]]></script>';
	
	$listDirCount = 0;
	$user_dir = $user['homedir'];
	$directory_list = $WE->user_func_exec('filelist', [$user_dir, 0, 1]);
	$home_progress = round($disk_list['home_size'] * (100 / $disk_list['total_used_mb']), 2);
	$hidden_progress = round($disk_list['hidden_size'] * (100 / $disk_list['total_used_mb']), 2);
	
	// If Storage exceeds for user
	if(!empty($usage['disk']['limit_bytes']) && $usage['disk']['limit'] != 'unlimited' && $usage['disk']['limit_bytes'] < ($disk_list[$user_dir]['bytes'] + $disk_list['other_disk_data']['bytes'])){
		echo '<div class="row">
			<div class="col-xs-12">
				<div class="alert alert-danger" style="padding:8px">
					<center>
						<img src="'.$theme['images'].'notice.gif" /> &nbsp;					
						'.__('You have exceeded the maximum disk space allocated to your account !').'
					</center>
				</div>
			</div>
		</div>';
	}

	echo '
	<table class="table align-middle table-nowrap mb-0 webuzo-table">
		<thead class="sai_head2">
			<tr>
				<th align="center" width="40%">'.__('Directory').'</th>
				<th align="center" width="30%">'.__('Size').'</th>
				<th align="center" width="40%">'.__('Disk Usage').'</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>'.__('Files in home directory').'</td>
				<td>'.$disk_list['home_size'].' '.__('MB').'</td>
				<td>
					<div class="disk-progress d-inline-block me-2">
						<div class="progress disk-progress d-inline-block me-2">
							<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:'.$home_progress.'%"></div>						
						</div>
					</div>
					<label class="disk-usage d-inline-block">'.$home_progress.'%</label>
				</td>
			</tr>
			<tr>
				<td>'.__('Files in hidden directory\'s').'</td>
				<td>'.$disk_list['hidden_size'].' '.__('MB').'</td>
				<td>
					<div class="disk-progress d-inline-block me-2">
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:'.$hidden_progress.'%">
							</div>
						</div>
					</div>
					<label class="disk-usage d-inline-block">'.$hidden_progress.'%</label>
				</td>
			</tr>';
			
	foreach($directory_list as $key => $size){
		$progress = round($disk_list[$key]['mb'] * (100 / $disk_list[$user_dir]['mb']), 2);
		if(is_dir($key) && substr(basename($key), 0, 1) != "."){			
			
			$folderHash = base64_encode(preg_replace('/^'. preg_quote($WE->user['homedir'], '/').'\//', '', $key));
			
			$filemanager_path = 'filemanager/#elf_l1_'.$folderHash;
			
			echo '
			<tr>
				<td class="endurl">
					<a target="_blank" href="'.$filemanager_path.'">'.basename($key).'</a>
				</td>';
			echo '
				<td>'.$disk_list[$key]['mb'].' '.__('MB').'</td>
				<td>
					<div class="disk-progress d-inline-block me-2">
						<div class="progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:'.$progress.'%"></div>
						</div>
					</div>
					<label class="disk-usage d-inline-block">'.$progress.'%</label>
				</td>';	
		}
		echo '
			</tr>';
	}
			
	echo '	
			<tr class="'.(empty($disk_list['$user_dir']['mb'] +  $disk_list['other_disk_data']['mb']) ? 'd-none' : '').'">
				<td>
					<label class="form-label">'.__('Other Disk Space Usage').'</label>
				</td>
				<td colspan="2">
					<label class="form-label">'.$disk_list['other_disk_data']['mb'].' '.__('MB').'</label>
				</td>
			</tr>
			<tr>
				<td colspan="3" class="text-end">
					<label class="form-label">'.($disk_list[$user_dir]['mb'] +  $disk_list['other_disk_data']['mb']).' '.__('MB total disk space Usage').'</label>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="mt-5 mb-3">
		<h5>'.__('The above progress bar shows how much percent is used by that directory from the total disk space used.').'</h5>
		<p>'.__('The Disk Usage table below indicates how much space the directories content use, not how much space the directory itself uses. Files typically occupy more disk space than their actual size. This may cause difference between the data you see in the File Manager versus the information you find here.').'</p>
		<label class="form-label d-block">'.__('Sort directory by : ').'</label>
		<label class="form-label me-2">
			<input type="radio" name="sort" id="sortname" value="Name" checked />'.__('By Name').'
		</label>
		<label class="form-label">
			<input type="radio" name="sort" id="sortsize" value="size" />'.__('By Size').'
		</label>
	</div>
	<div class="row py-3 disk-usage-titles">
		<div class="col-4">
			<label class="disk-usage-title">'.__('Directory').'</label>
		</div>
		<div class="col-4">
			<label class="disk-usage-title">'.__('Disk Usage in MB').'</label>
		</div>
		<div class="col-4">
			<label class="disk-usage-title">'.__('Disk Usage in Bytes').'</label>
		</div>
	</div>
	<div class="row py-3">
		<div class="col-4">
			<a target="_blank" class="text-decoration-none" href="filemanager/#elf_l1_">
				<i class="fas fa-home home-directory">/</i>
			</a>
		</div>
		<div class="col-4">'.$disk_list[$user_dir]['mb'].' '.__('MB').'</div>
		<div class="col-4">'.$disk_list[$user_dir]['bytes'].' '.__('Bytes').'</div></b>
	</div>
	<div id="div1">';
	
		listDir($user_dir, 0, $directory_list);
	
	echo '
	</div>
</div>';
	
	softfooter();
	
}

function listDir($path, $sort = 0, $directory_list = []){
	
	global $listDirCount, $disk_list, $WE;
	
	if(empty($directory_list)){
		$directory_list = $WE->user_func_exec('filelist', [$path, 0, 1]);
	}
	
	foreach($directory_list as $key => $value){
		if ($WE->user_func_exec('is_dir', [$key])){
			$test[$key]['mb'] = $disk_list[$key]['mb'];
			$test[$key]['bytes'] = $disk_list[$key]['bytes'];
		}
	}
    
	if($sort == 1){
		arsort($test);
	}else{
		ksort($test);
	}
	
	foreach($test as $folder => $sizeby){
		
		$md5 = md5($folder);
		
		$folder_path = preg_replace('/^'.preg_quote($WE->user['homedir'], '/').'\//', '', $folder);
		
		$first_folder = explode('/', trim($folder_path, '/'))[0];
		
		if($first_folder == '.trash'){
			// echo $folder_path;
			$folderHash = base64_encode(str_replace(['.trash/', '.trash'], '', $folder_path));
			$filemanager_path = 'filemanager/#elf_t1_'.$folderHash;
		}else{
			$folderHash = base64_encode($folder_path);
			$filemanager_path = 'filemanager/#elf_l1_'.$folderHash;
		}
		
		echo '
		<div class="row is-folder">
			<div class="col-4">
				<p class="m-0 mb-1">
					<i class="fas fa-folder expander" title="'.__('See disk usage for this directory’s child directories.').'" onclick="showSubs(\''.$folder.'\', \''.$md5.'\', this)"></i>
					
					<a target="_blank" class="text-decoration-none directory" href="'.$filemanager_path.'"> '.basename($folder).'</a>
				</p>
			</div>
			<div class="col-4">'.$sizeby['mb'].' '.__('MB').'</div>
			<div class="col-4">'.$sizeby['bytes'].' '.__('Bytes').'</div>
		</div>
		<div class="row ms-2 list-folder mb-3 p-2" id="folder' . $md5 . '" style="display: none;"></div>';
	}
	
}