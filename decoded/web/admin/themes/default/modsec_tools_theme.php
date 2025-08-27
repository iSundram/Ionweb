<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function modsec_tools_theme() {
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
<div class="col-md-6">
<h5>'.__('Hits List').'</h5>
</div>
<div class="col-md-6">
<a class="btn btn-primary float-end" href="'.$globals['ind'].'act=modsec_rules">'.__('Rules List').'</a>
</div>
</div>';
page_links();
if(!empty($modsec_conf_notice)){
echo '<div class="alert alert-warning text-center">
<img src="'.$theme['images'].'notice.gif" />'.__(' Note: $2 are set to DetectionOnly mode. ModSecurity will NOT block requests that match the configured rules but will only log them. You can change this preference from $0 ModSecurity Configuration $1', ['<a href="'.$globals['index'].'act=modsec_conf">', '</a>', $modsec_conf_notice['engine']]).'</span>
</div>';
}
echo '
<div class="table-responsive mt-4">
<table class="table sai_form webuzo-table">
<thead>
<tr>
<th>'.__('Date').'</th>
<th>'.__('Host').'</th>
<th>'.__('Source').'</th>
<th>'.__('Severity').'</th>
<th>'.__('Status').'</th>
<th colspan="2">'.__('Rule ID').'</th>
</tr>
</thead>
<tbody>';
if(empty($hitlist)){
echo '
<tr>
<td colspan="100" class="text-center">
<span>'.__('No Data Found').'</span>
</td>
</<tr>';
}else{
foreach($hitlist as $key => $value){
echo '
<tr>
<td>'.date('Y-m-d h:i:s', $value['timestamp']).'</td>
<td>'.$value['host'].'</td>
<td>'.$value['ip'].'</td>
<td>'.$value['meta_severity'].'</td>
<td>'.$value['http_status'].'</td>
<td width="25%">
<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#edit_rules" data-id="'.$value['meta_id'].'" data-filename="'.$value['meta_file'].'" onclick="edit_rule(this)">'.$value['meta_id'].': '.$value['meta_msg'].'</a>
</td>
<td class="pe-auto">
<a href="javascript:void(0);" id="td_'.$value['id'].'" data-bs-toggle="collapse" data-bs-target="#tr_'.$value['id'].'" aria-expanded="false" aria-controls="tr_'.$value['id'].'">'.__('More Info').'
</a>
</td>
</tr>
<tr class="table-info pt-0 mt-0">
<td colspan="100" class="p-0">
<div class="accordian-body collapse" id="tr_'.$value['id'].'">
<div class="p-2">
<b>Request:</b> '.$value['http_method'].' '.$value['path'].'</br>
<b>Action Description:</b> '.$value['action_desc'].'</br>
<b>Justification:</b> '.htmlentities($value['justification'], ENT_NOQUOTES | ENT_SUBSTITUTE | ENT_HTML401).'
</div>
</div>
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
<div class="modal fade" id="edit_rules" tabindex="-1" aria-labelledby="edit_rules" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title">'.__('Edit Rule').'</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="alert alert-warning p-1 mb-0" role="alert">'.__('You cannot edit vendor rules. You can enable or disable this rule.').'</div>
<div class="modal-body">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="modsec_hitlist" id="editform" class="form-horizontal">
<div id="rule_id_div">
<label class="form-label me-2">'.__('Original ID').' </label>
<input type="text" id="rule_id" name="rule_id" class="form-control mb-3" value="" readonly/>
</div>
<div>
<label class="form-label me-2">'.__('Rule Text').'</label>
<textarea class="form-control mb-3" id="rule_text" name="rule_text" rows="10" cols="50" readonly></textarea>
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
function edit_rule() {
};
submitit(d,{
handle:function(data, p){
$("#rule_text").val(data.config.rule_text);
if(data.config.active === 0){
$("#rule_status").attr("checked", true);
}
$("#config_file").val(data.config.filename);
}
});
}
$(document).ready(function () {
$(document).click(function () {
$(".accordian-body ").collapse("hide");
});
show_search();
});
function show_search() {
}else{
$("#searchbox").show();
$(".date").hide();
}
};
</script>';
softfooter();
}