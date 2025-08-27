<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function compiler_access_theme() {
}
echo '
</select>
<button class="btn btn-primary ms-3" onclick="return add_ca_user()">'.__('Add into the Group').'</button>
</div>
<div class="table-responsive mt-4">
<table class="table sai_form webuzo-table">
<thead>
<tr>
<th>#</th>
<th>'.__('Compiler group user(s)').'</th>
<th style="text-align:right">'.__('Options').'</th>
</tr>
</thead>
<tbody>';
if(empty($ca_users)){
echo '
<tr>
<td colspan="3" class="text-center">'.__('There is no user in the Compiler group yet !').'</td>
</tr>';
}else{
foreach($ca_users as $k => $v){
echo '
<tr id="tr'.$v.'">
<td>'.$k.'</td>
<td>'.$v.'</td>
<td style="text-align:right"><i class="fas fa-trash delete delete-icon" title="Delete" id="did'.$v.'" data-remove=1 data-user="'.$v.'" onclick="delete_record(this)"></i></td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>
</div>
<script>
function manage_access() {
}else{
lang = "'.__js('Are you sure you want to disable compiler access for users not in the compiler group ?').'";
}
var a = show_message_r("'.__js('Warning').'", lang);
a.alert = "alert-warning";
a.confirm.push(function(){
submitit(d, {
done_reload : window.location.href
});
});
show_message(a);
}
function add_ca_user() {
};
var a = show_message_r("'.__js('Warning').'", "'.__js('Do you want to add the user to the compiler group ?').'");
a.alert = "alert-warning";
a.confirm.push(function(){
submitit(d, {
done_reload : window.location.href
});
});
show_message(a);
}
</script>';
softfooter();
}