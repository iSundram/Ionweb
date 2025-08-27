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

function plugins_theme(){

global $user, $globals, $theme, $error, $saved, $plugins_list, $done, $deactivated, $activated, $installed_plugins;

	// For delete
	if(optGET('ajaxset')){
		if(!empty($error)){
			echo '0'.current($error);
			return false;
		}
		if(!empty($saved)){
			echo '1'.__('Your settings were saved successfully');
			return true;
		}
	}
	
	softheader(__('Installed Plugins'));

	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		'.__('Installed Plugins').'
		<input type="button" class="btn btn-primary mb-3 float-end" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" data-keyboard="false" value="'.__('Add New').'">
	</div>	
</div>
<div class="soft-smbox p-4 mt-4">';	
	
	error_handle($error, "100%");
	
	echo '
	<form accept-charset="'.$globals['charset'].'"  name="pluginsform" method="post" action="" onsubmit="return checkform();" role="form" class="form-horizontal" enctype="multipart/form-data">
	<!-- Modal -->
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered bd-example-modal-lg" role="document" style="width: 45vw;">
				<div class="modal-content">
			  		<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">'.__('Upload Plugin').'</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-12 col-md-6">
								<label for="domain" class="sai_head control-label">'.__('Upload Plugin').'</label>
							</div>
							<div class="col-12 col-md-6">
								<input type="file" class="form-control-file" name="updplugin" id="updplugin">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'.__('Close').'</button>
						<input type="submit" name="submit_plugin" value="'.__('Upload').'" class="btn btn-primary">
					</div>
				</div>
			</div>
		</div>
	</form>';
				
	if(!empty($done)){
		echo '
	<div class="alert alert-success text-l">
		<img src="'.$theme['images'].'success.gif" /> &nbsp; '.($activated == true ? __('Plugin Activated Successfully') : ($deactivated == true ? __('Plugin Deactivated Successfully') : '')).'
	</div>';
	}

	echo '
	<div class="table-responsive">
		<table border="0" cellpadding="8" cellspacing="1"  class="table webuzo-table td_font" width="100%">
			<thead>
				<tr>
					<th width="4%"><font>#</font></th>
					<th width="26%"><font>'.__('Plugin').'</font></th>
					<th width="52%"><font>'.__('Desciption').'</font></th>
					<th width="10%"><font>'.__('Version').'</font></th>
					<th width="8%"><font>'.__('Action').'</font></th>				
				</tr>
			</thead>
			<tbody id="list_plugins">';
		$i = 1;
		foreach ($plugins_list as $key => $value){
			echo '
				<tr>
					<td><span>'.$i.'</span></td>
					<td><span>'.$value['name'].'</span></td>
					<td><span>'.$value['description'].'</span></td>
					<td><span>'.$value['version'].'</span></td>
					<td>
						<a href="'.$globals['ind'].'act=plugins&'.(array_key_exists($key, $installed_plugins) ? "deactivate" : "activate").'='.$key.'" >
							<span class="btn btn-primary">'.(array_key_exists($key, $installed_plugins) == 1 ? __('Deactivate') : __('Activate')).'</span>
						</a>
					</td>
				</tr>';
			$i++;			
		}
		echo '
			</tbody>
		</table>
	</div>
</div>	

<script language="javascript" type="text/javascript">
$(".loader-div").hide();
</script>';
	softfooter();
}


