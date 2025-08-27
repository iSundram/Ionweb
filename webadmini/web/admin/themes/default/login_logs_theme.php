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

function login_logs_theme(){
	
global $user, $globals, $theme, $softpanel, $WE, $error, $logs, $done, $SESS;

	softheader(__('Login Logs'));
	
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'login_logs.png" alt="" class="webu_head_img"/>
		'.__('Login Logs').'
		
		<span class="search_btn float-end mt-2 p-2">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div class="mt-2" style="background-color:#e9ecef;">
		<div class="collapse '.(!empty(optREQ('search')) || !empty(optREQ('user')) || !empty(optREQ('owner')) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
			<div class="row p-3 col-md-12 d-flex justify-content-center">
				<div class="col-12 '.(empty($SESS['is_reseller']) ? 'col-md-4' : 'col-md-6').'">
					<label class="sai_head">'.__('Search By User Name').'</label><br/>
					<select class="form-select make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="user_search" name="user_search">
						<option value="'.optREQ('user').'" selected="selected">'.optREQ('user').'</option>
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
			</div>
			</form>
		</div>
	</div>
</div>
<div class="soft-smbox p-3 mt-4">';
	page_links();
	
	echo '
	<div id="show_log">
		<div id="display_tab" class="table-responsive">
			<table class="table sai_form webuzo-table">
			<thead>
				<tr>
					<th>'.__('Date').'</th>
					<th>'.__('User').'</th>
					<th>'.__('IP Address').'</th>
					<th width="10%">'.__('Status').'</th>			
				</tr>
			</thead>
			<tbody>';
			
	if(!empty($logs)){
		foreach ($logs as $key => $value){
			echo '
				<tr id="tr'.$key.'">
					<td>'.datify($value['time'], 1, 1, 0).'</td>
					<td>'.$value['user'].'</td>
					<td>'.$value['ip'].'</td>			
					<td>'.(empty($value['status']) ? '<font color="#FF0000">'.__('Failed').'</font>' : '<font color="#009900">'.($value['status'] == 2 ? '2FA -' : '').__('Successful').'</font>').'</td>
				</tr>';
		}
		echo '
			</tbody>
			</table>
			<div class="text-center">
				<input type="submit" value="'.__('Delete All Records').'" name="delete" id="delete" class="btn btn-primary" data-delete_all=1 />
			</div>';
		
	}else{
			echo '
				<tr>
					<td colspan="100" class="text-center">
						<span>'.__('There are no login logs as of now').'</span>
					</td>
				</<tr>
			</tbody>
			</table>';
	}
	
	echo '
		</div>
	</div>
</div>

<script language="javascript" type="text/javascript">

$("#user_search").on("select2:select", function(e, u = {}){		
	user = $("#user_search option:selected").val();
	if(user == "all"){
		window.location = "'.$globals['index'].'act=login_logs";
	}else{
		window.location = "'.$globals['index'].'act=login_logs&user="+user;
	}
});

$("#owner_search").on("select2:select", function(e, u = {}){
console.log(111);	
	owner = $("#owner_search option:selected").val();
	if(owner == "all"){
		window.location = "'.$globals['index'].'act=login_logs";
	}else{
		window.location = "'.$globals['index'].'act=login_logs&owner="+owner;
	}
});

// For deleting record
$("#delete").click(function() {
	var jEle = $(this);
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete the Login Logs ?').'");
	a.alert = "alert-warning";
	a.confirm.push(function(){
		var d = jEle.data();
		submitit(d, {
			done_reload:window.location
		});
	});
	
	show_message(a);
});

</script>';
		
	softfooter();
	
}

