<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function set_ttl_theme() {
}
softheader(__('Set DNS Zone TTL'));
echo '
<div class="soft-smbox p-3">
<div class="sai_main_head">
<i class="fas fa-project-diagram fa-xl me-1"></i> '.__('Set DNS Zone TTL').'
<span class="search_btn float-end mx-2">
<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
</span>
</div>
<div style="background-color:#e9ecef;" class="mt-1">
<div class="collapse '.(!empty(optREQ('user_search')) || !empty(optREQ('dom_search')) ? 'show' : '').'" id="search_queue">
<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
<div class="row p-3 col-md-12 d-flex">
<div class="col-12 col-md-6">
<label class="sai_head">'.__('Search By Domain Name').'</label>
<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act=dns_zones&api=json" s2-query="dom_search" s2-data-key="dns_zones" style="width: 100%" name="dom_search" id="dom_search">
<option value="'.optREQ('dom_search').'" selected="selected">'.optREQ('dom_search').'</option>
</select>
</div>
<div class="col-12 col-md-6">
<label class="sai_head">'.__('Search By User Name').'</label><br/>
<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="user_search" s2-data-key="users" s2-result-add="'.htmlentities(json_encode([['text' => 'root', 'id' => 'root', 'value' => 'root']])).'"  style="width: 100%" id="user_search" name="user_search">
<option value="'.optREQ('user_search').'" selected="selected">'.optREQ('user_search').'</option>
</select>
</div>
</div>
</form>
</div>
</div>
</div>
<div class="soft-smbox p-3 mt-4">';
page_links();
echo '
<div class="row">
<div class="col-12 col-sm-8 pb-2">
'.__('With Selected').' : <input type="text" name="ttl_sel" id="ttl_sel"  >
<input type="button" class="btn btn-primary py-1" value="'.__('Set TTL').'" name="update_selected" id="update_selected" onclick="set_ttl_sel(this)" disabled>
</div>
<div class="col-12 col-sm-4">
<div class="float-end">
<b>'.__('Total Zones').': '.$globals['total_dns_zones'].'</b>
</div>
</div>
</div>
<div id="showrectab" class="table-responsive">';
showdns();
echo '
</div>
</div>
<script language="javascript" type="text/javascript">
$(document).ready(function () {
$("#user_search").on("select2:select", function(){
user = $("#user_search option:selected").val();
window.location = "'.$globals['index'].'act=set_ttl&user_search="+user;
});
$("#dom_search").on("select2:select", function(){
var domain_selected = $("#dom_search option:selected").val();
window.location = "'.$globals['index'].'act=set_ttl&dom_search="+domain_selected;
});
});
$("#check_all_ttl").change(function () {
$(".ttlcheck").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function() {
if($(".ttlcheck:checked").length){
$("#update_selected").removeAttr("disabled");
}else{
$("#update_selected").prop("disabled", true);
}
});
function set_ttl_sel() {
}
});
if(empty(check)){
var a = show_message_r("'.__js('Error').'", "'.__js('Please select atleast one DNS Zone to set TTL').'");
a.alert = "alert-danger"
show_message(a);
return false;
}
$("input:checkbox[name=checked_dns]:checked").each(function(){
domains.push($(this).val());
});
var data = {"edit_ttl" : domains, "ttl" : up_ttl};
submitit(data,{
done_reload : window.location.href
});
}
$(".dns_rec_cancel").on("click", function() {
$(this).closest("tr").find(".dns_rec_cancel, .dns_rec_save, .update_ttl").hide();
$(this).closest("tr").find(".dns_rec_edit, .ttl").show();
});
$(".dns_rec_edit").on("click", function() {
$(this).closest("tr").find(".dns_rec_cancel, .dns_rec_save, .update_ttl").show();
$(this).closest("tr").find(".dns_rec_edit, .ttl").hide();
});
$(".dns_rec_save").on("click",function() {
var data = $(this).closest("tr").find("input").serialize();
submitit(data, {
done_reload : window.location.href
});
});
</script>';
softfooter();
}
function showdns() {
}else{
foreach ($dns_zones as $dom => $data){
echo '
<tr id="tr'.$dom.'">
<td>
<input type="checkbox" class="ttlcheck" name="checked_dns" id="check'.$dom.'" value="'.$dom.'">
</td>
<td>
<span>'.$dom.'</span>
<input type="hidden" id="name'.$dom.'" name="domain" value="'.$dom.'" />
<input type="hidden" name="edit_ttl" value="'.$dom.'" />
</td>
<td>
<span id="user'.$dom.'">'.$data['user'].'</span>
</td>
<td>
<span id="owner'.$dom.'">'.$data['owner'].'</span>
</td>
<td>
<span class="ttl" id="ttl'.$dom.'">'.$data['ttl'].'</span>
<input class="update_ttl" type="text" name="ttl" id="ttl_entry'.$dom.'" size="3" value="'.$data['ttl'].'" style="display:none" >
</td>
<td style="text-align: right;">
<i class="fas fa-undo-alt dns_rec_cancel cancel-icon mx-1" title="Cancel" id="cid'.$dom.'" style="display:none"></i>
<i class="fas fa-pencil-alt dns_rec_edit edit-icon mx-1" id="eid'.$dom.'" title="Edit"></i>
<i class="fas fa-save dns_rec_save edit-icon mx-1" id="sid'.$dom.'" data-dom="'.$data['user'].'" title="Save" style="display:none"></i>
</td>
</tr>';
}
}
echo '
<tbody>
</table>';
}