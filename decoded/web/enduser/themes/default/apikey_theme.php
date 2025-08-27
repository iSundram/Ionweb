<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function apikey_theme() {
}else{
foreach ($apikeys as $key => $v){
echo '
<tr id="tr'.$key.'">
<td><input type="checkbox" class="check_apikey" name="check_apikey" value="'.$key.'"></td>
<td>'.$WE->user['user'].'</td>
<td>'.$key.'</td>
<td>'.(empty($v['ips']) ? __('All IPs') : implode(', ', $v['ips'])).'</td>
<td>'.(empty($v['notes']) ? 'NA' : $v['notes']).'</td>
<td>'.datify($v['created']).'</td>
<td width="1%" class="text-center">
<a href="javascript:open_add_api_modal(\''.$key.'\', \''.implode(',', (array)$v['ips']).'\', \''.$v['notes'].'\')" id="edit'.$key.'" title="'.__('Edit').'">
<i class="fas fa-pencil-alt edit-icon"></i>
</a>
</td>
<td width="1%" class="text-center">
<i class="fas fa-trash delete-icon" data-del="'.$key.'" title="Delete" id="did'.$key.'" onclick="delete_record(this)""></i>
</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>
</div>
</form>
</div>';
echo '
<script language="javascript" type="text/javascript">
function open_add_api_modal() {
}
}
function add_key() {
});
}
var data = {"do" : 1, "ip" : ip, "notes" : notes, "key" : key};
submitit(data, {
done_reload:window.location
});
}
$(document).ready(function(){
$("#checkall").change(function(){
$(".check_apikey").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function(){
if($(".check_apikey:checked").length){
$("#delete_selected").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled",true);
}
});
});
function del_apikey() {
});
a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete this selected API Key(s) ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
var d = {"del" : arr.join()};
console.log(d);
submitit(d,{done_reload : window.location.href});
});
show_message(a);
}
</script>';
softfooter();
}