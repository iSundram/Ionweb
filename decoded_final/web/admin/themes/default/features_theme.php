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

function features_theme(){

global $globals, $theme, $features, $error, $done;

	softheader(__('Features'));

	error_handle($error);

	echo '
<form accept-charset="'.$globals['charset'].'" name="editfeatures" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)">
	<div class="soft-smbox p-3">
		<div class="sai_main_head">
			<i class="fa fa-align-justify me-2"></i>'.__('Disable Features').'
		</div>
	</div>';
	error_handle($error);
	
	echo '
	<div class="soft-smbox p-4 mt-4">
		<div class="sai_form">
			<div class="row">';
					
	foreach($features as $fk => $fv){
		
		foreach($fv['icons'] as $k => $v){
			$name = 'disable_'.$k;
			echo '
				<div class="col-12 col-md-6 col-lg-3 mb-3">
					<label class="sai_head label-secondary checkbox-inline p-2" for="'.$name.'" >
						<input type="checkbox" name="'.$name.'" id="'.$name.'"'.POSTchecked($name, !empty($globals['features'][$name])).' /> '.__('Disable').' '.$v['name'].'&nbsp;
					</label><br />
					<span class="sai_exp2">'.$v['exp'].'</span>
				</div>';
					
		}
	}

	echo '
			</div>
			<div class="text-center mt-4 mb-2">
					'.csrf_display().'
				<input type="submit" name="save" class="btn btn-primary" value="'.__('Edit Settings').'"/>
			</div>
		</div>
	</div>
</form>';

	softfooter();

}


