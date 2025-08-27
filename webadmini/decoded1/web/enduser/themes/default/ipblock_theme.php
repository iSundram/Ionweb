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

function ipblock_theme(){

global $theme, $globals, $error, $softpanel ,$WE, $done ,$add_list;
	
	softheader(__('IP Block'));

	echo '
<div class="card soft-card p-3 col-12">
	<div class="sai_main_head ">
		<img src="'.$theme['images'].'ip_block.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('IP Block').'</h5>
	</div>	
</div>	
<div class="card soft-card p-4 col-12 mt-4">
	<div class="modal fade" id="add-block-ip" tabindex="-1" aria-labelledby="add-block-ip" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="add-block-ip">'.__('Add Block IP').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-3">
					<form accept-charset="'.$globals['charset'].'" action="" method="post" name="ipblock" id="editform" class="form-horizontal">
						<label for="path" class="form-label">'.__('IP Address/Domain').'</label>
						<span class="d-block mb-1">'.__('NOTE: You can specify the IP Address in the following format').'</span>
						<div>
							<label class="form-label me-2">'.__('Single IP or Domain').' :</label>
							<span class="form-value">'.__('192.168.0.1 or example.com').'</span>
						</div>
						<div>
							<label class="form-label me-2">'.__('IP Range').' :</label>
							<span class="form-value">192.168.0.1 - 192.168.0.50</span>
						</div>
						<div>
							<label class="form-label me-2">'.__('CIDR Format').' :</label>
							<span class="form-value">192.168.0.1/20</span>
						</div>
						<input type="text" id="dip" name="dip" class="form-control mb-3" value="" />
						<center>	
							<input type="submit" class="flat-butt me-2" value="'.__('Add').'" name="add_ip" id="submitip" onclick="return submitit(this)" data-donereload="1" />
							<img id="createimg" src="'.$theme['images'].'progress.gif" style="display:none">
						</center>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- IP table started -->
	<div class="sai_sub_head record-table mb-2 position-relative">
		<input type="button" class="btn btn-danger delete_selected " value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_blockip(this)" disabled style="text-align:right;">
		<button type="button" class="flat-butt float-end" data-bs-toggle="modal" data-bs-target="#add-block-ip">'.__(' Add').'</button>
	</div>
	<div id="showrectab" class="table-responsive">	
		<table class="table align-middle table-nowrap mb-0 webuzo-table" >			
		<thead class="sai_head2">
			<tr>
				<th><input type="checkbox" id="checkall"></th>
				<th class="align-middle">'.__('IP Address').'</th>
				<th class="align-middle">'.__('Starting IP').'</th>
				<th class="align-middle">'.__('Ending IP').'</th>
				<th class="align-middle">'.__('Options').'</th>
			</tr>
					
		</thead>
		<tbody>';

	if(empty($add_list)) {
		echo '
			<tr>
				<td colspan="100" class="text-center">
					<span>'.__('No IP(s) Blocked').'</span>
				</td>
			</tr>';
	}else{

		$i =1;
		foreach ($add_list as $key => $value){	
			
			echo '
			<tr id="tr'.$key.'">
				<td><input type="checkbox" name="check_ip" class="check_ip" value="'.$value.'"></td>	
				<td><span>'.$value.'</span></td>';
				$tmp = cidr_to_iprange($value);
				echo '
				<td><span>'.$tmp[0].'</span></td>
				<td><span>'.$tmp[1].'</span></td>
				<td width="2%" align="center">
					<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$key.'" onclick="delete_record(this)" data-ip="'.$value.'" data-delete="1"></i>
				</td>
			</tr>';
			$i++;	
		}
	}
	
	echo '
		</tbody>
		</table>
	</div><!-- IP table ended -->
</div>

<script>
$(document).ready(function(){
	$("#checkall").change(function(){
		$(".check_ip").prop("checked", $(this).prop("checked"));
	})
	
	$("input:checkbox").change(function(){
		if($(".check_ip:checked").length){
			$("#delete_selected").removeAttr("disabled");
		}else{
			$("#delete_selected").prop("disabled",true);
		}
	});
});

function del_blockip(el){
	var a;
	var jEle = $(el);
	var arr = [];
	
	$("input:checkbox[name=check_ip]:checked").each(function(){
		var ips = $(this).val();
		console.log(ips);
		arr.push(ips);
	});

	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete/Unblock selected IP(s) ?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"ip" : arr.join(),"delete":1};
		console.log(d);
		submitit(d, {done_reload : window.location.href});
	});

	show_message(a);
}
</script>';

	softfooter();
	
}

