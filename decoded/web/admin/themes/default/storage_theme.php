<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function storage_theme() {
}else{
foreach($storage as $k => $v){
echo '
<tr id="tr'.$v['uuid'].'" val="'.$k.'">
<td>'.$v['uuid'].'</td>
<td>'.$v['name'].'</td>
<td>
<a href="'.$globals['index'].'act=addstorage&storage='.$k.'" target="blank">'.$k.'</a>
</td>
<td>'.$v['type'].'</td>
<td>'.$v['alert'].'%</td>
<td>'.implode(', ', array_keys($v['users'])).'</td>
<td>'.round($v['size'], 2).' GB</td>
<td>'.round($v['used'], 2).' GB</td>
<td>'.round($v['free'], 2).' GB</td>
<td>
<a href="'.$globals['admin_url'].'act=editstorage&storage='.$k.'" title="'.__('Edit').'">
<i class="fas fa-pencil-alt edit-icon"></i>
</a>
</td>
<td>
<i class="fas fa-trash text-danger delete-icon" onclick="delete_record(this)" id="did'.$v['uuid'].'" data-delete="'.$k.'" title="'.__('Delete').'"></i>
</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>';
page_links();
echo '
</form>
</div>
<script language="javascript" type="text/javascript">
$("#storage_search").on("select2:select", function(e, u = {}){
var storage = $("#storage_search option:selected").val();
if(storage == "all"){
window.location = "'.$globals['index'].'act=storage";
}else{
window.location = "'.$globals['index'].'act=storage&storage="+storage;
}
});
$("#user_search").on("select2:select", function(e, u = {}){
user = $("#user_search option:selected").val();
window.location = "'.$globals['index'].'act=storage&search="+user;
});
</script>';
softfooter();
}