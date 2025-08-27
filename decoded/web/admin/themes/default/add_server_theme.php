<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function add_server_theme() {
}
echo'<br/></select><span class="help-block"></span>
</div>
</div>
<div class="row mx-auto w-100 my-3">
<div class="col-4">
<label class="control-label">'.__('Lock Server').'&nbsp;</label>
</div>
<div class="col-4">
<input class="mb-2 mt-2" type="checkbox" name="locked" '.POSTchecked('locked', $server['locked']).' value="1">
</div>
</div>
<center><input type="submit" name="add_server" class="flat-butt btn btn-primary" value="'.__('Submit').'" /></center>
</form>
</div>';
softfooter();
}