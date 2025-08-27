<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function domainedit_theme() {
}
if($domain_type == 'parked' || $domain_type == 'addon'){
echo '
<div class="addon parked mb-4 px-3">
<input type="checkbox" id="wildcard" name="wildcard" '.POSTchecked('wildcard', $domains_list[$domain]['wildcard']).'" />
<label for="wildcard" class="sai_head d-inline-block">'.__('Allow Wildcard').'	</label>
<span class="sai_exp2 d-block">'.__('Tick to allow *.domain.com i.e. all subdomains to the domain folder').'</span>
</div>';
}
echo '
</div>
<div class="col-12 col-lg-6">
<div class="mb-4 px-3">
<input type="checkbox" id="issue_lecert" name="issue_lecert" '.POSTchecked('issue_lecert', false).'" />
<label for="issue_lecert" class="sai_head d-inline-block">'.__('Issue Let\'s Encrypt certificate').'</label>
<span class="sai_exp2 d-block">'.__('Tick to issue SSL certificate from LE for the new domain').'</span>
</div>';
if(!empty($ips) && (count($ips[4]) > 1 || count($ips[6]) > 1)){
echo '
<div class="mb-4 px-3">
<label for="ip" class="sai_head">'.__('IP Address').'</label>
<span class="sai_exp">'.__('Can be IPv4 or IPv6').'</span>
<select name="ip" id="ip" class="form-select" onchange="handle_ipv6(this)">
<option value="">'.__('Default').'</option>';
foreach ($ips['all'] as $k => $v){
echo '<option value="'.$k.'" '.POSTselect('ip', $k, ($domains_list[$domain]['ip'] == $k)).' type="'.$v['type'].'">'.$k.'</option>';
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
echo '<option value="'.$v.'" '.POSTselect('ipv6', $v, ($v == $domains_list[$domain]['ipv6']) ).'>'.$v.'</option>';
}
echo '
<option value="none" '.POSTselect('ipv6', 'none', ('none' == $domains_list[$domain]['ipv6']) ).'>'.__('None').'</option>
</select>
</div>';
}
}
echo '
</div>
</div>
<div class="my-4 px-3 text-center">
<input type="submit" name="edit" class="flat-butt me-2" value="'.__('Edit Domain').'" />
</div>
</form>
</div>
<script language="javascript" type="text/javascript">
function handle_ipv6() {
}else{
$(".ipv6").show();
$("#ipv6").prop("disabled", false);
}
}
$(document).ready(function(){
$("#ip").change();
});
softfooter();
}