<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function log_rotation_theme() {
}
echo '
</tbody>
</table>
</div>
<nav aria-label="Page navigation">
<ul class="pagination pager myPager justify-content-end">
</ul>
</nav>
</div>
<script>
$("#save_size").click(function(){
var size = $("#log_rotate_size").val();
if(empty(size)){
return false;
}
var d = {"setsize": 1, "size" : size};
submitit(d, {done_reload : window.location.href});
});
function log_rotation_toggle() {
}else{
lan = "'.__js('Do you want to stop log rotate by size ?').'";
}
a = show_message_r("'.__js('Warning').'", lan);
a.alert = "alert-warning";
var no = function(){
var status = d.do ? true : false;
jEle.prop("checked", status);
}
a.confirm.push(function(){
submitit(d, {done_reload : window.location.href, error: no});
});
a.no.push(no);
show_message(a);
}
function rotate_all() {
});
var d = {"log_rotation" : 1, "log_name": arr, "action" : "1"};
a = show_message_r("'.__js('Warning').'", "'.__js('Do you want all logs to rotate by size ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
submitit(d, {done_reload : window.location.href});
});
show_message(a);
}
function stop_rotate_all() {
});
var d = {"log_rotation" : 1, "log_name": arr, "action" : "0"};
a = show_message_r("'.__('Warning').'", "'.__('Do you want all logs to stop rotating by size ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
submitit(d, {done_reload : window.location.href});
});
show_message(a);
}
$("#log_search").keyup(function(){
var i = 0;
var log = $(this).val();
log = log.toLowerCase();
$(".logname").each(function(key, tr){
var l_name = $(this).text();
l_name = l_name.toLowerCase();
if(l_name.match(log)){
i++;
$(this).parent().parent().show();
}else{
$(this).parent().parent().hide();
}
})
if(i == 0){
$("#nofound").show();
}else{
$("#nofound").hide();
}
});
</script>';
softfooter();
}