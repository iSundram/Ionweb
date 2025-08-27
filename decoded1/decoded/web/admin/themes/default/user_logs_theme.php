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

function user_logs_theme(){

global $user, $globals, $theme, $error, $done, $users, $logs_list, $logfiles, $webserver_list;

	softheader(__('User Logs'));
	
	echo '
	<script src="https://iamdanfox.github.io/anno.js/dist/anno.js" ></script>
	<link href="https://iamdanfox.github.io/anno.js/dist/anno.css" rel="stylesheet" type="text/css" />
	
<div class="soft-smbox p-4">
	<div class="sai_main_head">
		<i class="fas fa-file-download me-2"></i>'.__('Download User Logs').'
		<span class="search_btn float-end">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div class="" style="background-color:#e9ecef;">
		<div class="collapse mt-2 '.(!empty(optREQ('user_search')) || !empty(optREQ('domain')) ? 'show' : '').'" id="search_queue">
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
					<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select Domain').'" s2-ajaxurl="'.$globals['index'].'act=domains&api=json" s2-query="dom_search" s2-data-key="domains" s2-data-subkey="domain" style="width: 100%" name="dom_search" id="dom_search">
						<option value="'.optREQ('domain').'" selected="selected">'.optREQ('domain').'</option>
					</select>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="soft-smbox p-4 mt-4">';
	
	error_handle($error, "100%");	
	page_links();
	
	echo '
	<div class="table-responsive">
		<table border="0" cellpadding="8" cellspacing="1" class="table table-hover-moz webuzo-table td_font">
			<thead class="sai_head2">
				<tr>
					<th>'.__('Users').'</th>
					<th>'.__('Domain').'</th>
					<th colspan="5" class="text-center"> '.__('Download').' </th>
				</tr>
			</thead>
			<tbody id="userlist">';
			
			if(empty($users)){
				echo '
					<tr>
						<td colspan="100" class="text-center">
							<span>'.__('No Users Exist').'</span>
						</td>
					</<tr>';						
			}else{
				foreach($users as $user => $value){
					echo '
					<tr '.($value['status'] == 'suspended' ? 'style="background-color: #ffdbdb;"' : '').' class="userdata">
						<td id="user_name" style="width: 25%;">
							<span>'.$user.'</span>
						</td>
						<td >
						<span class="text-center">
							<select class="form-select form-select-sm" style="width: 35%;"  name="domain" id="dom_'.$user.'">';
							foreach($value as $dom => $val){
								echo'
								<option name="domain_name">
									'.$val.'
								</option>';
							}
							echo'	
							</select>
						</span>
						</td>';
						foreach($webserver_list as $key => $val){
							echo'
							<td width="2%" style="position:relative" >
								<button type="button" class="btn btn-primary float-end showlogs anno_'.$key.'" id="domain_'.$user.'" data-show_log=1 data-user="'.$user.'" data-domain="'.$user.'" data-webserver="'.$key.'"> '.strtoupper($key).'</button>
							</td>';
						}
					echo '
					</tr>';
				}
			}
			echo '
			</tbody>
		</table>
	</div>';
	page_links();
	echo'
	<nav aria-label="Page navigation">
		<ul class="pagination pager myPager justify-content-end">
		</ul>
	</nav>
</div>
<div class="modal fade" id="show_logs_list" tabindex="-1" aria-labelledby="download-log" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title" id="domain_log"></h6>	
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4" id="mbody">
					<form accept-charset="'.$globals['charset'].'" action="" method="post" name="download_log" id="form_id" class="form-horizontal" onsubmit="return download_file(this)">
						<div id="warn">
							<label id="fileNotFound"></label>
						</div>
						<div id="form_data">
							<label id="file_exp" class="sai_head">
								'.__('Select a log file to download').' <span id="file_exp" class="form-control sai_exp">'.__('Empty files will not be listed here').'</span>
							</label>
							<input type="hidden" class="form-control" name="path" id="path_to_file" value=""/>
							<select id="log_list" name="domain_file" class="form-select">
							</select></br>
							<center><input type="submit" class="btn btn-primary" name="download_log" id="dwld" value="'.__('Download').'"/>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<script>
	// function show_log(ele){
	$(document).on("click", ".showlogs", function(){
		var da = $(this).data();
		var d = {"user" : da.user, "domain" : $("#dom_"+da.domain).val(), "webserver" : da.webserver, "show_log" : 1}
		var html;
		submitit(d, {
				handle:function(data, p){
					// console.log(data.logfiles);
					$("#domain_log").html(d.domain + " - " + d.webserver.toUpperCase());
					if(empty(data.logfiles[d.webserver])){
						$("#fileNotFound").html("'.__js('No Log file found for this domain').' "+d.domain);
						$("#warn").attr("class", "alert alert-warning col-sm justify-content-center");
						$("#form_data").attr("class", "d-none");
						$("#show_logs_list").modal("show");
						
					}else{
						$("#warn").attr("class", "d-none");
						$("#form_data").attr("class", "sai_form");
						
						var [first] = Object.keys(data.logfiles);
						$("#path_to_file").val(first);
						
						$.each(data.logfiles[d.webserver], function(key, val){
							$.each(data.logfiles[d.webserver][key], function(file, filesize){
								// console.log(filesize);
								html += \'<option>\'+file+"\t\t("+filesize+" Bytes)"+\'</option>\'
							});
						});
						
						$("#log_list").show();
						$("#dwld").show();
						$("#log_list").html(html);
						$("#show_logs_list").modal("show");
					}
				},
			});		
	})
	
	// Download link
	function download_file(ele){
		var filename = $("#log_list option:selected").val();
		var n = filename.indexOf("(");
		filename = filename.substring(0, n != -1 ? n : filename.length);
	
		window.location =  "'.$globals['index'].'act=user_logs&download_log="+$("#path_to_file").val()+"_"+filename;
		$("#show_logs_list").modal("hide");
		return false;
	}

	$("#user_search").on("select2:select", function(e, u = {}){		
		user = $("#user_search option:selected").val();
		window.location = "'.$globals['index'].'act=user_logs&user_search="+user;
	});
		
	$("#dom_search").on("select2:select", function(){
		var domain_selected = $("#dom_search option:selected").val();		
		window.location = "'.$globals['index'].'act=user_logs&domain="+domain_selected;
			
	});
	
	$(document).ready(function(){

		var f = function(){		
			
			var type = window.location.hash.substr(1);
			if(!empty(type)){
				var anno1 = new Anno({
					target : ".anno_"+type,
					position : "left",
					content: "'.__js('Click Here to Download Logs of ').'"+"<b>"+type.toUpperCase()+"</b>",
					onShow: function () {
						$(".anno-btn").hide();
					}
				})
				anno1.show();
				window.location.hash = "";
			}
		}
		f();
		$(window).on("hashchange", f);	
	});
</script>';

	softfooter();
}

