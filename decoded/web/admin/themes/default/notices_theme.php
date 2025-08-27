<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function notices_theme() {
}else{
foreach($notices as $k => $v){
echo '
<tr>
<td>'.$v['nid'].'</td>
<td>'.$v['nkey'].'</td>
<td width="20%">'.$v['title'].'</td>
<td width="30%">'.$v['body'].'</td>
<td>'.(!empty($v['dismissable']) ? '<input type="checkbox" title="dismiss notice" name="dismiss_'.$v['nid'].'" '.(!empty($v['dismissed']) ? 'checked disabled' : '').' data-nid="'.$v['nid'].'" class="notice-disable">' : '').'</td>
<td>'.datify($v['created']).'</td>
<td>'.datify($v['updated']).'</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>';
page_links();
echo '
</div>
<script>
$(".notice-disable").click(function(){
if($(this).is(":checked")){
$(this).attr("disabled", true);
dismiss_notice(this);
}
});
$(document).ready(function () {
show_search();
});
function show_search() {
}else if(searchin == "dismissed"){
$("#dismiss").show();
$("#searchbox").hide();
$(".date").hide();
}else{
$("#searchbox").show();
$(".date").hide();
$("#dismiss").hide();
}
};
</script>';
softfooter();
}