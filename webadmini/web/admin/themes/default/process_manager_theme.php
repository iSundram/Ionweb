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

function process_manager_theme(){

global $theme, $globals, $user, $langs, $error, $W, $saved, $done, $psOutput, $success;
	
	softheader(__('Process Manager'));
	
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-cogs me-1"></i> '.__('Process Manager').'
	</div>
</div>
<div class="soft-smbox p-4 mt-4">';
	
	error_handle($error, '100%');
	
	echo '
	<form accept-charset="'.$globals['charset'].'" name="kill_process" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1>
	<div class="row" id="user-list">
		<div class="col-sm-3">
			<label class="sai_head">'.__('Kill all processes by user ').'</label><br />
		</div>
		<div class="col-sm-4">				
			<select name="process_user" class="form-control" id="process_user">';
			$users = array();
			foreach ($psOutput as $k => $ps) {
				$ps = preg_split('/ +/', $ps);
				if ($k < 7) continue;					
					if(!in_array($ps[2],$users)){
						if($ps[2] != 'root'){
						echo '<option value="'.$ps[2].'">'.$ps[2].'</option>';
						}
					}
					$users[] = $ps[2];
			}
			echo '
			</select>
		</div>
		<div class="col-sm-5">
			<input type="submit" class="btn btn-primary" name="kill_process_by_user" value="'.__('Kill user\'s processes').'"/>
		</div>
	</div></br>
	</form>
	<div id="proc-table-div" class="table-responsive mt-4">
		<table id="process-list" border="0" cellpadding="8" cellspacing="1"  class="table sai_form webuzo-table">
			<thead>
			<tr>
				<th>'.__('PID').'</th>
				<th>'.__('Owner').'</th>
				<th>'.__('Priority').'</th>
				<th>'.__('CPU %').'</th>
				<th>'.__('Memory %').'</th>
				<th width="40%">'.__('Command').'</th>				
			</tr>
			</thead>
			<tbody>';
			
		if (count($psOutput) > 0) {
			foreach ($psOutput as $k => $ps) {
				if ($k < 7) continue;
				$ps = preg_split('/ +/', ltrim($ps));
				$pid = $ps[0];
				$owner = $ps[1];
				$priority = $ps[3];
				$cpu = $ps[8];					
				$mem = $ps[9];
				if(count($ps) >= 11){
					$command = implode(' ', array_slice($ps, 11));
				}					
				echo '
				<tr>
					<td>'. $pid .'&nbsp;(<a class="pm_link trace_pid" href="javascript:void(0)" pid="'.$pid.'" onclick="trace_process(this)">'.__('Trace').'</a>)&nbsp;(<a class="pm_link kill_pid" href="'.$globals['admin_url'].'act=process_manager&kill_process_pid='.$pid.'">'.__('Kill').'</a>)</td>
					<td>'. $owner .'</td>
					<td>'. $priority .'</td>
					<td>'. $cpu .'</td>
					<td>'. $mem .'</td>
					<td>'. $command .'</td>
				</tr>';
			}
		}
		
		echo '
			</tbody>
		</table>
	</div>
	<div id="TraceDiv">
		<div id="TraceLogs">
		</div>
		<div style="text-align: center;">
			<a class="btn btn-primary" href="'.$globals['admin_url'].'act=process_manager" >
				<i class="fas fa-arrow-circle-left" aria-hidden="true"></i>'.__('Back').'
			</a>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	$("#TraceDiv").hide();
})

function trace_process(el){	
	$("#TraceDiv").show();
	window.scroll({
		top: 0, 
		left: 0, 
		behavior: "auto" 
	});
   
	var pid = $(el).closest("a").attr("pid");
	$.ajax({				
		type: "POST",
		dataType: "json",
		url: "'.$globals['index'].'act=process_manager&api=json&trace_process_pid="+pid,
		success: function(data){
			//console.log(data.error.err_timeout);
			if(data.error && data.error.err_timeout){
				var a = show_message_r(l.error, data.error.err_timeout);
				a.alert = "alert-danger";
				show_message(a);
				return;
			}
			getTraceLog(pid);
			setInterval(getTraceLog,5000,pid);
		}
	});
}

function getTraceLog(pid){
	$.ajax({				
		type: "POST",
		dataType: "json",
		url: "'.$globals['index'].'act=process_manager&api=json&getTraceLog=1&process_pid="+pid,
		success: function(data){
			if(data["trace_logs"]){
				$("#process-list").hide();
				$("#user-list").hide();
				$("#TraceLogs").html(data["trace_logs"]);
			}			
		}
	});
}

</script>';

	softfooter();

}
