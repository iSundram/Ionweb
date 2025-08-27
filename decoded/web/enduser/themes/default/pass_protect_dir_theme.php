<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function pass_protect_dir_theme() {
}
}
$i = 1;
if(empty($flag)){
echo '
<tr>
<td colspan="100" class="text-center">
<span>'.__('No password protected directories found').'</span>
</td>
</tr>';
}else{
foreach ($list_users as $key => $value){
foreach($value['users'] as $kk => $vv){
echo '
<tr id="tr'.str_replace("/", "-", $value['spath']).''.$vv.'">
<td><input type="checkbox" class="check_passpro" name="check_passpro" value="'.$vv.'" data-user="'.$vv.'" data-path="'.$value['spath'].'" data-delete="1"></td>
<td>'.$value['spath'].'</td>
<td>'.$vv.'</td>
<td width="2%" class="text-center">
<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$kk.'" onclick="delete_record(this)" data-user="'.$vv.'" data-path="'.$value['spath'].'" data-delete="1"></i>
</td>
</tr>';
}
}
}
echo '
</tbody>
</table>
</div>
</div>
<script>
$(document).ready(function(){
$("#checkall").change(function(){
$(".check_passpro").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function(){
if($(".check_passpro:checked").length){
$("#delete_selected").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled",true);
}
});
});
function del_protected_dir() {
});
a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete selected password protected Directory(s) ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
var d = {"delete" : arr3.join(), "path" : arr2.join(), "user" : arr.join() };
submitit(d ,{done_reload : window.location.href});
});
show_message(a);
}
</script>';
softfooter();
}