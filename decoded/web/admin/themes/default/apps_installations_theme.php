<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function apps_installations_theme() {
}
ksort($user['apps_ins']);
echo '
<div class="soft-smbox p-3">
<div class="sai_main_head">
<i class="fa-solid fa-list me-2 mt-1"></i>'.__('Applications Installations').'
<span class="search_btn float-end">
<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
</span>
</div>
<div class="mt-2 px-2" style="background-color:#e9ecef;">
<div class="collapse" id="search_queue">
<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
<div class="row d-flex justify-content-center">
<div class="col-12 col-md-6 mb-4 mt-2">
<label class="sai_head">'.__('Search By App Name').'</label>
<input type="text" class="form-control search_val" name="search_app" id="app_search" value="">
</div>
</div>
</form>
</div>
</div>
</div>
<div class="soft-smbox p-3 mt-4">
<div class="table-responsive">
<table class="table webuzo-table">
<thead>
<tr>
<th>'.__('App').'</th>
<th>'.__('Path').'</th>
<th>'.__('Installation Time').'</th>
<th>'.__('Auto Upgrade').'</th>
<th>'.__('Version').'</th>
<th colspan="2">'.__('Options').'</th>
</tr>
</thead>';
if(count($user['apps_ins']) > 0){
echo '<tbody>';
foreach($user['apps_ins'] as $k => $v){
foreach($v as $ik => $iv){
if($apps[$iv['aid']]['type'] == 'library') continue;
echo '
<tr id="trid' . $ik . '">
<td class="app_name">
<a href='.$globals['ind'].'act=apps&app='.$iv['aid'].'>'.$apps[$iv['aid']]['name'].'</a>
</td>
<td>'.(empty($iv['path']['base']) ? __('/usr/local/apps') : $iv['path']['base']).'</td>
<td>'.datify($iv['itime']).'</td>';
if(empty($iv['_auto_upgrade'])){
echo '<td>'.__('NA').'</td>';
}else{
echo '<td>
<select class="form-select" id="auto_upgrade_type'.$iv['aid'].'" onchange="autoUpgradeApp('.$iv['aid'].')">
<option value="0" '.POSTselect('auto_upgrade_type', 0, $iv['auto_upgrade'] == 0).'>'.__('Do not Auto Upgrade').'</option>
<option value="2" '.POSTselect('auto_upgrade_type', 1, $iv['auto_upgrade'] == 2).'>'.__('Upgrade to <b>Minor</b> versions only').'</option>
<option value="1" '.POSTselect('auto_upgrade_type', 2, $iv['auto_upgrade'] == 1).'>'.__('Upgrade to latest version (<b>Major</b> or <b>Minor</b>)').'</option>
</select>
</td>';
}
echo '
<td>
<span class="sai_exp">'.__('Mod: $0 $2 Mod-Files: $1',[ $iv['mod'], $iv['mod_files'], '<br>' ]).'</span>'.$iv['version'].'
</td>
<td width="10">
<a href="'.$globals['ind'].'act=apps&app='.$iv['aid'].'" title="'.__('Remove').'">
<i class="fas fa-trash delete-icon"></i>
</a>
</td>
<td width="10"><input type="checkbox" name="ainsids[]" value="'.$iv['aid'].'"></td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>';
}else{
echo '<div class="row text-center">'.__('You do not have any installations').'</div>';
}
echo '
<div class="text-end mb-2 my-3">
<label for="multi_options_apps">'.__('With Selected').': </label>
<select name="multi_options_apps" id="multi_options_apps" class="form-select d-inline-block" style="width:auto;">
<option value="0">---</option>
<option value="mult_rem">'.__('Remove Apps').'</option>
</select>
<input type="button" class="btn btn-primary" value="'.__('Go').'" onclick="show_confirm_apps()">
</div>
<div id="rem_div_apps"></div>
</div>
<div class="soft-smbox mt-3 p-3">
<div class="sai_main_head text-center">
'.__('Libraries Installed').'
<span class="search_btn float-end">
<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue2" aria-expanded="true" aria-controls="search_queue2" title="'.__('Search').'"><i class="fas fa-search"></i></a>
</span>
</div>
<div class="mb-4 mt-2 px-2" style="background-color:#e9ecef;">
<div class="collapse" id="search_queue2">
<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
<div class="row d-flex justify-content-center">
<div class="col-12 col-md-6 mb-4 mt-2">
<label class="sai_head">'.__('Search By Library Name').'</label>
<input type="text" class="form-control search_val" name="search_lib" id="lib_search" value="">
</div>
</div>
</form>
</div>
</div>
<hr>
<div class="table-responsive">
<table class="table webuzo-table">
<thead>
<tr>
<th>'.__('App').'</th>
<th>'.__('Path').'</th>
<th>'.__('Installation Time').'</th>
<th>'.__('Auto Upgrade').'</th>
<th>'.__('Version').'</th>
<th colspan="2">'.__('Options').'</th>
</tr>
</thead>
<tbody>';
foreach($user['apps_ins'] as $k => $v){
foreach($v as $ik => $iv){
if($iv['type'] != 'library') continue;
echo '
<tr id="trid' . $ik . '">
<td class="lib_name">
<a href='.$globals['ind'].'act=apps&app='.$iv['aid'].'>'.$apps[$iv['aid']]['name'].'</a>
</td>
<td>'.(empty($iv['path']['base']) ? __('/usr/local/apps') : $iv['path']['base']).'</td>
<td>'.datify($iv['itime']).'</td>';
if(empty($iv['_auto_upgrade'])){
echo '<td>'.__('NA').'</td>';
}else{
echo '<td>
<select class="form-select" id="auto_upgrade_type'.$iv['aid'].'" onchange="autoUpgradeApp('.$iv['aid'].')">
<option value="0" '.POSTselect('auto_upgrade_type', 0, $iv['auto_upgrade'] == 0).'>'.__('Do not Auto Upgrade').'</option>
<option value="2" '.POSTselect('auto_upgrade_type', 1, $iv['auto_upgrade'] == 2).'>'.__('Upgrade to <b>Minor</b> versions only').'</option>
<option value="1" '.POSTselect('auto_upgrade_type', 2, $iv['auto_upgrade'] == 1).'>'.__('Upgrade to latest version (<b>Major</b> or <b>Minor</b>)').'</option>
</select>
</td>';
}
echo '
<td>'.$iv['version'].'</td>
<td width="10">
<a href="'.$globals['ind'].'act=apps&app='.$iv['aid'].'" title="'.__('Remove').'">
<i class="fas fa-trash delete-icon"></i>
</a>
</td>
<td width="10"><input type="checkbox" name="ainsids_lib[]" value="'.$iv['aid'].'"></td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>
<div class="text-end mb-2 my-3">
<label for="multi_options_apps_lib">'.__('With Selected').': </label>
<select name="multi_options_apps" id="multi_options_apps_lib" class="form-select d-inline-block" style="width:auto;">
<option value="0">---</option>
<option value="mult_rem">'.__('Remove Apps').'</option>
</select>
<input type="button" class="btn btn-primary" value="'.__('Go').'" onclick="show_confirm_apps(\'_lib\')">
</div>
<div id="rem_div_apps_lib"></div>
</div>
<script language="javascript" type="text/javascript">
ainsids = new Array();
removed = new Object();
function show_confirm_apps() {
}
var field = document.getElementsByName("ainsids"+suf+"[]");
ainsids = new Array();
var c = 0;
for(i = 0; i < field.length; i++){
if(field[i].checked == true){
ainsids[c] = field[i].value;
c++;
}
}
var a = {};
if(c == 0){
a = show_message_r("'.__js('Info').'", "'.__js('No installation(s) selected to remove.').'");
a.alert = "alert-info";
a.ok.push(function(){
});
show_message(a);
return false;
}
a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you wish to remove the selected installations ? The action will be irreversible. \\nNo further confirmations will be asked !').'");
a.alert = "alert-warning";
a.confirm.push(function(){
remove_by_id_apps(ainsids[0], "", 0, suf);
});
show_message(a);
}
function remove_by_id_apps() {
}
}
nextainsid = 0;
for(i = 0; i < ainsids.length; i++){
if(typeof(removed[ainsids[i]]) != "undefined"){
continue;
}
nextainsid = ainsids[i];
break;
}
if(ainsid != 0){
try{
AJAX("'.$globals['index'].'act=apps&app="+ainsid+"&remove=1&ajax=1&random="+Math.random(), "remove_by_id_apps(\'"+nextainsid+"\', re, \'"+ainsid+"\', \'"+suf+"\')");
$_("rem_div_apps"+suf).innerHTML = "<br /><br /><p class=\"text-center\"><img src=\"' . $theme['images'] . 'ajax_remove.gif\"> <br />'.__('Removing Installation - '). ' ID: " +ainsid+ "<br /></p>";
return true;
}catch(e){
alert(e.description);
return false;
}
}
$_("rem_div_apps"+suf).innerHTML = "";
a = show_message_r("'.__js('Info').'", "'.__js('The selected installation(s) have been removed. The page will now be reloaded !').'");
a.alert = "alert-info";
a.ok.push(function(){
location.reload(true);
});
show_message(a);
return true;
}
var search_app_fn = function(app, classname){
app = app.toLowerCase();
$(classname).each(function(key, tr){
var a_name = $(this).children().text();
a_name = a_name.toLowerCase();
if(a_name.match(app)){
$(this).parent().show();
}else{
$(this).parent().hide();
}
})
}
$("#app_search").keyup(function(){
search_app_fn($(this).val(), ".app_name");
});
$("#lib_search").keyup(function(){
search_app_fn($(this).val(), ".lib_name");
});
function autoUpgradeApp() {
}
submitit(data, {
done_reload: window.location
});
}
</script>';
softfooter();
}