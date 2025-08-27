<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function ssh_access_theme() {
}else{
$ssh_dir = $user['homedir'].'/.ssh/';
$auth_file = $WE->getsshkeyContent('authorized_keys');
$auth = __('Not Authorized');
$ssh_authentication = __('Authorize');
foreach ($key_list['publickeys'] as $value) {
$file = $WE->getsshkeyContent($value);
if (strpos(base64_decode($auth_file['key_content']), base64_decode($file['key_content'])) !== false){
$auth = __('Authorized');
$ssh_authentication = __('Deauthorize');
}else{
$auth = __('Not Authorized');
$ssh_authentication = __('Authorize');
}
echo '
<tr id="tr'.$value.'">
<td>'.$value.'</td>
<td id="td_value_auth">'.$auth.'</td>
<td>
<a href="javascript:void(0)" data-ssh_key_auth=1 data-ssh_keyname="'.$value.'" data-ssh_auth="'.$ssh_authentication.'" onclick="return this_submitit(this)" data-donereload=1 class="text-decoration-none">
<i class="fas fa-wrench" title="'.$ssh_authentication.'"></i>&nbsp;'.$ssh_authentication.'</a>
</td>
<td width="2%">
<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$value.'" onclick="delete_record(this)" data-delete_ssh_key="'.$value.'" ></i>
</td>
<td width="2%">
<a class="viewKey" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#viewModal" data-keyname="'.$value.'">
<i class="fas fa-file-alt" title="'.__('View').'"></i></a>
</td>
<td width="2%">
<a href="'.$globals['index'].'act=ssh_access&download='.$value.'" title="'.__('Download').'">
<i class="fas fa-download delete edit-icon"></i></a>
</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>
</br>
<h3>'.__('Private Keys').'</h3>
<div id="showprivatekey">
<table class="table align-middle table-nowrap mb-0 webuzo-table" >
<thead class="sai_head2">
<tr>
<th class="align-middle">'.__('Name').'</th>
<th class="align-middle" width="10%"></th>
<th class="align-middle" colspan="3">'.__('Actions').'</th>
</tr>
</thead>
<tbody>';
if(empty($key_list['privatekeys'])){
echo '<tr><td colspan=100><center><span>'.__('No private keys found').' !</span></center></td></tr>';
}else{
foreach ($key_list['privatekeys'] as $value) {
echo '
<tr id="tr'.$value.'">
<td>'.$value.'</td>
<td>
<a href="javascript:void(0)" class="convetppk text-decoration-none" data-bs-toggle="modal" data-bs-target="#convert-ppk" data-keyname="'.$value.'" title="'.__('Convert private key to PPK format').'">
<i class="fas fa-exchange-alt"></i>&nbsp;'.__('Convert').'</a>
</td>
<td width="2%">
<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$value.'" onclick="delete_record(this)" data-delete_ssh_key="'.$value.'"></i>
</td>
<td width="2%">
<a class="viewKey" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#viewModal" data-keyname="'.$value.'" title="'.__('View').'">
<i class="fas fa-file-alt"></i></a>
</td>
<td width="2%">
<a href="'.$globals['index'].'act=ssh_access&download='.$value.'" title="'.__('Download').'">
<i class="fas fa-download delete edit-icon"></i></a>
</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>
</div>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[>
function this_submitit() {
});
}
$(document).ready(function(){
$(document).on("click", ".convetppk", function() {
var keyname = $(this).data("keyname");
$("#key").val(keyname);
});
$(document).on("click", ".viewKey", function() {
var keyname = $(this).data("keyname");
$.ajax({
type: "POST",
dataType : "json",
url: window.location+"&view_key=1&api=json&keyname="+keyname,
success: function(data){
if(data["view_content"]){
$("#modal_content").html(data["view_content"].key_content);
$("#viewModalTitle").html(data["view_content"].modal_title);
}
},
error: function(request,error) {
var a = show_message_r("'.__js('Error').'", "'.__js('Oops there was an error while connecting to the $0 Server $1', ['<strong>','</strong>']).'");
a.alert = "alert-danger";
show_message(a);
}
});
});
});
softfooter();
}