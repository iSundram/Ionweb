<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function bg_process_killer_theme() {
}
</style>
<div class="soft-smbox p-3">
<div class="sai_main_head">
<i class="fas fa-cogs me-1"></i> '.__('Background Process Killer').'
</div>
</div>
<div class="soft-smbox p-3 mt-4">
<div class="row">
<div class="col-md-12" style="margin-bottom:25px;">
'.__('You can configure Webuzo to kill any of the following processes and send you an email when it finds one of them. $0 Malicious users may run an IRC bouncer on their shell accounts even though this may be against your policy. Webuzo detects these processes correctly even if the bouncer is renamed (e.g. to something that appears non-malicious like “pine”, to give the impression that the user is just reading email). $0
Please check the names of any programs you do not want running on your server; we recommend that you check them all since letting users run IRC bots and servers usually leads to denial-of-service attacks.', ['<br>']).'
</div>
</div>
<form action="" method="POST" name="killprocess" id="killprocess" class="form-horizontal" onsubmit="return submitit(this)">
<div class="row my-2">
<div class="col-md-4">
<label><strong>'.__('Processes').'</strong></label> <button type="button" class="btn btn-sm btn-primary" id="selectall" value="selectall"> '.__('Select All').'</button><br>';
foreach($process as $key => $ps_name){
(in_array($ps_name, $conf['process'])) ? $check = 'checked' : $check = '';
echo '<input class="form-group proc" type=checkbox name=killprocess[] value="'.$ps_name.'" '.$check.'> '.$ps_name.' <br>';
}
echo '
</div>
<div class="col-md-4">
<label><strong>'.__('Trusted users (optional)').'</strong><i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Choose trusted users that should be ignored by the process killer. Note: root, mysql, named, webuzo and users with UIDs below 99 are already present in the trusted users list hence they do not need to be added here.').'"></i></label>
<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" style="width: 100%" id="userlist" name="userlist">
<option value="'.optGET('user', $_user).'" selected="selected">'.optGET('user', $_user).'</option>
</select>
<ul class="list_trusted_users mt-4 p-0" id="list_trusted_users">';
foreach($conf['users'] as $key => $user){
echo '
<li class="pfe_item" id='.$user.' >'.$user.'<i id='.$user.' class="fas fa-times delete delete-icon" onclick="remove_user(this.id)"></i><input type="hidden" value="'.$user.'" name="trustedusers[]"></li>';
}
echo '
</ul>
</div>
</div>
<div class="row my-2">
<div class="col-md-8 text-center">
<input class="btn btn-primary center" type="submit" name="submit" id="save" value="'.__('Save').'">
</div>
</div>
</form>
</div>
<script>
$("#selectall").click(function(){
let selbtn = $(this).val();
if(selbtn == "selectall"){
$("#selectall").val("deselectall");
$("#selectall").html("Deselect All");
$(".proc").prop("checked", true);
}else{
$("#selectall").val("selectall");
$("#selectall").html("Select All");
$(".proc").prop("checked", false);
}
});
function remove_user() {
}
$("#userlist").on("select2:select", function(e, u = {}){
let user;
if("user" in u){
user = u.user;
}else{
user = $("#userlist option:selected").val();
}
let checkuser = [];
$("#list_trusted_users li").each(function(index) {
checkuser[index] = $(this).text();
});
if(!checkuser.includes(user)){
$("#list_trusted_users").append(\'<li class="pfe_item" id=\'+user+\' >\'+user+\'<i id=\'+user+\' class="fas fa-times delete delete-icon" onclick="remove_user(this.id)"></i><input type="hidden" value=\'+user+\' name="trustedusers[]"></li>\');
}
});
</script>';
softfooter();
}