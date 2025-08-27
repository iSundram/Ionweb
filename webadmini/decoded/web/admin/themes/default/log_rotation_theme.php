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

function log_rotation_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $logrotate_list, $log_data;

	softheader(__('Log Rotation'));

	echo '
<div class="soft-smbox p-3 col-11 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-sync-alt fa-xl me-2"></i>'.__('Log Rotation Configuration').'
		<span class="search_btn float-end">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue2" aria-expanded="true" aria-controls="search_queue2" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div class="mt-2 px-2" style="background-color:#e9ecef;">
		<div class="collapse" id="search_queue2">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >	
			<div class="row d-flex justify-content-center">
				<div class="col-12 col-md-6 mb-4 mt-2">
					<label class="sai_head">'.__('Search by logs').'</label>
					<input type="text" class="form-control search_val" name="search_log" id="log_search" value="">
				</div>
			</div>
		</form>
		</div>
	</div>
</div>
<div class="soft-smbox p-4 col-11 mx-auto mt-4">';	
	
	error_handle($error, "100%");	
	//page_links();
	
	echo '
	<div class="row">
		<div class="alert alert-warning">
			<strong>'.__('Note').' : </strong>
			<ul><li>'.__('Default size of log rotation is 300 MB.').'</li><li>'.__('Size should not be less than 10 MB').'</li></ul>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<form accept-charset="'.$globals['charset'].'" name="save" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1>
				<div class="mb-4">
					<label class="form-label">'.__('Enter size to rotate').': </label>
					<input type="number" id="log_rotate_size" style="width:80px" value="'.(!empty($globals['log_rotate_size']) ? $globals['log_rotate_size'] : '').'"> MB
					<button style="margin-left: 40px;" type="button" id="save_size" name="save_size" class="btn btn-primary" data-donereload=1 >'.__('Save Size').'</button>
				</div>
			</form>
		</div>
		<div class="col-md-6">
			<label class="form-label">'.__('Rotate by size threshold').' :</label>
			<button type="button" id="rotate_all" class="btn btn-primary mx-3" data-donereload=1 title="Rotate All" onclick="return rotate_all(this)">'.__('Enable all').'</button>
			<button type="button" id="stop_rotate_all" class="btn btn-primary" data-donereload=1 title="Stop Rotate All" onclick="return stop_rotate_all(this)">'.__('Disable all').'</button>
		</div>
	</div>
	<div class="table-responsive col-12 mx-auto">
		<table border="0" class="table table-hover-moz webuzo-table td_font">
			<thead class="sai_head2">
				<tr>
					<th style="width: 99%;">'.__('Logs').'</th>
					<th style="width: 1%;">'.__('Status').'</th>					
				</tr>
			</thead>
			<tbody>
				<tr id="nofound" style="display:none">
					<td colspan="100" class="text-center">
						<span>'.__('No results found !').'</span>						 
					</td>
				</tr>';	
			foreach($logrotate_list as $key => $value){
				echo '				
				<tr>
					<td>						
						<span class="logname" data-logname="'.$key.'">'.$value.' ('.$key.')</span>
					</td>
					<td>
						<label class="switch">
							<input type="checkbox" class="checkbox"  data-donereload="1"  data-log_name="'.$key.'" data-do="'.(empty($log_data[$key]) ? '0' : '1').'" data-action="'.(empty($log_data[$key]) ? '1' : '0').'" '.(empty($log_data[$key]) ? '' : 'checked').' onclick="return log_rotation_toggle(this)">
							<span class="slider" '.(empty($log_data[$key]) ? 'title="'.__('Click to rotate with size').'"' : 'title="'.__('Click to stop rotate with size').'"').'></span>
						</label>
					</td>
				</tr>';
			}
			
			echo '
			</tbody>
		</table>
	</div>
	<nav aria-label="Page navigation">
		<ul class="pagination pager myPager justify-content-end">
		</ul>
	</nav>
</div>

<script>
	
	$("#save_size").click(function(){
		var size = $("#log_rotate_size").val();
		
		if(empty(size)){
			return false;
		}
		var d = {"setsize": 1, "size" : size};
		submitit(d, {done_reload : window.location.href});
	});
	
	function log_rotation_toggle(ele){
		var jEle = $(ele);
		jEle.data("log_rotation", 1);
		var d = jEle.data();
		var a, lan;
		if(d.action == "1"){
			lan = "'.__js('Do you want to log rotate by size ?').'";
		}else{
			lan = "'.__js('Do you want to stop log rotate by size ?').'";
		}
		
		a = show_message_r("'.__js('Warning').'", lan);
		a.alert = "alert-warning";
		
		var no = function(){
			var status = d.do ? true : false;
			jEle.prop("checked", status);
		}
		
		// Submit the data
		a.confirm.push(function(){
			submitit(d, {done_reload : window.location.href, error: no});
		});
		
		// If user closes or chooses no
		a.no.push(no);
		
		//console.log(a);//return;
		show_message(a);
	}
	
	function rotate_all(){
		var arr = [];
		$(".logname").each(function(key,val){
			arr.push($(this).data("logname"));
		});
		
		var d = {"log_rotation" : 1, "log_name": arr, "action" : "1"};
		a = show_message_r("'.__js('Warning').'", "'.__js('Do you want all logs to rotate by size ?').'");
		a.alert = "alert-warning";
		
		a.confirm.push(function(){
			submitit(d, {done_reload : window.location.href});
		});
		show_message(a);
	
	}
	
	function stop_rotate_all(){
		var arr = [];
		$(".logname").each(function(key,val){
			arr.push($(this).data("logname"));
		});
		
		var d = {"log_rotation" : 1, "log_name": arr, "action" : "0"};
		a = show_message_r("'.__('Warning').'", "'.__('Do you want all logs to stop rotating by size ?').'");
		a.alert = "alert-warning";
		
		a.confirm.push(function(){
			submitit(d, {done_reload : window.location.href});
		});
		show_message(a);
	}
	
	$("#log_search").keyup(function(){
		var i = 0;
		var log = $(this).val();
		log = log.toLowerCase();
		$(".logname").each(function(key, tr){
			var l_name = $(this).text();
			l_name = l_name.toLowerCase();	
			if(l_name.match(log)){
				i++;
				$(this).parent().parent().show();
			}else{
				$(this).parent().parent().hide();
			}
		})
		if(i == 0){			
			$("#nofound").show();
		}else{
			$("#nofound").hide();
		}
	});
</script>';

	softfooter();
}