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

function servergroups_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $servergroups, $SESS, $server_groups, $sg_selection;
	
	softheader(__('Server Groups'));
	
	echo '
	<div class="soft-smbox p-3">
		<div class="sai_main_head">
			<i class="fas fa-servergroups me-1"></i>'.__('Server Groups').'
			<!--<span class="search_btn float-end">
				<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
			</span>-->
		</div>
		<!--<div class="mb-4 mt-2" style="background-color:#e9ecef;">
			<div class="collapse '.(!empty(optREQ('search')) ? 'show' : '').'" id="search_queue">
				<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
				<div class="row p-3 col-md-12 d-flex">
					<div class="col-12 col-md-4">
						<label class="sai_head">'.__('Search by Name').'</label><br/>
						<select class="form-select make-select2" s2-placeholder="'.__('Server Group Name').'" s2-ajaxurl="'.$globals['index'].'act=servergroups&api=json" s2-query="search" s2-data-key="servergroups" s2-data-subkey="name" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="sg_search" name="search">
							<option value="'.optREQ('search').'" selected="selected">'.optREQ('search').'</option>
						</select>
					</div>
				</div>
				</form>
			</div>
		</div>-->
	</div>
	<div class="soft-smbox p-3 mt-4">
		<form accept-charset="'.$globals['charset'].'" name="servergroupsform" id="servergroupsform" method="post" action=""; class="form-horizontal">';
		
		//page_links();
		
		echo '
			<div class="row">
				<div class="col-6">
					<input type="button" class="btn btn-danger" value="'.__('Delete Selected').'" onclick="delete_selected(this)" data-class="sgcheck" data-deletekey="delete" data-donereload=1 id="sg_delete_selected" disabled>
				</div>
				<div class="col-6 sai_sub_head record-table position-relative" style="text-align:right;">
					<a type="button" class="btn btn-primary text-decoration-none" href="'.$globals['index'].'act=add_server_group">'.__('Add Server Group').'</a>
				</div>
			</div>
			<div id="showservergroupslist" class="table-responsive mt-3">
				<table class="table sai_form webuzo-table">
				<thead>
					<tr>
						<th width="1%"><input type="checkbox" id="checkAll"></th>
						<th width="5%">'.__('Name').'</th>
						<th width="15%">'.__('Description').'</th>
						<th width="5%">'.__('Region').'</th>
						<th width="15%">'.__('Server Selection').'</th>
						<th width="20%">'.__('Servers').'</th>
						<th colspan="2" width="1%">'.__('Manage').'</th>
					</tr>
				</thead>
				<tbody id="servergroupslist">';
					
		if(empty($servergroups)){
			echo '
					<tr>
						<td colspan="100" class="text-center">
							<span>'.__('No servergroups found. Please add a server group.').'</span>
						</td>
					</tr>';
		}else{
			
			foreach($servergroups as $key => $value){
				
				echo '
					<tr id="tr'.$key.'">
						<td>
							<input type="checkbox" class="check sgcheck" name="checked" value="'.$key.'">
						</td>
						<td>'.$value['name'].'</td>
						<td>'.$value['desc'].'</td>
						<td>'.$value['region'].'</td>
						<td>'.$sg_selection[$value['server_selection']].'</td>
						<td>'.implode(', ', $value['servers']).'</td>
						<td>
							<a href="'.$globals['admin_url'].'act=add_server_group&uuid='.$key.'">
								<i class="fas fa-pencil-alt edit-icon" title="'.__('Edit').'"></i>
							</a>
						</td>
						<td>
							'.($key != 'default' ? '<i class="fas fa-trash delete-icon" onclick="delete_record(this)" id="did'.$key.'" data-delete="'.$key.'"  title="'.__('Delete').'"></i>' : '').'
						</td>
					</tr>';
			}
			
		}
		
		echo '
					</tbody>
				</table>
			</div>';
			
			//page_links();
			
			echo'
		</form>
	</div>
<script>
$(document).ready(function (){
	
	$("#checkAll").change(function () {
		$(".sgcheck").prop("checked", $(this).prop("checked"));
		$(".sgcheck").change();
	});
	
	$(".sgcheck").change(function() {
		if($(".sgcheck:checked").length){
			$("#sg_delete_selected").removeAttr("disabled");
		}else{
			$("#sg_delete_selected").prop("disabled", true);
		}
	});
	
});

$("#sg_search").on("select2:select", function(e, u = {}){		
	user = $("#sg_search option:selected").val();
	if(user == "all"){
		window.location = "'.$globals['index'].'act=servergroups'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}else{
		window.location = "'.$globals['index'].'act=servergroups&search="+user+"'.(!empty(optREQ('suspended')) ? '&suspended=1' : '').''.(!empty(optREQ('type')) ? '&type=2' : '').'";
	}
});

</script>';

	softfooter();
	
}

