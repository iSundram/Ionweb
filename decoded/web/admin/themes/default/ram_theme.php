<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function ram_theme() {
},
{ label: "Free",  data: '.$ram['free'].'}
];';
return true;
}
softheader(__('RAM'));
echo '
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.min.js" type="text/javascript"></script>
<script language="javascript" src="'.$theme['url'].'/js/jquery.flot.pie.min.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
function server_graph_data() {
},
{ label: "Free",  data: '.$ram['free'].'}
];
if(re.length > 0){
try{
eval(re);
}catch(e){ }
}
$_("server_ram_text").innerHTML = server_ram[0].data+" MB / "+(server_ram[0].data+server_ram[1].data)+" MB";
server_graph("server_ram", server_ram);
};
function getusage() {
}else{
return true;
}
};
function startusage() {
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
</script>';
echo '
<div class="col-12 col-md-11 mx-auto soft-smbox p-3">
<div class="sai_main_head">
<img src="'.$theme['images'].'ram.png" class="webu_head_img"/>'.__('RAM').'
</div>
</div>
<div class="col-12 col-md-11 mx-auto soft-smbox p-4 mt-4">
<div class="row">
<div class="col-12 col-md-6">
<div class="sai_graph_head">'.__('RAM Information').'</div>
<div class="p-3">
<label class="form-label label_si">'.__('Total RAM ').': </label>
<span class="val value_si">'.$ram['limit'].__(' MB').'</span><br/>
<label class="form-label label_si">'.(isset($ram['swap']) ? __('SWAP') : __('Burstable')).' : </label>
<span class="val value_si">'.(isset($ram['swap']) ? $ram['swap'] : $ram['burst']).__(' MB').'</span><br/>
<label class="label_si form-label">'.__('Utilised ').':</label>
<span class="val value_si">'.$ram['used'].' MB</span>
</div>
</div>
<div class="col-12 col-md-6">
<div class="sai_graph_head">'.__('RAM Utilization').'</div>
<div class="p-3">
<div id="server_ram" class="server_graph" style="margin: auto;"></div><br>
<div id="server_ram_text" class="text-center value_si">&nbsp;</div>
</div>
</div>
</div>
</div>';
softfooter();
}