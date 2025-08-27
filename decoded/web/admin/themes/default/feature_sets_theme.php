<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function feature_sets_theme() {
}else{
foreach($features as $key => $feature){
echo '
<tr id="tr'.$key.'">
<td>
'.$feature['feature_sets'].'
</td>
<td>
'.(empty($feature['owner']) ? 'root' : $feature['owner']).'
</td>
<td>
<i class="fas fa-copy" title="'.__('Clone').'" style="cursor:pointer;color: darkslategrey;" data-clone_feature="'.$feature['feature_sets'].'" onclick="clone_features(this)"></i>
</td>
<td>
<a href="'.$globals['admin_url'].'act=add_feature_sets&feature='.$key.'"><i class="fa-regular fa-pen-to-square edit-icon" title="'.__('Edit').'"></i></a>
</td>
<td>
<i class="fas fa-trash delete-icon" title="'.__('Delete').'" id="did'.$key.'" style="cursor:pointer" data-delete_feature="'.$key.'" onclick="delete_record(this)"></i>
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
</div>
<script language="javascript" type="text/javascript">
$("#feature_search").on("select2:select", function(e, u = {}){
feature = $("#feature_search option:selected").val();
if(feature == "all"){
window.location = "'.$globals['index'].'act=feature_sets";
}else{
window.location = "'.$globals['index'].'act=feature_sets&search="+feature;
}
});
$("#owner_search").on("select2:select", function(e, u = {}){
owner = $("#owner_search option:selected").val();
if(owner == "all"){
window.location = "'.$globals['index'].'act=feature_sets";
}else{
window.location = "'.$globals['index'].'act=feature_sets&owner="+owner;
}
});
function clone_features() {
}
</script>';
softfooter();
}