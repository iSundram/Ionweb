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

function domain_forwarding_theme(){

global $globals, $theme, $error;
	
	softheader(__('Domain Forwarding'));

	error_handle($error, "100%");

	echo '
<div class="col-12 col-md-11 mx-auto soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fa-solid fa-share fa-xl me-1"></i>'.__('Domain Forwarding').'
	</div>
</div>
<div class="col-12 col-md-11 mx-auto soft-smbox p-4 mt-4">
	<div class="mb-5 mt-5 col-md-6" style="background-color:#e9ecef;margin:0 auto;">
		<div class="" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" action="" method="post" id="editform" class="form-horizontal" onsubmit="return submitit(this)" data-doneredirect="'.$globals['ind'].'act=redirect_list">
				<div class="row p-3 col-md-12 d-flex">
					<div class="col-12 col-md-12 m-2">
						<label class="form-label">'.__('Select Domain').'</label>
						<select class="form-select form-control ms-1 make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act=domains&api=json" s2-query="dom_search" s2-data-key="domains" s2-data-subkey="domain" s2-data-display-handler="handleDomainOwner" style="width: 100%" name="dom_search" id="dom_search">
						</select>
					</div>
					<div class="col-12 col-md-12 m-2" >
						<label for="path" class="form-label">'.__('Path').'</label>
						<span class="sai_exp2 d-block">'.__('Type the Redirect path without the domain name').'</span>
						<div class="input-group ">
							<span class="input-group-text">/</span>
							<input type="text" name="path" class="form-control" />
						</div>
					</div>
					<div class="col-12 col-md-12 m-2" >
						<label for="type" class="form-label">'.__('Type').'</label>
						<select name="type" class="form-select">
							<option value="temporary">'.__('Temporary (302)').'</option>
							<option value="permanent">'.__('Permanent (301)').'</option>
						</select>
					</div>
					<div class="col-12 col-md-12 m-2" >
						<label for="address" class="form-label">'.__('Address').'</label>
						<span class="sai_exp2 d-block">'.__('Address must begin with a protocol like https://').'</span>
						<input type="text" name="address" class="form-control " required/>
					</div>
					<center>
						<input type="submit" class="btn btn-primary dom_redirect" value="'.__('Redirect').'" title="'.__('Redirect').'" name="save" >
					</center>
				</div>
			</form>
		</div>
	</div>
</div>

<script>

// Handle the UI
function handleDomainOwner(item){
	return {
		text: item["domain"]+" ("+item["user"]+")",
		id: item["domain"]
	}
}
</script>';

	softfooter();
	
}
