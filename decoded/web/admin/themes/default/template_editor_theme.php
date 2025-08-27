<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function template_editor_theme() {
}
.textarea-wrapper {
display: inline-block;
background-image: linear-gradient(#F1F1F1 50%, #F9F9F9 50%);
background-size: 100% 32px;
background-position: left 10px;
width:100%;
}
</style>
<div class="soft-smbox p-3">
<!-- Heading -->
<div class="sai_main_head">
<i class="fas fa-paint-roller fa-lg me-2"></i>'.__('Web Template Editor').'
</div>
</div>
<div class="soft-smbox p-4 mt-4">
<!--- tab start -->
<ul class="nav nav-tabs mb-3 webuzo-tabs" id="pills-tab" role="tablist" id="tablist">';
$temp_lang = array('noindex' => __('Default'),
'suspended' => __('Suspended'),
'te_noindex_msg' => __('If there is no default index page present, this will be the default $0 Welcome $1 page.', ['<b>', '</b>']),
'te_suspended_msg' => __('This page will appear to visitors when site has been suspended.'),
'te_upld_noindex' => __('Upload your default template'),
'te_upld_suspended' => __('Upload your suspended template')
);
foreach($templates as $k => $v){
echo '<li class="nav-item" role="presentation">
<button class="nav-link heading_a '.($k == 'noindex' ? 'active' : '').'" id="'.$k.'_t" data-bs-toggle="tab" data-bs-target="#'.$k.'" type="button" role="tab">'.$temp_lang[$k].'</button>
</li>';
}
echo '
</ul>
<!--- tab end -->
<!--- tab Content start -->
<div class="tab-content" id="pills-tabContent">';
foreach($templates as $k => $v){
echo'
<div class="tab-pane '.($k == 'noindex' ? 'active' : '').'" id="'.$k.'" role="tabpanel" aria-labelledby="'.$k.'_t">
<h5>'.$temp_lang[$k].' '.__('Page').'</h5>
<p>'.$temp_lang['te_'.$k.'_msg'].'</p>
</p>'.__('$0 Note $1 : You can use SVG images or either store your images some where which are web accessible and use the URLs directly', ['<b>', '</b>']).'</p>
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="edit_'.$k.'" onsubmit="return submitit(this)" data-donereload="1">
<div class="textarea-wrapper">
<textarea name="content" id="'.$k.'_txt" rows="20" style="overflow:auto;resize:none">'.$v.'</textarea>
</div>
<input type="hidden" name="template" value="'.$k.'">
<input type="submit" value="'.__('Save').'" class="btn btn-primary my-4" name="edit"/>
</form>
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="restore_'.$k.'" onsubmit="return submitit(this)" data-donereload="1">
<input type="hidden" name="template" value="'.$k.'">
<input type="submit" value="'.__('Restore').'" class="btn btn-primary my-2" name="restore"/>
<input type="button" value="'.__('Preview').'" class="btn btn-primary my-2" name="preview" onclick="show_preview(\''.$k.'\')"/>
</form>
<div id="preview_'.$k.'" style="display:none; margin-bottom:2px">
<h4 style="text-align:center;">'.__('Preview').'</h4>
<i class="fas fa-window-close d-flex justify-content-end p-1" title="close" id="preview_'.$k.'_close" style="font-size:25px;color:#b9323f"></i>
<iframe id="'.$k.'_frame" width="100%" height="600" style="border:2px solid;" title="preview"></iframe>
</div>
<div class="alert-secondary p-2 mt-2">
<form accept-charset="'.$globals['charset'].'" action="" method="post" name="upload_'.$k.'" onsubmit="return submititwithdata(this)" enctype="multipart/form-data" data-donereload=1>
<div class="mb-3">
<h4>'.$temp_lang['te_upld_'.$k].'</h4>
<label class="form-label d-block mb-3">'.__('You can upload your custom template here').'</label>
<label class="form-label d-block" for="'.$k.'_upload">'.__('Choose the template file').'</label>
<input name="template_file" class="form-control" type="file" accept="text/html">
</div>
<input type="hidden" name="upload" value="'.$k.'">
<input type="submit" value="'.__('Upload').'" class="btn btn-primary my-2" name="upload"/>
</form>
</div>
</div>';
}
echo'
</div>
<!--- tab Content end -->
</div>
<script>
function show_preview() {
});
var frame = $("#"+action+"_frame").contents().find("body");
var preview = $("#"+action+"_txt").val();
frame.html(preview);
}
</script>';
softfooter();
}