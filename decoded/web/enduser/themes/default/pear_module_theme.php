<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function pear_module_theme() {
}
}else{
echo '
<tr>
<td colspan="3" class="text-center">'.__('No package available').'</td>
</tr>';
}
echo '
</tbody>
</table>
</div>
<script>
function install_pear() {
}
function action_pear() {
}
if(typeof(data["error"]) != "undefined"){
let str = obj_join("\n", data["error"]);
$("#modIntallLog").html(str).show();
}
},
error: function(){
$(".pearProcess").hide();
$("#modIntallLog").html("'.__js('Oops there was an error while connecting to the $0 Server $1', ['<strong>', '</strong>']).'").show();
},
complete: function(){
myModalEl.find(".modal-header .btn-close").removeAttr("disabled");
myModalEl.find(".modal-footer .btn").removeAttr("disabled");
}
});
});
show_message(a);
}
</script>';
}