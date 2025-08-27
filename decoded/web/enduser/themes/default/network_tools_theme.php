<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function network_tools_theme() {
}
echo'
</div>
</div>
</form>
<div id="domain_lookup_tab" style="display:none">
<div class="card soft-card p-4 shadow-none">
<div>
<span class="sai_head" id="lookup_title"></span>
<button class="btn dclose btn-secondary float-end">'.__('Close').'</button>
</div>
<div class="my-3">
<textarea class="form-control" name="kpaste" rows="18" readonly="readonly" id="lookup" wrap="off"></textarea>
</div>
</div>
</div>
</div>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
function this_submitit() {
}
}
});
}
$(".dclose").click(function(){
$("#domain_lookup_tab").slideUp("slide", "", 1000);
});
softfooter();
}