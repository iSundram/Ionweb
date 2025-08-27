<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function rearrange_account_theme() {
}else{
echo '
<div class="sai_form" id="formcontent">
<div class="row">
<table class="table sai_form webuzo-table">
<tbody>
<tr>
<td>
'.__('Account User Name').'
</td>
<td>
'.$_user['user'].'
</td>
</tr>
<tr>
<td>
'.__('Primary Domain Name').'
</td>
<td>
'.$_user['domain'].'
</td>
</tr>
<tr>
<td>
'.__('Current Home Directory').'
</td>
<td>
'.$_user['homedir'].'
</td>
</tr>
<tr>
<td>
'.__('Current Disk Usage').'
</td>
<td>
'.round($storage[$cur_storage]['used'] ,2).'/'.round($storage[$cur_storage]['size'],2).' GB ('.round($storage[$cur_storage]['free'],2).' GB Free)
</td>
</tr>
<tr>
<td>
'.__('New Storage').'
</td>
<td>';
if(count($storage) > 1){
echo'
<select class="form-select" name="storage" id="new_dest" >';
foreach($storage as $k => $v){
if($cur_storage == $k){continue;}
echo '<option value="'.$k.'" >'.$v['name'].'( '.$k.' - '.round($v['free'],2).' GB free )</option>';
}
echo '
</select>';
}else{
echo '<span>'.__('No extra storage available !').'</span>
<a href="'.$globals['index'].'act=addstorage" class="btn btn-primary" style="margin-left: 30px;">'.__('Add Storage').'</a>';
}
echo '
</td>
</tr>
</tbody>
</table>
</div>';
if(count($storage) > 1){
echo'
<div class="text-center">
<input type="submit" name="move_account" id="move_account" class="btn btn-primary" value="'.__('Move Account').'"/>
</div>';
}
echo '
</div>';
}
}
echo '
<div id="progress_bar" class="progress" style="display: none; height: 20px;">
<div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" id="progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;height: 20px;"><span><span></div>
</div>
<div class="text-center" id="done_msg">
</div>
<div class="text-center" id="showlogdiv" style="margin-top : 50px;display: none;" >
<p>Check your process logs </p>
<input type="hidden" id="taskid" />
<a href="javascript:void(0);" id="loadlogs"  class="btn btn-primary btn-logs">'.__('Show logs').'</a>
</div>
</div>
<script>
$(document).ready(function () {
$("#move_account").click(function(e){
$("#formcontent").hide();
var d = {"storage" : $("#new_dest option:selected").val(), "move_account":1};
submitit(d, {
after_handle:function(data, p){
if(empty(data.done)){
return false;
}
var actid = data.done.actid;
$("#taskid").val(actid);
progressbar(actid);
interval = setInterval(function(){
progressbar(actid)
}, 3000);
}
});
});
function progressbar() {
});
$("#progress_bar").css("display","block");
$("#showlogdiv").show();
$("#progress").css("width", (process)+"%");
$("#progress span").text(process+"%");
if(status_txt.match(/Error:/) != null){
$("#progress_bar").css("display","none");
$("#done_msg").html("<p>'.__js('Rearranging account failed. Please check the task logs for more information !').'</p>");
clearInterval(interval); // stop the interval
}
if(process == 100){
clearInterval(interval); // stop the interval
setTimeout(function(){
$("#progress_bar").css("display","none");
$("#done_msg").html("<b>'.__js('The account was moved successfully').'</b>");
},2000)
}
}
});
}
}
});
$("#user_search").on("select2:select", function(e, u = {}){
let user;
if("user" in u){
user = u.user;
}else{
user = $("#user_search option:selected").val();
}
window.location = "'.$globals['index'].'act=rearrange_account&user="+user;
});
$("#dom_search").on("select2:select", function(){
var domain_selected = $("#dom_search option:selected").val();
var d = {"get_user": 1, "domain" : domain_selected};
submitit(d, {
handle:function(data){
window.location = "'.$globals['index'].'act=rearrange_account&user="+data.domain_user;
}
})
});
</script>';
softfooter();
}