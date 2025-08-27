<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function help_theme() {
}
echo '
<div class="accordion-item">
<h2 class="accordion-header" id="faqhead'.$i.'">
<button class="accordion-button '.$collapse.'" type="button" data-bs-toggle="collapse" data-bs-target="#faq'.$i.'" aria-expanded="'.$expand.'" aria-controls="faq'.$i.'">
'.$fv['question'].'
</button>
</h2>
<div id="faq'.$i.'" class="accordion-collapse collapse '.$show.'"  aria-labelledby="faqhead'.$i.'" data-bs-parent="#helpSupport">
<div class="accordion-body p-1">
<p class="sai_ans mb-0">'.$fv['answer'].'</p>
</div>
</div>
</div>';
$i++;
}
echo '
</div>
</div>';
softfooter();
}
?>