<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function tasks_theme() {
}else{
foreach($tasks as $key => $v){
echo '
<tr id="tr_'.$v['actid'].'" user="'.$v['actid'].'">
<td>'.$v['actid'].'</td>
<td>'.$v['uuid'].'</td>
<td>'.$v['user'].'</td>
<td>'.$v['action_txt'].'</td>
<td>'.$v['started'].'</td>
<td>'.$v['updated'].'</td>
<td>'.$v['ended'].'</td>
<td>'.$v['status_txt'].'</td>
<td>
<div class="progress-cont" id="progress-cont'.$v['actid'].'">
<center><div id="pbar'.$v['actid'].'">'.$v['progress'].'%</center>
<div class="progress progress_'.$v['actid'].'">
<div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width:'.($v['progress']).'%;" id="progressbar'.$v['actid'].'">
</div>
</div>
</div>
</td>
<td>
<a href="javascript:void(0);" onclick="return loadlogs('.$v['actid'].');" class="btn btn-primary btn-logs">'.__('Logs').'</a>
</td>
<td class="text-center" style="padding-top: 18px;">
<a href="'.$globals['index'].'act=tasks&export=html&actid='.$v['actid'].'"><i class="fa-solid fa-file-download" style="font-size:18px" title="Export HTML"></i></a>
</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>';
page_links();
echo '
</form>
</div>';
if(empty($globals['cur_page']) && empty($where)){
echo '
<script>
function refresh_tasks() {
});
}
var tasks_refresher = false;
function toggle_refresher() {
}else{
tasks_refresher = setInterval(refresh_tasks, 5000);
}
console.log(tasks_refresher);
}
$(document).ready(function(){
toggle_refresher();
});
</script>';
}
softfooter();
}