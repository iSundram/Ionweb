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


function blacklist_domain_theme(){

global $user, $globals, $theme, $softpanel, $SESS, $error, $done, $blacklist_domain;

	softheader(__('Blacklist Domains'));

	echo '
<div class="soft-smbox p-3 col-12 col-md-9 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-ban me-1"></i> '.__('Blacklist Domains').'
	</div>
</div>
<div class="soft-smbox p-3 col-12 col-md-9 mx-auto mt-4">';

	error_handle($error);

	echo '
	<div id="form-container">
		<form accept-charset="'.$globals['charset'].'" id="adddomain" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1">
			<div class="row  mx-4 my-4">
				<div class="col-12">
					<label class="form-label">'.__('Enter Domains : -').'
					</label>
					<span class="sai_exp2 d-block">'.__('You can add multiple domains by separating them with a $0 (,) comma $1', ['<b>', '</b>']).'</span>
					<span class="sai_exp2 d-block">'.__('To blacklist wildcard domains use $0 (*) $1 . E.g:- *.domain.com', ['<b>', '</b>']).'</span>
					<input type="text" name="domain" id="domain" class="form-control">
				</div>
			</div>
			<div class="text-center my-3">
				<input type="submit" name="add_block" class="btn btn-primary" value="'.__('Submit').'" />
			</div>
		</form>
	</div>
	<div class="row mt-4">
		<div class="col-12">
			<input class="me-2" type="checkbox" onchange="selectAlldomcheck(this, \'selectblockdom\');">
			<button class="btn btn-danger manyactionbtn" title="'.__('Delete selected domain').'" disabled data-delete_select_cls="selectblockdom" data-deletmanykey="domain" data-delete=1 onclick="delete_manyrecord(this)"> <i class="fas fa-trash delete"></i></button>
		</div>
	</div>
	<div class="table-responsive mt-2">
		<table class="table sai_form webuzo-table">
			<thead>
				<tr>
					<th width="10%" colspan="2">#</th>
					<th>'.__('Blocked Domain').'</th>
					<th width="10%">'.__('Action').'</th>
				</tr>
			</thead>
			<tbody id="tbody_domain">';
	
			if(empty($blacklist_domain)){
				
				echo '
				<tr>
					<td colspan="100" class="text-center">'.__('No domain blocked').'</td>
				</tr>';
		
			}else{
				
				foreach($blacklist_domain as $k => $v){
					foreach($v as $dk => $dv){
				
						echo '
					<tr id="tr'.$k.$dk.'">
						<td colspan="2"><input type="checkbox" group="block_domain" class="selectblockdom" data-domain="'.$dv.'" data-id="'.$k.$dk.'"></td>
						<td>'.$dv.'</td>
						<td><i class="fas fa-trash delete delete-icon" title="Delete" id="did'.$k.$dk.'" onclick="delete_record(this)" data-domain="'.$dv.'" data-delete="1"></i></td>
					</tr>
					';
					}
				}
			}
			echo '
			</tbody>
		</table>
	</div>
</div>


<script>

function handleManyaction(k){
	var len = 0;
	
	 $("."+k).each(function(){
		 len = $(this).is(":checked") ? len+1 : len;
	 });
	 
	if(len >= 1){
		
		$(".manyactionbtn").each(function(){
			// console.log(this);
			$(this).removeAttr("disabled");
		});
		
	}else{
		$(".manyactionbtn").each(function(){
			// console.log(this);
			$(this).attr("disabled", "disabled");
		});
	}
}

function selectAlldomcheck(ele, k){
	var check = ele.checked;
	
	$("."+k).each(function(){
		if(check){
			$(this).prop("checked", true);
		}else{
			$(this).prop("checked", false);
		}
	});
	
	handleManyaction(k);
}

$(".selectblockdom").change(function(){
	handleManyaction("selectblockdom");
});

$(document).on("done:delete_many", ".manyactionbtn", function(){
	$(this).parent().children().first().prop("checked", false);
	handleManyaction("selectblockdom");
});

</script>';

softfooter();

}

