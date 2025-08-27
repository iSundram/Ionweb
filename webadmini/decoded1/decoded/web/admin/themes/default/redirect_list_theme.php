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

function redirect_list_theme(){

global $globals, $theme, $error, $redirect_list;
	
	softheader(__('Redirect List'));

	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-th-list fa-xl me-2"></i>'.__('Redirect List').'
		<span class="search_btn float-end">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div class="mt-2" style="background-color:#e9ecef;">
		<div class="collapse '.(!empty(optREQ('user_search')) || !empty(optREQ('dom_search')) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
			<div class="row p-3 col-md-12 d-flex">
				<div class="col-12 col-md-6">
					<label class="sai_head">'.__('Search By Domain Name').'</label>
					<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select by Domain').'" s2-ajaxurl="'.$globals['index'].'act=domains&api=json" s2-query="dom_search" s2-data-key="domains" s2-data-subkey="domain" style="width: 100%" name="dom_search" id="dom_search">
						<option value="'.optREQ('dom_search').'" selected="selected">'.optREQ('dom_search').'</option>
					</select>
				</div>
				<div class="col-12 col-md-6">
					<label class="sai_head">'.__('Search By User Name').'</label><br/>
					<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" style="width: 100%" id="user_search" name="user_search">
						<option value="'.optREQ('user_search').'" selected="selected">'.optREQ('user_search').'</option>
					</select>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="soft-smbox p-4 mt-4">
	<div class="row">
		<div class="col-12 col-sm-4">
			<span class="form-label">'.__('Total Redirects').': </span>
			<span class="tot_count">'.$globals['total_redirects'].'</span>
		</div>
	</div>';

	error_handle($error, "100%");
	page_links();

	echo '
	<div class="table-responsive">
		<table border="0" cellpadding="8" cellspacing="1" class="table table-hover-moz webuzo-table td_font">
			<thead class="sai_head2">
				<tr>
					<th width="12%">'.__('Domain Name').'</th>
					<th width="10%">'.__('User Name').'</th>
					<th width="30%">'.__('Path').'</th>
					<th width="8%">'.__('Type').'</th>
					<th width="15%">'.__('Address').'</th>
					<th width="1%">'.__('Action').'</th>
				</tr>
			</thead>
			<tbody id="dom_list">';
			if(!empty($redirect_list)){
				foreach ($redirect_list as $key => $val){		
					echo'		
				<tr id="tr'.md5($key).'">					
					<td>
						<a target="_blank" href="http://'.$val['domain'].$val['path'].'">'.$val['domain'].'</a>
					</td>						
					<td>
						<span>'.$val['user'].'</span>
					</td>
					<td>
						<span>'.$val['path'].'</span>
					</td>
					<td>
						<span>'.$val['type'].'</span>
					</td>
					<td>
						<span>'.$val['address'].'</span>
					</td>
					<td class="text-end">
						<i class="fas fa-trash del_redirect delete-icon" title="'.__('Delete').'" id="did'.md5($key).'" onclick="delete_record(this)" data-domain="'.$val['domain'].'" data-delete="'.$val['user'].'" data-path="'.$val['path'].'" data-type="'.$val['type'].'" data-address="'.$val['address'].'"></i>
					</td>	
				</tr>';								
				}
			}else{
				echo '
				<tr>
					<td colspan="100"><h3 style="text-align: center">'.__('No Record found').'</h3></td>
				</tr>';				
			}
			echo '
			</tbody>
		</table>
	</div>
</div>

<script>
$(document).ready(function () {
	$("#user_search").on("select2:select", function(){	
		user = $("#user_search option:selected").val();	
		window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'&user_search="+user;
	});
	
	$("#dom_search").on("select2:select", function(){
		var domain_selected = $("#dom_search option:selected").val();
		window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'&dom_search="+domain_selected;		
	});
});

</script>';

	softfooter();
	
}
