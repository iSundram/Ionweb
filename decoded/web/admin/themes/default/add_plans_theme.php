<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function add_plans_theme() {
}
echo '
</div>
</div>
</div>';
}
echo '
<div class="text-center">
<input type="submit" class="btn btn-primary" id="create_plan" name="create_plan" value="'.__('Save Plan').'"/>
</div>
</form>
</div>';
echo '
<script language="javascript" type="text/javascript">
$(document).on("change", "#feature_sets", function() {
var val = $(this).val();
if(val && val !== "0") {
$("[key=features]").hide();
} else {
$("[key=features]").show();
}
});
$(document).ready(function (){
$("#feature_sets").change();
});
$("form[name=addpackageform] [name=reseller]").on("change", handleResellercheck);
function handleResellercheck() {
}
}
</script>';
softfooter();
}