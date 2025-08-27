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

function sslkey_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error, $done, $domain_list, $key_list , $detailkey, $done;



	softheader(__('Private Keys'));
	
	echo '
<div class="card soft-card p-4 mb-4">
	<div class="sai_main_head mb-4">
		<img src="'.$theme['images'].'sslkey.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Private Keys').'</h5>
	</div>
	<div id="showrectab" class="table-responsive">';
		showcert();
	echo '
	</div>
	<div id="detailrectab" style="display:none">
		<div class="row td_font my-4">
			<div class="col-12 col-lg-6 text-center"> 
				<label class="form-label">'.__('Key file of ').'<span id="ddomain">--</span></label>	
				<textarea name="dk" id="dkey" rows="25" readonly="readonly" class="w-100" wrap="off" >--</textarea>
			</div>
			<div class="col-12 col-lg-6 text-center"> 
				<label class="form-label">'.__('Information of Key').'</label>
				<textarea name="dv" id="dvalue" rows="25" readonly="readonly" class="w-100">---</textarea>
			</div>
		</div>
		<div class="text-center">
			<button class="flat-butt" onclick=\'$("#detailrectab").slideUp("slide", "", 1000);\'>'.__('Close Detail').'</button>
		</div>
	</div>
</div>
<div class="row mb-4">
	<div class="col-12 col-lg-6">
		<div class="card soft-card p-4 mb-3 h-100">
			<div class="sai_main_head mb-3">
				<h5 class="d-inline-block">'.__('Upload a Key').'</h5>
			</div>
			<form accept-charset="'.$globals['charset'].'" action="" method="post" enctype="multipart/form-data" id="upload_key" class="form-horizontal" onsubmit="return submitit(this)" name="upload_key" data-donereload=1>
				<label class="form-label" for="selectdom">'.__('Description').'</label>
				<input type="text" name="selectdom" id="selectdom" class="form-control mb-3" value=""/>
				<label class="form-label" for="kpaste">'.__('Paste your Private Key here').'</label>
				<textarea name="kpaste" id="kpaste" class="form-control mb-3"></textarea>
				<span class="form-label d-block mb-4">'.__('OR').'</span>
				<label class="form-label" for="ukey">'.__('Choose the .key file').'</label>
				<div id="filecabinet" class="mb-3">
					<input type=file id="ukey" name="ukey">
				</div>					
				<input type="submit" name="install_key" value="'.__('Upload').'" class="flat-butt" id="instkey"/>
			</form>
		</div>
	</div>
	<div class="col-12 col-lg-6">
		<div class="card soft-card p-4 mb-3 h-100">
			<div class="sai_main_head mb-3">
				<h5 class="d-inline-block">'.__('Generate a Key').'</h5>
			</div>
			<form accept-charset="'.$globals['charset'].'" action="" method="post" class="form-horizontal" id="certkey_add" name="certkey" onsubmit="return submitit(this)" data-donereload=1>
				<label class="form-label" for="selectdom1">'.__('Description').'</label>
				<input type="text" name="selectdom" id="selectdom1" class="form-control mb-3" value=""/>
				<label class="form-label" for="keysize">'.__('Key Size').'</label>
				<select class="form-select mb-3 input" name="keysize" id="keysize">
					<option value="2048">'.__('2,048 bits').'</option>
					<option value="4096">'.__('4,096 bits').'</option>
				</select>
				<input type="submit" name="create_key" value="'.__('Create').'" class="flat-butt d-block" id="submitcertkey"/>				
			</form>
		</div>
	</div>
</div>
<script>
	
// For showing detail
$(".edit").click(function(){
	
	var d = $(this).data();
	
	return submitit(d, {
		handle: function(data){
			if("detailkey" in data){
				// console.log(data);
				$.each(data.detailkey, function(key, value){
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

	softfooter();

}

function showcert(){
	
global $user, $globals, $theme, $softpanel, $WE, $error, $done, $key_list;

	echo '
<table class="table align-middle table-nowrap mb-0 webuzo-table td_font">
	<thead class="sai_head2">
		<tr>
			<th class="align-middle">'.__('HOST').'</th>
			<th class="align-middle" colspan="3">'.__('OPTION').'</th>
		</tr>
	</thead>
	<tbody>';
	
	if(empty($key_list)){
		echo '
		<tr class="text-center">
			<td colspan=3>
				<span>'.__('No keys have been Uploaded or Generated').'</span>
			</td>
		</tr>';					
	}else{
		
		foreach ($key_list as $key => $value){
			
			$ext = get_extension($value);
			
			if($ext == 'key'){
				$file = get_filename($value);
				
				echo '
		<tr id="tr'.$key.'">
			<td>
				<span id="name'.$key.'">'.$file.'</span>
			</td>
			<td width="2%">
				<i class="fas fa-file-alt edit edit-icon text-center me-2" title="Show" id="eid'.$key.'" data-detail_record="'.$file.'"></i>
			</td>
			<td width="2%">
				<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$key.'" onclick="delete_record(this)" data-delete_record="'.$key.'"></i>
			</td>
		</tr>';
		
			}
		}
	}

	echo '
	</tbody>
</table>';
}

?>