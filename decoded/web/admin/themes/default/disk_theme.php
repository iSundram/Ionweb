<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function disk_theme() {
}
}
echo '
</tbody>
</table>
</div>
</div>
</div>
</div>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.min.js" type="text/javascript"></script>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
function server_graph_data() {
},
{ label: "Free",  data: '.$disk['disk']['/']['free_gb'].'}
];
var server_inodes = [
{ label: "Used",  data: '.$disk['inodes']['/']['used'].'},
{ label: "Free",  data: '.$disk['inodes']['/']['free'].'}
];
$_("server_disk_text").innerHTML = server_disk[0].data+" GB / "+Math.round((server_disk[0].data+server_disk[1].data)*100)/100+" GB";
$_("server_inodes_text").innerHTML = server_inodes[0].data+"  / "+Math.round((server_inodes[0].data+server_inodes[1].data)*100)/100;
server_graph("server_disk", server_disk);
server_graph("server_inodes", server_inodes);
}
function server_graph() {
}
}
}
},
legend: {
show: false
}
});
}
$(document).ready(function(){
server_graph_data();
});
</script>';
softfooter();
}