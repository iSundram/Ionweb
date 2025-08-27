<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function editip_theme() {
}
echo '<label class="form-label">'.__('Note').'
<sapn class="sai_exp ms-1">'.__('Any notes for your reference').'</span>
</label>
<textarea class="form-control mb-3" name="note" rows="4">'.POSTval('note', $ip['note']).'</textarea>
<label class="form-label">'.__('Users').'
<span class="sai_exp">'.__('Users assign to this IP').'</span> :
</label>
<span class="ms-3">'.(empty($ip['users']) ? __('None') : implode(', ', $ip['users'])).'</span>
<span class="help-block"></span>
<div class="text-center">
<input type="submit" name="editip" class="btn btn-primary" value="'.__('Edit').'"/>
</div>
</form>
</div>';
softfooter();
}