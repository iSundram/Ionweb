<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function user_logs_theme() {
}else{
$i=0;
foreach($domains_list as $domain => $value){
echo '
<tr>
<td width="15%" class="endurl"><a target="_blank" href="https://'.$domain.'">
<span class="text-center">'.$domain.'</span></a>
</td>
<td width="20%">
<span class="text-center">'.$value['path'].'</span>
<td width="20%">
<span class="text-center">'.ucfirst(domain_type($WE->user, $domain)).'</span>
</td>
</td>';
foreach($webserver_list as $key => $val){
echo'
<td width="	2%">
<input type="submit" class="flat-butt p-2 float-end showlogs" id="domain_'.$domain.'" value="'.strtoupper($key).'" data-show_log=1 data-user="'.$user.'" data-domain="'.$domain.'" data-webserver="'.$key.'" />
</td>';
}
$i++;
echo '
</tr>';
}
}
echo '
</tbody>
</table>
</div>
<nav aria-label="Page navigation">
<ul class="pagination pager myPager justify-content-end">
</ul>
</nav>
</div>
<div class="modal fade" id="show_logs_list" tabindex="-1" aria-labelledby="download-log" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h6 class="modal-title" id="domain_log"></h6>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-4" id="mbody">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="download_log" id="form_id" class="form-horizontal" onsubmit="return download_file(this)">
<div id="warn">
<label id="fileNotFound"></label>
</div>
<div id="form_data">
<label id="file_exp" class="sai_head">'.__('Select log file to download').'
<i class="fas fa-exclamation-circle" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="'.__('Empty files will not be listed here').'"></i>
</label>
<input type="hidden" class="form-control" name="path" id="path_to_file" value=""/>
<select id="log_list" name="domain_file" class="form-select">
</select></br>
<center><input type="submit" class="btn flat-butt" name="download_log" id="dwld" value="'.__('Download').'"/>
</div>
</form>
</div>
</div>
</div>
</div>
<script>
}
var html;
submitit(d, {
handle:function(data, p){
$("#domain_log").html(d.domain + " - " + d.webserver.toUpperCase());
if(empty(data.logfiles[d.webserver])){
$("#fileNotFound").html("'.__js('No Log file found for this domain ').'"+d.domain);
$("#warn").attr("class", "alert alert-warning col-sm justify-content-center");
$("#form_data").attr("class", "d-none");
$("#show_logs_list").modal("show");
}else{
$("#warn").attr("class", "d-none");
$("#form_data").attr("class", "sai_form");
var [first] = Object.keys(data.logfiles);
$("#path_to_file").val(first);
$.each(data.logfiles[d.webserver], function(key, val){
$.each(data.logfiles[d.webserver][key], function(file, filesize){
html += \'<option>\'+file+"\t\t("+filesize+" Bytes)"+\'</option>\'
});
});
$("#log_list").show();
$("#dwld").show();
$("#log_list").html(html);
$("#show_logs_list").modal("show");
}
},
});
})
function download_file() {
}
</script>';
softfooter();
}