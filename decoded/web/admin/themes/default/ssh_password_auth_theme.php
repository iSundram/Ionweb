<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function ssh_password_auth_theme() {
}else{
echo '
<div class="alert alert-danger d-flex justify-content-center" role="alert">
'.__('SSH Password Authorization is currently disabled').'
</div>';
}
echo '
<div class="text-center">
<p class="sai_sub_head d-inline-block">'.__('We recommended you to disable SSH Password Authentication and use the SSH Key to SSH your server. You can generate the auth keys from the $0 Manage Root SSH Keys $1 wizard', ['<a href="'.$globals['index'].'act=manage_root_ssh_keys">', '</a>']).'</p>
</div>
<div class="sai_form">';
echo '
<div class="text-center">
<input type="hidden" name="action" value="'.((int)(!$globals['ssh_auth_enabled'])).'">
<input type="submit" name="change" class="btn btn-primary" value="'.($globals['ssh_auth_enabled'] == 1 ? __('Disable') : __('Enable')).'"/>
</div>
</div>
</form>
</div>';
softfooter();
}