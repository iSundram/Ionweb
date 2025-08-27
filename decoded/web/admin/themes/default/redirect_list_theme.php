<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function redirect_list_theme() {
}
}else{
echo '
<tr>
<td colspan="100"><h3 style="text-align: center">'.__('No Record found').'</h3></td>
</tr>';
}
echo '
</tbody>
</table>
</div>
</div>
<script>
$(document).ready(function () {
$("#user_search").on("select2:select", function(){
user = $("#user_search option:selected").val();
window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'&user_search="+user;
});
$("#dom_search").on("select2:select", function(){
var domain_selected = $("#dom_search option:selected").val();
window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'&dom_search="+domain_selected;
});
});
</script>';
softfooter();
}