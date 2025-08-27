<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function users_theme() {
}
</style>
<form accept-charset="'.$globals['charset'].'" name="usersform" id="usersform" method="post" action="" class="form-horizontal">
<div class="soft-smbox p-3">
<div class="sai_main_head">
<i class="fas fa-users fa-xl me-2"></i><span>'.__($title).'</span>
<span class="search_btn float-end">
<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
</span>
</div>
<div style="background-color:#e9ecef;">
<div class="collapse '.(!empty(optREQ('search')) || !empty(optREQ('domain')) || !empty(optREQ('owner')) || !empty(optREQ('email')) || !empty(optREQ('ip')) ? 'show' : '').' mt-2" id="search_queue">
<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
<div class="row p-3 col-md-12 d-flex">
<div class="col-12 '.(empty($SESS['is_reseller']) ? 'col-md-4' : 'col-md-6').'">
<label class="sai_head">'.__('Search By User Name').'</label><br/>
<select class="form-select make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="user_search" name="user_search">
<option value="'.optREQ('search').'" selected="selected">'.optREQ('search').'</option>
</select>
</div>
<div class="col-12 '.(empty($SESS['is_reseller']) ? 'col-md-4' : 'col-md-6').'">
<label class="sai_head">'.__('Search By Domain Name').'</label>
<select class="form-select make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act=domains&api=json" s2-query="dom_search" s2-data-key="domains" s2-data-subkey="domain" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" name="dom_search" id="dom_search">
<option value="'.optREQ('domain').'" selected="selected">'.optREQ('domain').'</option>
</select>
</div>';
if(empty($SESS['is_reseller'])){
echo '
<div class="col-12 col-md-4">
<label class="sai_head text-center">'.__('Search By Owner Name').'</label><br/>
<select class="form-select make-select2" s2-placeholder="'.__('Select Owner').'" s2-ajaxurl="'.$globals['index'].'act=users&type=2&api=json'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'root', 'id' => 'root', 'value' => 'root'],['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="owner_search" name="owner_search">
<option value="'.optREQ('owner').'" selected="selected">'.optREQ('owner').'</option>
</select>
</div>';
}
echo '
</div>
<div class="row p-3 col-md-12 d-flex">
<div class="col-12 col-md-6">
<label class="sai_head me-1">'.__('Search By Email Account').'</label>
<select class="form-select make-select2" s2-placeholder="'.__('Select Email').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'" s2-query="email" s2-data-key="users" s2-data-subkey="email" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="email_search" name="email">
<option value="'.optREQ('email').'" selected="selected">'.optREQ('email').'</option>
</select>
</div>
<div class="col-12 col-md-6">
<label class="sai_head text-center">'.__('Search by IPs').'</label><br/>
<select class="form-select make-select2" s2-placeholder="'.__('Select ip').'" s2-ajaxurl="'.$globals['index'].'act=ips&api=json" s2-query="ip" s2-data-key="ips" s2-data-subkey="ip" s2-result-add="'.htmlentities(json_encode([['text' => 'Default', 'id' => 'default', 'value' => 'default'],['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="ip_search" name="ip_search">
<option value="'.optREQ('ip').'" selected="selected">'.optREQ('ip').'</option>
</select>
</div>
</div>
</form>
</div>
</div>
</div>
<div class="soft-smbox p-3 mt-4">';
page_links();
echo '
<hr>
<div class="row mt-2">
<div class="col-12 col-sm-6">
<input type="button" class="btn btn-danger mb-1" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_user(this)" disabled>&nbsp;&nbsp;';
if(optGET('type') == '2'){
echo '<input type="button" class="btn btn-primary mb-1" value="'.__('Reset Reseller').'" name="reset_reseller" id="reset_reseller" onclick="reset_reseller_pri(this)" disabled>';
}
echo '
</div>
<div class="col-12 col-sm-6 sai_sub_head record-table position-relative" style="text-align:right;">
<a type="button" class="btn btn btn-primary text-decoration-none m-1" '.(empty(optREQ('type')) ? 'href="'.$globals['index'].'act=add_user"' : 'href="'.$globals['index'].'act=add_user&reseller=1"').'>'.(empty(optREQ('type')) ? __('Add User') : __('Add Reseller')).'</a>
<a class="btn btn-primary float-end m-1 '.(empty($users) ? 'dis_link' : '' ).'" target="_blank" href="'.$globals['request_url'].'&export=csv">'.__('Export CSV').'</a>
</div>
</div>
<div id="showplanlist" class="table-responsive  mt-2">
<table class="table table-hover sai_form webuzo-table">
<thead>
<tr>
<th><input type="checkbox" id="checkAll"></th>
<th colspan="2" width="5%">'.__('Options').'</th>
<th width="13% ">'.__('Users').'<i id="user_order" class="fa fa-sort m-1" style="cursor:pointer;"></i>
</th>
<th width="1%">'.__('Login').'</th>
<th width="15%">'.__('Email').'</th>
<th width="15%" class="">'.__('Domain').'<i id="dom_order" class="fa fa-sort m-1" style="cursor:pointer;"></i>
</th>
<th width="10%">'.__('IP Address').'</th>
<th width="7%">'.__('Owner').'</th>
<th width="7%">'.__('Plan').'</th>
<th width="5%">'.__('Type').'</th>
<th width="10%">'.__('Resources').'<i id="resource_order" class="fa fa-sort m-1" title="Sort users by resource disk usage" style="cursor:pointer;"></i></th>
'.(optREQ('suspended') ? '<th>'.__('Reason').'</th>' : '').'
<th>'.__('Created').'</th>
</tr>
</thead>
<tbody id="userslist">';
if(empty($users)){
echo '
<tr>
<td colspan="100" class="text-center">
<span>'.__('No Users Exist').'</span>';
if(empty(optREQ('suspended'))){
echo '
<a href="'.$globals['ind'].'act=add_user'.(!empty(optGET('demo')) ? '&demo=1' : '').(!empty(optGET('type') && optGET('type') == '2') ? '&reseller=1' : '').'">'.__('Create New User').'</a>';
}
echo '
</td>
</tr>';
}else{
foreach($users as $key => $value){
$U = load_user($value['user']);
$space = json_decode($value['space'], true);
$temp[$value['user']]['disk'] = [
[
'label' => __('Used Disk'),
'data' => (empty($users[$value['user']]['resource']['disk']['used_bytes']) ? 0 : (($users[$value['user']]['resource']['disk']['limit_bytes']) != 'unlimited' ?  $users[$value['user']]['resource']['disk']['used_bytes'] : 0)),
'color' => (($users[$value['user']]['resource']['disk']['used_bytes']) > ($users[$value['user']]['resource']['disk']['limit_bytes'])? '#dc3545' : '#111738'),
'percent' => $users[$value['user']]['resource']['disk']['percent']
],
[
'label' => __('Free Disk'),
'data' => (($users[$value['user']]['resource']['disk']['limit_bytes']) != 'unlimited' ?  ((($users[$value['user']]['resource']['disk']['used_bytes']) > ($users[$value['user']]['resource']['disk']['limit_bytes']))? 0 : $users[$value['user']]['resource']['disk']['limit_bytes']) : 100),
'color' => '#e91e63'
]
];
$temp[$value['user']]['bandwidth'] = [
[
'label' => __('Used Bandwidth'),
'data' => (empty($users[$value['user']]['resource']['bandwidth']['used_bytes'])? 0: (($users[$value['user']]['resource']['bandwidth']['limit_bytes']) != 'unlimited' ? $users[$value['user']]['resource']['bandwidth']['used_bytes'] : 0)),
'color' => (($users[$value['user']]['resource']['bandwidth']['used_bytes']) > ($users[$value['user']]['resource']['bandwidth']['limit_bytes']) ? '#dc3545' : '#111738'),
'percent' => $users[$value['user']]['resource']['bandwidth']['percent']
],
[
'label' => __('Free Bandwidth'),
'data' => (($users[$value['user']]['resource']['bandwidth']['limit_bytes']) != 'unlimited' ? ((($users[$value['user']]['resource']['bandwidth']['used_bytes']) > ($users[$value['user']]['resource']['bandwidth']['limit_bytes']))? 0 : ($users[$value['user']]['resource']['bandwidth']['limit_bytes'])) : 100),
'color' => '#9c27b0'
]
];
echo '
<tr user="'.$value['user'].'" '.($value['status'] == 'suspended' || !empty(optREQ('suspended_email_user')) ? 'style="background-color: #ffdbdb;"' : '').'>
<td>
<input type="checkbox" class="check" name="checked_user" value="'.$value['user'].'">
</td>
<td class="dropdown dropstart" style="position:relative">
<a href="dropdown-toggle" href="#" class="suspension" role="button" id="id'.$key.'" data-bs-toggle="dropdown">
<i class="fa-solid fa-gear"></i>
</a>
<ul class="dropdown-menu" aria-labelledby="id'.$key.'">';
if($value['type'] == 2){
echo '<li class="dropdown-item cursor-pointer" title="'.__('Edit Reseller Privileges').'">
<a href="'.$globals['admin_url'].'act=reseller_privileges&user_name='.$value['user'].'" style="color:#454545">
<i class="fas fa-sitemap" mb-2" style="color:blue;"></i>&nbsp;'.__('Edit Privileges').'
</a>
</li>';
}
if(!(!empty($SESS['is_reseller']) && !empty(get_reseller_privs('disable_suspend')))){
if($value['type'] == 1){
if($value['status'] != 'suspended'){
echo '<li class="dropdown-item cursor-pointer" title="'.__('Suspend').'" onclick="suspend_user(this)" data-suspend="'.$value['user'].'" data-reseller="'.(!empty($SESS['is_reseller']) ? "1" : "").'">
<i class="fas fa-pause suspend-icon mb-2"></i> &nbsp;'.__('Suspend').'
</li>';
}else{
echo '<li class="dropdown-item cursor-pointer '.((!empty($SESS['is_reseller']) && !empty($U['suspended_by'])) ? 'dis_link' : '').'" title="'.__('Unsuspend').'" onclick="unsuspend_user(this)" data-unsuspend="'.$value['user'].'">
<i class="fas fa-play unsuspend-icon mb-2"></i> &nbsp;'.__('Unsuspend').'
</li>';
}
}else{
echo '<li class="dropdown-item cursor-pointer" title="'.__('Suspend').'" onclick="suspend_user(this)" data-suspend="'.$value['user'].'" data-resl="1">
<i class="fas fa-pause suspend-icon mb-2"></i> &nbsp;'.__('Suspend reseller and users').'
</li>
<li class="dropdown-item cursor-pointer" title="'.__('Unsuspend').'" onclick="unsuspend_user(this)" data-unsuspend="'.$value['user'].'" data-resl="1">
<i class="fas fa-play unsuspend-icon mb-2"></i> &nbsp;'.__('Unsuspend reseller and users').'
</li>';
}
}
if(is_app_installed('exim')){
if(!(!empty($SESS['is_reseller']) && !empty(get_reseller_privs('disable_suspend')))){
if($value['type'] == 1){
if(empty($value['suspend_emails'])){
echo '<li class="dropdown-item cursor-pointer" title="'.__('Suspend Emails').'" onclick="suspend_email(this)" data-suspend_email="'.$value['user'].'">
<i class="fas fa-envelope suspend-icon mb-2"></i> &nbsp;'.__('Suspend Emails').'
</li>';
}else{
echo '<li class="dropdown-item cursor-pointer" title="'.__('Unsuspend Emails').'" onclick="unsuspend_email(this)" data-unsuspend_email="'.$value['user'].'">
<i class="fas fa-envelope-open unsuspend-icon mb-2"></i> &nbsp;'.__('Unsuspend Emails').'
</li>';
}
}else{
echo '<li class="dropdown-item cursor-pointer" title="'.__('Suspend Emails of reseller and user').'" onclick="suspend_email(this)" data-suspend_email="'.$value['user'].'" data-resl="1">
<i class="fas fa-envelope suspend-icon mb-2"></i> &nbsp;'.__('Suspend Emails of reseller and user').'
</li>
<li class="dropdown-item cursor-pointer" title="'.__('Unsuspend Emails of reseller and user').'" onclick="unsuspend_email(this)" data-unsuspend_email="'.$value['user'].'" data-resl="1">
<i class="fas fa-envelope-open unsuspend-icon mb-2"></i> &nbsp;'.__('Unsuspend Emails of reseller and user').'
</li>';
}
}
}
if(!(!empty($SESS['is_reseller']) && !empty(get_reseller_privs('disable_terminate')))){
echo '<li class="dropdown-item cursor-pointer" title="'.__('Delete').'" id="del_'.$value['user'].'" data-donereload=1 data-delete_user="'.$value['user'].'" onclick="'.(($value['type'] == 1) ? 'delete_record(this)' : 'delete_reseller(this)').'">
<i class="fas fa-trash delete-icon mb-2"></i> &nbsp;'.__('Delete').'
</li>';
}
echo '
<li class="dropdown-item cursor-pointer" title="'.__('Force Password').'" data-force_pass="1" data-do="'.(empty($value['force_password']) ? 1 : 0).'" data-user='.$value['user'].' onclick="return force_pass_toggle(this)">
<input type="checkbox" '.(empty($value['force_password']) ? "" : "checked").' disabled="disabled"> &nbsp;'.__('Force Password').'
</li>';
echo '
<li class="dropdown-item cursor-pointer" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="'.__('Click here to reset Bandwidth Usage for the current month').'" data-user='.$value['user'].' onclick="return reset_bandwidth_usage(this)">
<i <i class="fas fa-undo reset_usage text-danger" ></i>&nbsp;'.__('Reset Bandwidth Usage').'
</li>';
echo '
</ul>
</td>
<td>
<a href="'.$globals['admin_url'].'act=add_user&user_name='.$value['user'].'" style="color: #F0911C">
<i class="fa-regular fa-pen-to-square" title="'.__('Edit').'" style="text-decoration-none;"></i>
</a>
</td>
<td>
<span>'.$value['user'].'</span><br>
<span class="uuid">'.__('ID').': '.$value['uuid'].'</span>
</td>
<td>
<a '.(optREQ('type') == 2 ? 'onclick="reselleradmin(\''.$value['user'].'\')" style="cursor:pointer" class="text-decoration-none;"' : 'href="'.$globals['admin_url'].'act=sso&loginAs='.$value['user'].'"target="blank"').'><img src="'.$theme['images'].'webuzo_icon_64.svg" width="32" height="32" title="'.((optREQ('type') == 2)? __('Reseller Panel') : __('Enduser Panel')).'"/></a>
</td>
<td>
<span>'.$value['email'].'</span>';
if(!empty(optREQ('suspended_email_user'))){
echo '
<br><span style="font-size:10px; color: #515151;">'.__('Email suspended : ').''.$value['suspend_emails'].'</span>';
}
echo'
</td>
<td>
<span>'.$value['domain'].'</span>
</td>
<td>
'.$value['ip'].(!empty($value['ipv6']) ? '<div style="width: 100px; font-size: 10px">'.$value['ipv6'].'</div>' : '').'
</td>
<td>
<span>'.$value['owner'].'</span>
</td>
<td>
<span>'.(!empty($value['plan']) ? $value['plan'] : __('None')).'</span>
</td>
<td>
<span>'.($value['type'] == 1 ? __('User') : __('Reseller')).'</span>
</td>
<td>
<span id="disk_'.$key.'" style="width:50px; height:50px;" class="me-0 col-sm-6"></span>
<span class="col-sm-6" style="font-size: 11px;">'.$users[$value['user']]['resource']['disk']['used'].' / '.($users[$value['user']]['resource']['disk']['limit'] != 'unlimited' ? $users[$value['user']]['resource']['disk']['limit'] : '∞').'</span>
<span id="bandwidth_'.$key.'" style="width:50px; height:29px;" class="col-sm-6"></span>
<span class="col-sm-6" style="font-size: 11px;">'.$users[$value['user']]['resource']['bandwidth']['used'].' / '.($users[$value['user']]['resource']['bandwidth']['limit'] != 'unlimited' ? $users[$value['user']]['resource']['bandwidth']['limit'] : '∞').'</span>
</td>';
if(optREQ('suspended')){
echo '
<td>
<span>'.(!empty($value['suspend_reason']) ? str_replace(['<', '>'], ['&lt;', '&gt;'], $value['suspend_reason']) : '').'</span>
</td>';
}
echo '
<td>
<span>'.datify($value['created']).'</span>
</td>';
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
</div>
</form>
<script>
$(document).ready(function (){
$("#checkAll").change(function () {
$(".check").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function() {
if($(".check:checked").length){
$("#delete_selected").removeAttr("disabled");
$("#reset_reseller").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled", true);
$("#reset_reseller").prop("disabled", true);
}
});
var graph_data = \''.json_encode($temp).'\';
var graph_data = JSON.parse(graph_data);
$.each(graph_data, function(key, value) {
var disk = value["disk"];
var bandwidth = value["bandwidth"];
var diskUsedPercentage = Math.round(disk[0].percent);
var bandwidthUsedPercentage = Math.round(bandwidth[0].percent);
var diskProgress = \'<div class="progress" style="height: 8px; border: 1px solid #0472ED;"><div class="progress-bar " role="progressbar" style="width: \' + diskUsedPercentage + \'%; background-color: #0472ED;"></div></div>\';
var bandwidthProgress = \'<div class="progress" style="height: 8px; border: 1px solid #E92165;"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: \' + bandwidthUsedPercentage + \'%; background-color: #E92165;"></div></div>\';
$.plot($("#disk_" + key), []); // Clear the container
$("#disk_" + key).html(diskProgress);
$.plot($("#bandwidth_" + key), []); // Clear the container
$("#bandwidth_" + key).html(bandwidthProgress);
});
var f = function() {
var type = window.location.hash.substr(1);
if (!empty(type)) {
var anno1 = new Anno({
target: "." + type,
position: "right",
content: "'.__js('Click Here to Manage Suspension of Users').'",
onShow: function() {
$(".anno-btn").hide();
}
});
anno1.show();
$("." + type).click(function() {
anno1.hide();
});
window.location.hash = "";
}
};
f();
$(window).on("hashchange", f);
});
function del_user() {
}
});
jEle.data("delete_user", arr.join());
if(reseller_exist){
var del_sub_acc = "<br /><br /><input type=\"checkbox\" name=\"del_sub_users\" id=\"del_sub_users\"/>&nbsp;&nbsp;"+"'.__js('Do you want to delete the reseller\'s sub accounts as well ?').'";
}else{
var del_sub_acc = "";
}
var lang = confirmbox + "'.__js('Are you sure you want to delete the selected user(s) ?').'" + del_sub_acc;
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
var cboxValue = document.getElementById("confirmbox").value;
var cbox = cboxValue.trim();
var cbox = cbox.toLowerCase();
if(cbox != "i confirm"){
alert("'.__js('Confirmation message is invalid!').'");
return false;
}
var sub_users_checked = $("#del_sub_users").is(":checked");
if(sub_users_checked){
jEle.data("del_sub_acc", 1);
}
var d = jEle.data();
submitit(d, {
handle:function(data, p){
if(data.done){
var d = show_message_r("'.__js('Done').'", data.done.msg);
d.alert = "alert-success";
d.ok.push(function(){
location.reload(true);
});
show_message(d);
}
}
});
});
show_message(a);
}
function reset_reseller_pri() {
});
a.confirm.push(function(){
var data = {"reset" : resuser};
submitit(data,{
done_reload: window.location
});
});
show_message(a);
}
function force_pass_toggle() {
}else{
lan = "'.__js('Are you sure you want to force the password change for this user ?').'";
}
a = show_message_r("'.__js('Warning').'", lan);
a.alert = "alert-warning";
a.confirm.push(function(){
submitit(d, {
success : function(){
jEle.find("input").prop("checked", d.do);
jEle.data("do", d.do ? 0 : 1);
}
});
});
show_message(a);
}
function reset_bandwidth_usage() {
},
},
function(data){
if(data.done){
var dm = show_message_r("'.__js('Done').'", data.done.msg);
dm.alert = "alert-success";
dm.ok.push(function(){
location.reload(true);
});
show_message(dm);
}else{
var em = show_message_r("'.__js('Error').'", data.error);
em.alert = "alert-danger";
em.ok.push(function(){
location.reload(true);
});
show_message(em);
}
});
});
show_message(a);
}
function suspend_user() {
}else{
if(jEle.data().reseller){
var lang = "<br />'.__js('Are you sure you want to suspend this user ?').'";
}else{
var lang = prevent_unsuspend + "<br />'.__js('Are you sure you want to suspend this user ?').'";
}
}
var suspend_reason = "'.__js('Reason for suspension (Optional)').' :<br /><input type=\"text\" name=\"suspend_reason\" id=\"suspend_reason\" class=\"form-control\" /><br />";
var lang = suspend_reason + lang;
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
jEle.data("skip", 0);
if(jEle.data().resl == 1){
var radio_val = $("input[name=suspend_main]:checked").val();
jEle.data("skip", radio_val);
}
var suspend_reason = $("#suspend_reason").val();
if(suspend_reason){
jEle.data("suspend_reason", suspend_reason);
}
if($("#prevent_unsuspend").prop("checked") == true){
jEle.data("prevent_unsuspend", 1);
}
var d = jEle.data();
submitit(d, {
done_reload : window.location
});
});
show_message(a);
}
function unsuspend_user() {
}else{
var lang = "'.__js('Are you sure you want to unsuspend this user ?').'";
}
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
jEle.data("skip", 0);
if(jEle.data().resl == 1){
var radio_val = $("input[name=unsuspend_main]:checked").val();
jEle.data("skip", radio_val);
}
var d = jEle.data();
submitit(d, {
done_reload : window.location
});
});
show_message(a);
}
function suspend_email() {
}else{
var lang = "'.__js('Are you sure you want to suspend Emails as per selected options  ?').'" + email_check;
}
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
jEle.data("skip", 0);
if(jEle.data().resl == 1){
var radio_val = $("input[name=suspend_main]:checked").val();
jEle.data("skip", radio_val);
}
var match = $("input:radio[name=suspend_email_check]:checked").val();
jEle.data("suspend_email_check", match);
var d = jEle.data();
submitit(d, {
done_reload : window.location
});
});
show_message(a);
}
function unsuspend_email() {
}else{
var lang = "'.__js('Are you sure you want to unsuspend Email as per selected options ?').'";
}
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
jEle.data("skip", 0);
if(jEle.data().resl == 1){
var radio_val = $("input[name=unsuspend_main]:checked").val();
jEle.data("skip", radio_val);
}
var d = jEle.data();
submitit(d, {
done_reload : window.location
});
});
show_message(a);
}
function delete_reseller() {
}
if($("#del_r_users").prop("checked") == true){
jEle.data("del_sub_acc", 1);
}
var d = jEle.data();
submitit(d, {
done_reload : window.location
});
});
show_message(a);
}
$("#user_search").on("select2:select", function(e, u = {}){
user = $("#user_search option:selected").val();
if(user == "all"){
window.location = "'.$globals['index'].'act=users'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else{
window.location = "'.$globals['index'].'act=users&search="+user+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}
});
$("#dom_search").on("select2:select", function(){
var domain_selected = $("#dom_search option:selected").val();
if(domain_selected == "all"){
window.location = "'.$globals['index'].'act=users'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else{
window.location = "'.$globals['index'].'act=users&domain="+domain_selected+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}
});
$("#owner_search").on("select2:select", function(e, u = {}){
owner = $("#owner_search option:selected").val();
if(owner == "all"){
window.location = "'.$globals['index'].'act=users'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else{
window.location = "'.$globals['index'].'act=users&owner="+owner+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}
});
$("#email_search").on("select2:select", function(e, u = {}){
email = $("#email_search option:selected").val();
if(email == "all"){
window.location = "'.$globals['index'].'act=users'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else{
window.location = "'.$globals['index'].'act=users&email="+email+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}
});
$("#ip_search").on("select2:select", function(e, u = {}){
ip = $("#ip_search option:selected").val();
if(ip == "all"){
window.location = "'.$globals['index'].'act=users'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else if(ip == "default"){
window.location = "'.$globals['index'].'act=users&ip='.$globals['WU_PRIMARY_IP'].''.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else{
window.location = "'.$globals['index'].'act=users&ip="+ip+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}
});
$("#user_order").click(function(){
order_by = "ASC&by=user";
if($("#user_order").hasClass("fa-sort-up")){
order_by = "DESC&by=user";
}
url = window.location.toString();
url = url.replace(/[\?&]order=[^&]+/, "").replace(/[\?&]by=[^&]+/, "");
window.location = url+"&order="+order_by;
});
$("#dom_order").click(function(){
order_by = "ASC&by=domain";
if($("#dom_order").hasClass("fa-sort-up")){
order_by = "DESC&by=domain";
}
url = window.location.toString();
url = url.replace(/[\?&]order=[^&]+/, "").replace(/[\?&]by=[^&]+/, "");
window.location = url+"&order="+order_by;
});
$("#resource_order").click(function(){
order_by = "ASC&by=resource";
if($("#resource_order").hasClass("fa-sort-up")){
order_by = "DESC&by=resource";
}
url = window.location.toString();
url = url.replace(/[\?&]order=[^&]+/, "").replace(/[\?&]by=[^&]+/, "");
window.location = url+"&order="+order_by;
});
var order = "'.optGET('order').'";
var orderby = "'.optGET('by').'";
if(orderby == "user"){
$("#user_order").removeClass("fa-sort");
if(order == "DESC"){
$("#user_order").addClass("fa-sort-down");
}else if(order == "ASC"){
$("#user_order").addClass("fa-sort-up");
}
}
if(orderby == "domain"){
$("#dom_order").removeClass("fa-sort");
if(order == "ASC"){
$("#dom_order").addClass("fa-sort-up");
}else if(order == "DESC"){
$("#dom_order").addClass("fa-sort-down");
}
}
if(orderby == "resource"){
$("#resource_order").removeClass("fa-sort");
if(order == "ASC"){
$("#resource_order").addClass("fa-sort-up");
}else if(order == "DESC"){
$("#resource_order").addClass("fa-sort-down");
}
}
function reselleradmin() {
},
success: function(data) {
var ssoUrl = data["done"]["url"];
window.open(ssoUrl, "blank");
}
});
}
</script>';
softfooter();
}