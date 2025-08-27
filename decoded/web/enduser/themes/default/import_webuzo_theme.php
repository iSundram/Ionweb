<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function import_webuzo_theme() {
});
$("#submitftp").click(function(){
$("#import_log_a").click();
});
$("#isbackup").on("change", function(){
if($("#isbackup").is(":checked")){
$("#r_pass").val("");
$("#r_domain").val("");
$("#r_user").val("");
$("#no_backup").hide();
$("#backup").show();
$(".with-pass").hide();
}else{
$("#r_backup").val("");
$("#backup").hide();
$("#no_backup").show();
$(".with-pass").show();
}
});
function get_logs() {
}
$("#showlog").text(data["import_log"]);
}else{
handleResponseData(data);
}
}
});
}
function refresh_logs() {
}
};
softfooter();
}