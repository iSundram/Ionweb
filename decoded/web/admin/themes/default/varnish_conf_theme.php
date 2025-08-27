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

function varnish_conf_theme(){

	global $theme, $globals, $user, $langs, $error, $saved, $done, $filename, $iapps, $varnishid;

	softheader(__('Varnish Configuration'));

	echo '
<div class="soft-smbox col-12 col-md-10 mx-auto p-3">
	<div class="sai_main_head text-center">
		<img src="'.$theme['images'].$varnishid.'_icon.png" />&nbsp;'.$iapps[$varnishid.'_1']['name'].' '.__('Configuration').'
	</div>
	<hr>';
	
	$linecount = count(file($filename));
	
	echo '
	<form accept-charset="'.$globals['charset'].'" name="editvarnish" method="post" action="" id="editvarnish" class="form-horizontal" onsubmit="return submitit(this)">
		<div class="row">
			<div class="col-12 ContentDivs">
				<div class="row ini-data m-3">
					<div class="col-2 col-sm-1 text-center line-numbers">';
						for($i=1; $i<=$linecount;$i++){
							echo $i."<br />";
						}
					echo '
					</div>
					<div class="col-10 col-sm-11">
						<textarea class="form-control p-0" WRAP=OFF name="varnish_data" id="varnish_data" rows='.$linecount.'>'.htmlentities(file_get_contents($filename), ENT_QUOTES, "UTF-8").'</textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="text-center">
			<input type="submit" value="'.__('Save').'" name="savevarnish" class="btn btn-primary" id="savevarnish" />
		</div>
	</form>
</div>';

	softfooter();
}

