<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function usage_stats_theme() {
},
{label: "Free",  data: 100 - '.$stats['cpu'].'}
];
let memorygraph = [
{label: "Used",  data: '.$stats['mem_percent'].'},
{label: "Free",  data: 100 - '.$stats['mem_percent'].'}
];
let diskgraph = [
{label: "Used",  data: '.$stats['disk'].'},
{label: "Free",  data: 100 - '.$stats['disk'].'}
];
server_graph("cpugraph", cpugraph);
server_graph("memorygraph", memorygraph);
server_graph("diskgraph", diskgraph);
});
setInterval(function(){
$.get("'.$globals['request_url'].'&api=json", {}, function(data){
$("#cpu").text(data.stats.cpu);
$("#read_bw").text(data.stats.read_bw);
$("#write_bw").text(data.stats.write_bw);
$("#memory").text(data.stats.memory);
$("#tasks").text(data.stats.tasks);
let cpugraph = [
{label: "Used",  data: data.stats.cpu ? data.stats.cpu : 0.1},
{label: "Free",  data: 100 - data.stats.cpu}
];
let memorygraph = [
{label: "Used",  data: data.stats.mem_percent ? data.stats.mem_percent : 0.1},
{label: "Free",  data: 100 - data.stats.mem_percent}
];
let diskgraph = [
{label: "Used",  data: data.stats.disk ? data.stats.disk : 0.1},
{label: "Free",  data: 100 - data.stats.disk}
];
server_graph("cpugraph", cpugraph);
server_graph("memorygraph", memorygraph);
server_graph("diskgraph", diskgraph);
});
}, 5000);
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
</script>';
softfooter();
}