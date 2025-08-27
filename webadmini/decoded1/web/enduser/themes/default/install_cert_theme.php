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

function install_cert_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error, $done, $domain_list, $install_list , $detailkey, $detail;

	softheader(__('Install Certificate'));
	
	echo '
<div class="card soft-card p-4 mb-4">
	<div class="sai_main_head mb-4">
		<img src="'.$theme['images'].'install_cert.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Install Certificate').'</h5>
	</div>
	<div id="showrectab" class="table-responsive">';
		showcert();
	echo '
	</div>
</div>

<div class="card soft-card p-4 mb-4">
	<form accept-charset="'.$globals['charset'].'" action="" method="post" id="editformuplode" enctype="multipart/form-data" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
		<div class="row mb-4">
			<div class="col-12 col-md-4 col-lg-3">
				<label class="form-label d-block" for="selectdomain">'.__('Select Domain').'</label>
			</div>
			<div class="col-12 col-md-8 col-lg-9">
				<select class="p-2" name="selectdomain" id="selectdomain" onchange="disp_type(this.value)">	
					<option value="">'.__('Select Domain').'</option>';			
				foreach ($domain_list as $key => $value){
					echo '<option value='.$key.'>'.$key.'</option>';
				}
				echo '
				</select>
				<input type="button" name="fetch_data" value="'.__('Fetch').'" class="flat-butt" id="fetchdata"/>
				<img id="createcron" src="'.$theme['images'].'progress.gif" style="display:none">
				<div class="sai_exp2 sai_exp_small">'.__('Note: Fetch will search for your installed certificates and load them. If a certificate is not found, you can still manually paste the certificate, key and chain').'</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-4 col-lg-3">
				<label class="form-label" for="kpaste">'.__('Paste your Private Key here').'</label>
			</div>
			<div class="col-12 col-md-8 col-lg-9">
				<textarea name="kpaste" id="kpaste" rows="10" cols="70" class="form-control mb-3" wrap="off"></textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-4 col-lg-3">
				<label class="form-label" for="cpaste">'.__('Paste your Certificate(CRT) here').'</label>
			</div>
			<div class="col-12 col-md-8 col-lg-9">
				<textarea name="cpaste" id="cpaste" rows="10" cols="70" class="form-control mb-3" wrap="off"></textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-4 col-lg-3">
				<label class="form-label" for="bpaste">'.__('Paste the CA bundle here (Optional)').'</label>
			</div>
			<div class="col-12 col-md-8 col-lg-9">
				<textarea name="bpaste" id="bpaste" rows="10" cols="70" class="form-control mb-3" wrap="off"></textarea>
			</div>
		</div>			
		<div class="text-center">
			<input type="submit" value="'.__('Install').'" class="flat-butt" name="install_key" id="submitkey" />
		</div>
	</form>
</div>
<script language="javascript" type="text/javascript">

// For fetching cert details
$("#fetchdata").click(function(){
	var domain = $("#selectdomain").val();
	var d = {detail_record: domain};
	
	if(!domain){
		alert("Domain Invalid !");
		return false;
	}
	
	$("#kpaste").html("");
	$("#cpaste").html("");		
	$("#bpaste").html("");
	
	return submitit(d, {
		success: function(data){
			if("detailicert" in data){
				
				// console.log(data);
				$("#kpaste").html(data.detailicert.key);
				$("#cpaste").html(data.detailicert.crt);		
				$("#bpaste").html(data.detailicert.ca_crt);
				
			}
		}
	});												
});

// Function for show host
function disp_type(str){				
	$("#select_domain").val(str);			
}

</script>';	

	softfooter();
}

function showcert(){
	
global $user, $globals, $theme, $softpanel, $WE, $error , $done, $domain_list, $domain_name, $install_list;

	echo '
<table cellpadding="8" cellspacing="0" class="table align-middle table-nowrap mb-0 webuzo-table td_font">
	<thead class="sai_head2">
		<tr>
			<th class="align-middle">'.__('HOST').'</th>
			<th class="align-middle">'.__('OPTION').'</th>
		</tr>
	</thead>
<tbody>';

	if(empty($install_list)){
		echo '
		<tr class="text-center">
			<td colspan=2>
				<span>'.__('No Installed Certificates Found').'</span>
			</td>
		</tr>';
	} else {
		
		foreach($install_list as $key => $value){
			
			$ext = get_extension($value);
			if($ext == 'key'){
				$file = get_filename($value);
				echo '
		<tr id="tr'.$file.'">
			<td>
				<div class="endurl">
					<a href="https://'.$file.'" target="_blank" >
						<span id="name'.$key.'">'.$file.'</span>
					</a>
				</div>
			</td>
			<td width="2%">
				<i class="fas fa-trash delete delete-icon" title="Delete" id="did'.$file.'" onclick="delete_record(this)" data-delete_record="'.$key.'"></i>
			</td>
		</tr>';
		
			}
		}
	}

	echo '
	</tbody>
</table>';

}

