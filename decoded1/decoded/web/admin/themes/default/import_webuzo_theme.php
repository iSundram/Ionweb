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

function import_webuzo_theme(){
	
	global $user, $globals, $theme, $softpanel, $error, $done, $migrate_log, $local_users;
	
	softheader(__('Import From Webuzo'));

	echo '
<style>
.sai_popup {
	position:absolute;
	background:#FFFFFF;
	border:#666 1px solid;
	display:none;
	z-index:10000;
	min-height:200px;
	padding:5px;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}	
.heading_a{
	border-radius: 5px;
	border :2px solid  #FFFFFF;			
	background: #FFFFFF;
	padding: 8px;	
	font-size:18px;
	color:#333333;
	margin-top:20px;
	margin-bottom:5px;
	font-family: "Lucida Grande","Lucida Sans Unicode",Helvetica,Arial,Verdana,sans-serif;
}
#panel-heading-part{
	background-color: #FFFFFF;
	border-bottom: 0px solid #DDD;
}

.no-border-bottom{
	border-bottom: 0px solid #DDD;
}
.tab-center{
	margin: 0 auto;
	background-color: #FFFFFF;
}
#panel-body-part{
	border-top:2px solid #DDD; 
	width:100%;
}
.innertabs{
	width:100%;
	float: center;				
	padding:20px;
}
.with-nav-tabs.panel-default .nav-tabs > li > a,
.with-nav-tabs.panel-default .nav-tabs > li > a:hover,
.with-nav-tabs.panel-default .nav-tabs > li > a:focus {
color: #777;
}
.with-nav-tabs.panel-default .nav-tabs > .open > a,
.with-nav-tabs.panel-default .nav-tabs > .open > a:hover,
.with-nav-tabs.panel-default .nav-tabs > .open > a:focus,
.with-nav-tabs.panel-default .nav-tabs > li > a:hover,
.with-nav-tabs.panel-default .nav-tabs > li > a:focus {
color: #777;
background-color: #DDD;
border-color: transparent;
}
.with-nav-tabs.panel-default .nav-tabs > li.active > a,
.with-nav-tabs.panel-default .nav-tabs > li.active > a:hover,
.with-nav-tabs.panel-default .nav-tabs > li.active > a:focus {
color: #555;
background-color: #DDD;
border-color: #ccc;
}
.fa-rotate-360{
-webkit-transform: rotate(360deg);
-moz-transform: rotate(360deg);
-ms-transform: rotate(360deg);
-o-transform: rotate(360deg);
transform: rotate(360deg);
transition: .8s;
}
.options{
	background-color: #DDD;
}
@media screen and (min-width:320px) and (max-width: 560px) {
	.sai_popup{
		top: 50%;
		left: 50%;
		width:80%;
	}
	ul li {
		width:100%;
	}
	#panel-body-part{
		margin-top:250px;
	}
}
@media screen and (min-width:560px) and (max-width: 1030px) {
	ul li {
		width:50%;
	}
	#panel-body-part{
		margin-top:100px;
	}		
}
.options{
	border-color: #000;
	border-bottom: 5px solid #DDD;
	background-color: #DDD;
}
</style>

<script language="javascript" type="text/javascript">
var refreshInterval;

