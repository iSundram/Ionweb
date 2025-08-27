<?php

//////////////////////////////////////////////////////////////
//===========================================================
// WEBUZO CONTROL PANEL
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit
// Date:       10th Jan 2009
// Time:       21:00 hrs
// Site:       https://webuzo.com/ (WEBUZO)
// ----------------------------------------------------------
// Please Read the Terms of Use at https://webuzo.com/terms
// ----------------------------------------------------------
//===========================================================
// (c) Softaculous Ltd.
//===========================================================
//////////////////////////////////////////////////////////////

if(!defined('SOFTACULOUS')){
	die('Hacking Attempt');
}

function news_theme(){

global $globals, $softpanel, $theme, $done, $error, $news, $news_form;	
	
	softheader(__('News'));
	
	echo '
<div class="col-12 mx-auto soft-smbox p-4">
	<div class="sai_main_head">
			<i class="fas fa-newspaper"></i> '.__('News').'
	</div>
</div>
<div class="col-12 mx-auto soft-smbox p-4 mt-4">
	<form method="post" onsubmit="return submitnewsform(this)">';
		$nTabs = '';
		$nTabsDiv = '';
		
		foreach($news_form as $key => $val){
			
			$nTabs .= '
		<li class="nav-item" role="presentation">
			<button class="nav-link '.($key == 'global' ? 'active' : '').' sai_head" id="'.$key.'-tab" data-bs-toggle="tab" data-bs-target="#'.$key.'_div" type="button" role="tab" aria-controls="'.$key.'_div" aria-selected="'.($key == 'global' ? 'true' : 'false').'">'.$val['title'].'</button>
		</li>';
		
		$nTabsDiv .= '
		<div class="tab-pane fade row '.($key == 'global' ? 'show active' : 'false').'" id="'.$key.'_div" role="tabpanel" aria-labelledby="'.$key.'-tab">
			<label for="'.$key.'_msg" class="sai_sub_head d-block mb-3 mt-3">'.$val['subtitle'].'</label>
			
			<div class="row mb-3">
				<div class="col-md-5">
					<label for="'.$key.'_type" class="sai_head float-end">'.$val['alertlbl'].' :-</label>
				</div>
				<div class="col-md-5">
				<select class="form-select " name="'.$key.'_type" id="'.$key.'_type">';
					foreach($val['alerttype'] as $k => $v){
						$nTabsDiv .= '<option value="'.$k.'" '.POSTselect($key.'_type', $k, $k == $val['defaultalert']).'>'.$v.'</option>';
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

function submitnewsform(ele){
	
	var jEle = $(ele);
	
	var textareaEle = jEle.find("textarea");
	
	var isHtml = true;
	
	$.each(textareaEle, function(){
		if(!checkHTML($(this).val())){
			var a = show_message_r("'.__js('Error').'", "'.__js('News HTML is not valid').'"+" - "+$("#"+$(this).data("news_key")+"-tab").html());
			a.alert = "alert-danger";
			show_message(a);
			isHtml = false;
			return;
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