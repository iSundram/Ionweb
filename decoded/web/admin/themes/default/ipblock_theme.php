<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function ipblock_theme() {
}
function showip() {
}else{
$i =1;
foreach ($add_list as $key => $value){
echo'
<tr id="tr'.$key.'">
<td>
<input type="checkbox" name="check_ips" class="check_ips" value="'.$key.'">
</td>
<td><span>'.$value.'</span></td>';
$tmp = cidr_to_iprange($value);
echo'
<td><span>'.$tmp[0].'</span></td>
<td><span>'.$tmp[1].'</span></td>
<td class="text-end">
<i class="fas fa-trash delete delete-icon"  title="Delete" id="did'.$key.'"  src="' . $theme['images'] . 'remove.gif" data-delete="'.$key.'" onclick="delete_record(this)"/></i>
</td>
</tr>';
$i++;
}
}
echo '</tbody>
</table>
<script>
$("#checkAll").change(function () {
$(".check_ips").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function() {
if($(".check_ips:checked").length){
$("#delete_selected").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled", true);
}
});
function delete_ips() {
});
a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete this selected IP(s) ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
var d = {"delete" : arr.join()};
submitit(d, {done_reload : window.location.href});
});
show_message(a);
}
</script>';
}