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

function plans_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $langs, $plans, $tags, $plan_fields, $planval, $SESS;

	softheader(__('Plans'));

	echo '
<div class="modal fade" id="clone_plan_modal" tabindex="-1" aria-labelledby="atop_confLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="atop_conf_label">'.__('Clone Plan').'</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
			<form action="" method="POST" name="clone_plan_form" id="clone_plan_form" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1>

				<div class="row mb-3">
					<div class="col-12 col-md-6">
						<label class="sai_head" for="clone_name">'.__('Old Plan Name').'</label>
					</div>
					<div class="col-12 col-md-6 mb-1">
						<input type="text" name="clone" id="clone_name" class="form-control" value="" readonly />
					</div>
				</div>
				
				<div class="row mb-3">
					<div class="col-12 col-md-6">
						<label class="sai_head" for="new_name">'.__('New Plan Name').'</label>
					</div>
					<div class="col-12 col-md-6 mb-1">
						<input type="text" name="new_name" id="new_name" class="form-control" value="" />
					</div>
				</div>
					
				<div class="row mb-2 text-center">
					<div class="col-12">
						<input class="btn btn-primary" type="submit" name="clone_plan" id="clone_plan" value="'.__('Clone').'">
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>
<div class="col-12">
<form accept-charset="'.$globals['charset'].'" name="editplanform" id="editplanform" method="post" action=""; class="form-horizontal">
	<div class="soft-smbox p-3">
		<div class="sai_main_head">
			<i class="fas fa-book fa-xl me-2"></i><span>'.__('Plans List').'</span>
			<div class="float-end">
				<a type="button" class="btn btn-primary" href="'.$globals['index'].'act=add_plans">'.__('Add Plan').'</a>
				<span class="search_btn float-end" style="margin-left:10px">
					<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
				</span>
				</div>
		</div>
		<div class="mt-2" style="background-color:#e9ecef;">
			<div class="collapse '.(!empty(optREQ('search')) || !empty(optREQ('owner')) || !empty(optREQ('user')) ? 'show' : '').'" id="search_queue">
				<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
				<div class="row p-3 col-md-12 d-flex justify-content-center">
					<div class="col-12 '.(empty($SESS['is_reseller']) ? 'col-md-4' : 'col-md-6').'">
						<label class="sai_head text-center">'.__('Search By Plan Name').'</label><br/>
						<select class="form-select make-select2" s2-placeholder="'.__('Select plan').'" s2-ajaxurl="'.$globals['index'].'act=plans&api=json" s2-query="search" s2-data-key="plans" s2-data-subkey="plan_name" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="plan_search" name="plan_search">
							<option value="'.optREQ('search').'" selected="selected">'.optREQ('search').'</option>
						</select>
					</div>';
					
					if(empty($SESS['is_reseller'])){
						echo '
					<div class="col-12 col-md-4">
						<label class="sai_head text-center">'.__('Search By Owner Name').'</label><br/>
						<select class="form-select make-select2" s2-placeholder="'.__('Select Owner').'" s2-ajaxurl="'.$globals['index'].'act=users&type=2&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'root', 'id' => 'root', 'value' => 'root'],['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="owner_search" name="owner_search">
							<option value="'.optREQ('owner').'" selected="selected">'.optREQ('owner').'</option>
						</select>
					</div>';
					}
					
						echo '
					<div class="col-12 '.(empty($SESS['is_reseller']) ? 'col-md-4' : 'col-md-6').'">
						<label class="sai_head text-center">'.__('Search By User Name').'</label><br/>
						<select class="form-select make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" style="width: 100%" id="user_search" name="user_search">
							<option value="'.optREQ('user').'" selected="selected">'.optREQ('user').'</option>
						</select>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>';
	
	error_handle($error);
	
	echo '
	<div class="soft-smbox p-4 mt-4">
	<div id="showplanlist" class="table-responsive">
		<table class="table sai_form webuzo-table" id="planlisttable">
			<thead>	
				<tr>
					<th width="15%">'.__('Plan Name').'</th>
					<th width="15%">'.__('Owner').'</th>
					<th width="10%">'.__('Num Users').'</th>
					<th>'.__('Users').'</th>
					<th colspan="4" width="2%">'.__('Options').'</th>
				</tr>
			</thead>
			<tbody>';

			if(empty($plans)){
				echo '
					<tr>
						<td class="text-center" colspan="4">
							<span class="me-1">'.__('No Plan exists').'</span>
							<a href="'.$globals['ind'].'act=add_plans">'.__('Create New').'</a>
						</td>
					</<tr>';
			}else{
			
				foreach ($plans as $key => $plan){
					echo '
					<tr id="tr'.$key.'">
						<td>'.$plan['plan_name'].'<br><span class="sai_exp2" style="font-size:10px">'.$plan['slug'].'</span></td>
						<td>'.(empty($plan['plan_owner']) ? 'root' : $plan['plan_owner']).'</td>
						<td>'.$plan['num_users'].'</td>
						<td>'.implode(', ', $plan['users']).'</td>
						<td>
							<i class="fas fa-copy" title="'.__('Clone').'" style="cursor:pointer;color: darkslategrey;" data-clone_plan="'.$plan['plan_name'].'" onclick="clone_plan(this)"></i>
						</td>
						<td>
							<a href="'.$globals['admin_url'].'act=add_plans&plan='.$key.'">
								<i class="fas fa-pencil-alt edit-icon"></i>
							</a>
						</td>
						<td>
							<i class="fas fa-trash delete-icon" title="'.__('Delete').'" id="did'.$key.'" data-delete_plan="'.$key.'" onclick="delete_record(this)"></i>
						</td>
					</tr>';
				}
			}

			echo '
			</tbody>
		</table>
	</div>
</form>
</div>

<script language="javascript" type="text/javascript">

$("#plan_search").on("select2:select", function(e, u = {}){		
	plan = $("#plan_search option:selected").val();
	if(plan == "all"){
		window.location = "'.$globals['index'].'act=plans";
	}else{	
		window.location = "'.$globals['index'].'act=plans&search="+plan;
	}
});

$("#owner_search").on("select2:select", function(e, u = {}){		
	owner = $("#owner_search option:selected").val();
	if(owner == "all"){
		window.location = "'.$globals['index'].'act=plans";
	}else{	
		window.location = "'.$globals['index'].'act=plans&owner="+owner;
	}
});

$("#user_search").on("select2:select", function(e, u = {}){		
	user = $("#user_search option:selected").val();
	if(user == "all"){
		window.location = "'.$globals['index'].'act=plans";
	}else{	
		window.location = "'.$globals['index'].'act=plans&user="+user;
	}
});

function clone_plan(ele){
	var jEle = $(ele);
	var d = jEle.data();
	
	$("#clone_name").val(d.clone_plan);
	$("#clone_plan_modal").modal("show");	
}

</script>';

	softfooter();

}

