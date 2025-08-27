<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function errorlog_theme() {
};
if($("#errorLogType").val() == "soft_logfile"){
d.clear_log = "'.basename($softlog).'";
}else{
d.clear_log = "'.basename($filename).'";
}
submitit(d, {
sm_done_onclose:function(){
location.reload();
}
});
});
show_message(a);
}
var refreshInterval;
$( document ).ready(function(){
refreshInterval = setInterval(get_logs, 5000);
$("#errorlog_data").text('.json_encode($error_log_data['error_data']).');
});
function changelogs() {
}
if(optionVal == "error_logfile"){
eld.textContent = '.json_encode($error_log_data['error_data']).';
}
}
function get_logs() {
}, function(data){
var selectedValue = $("#errorLogType").val();
if(selectedValue == "error_logfile"){
$("#errorlog_data").text(data.error_log_data.error_data); return;
}
if(selectedValue == "soft_logfile"){
$("#errorlog_data").text(data.error_softlog_data.error_data); return;
}
});
}
</script>';
echo '
<div class="soft-smbox p-3 col-12 mx-auto">
<div class="sai_main_head">
<i class="far fa-file-alt me-1"></i>'.__('Error Logs').'
</div>
</div>
<div class="soft-smbox p-3 col-12 mx-auto mt-4">
<form accept-charset="'.$globals['charset'].'" name="errorlogform" method="post" action="" id="errorlogform" class="form-horizontal">
<div class="sai_form">';
error_handle($error);
echo '
<p class="alert alert-warning">'.__('Note: Latest logs appear at the top. Scroll down to see older entries.').'</p>
<div class="sai_reviewform mb-3">
<select class="form-select" name="errorLogType" onChange="changelogs(this);" id="errorLogType">
<option value="error_logfile">'.$filename.(!empty($error_log_data['file_size']) ? ' ('.$error_log_data['file_size'].')' : '').'</option>
'.($globals['logs_level'] > 0  ? '<option value="soft_logfile">'.$softlog.(!empty($error_softlog_data['file_size']) ? ' ('.$error_softlog_data['file_size'].')' : '').'</option>' : '').'
</select>
</div>
<textarea class="form-control log" name="errorlog_data" id="errorlog_data" rows="20" cols="250" readonly="readonly"></textarea>
<div class="text-center my-3">
<input type="button" value="'.__('Clear Logs').'" name="clear_log" class="btn btn-primary" onClick="confirm_reset();"/>
</div>
</div>
</form>
</div>';
softfooter();
}