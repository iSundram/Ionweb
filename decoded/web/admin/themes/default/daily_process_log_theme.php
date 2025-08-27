<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function daily_process_log_theme() {
}else{
foreach ($user_res_log as $index => $value) {
echo '<tr>
<td class="align-middle">'.$value['user'].'</td>
<td class="align-middle">'.$value['domain'].'</td>
<td class="align-middle text-center">'.$value['percpu'].'</td>
<td class="align-middle text-center">'.$value['permem'].'</td>
<td class="align-middle text-center">'.$value['pmysql'].'</td>
</tr>';
};
}
echo '</tbody>
</table>';
page_links();
echo'
<p class="text-center"><strong>'.__('Top Processes').'</strong></p>
<table class="table sai_form webuzo-table" >
<thead class="sai_head2" style="background-color: #EFEFEF;">
<tr>
<th class="align-middle">'.__('User').'</th>
<th class="align-middle">'.__('Domain').'</th>
<th class="align-middle text-center">'.__('% CPU').'</th>
<th class="align-middle">'.__('Process').'</th>
</tr>
</thead>
<tbody>';
if(empty($top_ps)){
echo '<tr>
<td class="text-center" colspan="5">'.__('No Top Process Found...!').'</td>
</tr>';
}else{
foreach ($top_ps as $index => $value) {
echo '<tr>
<td class="align-middle">'.$value['owner'].'</td>
<td class="align-middle">'.$value['domain'].'</td>
<td class="align-middle text-center">'.$value['cpu'].'</td>
<td class="align-middle">'.$value['cmd'].'</td>
</tr>';
};
}
echo '</tbody>
</table>
</div>';
softfooter();
}