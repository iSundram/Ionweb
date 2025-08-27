<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function softheader() {
}
if(empty($title)){
$title = __('Admin Panel');
}
$title .= ' - '.(defined('WEBUZO_RESELLER') ? $globals['sn'] : APP);
$is_single_user = is_single_user();
$custom_favicon  = (!empty($globals['favicon_logo']) ? $globals['favicon_logo'] : $theme['images'].'/favicon.ico');
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$globals['charset'].'" />
<meta name="keywords" content="softaculous, software" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<title>'.$title.'</title>
'.css_url(['bootstrap/css/bootstrap.min.css', 'style.css', 'select2.min.css'], 'combined.css').'
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="shortcut icon" href="'.$custom_favicon.'" />
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
'.js_url(['jquery.js','popper.min.js','bootstrap/js/bootstrap.min.js','universal.js', 'select2.min.js'], 'combined.js').'
<script>
var l = {};
'.js_lang_export(['delete', 'deleted', 'warning', 'error', 'r_connect_error', 'done', 'following_errors_occured', 'delete_conf', 'show', 'hide', 'no_tbl_rec', 'info', 'ser_no_notices']).'
var theme = "'.$theme['images'].'";
var appids = ["'.implode('", "', array_keys($iapps)).'"];
var tools = ["phpmyadmin","rockmongo", "rainloop", "tomcat", "monsta"];
function in_arrays() {
}
}
return false;
}
function is_app_inst() {
}else if(act == "rainloop"){
'.($iapps['35_1']['mod'] < 17 ? 'alert("'.__js('Please update Exim to use this functionality.').'")' : 'window.open("'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/rainloop/index.php", "_blank")' ).'
}else{
window.open(act+"/", "_blank");
}
}else if(act == "appact"){
window.location = "index.php?act="+act+"&app="+appname;
}else{
window.location = "index.php?act="+act;
}
}else{
var r = confirm("'.__js('This utility is not installed. Please install it first').'")
if(r==true){
'.(empty($disable_sysapps) ? 'window.location = "index.php?act=apps&app="+app_id;' : 'alert("'.__js('This functionality has been disabled from the Webuzo Admin Panel').'")').'
}else{
return true;
}
}
}
$(document).ready(function(){
new_theme_funcs_init();
$("[data-bs-toggle=tooltip]").tooltip();
$(".make-select2").each(function(index){
var jEle = $(this);
make_select2(jEle);
});
});
</script>
</head>
<body>
<div class="modal fade" id="logs_modal">
<div class="modal-dialog modal-dialog-scrollable modal-lg">
<div class="modal-content">
<div class="modal-header text-center" id="logs_modal_head" style="background: #1960bb; color: #fff;">
<h5 class="fhead modal-title">'.__('Logs ').'</h5>
<h6 class="fhead modal-title mx-2" id="task_id"></h6>
<div class="ms-auto d-flex align-items-center">
<a href="'.$globals['index'].'act=tasks&export=html&uuid='.$v['uuid'].'" class="me-2" id="exportLink">
<i class="fa-solid fa-file-download fa-2x" style="color: white;" title="Export HTML"></i>
</a>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
</div>
<div class="modal-body p-2" id="logs_modal_body"></div>
</div>
</div>
</div>';
if(!empty($act)){
$dns_arr = ['advancedns' => array('name' => 'Advance DNS Settings',
'menu_id' => 'dns_functions',
'submenu_id' => 'sub_advancedns'),
'dns_template' => array('name' => 'DNS Templates',
'menu_id' => 'dns_functions',
'submenu_id' => 'sub_dnstemplate'),
'mxentry' => array('name' => 'MX Entry',
'menu_id' => 'dns_functions',
'submenu_id' => 'sub_mxentry')];
if($act == 'apps' || $act == 'listapps'){
$mainmenu = 'Apps';
$submenu = 'Install an App';
$sub_menu_link = 'apps';
$sub_menu_active = 'appsinst';
}elseif(array_key_exists($act, $dns_arr)){
$mainmenu = 'DNS Functions';
$submenu = $dns_arr[$act]['name'];
$sub_menu_link = $dns_arr[$act]['menu_id'];
$sub_menu_active = $dns_arr[$act]['submenu_id'];
}else{
$extra = ['support_resources' => 1, 'suspended' => 1, 'type' => 2, 'used' => 1, 'reserved' => 1, 'demo' => 1];
foreach($extra as $k => $v){
if($_GET[$k] == $v){
$append = $k.'='.$v;
break;
}
}
$href = 'index.php?act='.$act.(!empty($append) ? '&'.$append : '');
foreach($admin_menu as $key => $val){
if(!isset($val['sub_menu']) && $val['href'] == $href && !empty($val['id'])){
$sub_menu_link = $val['id'];
break;
}
foreach($val['sub_menu'] as $k => $v){
if($href == $v['href']){
$mainmenu = $val['name'];
$submenu = $v['name'];
$sub_menu_link = $val['id'];
$sub_menu_active = $v['id'];
break;
}
}
if(!empty($mainmenu)){
break;
}
}
}
}
echo '
<script>
$(document).ready(function(){
$(document).on("keypress", function(e) {
if (e.key === "/") {
if (!$(document.activeElement).is("input, textarea")) {
$("#search_cat").focus();
e.preventDefault();
}
}
});
load_average();
setInterval(load_average,15000);
$(".sub-menu a").click(function(){
setcookie("sub_menu_active", $(this).closest("li").attr("id"));
});
$(".top").click(function(){
$("html, body").animate({ scrollTop: 0 }, 500);
return false;
});
});
function left_panel_state() {
}
}else{
if(!minimize){
par.find(".sub-menu").slideUp();
}
}
par.click(function(){
setcookie("sub_menu_link", link_id);
});
$(this).click(function(){
if($(".left_panel").hasClass("left-menu-minimize")){
return;
}
par.find(".sub-menu").slideToggle();
});
$("#home").click(function(){
removecookie("sub_menu_link");
});
$(".brand").click(function(){
removecookie("sub_menu_link");
});
});
}
function handlecookie() {
}
function left_menu_minimized() {
},
success: function(){
},
error: function(){
}
});
};
function left_menu_toggle() {
}
var shown = !jEle.hasClass("left-menu-minimize");
if(!minimize){
left_menu_minimized(!shown);
}
if(!shown){
$(".left_menu_toggle_btn").css({direction: "rtl"});
$(".nav-top-row").addClass("soft_nav_blue");
$("#softcontent").addClass("left_menu_min");
jEle.find(".sub-menu").hide();
}else{
$(".left_menu_toggle_btn").css({direction: "ltr"});
$(".nav-top-row").removeClass("soft_nav_blue");
$("#softcontent").removeClass("left_menu_min");
jEle.find(".menu-item.active .sub-menu").show();
}
}
$(document).ready(function(){
$("#left_menu_toggle_btn").on("click", function(e){left_menu_toggle();});
var left_menu_pref = '.((int) $softpanel->user_prefs['left_menu_minimized']).';
if($(window).width() < 768 || left_menu_pref){
left_menu_toggle(1);
}
handlecookie();
$("#header_prog").animate({width:"20%"}, "slow", function(){
$("#header_prog").animate({width:"50%"},"5000",function(){
$("#header_prog").animate({width:"100%"},"5000");
});
});
});
function search_cat() {
}else{
$(this).hide("fast");
}
});
$(".menu-list .menu-name").each(function(){
var text = $(this).text().toLowerCase();
if(text.indexOf($val) >= 0){
$(this).attr("found", 1);
found = 1;
}
});
}else{
force_show = 1;
$(".menu-list .sub-menu").slideUp();
$(".menu-list li.active .sub-menu").slideDown();
$(".menu-list .menu-name").attr("found", 1);
$(".menu-list .sub-menu li").show();
}
$(".menu-list .menu-name").each(function(){
if($(this).parent().find("[found]").length >= 1){
$(this).parent().show();
}else{
$(this).parent().hide();
}
});
if(qlen >= 1){
if(!found){
$(".no_data_found").show();
$(".no_data_found").html("'.__js('No data found !').'");
$(".menu-list").hide();
}else{
$(".no_data_found").hide();
$(".menu-list").show();
}
}
}
</script>
<!--height="'.(!empty($softpanel->leftpanel_resize) ? '40' : '70').'"-->
<div class="loading fixed-top" style="display:none">
<button class="btn btn-primary" type="button" disabled>
<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
<span class="loading-txt">Loading...</span>
</button>
</div>
<header class="top-header">
<div class="container-fluid">
<div class="nav-top-row row align-items-center">
<div class="col p-0">
<div class="brand">
<a href="'.$globals['ind'].'" class="text-decoration-none">
<img src="'.(empty($globals['logo_url']) ? $theme['images'].'header.png' : $globals['logo_url']).'" alt="" height="'.(!empty($softpanel->leftpanel_resize) ? '60' : '55').'" />
</a>
</div>
</div>
<div class="col-auto top_header_info d-flex align-items-center justify-content-end">
<label class="label mb-0 me-3">'.ucfirst($globals['WU_DISTRO']).' v'.$globals['os_version'].'</label>
<a id="webuzo_version" href="'.$globals['ind'].'act=updates" title="'.__('Webuzo Version').'" class="mb-0 me-3 text-decoration-none" style="color: #595F84; font-size: 14px;">v'.$globals['webuzo_version'].'</a>
'.(!empty($globals['WU_PRIMARY_DOMAIN']) ? '<a id="load_ave_live" class="me-3" href="'.$globals['ind'].'act=webuzoconfigs" title="'.__('Server Hostname').'" clEditass="process-manager me-2 text-decoration-none" style="color: #595F84;">
'.__('Hostname:').'
<span id="hostname">'.$globals['WU_PRIMARY_DOMAIN'].'</span>
</a>' : '').'
<a id="load_ave_live" class="me-3" href="'.$globals['ind'].'act=process_manager" title="'.__('Your server\'s 1-minute, 5-minute, and 15-minute load averages').'" clEditass="process-manager me-2 text-decoration-none" style="color: #595F84;">
'.__('Load Averages:').'
<span id="lavg_one">0</span>, <span id="lavg_five">0</span>, <span id="lavg_fifteen">0</span>
</a>
<label class="label label-primary p-2 mb-0 me-3 px-3 top_login_txt" style="color: #0472ED;">'.__('Logged in as $0', array($SESS['user'])).'</label>
<div class="top_sm_abs">
'.($is_single_user ? '
<a href="'.$globals['enduser_url'].'loginAs='.$is_single_user.'" target="_blank">
<i class="fas fa-chalkboard-teacher header-icon" data-toggle="tooltip" title="'.__('Enduser Panel').'"></i>
</a>&nbsp; &nbsp; &nbsp;' : '').'
'.(empty($SESS['is_reseller']) ? '
<a class="circle-box" href="'.$globals['ind'].'act=terminal" target="_blank"><i class="fas fa-terminal header-icon " data-toggle="tooltip" title="'.__('Terminal').'" style="font-weight: bold;"></i></a> &nbsp; &nbsp; &nbsp;
' : '').'
<a href="'.$globals['ind'].'act=logout">
<i class="fas fa-sign-out-alt header-icon" data-toggle="tooltip" title="'.__('Logout').'"></i>
</a>
</div>
</div>
</div>
</div>
</header>
<div id="header_prog" class="header_prog" style="width:0px;"></div>
<div class="main">
<div class="modal fade" id="show_message" tabindex="-1" aria-labelledby="show_messageLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="show_messageLabel">Modal title</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
...
</div>
<div class="modal-footer">
<button type="button" class="btn btn-primary ok" data-bs-dismiss="modal">'.__('Ok').'</button>
<button type="button" class="btn btn-primary yes" data-bs-dismiss="modal">'.__('Yes').'</button>
<button type="button" class="btn btn-dark no" data-bs-dismiss="modal">'.__('No').'</button>
</div>
</div>
</div>
</div>
<!-- Notices Modal -->
<div class="modal fade modal-lg right modal_outer right_modal" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalHeader" aria-hidden="true">
<div class="modal-dialog modal-dialog-scrollable notic-dialog" role="document">
<div class="modal-content notice-content">
<div class="modal-header bg-info">
<h5 class="modal-title" id="noticeModalHeader">'.__('Notices').'</h5>
<a href="#" style="margin-left:20px; text-decoration:none; font-size:1.1em; float:right; background-color:#dc3545; color:#ffffff; border: 1px solid #dc3545; padding: 2px 7px; border-radius: 4px;" data-bs-dismiss="alert" aria-label="Close" data-nid="all" onclick="dismiss_notice(this);" title="'.__('Dismiss all notices').'">'.__('Dismiss all').'</a>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body" style="background-color:#eff2f7" id="notices">';
$notices = get_notices(['dismissed' => 0], 200);
if(!empty($notices)){
foreach($notices as $k => $v){
if($k == 'count'){
continue;
}
echo '
<div class="alert alert-'.($v['atype'] ?: 'danger').' '.(!empty($v['dismissable']) ? 'alert-dismissible' : '').' pb-0" id="ndiv_'.$v['nid'].'">
<div id="'.$v['nid'].'">
<a href="'.($v['link'] ?: '#').'" class="text-decoration-none"><h6 class="d-inline">'.$v['title'].'</h6></a>
</div>
<p>'.$v['body'].'</p>
<p><i>'.datify($v['updated']).'</i></p>
'.(!empty($v['dismissable']) ? '
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" data-nid="'.$v['nid'].'" onclick="dismiss_notice(this)"></button>' : '' ).'
</div>';
}
if($notices['count'] > 200){
echo '<div id="more_notices">
<a class="btn btn-primary text-decoration-none" href="'.$globals['ind'].'act=notices&searchin=dismissed&search=dismiss">'.__('Load More').'</a>
</div>';
}
}else{
echo '<p>'.__('The server is running just fine ! There are no notices.').'</p>';
}
echo '
</div>
</div>
</div>
</div>
<!-- End Notices Modal -->';
$news = loaddata($globals['var_path'].'/news.json');
if(!empty($news['reseller']['message'])){
echo '
<!-- News Modal -->
<div class="modal fade" id="newsModal" tabindex="-1" aria-labelledby="newsModalHeader" aria-hidden="true">
<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
<div class="modal-content">
<div class="modal-header alert alert-'.($news['reseller']['alert'] != 'none' ? $news['reseller']['alert'] : 'info').'">
<h5 class="modal-title" id="newsModalHeader">'.__('Admin News').'</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body" id="news">';
$news['reseller']['message'] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $news['reseller']['message']);
if(empty($news['reseller']['message'])){
echo '<p>'.__('There is no Admin news').'</p>';
}else{
echo html_entity_decode($news['reseller']['message'], ENT_HTML5);
}
echo '
</div>
</div>
</div>
</div>
<!-- End News Modal -->';
}
echo '
<!--left panel starts here-->
<div class="left_panel">
<div class="left_menu_toggle_btn" alt="'.__('Left Menu').'" id="left_menu_toggle_btn">‹</div>
<div class="nav-side-menu">
<div class="container">
<div class="search-box">
<input type="text" id="search_cat" onfocus="this.value=\'\';" onKeyUp="search_cat(this.value);" value="'.__('Search Now (/)').'" placeholder="'.__('Search Now (/)').'" class="sai_search_box my-3">
<i class="fa-solid fa-magnifying-glass fa-xs"></i>
</div>
<div class="no_data_found mb-3 px-2"></div>
</div>
<div class="menu-list mt-3">
<ul id="menu-content" class="menu-content collapse out">';
foreach($admin_menu as $cat => $val){
if(!empty($SESS['is_reseller']) && !in_array($cat, $reseller_menu)){
continue;
}
if(!empty($val['hidden'])){
continue;
}
echo '
<li id="'.$val['id'].'" class="menu-item '.$val['class'].'" onclick="'.$val['onclick'].'">
<div class="menu-name px-3">
'.(!empty($val['href']) ? '<a href="'.$val['href'].'" '.(!empty($val['attr']) ? $val['attr'] : '').' '.(!empty($val['target']) ? 'target="'.$val['target'].'"' : '').'>' : '');
if(preg_match('/.png|.gif|.jpeg|jpg|.svg/is', $val['icon'])){
echo '
<img src="'.$val['icon'].'" '.(!empty($val['attr']) ? $val['attr'] : '').'/> ';
}else{
echo '
<i class="'.$val['icon'].' me-1"></i> ';
}
echo '
<span class="menu_label">
<label>'.$val['name'].'</label> '.(!empty($val['href']) ? '</a>' : '');
if(is_array($val['sub_menu'])){
echo '
<div id="'.$val['id'].'_img" class="float-end menu-expand-holder">
<i class="fas fa-chevron-right"></i>
</div>
<div class="float-end menu-expand-holder" id="'.$val['id'].'_img_opened" style="display:none;">
<i class="fas fa-chevron-down"></i>
</div>';
}
echo '
</span>
</div>';
if($val['sub_menu']){
echo '
<ul class="sub-menu collapse" id="'.$val['id'].'_l">';
foreach($val['sub_menu'] as $sub_cat => $sub_val){
if(!empty($sub_val['hidden'])){
continue;
}
echo'
<li id="'.$sub_val['id'].'" tags="'.(!empty($sub_val['tags']) ? $sub_val['tags'] : '').'">
<a href="'.$sub_val['href'].'" '.($submenu == $sub_val['name'] ? 'class="active-link"' : '').' onclick="'.$sub_val['onclick'].'" '.(!empty($sub_val['attr']) ? $sub_val['attr'] : '').' '.(!empty($sub_val['target']) ? 'target="'.$sub_val['target'].'"' : '').'>
<span class="menu_item">- '.$sub_val['name'].'</span>
</a>
</li>';
}
echo '
</ul>';
}
echo '
</li>';
}
echo '
</ul>
</div>
</div>
</div>
<div id="softcontent" class="container-fluid page-content">';
$bnstr = '';
if(defined('WEBUZO_RESELLER') && !empty($news['reseller']['message'])){
$bnstr = '
<button type="button" class="btn btn-primary" style="margin-bottom:20px" data-bs-toggle="modal" data-bs-target="#newsModal">
<i class="fas fa-bell fa-2x position-relative">';
if(!empty($news['reseller']['message'])){
$bnstr .= '
<span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
<span class="visually-hidden">News alerts</span>
</span>
';
}
$bnstr .= '
</i>
</button>';
}
if(empty($SESS['is_reseller'])){
echo '
<div class="position-fixed bottom-0 end-0 d-flex flex-column" style="margin-bottom:15vh; z-index:99999;" tabindex="99">
'.$bnstr.'
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#noticeModal">
<i class="far fa-newspaper fa-2x position-relative"><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 12px;" id="notices_count">'.$notices['count'].'<span class="visually-hidden">unread messages</span></span></i>
</button>
</div>';
}
echo '
<div class="row" id="breadcrumbs">
<div class="col-12">
<ul id="breadcrumbs_list" class=" bd_crumb m-0 p-3">
<li style="display: inline;"><a href="'.$globals['admin_url'].'" onclick="removecookie(\'sub_menu_link\');" >'.__('Home').'</a> </li>';
if(!empty($mainmenu)){
echo '<li style="display: inline;"><span>  &gt; <a href="'.$href.'" onclick="setcookie(\'sub_menu_link\', \''.$sub_menu_link.'\');">'.$mainmenu.'</a> </span></li>';
if(!empty($submenu)){
echo '<li style="display: inline;"> <span>  &gt; <a href="'.$href.'" onclick="setcookie(\'sub_menu_active\', \''.$sub_menu_active.'\');">'.$submenu.'</a> </span></li>';
}
}
echo '
</ul>
</div>';
if(empty($globals['webuzo_setup']) && empty($SESS['is_reseller'])){
echo '
<div class="col-12 alert alert-info text-center mt-2 '.(optREQ('act') == 'listapps' ? 'd-none' : '').'">
'.__('Webuzo initial setup is not completed. Please {$0}click here{$1} to configure the panel settings.', ['<a href="'.$globals['index'].'act=webuzoconfigs">', '</a>']).'
<button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
echo '
</div>
<div class="row px-2">
<div class="col-12">
';
if($globals['lictype'] == '-2'){
echo '
<div class="mb-3">
<div id="soft_dev_banner" style="display:block;margin:0;padding:0;width:100%;background-color:#ffffd2;">
<div style="padding:10px 35px;font-size:14px;text-align:center;color:#555;"><strong>Dev License:</strong> This installation of <b>'.APP.'</b> is running under a Development License and is not authorized to be used for production use. <br>Please report any cases of abuse to <a href="mailto:support@'.strtolower(APP).'.com">support@'.strtolower(APP).'.com</a>
</div>
</div>
</div>'
;
}
if(!defined('WEBUZO_RESELLER')){
$severe_notices = get_notices(['dismissed' => 0, 'ntype' => 'hf_notice'], 5);
if(!empty($severe_notices)){
foreach($severe_notices as $k => $v){
if($k == 'count'){
continue;
}
echo '
<div class="alert alert-'.($v['atype'] ?: 'danger').' '.(!empty($v['dismissable']) ? 'alert-dismissible' : '').' pb-0" id="ndiv_'.$v['nid'].'">
<div id="nid_'.$v['nid'].'" class="mb-1">
<a href="'.($v['link'] ?: '#').'" class="text-decoration-none"><h6 class="d-inline">'.$v['title'].'</h6></a>
</div>
<p>'.$v['body'].'</p>
'.(!empty($v['dismissable']) ? '
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" data-nid="'.$v['nid'].'" onclick="dismiss_notice(this)"></button>' : '' ).'
</div>';
}
}
}
}
function softfooter() {
}
$pageinfo = array();
if(!empty($globals['showntimetaken'])){
$pageinfo[] = __('Page Created In').':'.substr($end_time-$start_time,0,5);
}
if(!empty($theme['copyright'])){
echo unhtmlentities($theme['copyright']);
}
echo '
</div>
</div>
<footer class="sai_foot text-center py-4">
<p>'.__('All times are').' '.get_timezone_offset().'. '.__('The time now is').' '.datify(time(), false).'.</p>
<label class="me-2">'.copyright().'</label>'.(empty($pageinfo) ? '' : '<label>'.implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $pageinfo).'</label>').'
<div class="top position-fixed fixed-bottom">
<div class="back-to-top" class="navtop" alt="'.__('Back to Top').'" title="'.__('Back to Top').'">
<i class="fas fa-angle-up"></i>
</div>
</div>
</footer>
</div>
</div>';
if(method_exists($softpanel, 'admin_cp_footer')){
$softpanel->admin_cp_footer();
}
echo '
</body>
</html>';
}
function css_url() {
}
function js_url() {
}
function js_lang_export() {
}
if(empty($ret)){
echo $ret;
return;
}
return $str;
}
function error_handle() {
}
$str .= '</ul>
</div>
<div class="col-sm-2" align="right">
<img src="'.$theme['images'].'/caution.png">
</div>
<div class="col-sm-1">
<a href="#close" class="close" data-bs-dismiss="alert">&times;</a>
</div></div></div>';
if(empty($return)){
echo $str;
}else{
return $str;
}
}
}
function success_message() {
}
echo '</ul>
</td>
</tr>
</table>'.(($center) ? '</center>' : '' ).'
<br />';
}
}
function majorerror() {
}
function message() {
}
function get_checkbox($name, $text = '', $id = '', $chk_post = array('chk_it' => 1), $value = 1, $class_name = '', $events= array()){
$events = implode(' ', $events);
if(!empty($chk_post['chk_it'])){
$_name = $name;
if(!empty($chk_post['name'])){
$_name = $chk_post['name'];
}
$chk_flag = POSTchecked($_name, $chk_post['val']);
}
if(empty($id)){
$id = generateRandStr(5);
}
return '<div class="custom-control custom-checkbox d-inline block me-1">
<input type="checkbox" class="'.($name != 'select_all' ? 'ios' : '').' custom-control-input '.$class_name.'" name="'.$name.'" '.(!empty($id) ? 'id="'.$id.'"' : '').' '.$chk_flag.' '.(!empty($value) ? 'value="'.$value.'"' : '').' '.$events.'>
<label class="custom-control-label" for="'.$id.'">'.$text.'</label>
</div>';
}
function page_links() {
}
$page = $globals['cur_page'];
if(is_null($max)){
$max = $globals['reslen'];
}
$pages = $max != -1 ? ceil($num_res/$max) : 0; // Number of Pages
$pg = ceil(($page/$max) + 1); //Current Page
$_pages = array();
if($pages > 1){
if($pg != 1){
$_pages['&lt;&lt;'] = 1;
$_pages['&lt;'] = ($pg - 1);
}
for($i = ($pg - 4); $i < $pg; $i++){
if($i >= 1){
$_pages[$i] = $i;
}
}
$_pages[$pg] = $pg;
for($i = ($pg + 1); $i <= ($pg + 4); $i++){
if($i <= $pages){
$_pages[$i] = $i;
}
}
if($pg != $pages){
$_pages['&gt;'] = ($pg + 1);
$_pages['&gt;&gt;'] = $pages;
}
}
$name = 'pgjmp_'.generateRandStr(4);
$links = '
<div class="row my-3">
<div class="col-12 col-md-5 col-lg-4 align-items-center">
<label colspan="0" class="form-label me-1">'.__('Entries Per Page').'
<select name = "reslen" class="perpage" onchange="return go_to(this.value)">
<option value="-1">--</option>
<option value="10" '.($max == 10 ? 'selected="selected"' : '').'>10</option>
<option value="25" '.($max == 25 ? 'selected="selected"' : '').'>25</option>
<option value="50" '.($max == 50 ? 'selected="selected"' : '').'>50</option>
<option value="100" '.($max == 100 ? 'selected="selected"' : '').'>100</option>
<option value="all" '.($max == -1 ? 'selected="selected"' : '').'>'.__('All').'</option>
</select>
</label>
<label class="form-label me-2">'.$text.' :
<span id="num_res">'.$num_res.'</span>
</label>
</div>
<div class="col-12 col-md-7 col-lg-8" style="display:'.($max != -1 ? '' : 'none').'">
<nav class="d-flex align-items-center justify-content-md-end flex-wrap" aria-label="Page navigation">
<label class="pagelinks me-2">
'.__('Page').' '.$pg.' '.__('of').' '.$pages.'
</label>
<ul class="pagination mb-0 me-2">';
foreach($_pages as $k => $lv){
$links .= '
<li class="page-item '.($k == $pg ? 'active' : '' ).'">
<a class="page-link" href="javascript:go_to('.$max.', '.$lv.')">'.$k.'</a>
</li>';
}
$links .= '
</ul>
<div id="'.$name.'" class="mt-2 mt-sm-2 mt-md-0">
<input type="text" name="page" id="in'.$name.'" size="10" style="width:70px;" class="me-2" />
<input class="perpage" type="button" value="'.__('Go').'" style="padding:4px 10px; border-radius:20px;" onclick="go_to('.$max.', $_(\'in'.$name.'\').value)"/>
</div>
</nav>
</div>
</div>';
echo $links;
}
function form_js() {
});
function onload_unlimited() {
});
}
function showHiddenTextBox() {
}else{
txt.show();
v = txt.val();
}
jEle.val(v);
}
function setDropDownValue() {
}
function selectAllcheckbox() {
}else{
$(this).removeAttr("checked");
}
});
}
</script>';
}
function form_type_unlimited() {
}
function form_type_multicheckbox() {
}
$str .= '
<div class="row values mb-4" key="'.$key.'">';
if(!empty($props['selectall'])) {
$str .= '
<div class="col-12 mb-3">
<label class="checkbox-inline">
<input class="me-2" type="checkbox" onchange="selectAllcheckbox(this, \''.$key.'\');">'.$props['selectall'].'
</label>
</div>';
}
foreach ($props['list'] as $k => $v) {
$str .= '
<div class="col-12 col-md-6 col-lg-3 mb-3">';
$checked = (int)$v['checked'];
if(!empty($val)){
$checked = isset($val[$k]) ? true : false;
}
if(!empty($_POST)){
$checked = isset($_POST[$key][$k]) ? true : false;
}
$str .= '
<label class=" label-secondary checkbox-inline p-2" style="width:100%;">
<input type="checkbox" group="'.$key.'" id="'.$k.'" name="'.$key.'['.$k.']" value="1" '.(!empty($checked) ? 'checked' : '').' class="me-2 align-middle">'.$v['heading'].'
</label>';
$str .='
</div>';
}
$str .= '
</div>';
return $str;
}
function form_type_select() {
}
$str .=	'
</select>
</div>
</div>
</div>';
return $str;
}
function form_type_text() {
}
function form_type_checkbox() {
}
function form_type_radio() {
}
$str .= '
</div>
</div>
</div>';
return $str;
}