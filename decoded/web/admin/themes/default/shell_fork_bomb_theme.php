<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function shell_fork_bomb_theme() {
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