$(document).ready(function(){	
	// Refresh the logs
	refreshInterval = setInterval(refresh_logs, 3000);
	
	$("#server_ip").val("");
	$("#remote_pass").val("");
	$("#local_pass").val("");
	
	// Submit form on enter key
	$("#server_ip,#remote_pass,#local_pass,#server_port").keyup(function(event) {
		if (event.keyCode === 13) {
			$("#remote_scan").click();
		}
	});
	
	$("#remote_scan").click(function(){
	
		var server_ip = $("#server_ip").val();
		var remote_pass = $("#remote_pass").val();
		var local_pass = $("#local_pass").val();
		var server_port = $("#server_port").val();
		
		// Show 50 records by default
		$("#page_size").val(50);
	
		var d = "server_ip="+server_ip+"&server_port="+server_port+"&remote_pass="+encodeURIComponent(remote_pass)+"&local_pass="+encodeURIComponent(local_pass)+"&action=users"
		submitit(d, {
			handle:function(data, p){
				//alert(data);
				
				var response = data;
				var trHTML = "";
				var page_size = $("#page_size option:selected").val();
				var page_count = 1;
				
				if(response.error){
					var err = Object.values(response.error);
					var a = show_message_r("'.__js('Error').'", err);
					a.alert = "alert-danger"
					show_message(a);
					return false;
				}
				
				localStorage.setItem("allusers", JSON.stringify(response.remote_resp.users));
				
				$.each(response.remote_resp.users, function (i, item){
					
					if(page_size < page_count){
						return true;
					}
					
					trHTML += "<tr id="+ i +"_tr><td><input type=checkbox name=migrate_users[] value="+ i +" id="+ i +"></td><td>" + item.domain + "</td><td>" + i + "</td><td><select name=overwrite_option class=\'form-select form-select-sm\' id="+ i +"_overwrite_option><option value=\"overwrite_only\">'.__js('Overwrite').'</option><option value=\"no_overwrite\">'.__js('Do Not Overwrite').'</option><option value=\"delete_overwrite\">'.__js('Delete and Overwrite').'</option></select></td></tr><tr id="+i+"_error style=display:none;><td colspan=5><br /><div id=error_box class=row><div class=\'col-sm-12\'><div class=\'alert alert-danger\'><center><span style=\'font-size:14px; text-decoration:none;\'><a href=#close class=close data-dismiss=alert>&times;</a>'.__js('The selected account can not be transferred because the selected remote account exists on the local/destination server, if you want to transfer then please select Overwrite option.').'</span></center></div></div></div></td></tr>";
					
					page_count++;
				});
				
				if(trHTML){
					$("#show_accounts tr").remove();
				}
								
				$("#show_accounts").append(trHTML);
				
				localStorage.setItem("allusershtml", JSON.stringify(trHTML));
				
				$("#scan").css("display", "none");
				$("#panel-background").hide();
				$("#panel-body-part").hide();
				$("#panel-background-users").show();
				$("#panel-body-part-users").show();
				
				var local_users = ["'.implode('", "', $local_users).'"];
				
				// Check if user exist on local server
				$.each(response.remote_resp.users, function (i, item){
					$("#"+i+",#"+i+"_overwrite_option,#select_all_users").change(function(){
							
						var user_checked = $("#"+i).is(":checked");
						if(user_checked){
							var selected_overwrite = $("#"+i+"_overwrite_option option:selected").text();
							if(selected_overwrite == "Do Not Overwrite" && in_arrays(i, local_users)){
								$("#"+i+"_error").css("display", "");
								$("#"+i+"_tr").css("background-color", "#f2dede");
							}else{
								$("#"+i+"_error").css("display", "none");
								$("#"+i+"_tr").css("background-color", "");
							}
						}else{
							$("#"+i+"_error").css("display", "none");
							$("#"+i+"_tr").css("background-color", "");
						}
					});
				});
			}
		});
	});
	
	
	function handle_users_table(){
		var allusers = JSON.parse(localStorage.getItem("allusers"));
		var val = $("#search_user_dom").val();
		var search_str = val.toLowerCase(); 
		$("#show_accounts tbody tr").each(function(key, val){
			var row_id = $(val).attr("id");
			if(row_id.includes("_tr")){
				var username = row_id.replace("_tr", "");
				if(search_str.length > 0){
					if((!username.match(search_str, "gi")) && (!allusers[username].domain.match(search_str, "gi"))){
						$(val).hide()
					}else{
						$(val).show()
					}
				}else{
					$(val).show()
				} 
			}
			
		});
	}
	
	// Show result as per page size
	$("#page_size").change(function(){
		var allusers = JSON.parse(localStorage.getItem("allusers"));
		var page_size = $("#page_size option:selected").val();
		var trHTML = "";
		var page_count = 1;
		
		$("#show_accounts tbody tr").remove();
		$.each(allusers, function (i, item){
			
			if(page_size < page_count){
				return true;
			}
			
			trHTML += "<tr id="+ i +"_tr><td><input type=checkbox name=migrate_users[] value="+ i +" id="+ i +"></td><td>" + item.domain + "</td><td>" + i + "</td><td><select name=overwrite_option class=\'form-select form-select-sm\' id="+ i +"_overwrite_option><option value=\"overwrite_only\">'.__js('Overwrite').'</option><option value=\"no_overwrite\">'.__js('Do Not Overwrite').'</option><option value=\"delete_overwrite\">'.__js('Delete and Overwrite').'</option></select></td></tr><tr id="+i+"_error style=display:none;><td colspan=5><br /><div id=error_box class=row><div class=\'col-sm-12\'><div class=\'alert alert-danger\'><center><span style=\'font-size:14px; text-decoration:none;\'><a href=#close class=close data-dismiss=alert>&times;</a>'.__js('The selected account can not be transferred because the selected remote account exists on the local/destination server, if you want to transfer then please select Overwrite option.').'</span></center></div></div></div></td></tr>";
			
			page_count++;
		});
		$("#show_accounts").append(trHTML);
	});
	
	// Select all users and set overwrite option
	$("#select_all_users, #all_overwrite_option").change(function(){
		var allusers = JSON.parse(localStorage.getItem("allusers"));
		var select_all = $("#select_all_users").is(":checked");
		var selected_overwrite = $("#all_overwrite_option option:selected").val();
		$.each(allusers, function (i, item){
			if(select_all){
				$("#"+i).prop("checked", true);
				$("#"+i+"_overwrite_option").val(selected_overwrite);
			}else{
				$("#"+i).prop("checked", false);
			}
		});
	});

	// Search by domain/user
	$("#search_user_dom").keyup(function(){
		handle_users_table();
	}); 


	
	// Start transfer
	$("#remote_copy").click(function(){
		var server_ip = $("#server_ip").val();
		var remote_pass = $("#remote_pass").val();
		var local_pass = $("#local_pass").val();
		var server_port = $("#server_port").val();
		
		var select_all_users = $("#select_all_users").is(":checked");
		var all_selected_overwrite = $("#all_overwrite_option option:selected").val();
		
		var allusers = JSON.parse(localStorage.getItem("allusers"));
		var alldata = {};
		var users = {};
		var user_overwrite = {};
		
		// Get the selected users and their selected backup options
		$.each(allusers, function (i, item){
			var backup_option = {};
			
			//If user already exist and overwrite is not selected then skip that user to import
			var overwrite_val = $("#"+i+"_overwrite_option option:selected").val();
			if(overwrite_val == "no_overwrite"){
				return;
			}
			
			if(select_all_users){
				// console.log(i);
				backup_option["user"] = i;
				backup_option["overwrite_option"] = all_selected_overwrite;
				alldata["allusers"] = 1;
				alldata["all_overwrite_option"] = all_selected_overwrite;
				users[i] = backup_option;
			}else{
				var user_selected = $("#"+i).is(":checked");
				if(user_selected){
					backup_option["user"] = i;
					backup_option["overwrite_option"] = $("#"+i+"_overwrite_option option:selected").val();
					users[i] = backup_option;
					
					user_overwrite[i] = $("#"+i+"_overwrite_option option:selected").val();
				}
			}
		});
		
		alldata["users"] = users;
		alldata["user_overwrite"] = user_overwrite;
		
		var alldata = JSON.stringify(alldata);
		var d = "server_ip="+server_ip+"&remote_pass="+encodeURIComponent(remote_pass)+"&local_pass="+encodeURIComponent(local_pass)+"&server_port="+server_port+"&all_users="+select_all_users+"&all_selected_overwrite="+all_selected_overwrite+"&alldata="+alldata+"&start_import=1";
		submitit(d, {
			done:function(data, p){
				//alert(data);
				//console.log(data, p);return false;
				var resp = data;
				
				if(resp.error){
					$("body").animate({ scrollTop: 0 }, "slow");
					var a = show_message_r("'.__js('Error').'", resp.error.values_not_passed);
					a.alert = "alert-warning"
					show_message(a);
					return false;
				}
				
				if(resp.done != "undefined"){
					$("body").animate({ scrollTop: 0 }, "slow");
					$("#panel-background").show();
					$("#panel-body-part").show();
					$("#webuzo_import").removeClass("active show");
					$("#webuzo_import_head").hide();
					$("#logs").addClass("active show");
					$("#logs_head").addClass("active");
					$("#panel-background-users").hide();
					$("#panel-body-part-users").hide();
				}
			}
		});
	});
});

