<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function awstats_theme() {
}
if(!empty($done) && !empty($domain)){
echo '1'.$domain.'_'.$awstat_key;
return true;
}
}
softheader(__('AWStats Install'));
echo '
<div class="card col-7 soft-card p-3 mx-auto">
<div class="sai_main_head ">
<img src="'.$theme['images'].'awstats.png" class="webu_head_img me-2"/>
<h5 class="d-inline-block">'.__('AWStats | View Site Statistics').'</h5>
</div>
</div>
<div class="card col-7 soft-card p-4 mx-auto mt-4">
<div class="sai_sub_head mt-2">'.__('AWStats is a powerful and featureful tool that generates advanced web, streaming, FTP or mail server statistics, graphically.').'</div>
<div class="table-responsive">
<table class="table align-middle table-nowrap mb-0 webuzo-table">
<thead class="sai_head2">
<th>'.__('Select Domain').'</th>
<th width="10%">'.__('View').'</th>
</thead>
<tbody>';
foreach($domains_list as $key => $value){
echo '<tr id="tr'.$key.'">
<td>'.$key.'</td>
<td><span class="fa fa-eye fa-2x awstats_view text-primary" title="View" id="did'.$key.'" data-domain="'.$key.'" style="cursor:pointer"></td>
</tr>';
}
echo '
</tbody>
</table>
</div>
</div>
<script language="javascript" type="text/javascript" >
$(document).ready(function(){
$(".awstats_view").click(function() {
var jEle = $(this), d = jEle.data();
d.awstats_config = d.domain;
submitit(d , {
handle:function(data){
var wprotocol = location.protocol;
var ip = location.host;
if(data.done){
window.open(wprotocol+"//"+ip+"/'.$SESS['token'].'/awstats/cgi-bin/awstats.pl?config="+d.domain);
}else{
alert("'.__js('Oops!!! There was an error loading statistics').'");
}
}
});
});
});
</script>';
softfooter();
}
?>