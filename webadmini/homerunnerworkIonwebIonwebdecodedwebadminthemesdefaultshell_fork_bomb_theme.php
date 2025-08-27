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

function shell_fork_bomb_theme(){

global $theme, $globals, $langs, $error, $done;

	softheader(__('Shell Fork Bomb'));

	error_handle($error);

	echo '
<div class="soft-smbox col-12 col-md-11 p-3 mx-auto">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'shell_fork_bomb.png" width="40" height="40"/>'.__('Shell Fork Bomb Protection').'
	</div>
</div>
<div class="soft-smbox col-12 col-md-11 p-3 mx-auto mt-4">
	<form accept-charset="'.$globals['charset'].'" name="shell_bomb_action" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1 >';
		if(empty($globals['shell_fork_enabled'])){	
		
			echo '	
		<div class="alert alert-danger d-flex justify-content-center" role="alert">
			'.__('Shell Fork bomb is currently disabled.').'
		</div>';
		
		}else{
			
			echo '	
		<div class="alert alert-info d-flex justify-content-center" role="alert">
			'.__('Shell Fork bomb is currently Enabled.').'
		</div>';
		
		}
		
		echo '
		<div class="sai_form">
			<h6>'.__('Description').'</h6><br>
			<p>'.__('Shell fork bomb protection limits the resources on the server given to the users who have access of terminal.  To prevent the server crashes, shell fork bomb do $0 not $1 allow unlimited processes and resource for the users.', ['<b>', '</b>']).'</p><br><br>
		
			<div class="text-center"> 
				<input type="submit" class="btn btn-primary" name="'.(!empty($globals['shell_fork_enabled'] ) ? 'disable' : 'enable').'" value="'.(!empty($globals['shell_fork_enabled'] ) ? __('Disable') : __('Enable')).'"/>
			</div>		
		</div>
	</form>
</div>';

	softfooter();

}