function in_array(val, array){
	if (typeof array == "undefined") {
		return false;
	}
	
	for (i=0; i <= array.length; i++){
		if (array[i] == val) {
			return true;
			// {alert(i +" -- "+ids[i]+" -- "+val);return i;}
		}
	}
	return false;
}
	
// Remove hash from URL before making ajax request
function remove_hash(w_l){
	if(w_l.indexOf("#") > 0){
		var unhashed_w_l = w_l.substring(0, w_l.indexOf("#"));
		return unhashed_w_l;
	}
	return w_l;
}

//get backup logs (and clear them if needed)
function get_logs(id){
	if(id == "clear_log"){
		dataval = "clearlog=1";
	}else{
		dataval = "";
	}
	
	var d = dataval;
	submitit(d, {
		handle: function(data, p){
			//console.log(data, p);return false;
			if("done" in data){
				if(id == "clear_log"){
					var a = show_message_r("'.__js('Done').'", "'.__('Logs Cleared').'");
					a.alert = "alert-success"
					show_message(a);
				}
				$("#showlog").text(data["migrate_log"]);
			}else{
				var a = show_message_r("'.__js('Error').'", data["error"]);
				a.alert = "alert-danger"
				show_message(a);
			}
		}
	});
}

// Refresh the logs automatically
function refresh_logs(){	
	if($("#logs").is(":visible")){
		$("#get_log").click();
	}	
};
</script>';
	
	echo '
