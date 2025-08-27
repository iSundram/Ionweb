<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function sel_domain_files_theme() {
}
softheader(__('Backuply Domains'));
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
function basename() {
}
function dirname() {
}
$("#checkAllFiles").change(function () {
$(".check").prop("checked", $(this).prop("checked"));
});
</script>
<div class="card soft-card p-4">';
backuply_tabs('backupdomain');
echo '
<div class="tab-content" id="pills-tabContent">
<!-- Domains Now-->
<div class="tab-pane fade show active" id="backupdomain" role="tabpanel" aria-labelledby="backupdomain_a">
<div class="sai_main_head mb-4">
<img src="'.$theme['images'].'backup_restore.png" alt="" class="mt-2 me-2"/>
<h5 class="d-inline-block">'. __('Domain Restore').'</h5>
<span type="button" id="refresh_domainBackup" onclick="rotate_img(this);" data-tab="domains" class="refresh-icon float-end mt-3" title="'.__('Refresh Table').'">
<i class="fas fa-sync-alt"></i>
</span>
</div>
<div class="table-responsive domains_table" id="refresh_domainBackupDisplay">';
show_table_domains('domains', '.tar.gz');
echo '
</div>
<div class="text-center" id="msg_backup_domain"></div>
</div>
<!-- Domains Now end-->
</div>
</div>
<div class="row">
<div class="sai_popup"></div>
</div>';
softfooter();
}
function show_table_domains() {
}else{
foreach($domainbackups[$type] as $db => $dbinfo){
$db_id = str_replace(['@', '.'], '_', $db);
$db_backups = explode(',', $dbinfo['backup_files']);
$bconut = count($db_backups);
$file_data = backup_file_data($WE->user['user'], 'full', current($db_backups));
echo '
<tr id="'.$db_id.'">
<td>'.$db.'</td>
<td>
<span id="'.$db_id.'_filespan">'.$file_data['filename'].'</span><br>
<span id="'.$db_id.'_filecount">'.__('Total of $0 backups found', [$bconut]).'</span><br>
'.('<button class="btn btn-primary btn-sm change_file_of_db" id="'.$db_id.'_selbutton" onclick="show_select(\'backup_file_'.$db_id.'\', \''.$db_id.'\')" title="'.__('Select other backup').'">Select Other Backup</button>').'
<script>
</script>
<select name="backup_file_'.$db.'" id="backup_file_'.$db_id.'" onchange="backup_file(\'backup_file_'.$db_id.'\', \''.$db_id.'\')" class="form-select form-select-sm sel_backups d-none">';
foreach($db_backups as $kk => $file){
echo '<option val="'.$file.'">'.$file.'</option>';
}
echo '
</select></td>';
echo $_COOKIE["fcookie"];
echo '
<td id="'.$db_id.'_filemethod">'.(!empty($file_data['backup_method'])?$file_data['backup_method']:'compressed').'</td>
<td id="'.$db_id.'_fileserver">'.$file_data['server_name'].'</td>
<td id="'.$db_id.'_filenote"><span id="note_span_'.preg_replace("~[.]~", "_", basename($file_data['filename'])).'">'.(!empty($file_data['backup_notes']) ? $file_data['backup_notes'] : '--').'</td>
<td id="'.$db_id.'_filesize">'.(!empty($dbinfo['size']) ? trim(round(($dbinfo['size']/1024)/1024, 2).' M') : "--").'</td>
<td onclick="restore_backup(this.parentNode.id);" data-bsid="'.$file_data['backup_server_id'].'" id="restore_'.$db_id.'" data-bmethod="'.$file_data['backup_method'].'" data-btype="'.$file_data['type'].'">
<i class="fas fa-undo restore edit-icon" title="'.__('Restore').'"></i>
</td>'
.($file_data['backup_server_id'] == -1 ? '<td>
<a href="'.$globals['index'].'act=sel_email_files&download='.$file_data['filename'].'" id="'.$db_id.'_downloadlink" title="'.__('Download').'" >
<i class="fas fa-download download edit-icon" title="'.__('Download').'"></i>
</a>
</td>' : '<td></td>');
echo '</tr>';
}
}
echo '
</tbody>
</table>
<script language="javascript" type="text/javascript">
function show_select() {
}
function backup_file() {
}else{
$("#restore_"+dbname).attr("data-bmethod", data.filedata.backup_method);
$("#restore_"+dbname).attr("data-bsid", data.filedata.backup_server_id);
$("#restore_"+dbname).attr("data-btype", data.filedata.type);
$("#"+dbname+"_downloadlink").attr("href", "'.$globals['index'].'act=sel_email_files&download="+data.filedata.filename);
},
error: function() {
$(".loading").hide();
var a = show_message_r(l.error, l.r_connect_error);
a.alert = "alert-danger";
show_message(a);
}
});
$("#"+db).addClass("d-none")
}
</script>
';
}