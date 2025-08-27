<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function reset_bandwidth_theme() {
}
} else {
echo '<tr><td colspan=9><h4 style="text-align: center">'.__('No Record found').'</h4></td></tr>';
}
echo '
</tbody>
</table>
</div>
</form>
</div>
<script>
$("#checkAll").change(function (){
$("input:checkbox").prop("checked", $(this).prop("checked"));
});
$("input:checkbox").change(function(){
if($(".check:checked").length){
$("#reset_selected").removeAttr("disabled");
}else{
$("#reset_selected").prop("disabled", true);
}
});
function reset_limit() {
});
jEle.data("user", arr);
}else{
jEle.data("user", [jEle.data().user]);
}
var d = jEle.data();
submitit(d,{
done_reload: window.location
});
}
</script>';
softfooter();
}