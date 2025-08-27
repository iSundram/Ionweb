<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function contact_manager_theme() {
}
echo'
</select>
</div>
</div>
</div>
<div class="row mb-3 cform">
<div class="col-12 col-md-5">
<label for="Receives" class="sai_head">'.__('Receives').'</label>
</div>
<div class="col-12 col-md-7">
<select class="form-select form-select-sm" name="receive" id="receive">
<option value="3">High Only</option>
<option value="2">High And Medium Only</option>
<option value="1">All</option>
<option value="0">None</option>
</select>
</div>
</div>
<div class="row mb-3 cform">
<div class="col-12 col-md-5">
<label for="destination" class="sai_head">'.__('Destinations').'</label>
</div>
<div class="col-12 col-md-7">
<input type="text" class="form-control form-control-sm" title="" id="destination" name="destination" value="'.$globals['soft_email'].'"/>
<label class="sai_exp2 mt-1 me-3" id="c_type_exp"></label>
</div>
</div>
<div class="text-center">
<input type="submit" class="btn btn-primary" id="save_communications" name="save_communications" value="Save"/>
<input type="button" class="btn btn-primary" id="test" name="test" value="Test"/>
</div>
</div>
</form>
</div>
</div>
<div class="tab-pane fade show" id="notification_setting" role="tabpanel" aria-labelledby="notes">
<div class = "row col-md-12 mt-3 mb-3">
<div class="col-12 col-md-4">
<div class="input-group input-group-sm multi-check d-none" style="width: 60%;">
<label class="input-group-text" for="multicheck">'.__('With Selected').'</label>
<select class="form-select form-select-sm" id="multicheck">
<option value="0">'.__('Disabled').'</option>
<option value="1">'.__('Low').'</option>
<option value="2">'.__('Medium').'</option>
<option value="3">'.__('High').'</option>
</select>
</div>
</div>
<div class="col-12 col-md-8">
<label class="form-label">'.__('Search Alert').' : </label>
<span class="">
<select class="form-select p-3 make-select2" s2-placeholder="Select Alert Type" style="width: 30%" id="a_search" name="a_search">
<option value="0" selected>All</option>';
foreach($cm_data['notifications'] as $key => $val){
echo '<option value="'.$val['key'].'">'.$val['label'].'</option>';
}
echo'
</select>
</span>
</div>
</div>
<div class="row mt-3">
<form accept-charset="'.$globals['charset'].'" name="save_alerts" id="save_alerts" method="post" action=""; class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
<table border="0" cellpadding="8" cellspacing="1" class="table webuzo-table td_font" id="alert_table">
<thead>
<tr>
<th width="2%"><input type="checkbox" id="checkAll"></th>
<th width="50%">'.__('Alert type').'</th>
<th width="10%" class="text-center">'.__('Importance').'</th>
<th width="10%" class="text-center">'.__('Alerts').'</th>
</tr>
</thead>
<tbody>';
foreach($cm_data['notifications'] as $key => $value){
if(empty($value['key'])){
continue;
}
echo '
<tr class="al_search" id="'.$value['key'].'">
<td>
<input type="checkbox" class="check" id="check_'.$value['key'].'" name="checked_alert" value="'.$value['key'].'">
</td>
<td class="sai_head">'.$value['label'].'<br>'.(isset($value['label_exp']) ? '<label class="sai_exp2" id="c_type_exp">'.$value['label_exp'].'</label>' : '').'</td>
<td class="me-3"  id="impselect">
<select id="'.$value['key'].'imp" name="'.$value['key'].'" data-selected="'.$value['selected'].'" data-alert_type="'.$value['key'].'" class="form-select form-select-sm a_class" onchange="alert_data(this)">';
foreach($cm_data['importance'] as $imp => $opts){
echo '
<option value="'.$imp.'" '.($value['selected'] == $imp ? 'selected' : '' ).'>'.$opts.'</option>';
}
echo '
</select>
</td>
<td style="padding-left: 45px;" id="'.$value['key'].'_imp_list"></td>
</tr>';
}
echo '
</tbody>
</table>
<div class="text-center">
<input type="submit" class="btn btn-primary" id="save_alerts" name="save_alerts" value="'.__('Save Alerts').'"/>
</div>
</form>
</div>
</div>
</div>
</div>
<script>
var cm_data = '.json_encode($cm_data).';
var c_icons = '.json_encode($c_icons).';
$(document).ready(function(){
$(".multi-check").addClass("d-none");
$("#checkAll").change(function () {
$(".check").prop("checked", $(this).prop("checked"));
$(".multi-check").removeClass("d-none");
});
$("input:checkbox").change(function() {
if($(".check:checked").length){
$(".multi-check").removeClass("d-none");
}else{
$(".multi-check").addClass("d-none");
}
});
$("#multicheck").change(function(){
$(".a_class").each(function (ele, val) {
var id = $(val).data("alert_type");
console.log("#"+id);
if($("#check_"+id).is(":checked") && $("#check_"+id).is(":visible")){
$(val).val($("#multicheck").val()).change();
}
});
});
$(".a_class").each(function (ele, val) {
alert_data(val);
});
});
function alert_data() {
}else{
var i;
$(list).each(function(key, val){
var receive = cm_data["communication"][val]["receive"];
if(empty(receive)){
return true;
}
if(imp == 1 && receive == 1){
i=1;
$("#"+a_key+"_imp_list").append(c_icons[val]+"&nbsp;");
return true;
}
if(imp == 2 && (receive == 2 || receive == 1)){
i=1;
$("#"+a_key+"_imp_list").append(c_icons[val]+"&nbsp;");
return true;
}
if(imp == 3 && (receive == 2 || receive == 1 || receive == 3)){
i=1;
$("#"+a_key+"_imp_list").append(c_icons[val]+"&nbsp;");
return true;
}
})
if(empty(i)){
$("#"+a_key+"_imp_list").html("None");
}
}
}
function reload_form() {
}
$(document).ready(function(){
$(".cform").hide();
show_form();
});
function show_form() {
}
$(".logo").html(c_icons[$("#c_type").val().toLowerCase()]);
submitit(d, {
handle:function(data, p){
$(".cform").show();
$("#c_type_exp").html(data.form_data.c_type_exp);
$("#receive").val(data.form_data.receive).show();
$("#destination").val(data.form_data.destination).show();
empty(data.form_data.destination) ? $("#test").hide() : $("#test").show();
if(!empty(data.form_data.destination)){
$("#destination").attr("title", data.form_data.destination.split(",").join("\n"));
}else{
$("#destination").attr("title", "'.__js('No Destination found !').'");
}
}
})
}
$("#test").click(function(){
var d = {"type" : $("#c_type").val(), "test" : 1}
submitit(d);
})
$("#a_search").on("select2:select", function(e, u = {}){
var alert = $("#a_search option:selected").val();
$(".al_search").each(function(key, tr){
if(alert == $(this).attr("id")){
$(this).show(); return true;
}else if(empty(alert)){
$(this).show();
}else{
$(this).hide();
}
})
});
</script>';
softfooter();
}