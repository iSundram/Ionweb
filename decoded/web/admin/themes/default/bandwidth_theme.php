<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function bandwidth_theme() {
};
submitit(d,{
sm_done_onclose:function(){
location.reload();
}
});
});
</script>';
softfooter();
}
function show_band() {
},
{ label: "Free",  data: '.(empty($bandwidth['free_gb']) ? $bandwidth['used_gb']*100 : $bandwidth['free_gb']).'}
];
$_("server_bandwidth_text").innerHTML = server_bandwidth[0].data+" GB";
server_graph("server_bandwidth", server_bandwidth);
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
addjustGraph()
function makedata() {
}
return fdata;
}
var d1 = makedata([0, '.implode(', ', $bandwidth['in']).']);
var d2 = makedata([0, '.implode(', ', $bandwidth['out']).']);
var bandwidth_graph = [
{ label: "In",  data: d1},
{ label: "Out",  data: d2}
];
$.plot($("#bwband_holder"),  bandwidth_graph, {
series: {
stack: true,
points: { show: true },
lines: { show: true, fill: true, steps: false }
},
yaxis:{
tickFormatter: function (v) {
if(v <= 1024)
return Math.round(v) + " M";
if(v > 1024 && v < (1024*1024))
return (v /1024).toFixed(1) + " G";
if(v > (1024*1024))
return (v / (1024*1024)).toFixed(2) + " T"
}
},
legend: {
show: true
},
grid: { hoverable: true}
});
function showTooltip() {
}).appendTo("body").fadeIn(200);
}
var previousPoint = null;
$("#bwband_holder").bind("plothover", function (event, pos, item) {
$("#x").text(pos.x.toFixed(2));
$("#y").text(pos.y.toFixed(2));
if (item) {
if (previousPoint != item.dataIndex) {
previousPoint = item.dataIndex;
$("#tooltip").remove();
var x = item.datapoint[0].toFixed(2),
y = item.datapoint[1].toFixed(2);
showTooltip(item.pageX, item.pageY,
"Total : " + parseInt(y) + " MB <br>Day : " + parseInt(x));
}
} else {
$("#tooltip").remove();
previousPoint = null;
}
});
function addjustGraph() {
}
$(window).resize(function(){
addjustGraph();
});
server_graph_data();
});
function getPrevMonth() {
}
function getNextMonth() {
}
}