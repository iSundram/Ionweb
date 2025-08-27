<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function bandwidth_theme() {
}
</style>
';
echo '
<div class="card soft-card p-3">
<div class="sai_main_head">
<img src="'.$theme['images'].'bandwidth.png" alt="" class="webu_head_img me-2"/>
<h5 class="d-inline-block">'.__('Bandwidth').'</h5>
</div>
</div>
<div class="card soft-card p-4 mt-4">';
$colorcode = ['#ff0000','#ff00ff', '#ffff00', '#aaff00', '#ff00bf', '#5500ff', '#669999', '#cc0000'];
$protocols = ['http', 'imap', 'pop3', 'ftp', 'smtp'];
echo js_url(['jquery.flot.min.js', 'jquery.flot.pie.min.js', 'jquery.flot.resize.min.js', 'jquery.flot.time.min.js', 'jquery.flot.tooltip.min.js', 'jquery.flot.stack.min.js', 'jquery.flot.symbol.min.js', 'jquery.flot.axislabels.js'], 'flot.combined.js');
if(!empty($day) && !empty($month) && !empty($year)){
for($i = 0; $i < 24; $i++){
$xaxis[] = $i;
}
$tstamp = strtotime($day.' '.$month.' '.$year);
$yaxis = [];
echo '
<h4 class="data-descriptor text-center">
'.date('D, M d, Y', $tstamp).'
</h4>
<div class="row text-center">
';
$protocol_langs = array('http' => __('HTTP Traffic'),
'imap' => __('IMAP Traffic'),
'smtp' => __('SMTP Traffic'),
'pop3' => __('POP3 Traffic'),
'ftp' => __('FTP Traffic')
);
foreach($protocols as $k => $p){
$c = $colorcode[$k];
if(empty($c)){
$c = rand_color();
}
$yaxis[$p] = ['label' => $protocol_langs[$p], 'color' => $c];
}
foreach($bandwidth['day_band'] as $hk => $hv){
$yaxis[$hv['type']]['data'][] = [date('H.i', $hv['unixtime']), $hv['bytes']];
}
echo '
<div class="alert alert-info" role="alert" style="display:'.(empty($bandwidth['day_band']) ? '' : 'none').'">
'.__('There is no data for this period.').'
</div>';
echo '
<div class="col-12" style="display:'.(!empty($bandwidth['day_band']) ? '' : 'none').'">
<div id="mon-band-bar" class="mx-auto" style="width:700px; height:400px;"></div>
</div>';
echo '
</div>
<div class="row">
<div class="col-12 text-center">
<a id="lnkReturn" href="javascript:history.go(-1)">
<i class="fas fa-arrow-left" aria-hidden="true"></i>
'.__('Go Back').'
</a>
</div>
</div>';
}elseif(!empty($month) && !empty($year)){
$d = date('t', strtotime($month.' '.$year));
$xaxis = [];
echo '
<div class="text-center">
<h4 class="data-descriptor">
'.(!empty($domain) ? $domain : strtoupper($type)).' - '.date('M Y', strtotime($month.' '.$year)).'
</h4>
</div>';
echo '
<div class="row">
<div class="col-12">
<div id="mon-band-bar" class="mx-auto" style="width:700px; height:400px;"></div>
<div class="text-center my-3">
<button id="prevMonth" onclick="getPrevMonth()" class="btn flat-butt">Prev Month</button>
<button type="button" class="btn flat-butt" onclick="getNextMonth()" '.(((date_parse($next_month["month"])['month'] > date("m") && $next_month["year"] == date("Y")) || $next_month["year"] > date("Y")) ?"disabled":"").'>Next Month</button>
</div>
</div>
</div>';
echo '
<div class="table-responsive my-4">
<table border="0" cellpadding="8" cellspacing="1" width="100%" class="table align-middle table-nowrap mb-0 webuzo-table td_font">
<thead class="sai_head2">
<tr class="text-center">
<th>'.__('Days').'</th>
<th>'.__('All Traffic').'</th>';
if($type != 'all'){
echo '<th style="text-align:right" colspan="4">'.$protocol_langs[$type].'</th>';
}else{
foreach($protocols as $p){
echo '<th>'.$protocol_langs[$p].'</th>';
}
}
echo '
</tr>
</thead>
<tbody>';
$columntotal['all'] = 0;
$yaxis = [];
foreach($protocols as $k => $p){
$c = $colorcode[$k];
if(empty($c)){
$c = rand_color();
}
$yaxis[$p] = ['label' => $protocol_langs[$p], 'color' => $c];
}
for($i=1; $i <= $d; $i++){
$xaxis[] = $i;
$rowtotal['all'] = 0;
foreach($bandwidth['monthly_band'][$i] as $dk => $dv){
$rowtotal['all'] = $rowtotal['all'] + $dv['bytes'];
$rowtotal[$dv['type']] = isset($rowtotal[$dv['type']]) ?  $dv['bytes'] + $rowtotal[$dv['type']] : $dv['bytes'];
}
foreach($protocols as $k => $p){
$yaxis[$p]['data'][] = [$i, !empty($rowtotal[$p]) ? $rowtotal[$p] : 0];
}
+
$columntotal['all'] = $columntotal['all'] + $rowtotal['all'];
$days_link = strtotime($i.' '.$month.' '.$year) > time() ? '#' : $globals['ind'].'act=bandwidth&month='.$month.'&year='.$year.'&type='.$type.'&day='.$i.'&domain='.($type != 'http' ? '' : $domain);
echo '
<tr class="text-center">
<td><a class="text-decoration-none" href="'.$days_link.'">'.$i.'</a></td>
<td>'.bytes_to_human($rowtotal['all']).'</td>';
if($type != 'all'){
echo '<td style="text-align:right" colspan="5">'.bytes_to_human($rowtotal[$type]).'</td>';
}else{
foreach($protocols as $k => $p){
echo '<td>'.bytes_to_human($rowtotal[$p]).'</td>';
}
}
echo '
</tr>';
foreach($protocols as $k => $p){
$columntotal[$p] = $columntotal[$p] + $rowtotal[$p];
}
unset($rowtotal);
}
echo '
<tr class="text-center">
<td>'.__('Total').'</td>
<td>'.bytes_to_human($columntotal['all']).'</td>';
if($type != 'all'){
echo '<td style="text-align:right" colspan="5">'.bytes_to_human($columntotal[$type]).'</td>';
}else{
foreach($protocols as $k => $p){
echo '<td>'.bytes_to_human($columntotal[$p]).'</td>';
}
}
echo '
</tr>
</tbody>
</table>
</div>
<div class="text-center my-3">
<a id="lnkReturn" href="javascript:history.go(-1)" class="flat-butt text-decoration-none">
<i class="fas fa-arrow-left" aria-hidden="true"></i>
'.__('Go Back').'
</a>
</div>';
}else{
$past_24 = [];
$past_year = [];
$past_week = [];
foreach($protocols as $k => $p){
$c = $colorcode[$k];
if(empty($c)){
$c = rand_color();
}
$past_24[$p] = ['label' => $protocol_langs[$p], 'color' => $c];
$past_year[$p] = ['label' => $protocol_langs[$p], 'color' => $c];
$past_week[$p] = ['label' => $protocol_langs[$p], 'color' => $c];
}
foreach($bandwidth['past_24hour'] as $key => $value){
$past_24[$value['type']]['data'][] = [$value['unixtime']*1000, $value['bytes']];
}
echo '
<div class="row text-center">
<h3>'.__('Past 24 hours').'</h3>
<div class="col-12">
<div id="past_24_hour_band" class="mx-auto" style="width:700px; height:400px;display:'.(!empty($bandwidth['past_24hour']) ? '' : 'none').';">
</div>';
if(empty($bandwidth['past_24hour'])){
echo '
<div class="my-4">'.__('There is no data for this period.').'</div>';
}
echo '
</div>
</div>';
foreach($bandwidth['past_week'] as $key => $value){
$past_week[$value['type']]['data'][] = [$value['unixtime']*1000, $value['bytes']];
}
echo '
<div class="row text-center">
<h3>'.__('Past Week').'</h3>
<div class="col-12">
<div id="past_week_band" class="mx-auto" style="width:700px; height:400px;display:'.(!empty($bandwidth['past_week']) ? '' : 'none').';">
</div>';
if(empty($bandwidth['past_week'])){
echo '
<div class="my-4">'.__('There is no data for this period.').'</div>';
}
echo'
</div>
</div>';
foreach($bandwidth['past_year'] as $key => $value){
$past_year[$value['type']]['data'][] = [$value['unixtime']*1000, $value['bytes']];
}
echo '
<div class="row text-center">
<h3>'.__('Past Year').'</h3>
<div class="col-12">
<div id="past_year_band" class="mx-auto" style="width:700px; height:400px;display:'.(!empty($bandwidth['past_year']) ? '' : 'none').';"></div>';
if(empty($bandwidth['past_year'])){
echo '
<div>'.__('There is no data for this period.').'</div>';
}
echo'
</div>
</div>';
echo '
<div class="bandmonthly">';
$tmpmonth = [];
foreach($bandwidth['all_band'] as $yk => $yv){
foreach($yv as $mk => $mv){
$total_band = 0;
echo '
<div class="row">
<h3>'.$yk.' '.$mk.'</h3>
<div class="col-7">';
$tmdomainbyte = [];
foreach($mv as $k => $v){
$total_band += $v['bytes'];
if($v['type'] == 'http'){
$tmdomainbyte[$v['type'].'-'.$v['domain']]['type'] = $v['type'];
$tmdomainbyte[$v['type'].'-'.$v['domain']]['domain'] = $v['domain'];
$tmdomainbyte[$v['type'].'-'.$v['domain']]['bytes'] = !empty($tmdomainbyte[$v['type'].'-'.$v['domain']]['bytes']) ? ($tmdomainbyte[$v['type'].'-'.$v['domain']]['bytes']+$v['bytes']) : $v['bytes'];
}else{
$tmdomainbyte[$v['type']]['type'] = $v['type'];
$tmdomainbyte[$v['type']]['bytes'] = !empty($tmdomainbyte[$v['type']]['bytes']) ? ($tmdomainbyte[$v['type']]['bytes']+$v['bytes']) : $v['bytes'];
}
}
$i = 0;
foreach($tmdomainbyte as $tk => $tv){
$c = $colorcode[$i];
if(empty($c)){
$c = rand_color();
}
$tmpmonth[$yk.'-'.$mk][] = [ 'label' => $tk, 'data' => $tv['bytes'], 'color' => $c];
echo '
<div class="row">
<div class="col-9 text-left">
<span class="box me-1" style="background-color:'.$colorcode[$i].'"></span>
<a href="'.$globals['ind'].'act=bandwidth&month='.$mk.'&year='.$yk.'&type='.$tv['type'].'&domain='.($tv['type'] != 'http' ? '' :$tv['domain']).'" id="lnk-details-'.$mk.'-'.$yk.'-'.$tv['type'].'">
'.$tv['type'].($tv['type'] != 'http' ? '' :' - '.$tv['domain']).'
</a>
</div>
<div class="col-3 text-right">
<span id="total-'.$mk.'-'.$yk.'-'.$tv['type'].'">
'.bytes_to_human($tv['bytes']).'
</span>
</div>
</div>';
$i++;
}
echo '
<div class="row">
<div class="col-9 text-left">
<a href="'.$globals['ind'].'act=bandwidth&month='.$mk.'&year='.$yk.'&type=all" id="lnk-details-'.$mk.'-'.$yk.'-all">
'.__('Total - All Services').'
</a>
</div>
<div class="col-3 text-right">
<span id="total-'.$mk.'-'.$yk.'-all">
'.bytes_to_human($total_band).'
</span>
</div>
</div>';
echo '
</div>
<div class="col-5">
<div id="monthly_band_'.$yk.'-'.$mk.'" style="width:300px;height:200px"></div>
</div>
</div>';
}
}
echo '
</div>';
}
echo '
</div>';
echo '
<script type="text/javascript">
$(document).ready(function (){
var monthly_band = \''.json_encode($tmpmonth).'\';
var monthly_band = JSON.parse(monthly_band);
console.log("monthly_band", monthly_band, typeof monthly_band);
var past_24_hour_band = \''.json_encode($past_24).'\';
past_24_hour_band = JSON.parse(past_24_hour_band);
var past_year_band = \''.json_encode($past_year).'\';
past_year_band = JSON.parse(past_year_band);
var past_week_band = \''.json_encode($past_week).'\';
past_week_band = JSON.parse(past_week_band);
var xaxis_data = '.json_encode($xaxis).'
var pie_option = {
series: {
pie: {
show: true,
label: {
show:true,
radius: 0.8,
formatter: function (label, series) {
return \'<div style="border:1px solid grey;font-size:8pt;text-align:center;padding:5px;color:white;">\' +
label + \' : \' +
Math.round(series.percent) +
\'%</div>\';
},
background: {
opacity: 0.8,
color: \'#000\'
}
}
}
}
};
var bar_options = {
series: {
lines: {
show: true,
fill: true
},
points:{
show: true,
radius: 3
}
},
xaxis: {
show: true,
ticks: xaxis_data,
tickLength: 1,
},
yaxis: {
tickFormatter: function (v) {
return human_readable_bytes(v);
}
},
grid: {
hoverable: true,
borderWidth: 2,
backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
},
tooltip: {
show: true,
content: function(label, x, y, flotItem){
return "Day: "+ (Number(x)) + ", Bandwidth:" +human_readable_bytes(y);
}
}
};
var timeseries_baroption = {
xaxis: {
mode: "time",
tickSize: [],
tickLength: 10,
color: "black",
axisLabel: "Date",
axisLabelUseCanvas: true,
axisLabelFontSizePixels: 12,
axisLabelFontFamily: \'Verdana, Arial\',
axisLabelPadding: 10
},
yaxis: {
color: "black",
axisLabel: "Bandwidth",
axisLabelUseCanvas: true,
axisLabelFontSizePixels: 12,
axisLabelFontFamily: \'Verdana, Arial\',
axisLabelPadding: 3,
tickFormatter: function (v) {
return human_readable_bytes(v);
}
},
grid: {
hoverable: true,
borderWidth: 2,
backgroundColor: { colors: ["#EDF5FF", "#ffffff"] }
}
}
if(xaxis_data != null && xaxis_data.length > 0){
var type = "'.$type.'";
var day = "'.$day.'";
var data = \''.json_encode($yaxis).'\';
data = JSON.parse(data);
var data_set = [];
$.each(data, function(key, value){
data_set.push(value);
});
if(type && type !== "all"){
data_set = [data[type]];
}
if(day){
bar_options.tooltip.show = false;
}
$.plot($("#mon-band-bar"), data_set, bar_options);
}
let data_set_24 = [], data_set_past_year = [], data_set_past_week=[];
$.each(past_24_hour_band, function(key, value){
let v = {...value,  points: { fillColor: value.color, show: true }, lines: { show: true }};
data_set_24.push(v);
});
$.each(past_year_band, function(key, value){
let v = {...value,  points: { fillColor: value.color, show: true }, lines: { show: true }};
data_set_past_year.push(v);
});
$.each(past_week_band, function(key, value){
let v = {...value,  points: { fillColor: value.color, show: true }, lines: { show: true }};
data_set_past_week.push(v);
});
if(data_set_24.length > 0){
timeseries_baroption.xaxis.tickSize = [1, "hours"];
timeseries_baroption.xaxis.timeformat = "%H";
$.plot($("#past_24_hour_band"), data_set_24, timeseries_baroption);
}
if(data_set_past_week.length > 0){
timeseries_baroption.xaxis.tickSize = [1, "day"];
timeseries_baroption.xaxis.timeformat = "%a(%d)";
$.plot($("#past_week_band"), data_set_past_week, timeseries_baroption);
}
if(data_set_past_year.length > 0){
timeseries_baroption.xaxis.tickSize = [1, "month"];
timeseries_baroption.xaxis.timeformat = "%b-%Y";
$.plot($("#past_year_band"), data_set_past_year, timeseries_baroption);
}
$.each(monthly_band, function(key, value){
var dataset = value;
$.plot($("#monthly_band_"+key), value, pie_option);
});
});
function gd() {
}
function human_readable_bytes() {
}
var previousPoint = null, previousLabel = null;
$.fn.UseTooltip = function () {
$(this).bind("plothover", function (event, pos, item) {
if (item) {
if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
previousPoint = item.dataIndex;
previousLabel = item.series.label;
$("#tooltip").remove();
var x = item.datapoint[0];
var y = item.datapoint[1];
var color = item.series.color;
showTooltip(item.pageX,
item.pageY,
color,
"<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong> °C");
}
} else {
$("#tooltip").remove();
previousPoint = null;
}
});
};
function showTooltip() {
}).appendTo("body").fadeIn(200);
}
function getPrevMonth() {
}
function getNextMonth() {
}
</script>';
softfooter();
}
function rand_color() {
}
$colorcode[] = $c;
return $c;
}