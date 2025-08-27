<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function add_user_theme() {
}else{
$plan = $_user;
}
get_form($plan, 1);
return;
}
softheader(__('Add/Edit User'));
form_js();
echo '
<div class="col-12">
<form accept-charset="'.$globals['charset'].'" name="adduserform" id="adduserform" method="post" action=""; class="form-horizontal" onsubmit="return submitit(this)"  data-doneredirect="'.$globals['admin_index'].'act=users">
<div class="soft-smbox">
<div class="sai_main_head  p-3">
<i class="fas fa-user fa-lg me-2"></i>'.(!empty($_user) ? __('Edit user') : __('Add New user')).'
</div>
</div>';
error_handle($error);
if(!empty($SESS['is_reseller'])){
echo '
<div class="alert alert-warning col-sm my-3">
<h5>'.__('Accounts Limits').'</h5>
<table class="table">
<tbody>
<tr align="center">
<th rowspan="2" width="2%">'.__('Limits').'</th>
<th colspan="3" width="2%">'.__('Usage').'</th>
<th rowspan="2" width="6%">'.__('Information').'</th>
</tr>
<tr  align="center">
<th >'.__('Limits').'</th>
<th  >'.__('Used').'</th>
<th >'.__('Allocated').'</th>
</tr>
<tr>
<td rowspan="2">'.__('Number of accounts').'</td>
<td align="center">'.$resource_total['users']['limit'].'</td>
<td align="center">'.$resource_total['users']['used'].'</td>
<td align="center">'.$resource_total['users']['allocated'].'</td>
<td rowspan="2">'.__('Maximum number of accounts you can create.').'</td>
</tr>
<tr>
<td  colspan="3">
<div class="progress disk-bar " style="height: 10px;">
<div style="cursor:pointer;width:'.$resource_total['users']['percent'].'%;" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress-bar '.($resource_total['users']['percent'] >= 90 ? "bg-danger" : ($resource_total['users']['percent'] >= 80 ? "bg-warning" : "prog-blue")).' progress-bar-striped progress-bar-animated" data-placement="right" data-toggle="tooltip">
</div>
</div>
</td>
</tr>
<tr>
<td rowspan="2">'.__('Disk Space Quota (MB)').'</td>
<td align="center">'.$resource_total['disk']['limit'].' '.__('MB').'</td>
<td align="center">'.$resource_total['disk']['used'].' '.__('MB').'</td>
<td align="center">'.$resource_total['disk']['allocated'].' '.__('MB').'</td>
<td rowspan="2">'.__('If total used disk space quota of reseller users is exceeding to set privillege of disk space quota for the reseller then reseller and reseller users will suspened.').'</td>
</tr>
<tr >
<td  colspan="3">
<div class="progress disk-bar " style="height: 10px;">
<div style="cursor:pointer;width:'.$resource_total['disk']['percent'].'%;" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress-bar '.($resource_total['disk']['percent'] >= 90 ? "bg-danger" : ($resource_total['disk']['percent'] >= 80 ? "bg-warning" : "prog-blue")).' progress-bar-striped progress-bar-animated" data-placement="right" data-toggle="tooltip" >
</div>
</div>
</td>
</tr>
<tr>
<td rowspan="2">'.__('Monthly Bandwidth Limit (MB)').'</td>
<td align="center">'.$resource_total['bandwidth']['limit'].' '.__('MB').'</td>
<td align="center">'.$resource_total['bandwidth']['used'].' '.__('MB').'</td>
<td align="center">'.$resource_total['bandwidth']['allocated'].' '.__('MB').'</td>
<td rowspan="2">'.__('If total used Bandwidth of reseller users is exceeding to set privillege of Bandwidth for the reseller then reseller and reseller users will be suspened.').'</td>
</tr>
<tr>
<td colspan="3">
<div class="progress disk-bar"  style="height: 10px;">
<div style="cursor:pointer;width:'.$resource_total['bandwidth']['percent'].'%;" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress-bar '.($resource_total['bandwidth']['percent'] >= 90 ? "bg-danger" : ($resource_total['bandwidth']['percent'] >= 80 ? "bg-warning" : "prog-blue")).' progress-bar-striped progress-bar-animated" data-placement="right" data-toggle="tooltip">
</div>
</div
</td>
</tr>
</tbody>
</table>
</div>';
}
echo '
<div class="soft-smbox mb-3 mt-4">
<div class="sai_form_head">'.__('Domain Information').'</div>
<div class="sai_form p-3">
<div class="row">
<div class="col-12 col-md-4">
<label for="user_name" class="sai_head">'.__('Username').'</label>
<input type="text" required name="user" id="user_name" class="form-control mb-2" value="'.POSTval('user', $_user['user']).'" onchange="javascript:this.value=this.value.toLowerCase();" autocomplete="new-password" style="border-radius"/>
</div>
<div class="col-12 col-md-4">
<label for="domain" class="sai_head">'.__('Domain').'</label>
<input required type="text" name="domain" id="pri_domain" class="form-control mb-2" value="'.POSTval('domain', $_user['domain']).'" />
</div>
<div class="col-12 col-md-4">
<label for="user_email" class="sai_head">'.__('Email').'</label>
<input type="email" required name="email" id="user_email" class="form-control mb-2" value="'.POSTval('email', $_user['email']).'" />
</div>
</div>';
if(empty($_user) || !(!empty($SESS['is_reseller']) && !empty(get_reseller_privs('disable_change_pass')) )){
echo'	<div class="row mt-2">
<div class="col-12 col-md-6">
<label for="user_passwd" class="sai_head">'.__('Password').'
<span class="sai_exp">'.__('Password strength must be greater than or equal to ($0)', [pass_score_val('user')]).'</span>
</label>
<div class="input-group password-field">
<input type="password" name="user_passwd" id="user_passwd" class="form-control" onkeyup="check_pass_strength($(\'#user_passwd\'), $(\'#pass-prog-bar\'))" value="" autocomplete="new-password"/>
<span class="input-group-text" onclick="change_image(this, \'user_passwd\')">
<i class="fas fa-eye"></i>
</span>
<a href="javascript: void(0);" onclick="rand_val=randstr(10, '.pass_score_val('user').');$_(\'user_passwd\').value=rand_val;$_(\'cnf_user_passwd\').value=rand_val;check_pass_strength($(\'#user_passwd\'), $(\'#pass-prog-bar\'));return false;" title="'.__('Generate a Random Password').'" class="random-pass">
<i class="fas fa-key fa-lg"></i>
</a>
</div>
</div>
<div class="col-12 col-md-6">
<label for="cnf_user_passwd" class="sai_head">'.__('Re-type Password').'</label>
<input type="password" name="cnf_user_passwd" id="cnf_user_passwd" class="form-control mb-2" value="" style="width:100%"/>
</div>
</div>
<div class="row">
<div class="col-12 col-md-6">
<div class="progress pass-progress mb-2">
<div class="progress-bar bg-danger" id="pass-prog-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
<span>0</span>
</div>
</div>
</div>
</div>';
}
echo' </div>
</div>
<div class="soft-smbox mb-3">
<div class="sai_form_head">'.__('Plan').'</div>
<div class="sai_form">
<div class="row mb-3">
<div class="col-12 col-md-6">
<label for="plan" class="sai_head">'.__('Choose a Plan').'</label>
<select class="form-select" name="plan" id="plan" >
<option value="">---</option>';
foreach($plans as $k => $v){
echo '<option value="'.$k.'" '.POSTselect('plan', $k, $_user['plan'] == $k).'>'.$v['plan_name'].'</option>';
}
echo '
</select>
</div>
<div class="col-12 col-md-6">
<label  class="label label-secondary-auto p-2 ml-2 px-2" style="margin: 30px 0px 0px 35px;">
<input class="align-middle" type="checkbox" id="pln_select_manually" name="pln_select_manually" '.POSTchecked('pln_select_manually', 1).' />&nbsp;&nbsp;&nbsp;'.__('Select Option Manually').'
</label>
</div>
</div>
</div>
</div>
<div id="plan_form" style="display:none">';
$tmp = $_user['p'];
get_form($tmp);
echo '
</div>
<div class="soft-smbox mb-5">
<div class="sai_form_head">'.__('Advanced Options').'</div>
<div class="sai_form"><br />';
if(empty($SESS['is_reseller'])){
if(!empty($_user)){
echo '
<div class="row">
<div class="col-sm-5">
<label class="sai_head">'.__('Home Directory').'</label>
</div>
<div class="col-sm-7">'.$_user['homedir'].'</div>
</div><br />';
}
echo '
<div class="row">
<div class="col-12 col-md-4">
<label class="sai_head" for="owner">'.__('Select Owner').'</label>
<select name="owner" id="owner" class="form-select">';
if(empty($_user) || !(!empty($_user) && !empty($_user['reseller'])) || !empty($_user['reseller'])){
foreach($owners as $owner => $ownerval){
echo '<option value="'.$owner.'" '.($owner == $_user['owner'] ? 'selected' : '').'>'.$owner.'</option>';
}
}else{
echo '<option value="root"> root </option>';
}
echo '
<option value="" style="display:none" self=1>[Self]</option>
</select>
</div>
<div class="col-12 col-md-4">
<label  class="label label-secondary-auto p-2 ml-2 px-2" style="margin: 34px 0px 0px 34px;">
<input class="align-middle" type="checkbox" id="demo" name="demo" '.POSTchecked('demo', (!empty(optGET('demo')) ? 1 : $_user['demo'])).' />&nbsp;&nbsp;&nbsp;'.__('Demo Account').'
</label>
</div>
</div><br>';
}
if($globals['cgroup_version'] == 'cgroup2fs'){
echo '
<div class="row">
<div class="col-sm-6">
<label class="sai_head" for="resource_limit">'.__('Resource Limits Plan').'</label>
<select name="resource_limit" id="resource_limit" class="form-select">
<option value="">'.__('[No Plan]').'</option>';
foreach($resource_limits as $k => $v){
echo '<option value="'.$k.'" '.($k == $_user['resource_limit'] ? 'selected' : '').'>'.$k.'</option>';
}
echo '
</select>
</div>
</div><br>';
}
echo '
<div class="row" >
<div class="col-12 col-md-4" id="ip_row">
<label class="sai_head">'.__('IPv4').'</label>
<select name="ip" id="ip" class="form-select">
<option value="">'.__('Default').'</option>';
foreach ($ips as $k => $v){
if($v['type'] != 4){
continue;
}
if(!empty($v['users'])){
$v['users'] = explode(',', $v['users']);
}
echo '<option value="'.$k.'" '.POSTselect('ip', $k, (!empty($_user['user']) && in_array($_user['user'], $v['users']))).' type="'.$v['type'].'" shared="'.$v['shared'].'">'.$k.' ('.(!empty($v['shared']) ? 'Shared' : 'Dedicated').')</option>';
}
echo '
</select>
</div>
<div class="col-12 col-md-4" id="ipv6_row"">
<label class="sai_head">'.__('IPv6').'</label>
<select name="ipv6" id="ipv6" class="form-select">
<option value="">'.__('Default').'</option>';
foreach ($ips as $k => $v){
if($v['type'] != 6){
continue;
}
if(!empty($v['users'])){
$v['users'] = explode(',', $v['users']);
}
echo '<option value="'.$k.'" '.POSTselect('ipv6', $k, (!empty($_user['user']) && in_array($_user['user'], $v['users']))).' type="'.$v['type'].'" shared="'.$v['shared'].'">'.$k.' ('.(!empty($v['shared']) ? 'Shared' : 'Dedicated').')</option>';
}
echo '
</select>
</div>';
if(empty($_user)){
echo '
<div class="col-12 col-md-4">
<label  class="label label-secondary-auto p-2 ml-2 px-2" style="margin: 34px 0px 0px 34px;">
<input class="align-middle" type="checkbox" id="skip_default_mail" name="skip_default_mail" '.POSTchecked('skip_default_mail', (!empty(optGET('skip_default_mail')) ? 1 : $_user['skip_default_mail'])).' />&nbsp;&nbsp;&nbsp;'.__('Do not create default email account').'
</label>
</div>';
}
echo '
</div>
<br />
</div>
</div>
<div class="text-center" >
<input type="submit" class="btn btn-primary" id="create_user" name="create_user" value="'.__('Save User').'"/>
</div>
</form>
</div>';
echo '
<script language="javascript" type="text/javascript">
$("#owner").change(function(){
var owner = $(this).val();
$.post("'.$globals['index'].'act=add_user&api=json&get_delegated_ips="+owner, function(data){
var text_v4 = text_v6 = `<option value="">'.__('Default').'</option>`;
var ips = data.delegated_ips;
for(x in ips){
if(ips[x]["shared"]){
var iptype = "Shared";
}else{
var iptype = "Dedicated";
}
if(ips[x]["type"] == "4"){
text_v4 += "<option value="+x+">"+x+" ("+iptype+")</option>";
}else{
text_v6 += "<option value="+x+">"+x+" ("+iptype+")</option>";
}
}
$("#ip").html(text_v4);
$("#ipv6").html(text_v6);
});
});
function ip_handler() {
}else{
if($(ipfield+" > option").length < 2){
$(ipfield+"_row").hide();
}
}
$(ipfield+" > option").each(function(){
var shared = $(this).attr("shared");
var show_ip = 1;
if(shared){
if(shared == 1 && dedicated_selected == 1){
show_ip = 0;
}
if(shared == 0 && dedicated_selected == 0){
show_ip = 0;
}
}
if($(ipfield).val() == $(this).val()){
show_ip = 1;
}
if(show_ip == 0){
$(this).attr("disabled", "disabled");
}else{
$(this).removeAttr("disabled");
}
});
});
$(id).change();
}
$(document).ready(function (){
$("#pln_select_manually").change(function(){
if($(this).is(":checked")) {
$("#plan_form").show();
} else {
$("#plan_form").hide();
}
});
ip_handler("#dedicated_ip", "#ip");
ip_handler("#dedicated_ipv6", "#ipv6");
$("#pln_select_manually").trigger("change");
$("#plan").change(load_features_form);
shift_use_default_div();
$("#feature_sets").change(function(){
if(!empty($(this).val())) {
$("[key=features]").hide();
} else {
$("[key=features]").show();
}
});
$("#feature_sets").change();
$("form[name=adduserform] [name=user]").on("keyup", handleResellerOwner);
$("form[name=adduserform] [name=reseller]").on("change", handleResellerOwner);
$("form[name=adduserform] [name=reseller]").on("change", handleResellerField);
});
function handleResellerField() {
}
}
}
function handleResellerOwner() {
}
ele.val($("form[name=adduserform] [name=user]").val());
ele.show();
}
function load_features_form() {
}
onload_unlimited();
if(plan.length > 0){
$("#pln_select_manually").prop("checked", false);
}else{
$("#pln_select_manually").prop("checked", true);
}
$("#pln_select_manually").trigger("change");
ip_handler("#dedicated_ip", "#ip");
ip_handler("#dedicated_ipv6", "#ipv6");
handleResellerField();
},
error: function(error){
}
});
}
function shift_use_default_div() {
}
var key = $(this).attr("cb");
$(this).detach();
var par = $("#plan_form [key=\""+key+"\"]").find(".values");
par.prepend($(this));
handle_plan_default($(this).find("[type=checkbox]")[0]);
});
}
function handle_plan_default() {
}
if(val){
par.children().hide();
}else{
par.children().show();
}
if($("#plan").val().length > 0){
$(ele).parent().show();
}else{
$(ele).parent().hide();
}
}
</script>';
softfooter();
}
function get_form() {
}
foreach ($plan_fields as $cat => $c) {
echo '
<div class="soft-smbox mb-3">
<div class="sai_form_head">'.$c['name'].'</div>
<div class=" sai_form">
<div class=" row">';
foreach ($c['list'] as $key => $props) {
$cb_name = 'plan_default_'.$key;
echo '
<div class="plan_default_cb" cb="'.$key.'" style="display:none">
<input type="checkbox" name="'.$cb_name.'" '.POSTchecked($cb_name, (empty($val[$key]) || !empty($isplan)) ).' onclick="handle_plan_default(this)" /> '.__('Use Plan Default').'
</div>';
echo call_user_func_array('form_type_'.$props['type'], array($key, $props, &$val[$key]));
}
echo '
</div>
</div>
</div>';
}
}