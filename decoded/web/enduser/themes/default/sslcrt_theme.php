<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function sslcrt_theme() {
}
}
echo '<option value=newkey>'.__('Generate a new 2048 bit key').'</option>
</select>
<label class="form-label" for="domain">'.__('Domain').'</label>
<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Provide the FQDNs that you are trying to secure. You may use a wildcard domain by adding an asterisk in a domain name in the form: ').'"></i>
<input type="text" name="domain" id="domain" class="form-control mb-3"'.(!empty($error)? 'value="'.POSTval('domain', '').'"' : '').' />
<label class="form-label" for="country">'.__('Country Code').'</label>
<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('2 letter abbreviation e.g. US or IN').'"></i>
<input type="text" name="country" id="country" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('country', '').'"' : '').' />
<label class="form-label" for="state">'.__('State').'</label>
<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of the State or Province').'"></i>
<input type="text" name="state" id="state" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('state', '').'"' : '').' />
<label class="form-label" for="locality">'.__('Locality').'</label>
<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of the City or Town').'"></i>
<input type="text" name="locality" id="locality" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('locality', '').'"' : '').' />
<label class="form-label" for="organisation">'.__('Company Name').'</label>
<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of your Company or Organisation').'"></i>
<input type="text" name="organisation" id="organisation" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('organisation', '').'"' : '').' />
<label class="form-label" for="orgunit">'.__('Company Branch').'</label>
<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of the Organisation branch or Division').'"></i>
<input type="text" name="orgunit" id="orgunit" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('orgunit', '').'"' : '').' />
<label class="form-label" for="email">'.__('Email Address').'</label>
<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Your email address').'"></i>
<input type="text" name="email" id="email" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('email', '').'"' : '').' />
<div class="text-center my-3">
<input type="submit" value="'.__('Create').'" class="flat-butt" name="create_crt" id="submitcrt"/>
</div>
</form>
</div>
</div>
<!-- End Generate Certificate form-->
<!--New Certificate form-->
<div class="col-12 col-lg-6">
<div class="card soft-card p-4 mb-4">
<div class="sai_main_head mb-3">
<h5 class="d-inline-block">'.__('Upload a New Certificate').'</h5>
</div>
<form accept-charset="'.$globals['charset'].'" action="" method="post" enctype="multipart/form-data" name="upload_cert" onsubmit="return submititwithdata(this)" id="upload_cert" data-donereload="1">
<label class="form-label" for="kpaste">'.__('Paste your Certificate here').'</label>
<textarea name="kpaste" id="kpaste" class="form-control mb-2" rows="20" cols="70"></textarea>
<label class="form-label d-block mb-3">'.__('OR').'</label>
<label class="form-label d-block" for="ukey">'.__('Choose the .crt file').'</label>
<input type="file" id="ukey" name="ukey"/>
<div class="text-center">
<input type="hidden" name="install_crt" value="install_crt">
<input type="submit" value="'.__('Upload').'" class="flat-butt" name="install_crt" id="install_crt"/>
</div>
</form>
</div>
</div>
<!--end New Certificate form-->
</div>
<script language="javascript" type="text/javascript">
$(".edit").click(function(){
var d = $(this).data();
return submitit(d, {
handle: function(data){
if("detailcert" in data){
$.each(data.detailcert, function(key, value){
$("#ddomain").html(key);
$("#dkey").html(value.key);
$("#dvalue").html(value.info);
});
$("#detailrectab").slideDown("slide", "", 5000).show();
}
}
});
});
</script>';
softfooter();
}
function showcert() {
}else{
foreach ($crt_list as $key => $value){
$ext = get_extension($value);
if($ext == 'crt'){
$file = get_filename($value);
echo '
<tr>
<td>
<span id="name'.$key.'">'.$file.'</span>
</td>
<td width="2%">
<i title="Show" id="eid'.$file.'" class="fas fa-file-alt edit edit-icon text-center me-2" data-detail_record="'.$file.'"></i>
</td>
<td width="2%">
<i class="fas fa-times delete delete-icon" title="Delete" id="did'.$key.'" onclick="delete_record(this)" data-delete_record="'.$key.'" data-donereload="1"></i>
</td>
</tr>';
}
}
}
echo '
</tbody>
</table>';
}