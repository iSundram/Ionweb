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

function sel_restore_files_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error, $done, $wbackup_list, $wbackup_log, $json_files, $backup_path, $type, $dir_check, $database_backup_list, $backup_data, $full_data, $dbbackups, $usage;
	
	softheader(__('Partial Restore'));
	// r_print($wbackup_list);
echo '
<div class="card soft-card p-4">';
	echo '
	<div class="row justify-content-center mb-3">
		<div class="col-md-3 col-sm-6 col-xs-12 mb-3 me-2 p-2 shadow">
			<div class="card soft-card p-2">
				<div class="d-flex">
					<div class="flex-shrink-0 align-self-center">
						<div class="card-icon">
							<i class="fas fa-user"></i>
						</div>
					</div>
					<div class="flex-grow-1 ps-3">
						<span class="soft-card-desc mb-1 d-block">'.__('Current User').'</span>
						<h4 class="soft-card-val">'.$WE->user['user'].'</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mb-3 me-2 p-2 shadow">
			<div class="card soft-card p-2">
				<div class="d-flex">
					<div class="flex-shrink-0 align-self-center">
						<div class="card-icon">
							<i class="fas fa-file"></i>
						</div>
					</div>
					<div class="flex-grow-1 ps-3">
						<span class="soft-card-desc mb-1 d-block">'.__('Total Backups').'</span>
						<h4 class="soft-card-val">'.count($wbackup_list['full']).'</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-12 mb-3 me-2 p-2 shadow">
			<div class="card soft-card p-2">
				<div class="d-flex">
					<div class="flex-shrink-0 align-self-center">
						<div class="card-icon" style="padding: 15px 21px;">
							<i class="fas fa-hdd"></i>
						</div>
					</div>
					<div class="flex-grow-1 ps-3">
						<span class="soft-card-desc mb-1 d-block">'.__('Total Account Usage').'</span>
						<h4 class="soft-card-val">'.$usage['disk']['used'].'</h4>
					</div>
				</div>
			</div>
		</div>';
	echo '
	</div>';

	$bicons = load_backuply_icons();
	// r_print($bicons);
		echo '
	<div class="row justify-content-center p-2">
		<div class="col-xxl-10 col-xl-10 col-lg-10 col-md-12 col-12" id="draggablePanelList">';
			foreach ($bicons as $key => $value) {
					
				if(empty($value['icons']) && empty($value['html'])){
					continue;
				}
				
				// $glyph = '';
				$collapsed = 'show';
				
				echo '
				<div class="card soft-card mb-3 panel-row" id="row_'.$key.'">
					<div class="accordion panel-default" id="main_div_'.$key.'">
						<div class="accordion-item" id="main_table_'.$key.'">
							<div class="accordion-header" id="panel_'.$key.'_heading">
								<div class="row align-items-center panel-heading">
									<div class="col-10 px-4 py-2">							
										<i class="'.$value['fa-icon'].' panel-icon"></i>
										<label class="panel-head d-inline-block mb-0">'.$value['name'].'</label>
									</div>
									<div class="col-2 p3-5">
										<div class="accordion-button '.$glyph.'" type="button" data-bs-toggle="collapse" data-bs-target="#panel_'.$key.'" aria-controls="panel_'.$key.'" onclick="panel_collapse(\''.$key.'\');"></div>
									</div>
								</div>
							</div>
							<div id="panel_'.$key.'" class="accordion-collapse collapse '.$collapsed.'" aria-labelledby="panel_'.$key.'_heading" data-bs-parent="#main_div_'.$key.'">
								<div class="accordion-body p-1">
									<div class="row">';
									
									if(!empty($value['icons'])){
										foreach ($value['icons'] as $k => $v) {
											if(!empty($v['hidden'])){
												continue;
											}
											
											// echo $k;
											echo '
											<div class="col-xs-6 col-sm-6 col-md-4 col-lg-2 col-xl-2 col-xxl-2">
												<a href="'.$v['href'].'" onclick="'.$v['onclick'].'" '.( $v['atts'] ? $v['atts'] : "").' class="webuzo_icons '.( $v['class'] ? $v['class'] : "").'" value="'.$v['label'].'" '.( !empty($v['target']) ? 'target="'.$v['target'].'"' : '').'>
													<div id="'.$v['id'].'" class="pan-button">
														'.(empty($v['fa-icon']) ? '<img src="'.$v['icon'].'" alt="" width="42" />' : '<i class="'.$v['fa-icon'].'" style="font-size: 45px;"></i>').'<br>
														<span class="medium">'.$v['label'].'</span>
													</div>
												</a>
											</div>';
										}
									}
									
									if(!empty($value['html'])){
										echo $value['html'];
									}
									
								echo '
									</div>
								</div>
							</div>					
						</div>
					</div>
				</div>';
			}
	echo '</div>
	</div>
</div>';
}