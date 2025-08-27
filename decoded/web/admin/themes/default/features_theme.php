<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function features_theme() {
}
}
echo '
</div>
<div class="text-center mt-4 mb-2">
'.csrf_display().'
<input type="submit" name="save" class="btn btn-primary" value="'.__('Edit Settings').'"/>
</div>
</div>
</div>
</form>';
softfooter();
}