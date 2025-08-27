<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function sel_home_files_theme() {
}else{
echo '
<p class="modal_file_name" ></p>
<div class="sai_main_head mb-4 row">
<div class=" py-3 d-inline-block col-9">
<i class="fa fa-arrow-circle-left" title="back" onclick="showSubs(\''.$backup_path.'\', \''.basename($backup_path).'\', \''.NULL.'\', 1)" id="back_path"></i>
<span id="files_route"> <i class="fas fa-home home-directory" > '.$backup_homepath.'</i></span>
</div>
<div class="col-3">
<button class="float-end border-0 p-2">
<input type="checkbox" name="show_hidden_files" id="show_hidden_files">
<label for="show_hidden_files" >'.__('Show Hidden Files').'</label>
</button>
</div>
</div>
<div id="div1">';
echo'<table border="0" cellpadding="6" cellspacing="1" width="100%" class="table align-middle table-nowrap mb-0 webuzo-table">';
echo '
<thead class="sai_head2">
<tr>
<th width="1%"><input type="checkbox" id="checkAllFiles"></th>
<th class="align-middle" width="35%">'.__('Filename').'</th>
<th class="align-middle" width="10%">'.__('size').'</th>
<th class="align-middle" width="10%">'.__('Type').'</th>
<th class="align-middle" width="20%">'.__('Created').'</th>
</tr>
</thead>
<tbody id="full_show_table">';
listDir($backup_path);
echo'
</tbody>
</table>
<div class="col-12">
<div class="sai_sub_head record-table m-3 position-relative" style="text-align:center;">
<input type="button" class="btn btn-primary" value="'.__('Restore Selection').'" name="restore_selected" id="restore_selected" onclick="" >
</div>
</div>
</div>
</div>';
}
echo '
</div>
</div>
</div>
</div>
<script language="javascript" type="text/javascript">
$("#show_hidden_files").change(function() {
if($("#show_hidden_files").is(":checked")){
$(".selection_files_tr").show();
} else{
$(".selection_files_tr").hide();
}
});
$("input:checkbox[name=checked_file]").each(function(){
if(sel_files_val.includes($(this).val())){
var checkfile = $(this).val();
checkfile = checkfile.replace(/[./]/g, "_");
$("#check_"+checkfile).prop("checked", true);
}
});
$("#checkAllFiles").change(function () {
$(".check").prop("checked", $(this).prop("checked"));
});
$("#restore_selected").click(function(){
$(".loading").show();
$("input:checkbox[name=checked_file]:checked").each(function(){
if($(this).is(":visible") == true && !sel_files_val.includes("'.$backup_path.'") && !sel_files_val.includes($(this).val())){
selecetd_files.push($(this).val());
sel_files_val.push($(this).val());
sel_files_key.push("'.$backup_path.'");
}
});
selecetd_files_arr = JSON.stringify(selecetd_files);
var d = "&show_files=1&restore=1&sel_restore=1&selecetd_files_arr="+selecetd_files_arr+"&backup_filename="+backup_filename
submitit(d, {
sm_done_onclose: function(){
$(".file_selection").click();
$(".loading").hide();
location.reload(true);
}
});
});
</script>';
return true;
}
if(optGET('refreshTable') && !empty($done) && optGET('refreshTable') == 'refresh_homeBackup'){
show_table('full', '.tar.gz');
return true;
}
softheader(__('Backuply Home'));
echo '
<style>
.sai_popup {
position:absolute;
background:#FFFFFF;
border:#666 1px solid;
display:none;
z-index:10000;
min-height:200px;
padding:5px;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
}
.fa-rotate-360{
-webkit-transform: rotate(360deg);
-moz-transform: rotate(360deg);
-ms-transform: rotate(360deg);
-o-transform: rotate(360deg);
transform: rotate(360deg);
transition: .8s;
}
@media screen and (min-width:320px) and (max-width: 560px) {
.sai_popup{
top: 50%;
left: 50%;
width:80%;
}
ul li {
width:100%;
}
}
@media screen and (min-width:560px) and (max-width: 1030px) {
ul li {
width:50%;
}
}
</style>
<script language="javascript" type="text/javascript">
function onChange() {
}
$(document).ready(function(){
});
function remove_hash() {
}
return w_l;
}
function restore_backup() {
}
});
});
show_message(d);
}
function get_logs() {
}else{
dataval = "";
}
var w_l = remove_hash(window.location.toString());
$.ajax({
type: "POST",
url: w_l+"&api=json",
data: dataval,
dataType : "json",
success: function(data){
$(".loading").hide();
if("done" in data){
if(id == "clear_log"){
var d = show_message_r(l.done, "'.__js('Logs cleared').'");
d.alert = "alert-success";
show_message(d);
}
$("#showlog").text(data["wbackup_log"]);
}else{
var d = show_message_r("'.__js('Error').'", data["error"]);
d.alert = "alert-danger";
show_message(d);
}
},
error: function(){
$(".loading").hide();
var d = show_message_r("'.__js('Error').'", \''.__js('Unable to connect to the server').'\');
d.alert = "alert-danger";
show_message(d);
}
});
}
function rotate_img() {
}
});
};
$(".backup_note").click(function(){
$(".note_span").hide();
$(".note_input").show();
$(".note_input").change(function(){
$(".note_span").show();
$(".note_input").hide();
});
});
var selecetd_files = [];
var parent_dir = [];
var sel_files_val = [];
var sel_files_key = [];
function showSubs() {
}
parent_dir = $(".parent_dir").val();
checkfile = folder_path.replace(/[./:]/g, "_");
if($("#check_"+checkfile).is(":checked")){
var dir_check = 1;
}
if(!empty(back_path)){
folder_path = folder_path.substring(0, folder_path.indexOf(filename))
}
$("input:checkbox[name=checked_file]:checked").each(function(){
if($(this).is(":visible") == true && !sel_files_val.includes(parent_dir) && !sel_files_val.includes($(this).val())){
selecetd_files.push($(this).val()
);
sel_files_val.push($(this).val());
sel_files_key.push(parent_dir);
}
});
var w_l = remove_hash(window.location.toString());
$.ajax({
type: "POST",
url: w_l+"&show_files=1",
data: "backup_path="+backup_path+"&backup_filename="+backup_filename+"&folder_path="+folder_path+"&filename="+filename+"&bak_server_id="+bak_server_id+"&dir_check="+dir_check+"&backup_method="+bak_method,
success: function(data){
$(".loading").hide();
$(".file_selection_modal").html(data);
if(empty(folder_path)){
$("#selection-modal-btn").trigger("click");
}
}
});
}
</script>
<button type="button" id="selection-modal-btn" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#file_selection_modal"></button>
<div class="file_selection_modal modal fade" id="file_selection_modal" tabindex="-1" aria-labelledby="add-spfLabel" aria-hidden="true"></div>
<div class="card soft-card p-4">';
backuply_tabs('backuphome');
echo '
<div class="tab-content" id="pills-tabContent">
<!-- Home Backup -->
<div class="tab-pane fade show active" id="backuphome" role="tabpanel" aria-labelledby="backuphome_a">
<div class="sai_main_head mb-3 mt-4">
<i class="fas fa-folder expander fa-2x">
<h5 class="d-inline-block">'.__('Home Restore').'</h5></i>
<span type="button" id="refresh_homeBackup" onclick="rotate_img(this);" class="refresh-icon float-end me-3 mt-3" title="'.__('Refresh Table').'">
<i class="fas fa-sync-alt"></i>
</span>
</div>
<div class="table-responsive mb-4" id="refresh_homeBackupDisplay">';
show_table('full', '.tar.gz');
echo '
</div>
<div id="msg_backup_home" class="text-center"></div>
</div>
<!-- Home Backup end-->
</div>
</div>
<div class="row">
<div class="sai_popup"></div>
</div>';
softfooter();
}
function show_table() {
}
echo'
<th class="align-middle" width="15%">'.__('Created').'</th>
<th class="align-middle" width="10%">'.__('Method').'</th>
<th class="align-middle" width="10%">'.__('Server').'</th>
<th class="align-middle" width="5%">'.__('Size').'</th>
<th class="align-middle" width="5%">'.__('Options').'</th>
</tr>
</thead>
<tbody id="'.$type.'_show_table">';
if(empty($wbackup_list[$type])){
echo '
<tr class="text-center">
<td colspan=11>
<span>'.__('There are no home backup files found').' !</span>
</td>
</tr>';
}else{
foreach($wbackup_list['full'] as $k => $v){
echo '
<tr id="'.basename($v['filename']).'">
<td>'.$v['filename'].'
<input id="bac_path_'.$v['filename'].'" value="'.$v['backup_location'].'" style="display:none">
<input id="bac_method_'.$v['filename'].'" value="'.$v['backup_method'].'" style="display:none">
<input id="bac_filename_'.$v['filename'].'" value="'.$v['filename'].'" style="display:none">
<input id="bac_server_'.$v['filename'].'" value="'.$v['backup_server_id'].'" style="display:none">
</td>';
echo '<td>'.($v['backup_method'] == 'compressed' ? 'All Files/Directories Selected' : 'All Files/Directories Selected<br><a href="#" class="btn btn-primary btn-sm restore_file_select" id="res_sel_'.basename($v['filename']).'" onclick="showSubs(\''.NULL.'\', \''.$v['filename'].'\', \''.$v['backup_server_id'].'\');" title="'.__('Partial Restore').'">Change Files Selection</a>').'</td>';
echo '
<td>'.datify($v['created']).'</td>
<td>'.ucfirst(!empty($v['backup_method']) ? $v['backup_method'] : 'compressed').'</td>
<td>'.$v['server_name'].' ('.($v['backup_server_id'] == -1 ? 'Local' : 'Remote').')</td>
<td>'.(!empty($v['size']) ? trim(round(($v['size']/1024)/1024, 2).' M') : "--").'</td>';
echo '
<td onclick="restore_backup(this.parentNode.id, '.$v['backup_server_id'].', \''.$v['type'].'\', \''.$v['backup_method'].'\', \''.$v['backup_location'].'\');" class="text-center">
<i class="fas fa-undo restore edit-icon" title="'.__('Restore').'"></i>
</td>
</tr>';
}
}
echo '
</tbody>
</table>';
}