<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function adminindex_theme() {
}
$panel_order_array = json_encode($softpanel->user_prefs['admin_panel_order']);
softheader(__('Admin Panel'));
form_js();
echo '
<script language="javascript" type="text/javascript" src="'.serverurls('latestinfo').'"></script>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.min.js" type="text/javascript"></script>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script type="text/javascript" src="'.$theme['url'].'/js/jquery-ui.min.js"></script>';
if(!empty($usage)){
echo '
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
function server_graph_data() {
},
{ label: "Free",  data: '.$usage['cpu']['percent_free'].'}
];
var server_ram = [
{ label: "Used",  data: '.$usage['ram']['used'].'},
{ label: "Free",  data: '.$usage['ram']['free'].'}
];
var server_disk = [
{ label: "Used",  data: '.$usage['disk']['/']['used_gb'].'},
{ label: "Free",  data: '.$usage['disk']['/']['free_gb'].'}
];
var server_bandwidth = [
{ label: "Used",  data: '.(empty($usage['bandwidth']['used_gb']) ? 0.01 : $usage['bandwidth']['used_gb']).'},
{ label: "Free",  data: '.(empty($usage['bandwidth']['free_gb']) ? $usage['bandwidth']['used_gb']*100 : $usage['bandwidth']['free_gb']).'}
];
var disk_percent = '.$usage['disk']['/']['percent'].';
if(data){
server_cpu = [
{ label: "Used",  data: data["cpu"]["percent"]},
{ label: "Free",  data: data["cpu"]["percent_free"]}
];
server_ram = [
{ label: "Used",  data: data["ram"]["used"]},
{ label: "Free",  data: data["ram"]["free"]}
];
server_disk = [
{ label: "Used",  data: data["disk"]["/"]["used_gb"]},
{ label: "Free",  data: data["disk"]["/"]["free_gb"]}
];
}
server_graph("server_ram", server_ram, "MB");
server_graph("server_cpu", server_cpu, "%");
server_graph("server_disk", server_disk, "");
try{
cpudata.shift();
cpudata.push(parseFloat(server_cpu[0].data));
cpu_dataset = [
{ label: "", data: cpu_makedata(cpudata), color: "#3498DB" }
];
$.plot($("#cpu_hist"), cpu_dataset, cpu_options);
}catch(e){ }
};
function server_graph() {
}
},
}
},
grid: {
hoverable: true
},
tooltip: {
show: true,
content: "%p.0% %s"+(tooltip_suffix == "" ? "" : "<br /> %n "+tooltip_suffix), // show percentages, rounding to 2 decimal places
shifts: {
x: 20,
y: 0
}
},
legend: {
show: false
}
});
};
}
echo '
<script type="text/javascript">
function load_soft_info() {
}else{
var newsstr = "";
for(x in soft_news){
newsstr = newsstr+\'<div class="softnewshead">\'+soft_news[x][0]+\'</div>\'+\'<div class="softnewsblock">\'+soft_news[x][1]+\'</div><br />\';
}
$_("softnews").innerHTML = newsstr;
}
if(typeof(soft_latest_version) == "undefined"){
$_("newsoftversion").innerHTML = "<i>'.__js('No Info').'</i>";
}else{
$_("newsoftversion").innerHTML = soft_latest_version;
}
}
$(document).ready(function(){
load_soft_info();
});
function rotate_img() {
});
}
$(document).ready(function(){
$("#refresh_ins_count, #refresh_user_count, #refresh_domain_count").tooltip();
$("#refresh_user_count, #refresh_ins_count, #refresh_domain_count").click(function(){
var that = this;
$.getJSON("'.$globals['ind'].'act=adminindex&rebuild_cache=1&jsnohf=1", function(data){
$(that).first().removeClass("fa-rotate-360");
$("#total_ins").html(data.ins_count);
$("#total_user").html(data.users_count);
$("#total_domain").html(data.domain_count);
});
});
$("#refresh_user_count, #refresh_ins_count, #refresh_domain_count").click(function(){
var that = this;
$.getJSON("'.$globals['ind'].'act=adminindex&rebuild_cache=1&jsnohf=1", function(data){
$(that).first().removeClass("fa-rotate-360");
$("#total_ins").html(data.ins_count);
$("#total_user").html(data.users_count);
$("#total_domain").html(data.domain_count);
});
});
$("#checkAll").change(function(){
$(".check").prop("checked", $(this).prop("checked"));
});
';
if(!empty($usage)){
echo '
server_graph_data();
startusage();';
}
if(empty($SESS['is_reseller'])){
echo '
$.getJSON("'.$globals['ind'].'act=updates&api=json", function(data){
if("update_available" in data.info){
$("#update_available").html(data.info.update_available);
$("#core_update_notice").show();
}
});';
}
echo '
});
function getusage() {
});
};
function startusage() {
};
function panel_collapse() {
},
success: function(){
},
error: function(){
}
});
$("#"+Id).trigger("t:accordian");
};
$( document ).ready(function() {
var panelList = $(\'#draggablePanelList\');
var panel_order_array = '.$panel_order_array.';
if(panel_order_array != null){
$.each(panel_order_array, function(key, value){
var row_name = value.substring(6);
$("#row_"+row_name).appendTo("#draggablePanelList");
});
}
panelList.sortable({
handle: ".panel-heading",
axis: "y",
placeholder: "highlight_placeholder",
update: function() {
var panel_order = [];
$(".accordion-item", panelList).each(function(index, elem) {
var elem_id = $(this).attr(\'id\');
var panel_id = "panel_"+elem_id.substring(11);
panel_order.push(panel_id);
});
$.ajax({
type: "POST",
url: "'.$globals['current_url'].'api=json",
data: {"panel_order": panel_order},
success: function(){
console.log("success");
},
error: function(){
console.log("error");
}
});
}
});
});
</script>';
if(!empty($sitepad_installed)){
echo '
<div class="alert alert-success text-center">
<a href="#close" class="close" data-bs-dismiss="alert">&times;</a>
<img src="'.$theme['images'].'success.gif" /> SitePad installation has started in background, your users can now access SitePad from their Webuzo accounts.
</div>';
}
echo '
<div class="alert alert-warning text-center" id="core_update_notice" style="display:none">
<img src="'.$theme['images'].'notice.gif" />
<a href="'.$globals['index'].'act=updates" style="text-decoration:none;" id="update_available"></a>
</div>';
$installed_apps = loadinsapps();
$apps_updates_available = 0;
if(empty($globals['DISABLE_SYSAPPS'])){
foreach($installed_apps as $k => $v){
if(!empty($v['aid'])){
if(is_app_upgradable($v['aid'], $v['mod']) && (($apps[$v['aid']]['ins'] == 1 && empty($globals['lictype'])) || !empty($globals['lictype']))){
$apps_updates_available++;
}
}
}
}
if(!empty($apps_updates_available) || !empty($updates_available) ){
$col_size = !empty($apps_updates_available) && !empty($updates_available) ? 'col-md-6 col-xs-12' : 'col-xs-12';
echo'
<div class="row"><!----#Update---->';
echo (!empty($apps_updates_available) ? '
<div class="'.$col_size.'">
<div class="alert alert-warning">
<center>
<img src="'.$theme['images'].'notice.gif" /> &nbsp;
<a href="'.$globals['ind'].'act=apps_updates" alt="" class="text-decoration-none">'.__('There are <b>$0</b> Apps Update(s) available.', array($apps_updates_available)).'</a>
</center>
</div>
</div>' : '');
echo (!empty($updates_available) ? '
<div class="'.$col_size.'">
<div class="alert alert-warning">
<center>
<img src="'.$theme['images'].'notice.gif" /> &nbsp;
<a href="'.$globals['ind'].'act=installations&showupdates=true" alt="" class="text-decoration-none">'.__('There are <b>$0</b> Update(s) available.', array($updates_available)).'</a>
</center>
</div>
</div>' : '');
echo'
</div>';
}
if(1){
$license_col = 8;
$sitepad_col = 4;
$badge_align = 'start';
}else{
$license_col = 12;
$sitepad_col = 0;
$badge_align = 'center';
}
echo '
<div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="add-aliasesLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="addRecordLabel">'.__('Add Favorites').'</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="'.__('Close').'"></button>
</div>
<div class="modal-body p-2">
<form accept-charset="'.$globals['charset'].'" name="create_features" id="create_features" method="post" class="form-horizontal" action="'.$globals['index'].'act=home" data-donereload="1" onsubmit="return submitit(this)">
<div class="row p-3 col-md-12 d-flex">
<div class="col-12 col-md-12 m-2">
<div class="row">
<div class="col-12 mb-3">
<label class="checkbox-inline">
<input class="me-2" type="checkbox" id="checkAll">'.__('Select All').'
</label>
</div>
</div>
<div class="row">';
foreach($menu_list as $k => $v){
if(!empty($fav_list)){
$checked = isset($fav_list[$k]) ? true : false;
}
echo '
<div class="col-12 col-md-6 col-lg-3 mb-3">
<label class="sai_head label-secondary checkbox-inline p-2 cursor-pointer" style="width:100%;">
<input type="checkbox" class="check" name="acts['.$k.']" value="1" '.(!empty($checked) ? 'checked' : '').' class="me-2"> '.$v['name'].'
</label>
</div>';
}
echo '
</div>
</div>
<div class="text-center">
<input type="submit" class="btn btn-primary" id="create_favorites" name="save_favorites" value="'.__('Save').'"/>
</div>
</div>
</form>
</div>
</div>
</div>
</div>';
echo '
<div class="row mb-2 d-flex">
<div class="col-12 col-md-12 col-lg-6 mb-4">
<div class="row g-4">
<div class="col-12 col-md-4">
<a href="'.$globals['index'].'act=users" class="text-decoration-none">
<div class="soft-smbox py-4 px-3 d-flex soft-icon-count">
<div class=" align-self-center">
<span class="soft-smbox-icon" style="background-color: #845ADF;">
<i class="fa fa-user"></i>
</span>
</div>
<div class="flex-grow-1 align-self-center">
<span class="soft-smbox-info d-block text-end">'.(!empty($count_data['users']) ? $count_data['users'] : 0).'</span>
</div>
</div>
<div class="soft-smbox px-3 py-2 " style="border-radius: 0px 0px 12px 12px;">
<span>'.__('Total Users').'</span>
</div>
</a>
</div>
<div class="col-12 col-md-4">
<a href="'.$globals['index'].'act=domains" class="text-decoration-none">
<div class="soft-smbox py-4 px-3 d-flex soft-icon-count">
<div class="align-self-center">
<span class="soft-smbox-icon" style="background-color: #23B7E5;">
<i class="fa fa-globe"></i>
</span>
</div>
<div class="flex-grow-1 align-self-center">
<span class="soft-smbox-info d-block text-end">'.(!empty($count_data['domains']) ? $count_data['domains'] : 0).'</span>
</div>
</div>
<div class="soft-smbox px-3 py-2 " style="border-radius: 0px 0px 12px 12px;">
<span >'.__('Total Domains').'</span>
</div>
</a>
</div>
<div class="col-12 col-md-4">
<a href="'.$globals['index'].'act=mysql_manage_dbs" class="text-decoration-none">
<div class="soft-smbox py-4 px-3 d-flex soft-icon-count">
<div class="flex-shrink-0 align-self-center">
<span class="soft-smbox-icon" style="background-color: #26BF94;">
<i class="fas fa-database"></i>
</span>
</div>
<div class="flex-grow-1 align-self-center">
<span class="soft-smbox-info d-block text-end">'.(!empty($count_data['dbs']) ? $count_data['dbs'] : 0).'</span>
</div>
</div>
<div class="soft-smbox px-3 py-2 " style="border-radius: 0px 0px 12px 12px;">
<span>'.__('Total Databases').'</span>
</div>
</a>
</div>
</div>
<div class="soft-smbox mt-4" style="border-radius: 12px 12px 0px 0px; border-bottom: 1px solid #F0F1F7;">
<div class="row p-3">
<div class="sai_main_head col-7">
<span class="vl">'.__('Favorites').'</span>
</div>
<div class="col-12 col-md-5 " >
<input type="button" class="btn btn-primary float-end border-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#addRecordModal" value="'.__(' Edit Favorites').'" id="favclose_btn">
</div>
</div>
</div>
<div class="soft-smbox pt-1" style="border-radius: 0px 0px 12px 12px;">
<div class="row g-3 p-3">';
foreach($fav_pref as $k => $v){
echo '
<div class="col-12 col-md-6 mx-0">
<a href="'.$v['href'].'" onclick="'.$v['onclick'].'" '.(!empty($v['attr']) ? $v['attr'] : '').' '.(!empty($v['target']) ? 'target="'.$v['target'].'"' : '').' class="text-decoration-none">
<label class="label label-secondary p-2 d-flex cursor-pointer" style="height:100%">
<div class="align-self-center">
<span class="soft-smbox-icon" >'.
(preg_match('/.png|.gif|.jpeg|jpg|.svg$/is', $v['icon']) ? '<img src="'.$v['icon'].'" '.(!empty($v['attr']) ? $v['attr'] : '').' />' : '<i class="'.$v['icon'].'"></i>').'
</span>
</div>
<div class="flex-grow-1 ps-3 d-flex">
<span class="soft-smbox-title" style="font-size: 15px;">'.$v['name'].'</span>
</div>
</label>
</a>
</div>';
}
echo '
</div>
</div>
</div>
<div class="col-12 col-md-12 col-lg-6">
<div class="soft-smbox p-3" style="border-radius: 12px 12px 0px 0px; border-bottom: 1px solid #F0F1F7;">
<div class="pt-1">
<label class="sai_main_head vl" >'.__('Statistics').'</label><br>
</div>
</div>
<div class=" soft-smbox mb-4 p-4" style="border-radius: 0px 0px 12px 12px; height:auto;">';
if(!empty($usage)){
echo '
<div class="row mt-1" >
<div class="col-12 col-md-4 p-2">
<div class ="text-center">
<div class="server_cpu server_graph " id="server_cpu"></div>
<span class="soft-smbox-title">'.__('CPU').'</span>
</div>
</div>
<div class="col-12 col-md-4 p-2">
<div class ="text-center" style="border-left: 1px solid #E7EAF3; padding-left: 12px;">
<div class="server_ram server_graph" id="server_ram"></div>
<span class="soft-smbox-title">'.__('RAM').'</span>
</div>
</div>
<div class="col-12 col-md-4 p-2">
<div  class ="text-center" style="border-left: 1px solid #E7EAF3; padding-left: 12px;">
<div class="server_disk server_graph" id="server_disk"></div>
<span class="soft-smbox-title">'.__('Disk').'</span>
</div>
</div>
</div><hr>';
}
echo '
<div class="softinfo">'.(!defined('WEBUZO_RESELLER') ? '
<table class="table sai_form stats-table">
<tbody>
<tr class="">
<th class="form-label">'.APP.' - '.__(' License').'</th>
<td>'.$globals['license'].' ('.$globals['primary_ip'].')</td>
</tr>
<tr class="">
<th class="form-label">'.__('License Type').'</th>
<td>'.(empty($globals['lictype']) ? __('Free').'
<a class="text-decoration-none" href="'.serverurls('buy').'" target="_blank">
<button class="btn btn-success">'.__('Buy Premium License').'</button>
</a>
<a class="text-decoration-none" href="'.serverurls('pricing').'" target="_blank">
<button class="btn btn-warning">'.__('Pricing').'</button>
</a>' : '
<span color="green">'.__('Premium')).'</span>
<a class="text-decoration-none" href="'.$globals['ind'].'refreshlicense">
<button class="btn btn-primary">'.__('Refresh License').'</button>
</a>'.(asperapp(0, 1, 1) ? ' (
<a class="text-decoration-none" href="'.$globals['ind'].'act=licensekey">Enter License Key</a>)' : '') : '').'
</td>
</tr>
<tr class="">
<th class="form-label">'.__('Server IP').'</th>
<td>'.(!empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR']).'</td>
</td>
'.(!defined('WEBUZO_RESELLER') ? '
<tr class="">
<th class="form-label">'.__('Expires').'</th>
<td>'.(empty($globals['licexpires']) ? __('Never') : makedate($globals['licexpires']).' (DD/MM/YYYY)').'
</td>
</tr>
<tr class="">
<th class="form-label">'.__('Plan').'</th>
<td>'.ucfirst($globals['plan']).'</td>
</tr>
<tr class="">
<th class="form-label">'.__('IP as per licensing server').'</th>
<td>'.@substr(curl_call(fastestmirror().'/ip.php', 0, 5), 0, 16).'</td>
</tr>' : '').'
<tr class="">
<th class="form-label">'.__('PHP Version').'</th>
<td>'.sphpversion().'</td>
</tr>
<tr class="">
<th class="form-label">'.APP.' - '.__(' Version').'</th>
<td>'.asperapp($globals['version'], @$globals['webuzo_version'], @$globals['ampps_version']).'</td>
</tr>
<tr class="">
<th class="form-label">'.__('Latest Version').'</th>
<td id="newsoftversion"></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
<div class="row">
<div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12" id="draggablePanelList">';
foreach ($admin_menu as $key => $value) {
if($key == 'home'){
continue;
}
if(!empty($SESS['is_reseller']) && !in_array($key, $reseller_menu)){
continue;
}
if(!empty($softpanel->user_prefs['admin_collapsed_panels']['panel_'.$key])){
$glyph = 'collapsed';
$collapsed = '';
}else{
$glyph = '';
$collapsed = 'show';
}
echo '
<div class="card soft-smbox mb-4 panel-row" id="row_'.$key.'">
<div class="accordion" id="main_div_'.$key.'">
<div class="accordion-item border-0" id="main_table_'.$key.'">
<div class="accordion-header" id="panel_'.$key.'_heading" >
<div class="row align-items-center panel-heading px-4 ">
<div class="col-10">
<i class="'.$value['icon'].' panel-icon"></i>
<label class="panel-head d-inline-block mb-0">'.$value['name'].'</label>
</div>
<div class="col-2">
<div class="accordion-button '.$glyph.' bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#panel_'.$key.'" aria-controls="panel_'.$key.'" onclick="panel_collapse(\''.$key.'\');"></div>
</div>
</div>
</div>
<div id="panel_'.$key.'" class="accordion-collapse collapse '.$collapsed.'" aria-labelledby="panel_'.$key.'_heading" data-bs-parent="#main_div_'.$key.'">
<div class="accordion-body p-1">
<div class="row g-3 p-3">';
if(!empty($value['sub_menu'])){
foreach ($value['sub_menu'] as $k => $v) {
if(!empty($v['hidden'])){
continue;
}
echo '
<div class="col-12 col-md-4 mx-0 accordion_item">
<a href="'.$v['href'].'" onclick="'.$v['onclick'].'" '.(!empty($v['attr']) ? $v['attr'] : '').' '.(!empty($v['target']) ? 'target="'.$v['target'].'"' : '').' class="text-decoration-none">
<label class="label label-secondary p-2 d-flex cursor-pointer" style="height:100%">
<div class="align-self-center">
<span class="soft-smbox-icon" >'.
(preg_match('/.png|.gif|.jpeg|jpg|.svg$/is', $v['icon']) ? '<img src="'.$v['icon'].'" '.(!empty($v['attr']) ? $v['attr'] : '').' />' : '<i class="'.$v['icon'].'"></i>').'
</span>
</div>
<div class="flex-grow-1 ps-2 d-flex">
<span class="soft-smbox-title" style="font-size: 15px;">'.$v['name'].'</span>
</div>
</label>
</a>
</div>';
}
}else{
echo '
<div class="col-12 col-md-4 mx-0 accordion_item">
<a href="'.$value['href'].'" onclick="'.$value['onclick'].'" '.(!empty($v['attr']) ? $value['attr'] : '').' '.(!empty($value['target']) ? 'target="'.$value['target'].'"' : '').' class="text-decoration-none">
<label class="label label-secondary p-2 d-flex cursor-pointer" style="height:100%">
<div class="align-self-center">'.
(preg_match('/.png|.gif|.jpeg|jpg|.svg$/is', $value['icon']) ? '<span><img style="width:38px;" src="'.$value['icon'].'"/></span>' : '<span class="soft-smbox-icon"><i class="'.$value['icon'].'"></i></span>').'
</div>
<div class="flex-grow-1 ps-2 d-flex">
<span class="soft-smbox-title" style="font-size: 15px;">'.$value['name'].'</span>
</div>
</label>
</a>
</div>';
}
if(!empty($value['html'])){
echo $value['html'];
}
echo '
</div>
</div>
</div>
</div>
</div>
</div>';
}
echo '
</div>';
$show_promo = 0;
if($SESS['user'] == 'root'){
$show_promo = 1;
}
if(!empty($show_promo)){
$promo[] = '
<div class="col-md-12" align="center"><h2 style="margin-top:5px;">Pinguzo - Server and Website Monitoring</h2><hr></div>
<div class="col-md-7">
<span style="font-size:15px; line-height:150%">We have been developing Pinguzo, which is a Server and Website Monitoring SaaS. As you know, downtime can happen on your Servers and Websites. Pinguzo can send notifications instantly, so that you can take corrective steps.
<br>You can use your <b>Softaculous Account to Sign In</b>. Since its a SaaS, you will not need to manage any storage or processes related to monitoring.
<center style="margin-top:15px;"><a href="https://cp.pinguzo.com" style="text-decoration:none;color:#FFF;" target="new"><button class="btn btn-primary">Let\'s Try Pinguzo</button></a></center>
<br>If you have any feedback / questions, please do let us know - <a href="mailto:support%40pinguzo.com">support@pinguzo.com</a>.</span>
</div>
<div class="col-md-5" style="vertical-align:center;">
<img src="'.$theme['images'].'pinguzo_scr.jpg" class="img-responsive">
</div>';
} */
$promo[] = '
<div class="accordion-header">
<div class="row align-items-center">
<div class="col-11">
<h2 class="vl soft-smbox-heading">'.__('SitePad Website Builder').'</h2>
</div>
<div class="col-1">
<div class="col-2 accordion-button bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#sitepad" aria-expanded="true" aria-controls="sitepad" >
</div>
</div>
</div>
</div>
<div class="collpase show row mt-4" id="sitepad">
<div class="col-12 col-md-7">
<div class="soft-infobox-info p-4 h-100">
<p>We have been developing SitePad, which is a website builder that publishes static web pages to hosting account. SitePad is an Easy to use, Drag &amp; Drop Website builder with 40+ Widgets like Image/Video Slider, Image Galleries, Rich Text and many more!! SitePad currently has <b>345+</b> Themes and we are adding more..</p>
<p>SitePad plugin can be installed in your control panel and users can publish their site to their hosting account with just One Click.</p>
<p>Pricing starts at $5/month per server with unlimited users and goes down to $3/month with volumes</p>
<div class="my-4">
<a href="?install_sitepad=1" class="btn btn-primary">Install SitePad</a>
<a href="http://sitepad.com" class="btn btn-primary" target="new">More Details</a>
</div>
<p>If you have any feedback / questions, please do let us know - <a href="mailto:support%40sitepad.com">support@sitepad.com</a>.</span></p>
</div>
</div>
<div class="col-12 col-md-5">
<img src="'.$theme['images'].'sitemush_scr.png" class="img-fluid">
</div>
</div>';
$promo[] = '
<div class="accordion-header">
<div class="row align-items-center">
<div class="col-11">
<h2 class="vl soft-smbox-heading">'.__('Virtualizor - VPS Control Panel').'</h2>
</div>
<div class="col-1">
<div class="col-2 accordion-button bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#virtualizor" aria-expanded="true" aria-controls="virtualizor" >
</div>
</div>
</div>
</div>
<div class=" collpase show row mt-4" id="virtualizor">
<div class="col-12 col-md-7">
<div class="soft-infobox-info p-4 h-100">
<p>Virtualizor is a powerful web based VPS Control Panel. It supports OpenVZ, Xen PV, Xen HVM, XenServer, Linux KVM, LXC and OpenVZ 7 virtualization. Admins can create a VPS on the fly by the click of a button. VPS users can start, stop, restart and manage their VPS using a very advanced web based GUI.</p>
<p>Pricing starts at $9/month per server with unlimited VPS and goes down to $7/month with volumes</p>
<div class="my-4">
<a href="http://virtualizor.com" class="btn btn-primary" target="new">Try Virtualizor</a>
</div>
<p>If you have any feedback / questions, please do let us know - <a href="mailto:support%40virtualizor.com">support@virtualizor.com</a>.</p>
</div>
</div>
<div class="col-12 col-md-5">
<img src="'.$theme['images'].'virtualizor_scr.png" class="img-fluid">
</div>
</div>
';
echo '
<div class="soft-smbox mb-4 p-3">
'.$promo[mt_rand(0, count($promo) - 1)].'
</div>';
}
echo '
<div id="softnewsholder" class="soft-smbox mt-5">
<div class="news_content_header p-3">
<h2>'.APP.' - '.__(' News').'</h2>
</div>
<div class="news_content sai_newzbox" style="padding:0;">
<div class="softnews" id="softnews"></div>
</div>
</div>';
softfooter();
}