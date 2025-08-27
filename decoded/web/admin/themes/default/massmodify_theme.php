<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function massmodify_theme() {
}else{
foreach($users as $key => $value){
echo '
<tr class="table_row">
<td>
<input type="checkbox" class="check_current" value="'.$value['user'].'" name="check_list[]" />
</td>
<td>
<span class="search_by_domain">'.$value['domain'].'</span>
</td>
<td>
<span class="search_by_user">'.$value['user'].'</span>
</td>
<td>
<span class="search_by_owner">'.$value['owner'].'</span>
</td>
<td>
<span class="search_by_package">'.(!empty($value['plan']) ? $value['plan'] : '---').'</span>
</td>
<td>
<span class="search_by_ipv4">'.(!empty($value['ips'][4][0]) ? $value['ips'][4][0] : '---').'</span>
</td>
<td>
<span class="search_by_ipv6">'.(!empty($value['ips'][6][0]) ? $value['ips'][6][0] : '---').'</span>
</td>
<td>
<span>'.date('m/d/Y H:i:s', $value['created']).'</span>
</td>
</tr>';
}
}
echo '</tbody>
</table>
</div>';
echo '
<center>
<button type="button" class="btn btn-primary" id="massmodify_modal_btn" data-bs-toggle="modal" data-bs-target="#massmodify_modal">
'.__('With Selected').'
</button>
</center>
<!-- Massmodify Setting Modal -->
<div class="modal fade" id="massmodify_modal" tabindex="-1" aria-labelledby="massmodifyLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<h6 class="modal-title" id="bl_import_label">'.__('Settings').'</h6>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
<div class="sai_form">
<div class="row">
<div class="col-sm-5">
<label for="reseller" class="sai_head">'.__('Owner').'</label>
</div>
<div class="col-sm-7">
<select class="form-select" name="owner" id="owner" >
<option value="">Choose a Owner</option>';
foreach($owners as $owner => $ownerval){
echo '<option value="'.$owner.'">'.$owner.'</option>';
}
echo '</select>
</div>
</div><br />
<div class="row">
<div class="col-sm-5">
<label for="start_date" class="sai_head">'.__('Start Date').'</label>
</div>
<div class="col-sm-7">
<input class="form-control" type="datetime-local" id="start_date" name="start_date">
</div>
</div><br />
<div class="row">
<div class="col-sm-5">
<label for="theme" class="sai_head">'.__('Webuzo Theme').'</label>
</div>
<div class="col-sm-7">
<select class="form-select" name="theme" id="theme" >
<option value="">Choose a Theme</option>';
foreach($plan_theme as $k => $v){
echo '<option value="'.$k.'" >'.$v.'</option>';
}
echo '</select>
</div>
</div><br />
<div class="row">
<div class="col-sm-5">
<label for="locale" class="sai_head">'.__('Locale').'</label>
</div>
<div class="col-sm-7">
<select class="form-select" name="locale" id="locale" >
<option value="">Choose a Locale</option>';
foreach($language_options as $k => $v){
echo '<option value="'.$k.'" >'.$v.'</option>';
}
echo '</select>
</div>
</div><br />
<div class="row">
<div class="col-sm-5">
<label for="package" class="sai_head">'.__('Plan').'</label>
</div>
<div class="col-sm-7">
<select class="form-select" name="plan" id="plan" >
<option value="">Choose a Plan</option>';
foreach($plans as $k => $v){
echo '<option value="'.$k.'">'.$v['plan_name'].'</option>';
}
echo '</select>
</div>
</div><br />
<div class="row">
<div class="col-sm-5">
<label for="ips" class="sai_head">'.__('Account IP').'</label>
</div>
<div class="col-sm-7">
<div class="row">
<div class="col-sm-6">
<label class="sai_head">'.__('IPv4').'</label>
<select name="ip" id="ip" class="form-select">
<option value="">'.__('Default').'</option>';
if(!empty($ips) && is_array($ips)){
foreach ($ips as $k => $v){
if($v['type'] != 4){
continue;
}
echo '<option value="'.$k.'">'.$k.'</option>';
}
}
echo '
</select>
</div>
<div class="col-sm-6">
<label class="sai_head">'.__('IPv6').'</label>
<select name="ipv6" id="ipv6" class="form-select">
<option value="">'.__('Default').'</option>';
if(!empty($ips) && is_array($ips)){
foreach ($ips as $k => $v){
if($v['type'] != 6){
continue;
}
echo '<option value="'.$k.'">'.$k.'</option>';
}
}
echo '
</select>
</div>
<div class="col-md-12 mt-3">Note: Only <b>Shared</b> IP\'s will be listed.</div>
</div>
</div>
</div>
<br />
<div class="text-center my-3">'.csrf_display().'
<input type="submit" class="btn btn-primary" id="modify_account" name="modify_account" value="'.__('Save Changes').'"/>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Massmodify Setting Modal End-->
</form>
</div>';
echo '
<script language="javascript" type="text/javascript">
function apply_filter() {
}else{
$(this).closest(".table_row").find(".check_current").prop(\'checked\', false);
}
}
})
}
});
if ($(".check_current").filter(":checked").length < 1){
$("#massmodify_modal_btn").attr("disabled" , true);
}else{
$("#massmodify_modal_btn").attr("disabled" , false);
}
}
$(document).ready(function(){
$("#massmodify_modal_btn").attr("disabled" , true);
$(".check_current").on("click, change", function(event){
if ($(".check_current").filter(":checked").length < 1){
$("#massmodify_modal_btn").attr("disabled" , true);
}else{
$("#massmodify_modal_btn").attr("disabled" , false);
}
});
});
</script>';
softfooter();
}