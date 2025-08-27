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

function terminal_theme(){

global $user, $globals, $theme, $WE, $error, $done;
	
	softheader(__('Terminal'));
	
	echo '<div class="sai_main_head text-center">
		<i class="fas fa-terminal me-1"></i>'.__('Terminal').'<br>
	</div>';
	
	error_handle($error, '100%');
	
	/*if(!empty($done)){
		
		$_url = 'http'.(!empty($_SERVER['HTTPS']) ? 's' : '').'://'.$done['user'].':'.$done['password'].'@'.str_replace('//', '/', $_SERVER['HTTP_HOST'].'/tty/');

		$url = 'http'.(!empty($_SERVER['HTTPS']) ? 's' : '').'://'.str_replace('//', '/', $_SERVER['HTTP_HOST'].'/tty/');
		
		echo '<iframe style="width:100%; height: 100%; min-height: 500px" id="terminal-iframe"></iframe>
		
<script>
var xhr = new XMLHttpRequest();
xhr.open("GET", "'.$url.'");
xhr.onreadystatechange = handler;
xhr.responseType = "document";
xhr.setRequestHeader("Authorization", "Basic '.base64_encode($done['user'].':'.$done['password']).'");
xhr.send();

function handler(){
	if (this.readyState === this.DONE) {
		if (this.status === 200) {
			var iframe = document.querySelector("#terminal-iframe");
			var content = iframe.contentWindow || iframe.contentDocument.document || iframe.contentDocument;
			content.document.open();
			var html = this.response.documentElement.innerHTML.replace(/,be,/g, ",\"/tty\",");
			//console.log(html);
			content.document.write(html);
			content.document.close();
		} else {
			iframe.attr("srcdoc", "<html><head></head><body>Error loading page.</body></html>");
		}
	}
}

</script>';
	
	}*/
	
	softfooter();

}
