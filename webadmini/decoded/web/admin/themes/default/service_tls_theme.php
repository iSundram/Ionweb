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

function service_tls_theme(){

global $theme, $globals, $user, $langs, $error, $W, $saved, $done, $services_list, $user_certs, $le_log, $details;
	
	foreach($services_list as $k => $v){
			
		// Is there a cert installed ?
		if(file_exists($globals['certs_path'].$k.'.crt')){
			$services_list[$k]['cert'] = cert_details($globals['certs_path'].$k.'.crt');
		}
		
	}
	
	softheader(__('Manage Service SSL Certificates'));
	
	echo '<div class="soft-smbox p-3 mb-3" id="showrectab">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'sslcrt.png" class="me-1" alt="" />'.__('Manage Service SSL Certificates').'
	</div>
</div>

<div class="soft-smbox p-3 mb-3" id="showrectab"><br/>';
	error_handle($error, '100%');
	if(!empty($done)){	
	echo '
	<div class="alert alert-success text-center"><a href="#close" class="close" data-dismiss="alert">&times;</a><img src="'.$theme['images'].'success.gif" /> '.__('The certificate(s) were successfully installed for the selected services').'</div>';
	
	}
	services_cert_list();

	echo '
	<div class="sai_main_head">'.__('Install a New Certificate').'</div>
	<hr>
	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active" id="install-acme-tab" data-bs-toggle="tab" data-bs-target="#install_acme" type="button" role="tab" aria-controls="install_acme" aria-selected="true">'.__('Install $CA Certificate', ['CA' => $globals['SSL_CA']]).'</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="choose-crt-tab" data-bs-toggle="tab" data-bs-target="#choose_crt" type="button" role="tab" aria-controls="choose_crt" aria-selected="false">'.__('Choose Certificate').'</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="install-manually-tab" data-bs-toggle="tab" data-bs-target="#install_manually" type="button" role="tab" aria-controls="install_manually" aria-selected="false">'.__('Install Certificate Manually').'</button>
		</li>
	</ul>
	<div class="tab-content p-4">
		<form accept-charset="'.$globals['charset'].'" name="cert_service_list" id="cert_service_list"  method="post" action="" class="form-horizontal">
			<h5 class="form-label">'.__('Description').':</h5>';
			
			foreach($services_list as $k => $service){
				echo '
			<div class="form-check mb-3">
				<input class="form-check-input" type="checkbox" name="'.$k.'_service" id="service_'.$k.'">
				<label class="form-check-label" for="service_'.$k.'">'.$service['name'].'</label>
			</div>';
			}

			echo '
		</form>
		
		<div id="install_acme" class="tab-pane fade show active" role="tabpanel">
			<h5>'.__('This certificate will be issued for the hostname - ').'<b>'.$globals['WU_PRIMARY_DOMAIN'].'</b></h5>
			<input type="button" name="issue_acme" value="'.__('Issue Certificate').'" class="btn btn-primary" onclick="submitACME(this)"  data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('This action will issue / re-issue the certificate (i.e. create a new trusted certificate) and install it for the selected services').'" /> &nbsp;
			'.(!empty($details) ? '
			<input type="button" name="install_acme" value="'.__('Install Certificate').'" class="btn btn-primary" onclick="submitACME(this)" data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('This action will use the $0 existing $1 certificate and install it for the selected services', ['<b>', '</b>']).'" /> &nbsp;
			<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#showACME">'.__('Show Certificate').'</button> &nbsp;' : '').'
		</div>
		<div id="choose_crt" class="tab-pane fade" role="tabpanel" aria-labelledby="choose-crt-tab">
			<input type="button" class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#browseCerts" data-backdrop="static" value="'.__('Browse User Certificates').'"/>
		</div>
		<div id="install_manually" class="tab-pane fade" role="tabpanel" aria-labelledby="install-manually-tab">
		<form accept-charset="'.$globals['charset'].'" name="f_install_manually" id="f_install_manually"  method="post" action="" class="form-horizontal" onsubmit="return submitInsManually(this)">
			<div class="row">
				<div class="col-12 col-md-6 mb-3">
					<label class="sai_head" for="kpaste">'.__('Private Key').':</label>
					<textarea name="kpaste" id="kpaste"  rows="5" cols="40" class="form-control" wrap="off"></textarea>
				</div>
				<div class="col-12 col-md-6 mb-3">
					<label class="sai_head" for="cpaste">'.__('Certificate').':</label>
					<textarea name="cpaste" id="cpaste" rows="5" cols="40" class="form-control" wrap="off"></textarea>
				</div>
				<div class="col-12 col-md-6 mb-3">
					<label class="sai_head" for="bpaste">'.__('Certificate Authority Bundle').':</label>
					<textarea name="bpaste" id="bpaste" rows="5" cols="40" class="form-control" wrap="off"></textarea>
				</div>
			</div>
			<div class="text-center my-3">
				<input type="hidden" name="install_manually" value="1">
				<input type="submit" value="'.__('Install Certificate').'" class="btn btn-primary me-1" name="install_manually" id="install_manually" />
			</div>
		</form>
		</div>
	</div>

	<!-- Browse Certificate Modal -->
	<div class="modal fade" id="browseCerts" role="dialog" aria-labelledby="browseCertsTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="browseCertsTitle">'.__('SSL Certificate List').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
				<form accept-charset="'.$globals['charset'].'" action="" method="post" name="f_apply_dom" id="f_apply_dom" class="form-horizontal" onsubmit="return submitapplydom(this)">
					<div class="row">
						<div class="col-12 col-md-4 col-lg-3">
							<label class="sai_head" for="selectUser">'.__('Select User').':</label>
						</div>
						<div class="col-12 col-md-8 col-lg-9">
							<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-dropdownparent="#browseCerts" style="width:200px" id="user_search" name="user_search"></select>
						</div>
					</div>
					<div class="table-responsive my-3">
						<table border="0" cellpadding="8" cellspacing="1"  class="table webuzo-table td_font" id="browse_table">
							<thead>
								<tr style="color:#fff;">
									<th></th>
									<th>'.__('Domains').'</th>
									<th>'.__('Issuer').'</th>
									<th>'.__('Valid Till').'</th>
									<th>'.__('Description').'</th>				
								</tr>
							</thead>
							<tbody>';
								
							foreach ($user_certs as $dom => $v){
								echo '
								<tr>
									<td>
										<div class="form-check">
											<input class="form-check-input" type="radio" value="'.$dom.'" name="domain" issuer="'.$v['issuer'].'" />
										</div>
									</td>
									<td>'.$dom.'</td>
									<td>'.$v['issuer'].'</td>
									<td>'.$v['expiry'].'</td>
									<td>'.$v['desc'].'</td>
								</tr>';
							}
							echo '
							</tbody>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'.__('Cancel').'</button>
					<input type="submit" name="apply_dom_cert" class="btn btn-primary" value="'.__('Use Certificate').'">
				</div>
			</form>
			</div>
		</div>
	</div>
</div>

<!-- Show Cert Modal -->
<div class="modal fade" id="showACME" tabindex="-1" aria-labelledby="showACMETitle" aria-hidden="true" data-bs-keyboard="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="showACMETitle">'.__('Certificate').':</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="'.__('Close').'"></button>
			</div>
			<div class="modal-body">			
				<div class="tabel-responsive mb-3">
					<table class="table align-middle table-nowrap mb-0 webuzo-table td_font">
					<tbody>';
						$flag=0;
						
						$cert_langs = array('msc_subject' => __('Domain'),
									'msc_issuer' => __('Issued By'),
									'msc_version' => __('Version'),
									'msc_serial' => __('Serial No.'),
									'msc_val_from' => __('Valid From'),
									'msc_val_till' => __('Valid Till'),
									'msc_type' => __('Type'),
									'msc_created' => __('Created on'),
									'msc_next_renew' => __('Next Renew Date'),
									'msc_cert_domains' => __('Certificate Domains'),
							);
						
						foreach($details as $k => $v){
							echo '
						<tr>
							<td>'.$cert_langs['msc_'.$k].'</td>
								<td id="'.$k.'" style="text-align: left;">';
							if($k == 'subject' && $flag == 0){
								echo '<div class="endurl"><a href="https://'.$v.'" target="_blank" ><span id="name'.$k.'" >'.$v.'</span></a></div>';
								$flag = 1;
							}else{
								echo (is_array($v) ? implode('<br />', $v) : $v);
							}
								
							echo '</td>
						</tr>';
						
						}
						
						echo '
					</tbody>
					</table>
				</div>
			</div>
			<div class="modal-footer">			
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'.__('Close').'</button>
			</div>
		</div>
	</div>
</div>

<script language="javascript" type="text/javascript">

$("#user_search").on("select2:select", function(e){
	$("#browse_table tr td").remove();				
	var user = $(this).val();
	userData(user);
});
	
$(document).ready(function(){
	// Refresh the logs
	
	$("#browse_table tr").click(function() {
		$(this).find("td input[type=radio]").prop("checked", true);
	});
	
	
	$(document).on("click", ".reset_cert", function() {	
		var jEle = $(this);
		a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure want to reset certificate ?').'");
		a.alert = "alert-warning";
		a.confirm.push(function(){
			var d = jEle.data();
			submitit(d, {
				done_reload:window.location
			});
		});
		
		show_message(a);
	});
});

function userData(user){
	user = user || "";
	if(user.length < 1){
		return;
	}
	$.ajax({
		type: "POST",
		dataType: "JSON",
		url: window.location+"&user="+user+"&browse_crt=1&api=json",						
		// Checking for error
		success: function(data){
			if(data["user_certs"] != null){
				var trHTML = "";
				var domains = "";
				$.each(data["user_certs"], function (i, obj) {
					if(obj.alt_name){
						domains = obj.alt_name.replace(/DNS:/gi, "");
					}else{
						domains = obj.domain;
					}
					trHTML += "<tr><td><div class=\"form-check\"><input class=\"form-check-input\" type=\"radio\" value="+ i +" name=\"domain\"/></div></td><td>" + i + "</td><td>" + obj.issuer + "</td><td>" + obj.expiry + "</td><td>" + obj.desc + "</td></tr>";
				})
				$("#browse_table").append(trHTML);						
			}else{
				$("#browse_table").append("<tr><td colspan=\"5\"><center>'.__('No Data Found').'<center></td></tr>");
			}
			$("#browse_table tr").click(function() {
				$(this).find("td input[type=radio]").prop("checked", true);
			});
		}														
	});	
}

function submitapplydom(ele){
	var jEle = $(ele);
	var d = $(ele).serialize();
	d += "&"+ $("#cert_service_list").serialize();
	d += "&apply_dom_cert=1";
	return submitit(d,{
		done_reload:window.location
	});
}

function submitACME(ele){
	var jEle = $(ele);
	var d = $(ele).attr("name")+"=1";
	d += "&"+ $("#cert_service_list").serialize();
	d += "&acme=1";
	submitit(d, {
		handle:function(data){
			if(data.error){
				var a = show_message_r("'.__js('Error').'", data.error);
				a.alert = "alert-danger";
				show_message(a);
				return false;
			}
			
			if(data.done){
				var d = show_message_r("'.__js('Done').'", data.done.msg);
				d.alert = "alert-success";
				show_message(d);
				
				if(data.done.actid){
					var tmp_btn = \'<a type="button" id="task_log_btn" class="btn btn-info" href="javascript:loadlogs(\'+data.done.actid+\');">'.__js('Show Task logs').'</a>\'
					$("#install_acme").append(tmp_btn)
				}
			}
		}
	})
}

function submitInsManually(ele){
	var jEle = $(ele);
	var d = $(ele).serialize();
	d += "&"+ $("#cert_service_list").serialize();
	return submitit(d,{
		done_reload:window.location
	});
}

</script>';

	softfooter();

}

