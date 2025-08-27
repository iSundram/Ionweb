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

function password_strength_theme(){

	global $theme, $globals,  $error, $softpanel , $done, $pass_form;
	
	softheader(__('Set Password Strength'));
	
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		'.__('Set Password Strength').'
	</div>
</div>
<div class="soft-smbox p-4 mt-4">';
	
	error_handle($error);
	echo '
	<form accept-charset="'.$globals['charset'].'" name="password_strength" id="passwordstrength" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload>
		
		
		<div class="container">
			<div class="row pb-3">
				<div class="col-lg-4 col-md-4">
					<label class="sai_head">'.__('Default Password Strength').'</label>
					<p>'.__('Drag the slider to set the password strength from 0 to 100. Setting to 0 disables the check.').'</p>
				</div>
				<div class="col-lg-6 col-md-6">
					<input type="range" style="margin-top: 8px;" class="form-range pass-strength" min="0" max="100" step="1" id="default_range" oninput="updateTextInput(this)"
					value="'.$globals['pass_score']['default'].'">
				</div>
				<div class="col-lg-2 col-md-2 ">
					<input class="form-control pass-strength text-center" name="defualt_strength" type="text" id="defualt_passrange" autofill="off" autocomplete="off" size="3" onkeyup="updateTextInput(this)" value="'.$globals['pass_score']['default'].'">	
				</div>
			</div>
			<hr>
			
			<p>'.__('You can set different strength values for different types of password(s)').'</p>';
			
			foreach($pass_form as $k => $v){
				
				echo '
				<div class="row pb-3">	
					<div class="col-lg-3 col-md-3" style="margin-top: 8px;">'.$v['name'].' : </div>
					<div class="col-lg-2 col-md-2" style="margin-top: 8px;">
						<label><input type="radio" name="'.$k.'" value="default" '.($v['default'] == 'default' ? 'checked' : '').'> '.__('Default').'</label>
					</div>
					<div class="col-lg-5 col-md-5" style="white-space: nowrap;">
						<input class="radio_enable" type="radio" name="'.$k.'" value="value" '.($v['default'] != 'default' ? 'checked' : '').'>
						<input type="range" style="margin-top: 7px;" class="form-range pass-strength" min="0" max="100" step="1" oninput="updateTextInput(this)" value="'.$v['value'].'">
					</div>
					<div class="col-lg-2 col-md-2 m-2px">
						<input class="form-control pass-strength text-center" name="'.$k.'_value" type="number"  autofill="off" min="0" max="100" autocomplete="off" size="3" oninput="updateTextInput(this)" value="'.$v['value'].'">	
					</div>
				</div>';
			}
			
			echo '
			<div class="text-center">
				<input type="submit" class="btn btn-primary" name="password_strength" value="'.__('Edit Settings').'"/>
			</div>
		</div>
	</form>

	<script>
	function updateTextInput(ele){
		var jEle = $(ele);
		var val = jEle.val();
		var pEle = jEle.closest(".row").find(".pass-strength");
		var rEle=jEle.closest(".row").find(".radio_enable");
		rEle.attr("checked", "checked");
		pEle.each(function(){
			$(this).val(val);
		});
	}
	
	</script>';
	softfooter();
	
}

