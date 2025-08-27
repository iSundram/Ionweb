<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function eu_tasklist_theme() {
}
if(!empty($array)){
echo json_encode($array);
return true;
}
echo 0;
return false;
}
softheader(__('Task List'));
$_key = array();
echo '
<div class="bg"><br />
<div class="row sai_main_head align-items-center" style="width:100%;" align="center">
<div class="col-sm-6 col-xs-5" style="padding:0 10px 0 0; text-align:right;">
<i class="fas fa-th-list" style="color:#00A0D2;"></i>
</div>
<div class="col-sm-6 col-xs-7" style="padding-left:0; text-align:left;">'.__('Task List').'</div>
</div>
<hr>
<div class="row">
<div class="sai_sub_head" align="center">'.(!empty($no_tasks) ? __('There are no tasks at the moment.') : __('Following are your active tasks. (Past 1 hour)')).'</div><br /><br />
</div>
<div id="main_div" >';
foreach($tasks_file as $k => $v){
$_key[$v['name']] = $v['name'];
echo '<div  id="body_progressbar'.$v['name'].'">
<div class="row">
<div class="col-sm-6">
<label class="sai_head" id="progress_script'.$v['name'].'"></label><br />
<label class="sai_head" id="progress_softurl'.$v['name'].'"></label><br />
</div>
<div class="col-sm-5">
<div id="progressbar'.$v['name'].'" style="height:25px;"></div><br />
<div class="row">
<div class="col-sm-8 sai_head" id="progress_process'.$v['name'].'"></div>
<div class="col-sm-8 sai_head" id="progress_txt'.$v['name'].'"></div>
<div class="col-sm-4 sai_head" id="progress_percent'.$v['name'].'"></div>
</div>
</div>
<div class="col-sm-1" style="margin-top:10px;">
<img src="'.$theme['images'].'admin/softok.gif" style="display:none" id="suucess_img'.$v['name'].'"/>
</div>
</div>
</div>
<hr>';
}
echo '</div>
</div>';
echo '<script language="javascript" src="'.$theme['url'].'/js/jquery.ui.widget.min.js" type="text/javascript"></script>
<script language="javascript" src="'.$theme['url'].'/js/ui.progressbar.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
var set = 0;
var refresh_period = 3000; // ms
var script_path = window.location;
ids = ["'.implode('", "', array_keys($_key)).'"];
for(x in ids){
$("#progressbar"+ids[x]).progressbar();
$("#progressbar"+ids[x]).progressbar( "option", "value", 0.1 );
$("#progressbar"+ids[x]).css("height", "25px");
}
(function( $ ) {
$.fn.animate_progressbar = function(value,duration,easing,complete) {
try{
if (value == null)value = 0;
if (duration == null)duration = 2000;
if (easing == null)easing = "swing";
if (complete == null)complete = function(){};
var progress = this.find(".ui-progressbar-value").css("height","25px");
progress.stop(true).animate({
width: value + "%"
},duration,easing,function(){
if(value >= 99.5){
progress.addClass("ui-corner-right");
} else {
progress.removeClass("ui-corner-right");
}
complete();
});
}catch(e){
}
}
})( jQuery );
function create_new_div() {
}
$(document).ready(function(){
set = 0;
updateProgressBar();
});
function in_array() {
}
}
}
return false;
}
function updateProgressBar() {
}
}
});
setTimeout("updateProgressBar()", 3000);
return;
}
setTimeout("updateProgressBar()", 3000);
$("#task_heading").html("'.__js('Following are your active tasks. (Past 1 hour)').'");
var real_ids = [];
var prog_id = "";
$.each(data, function (key, item){
if($("#body_progressbar"+key).length == 0){
create_new_div(key);
}
real_ids.push(key);
});
$("#main_div").find("div").each(function(){
prog_id = $(this).attr("id");
if(prog_id){
if(prog_id.match(/body_progressbar/gi)){
prog_id = prog_id.replace("body_progressbar", "");
var found = 0;
for(var i = 0; i <= real_ids.length; i++ ){
if(real_ids[i] == prog_id){
found = 1;
}
}
if(!found){
$("#body_progressbar"+prog_id).hide();
$("#body_progressbar"+prog_id).next("hr").remove();
}
}
}
});
$.each(data, function (key, item){
if(set == 0){
$("#progressbar"+key).progressbar();
$("#progressbar"+key).progressbar( "option", "value", 0.1 );
set = 1;
}
var cur_prog = data[key][0].split("|");
var prog_data = data[key][1];
if(cur_prog[0] == 0){
if($("#body_progressbar"+key).is(":visible")){
$("#body_progressbar"+key).hide();
$("#body_progressbar"+key).next("hr").remove();
}
}
if(!in_array(key)){
$("#body_progressbar"+key).remove();
}
$("#progress_percent"+key).html("<font size=\"2\" color=\"#222222\"> ("+cur_prog[0]+" %)</font>");
if(prog_data.version){
$("#progress_script"+key).html(prog_data.name +" "+"("+prog_data.version+")");
}else{
$("#progress_script"+key).html(prog_data.name);
}
$("#progress_process"+key).html(prog_data.process+" "+prog_data.name+"<br>");
if(typeof prog_data.softurl != "undefined"){
$("#progress_softurl"+key).html("<a href=\'"+prog_data.softurl+"\' target=\"_blank\">"+prog_data.softurl+"</a>");
}
if(typeof prog_data.softpath != "undefined"){
$("#progress_softurl"+key).html(prog_data.softpath);
}
if(cur_prog[0] == 100){
$("#progress_process"+key).html("");
$("#suucess_img"+key).css("display", "block");
$("#progressbar"+key).fadeOut(100);
$("#progress_txt"+key).html(prog_data.completed);
}else{
$("#progress_txt"+key).html(prog_data.current_status);
}
$("#progressbar"+key).animate_progressbar(cur_prog[0], 3000);
});
});
}
echo '<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
function show_msg() {
}
softfooter();
}
?>