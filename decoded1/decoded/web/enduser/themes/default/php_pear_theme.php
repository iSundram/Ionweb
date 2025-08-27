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

function php_pear_theme(){

global $theme, $globals, $user, $langs, $error, $system_pear, $user_pear, $done, $WE;

	softheader(__('PHP PEAR Packages'));
	
	echo '
<div class="card soft-card p-4 col-12">
	<div class="sai_main_head mb-5">
		<img src="'.$theme['images'].'php_ext.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('PHP PEAR Packages').'</h5>
		<div class="sai_notice text-center float-end">
			<strong>'.__('Module Install Path').'</strong> '.$user_pear['dir'].'
		</div>
	</div>
	
	<div class="modal fade" id="modInstallation_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modInstallation" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-scrollable modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="modInstallation">'.__('Processing').' - <b> <span id="modName"></span></b> - <span id="modAction"></span></h5>
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
	<div class="section">
        <h4>'.__('Using Your PEAR Packages').'</h4>
		<p>'.__('You will need to add "$0 $1 $2" to the include path. You can do this by adding the following code to your script:', [$user_pear['dir'], '<strong>', '</strong>']).'</p>
		<p>
			<code>ini_set("include_path", "'.$user_pear['dir'].':" . ini_get("include_path")  );</code>
		</p>
    </div>
	<div class="section">
		<div class="row">
			<div class="col-12 col-lg-4">
				<form accept-charset="'.$globals['charset'].'" action="'.$globals['ind'].'" method="get" name="searchpear_form" id="searchpear_form" class="form-horizontal">
					<div class="input-group mb-3">
					  <input type="hidden" name="act" value="pear_module">
					  <input type="hidden" name="type" value="q">
					  <input type="text" class="form-control " id="searchpear" name="q" size="15" placeholder="'.__('Find PEAR Packages').'">&nbsp;&nbsp;
					  <input class="btn btn-primary" type="submit" id="findAMod" value="'.__('Go').'">
					</div>
				</form>
			</div>
			<div class="col-12 col-lg-4">
				<span>'.__('or').'</span>
				<a class="btn flat-butt text-decoration-none" href="'.$globals['index'].'act=pear_module&type=all" id="showMods">'.__('Show Available Modules').'</a>
			</div>
			<div class="col-12 col-lg-4">
				<form accept-charset="'.$globals['charset'].'" action="" method="post" name="installpear_form" id="installpear_form" class="form-horizontal" onsubmit="return submitinspear(this)" data-donereload=1>
					<div class="input-group mb-3">
					  <input type="text" class="form-control" name="mod_name" size="15" placeholder="'.__('Install a PEAR package').'">&nbsp;&nbsp;
					  <input class="btn flat-butt" type="submit" id="install" value="'.__('Install Now').'">
					</div>
				</form>
			</div>
		</div>
    </div>
	<div class="section">
        <h4>'.__('Installed PEAR Packages').'</h4>
		<table class="table align-middle table-nowrap mb-0 webuzo-table" >			
			<thead class="sai_head2" style="background-color: #EFEFEF;">
				<tr>
					<th class="align-middle">'.__('Module Name').'</th>
					<th class="align-middle">'.__('Version').'</th>
					<th class="align-middle text-center" colspan="4">'.__('Actions').'</th>
				</tr>		
			</thead>
			<tbody id="ipear_list">';
			if(empty($user_pear['data'])){
				
				echo '
				<tr><td colspan="6" class="text-center">'.__('No module installed').'</td></tr>';
				
			}else{
				foreach($user_pear['data'] as $key => $value){
					echo '
				<tr id="tr'.$value['mod_name'].'">
					<td>'.$value['mod_name'].'</td>
					<td>'.$value['mod_ver'].' ('.$value['mod_state'].')</td>
					<td data-action="upgrade" data-mod_name="'.$value['mod_name'].'-'.$value['mod_ver'].'" onclick="install_pear(this)" style="cursor:pointer"><i class="fas fa-upload"></i> '.__('Update').'</td>
					<td data-action="reinstall" data-mod_name="'.$value['mod_name'].'-'.$value['mod_ver'].'" onclick="install_pear(this)" style="cursor:pointer"><i class="fab fa-rev"></i> '.__('Reinstall').'</a></td>
					<td data-action="uninstall" data-mod_name="'.$value['mod_name'].'-'.$value['mod_ver'].'" onclick="install_pear(this)" data-doneremoverow="'.$value['mod_name'].'" style="cursor:pointer"><i class="fas fa-trash-alt"></i> '.__('Uninstall').'</a></td>
					<td><a href="https://pear.php.net/package/'.$value['mod_name'].'/docs" target="_blank" class="btn text-decoration-none"><i class="fas fa-book"></i> '.__('Show Docs').'</a></td>
				</tr>
				';
				}
			}
			echo '
			</tbody>
		</table>
		
		<button class="btn flat-butt mt-4" id="showSysMods">'.__('Show System Installed Modules').'</button>
		
		<table class="table align-middle table-nowrap mb-0 webuzo-table mt-4" id="sys_mods" style="display:none">			
			<thead class="sai_head2" style="background-color: #EFEFEF;">
				<tr>
					<th class="align-middle">'.__('Module Name').'</th>
					<th class="align-middle">'.__('Version').'</th>
					<th class="align-middle">'.__('Actions').'</th>
				</tr>			
			</thead>
			<tbody id="ipear_list">';
			if(empty($system_pear['data'])){
				
				echo '
				<tr><td colspan="3" class="text-center">'.__('No module installed').'</td></tr>';
				
			}else{
				
				foreach($system_pear['data'] as $key => $value){
					echo '
				<tr>
					<td>'.$value['mod_name'].'</td>
					<td>'.$value['mod_ver'].' ('.$value['mod_state'].')</td>
					<td><a href="https://pear.php.net/package/'.$value['mod_name'].'/docs" target="_blank"  class="btn text-decoration-none"><i class="fas fa-book"></i> '.__('Show Docs').'</a></td>
				</tr>
				';
				}
				
			}
			echo '
			</tbody>
		</table>
	</div>
</div>
<script>

$("#showSysMods").click(function(){
	$("#sys_mods").toggle("slow", "swing");
});

function submitinspear(ele){
	var jEle = $(ele);
	var da = jEle.serializeArray();
	da.push({name : "action", value : "install"});
	
	var d = {};
	$.each(da, function(key, value){
		d[value["name"]] = value["value"];
	});
	
	if(!d.mod_name){
		return false;
	}
	
	var dd = jEle.data();
	d = {...d, ...dd};
	
	action_pear(d);
	return false;
}

function install_pear(ele){
	var jEle = $(ele);
	var d = jEle.data();
	action_pear(d);
}

function action_pear(d){
	
	var a = show_message_r("'.__js('Warning').'", "'.__js('Do you want to ').'"+""+d.action+" PEAR module <b>"+d.mod_name+"</b>");
	a.alert = "alert-warning";
	a.confirm.push(function(){
		
		$("#modIntallLog").hide();
		var myModalEl = $("#modInstallation_modal");
		var modalP = bootstrap.Modal.getOrCreateInstance(myModalEl[0]);
		$("#modInstallation").html("'.__js('Processing').'"+" "+d.action+" PEAR module <b>"+d.mod_name+"</b>");
		modalP.show();
		$(".pearProcess").show();
		myModalEl.find(".modal-header .btn-close").attr("disabled","disabled")
		myModalEl.find(".modal-footer .btn").attr("disabled","disabled");

		$.ajax({
			type: "POST",
			url: window.location.toString()+"&api=json",
			data: d,
			dataType: "json",
			success: function(data){
				$(".pearProcess").hide();
				if("done" in data && "log" in data.done){
					$("#modIntallLog").html(data.done.log).show();
					if("doneremoverow" in d){
						myModalEl.attr("data-doneremoverow", d.doneremoverow);
					}
					
					if("donereload" in d){
						myModalEl.attr("data-donereload", 1);
					}
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

$(document).on("hidden.bs.modal", "#modInstallation_modal", function(){
	var d = $(this).data();
	if("doneremoverow" in d){
		$("#tr"+d.doneremoverow).remove();
	}
	
	if("donereload" in d){
		location.reload();
	}
	
	$(this).removeAttr("data-donereload");
	$(this).removeAttr("data-doneremoverow");
});
</script>';
	
	softfooter();

}

?>