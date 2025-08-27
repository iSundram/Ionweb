<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function extra_conf_theme() {
}
if(optGET('ajaxdom')){
showconf();
return true;
}
if(optGET('ajaxdel')){
if(!empty($error)){
echo '0'.current($error);
return false;
}
if(!empty($done)){
echo '1'.__('Record deleted successfully');
showconf();
return true;
}
}
softheader(__('Extra Configuration'));
echo '
<div class="card col-12 soft-card p-4"><br>
<div class="sai_main_head"><img src="'.$theme['images'].'extra_conf.png" class="webu_head_img"/>
<h5 class="d-inline-block">'.__('Extra Configuration').'</h5>
</div><hr>';
if(!empty($done)){
echo '  <div class="alert alert-success"><a href="#close" class="close" data-dismiss="alert" aria-lable="close">&times;</a>'.$done.'</div>';
}
error_handle($error);
echo '
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="mxentry" id="editform" enctype = "multipart/form-data" class="form-horizontal">
<div class="row">
<div class="col-sm-5"><label for="selectdomain" class="sai_head ">'.__('Select Domain').'</label></div>
<div class="col-sm-7">
<select name="selectdomain" id="selectdomain" class="form-select mb-3">';
foreach ($domain_list as $key => $value){
if($domain_name == $key){
echo '<option value='.$key.' selected="selected" >'.$key.'</option>';
}else{
echo '<option value='.$key.'>'.$key.'</option>';
}
}
echo '</select>&nbsp;
</div>
</div>
<div class="row">
<div class="col-sm-5"><label for="selectweb" class="sai_head ">'.__('Webservers').'</label></div>
<div class="col-sm-7">
<select name="selectweb" id="selectweb" class="form-select mb-3">';
foreach ($w_list as $key => $value){
echo '<option value='.$key.'>'.$value.'</option>';
}
echo '</select>
</div>
</div><br />
<div class="row">
<div class="col-sm-5">
<label for="destination_upload" class="sai_head " style="margin-top:5px;">'.__('Upload File').'</label>
</div>
<div class="col-sm-7">
<input type="file" name="destination_upload" id="destination_upload" accept=".conf"/>
</div>
</div><br/>
<div class="row">
<div class="text-center">
<input type="submit" value="'.__('Add Record').'" name="create_record" class="flat-butt" id="submitrec"/> &nbsp;
</div><br/>
</div>
</form>
<br>';
echo '
<br><hr>
<div class="row">
<div class="col-sm-12 sai_head text-center">
<h4 style="display:inline;">
'.__('Extra Configuration of').' :
<span id="domain_file">'.$domain_name.'</span>
</h4>
</div>
</div>
<br >
<div id="showrectab">';
showconf();
echo '  </div>
</div>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
$(document).ready(function(){
$("#destination_upload").on("change",function(){
$("#fileselected").text("Selected: "+this.value.split(/[\\\/]/).pop());
});
$("#selectdomain").change(function(){
$(".loading").show();
var domain = $(this).val();
$.ajax({
type: "POST",
url: window.location+"&ajaxdom=1&domain_name="+domain,
success: function(data){
$(".loading").hide();
$("#showrectab").html(data);
$("#domain_file").html(domain);
}
});
});
});
softfooter();
}
function showconf() {
}else{
echo '
<table class="table align-middle table-nowrap mb-0 webuzo-table">
<thead class="sai_head2">
<tr>
<th>'.__('Webservers').'</th>
<th>'.__('Upload File').'</th>
<th style="text-align: right; padding-right: 2%;">'.__('Option').'</th>
</tr>
<thead>
<tbody>';
$i = 1;
foreach ($w_list as $kkey => $vvalue){
foreach ($file_list[$kkey] as $key => $value){
echo '
<tr id="trc'.$kkey.'_'.$key.'" >
<td>
<span value="'.$kkey.'" id="priorityc'.$kkey.'_'.$key.'">'.$vvalue.'</span>
</td>
<td>
<span id="destinationc'.$kkey.'_'.$key.'" class="editfile" cursor:pointer;" >'.$value.'</span>
</td>
<td style="text-align: right; padding-right: 2%;">
<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$key.'" onclick="delete_record(this)" data-editwebserver="'.$kkey.'" data-editdestination="'.$value.'"  data-domain_name="'.$domain_name.'" data-delete_record="'.$i.'"></i>
</td>
</tr>';
$i++;
}
}
echo '
</tbody>
</table>';
}
}
?>