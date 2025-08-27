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

function mime_type_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error, $done, $system_mimes, $user_mimes;

	softheader(__('MIME Types'));

	echo '
<div class="card soft-card p-4">
	<div class="sai_main_head mb-4">
		<h4 class="d-inline-block">'.__('MIME Types').'</h4>
	</div>
	<div>
		'.__('MIME types tell browsers how to handle specific extensions. For example, the text/html MIME type equates to .htm, .html, and .shtml extensions on most servers, and this tells your browser to interpret all files with those extensions as HTML files. You can add new MIME types for your website with this wizard.').'
	</div>';
		
	echo '
	<div class="my-5">
		<h5 class="d-inline-block">'.__('Create MIME Types').'</h5>
		<form accept-charset="'.$globals['charset'].'" action="" method="post" name="add_mime" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
			<div class="col-12 mb-2">
				<label class="form-label m-2" for="mime_type">'.__('MIME Type').'</label>
				<input type="text" class="form-control" name="mime_type" id="mime_type" value="" placeholder="'.__('Example: text/html').'" required>
				<label class="form-label m-2" for="extensions">'.__('Extension(s)').'</label>
				<input type="text" class="form-control" name="extensions" id="extensions" value="" placeholder="'.__('Example: htm html shtml').'" required>
				<span>'.__('Tip: Separate multiple extension types with a space.').'</span>
			</div>
			<input type="submit" name="add_mime" value="'.__('Add').'" class="flat-butt">
		</form>
	</div>
	
	<div id="showrectab" class="table-responsive">
		<h5 class="d-inline-block">'.__('User Defined MIME Types').'</h5>
		<table class="table align-middle table-nowrap webuzo-table">
			<thead class="sai_head2">	
				<tr>
					<th>'.__('MIME Type').'</th>
					<th>'.__('Extension(s)').'</th>
					<th>'.__('Option').'</th>
				</tr>
			</thead>
			<tbody>';			
			
		if(!empty($user_mimes)){
			foreach ($user_mimes as $key => $value){
				$key = strip_tags($key);
				$value = strip_tags($value);
				echo '
				<tr>
					<td>'.$key.'</td>
					<td>'.$value.'</td>
					<td>
						<input type="button" class="flat-butt" value="'.__('Delete').'" data-mime="'.$key.'" data-extension="'.$value.'" onclick="delete_mime(this)">
					</td>
				</tr>';
			}
			
		}else{
			echo '
				<tr>
					<td class="text-center" colspan=6>'.__('There are no user configured MIME types').'</td>
				</tr>';
		}	
		
		echo '
			</tbody>
		</table>
	</div>
		
	<div id="showrectab" class="table-responsive pt-3">
		<h5 class="d-inline-block">'.__('System MIME Types').'</h5>
		<table class="table align-middle table-nowrap mb-0 webuzo-table">
			<thead class="sai_head2">	
				<tr>
					<th>'.__('MIME Type').'</th>
					<th>'.__('Extension(s)').'</th>
				</tr>
			</thead>
			<tbody>';
					
			foreach($system_mimes as $key => $value){
			echo '
				<tr>
					<td>'.$key.'</td>
					<td>'.$value.'</td>
				</tr>';
			}
				
		echo '
			</tbody>
		</table>
	</div>
</div>

<script>
function delete_mime(el){
	var jEle = $(el);
	
	var a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete?').'");
	a.alert = "alert-warning";
	
	jEle.data("delete", 1);
	
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