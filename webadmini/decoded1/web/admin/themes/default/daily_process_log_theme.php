<?php

//////////////////////////////////////////////////////////////
//===========================================================
// WEBUZO CONTROL PANEL
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Mamta Malvi
// Date:       07 th April 2022
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

function daily_process_log_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $top_ps, $user_res_log, $date;

	softheader(__('Daily Process Log'));
    
    $next_day = date('Y-m-d', strtotime('+1 day', strtotime($date)));
    $previous_day = date('Y-m-d', strtotime('-1 day', strtotime($date)));

    echo '	
<div class="modal fade" id="atop_conf" tabindex="-1" aria-labelledby="atop_confLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="atop_conf_label">'.__('Configure Top Process Log').'</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
			<form action="" method="POST" name="atop_config" id="atop_config" class="form-horizontal" onsubmit="return submitit(this)">

				<div class="row mb-3">
					<div class="col-12 col-md-3 col-lg-3">
						<label class="sai_head" for="minute">'.__('Disable Atop').'
							<span class="sai_exp">'.__('If checked process will not be logged.').'</span>
						</label>
					</div>
					<div class="col-12 col-md-7 mb-1">
					   <input type="checkbox" name="disable_atop" '.POSTchecked('disable_atop', $globals['disable_atop']).' class="custom-control-input" />
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-12 col-md-3 col-lg-3">
						<label class="sai_head" for="hour">'.__('Log Interval (in seconds)').'</label>
					</div>
					<div class="col-12 col-md-7 mb-1">
						<input type="number" class="form-control" id="atop_interval" name="atop_interval" value="'.$globals['atop_interval'].'">
					</div>
				</div>
			   
				<div class="row mb-3">
					<div class="col-12 col-md-3 col-lg-3">
						<label class="sai_head" for="hour">'.__('Truncate Logs (in days)').'</label>
					</div>
					<div class="col-12 col-md-7 mb-1">
						<input type="number" class="form-control" id="atop_log" name="atop_log" value="'.$globals['atop_log'].'">
					</div>
				</div>
				<div class="row mb-2 text-center">
					<div class="col-12">
						<input class="btn btn-primary" type="submit" name="atop_conf" id="atop_conf" value="'.__('Save').'">
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>
	
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="far fa-file-alt fa-xl me-1"></i>
		'.__('Daily Process Log').'

		<span class="search_btn float-end">
			<a type="button" class="float-end" data-bs-toggle="modal" data-bs-target="#atop_conf"><i class="fas fa-cogs"></i></a>
		</span>
	</div>
</div>

<div class="soft-smbox p-3 mt-4">
	<div class="row my-2">
		<div class="col-md-4">
			<a href="'.$globals['admin_index'].'act=daily_process_log&date='.$previous_day.'" title="Previous Day" ><i class="fa fa-angle-double-left"></i> '.$previous_day.'</a> 
		</div>
		<div class="col-md-4  text-center"><strong>'.$date.'</strong></div>
		<div class="col-md-4">
			<a style="float:right;" href="'.$globals['admin_index'].'act=daily_process_log&date='.$next_day.'" title="Next Day" >'.$next_day.' <i class="fa fa-angle-double-right"></i></a>
		</div>
	</div>';
	page_links();
	echo '
	<table class="table sai_form webuzo-table" >			
		<thead class="sai_head2" style="background-color: #EFEFEF;">
			<tr>
				<th class="align-middle">'.__('User').'</th>
				<th class="align-middle">'.__('Domain').'</th>
				<th class="align-middle text-center">'.__('% CPU').'</th>
				<th class="align-middle text-center">'.__('% MEM').'</th>
				<th class="align-middle text-center">'.__('MySQL Processes').'</th>
			</tr>			
		</thead>
		<tbody>';
		if(empty($user_res_log)){
			echo '<tr>
					<td class="text-center" colspan="5">'.__('No Log Found ... !').'</td>
				</tr>';
		}else{
			foreach ($user_res_log as $index => $value) {
				echo '<tr>
					<td class="align-middle">'.$value['user'].'</td>
					<td class="align-middle">'.$value['domain'].'</td>
					<td class="align-middle text-center">'.$value['percpu'].'</td>
					<td class="align-middle text-center">'.$value['permem'].'</td>
					<td class="align-middle text-center">'.$value['pmysql'].'</td>
				</tr>';
			};
		}
		echo '</tbody>

	</table>';
		page_links();
		echo'
		<p class="text-center"><strong>'.__('Top Processes').'</strong></p>
	<table class="table sai_form webuzo-table" >			
		<thead class="sai_head2" style="background-color: #EFEFEF;">
			<tr>
				<th class="align-middle">'.__('User').'</th>
				<th class="align-middle">'.__('Domain').'</th>
				<th class="align-middle text-center">'.__('% CPU').'</th>
				<th class="align-middle">'.__('Process').'</th>
			</tr>			
		</thead>
		<tbody>';
		if(empty($top_ps)){
			echo '<tr>
					<td class="text-center" colspan="5">'.__('No Top Process Found...!').'</td>
				</tr>';
		}else{
			foreach ($top_ps as $index => $value) {
				echo '<tr>
					<td class="align-middle">'.$value['owner'].'</td>
					<td class="align-middle">'.$value['domain'].'</td>
					<td class="align-middle text-center">'.$value['cpu'].'</td>
					<td class="align-middle">'.$value['cmd'].'</td>
				</tr>';
			};
		}
		echo '</tbody>
	</table>
</div>';
	
	softfooter();
}