function services_cert_list(){
	
	global $theme, $globals, $user, $langs, $error, $W, $saved, $done, $services_list, $user_certs;
	
	echo '
<div class="table-responsive">
	<table border="0" cellpadding="8" cellspacing="1"  class="table webuzo-table td_font">
		<thead style="background:#08344F;" class="sai_head2">
			<tr style="color:#fff;">
				<th width ="20%">'.__('Service').'</th>
				<th width ="17%">'.__('Domains').'</th>
				<th width ="8%">'.__('Issuer').'</th>
				<th width ="25%">'.__('Certificate Expiry Date').'</th>
				<th width ="20%">'.__('Certificate Key').'</th>
				<th width ="10%" class="text-center">'.__('Actions').'</th>				
			</tr>
		</thead>
		<tbody>';
			
		$expiry_date = '';
		foreach($services_list as $k => $service){
			
			$cert = $service['cert'];
			
			if(empty($cert)){
				continue;
			}		
			
			echo '
			<tr id="service_row_'.$k.'">
				<td>'.$service['name'].'</td>';
			
			if(!empty($cert['alt_name'])){
				$alt_domains = str_replace('DNS:', '', $cert['alt_name']);
				echo '
				<td>'.$alt_domains.'</td>';
			}else{
				echo'
				<td>'.$cert['domain'].'</td>';
			}
			
			echo '
				<td>'.$cert['issuer'].'</td>
				<td>'.$cert['expiry'].'</td>
				<td>'.$cert['type'].'</td>
				<td>
					<a class="btn btn-primary text-decoration-none reset_cert" href="javascript:void(0)" data-reset_cert="'.$k.'" service="'.$k.'" service_name="'.$service.'">'.__('Reset Certificate').'</a>
				</td>
			</tr>';
		}
		
		echo'
		</tbody>
	</table>
</div>';
	
}


