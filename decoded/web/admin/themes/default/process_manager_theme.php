<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function process_manager_theme() {
}
}
$users[] = $ps[2];
}
echo '
</select>
</div>
<div class="col-sm-5">
<input type="submit" class="btn btn-primary" name="kill_process_by_user" value="'.__('Kill user\'s processes').'"/>
</div>
</div></br>
</form>
<div id="proc-table-div" class="table-responsive mt-4">
<table id="process-list" border="0" cellpadding="8" cellspacing="1"  class="table sai_form webuzo-table">
<thead>
<tr>
<th>'.__('PID').'</th>
<th>'.__('Owner').'</th>
<th>'.__('Priority').'</th>
<th>'.__('CPU %').'</th>
<th>'.__('Memory %').'</th>
<th width="40%">'.__('Command').'</th>
</tr>
</thead>
<tbody>';
if (count($psOutput) > 0) {
foreach ($psOutput as $k => $ps) {
if ($k < 7) continue;
$ps = preg_split('/ +/', ltrim($ps));
$pid = $ps[0];
$owner = $ps[1];
$priority = $ps[3];
$cpu = $ps[8];
$mem = $ps[9];
if(count($ps) >= 11){
$command = implode(' ', array_slice($ps, 11));
}
echo '
<tr>
<td>'. $pid .'&nbsp;(<a class="pm_link trace_pid" href="javascript:void(0)" pid="'.$pid.'" onclick="trace_process(this)">'.__('Trace').'</a>)&nbsp;(<a class="pm_link kill_pid" href="'.$globals['admin_url'].'act=process_manager&kill_process_pid='.$pid.'">'.__('Kill').'</a>)</td>
<td>'. $owner .'</td>
<td>'. $priority .'</td>
<td>'. $cpu .'</td>
<td>'. $mem .'</td>
<td>'. $command .'</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>
<div id="TraceDiv">
<div id="TraceLogs">
</div>
<div style="text-align: center;">
<a class="btn btn-primary" href="'.$globals['admin_url'].'act=process_manager" >
<i class="fas fa-arrow-circle-left" aria-hidden="true"></i>'.__('Back').'
</a>
</div>
</div>
</div>
<script>
$(document).ready(function(){
$("#TraceDiv").hide();
})
function trace_process() {
});
var pid = $(el).closest("a").attr("pid");
$.ajax({
type: "POST",
dataType: "json",
url: "'.$globals['index'].'act=process_manager&api=json&trace_process_pid="+pid,
success: function(data){
if(data.error && data.error.err_timeout){
var a = show_message_r(l.error, data.error.err_timeout);
a.alert = "alert-danger";
show_message(a);
return;
}
getTraceLog(pid);
setInterval(getTraceLog,5000,pid);
}
});
}
function getTraceLog() {
}
}
});
}
</script>';
softfooter();
}