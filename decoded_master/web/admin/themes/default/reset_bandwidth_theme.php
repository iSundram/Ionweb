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

function reset_bandwidth_theme(){

global $globals, $theme, $softpanel, $error, $done, $users;	

	softheader(__('Reset Account Bandwidth Limit'));
	
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'bandwidth.png" class="webu_head_img me-2"/>'.__('Reset Account Bandwidth Limit').'
	</div>
</div>
<div class="soft-smbox p-3 mt-4">';

	echo '
	<form accept-charset="'.$globals['charset'].'" name="usersform" id="usersform" method="post" action=""; class="form-horizontal">
		<div class="alert alert-warning">
			<strong>NOTE : </strong> '.__('This Wizard will show every user account where the bandwidth limit has been changed manually(custom) different from the plan assigned to the user and it allows you to reset to the limit that the assinged plan has.').'
		</div>
		<div class="sai_sub_head record-table mb-2 position-relative" style="text-align:right">
			
			<input type="button" class="btn btn-primary" value="'.__('Reset Selected').'" id="reset_selected" data-reset="checked" onclick="reset_limit(this)" disabled>
		</div>
		<div class="table-responsive">
			<table class="table sai_form webuzo-table">
				<thead>
					<tr>
						<th><input type="checkbox" id="checkAll"></th>
						<th>'.__('Users').'</th>
						<th>'.__('Domain').'</th>
						<th>'.__('Plan').'</th>
						<th>'.__('Plan Bandwidth Limit').'</th>
						<th>'.__('Current Bandwidth Limit').'</th>
						<th>'.__('Options').'</th>
					</tr>
				</thead>
				<tbody id="userlist">';
			
			if(!empty($users)){
				foreach($users as $key => $value){
					echo '
					<tr>
						<td>
							<input type="checkbox" class="check" name="checked_user" value="'.$key.'">
						</td>
						<td>
							<span>'.$key.'</span>
						</td>
						<td>
							<span>'.$value['domain'].'</span>
						</td>
						<td>
							<span>'.$value['plan'].'</span>
						</td>
						<td>
							<span>'.($value['plan_bandwidth_limit'] == "unlimited" ? $value['plan_bandwidth_limit'] : $value['plan_bandwidth_limit'].' MB').'</span>
						</td>
						<td>
							<span>'.($value['p']['max_bandwidth_limit'] == "unlimited" ? $value['p']['max_bandwidth_limit'] : $value['p']['max_bandwidth_limit'].' MB').'</span>
						</td>
						<td>
							<input type="button" class="btn btn-primary" value="'.__('Reset to Plan Bandwidth Limit').'" data-reset="single" data-user="'.$key.'" onclick="reset_limit(this)" id="reset_button">
						</td>
					</tr>';
				}
			} else {
				echo '<tr><td colspan=9><h4 style="text-align: center">'.__('No Record found').'</h4></td></tr>';
			}
			
			echo '
				</tbody>
			</table>
		</div>
	</form>
</div>

<script>

$("#checkAll").change(function (){
	$("input:checkbox").prop("checked", $(this).prop("checked"));
});

$("input:checkbox").change(function(){
	if($(".check:checked").length){
		$("#reset_selected").removeAttr("disabled");
	}else{
		$("#reset_selected").prop("disabled", true);
	}
});

// For resetting limit
function reset_limit(el){
	var jEle = $(el);
	
	// For checked users
	if(jEle.data().reset == "checked"){
		var arr = [];
		$("input:checkbox[name=checked_user]:checked").each(function(){
			arr.push($(this).val());
		});
		
		jEle.data("user", arr);
	
	// For single user
	}else{
		jEle.data("user", [jEle.data().user]);
	}
	
	var d = jEle.data();

	// Submit the data
	submitit(d,{
		done_reload: window.location
	});
	
}

</script>';
	
	softfooter();
	
}