<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function blacklist_domain_theme() {
}else{
foreach($blacklist_domain as $k => $v){
foreach($v as $dk => $dv){
echo '
<tr id="tr'.$k.$dk.'">
<td colspan="2"><input type="checkbox" group="block_domain" class="selectblockdom" data-domain="'.$dv.'" data-id="'.$k.$dk.'"></td>
<td>'.$dv.'</td>
<td><i class="fas fa-trash delete delete-icon" title="Delete" id="did'.$k.$dk.'" onclick="delete_record(this)" data-domain="'.$dv.'" data-delete="1"></i></td>
</tr>
';
}
}
}
echo '
</tbody>
</table>
</div>
</div>
<script>
function handleManyaction() {
});
if(len >= 1){
$(".manyactionbtn").each(function(){
$(this).removeAttr("disabled");
});
}else{
$(".manyactionbtn").each(function(){
$(this).attr("disabled", "disabled");
});
}
}
function selectAlldomcheck() {
}else{
$(this).prop("checked", false);
}
});
handleManyaction(k);
}
$(".selectblockdom").change(function(){
handleManyaction("selectblockdom");
});
$(document).on("done:delete_many", ".manyactionbtn", function(){
$(this).parent().children().first().prop("checked", false);
handleManyaction("selectblockdom");
});
</script>';
softfooter();
}