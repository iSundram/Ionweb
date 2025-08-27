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

function current_disk_usage_theme(){

global $globals, $theme, $softpanel, $error, $disk, $io;
	
	softheader(__('Current Disk Usage'));
	
	//Disk usage info
	echo '<div class="row">&nbsp;&nbsp;&nbsp;
	<div class="soft-smbox p-3 col">
	<div class="sai_main_head text-center">
		<img src="'.$theme['images'].'disk.png" width="40" height="40" class="webu_head_img me-1" /> '.__('Disk Usage').'
	</div>
	<hr>';
	
	error_handle($error, '100%');
	
	echo '
	<div id="" class="table-responsive mt-4 ">
		<table id="" border="0" cellpadding="8" cellspacing="1"  class="table sai_form webuzo-table">
			<thead>
			<tr>
				<th>'.__('FILE SYSTEM').'</th>
				<th>'.__('SIZE').'</th>
				<th>'.__('USED').'</th>
				<th>'.__('AVAILABLE').'</th>
				<th>'.__('USE %').'</th>
				<th width="">'.__('MOUNT POINT').'</th>				
			</tr>
			</thead>
			<tbody>';
		if (count($disk['output']) > 0) {
			foreach ($disk['output'] as $k => $du) {
				if ($k === 0 ) continue;
				$du = preg_split('/ +/', $du);
				$fs = $du[0];
				$sz = $du[1];
				$used = $du[2];
				$available = $du[3];
				$usedpercent = $du[4];					
				$mount = $du[5];
		
				echo '
				<tr>					
					<td>'. $fs .'&nbsp;</td>
					<td>'. $sz .'</td>
					<td>'. $used .'</td>
					<td>'. $available .'</td>
					<td>'. $usedpercent .'</td>
					<td>'. $mount .'</td>
				</tr>';
			}
		}
		
		echo '
			</tbody>
		</table>
	</div>
	
</div>';

	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

	//IOstat Info 
	echo '
<div class="soft-smbox p-3 col-md-auto text-center">
	<div class="sai_main_head text-center mt-2">
		<i class="fas fa-chart-bar me-2" style="color:black;"></i> '.__('IO Statistics').'
	</div>
	<hr>';
			
	echo '
	<div id="" class="table-responsive mt-4">
		<table id="" border="0" cellpadding="8" cellspacing="1"  class="table sai_form webuzo-table">
			<tbody>';

	if (count($io['output']) > 0) {
		foreach ($io['output'] as $k => $is) {
			//if ($k === 0 || $k === 1 || $k === 2 || $k === 3 || $k === 4 || $k === 5 || $k ===7) continue;
			$is = preg_split('/ +/', $is);
			$dv = $is[0];
			$tps = $is[1];
			$brc = $is[2];
			$bwc = $is[3];
			$tbr = $is[4];					
			$tbw = $is[5];	
		echo '
			<thead>
			<tr>
				<th>'.__('Device').'</th> 	<td>'. $dv .'&nbsp;</td>
			</tr>
			<tr>
				<th>'.__('Trans/Sec').'</th> 	<td>'. $tps .'</td>
			</tr>
			<tr>
				<th>'.__('Blocks Read/Sec').'</th> 	<td>'. $brc .'</td>
			</tr>
			<tr>	
				<th>'.__('Blocks Written/Sec').'</th> 	<td>'. $bwc .'</td>
			</tr>
			<tr>	
				<th>'.__('Total Blocks Read').'</th> 	<td>'. $tbr .'</td>
			</tr>
			<tr>	
				<th width="40%">'.__('Total Blocks Written').'</th>	 <td>'. $tbw .'</td>			
			</tr>
			</thead>';
		}
	}
	
	echo '
			</tbody>
		</table>
	</div>
</div>&nbsp;&nbsp;&nbsp;
</div>';

	softfooter();

}

