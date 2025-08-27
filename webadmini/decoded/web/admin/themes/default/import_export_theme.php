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

function import_export_theme(){

global $theme, $globals, $user, $error, $updated, $info, $report, $allusers, $done;

	softheader(__(' - Import Export Settings'));

	echo '

<div class="soft-smbox col-12 col-md-10 p-3 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-exchange-alt fa-rotate-90"></i>
		'.__('Import / Export Settings').'
	</div>
</div>
<div class="mt-4">
	<form accept-charset="'.$globals['charset'].'" name="importexport" method="post" action="" enctype="multipart/form-data" class="form-horizontal">';
	
	error_handle($error);

	if(!empty($done)){
		echo '
		<div class="alert alert-success alert-dismissible fade show" role="alert">
		  '.$done['msg'].'
		   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
	}

	echo '
		<div class="soft-smbox mb-3 col-12 col-md-10 col-lg-10 mx-auto">
			<div class="sai_form_head">'.__('Import Settings').'</div>
			<div class="sai_form p-3">
				<div class="row">
					<div class="col-12 col-md-5">
						<label class="sai_head d-block">'.__('Import Settings').'
							<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('Import the $0 settings using the previously exported file. $1 ( $2 Note $3 : It will replace your current settings)', [APP, '<br />', '<b>', '</b>']).'"></i>
						</label>
					</div>
					<div class="col-12 col-md-7">
						<input type="file" name="import_file"/>
					</div>
					<div class="col-12 text-center">
						<input type="submit" name="import_setting" value="'.__('Import Settings').'" class="btn btn-primary mt-4"/>
					</div>
				</div>
			</div>
		</div>
		
		<div class="mb-3 col-12 col-md-10 col-lg-10 mx-auto">
			<div class="sai_form_head">'.__('Export Settings').'</div>
			<div class="sai_form p-3">
				<div class="row">
					<div class="col-12 col-md-5">
						<label class="sai_head d-block">'.__('Export Settings').'
							<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" title="'.__('Export the $0 settings that you can use to import in future or replicate over multiple servers.', [APP]).'"></i>
						</label>
					</div>
					<div class="col-12 col-md-7">
						<button class="btn btn-primary">
							<a href="'.$globals['ind'].'act=import_export&download=softaculous_settings.zip"  class="text-decoration-none" style="color:#fff;">
								'.__('Export Settings').'
							</a>
						</button>
					</div>
				</div>
			</div>
		</div>	
'.csrf_display().'
	</form>
</div>';

	softfooter();

}

