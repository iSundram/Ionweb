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

function rearrange_account_theme(){

global $theme, $globals, $user, $error, $done, $_user, $cur_storage, $storage;

	softheader(__('Rearrange an account'));
	
	error_handle($error);
		
	echo '
<div class="soft-smbox col-12 col-md-11 p-3 mx-auto">
	<div class="sai_main_head">
		<i class="fa fa-sitemap me-2"></i>'.__('Rearrange an account').'
	</div>
</div>
<div class="soft-smbox col-12 col-md-11 p-4 mx-auto mt-4">
	<div class="sai_sub_head mb-4 position-relative row">
		<div class="col-md-6">
			'.__('Select User').': &nbsp;
			<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" style="width: 100%" id="user_search" name="user_search">
				<option value="'.optGET('user', $_user).'" selected="selected">'.optGET('user', $_user).'</option>
			</select>
		</div>
		<div class="col-md-6">
			'.__('Select by Domain').': &nbsp;
			<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act=domains&api=json" s2-query="dom_search" s2-data-key="domains" s2-data-subkey="domain" style="width: 100%" name="dom_search" id="dom_search"></select>
		</div>
	</div>';
	
	if(optREQ('user')){
		if(!array_key_exists(optREQ('user'), get_users()) ){
			echo 
			'<div>
				<center><p class="alert alert-danger">'.__('Please select a valid user').'</p></center>
			</div>';
		}else{
			echo '
			<div class="sai_form" id="formcontent">
				<div class="row">
					<table class="table sai_form webuzo-table">
						<tbody>
							<tr>
								<td>
									'.__('Account User Name').'
								</td>
								<td>
									'.$_user['user'].'
								</td>
							</tr>
							<tr>
								<td>
									'.__('Primary Domain Name').'
								</td>
								<td>
									'.$_user['domain'].'
								</td>
							</tr>
							<tr>
								<td>
									'.__('Current Home Directory').' 
								</td>
								<td>
									'.$_user['homedir'].'
								</td>
							</tr>
							<tr>
								<td>
									'.__('Current Disk Usage').'
								</td>
								<td>
									'.round($storage[$cur_storage]['used'] ,2).'/'.round($storage[$cur_storage]['size'],2).' GB ('.round($storage[$cur_storage]['free'],2).' GB Free)
								</td>
							</tr>
							<tr>
								<td>
									'.__('New Storage').'
								</td>
								<td>';
								if(count($storage) > 1){
								echo'
									<select class="form-select" name="storage" id="new_dest" >';
										foreach($storage as $k => $v){
											if($cur_storage == $k){continue;}
											echo '<option value="'.$k.'" >'.$v['name'].'( '.$k.' - '.round($v['free'],2).' GB free )</option>';	
										}								
									echo '
									</select>';
								}else{
									echo '<span>'.__('No extra storage available !').'</span>
										  <a href="'.$globals['index'].'act=addstorage" class="btn btn-primary" style="margin-left: 30px;">'.__('Add Storage').'</a>';
								}
								echo '
								</td>
							</tr>
						</tbody>
					</table>
				</div>';
				if(count($storage) > 1){
				echo'
				<div class="text-center">
					<input type="submit" name="move_account" id="move_account" class="btn btn-primary" value="'.__('Move Account').'"/>
				</div>';
				}
				echo '
			</div>';
		}
	}
	echo '
	<div id="progress_bar" class="progress" style="display: none; height: 20px;">	
		<div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" id="progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;height: 20px;"><span><span></div>
	</div>
	<div class="text-center" id="done_msg">
	</div>
	<div class="text-center" id="showlogdiv" style="margin-top : 50px;display: none;" >
		<p>Check your process logs </p>
		<input type="hidden" id="taskid" />
		<a href="javascript:void(0);" id="loadlogs"  class="btn btn-primary btn-logs">'.__('Show logs').'</a>
	</div>
</div>

<script>
$(document).ready(function () {
	$("#move_account").click(function(e){
		$("#formcontent").hide();
		var d = {"storage" : $("#new_dest option:selected").val(), "move_account":1};
		submitit(d, {
			after_handle:function(data, p){
				
				if(empty(data.done)){
					return false;
				}
				
				var actid = data.done.actid;
				
				$("#taskid").val(actid);
				progressbar(actid);
				interval = setInterval(function(){
					progressbar(actid)
				}, 3000);
			}
		});	

	});
	
	function progressbar(taskID){
		if(taskID != ""){
			$.ajax({				
				type: "POST",
				dataType: "json",			
				url:"'.$globals['admin_url'].'"+"act=tasks&api=json&taskID="+taskID,
				success: function(data){
					var process = data["tasks"][taskID]["progress"];
					var status_txt = data["tasks"][taskID]["status_txt"];
					
					$("#loadlogs").click(function(){
						loadlogs(taskID);
					});
					$("#progress_bar").css("display","block");
					$("#showlogdiv").show();					
					$("#progress").css("width", (process)+"%");
					$("#progress span").text(process+"%");
					
					if(status_txt.match(/Error:/) != null){
						$("#progress_bar").css("display","none");
						$("#done_msg").html("<p>'.__js('Rearranging account failed. Please check the task logs for more information !').'</p>");
						clearInterval(interval); // stop the interval
					}
					
					if(process == 100){					
						clearInterval(interval); // stop the interval
						setTimeout(function(){
							$("#progress_bar").css("display","none");
							$("#done_msg").html("<b>'.__js('The account was moved successfully').'</b>");
						},2000)					
					}		
				}															  
			});
		}		
	}
	
});

$("#user_search").on("select2:select", function(e, u = {}){	
	let user;
	if("user" in u){
		user = u.user;
	}else{
		user = $("#user_search option:selected").val();
	}
	window.location = "'.$globals['index'].'act=rearrange_account&user="+user;
});

$("#dom_search").on("select2:select", function(){
	var domain_selected = $("#dom_search option:selected").val();
	var d = {"get_user": 1, "domain" : domain_selected};
	submitit(d, {
		handle:function(data){
			window.location = "'.$globals['index'].'act=rearrange_account&user="+data.domain_user;
		}
	})	
});
</script>';

	softfooter();

}

