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

function storage_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $storage, $SESS;
	
	softheader(__('Storage'));
	
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-hdd fa-xl me-2"></i>'.__('Storage').'
		<span class="search_btn float-end">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div class="mt-2" style="background-color:#e9ecef;">
		<div class="collapse '.(!empty(optREQ('storage')) || !empty(optREQ('search')) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
			<div class="row p-3 col-md-12 d-flex justify-content-center">
				<div class="col-12 col-md-6">
					<label class="sai_head text-center">'.__('Search By Storage Name').'</label><br/>
					<select class="form-select make-select2" s2-placeholder="Select storage" s2-ajaxurl="'.$globals['index'].'act=storage&api=json" s2-query="storage" s2-data-key="storage" s2-data-subkey="name" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="storage_search" name="storage_search">
						<option value="'.optREQ('storage').'" selected="selected">'.optREQ('storage').'</option>
					</select>
				</div>
				<div class="col-12 col-md-6">
					<label class="sai_head">'.__('Search By User Name').'</label><br/>
					<select class="form-select make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" style="width: 100%" id="user_search" name="user_search">
						<option value="'.optREQ('search').'" selected="selected">'.optREQ('search').'</option>
					</select>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="soft-smbox p-3 mt-4">
	<form accept-charset="'.$globals['charset'].'" name="usersform" id="usersform" method="post" action="" class="form-horizontal">';
	
	error_handle($error);
	
	page_links();
	
	echo '
	<div id="showplanlist" class="table-responsive">
		<table class="table sai_form webuzo-table">
			<thead>
			<tr>
				<th width="10%">'.__('ID').'</th>
				<th width="15%">'.__('Name').'</th>
				<th width="15%">'.__('Path').'</th>
				<th width="1%">'.__('Type').'</th>
				<th width="1%">'.__('Alert').'</th>
				<th>'.__('Users').'</th>
				<th width="5%">'.__('Size').'</th>
				<th width="5%">'.__('Used').'</th>
				<th width="5%">'.__('Available').'</th>
				<th colspan="4" width="5%">'.__('Options').'</th>
			</tr>
			</thead>
			<tbody id="userslist">';
	
	if(empty($storage)){
		
		echo '
			<tr class="text-center">
				<td colspan="100">
					<span class="me-1">'.__('No Users Exist').'</span>
					<a href="'.$globals['ind'].'act=add_user">'.__('Create New User').'</a>
				</td>
			</<tr>';
			
	}else{
		
		foreach($storage as $k => $v){
			echo '
			<tr id="tr'.$v['uuid'].'" val="'.$k.'">
				<td>'.$v['uuid'].'</td>
				<td>'.$v['name'].'</td>
				<td>
					<a href="'.$globals['index'].'act=addstorage&storage='.$k.'" target="blank">'.$k.'</a>
				</td>
				<td>'.$v['type'].'</td>
				<td>'.$v['alert'].'%</td>
				<td>'.implode(', ', array_keys($v['users'])).'</td>
				<td>'.round($v['size'], 2).' GB</td>
				<td>'.round($v['used'], 2).' GB</td>
				<td>'.round($v['free'], 2).' GB</td>
				<td>
					<a href="'.$globals['admin_url'].'act=editstorage&storage='.$k.'" title="'.__('Edit').'">
						<i class="fas fa-pencil-alt edit-icon"></i>
					</a>
				</td>
				<td>
					<i class="fas fa-trash text-danger delete-icon" onclick="delete_record(this)" id="did'.$v['uuid'].'" data-delete="'.$k.'" title="'.__('Delete').'"></i>
				</td>
			</tr>';
			
		}
	}
	
	echo '
			</tbody>
		</table>
	</div>';
	page_links();
	echo '
	</form>
</div>

<script language="javascript" type="text/javascript">

$("#storage_search").on("select2:select", function(e, u = {}){		
	var storage = $("#storage_search option:selected").val();
	if(storage == "all"){
		window.location = "'.$globals['index'].'act=storage";
	}else{	
		window.location = "'.$globals['index'].'act=storage&storage="+storage;
	}
});

$("#user_search").on("select2:select", function(e, u = {}){		
	user = $("#user_search option:selected").val();	
	window.location = "'.$globals['index'].'act=storage&search="+user;
	
});

</script>';

	softfooter();
	
}

