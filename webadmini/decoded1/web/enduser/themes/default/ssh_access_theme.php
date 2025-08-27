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

function ssh_access_theme(){

	global $user, $globals, $theme, $softpanel, $WE, $error, $done, $key_list;
	
	softheader(__('SSH Access'));
	
	echo '
<div class="card soft-card p-3 col-12">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'ssh_login.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('SSH Access').'</h5>		
		<a type="button" class="btn flat-butt float-end" href="'.$globals['index'].'act=ssh_import_keys">'.__('Import Key').'</a>
		<a type="button" class="btn flat-butt float-end" href="'.$globals['index'].'act=ssh_generate_keys" style="margin-right: 10px;">'.__('Generate a New Key').'</a>
	</div>
</div>
<div class="card soft-card p-4 col-12 mt-4">
	<!-- Start Convert PPK Modal -->
	<div class="modal fade" id="convert-ppk" tabindex="-1" aria-labelledby="convert-ppk" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="add-block-ip">'.__('Convert private key to PPK format').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<form accept-charset="'.$globals['charset'].'" action="" method="post" name="ipblock" id="editform" class="form-horizontal">
						<label for="path" class="form-label">'.__('Enter the passphrase to unlock the key for conversion').':</label>
						<input type="hidden" id="key" name="key" />
						<input type="password" id="passphrase" name="passphrase" class="form-control mb-3" value="" />
						<center>
							<input type="submit" class="flat-butt me-2" value="'.__('Convert').'" name="ppk_gen" id="submitppk" onclick="return submitit(this)" data-donereload="1" />
						</center>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- End Convert PPK Modal -->
	<!-- Start View Modal -->
	<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="viewModalTitle"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<pre id="modal_content" style="white-space: pre-wrap;"></pre>
				</div>
				<div class="modal-footer">
					<button type="button" class="flat-butt" data-bs-dismiss="modal">'.__('Close').'</button>
				</div>				
			</div>
		</div>
	</div>
	<!-- End View Modal -->
	<h3>'.__('Key Descriptions').'</h3>
	<p class="ssh_desc">'.__('SSH keys are a matching set of cryptographic keys which can be used for authentication. Each set contains a public and a private key. The public key can be shared freely without concern, while the private key must be vigilantly guarded and never exposed to anyone.').'</p>
	<p class="ssh_desc">'.__('**You can download the private/public keys here and import into PuTTY (or other SSH clients) if you prefer using something else for SSH connections. Or, if you have been using PuTTY, you can import the public/private keys by clicking on Import Key.').'</p>
	<div id="showpublickey">
		<h3>'.__('Public Keys').'</h3>
		<table class="table align-middle table-nowrap mb-0 webuzo-table" >			
			<thead class="sai_head2">
				<tr>
					<th class="align-middle">'.__('Name').'</th>
					<th class="align-middle">'.__('Authorization Status').'</th>
					<th class="align-middle" width="12%"></th>
					<th class="align-middle" colspan="3">'.__('Actions').'</th>
				</tr>
						
			</thead>
			<tbody>';
			
		if(empty($key_list['publickeys'])){
			echo '<tr><td colspan="100"><center><span>'.__('No public keys found').' !</span></center></td></tr>';
		}else{
			
			$ssh_dir = $user['homedir'].'/.ssh/';
			$auth_file = $WE->getsshkeyContent('authorized_keys');
			$auth = __('Not Authorized');
			$ssh_authentication = __('Authorize');
			
			foreach ($key_list['publickeys'] as $value) {
				$file = $WE->getsshkeyContent($value);
				if (strpos(base64_decode($auth_file['key_content']), base64_decode($file['key_content'])) !== false){
					$auth = __('Authorized');
					$ssh_authentication = __('Deauthorize');
				}else{
					$auth = __('Not Authorized');
					$ssh_authentication = __('Authorize');
				} 
			echo '
				<tr id="tr'.$value.'">
					<td>'.$value.'</td>
					<td id="td_value_auth">'.$auth.'</td>
					<td>
						<a href="javascript:void(0)" data-ssh_key_auth=1 data-ssh_keyname="'.$value.'" data-ssh_auth="'.$ssh_authentication.'" onclick="return this_submitit(this)" data-donereload=1 class="text-decoration-none">
						<i class="fas fa-wrench" title="'.$ssh_authentication.'"></i>&nbsp;'.$ssh_authentication.'</a>
					</td>
					<td width="2%">
						<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$value.'" onclick="delete_record(this)" data-delete_ssh_key="'.$value.'" ></i>
					</td>
					<td width="2%">
						<a class="viewKey" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#viewModal" data-keyname="'.$value.'">
						<i class="fas fa-file-alt" title="'.__('View').'"></i></a>
					</td>
					<td width="2%">
						<a href="'.$globals['index'].'act=ssh_access&download='.$value.'" title="'.__('Download').'">
						<i class="fas fa-download delete edit-icon"></i></a>
					</td>
				</tr>';
			}
		}
		
		echo '
			</tbody>
		</table>
	</div>
	</br>
	<h3>'.__('Private Keys').'</h3>
	<div id="showprivatekey">	
		<table class="table align-middle table-nowrap mb-0 webuzo-table" >			
			<thead class="sai_head2">
				<tr>
					<th class="align-middle">'.__('Name').'</th>
					<th class="align-middle" width="10%"></th>
					<th class="align-middle" colspan="3">'.__('Actions').'</th>
				</tr>
						
			</thead>
			<tbody>';

		if(empty($key_list['privatekeys'])){
			echo '<tr><td colspan=100><center><span>'.__('No private keys found').' !</span></center></td></tr>';
		}else{
			
			foreach ($key_list['privatekeys'] as $value) {
				
			echo '
				<tr id="tr'.$value.'">
					<td>'.$value.'</td>
					<td>
						<a href="javascript:void(0)" class="convetppk text-decoration-none" data-bs-toggle="modal" data-bs-target="#convert-ppk" data-keyname="'.$value.'" title="'.__('Convert private key to PPK format').'">
						<i class="fas fa-exchange-alt"></i>&nbsp;'.__('Convert').'</a>
					</td>
					<td width="2%">
						<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$value.'" onclick="delete_record(this)" data-delete_ssh_key="'.$value.'"></i>
					</td>
					<td width="2%">
						<a class="viewKey" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#viewModal" data-keyname="'.$value.'" title="'.__('View').'">
						<i class="fas fa-file-alt"></i></a>
					</td>
					<td width="2%">
						<a href="'.$globals['index'].'act=ssh_access&download='.$value.'" title="'.__('Download').'">
						<i class="fas fa-download delete edit-icon"></i></a>
					</td>
				</tr>';
			}
		}
		
		echo '
			</tbody>
		</table>	
	</div>
</div>
<script language="javascript" type="text/javascript"><!-- // --><![CDATA[>

function this_submitit(jEle){
	var d = $(jEle).data();
	
	return submitit(d, {
		done_reload: String(window.location.href)
	});
}

$(document).ready(function(){
	
	$(document).on("click", ".convetppk", function() {
		 var keyname = $(this).data("keyname");
		 $("#key").val(keyname);
	});
	
	$(document).on("click", ".viewKey", function() {	
		var keyname = $(this).data("keyname");
		$.ajax({				
			type: "POST",
			dataType : "json",
			url: window.location+"&view_key=1&api=json&keyname="+keyname,				
			// checking for error
			success: function(data){
				if(data["view_content"]){
					$("#modal_content").html(data["view_content"].key_content);
					$("#viewModalTitle").html(data["view_content"].modal_title);
				}
			},				
			error: function(request,error) {
				var a = show_message_r("'.__js('Error').'", "'.__js('Oops there was an error while connecting to the $0 Server $1', ['<strong>','</strong>']).'");
				a.alert = "alert-danger";
				show_message(a);				
			}
		});
	});
});
// ]]></script>';
	softfooter();
}

