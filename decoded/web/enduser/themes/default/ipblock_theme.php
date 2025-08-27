<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function ipblock_theme() {
}else{
$i =1;
foreach ($add_list as $key => $value){
echo '
<tr id="tr'.$key.'">
<td><input type="checkbox" name="check_ip" class="check_ip" value="'.$value.'"></td>
<td><span>'.$value.'</span></td>';
$tmp = cidr_to_iprange($value);
echo '
<td><span>'.$tmp[0].'</span></td>
<td><span>'.$tmp[1].'</span></td>
<td width="2%" align="center">
<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$key.'" onclick="delete_record(this)" data-ip="'.$value.'" data-delete="1"></i>
</td>
</tr>';
$i++;
}
}
echo '
</tbody>
</table>
</div><!-- IP table ended -->
</div>
<script>
$(document).ready(function(){
$("#checkall").change(function(){
$(".check_ip").prop("checked", $(this).prop("checked"));
})
$("input:checkbox").change(function(){
if($(".check_ip:checked").length){
$("#delete_selected").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled",true);
}
});
});
function del_blockip() {
});
a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete/Unblock selected IP(s) ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
var d = {"ip" : arr.join(),"delete":1};
console.log(d);
submitit(d, {done_reload : window.location.href});
});
show_message(a);
}
</script>';
softfooter();
}