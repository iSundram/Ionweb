<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function servergroups_theme() {
}else{
foreach($servergroups as $key => $value){
echo '
<tr id="tr'.$key.'">
<td>
<input type="checkbox" class="check sgcheck" name="checked" value="'.$key.'">
</td>
<td>'.$value['name'].'</td>
<td>'.$value['desc'].'</td>
<td>'.$value['region'].'</td>
<td>'.$sg_selection[$value['server_selection']].'</td>
<td>'.implode(', ', $value['servers']).'</td>
<td>
<a href="'.$globals['admin_url'].'act=add_server_group&uuid='.$key.'">
<i class="fas fa-pencil-alt edit-icon" title="'.__('Edit').'"></i>
</a>
</td>
<td>
'.($key != 'default' ? '<i class="fas fa-trash delete-icon" onclick="delete_record(this)" id="did'.$key.'" data-delete="'.$key.'"  title="'.__('Delete').'"></i>' : '').'
</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>';
echo'
</form>
</div>
<script>
$(document).ready(function (){
$("#checkAll").change(function () {
$(".sgcheck").prop("checked", $(this).prop("checked"));
$(".sgcheck").change();
});
$(".sgcheck").change(function() {
if($(".sgcheck:checked").length){
$("#sg_delete_selected").removeAttr("disabled");
}else{
$("#sg_delete_selected").prop("disabled", true);
}
});
});
$("#sg_search").on("select2:select", function(e, u = {}){
user = $("#sg_search option:selected").val();
if(user == "all"){
window.location = "'.$globals['index'].'act=servergroups'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}else{
window.location = "'.$globals['index'].'act=servergroups&search="+user+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
}
});
</script>';
softfooter();
}