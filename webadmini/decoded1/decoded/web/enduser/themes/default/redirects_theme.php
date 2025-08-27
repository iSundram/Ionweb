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

function redirects_theme(){

global $user, $globals, $theme, $softpanel, $WE, $catwise, $error, $done, $domain_list, $redirects_list;
	
softheader(__('Redirects'));

echo '
<div class="card soft-card p-3 col-12">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'redirects.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Redirects').'</h5>
		<button type="button" class="flat-butt float-end" data-bs-toggle="modal" data-bs-target="#add-redirect">'.__('Add Redirect').'</button>
	</div>
</div>
	
<div class="card soft-card p-4 col-12 mt-4">
	<div class="modal fade" id="add-redirect" tabindex="-1" aria-labelledby="add-redirectLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="add-redirectLabel">'.__('Add New Redirect').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<form accept-charset="'.$globals['charset'].'" action="" method="post" id="editform" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
						<label for="selectdomain" class="form-label">'.__('Select Domain').'</label>
						<select class="form-select mb-3" id="selectdomain" name="selectdomain">';
							foreach ($domain_list as $key => $value){
								echo '<option value='.$key.'>'.$key.'</option>';
							}
							echo '
						</select>
						<img id="changeredirects" class="mb-3" src="'.$theme['images'].'progress.gif" style="display:none;">
						<label for="path" class="form-label">'.__('Path').'</label>
						<span class="sai_exp2 d-block">'.__('Type the Redirect path without the domain name').'</span>
						<div class="input-group mb-3">
							<span class="input-group-text">/</span>
							<input type="text" name="path" id="path" class="form-control" />
						</div>
						<label for="type" class="form-label">'.__('Type').'</label>
						<select name="type" id="type" class="form-select mb-3">
							<option value="temporary">'.__('Temporary (302)').'</option>
							<option value="permanent">'.__('Permanent (301)').'</option>
						</select>
						<label for="address" class="form-label" id="type">'.__('Address').'</label>
						<span class="sai_exp2 d-block">'.__('Address must begin with a protocol like http://').'</span>
						<input type="text" id="address" name="address" class="form-control mb-3" value="" />
							
						<input type="submit" class="flat-butt me-2" value="'.__('Add Redirect').'" name="add" id="submitredirect" />
						<img id="createimg" src="'.$theme['images'].'progress.gif" style="display:none">						
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div id="showrectab" class="table-responsive">	
		<table class="table align-middle table-nowrap mb-0 webuzo-table" >			
		<thead class="sai_head2">
			<tr>
				<th class="align-middle">'.__('Select Domain').'</th>
				<th class="align-middle">'.__('Path').'</th>
				<th class="align-middle">'.__('Type').'</th>
				<th class="align-middle">'.__('Address').'</th>
				<th class="align-middle">'.__('Options').'</th>
			</tr>
					
		</thead>
		<tbody>';

	if(empty(array_filter($redirects_list))){
		echo '
			<tr>
				<td colspan="100" class="text-center">
					<span>'.__('No Redirect(s) found').'</span>
				</td>
			</tr>';
	}else{
		foreach ($redirects_list as $domain => $vals){
			
			foreach($vals as $key => $v){
				
				echo '
			<tr id="tr'.$key.'">
				<td>'.$domain.'</td>
				<td><a href="http://'.$domain.$v['path'].'" target="_blank">'.$v['path'].'</a></td>
				<td>'.$v['type'].'</td>
				<td>'.$v['address'].'</td>
				<td width="2%" align="center">
					<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$key.'" onclick="delete_record(this)" data-domain="'.$domain.'" data-path="'.$v['path'].'" data-delete="1"></i>
				</td>
			</tr>';
			}
		}
	}
	echo '
		</tbody>
		</table>	
	
	</div>		
</div>';

softfooter();

}