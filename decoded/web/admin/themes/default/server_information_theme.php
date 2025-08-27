<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function server_information_theme() {
}
if(count($cpu['output']) > 0){
$info = array(__('Processor #'), __('Vendor : '), __('Name : '), __('Speed : '), __('Cache : '));
$ret = array_chunk($cpu["output"], 5);
$i = 0;
foreach($ret as $key => $value){
foreach($value as $k => $v){
$result = preg_split("/(\w*:)/iu", $v);
echo $info[$i].$result[1].'<br>';
$i++;
if($i == 5){
$i = 0;
echo '<br>';
}
}
}
}
echo '
</td></tr>
</tbody>
</table>
</div>
<br>
<div class="soft-smbox mb-3 table-responsive">
<table class="table align-middle table-nowrap mb-0 webuzo-table">
<div class="sai_form_head">'.__('Memory Information').'</div>
<tbody>';
if(count($memory_info['output']) > 0){
foreach ($memory_info['output'] as $key => $value){
echo'<tr>
<td>'.$value.'</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>
<br>
<div class="soft-smbox mb-3 table-responsive">
<table class="table align-middle table-nowrap mb-0 webuzo-table">
<div class="sai_form_head">'.__('System Information').'</div>
<tbody>';
if(count($system_info['output']) > 0){
foreach($system_info['output'] as $key => $value){
echo'<tr>
<td>'.$value.'</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>
<br>
<div class="soft-smbox mb-3 table-responsive">
<table class="table align-middle table-nowrap mb-0 webuzo-table">
<div class="sai_form_head">'.__('Physical Disks').'</div>
<tbody>';
if(count($physical_disk['output']) > 0){
foreach($physical_disk['output'] as $key => $value){
if($key === 0) continue;
$value = preg_split('/ +/', $value);
$val .= ' : '.$value[0];
}
}
echo '<tr>
<td>'.$val.'</td>
</tr>';
echo '
</tbody>
</table>
</div>
<br>
<div class="soft-smbox mb-3 table-responsive">
<table class="table align-middle table-nowrap mb-0 webuzo-table">
<div class="sai_form_head">'.__('Current Memory Usage').'</div>
<tbody>';
echo '<tr>
<td>
<table>
<tr>
<th></th>
<th>'.__('Total').'</th>
<th>'.__('Used').'</th>
<th>'.__('Free').'</th>
<th>'.__('Shared').'</th>
<th>'.__('Buff/Cache').'</th>
<th>'.__('Available').'</th>
</tr>';
if(count($memory_usage['output']) > 0){
foreach($memory_usage['output'] as $key => $value){
if($key === 0) continue;
$value = preg_split('/ +/', $value);
$type = $value[0];
$total = $value[1];
$used = $value[2];
$free = $value[3];
$shrd = $value[4];
$bufcach = $value[5];
$availble = $value[5];
echo '
<tr>
<td>'. $type .'&nbsp;</td>
<td>'. $total .'&nbsp;</td>
<td>'. $used .'</td>
<td>'. $free .'</td>
<td>'. $shrd .'</td>
<td>'. $bufcach .'</td>
<td>'. $availble .'</td>
</tr>';
}
}
echo '</table>
</td>
</tr>
</tbody>
</table>
</div>
<br>
<div class="soft-smbox mb-3 table-responsive">
<table class="table align-middle table-nowrap mb-0 webuzo-table">
<div class="sai_form_head">'.__('Current Disk Usage').'</div>
<tbody>
<tr><td>
<table>
<tr>
<th>'.__('FILE SYSTEM').'</th>
<th>'.__('SIZE').'</th>
<th>'.__('USED').'</th>
<th>'.__('AVAILABLE').'</th>
<th>'.__('USE %').'</th>
<th>'.__('Mounted on').'</th>
</tr>';
if(count($disk['output']) > 0){
foreach($disk['output'] as $key => $value){
if($key === 0) continue;
$value = preg_split('/ +/', $value);
$filename = $value[0];
$size = $value[1];
$used = $value[2];
$available = $value[3];
$usedpercent = $value[4];
$mount = $value[5];
echo '<tr>
<td>'. $filename .'&nbsp;</td>
<td>'. $size .'</td>
<td>'. $used .'</td>
<td>'. $available .'</td>
<td>'. $usedpercent .'</td>
<td>'. $mount .'</td>
</tr>';
}
}
echo '</table>
</td></tr>
</tbody>
</table>
</div>
</div>';
softfooter();
}