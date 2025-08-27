<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function advancedns_theme() {
}
if(!empty($done)){
echo '1'.__('Record edited successfully');
return true;
}
}
if(optGET('ajaxdom')){
showdns();
return true;
}
softheader(__('Advance DNS Setting'));
$hostname = htmlentities(json_encode([['text' => $globals['WU_PRIMARY_DOMAIN'], 'id' => $globals['WU_PRIMARY_DOMAIN'], 'value' => $globals['WU_PRIMARY_DOMAIN']]]));
echo '
<div class="soft-smbox p-3">
<div class="sai_main_head">
'.__('Advance DNS Setting').'
<form method="post" name="dnsexport" onsubmit="return exportdnsfile(this)">
<input type="submit" class="btn btn-primary float-end" name="exportdns" value="'.__('Export DNS File').'">
</form>
</div>
</div>
<div class="soft-smbox p-3 mt-3">';
if(empty($is_running)){
echo '
<div class="alert alert-danger p-1 text-center">
<a href="'.$globals['ind'].'act=services" class="mt-1 text-decoration-none" style="font-size:15px;">'.__('Your Bind service is not running currently. Click here to start.').'</a>
</div>';
}
echo '
<div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="addRecordLabel">'.__('Add Record').'</h5>
<button type="button" class="btn-close add_record_close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="advancedns" id="advancedns" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
<label class="sai_head d-block">'.__('Select Domain').'</label>
<select class="form-select search_val mb-3 make-select2" s2-placeholder="'.__('Select Domain').'" s2-ajaxurl="'.$globals['index'].'act=dns_zones&api=json" s2-query="dom_search" s2-data-key="dns_zones"  s2-dropdownparent="#addRecordModal" style="width: 100%" name="domain" id="f_dom_search">
<option value="'.$domain.'">'.$domain.'</value>
</select>
<label for="name" class="sai_head d-block mt-3">'.__('Name').'</label>
<div class="input-group mb-3">
<input type="text" name="name" id="name" class="form-control" />
<span class="input-group-text" id="domainname">.'.$domain.'</span></span>
</div>
<label for="ttl" class="sai_head">'.__('TTL').'</label>
<input type="text" name="ttl" id="ttl" class="form-control mb-3" size="30" value="14400" />
<label for="selecttype" class="sai_head">'.__('Type').'</label>
<select name="selecttype" id="selecttype" class="form-select mb-3" onchange="disp_type(this.value)">
<option value="A">A</option>
<option value="AAAA">AAAA</option>
<option value="CNAME">CNAME</option>
<option value="TXT">TXT</option>
<option value="PTR">PTR</option>
<option value="SRV">SRV</option>
<option value="CAA">CAA</option>
<option value="DNAME">DNAME</option>
<option value="NS">NS</option>
<option value="AFSDB">AFSDB</option>
<option value="HINFO">HINFO</option>
<option value="RP">RP</option>
<option value="DS">DS</option>
</select>
<div class="srv-form">
<label for="srv_priority" class="sai_head" id="srv_priority_lbl">'.__('Priority').'</label>
<input type="number" id="srv_priority" name="srv_address[priority]" class="form-control" value="" />
<label for="srv_weight" class="sai_head" id="srv_weight_lbl">'.__('Weight').'</label>
<input type="number" id="srv_weight" name="srv_address[weight]" class="form-control" value="" />
<label for="srv_port" class="sai_head" id="srv_port_lbl">'.__('Port').'</label>
<input type="number" id="srv_port" name="srv_address[port]" class="form-control" value="" />
<label for="srv_target" class="sai_head" id="srv_target_lbl">'.__('Target').'</label>
<input type="text" id="srv_target" name="srv_address[target]" class="form-control" value="" />
</div>
<div class="caa-form">
<label for="caa_flag" class="sai_head" id="caa_flag_lbl">'.__('Issuer Critical Flag').' :
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('If 1, the certificate issuer must understand the tag in order to correctly process the CAA record.').'"></i>
</label>
</br>
<label class="form-label me-2">
<input type="radio" id="caa_flag" name="caa_address[flag]" value="0"  checked/>
'.__('0').'
</label>
<label class="form-label me-2">
<input type="radio" id="caa_flag" name="caa_address[flag]" value="128"/>
'.__('1').'
</label>
</br>
<label for="caa_tag" class="sai_head mt-2" id="caa_tag_lbl">'.__('Tag').' :
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('The type of CAA record that this DNS entry represents. The issue and issuewild represent the certificate authority’s domain name. The iodef value is the email to which the authority reports exceptions.').'"></i>
</label>
</br>
<label class="form-label me-2">
<input type="radio" id="caa_tag" name="caa_address[tag]" value="issue" checked onclick="caa_exp(\'issue\')"/>
'.__('Issue').'
</label>
<label class="form-label me-2">
<input type="radio" id="caa_tag" name="caa_address[tag]" value="issuewild" onclick="caa_exp(\'issuewild\')"/>
'.__('Issuewild').'
</label>
<label class="form-label me-2">
<input type="radio" id="caa_tag" name="caa_address[tag]" value="iodef" onclick="caa_exp(\'iodef\')"/>
'.__('Iodef').'
</label>
</br>
<label for="caa_value" class="sai_head mt-2" id="caa_value_lbl">'.__('Value').' :
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('Certificate Authority\'s Domain Name').'"></i></label>
<input type="text" id="caa_value" name="caa_address[value]" class="form-control" value=""/>
<label class="sai_exp2" id="caaval_exp"></label>
</div>
<div class="afsdb-form">
<label for="afsdb_subtype" class="sai_head" id="afsdb_subtype_lbl">'.__('Subtype').'
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('A 16-bit integer that represents a service type used by clients to connect to a cell’s servers. Currently accepted values are 1, for AFS Database Server or 2, for DCE Authentication Server.').'"></i></label>
</label>
<input type="number" id="afsdb_subtype" name="afsdb_address[subtype]" class="form-control" value="" />
<label for="afsdb_hostname" class="sai_head" id="afsdb_hostname_lbl">'.__('Hostname').'
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('A hostname of the server that provides the cell.').'"></i></label>
<input type="text" id="afsdb_hostname" name="afsdb_address[hostname]" class="form-control" value="" />
</div>
<div class="hinfo-form">
<label for="hinfo_cpu" class="sai_head" id="hinfo_cpu_lbl">'.__('CPU').'
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('A machine name or CPU type').'"></i></label>
<input type="text" id="hinfo_cpu" name="hinfo_address[cpu]" class="form-control" value="" />
<label for="hinfo_os" class="sai_head" id="hinfo_os_lbl">'.__('Operating System').'
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('A system name').'"></i></label>
<input type="text" id="hinfo_os" name="hinfo_address[os]" class="form-control" value="" />
</div>
<div class="rp-form">
<label for="rp_mbox_dname" class="sai_head" id="rp_mbox_dname_lbl">'.__('Mbox-dname').'
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('A valid Email address').'"></i></label>
<input type="text" id="rp_mbox_dname" name="rp_address[mbox-dname]" class="form-control" value="" />
<label for="rp_txt_dname_" class="sai_head" id="rp_txt_dname_lbl">'.__('txt-dname').'
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('A valid Hostname or qualified domain name').'"></i></label>
<input type="text" id="rp_txt_dname_" name="rp_address[txt-dname]" class="form-control" value="" />
</div>
<div class="ds-form">
<label for="ds_key-tag" class="sai_head" id="ds_key-tag_lbl">'.__('Key-tag').'
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('Positive, unsigned, 16-bit integer representing the key tag of the DNSKEY RR.').'"></i></label>
<input type="text" id="ds_key-tag" name="ds_address[key-tag]" class="form-control" value="" />
<label for="ds_algorithms" class="sai_head" id="ds_algorithms_lbl">'.__('Algorithm').'
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('The algorithm number of the DNSKEY RR referred to by the DS record.').'"></i></label>
<select id="ds_algorithms" name="ds_address[algorithm]" class="form-control">';
foreach($ds_algorithms as $dsa => $dsv){
echo '<option value="'.$dsv.'">'.$dsv.'</option>';
}
echo '
</select>
<label for="ds_digest-type" class="sai_head" id="ds_digest-type_lbl">'.__('Digest-type').'
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('The algorithm used to construct the digest.').'"></i></label>
<select id="ds_digest-type" name="ds_address[digest-type]" class="form-control">';
foreach($ds_digest_types as $dta => $dtv){
echo '<option value="'.$dtv.'">'.$dtv.'</option>';
}
echo '
</select>
<label for="ds_digest" class="sai_head" id="ds_digest_lbl">'.__('Digest').'
<i class="fa fa-info-circle sai-info" data-bs-html="true" data-bs-toggle="tooltip" title="" data-bs-original-title="'.__('The DS record refers to a DNSKEY RR by including a 20 octet digest of that DNSKEY RR.').'"></i></label>
<input type="text" id="ds_digest" name="ds_address[digest]" class="form-control" value="" />
</div>
<div class="other-form">
<label for="address" class="sai_head" id="type">'.__('Address').'</label>
<input type="text" id="address" name="address" class="form-control mb-3" value="" />
</div>
<div class="text-center my-3">
<input type="submit" class="btn btn-primary" value="'.__('Add Record').'" name="create_record" id="submitdns" />
</div>';
echo '
</form>
</div>
</div>
</div>
</div>
<div class="sai_sub_head mb-3 position-relative">
<div class="row">
<div class="col-12 col-md-4">
<button type="button" class="btn btn-danger" name="delete_selected" id="delete_selected" onclick="delete_dns(this)" disabled>'.__('Delete Selected').'</button>
<button type="button" class="btn btn-outline-danger reset_zone" id="reset_zone" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Click here to reset DNS zone" onclick="reset_dns()">'.__('Reset DNS').'</button>
</div>
'.__('Zone File records of').'
<div class="col-12 col-md-3">
<select class="form-select dom_search my-3 make-select2" s2-placeholder="'.__('Select Domain').'" s2-ajaxurl="'.$globals['index'].'act=dns_zones&api=json" s2-query="dom_search" s2-data-key="dns_zones" name="dom_search" id="dom_search">
<option value="'.$domain.'">'.$domain.'</option>
</select>
</div>
<div class="col-12 col-md-3">
<button type="button" class="btn btn-primary open-modal add_record" data-bs-toggle="modal" data-bs-target="#addRecordModal">'.__('Add Record').'</button>
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
$(window).on("hashchange", add_record_hash);
add_record_hash();
});
function reset_dns() {
};
var a, lan;
lan = "'.__js('Are you sure you want to $0 reset DNS $1 of ', ['<b>', '</b>']).'<b>"+d.domain+"</b>";
a = show_message_r("'.__js('Warning').'", lan);
a.alert = "alert-warning";
a.confirm.push(function(){
submitit(d, {done_reload : window.location.href});
});
show_message(a);
}
function add_record_hash() {
}
$(".add_record").click();
if(hash.match(/CNAME/i)){
$("#selecttype").val("CNAME");
}
}
$("#dom_search").on("select2:select", function(e, dom = {}){
var domain;
if("domain" in dom){
domain = dom.domain;
}else{
domain = $("#dom_search option:selected").val();
}
var url = window.location.href;
if (url.indexOf("&domain=") !== -1) {
url = url.replace(/&domain=[^&]*/g, "");
}
if (url.indexOf("&domain=") === -1) {
url += "&domain=" + domain;
}
window.history.pushState({path: url}, "", url);
AJAX({
type: "POST",
url: "'.$globals['admin_url'].'act=advancedns&ajaxdom=1&domain="+domain,
},
function(data){
$("#showrectab").html(data);
$("#domain_file").html(domain);
if ($("#f_dom_search").find("option[value=\'" + domain + "\']").length) {
$("#f_dom_search").val(domain).trigger("change");
} else {
var newOption = new Option(domain, domain, true, true);
$("#f_dom_search").append(newOption).trigger("change");
}
$("#domainname").html($("#f_dom_search").val());
});
});
$("#f_dom_search").on("select2:select", function(){
$("#domainname").html("."+$(this).val());
});
$("#advancedns").on("done", function(){
$("#dom_search").trigger("select2:select", {domain:$("#f_dom_search").val()});
});
$(document).on("click", ".cancel", function() {
var id = $(this).attr("id");
id = id.substr(3);
$("#cid"+id).hide();
$("#eid"+id).removeClass("fa-save").addClass("fa-edit");
$("#tr"+id).find("span").show();
$("#tr"+id).find("input[type!=\"checkbox\"],.input").hide();
$("#tr"+id).find("select,.select").hide();
$("#tr"+id).find("label,.label").hide();
$("#tr"+id).css("background-color","");
});
$(document).on("click", ".edit", function() {
var id = $(this).attr("id");
id = id.substr(3);
$("#cid"+id).show();
$("#tr"+id).css("background-color","#d7edf9");
var current_type = $("#type_entry"+id).val()
var current_html = $("#recordtd"+id).children()
caa_exp(id);
$(".record_entry"+id+"tag").click(function(){
caa_exp(id);
})
$("#type_entry"+id).on("change", function(){
var rec_type = $(this).val();
$(".other-form"+id).remove()
$(".srv-form"+id).remove()
$(".caa-form"+id).remove()
$(".afsdb-form"+id).remove()
$(".hinfo-form"+id).remove()
$(".rp-form"+id).remove()
$(".ds-form"+id).remove()
var tmphtml = \'<div class="other-form\'+id+\'"><span id="record\'+id+\'"></span><input type="text" name="record" class="form-control-sm" id="record_entry\'+id+\'" style=""  value="" size="10"></div>\';
if(rec_type == "SRV"){
var tmphtml = \'<div class="srv-form\'+id+\'"><b>Priority</b> : <span class="mt-2" id="record_entrypriorityspan\'+id+\'"></span><input type="text" name="record[priority]" id="record_entrypriority\'+id+\'" data-dtype="srv" class="form-control-sm mt-2" style=""  value="" size="10"></br>\';
tmphtml += \'<b>Weight</b> : <span class="mt-2" id="record_entryweightspan\'+id+\'"></span><input type="text" name="record[weight]" id="record_entryweight\'+id+\'" data-dtype="srv" class="form-control-sm mt-2" style=""  value="" size="10"></br>\';
tmphtml += \'<b>Port</b> : <span class="mt-2" id="record_entryportspan\'+id+\'"></span><input type="text" name="record[port]" id="record_entryport\'+id+\'" data-dtype="srv" class="form-control-sm mt-2" style=""  value="" size="10"></br>\';
tmphtml += \'<b>Target</b> : <span class="mt-2" id="record_entrytargetspan\'+id+\'"></span><input type="text" name="record[target]" id="record_entrytarget\'+id+\'" data-dtype="srv" class="form-control-sm mt-2" style=""  value="" size="10"></br></div>\';
}
if(rec_type == "CAA"){
var tmphtml = \'<div class="caa-form\'+id+\'"><b>Flag</b> : <span class="mt-2" id="record_entry\'+id+\'flagspan"></span><label class="form-label me-2" style=""><input type="radio" name="record\'+id+\'[flag]" id="record_entry\'+id+\'flag" data-dtype="caa" class="mt-2" style="" value="0" checked />0</label><label class="form-label me-2" style=""><input type="radio" name="record\'+id+\'[flag]" id="record_entry\'+id+\'flag" data-dtype="caa" class="mt-2" style="" value="128" />1</label><br>\';
tmphtml += \'<b>Tag</b> : <span class="mt-2" id="record_entry\'+id+\'tagspan"></span><label class="form-label me-2"><input type="radio" name="record\'+id+\'[tag]" class="record_entry\'+id+\'tag" data-dtype="caa" value="issue" checked />issue&nbsp;</label><label class="form-label me-2"><input type="radio" name="record\'+id+\'[tag]" class="record_entry\'+id+\'tag" data-dtype="caa" value="issuewild" />issuewild&nbsp;</label><label class="form-label me-2"><input type="radio" name="record\'+id+\'[tag]" class="record_entry\'+id+\'tag" data-dtype="caa" value="iodef" />iodef&nbsp;</label><br>\';
tmphtml += \'<b>Value</b> : <span class="mt-2" id="record_entry\'+id+\'valuespan"></span><input type="text" name="record\'+id+\'[value]" id="record_entry\'+id+\'value" data-dtype="caa" class="mt-2" style=""  value="" size="10"></br><label class="sai_exp2 mt-1 me-3" id="\'+id+\'val_exp"></label></div>\';
}
if(rec_type == "AFSDB"){
var tmphtml = \'<div class="afsdb-form\'+id+\'"><b>Subtype</b> : <span class="mt-2" id="record_entrysubtypespan\'+id+\'"></span><input type="text" name="record[subtype]" id="record_entrysubtype\'+id+\'" data-dtype="afsdb" class="form-control-sm mt-2" style=""  value="" size="10"></br>\';
tmphtml += \'<b>Hostname</b> : <span class="mt-2" id="record_entryhostnamespan\'+id+\'"></span><input type="text" name="record[hostname]" id="record_entryhostname\'+id+\'" data-dtype="afsdb" class="form-control-sm mt-2" style=""  value="" size="10"></br></div>\';
}
if(rec_type == "HINFO"){
var tmphtml = \'<div class="hinfo-form\'+id+\'"><b>CPU</b> : <span class="mt-2" id="record_entrycpuspan\'+id+\'"></span><input type="text" name="record[cpu]" id="record_entrycpu\'+id+\'" data-dtype="hinfo" class="form-control-sm mt-2" style=""  value="" size="10"></br>\';
tmphtml += \'<b>OS</b> : <span class="mt-2" id="record_entryosspan\'+id+\'"></span><input type="text" name="record[os]" id="record_entryos\'+id+\'" data-dtype="hinfo" class="form-control-sm mt-2" style=""  value="" size="10"></br></div>\';
}
if(rec_type == "RP"){
var tmphtml = \'<div class="rp-form\'+id+\'"><b>Mbox-dname</b> : <span class="mt-2" id="record_entrymbox-dnamespan\'+id+\'"></span><input type="text" name="record[mbox-dname]" id="record_entrymbox-dname\'+id+\'" data-dtype="rp" class="form-control-sm mt-2" style=""  value="" size="10"></br>\';
tmphtml += \'<b>Txt-dname</b> : <span class="mt-2" id="record_entrytxt-dnamespan\'+id+\'"></span><input type="text" name="record[txt-dname]" id="record_entrytxt-dname\'+id+\'" data-dtype="rp" class="form-control-sm mt-2" style=""  value="" size="10"></br></div>\';
}
var algos = '.json_encode($ds_algorithms).';
var digest_type = '.json_encode($ds_digest_types).';
if(rec_type == "DS"){
var tmphtml = \'<div class="ds-form\'+id+\'"><b>Key-tag</b> : <span class="mt-2" id="record_entrykey-tagspan\'+id+\'"></span><input type="text" name="record\'+id+\'[key-tag]" id="record_entrykey-tag\'+id+\'" data-dtype="ds" class="form-control-sm mt-2" style=""  value="" size="10"></br>\';
tmphtml += \'<b>Algorithm</b> : <span class="mt-2" id="record_entryalgorithmspan\'+id+\'"></span><select name="record\'+id+\'[algorithm]" id="record_entryalgorithm\'+id+\'" data-dtype="ds" class="form-control-sm mt-2" style="">\';
$(algos).each(function(kk, vv){
tmphtml +=\'<option value="\'+vv+\'">\'+vv+\'</option>\';
})
tmphtml +=\'</select></br>\';
tmphtml += \'<b>Digest-type</b> : <span class="mt-2" id="record_entrydigest-typespan\'+id+\'"></span><select name="record\'+id+\'[digest-type]" id="record_entrydigest-type\'+id+\'" data-dtype="ds" class="form-control-sm mt-2" style="">\';
$(digest_type).each(function(dkk, dvv){
tmphtml +=\'<option value="\'+dvv+\'">\'+dvv+\'</option>\';
})
tmphtml +=\'</select></br>\';
tmphtml += \'<b>Digest</b> : <span class="mt-2" id="record_entrydigestspan\'+id+\'"></span><input type="txt" name="record\'+id+\'[digest]" id="record_entrydigest\'+id+\'" data-dtype="ds" class="form-control-sm mt-2" style="" size="10" /></br></div>\';
}
if(rec_type == current_type){
$("#recordtd"+id).append(current_html);
}else{
$("#recordtd"+id).append(tmphtml);
}
caa_exp(id);
$(".record_entry"+id+"tag").click(function(){
caa_exp(id);
})
$("#cid"+id).click(function(){
$("#recordtd"+id).children().remove();
$("#recordtd"+id).append(current_html);
$("#type_entry"+id).val(current_type);
})
})
if($("#eid"+id).hasClass("fa-save")){
var d = $("#tr"+id).find("input, textarea, select").serialize();
submitit(d, {
done: function(){
var tr = $("#tr"+id);
tr.find(".cancel").click();// Revert showing the inputs
var select_field = tr.find("select");
tr.find("input, textarea, select").each(function(){
var jE = $(this);
if(jE.attr("type") == "hidden"){
return;
}
if(jE.attr("name") == "record"){
var value = jE.val();
var record_type = select_field.closest("td").find("span").html();
value = value.split("<").join("&lt;").split(">").join("&gt;");
if(record_type == "CNAME" || record_type == "DNAME" || record_type == "NS"){
jE.closest("td").find("span").html(value+".");
}else{
jE.closest("td").find("span").html(value);
}
return;
}
if(["soa", "srv", "caa", "afsdb", "rp", "hinfo", "ds"].includes(jE.data("dtype"))){
var value = jE.val();
value = value.split("<").join("&lt;").split(">").join("&gt;");
var input_id = jE.attr("id");
jE.closest("td").find("#"+input_id+"span").html(value);
return;
}
jE.closest("td").find("span").html(jE.val());
});
},
sm_done_onclose: function(){
$("#tr"+id).find("span").show();
$("#tr"+id).find("input[type!=\"checkbox\"],.input").hide();
$("#tr"+id).find("select,.select").hide();
$("#tr"+id).css("background-color","");
var url = window.location.href;
window.location.href = url
}
});
}else{
$("#eid"+id).addClass("fa-save").removeClass("fa-edit");
$("#tr"+id).find("span").hide();
$("#tr"+id).find("input,.input").show();
$("#tr"+id).find("select,.select").show();
$("#tr"+id).find("label,.label").show();
if($("#type"+id).text() == "SOA"){
$("#name_entry"+id).attr("disabled", "disabled");
$("#record_entry"+"serial").attr("disabled", "disabled");
}
if($("#type"+id).text() == "CNAME" || $("#type"+id).text() == "DNAME" || $("#type"+id).text() == "PTR" || $("#type"+id).text() == "NS"){
$("#record_entry"+id).val($("#record"+id).text().substring(0, $("#record"+id).text().length - 1));
}else if($("#type"+id).text() == "SRV"){
$("#record_entrytarget"+id).val($("#record_entrytargetspan"+id).text().substring(0, $("#record_entrytargetspan"+id).text().length - 1));
}else{
$("#record_entry"+id).val($("#record"+id).text());
}
$("#record_entry"+id).show().focus();
}
});
</script>';
softfooter();
}
function showdns() {
}else{
foreach ($dns_list as $key => $value){
if(!preg_match('/'.$domain.'/is', $dns_list[$key]['name'])){
$dns_list[$key]['name'] = $dns_list[$key]['name'].'.'.$domain.'.';
}
echo '
<tr id="tr'.$key.'">
<td>
<input type="checkbox" name="check_dns" class="check_dns" value="'.$key.'" data-domain="'.$domain.'" '.(($dns_list[$key]['type'] == 'SOA') ? 'disabled' : '').'>
</td>
<td>
<span id="name'.$key.'">'.$dns_list[$key]['name'].'</span>
<input type="text" id="name_entry'.$key.'" style="display:none;" class="form-control-sm" name="name" value="'.$dns_list[$key]['name'].'">
<input type="hidden" name="domain" value="'.$domain.'" />
<input type="hidden" name="edit_record" value="'.$key.'" />
</td>
<td>
<span id="ttl'.$key.'">'.$dns_list[$key]['ttl'].'</span>
<input type="text" name="ttl" id="ttl_entry'.$key.'" style="display:none;" class="form-control-sm" size="3" value="'.$dns_list[$key]['ttl'].'">
</td>
<td>
'.$dns_list[$key]['class'].'
</td>
<td>
<span id="type'.$key.'">'.$dns_list[$key]['type'].'</span>
<select class="form-control-sm input" name="type" id="type_entry'.$key.'" style="display:none;">';
if($dns_list[$key]['type'] == 'SOA'){
echo '<option value="SOA" '.($dns_list[$key]['type'] == 'SOA' ? 'selected=selected' : '').'>SOA</option>';
}else{
echo '
<option value="A" '.($dns_list[$key]['type'] == 'A' ? 'selected=selected' : '').'>A</option>
<option value="AAAA" '.($dns_list[$key]['type'] == 'AAAA' ? 'selected=selected' : '').'>AAAA</option>
<option value="CNAME" '.($dns_list[$key]['type'] == 'CNAME' ? 'selected=selected' : '').'>CNAME</option>
<option value="TXT" '.($dns_list[$key]['type'] == 'TXT' ? 'selected=selected' : '').'>TXT</option>
<option value="PTR" '.($dns_list[$key]['type'] == 'PTR' ? 'selected=selected' : '').'>PTR</option>
<option value="SRV" '.($dns_list[$key]['type'] == 'SRV' ? 'selected=selected' : '').'>SRV</option>
<option value="CAA" '.($dns_list[$key]['type'] == 'CAA' ? 'selected=selected' : '').'>CAA</option>
<option value="DNAME" '.($dns_list[$key]['type'] == 'DNAME' ? 'selected=selected' : '').'>DNAME</option>
<option value="NS" '.($dns_list[$key]['type'] == 'NS' ? 'selected=selected' : '').'>NS</option>
<option value="AFSDB" '.($dns_list[$key]['type'] == 'AFSDB' ? 'selected=selected' : '').'>AFSDB</option>
<option value="HINFO" '.($dns_list[$key]['type'] == 'HINFO' ? 'selected=selected' : '').'>HINFO</option>
<option value="RP" '.($dns_list[$key]['type'] == 'RP' ? 'selected=selected' : '').'>RP</option>
<option value="DS" '.($dns_list[$key]['type'] == 'DS' ? 'selected=selected' : '').'>DS</option>';
}
echo '
</select>
</td>
<td style="word-break: break-word; max-width: 350px;" id="recordtd'.$key.'">';
if($dns_list[$key]['type'] == 'SOA'){
foreach($dns_list[$key]['record'] as $sk => $sv){
echo '<b>'.ucfirst($sk).'</b> : <span class="mt-2" id="record_entry'.$sk.'span">'.$sv.'</span>
<input type="'.(in_array($sk, ['retry', 'refresh', 'expire']) ? 'number' : 'text').'" class="form-control-sm mt-2" name="record['.$sk.']" id="record_entry'.$sk.'" data-dtype="soa" class="mt-2" style="display:none;"  value="'.$sv.'" size="10"><br>';
}
}elseif($dns_list[$key]['type'] == 'SRV'){
echo '<div class="srv-form'.$key.'">';
foreach($dns_list[$key]['record'] as $srk => $srv){
echo '<b>'.ucfirst($srk).'</b> : <span class="mt-2" id="record_entry'.$srk.'span'.$key.'">'.$srv.'</span>
<input type="text" name="record['.$srk.']" id="record_entry'.$srk.$key.'" class="form-control-sm mt-2" data-dtype="srv" class="mt-2" style="display:none;"  value="'.$srv.'" size="10"><br>';
}
echo '</div>';
}elseif($dns_list[$key]['type'] == 'CAA'){
echo '<div class="caa-form'.$key.'">';
foreach($dns_list[$key]['record'] as $crk => $crv){
if($crk == 'flag'){
echo '<b>'.ucfirst($crk).'</b> : <span class="mt-2" id="record_entry'.$key.$crk.'span">'.$crv.'</span>
<label class="form-label me-2" style="display:none;">
<input type="radio" name="record'.$key.'['.$crk.']" id="record_entry'.$key.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="0" '.($crv == 0 ? 'checked' : '').'>0
</label>
<label class="form-label me-2" style="display:none;">
<input type="radio" name="record'.$key.'['.$crk.']" id="record_entry'.$key.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="128" '.($crv == 128 ? 'checked' : '').'>1
</label><br>';
}elseif($crk == 'tag'){
echo '<b>'.ucfirst($crk).'</b> : <span class="mt-2" id="record_entry'.$key.$crk.'span">'.$crv.'</span>
<label class="form-label me-2" style="display:none;">
<input type="radio" name="record'.$key.'['.$crk.']" class="record_entry'.$key.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="issue" '.($crv == 'issue' ? 'checked' : '').'>'.__('Issue').'
</label>
<label class="form-label me-2" style="display:none;">
<input type="radio" name="record'.$key.'['.$crk.']" class="record_entry'.$key.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="issuewild" '.($crv == 'issuewild' ? 'checked' : '').'>'.__('Issuewild').'
</label>
<label class="form-label me-2" style="display:none;">
<input type="radio" name="record'.$key.'['.$crk.']" class="record_entry'.$key.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="iodef" '.($crv == 'iodef' ? 'checked' : '').'>'.__('Iodef').'
</label><br>';
}else{
echo '<b>'.ucfirst($crk).'</b> : <span class="mt-2" id="record_entry'.$key.$crk.'span">'.$crv.'</span>
<input type="text" name="record'.$key.'['.$crk.']" id="record_entry'.$key.''.$crk.'" data-dtype="caa" class="mt-2" style="display:none;" value="'.$crv.'" size="10"><br>
<label class="sai_exp2 mt-1 me-3" id="'.$key.'val_exp"></label>';
}
}
echo '</div>';
}elseif($dns_list[$key]['type'] == 'AFSDB'){
echo '<div class="afsdb-form'.$key.'">';
foreach($dns_list[$key]['record'] as $afsdb_k => $afsdb_v){
echo '<b>'.ucfirst($afsdb_k).'</b> : <span class="mt-2" id="record_entry'.$afsdb_k.'span'.$key.'">'.$afsdb_v.'</span>
<input type="text" name="record['.$afsdb_k.']" id="record_entry'.$afsdb_k.$key.'" class="form-control-sm mt-2" data-dtype="afsdb" class="mt-2" style="display:none;"  value="'.$afsdb_v.'" size="10"><br>';
}
echo '</div>';
}elseif($dns_list[$key]['type'] == 'HINFO'){
echo '<div class="hinfo-form'.$key.'">';
foreach($dns_list[$key]['record'] as $hinfo_k => $hinfo_v){
echo '<b>'.strtoupper($hinfo_k).'</b> : <span class="mt-2" id="record_entry'.$hinfo_k.'span'.$key.'">'.$hinfo_v.'</span>
<input type="text" name="record['.$hinfo_k.']" id="record_entry'.$hinfo_k.$key.'" class="form-control-sm mt-2" data-dtype="hinfo" class="mt-2" style="display:none;"  value="'.$hinfo_v.'" size="10"><br>';
}
echo '</div>';
}elseif($dns_list[$key]['type'] == 'RP'){
echo '<div class="rp-form'.$key.'">';
foreach($dns_list[$key]['record'] as $rp_k => $rp_v){
echo '<b>'.ucfirst($rp_k).'</b> : <span class="mt-2" id="record_entry'.$rp_k.'span'.$key.'">'.$rp_v.'</span>
<input type="text" name="record['.$rp_k.']" id="record_entry'.$rp_k.$key.'" class="form-control-sm mt-2" data-dtype="rp" class="mt-2" style="display:none;"  value="'.$rp_v.'" size="10"><br>';
}
echo '</div>';
}elseif($dns_list[$key]['type'] == 'DS'){
echo '<div class="ds-form'.$key.'">';
foreach($dns_list[$key]['record'] as $ds_k => $ds_v){
if($ds_k == 'key-tag'){
echo '<b>'.ucfirst($ds_k).'</b> : <span class="mt-2" id="record_entry'.$ds_k.'span'.$key.'">'.$ds_v.'</span>
<input type="text" name="record'.$key.'['.$ds_k.']" id="record_entry'.$ds_k.$key.'" class="form-control-sm mt-2" data-dtype="ds" class="mt-2" style="display:none;"  value="'.$ds_v.'" size="10"><br>';
}elseif($ds_k == 'algorithm'){
echo '<b>'.ucfirst($ds_k).'</b> : <span class="mt-2" id="record_entry'.$ds_k.'span'.$key.'">'.$ds_v.'</span><select class="form-control-sm mt-2 input" name="record'.$key.'['.$ds_k.']" id="record_entry'.$ds_k.$key.'" data-dtype="ds" style="display:none;" >';
foreach($ds_algorithms as $dsak => $dsav){
echo '<option value="'.$dsav.'" '.($dsav == $ds_v ? 'selected' : '').'>'.$dsav.'</option>';
}
echo '</select><br>';
}elseif($ds_k == 'digest-type'){
echo '<b>'.ucfirst($ds_k).'</b> : <span class="mt-2" id="record_entry'.$ds_k.'span'.$key.'">'.$ds_v.'</span><select class="form-control-sm mt-2 input" name="record'.$key.'['.$ds_k.']" id="record_entry'.$ds_k.$key.'" data-dtype="ds" class="mt-2" style="display:none;">';
foreach($ds_digest_types as $dtk => $dtv){
echo '<option value="'.$dtv.'" '.($dtv == $ds_v ? 'selected' : '').'>'.$dtv.'</option>';
}
echo '</select><br>';
}elseif($ds_k == 'digest'){
echo '<b>'.ucfirst($ds_k).'</b> : <span class="mt-2" id="record_entry'.$ds_k.'span'.$key.'">'.$ds_v.'</span>
<input type="text" name="record'.$key.'['.$ds_k.']" id="record_entry'.$ds_k.$key.'" class="form-control-sm mt-2" data-dtype="ds" class="mt-2" style="display:none;"  value="'.$ds_v.'" size="10"><br>';
}
}
echo '</div>';
}else{
echo '
<div class="other-form'.$key.'">
<span id="record'.$key.'">'.$dns_list[$key]['record'].'</span>
<input type="text" name="record" id="record_entry'.$key.'" style="display:none;"  class="form-control-sm" value="'.$dns_list[$key]['record'].'" size="10">
</div>';
}
echo '
</td>
<td width="2%">
<i class="fas fa-undo-alt cancel cancel-icon" title="Cancel" id="cid'.$key.'" style="display:none;"></i>
</td>
<td width="2%">
<i class="fa-regular fa-pen-to-square edit edit-icon" id="eid'.$key.'" title="Edit"></i>
</td>
<td width="2%">';
if($dns_list[$key]['type'] != 'SOA'){
echo '
<i class="fas fa-trash delete delete-icon" title="Delete" id="did'.$key.'" data-delete="'.$key.'" onclick="delete_record(this)" data-domain="'.$domain.'"></i>';
}
echo '
</td>
</tr>';
}
}
echo '
<tbody>
</table>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
var disptype = $("#selecttype").val();
disp_type(disptype);
});
$("#checkAll").change(function () {
$(".check_dns:enabled").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function() {
if($(".check_dns:checked").length){
$("#delete_selected").removeAttr("disabled");
}else{
$("#delete_selected").prop("disabled", true);
}
});
function exportdnsfile() {
}
function caa_exp() {
}
if(tag_v == "iodef"){
$("#"+id+"val_exp").html("'.__js('The location to which the certificate authority will report exceptions. Either a mailto or standard URL <br> For example : mailto:test@example.com , https://domain.com').'");
}else{
$("#"+id+"val_exp").html("'.__js('The ceritficate authorities domain name.<br> For example : buypass.com , letsencrypt.org').'");
}
}
var previousForm = "";
function disp_type() {
}
previousForm = str;
}
function revertForm() {
}
}
function delete_dns() {
});
var dom = $("#f_dom_search").val();
a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete this selected DNS(s) ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
var d = {"delete" : arr.join(), "domain" : dom};
submitit(d,{
sm_done_onclose: function(){
$("#dom_search").trigger("select2:select");
}
});
});
show_message(a);
}
</script>';
}