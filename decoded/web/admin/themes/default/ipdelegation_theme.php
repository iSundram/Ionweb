<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function ipdelegation_theme() {
}
echo '
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
window.location = "'.$globals['index'].'act=ipdelegation&user="+user;
});
$("#select").change(function(){
var val = $("select#select option").filter(":selected").val();
if(val == "open"){
$("#checkboxes").hide();
}else{
$("#checkboxes").show();
}
});
function save() {
});
jEle.data("ips", arr);
var val = $("select#select option").filter(":selected").val();
jEle.data("delegation", val);
var d = jEle.data();
submitit(d,{
done_reload: window.location
});
}
</script>';
softfooter();
}