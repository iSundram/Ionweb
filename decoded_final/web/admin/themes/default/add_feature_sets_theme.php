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

function add_feature_sets_theme(){
global $user, $globals, $theme, $softpanel, $error ,$done, $plan_fields, $feature, $features_list;

	softheader(__('Add / Edit Features Sets'));
	
	form_js();

echo '
<div class="col-12 col-md-12 mx-auto">
	<form accept-charset="'.$globals['charset'].'" name="addpackageform" id="addpackageform" method="post" action=""; class="form-horizontal" onsubmit="return submitit(this)">
		<div class="soft-smbox sai_main_head mb-4 p-3">
			<i class="fas fa-sliders-h me-2"></i>'.(!empty($feature) ? __('Edit Feature Set') : __('Add Feature Set')).'
		</div>';
	error_handle($error);
	
	echo '
		<div class="soft-smbox mb-3">
			<div class="sai_form_head">'.(!empty($feature) ? __('Edit Feature Set') : __('Add Feature Set')).'</div>
			<div class="sai_form">
				<div class="row">
					<div class="col-12 col-sm-4 col-lg-2">
						<label for="features_name" class="sai_head">'.__('Feature Set Name').'</label>
					</div>
					<div class="col-12 col-sm-8 col-lg-6">
						<input type="text" name="features_name" id="features_name" class="form-control" value="'.POSTval('features_name', $feature['feature_sets']).'" />
					</div>
				</div>
			</div>
		</div>
		<div class="soft-smbox mb-3">
			<div class="sai_form_head">'.__('Features').'</div>
			<div class="sai_form">';
	
	foreach($features_list as $key => $props) {
		echo call_user_func_array('form_type_'.$props['type'], array($key, $props, &$feature[$key]));
	}
	
	echo '
			</div>
		</div>
		<div class="text-center">
			<input type="submit" class="btn btn-primary" id="create_features" name="create_features" value="'.__('Save').'"/>
		</div>
	</form>
</div>';
	
	softfooter();
	
}
