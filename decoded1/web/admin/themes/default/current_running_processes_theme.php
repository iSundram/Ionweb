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

function current_running_processes_theme(){

global $theme, $globals, $user, $error, $W, $done, $pid, $process_name, $command_line, $current_directory, $file_path, $p_user; 
	
	softheader(__('Current Running Processes'));
	echo'
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-cogs fa-xl me-1"></i> '.__('Current Running Processes').'
	</div>
</div>

<div class="soft-smbox p-3 mt-4">
	<div id="proc-table-div" class="table-responsive mt-4">
		<table id="process-list" border="0" cellpadding="8" cellspacing="1"  class="table sai_form webuzo-table">
			<thead>
			<tr>
				<th>'.__('PID').'</th>
				<th>'.__('User').'</th>
				<th>'.__('Name').'</th>
				<th>'.__('Files').'</th>
				<th>'.__('Current Directory').'</th>
				<th width="50%">'.__('Command').'</th>				
			</tr>
			</thead>
			<tbody>';
			
			foreach($pid as $id => $v){
				echo'
				<tr>	
					<td>'.$v.'</td>
					<td>'.$p_user[$id].'</td>
					<td>'.$process_name[$id].'</td>
					<td>'.$file_path[$id].'</td>';
					if(empty($current_directory[$id])){
						echo '<td> / </td>';
					}else{
						echo'<td>'.$current_directory[$id].'</td>';
					}
					echo'
					<td>'.$command_line[$id].'</td>
				</tr>';	
			}
			
		echo '
			</tbody>';
	echo '
		</table>
	</div>
</div>';		
			
	softfooter();
}