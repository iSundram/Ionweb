<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function quota_modification_theme() {
}
echo '
</select>
<div class="mt-2"><input type="checkbox" id="dis_hard_'.$value['id'].'" value="1" '.(!empty($value['info']['P']['disable_hard_limit']) ? 'checked' : '').' ><span> Disable hard limit for quota and inodes</span></div>
<input type="hidden" class="user" value="'.$value['info']['P']['max_inode'].'" id="ind'.$value['id'].'">
</td>
<td width="20%" style="min-width:110px;">
<input type="number" class="form-control quotabox" value="'.(($value['info']['P']['max_disk_limit'] != 'unlimited') ? $value['info']['P']['max_disk_limit'] : '10240').'" id="box'.$value['id'].'" '.(($value['info']['P']['max_disk_limit'] == 'unlimited' || (!empty($value['plan']) && !array_key_exists('max_disk_limit', $value['info']['p']))) ? 'style="display:none"' : '').' title="'.__('Set limit in MB').'">
</td>
</tr>';
}
}else{
echo '<tr><td colspan=9><h3 style="text-align: center">'.__('No Record found').'</h3></td></tr>';
}
echo '
</tbody>
</table>
</div>
<div class="text-center mt-2">
<input type="button" class="btn btn-primary" value="'.__('Modify').'" data-modify="1" onclick="modify_limit(this)">
</div>
</form>
</div>';
page_links();
echo '
<script>
$(".quotabox, .quotaselect").change(function(){
var id = $(this).attr("id");
id = id.substr(3);
$("#chk"+id).prop("checked", true);
})
function modify_limit() {
};
$(".check:checked").each(function(key,tr){
var id = $(this).attr("id");
id = id.substr(3);
arr[$("#usr"+id).val()] = {quota: $("#sel"+id).val(), limit: ($("#sel"+id).val() == "unlimited" ? "unlimited" : $("#box"+id).val()), inode: $("#ind"+id).val(), disable_hard_limit: $("#dis_hard_"+id).is(":checked") ? 1 : 0};
});
jEle.data("main_array", arr);
var d = jEle.data();
submitit(d,{
done_reload: window.location
});
}
$("#user_search").on("select2:select", function(){
user = $("#user_search option:selected").val();
if(user == "all"){
window.location = "'.$globals['index'].'act=quota_modification";
}else{
window.location = "'.$globals['index'].'act=quota_modification&user_search="+user;
}
});
$("#dom_search").on("select2:select", function(){
var domain = $("#dom_search option:selected").val();
if(domain == "all"){
window.location = "'.$globals['index'].'act=quota_modification";
}else{
window.location = "'.$globals['index'].'act=quota_modification&dom_search="+domain;
}
});
$("#checkAll").change(function(){
$(".check").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function(){
if($(".check:checked").length){
$("#mainselect").removeAttr("disabled");
}else{
$("#mainselect").prop("disabled", true);
$("#mainbox").hide();
}
});
$("#mainselect").change(function(){
var val = $(this).val();
if(val == "custom"){
$("#mainbox").show();
}else{
$("#mainbox").hide();
}
$(".check").each(function(key, tr){
var id = $(this).attr("id");
id = id.substr(3);
if(document.getElementById("chk"+id).checked){
if(val == "plan_default"){
var plan = $(this).attr("plan");
if(!empty(plan)){
$("#sel"+id).val(val);
}
}else{
$("#sel"+id).val(val);
}
if($("#sel"+id).val() == "custom"){
$("#box"+id).show();
}else{
$("#box"+id).hide();
}
}
});
});
$(".quotaselect").change(function(){
var id = $(this).attr("id");
id = id.substr(3);
if($(this).val() == "custom"){
$("#box"+id).show();
}else{
$("#box"+id).hide();
}
});
$("#mainbox").on("keyup", function(){
var val = $(this).val();
$(".check").each(function(key, tr){
var id = $(this).attr("id");
id = id.substr(3);
if(document.getElementById("chk"+id).checked){
$("#box"+id).val(val);
}
});
});
</script>';
softfooter();
}