<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function sel_restore_files_theme() {
}
$collapsed = 'show';
echo '
<div class="card soft-card mb-3 panel-row" id="row_'.$key.'">
<div class="accordion panel-default" id="main_div_'.$key.'">
<div class="accordion-item" id="main_table_'.$key.'">
<div class="accordion-header" id="panel_'.$key.'_heading">
<div class="row align-items-center panel-heading">
<div class="col-10 px-4 py-2">
<i class="'.$value['fa-icon'].' panel-icon"></i>
<label class="panel-head d-inline-block mb-0">'.$value['name'].'</label>
</div>
<div class="col-2 p3-5">
<div class="accordion-button '.$glyph.'" type="button" data-bs-toggle="collapse" data-bs-target="#panel_'.$key.'" aria-controls="panel_'.$key.'" onclick="panel_collapse(\''.$key.'\');"></div>
</div>
</div>
</div>
<div id="panel_'.$key.'" class="accordion-collapse collapse '.$collapsed.'" aria-labelledby="panel_'.$key.'_heading" data-bs-parent="#main_div_'.$key.'">
<div class="accordion-body p-1">
<div class="row">';
if(!empty($value['icons'])){
foreach ($value['icons'] as $k => $v) {
if(!empty($v['hidden'])){
continue;
}
echo '
<div class="col-xs-6 col-sm-6 col-md-4 col-lg-2 col-xl-2 col-xxl-2">
<a href="'.$v['href'].'" onclick="'.$v['onclick'].'" '.( $v['atts'] ? $v['atts'] : "").' class="webuzo_icons '.( $v['class'] ? $v['class'] : "").'" value="'.$v['label'].'" '.( !empty($v['target']) ? 'target="'.$v['target'].'"' : '').'>
<div id="'.$v['id'].'" class="pan-button">
'.(empty($v['fa-icon']) ? '<img src="'.$v['icon'].'" alt="" width="42" />' : '<i class="'.$v['fa-icon'].'" style="font-size: 45px;"></i>').'<br>
<span class="medium">'.$v['label'].'</span>
</div>
</a>
</div>';
}
}
if(!empty($value['html'])){
echo $value['html'];
}
echo '
</div>
</div>
</div>
</div>
</div>
</div>';
}
echo '</div>
</div>
</div>';
}