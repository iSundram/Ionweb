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

	global $theme, $globals, $error, $softpanel ,$done ,$add_list;
	
	softheader(__('IP Block'));
	
	echo '
<div class="soft-smbox p-2">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'ip_block.png"  alt="" />'.__('IP Block').'
	</div>
</div>
<div class="soft-smbox p-4 mt-4">';
	
	error_handle($error);
	
	echo '
	<form accept-charset="'.$globals['charset'].'" action="" method="post" name="ipblock" id="editform" class="form-horizontal" onsubmit="return submitit(this)" data-donereload=1>
		<div class="row">
			<div class="col-12 col-md-4">	
				<label class="sai_head" style="font-size:16px" for="dip">'.__('IP Address/Domain').'</label>
				<span class="sai_exp">
					<span>'.__('You can specify the IP Address in the following format').'</span><br>
					<span class="sai_head">'.__('Single IP or Domain').':</span>
					<span> 192.168.0.1 or example.com</span><br/>
					<span class="sai_head">'.__('IP Range').':</span>
					<span> 192.168.0.1 - 192.168.0.50</span><br/>
					<span class="sai_head">'.__('CIDR Format').':</span>
					<span> 192.168.0.1/20</span><br/>
				</span>
			</div>
			<div class="col-12 col-md-8">
				<div class="d-flex">
					<div class="flex-grow-1 px-2">
						<input type="text" name="dip" id="dip" class="form-control"/>
					</div>
					<div class="flex-grow-1 px-2">
						<input type="submit"  class="btn btn-primary" style="cursor:pointer" value="'.__('Add').'" name="add_ip" class="btn btn-primary" id="submitip"/>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div class="sai_sub_head my-4">'.__('IP addresses being blocked:').'</div>
	<div class="d-flex mb-3">
		<input type="button" class="btn btn-primary" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="delete_ips(this)" style="float: left;" disabled>
	</div>
	<div id="showrectab" class="table-responsive">';
	
	showip();
	
	echo '
	</div>
</div>';

	softfooter();
	
}

function showip(){

	global $globals, $softpanel, $error, $dns_list, $domain_name, $theme, $add_list;
	
	// printing from file
	$add_list = $softpanel->readipblock();	

	echo '
	<table border="0" cellpadding="8" cellspacing="1"  class="table webuzo-table td_font">
	<thead>
		<tr>
			<th class="align-middle"><input type="checkbox" id="checkAll"></th>
			<th width="30%">'.__('IP Address').'</th>
			<th width="30%">'.__('Starting IP').'</th>
			<th width="30%">'.__('Ending IP').'</th>
			<th class="text-end">'.__('Options').'</th>				
		</tr>
	</thead>
	<tbody>';					

	if(empty($add_list)) {
		echo '<tr class="text-center"><td colspan=4><span>'.__('No IP(s) Blocked').'</span></td></<tr>';
	}else{

		$i =1;
		foreach ($add_list as $key => $value){	
			echo'
			<tr id="tr'.$key.'">
				<td>
					<input type="checkbox" name="check_ips" class="check_ips" value="'.$key.'">
				</td>
				<td><span>'.$value.'</span></td>';
				$tmp = cidr_to_iprange($value);
				echo'
				<td><span>'.$tmp[0].'</span></td>
				<td><span>'.$tmp[1].'</span></td>
				<td class="text-end">
					<i class="fas fa-trash delete delete-icon"  title="Delete" id="did'.$key.'"  src="' . $theme['images'] . 'remove.gif" data-delete="'.$key.'" onclick="delete_record(this)"/></i>
				</td>
			</tr>';
			$i++;			
		}
	}
	
	echo '</tbody>
</table>
<script>
$("#checkAll").change(function () {
	$(".check_ips").prop("checked", $(this).prop("checked"));
});

$("input:checkbox").change(function() {
	if($(".check_ips:checked").length){
		$("#delete_selected").removeAttr("disabled");
	}else{
		$("#delete_selected").prop("disabled", true);
	}
});

function delete_ips(el){
	
	var a;
	var jEle = $(el);
	var arr = [];
	
	$("input:checkbox[name=check_ips]:checked").each(function(){
		var ips = $(this).val();
		arr.push(ips);
	});

	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete this selected IP(s) ?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"delete" : arr.join()};
		submitit(d, {done_reload : window.location.href});
	});
	show_message(a);
}
	
</script>';		
}

