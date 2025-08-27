<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function aliases_theme() {
}
if(optREQ('domid')){
$domain = optREQ('domid');
if(!empty($aliases_list[$domain])){
echo '
<script>
$(document).ready(function(){
$("#edit'.$domain.'").click();
});
</script>';
}
}
echo '
<div class="modal fade" id="add-aliases" tabindex="-1" aria-labelledby="add-aliasesLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title action-type" id="add-aliasesLabel">'.__('Add New Aliases').'</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="'.__('Close').'"></button>
</div>
<div class="modal-body p-4">
<form accept-charset="'.$globals['charset'].'" method="post" action="" role="form" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
<label for="domain" class="form-label">'.__('Aliases').'</label>
<input type="text" id="domain" name="domain" class="form-control mb-3" required value="" />
<input type="hidden" id="domain_val" name="domain_val" />
<label for="domain" class="form-label">'.__('Redirects To').'</label>
<select name="redirect_to" id="redirect_to" class="form-select mb-4">';
foreach ($domains as $k => $v)	{
echo '<option value="'.$k.'" '.((!empty($_POST['redirect_to']) && $_POST['redirect_to'] == $k) ? 'selected="selected"' : '').'>'.$k.'</option>';
}
echo '</select>
<center>
<button type="submit" id="submitdomain" name="add" value="1" class="flat-butt action-type">'.__('Add Alias').'</button>
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'.__('Close').'</button>
</center>
</form>
</div>
</div>
</div>
</div>
<div class="alert alert-warning" style="text-align:center;">
'.__('$0 NOTE : $1 Alias domain(s) is a part of parked domain(s). ', ['<strong>', '</strong>']).'
</div>
<div class="sai_sub_head record-table mb-2 position-relative" style="text-align:right;">
<input type="button" class="btn btn-danger delete_selected" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_aliases(this)" disabled>
</div>
<div class="table-responsive">
<table class="table align-middle table-nowrap mb-0 webuzo-table">
<thead class="sai_head2">
<tr>
<th><input type="checkbox" id="checkall"></th>
<th class="align-middle" width="28%">'.__('Domain').'</th>
<th class="align-middle" width="27%">'.__('Redirects To').'</th>
<th class="align-middle" width="35%">'.__('Domain Root').'</th>
<th class="align-middle" width="5%" style="text-align: right;" colspan="2">'.__('Actions').'</th>
</tr>
</thead>
<tbody>';
if(empty($aliases_list)){
echo
'<tr>
<td class="text-center" colspan=5>'.__('No record exists. Please add one !').'</td>
</tr>';
}else{
foreach ($aliases_list as $key => $value){
echo '<tr id="tr'.$key.'">
<td><input type="checkbox" class="check_aliase" name="check_aliase" value="'.$key.'"></td>
<td class="endurl"><a target="_blank" href="http://' . $key . '">'.$key.'</a></td>';
echo '<td>'.$value['alias'].'</td>
<td>'.$value['path'].'</td>
<td width="1%">
<a href="javascript:open_modal(\''.$key.'\', \''.$value['alias'].'\')" id="edit'.$key.'" title="'.__('Edit').'">
<i class="fas fa-pencil-alt edit-icon"></i>
</a>
</td>';
if ($key != $primary_domain){
echo '<td  width="1%">
<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$key.'" onclick="delete_record(this)" data-delete="'.$key.'"></i>
</td>';
}else{
echo '<td width="1">-</td>';
}
echo '</tr>';
}
}
echo
'</tbody>
</table>
</div>
</div>
<script language="javascript" type="text/javascript">
function open_modal() {
}else{
$(".action-type").html("'.__js('Add Alias').'");
$("#domain").prop("disabled", false).attr("name", "domain");
$("#domain").val("");
$("#domain_val").val(dom).attr("name", "domain_val");
var to = $("#redirect_to").first().val();
$("#redirect_to").val(to);
return false;
}
}
$(document).ready(function(){
$("#checkall").change(function(){
$(".check_aliase").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function(){
if($(".check_aliase:checked").length){
$("#delete_selected").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled",true);
}
});
});
function del_aliases() {
});
a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete selected aliases ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
var d = {"delete" : arr.join()};
submitit(d, {done_reload : window.location.href});
});
show_message(a);
}
</script>';
softfooter();
}