<div class="modal fade" id="cpanel_import_conf" tabindex="-1" aria-labelledby="atop_confLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="atop_conf_label">'.__('Import Settings').'</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
			<form action="" method="POST" name="cpanel_import_config" id="cpanel_import_config" class="form-horizontal" onsubmit="return submitit(this)">

				<div class="row mb-3">
					<div class="col-12 col-md-3 col-lg-3">
						<label class="sai_head" for="max_bg_import_process">'.__('Max background processes').'
							<span class="sai_exp">'.__('Set the maximum number of users to be imported simultaneously in background. Default value : 5').'</span>
						</label>
					</div>
					<div class="col-12 col-md-7 mb-1">
						<input type="number" name="max_bg_process_import" class="form-control" value="'.aPOSTval('max_bg_process_import', $globals['max_bg_process_import']).'" />
					</div>
				</div>
				
				<div class="row mb-2 text-center">
					<div class="col-12">
						<input class="btn btn-primary" type="submit" name="cpanel_import_conf" id="cpanel_import_conf" value="'.__('Save').'">
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>

<div class="soft-smbox p-3 col-12 col-md-10 col-lg-11 mx-auto">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'import_cpanel.png" class="me-1"/>'.__('Import From Webuzo').'

		<span class="search_btn float-end">
			<a type="button" class="float-end" data-bs-toggle="modal" data-bs-target="#cpanel_import_conf"><i class="fas fa-cogs"></i></a>
		</span>
	</div>
