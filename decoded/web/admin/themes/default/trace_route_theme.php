<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function trace_route_theme() {
}else{
echo '
<div class="alert alert-danger d-flex justify-content-center" role="alert">
'.__('Traceroute is currently disabled.').'
</div>';
}
echo '
<div class="sai_form">';
if(!empty($is_traceintsall)){
echo '
<div class="row">
<table class="table sai_form webuzo-table" id="traceroute_table">
<thead>
<tr>
<th width="50%">'.__('Binary').'</th>
<th width="50%">'.__('Permissions').'</th>
</tr>
</thead>
<tbody>
<tr id="">
<td>
'.$binpath1.'
</td>
<td>
'.($globals['trace_route_enabled'] == 1 ? '755' : '700').'
</td>
</tr>
<tr id="">
<td>
'.$binpath2.'
</td>
<td>
'.($globals['trace_route_enabled'] == 1 ? '755' : '700').'
</td>
</tr>
</tbody>
</table>
</div>
<div class="text-center">
<input type="submit" name="trace_route_action" class="btn btn-primary" value="'.($globals['trace_route_enabled'] == 1 ? __('Disable') : __('Enable')).'"/>
</div>';
}else{
echo '
<div class="text-center">
<p>'.__('Trace Route not installed.').'</p>
</div>
<div class="text-center">
<input type="submit" name="trace_route_action" class="btn btn-primary" value="'.__('Install').'"/>
</div>';
}
echo '
</div>
</div>
</form>';
softfooter();
}