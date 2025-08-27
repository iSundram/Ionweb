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

function pear_module_theme(){

global $theme, $globals, $user, $langs, $error, $done, $WE, $pearlist;

	softheader(__('PHP PEAR Packages'));
	
	echo '
<div class="card soft-card p-4 col-12">
	<div class="sai_main_head mb-5">
		<img src="'.$theme['images'].'php_ext.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('PHP PEAR Packages').'</h5>
	</div>
	<div class="modal fade" id="modInstallation_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modInstallation" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="modInstallation"></h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div>
		  <div class="modal-body">
			<div class="text-center pearProcess">
				<div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
				  <span class="visually-hidden">'.__('Loading...').'</span>
				</div>
			</div>
			<textarea class="form-control log" readonly="readonly" style="height:150px; width:100%; overflow:auto; resize: none;display:none" id="modIntallLog">
			
			</textarea>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn flat-butt" data-bs-dismiss="modal">'.__('Ok').'</button>
		  </div>
		</div>
	  </div>
	</div>
	<div class="row my-3">
		<div class="col-12 col-md-4 mb-2">
			<form action="" method="POST" name="search">
				<label class="form-label me-1">'.__('Available for installation').'</label>
				<input type="hidden" name="type" value="q">
				<input type="text" class="search_val" name="q" value="'.optREQ('q').'" placeholder="'.__('search').'">
				<input type="submit" name="submit" style="display:none">
			</form>
		</div>
	</div>';
	
	page_links();
	
	echo'
	<table class="table align-middle table-nowrap mb-0 webuzo-table" >			
		<thead class="sai_head2" style="background-color: #EFEFEF;">
			<tr>
				<th class="align-middle">'.__('Module Name').'</th>
				<th class="align-middle">'.__('Description').'</th>
				<th class="align-middle text-center" colspan="2">'.__('Actions').'</th>
			</tr>			
		</thead>
		<tbody>';
			if(!empty($pearlist)){
				
				foreach($pearlist as $k => $v){
			echo '
			<tr>
				<td>'.$v['mod_name'].' ( '.$v['mod_latest_version'].')</td>
				<td>'.$v['mod_desc'].'</td>
				<td width="10%" onclick="install_pear(this)" data-mod_name="'.$v['mod_name'].'-'.$v['mod_latest_version'].'" data-action="install" style="cursor:pointer"><i class="fas fa-download"></i>&nbsp;'.__('Install').'</td>
				<td width="15%"><a href="https://pear.php.net/package/'.$v['mod_name'].'/docs" target="_blank" class="btn"><i class="fas fa-book"></i> &nbsp;'.__('Show Docs').'</a></td>
			</tr>';
				
				}
				
			}else{
				echo '
			<tr>
				<td colspan="3" class="text-center">'.__('No package available').'</td>
			</tr>';
			
			}
			
			echo '
		</tbody>
	</table>
</div>

<script>

function install_pear(ele){
	
	$("#modIntallLog").hide();
	var jEle = $(ele);
	var d = jEle.data();
	action_pear(d);
}

function action_pear(d){
	
	var a = show_message_r("'.__js('Warning').'", "'.__js('Do you want to install ').'"+" <b>"+d.mod_name+"</b>");
	a.alert = "alert-warning";
	a.confirm.push(function(){
		
		$("#modIntallLog").hide();
		var myModalEl = $("#modInstallation_modal");
		var modalP = bootstrap.Modal.getOrCreateInstance(myModalEl[0]);
		$("#modInstallation").html("'.__js('Installing PEAR module').'"+" <b>"+d.mod_name+"</b>");
		modalP.show();
		$(".pearProcess").show();
		myModalEl.find(".modal-header .btn-close").attr("disabled","disabled")
		myModalEl.find(".modal-footer .btn").attr("disabled","disabled");

		$.ajax({
			type: "POST",
			url: "'.$globals['ind'].'act=php_pear&api=json",
			data: d,
			dataType: "json",
			success: function(data){
				$(".pearProcess").hide();
				if("done" in data && "log" in data.done){
					$("#modIntallLog").html(data.done.log).show();
				}
				
				// Are there any errors ?
				if(typeof(data["error"]) != "undefined"){
					let str = obj_join("\n", data["error"]);
					$("#modIntallLog").html(str).show();
				}
			},
			error: function(){
				$(".pearProcess").hide();
				$("#modIntallLog").html("'.__js('Oops there was an error while connecting to the $0 Server $1', ['<strong>', '</strong>']).'").show();
			},
			complete: function(){
				myModalEl.find(".modal-header .btn-close").removeAttr("disabled");
				myModalEl.find(".modal-footer .btn").removeAttr("disabled");
			}
		});
		
	});
	
	show_message(a);
}
</script>';
	
}