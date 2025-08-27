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

function change_domain_ip_theme(){

global $user, $globals, $theme, $error, $saved, $domains;
	
	softheader(__('Change Domain IP'));

	echo '
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="fas fa-th-list me-2"></i>'.__('Change Domain IP').'
		<span class="search_btn float-end">
			<a href="javascript:void(0);" class="text-dark" data-bs-toggle="collapse" data-bs-target="#search_queue" aria-expanded="true" aria-controls="search_queue" title="'.__('Search').'"><i class="fas fa-search"></i></a>
		</span>
	</div>
	<div class="mt-2" style="background-color:#e9ecef;">
		<div class="collapse '.(!empty(optREQ('user_search')) || !empty(optREQ('dom_search')) ? 'show' : '').'" id="search_queue">
			<form accept-charset="'.$globals['charset'].'" name="search" method="post" action=""; class="form-horizontal" >
			<div class="row p-3 col-md-12 d-flex">
				<div class="col-12 col-md-6">
					<label class="sai_head">'.__('Search By Domain Name').'</label>
					<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select domain').'" s2-ajaxurl="'.$globals['index'].'act=domains&api=json" s2-query="dom_search" s2-data-key="domains" s2-data-subkey="domain" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" name="dom_search" id="dom_search">
						<option value="'.optREQ('dom_search').'" selected="selected">'.optREQ('dom_search').'</option>
					</select>
				</div>
				<div class="col-12 col-md-6">
					<label class="sai_head">'.__('Search By User Name').'</label><br/>
					<select class="form-select ms-1 make-select2" s2-placeholder="'.__('Select User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'All', 'id' => 'all', 'value' => 'all']])).'" style="width: 100%" id="user_search" name="user_search">
						<option value="'.optREQ('user_search').'" selected="selected">'.optREQ('user_search').'</option>
					</select>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>

<div class="soft-smbox p-4 mt-4">';
	
	error_handle($error, "100%");
	
	echo '	
	<div class="row">
		<div class="col-12 col-sm-4">
			<span class="form-label">'.__('Total Domains').': </span>
			<span class="tot_count">'.$globals['total_domains'].'</span>
		</div>
		<div class="col-12 col-sm-8">
			<nav aria-label="Page navigation example">
				<ul class="pagination pager myPager justify-content-end">
				</ul>
			</nav>
		</div>
	</div>';
	page_links();
	
	echo '
	<div class="table-responsive">
		<table border="0" cellpadding="8" cellspacing="1" class="table table-hover-moz webuzo-table td_font">
			<thead class="sai_head2">
				<tr>
					<th width="5%">#</th>
					<th width="15%">'.__('Domain Name').'</th>
					<th width="10%">'.__( 'User Name').'</th>
					<th width="10%">'.__('Owner').'</th>
					<th width="20%">'.__('IP Address').'</th>
					<th width="20%">'.__('IPv6').'</th>
					<th width="10%">'.__('Type').'</th>
					<th colspan="2" style="text-align:right">Option</th>
				</tr>
			</thead>
			<tbody id="dom_list">';
			
			if(!empty($domains)){
				foreach ($domains as $key => $value){
					echo'
				<tr id="tr'.$key.'">
					<td><span>' . $value['domid'] . '</span></td>
					<td>
						<span>' . $value['domain'] . '</span>
						<input type="hidden" name="domain" value="'.$value['domain'].'" />
					</td>
					<td>
						<span>' . $value['user'] . '</span>
						<input type="hidden" name="user" value="'.$value['user'].'" />
					</td>
					<td><span>'.$value['owner'].'</span></td>
					<td>
						<span id="sel'.$key.'" class="exist_ip">'.$value['ip'].'</span>
						<div class="hideselect" style="display:none;">	
							<select class="form-select ms-1" s2-placeholder="Select IP" s2-ajaxurl="'.$globals['index'].'act=ips&api=json&free=1&or_user='.$value['user'].'" s2-query="ip" s2-data-key="ips" s2-data-subkey="ip" style="width: 100%;" name="ip" id="s2ip'.$key.'">
								<option value="'.$value['ip'].'" selected="selected">'.$value['ip'].'</option>
							</select>
						</div>
						<input type="hidden" name="edit_record" value="'.$key.'" />
					</td>
					<td>
						<span id="ipv6'.$key.'" class="exist_ip">'.$value['ipv6'].'</span>
						<div class="hideselect" style="display:none;">	
							<select class="form-select ms-1" s2-placeholder="Select IP" s2-ajaxurl="'.$globals['index'].'act=ips&api=json&free=1&or_user='.$value['user'].'&type=6" s2-query="ip" s2-data-key="ips" s2-data-subkey="ip" style="width: 100%;" name="ipv6" id="s2ipv6'.$key.'">
								<option value="'.$value['ipv6'].'" selected="selected">'.$value['ipv6'].'</option>
							</select>
						</div>
					</td>
					<td><span>'.ucfirst($value['type']).'</span></td>
					<td width="2%">
						<i class="fas fa-undo-alt cancel cancel-icon" title="'.__('Cancel').'" id="cid'.$key.'" style="display:none;"></i>
					</td>
					<td width="2%">
						<i name="editbtn" class="fas fa-pencil-alt edit edit-icon fa-edit" id="eid'.$key.'" title="'.__('Edit').'"></i>
					</td>
				</tr>';			
				}
			}else{
				echo '
				<tr><td colspan=8><h3 style="text-align: center">No Record Found</h3></td></tr>';
				
			}
			
			echo '
			</tbody>
		</table>
	</div>';
	
	page_links();
	
	echo '
	<nav aria-label="Page navigation">
		<ul class="pagination pager myPager justify-content-end">
		</ul>
	</nav>
</div>

<script>
$(document).ready(function () {
	$("#user_search").on("select2:select", function(){	
		user = $("#user_search option:selected").val();
		if(user == "all"){
			window.location = "'.$globals['index'].'act=change_domain_ip";
		}else{
			window.location = "'.$globals['index'].'act=change_domain_ip&user_search="+user;
		}		
	});
	
	$("#dom_search").on("select2:select", function(){
		var domain_selected = $("#dom_search option:selected").val();
		if(domain_selected == "all"){
			window.location = "'.$globals['index'].'act=change_domain_ip";		
		}else{
			window.location = "'.$globals['index'].'act=change_domain_ip&dom_search="+domain_selected;		
		}
	});
});

// For cancel
$(document).on("click", ".cancel", function() {
	var id = $(this).attr("id");
	id = id.substr(3);
	$("#cid"+id).hide();
	$("#eid"+id).removeClass("fa-save").addClass("fa-edit");
	$("#tr"+id).find("span").show();
	$("#tr"+id).find(".hideselect").hide();
});

// For editing record
$(document).on("click", ".edit", function() {
	var id = $(this).attr("id");
	id = id.substr(3);
	$("#cid"+id).show();
	
	make_select2($("#s2ip"+id));
	make_select2($("#s2ipv6"+id));
	
	// Submit the form
	if($("#eid"+id).hasClass("fa-save")){
		
		var d = $("#tr"+id).find("input, select").serialize();
		
		submitit(d, {
			done: function(){
				var tr = $("#tr"+id);
				tr.find(".cancel").click();// Revert showing the inputs
				
				tr.find("select").each(function(){
					var jE = $(this);
					jE.closest("td").find("span").html(jE.val());
				});
			},
			sm_done_onclose: function(){			
				$("#tr"+id).find("span").show();
				$("#tr"+id).find(".hideselect").hide();		
				location.reload();
			}
		});
		
	}else{
		$("#eid"+id).addClass("fa-save").removeClass("fa-edit");
		$("#tr"+id).find(".exist_ip").hide();
		$("#tr"+id).find(".hideselect").show();
	}
});

</script>';

	softfooter();
	
}
