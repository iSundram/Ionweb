<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function eapps_theme() {
}
.active-icon{
color: #69f0ae;
}
.inactive-icon{
color: #ef5350;
}
.apptype {
font-size: 10px;
color: #515151;
}
</style>
<div class="card soft-card p-3">
<div class="sai_main_head ">
<i class="fa fa-th-large fa-2x webu_head_img me-2"></i>
<h5 class="d-inline-block">'.__('List Application').'</h5>
<a type="button" class="btn flat-butt float-end" href="'.$globals['index'].'act=eapps_add">'.__('Create').'</a>
</div>
</div>
<div class="card soft-card p-4 mt-4">';
page_links();
echo '
<div id="showrectab" class="table-responsive">
<table class="table align-middle table-nowrap mb-0 webuzo-table">
<thead class="sai_head2">
<tr>
<th class="align-middle">'.__('Name').'</th>
<th class="align-middle">'.__('Base URL').'</th>
<th class="align-middle">'.__('Port').'</th>
<th class="align-middle">'.__('Application type').'</th>
<th class="align-middle">'.__('Path').'</th>
<th class="align-middle">'.__('Enable/Disable').'</th>
<th class="align-middle">'.__('Status').'</th>
<th class="align-middle">'.__('Start/Stop').'</th>
<th class="align-middle text-center" colspan="3">'.__('Options').'</th>
</tr>
</thead>
<tbody>';
if(empty($eapps_list)){
echo '
<tr>
<td class="text-center" colspan=9>'.__('No application exist').'</td>
</tr>';
}else{
foreach ($eapps_list as $key => $value){
$appid = get_app_record($value['app_type']);
$folder_path = preg_replace('/^'.preg_quote($WE->user['homedir'], '/').'\//', '', $value['app_path']);
$first_folder = explode('/', trim($folder_path, '/'))[0];
if($first_folder == '.trash'){
$folderHash = base64_encode(str_replace(['.trash/', '.trash'], '', $folder_path));
$filemanager_path = 'filemanager/#elf_t1_'.$folderHash;
}else{
$folderHash = base64_encode($folder_path);
$filemanager_path = 'filemanager/#elf_l1_'.$folderHash;
}
echo '
<tr id="tr'.$key.'">
<td>
<span>'.$value['name'].'</span>
</td>
<td class="endurl">
<a target="_blank" href="http://'.(!empty($value['base_url']) ? $value['base_url'] : $value['domain']).'">'.(!empty($value['base_url']) ? $value['base_url'] : $value['domain']).'</a>
</td>
<td style="word-break: break-word;max-width: 150px;">
<span>'.$value['port'].''.(!empty($value['additional_ports']) ? ', '.$value['additional_ports'] : '').'</span>
</td>
<td>'.$apps[$appid]['name'].' '.($value['type'] == 'passenger' ? '<br><span class="apptype">(Passenger)</span>' : '').'</td>
<td><a target="_blank" class="text-decoration-none" href="'.$filemanager_path.'"> '.$value['app_path'].'<span class="mx-1 fas fa-external-link-alt"></span></a></td>
<td>
<label class="switch">
<input type="checkbox" class="checkbox"  data-donereload="1" data-app="'.$key.'" data-action="'.(!empty($value['enabled'])? 'disable' : 'enable').'" '.(!empty($value['enabled']) ? 'checked' : '').' onclick="return access_toggle(this)">
<span class="slider" '.(!empty($value['enabled']) ? 'title="Enabled"' : 'title="Disabled"').'></span>
</label>
</td>
<td align="center">
<i class="run_status fas fa-'.(!empty($ports_in_use[$value['port']]) ? 'running active-icon running' : 'power-off inactive-icon stop').'"  title="'.(!empty($ports_in_use[$value['port']]) ? __('Running') : __('Stopped')).'"></i>
</td>
<td align="center">
<i class="fas fa-power-off '.(!empty($ports_in_use[$value['port']]) ? 'inactive-icon' : ' active-icon').' startstop" data-action="'.(!empty($ports_in_use[$value['port']]) ? 'stop' : 'start').'" data-app="'.$key.'" onclick="return eapp_start_stop(this)" '.(!empty($ports_in_use[$value['port']]) ? 'title="'.$l['stop'].'"' : 'title="'.$l['start'].'"').'></i>
</td>
<td width="2%">';
if($value['type'] == 'self' || (is_app_installed('passenger') && $value['type'] == 'passenger')){
echo '
<a href="'.$globals['ind'].'act=eapps_add&edit_app='.$key.'&type='.$value['type'].'">
<i class="fas fa-pen edit edit-icon" title="'.__('Edit').'"></i>
</a>
';
}
echo '
</td>
<td width="2%">
<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="'.$key.'" onclick="delete_eapps(this)" data-delete="'.$key.'"></i>
</td>
</tr>';
}
}
echo '
</tbody>
</table>
</div>
</div>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[
$(document).ready(function (){
});
function access_toggle() {
}else{
lan = "'.__js('Do you want to disable application').'";
}
a = show_message_r(l.warning, lan);
a.alert = "alert-warning";
var no = function(){
var status = d.action ? false : true;
jEle.prop("checked", status);
}
a.confirm.push(function(){
submitit(d, {done_reload : window.location.href, error: no});
});
a.no.push(no);
a.onclose.push(no);
show_message(a);
}
function eapp_start_stop() {
});
}
function delete_eapps() {
}
var d = jEle.data();
submitit(d, {
done_reload : window.location
});
});
show_message(a);
}
softfooter();
}