</div>
<div class="soft-smbox p-4 col-12 col-md-10 col-lg-11 mx-auto mt-4">
	<div id="panel-background">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="webuzo_import_head" data-bs-toggle="tab" data-bs-target="#webuzo_import" type="button" role="tab" aria-controls="webuzo_import" aria-selected="true">'.__('Import Webuzo').'</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="logs_head" data-bs-toggle="tab" data-bs-target="#logs" type="button" role="tab" aria-controls="logs" aria-selected="false">'.__('Logs').'</button>
			</li>
		</ul>
	</div>
	<div id="success_box" class="row" style="display:none;">
		<div class="col-sm-offset-1 col-sm-10">
			<div class="alert alert-success">
				<center><span style="font-size:14px; text-decoration:none;"><a href="#close" class="close" data-dismiss="alert">&times;</a>'.__('Migration process has been started in the background. Check the logs for more info').'</span></center>
			</div>
		</div>
	</div>
	
	<div class="tab-content" id="panel-body-part" style="border-top:0px;">
		<div class="tab-pane fade p-3 active show" id="webuzo_import" role="tabpanel" aria-labelledby="webuzo_import_head">';
			if(!empty($error)){
				echo '
			<div id="error_box" class="row">
				<div class="col-sm-offset-1 col-sm-10">
					<div class="alert alert-danger">
						<center><span style="font-size:14px; text-decoration:none;"><a href="#close" class="close" data-dismiss="alert">&times;</a>'.$error.'</span></center>
					</div>
				</div>
			</div>';
			}
			echo '
			<div class="row">
				<div class="col-12 col-md-6 mb-3">
					<label class="sai_head">
						'.__('Server IP').'
						<span class="sai_exp">'.__('Server IP of your Webuzo server from where you want to import').'</span>
					</label>
					<input type="text" name="server_ip" id="server_ip" class="form-control" value="" required />
				</div>	
				<div class="col-12 col-md-6 mb-3">
					<label class="sai_head">
						'.__('Server Root Password(Remote)').'
						<span class="sai_exp">'.__('Root Password of your Webuzo server from where you want to import').'</span>
					</label>
					<input type="password" name="remote_pass" id="remote_pass" class="form-control" value="" style="height:35px;" required />
				</div>
			</div>
					
			<div class="text-center my-3">
				<input type="submit" id="remote_scan" name="submit" class="ms-1 btn btn-primary" value="'.__('Scan Remote Server').'" />
				<img class="scan" id="scan" src="'.$theme['images'].'progress.gif'.'" style="display:none;" />
			</div>	
			<div class="text-center">
				'.__('$0 Note: $1 This utility will import your data from another Webuzo instance. $2 Here $3 is the guide for the same.', ['<b>', '</b>', '<a href="https://www.webuzo.com/docs/admin/import-from-webuzo/" target="_blank" >', '</a>']).'
			</div>
		</div>
		
		<div class="tab-pane fade p-3" id="logs" role="tabpanel" aria-labelledby="logs_head">
			<div class="sai_main_head text-center">
				<img src="'.$theme['images'].'login_logs.png" class="me-1" />'.__('Logs').'
			</div>
			<div class="innertabs" nowrap="nowrap" >
				<textarea class="form-control log" id="showlog" readonly="readonly"; style="height:400px; overflow:auto; resize: none;" wrap="off"; >'.$migrate_log.'</textarea>
			</div>
			<div class="text-center">
				<input type="button" class="btn btn-primary" id="get_log" value="'.__('Refresh Logs').'" onclick="get_logs();" />
				<input type="button" class="btn btn-primary ms-1" id="clear_log" value="'.__('Clear Logs').'" onclick="get_logs(this.id);" />
				<img id="trace_prog" src="'.$theme['images'].'progress.gif" style="display:none">
			</div>
		</div>
	</div>
	
	<div class="panel with-nav-tabs panel-default" id="panel-background-users" style="display:none;"> 
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="accounts_head" data-bs-toggle="tab" data-bs-target="#accounts" type="button" role="tab" aria-controls="accounts" aria-selected="true">'.__('Accounts').'</button>
			</li>
		</ul>
	</div>
	
	<div class="panel-body" id="panel-body-part-users" style="display:none;">
		<div class="tab-content p-3" style="height: auto;">
			<div class="tab-pane fade show active" id="accounts">
				
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-6 col-xs-6">
						<input type="text" class="form-control" name="search_user_dom" id="search_user_dom" style="width: 50%" placeholder='.__('Search').'>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
						<div class="row">
							<div class="col-lg-4">
								<span class="pull-right"><b>'.__('Page Size').' :</b></span>
							</div>
							<div class="col-lg-8">
								<select name="page_size" class="form-select form-select-sm pull-right form-select" id="page_size" style="width: 100%">
									<option value=10>10</option>
									<option value=20>20</option>
									<option value=50>50</option>
									<option value=100>100</option>
									<option value="All">'.__('All').'</option>
								</select>
							</div>
						</div>
					</div>
				</div><br />
				
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
					<div class="input-group-text" style="padding:10px;">
					  <input type="checkbox" bool-to-int="" id="select_all_users" class="ng-valid ng-dirty ng-valid-parse ng-touched">
					</div>
				  </div>
					<select name="all_overwrite_option" class="form-select-sm" id="all_overwrite_option">
						<option value="overwrite_only">'.__('Overwrite').'</option>
						<option value="no_overwrite">'.__('Do Not Overwrite').'</option>
						<option value="delete_overwrite">'.__('Delete and Overwrite').'</option>
					</select>
				</div><br />
		
				<table border="0" cellpadding="5" cellspacing="1" width="95%" class="table table-hover" id="show_accounts">			
					<tr>
						<thead class="sai_head2" style="background:#efefef;">
							<th></th>
							<th>'.__('Domains').'</th>
							<th>'.__('Users').'</th>
							<th>'.__('Transfer Options').'</th>
							<th>'.__('If user exists ?').'</th>
						</thead>
					</tr>
				</table>
			</div>
		</div><br/>
		<div class="row">
			<center>
				<input type="submit" id="remote_copy" name="remote_copy" class="ms-1 btn btn-primary" value="'.__('Submit').'" />
				<img class="transfer_scan" id="transfer_scan" src="'.$theme['images'].'progress.gif'.'" style="display:none;margin-left:30px;" />
			</center>
		</div>
	</div>
	
	
</div>';
		
		softfooter();
	
}

