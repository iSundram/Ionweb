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

function change_owner_theme(){

global $globals, $theme, $softpanel, $error, $done, $users;	

	softheader(__('Change Ownership Of Multiple Accounts'));

	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-th-list me-2"></i>'.__('Change Ownership Of Multiple Accounts').'
	</div>
</div>

<div class="soft-smbox p-3 mt-4">
	<form accept-charset="'.$globals['charset'].'" name="changeowner" method="post" action=""; class="form-horizontal" onsubmit="return submitit(this)" data-donereload>

		<div class="sai_sub_head text-center record-table mb-5 position-relative">
			'.__('Select New Owner').' :
			
			<select class="form-select my-3 make-select2" s2-placeholder="'.__('Select Owner').'" s2-ajaxurl="'.$globals['index'].'act=users&type=2&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'root', 'id' => 'root','value' => 'root']])).'" style="width: 30%" name="owner_search" id="owner_search">
				<option value="'.optREQ('owner_search').'" selected="selected">'.optREQ('owner_search').'<option>
			</select>
			
			<input type="submit" name="changeowner" class="btn btn-primary" value="'.__('Change Owner').'">
		</div>';
		
		error_handle($error, "100%");
	
		if(empty($error) && optREQ('owner_search')){
			
			page_links();

			echo '
			<div class="table-responsive mt-4">
				<table class="table sai_form webuzo-table">
					<thead>
						<tr>
							<th><input type="checkbox" id="checkAll"></th>
							<th>'.__('Users').'</th>
							<th>'.__('Email').'</th>
							<th>'.__('Domain').'</th>
							<th>'.__('IP Address').'</th>
							<th>'.__('Type').'</th>
							<th>'.__('Owner').'</th>
						</tr>
					</thead>
					<tbody>';
			
			if(!empty($users)){
				foreach($users as $key => $value){			
					echo '
						<tr>
							<td>
								<input type="checkbox" name="checked_user[]" value="'.$value['user'].'">
							</td>
							<td>
								<span>'.$value['user'].'</span>
							</td>
							<td>
								<span>'.$value['email'].'</span>
							</td>
							<td>
								<span>'.$value['domain'].'</span>
							</td>
							<td>
								<span>'.$value['ip'].'</span>
							</td>
							<td>
								<span>'.($value['type'] == 1 ? __('User') : __('Reseller')).'</span>
							</td>
							<td>
								<span name="owner">'.$value['owner'].'</span>
							</td>
						</tr>';
				}
			}else{
				echo '<tr><td colspan=9><h4 style="text-align: center">'.__('No Record found').'</h4></td></tr>';
			}
				
				echo '
					</tbody>
				</table>
			</div>
		</form>';
		
		page_links();
	
	}
	
	echo '
</div>';
	
echo'	
<script>	

$("#owner_search").on("select2:select", function(){	
	user = $("#owner_search option:selected").val();	
	window.location = "'.$globals['index'].'act=change_owner&owner_search="+user;
});

$("#checkAll").change(function(){
	$("input:checkbox").prop("checked", $(this).prop("checked"));
});

</script>';
	
	softfooter();
	
}