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

function sslcrt_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error , $done, $key_list, $crt_list, $detailcert;


	softheader(__('Certificate'));
	
	echo '
<div class="card soft-card p-4 mb-4">
	<div class="sai_main_head mb-4">
		<img src="'.$theme['images'].'sslcrt.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Certificate').'</h5>
	</div>
	<div id="showrectab" class="table-responsive">';
		showcert();
	echo '
	</div>
	<div id="detailrectab" style="display:none">
		<div class="row td_font my-4">
			<div class="col-12 col-lg-6 text-center"> 
				<label class="form-label">'.__('Certificate file of ').'<span id="ddomain">--</span></label><br/>
				<textarea class="w-100" id="dkey" name="kpaste" rows="25" readonly="readonly" wrap="off">---</textarea>	
			</div>
			<div class="col-12 col-lg-6 text-center">
				<label class="form-label">'.__('Infomation of Certificate').'</label><br />
				<textarea class="w-100" id="dvalue" name="kpaste" rows="25" readonly="readonly">---</textarea>			
			</div>
		</div>
		<div class="text-center">
			<button class="dclose flat-butt" onclick="$(\'#detailrectab\').slideUp(\'slide\',\'\',1000);">'.__('Close Detail').'</button>
		</div>
	</div>
</div>

<div class="row">
	<!--Generate Certificate form-->
	<div class="col-12 col-lg-6">
		<div class="card soft-card p-4 mb-4">
			<div class="sai_main_head mb-3">
				<h5 class="d-inline-block">'.__('Generate a Certificate').'</h5>
			</div>
			<div class="alert alert-warning">
				'.__('<strong>NOTE : </strong>You must generate or upload a key before generating any certificates.').'
			</div>
			<form accept-charset="'.$globals['charset'].'" action="" method="post" id="generate_cert" name="generate_cert" onsubmit="return submitit(this)" data-donereload="1">
				
				<label class="form-label" for="selectkey">'.__('key').'</label>
				<select class="form-select mb-3" name="selectkey" id="selectkey">';				
					foreach ($key_list as $value){
						$ext = get_extension($value);					
						if($ext == 'key'){				
							$file = get_filename($value);
							if(!array_key_exists(trim($file), $crt_list)) echo '<option value='.$file.'>'.$file.'</option>';
						}								
					}
					echo '<option value=newkey>'.__('Generate a new 2048 bit key').'</option>
				</select>
				<label class="form-label" for="domain">'.__('Domain').'</label>
				<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Provide the FQDNs that you are trying to secure. You may use a wildcard domain by adding an asterisk in a domain name in the form: ').'"></i>
				<input type="text" name="domain" id="domain" class="form-control mb-3"'.(!empty($error)? 'value="'.POSTval('domain', '').'"' : '').' />
				<label class="form-label" for="country">'.__('Country Code').'</label>
				<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('2 letter abbreviation e.g. US or IN').'"></i>
				<input type="text" name="country" id="country" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('country', '').'"' : '').' />
				<label class="form-label" for="state">'.__('State').'</label>
				<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of the State or Province').'"></i>
				<input type="text" name="state" id="state" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('state', '').'"' : '').' />
				<label class="form-label" for="locality">'.__('Locality').'</label>
				<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of the City or Town').'"></i>			
				<input type="text" name="locality" id="locality" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('locality', '').'"' : '').' />
				<label class="form-label" for="organisation">'.__('Company Name').'</label>
				<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of your Company or Organisation').'"></i>	
				<input type="text" name="organisation" id="organisation" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('organisation', '').'"' : '').' />
				<label class="form-label" for="orgunit">'.__('Company Branch').'</label>
				<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of the Organisation branch or Division').'"></i>
				<input type="text" name="orgunit" id="orgunit" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('orgunit', '').'"' : '').' />
				<label class="form-label" for="email">'.__('Email Address').'</label>
				<i class="fas fa-info-circle" data-bs-custom-class="webuzo-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Your email address').'"></i>	
				<input type="text" name="email" id="email" class="form-control mb-3" '.(!empty($error)? 'value="'.POSTval('email', '').'"' : '').' />
				<div class="text-center my-3">		
					<input type="submit" value="'.__('Create').'" class="flat-butt" name="create_crt" id="submitcrt"/>
				</div>
			</form>
		</div>
	</div>
	<!-- End Generate Certificate form-->
	<!--New Certificate form-->
	<div class="col-12 col-lg-6">
		<div class="card soft-card p-4 mb-4">
			<div class="sai_main_head mb-3">
				<h5 class="d-inline-block">'.__('Upload a New Certificate').'</h5>
			</div>
			<form accept-charset="'.$globals['charset'].'" action="" method="post" enctype="multipart/form-data" name="upload_cert" onsubmit="return submititwithdata(this)" id="upload_cert" data-donereload="1">		
				<label class="form-label" for="kpaste">'.__('Paste your Certificate here').'</label>					
				<textarea name="kpaste" id="kpaste" class="form-control mb-2" rows="20" cols="70"></textarea>
				<label class="form-label d-block mb-3">'.__('OR').'</label>
				<label class="form-label d-block" for="ukey">'.__('Choose the .crt file').'</label>
				<input type="file" id="ukey" name="ukey"/>
				<div class="text-center">
					<input type="hidden" name="install_crt" value="install_crt">
					<input type="submit" value="'.__('Upload').'" class="flat-butt" name="install_crt" id="install_crt"/>
				</div>
			</form>
		</div>
	</div>
	<!--end New Certificate form-->
</div>

<script language="javascript" type="text/javascript">
// For showing detail	
$(".edit").click(function(){
	var d = $(this).data();
	
	return submitit(d, {
		handle: function(data){
			if("detailcert" in data){
				// console.log(data);
				$.each(data.detailcert, function(key, value){
					// console.log(key, value);
					$("#ddomain").html(key);
					$("#dkey").html(value.key);
					$("#dvalue").html(value.info);
				});
				
				$("#detailrectab").slideDown("slide", "", 5000).show();
				
			}
		}
	});												
});
</script>';

softfooter();

}

function showcert(){
	
global $user, $globals, $theme, $softpanel, $WE, $error , $done, $key_list, $domain_name, $crt_list;
	
	echo '
<table border="0" cellpadding="8" cellspacing="0" class="table align-middle table-nowrap mb-0 webuzo-table td_font">
	<thead class="sai_head2">
		<tr >
			<th class="align-middle">'.__('HOST').'</th>
			<th class="align-middle" colspan="2">'.__('OPTION').'</th>
		</tr>
	</thead>
	<tbody>';

	if(empty($crt_list)){
		
		echo '
		<tr class="text-center">
			<td colspan=3>
				<span>'.__('No Certificates Found').'</span>
			</td>
		</tr>';
		
	}else{
		
		foreach ($crt_list as $key => $value){
			
			$ext = get_extension($value);
			
			if($ext == 'crt'){
				$file = get_filename($value);
				
				echo '
		<tr>
			<td>
				<span id="name'.$key.'">'.$file.'</span>
			</td>
			<td width="2%">
				<i title="Show" id="eid'.$file.'" class="fas fa-file-alt edit edit-icon text-center me-2" data-detail_record="'.$file.'"></i>
			</td>
			<td width="2%">
				<i class="fas fa-times delete delete-icon" title="Delete" id="did'.$key.'" onclick="delete_record(this)" data-delete_record="'.$key.'" data-donereload="1"></i>
			</td>
		</tr>';
		
			}
		}
	}
	
	echo '
	</tbody>
</table>';

}
