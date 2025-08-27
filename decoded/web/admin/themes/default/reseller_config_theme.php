<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function reseller_config_theme() {
}else{
$("#ns_form").hide();
}
}
$(document).ready(function (){
show_custom_ns();
$("input[name=\"inherit_ns\"]").click(function(){
show_custom_ns();
});
});
</script>';
softfooter();
}