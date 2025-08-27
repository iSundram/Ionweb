<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function servers_theme() {
}else{
foreach($servers as $key => $value){
$space = json_decode($value['space'], true);
$temp[$value['user']]['disk'] = [
[
'label' => __('Used Disk'),
'data' => (empty($servers[$value['user']]['resource']['disk']['used_bytes']) ? 0 : (($servers[$value['user']]['resource']['disk']['limit_bytes']) != 'unlimited' ?  $servers[$value['user']]['resource']['disk']['used_bytes'] : 0)),
'color' => (($servers[$value['user']]['resource']['disk']['used_bytes']) > ($servers[$value['user']]['resource']['disk']['limit_bytes'])? '#dc3545' : '#111738')
],
[
'label' => __('Free Disk'),
'data' => (($servers[$value['user']]['resource']['disk']['limit_bytes']) != 'unlimited' ?  ((($servers[$value['user']]['resource']['disk']['used_bytes']) > ($servers[$value['user']]['resource']['disk']['limit_bytes']))? 0 : $servers[$value['user']]['resource']['disk']['limit_bytes']) : 100),
'color' => '#e91e63'
]
];
$temp[$value['user']]['bandwidth'] = [
[
'label' => __('Used Bandwidth'),
'data' => (empty($servers[$value['user']]['resource']['bandwidth']['used_bytes'])? 0: (($servers[$value['user']]['resource']['bandwidth']['limit_bytes']) != 'unlimited' ? $servers[$value['user']]['resource']['bandwidth']['used_bytes'] : 0)),
'color' => (($servers[$value['user']]['resource']['bandwidth']['used_bytes']) > ($servers[$value['user']]['resource']['bandwidth']['limit_bytes']) ? '#dc3545' : '#111738')
],
[
'label' => __('Free Bandwidth'),
'data' => (($servers[$value['user']]['resource']['bandwidth']['limit_bytes']) != 'unlimited' ? ((($servers[$value['user']]['resource']['bandwidth']['used_bytes']) > ($servers[$value['user']]['resource']['bandwidth']['limit_bytes']))? 0 : ($servers[$value['user']]['resource']['bandwidth']['limit_bytes'])) : 100),
'color' => '#9c27b0'
]
];
echo '
<tr server="'.$value['uuid'].'" '.($value['status'] == 'locked' ? 'style="background-color: #ffdbdb;"' : '').'>
<td>
<input type="checkbox" class="check" name="checked" value="'.$value['uuid'].'">
</td>
<td>
<a href="'.$globals['index'].'act=manageserver&'.$value['uuid'].'" class="text-decoration-none;" target="blank">'.$value['server_name'].'</a><br>
'.__('UUID').' : '.$value['uuid'].'
</td>
<td>'.$value['os'].'</td>
<td>'.$value['ip'].'</td>
<td>'.$server_groups[$value['sgid']]['name'].'</td>
<td>'.$value['ram'].'</td>
<td>'.$value['space'].'</td>
<td>'.$value['cores'].'</td>
<td>'.$value['num_users'].'</td>
<td>'.$value['lic_expires'].'</td>
<td>'.$value['version'].'</td>
<td class="dropdown dropstart" style="position:relative">
<a href="dropdown-toggle" href="#" class="suspension" role="button" id="id'.$key.'" data-bs-toggle="dropdown">
<i class="fas fa-cog text-primary"></i>
</a>
<ul class="dropdown-menu" aria-labelledby="id'.$key.'">
<li class="dropdown-item cursor-pointer" title="'.__('Edit Reseller Privileges').'">
<a href="'.$globals['admin_url'].'act=reseller_privileges&user_name='.$value['user'].'" style="color:#454545">
<i class="fas fa-sitemap" mb-2" style="color:blue;"></i>&nbsp;'.__('Edit Privileges').'
</a>
</li>
<li class="dropdown-item cursor-pointer" title="'.__('Force Password').'" data-force_pass="1" data-do="'.(empty($value['force_password']) ? 1 : 0).'" data-user='.$value['user'].' onclick="return force_pass_toggle(this)">
<input type="checkbox" '.(empty($value['force_password']) ? "" : "checked").' disabled="disabled"> &nbsp;'.__('Force Password').'
</li>
</ul>
</td>
<td>
<a href="'.$globals['admin_url'].'act=edit_server&uuid='.$value['uuid'].'">
<i class="fas fa-pencil-alt edit-icon" title="'.__('Edit').'"></i>
</a>
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
</form>
</div>
<script>
$(document).ready(function (){
$("#checkAll").change(function () {
$(".check").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function() {
if($(".check:checked").length){
$("#delete_selected").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled", true);
}
});
var graph_data = \''.json_encode($temp).'\';
var graph_data = JSON.parse(graph_data);
var pie_option = {
series: {
pie: {
show: true,
label: {
show: true,
radius: 0.5,
formatter: function(label, series){
if(label != "used bandwidth" || label != "used disk") return "";
return \'<div style="font-size:13px;">\'+series.data+\'</div><div style="font-size:10px; color:#333;">\'+label+\'</div>\';
}
}
}
},
tooltip: {
show: true,
content: "%p.0% %s",
shifts: {
x: 20,
y: 0
}
},
legend: {
show: false
},
grid: {
hoverable: true
},
};
$.each(graph_data, function(key, value){
var disk = value["disk"];
var bandwidth = value["bandwidth"];
$.plot($("#disk_"+key), disk, pie_option);
$.plot($("#bandwidth_"+key), bandwidth, pie_option);
});
var f = function(){
var type = window.location.hash.substr(1);
if(!empty(type)){
var anno1 = new Anno({
target : "."+type,
position : "left",
content: "'.__js('Click Here to Manage Suspension of Users').'",
onShow: function () {
$(".anno-btn").hide();
}
})
anno1.show();
$("."+type).click(function(){
anno1.hide();
})
window.location.hash = "";
}
}
f();
$(window).on("hashchange", f);
});
function del_user() {
}
});
jEle.data("delete_user", arr.join());
if(reseller_exist){
var del_sub_acc = "<br /><br /><input type=\"checkbox\" name=\"del_sub_servers\" id=\"del_sub_servers\"/>&nbsp;&nbsp;"+"'.__js('Do you want to delete the reseller\'s sub accounts as well ?').'";
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
var sub_servers_checked = $("#del_sub_servers").is(":checked");
if(sub_servers_checked){
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
function suspend_user() {
}else{
var lang = "'.__js('Are you sure you want to suspend this user ?').'";
}
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
jEle.data("skip", 0);
if(jEle.data().resl == 1){
if($("#suspend_main").prop("checked") != true){
jEle.data("skip", 1);
}
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
if($("#unsuspend_main").prop("checked") != true){
jEle.data("skip", 1);
}
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
if($("#del_r_servers").prop("checked") == true){
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
window.location = "'.$globals['index'].'act=servers'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else{
window.location = "'.$globals['index'].'act=servers&search="+user+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}
});
$("#dom_search").on("select2:select", function(){
var domain_selected = $("#dom_search option:selected").val();
if(domain_selected == "all"){
window.location = "'.$globals['index'].'act=servers'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else{
window.location = "'.$globals['index'].'act=servers&domain="+domain_selected+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}
});
$("#owner_search").on("select2:select", function(e, u = {}){
owner = $("#owner_search option:selected").val();
if(owner == "all"){
window.location = "'.$globals['index'].'act=servers'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else{
window.location = "'.$globals['index'].'act=servers&owner="+owner+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}
});
$("#email_search").on("select2:select", function(e, u = {}){
email = $("#email_search option:selected").val();
if(email == "all"){
window.location = "'.$globals['index'].'act=servers'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else{
window.location = "'.$globals['index'].'act=servers&email="+email+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}
});
$("#ip_search").on("select2:select", function(e, u = {}){
ip = $("#ip_search option:selected").val();
if(ip == "all"){
window.location = "'.$globals['index'].'act=servers'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else if(ip == "default"){
window.location = "'.$globals['index'].'act=servers&ip='.$globals['WU_PRIMARY_IP'].''.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else{
window.location = "'.$globals['index'].'act=servers&ip="+ip+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}
});
</script>';
softfooter();
}