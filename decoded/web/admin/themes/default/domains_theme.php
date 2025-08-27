<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function domains_theme() {
}
}else{
echo '
<tr><td colspan=8><h3 style="text-align: center">No Record Found</h3></td></tr>';
}
echo '
</tbody>
</table>
</div>';
page_links();
echo '
<nav aria-label="Page navigation">
<ul class="pagination pager myPager justify-content-end">
</ul>
</nav>
</div>
<script>
$(document).ready(function () {
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
$("#user_search").on("select2:select", function(){
user = $("#user_search option:selected").val();
if(user == "all"){
window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'";
}else{
window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'&user_search="+user;
}
});
$("#dom_search").on("select2:select", function(){
var domain_selected = $("#dom_search option:selected").val();
if(domain_selected == "all"){
window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'";
}else{
window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'&dom_search="+domain_selected;
}
});
});
function del_domain() {
});
jEle.data("delete", domains.join());
var lang = confirmbox;
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
var d = jEle.data();
submitit(d, {
handle:function(data, p){
if(data.error){
var err = Object.values(data.error);
var e = show_message_r("'.__js('Error').'", err);
e.alert = "alert-danger"
show_message(e);
return false;
}
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
$("#dom_order").click(function(){
order_by = "ASC&by=domain";
if($("#dom_order").hasClass("fa-sort-up")){
order_by = "DESC&by=domain";
}
url = window.location.toString();
url = url.replace(/[\?&]order=[^&]+/, "").replace(/[\?&]by=[^&]+/, "");
window.location = url+"&order="+order_by;
});
var order = "'.optGET('order').'";
if(order == "ASC"){
$("#dom_order").removeClass("fa-sort");
$("#dom_order").addClass("fa-sort-up");
}
if(order == "DESC"){
$("#dom_order").removeClass("fa-sort");
$("#dom_order").addClass("fa-sort-down");
}
</script>';
softfooter();
}