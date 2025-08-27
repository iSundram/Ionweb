<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function user_logs_theme() {
}else{
foreach($users as $user => $value){
echo '
<tr '.($value['status'] == 'suspended' ? 'style="background-color: #ffdbdb;"' : '').' class="userdata">
<td id="user_name" style="width: 25%;">
<span>'.$user.'</span>
</td>
<td >
<span class="text-center">
<select class="form-select form-select-sm" style="width: 35%;"  name="domain" id="dom_'.$user.'">';
foreach($value as $dom => $val){
echo'
<option name="domain_name">
'.$val.'
</option>';
}
echo'
</select>
</span>
</td>';
foreach($webserver_list as $key => $val){
echo'
<td width="2%" style="position:relative" >
<button type="button" class="btn btn-primary float-end showlogs anno_'.$key.'" id="domain_'.$user.'" data-show_log=1 data-user="'.$user.'" data-domain="'.$user.'" data-webserver="'.$key.'"> '.strtoupper($key).'</button>
</td>';
}
echo '
</tr>';
}
}
echo '
</tbody>
</table>
</div>';
page_links();
echo'
<nav aria-label="Page navigation">
<ul class="pagination pager myPager justify-content-end">
</ul>
</nav>
</div>
<div class="modal fade" id="show_logs_list" tabindex="-1" aria-labelledby="download-log" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h6 class="modal-title" id="domain_log"></h6>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-4" id="mbody">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="download_log" id="form_id" class="form-horizontal" onsubmit="return download_file(this)">
<div id="warn">
<label id="fileNotFound"></label>
</div>
<div id="form_data">
<label id="file_exp" class="sai_head">
'.__('Select a log file to download').' <span id="file_exp" class="form-control sai_exp">'.__('Empty files will not be listed here').'</span>
</label>
<input type="hidden" class="form-control" name="path" id="path_to_file" value=""/>
<select id="log_list" name="domain_file" class="form-select">
</select></br>
<center><input type="submit" class="btn btn-primary" name="download_log" id="dwld" value="'.__('Download').'"/>
</div>
</form>
</div>
</div>
</div>
</div>
<script>
}
var html;
submitit(d, {
handle:function(data, p){
$("#domain_log").html(d.domain + " - " + d.webserver.toUpperCase());
if(empty(data.logfiles[d.webserver])){
$("#fileNotFound").html("'.__js('No Log file found for this domain').' "+d.domain);
$("#warn").attr("class", "alert alert-warning col-sm justify-content-center");
$("#form_data").attr("class", "d-none");
$("#show_logs_list").modal("show");
}else{
$("#warn").attr("class", "d-none");
$("#form_data").attr("class", "sai_form");
var [first] = Object.keys(data.logfiles);
$("#path_to_file").val(first);
$.each(data.logfiles[d.webserver], function(key, val){
$.each(data.logfiles[d.webserver][key], function(file, filesize){
html += \'<option>\'+file+"\t\t("+filesize+" Bytes)"+\'</option>\'
});
});
$("#log_list").show();
$("#dwld").show();
$("#log_list").html(html);
$("#show_logs_list").modal("show");
}
},
});
})
function download_file() {
}
$("#user_search").on("select2:select", function(e, u = {}){
user = $("#user_search option:selected").val();
window.location = "'.$globals['index'].'act=user_logs&user_search="+user;
});
$("#dom_search").on("select2:select", function(){
var domain_selected = $("#dom_search option:selected").val();
window.location = "'.$globals['index'].'act=user_logs&domain="+domain_selected;
});
$(document).ready(function(){
var f = function(){
var type = window.location.hash.substr(1);
if(!empty(type)){
var anno1 = new Anno({
target : ".anno_"+type,
position : "left",
content: "'.__js('Click Here to Download Logs of ').'"+"<b>"+type.toUpperCase()+"</b>",
onShow: function () {
$(".anno-btn").hide();
}
})
anno1.show();
window.location.hash = "";
}
}
f();
$(window).on("hashchange", f);
});
</script>';
softfooter();
}