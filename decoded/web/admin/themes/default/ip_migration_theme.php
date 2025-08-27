<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function ip_migration_theme() {
};
submitit(d, {handle:function(data, p){
if(data.error){
var err = Object.values(data.error);
var a = show_message_r("'.__js('Error').'", err);
a.alert = "alert-danger"
show_message(a);
return false;
}
$("#ip_form").hide();
$("#div_iplist").show();
var form = get_ips_list(data["ips_user_arr"], data["valid_ips"]);
$("#iplist").html(form);
}
});
});
$("#changeip").click(function(){
var arr = [];
var newip, oldip;
$(".input_ips").each(function(ik, iv){
newip = $(this).val();
oldip = $(this).data("oldip");
if(!empty(newip)){
arr.push(oldip+"-"+newip);
}
})
var d = {"changeip": 1, "ip_migrate" : arr};
submitit(d, {handle:function(data, p){
if(data.error){
var err = Object.values(data.error);
var a = show_message_r("'.__js('Error').'", err);
a.alert = "alert-danger"
show_message(a);
return false;
}
var download = "<br/><a href=\"javascript:void(0)\" onclick=\"download_matrix()\" id=\"download_matrix\" >"+"'.__js('Click here to download IP translation matrix').'"+"</a>";
var task = "<br/><a href=\"javascript:loadlogs("+data.done.taskid+");\"><b>Click here to view the logs</b></a>";
if(!empty(data.done.msg)){
var a = show_message_r("'.__js('Done').'", data.done.msg + download + task);
a.alert = "alert-success"
a.ok.push(function(){
location.reload();
});
show_message(a);
}
}
});
});
function get_ips_list() {
})
html += "</ul></td></tr>";
i++;
});
return html;
}
function download_matrix() {
}
</script>';
softfooter();
}