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

function multiphp_ini_editor_theme(){

global $globals, $theme, $softpanel, $WE, $domain, $error, $apps, $iapps, $done, $domains_list;

	softheader(__('PHP INI Editor'));

	echo '
<style>

textarea {
  background: url(http://i.imgur.com/2cOaJ.png);
  background-attachment: local;
  background-repeat: no-repeat;
  padding-left: 35px;
  padding-top: 10px;
  border-color: #ccc;

  font-size: 13px;
  line-height: 16px;
  width:100%;
}

.textarea-wrapper {
  display: inline-block;
  background-image: linear-gradient(#F1F1F1 50%, #F9F9F9 50%);
  background-size: 100% 32px;
  background-position: left 10px;
  width:100%;
}

</style>

<div class="card soft-card p-4 col-12">
	<!-- Heading -->
	<div class="sai_main_head mb-5">
		<img src="'.$theme['images'].'multi_php.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('PHP INI Editor').'</h5>
	</div>
	
	<!-- domain and User home directory list -->
	<div class="record-table mb-3">
		<h4 class="sai_sub_head d-inline-block" id="setting_lbl">'.__('Configure PHP INI basic settings').'</h4>
		<span class="sai_exp2 d-block" id="setting_sublbl">'.__('Select the home directory or a domain\'s document root to open the corresponding PHP configuration.').'</span>
		<select id="selectdomain" data-loc_change=1>
			<option value="0"> -- '.__('Select a location').' -- </option>';
			foreach ($domains_list as $key => $value){
				echo '<option value='.$key.'>'.(!empty($value['lbl']) ? $value['lbl'] : $key).'</option>';
			}
		echo '
		</select>
	</div>
	
	<!-- Show Extra info -->
	<div class="alert alert-info row" style="display:none" id="path_row">
		<div class="col-sm-6">
			<strong>'.__('Path :').'</strong> <span id="path"></span>
		</div>
		<div class="col-sm-6">
			<strong>'.__('PHP Version :').'</strong> <span id="php_v"></span>
		</div>
	</div>
	
	<!--- tab start -->
	<ul class="nav nav-tabs mb-3 webuzo-tabs" id="pills-tab" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active heading_a" id="b_mode_t" data-bs-toggle="tab" data-bs-target="#b_mode" type="button" role="tab" aria-controls="b_mode" aria-selected="true" onclick="changeTab(this)">'.__('Basic Mode').'</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" href="#e_mode" id="e_mode_t" data-bs-toggle="tab" data-bs-target="#e_mode" type="button" role="tab" aria-controls="e_mode" aria-selected="false" onclick="changeTab(this)">'.__('Editor Mode').'</button>
		</li>
	</ul>
	<!--- tab end -->
	
	<!--- tab Content start -->
	<div class="tab-content" id="pills-tabContent">
		<div class="tab-pane fade show active" id="b_mode" role="tabpanel" aria-labelledby="b_mode_t">
			<div class="alert alert-info" id="b_no_loc">
				<strong>'.__('NOTE :').'</strong> '.__('You must select a location.').'
			</div>
			<form name="b_form_cont" id="b_form_cont" action="" method="post" style="display:none" onsubmit="return submitit(this)" data-domain="">
			
			</form>
		</div>
		<div class="tab-pane fade show" id="e_mode" role="tabpanel" aria-labelledby="e_mode_t">
			<div class="alert alert-info" id="e_no_loc">
				<strong>'.__('NOTE :').'</strong> '.__('You must select a location.').'
			</div>
			<div class="alert alert-info" style="display:none" id="no_cont">
				<strong>'.__('Information:').'</strong> '.__('The INI content does not exist. You may add new content.').'
			</div>
			<div class="textarea-wrapper">
				<textarea name="e_cont" id="e_cont" rows="20" style="display:none;overflow:auto;resize:none"></textarea>
			</div>
			<button type="button" class="flat-butt my-2" id="s_e_cont" style="display:none" onclick="return submit_e_cont(this)">'.__('Save').'</button>
		</div>
	</div>
	<!--- tab Content end -->
	
</div>

<script>

function changeTab(jEle){
	let mt = $(jEle).attr("aria-controls");
	if(mt === "b_mode"){
		$("#setting_lbl").html("'.__js('Configure PHP INI basic settings').'");
	}else if(mt === "e_mode"){
		$("#setting_lbl").html("'.__js('Edit PHP INI settings').'");
	}
}

function create_b_form(data, dom){
	
	var tmpHtml = "";
	
	tmpHtml += \'<input type="hidden" name="domain" value="\'+dom+\'">\';
	tmpHtml += \'<div class="table-responsive"><table class="table align-middle table-nowrap mb-0 webuzo-table" ><thead class="sai_head2"><tr><th class="align-middle">'.__js('PHP Directive').'</th><th class="align-middle">'.__js('Information').'</th><th class="align-middle">'.__js('Setting').'</th></tr></thead><tbody>\';
	
	$.each(data["done"]["php_ini_keys"], function(key, value){
		tmpHtml += \'<tr id="tr\'+key+\'"><td>\'+(value["key"] ? value["key"] : key)+\'</td><td>\'+value["info"]+\'</td><td style="text-align: right">\';
		
		if(value["type"] == "checkbox"){
			
			tmpHtml += \'<div class="form-check form-switch"><input class="form-check-input" name="\'+key+\'" type="checkbox" \'+(value["default"] ? "checked" : "")+\'></div>\'
			
			// tmpHtml += \'<label class="switch"><input type="radio" class="radio" value="1" \'+(value["default"] ? "checked" : "")+\' name="\'+key+\' /" ><span class="slider"></span>\';
		}else{
			tmpHtml += \'<input type="text" name="\'+key+\'" value="\'+value["default"]+\'" >\';
		}
		
		tmpHtml += \'</td></tr>\';
		
	});
	
	tmpHtml += \'</tbody></table></div>\';
	tmpHtml += \'<br><input type="submit" class="flat-butt me-2" name="b_cont" value="Apply">\';
	
	$("#b_form_cont").html(tmpHtml).show();
}

$("#selectdomain").change(function(){
	let val = $(this).val();
	if(val === "0"){
		$("#b_no_loc").show();
		$("#e_no_loc").show();
		$("#b_form_cont").hide();
		$("#e_cont").hide();
		$("#no_cont").hide();
		$("#path_row").hide();
		$("#s_e_cont").hide();
		return;
	}
	
	var d = $(this).data();
	d.domain = val;
	
	submitit(d, {
		handle:function(data,p){
			
			if(data["error"]){
				$("#e_cont").hide();
				$("#b_form_cont").hide();
				$("#path_row").hide();
				$("#s_e_cont").hide();
				return;
			}
			
			create_b_form(data, val);
			
			if(!data["done"]["user_ini_content"]){
				$("#e_cont").hide();
				$("#no_cont").show();
				$("#e_cont").html("").show();
				$("#s_e_cont").show();
			}else{
				$("#e_cont").html(data["done"]["user_ini_content"]).show();
				$("#s_e_cont").show();
				$("#no_cont").hide();
			}
			$("#b_no_loc").hide();
			$("#e_no_loc").hide();
			$("#path_row").show();
			$("#path").html(data["done"]["path"]);
			$("#php_v").html(data["done"]["php_v"]);
		}
	});
});

function submit_e_cont(jEle){
	var d = $(jEle).data();
	d.domain = $("#selectdomain").val();
	d.e_cont = $("#e_cont").val();
	
	submitit(d);
	$("#selectdomain").trigger("change");
}

$("#b_form_cont").on("done", function(){
	$("#selectdomain").trigger("change");
});

</script>';

softfooter();
}