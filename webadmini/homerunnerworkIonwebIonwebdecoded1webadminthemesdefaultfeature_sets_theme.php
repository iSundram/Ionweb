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

function feature_sets_theme(){

global $globals, $theme, $features, $error, $done;

	softheader(__('Features Sets'));

	error_handle($error);

	echo '
<div class="modal fade" id="clone_feature_modal" tabindex="-1" aria-labelledby="atop_confLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="atop_conf_label">'.__('Clone Feature Set').'</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
			<form action="" method="POST" name="clone_feature_form" id="clone_feature_form" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1>

				<div class="row mb-3">
					<div class="col-12 col-md-6">
						<label class="sai_head" for="clone_name">'.__('Old Feature Set Name').'</label>
					</div>
					<div class="col-12 col-md-6 mb-1">
						<input type="text" name="clone" id="clone_name" class="form-control" value="" readonly />
					</div>
				</div>
				
				<div class="row mb-3">
					<div class="col-12 col-md-6">
						<label class="sai_head" for="new_name">'.__('New Feature Set Name').'</label>
					</div>
					<div class="col-12 col-md-6 mb-1">
						<input type="text" name="new_name" id="new_name" class="form-control" value="" />
					</div>
				</div>
					
				<div class="row mb-2 text-center">
					<div class="col-12">
						<input class="btn btn-primary" type="submit" name="clone_feature" id="clone_feature" value="'.__('Clone').'">
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>
<div class="col-12">
<form accept-charset="'.$globals['charset'].'" name="editfeatures" method="post" action="" class="form-horizontal">
	<div class="soft-smbox p-3">
		<div class="sai_main_head">
			<i class="fa fa-align-justify me-2"></i>'.__('Features Sets List').'
			<span class="search_btn float-end">
				<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
			</span>
		</div>
		<div class="mt-2" style="background-color:#e9ecef;">
			<div class="collapse '.(!empty(optREQ('search')) || !empty(optREQ('owner')) ? 'show' : '').'" id="search_queue">
				<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
				<div class="row p-3 col-md-12 d-flex justify-content-center">
					<div class="col-12 col-md-6">
						<label class="sai_head text-center">'.__('Search By Feature Name').'</label><br/>
						<select class="form-select make-select2" s2-placeholder="'.__('Select feature').'" s2-ajaxurl="'.$globals['index'].'act=feature_sets&api=json" s2-query="search" s2-data-key="features" s2-data-subkey="feature_sets" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="feature_search" name="feature_search">
							<option value="'.optREQ('search').'" selected="selected">'.optREQ('search').'</option>
						</select>
					</div>
					<div class="col-12 col-md-6">
						<label class="sai_head text-center">'.__('Search By Owner Name').'</label><br/>
						<select class="form-select make-select2" s2-placeholder="'.__('Select Owner').'" s2-ajaxurl="'.$globals['index'].'act=users&type=2&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'root', 'id' => 'root', 'value' => 'root'],['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="owner_search" name="owner_search">
							<option value="'.optREQ('owner').'" selected="selected">'.optREQ('owner').'</option>
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
		<div class="mb-2">
			<a type="button" class="btn btn-primary" href="'.$globals['index'].'act=add_feature_sets">'.__('Add Feature Set').'</a>
		</div>
		<div id="showfeatureslist" class="table-responsive">
			<table class="table sai_form webuzo-table" id="featureslisttable">
				<thead>	
					<tr>
						<th>'.__('Name').'</th>
						<th width="15%">'.__('Owner').'</th>
						<th colspan="4" width="2%">'.__('Options').'</th>
					</tr>
				</thead>
				<tbody>';

	if(empty($features)){
		echo '
					<tr>
						<td colspan=5 class="text-center">
							<span class="me-1">'.__('No Features Sets exists').'</span>
							<a href="'.$globals['ind'].'act=add_feature_sets">'.__('Create New').'</a>
						</td>
					</<tr>';
	}else{
		foreach($features as $key => $feature){
			
			echo '
					<tr id="tr'.$key.'">
						<td>
							'.$feature['feature_sets'].'
						</td>
						<td>
							'.(empty($feature['owner']) ? 'root' : $feature['owner']).'
						</td>
						<td>
							<i class="fas fa-copy" title="'.__('Clone').'" style="cursor:pointer;color: darkslategrey;" data-clone_feature="'.$feature['feature_sets'].'" onclick="clone_features(this)"></i>
						</td>
						<td>
							<a href="'.$globals['admin_url'].'act=add_feature_sets&feature='.$key.'"><i class="fa-regular fa-pen-to-square edit-icon" title="'.__('Edit').'"></i></a>
						</td>
						<td>
							<i class="fas fa-trash delete-icon" title="'.__('Delete').'" id="did'.$key.'" style="cursor:pointer" data-delete_feature="'.$key.'" onclick="delete_record(this)"></i>
						</td>
					</tr>';
		}
	}

	echo '
				</tbody>
			</table>
		</div>
	</div>
</form>
</div>

<script language="javascript" type="text/javascript">

$("#feature_search").on("select2:select", function(e, u = {}){		
	feature = $("#feature_search option:selected").val();
	if(feature == "all"){
		window.location = "'.$globals['index'].'act=feature_sets";
	}else{	
		window.location = "'.$globals['index'].'act=feature_sets&search="+feature;
	}
});

$("#owner_search").on("select2:select", function(e, u = {}){		
	owner = $("#owner_search option:selected").val();
	if(owner == "all"){
		window.location = "'.$globals['index'].'act=feature_sets";
	}else{	
		window.location = "'.$globals['index'].'act=feature_sets&owner="+owner;
	}
});

function clone_features(ele){
	var jEle = $(ele);
	var d = jEle.data();
	
	$("#clone_name").val(d.clone_feature);
	$("#clone_feature_modal").modal("show");	
}

</script>';

	softfooter();
}
