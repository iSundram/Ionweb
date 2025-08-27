<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function domainadd_theme() {
}else{
echo '
<form accept-charset="'.$globals['charset'].'" name="domainadd" method="post" action="" role="form" class="form-horizontal" onsubmit="return submitit(this);" data-doneredirect="'.$globals['index'].'act=domainmanage">
<div class="row">
<div class="col-12 col-lg-6">
<div class="mb-4 px-3">
<label for="domain_type" class="sai_head d-inline-block">'.__('Domain Type').'</label>
<span class="sai_exp">'.__('Choose the type of domain you want to add').'</span>
<select name="domain_type" id="domain_type" class="form-select">';
foreach ($domain_types as $k => $v)	{
echo '<option value="'.$v.'" '.POSTselect('domain_type', $v, (optREQ('domain_type') == $v)).'>'.__('$0', [ucfirst($v)]).'</option>';
}
echo '
</select>
</div>
<div class="addon parked mb-4 px-3">
<label for="domain" class="sai_head d-block">'.__('Domain').'</label>
<input type="text" id="domain" name="domain" class="form-control" onkeyup="suggestdompath(this);" value="'.POSTval('domain', '').'"/>
</div>
<div class="subdomain mb-4 px-3">
<label for="subdomain" class="sai_head d-block">'.__('Subdomain').'</label>
<div class="input-group">
<input type="text" id="subdomain" name="subdomain" class="form-control" onkeyup="suggestdompath(this);" value="'.POSTval('domain', '').'"/>
<div class="input-group-text p-0">
<select name="domain" id="main_domain" class="form-select">';
foreach ($domains_list as $k => $v)	{
echo '<option value="'.$k.'" '.POSTselect('domain', $k).'>.'.$k.'</option>';
}
echo '
</select>
</div>
</div>
</div>
<div class="addon subdomain mb-4 px-3" id="addon" style="display:none;">
<label for="domainpath" class="sai_head d-block">'.__('Domain Path').'</label>
<div class="input-group">
<span class="input-group-text" >'.$WE->user['homedir'].'/'.'</span>
<input type="text" id="domainpath" name="domainpath" onkeydown="dompath=true" class="form-control" value="'.stripslashes(POSTval('domainpath', 'public_html/')).'" onfocus=""/>
</div>
</div>
<div class="addon parked mb-4 px-3">
<input type="checkbox" id="wildcard" onchange="handle_wildcard(this)" name="wildcard" '.POSTchecked('wildcard', false).'" />
<label for="wildcard" class="sai_head d-inline-block">'.__('Allow Wildcard').'</label>
<span class="sai_exp2 d-block">'.__('Tick to allow *.domain.com i.e. all subdomains to the domain folder').'</span>
</div>
</div>
<div class="col-12 col-lg-6">
<div class="mb-4 px-3">
<input type="checkbox" id="issue_lecert" name="issue_lecert" '.POSTchecked('issue_lecert', empty($globals['disable_auto_ssl']) ? 1 : 0).'" />
<label for="issue_lecert" class="sai_head d-inline-block">'.__('Issue Let\'s Encrypt certificate').'</label>
<span class="sai_exp2 d-block">'.__('Tick to issue SSL certificate for the new domain').'</span>
</div>';
if(!empty($ips) && (count($ips[4]) > 1 || count($ips[6]) > 1)){
echo '
<div class="mb-4 px-3">
<label for="ip" class="sai_head">'.__('IP Address').'</label>
<span class="sai_exp">'.__('Can be IPv4 or IPv6').'</span>
<select name="ip" id="ip" class="form-select" onchange="handle_ipv6(this)">
<option value="">'.__('Default').'</option>';
foreach ($ips['all'] as $k => $v){
echo '<option value="'.$k.'" '.POSTselect('ip', $k).' type="'.$v['type'].'">'.$k.'</option>';
}
echo '
</select>
</div>';
if(!empty($ips[6])){
echo '
<div class="ipv6 mb-4 px-3">
<label for="ipv6" class="sai_head">'.__('Select IPv6').'</label>
<span class="sai_exp">'.__('You can choose an IPv6 for your domain as well').'</span>
<select name="ipv6" id="ipv6" class="form-select">
<option value="">'.__('Default').'</option>';
foreach ($ips[6] as $k => $v){
echo '<option value="'.$v.'" '.POSTselect('ipv6', $v).'>'.$v.'</option>';
}
echo '
<option value="none" '.POSTselect('ipv6', 'none').'>'.__('None').'</option>
</select>
</div>';
}
}
echo '
</div>
</div>
<div class="my-4 px-3 text-center">
<input type="submit" name="add" class="flat-butt me-2" value="'.__('Add Domain').'" />
</div>
</form>
</div>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
var dompath = false;
function suggestdompath() {
}
$_("domainpath").value = "public_html/" + domain.replace(/^\*\./, "").replace(/([^\w\/\+_\-\.]*?)/, "").trim();
}
function handle_ipv6() {
}else{
$(".ipv6").show();
$("#ipv6").prop("disabled", false);
}
}
function handle_wildcard() {
}else{
$("#domain").val($("#domain").val().replace("*.", ""));
}
}
$(document).ready(function(){
$("#domain_type").on("change", function(){
var val = $("#domain_type").val();
$(".addon, .parked, .subdomain").hide().find("input, select").prop("disabled", true);
$("."+val).show().find("input, select").prop("disabled", false);
});
$("#domain_type, #ip").change();
});
}
softfooter();
}