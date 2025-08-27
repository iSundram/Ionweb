<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function news_theme() {
}
$nTabsDiv .= '
</select>
</div>
</div>
<textarea rows="10" class="form-control" spellcheck="false" id="'.$key.'_msg" name="'.$key.'_msg" data-news_key = "'.$key.'">'.preg_replace('#<script(.*?)>(.*?)</script>#is', '', $val['message']).'</textarea>
</div>';
}
echo '
<ul class="nav nav-tabs sai_head"  role="tablist" id="newsTabs">
'.$nTabs.'
</ul>
<div class="tab-content" id="newsTabsContent">
'.$nTabsDiv.'
</div>
<div class="text-center">
<input type="submit" class="btn btn-primary mt-3" id="save_news" name="save_news" value="'.__('Save News').'">
</div>
</form>
</div>
<script>
var checkHTML = function(html) {
var doc = document.createElement("div");
doc.innerHTML = html;
return ( doc.innerHTML === html );
}
function submitnewsform() {
}
});
if(!isHtml){
return false;
}
return submitit(ele);
}
</script>';
softfooter();
}