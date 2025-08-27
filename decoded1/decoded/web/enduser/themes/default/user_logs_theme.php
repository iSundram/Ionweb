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

global $user, $globals, $WE, $theme, $error, $done, $domains_list, $logfiles, $webserver_list;

	softheader(__('User Logs'));
	echo '
<div class="card soft-card p-3 col-12 mx-auto">
	<div class="sai_main_head ">
		<img src="'.$theme['images'].'download_log.png" alt="" class="webu_head_img me-2" >
		<h5 class="d-inline-block">'.__('Download User Logs').'</h5>
		<a href="'.$globals['ind'].'act=errorlog" class="float-end"><button type="button" class="flat-butt">'.__('Show Error Logs').'</button></a>
	</div>
</div>
<div class="card soft-card p-4 col-12 mx-auto mt-4">';
	error_handle($error, "100%");
	
	echo '
	<div class="table-responsive mt-4">
		<table class="table align-middle table-nowrap mb-0 webuzo-table" >			
			<thead class="sai_head2" style="background-color: #EFEFEF;">
				<tr>
					<th>'.__('Domain').'</th>
					<th>'.__('Path').'</th>
					<th>'.__('Type').'</th>
					<th colspan="5" class="text-center"> '.__('Download').' </th>
				</tr>
			</thead>
			<tbody id="userlist">';
		if(empty($domains_list)){
			echo '
				<tr>
					<td colspan="100" class="text-center">
						<span>'.__('No Users Exist').'</span>
					</td>
				</<tr>';						
		}else{
			$i=0;
			foreach($domains_list as $domain => $value){
				echo '
				<tr>
					<td width="15%" class="endurl"><a target="_blank" href="https://'.$domain.'">
						<span class="text-center">'.$domain.'</span></a>
					</td>
					<td width="20%">
						<span class="text-center">'.$value['path'].'</span>
					<td width="20%">
						<span class="text-center">'.ucfirst(domain_type($WE->user, $domain)).'</span>
					</td>
					</td>';
					foreach($webserver_list as $key => $val){
						echo'
						<td width="	2%">
							<input type="submit" class="flat-butt p-2 float-end showlogs" id="domain_'.$domain.'" value="'.strtoupper($key).'" data-show_log=1 data-user="'.$user.'" data-domain="'.$domain.'" data-webserver="'.$key.'" /> 
						</td>';
					}
					$i++;
				echo '
				</tr>';
			}
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
						<label id="file_exp" class="sai_head">'.__('Select log file to download').' 
							<i class="fas fa-exclamation-circle" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="'.__('Empty files will not be listed here').'"></i>
						</label>
						<input type="hidden" class="form-control" name="path" id="path_to_file" value=""/>
						<select id="log_list" name="domain_file" class="form-select">
						</select></br>
						<center><input type="submit" class="btn flat-butt" name="download_log" id="dwld" value="'.__('Download').'"/>
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
		var d = {"user" : da.user, "domain" : da.domain, "webserver" : da.webserver, "show_log" : 1}
		var html;
		submitit(d, {
				handle:function(data, p){
					// console.log(data.logfiles[d.webserver]);
					$("#domain_log").html(d.domain + " - " + d.webserver.toUpperCase());
					if(empty(data.logfiles[d.webserver])){
						$("#fileNotFound").html("'.__js('No Log file found for this domain ').'"+d.domain);
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
	
		window.location =  "'.$globals['ind'].'act=user_logs&download_log="+$("#path_to_file").val()+"_"+filename;
		$("#show_logs_list").modal("hide");
		return false;
	}
	
</script>';

	softfooter();
}