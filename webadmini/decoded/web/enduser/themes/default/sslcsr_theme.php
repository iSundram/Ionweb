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

function sslcsr_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error , $done, $domain_list, $domain_name, $csr_list, $detailcsr;

	softheader(__('Certificate Signing Requests'));

	echo '
<div class="card soft-card p-3 ">
	<div class="sai_main_head ">
		<img src="'.$theme['images'].'sslcsr.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Certificate Signing Requests').'</h5>
	</div>
</div>
<div class="card soft-card p-4 mt-4 mb-4">';
	if(empty($domain_list)){
		
		echo ' 
	<div class="alert alert-warning text-center">
		<h6>'.__('Please add the Private Key file for the specified domain.').'</h6>
	</div>';
	
	}else{
		
		echo '
	<div id="showrectab" class="table-responsive">';
		showcert();
	echo '
	</div>
	<div id="detailrectab" style="display:none">
		<div class="row td_font my-4">
			<div class="col-12 col-lg-6 text-center"> 
				<label class="form-label">'.__('Certificate Signing Request file of ').'<span id="ddomain">--</span></label><br/>
				<textarea class="w-100" id="dkey" name="kpaste" rows="25" readonly="readonly" wrap="off">---</textarea>	
			</div>
			<div class="col-12 col-lg-6 text-center">
				<label class="form-label">'.__('Information of Certificate Signing Requests').'</label><br />
				<textarea class="w-100" id="dvalue" name="kpaste" rows="25" readonly="readonly">---</textarea>			
			</div>
		</div>
		<div class="text-center">
			<button class="dclose flat-butt" onclick="$(\'#detailrectab\').slideUp(\'slide\',\'\',1000);">'.__('Close Detail').'</button>
		</div>
	</div>
</div>

<!--editform card-->
<div class="card soft-card p-4 mb-4">
	<form accept-charset="'.$globals['charset'].'" name="createssl" method="post" action="" class="form-horizontal" id="ssl_add" onsubmit="return submitit(this)" data-donereload="1">
		<div class="row">
			<div class="col-12 col-lg-6 mb-3">
				<label class="form-label" for="selectkey">'.__('key').'</label>
				<select name="selectkey" id="selectkey" class="form-select">';
				foreach ($domain_list as $key => $value){
					$ext = get_extension($value);					
					if($ext == 'key'){				
						$file = get_filename($value);
						if(!in_array($file.'.csr', $csr_list))									
						echo '<option value='.$file.'>'.$file.'</option>';
					}
				}				
				echo '<option value=newkey>'.__('Generate a new 2048 bit key').'</option>
				</select>
			</div>
			<div class="col-12 col-lg-6 mb-3">
				<label class="form-label" for="domain">'.__('Domain').'</label>
				<i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Provide the FQDNs that you are trying to secure. You may use a wildcard domain by adding an asterisk in a domain name in the form: ').'"></i>
				<input type="text" name="domain" id="domain" class="form-control mb-2" '.(!empty($error)? 'value="'.POSTval('domain', '').'"' : '').'/>
			</div>	
			<div class="col-12 col-lg-6 mb-3">
				<label class="form-label" for="country">'.__('Country Code').'</label>
				<i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('2 letter abbreviation e.g. US or IN').'"></i>
				<input type="text" name="country" id="country" class="form-control mb-2" '.(!empty($error)? 'value="'.POSTval('country', '').'"' : '').'/>
			</div>
			<div class="col-12 col-lg-6 mb-3">
				<label class="form-label" for="state">'.__('State').'</label>
				<i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of the State or Province').'"></i>
				<input type="text" name="state" id="state" class="form-control mb-2" '.(!empty($error)? 'value="'.POSTval('state', '').'"' : '').'/>
			</div>
			<div class="col-12 col-lg-6 mb-3">
				<label class="form-label" for="locality">'.__('Locality').'</label>
				<i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of the City or Town').'"></i>
				<input type="text" name="locality" id="locality" class="form-control mb-2"'.(!empty($error)? 'value="'.POSTval('locality', '').'"' : '').'/>
			</div>
			<div class="col-12 col-lg-6 mb-3">
				<label class="form-label" for="organisation">'.__('Company Name').'</label>
				<i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of your Company or Organisation').'"></i>
				<input type="text" name="organisation" id="organisation" class="form-control mb-2" '.(!empty($error)? 'value="'.POSTval('organisation', '').'"' : '').'/>
			</div>
			<div class="col-12 col-lg-6 mb-3">
				<label class="form-label" for="orgunit">'.__('Company Branch').'</label>
				<i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Name of the Organisation branch or Division').'"></i>
				<input type="text" name="orgunit" id="orgunit" class="form-control mb-2" '.(!empty($error)? 'value="'.POSTval('orgunit', '').'"' : '').'/>
			</div>
			<div class="col-12 col-lg-6 mb-3">
				<label class="form-label" for="email">'.__('Email Address').'</label>
				<i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Your email address').'"></i>
				<input type="text" name="email" id="email" class="form-control mb-2" '.(!empty($error)? 'value="'.POSTval('email', '').'"' : '').'/>
			</div>
			<div class="col-12 col-lg-6 mb-3">
				<label class="form-label" for="pass">'.__('Pass Phrase').'</label>
				<i class="fas fa-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Pass Phrase is similar to a PASSWORD.').'"></i>
				<input type="text" name="pass" id="pass" class="form-control mb-2" '.(!empty($error)? 'value="'.POSTval('pass', '').'"' : '').'/>
			</div>
		</div>
		<div class="text-center">
			<input type="submit" value="'.__('Create').'" class="flat-butt" name="createcsr" id="submitcsr"/>
		</div>
	</form>
</div>
			
<script language="javascript" type="text/javascript">

// For showing detail	
$(".edit").click(function(){
	var d = $(this).data();
	
	return submitit(d, {
		handle: function(data){
			if("detailcsr" in data){
				// console.log(data);
				$.each(data.detailcsr, function(key, value){
					console.log(key, value);
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
	}

	softfooter();
}

function showcert(){
	
global $user, $globals, $theme, $softpanel, $WE, $error , $done, $domain_list, $domain_name, $csr_list;
	
	echo '
<table class="table align-middle table-nowrap mb-0 webuzo-table td_font">	
	<thead class="sai_head2">
		<tr>
			<th class="align-middle">'.__('HOST').'</th>
			<th class="align-middle" colspan="2">'.__('OPTION').'</th>
		</tr>
	</thead>
	<tbody>';

	if(empty($csr_list)) {
		echo '
		<tr class="text-center">
			<td colspan=4>
				<span>'.__('No CSR record found.').'</span>
			</td>
		</tr>';
	}else{

		foreach ($csr_list as $key => $value){	
			$ext = get_extension($value);					
			if($ext == 'csr'){
				
				$file = get_filename($value);
				
				echo '
		<tr>
			<td>
				<span id="name'.$key.'">'.$file.'</span>							
			</td>
			<td width="2%">
				<i class="fas fa-file-alt edit edit-icon text-center me-2" title="Show" id="eid'.$file.'" data-detail_record="'.$file.'"></i>
			</td>
			<td width="2%">
				<i class="fas fa-times delete delete-icon" title="'.__('Delete').'" id="did'.$key.'" onclick="delete_record(this)" data-delete_record="'.$key.'"></i>
			</td>
		</tr>';
		
			}
		}
	}
	
	echo '
	</tbody>
</table>';
}

