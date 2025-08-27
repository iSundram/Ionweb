<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function manage_wheel_theme() {
}
if(in_array($val, $wheel_users)){
continue;
}
echo '<option value="'.$val.'">'.$val.'</option>';
}
echo'
</select>
</div>
</div>
</div>
<div class="col-md-2 col-sm-2" style="margin-top: 5.5rem;margin-bottom: 5.5rem;">
<div class="text-center mb-3">
<button class="btn btn-primary w-100" id="add_group" name="add_group" data-action="add" onclick="toggleAction(this)">'.__('Add').'<i class="fas fa-angle-double-right ps-2"></i></buuton>
</div>
<div class="text-center">
<button class="btn btn-primary w-100" id="remove_group" name="remove_group" data-action="remove" onclick="toggleAction(this)"><i class="fas fa-angle-double-left pe-2"></i>'.__('Remove').'</buuton>
</div>
</div>
<div class="col-md-5 col-sm-5 mb-4">
<div class="soft-smbox mb-3">
<div class="sai_form_head">'.__('List wheel group users').'</div>
<div class="sai_form p-3">
<select name="remove_grp" id="remove_grp" class="form-select form-unlimited" size="8" id="">';
foreach($wheel_users as $k => $v){
if(!empty($v)){
echo'<option value="'.$v.'">'.$v.'</option>';
}
}
echo'
</select>
</div>
</div>
</div>
</div>
<script>
$(document).ready(function(){
$("#add_grp").change(function(){
$("#remove_group").attr("disabled", true);
$("#add_group").attr("disabled", false);
$("#remove_grp").val("");
});
$("#remove_grp").change(function(){
$("#remove_group").attr("disabled", false);
$("#add_group").attr("disabled", true);
$("#add_grp").val("");
});
});
function toggleAction() {
}else{
user = $("#remove_grp option:selected").val();
}
var d = {action:action, user:user}
submitit(d, {
done_reload : window.location.href
});
}
</script>';
softfooter();
}