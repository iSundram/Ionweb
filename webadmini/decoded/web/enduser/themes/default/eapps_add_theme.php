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

function eapps_add_theme(){

global $theme, $globals, $error, $WE, $domains_list, $edit_app, $eapps_type, $free_port;

	softheader(__('Application Manager'));
	
	error_handle($error);
	
	echo '

<div class="card soft-card p-3 col-12 col-md-10 col-lg-8 mx-auto" >
	<div class="sai_main_head ">
		<i class="fa fa-th-large fa-2x webu_head_img me-2"></i>
		<h5 class="d-inline-block">'.__('Application Manager').'</h5>
	</div>
</div>
<div class="card soft-card p-4 col-12 col-md-10 col-lg-8 mx-auto mt-4" >
	<div class="card soft-card p-4 shadow-none">
	<!--tabs started-->
	<ul class="nav nav-tabs mb-3 webuzo-tabs" id="pills-tab" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active heading_a" id="self_head" data-bs-toggle="tab" data-bs-target="#system_tab" type="button" role="tab" aria-controls="system_tab" aria-selected="true">'.__('Self Managed').'</button>
		</li>';
		if(is_app_installed('passenger')){
			echo '
		<li class="nav-item" role="presentation">
			<button class="nav-link" href="#passenger_tab" id="passenger_head" data-bs-toggle="tab" data-bs-target="#system_tab" type="button" role="tab" aria-controls="system_tab" aria-selected="false">'.__('Passenger').'</button>
		</li>';
		}
		echo '
	</ul>
	<div class="tab-content" id="pills-tabContent">
		<div class="tab-pane fade show active" id="system_tab" role="tabpanel" aria-labelledby="system_tab">
			<div class="sai_main_head mt-3 mb-4">
				<div class="alert alert-warning binary" style="display:none"></div>
				<div class="row">
					<label class="col-md-2 mb-3">'.__('Port ').'<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Port on which application run.').'"></i></label>
					<div class="input-group col-md-8" id="ports_div" style="width: 50%;">
						<span class="input-group-text" style="height: 70%;">'.(!empty($edit_app['port']) ? $edit_app['port'] : $free_port[0]).'</span>
					</div>
					<div class="col-md-6">
						<label class="mb-2">'.__('Application Name').'<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('The application’s name.').'"></i></label>
						<input type="text" id="app_name" name="app_name" class="form-control mb-3" value="'.POSTval('app_name', $edit_app['name']).'"/>
					</div>
					<div class="col-md-6">
						<label class="mb-2">'.__('Deployment Domain').'<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('The application’s domain.').'"></i></label>
						<select name="domain" id="domain" class="form-control  mb-3">					
							<option value="">'.__('Select a domain').'</option>';					
							foreach ($domains_list as $k => $v)	{
								echo '<option value="'.$k.'" '.POSTselect('domain', $k, ($edit_app['domain'] == $k)).'>'.$k.'</option>';
							}
							echo '
						</select>
					</div>
					<div class="col-md-6">
						<label class="mb-2">'.__('Base Application URL').'<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('URL to access the application.').'"></i></label>
						<div class="input-group mb-3">
							<span class="input-group-text" id="url_prefix">'.$edit_app['domain'].'/</span>
							<input type="text" id="base_url" name="base_url" class="form-control" value="'.str_replace($edit_app['domain'].'/','',POSTval('base_url', $edit_app['base_url'])).'" />
						</div>
					</div>
					<div class="col-md-6">
						<label class="mb-2">'.__('Application Path ').'<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('The application’s source code path  relative to home directory.').'"></i></label>
						<div class="input-group mb-3">
							<span class="input-group-text">'.$WE->user['homedir'].'/'.'</span>
							<input type="text" id="app_path" name="app_path" class="form-control" value="'.str_replace($WE->user['homedir'].'/','',POSTval('app_path', $edit_app['app_path'])).'" />
						</div>
						<label class="sai_exp2" id="app_notes"></label>
					</div>
					<div class="col-md-6">
						<label class="mb-2">'.__('Application type').'<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('The application’s type').'"></i></label>
						<div class="input-group" >
							<select name="app_type" id="app_type" class="form-control mb-3" style="width: 50%;">
							<option value="">'.__('Select a application type').'</option>';
							foreach($eapps_type as $ek => $ev){
								echo '<option value="'.$ek.'" '.POSTselect('app_type', $ek, ($edit_app['app_type'] == $ek)).'>'.$ev['name'].'</option>';								
							}
							echo '
						</select>
						</div>
					</div>
					<div class="col-md-6 mb-3">
						<label class="mb-2">'.__('Application startup file').'<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('The start up file to start the application').'"></i></label>
						<div class="input-group">
							<input type="text" id="startup_file" name="startup_file" class="form-control mb-3" value="'.POSTval('startup_file', $edit_app['startup_file']).'" />
						</div>
					</div>
					<div class="col-md-10 mb-3">
						<label class="mb-2">'.__('Deployment Environment').'</label>							
						<label><input type="radio" '.POSTradio('deploy', 'development', ($edit_app['deploy'] == 'development')).' class="mx-2" name="deploy" id="development" value="development" checked />'.__('Development').'</label>
						<label><input type="radio" '.POSTradio('deploy', 'production', ($edit_app['deploy'] == 'production')).' class="mx-2" name="deploy" id="production" value="production" />'.__('Production').'</label>							
					</div>
					<div class="col-md-6 mb-3 self_input">
						<label class="mb-2">'.__('Start Command').'<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Start command to start the application.').'"></i></label>
						<div class="input-group">
							<input type="text" id="start_cmd" name="start_cmd" class="form-control" value="'.POSTval('start_cmd', $edit_app['start_cmd']).'"/>
						</div>
					</div>
					<div class="col-md-6 mb-3 self_input">
						<label class="mb-2">'.__('Stop Command').'<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Stop command to stop the application.').'"></i></label>
						<div class="input-group">
							<input type="text" id="stop_cmd" name="stop_cmd" class="form-control" value="'.POSTval('stop_cmd', $edit_app['stop_cmd']).'"/>
						</div>
					</div>
					<label class="col-md-4 mb-3">'.__('Environment Variables').'</label>
					<div class="flex-grow-1 p-1 col-md-2">
						<i onclick="addrow(\'env_var\')" class="go_btn blue_btn float-end" style="cursor: pointer;">Add +</i>
					</div>
					<div id="env_var">';
					if(!empty($edit_app['env_var'])){
						$env_var = json_decode($edit_app['env_var']);
						foreach($env_var as $ek => $ev){
							echo '
							<div class="d-flex row">
								<div class="flex-grow-1 p-1 col-md-5">
									<label class="pt-0 form-label me-1">'.__('Variable name').' :</label>
									<input type="text" class="form-control" name="var_name[]" value="'.$ek.'" size="30" />
								</div>
								<div class="flex-grow-1 p-1 col-md-5">
									<label class="pt-0 form-label me-1">'.__('Value').' :</label>
									<input type="text" class="form-control" name="var_val[]" value="'.$ev.'" size="30" />
								</div>
								<div class="flex-shrink-0 align-self-center p-1 col-md-2"><i class="fas fa-times pl-1 delip text-danger cursor-pointer py-2 mt-4 d-block"></i></div>
							</div>';
						}
					}
					echo '
					</div>
				</div>
				<div class="pt-4 text-center">
				<input type="hidden" name="type" id="type" value="self" class="flat-butt me-2" />';
				if(!empty($edit_app['uuid'])){						
					echo '<input type="hidden" name="uuid" value="'.$edit_app['uuid'].'" class="flat-butt me-2" />';
				}
				echo '
				<input type="submit" name="create" value="'.(!empty($edit_app) ? __('Edit') : __('Create')).'" class="flat-butt me-2" id="create" />
				</div>
			</div>
		</div>
	</div><!--tabs ended-->
</div>	
</div>

<script language="javascript" type="text/javascript">

$("#domain").change(function(){
	var domain = $("#domain option:selected").val();
	if(!empty(domain)){
		$("#url_prefix").text(domain+"/");		
	}
});

var urlParams = new URLSearchParams(window.location.search);
var type = urlParams.get("type");
if(type == "passenger"){
	$("#self_head").removeClass("active");
	$("#passenger_head").addClass("active");
	$(".self_input").hide();
	$("input[name=\"type\"]").val("passenger");
}

$(".delip").click(function(){
	var parent = $(this).parent();
	var parent2 = $(this).parent().parent();
	parent.remove();
	parent2.remove();
});

$("#passenger_head, #self_head").click(function(){
	if($("#passenger_head").hasClass("active")){
		$(".self_input").hide();
		$("input[name=\"type\"]").val("passenger");
		get_app_fields();
	}else{
		$(".self_input").show();
		$("input[name=\"type\"]").val("self");
		get_app_fields();
	}
});

function addrow(id){
	var t = $_(id);

	var row = document.createElement("div");
	row.className = "d-flex row";
	var col1 = document.createElement("div");
	col1.className = "flex-grow-1 p-1 col-md-5";
	var col2 = document.createElement("div");
	col2.className = "flex-grow-1 p-1 col-md-5";
	var col3 = document.createElement("div");
	col3.className = "flex-shrink-0 align-self-center p-1 col-md-2";

	col1.innerHTML = \'<label class="pt-0 form-label me-1">'.__('Variable name').' :</label><input type="text" class="form-control" name="var_name[]" value="" size="30" />\';
	col2.innerHTML = \'<label class="pt-0 form-label me-1">'.__('Value').' :</label><input type="text" class="form-control" name="var_val[]" value="" size="30" />\';
	col3.innerHTML = \'<i class="fas fa-times pl-1 delip text-danger cursor-pointer py-2 mt-4 d-block"></i>\';
	row.appendChild(col1);
	row.appendChild(col2);
	row.appendChild(col3);
	t.appendChild(row);
	$(".delip").each(function(){
		$(this).unbind("click");
		$(this).click(function(){
			var parent = $(this).parent();
			var parent2 = $(this).parent().parent();
			parent.remove();
			parent2.remove();
		});
	});
};

$("#create").click(function(){
	var var_name = [];
	var var_val = [];
	var env_var = [];
	var additional_ports = [];
	$(\'input[name="var_name[]"]\').each(function () {
		var_name.push(this.value); // $(this).val()
	})
	$(\'input[name="var_val[]"]\').each(function () {
		var_val.push(this.value); // $(this).val()
	})
		
	for(var i=0; i<var_name.length; i++) {
		env_var[var_name[i]] = var_val[i];
	}
		
	$(".additional-ports").each(function(){
		additional_ports.push($(this).text());
	})
		
	var d = {"create": 1, "app_name" : $("#app_name").val(), "domain" : $("#domain").val(), "base_url" : $("#base_url").val(), "app_path" : $("#app_path").val(), "app_type" : $("#app_type").val(), "startup_file" : $("#startup_file").val(), "deploy" : $(\'input[name="deploy"]:checked\').val(), "start_cmd" : $("#start_cmd").val(),  "stop_cmd" : $("#stop_cmd").val(), "env_var" : Object.assign({}, env_var), "type" : $("#type").val(), "additional_ports" : additional_ports};
	submitit(d, {done_reload : "'.$globals['admin_index'].'act=eapps"});
});

function get_app_fields(){
	
	var d = {"app_selected" : $("#app_type").val(), "get_app_fields" : 1};
	
	submitit(d, {
		handle:function(data, p){
			if(!empty(data.field_data.bin)){
				var binary = data.field_data.bin;
				var info = "<h6>The following files are the binaries of the application:-</h6>";
				for (let key in binary) {
					info += "<span>"+key+": \"<code>"+binary[key]+"</code>\"</span><br>";
				}
				
				$(".binary").show();
				$(".binary").html(info);
			}else{
				$(".binary").hide();
			}
			
			// Additional Ports
			$(".additional-ports").remove();
			if(!empty(data.field_data.ports)){
				$(data.field_data.ports).each(function(key, val){
					$("#ports_div").append("&nbsp<span class=\'input-group-text additional-ports\' style=\'height: 70%;\'>"+val+"</span>");
				})
			}
			
			if($("#passenger_head").hasClass("active")){
				return;
			}
			$("#app_name").parent().parent().show();
			$("#app_path").parent().parent().show();
			$("#startup_file").parent().parent().show();
			$("#start_cmd").parent().parent().show();
			$("#stop_cmd").parent().parent().show();
			$("#base_url").parent().parent().show();
			$("#app_notes").text("");
			
			// Show and hide fields
			if(!empty(data.field_data.disable)){
				var disable_f = data.field_data.disable;
				
				for (let key in disable_f) {
				  if (!empty(disable_f[key])) {
					  $("#"+key).parent().parent().hide();
				  }else{
					  $("#"+key).parent().parent().show();
				  }
				}
			}
			
			if(!empty(data.field_data.notes)){
				$("#app_notes").text(data.field_data.notes);
			}
		}
	})
}

$( document ).ready(function() {
	
	var urlParams = new URLSearchParams(window.location.search);
	var appTypeValue = urlParams.get("app_type");

	if(appTypeValue){
		$("#app_type").val(appTypeValue);
	}else{
		get_app_fields();
	}

});

$("#app_type").change(function(){	
	get_app_fields();
})

</script>';

	softfooter();

}
