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

function help_theme(){

global $user, $globals, $theme, $softpanel, $WE, $catwise, $error, $faq_list;

	softheader(__('Help'));
	
	echo '
	<div class="card soft-card p-4 col-12 col-md-8 mx-auto">
		<div class="sai_main_head text-center mb-4">
			<h4>'.__('Frequently Asked Questions').'</h4>
		</div>
		<div class="accordion" id="helpSupport">';
	$i = 1;
	foreach($faq_list as $fk => $fv){
		$expand = false;
		$show = '';
		$collapse = "collapsed";
		if($i==1){
			$expand = true;
			$show = "show";
			$collapse = "";
		}

		echo '
		<div class="accordion-item">
			<h2 class="accordion-header" id="faqhead'.$i.'">
				<button class="accordion-button '.$collapse.'" type="button" data-bs-toggle="collapse" data-bs-target="#faq'.$i.'" aria-expanded="'.$expand.'" aria-controls="faq'.$i.'">
					'.$fv['question'].'
				</button>
			</h2>
			<div id="faq'.$i.'" class="accordion-collapse collapse '.$show.'"  aria-labelledby="faqhead'.$i.'" data-bs-parent="#helpSupport">
				<div class="accordion-body p-1">
					<p class="sai_ans mb-0">'.$fv['answer'].'</p>
				</div>
			</div>
		</div>';
		$i++;
	}
	
	echo '
		</div>
	</div>';

	softfooter();

}

?>