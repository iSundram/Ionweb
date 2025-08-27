<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function change_owner_theme() {
}
}else{
echo '<tr><td colspan=9><h4 style="text-align: center">'.__('No Record found').'</h4></td></tr>';
}
echo '
</tbody>
</table>
</div>
</form>';
page_links();
}
echo '
</div>';
echo'
<script>
$("#owner_search").on("select2:select", function(){
user = $("#owner_search option:selected").val();
window.location = "'.$globals['index'].'act=change_owner&owner_search="+user;
});
$("#checkAll").change(function(){
$("input:checkbox").prop("checked", $(this).prop("checked"));
});
</script>';
softfooter();
}