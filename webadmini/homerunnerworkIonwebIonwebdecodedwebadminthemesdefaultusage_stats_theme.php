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

function usage_stats_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $stats;

	softheader(__('Resource Usage Stats'));
	
	error_handle($error);

	echo '
<div class="card col-12 soft-card p-3 mb-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'disk.png" class="webu_head_img me-2"/> 
		<h5 class="d-inline-block">'.__('Resource Usage Stats').'</h5>
	</div>
</div>
<div class="soft-smbox p-3 my-4">
	<div class="sai_sub_head record-table mb-2 position-relative">
		'.__('Select User').' :
		
		<select class="form-select my-3 make-select2" s2-placeholder="'.__('Users').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" style="width:30%" name="user" id="user">
			<option value="'.$user.'" selected="selected">'.$user.'<option>
		</select>		
	</div>
</div>';

if(!empty($user) && empty($error)){
	echo '
<div class="row mb-4">
	<div class="col-12 col-lg-6">
		<div class="card soft-card p-4 mb-3 h-100">
			<h6>'.__('Live Stats').'</h6>
			<hr>
			<div class="table-responsive">
				<table class="table align-middle table-nowrap mb-0 webuzo-table">
					<tbody>
						<tr>
							<td>'.__('CPU Usage (in %)').'</td>
							<td><span id="cpuusage">'.$stats['cpu'].'</span> / '.(empty($stats['assigned']['cpuquota']) ? '∞' : $stats['assigned']['cpuquota']).'</td>
						</tr>
						<tr>
							<td>'.__('Total Disk Read').'</td>
							<td><span id="readbw">'.$stats['read_bw'].'</span></td>
						</tr>
						<tr>
							<td>'.__('Total Disk Write').'</td>
							<td><span id="writebw">'.$stats['write_bw'].'</span></td>
						</tr>
						<tr>
							<td>'.__('Memory Usage').'</td>
							<td><span id="mem">'.$stats['memory'].'</span> / '.(empty($stats['assigned']['mem_max']) ? '∞' : $stats['assigned']['mem_max']).'</td>
						</tr>
						<tr>
							<td>'.__('Tasks').'</td>
							<td><span id="task">'.$stats['tasks'].'</span> / '.(empty($stats['assigned']['maxtask']) ? '∞' : $stats['assigned']['maxtask']).'</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-6">
		<div class="card soft-card p-4 mb-3 h-100">
			<div class="table-responsive">
				<table class="table align-middle table-nowrap mb-0 webuzo-table">
					<thead>
						<th>'.__('Limits').'</th>
						<th>'.__('Limit Assigned').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('cGroups v2 should be enabled in the OS to assign limits using Resource Limits feature').'"></i></th>
					</thead>
					<tbody>
						<tr>
							<td>'.__('CPU Quota (in %)').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Percentage of CPU limit (100% refers to 1 core. >100% for more cpu cores).').'"></i></td>
							<td>'.$stats['assigned']['cpuquota'].'</td>
						</tr>
						<tr>
							<td>'.__('IO Read Bandwidth Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max rate data can be read from a disk.').'"></i></td>
							<td>'.$stats['assigned']['read_bw'].'</td>
						</tr>
						<tr>
							<td>'.__('IO Write Bandwidth Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max rate data can be written to a disk.').'"></i></td>
							<td>'.$stats['assigned']['write_bw'].'</td>
						</tr>
						<tr>
							<td>'.__('IOPS Read Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max disk read operations per second.').'"></i></td>
							<td>'.$stats['assigned']['diskread'].'</td>
						</tr>
						<tr>
							<td>'.__('IOPS Write Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max disk write operations per second.').'"></i></td>
							<td>'.$stats['assigned']['diskwrite'].'</td>
						</tr>
						<tr>
							<td>'.__('Memory Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Absolute Memory Limit. Triggers Out-Of-Memory Killer.').'"></i></td>
							<td>'.$stats['assigned']['mem_max'].'</td>
						</tr>
						<tr>
							<td>'.__('Memory High').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Throttle Memory above this Limit.').'"></i></td>
							<td>'.$stats['assigned']['memhigh'].'</td>
						</tr>
						<tr>
							<td>'.__('Tasks Max').' <i class="fa fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Max Tasks that may be created in the unit').'"></i></td>
							<td>'.$stats['assigned']['maxtask'].'</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>';
}

echo '
<script>
$("#user").on("select2:select", function(e, u = {}){
	let user;
	if("user" in u){
		user = u.user;
	}else{
		user = $("#user option:selected").val();
	}
	window.location = "'.$globals['index'].'act=usage_stats&user="+user;
});

setInterval(function(){
	$.get("'.$globals['request_url'].'&api=json", {}, function(data){
		if(data.stats){
			$("#cpuusage").text(data.stats.cpu);
			$("#readbw").text(data.stats.read_bw);
			$("#writebw").text(data.stats.write_bw);
			$("#mem").text(data.stats.memory);
			$("#task").text(data.stats.tasks);
		}
	});
}, 5000);
</script>';

	softfooter();
}