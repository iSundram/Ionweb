<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function dns_zones_theme() {
}else{
foreach ($dns_zones as $key => $val){
echo '
<tr id="tr'.$key.'">
<td>
<input type="checkbox" class="check" name="checked_dns" id="check'.$key.'" value="'.$key.'" >
</td>
<td>
<span><a href="'.$globals['index'].'act=advancedns&domain='.$key.'">'.$key.'</a></span>
</td>
<td>
<span >'.$val.'</span>
</td>
<td>
<a href="'.$globals['index'].'act=advancedns&domain='.$key.'#add_record&type=A"><button type="button" class="btn btn-secondary"><i class="fa fa-plus"></i> '.__('A Record').'</button></a>
<a href="'.$globals['index'].'act=advancedns&domain='.$key.'#add_record&type=CNAME"><button type="button" class="btn btn-secondary"><i class="fa fa-plus"></i> '.__('CNAME Record').'</button></a>
<a href="'.$globals['index'].'act=mxentry&domain='.$key.'#add-MX"><button type="button" class="btn btn-secondary"><i class="fa fa-plus"></i> '.__('MX Record').'</button></a>
<a href="'.$globals['index'].'act=advancedns&domain='.$key.'"><button type="button" class="btn btn-secondary"><i class="fa fa-wrench"></i> '.__('Manage').'</button></a>
</td>
<td class="text-center">
<i class="fas fa-trash delete delete-icon delzone" id="did'.$key.'" title="'.__('Delete').'" data-donereload="1" data-delete="1" data-domain="'.$key.'" onclick="delete_record(this)"></i>
</td>
</tr>';
}
}
echo '
<tbody>
</table>
</div>
</div>
</div>
<script>
$(document).ready(function () {
$(window).on("hashchange", add_dns_hash);
add_dns_hash();
$("#user_search").on("select2:select", function(){
user = $("#user_search option:selected").val();
window.location = "'.$globals['index'].'act=dns_zones&user_search="+user;
});
$("#dom_search").on("select2:select", function(){
var domain_selected = $("#dom_search option:selected").val();
window.location = "'.$globals['index'].'act=dns_zones&dom_search="+domain_selected;
});
});
function add_dns_hash() {
}
}
function add_dns_modal() {
}
$("#checkAll").change(function () {
$(".check").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function() {
if($(".check:checked").length){
$("#del_selected").removeAttr("disabled");
}else{
$("#del_selected").prop("disabled", true);
}
});
function multi_delete() {
});
var lang = "'.__js('Are you sure you want to delete the selected DNS zone(s) ?').'";
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
var d = {"delete" : 1, "domain" : arr.join(",")};
submitit(d, {
done_reload: window.location
});
});
show_message(a);
}
</script>';
softfooter();
}