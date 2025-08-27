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

function spamd_startup_config_theme(){

global $theme, $globals, $user, $langs, $error, $done, $spamd_conf;

	softheader(__('Spamd Startup Configuration'));
	error_handle($error);
	
	echo '
<div class="soft-smbox p-3 col-12 col-md-10 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-cog fa-xl me-2"></i>'.__('Spamd Startup Configuration').'
	</div>
</div>
<div class="soft-smbox p-4 col-12 col-md-10 mx-auto mt-4">
	<form accept-charset="'.$globals['charset'].'" name="spamdstartconfigs" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1>
	<div class="sai_form">
	<div class="row">
		<div class="col-12 col-md-12">
			<label class="sai_head">'.__('Allowed IPs').'
				<span class="sai_exp">'.__('Allow connections to spamd to specific IP address (e.g. 127.0.0.1)').'</span>
			</label>
			<input class="form-control mb-3" type="text" name="allowedip" value="'.(!empty($spamd_conf['-A']) ? $spamd_conf['-A'] : $spamd_conf['--allowed-ips']).'"/>
		</div>
		<div class="col-12 col-md-6">
			<label class="sai_head">'.__('Maximum Connections per Child').' 
				<span class="sai_exp">'.__('Number of connections to spamd child before the process is abandoned').'</span>
			</label>
			<input class="form-control mb-3" type="text" name="max_con_child" value="'.(!empty($spamd_conf['--max-conn-per-child']) ? $spamd_conf['--max-conn-per-child'] : '').'"/>
		</div>
		<div class="col-12 col-md-6 mb-3">
			<label class="sai_head">'.__('Maximum Children').'
				<span class="sai_exp">'.__('Number of Spamd child processes spawned upon startup $0 Range (1 - 5)', ['<br>']).'</span>
			</label>
			<input class="form-control mb-3" type="text" name="max_child" value="'.(!empty($spamd_conf['-m']) ? $spamd_conf['-m'] : $spamd_conf['--max-children']).'" placeholder="1 - 5"/>
		</div>
		<div class="col-12 col-md-6">		
			<label class="sai_head">'.__('TCP Timeout').'
				<span class="sai_exp">'.__('Time (in seconds) before TCP connection is abandoned').'</span>
			</label>
			<input class="form-control mb-3" type="text" name="tcp_timeout" value="'.(!empty($spamd_conf['--timeout-tcp']) ? $spamd_conf['--timeout-tcp'] : '').'"/>
		</div>
		<div class="col-12 col-md-6">
			<label class="sai_head">'.__('TCP Child Timeout').'
				<span class="sai_exp">'.__('Time (in seconds) before TCP connection is abandoned by child process').'</span>
			</label>
			<input class="form-control mb-3" type="text" name="tcp_child_timeout" value="'.(!empty($spamd_conf['--timeout-child']) ? $spamd_conf['--timeout-child'] : '').'"/>
		</div>
	</div>
	<div class="text-center">
		<input type="submit" name="spamdconfigs" value="'.__('Update').'" class="btn btn-primary" />
	</div>
	</div>
	</form>
</div>';

	softfooter();

}
