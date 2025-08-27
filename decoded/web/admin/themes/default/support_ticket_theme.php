<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function support_ticket_theme() {
}else{
echo'
<div class="soft-smbox p-3">
<div class="sai_main_head">
<i class="fas fa-headset fa-xl fa-2x flip align-middle me-1"></i>&nbsp;<span class="p2">'.__('Support Center').'</span>
</div>
</div>
<div class="soft-smbox p-3 mt-4">
<div class="row justify-content-center p-2">
<div class="col-12 col-md-5 shadow p-3 mb-5 me-3">
<i class="far fa-list-alt fa-2x fa-pull-left flip align-middle" aria-hidden="true"></i>
<h5 class=""> '.__('Webuzo Features').' </h5><hr>
<p class="">'.__('Access information about different Webuzo features.').'</p>
<a id="feature" class="btn btn-primary float-end" target="_blank" href="https://webuzo.com/features/">
<span>'.__('Features').'<i class="fas fa-fw fa-external-link-alt"></i></span>
</a>
</div>
<div class="col-12 col-md-5 shadow p-3 mb-5 me-3">
<i class="far fa-comment fa-2x fa-pull-left flip align-middle" aria-hidden="true"></i>
<h5 class=""> '.__('Support Forum').' </h5><hr>
<p class="">'.__('Find and share solutions with Webuzo and other users around the world.').'</p>
<a id="board" class="btn btn-primary float-end" target="_blank" href="https://www.softaculous.com/board/index.php?fid=9&fname=General_Support">
<span>'.__('Support').'<i class="fas fa-fw fa-external-link-alt"></i></span>
</a>
</div>
<div class="col-12 col-md-5 shadow p-3 mb-5 me-3">
<i class="far fa-bell fa-2x fa-pull-left flip align-middle" aria-hidden="true"></i>
<h5 class=""> '.__('Webuzo Release Notifications / News').' </h5><hr>
<p class="">'.__('Learn when new versions of Webuzo are propagating.').'</p>
<a id="release" class="btn btn-primary float-end" target="_blank" href="https://webuzo.com/blog/">
<span>'.__('Check latest Version').'<i class="fas fa-fw fa-external-link-alt"></i></span>
</a>
</div>
<div class="col-12 col-md-5 shadow p-3 mb-5 me-3">
<i class="far fa-file-word fa-2x fa-pull-left flip align-middle" aria-hidden="true"></i>
<h5 class=""> '.__('Admin Documentation').' </h5><hr>
<p class="">'.__('Learn how to set up, use, and troubleshoot Webuzo Admin Panel.').'</p>
<a id="ad_doc" class="btn btn-primary float-end" target="_blank" href="https://webuzo.com/docs/admin/">
<span>'.__('Admin Doc\'s').' <i class="fas fa-fw fa-external-link-alt"></i></span>
</a>
</div>
<div class="col-12 col-md-5 shadow p-3 mb-5 me-3"><i class="fa fa-file-user"></i><i class="fa fa-file-user"></i>
<i class="fas fa-book fa-2x fa-pull-left flip align-middle" aria-hidden="true"></i>
<h5 class=""> '.__('End-User Documentation').' </h5><hr>
<p class="">'.__('Learn how to set up, use, and troubleshoot Webuzo End-User Panel.').'</p>
<a id="ad_doc" class="btn btn-primary float-end" target="_blank" href="https://webuzo.com/docs/endusers/">
<span>'.__('End-User Doc\'s').' <i class="fas fa-fw fa-external-link-alt"></i></span>
</a>
</div>
<div class="col-12 col-md-5 shadow p-3 mb-5 me-3">
<i class="fas fa-mail-bulk fa-2x fa-pull-left flip align-middle" aria-hidden="true"></i>
<h5 class=""> '.__('Contact Webuzo').' </h5><hr>
<p class="">'.__('Contact us via Email and you will get a response as fast as possible.').'</p>
<p class=""><i class="far fa-address-card fa-3x fa-pull-left flip align-middle" aria-hidden="true"></i><b>'
.__('Server Hostname : ').'</b>'.$panel_domain.'<br><b>'.__('Server IP Address : ').'</b>'.$ip.'
</p>
<a id="ed_doc" class="btn btn-primary float-end" target="_blank" href="mailto:support@webuzo.com">
<span>'.__('Contact Support').' <i class="fas fa-fw fa-external-link-alt"></i></span>
</a>
<a id="ed_doc" class="btn btn-primary" target="_blank" href="mailto:sales@webuzo.com">
<span>'.__('Contact Sale\'s').' <i class="fas fa-fw fa-external-link-alt"></i></span>
</a>
</div>
</div>
</div>';
}
softfooter();
}