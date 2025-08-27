<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function apikey_theme() {
}
echo '</div>
</div>
<center>
<input type="submit" class="btn btn-primary text-decoration-none" name="save" id="api_key_add" value="'.__('Save Key').'" onclick="return submitit(this)" data-donereload="1" />
</center>
</div>
</form>
</div>
</div>
</div>
</div>
<div id="the_list" class="table-responsive">
<div class="col-6">
<input type="button" class="btn btn-danger mb-3" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_apikeys(this)" style="float: left;" disabled>
</div>
<table class="table sai_form webuzo-table" id="the_list_table">
<thead>
<tr>
<th class="align-middle"><input type="checkbox" id="checkAll"></th>
<th>'.__('User').'</th>
<th>'.__('API Key').'</th>
<th width="25%">'.__('IP Address').'</th>
<th width="20%">'.__('Acts').'</th>
<th width="20%">'.__('Notes').'</th>
<th width="15%">'.__('Created').'</th>
<th colspan="2" width="1%">'.__('Options').'</th>
</tr>
</thead>
<tbody>';
if(empty($apikeys)){
echo '
<tr class="text-center">
<td colspan="9">
<span>'.__('There are no API Keys !').'</span>
<a class="btn text-decoration-none"  onclick="open_add_api_modal()">'.__('Please create one').'</a>
</td>
</<tr>';
}else{
foreach ($apikeys as $key => $v){
echo '
<tr id="tr'.$key.'">
<td>
<input type="checkbox" name="api_checkbox" class="api_checkbox" value="'.$key.'">
</td>
<td>'.$SESS['user'].'</td>
<td>'.$key.'</td>
<td>'.(empty($v['ips']) ? __('All IP Addresses') : implode(', ', $v['ips'])).'</td>
<td>';
if(empty($v['acts'])){
echo 'All';
}else{
$tmp = [];
foreach($v['acts'] as $kk => $vv){
$tmp[$vv] = $_admin_menu[$vv];
}
echo implode(', ', $tmp);
}
echo '</td>
<td>'.(empty($v['notes']) ? 'NA' : $v['notes']).'</td>
<td>'.datify($v['created']).'</td>
<td width="1%" class="text-center">
<a href="javascript:open_add_api_modal(\''.$key.'\', \''.implode(', ', (array)$v['ips']).'\', \''.$v['notes'].'\', JSON.parse(\''.str_replace('"', '&quot;', json_encode($v['acts'])).'\'))" id="edit'.$key.'" title="'.__('Edit').'">
<i class="fas fa-pencil-alt edit-icon"></i>
</a>
</td>
<td width="1%" class="text-center">
<i class="fas fa-trash delete-icon" data-del="'.$key.'" title="Delete" id="did'.$key.'" onclick="delete_record(this)""></i>
</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>
</form>
</div>';
echo '
<script language="javascript" type="text/javascript">
$(document).ready(function(){
$("#checkAll").change(function () {
$(".api_checkbox").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function() {
if($(".api_checkbox:checked").length){
$("#delete_selected").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled", true);
}
});
});
$("#allow_all").click(function(){
$("#acts_list").toggle();
});
function del_apikeys() {
});
jEle.data("del", arr.join());
var lang = confirmbox;
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
var d = jEle.data();
submitit(d, {
handle:function(data){
if(data.done){
var d = show_message_r("'.__js('Done').'", data.done.msg);
d.alert = "alert-success";
d.ok.push(function(){
location.reload(true);
});
show_message(d);
}
}
});
});
show_message(a);
}
function open_add_api_modal() {
}else{
$("#allow_all").prop("checked", true);
$("#acts_list").hide();
}
$("#api_key_ip").val(ips);
$("#api_key_notes").val(notes);
$("#api_key").val(key);
$(".api-acts input").each(function(){
if(in_array($(this).attr("act"), acts)){
$(this).prop("checked", true);
}else{
$(this).prop("checked", false);
}
});
}
function add_key() {
});
}
var data = {"do" : 1, "ip" : ip, "notes" : notes, "key" : key};
submitit(data, {
done_reload:window.location
});
}
</script>';
softfooter();
}