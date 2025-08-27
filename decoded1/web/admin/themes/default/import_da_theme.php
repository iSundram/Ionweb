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

function import_da_theme(){

global $user, $globals, $theme, $softpanel, $error, $done, $import_log;

	softheader(__('Import From DirectAdmin'));
	
	echo '		
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[

var refreshInterval;
var local_users = ["'.implode('", "', array_keys(get_server_users(0))).'"];

$(document).ready(function(){
	$("#scan_backups").prop("checked", false);

	e_scan_backups();
	
	// Refresh the logs
	refreshInterval = setInterval(refresh_logs, 3000);
		
	// Show All records by default
	$("#page_size").val(0);
	
	// Submit form on enter key
	$("#host,#da_user,#da_pass,#da_port").keyup(function(event) {
		if (event.keyCode === 13) {
			$("#da_scan").click();
		}
	});
	
	function show_users_table(){
		
		var allusers = JSON.parse(localStorage.getItem("allusers"));
		// Is there any search term ?
		var val = $("#search_user_dom").val();
		var search_str = val.toLowerCase();
		var page_size = $("#page_size option:selected").val();
		var row_count = 0;
		var newHTML = "";
		
		// Empty the table
		$("#show_accounts tbody tr").remove();
		
		$.each(allusers, function (i, item){
			
			if(search_str.length > 0){
				if((!i.match(search_str, "gi")) && (!item.domain.match(search_str, "gi"))){
					return true;
				}
			}
			
			if(page_size > 0 && page_size < row_count){
				return true;
			}
			
			newHTML += "<tr id="+ i +"_tr>"+
				"<td>"+
					"<input type=checkbox name=migrate_users[] value="+ i +" id="+i+"_input onchange=check_user_errors(\'"+i+"\')>"+
					//"<button type=button class=\'btn btn-link\' id="+ i +"_options onclick=show_migrate_options(this.id);><i class=\'fas fa-chevron-right\' aria-hidden=true id=fa-chevron-right></i><i class=\'fas fa-chevron-down\' aria-hidden=true style=display:none; id=fa-chevron-down></i></button>"+
				"</td>"+
				"<td>" + i + "<div id="+i+"_error class=\'alert alert-danger p-1 m-0\' style=\'display:none;\'></div></td>"+
				"<td>" + item.owner + "</td>"+
				"<td>" + item.domain + "</td>"+
				//"<td><button type=button class=\'btn btn-link\' id="+ i +"_options onclick=show_migrate_options(this.id);><i class=\'fas fa-tools fa-1x\' style=cursor:pointer;></i></button></td>"+
				"<td><select name=overwrite_option class=\'form-select form-select-sm\' id="+ i +"_overwrite_option><option value=\"overwrite\">'.__js('Overwrite').'</option><option value=\"no_overwrite\">'.__js('Do Not Overwrite').'</option><option value=\"delete_overwrite\">'.__js('Delete and Overwrite').'</option></select></td>"+
			"</tr>";
			/*"<tr style=\'display:none;\' id="+ i +"_options_show>"+
				"<td colspan=5>"+
					"<div class=\'row sai_head\'>"+
						"<div class=col-sm-6>"+
							"<h4>'.__js('What to Migrate ?').'</h4>"+
							"<input type=checkbox id="+ i +"_home value="+ i +"_home checked>&nbsp&nbsp'.__('Home').'<br />"+
							"<input type=checkbox id="+ i +"_database value="+ i +"_database checked>&nbsp&nbsp'.__('Databases').'<br />"+
							"<input type=checkbox id="+ i +"_email value="+ i +"_email checked>&nbsp&nbsp'.__('Emails').'<br/>"+
							"<button type=button class=\'btn btn-link\' id="+ i +" style=cursor:pointer;text-decoration:none onclick=\'apply_to_others(this.id);\'><i class=\'fas fa-save fa-1x\'></i>&nbsp;'.__('Apply to other selected users').'</button>"+							
						"</div>"+
					"</div>"+
				"</td>"+
			"</tr>"+
			"<tr id="+i+"_error style=display:none;>"+
				"<td colspan=5><br /><div id=error_box class=row><div class=\'col-sm-12\'><div class=\'alert alert-danger\'><center><span style=\'font-size:14px; text-decoration:none;\'><a href=#close class=close data-dismiss=alert>&times;</a>'.__js('The selected account can not be transferred because the selected remote account exists on the local/destination server, if you want to transfer then please select Overwrite option.').'</span></center></div></div></div></td>"+
			"</tr>";*/
			
			row_count++;
		});
		
		// Show the table
		$("#show_accounts tbody").append(newHTML);
	};
	
	function handle_users_table(){
		
		var allusers = JSON.parse(localStorage.getItem("allusers"));
		
		var val = $("#search_user_dom").val();
		var search_str = val.toLowerCase();
		
		$("#show_accounts tbody tr").each(function(key, val){
			// console.log(val)
			var usr = $(val).attr("id").replace("_tr", "");
			// console.log(allusers[usr].domain)
			if(search_str.length > 0){
				if((!usr.match(search_str, "gi")) && (!allusers[usr].domain.match(search_str, "gi"))){
					$(val).hide()
				}else{
					$(val).show()
				}
			}else{
				$(val).show()
			}
			
		});
	}
	
	$("#da_scan").click(function(){
	
		var host = $("#host").val();
		var da_user = $("#da_user").val();
		var da_pass = $("#da_pass").val();
		
		localStorage.removeItem("allusers");
		
		var d = "host="+host+"&da_pass="+encodeURIComponent(da_pass)+"&da_user="+da_user+"&action=listaccts&scan_acc=1";
		
		submitit(d, {
			handle:function(data, p){
				// alert(data);
				var response = data;
				
				if(response.error){
					var err = Object.values(response.error);
					var a = show_message_r("'.__js('Error').'", err);
					a.alert = "alert-danger"
					show_message(a);
					return false;
				}
				
				localStorage.setItem("allusers", JSON.stringify(response.da_users));
				
				show_users_table();
				
				$("#import_users_tab").show();
				$("#import_users_a").click();
			}
		});
	});
	
	// Show result as per page size
	$("#page_size").change(function(){
		show_users_table();
	});

	// Search by domain/user
	$("#search_user_dom").keyup(function(){
		handle_users_table();
	});
	
	// Start import
	$(sub_cp_users).click(function(){
		
		var host = $("#host").val();
		var whm_user = $("#da_user").val();
		var da_pass = $("#da_pass").val();
		
		var select_all_users = $("#select_all_users").is(":checked");
		var all_selected_overwrite = $("#all_overwrite_option option:selected").val();
		var allusers = JSON.parse(localStorage.getItem("allusers"));
		
		var import_data = {};
		var users = {};
		
		// Get the selected users and their selected import options
		$.each(allusers, function (i, item){
			var import_option = {};
			
			var user_selected = $("#"+i+"_input").is(":checked");				
			if(!user_selected){
				return;
			}
			
			import_option["user"] = i;
			//import_option["home"] = $("#"+i+"_home").is(":checked");
			//import_option["email"] = $("#"+i+"_email").is(":checked");
			//import_option["database"] = $("#"+i+"_database").is(":checked");
			import_option["overwrite"] = $("#"+i+"_overwrite_option option:selected").val();
			
			users[i] = import_option;
		});
		
		import_data["users"] = users;		
		var import_data = JSON.stringify(import_data);
		
		var d = "host="+host+"&da_pass="+encodeURIComponent(da_pass)+"&whm_user="+whm_user+"&import_data="+import_data+"&start_import=1";
		submitit(d, {
			handle:function(data, p){
				//console.log(data, p);return false;
				var resp = data;
				
				if(resp.error){
					var err = Object.values(resp.error);
					var a = show_message_r("'.__js('Error').'", err);
					a.alert = "alert-danger"
					show_message(a);
					return false;
				}
				
				var a = show_message_r("'.__js('Done').'", resp.done.msg);
				
				$("#import_log_a").click();
				$("#import_users_a").hide();
				
				a.alert = "alert-success"
				show_message(a);
			}
		});
	});

	// Select all users and set overwrite option
	$("#select_all_users,#all_overwrite_option").change(function(){
		
		var allusers = JSON.parse(localStorage.getItem("allusers"));
		var select_all = $("#select_all_users").is(":checked");
		var selected_overwrite = $("#all_overwrite_option option:selected").val();
		$.each(allusers, function (i, item){
			
			if(select_all && $("#"+i+"_input").is(":visible")){
				$("#"+i+"_input").prop("checked", true);
				$("#"+i+"_overwrite_option").val(selected_overwrite);
			}else{
				$("#"+i+"_input").prop("checked", false);
			}
			
			$("#"+i+"_input").trigger("change");
			
		});
	});
	
	$("#enduser_import").click(function(){
		var host = $("#import_hostname").val();
		var username = $("#import_username").val();
		var pass = $("#import_password").val();		
		
		var plan = $(\'input[name="plan"]:checked\').val();
		if(plan == 2){
			plan = $("#plan_name").val();
		}
		var overwrite = $("input:checkbox[name=overwrite_user]:checked").val();
		
		if((empty(plan) && empty(overwrite)) || empty(host) || empty(username) || empty(pass)){
			var a = show_message_r("'.__js('Error').'",  "'.__js('Please fill the required fields').'");
			a.alert = "alert-danger"
			show_message(a);
			return false;				
		}
		var d = {"enduser_import": 1, "hostname" : host, "username" : username, "password" : pass, "overwrite" : overwrite, "plan" : plan};
		
		if($("#backupop_div").is(":visible") && $("#scan_backups").is(":checked")){
			var dabackup = $("#backupop_name").val();
			d.existing_da_backup = 1;
			d.dabackup = dabackup;
		}
		// console.log(d); return false;
		submitit(d, {
			handle:function(data, p){
				//console.log(data, p);return false;
				var resp = data;
				
				if(resp.error){
					var err = Object.values(resp.error);
					var a = show_message_r("'.__js('Error').'", err);
					a.alert = "alert-danger"
					show_message(a);
					return false;
				}
				
				var a = show_message_r("'.__js('Done').'", resp.done.msg);
				
				$("#import_log_a").click();
				
				a.alert = "alert-success"
				show_message(a);
			}
		});
	});	
	
	$("#enduser_import_scan").click(function(){
		
		$("#backupop_div").hide();
		
		var host = $("#import_hostname").val();
		var username = $("#import_username").val();
		var pass = $("#import_password").val();		
		var backup_path = $("#scan_backups_path").val();		
		// console.log("scanning ...."); return false;
		var d = {"enduser_import": 1, "scan_backups": 1, "hostname" : host, "username" : username, "password" : pass, "scan_backups_path" : backup_path};
		submitit(d, {
			handle:function(data, p){
				//console.log(data, p);return false;
				var resp = data;
				
				if(resp.error){
					var err = Object.values(resp.error);
					var a = show_message_r("'.__js('Error').'", err);
					a.alert = "alert-danger"
					show_message(a);
					return false;
				}
				
				console.log(resp.backups_list);
				
				$("#backupop_div").show();
				if(empty(data.backups_list)){
					$("#backupop_div").html("<label class=\"sai_head\">'.__js('Backups doesn\'t exist on DirectAdmin').'</label>");
					$(".loading").hide();
					return false;
				}
				
				blist = \'<label for="backupop_name" class="sai_head">'.__js('Select Bakcup to import').'</label><br><select class="form-control" name="backupop_name" id="backupop_name" >\';
				$.each(data.backups_list, function(key,val){
					blist += \'<option value="\'+val+\'">\'+val+\'</option>\';
				})
				blist += \'</select>\';
				$("#backupop_div").show();	
				$("#backupop_div").html(blist);
				$(".loading").hide();
				
			}
		});
	});
	
	$("#backup_import").click(function(){
		var r_backup = $("#r_backup").val();
		
		var plan = $(\'input[name="plan_backup"]:checked\').val();
		if(plan == 2){
			plan = $("#plan_backup_name").val();
		}
		var d = {"backup_import": 1, "r_backup" : r_backup, "plan" : plan};
		submitit(d, {
			handle:function(data, p){
				//console.log(data, p);return false;
				var resp = data;
				
				if(resp.error){
					var err = Object.values(resp.error);
					var a = show_message_r("'.__js('Error').'", err);
					a.alert = "alert-danger"
					show_message(a);
					return false;
				}
				
				var a = show_message_r("'.__js('Done').'", resp.done.msg);
				
				$("#import_log_a").click();
				
				a.alert = "alert-success"
				show_message(a);
			}
		});
	});
			
});

function show_options(){	
	var username = $("#import_username").val();
	
	$("#extra_options").show();
	if(in_array(username.trim(), local_users)){
		$("#extra_options").html(\'<input type="checkbox" id="overwrite_user" name="overwrite_user" value="1" />&nbsp;<label for="overwrite_user" class="sai_head">'.__js('User already exist, check the box to overwrite the existing user.').'</label>\');
	}else{
		$("#extra_options").html(\'<div class="row"><div class="col-md-8"><label class="sai_head">'.__js('Create user with :-').'</label>&nbsp;<br><input type="radio" name="plan" id="da_plan" value="1" onclick="show_planlist(`plan`)"/>&nbsp;<label for="da_plan" class="sai_head">'.__js('Create DirectAdmin plan').'</label>&nbsp;&nbsp;<br><input type="radio" name="plan" id="webuzo_plan" value="2" onclick="show_planlist(`plan`)"/>&nbsp;<label for="webuzo_plan" class="sai_head">'.__js('Existing Webuzo plan').'</label>&nbsp;&nbsp;<br><input type="radio" name="plan" id="default_plan" value="3" onclick="show_planlist(`plan`)"/>&nbsp;<label for="default_plan" class="sai_head">'.__js('No plan').'</label></div><div class="col-md-4" id="plan_div" style="display:none"></div></div>\');
		
	}
}

function show_planlist(option){
	var planlist;
	
	if($(\'input[name="\'+option+\'"]:checked\').val() == 2){
		$(".loading").show();
		$.ajax({				
			type: "POST",
			dataType: "json",			
			url:"'.$globals['admin_url'].'"+"act=plans&api=json",
			success: function(data){
				$("#"+option+"_div").show();
				if(empty(data.plans)){
					$("#"+option+"_div").html("<label class=\"sai_head\">'.__js('Webuzo plan doesn\'t exist').'</label>");
					$(".loading").hide();
					return false;
				}
				planlist = \'<label for="\'+option+\'_name" class="sai_head">'.__js('Select webuzo plan').'</label><br><select class="form-select form-select-sm" name="\'+option+\'_name" id="\'+option+\'_name" >\';
				$.each(data.plans, function(key,val){
					planlist += \'<option value="\'+key+\'">\'+key+\'</option>\';
				})
				planlist += \'</select>\';
				$("#"+option+"_div").show();	
				$("#"+option+"_div").html(planlist);						
				$(".loading").hide();
			}															  
		});
	}else{
		$("#"+option+"_div").hide();
	}	
}

// Refresh the logs automatically
function refresh_logs(){
	if($("#showlog").is(":visible")){
		$("#get_log").click();
	}	
};

function apply_to_others(user){
	var allusers = JSON.parse(localStorage.getItem("allusers"));
	var home = $("#"+user+"_home").is(":checked");
	var database = $("#"+user+"_database").is(":checked");
	var email = $("#"+user+"_email").is(":checked");
	var selected_overwrite = $("#"+user+"_overwrite_option option:selected").val();

	$.each(allusers, function (i, item){
		var user_checked = $("#"+i).is(":checked");
		
		if(user_checked){
			$("#"+i+"_overwrite_option").val(selected_overwrite);
			
			if(home){
				$("#"+i+"_home").prop("checked", true);
			}else{
				$("#"+i+"_home").prop("checked", false);
			}
			
			if(database){
				$("#"+i+"_database").prop("checked", true);
			}else{
				$("#"+i+"_database").prop("checked", false);
			}
			
			if(email){
				$("#"+i+"_email").prop("checked", true);
			}else{
				$("#"+i+"_email").prop("checked", false);
			}
		}
	});
}

// Show users migrate options	
function show_migrate_options(id){
	
	var isvisible = $("#"+id+"_show").is(":visible");
	
	if(isvisible){
		$("#"+id+"_fa-chevron-right").show();
		$("#"+id+"_fa-chevron-down").css("display", "none");
	}else{
		$("#"+id+"_fa-chevron-right").hide();
		$("#"+id+"_fa-chevron-down").css("display", "");
	}
	$("#"+id+"_show").slideToggle();
}

// Get DirectAdmin import logs (and clear them if needed)
function get_logs(jEle){
	
	var m;
	var d = $(jEle).data();
	
	AJAX({
		url: window.location.toString()+"&api=json",
		data: d,
		dataType: "json"
	}, function(data){
		if("done" in data){
			
			if("clearlog" in d){
				m = show_message_r("Delete", "'.__js('Logs Cleared').'");
				m.alert = "alert-warning";
				m.ok.push(function(){
					location.reload();
				});
				show_message(m);
				$("#get_log").click();
			}
			
			$("#showlog").text(data["import_log"]);			
		}else{
			handleResponseData(data);
		}
	});
	
}

function e_scan_backups(){
	
	var is_checked = $("#scan_backups").is(":checked");
	console.log(is_checked)
	if(is_checked){
		$(".scanbackuppath").removeClass("d-none");
	}else{
		$(".scanbackuppath").addClass("d-none");
	}
	
	if($("#backupop_div").is(":visible")){
		$("#backupop_div").hide();
	}
}


// Check if user exist on local server
function check_user_errors(u){
	
	var is_checked = $("#"+u+"_input").is(":checked");
	var allusers = JSON.parse(localStorage.getItem("allusers"));
	var error = [];	
	var checked_users = {};
	
	$.each(allusers, function (i, item){
		var user_checked = $("#"+i+"_input").is(":checked");
		if(user_checked){
			checked_users[i] = item.owner;
		}
	});
	
	var owner = allusers[u].owner;
	
	// Check if the owner is being imported
	if(!empty(owner) && owner != "root" && owner != "admin" && empty(checked_users[owner]) && !in_array(owner, local_users)){
		error.push("'.__js('The Owner is missing. After import, root will own this account').'");
	}
	
	// If the user exists
	if(in_array(u, local_users)){
		error.push("'.__js('The user already exists. Please choose your overwrite preference carefully !').'");
	}
	
	if(!empty(error) && is_checked){
		$("#"+u+"_error").html("<ul class=\'m-0\'><li>"+error.join("<li>")+"</ul>");
		$("#"+u+"_error").show();
	}else{
		$("#"+u+"_error").hide();
	}
}

// ]]></script>

<div class="modal fade" id="da_import_conf" tabindex="-1" aria-labelledby="atop_confLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="atop_conf_label">'.__('Import Settings').'</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
			<form action="" method="POST" name="da_import_config" id="da_import_config" class="form-horizontal" onsubmit="return submitit(this)">

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
						<input class="btn btn-primary" type="submit" name="da_import_conf" id="da_import_conf" value="'.__('Save').'">
					</div>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>
	
<div class="soft-smbox p-3 col-12 col-md-11 mx-auto">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'import_da.png" alt="" class="webu_head_img me-2"/>
		'.__('Import From DirectAdmin').'

		<span class="search_btn float-end">
			<a type="button" class="float-end" data-bs-toggle="modal" data-bs-target="#da_import_conf"><i class="fas fa-cogs"></i></a>
		</span>
	</div>
</div>
<div class="soft-smbox p-4 col-12 col-md-11 mx-auto mt-4">
	<ul class="nav nav-tabs mb-3 webuzo-tabs" id="panel-heading-part" role="tablist">
		<li class="nav-item" role="presentation" id="import_users_tab" style="display:none">
			<button class="nav-link" id="import_users_a" data-bs-toggle="tab" data-bs-target="#import_users" type="button" role="tab" aria-controls="import_users" aria-selected="true">'.__('Users').'</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link active" id="import_a" data-bs-toggle="tab" data-bs-target="#import" type="button" role="tab" aria-controls="import" aria-selected="true">'.__('Import multiple users').'</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="import_enduser" data-bs-toggle="tab" data-bs-target="#import_enduser_tab" type="button" role="tab" aria-controls="import_enduser_tab" aria-selected="true">'.__('Import with enduser credentials').'</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="import_backup" data-bs-toggle="tab" data-bs-target="#import_backup_tab" type="button" role="tab" aria-controls="import_backup_tab" aria-selected="true">'.__('Import from backup').'</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="import_log_a" data-bs-toggle="tab" data-bs-target="#import_log" type="button" role="tab" aria-controls="import_log" aria-selected="false">'.__('Logs').'</button>
		</li>
	</ul>
	<div class="tab-content" id="panel-body-part">
		<div class="tab-pane fade show active" id="import" role="tabpanel" aria-labelledby="import_a">
			<div class="row" id="import_form">
				<div class="col-12 col-md-6 mb-3">
					<label for="host" class="sai_head">'.__('DirectAdmin Server Address (Required)').'
						<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('IP address or FQDN').'"></i>
					</label>
					<input type="text" name="host" id="host" class="form-control" value="'.POSTval('host', '').'" />
				</div>	
				<div class="col-12 col-md-6 mb-3">
					<label for="da_user" class="sai_head">'.__('User Name (Required)').'
						<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('DirectAdmin Admin Name').'"></i>
					</label>
					<input type="text" name="da_user" id="da_user" class="form-control" value="'.POSTval('da_user', '').'" />
				</div>	
				<div class="col-12 col-md-6 mb-3">
					<label for="da_pass" class="sai_head ">'.__('Password').'
						<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('DirectAdmin user password.<br/>If specified, the process will import data directly from DirectAdmin.').'"></i>
					</label>
					<div class="input-group password-field mb-3 w-100">
						<input type="password" name="da_pass" id="da_pass" class="form-control" value="'.POSTval('da_pass', '').'" />
						<span class="input-group-text" onclick="change_image(this, \'da_pass\')">
							<i class="fas fa-eye"></i>
						</span>
					</div>
				</div>
				
				<div class="text-center my-3">
					<input type="hidden" name="create_acc" value="1" /><br />
					<button id="da_scan" class="flat-butt btn btn-primary"/>'.__('Scan DirectAdmin Server').'</button>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="import_enduser_tab" role="tabpanel" aria-labelledby="import_end">
			<div class="row" id="import_form">
				<div class="col-12 col-md-6 mb-3">
					<label for="import_hostname" class="sai_head">'.__('DirectAdmin Server Address (Required)').'
						<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('IP address or FQDN').'"></i>
					</label>
					<input type="text" name="hostname" id="import_hostname" class="form-control" value="'.POSTval('hostname', '').'" />
				</div>	
				<div class="col-12 col-md-6 mb-3">
					<label for="import_username" class="sai_head">'.__('User Name (Required)').'
						<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('DirectAdmin User Name').'"></i>
					</label>
					<input type="text" name="username" onkeyup="show_options()" id="import_username" class="form-control" value="'.POSTval('username', '').'" />
				</div>	
				<div class="col-12 col-md-6 mb-3">
					<label for="import_password" class="sai_head ">'.__('Password').'
						<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('DirectAdmin user password.<br/>If specified, the process will import data directly from DirectAdmin.').'"></i>
					</label>
					<div class="input-group password-field mb-3 w-100">
						<input type="password" name="password" id="import_password" class="form-control" value="'.POSTval('password', '').'" />
						<span class="input-group-text" onclick="change_image(this, \'import_password\')">
							<i class="fas fa-eye"></i>
						</span>
					</div>
				</div>
				<div class="col-12 col-md-6 mb-3" id="extra_options" style="display:none">
				</div>
				<div class="col-12 col-md-6 mb-3">
					
					<input type="checkbox" id="scan_backups" name="scan_backups" '.POSTchecked('scan_backups', false).'" onclick="e_scan_backups()"/>	
						<label for="scan_backups" class="sai_head control-label">'.__('Scan existing backups (.tar.zst) saved in home directory at DirectAdmin').'
						</label><br />
					<br>
					<div class="scanbackuppath d-none">
						<div class="input-group scanbackuppath-field mb-3 w-100">
							<span class="input-group-text">
								'.__('Backup Directory').'
							</span>
							<input type="text" name="scan_backups_path" id="scan_backups_path" class="form-control" value="'.POSTval('scan_backups_path', 'backups').'" />
							<input type="submit" id="enduser_import_scan" name="enduser_import_scan" class="flat-butt btn btn-primary" value="'.__('Scan').'"/>
						</div>
					</div>
					<div class="col-md-6 w-100" id="backupop_div" style="display:none"></div>
				</div>
				<div class="submit_to_start text-center my-3">
					<input type="submit" id="enduser_import" name="enduser_import" class="flat-butt btn btn-primary" value="'.__('Start Import').'"/>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="import_backup_tab" role="tabpanel" aria-labelledby="import_backup">
		<div class="row">
			<div id="backup" class="col-12 col-md-6 mb-3">
				<label for="r_backup" class="sai_head ">'.__('DirectAdmin backup file').'</label><br />
				<input type="text" name="r_backup" id="r_backup" class="form-control" value="'.POSTval('r_backup', '').'" />
				<span class="sai_exp2">'.__('$0 Note: $1 The process will import data from this file and file should be stored locally on server. $2 The backup path should have executable permission for others. ', ['<b>', '</b>', '<br>']).'</span>
			</div>
			<div class="col-12 col-md-6 mb-3">
				<div class="row">
					<div class="col-md-8">
						<label class="sai_head">'.__('Create user with :-').'</label>&nbsp;<br>
						<input type="radio" name="plan_backup" id="da_plan1" value="1" onclick="show_planlist(`plan_backup`)"/>&nbsp;
						<label for="da_plan1" class="sai_head">'.__('Create DirectAdmin plan').'</label>&nbsp;<br>
						<input type="radio" name="plan_backup" id="webuzo_plan1" value="2" onclick="show_planlist(`plan_backup`)"/>&nbsp;
						<label for="webuzo_plan1" class="sai_head">'.__('Existing Webuzo plan').'</label>&nbsp;<br>
						<input type="radio" name="plan_backup" id="default_plan1" value="3" onclick="show_planlist(`plan_backup`)"/>&nbsp;
						<label for="default_plan1" class="sai_head">'.__('No plan').'</label>
					</div>
					<div class="col-md-4" id="plan_backup_div" style="display:none"></div>
				</div>
			</div>
			<div class="text-center my-3">
				<input type="submit" id="backup_import" name="backup_import" class="flat-butt btn btn-primary" value="'.__('Start Import').'"/>
			</div>
			
		</div>
		</div>
		<div class="tab-pane fade" id="import_log" role="tabpanel" aria-labelledby="import_log_a">
			<div class="innertabs mb-3" nowrap="nowrap">
				<textarea class="form-control" id="showlog" readonly="readonly" wrap="off" style="height:400px; overflow:auto; resize: none;">'.$import_log.'</textarea>
			</div>
			<div class="text-center">
				<input type="button" class="flat-butt btn btn-primary" id="get_log" value="'.__('Refresh').'" onclick="get_logs(this);" />
				<input type="button" class="flat-butt btn btn-primary" id="clear_log" value="'.__('Clear Logs').'" onclick="get_logs(this);" data-clearlog=1 />
			</div>
		</div>
		<div class="tab-pane fade" id="import_users" role="tabpanel" aria-labelledby="import_users_a"><br/>
			<div class="tab-pane fade show active" id="accounts">
				
				<div class="row">
					
					<div class="col-md-4 col-sm-6">
						'.__('Select All').' : &nbsp; <div class="input-group mb-3">
							<div class="input-group-prepend">
								<div class="input-group-text" style="padding:10px;">
									<input type="checkbox" bool-to-int="" id="select_all_users" class="ng-valid ng-dirty ng-valid-parse ng-touched">
								</div>
							</div>
							<select name="all_overwrite_option" class="form-select-sm" id="all_overwrite_option">
								<option value="overwrite">'.__('Overwrite').'</option>
								<option value="no_overwrite">'.__('Do Not Overwrite').'</option>
								<option value="delete_overwrite">'.__('Delete and Overwrite').'</option>
							</select>
						</div>
					</div>
					
					<div class="col-md-4 col-sm-6">
						'.__('Search').' : 
						<input type="text" class="form-control" name="search_user_dom" id="search_user_dom" placeholder='.__('Search').'>
					</div>
					<div class="col-md-4 col-sm-6">
						'.__('Page Size').' :
						<select name="page_size" class="form-select form-select-sm pull-right form-select" id="page_size">
							<option value=10>10</option>
							<option value=20>20</option>
							<option value=50>50</option>
							<option value=100>100</option>
							<option value="0">'.__('All').'</option>
						</select>
					</div>
				</div><br />
		
				<table border="0" cellpadding="5" cellspacing="1" width="95%" class="table table-hover" id="show_accounts">
				<thead class="sai_head2" style="background:#efefef;">
					<tr>
						<th></th>
						<th>'.__('User').'</th>
						<th>'.__('Owner').'</th>
						<th>'.__('Domain').'</th>
						<!--<th>'.__('Transfer Options').'</th>-->
						<th>'.__('If user exists ?').'</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
				</table>
			</div>
			<div class="text-center my-3">
				<button id="sub_cp_users" class="flat-butt btn btn-primary" />'.__('Start Import').'</button>
			</div>
		</div>
	</div>
</div>';

	softfooter();
	
}
