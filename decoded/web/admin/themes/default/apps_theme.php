<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function apps_theme() {
}
echo 0;
return false;
}
softheader($software['name']);
$loadedinfo = array('overview', 'notes', 'features', 'reviews', 'ratings');
?>
<script language="JavaScript" src="<?php echo $theme['url'].'/js/tabber.js';?>" type="text/javascript"></script>
<script language="JavaScript" id="ratings_js_file" type="text/javascript"></script>
<script language="JavaScript" id="review_js_file"  type="text/javascript"></script>
<script language="JavaScript" id="reviews_js_file"  type="text/javascript"></script>
<script type="text/javascript">
tabs = new tabber;
tabs.tabs = new Array('<?php echo implode('\', \'', $loadedinfo);?>');
tabs.tabwindows = new Array('<?php echo implode('_win\', \'', $loadedinfo);?>_win');
tabs.inittab = <?php echo '\''.$init_tab.'\';';?>
$(document).ready(function(){
tabs.init();loadraterev();
});
function loadraterev() {
}
if(empty($globals['off_review_link'])){
echo '$_(\'review_js_file\').src =  "https://api.webuzo.com/reviewjs.php?soft='.$soft.'&ip='.$_SERVER['SERVER_ADDR'].'";';
echo '$_(\'reviews_js_file\').src =  "https://api.webuzo.com/reviewsjs.php?soft='.$soft.'&ip='.$_SERVER['SERVER_ADDR'].'";';
}
?>
};
function notifyversion() {
}else{
return true;
}
};
function notified() {
}
};
</script>
<?php
$url = $theme['images'];
$ratings = array();
$deduct = 1;
$r = $apps[$soft]['ratings'];
for($i_r=1; $i_r<=5; $i_r++){
if($r >= 1){
$ratings[$i_r] = '<i class="fas fa-star" alt="('.number_format($apps[$soft]['ratings'], 2).' out of 5)"></i>';
}elseif($r > 0){
$ratings[$i_r] = '<i class="fas fa-star-half-alt" alt="('.number_format($apps[$soft]['ratings'], 2).' out of 5)"></i>';
}else{
$ratings[$i_r] = '<i class="far fa-star" alt="('.number_format($apps[$soft]['ratings'], 2).' out of 5)"></i>';
}
$r = $r - $deduct;
}
if($soft > 10000){
if(file_exists($globals['euthemes'].'/'.$globals['theme_folder'].'/images/topscripts/48/'.$iscripts[$soft]['softname'].'.png')){
$custom_48 = $theme['images'].'topscripts/48/'.$iscripts[$soft]['softname'].'.png';
}else{
$custom_48 = $theme['images'].'/custom.png';
}
}
$spaceremain = 'Unlimited';
echo '
<div class="row">
<div class="col-12 col-md-10">
<div class="soft-smbox p-3">
<div id="currentrating" style="display:none"></div>
<div class="sai_main_head mb-2" style="font-size:14px;">
<div class="row">
<div class="col-5">
'.(!empty($custom_48) ? '<img src="'.$custom_48.'" alt="" class="img_size">' : '<img src="'.$globals['mirror_images'].'webuzo/softimages/'.$soft.'__logo.gif" class="img_size">').'
<span class="ms-2">'.$software['name'].'</span>
<span class="vl apps_rate ratings ms-2" title="'.$apps[$soft]['ratings'].'">'.implode('', $ratings).'</span></div>
<div class="col-4 div_divider align-items-center">
<span class="ms-2">'.(!empty($info['release_date']) ? __('Release Date').' :'.$info['release_date'] : '').'</span>
</div>
<div class="col-3 div_divider">
<span>'.__('Version').' :'.(!empty($info['version']) ? $info['version'] : 'NA').'</span>
</div>
</div>
</div>
</div>
<div class="col-12 soft-smbox p-3 mt-4">
<div class="old_tab">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="sai_tabs">
<tr>
<td><a href="javascript:tabs.tab(\'overview\', 1)" class="sai_insbut" id="overview">'.__('Overview').'</a></td>
<td><a href="javascript:tabs.tab(\'notes\', 1)" class="sai_tabbed" id="notes">'.__('Notes').'</a></td>
<td><a href="javascript:tabs.tab(\'features\', 1)" class="tab" id="features">'.__('Features').'</a></td>
'.(empty($globals['off_rating_link']) && $soft < 10000 ? '<td><a href="javascript:tabs.tab(\'ratings\', 1)" class="tab" id="ratings">'.__('Ratings').'</a></td>' : '').'
'.(empty($globals['off_review_link']) && $soft < 10000 ? '<td><a href="javascript:tabs.tab(\'reviews\', 1)" class="tab" id="reviews">'.__('Reviews').'</a></td>' : '').'
</tr>
</table><br />
</div>
<div class="new_tab">
<nav class="navbar navbar-default">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<ul class="nav">
<li class="active" style="width:80px;"><a href="javascript:tabs.tab(\'overview\')" id="overview" class="sai_tab2" style="text-decoration:none; height:40px; top:5px;" data-toggle="collapse" data-target="#myNavbar">'.__('Overview').'</a></li>					</ul>
</div>
<div class="collapse navbar-collapse" id="myNavbar" style="position:absolute; z-index:1000; width:100%; background:#F8F8F8;">
<ul class="nav navbar-nav">
<li><a href="javascript:tabs.tab(\'notes\' 1)"  id="overview" class="sai_tab2" style="text-decoration:none;" data-toggle="collapse" data-target="#myNavbar">'.__('Notes').'</a></li>
<li><a href="javascript:tabs.tab(\'features\', 1)"  id="features" class="sai_tab2" style="text-decoration:none;" data-toggle="collapse" data-target="#myNavbar">'.__('Features').'</a></li>
<li><a href="javascript:tabs.tab(\'ratings\', 1)" class="sai_tab2" id="ratings" style="text-decoration:none;" data-toggle="collapse" data-target="#myNavbar">'.__('Ratings').'</a></li>
<li><a href="javascript:tabs.tab(\'reviews\', 1)" class="sai_tab2" id="reviews" style="text-decoration:none;" data-toggle="collapse" data-target="#myNavbar">'.__('Reviews').'</a></li>
</ul>
</div>
</nav>
</div>';
$info['overview'] = '<div id="fadeout_div">'.(!empty($info['changelog']) ? '<div id="changelog_div" class="sai_popup" style="display:none;"><span class="sai_clogbutton b-close"><span>X</span></span><div class="sai_changelog">'.$info['changelog'].'</div></div>' : '').'
<div class="row p-4">
'.(!empty($scripts[$soft]['screenshots']) && empty($globals['panel_hf']) ? '
<div class="col-sm-6">
<div id="overview_img"> <img src="'.$globals['mirror_images'].'softimages/screenshots/'.$soft.'_screenshot1.gif" class="img-responsive" alt="" > </div>
</div>': '').'
<div class="'.(!empty($scripts[$soft]['screenshots']) && empty($globals['panel_hf']) ? 'col-sm-6' : 'col-sm-12').' col-xs-12">
'.softparse($info['overview'], $soft).'<br /><br />
<div class="row mt-5">
<div class="board_box col-sm-1 col-xs-1 my-1"><font color="#447edf"><i class="fa-solid fa-floppy-disk fa-3x"></i></font></div>
<div class="col-sm-5 col-xs-11 d-flex">
<div class="m-auto mx-0">
<label class="sai_head">'.__('Space Required').'</label><br />
<span class="sai_exp2">'.__('Available Space').' : '.(is_numeric($spaceremain) ? number_format($spaceremain/1024/1024, 2) : $spaceremain).' '.__('MB').'<br />
'.__('Required Space').' : '.number_format($info['space']/1024/1024, 2).' '.__('MB').'</span>
</div>
</div>
<div class="board_box col-sm-1 col-xs-1 my-1"><a href="'.$info['support'].'"><font color="#447edf"><i class="fas fa-question-circle fa-3x"></i></font></a></div>
<div class="col-sm-5 col-xs-11 d-flex">
<div class="m-auto mx-0">
<label class="sai_head">'.__('Software Support').'</label><br />
<span class="sai_exp2"><a href="'.$info['support'].'" target="_blank">'.__('Visit Support Site').'</a><br />
'.__('Note: Softaculous does not provide support for any software.').'</span>
</div>
</div>
</div><br/><br/>
</div>
</div>';
if(!empty($taskID)){
$task_alert = '<div class="alert alert-warning alert-dismissible fade show" role="alert">
<a href="javascript:loadlogs('.$taskID.')" style="font-size: 1.2rem;">'.__('Click here to check the logs').'</a>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
}
if(!empty($done['installed'])){
$info['overview'] = $task_alert.'<h3>'.__('Congratulations, the software was installed successfully').'</h3><br />
'.$software['name'].' '.__('has been successfully installed.').'<br />
'.__('We hope the installation process was easy.').'<br /><br />
'.(!empty($notes) ? __('The following are some important notes. It is highly recommended that you read them ').' : <br />
<div class="sai_notes">'.softparse($notes, $soft, 1).'</div><br /><br />' : '').'
'.__('$0 NOTE $1', ['<b>', '</b>']).' : '.APP.' '.__('is just an automatic software installer and does not provide any support for the individual software packages. Please visit the software vendor\'s web site for support!').'<br /><br />
'.__('Regards').',<br />
'.APP.' - '.__(' Auto Installer').'<br /><br />
<center><b><a href="'.app_link($soft).'">'.__('Return to Overview').'</a></b></center><!--PROC_DONE--><br /><br />';
if(empty($globals['install_tweet_off']) || empty($globals['lictype'])){
$info['overview'] .= '<form method="get" action="http://twitter.com/intent/tweet" id="tweet" onsubmit="return dotweet(this);">
<div class="panel panel-primary" style="width:50%; margin:0 auto;">
<div class="panel-heading text-center" style="padding:5px;background-color:#4B77BE;border-color:#4B77BE;border-radius:0px;">
<font size="3"><b>'.__('Tell your friends about your new application ').$software['name'].'</b></font>
</div>
<div class="panel-body">
<div class="row">
<div class="col-sm-12 text-center">
<textarea name="text" style="resize:none;width:100%;padding:5px;">'.loadtweetdata('install_tweet', __('I just installed #[[SCRIPTNAME]] via #[[APP]] #[[TYPE]]')).'</textarea><br>
<input type="submit"  value="Tweet!" class="btn btn-primary" onsubmit="return false;" id="twitter-btn" style="margin-top:10px;padding: 7px 14px;" >
</div>
</div>
</div>
</div>
</form>';
}
}elseif(!empty($done['removed'])){
$info['overview'] = $task_alert.'<br /><br /><br /><div class="sai_success"><img src="'.$theme['images'].'notice.gif" /> &nbsp; '.__('The Application was removed successfully').'</div><!--PROC_DONE--><br /><br />
<center><b><a href="'.app_link($soft).'">'.__('Return to Overview').'</a></b></center>';
}elseif(!empty($done['edited'])){
$info['overview'] = '<div class="sai_success"><img src="'.$theme['images'].'notice.gif" /> &nbsp; '.__('The settings were saved successfully').'</div><!--PROC_DONE--><br /><br />
<center><b><a href="'.app_link($soft).'">'.__('Return to Overview').'</a></b></center>';
}else{
$info['overview'] .= $task_alert;
if(empty($globals['lictype']) && !empty($apps[$soft]['force_apps'])){
$info['overview'] .= '<center class="sai_anotice">'.__('$0 $1 $2 cannot be installed in the Free version of Webuzo. Please upgrade to the premium version of Webuzo!', array('<b>', $apps[$soft]['name'], '</b>')).'</center><br />';
}
$info['overview'] .= '<br />
<form accept-charset="'.$globals['charset'].'" name="installsoftware" method="post" action="" onsubmit="return checkform();" id="installsoftware">
'.error_handle($error, "100%", 0, 1).'
<p align="center">';
$ext_disp = '';
if(!empty($settings)){
if(!empty($settings['Default'])){
$ins_display = 1;
}
if(!empty($ins_display) || !empty($rem_display)){
foreach($settings as $group => $sets){
if($group == 'hidden' || empty($sets)) continue;
$ext_disp .= '<br />
<div class="bg">
<div class="row">
<div class="col-sm-12 mb-3">
<div class="sai_sub_head">'.$group.'</div>
</div>
</div>';
foreach($sets as $sk => $sv){
$ext_disp .= '<div class="row">
<div class="col-sm-5">
<label class="sai_head">'.$sv['head'].'</label>
'.(empty($sv['exp']) ? '' : '<br />
<span class="sai_exp2">'.$sv['exp'].'</span>').'
</div>
<div class="col-sm-7">
'.$sv['tag'].'
</div>
</div><br/>';
}
$ext_disp .= '</div><br /><!--end of bg class-->';
}
}
}//End of if($settings)
if(!empty($info['auto_upgrade'])){
$auto_upgrade = (empty($iapps[$soft.'_1']) ? $info['auto_upgrade'] : $iapps[$soft.'_1']['auto_upgrade']);
$ext_disp .= '
<div class="bg">
<div class="row">
<div class="col-sm-12 mb-3">
<div class="sai_sub_head">'.__('Advanced Settings').'</div>
</div>
</div>
<div class="row">
<div class="col-5 mb-3">
<label class="sai_head mb-2">'.__('Auto Upgrade').'</label><br>
<span class="sai_exp2">'.__('Select the automatic upgrade preference for this application when a new version is available').'</span><br />
</div>
<div class="col-7 mb-3">
<input class="mr-1" type="radio" name="auto_upgrade" id="auto_upgrade_0" value="0" '.POSTradio('auto_upgrade', 0, empty($auto_upgrade) ? 0 : $auto_upgrade).' /> <label for="auto_upgrade_0" class="radio-title mb-1">'.__('Do not Auto Upgrade').'</label> <br />
<input class="mr-1" type="radio" name="auto_upgrade" id="auto_upgrade_2" value="2" '.POSTradio('auto_upgrade', 2, $auto_upgrade).' /> <label for="auto_upgrade_2" class="radio-title mb-1"> '.__('Upgrade to <b>Minor</b> versions only').'</label> <br />
<input class="mr-1" type="radio" name="auto_upgrade" id="auto_upgrade_1" value="1" '.POSTradio('auto_upgrade', 1, $auto_upgrade).' /> <label for="auto_upgrade_1" class="radio-title mb-1">'.__('Upgrade to any latest version available (<b>Major</b> as well as <b>Minor</b>)').'</label><br />
</div>
</div>
</div>';
}
if(empty($iapps[$soft.'_1'])) {
if(!empty($ext_disp)){
$info['overview'] .= $ext_disp;
}
if(($apps[$soft]['ins'] > 0 && empty($globals['lictype'])) || !empty($globals['lictype'])){
$info['overview'] .= '<center><input type="hidden" align="" name="install" id="softsubmit" value="install" />
<input type="submit" name="softsubmitbut" id="softsubmitbut" value="'.__('Install').'" class="btn btn-primary" /></center>';
}else{
$info['overview'] .='<center><div class="alert alert-danger">'.__('$0 $1 $2 cannot be installed in the Free version of Webuzo. Please upgrade to the premium version of Webuzo!', array('<b>', $apps[$soft]['name'], '</b>')).'</div></center>';
}
}else{
foreach($iapps as $k => $v){
if(empty($v['deps'])) continue;
if(in_array($apps[$soft]['softname'], $v['deps'])){
$rem_display = 1;
}
}
if(!empty($rem_display)){
$info['overview'] .= '';
}
$info['overview'] .= '<center><input type="hidden" name="" id="softsubmit" value="" />
<input type="submit" name="softsubmitbut" id="softsubmitbut" onclick="return confirm_remove();" value="'.__('Remove').'" class="btn btn-danger m-2" />
<input type="submit" name="reins_softsubmitbut" id="reins_softsubmitbut" onclick="return confirm_reinstall();" value="'.__('Reinstall').'" class="btn btn-primary" /></center>';
if(!empty($ext_disp)){
$info['overview'] .= '<br><br><br>
<div class="soft-smbox p-3">
<center><h3>'.__('Edit Settings').'</h3></center>
'.$ext_disp.'
<center>
<input type="submit" name="softsubmitbut" id="softsubmitbut" onclick="return set_edit_form();" value="'.__('Edit').'" class="btn btn-primary" />
</center>
</div>';
}
}
$info['overview'] .= '<span id="show_txt" style="display:none;"></span>
<input type="hidden" name="soft_status_key" id="soft_status_key" value="'.POSTval('soft_status_key', generateRandStr(32)).'" />
</p>
</form>
</div><br />
<div id="progress_bar" style="height:125px; display: none;">
<div class="alert alert-warning alert-dismissible fade show" id="progress_task_alert" role="alert" style="display:none;">
<a href="" style="font-size: 1.2rem;">'.__('Click here to check the logs').'</a>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<br />
<center>
<font size="4" color="#222222" id="progress_txt">'.__('Performing Initial Steps').'</font>
<font style="font-size: 18px;font-weight: 400;color: #444444;" id="progress_percent">(0 %)</font><br /><br />
</center>
<table width="500" cellpadding="0" cellspacing="0" id="table_progress" border="0" align="center" height="28" style="border:1px solid #CCC; -moz-border-radius: 5px;
-webkit-border-radius: 5px; border-radius: 2px;background-color:#efefef;">
<tr>
<td id="progress_color" width="1" style="background-image: url('.$theme['images'].'bar.gif); -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px;"></td>
<td id="progress_nocolor">&nbsp;</td>
</tr>
</table>
<br /><center>'.__('$0 NOTE : $1 This may take 3-4 minutes. Please do not leave this page until the progress bar reaches 100%', ['<b>', '</b>']).'</center>
</div>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
function confirm_remove() {
}
$("#softsubmit").attr("name", "remove");
$("#softsubmit").val("remove");
return true;
}
function confirm_reinstall() {
}
$("#softsubmit").attr("name", "reinstall");
$("#softsubmit").val("reinstall");
return true;
}
function set_edit_form() {
}
function checkform() {
}
}catch(e){
}
$_("softsubmitbut").disabled = true;
if(!get_package()){
return false;
}
if(useprog){
progressbar.start();
return false;
}else{
if($_("softsubmit").value == "remove"){
show_msg("'.__js('Removing Application').'");
}else{
if(dosubmit == 1){
$_("installsoftware").submit();
}
show_msg("'.__js('Installing Application').'");
}
}
return true;
};
var progressbar = {
timer: 0,
total_width: 0,
status_key: "",
synctimer: 0,
fadeout_div: "#fadeout_div",
win_div: "#overview_win",
progress_div: "#progress_bar",
formid: "#installsoftware",
frequency: 3000,
current: function(){
try{
var tmp_cur = Math.round(parseInt($_("progress_color").width)/parseInt($_("table_progress").width)*100);
if(tmp_cur > 100){
tmp_cur = 99;
}
return tmp_cur;
}catch(e){
return -1;
}
},
reset: function(){ try{
clearTimeout(this.timer);
$_("progress_color").width = 1;
}catch(e){ }},
move: function(dest, speed, todo){ try{
var cur = this.current();
if(cur < 0){
clearTimeout(this.timer);
return false;
}
var cent = cur + 1;
var new_width = cent/100*this.total_width;
if(new_width < 1){
new_width = 1;
}
$_("progress_color").width = new_width;
$_("progress_percent").innerHTML = "("+cent+" %)";
if(cent < dest){
this.timer = setTimeout("progressbar.move("+dest+", "+speed+")", speed);
}else{
eval(todo);
}
}catch(e){ }},
text: function(txt){ try{
$("#progress_txt").html(txt);
}catch(e){ }},
sync: function(){
if(progressbar.status_key.length < 2){
return false;
}
$.ajax({
url: window.location+"&ajaxstatus="+progressbar.status_key+"&random="+Math.random(),
type: "GET",
success: function(data){
if(data == 0) return false;
var tmp = data.split("|");
var taskID = tmp[2];
var cur = progressbar.current();
tmp[2] = (3000/(tmp[0]-cur));
if(!empty(taskID)){
$("#progress_task_alert").find("a").attr("href", "javascript:loadlogs("+taskID+")");
$("#progress_task_alert").show();
}else{
$("#progress_task_alert").hide();
}
if(tmp[0] > cur){
if(parseInt(tmp[2]) == 0){
tmp[2] = 800;
}
progressbar.move(tmp[0], tmp[2]);
}
progressbar.text(tmp[1]);
progressbar.synctimer = setTimeout("progressbar.sync()", progressbar.frequency);
}
});
},
sync_abort: function(){
clearTimeout(this.synctimer);
},
start: function(){ try{
this.post();
this.reset();
this.total_width = parseInt($_("table_progress").width);
this.move(95, 800);
this.status_key = $("#soft_status_key").attr("value");
this.sync();
}catch(e){ }},
post: function(){
$("body").animate({ scrollTop: 0 }, "slow");
$(progressbar.fadeout_div).fadeOut(500,
function(){
$(progressbar.progress_div).fadeOut(1);
$(progressbar.progress_div).fadeIn(500);
}
);
try{
var sid = $_("softbranch").value;
}catch(e){
var sid = '.$soft.'
}
var action = $("#softsubmitbut").val();
$.ajax({
url: window.location+"&jsnohf=1&soft="+sid+"&multi_ver=1",
type: "POST",
data: $(progressbar.formid).serialize(),
complete: function( jqXHR, status, responseText ) {
progressbar.sync_abort();
responseText = jqXHR.responseText;
try{
if(responseText.match(/\<\!\-\-PROC_DONE\-\-\>/gi)){
if(action == "Install"){
progressbar.text("'.addslashes(__js('Finishing Installation')).'");
}else{
progressbar.text("'.addslashes(__js('Removed Successfully')).'");
}
progressbar.move(99, 10, "$(progressbar.progress_div).fadeOut(1)");
}else{
progressbar.reset();
}
}catch(e){ }
if ( jqXHR.state() == "resolved" ) {
jqXHR.done(function( r ) {
responseText = r;
});
var newhtml = jQuery("<div>").append(responseText).find(progressbar.win_div).html();
$(progressbar.win_div).animate({opacity: 0}, 1000, "", function(){
$(progressbar.win_div).html(newhtml);
}).delay(50).animate({opacity: 1}, 500);
}else{
alert("Oops ... the connection was lost");
}
}
});
}
};
function show_msg() {
}
var nopackage = '.(empty($nopackage) ? 0 : 1).';
var useprog = 1;
function get_package() {
}catch(e){ }
return false;
}else{
$_("show_txt").style.display = "none";
return true;
}
};
function get_package_handle() {
};
</script>
';
}
$info['features'] = softparse($info['features'], $soft, 1);
$info['ratings'] = '';
$info['reviews'] = '<div id="allreviews"></div>';
foreach($info as $k => $v){
if(in_array($k, array('demo', 'support', 'import', 'install'))) continue;
echo '<div id="'.$k.'_win" style="display: '.($init_tab == $k ? "block" : "none").';">
'.$v.'
</div>';
}
echo '<br /><br />
</div>
</div><!--end of bg class-->
<div class="col-12 col-md-2 px-0">';
if(function_exists('dropdown_app_list')){
dropdown_app_list();
}
echo'
</div>
</div>';
softfooter();
}
?>