<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function current_disk_usage_theme() {
}
}
echo '
</tbody>
</table>
</div>
</div>';
echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
echo '
<div class="soft-smbox p-3 col-md-auto text-center">
<div class="sai_main_head text-center mt-2">
<i class="fas fa-chart-bar me-2" style="color:black;"></i> '.__('IO Statistics').'
</div>
<hr>';
echo '
<div id="" class="table-responsive mt-4">
<table id="" border="0" cellpadding="8" cellspacing="1"  class="table sai_form webuzo-table">
<tbody>';
if (count($io['output']) > 0) {
foreach ($io['output'] as $k => $is) {
$is = preg_split('/ +/', $is);
$dv = $is[0];
$tps = $is[1];
$brc = $is[2];
$bwc = $is[3];
$tbr = $is[4];
$tbw = $is[5];
echo '
<thead>
<tr>
<th>'.__('Device').'</th> 	<td>'. $dv .'&nbsp;</td>
</tr>
<tr>
<th>'.__('Trans/Sec').'</th> 	<td>'. $tps .'</td>
</tr>
<tr>
<th>'.__('Blocks Read/Sec').'</th> 	<td>'. $brc .'</td>
</tr>
<tr>
<th>'.__('Blocks Written/Sec').'</th> 	<td>'. $bwc .'</td>
</tr>
<tr>
<th>'.__('Total Blocks Read').'</th> 	<td>'. $tbr .'</td>
</tr>
<tr>
<th width="40%">'.__('Total Blocks Written').'</th>	 <td>'. $tbw .'</td>
</tr>
</thead>';
}
}
echo '
</tbody>
</table>
</div>
</div>&nbsp;&nbsp;&nbsp;
</div>';
softfooter();
}