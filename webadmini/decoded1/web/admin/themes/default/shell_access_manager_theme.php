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

function shell_access_manager_theme(){

global $user, $globals, $theme, $error, $done, $users_list;

	softheader(__('Manage Shell Access'));
	
	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'ssh_login.png" width="40" height="40"/>   '.__('Manage Shell Access').'		
	</div>
</div>
<div class="soft-smbox p-4 mt-4">
	<div class="sai_main_head">
		<label class="form-label">'.__('Normal Shell :').'</label>
		<button type="button" class="btn btn-primary  mx-3" data-do="2" data-shell_access=1 data-donereload=1 onclick="return shell_access_toggle(this)" title = "Shell access">'.__('Allow all').'</button>
		<button type="button" class="btn btn-primary me-5" data-do="3" data-shell_access=1 data-donereload=1 onclick="return shell_access_toggle(this)" title = "Shell access">'.__('Disallow all').'</button>
		<label class="form-label">'.__('Jailed Shell :').'</label>';
		
		if(is_app_installed('jailshell')){
			echo '
		<button type="button" class="btn btn-primary  mx-3" data-do="6" data-shell_access=1 data-donereload=1 onclick="return shell_access_toggle(this)">'.__('Allow all').'</button>
		<button type="button" class="btn btn-primary" data-do="7" data-shell_access=1 data-donereload=1 onclick="return shell_access_toggle(this)">'.__('Disallow all').'</button>';
		
		}else{
			echo '
			<a href="'.$globals[ind].'act=apps&app=172"><button class="btn btn-primary mx-3">'.__('Install Jailshell').'</button></a>';
		}
		
		echo '
		<span class="search_btn float-end">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<hr>';
	
	error_handle($error, "100%");
	
	echo '
	<div style="background-color:#e9ecef;">
		<div class="collapse '.(!empty(optREQ('user_search')) || !empty(optREQ('domain')) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
			<div class="row p-3 col-md-12 d-flex">
				<div class="col-12 col-md-6">
					<label class="sai_head">'.__('Search By User Name').'</label><br/>
					<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" style="width: 100%" id="user_search" name="user_search">
						<option value="'.optREQ('user_search').'" selected="selected">'.optREQ('user_search').'</option>
					</select>
				</div>
				<div class="col-12 col-md-6">
					<label class="sai_head">'.__('Search By Domain Name').'</label>
					<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act=domains&api=json" s2-query="dom_search" s2-data-key="domains" s2-data-subkey="domain" style="width: 100%" name="dom_search" id="dom_search">
						<option value="'.optREQ('domain').'" selected="selected">'.optREQ('domain').'</option>
					</select>
				</div>	
			</div>
			</form>
		</div>
	</div>';
	
	page_links();
	
	echo '
	<div class="table-responsive">
		<table border="0" cellpadding="8" cellspacing="1" class="table table-hover-moz webuzo-table td_font">
			<thead class="sai_head2">
				<tr>
					<th width="15%">'.__('Users').'</th>
					<th width="15%">'.__('Owner').'</th>
					<th width="25%">'.__('Domain').'</th>
					<th colspan="" width="15%">'.__('Normal Shell').'</th>';
					
					if(is_app_installed('jailshell')){
						echo '
					<th width="15%">'.__('Jailed Shell').'</th>';
					}
					
					echo '
					<th colspan="" width="15%">'.__('Enable Cron').'</th>
				</tr>
			</thead>
			<tbody id="userlist">';
			
			if(empty($users_list)){
				echo '
					<tr>
						<td colspan="100" class="text-center">
							<span>'.__('No Users Exist').'</span>
						</td>
					</<tr>';						
			}else{
				foreach($users_list as $key => $value){
					echo '
					<tr '.($value['status'] == 'suspended' ? 'style="background-color: #ffdbdb;"' : '').'>
						<td id="user_name">
							<span>'.$value['user'].'</span>
						</td>
						<td id="owner">
							<span>'.$value['owner'].'</span>
						</td>
						<td>
							<span>'.$value['domain'].'</span>
						</td>
						<td>
							<label class="switch" style="margin-left:20px;">
								<input type="checkbox" class="checkbox" data-shell_access="1" data-donereload="1" data-do="'.(!empty($value['shell']) ? 0 : 1).'" '.(!empty($value['shell']) ? 'checked' : '').' data-user='.$value['user'].' onclick="return shell_access_toggle(this)">
								<span class="slider" '.($value['status'] == 'suspended' ? 'title="User Suspended" disabled' : empty($value['shell']) ? 'title="Enable Shell"' : 'title="Disable Shell"').'></span>
							</label>
						</td>';
						
						if(is_app_installed('jailshell')){
							echo '
						<td>
							<label class="switch" style="margin-left:20px;">
								<input type="checkbox" class="checkbox" data-shell_access="1" data-donereload="1" data-do="'.(!empty($value['jail_shell']) ? 4 : 5).'" '.(!empty($value['jail_shell']) ? 'checked' : '').' data-user='.$value['user'].' onclick="return shell_access_toggle(this)">
								<span class="slider" '.($value['status'] == 'suspended' ? 'title="User Suspended" disabled' : empty($value['jail_shell']) ? 'title="Enable Jailed Shell"' : 'title="Disable Jailed Shell"').'></span>
							</label>
						</td>';
						}
						
						echo '
						<td>
							<label class="switch" style="margin-left:20px;">
								<input type="checkbox" class="checkbox" data-deny_cron="1" data-donereload="1" data-do="'.(!empty($value['deny_cron']) ? 0 : 1).'" '.(!empty($value['deny_cron']) ? '' : 'checked').' data-user='.$value['user'].' onclick="return cron_access_toggle(this)">
								<span class="slider" '.($value['status'] == 'suspended' ? 'title="User Suspended" disabled' : !empty($value['deny_cron']) ? 'title="Enable Cron"' : 'title="Disable Cron"').'></span>
							</label>
						</td>';
					echo '
					</tr>';
				}
			}
			echo '
			</tbody>
		</table>
	</div>';
	
	page_links();
	
	echo '
	<nav aria-label="Page navigation">
		<ul class="pagination pager myPager justify-content-end">
		</ul>
	</nav>
</div>
<script>
	function shell_access_toggle(ele){
		var jEle = $(ele);
		var d = jEle.data();
		var a, lan;
		if(d.do == "0" || d.do == "3"){
			if("user" in d){
				lan = "'.__js('Do you want to $0 Disallow Shell $1 access to ', ['<b>', '</b>']).'<b>"+d.user+"</b>?";
			}else{
				lan = "'.__js('Do you want to $0 Disallow Shell $1 access to ', ['<b>', '</b>']).'<b>'.__js('All Users').'</b>?";
			}
		}else if(d.do == "1" || d.do == "2"){
			lan = "'.__('Note: Enabling normal shell disables jail shell of the user.<br>').'";
			
			if("user" in d){
				lan += "'.__js('Do you want to $0 Allow Shell $1 access to ', ['<b>', '</b>']).'<b>"+d.user+"</b>?";
			}else{
				lan += "'.__js('Do you want to $0 Allow Shell $1 access to ', ['<b>', '</b>']).'<b>'.__js('All Users').'</b>?";
			}
		}else if(d.do == "4" || d.do == "7"){
			if("user" in d){
				lan = "'.__js('Do you want to disable jail shell for ').'<b>"+d.user+"</b>?";
			}else{
				lan = "'.__js('Do you want to disable jail shell for ').'<b>'.__js('All Users').'</b>?";
			}
		}else{
			lan = "'.__js('Note: Enabling jail shell disables normal shell of the user.<br>').'";
			
			if("user" in d){
				lan += "'.__js('Do you want to enable jail shell for ').'<b>"+d.user+"</b>?";
			}else{
				lan += "'.__js('Do you want to enable jail shell for ').'<b>'.__js('All Users').'</b>?";
			}
		}
		
		a = show_message_r("'.__js('Warning').'", lan);
		a.alert = "alert-warning";
		
		var no = function(){
			var status = d.do ? false : true;
			jEle.prop("checked", status);
		}
		
		// Submit the data
		a.confirm.push(function(){
			submitit(d, {done_reload : window.location.href, error: no});
		});
		
		// If user closes or chooses no
		a.no.push(no);
		a.onclose.push(no);
		
		//console.log(a);//return;
		show_message(a);
	}
	
	function cron_access_toggle(ele){
		var jEle = $(ele);
		var d = jEle.data();
		var a, lan;
		if(d.do == "0"){
			lan = "'.__js('Do you want to $0 Allow Cron $1 access to ', ['<b>', '</b>']).'<b>"+d.user+"</b>";
		}else{
			lan = "'.__js('Do you want to $0 Disallow Cron $1 access to ', ['<b>', '</b>']).'<b>"+d.user+"</b>";
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
		a.onclose.push(no);
		
		//console.log(a);//return;
		show_message(a);
	}
	
	$("#user_search").on("select2:select", function(e, u = {}){		
		user = $("#user_search option:selected").val();		
		window.location = "'.$globals['index'].'act=shell_access_manager&user_search="+user;
	});
		
	$("#dom_search").on("select2:select", function(){
		var domain_selected = $("#dom_search option:selected").val();
		window.location = "'.$globals['index'].'act=shell_access_manager&domain="+domain_selected;			
	});
</script>';

	softfooter();
}