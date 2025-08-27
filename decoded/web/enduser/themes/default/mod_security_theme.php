<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function mod_security_theme() {
}
echo '
</tbody>
</table>
</div>
</div>
<script>
function modtoggle() {
}
var a, lan;
if(d.mod_security == "0" || d.mod_security == "3"){
if("domain" in d){
lan = "'.__js('Are you want to $0 Disable $1 Mod Security for domain ', ['<b>','</b>']).'<b>"+d.domain+"</b>";
}else{
lan = "'.__js('Are you want to $0 Disable $1 Mod Security for all the domain', ['<b>','</b>']).'";
}
}else{
if("domain" in d){
lan = "'.__js('Are you want to $0 Enable $1 Mod Security for domain ', ['<b>','</b>']).'<b>"+d.domain+"</b>";
}else{
lan = "'.__js('Are you want to $0 Enable $1 Mod Security for all the domain', ['<b>','</b>']).'";
}
}
a = show_message_r("'.__js('Warning').'", lan);
a.alert = "alert-warning";
a.confirm.push(function(){
submitit(d, {
sm_done_onclose: function(){
if("domain" in d){
let status = d.mod_security == "1" ? true : false;
$(jEle).prop("checked", status);
$(jEle).attr("data-mod_security", ((status) ? 0 : 1));
}
if("donereload" in d){
location.reload();
}
}
});
});
a.no.push(function(){
if("domain" in d){
let status = d.mod_security ? false : true;
$(jEle).prop("checked", status);
}
});
a.onclose.push(function(){
if("domain" in d){
let status = d.mod_security ? false : true;
$(jEle).prop("checked", status);
}
});
show_message(a);
}
</script>';
softfooter();
}