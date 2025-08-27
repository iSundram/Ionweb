<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function add_modsec_vendors_theme() {
})
});
$("#loadvendor").click(function(){
var vendor_conf_url = $("#vendor_conf_url").val();
var confirmbox = "'.__js('The given path seems to be unsafe! Are you sure you want to continue?').'";
var dc = "vendor_conf_url="+vendor_conf_url+"&is_safe=1";
var lang = confirmbox;
var no = function(){
location.reload(true);
}
submitit(dc, {
handle:function(data){
if(empty(data.done) && !vendor_conf_url.toLowerCase().startsWith("http")){
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
load_modsec_vendor(vendor_conf_url, 1);
});
a.no.push(no);
show_message(a);
}else{
load_modsec_vendor(vendor_conf_url);
}
}
});
});
function load_modsec_vendor() {
}else{
$("#vendor_name").val("");
$("#vendor_desc").val("");
$("#vendor_url").val("");
$("#report_url").val("");
$("#path").val("");
$("#add_vendor").attr("disabled",true);
}
}
});
}
</script>';
softfooter();
}