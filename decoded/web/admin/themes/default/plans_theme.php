<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function plans_theme() {
}
echo '
<div class="col-12 '.(empty($SESS['is_reseller']) ? 'col-md-4' : 'col-md-6').'">
<label class="sai_head text-center">'.__('Search By User Name').'</label><br/>
<select class="form-select make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" style="width: 100%" id="user_search" name="user_search">
<option value="'.optREQ('user').'" selected="selected">'.optREQ('user').'</option>
</select>
</div>
</div>
</form>
</div>
</div>
</div>';
error_handle($error);
echo '
<div class="soft-smbox p-4 mt-4">
<div id="showplanlist" class="table-responsive">
<table class="table sai_form webuzo-table" id="planlisttable">
<thead>
<tr>
<th width="15%">'.__('Plan Name').'</th>
<th width="15%">'.__('Owner').'</th>
<th width="10%">'.__('Num Users').'</th>
<th>'.__('Users').'</th>
<th colspan="4" width="2%">'.__('Options').'</th>
</tr>
</thead>
<tbody>';
if(empty($plans)){
echo '
<tr>
<td class="text-center" colspan="4">
<span class="me-1">'.__('No Plan exists').'</span>
<a href="'.$globals['ind'].'act=add_plans">'.__('Create New').'</a>
</td>
</<tr>';
}else{
foreach ($plans as $key => $plan){
echo '
<tr id="tr'.$key.'">
<td>'.$plan['plan_name'].'<br><span class="sai_exp2" style="font-size:10px">'.$plan['slug'].'</span></td>
<td>'.(empty($plan['plan_owner']) ? 'root' : $plan['plan_owner']).'</td>
<td>'.$plan['num_users'].'</td>
<td>'.implode(', ', $plan['users']).'</td>
<td>
<i class="fas fa-copy" title="'.__('Clone').'" style="cursor:pointer;color: darkslategrey;" data-clone_plan="'.$plan['plan_name'].'" onclick="clone_plan(this)"></i>
</td>
<td>
<a href="'.$globals['admin_url'].'act=add_plans&plan='.$key.'">
<i class="fas fa-pencil-alt edit-icon"></i>
</a>
</td>
<td>
<i class="fas fa-trash delete-icon" title="'.__('Delete').'" id="did'.$key.'" data-delete_plan="'.$key.'" onclick="delete_record(this)"></i>
</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>
</form>
</div>
<script language="javascript" type="text/javascript">
$("#plan_search").on("select2:select", function(e, u = {}){
plan = $("#plan_search option:selected").val();
if(plan == "all"){
window.location = "'.$globals['index'].'act=plans";
}else{
window.location = "'.$globals['index'].'act=plans&search="+plan;
}
});
$("#owner_search").on("select2:select", function(e, u = {}){
owner = $("#owner_search option:selected").val();
if(owner == "all"){
window.location = "'.$globals['index'].'act=plans";
}else{
window.location = "'.$globals['index'].'act=plans&owner="+owner;
}
});
$("#user_search").on("select2:select", function(e, u = {}){
user = $("#user_search option:selected").val();
if(user == "all"){
window.location = "'.$globals['index'].'act=plans";
}else{
window.location = "'.$globals['index'].'act=plans&user="+user;
}
});
function clone_plan() {
}
</script>';
softfooter();
}