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

function massmodify_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $users, $language_options, $plan_theme, $plans, $ips, $owners, $SESS;

	softheader(__('Modify Multiple Accounts'));
	error_handle($error);

	echo '
	<div class="soft-smbox p-3 col-12 col-md-12 mx-auto">
		<form accept-charset="'.$globals['charset'].'" name="massmodifyform" id="massmodifyform" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)">
			<div class="sai_main_head">
				<i class="fas fa-sliders-h me-2"></i> '.__('Modify Multiple Accounts').'
				<span class="search_btn float-end">
					<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
				</span>
			</div>
			<div class="mt-2" style="background-color:#e9ecef;">
				<div class="collapse '.(!empty(optREQ('search')) || !empty(optREQ('domain')) || !empty(optREQ('owner')) || !empty(optREQ('email')) || !empty(optREQ('ip')) ? 'show' : '').'" id="search_queue">
					<div class="row p-3 col-md-12 d-flex">
						<div class="" style="text-align:center;">
						<input type="radio" id="by_domain" name="radio_search" value="by_domain" class="me-1 filter_radio">
						<label for="by_domain" style="padding: 5px 5px 5px 0;" class="sai_head">'.__('Search by Domain').'</label>

						<input type="radio" id="by_user" name="radio_search" value="by_user" class="me-1 filter_radio">
						<label for="by_user" style="padding: 8px;" class="sai_head">'.__('Search by User').'</label>

						<input type="radio" id="by_owner" name="radio_search" value="by_owner" class="me-1 filter_radio">
						<label for="by_owner" style="padding: 8px;" class="sai_head">'.__('Search by Reseller/Owner').'</label>

						<input type="radio" id="by_package" name="radio_search" value="by_package" class="me-1 filter_radio">
						<label for="by_package" style="padding: 8px;" class="sai_head">'.__('Search by Package').'</label>

						<input type="radio" id="by_ipv4" name="radio_search" value="by_ipv4" class="me-1 filter_radio">
						<label for="by_ipv4" style="padding: 8px;" class="sai_head">'.__('Search by ipv4').'</label>

						<input type="radio" id="by_ipv6" name="radio_search" value="by_ipv6" class="me-1 filter_radio">
						<label for="by_ipv6" style="padding: 8px;" class="sai_head">'.__('Search by ipv6').'</label>

						<input type="text" placeholder="Search Box" name="search_key" id="search_key" class="form-control">
						<div class="text-center">
							<input type="checkbox" class="check_current" value="1" name="extact_search" id="extact_search">
							<label for="extact_search" style="padding: 8px;" class="sai_head">'.__('Perform Exact Match').'</label>
						</div>
						<div class="text-center my-3">
							<input type="button" class="btn btn-secondary" id="search_select" name="search_select" onclick=" return apply_filter(\'select\')"  value="'.__('Select Matching Users').'" />
							<input type="button" class="btn btn-secondary" id="search_deselect" name="search_deselect" onclick=" return apply_filter()" value="'.__('DeSelect Matching Users').'"/>
						</div>
					</div>
				</div>
			</div>

			<div class="soft-smbox p-4 col-12 col-md-12 mx-auto mt-4">
				<div id="" class="table-responsive mt-4">
					<table class="table sai_form webuzo-table">
					<thead>
						<tr>
							<th width="10%"></th>
							<th width="18%">'.__('Domain').'</th>
							<th width="18%">'.__('User').'</th>
							<th width="18%">'.__('Owner').'</th>
							<th width="18%">'.__('Package').'</th>
							<th width="18%">'.__('IP').'</th>
							<th width="18%">'.__('IPv6').'</th>
							<th width="18%">'.__('Setup Date').'</th>
						</tr>
					</thead>
					<tbody id="userslist">';

			if(empty($users)){
				echo '
					<tr>
						<td colspan="100" class="text-center">
							<span>'.__('No Users Exist').'</span>
						</td>
					</tr>';
			}else{
				foreach($users as $key => $value){
					echo '
						<tr class="table_row">
							<td>
								<input type="checkbox" class="check_current" value="'.$value['user'].'" name="check_list[]" />
							</td>
							<td>
								<span class="search_by_domain">'.$value['domain'].'</span>
							</td>
							<td>
								<span class="search_by_user">'.$value['user'].'</span>
							</td>
							<td>
								<span class="search_by_owner">'.$value['owner'].'</span>
							</td>
							<td>
								<span class="search_by_package">'.(!empty($value['plan']) ? $value['plan'] : '---').'</span>
							</td>
							<td>
								<span class="search_by_ipv4">'.(!empty($value['ips'][4][0]) ? $value['ips'][4][0] : '---').'</span>
							</td>
							<td>
								<span class="search_by_ipv6">'.(!empty($value['ips'][6][0]) ? $value['ips'][6][0] : '---').'</span>
							</td>
							<td>
								<span>'.date('m/d/Y H:i:s', $value['created']).'</span>
							</td>
						</tr>';
				}
			}

			echo '</tbody>
				</table>
			</div>';

			echo '
			<center>
				<button type="button" class="btn btn-primary" id="massmodify_modal_btn" data-bs-toggle="modal" data-bs-target="#massmodify_modal">
				'.__('With Selected').'
				</button>
			</center>

			<!-- Massmodify Setting Modal -->
			<div class="modal fade" id="massmodify_modal" tabindex="-1" aria-labelledby="massmodifyLabel" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h6 class="modal-title" id="bl_import_label">'.__('Settings').'</h6>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="sai_form">
								<div class="row">
									<div class="col-sm-5">
										<label for="reseller" class="sai_head">'.__('Owner').'</label>
									</div>
									<div class="col-sm-7">
										<select class="form-select" name="owner" id="owner" >
											<option value="">Choose a Owner</option>';
											foreach($owners as $owner => $ownerval){
												echo '<option value="'.$owner.'">'.$owner.'</option>';
											}
										echo '</select>
									</div>
								</div><br />

								<div class="row">
									<div class="col-sm-5">
										<label for="start_date" class="sai_head">'.__('Start Date').'</label>
									</div>
									<div class="col-sm-7">
										<input class="form-control" type="datetime-local" id="start_date" name="start_date">
									</div>
								</div><br />

								<div class="row">
									<div class="col-sm-5">
										<label for="theme" class="sai_head">'.__('Webuzo Theme').'</label>
									</div>
									<div class="col-sm-7">
										<select class="form-select" name="theme" id="theme" >
											<option value="">Choose a Theme</option>';
											foreach($plan_theme as $k => $v){
												echo '<option value="'.$k.'" >'.$v.'</option>';
											}
										echo '</select>
									</div>
								</div><br />

								<div class="row">
									<div class="col-sm-5">
										<label for="locale" class="sai_head">'.__('Locale').'</label>
									</div>
									<div class="col-sm-7">
										<select class="form-select" name="locale" id="locale" >
											<option value="">Choose a Locale</option>';
											foreach($language_options as $k => $v){
												echo '<option value="'.$k.'" >'.$v.'</option>';
											}
										echo '</select>
									</div>
								</div><br />

								<div class="row">
									<div class="col-sm-5">
										<label for="package" class="sai_head">'.__('Plan').'</label>
									</div>
									<div class="col-sm-7">
										<select class="form-select" name="plan" id="plan" >
											<option value="">Choose a Plan</option>';
											foreach($plans as $k => $v){
												echo '<option value="'.$k.'">'.$v['plan_name'].'</option>';
											}
										echo '</select>
									</div>
								</div><br />

								<div class="row">
									<div class="col-sm-5">
										<label for="ips" class="sai_head">'.__('Account IP').'</label>
									</div>
									<div class="col-sm-7">
										<div class="row">
											<div class="col-sm-6">
												<label class="sai_head">'.__('IPv4').'</label>			
												<select name="ip" id="ip" class="form-select">
													<option value="">'.__('Default').'</option>';
													if(!empty($ips) && is_array($ips)){
														foreach ($ips as $k => $v){
															if($v['type'] != 4){
																continue;
															}
															
															echo '<option value="'.$k.'">'.$k.'</option>';
														}
													}
												echo '
												</select>
											</div>
											<div class="col-sm-6">
												<label class="sai_head">'.__('IPv6').'</label>			
												<select name="ipv6" id="ipv6" class="form-select">
													<option value="">'.__('Default').'</option>';
													if(!empty($ips) && is_array($ips)){
														foreach ($ips as $k => $v){
															if($v['type'] != 6){
																continue;
															}

															echo '<option value="'.$k.'">'.$k.'</option>';
														}
													}
												echo '
												</select>
											</div>
											<div class="col-md-12 mt-3">Note: Only <b>Shared</b> IP\'s will be listed.</div>
										</div>
									</div>
								</div>
								<br />

								<div class="text-center my-3">'.csrf_display().'
									<input type="submit" class="btn btn-primary" id="modify_account" name="modify_account" value="'.__('Save Changes').'"/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Massmodify Setting Modal End-->
		</form>
	</div>';

	echo '
