<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function editemailtemp_theme() {
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
function confirm_reset() {
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