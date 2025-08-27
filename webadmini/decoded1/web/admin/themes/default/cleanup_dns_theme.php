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

function cleanup_dns_theme(){

global $globals, $theme, $error, $done;
	
	softheader(__('Cleanup DNS'));

	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'backup_restore.png" class="webu_head_img me-1"/>'.__('Cleanup DNS').'
	</div>	
</div>	
<div class="soft-smbox p-4 mt-4">
	<div class="row mb-3">
		<label class="text-warning" font-size:20px;><b>'.__('Warning !!!').'</b></label>
	</div>
	<p>'.__('This program will clean up your named config file and remove any duplicate entries. Please make sure you are not editing any nameserver configuration files during the clean up.').'</p>
	<div class="sai_sub_head record-table mb-2 position-relative" style="text-align:center;">
		<input type="button" class="btn btn-primary cleanup" value="'.__('Proceed').'" name="cleanup_proceed" >
	</div>';
	
	error_handle($error, "100%");
	
	echo '
</div>

<script>

$(document).on("click", ".cleanup", function() {	
	var data = {"cleanup" : 1};
	submitit(data);
});


</script>';

	softfooter();
	
}
