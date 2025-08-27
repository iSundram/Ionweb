<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function ips_theme() {
}else{
foreach($ips as $key => $v){
echo '
<tr id="tr'.$v['uuid'].'" ip="'.$v['ip'].'" '.(!empty($v['lock']) ? 'style="background-color: #F7F7F7;"' : '').'>
<td>
<input type="checkbox" class="check" name="checked_ips" data-ips="'.$v['ip'].'" '.((in_array($v['ip'], array($globals['WU_PRIMARY_IP'], $globals['WU_PRIMARY_IPV6']))) ? 'disabled' : '').'>
</td>
<td>
<span>'.$v['uuid'].'</span>
</td>
<td>
<span>'.$v['ip'].'</span>'.(!empty($v['lock']) ? '
<span>
<i class="fas fa-lock locked-icon"></i>
</span> ' : '').(!empty($v['note']) ? '
<span>
<i class="fas fa-sticky-note text-warning" title="'.$v['note'].'"></i>
</span>' : '').'
</td>
<td>
'.__('IPv$0', [$v['type']]).'
</td>
<td>
'.((in_array($v['ip'], array($globals['WU_PRIMARY_IP'], $globals['WU_PRIMARY_IPV6']))) ? __('Shared (Main)') : (!empty($v['shared']) ? __('Shared') : __('Dedicated'))).'
</td>
<td>
<span>'.str_replace(',', ', ', $v['users']).'</span>
</td>
<td>
<label class="switch">
<input type="checkbox" class="checkbox"  data-donereload="1" data-active_ip="1" data-ip_add="'.$v['ip'].'" data-status="'.(empty($v['inactive_ip']) ? '0' : '1').'" '.(empty($v['inactive_ip']) ? 'checked' : '').' onclick="return active_ip(this)">
<span class="slider" '.(empty($v['inactive_ip']) ? 'title="Active"' : 'title="Inactive"').'></span>
</label>
</td>
<td>
<span>'.datify($v['created']).'</span>
</td>
<td>
'.(!empty($v['lock']) ? '
<i class="fas fa-unlock locked-icon" title="'.__('Unlock IP').'" onclick="unlock_ip(this)" data-do="unlock" data-ips="'.$v['ip'].'"></i>' : '
<i class="fas fa-lock locked-icon" title="'.__('Lock IP').'" onclick="lock_ip(this)" data-do="lock" data-ips="'.$v['ip'].'"></i>').'
</td>
<td>
<a href="'.$globals['admin_url'].'act=editip&ip='.$v['ip'].'" title="'.__('Edit').'">
<i class="fas fa-pencil-alt edit-icon"></i>
</a>
</td>
<td>
<i class="fas fa-trash delete-icon" onclick="delete_record(this)" id="did'.$v['uuid'].'" data-do="delete" data-ips="'.$v['ip'].'" title="'.__('Delete').'"></i>
</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>';
page_links();
echo '
</div>
</form>
<script>
$(document).ready(function(){
$("#checkAll").change(function () {
$(".check:enabled").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function() {
if($(".check:checked").length){
$("#delete_selected").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled", true);
}
});
var f = function(){
var type = window.location.hash.substr(1);
if(!empty(type)){
var intro = new Anno([
{
target:"#"+type,
content: "'.__js('Click here to rebuild IP\'s').'",
onShow: function () {
$(".anno-btn").hide();
}
},
]);
intro.show();
window.location.hash = "";
}
}
f();
$(window).on("hashchange", f);
});
function del_ips() {
});
console.log(ips);
jEle.data("ips", ips.join());
var lang = confirmbox;
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
var d = jEle.data();
submitit(d, {done_reload : window.location.href});
});
show_message(a);
}
function lock_ip() {
});
});
show_message(a);
}
function unlock_ip() {
});
});
show_message(a);
}
function active_ip() {
}else{
lan = "'.__('Do you want to inactive the IP : $0 $ip $1?', ['ip' => '"+d.ip_add+"', '<b>', '</b>']).'";
}
a = show_message_r("'.__js('Warning').'", lan);
a.alert = "alert-warning";
var no = function(){
var status = d.status ? false : true;
jEle.prop("checked", status);
}
a.confirm.push(function(){
submitit(d, {done_reload : window.location.href, error: no});
});
a.no.push(no);
a.onclose.push(no);
show_message(a);
}
function add_all_ip() {
};
submitit(d,{done_reload: window.location});
});
show_message(a);
return false;
}
$("#ip_search").on("select2:select", function(e, u = {}){
ip = $("#ip_search option:selected").val();
if(ip == "all"){
window.location = "'.$globals['index'].'act=ips'.(!empty(optREQ('used')) ? '&used=1' : '').''.(!empty(optREQ('reserved')) ? '&reserved=1' : '').'";
}else{
window.location = "'.$globals['index'].'act=ips&ip="+ip+"'.(!empty(optREQ('used')) ? '&used=1' : '').''.(!empty(optREQ('reserved')) ? '&reserved=1' : '').'";
}
});
$("#user_search").on("select2:select", function(e, u = {}){
user = $("#user_search option:selected").val();
if(user == "all"){
window.location = "'.$globals['index'].'act=ips'.(!empty(optREQ('used')) ? '&used=1' : '').''.(!empty(optREQ('reserved')) ? '&reserved=1' : '').'";
}else{
window.location = "'.$globals['index'].'act=ips&user="+user+"'.(!empty(optREQ('used')) ? '&used=1' : '').''.(!empty(optREQ('reserved')) ? '&reserved=1' : '').'";
}
});
</script>';
softfooter();
}