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

function editemailtemp_theme(){

global $theme, $globals, $kernel, $user, $error, $emailtemp, $done, $notice, $tempname, $ll, $langs;

softheader(__('Edit Email Template'));
echo '
<div class="soft-smbox p-3 col-12 col-md-8 mx-auto">
	<div class="sai_main_head">
		<i class="fa fa-envelope fa-xl me-2"></i>'.__('Edit Email Template').'
	</div>
</div>
<div class="soft-smbox p-4 col-12 col-md-8 mx-auto mt-4">
<form accept-charset="'.$globals['charset'].'" name="editemailtemp" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)">
	<div class="sai_form">';

error_handle($error);

echo '
		<label class="sai_head">'.__('Language').':</label>	
		<select name="changelang" class="form-select mb-3" id="changelang">';
		
	foreach($langs as $k => $v){
		echo '
			<option value="'.$v.'" '.(empty($_REQUEST['editlang']) && $globals['language'] == $v ? 'selected="selected"' : (@trim($_REQUEST['editlang']) == $v ? 'selected="selected"' : '') ).'>'._ucfirst($v).'</option>';
	}
		echo '
		</select>
		<label class="sai_head">'.__('Subject').':</label>	
		<input type="text" class="form-control mb-3" name="tempsub" value="'.htmlizer($ll['title']).'" size="73">
		<label class="sai_head">'.__('Content').':</label>
		<textarea  class="form-control mb-3" cols="70" name="tempcontent" rows="16">'.htmlizer($ll['body']).'</textarea>
		<label class="sai_head">
			<input type="checkbox" name="ishtml" '.POSTchecked('ishtml', $ll['ishtml']).' class="me-1" />'.__('Send as HTML').'
		</label><br />
		<label class="sai_head">'.__('$0 Note $1 : You can use an IF for an Optional Variable in the following manner', ['<strong>', '</strong>']).' :<br /><strong>&lt;if $VARIABLE_NAME&gt;$VARIABLE_NAME&lt;/if&gt;</strong></label>
		<div class="text-center mt-3">
			'.csrf_display().'
			<input type="submit" name="savetemplate" value="'.__('Save Email Template').'" class="btn btn-primary">
			<input type="button" name="savetemplate" value="'.__('Reset Template').'" class="btn btn-danger" onClick="confirm_reset();">
		</div>
		<center class="mt-3">
			<a href="'.$globals['index'].'act=emailtemp" class="sai_head">'.__('Templates Overview').'</a>
		</center>
	</div>
</form>
</div>

<script language="javascript" type="text/javascript">
function confirm_reset(){
	var r = confirm("'.__js('Are you sure you want to Reset the email template ?').'");
	if(r != true){
		return false;
	}else{
		window.location = window.location+"&reset='.$tempname.'";
	}
}

$(document).ready( function() {
	$("#changelang").change( function() {
		location.href = "'.$globals['index'].'act=editemailtemp&temp='.$tempname.'&editlang="+$(this).val();
	});
});
</script>';

	softfooter();

}

