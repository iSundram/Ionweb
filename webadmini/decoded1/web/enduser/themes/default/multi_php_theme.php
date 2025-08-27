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

function multi_php_theme(){

global $globals, $theme, $softpanel, $WE, $domain, $error, $done, $domains_list, $installed_php;

	softheader(__('MultiPHP Manager'));

	echo '
<div class="card soft-card p-4 col-12">
	<div class="sai_main_head mb-5">
		<img src="'.$theme['images'].'multi_php.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('MultiPHP Manager').'</h5>
		<div class="col-12 col-md-4 mb-2 float-end">
			<label class="form-label me-1">'.__('Search by PHP Version').'</label>
			<select name="php_search" id="php_search" class="search_val" style="width:50%">
				<option value="all">'.__('All').'</option>			
				<option value="">'.__('Default').' '.$installed_php[$globals['WU_DEFAULT_PHP']].'</option>';			
				foreach ($installed_php as $k => $v){
					echo '<option value="'.$k.'">'.$v.'</option>';
				}
				
				echo '
			</select>
		</div>
	</div>
	<form accept-charset="'.$globals['charset'].'"  name="importsoftware" method="post" action="" onsubmit="return checkform();" role="form" class="form-horizontal">
		<table class="table align-middle table-nowrap mb-0 webuzo-table" >			
		<thead class="sai_head2">
			<tr>
				<th class="align-middle">'.__('Domain').'</th>
				<th class="align-middle">'.__('PHP Version').'</th>
			</tr>
					
		</thead>
		<tbody>
		<tr id = "nofound" style="display:none">
			<td colspan="100" class="text-center">
				<span>'.__('No match found').'</span>						 
			</td>
		</tr>';

	foreach ($domains_list as $key => $value){
		echo '
			<tr class="search_row">
				<td class="endurl">
					<a target="_blank" href="http://' . $key . '"> '.$key.'</a>
					<input type="hidden" name="'.$key.'" value="'.$key.'">
				</td>
				<td>
					<select name="php_version_'.$key.'" id="php_version_'.$key.'" class="form-select mb-3">
						<option value="" '.(empty($value['php_version']) ? 'selected' : '').'>'.__('Default').' '.$installed_php[$globals['WU_DEFAULT_PHP']].'</option>';
						
						foreach ($installed_php as $k => $v){
							echo '<option value="'.$k.'" '.($value['php_version'] == $k ? 'selected' : '').'>'.$v.'</option>';
						}
						
						echo '
					</select>
				</td>
			</tr>';
			$i++;
	}
	
	echo '
		</tbody>
		</table>
		<div class="text-center mt-4">
			<input type="submit" class="flat-butt me-2" value="'.__('Apply').'" name="submitphp" id="submitphp" onclick="return submitit(this)" data-donereload="1" />
			
		</div>
	</form>	
</div>

<script>
$("#php_search").change(function(){
	var php = $("#php_search option:selected").val();
	var i = 0;
	$(".search_row").each(function(key,val){
		var name = $(this).find("select").val();
		if(name === php || php === "all"){
			i++;
			$(this).show();				
		}else{
			$(this).hide();
		}
	});
	
	if(i == 0){			
		$("#nofound").show();
	}else{
		$("#nofound").hide();
	}
});
</script>';

softfooter();
}