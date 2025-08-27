<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function install_cert_theme() {
}
echo '
</select>
<input type="button" name="fetch_data" value="'.__('Fetch').'" class="flat-butt" id="fetchdata"/>
<img id="createcron" src="'.$theme['images'].'progress.gif" style="display:none">
<div class="sai_exp2 sai_exp_small">'.__('Note: Fetch will search for your installed certificates and load them. If a certificate is not found, you can still manually paste the certificate, key and chain').'</div>
</div>
</div>
<div class="row">
<div class="col-12 col-md-4 col-lg-3">
<label class="form-label" for="kpaste">'.__('Paste your Private Key here').'</label>
</div>
<div class="col-12 col-md-8 col-lg-9">
<textarea name="kpaste" id="kpaste" rows="10" cols="70" class="form-control mb-3" wrap="off"></textarea>
</div>
</div>
<div class="row">
<div class="col-12 col-md-4 col-lg-3">
<label class="form-label" for="cpaste">'.__('Paste your Certificate(CRT) here').'</label>
</div>
<div class="col-12 col-md-8 col-lg-9">
<textarea name="cpaste" id="cpaste" rows="10" cols="70" class="form-control mb-3" wrap="off"></textarea>
</div>
</div>
<div class="row">
<div class="col-12 col-md-4 col-lg-3">
<label class="form-label" for="bpaste">'.__('Paste the CA bundle here (Optional)').'</label>
</div>
<div class="col-12 col-md-8 col-lg-9">
<textarea name="bpaste" id="bpaste" rows="10" cols="70" class="form-control mb-3" wrap="off"></textarea>
</div>
</div>
<div class="text-center">
<input type="submit" value="'.__('Install').'" class="flat-butt" name="install_key" id="submitkey" />
</div>
</form>
</div>
<script language="javascript" type="text/javascript">
$("#fetchdata").click(function(){
var domain = $("#selectdomain").val();
var d = {detail_record: domain};
if(!domain){
alert("Domain Invalid !");
return false;
}
$("#kpaste").html("");
$("#cpaste").html("");
$("#bpaste").html("");
return submitit(d, {
success: function(data){
if("detailicert" in data){
$("#kpaste").html(data.detailicert.key);
$("#cpaste").html(data.detailicert.crt);
$("#bpaste").html(data.detailicert.ca_crt);
}
}
});
});
function disp_type() {
}
</script>';
softfooter();
}
function showcert() {
} else {
foreach($install_list as $key => $value){
$ext = get_extension($value);
if($ext == 'key'){
$file = get_filename($value);
echo '
<tr id="tr'.$file.'">
<td>
<div class="endurl">
<a href="https://'.$file.'" target="_blank" >
<span id="name'.$key.'">'.$file.'</span>
</a>
</div>
</td>
<td width="2%">
<i class="fas fa-trash delete delete-icon" title="Delete" id="did'.$file.'" onclick="delete_record(this)" data-delete_record="'.$key.'"></i>
</td>
</tr>';
}
}
}
echo '
</tbody>
</table>';
}