<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function sharedip_theme() {
}
echo '
</select>
</td>
</tr>
</tbody>
</table>
</div>
<div class="text-center">
<input type="submit" data-save=1 id="save" class="btn btn-primary" value="'.__('Save').'" onclick="save(this)">
</div>
</div>';
}
echo '
</div>
<script>
$("#user_search").on("select2:select", function(e, u = {}){
let user;
if("user" in u){
user = u.user;
}else{
user = $("#user_search option:selected").val();
}
window.location = "'.$globals['index'].'act=sharedip&user="+user;
});
function save() {
});
}
</script>';
softfooter();
}