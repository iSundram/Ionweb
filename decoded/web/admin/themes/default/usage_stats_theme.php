<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function usage_stats_theme() {
}
echo '
<script>
$("#user").on("select2:select", function(e, u = {}){
let user;
if("user" in u){
user = u.user;
}else{
user = $("#user option:selected").val();
}
window.location = "'.$globals['index'].'act=usage_stats&user="+user;
});
setInterval(function(){
$.get("'.$globals['request_url'].'&api=json", {}, function(data){
if(data.stats){
$("#cpuusage").text(data.stats.cpu);
$("#readbw").text(data.stats.read_bw);
$("#writebw").text(data.stats.write_bw);
$("#mem").text(data.stats.memory);
$("#task").text(data.stats.tasks);
}
});
}, 5000);
</script>';
softfooter();
}