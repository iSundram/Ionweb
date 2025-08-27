<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function domainmanage_theme() {
}else{
echo '<td width="1%" class="text-center">-</td>';
}
echo '</tr>';
}
echo '
</tbody>
</table>
</div>
</div>
<script>
function force_https_toggle() {
}else{
lan = "'.__js('Are you sure you want to $0 Enable $1 Force HTTPS redirect for domain ', ['<b>', '</b>']).'<b>"+d.domain+"</b>";
}
a = show_message_r("'.__js('Warning').'", lan);
a.alert = "alert-warning";
var no = function(){
var status = d.do ? false : true;
jEle.prop("checked", status);
}
a.confirm.push(function(){
submitit(d, {
done_reload : window.location.href,
error: no
});
});
a.no.push(no);
a.onclose.push(no);
show_message(a);
}
$(document).ready(function(){
$("#checkall").change(function(){
$(".check_dom").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function(){
if($(".check_dom:checked").length){
$("#delete_selected").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled",true);
}
});
});
function del_dom() {
});
a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete selected Domain(s) ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
var d = {"delete" : arr.join()};
submitit(d,{done_reload : window.location.href });
});
show_message(a);
}
</script>';
softfooter();
}