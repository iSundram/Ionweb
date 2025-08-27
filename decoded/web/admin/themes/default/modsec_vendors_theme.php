<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function modsec_vendors_theme() {
}
}
echo '
<tr>
<td>'.$val['name'].'</td>';
if(!empty($val['webuzo_provided'])){
echo '
<td class="text-center"><span class="text-center"><img src="'.$theme['images'].'webuzo_icon_64.png" title="Webuzo" width="27"></span></td>';
}else{
echo '
<td class="text-center"><span class="text-center">'.__('Third Party').'</span></td>';
}
if(empty($val['installed'])){
echo '
<td colspan="3" class="text-center">
<span>'.__('There is no vendor installed.').'</span>
</td>
<td class="text-center">
<a href="javascript:void(0)" data-install_vendor="1" data-vendorname="'.$val['name'].'" onclick="return addVendor(this)">'.__('Install').'
</a>
</td>';
}else{
echo '
<td class="text-center">
<label class="switch">
<input type="checkbox" class="checkbox" data-vendorstatus="1" data-enabled="'.(!empty($val['enabled']) ? 0 : 1).'" '.(!empty($val['enabled']) ? 'checked' : '').' data-vendor="'.$key.'" data-vendorname="'.$val['name'].'" onclick="return enableVendor(this)" /><span class="slider"></span>
</label>
</td>
<td class="text-center">
<label class="switch">
<input type="checkbox" class="checkbox" data-vendorupdate="1" data-updates="'.(!empty($val['updates']) ? 0 : 1).'" '.(!empty($val['updates']) ? 'checked' : '').' data-vendor="'.$key.'" data-vendorname="'.$val['name'].'" onclick="return updateVendor(this)" '.(!empty($val['enabled']) ? '' : 'disabled').'/><span class="slider"></span>
</label>
</td>';
if(empty($val['in_use'])){
echo '<td class="text-center" style="vertical-align: middle;">No rule sets</td>';
}else{
echo '<td class="text-center" style="vertical-align: middle;">'.$val['in_use'].'/'.$val['sets'].'</td>';
}
echo '
<td class="text-center">
<a href="'.$globals['admin_url'].'act=modsec_vendors&editvendor='.$key.'">
<i class="fas fa-pencil-alt edit-icon" title="'.__('Edit').'"></i>
</a>
</td>
<td class="text-center">
<i class="fas fa-trash delete-icon" id="del_'.$key.'" data-donereload=1  data-delete_vendor="'.$key.'" class="" onclick="delete_record(this)" title="'.__('Delete').'"></i>
</td>';
}
echo '
</tr>';
}
echo '
</tbody>
</table>
</div>';
page_links();
echo '
</form>';
}else{
$vendor = optGET('editvendor');
echo '
<div id="manage_config">
<div class="col-12 col-md-12">
<div class="row d-flex align-items-center mb-2">
<div class="col-12 col-sm-4 col-lg-3">
<label for="vendor_name" class="sai_head">'.__('Vendor Name').'</label>
</div>
<div class="col-12 col-sm-4 col-lg-5">
<input type="text" readonly id="vendor_name" class="form-control" value="'.$vendor_info[$vendor]['name'].'"/>
</div>
<div class="col-12 col-sm-8 col-lg-4">
<button type="button" class="btn btn-info float-end expander">'.__('Show Additional Vendor Information').'</button>
</div>
</div>
<div id="vendorinfo" style="display:none;">
<div class="row d-flex align-items-center">
<div class="col-12 col-sm-4 col-lg-3">
<label for="vendor_desc" class="sai_head">'.__('Vendor Description').'</label>
</div>
<div class="col-12 col-sm-4 col-lg-5">
<input type="text" readonly id="vendor_desc" class="form-control mb-2" value="'.$vendor_info[$vendor]['description'].'"/>
</div>
</div>
<div class="row d-flex align-items-center">
<div class="col-12 col-sm-4 col-lg-3">
<label for="vendor_url" class="sai_head">'.__('Vendor Documentation URL').'</label>
</div>
<div class="col-12 col-sm-4 col-lg-5">
<input type="text" readonly id="vendor_url" class="form-control mb-2" value="'.$vendor_info[$vendor]['vendor_url'].'"/>
</div>
</div>
<div class="row d-flex align-items-center">
<div class="col-12 col-sm-4 col-lg-3">
<label for="vendor_report_url" class="sai_head">'.__('Vendor Report URL').'</label>
</div>
<div class="col-12 col-sm-4 col-lg-5">
<input type="text" readonly id="report_url" class="form-control mb-2" value="'.$vendor_info[$vendor]['report_url'].'"/>
</div>
</div>
<div class="row d-flex align-items-center">
<div class="col-12 col-sm-4 col-lg-3">
<label for="vendor_path" class="sai_head">'.__('Path').'</label>
</div>
<div class="col-12 col-sm-4 col-lg-5">
<input type="text" readonly id="vendor_path" class="form-control mb-2" value="'.$vendor_info[$vendor]['vendor_path'].'"/>
</div>
</div>
</div>
</div><br>
<div>
<button type="button" class="btn btn-primary float-end" data-vendor="'.$vendor.'" data-status="3" data-modconfig=1 data-donereload=1 onclick="return toggleModConfig(this)">'.__('Disable All').'</button>
<button type="button" class="btn btn-primary float-end me-3" data-vendor="'.$vendor.'"  data-status="2" data-modconfig=1 data-donereload=1 onclick="return toggleModConfig(this)">'.__('Enable All').'</button>
<a class="btn btn-primary text-decoration-none" href="'.$globals['admin'].'?act=modsec_vendors">'.__('Vendor Manager').'</a>
</div><br>
<table class="table sai_form webuzo-table" id="">
<thead>
<tr>
<th width="25%">'.__('Config File').'</th>
<th class="text-center" width="5%">'.__('Status').'</th>
</tr>
</thead>
<tbody>';
foreach($vendor_info as $key => $value){
if(optGET('editvendor') !== $key){
continue;
}
foreach($value['config'] as $k => $val){
echo '
<tr>
<td>'.$val['name'].'</td>
<td class="text-center">
<label class="switch">
<input type="checkbox" class="checkbox" data-status="'.(!empty($val['active']) ? 0 : 1).'"  data-modconfig="1" data-vendor="'.$key.'" data-configfile="'.$k.'" onclick="return toggleModConfig(this)" '.(!empty($val['active']) ? 'checked' : '').'/><span class="slider"></span>
</label>
</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>';
}
echo '
</div>
<script>
function addVendor() {
});
}
function enableVendor() {
}else{
lan = "'.__js('Do you want to $0 $status $1 the vendor $0', ['status' => 'enable', '<b>', '</b>']).' "+vendor;
}
a = show_message_r("'.__js('Warning').'", lan);
a.alert = "alert-warning";
a.confirm.push(function(){
submitit(d, {
done_reload : window.location.href
});
});
a.no.push(function(){
let status = d.enabled ? true : false;
$(jEle).prop("checked", status);
});
a.onclose.push(function(){
let status = d.enabled ? false : true;
$(jEle).prop("checked", status);
});
show_message(a);
}
function updateVendor() {
}else{
lan = "'.__js('Do you want to $0 $status $1 the vendor updates', ['status' => 'enable', '<b>', '</b>']).'";
}
a = show_message_r("'.__js('Warning').'", lan);
a.alert = "alert-warning";
a.confirm.push(function(){
submitit(d, {
done_reload : window.location.href
});
});
a.no.push(function(){
let status = d.updates ? true : false;
$(jEle).prop("checked", status);
});
a.onclose.push(function(){
let status = d.updates ? false : true;
$(jEle).prop("checked", status);
});
show_message(a);
}
function toggleModConfig() {
});
}
$(document).ready(function(){
$(".expander").on("click", function () {
$("#vendorinfo").toggle();
if($("#vendorinfo").is(":visible")){
$(".expander").text("'.__js('Hide Additional Vendor Information').'");
}else{
$(".expander").text("'.__js('Show Additional Vendor Information').'");
}
});
});
</script>';
softfooter();
}