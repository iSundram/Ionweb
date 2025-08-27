<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function convert_addon_theme() {
}
echo '
</select>
</div>
<div class="col-12 col-md-4">
<label for="plan" class="sai_head">'.__('Plan').'</label>
<select id="plan" class="form-select make-select2" s2-placeholder="---" s2-ajaxurl="'.$globals['index'].'act=plans&api=json" s2-query="search" s2-data-key="plans" style="width:100%">
</select>
</div>
<div class="col-12 col-md-4">
<label  class="label label-secondary-auto p-2 ml-2 px-2" style="margin: 34px 0px 0px 34px;">
<input class="align-middle" type="checkbox" id="preserve_owner" checked>&nbsp;&nbsp;&nbsp;'.__('Preserve Ownership').'
</label>
</div>
</div>
</div>
</div>
<div class="soft-smbox my-4">
<div class="sai_graph_head">
'.__('Basic Settings').'
</div>
<div class="sai_form p-3">
<div class="p-1">
<input class="me-3" type="checkbox" id="copydocroot" checked>
<label for="copydocroot">'.__('Copy Contents of Document Root Directory').'</label>
</div>
<div class="p-1">
<input class="me-3" type="checkbox" id="copysslcert" checked>
<label for="copysslcert">'.__('Copy installed SSL certificate').'</label>
</div>
</div>
</div>
<div class="soft-smbox mb-3">
<div class="sai_graph_head">
'.__('MySQL Databases').'
<button class="btn btn-primary" id="mysqlconfig">Configure</button>
</div>
<div class="sai_form p-3 fillmysql"></div>
</div>
<div class="text-center">
<input type="button" class="btn btn-primary" data-convert=1 value="Convert" onclick="convert(this)">
</div>
</div>
<div class="tab-pane fade show" id="mysql_mode" role="tabpanel" aria-labelledby="mysql_mode_t">
<div class="soft-smbox my-4">
<div class="sai_graph_head">
'.__(' How do you want to transfer your MySQL Databases? ').'
</div>
<div class="sai_form p-3">
<div class="form-check form-check-inline mb-2">
<input class="form-check-input" type="radio" name="mysqldbs" id="movedbs" value="movedbs" checked>
<label class="form-check-label" for="movedbs">'.__('Move').'</label>
</div>
<div class="form-check form-check-inline mb-2">
<input class="form-check-input" type="radio" name="mysqldbs" id="copydbs" value="copydbs" '.($domain['mysqlsettings']['action'] == 'copy' ? 'checked' : '').'>
<label class="form-check-label" for="copydbs">'.__('Copy').'</label>
</div>
<div class="alert alert-warning mysqlalert">
'.__('When you move a database, you must also move the associated database users to preserve their privileges.$0', ['<br>']).'
'.__('If you move a database user and not a database to which they have access, the user will lose all access to that database.$0', ['<br>']).'
'.__('When you move a database:').'
<ul>
<li>'.__('The addon domain user "$0" will lose access to the database.', [$domain['user']]).'</li>
<li>'.__('The newly created user will gain full access to the database.').'</li>
</ul>
</div>
</div>
</div>
<div class="mysqldatabases">
<h6>MySQL Databases</h6>
<table class="table sai_form webuzo-table mb-4">
<thead>
<tr>
<th><input type="checkbox" id="checkall_dbs"></th>
<th>'.__('Database').'</th>
<th></th>
</tr>
</thead>
<tbody>';
foreach($domain['dbs'] as $k => $v){
if(!empty($domain['mysqlsettings']['dbs'])){
$checked = array_key_exists($v, $domain['mysqlsettings']['dbs']) ? 'checked' : '';
}
if(!empty($checked) && $domain['mysqlsettings']['action'] == 'copy'){
$show = '';
}else{
$show = 'style="display:none"';
}
echo '
<tr>
<td>
<input class="dbs" type="checkbox" id="chkdbs_'.$k.'" '.$checked.'>
</td>
<td><label for="chkdbs_'.$k.'">'.$k.'</label></td>
<td>
<div class="input-group newdb mb-3" id="dbbox_'.$k.'" '.$show.'>
'.(empty($globals['disable_dbprefix']) ? '
<span class="input-group-text">
<span class="dbprefix"></span>
</span>' : '').'
<input type="text" class="form-control" id="newdb_'.$k.'" placeholder="'.__('Enter new database name').'" value="'.$domain['mysqlsettings']['dbs'][$v].'">
</div>
</td>
</tr>';
}
echo '
</tbody>
</table>
</div>
<div class="mysqlusers">
<h6>MySQL Users</h6>
<table class="table sai_form webuzo-table">
<thead>
<tr>
<th><input type="checkbox" id="checkall_dbusers"></th>
<th>'.__('Users').'</th>
<th>'.__('Databases').'</th>
</tr>
</thead>
<tbody>';
foreach($domain['dbusers'] as $k => $v){
echo '
<tr>
<td>
<input class="dbusers" type="checkbox" id="dbuser_'.$k.'" '.(in_array($v, $domain['mysqlsettings']['users']) ? 'checked' : '').'>
</td>
<td><label for="dbuser_'.$k.'">'.$k.'</label></td>
<td>';
$tmp = [];
foreach($domain['dbwithusers'] as $key => $val){
if(in_array($v, $val)){
$tmp[] = $key;
}
}
echo implode(', ', $tmp).'
</td>
</tr>';
}
echo '
</tbody>
</table>
</div>
<div class="text-center">
<input type="button" class="btn btn-primary" data-savemysql=1 value="Save" onclick="savemysql(this)">
</div>
</div>
</div>
</div>';
}
echo '
<script>
$(document).ready(function(){
fillmysql();
let mysqlaction = '.json_encode($domain['mysqlsettings']['action']).';
if(mysqlaction == "copy"){
$(".mysqlalert").hide();
$(".mysqlusers").hide();
}
});
function fillmysql() {
}else{
settings = '.json_encode($domain['mysqlsettings']).';
}
let str = "";
if(!empty(settings.dbs)){
str += `Databases to ${settings.action}:`;
if(settings.action == "move"){
for(let db in settings.dbs){
str += `<li>${db}</li>`;
}
}else{
for(let db in settings.dbs){
str += `<li>"${db}" as "<span class="dbprefix"></span>${settings.dbs[db]}"</li>`;
}
}
str += "<br>";
}
if(!empty(settings.users) && settings.action == "move"){
str += `Users to ${settings.action}:`;
settings.users.forEach(usr => {
str += `<li>${usr}</li>`;
});
}
$(".fillmysql").append(str);
$("#general_mode_t").click();
$("#user").trigger("keyup");
}
function convert() {
});
show_message(a);
}
function savemysql() {
};
if(d.movedbs){
dbboxes.forEach(checkbox => {
const db = checkbox.id.substr(7);
d.dbs[db] = db;
});
let userboxes = document.querySelectorAll(`input[type="checkbox"][id^="dbuser_"]:checked`);
d.users = [];
userboxes.forEach(checkbox => {
d.users.push(checkbox.id.substr(7));
});
}else{
dbboxes.forEach(checkbox => {
const db = checkbox.id.substr(7);
const newdb = $("#newdb_"+db).val();
d.dbs[db] = newdb;
});
}
AJAX({
url: window.location.toString()+"&api=json",
data: d,
type: "POST"
}, function(data){
if(data.error){
let em = show_message_r("'.__js('Error').'", data.error);
em.alert = "alert-danger";
show_message(em);
}else{
fillmysql(d);
}
});
}
$("#mysqlconfig").click(function(){
$("#mysql_mode_t").click();
});
$("#checkall_dbs").change(function(){
$(".dbs").prop("checked", $(this).prop("checked"));
let chk = this.checked;
if($("#copydbs:checked").val()){
if(chk){
$(".newdb").show();
}else{
$(".newdb").hide();
}
}
});
$("#checkall_dbusers").change(function(){
$(".dbusers").prop("checked", $(this).prop("checked"));
});
$(`input[type="radio"][name="mysqldbs"]`).change(function(){
if($(this).attr("id") === "copydbs"){
$(".mysqlalert").hide();
$(".mysqlusers").hide();
let dbboxes = document.querySelectorAll(`input[type="checkbox"][id^="chkdbs_"]:checked`);
dbboxes.forEach(checkbox => {
const db = checkbox.id.substr(7);
$("#dbbox_"+db).show();
});
}else if($(this).attr("id") === "movedbs"){
$(".mysqlalert").show();
$(".mysqlusers").show();
$(".newdb").hide();
}
});
$(".dbs").change(function(){
if($("#copydbs:checked").val()){
let id = $(this).attr("id");
id = id.substr(7);
if(this.checked){
$("#dbbox_"+id).show();
}else{
$("#dbbox_"+id).hide();
}
}
});
$("#user").on("keyup", function(){
if(!'.json_encode($globals['disable_dbprefix']).'){
let userinput = $(this).val();
$(".dbprefix").text(userinput + "_");
}
});
$("#user").on("input", function(){
let str = $(this).val().toLowerCase();
$(this).val(str.toLowerCase());
});
$("#dom").on("select2:select", function(){
window.location = "'.$globals['index'].'act=convert_addon&dom="+$(this).val();
});
</script>';
softfooter();
}