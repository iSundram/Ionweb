<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function reseller_resource_usage_theme() {
}
error_handle($error);
if(!empty($resource_total)){
echo '
<div class="table-responsive mt-4">
<table class="table sai_form webuzo-table">
<tbody>
<tr align="center">
<th  width="30%" >'.__('Resource').'</th>
<th  width="10%">'.__('Limit').'</th>
<th  width="10%">'.__('Used').'</th>
<th  width="10%">'.__('Allocated').'</th>
<th  width="40%">'.__('Information').'</th>
</tr>';
foreach($resource_total as $k => $v){
if(empty($reseller_privileges_fields['Total Limits']['list'][$v['key']]['heading'])){
continue;
}
$units = empty($reseller_privileges_fields['Total Limits']['list'][$v['key']]['units']) ? '' : ' '.$reseller_privileges_fields['Total Limits']['list'][$v['key']]['units'];
echo '
<tr>
<td rowspan="2">'.$reseller_privileges_fields['Total Limits']['list'][$v['key']]['heading'].'</td>
<td align="center">'.$resource_total[$k]['limit'].$units.'</td>
<td align="center">'.$resource_total[$k]['used'].$units.'</td>
<td align="center">'.$resource_total[$k]['allocated'].$units.'</td>
<td rowspan="2">'.$reseller_privileges_fields['Total Limits']['list'][$v['key']]['exp'].'</td>
</tr>
<tr>
<td colspan="3">
<div class="progress disk-bar " style="height: 10px;">
<div style="cursor:pointer;width:'.$resource_total[$k]['percent'].'%;" aria-valuemax="100" aria-valuemin="0" role="progressbar" class="progress-bar '.($resource_total[$k]['percent'] >= 90 ? "bg-danger" : ($resource_total[$k]['percent'] >= 80 ? "bg-warning" : "prog-blue")).' progress-bar-striped progress-bar-animated" data-placement="right" data-toggle="tooltip">
</div>
</div>
</td>
</tr>';
}
echo'
</tbody>
</table>
</div>';
}
echo'
</div>
<script>
$("#user_search").on("select2:select", function(e, u = {}){
let user;
if("user" in u){
user = u.user;
}else{
user = $("#user_search option:selected").val();
}
window.location = "'.$globals['index'].'act=reseller_resource_usage&user="+user;
});
</script>';
softfooter();
}