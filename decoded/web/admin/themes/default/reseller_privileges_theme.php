<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function reseller_privileges_theme() {
}
echo'
</div>
</div>
</div>
</div>';
if(empty($_user) || !empty($_user['reseller'])){
$rp = $_user['reseller_privileges'];
foreach($reseller_privileges_fields as $cat => $c){
echo '
<div class="soft-smbox mb-3">
<div class="sai_form_head">'.$c['name'].'</div>
<div class="sai_form">
<div class=" row">';
foreach($c['list'] as $key => $props){
if($props['type'] == 'multicheckbox' && !empty($_user['reseller']) && isset($rp[$key]) && empty($rp[$key])){
$rp[$key]['dummy'] = 1;
}
echo call_user_func_array('form_type_'.$props['type'], array($key, $props, &$rp[$key]));
}
echo '
</div>
</div>
</div>';
}
if(!empty($_user)){
echo'
<div class="soft-smbox mb-3">
<div class="sai_form_head">'.__('Nameservers').'</div>
<div class="sai_form"><br />
<div class="form-check">
<input class="form-check-input" type="radio" name="inherit_ns" id="inherit_nameservers" value="1" '.(empty($_user['reseller_privileges']['nameservers']) ? 'checked="checked"' : '').'>
<label class="form-check-label" for="inherit_nameservers">'.__('Inherit from Root').'</label></br>
<span class="sai_exp2">'.
__('Nameserver 1').' : '.$globals['WU_NS1'].' </br>'.
__('Nameserver 2').' : '.$globals['WU_NS2'].'
</span>
</div></br>
<div class="form-check">
<input class="form-check-input" type="radio" name="inherit_ns" id="custom_nameservers" value="0" '.(!empty($_user['reseller_privileges']['nameservers']) ? 'checked="checked"' : '').'>
<label class="form-check-label" for="custom_nameservers">'.__('Set Custom Nameservers').'
</label>
</div>
<div id="ns_form" style="display:none;" >
<div class="col-12 col-md-6">
<label for="mail" class="sai_head">'.__('Nameserver 1').'</label>
<input class="form-control mb-3" type="text" name="NS1" size="30" value="'.aPOSTval('NS1', $_user['reseller_privileges']['nameservers']['WU_NS1']).'" />
</div>
<div class="col-12 col-md-6">
<label for="mail" class="sai_head">'.__('Nameserver 2').'</label>
<input class="form-control mb-3" type="text" name="NS2" size="30" value="'.aPOSTval('NS2', $_user['reseller_privileges']['nameservers']['WU_NS2']).'" />
</div>
</div>
</div>
</div>';
}
echo'
<div class="text-center">
<input type="submit" class="btn btn-primary" id="save_privileges" name="save_privileges" value="'.__('Save Settings').'"/>
</div>';
}else{
echo '
<div>
<center><p class="alert alert-danger">'.__('Please select a valid user').'</p></center>
</div>';
}
echo '
</form>
</div>
<script language="javascript" type="text/javascript">
function show_custom_ns() {
}else{
$("#ns_form").hide();
}
}
function show_root_plans() {
}else{
$("[key=root_plans]").hide();
}
}
$(document).ready(function (){
show_custom_ns();
show_root_plans();
$("input[name=\"inherit_ns\"]").click(function(){
show_custom_ns();
});
$("#choose_root_plans").click(function(){
show_root_plans();
});
});
$("#user_search").on("select2:select", function(e, u = {}){
let user_name;
if("user_name" in u){
user_name = u.user_name;
}else{
user_name = $("#user_search option:selected").val();
}
window.location = "'.$globals['index'].'act=reseller_privileges&user_name="+user_name;
});
function reset_reseller() {
};
submitit(d, {done_reload : window.location.href});
});
show_message(a);
}
</script>';
softfooter();
}