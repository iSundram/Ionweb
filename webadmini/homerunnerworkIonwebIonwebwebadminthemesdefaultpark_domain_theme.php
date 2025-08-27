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

function park_domain_theme(){

global $globals, $theme, $error;
	
	softheader(__('Park a Domain'));

	error_handle($error, "100%");
	
	echo '
<div class="col-12 col-md-11 mx-auto soft-smbox p-4">
	<div class="sai_main_head">
		<i class="fas fa-globe fa-lg me-1"></i>
		<h5 class="d-inline-block">'.__('Park a Domain').'</h5>
	</div>
</div>
<div class="col-12 col-md-11 mx-auto soft-smbox p-4 mt-4">
	<div class="mb-5 mt-5 col-md-6" style="background-color:#e9ecef;margin:0 auto;">
		<div class="" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
				<div class="row p-3 col-md-12 d-flex">
					<div class="col-12 col-md-12 m-2">
						<label class="form-label">'.__('Park Domain to').'</label>
						<select class="form-select form-control ms-1 make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act=domains&api=json" s2-query="dom_search" s2-data-key="domains" s2-data-subkey="domain" s2-data-display-handler="handleDomainOwner" style="width: 100%" name="dom_search" id="dom_search">
						</select>
					</div>
					<div class="col-12 col-md-12 m-2">
						<label for="domain" class="form-label">'.__('Add Domain').'</label>
						<input type="text" id="park_domain_val" name="domain" style="width:100%">
					</div>
					<center>
						<button type="submit" id="add_parke_domain" name="save" value="1" class="btn btn-primary save " title="'.__('Park').'">'.__('Park').'</button>
					</center>
				</div>
			</form>
		</div>
	</div>
</div>

<script>

// For save
$("#add_parke_domain").click(function() {	
	var park_domain = $("#park_domain_val").val();
	var redirect_to = $("#dom_search").val();
	var data = {"add" : 1, "domain" : park_domain, "redirect_to": redirect_to};
	submitit(data,{
		done_reload : "'.$globals['index'].'act=alias_domains"
	});
});

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
