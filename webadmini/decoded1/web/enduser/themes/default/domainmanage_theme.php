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

function domainmanage_theme(){
	
global $theme, $globals, $WE, $error, $softpanel, $done, $user, $domains_list, $ips, $primary_domain;
	
	softheader(__('Manage Domain'));
	
	echo '
<div class="card col-12 soft-card p-3 mb-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'domains.png" class="webu_head_img me-2"/> 
		<h5 class="d-inline-block">'.__('Manage Domain').'</h5>
	</div>
</div>
<div class="card col-12 soft-card p-4">
	<div class="sai_sub_head record-table mb-2 position-relative">
		<input type="button" class="btn btn-danger delete_selected " value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="del_dom(this)" disabled>
		'.(empty($globals['DISABLE_DOMAINADD']) ? '<a href="'.$globals['ind'].'act=domainadd" class="text-decoration-none float-end"><button class="flat-butt">'.__('Add New').'</button></a>' : '').'
	</div>
	
	<div class="table-responsive">
		<table class="table align-middle table-nowrap mb-0 webuzo-table">
			<thead class="sai_head2">
				<tr>
					<th><input type="checkbox" id="checkall"></th>
					<th class="align-middle" width="30%">'.__('Domain').'</th>
					<th class="align-middle" width="40%">'.__('Path').'</th>
					<th class="align-middle" width="10%">'.__('Type').'</th>
					<th class="align-middle" width="15%">'.__('Force HTTPS').'</th>
					<th class="align-middle" width="20%">'.__('IP Address').'</th>
					<th class="align-middle" width="10%" colspan="2">'.__('Options').'</th>
				</tr>
			</thead>
			<tbody>';
			
			foreach ($domains_list as $key => $value){
				
				$editAct = 'domainedit';
				
				$ipv6 = $value['ipv6'];
				$ip = domain_ips($ips, $value['ip'], $ipv6);	
				echo '
				<tr id="tr'.$key.'">
					<td><input type="checkbox" name="check_dom" value="'.$key.'" '.($key == $primary_domain ? 'disabled' : ' class="check_dom"').'></td>
					<td class="endurl"><a target="_blank" href="http'.(!empty($value['force_https']) ? 's' : '').'://' . $key . '"> '.$key.'</a></td>
					<td>'.$value['path'].'</td>
					<td>'.ucfirst(domain_type($WE->user, $key)).(!empty($value['wildcard']) ? '<br><span class="sai_info">'.__('Wildcard').'</span>' : '').'</td>
					<td style="text-align: center">
						<label class="switch">
							<input type="checkbox" class="checkbox" data-force_https="1" data-donereload="1" id="edi'.$key.'" value="'.$key.'" 
							data-do="'.(!empty($value['force_https']) ? 0 : 1).'" '.(!empty($value['force_https']) ? 'checked' : '').' data-domain='.$key.' onclick="return force_https_toggle(this)">
							<span class="slider"></span>
						</label>';
						echo'
					</td>
					<td style="font-size:12px">'.$ip.(!empty($ipv6) ? '<br> '.$ipv6 : '').'</td>
					<td width="1%">
						<a href="'.$globals['ind'].'act='.$editAct.'&domid='.$key.'" title="'.__('Edit').'">
							<i class="fas fa-pencil-alt edit-icon"></i>
						</a>
					</td>';
					
				if ($key != $primary_domain){
					echo '
					<td  width="1%">
						<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$key.'" onclick="delete_record(this)" data-delete="'.$key.'"></i>
					</td>';
				}else{
					echo '<td width="1%" class="text-center">-</td>';
				}
				
				echo '</tr>';
			}
			
			echo '
			</tbody>
		</table>
	</div>
</div>

<script>	
function force_https_toggle(ele){
	var jEle = $(ele);
	var d = jEle.data();
	
	// console.log(d);
	var a, lan;
	if(d.do == "0"){
		lan = "'.__js('Are you sure you want to $0 Disable $1 Force HTTPS redirect for domain ', ['<b>', '</b>']).'<b>"+d.domain+"</b>";
	}else{
		lan = "'.__js('Are you sure you want to $0 Enable $1 Force HTTPS redirect for domain ', ['<b>', '</b>']).'<b>"+d.domain+"</b>";
	}
	
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
	
	var no = function(){
		var status = d.do ? false : true;
		jEle.prop("checked", status);
	}
	
	// Submit the data
	a.confirm.push(function(){
		submitit(d, {
			done_reload : window.location.href, 
			error: no
		});
	});
	
	// If user closes or chooses no
	a.no.push(no);	
	a.onclose.push(no);
	
	//console.log(a);//return;
	show_message(a);
	
}

$(document).ready(function(){
	$("#checkall").change(function(){
		$(".check_dom").prop("checked", $(this).prop("checked"));
	});
	
	$("input:checkbox").change(function(){
		if($(".check_dom:checked").length){
			$("#delete_selected").removeAttr("disabled");
		}else{
			$("#delete_selected").prop("disabled",true);
		}
	});
});

function del_dom(el){
	var a;
	var jEle = $(el);
	var arr = [];
	
	$("input:checkbox[name=check_dom]:checked").each(function(){
		var dom = $(this).val();
		arr.push(dom);
		console.log(dom);
	});
	
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete selected Domain(s) ?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"delete" : arr.join()};
		submitit(d,{done_reload : window.location.href });
	});
	
	show_message(a);
}
</script>';
	
	softfooter();

}