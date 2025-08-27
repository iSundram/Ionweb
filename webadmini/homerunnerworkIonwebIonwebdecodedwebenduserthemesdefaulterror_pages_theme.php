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

function error_pages_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error, $done, $U, $statuscodes, $domain, $errorcode, $path, $existing_content;

	softheader(__('Error Pages'));

	echo '
<style>

p {
cursor:pointer;
color:#1974D2;
}

textarea {
background: url(http://i.imgur.com/2cOaJ.png);
background-attachment: local;
background-repeat: no-repeat;
padding-left: 35px;
padding-top: 10px;
border-color: #ccc;

font-size: 13px;
line-height: 16px;
width:100%;
}

.textarea-wrapper {
display: inline-block;
background-image: linear-gradient(#F1F1F1 50%, #F9F9F9 50%);
background-size: 100% 32px;
background-position: left 10px;
width:100%;
}

</style>
<div class="card soft-card p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'errorpages.png" alt="" class="webu_head_img me-2"/>
		<h4 class="d-inline-block">'.__('Error Pages').'</h4>
	</div>
</div>
<div class="card soft-card p-4 mt-4">
	<div>
		'.__('Error pages inform a user when there is a problem accessing your website. Each type of problem has its own HTTP status code. For example, an unauthorized user trying to access a restricted area of your website will see a 401 error, while a user who enters a non-existent URL will see a 404 error. You can create a custom error page for any valid HTTP status code beginning with 4 or 5 with this wizard.').'
	</div>';
		
	echo '
	<div class="my-5">
		<form accept-charset="'.$globals['charset'].'" action="" method="post" name="save" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
			<h5 class="d-inline-block">'.__('Select Domain to Manage Error Pages').'</h5>
			<div class="sai_sub_head position-relative mb-5 row">
				<div class="col-md-3">
					<label class="form-label m-2" for="domain">'.__('Domains List:').'</label>
				</div>
				<div class="col-md-6">
					<select class="form-select" id="domain">
						<option value="" selected disabled>'.__('Select domain').'</option>';
						
					foreach($U['domains'] as $key => $value){
						echo '
						<option value="'.$key.'" '.($key == $domain ? "selected" : "").'>'.$key.'</option>';
					}
					
					echo '
					</select>
				</div>
			</div>
			<h5 class="d-inline-block">'.__('Edit Error Pages').' </h5>
			<div class="sai_sub_head position-relative mb-5 row">			
				<div class="col-md-3">
					<label class="form-label m-2" for="errorcode">'.__('HTTP Status Codes:').'</label>
				</div>
				<div class="col-md-6">
					<select class="form-select" id="errorcode">
						<option value="" selected disabled>'.__('Select code').'</option>';
						
					foreach($statuscodes as $key => $value){
						echo '
						<option value="'.$key.'" '.($key == $errorcode ? "selected" : "").'>'.$key.' ('.$value.')</option>';
					}
					
					echo '
					</select>
				</div>
			</div>';
			
			error_handle($error);
			
			if($domain && $errorcode && empty($error)){
				echo '
				<h5 class="d-inline-block">'.$errorcode.'.shtml ('.$statuscodes[$errorcode].')</h5>
				<div class="mb-2">
					<h6 class="d-inline-block">'.__('You are editing the error pages for the domain: ').$domain.'</h6>
				</div>
				<h6>Select Tags to insert:</h6>
				<div class="row mb-2">
					<div class="col-md-3">
						<p data-tag="refurl" onclick="filltag(this)">'.__('&lt/&gt Referring URL').'</p>
					</div>
					<div class="col-md-3">
						<p data-tag="ipaddr" onclick="filltag(this)">'.__('&lt/&gt Visitor\'s IP Address').'</p>
					</div>
					<div class="col-md-3">
						<p data-tag="requrl" onclick="filltag(this)">'.__('&lt/&gt Requested URL').'</p>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-md-3">
						<p data-tag="server" onclick="filltag(this)">'.__('&lt/&gt Server Name').'</p>
					</div>
					<div class="col-md-3">
						<p data-tag="browser" onclick="filltag(this)">'.__('&lt/&gt Visitor\'s Browser').'</p>
					</div>
					<div class="col-md-3">
						<p data-tag="redirect" onclick="filltag(this)">'.__('&lt/&gt Redirect Status Code').'</p>
					</div>
				</div>
				<div class="textarea-wrapper mb-3">
					<textarea name="content" id="content" rows="20" value="" style="overflow:auto;resize:none">'.$existing_content.'</textarea>
				</div>
				<input type="submit" name="save" value="'.__('Save').'" class="flat-butt me-2">
			</form>';
		
				if($softpanel->root_func_exec('file_exists', [$path])){
					echo '
					<input type="button" onclick="delete_page(this)" data-delete="1" value="'.__('Delete').'" class="flat-butt">';
				}
			}
			
			echo '
	</div>
</div>

<script>
function filltag(el){
	var d = $(el).data();
	
	if(d["tag"] == "refurl"){
		var text = `<!--#echo var="HTTP_REFERER" -->`;
	}else if(d["tag"] == "ipaddr"){
		var text = `<!--#echo var="REMOTE_ADDR" -->`;
	}else if(d["tag"] == "requrl"){
		var text = `<!--#echo var="REQUEST_URI" -->`;
	}else if(d["tag"] == "server"){
		var text = `<!--#echo var="HTTP_HOST" -->`;
	}else if(d["tag"] == "browser"){
		var text = `<!--#echo var="HTTP_USER_AGENT" -->`;
	}else if(d["tag"] == "redirect"){
		var text = `<!--#echo var="REDIRECT_STATUS" -->`;
	}
	
	var curPos = $("#content").prop("selectionStart");
    var x = $("#content").val();
    $("#content").val(x.slice(0, curPos) + text + x.slice(curPos));
}

$("#domain").change(function(){
	var domain = $(this).val();
	window.location = "'.$globals['index'].'act=error_pages&code='.$errorcode.'&domain="+domain;
});

$("#errorcode").change(function(){
	var code = $(this).val();
	window.location = "'.$globals['index'].'act=error_pages&domain='.$domain.'&code="+code;
});

function delete_page(el){
	var jEle = $(el);
	
	var a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete the error page?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = jEle.data();

		submitit(d,{
			done_reload: window.location
		});
	});
	
	show_message(a);
}
</script>';

	softfooter();
}