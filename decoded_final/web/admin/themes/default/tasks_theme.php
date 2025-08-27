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

function tasks_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $tasks, $SESS, $where;
	
	softheader(__('Tasks'));
	
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-list me-1"></i>'.__('Tasks').'
		<span class="search_btn float-end">
			<a href="javascript:void(0);" onclick="toggle_refresher()" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div class="mt-2" style="background-color:#e9ecef;">
		<div class="collapse '.(!empty(optREQ('action')) || !empty(optREQ('user')) || !empty(optREQ('actid')) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action="" class="form-horizontal">
			<div class="row p-3 col-md-12 d-flex">
				<div class="col-12 col-md-4">
					<label class="sai_head me-1">'.__('Action').'</label>	
					<input type="text" name="action" />
				</div>
				<div class="col-12 col-md-4">
					<label class="sai_head">'.__('Search By User Name').'</label><br/>
					<select class="form-select make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" style="width: 100%" id="user_search" name="user_search">
						<option value="'.optREQ('user').'" selected="selected">'.optREQ('user').'</option>
					</select>
				</div>
				<div class="col-12 col-md-4">
					<label class="sai_head">'.__('TaskID').'</label>
					<input type="text" name="actid" />
				</div>
			</div>
			<div class="col-12 text-center pb-2">
				<button class="btn btn-primary" type="submit" name="search">'.__('Search').'</button>
			</div>
			</form>
		</div>
	</div>
</div>

<div class="soft-smbox p-3 mt-4">
<form accept-charset="'.$globals['charset'].'" name="tasksform" id="tasksform" method="post" action=""; class="form-horizontal">';

	error_handle($error);
	
	page_links();
	
	echo '
	<div id="showplanlist" class="table-responsive my-2">
		<table class="table sai_form webuzo-table">
			<thead>
				<tr>
					<th width="1%">'.__( 'TaskID').'</th>
					<th width="1%">'.__('ID').'</th>
					<th width="5%">'.__('User').'</th>
					<th width="10%">'.__('Action').'</th>
					<th width="15%">'.__('Started').'</th>
					<th width="15%">'.__('Updated').'</th>
					<th width="15%">'.__('Ended').'</th>
					<th width="15%">'.__('Status').'</th>
					<th width="15%">'.__('Progress').'</th>
					<th width="1%">'.__('Logs').'</th>
					<th width="1%">'.__('Export HTML').'</th>
				</tr>
			</thead>
			<tbody id="taskslist">';
			
		if(empty($tasks)){
			
			echo '
			<tr>
				<td colspan="100">
					<span>'.__('There are no tasks at the moment.').'</span>
				</td>
			</<tr>';
						
		}else{
			
			foreach($tasks as $key => $v){
				echo '
			<tr id="tr_'.$v['actid'].'" user="'.$v['actid'].'">
				<td>'.$v['actid'].'</td>
				<td>'.$v['uuid'].'</td>
				<td>'.$v['user'].'</td>
				<td>'.$v['action_txt'].'</td>
				<td>'.$v['started'].'</td>
				<td>'.$v['updated'].'</td>
				<td>'.$v['ended'].'</td>
				<td>'.$v['status_txt'].'</td>
				<td>
					<div class="progress-cont" id="progress-cont'.$v['actid'].'">
						<center><div id="pbar'.$v['actid'].'">'.$v['progress'].'%</center>
						<div class="progress progress_'.$v['actid'].'">
							<div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width:'.($v['progress']).'%;" id="progressbar'.$v['actid'].'">
							</div>
						</div>
					</div>
				</td>
				<td>
					<a href="javascript:void(0);" onclick="return loadlogs('.$v['actid'].');" class="btn btn-primary btn-logs">'.__('Logs').'</a>
				</td>
				<td class="text-center" style="padding-top: 18px;">
					<a href="'.$globals['index'].'act=tasks&export=html&actid='.$v['actid'].'"><i class="fa-solid fa-file-download" style="font-size:18px" title="Export HTML"></i></a>
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
</div>';

	if(empty($globals['cur_page']) && empty($where)){
	
echo '
<script>

function refresh_tasks(){

	// Make an ajax call
	AJAX(window.location, function(data){
		$("#tasksform").html($(data).find("#tasksform").html());
	});

}

var tasks_refresher = false;
function toggle_refresher(){
	if(!empty(tasks_refresher)){
		clearInterval(tasks_refresher);
		tasks_refresher = false;
	}else{
		tasks_refresher = setInterval(refresh_tasks, 5000);
	}
	console.log(tasks_refresher);
}

$(document).ready(function(){
	toggle_refresher();
});

</script>';

	}

	softfooter();
	
}

