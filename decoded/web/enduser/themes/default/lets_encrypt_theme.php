<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function lets_encrypt_theme() {
}
softheader(__('Automatic SSL'));
echo '<style>
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
.heading_a{
border-radius: 5px;
border :2px solid  #FFFFFF;
background: #FFFFFF;
padding: 8px;
font-size:18px;
color:#333333;
margin-top:20px;
margin-bottom:5px;
font-family: "Lucida Grande","Lucida Sans Unicode",Helvetica,Arial,Verdana,sans-serif;
}
.no-border-bottom{
border-bottom: 0px solid #DDD;
}
</style>';
echo '
<div class="card soft-card p-3">
<div class="sai_main_head">
<div class="row">
<div class="col-6">
<img src="'.$theme['images'].'sslcrt.png" alt="" class="webu_head_img me-2"/>
<h5 class="d-inline-block">'.__('Automatic SSL').'</h5>
</div>
<div class="col-6 ">
<input id="search_dom" type="text" class="sai_inputs float-end" autofocus placeholder="'.__('Search Domain').'" autocomplete="off">
</div>
</div>
</div>
</div>
<div class="card soft-card p-4 mt-4">
<ul class="nav nav-tabs mb-3 webuzo-tabs" id="panel-heading-part" role="tablist">
<li class="nav-item" role="presentation">
<button class="nav-link active" id="lecerts_a" data-bs-toggle="tab" data-bs-target="#lecerts" type="button" role="tab" aria-controls="lecerts" aria-selected="true">'.__('Certificates').'</button>
</li>
<li class="nav-item" role="presentation">
<button class="nav-link" id="lelog_a" data-bs-toggle="tab" data-bs-target="#lelog" type="button" role="tab" aria-controls="lelog" aria-selected="false" onclick="refresh_logs()">'.__('Logs').'</button>
</li>
</ul>
<div class="tab-content">
<div class="tab-pane fade show active" id="lecerts" role="tabpanel" aria-labelledby="lecerts_a">';
if(!empty($error['no_domain'])){
echo '
<div class="alert alert-danger " style="width:100%">
<center style="margin-top:4px; font-size:16px;">'.__('No valid domain found on your machine').'</center>
</div>';
}else{
echo '
<form accept-charset="'.$globals['charset'].'" action="" method="post" id="editformuplode" enctype="multipart/form-data" class="form-horizontal" onsubmit="return submitit(this)"  data-donereload="1">
<div class="row mb-3">
<div class="col-12 text-center">
<input type="button" class="flat-butt backup" id="sel_ins" value="'.__('Install').'" data-ispanel=0 onclick="with_selected_cert(\'install\');" disabled/>
<input type="button" class="flat-butt backup" id="sel_rev" value="'.__('Revoke').'" onclick="with_selected_cert(\'revoke\');" disabled/>
<input type="button" class="flat-butt backup" id="sel_ren" value="'.__('Renew').'" onclick="with_selected_cert(\'renew\');" disabled/>
</div>
</div>
</form>
<div class="table-responsive mb-3">
<table class="table align-middle table-nowrap mb-0 webuzo-table td_font">
<thead class="sai_head2">
<tr>
<th width="1%"><input type="checkbox" id="check_all_cer"></th>
<th>'.__('Domain').'</th>
<th width="30%">'.__('Created on').'</th>
<th width="20%">'.__('Certificate Domains').'</th>
<th width="1%">'.__('View').'</th>
<th width="1%" class="text-center" colspan="3">'.__('Actions').'</th>
</tr>
</thead>
<tbody id="search_dom_list">
<tr id="empty_list" style="display:none;">
<td colspan="8" class="text-center" >'.__('No result found !').'</td>
</tr>';
foreach($domains as $domain_name => $path){
echo '
<tr id="tr'.$domain_name.'">
<td>
<input type="checkbox" class="cer_check" name="checked_cer" id="check'.$domain_name.'" value="'.$domain_name.'">
</td>
<td>'.$domain_name.'</td>
<td>'.(empty($install_lelist[$domain_name]) ? __('NA') : $install_lelist[$domain_name]['cert_info']['created']).'</td>
<td><span style="font-size:13px">'.(!empty($install_lelist[$domain_name]['cert_info']['cert_domains']) ? implode('<br />', $install_lelist[$domain_name]['cert_info']['cert_domains']) : '').'</span></td>
<td>
<a href="javascript:open_cer(\''.$domain_name.'\')" id="cer'.$key.'" title="'.__('View').'">
<i class="fas fa-eye edit-icon"></i>
</a>
</td>
<td class="p-1">
<input type="button" class="flat-butt backup" id="'.$domain_name.'-ins" value="'.__('Install').'" data-domain="'.$domain_name.'" data-install_cert=1 data-ispanel=0 onclick="cert_action(this)" '.(empty($install_lelist[$domain_name]) ? '' : 'disabled').'/>
</td>
<td class="p-1">
<input type="button" class="flat-butt backup" data-domain="'.$domain_name.'" data-revoke_cert=1 id="'.$domain_name.'-rev" value="'.__('Revoke').'" onclick="cert_action(this)" '.(!empty($install_lelist[$domain_name]) ? '' : 'disabled').'/>
</td>
<td class="p-1">
<input type="button" class="flat-butt backup" data-renew_cert=1 data-domain="'.$domain_name.'" id="'.$domain_name.'-ren" value="'.__('Renew').'" onclick="cert_action(this)" '.(!empty($install_lelist[$domain_name]) && (strtotime($install_lelist[$domain_name]['cert_info']['next_renew']) < time()) ? '' : 'disabled').'/>
</td>
</tr>';
}
echo '
</tbody>
</table>
</div>
<div class="text-center mt-5 mb-3">
<span class="sai_notice">'.__('<b>Note:</b> If the utility fails, disable your custom webserver config or htaccess and try again.').'</span>
</div>
<div id="showrectab">';
showcert();
echo '
</div>';
}
echo '
</div>
<div class="tab-pane fade" id="lelog" role="tabpanel" aria-labelledby="lelog_a">
<div class="innertabs mb-3" nowrap="nowrap">
<textarea class="form-control" id="showlog" readonly="readonly" wrap="off">'.$le_log.'</textarea>
</div>
<div class="text-center">
<input type="button" class="flat-butt" id="get_log" value="'.__('Refresh Logs').'" onclick="get_logs(this);" />
<input type="button" class="flat-butt" id="clear_log" value="'.__('Clear Logs').'" onclick="get_logs(this);" data-clearlog=1 />
</div>
</div>
<div class="tab-pane fade" id="leconf_cert" role="tabpanel" aria-labelledby="lelog_a">';
if(empty($install_lelist)){
echo '
<div class="alert alert-danger " style="width:100%">
<center style="margin-top:4px; font-size:16px;">'.__('No domain found with Let\'s Encrypt Certificate installed on it').'</center>
</div>';
}else{
echo '
<form accept-charset="'.$globals['charset'].'" action="" method="post" id="editcertformuplode" enctype="multipart/form-data" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
<div class="row">
<div class="col-12 text-center">
<label class="form-label" for="selectledomain">'.__('Select Domain').'</label>
<select class="form-select w-75 d-inline-block" name="selectledomain" id="selectledomain">';
foreach ($install_lelist as $key => $value){
echo '<option value='.$key.'>'.$key.'</option>';
}
echo '
</select>
</div>
</div>
</form>';
}
echo '
</div>
</div>
</div>
<script language="javascript" type="text/javascript">
var search_dom_string;
$("#search_dom").on("keyup", function() {
var visible;
search_dom_string = $(this).val().toLowerCase();
$("#search_dom_list tr").filter(function() {
$(this).toggle($(this).text().toLowerCase().indexOf(search_dom_string) > -1);
if($(this).is(":visible") == true){
visible = 1;
}
});
if(empty(visible)){
$("#empty_list").show();
}else{
$("#empty_list").hide();
}
});
function handle_action_buts() {
}else{
$("#sel_ins").prop("disabled", true);
$("#sel_ren").prop("disabled", true);
$("#sel_rev").prop("disabled", true);
}
}
$("#check_all_cer").change(function () {
$(".cer_check").prop("checked", $(this).prop("checked"));
handle_action_buts();
});
$(".cer_check").change(handle_action_buts);
function cert_action() {
}
function with_selected_cert() {
}
});
var data = {"domain" : domains, "do" : action};
submitit(data,{
done_reload : window.location.href
});
}
function open_cer() {
}
});
}
try{
var select_tab = window.location;
if(select_tab.length > 0){
$(select_tab+"_a").addClass("active");
$(select_tab).addClass("in active");
}else{
$("#lecerts_a").addClass("active");
$("#lecerts").addClass("in active");
}
}catch(e){}
var refreshInterval;
$(document).ready(function(){
refreshInterval = setInterval(refresh_logs, 3000);
});
function get_logs() {
}
$("#showlog").text(data["le_log"]);
clearInterval(refreshInterval);
refreshInterval = setInterval(refresh_logs, 3000);
}else{
handleResponseData(data);
}
}
});
}
function refresh_logs() {
}
};
</script>';
softfooter();
}
function showcert() {
}
}else{
echo __('No Installed Certificates Found');
}
echo '
</tbody>
</table>
</div>
</div>
</div>
</div>';
}