<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function sslkey_theme() {
});
$("#detailrectab").slideDown("slide", "", 5000).show();
}
}
});
});
</script>';
softfooter();
}
function showcert() {
}else{
foreach ($key_list as $key => $value){
$ext = get_extension($value);
if($ext == 'key'){
$file = get_filename($value);
echo '
<tr id="tr'.$key.'">
<td>
<span id="name'.$key.'">'.$file.'</span>
</td>
<td width="2%">
<i class="fas fa-file-alt edit edit-icon text-center me-2" title="Show" id="eid'.$key.'" data-detail_record="'.$file.'"></i>
</td>
<td width="2%">
<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$key.'" onclick="delete_record(this)" data-delete_record="'.$key.'"></i>
</td>
</tr>';
}
}
}
echo '
</tbody>
</table>';
}
?>