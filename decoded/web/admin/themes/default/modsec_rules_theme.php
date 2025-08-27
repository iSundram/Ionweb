<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function modsec_rules_theme() {
}
</style>
<div class="soft-smbox p-3">
<div class="sai_main_head">
<i class="fas fa-tools me-2"></i> '.__('ModSecurity Rules').'
</div>
</div>
<div class="soft-smbox p-4 mt-4">
<form accept-charset="'.$globals['charset'].'" name="modsec_rulelist" id="modsec_rulelist" method="post" action=""; class="form-horizontal">';
$count = 0;
foreach($vendor_info as $key => $val){
if(empty($val["installed"])){
continue;
}
$count = 1;
}
if(empty($count)){
echo '
<div class="alert alert-danger d-flex justify-content-center"" role="alert">
'.__('There is no vendor installed.').'&nbsp; &nbsp;
<a href="'.$globals['ind'].'act=modsec_vendors">'.__('Vendor Manager').'</a>
</div>';
}else{
echo '
<div class="row">
<div class="col-md-8">
<h5>'.__('Rules List').'</h5>
</div>
<div class="col-md-4">
<div class="float-end">
<a class="btn btn-light" href="'.$globals['ind'].'act=modsec_tools">'.__('Hits List').'</a>
<a class="btn btn-primary" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_rules" data-bs-action="Add"  data-bs-btnval="add_rule" id="add_rule_btn">'.__('Add Rule').'</a>
</div>
</div>
</div>';
page_links();
echo '
<div class="table-responsive mt-4">
<table class="table sai_form webuzo-table">
<thead>
<tr>
<th>'.__('Status').'</th>
<th>'.__('Vendor').'</th>
<th>'.__('ID').'</th>
<th>'.__('Message').'</th>
<th>'.__('Action').'</th>
</tr>
</thead>
<tbody>';
if(empty($rulelist)){
echo '
<tr>
<td colspan="100" class="text-center">
<span>'.__('No Data Found').'</span>
</td>
</<tr>';
}else{
foreach($rulelist as $key => $val){
echo '
<tr>
<td class="border-bottom-0"><span class="badge bg-'.(empty($modsec_conf['disable_ids'][$val['rule_id']]) ? 'success' : 'danger').'">'.(!empty($modsec_conf['disable_ids'][$val['rule_id']]) ? __('Disabled') : __('Enabled')).'</span></td>
<td class="border-bottom-0"><span class="badge bg-'.(($val['vendor']=='Custom') ? 'warning text-dark': 'primary').'">'.$val['vendor'].'</span></td>
<td class="border-bottom-0">'.$val['rule_id'].'</td>
<td class="border-bottom-0">'.$val['message'].'</td>
</tr>
<tr>
<td colspan="4"><div class="wordwrap">'.$val['rule_text'].'</div></td>
<td class="p-0" width="7%">
<br>
<a href="javascript:void(0)" data-id="'.$val['rule_id'].'" data-status="'.(!empty($modsec_conf['disable_ids'][$val['rule_id']]) ? 1 : 0).'" onclick="disable_rule(this)">'.(!empty($modsec_conf['disable_ids'][$val['rule_id']]) ? '<i class="fas fa-check text-success"></i>&nbsp'.__('Enable') : '<i class="fas fa-ban text-danger"></i>&nbsp'.__('Disable')).'</a><br><br>';
if($val['vendor'] == 'Custom'){
echo '
<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_rules" data-bs-action="Edit" data-bs-id="'.$val['rule_id'].'" data-bs-ruletext="'.htmlspecialchars($val['rule_text']).'" data-bs-status="'.(!empty($modsec_conf['disable_ids'][$val['rule_id']]) ? 1 : 0).'" data-bs-btnval="edit_rule" id="edit_rule_btn'.$val['rule_id'].'"><i class="fas fa-copy"></i>&nbsp'.__('Edit').'</a><br><br>
<a href="javascript:void(0)" data-id="'.$val['rule_id'].'" data-rule_text="'.htmlspecialchars($val['rule_text']).'" onclick="delete_rule(this)"><i class="fas fa-trash text-danger"></i>&nbsp'.__('Delete').'</a>';
}
echo '
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
</form>
</div>
<div class="modal fade" id="add_rules" tabindex="-1" aria-labelledby="add_rules" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">'.__('Add Rule').'</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="modsec_rulelist" id="editform" class="form-horizontal">
<div class="hide" id="rule_id_div">
<label class="form-label me-2">'.__('Original ID').' </label>
<input type="text" id="rule_id" name="rule_id" class="form-control mb-3" value="" />
</div>
<div>
<label class="form-label me-2">'.__('Rule Text').'</label>
<textarea class="form-control mb-3" id="rule_text" name="rule_text" rows="10" cols="50"></textarea>
</div>
<div class="form-check">
<label class="form-check-label" for="rule_status">'.__('Enable Rule').'</label>
<input class="form-check-input" type="checkbox" name="rule_status" id="rule_status">
</div>
<input type="hidden" id="config_file" name="config_file" val=""/>
<center>
<input type="submit" class="btn btn-primary me-2" value="'.__('Save').'" id="save_btn" name="edit_rule" onclick="return submitit(this)" data-donereload="1" />
<img id="createimg" src="'.$theme['images'].'progress.gif" style="display:none">
</center>
</form>
</div>
</div>
</div>
</div>
<script>
function disable_rule() {
};
submitit(d,{
done_reload: window.location
});
}
function delete_rule() {
};
submitit(d, {
done_reload : window.location
});
});
show_message(a);
}
$("#add_rules").on("show.bs.modal", function(e){
var button, jEle, opt;
if("relatedTarget" in e){
button = e.relatedTarget;
jEle = $(button);
opt = jEle.data();
$(this).attr("lastevent-ele", jEle.attr("id"));
}else{
var text = $("#rule_text").val();
var status = 0;
if($("#rule_status").is(":checked")){
status = 1;
}
opt = {bsStatus:status, bsRuletext:text}
}
if("bsAction" in opt && opt.bsAction == "Edit"){
$("#rule_id_div").show();
$("#rule_id").val(opt.bsId);
$("#rule_id").attr("readonly", true);
$(".modal-title").text("'.__js('Edit Rule').'");
if("bsStatus" in opt && "bsRuletext" in opt){
$("#rule_text").val(opt.bsRuletext);
$("#rule_status").prop("checked", opt.bsStatus ? false : true);
}
}else{
$("#rule_id_div").hide();
$(".modal-title").text("'.__js('Add Rule').'");
if("bsStatus" in opt && "bsRuletext" in opt){
$("#rule_status").prop("checked", opt.bsStatus ? true : false);
$("#rule_text").val($("#rule_text").val());
}else{
$("#rule_status").prop("checked", false);
$("#rule_text").val("");
}
}
$("#save_btn").attr("name", opt.bsBtnval);
});
</script>';
softfooter();
}