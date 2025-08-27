<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function login_logs_theme() {
}
echo '
</div>
</form>
</div>
</div>
</div>
<div class="soft-smbox p-3 mt-4">';
page_links();
echo '
<div id="show_log">
<div id="display_tab" class="table-responsive">
<table class="table sai_form webuzo-table">
<thead>
<tr>
<th>'.__('Date').'</th>
<th>'.__('User').'</th>
<th>'.__('IP Address').'</th>
<th width="10%">'.__('Status').'</th>
</tr>
</thead>
<tbody>';
if(!empty($logs)){
foreach ($logs as $key => $value){
echo '
<tr id="tr'.$key.'">
<td>'.datify($value['time'], 1, 1, 0).'</td>
<td>'.$value['user'].'</td>
<td>'.$value['ip'].'</td>
<td>'.(empty($value['status']) ? '<font color="#FF0000">'.__('Failed').'</font>' : '<font color="#009900">'.($value['status'] == 2 ? '2FA -' : '').__('Successful').'</font>').'</td>
</tr>';
}
echo '
</tbody>
</table>
<div class="text-center">
<input type="submit" value="'.__('Delete All Records').'" name="delete" id="delete" class="btn btn-primary" data-delete_all=1 />
</div>';
}else{
echo '
<tr>
<td colspan="100" class="text-center">
<span>'.__('There are no login logs as of now').'</span>
</td>
</<tr>
</tbody>
</table>';
}
echo '
</div>
</div>
</div>
<script language="javascript" type="text/javascript">
$("#user_search").on("select2:select", function(e, u = {}){
user = $("#user_search option:selected").val();
if(user == "all"){
window.location = "'.$globals['index'].'act=login_logs";
}else{
window.location = "'.$globals['index'].'act=login_logs&user="+user;
}
});
$("#owner_search").on("select2:select", function(e, u = {}){
console.log(111);
owner = $("#owner_search option:selected").val();
if(owner == "all"){
window.location = "'.$globals['index'].'act=login_logs";
}else{
window.location = "'.$globals['index'].'act=login_logs&owner="+owner;
}
});
$("#delete").click(function() {
var jEle = $(this);
a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete the Login Logs ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
var d = jEle.data();
submitit(d, {
done_reload:window.location
});
});
show_message(a);
});
</script>';
softfooter();
}