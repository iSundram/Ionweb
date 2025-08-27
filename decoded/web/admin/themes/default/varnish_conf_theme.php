<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function varnish_conf_theme() {
}
echo '
</div>
<div class="col-10 col-sm-11">
<textarea class="form-control p-0" WRAP=OFF name="varnish_data" id="varnish_data" rows='.$linecount.'>'.htmlentities(file_get_contents($filename), ENT_QUOTES, "UTF-8").'</textarea>
</div>
</div>
</div>
</div>
<div class="text-center">
<input type="submit" value="'.__('Save').'" name="savevarnish" class="btn btn-primary" id="savevarnish" />
</div>
</form>
</div>';
softfooter();
}