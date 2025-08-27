<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function modsec_conf_theme() {
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
<h5>'.__('Configure Global Directives').'
<span class="sai_exp">'.__('This interface allows you to configure a number of global settings for ModSecurity. For more information about each supported directive, you can review additional details using the links provided with each directive.').'</span>
</h5><br/>';
foreach($conf_options as $k => $v){
echo '
<div class="row">
<div class="col-11 col-sm-5">
<label class="sai_head">'.
$v['lbl'];
if(!empty($v['sublbl'])){
echo '
<span class="sai_exp">'.$v['sublbl'].'</span>';
}
echo '
</label>
<br/>';
if(!empty($v['doc_title'])){
echo '
<a href="'.(!empty($v['doc_link']) ? $v['doc_link'] : '#').'" target="_blank" title="'.$v['doc_title'].'">'.$v['conf_name'].'<span class="fas fa-external-link-alt"></span></a>';
}
echo '
</div>
<div class="col-1 col-sm-7">';
if($v['type'] == 'radio'){
foreach($v['options'] as $ok => $ov){
echo '
<label>
<input type="radio" name="'.$k.'" value="'.$ok.'" '.($v['selected'] == $ok ? 'checked' : '').'/> '.$ov['lbl'].'
'.(!empty($ov['badge']) ? '<span class="badge bg-'.(!empty($ov['badge_class']) ? $ov['badge_class'] : 'info' ).'">'.$ov['badge'].'</span>' : '' ).'
</label><br>';
}
}elseif($v['type'] == 'text' || $v['type'] == 'number'){
echo'
<input class="form-control" type="'.$v['type'].'" name="'.$k.'" id="'.$k.'" value="'.$v['default'].'"/>
<div class="alert alert-danger" style="display:none;" role="alert" id="'.$k.'_msg">'.(!empty($v['error']) ? $v['error'] : '').'</div>';
}
echo '
</div>
</div><br>';
}
echo '
<div class="text-center my-4">
<input type="submit" class="btn btn-primary" name="save_modsec_conf" value="'.__('Save Settings').'" id="submitconf">
<input type="button" id="refresh" class="btn btn-light" value="'.__('Refresh').'">
</div>';
}
echo '
</form>
</div>
<script>
$("#secguardianlog").keyup(function(){
var txt = $(this).val();
if(txt && !txt.startsWith("|")){
$("#secguardianlog_msg").show();
}else{
$("#secguardianlog_msg").hide();
}
});
$("#sechttpblkey").keyup(function(){
var pat = /^[a-z]{12}$/;
var key = $(this).val();
if(key && !pat.test(key)){
$("#sechttpblkey_msg").show();
}else{
$("#sechttpblkey_msg").hide();
}
});
$("#refresh").click(function(){
window.location.reload();
})
</script>';
softfooter();
}