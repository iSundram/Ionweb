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

function resolver_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $SESS, $resolvers;
	
	softheader(__('Resolver Configuration'));
	//r_print($resolvers);
echo '
<div class="soft-smbox p-3 col-12 col-md-8 mx-auto">
	<div class="sai_main_head">
		'.__('Resolver Configuration').'
	</div>
</div>
<div class="soft-smbox p-3 col-12 col-md-8 mx-auto mt-4">
	<form accept-charset="'.$globals['charset'].'" name="resolve_conf" id="resolve_conf" method="post" action=""; class="form-horizontal" onsubmit="return submitit(this)">
		<div class="alert alert-warning col-sm mb-3">
			<i class="fas fa-info-circle"></i> <span>'.__('Its very important that these nameservers are correct, or your server will not function properly. If you dont know what to put in the boxes below then close this window and contact your server provider').'</span>
		</div>
		<div class="sai_form">';	
			echo '
			<div class="row mb-3">
				<div class="col-12 col-md-5">
					<label for="mail" class="sai_head">'.__('Primary Resolver').'</label>
				</div>
				<div class="col-12 col-md-7">
					<input class="form-control" type="text" name="primary" id="conf" size="15" value="'.$resolvers[0].'" />
				</div>
			</div>	
			<div class="row mb-3">
				<div class="col-12 col-md-5">
					<label for="mail" class="sai_head">'.__('Secondary Resolver').'</label>
				</div>
				<div class="col-12 col-md-7">
					<input class="form-control" type="text" name="secondary" id="conf" size="15" value="'.$resolvers[1].'" />
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-12 col-md-5">
					<label for="mail" class="sai_head">'.__('Tertiary Resolver (Optional)').'</label>
				</div>
				<div class="col-12 col-md-7">
					<input class="form-control" type="text" name="tertiary" id="conf" size="15" value="'.$resolvers[2].'" />
				</div>	
			</div></br>';
			echo'
			<div class="text-center mt-3">
				<input class="btn btn-primary me-1" type="submit" value="Submit" name="resolve_conf" id="submitresovler"/> 
			</div>
		</div>
	</form>
</div>
<script>
</script>';
	softfooter();
}