<script language="javascript" type="text/javascript">

	function apply_filter(select){

		select = select || 0;

		//get search key value
		search_key = $("#search_key").val();

		$(".filter_radio").each(function(){
		  	var radio_id = $(this).attr("id");
	        if($("input:radio[id="+radio_id+"]:checked").length > 0){
		       value = $(this).val();

		       // Search in respective row
		       $(".search_"+value).each(function(){
		       		html = $(this).html();
					
				var is_exact = $("#extact_search").is(":checked");
				var match = is_exact ? html === search_key : html.indexOf(search_key) !== -1;
					
		       		// check if value matches					
				if(match){
						
					// checked the checkbox
					if(select){
						$(this).closest(".table_row").find(".check_current").prop(\'checked\', true);
					}else{
						$(this).closest(".table_row").find(".check_current").prop(\'checked\', false);
					}
				}
		       })
		    }
		});

		// Enable/Disable Massmodify Setting button
		if ($(".check_current").filter(":checked").length < 1){
			$("#massmodify_modal_btn").attr("disabled" , true);
		}else{
			$("#massmodify_modal_btn").attr("disabled" , false);
		}
	}

	$(document).ready(function(){
		// Enable/Disable Massmodify Setting button
		$("#massmodify_modal_btn").attr("disabled" , true);
		$(".check_current").on("click, change", function(event){

			if ($(".check_current").filter(":checked").length < 1){
				$("#massmodify_modal_btn").attr("disabled" , true);
			}else{
				$("#massmodify_modal_btn").attr("disabled" , false);
			}
		});
	});

</script>';

	softfooter();


}

