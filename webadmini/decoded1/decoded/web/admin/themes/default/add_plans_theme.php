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

function add_plans_theme(){

global $user, $globals, $theme, $softpanel, $error ,$done, $langs, $plan_fields, $tags, $plan;

	softheader(__('Add/Edit Plan'));
		
	form_js();

	echo '
<div class="col-12 col-md-12 mx-auto">
	<form accept-charset="'.$globals['charset'].'" name="addpackageform" id="addpackageform" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-doneredirect="'.$globals['admin_index'].'act=plans">
		<div class="soft-smbox">
			<div class="sai_main_head  p-3"><i class="fas fa-book me-2"></i>
				'.(!empty($plan) ? __('Edit Plan') : __('Add Plan')).'
			</div>
		</div>';
	error_handle($error);
			
	echo '
		<div class="soft-smbox my-3">
			<div class="sai_form_head">'.(!empty($plan) ? __('Edit Plan') : __('Add Plan')).'</div>
			<div class="sai_form p-3">
				<div class="row d-flex align-items-center">
					<div class="col-12 col-sm-4 col-lg-2">
						<label for="plan_name" class="sai_head">'.__('Plan Name').'</label>
					</div>
					<div class="col-12 col-sm-8 col-lg-6">
						<input type="text" name="plan_name" id="plan_name" class="form-control" value="'.POSTval('plan_name', $plan['plan_name']).'" />
					</div>
				</div>
			</div>
		</div>';
		
	foreach($plan_fields as $cat => $c){		
		echo '
		<div class="soft-smbox mb-4">
			<div class="sai_form_head">'.$c['name'].'</div>
			<div class="sai_form p-3">
				<div class="row">';
					foreach ($c['list'] as $key => $props) {
						echo call_user_func_array('form_type_'.$props['type'], array($key, $props, &$plan[$key]));
					}

		echo '
				</div>
			</div>
		</div>';			
	}
				
	echo '
		<div class="text-center">
			<input type="submit" class="btn btn-primary" id="create_plan" name="create_plan" value="'.__('Save Plan').'"/>
		</div>
	</form>
</div>';

	echo '
<script language="javascript" type="text/javascript">
$(document).on("change", "#feature_sets", function() {
	var val = $(this).val();
	if(val && val !== "0") {
		$("[key=features]").hide();
	} else {
		$("[key=features]").show();
	}
});

$(document).ready(function (){
	$("#feature_sets").change();
});

$("form[name=addpackageform] [name=reseller]").on("change", handleResellercheck);

function handleResellercheck(){
	var plan = '.json_encode($plan).';

	if(!empty(plan.plan_name) && !$(this).prop("checked") && !empty(plan.reseller)){
		alert("'.__('Warning: The Make Reseller option is currently unchecked. If you decide to uncheck Make Reseller option, the users associated with this plan will no longer have reseller, and ownership of these associated users will be owned by the root user.').'");
	}
}


</script>';

	softfooter();
	
}