<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function cronjob_theme() {
} elseif ($i == 15) {
echo '<option value="'.$i.'">'.__('15(At quarter past the hour.)('.$i.')').'</option>';
} elseif ($i == 30) {
echo '<option value="'.$i.'">'.__('30(At half past the hour.)('.$i.')').'</option>';
} elseif ($i == 45) {
echo '<option value="'.$i.'">'.__('45(At one quarter until the hour.)('.$i.')').'</option>';
} else {
echo '<option value="'.$i.'">'.($i < 10 ? ':0'.$i : ':'.$i).'</option>';
}
}
echo
'</select>
<label class="form-label d-block" for="hour">'.__('Hour').'
<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('[0-23]').'"></i>
</label>
<input type="text" name="hour" id="hour" class="display-value d-inline-block" value="  * " />
<select id="hour_options" class="selected-value d-inline-block mb-2" onchange="select_option(\'hour\')">
<option value="---">---'.__('Common Settings').'---</option>
<option value="*">'.__('Every Hour (*)').'</option>
<option value="*/2">'.__('Every Other Hour (*/2)').'</option>
<option value="*/3">'.__('Every Third Hour (*/3)').'</option>
<option value="*/4">'.__('Every Fourth Hour (*/4)').'</option>
<option value="*/6">'.__('Every Sixth Hour (*/6)').'</option>
<option value="0,12">'.__('Every Twelve Hour (0,12)').'</option>
<option value="---">---'.__('Hour').'---</option>';
for ($i = 0; $i < 24; $i++) {
if ($i == 0) {
echo '<option value="'.$i.'">12:00 a.m. Midnight('.$i.')</option>';
} elseif ($i == 12) {
echo '<option value="'.$i.'">12:00 p.m. Noon('.$i.')</option>';
} else {
echo '<option value="'.$i.'">'.(($i < 12) ? $i : $i - 12).':00'.(($i < 12) ? ' a.m.' : ' p.m.').'('.$i.')</option>';
}
}
echo '
</select>
<label class="form-label d-block" for="day">'.__('Day').'
<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('[1-31]').'"></i>
</label>
<input type="text" name="day" id="day" class="display-value d-inline-block" value="  * " />
<select id="day_options" class="selected-value d-inline-block mb-2" onchange="select_option(\'day\')">
<option value="---">---'.__('Common Settings').'---</option>
<option value="*">'.__('Every Day (*)').'</option>
<option value="*/2">'.__('Every Other Day (*/2)').'</option>
<option value="1,15">'.__('On the 1st and 15th of the Month (1,15)').'</option>
<option value="---">---'.__('Day').'---</option>';
$ends = array('th','st','nd','rd','th','th','th','th','th','th');
for($i = 1 ; $i < 32 ; $i++){
if((($i % 100) >= 11) && (($i % 100) <= 13)){
echo '<option value="'.$i.'">'.$i.'th ('.$i.')</option>';
}
else{
echo '<option value="'.$i.'">'.$i.$ends[$i % 10].' ('.$i.')</option>';
}
}
echo '
</select>
<label class="form-label d-block" id="type" for="month">'.__('Month').'
<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('[1-12]').'"></i>
</label>
<input type="text" name="month" id="month" class="display-value d-inline-block mb-2" value="  * " />
<select id="month_options" class="selected-value d-inline-block" onchange="select_option(\'month\')">
<option value="---">---'.__('Common Settings').'---</option>
<option value="*">'.__('Every Month (*)').'</option>
<option value="*/2">'.__('Every Other Month (*/2)').'</option>
<option value="*/3">'.__('Every Third Month (*/3)').'</option>
<option value="---">---'.__('Month').'---</option>';
for($i = 1 ; $i < 13 ; $i++){
echo '<option value="'.$i.'">'.date("F", mktime(0, 0, 0, $i, 10)).' ('.$i.')</option>';
}
echo '
</select>
<label class="form-label d-block" id="type" for="weekday">'.__('Weekday').'
<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('[0-6]').'"></i>
</label>
<input type="text" name="weekday" id="weekday" class="display-value d-inline-block"  value="  * " />
<select id="weekday_options" class="selected-value d-inline-block mb-2" onchange="select_option(\'weekday\')">
<option value="---">---'.__('Common Settings').'---</option>
<option value="*">'.__('Every Day Of Week (*)').'</option>
<option value="1-5">'.__('Every Weekday (1-5)').'</option>
<option value="0,6">'.__('Every Weekend (0,6)').'</option>
<option value="1,2,3">'.__('Every Monday Tuesday Wednesday (1,2,3)').'</option>
<option value="0,2,4">'.__('Every Sunday Tuesday Thursday (0,2,4)').'</option>
<option value="---">---'.__('Weekday').'---</option>';
$days = array(__('Sunday'), __('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday'), __('Saturday'));
for($i = 0 ; $i < 7 ; $i++){
echo '<option value="'.$i.'">'.$days[$i].' ('.$i.')</option>';
}
echo '
</select>
<label class="form-label" id="type" for="cmd">'.__('Command').'</label>
<input type="text" name="cmd" id="cmd" size="60" class="form-control" placeholder="" />
<div class="text-center mt-4">
<input type="submit" value="'.__('Add Cron Job').'" name="create_record" class="flat-butt" id="create_record" />
</div>
</form>
</div>
</div>
</div>
</div>
<!--Modal end -->
<!-- Javascript code -->
<script type="text/javascript">
function updatecronfields() {
}
}
$("#common_settings").on("change", function() {
updatecronfields();
});
</script>
`
<!--Cron job table-->
<div class="sai_notice text-center mb-3">'.__('$0 Note: $1', ['<strong>', '</strong>']).' '.__('You can learn more about Cron Job $0 here $1', ['<a href="http://www.manpagez.com/man/5/crontab/" target="_blank">', '</a>']).'</div>
<div class="row py-3">
<div class="col-12 col-lg-6 d-flex">';
if(empty($globals['disable_cronupdate_email'])){
echo '
<div class="input-group input-group-sm mr-1" style="width: 60%;">
<label class="input-group-text " for="cron_email">'.__('Cron Email').'</label>
<input type="textbox" class="form-control mr-1" name="cron_email" id= "cron_email" value="'.$WE->user['cron_email'].'"><br>
</div>
<input type="button" class="btn flat-butt" id="update_email" name="update_email" style="margin-left: 15px;" value="'.__('Update Email').'"/>';
}
echo '
</div>
<div class="col-12 col-lg-6">
<input type="button" class="btn btn-danger float-end delete_selected" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_cron(this)" disabled>
</div>
</div>
<div class="alert alert-warning mb-4" style="border-radius:5px;">
<h6>'.__('PHP command examples:').'</h6>
<p>
<span>'.__('General Example:').'</span><br>
<code>/usr/bin/php /home/'.$user['user'].'/public_html/path/to/cron/script.php</code>
</p>
<p>
<span>'.__('Domain specific example:').'</span><br>
<code>/usr/local/apps/php-x/bin/php /home/'.$user['user'].'/domain_path/path/to/cron/script.php</code><br>
<span>'.__('In the above example replace "php-x" with the actual PHP version assigned to the domain. You can refer to the ').'<a href="'.$globals['index'].'act=multi_php">'.__('MultiPHP Manager').'</a>'.__(' to determine the specific PHP version assigned to a domain.').'</span>
</p>
</div>
<div id="showrectab" class="table-responsive">';
showcron();
echo '
</div>
</div>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
function select_option() {
}
}
$("#update_email").click(function(){
var a;
email = $("#cron_email").val();
if(empty(email)){
var lang = "'.__js('Are you sure you want to disable cron update on email').'";
}else{
var lang = "'.__js('Are you sure you want to change cron email').'";
}
a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
var data = {"update_cron_email" : 1 , "email" : email};
submitit(data,{
done_reload : window.location.href
});
});
show_message(a);
});
softfooter();
}
function showcron() {
}else{
foreach ($read_cron as $key => $value){
echo '
<tr id="trc'.$key.'">
<td><input type="checkbox" class="check_cron" name="check_cron" value="'.$key.'"></td>
<td>
<span id="minc'.$key.'">'.$read_cron[$key]['min'].'</span>
<input id="min_entryc'.$key.'" name="minute" style="display:none;" value="'.$read_cron[$key]['min'].'" size="2" >
<input type="hidden" name="edit_record" value="'.$key.'" />
</td>
<td>
<span id="houc'.$key.'">'.$read_cron[$key]['hou'].'</span>
<input id="hou_entryc'.$key.'" name="hour" style="display:none;" value="'.$read_cron[$key]['hou'].'" size="2" >
</td>
<td>
<span id="dayc'.$key.'">'.$read_cron[$key]['day'].'</span>
<input id="day_entryc'.$key.'" name="day" style="display:none;" value="'.$read_cron[$key]['day'].'" size="2" >
</td>
<td>
<span id="monc'.$key.'">'.$read_cron[$key]['mon'].'</span>
<input id="mon_entryc'.$key.'" name="month" style="display:none;" value="'.$read_cron[$key]['mon'].'" size="2" >
</td>
<td>
<span id="weec'.$key.'">'.$read_cron[$key]['wee'].'</span>
<input id="wee_entryc'.$key.'" name="weekday" style="display:none;" value="'.$read_cron[$key]['wee'].'" size="2" >
</td>
<td>
<code style="color:#000000;background-color:#e8e8e8"><span id="cmdc'.$key.'">'.htmlentities($read_cron[$key]['cmd']).'</span></code>
<input id="cmd_entryc'.$key.'" name="cmd" style="display:none;" value="'.htmlentities($read_cron[$key]['cmd']).'" size="30" >
</td>
<td style="width:1%;">
<i class="fas fa-undo cancel cancel-icon" title="'.__('Cancel').'" id="cid'.$key.'" style="display:none;"></i>
</td>
<td style="width:1%;">
<i class="fas fa-pencil-alt edit edit-icon" title="'.__('Edit').'" id="eid'.$key.'"></i>
</td>
<td width="2%" align="center">
<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="didc'.$key.'" onclick="delete_record(this)" data-delete_record="'.$key.'" data-delete="1"></i>
</td>
</tr>';
$i++;
}
}
echo '
</tbody>
</table>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
$(".sai_altrowstable tr").mouseover(function(){
var old_class = $(this).attr("class");
$(this).attr("class", "sai_tr_bgcolor");
$(this).mouseout(function(){
$(this).attr("class", old_class);
});
});
$(".cancel").click(function() {
var id = $(this).attr("id");
id = id.substr(3);
$("#cid"+id).hide();
$("#eid"+id).removeClass("fa-save").addClass("fa-edit");
$("#trc"+id).find("span").show();
$("#trc"+id).find("input,.input").hide();
});
$(".edit").click(function() {
var id = $(this).attr("id");
id = id.substr(3);
$("#cid"+id).show();
if($("#eid"+id).hasClass("fa-save")){
var d = $("#trc"+id).find("input, textarea, select").serialize();
submitit(d, {
done: function(){
var tr = $("#trc"+id);
tr.find(".cancel").click();// Revert showing the inputs
tr.find("input, textarea, select").each(function(){
var jE = $(this);
if(jE.attr("type") == "hidden"){
return;
}
var value = jE.val();
value = value.split("<").join("&lt;").split(">").join("&gt;");
jE.closest("td").find("span").html(value);
});
},
sm_done_onclose: function(){
$("#trc"+id).find("span").show();
$("#trc"+id).find("input,.input").hide();
},
done_reload: window.location
});
$("#trc"+id).find("span").hide();
$("#trc"+id).find("input,.input").show();
}else{
$("#eid"+id).addClass("fa-save").removeClass("fa-edit");
$("#trc"+id).find("span").hide();
$("#trc"+id).find("input,.input").show();
}
});
$(document).ready(function(){
$("#checkall").change(function(){
$(".check_cron").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function(){
if($(".check_cron:checked").length){
$("#delete_selected").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled",true);
}
});
});
function del_cron() {
});
arr.reverse();
a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete selected cronjob(s) ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
var d ={"delete_record" : arr.join(), "delete" : 1};
submitit(d, {done_reload: window.location.href});
});
show_message(a);
}
}