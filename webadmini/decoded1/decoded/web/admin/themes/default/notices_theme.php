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

function notices_theme(){

global $globals, $softpanel, $theme, $done, $error, $notices, $start_date, $end_date;	
	
	softheader(__('Notices'));
	
	echo '
<div class="soft-smbox col-12 p-3 mx-auto">
	<div class="sai_main_head">
			<img src="'.$theme['images'].'notice.gif" /> '.__('Notices').'
			<span class="search_btn float-end">
				<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'">
					<i class="fas fa-search"></i>
				</a>
			</span>
	</div>
	<div class="show p-2 mt-2" id="search_queue" style="background-color:#e9ecef;">
	<form accept-charset="'.$globals['charset'].'" name="notices_search" method="POST" >
		<input type="hidden" name="act" value="notices">
		<div class="row">
			<div class="col-md-4 col-lg-6">
				<label>'.__('Select type').' : </label>
				<select class="form-control" name="searchin" id="searchin" onchange="show_search()">
					<option value="" '.REQselect('searchin', '').'>'.__('All').'</option>
					<option value="nkey" '.REQselect('searchin', 'nkey').'>'.__('Notice Key').'</option>
					<option value="title" '.REQselect('searchin', 'title').'>'.__('Notice Title').'</option>
					<option value="body" '.REQselect('searchin', 'body').'>'.__('Notice').'</option>
					<option value="dismissed" '.REQselect('searchin', 'dismissed').'>'.__('Dismiss').'</option>
					<option value="created" '.REQselect('searchin', 'created').'>'.__('Created Date').'</option>
					<option value="updated" '.REQselect('searchin', 'updated').'>'.__('Updated Date').'</option>
				</select>
			</div>
			<div class="col-md-4 col-lg-6" id="searchbox">				
				<label>'.__('Search').':</label>
				<input class="form-control" name="search" id="search" value="'.optREQ('search').'" placeholder="'.__('Search').'">			
			</div>
			<div class="col-md-2" id="dismiss">
				<label>'.__('Select type').':</label>
				<select class="form-control" name="dismiss">
					<option value="1" '.REQselect('dismiss', '1').'>'.__('Dismissed').'</option>
					<option value="" '.REQselect('dismiss', '').'>'.__('Non Dismissed').'</option>
				</select>
			</div>
			<div class="col-md-2 date">
				<label>'.__('Start Date').' :</label>
				<input type="datetime-local" class="form-control" name="start_date" id="start_date" value="'.date("Y-m-d\TH:i", $start_date).'" max="'.date("Y-m-d\TH:i").'">
			</div>
			<div class="col-md-2 date">
				<label>'.__('End Date').' :</label>
				<input type="datetime-local" class="form-control" name="end_date" id="end_date" value="'.date("Y-m-d\TH:i", $end_date).'" max="'.date("Y-m-d\TH:i").'">
			</div>
			<div class="col-md-12" style="margin-top: 10px;">
				<center>
					<button class="btn btn-primary" type="submit" name="run_report">'.__('Search').'</button>
				</center>
			</div>
		</div>
	</form>
	</div>
</div>
<div class="soft-smbox col-12 mx-auto p-4 mt-4">';	
	page_links();
	echo '
	<div id="show_notices" class="table-responsive mt-4">
		<table class="table sai_form webuzo-table">
			<thead>
				<tr>
					<th>#</th>
					<th>'.__('Notice Key').'</th>
					<th>'.__('Notice Title').'</th>
					<th>'.__('Notice').'</th>
					<th>'.__('Dismiss').'</th>
					<th>'.__('Created').'</th>
					<th>'.__('Updated').'</th>
				</tr>
			</thead>
			<tbody>';
			if(empty($notices)){
				echo '<tr><td colspan="6" class="text-center">'.__('The server is running just fine ! There are no notices.').'</td></td>';
			}else{
				
				foreach($notices as $k => $v){	
				echo '
				<tr>
					<td>'.$v['nid'].'</td>
					<td>'.$v['nkey'].'</td>
					<td width="20%">'.$v['title'].'</td>
					<td width="30%">'.$v['body'].'</td>
					<td>'.(!empty($v['dismissable']) ? '<input type="checkbox" title="dismiss notice" name="dismiss_'.$v['nid'].'" '.(!empty($v['dismissed']) ? 'checked disabled' : '').' data-nid="'.$v['nid'].'" class="notice-disable">' : '').'</td>
					<td>'.datify($v['created']).'</td>
					<td>'.datify($v['updated']).'</td>
				</tr>';
				
				}
			}
			
			echo '
			</tbody>
		</table>
	</div>';
	page_links();
	echo '
</div>

<script>
$(".notice-disable").click(function(){
	if($(this).is(":checked")){
		$(this).attr("disabled", true);
		dismiss_notice(this);
	}
});

$(document).ready(function () {
	show_search();
});

function show_search(){
	
	var searchin = $("#searchin").val();
	
	if(searchin == "created" || searchin == "updated"){
		$(".date").show();
		$("#searchbox").hide();
		$("#dismiss").hide();
	}else if(searchin == "dismissed"){
		$("#dismiss").show();
		$("#searchbox").hide();
		$(".date").hide();
	}else{
		$("#searchbox").show();
		$(".date").hide();
		$("#dismiss").hide();
	}
};

</script>';

	softfooter();
}