<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function cpu_theme() {
},
{ label: "Free",  data: '.$cpu['cpu']['percent_free'].'}
];';
return true;
}
softheader(__('CPU Information'));
echo '
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.min.js" type="text/javascript"></script>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function getusage() {
}else{
return true;
}
};
function startusage() {
};
function server_graph_data() {
},
{ label: "Free",  data: '.$cpu['cpu']['percent_free'].'}
];
if(re.length > 0){
try{
eval(re);
}catch(e){ }
}
$_("server_cpu_text").innerHTML = server_cpu[0].data+"% / 100%";
server_graph("server_cpu", server_cpu);
};
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
server_graph_data(\'void(0);\');
startusage();
});
</script>
<style>
.row-centered{
text-aling: center;
}
.col-centered {
display:inline-block;
float:none;
text-align:left;
margin-right:-4px;
}
</style>';
echo '
<div class="soft-smbox col-12 col-md-12 mx-auto p-3">
<div class="sai_main_head">
<img src="'.$theme['images'].'cpu.png" class="webu_head_img"/>'.__(' CPU').'
</div>
</div>
<div class="soft-smbox col-12 col-md-12 mx-auto p-3 mt-4">
<div class="sai_notice">'.__('All respective LOGOS used are trademarks or registered trademarks of their respective companies.').'</div><br>
<div class="row my-3">
<div class="col-12 col-md-6">
<div class="sai_graph_head">'.__('CPU Information').'</div>
<div class="p-3">
<label class="label_si form-label">'.__('CPU Model :').'</label>
<span class="val value_si">'.$cpu['cpu']['model_name'].'</span><br/>
<label class="label_si form-label">'.__('Max Frequency :').'</label>
<span class="val value_si">'.$cpu['cpu']['limit'].' MHz</span><br/>
<label class="label_si form-label">'.__('Cache Size :').'</label>
<span class="val value_si">'.$cpu['cpu']['cache_size'].'</span><br/>
<label class="label_si form-label">'.__('Core Count :').'</label>
<span class="val value_si">'.$cpu['cpu']['core_count'].'</span><br/>
<label class="form-label label_si">'.__('Powered by : ').'</label>
<span class="val value_si pull-left"><img src="'.$theme['images'].$cpu['cpu']['manu'].'.gif" style="width: 100; height: 400;" /></span>
</div>
</div>
<div class="col-12 col-md-6">
<div class="sai_graph_head">'.__('CPU Utilization').'</div>
<div class="p-3">
<div id="server_cpu" class="server_graph" style="margin: auto;"></div>
<div id="server_cpu_text" class="text-center value_si"></div>
</div>
</div>
</div>
</div>';
softfooter();
}