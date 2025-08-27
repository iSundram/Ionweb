<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function change_domain_ip_theme() {
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
$("#user_search").on("select2:select", function(){
user = $("#user_search option:selected").val();
if(user == "all"){
window.location = "'.$globals['index'].'act=change_domain_ip";
}else{
window.location = "'.$globals['index'].'act=change_domain_ip&user_search="+user;
}
});
$("#dom_search").on("select2:select", function(){
var domain_selected = $("#dom_search option:selected").val();
if(domain_selected == "all"){
window.location = "'.$globals['index'].'act=change_domain_ip";
}else{
window.location = "'.$globals['index'].'act=change_domain_ip&dom_search="+domain_selected;
}
});
});
$(document).on("click", ".cancel", function() {
var id = $(this).attr("id");
id = id.substr(3);
$("#cid"+id).hide();
$("#eid"+id).removeClass("fa-save").addClass("fa-edit");
$("#tr"+id).find("span").show();
$("#tr"+id).find(".hideselect").hide();
});
$(document).on("click", ".edit", function() {
var id = $(this).attr("id");
id = id.substr(3);
$("#cid"+id).show();
make_select2($("#s2ip"+id));
make_select2($("#s2ipv6"+id));
if($("#eid"+id).hasClass("fa-save")){
var d = $("#tr"+id).find("input, select").serialize();
submitit(d, {
done: function(){
var tr = $("#tr"+id);
tr.find(".cancel").click();// Revert showing the inputs
tr.find("select").each(function(){
var jE = $(this);
jE.closest("td").find("span").html(jE.val());
});
},
sm_done_onclose: function(){
$("#tr"+id).find("span").show();
$("#tr"+id).find(".hideselect").hide();
location.reload();
}
});
}else{
$("#eid"+id).addClass("fa-save").removeClass("fa-edit");
$("#tr"+id).find(".exist_ip").hide();
$("#tr"+id).find(".hideselect").show();
}
});
</script>';
softfooter();
}