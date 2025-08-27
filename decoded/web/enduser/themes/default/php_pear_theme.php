<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function php_pear_theme() {
}else{
foreach($user_pear['data'] as $key => $value){
echo '
<tr id="tr'.$value['mod_name'].'">
<td>'.$value['mod_name'].'</td>
<td>'.$value['mod_ver'].' ('.$value['mod_state'].')</td>
<td data-action="upgrade" data-mod_name="'.$value['mod_name'].'-'.$value['mod_ver'].'" onclick="install_pear(this)" style="cursor:pointer"><i class="fas fa-upload"></i> '.__('Update').'</td>
<td data-action="reinstall" data-mod_name="'.$value['mod_name'].'-'.$value['mod_ver'].'" onclick="install_pear(this)" style="cursor:pointer"><i class="fab fa-rev"></i> '.__('Reinstall').'</a></td>
<td data-action="uninstall" data-mod_name="'.$value['mod_name'].'-'.$value['mod_ver'].'" onclick="install_pear(this)" data-doneremoverow="'.$value['mod_name'].'" style="cursor:pointer"><i class="fas fa-trash-alt"></i> '.__('Uninstall').'</a></td>
<td><a href="https://pear.php.net/package/'.$value['mod_name'].'/docs" target="_blank" class="btn text-decoration-none"><i class="fas fa-book"></i> '.__('Show Docs').'</a></td>
</tr>
';
}
}
echo '
</tbody>
</table>
<button class="btn flat-butt mt-4" id="showSysMods">'.__('Show System Installed Modules').'</button>
<table class="table align-middle table-nowrap mb-0 webuzo-table mt-4" id="sys_mods" style="display:none">
<thead class="sai_head2" style="background-color: #EFEFEF;">
<tr>
<th class="align-middle">'.__('Module Name').'</th>
<th class="align-middle">'.__('Version').'</th>
<th class="align-middle">'.__('Actions').'</th>
</tr>
</thead>
<tbody id="ipear_list">';
if(empty($system_pear['data'])){
echo '
<tr><td colspan="3" class="text-center">'.__('No module installed').'</td></tr>';
}else{
foreach($system_pear['data'] as $key => $value){
echo '
<tr>
<td>'.$value['mod_name'].'</td>
<td>'.$value['mod_ver'].' ('.$value['mod_state'].')</td>
<td><a href="https://pear.php.net/package/'.$value['mod_name'].'/docs" target="_blank"  class="btn text-decoration-none"><i class="fas fa-book"></i> '.__('Show Docs').'</a></td>
</tr>
';
}
}
echo '
</tbody>
</table>
</div>
</div>
<script>
$("#showSysMods").click(function(){
$("#sys_mods").toggle("slow", "swing");
});
function submitinspear() {
});
var d = {};
$.each(da, function(key, value){
d[value["name"]] = value["value"];
});
if(!d.mod_name){
return false;
}
var dd = jEle.data();
d = {...d, ...dd};
action_pear(d);
return false;
}
function install_pear() {
}
function action_pear() {
}
if("donereload" in d){
myModalEl.attr("data-donereload", 1);
}
}
if(typeof(data["error"]) != "undefined"){
let str = obj_join("\n", data["error"]);
$("#modIntallLog").html(str).show();
}
},
error: function(){
$(".pearProcess").hide();
$("#modIntallLog").html("'.__js('Oops there was an error while connecting to the $0 Server $1', ['<strong>', '</strong>']).'").show();
},
complete: function(){
myModalEl.find(".modal-header .btn-close").removeAttr("disabled");
myModalEl.find(".modal-footer .btn").removeAttr("disabled");
}
});
});
show_message(a);
}
$(document).on("hidden.bs.modal", "#modInstallation_modal", function(){
var d = $(this).data();
if("doneremoverow" in d){
$("#tr"+d.doneremoverow).remove();
}
if("donereload" in d){
location.reload();
}
$(this).removeAttr("data-donereload");
$(this).removeAttr("data-doneremoverow");
});
</script>';
softfooter();
}
?>