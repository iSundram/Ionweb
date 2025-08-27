<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function updates_theme() {
}
$curr_version = (!empty($done) ? $report['version'] : $globals['version']);
$latest_version = (empty($info['version']) ? __('Could not connect to $0', [APP]) : $info['version']);
echo '
<form accept-charset="'.$globals['charset'].'" name="updatesoftaculous" method="post" action="" onsubmit="return submitit(this)">
<label class="sai_head d-block">
'.__('Current Version').':
<span class="ms-1">'.$curr_version.'</span>
</label>
<label class="sai_head">'.__('Latest Version').': '.($curr_version != $latest_version ? '
<span class="ms-1" style="color:#FF0033;">' : '<span class="ms-1">').$latest_version.'</span>
</label>
<div class="sai_bboxtxt mt-3" style="color:#767676;">'.$info['message'].'</div>
'.($curr_version != $latest_version ? '
<div class="text-center">
<input type="submit" name="update" value="'.__('Update $0', [APP]).'" '.(empty($info['link']) ? 'disabled="disabled"' : '').' class="btn btn-primary" />
</div>' : '').'
'.csrf_display().'
</form>
</div>
</div>';
softfooter();
}