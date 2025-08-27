<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function import_da_theme() {
});
function e_scan_backups() {
}else{
$(".scanbackuppath").addClass("d-none");
}
if($("#backupop_div").is(":visible")){
$("#backupop_div").hide();
}
}
$("#enduser_import_scan").click(function(){
$("#backupop_div").hide();
var host = $("#r_domain").val();
var username = $("#r_user").val();
var pass = $("#r_pass").val();
var backup_path = $("#scan_backups_path").val();
var d = {"create_acc": 1, "scan_backups": 1, "r_domain" : host, "r_user" : username, "r_pass" : pass, "scan_backups_path" : backup_path};
submitit(d, {
handle:function(data, p){
var resp = data;
if(resp.error){
var err = Object.values(resp.error);
var a = show_message_r("'.__js('Error').'", err);
a.alert = "alert-danger"
show_message(a);
return false;
}
$("#backupop_div").show();
if(empty(data.backups_list)){
$("#backupop_div").html("<label class=\"sai_head alert alert-warning\">'.__js('No Backups found on DirectAdmin').'</label>");
$(".loading").hide();
return false;
}
blist = \'<label for="backupop_name" class="sai_head">'.__js('Select Bakcup to import').'</label><br><span class="sai_exp2">'.__js('This process will import data from the selected backup file.').'<br/>'.'</span><select class="form-control" name="backupop_name" id="backupop_name" >\';
$.each(data.backups_list, function(key,val){
blist += \'<option value="\'+val+\'">\'+val+\'</option>\';
})
blist += \'</select>\';
$("#backupop_div").show();
$("#backupop_div").html(blist);
$(".loading").hide();
}
});
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