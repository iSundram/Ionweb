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

// r_print(_dns_get_record('203.compilor.com', 'A')); die();

function contact_manager_theme(){

global $globals, $theme, $cm_data;

	$c_icons = [
		'email' => '<img src="'.$theme['images'].'email.gif" width="20px" title="Email"/>',
		'sms' => '<img src="'.$theme['images'].'sms_pager.png" width="20px" title="Short message service"/>',
		'slack' => '<img src="'.$theme['images'].'slack.png" width="20px" title="Slack"/>',
		'pushbullet' => '<img src="'.$theme['images'].'pushbullet.png" width="20px" title="Pushbullet" />',
		'url' => '<img src="'.$theme['images'].'posturl.png" width="20px" title="Post to URL"/>',
	];
	
	softheader(__('Contact Manager'));
	
	echo '

<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<i class="far fa-solid fa-address-card fa-xl"></i>&nbsp; '.__('Contact Manager').'
	</div>
</div>
<div class="soft-smbox p-4 mt-4">
	<div class="row mb-3">
		<ul class="nav nav-tabs mb-3 webuzo-tabs" id="pills-tab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="c_type_t" data-bs-toggle="tab" data-bs-target="#cm_type" type="button" role="tab" aria-controls="cm_type" aria-selected="true">'.__('Communications').'</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" href="#notification_setting" id="backup" data-bs-toggle="tab" data-bs-target="#notification_setting" type="button" role="tab" aria-controls="notification_setting" aria-selected="false" >'.__('Notifications').'</button>
			</li>
		</ul>
	</div>
	<div class="tab-content" id="pills-tabContent">
		<div class="tab-pane fade show active" id="cm_type" role="tabpanel" aria-labelledby="c_type_t">	
			<div class="p-3 col-12 col-md-12 mx-auto">
				<form accept-charset="'.$globals['charset'].'" name="save_communications" id="save_communications" method="post" action=""; class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
					<div class="sai_form">
						<div class="row mb-3">
							<div class="col-12 col-md-5">
								<label class="sai_head">'.__('Communication Type').'</label>
							</div>
							<div class="col-12 col-md-7">
								<div class="input-group input-group-sm">
									<span class = "input-group-text logo" style="background-color:white"></span>
									<select class="form-select form-select-sm c_type" name="c_type" id="c_type" onchange="reload_form()">
									<!--<input class="form-control c_type" list="datalistCTypes" id="c_type" name="c_type" placeholder="Type to search..." onchange="reload_form()">
									<datalist id="datalistCTypes">-->';
									foreach($cm_data['communication'] as $key => $value){
										echo'<option>'.$value['c_type'].'</option>';	
									}
									echo'
									</select>
								</div>
							</div>
						</div>
						<div class="row mb-3 cform">
							<div class="col-12 col-md-5">
								<label for="Receives" class="sai_head">'.__('Receives').'</label>
							</div>
							<div class="col-12 col-md-7">
								<select class="form-select form-select-sm" name="receive" id="receive">
									<option value="3">High Only</option>
									<option value="2">High And Medium Only</option>
									<option value="1">All</option>
									<option value="0">None</option>
								</select>					
							</div>			
						</div>
						<div class="row mb-3 cform">
							<div class="col-12 col-md-5">
								<label for="destination" class="sai_head">'.__('Destinations').'</label>
							</div>
							<div class="col-12 col-md-7">
								<input type="text" class="form-control form-control-sm" title="" id="destination" name="destination" value="'.$globals['soft_email'].'"/>
								<label class="sai_exp2 mt-1 me-3" id="c_type_exp"></label>
							</div>			
						</div>
						<div class="text-center">
							<input type="submit" class="btn btn-primary" id="save_communications" name="save_communications" value="Save"/>
							<input type="button" class="btn btn-primary" id="test" name="test" value="Test"/>
						</div>
					</div>
				</form>
			</div>
		</div>
		
		<div class="tab-pane fade show" id="notification_setting" role="tabpanel" aria-labelledby="notes">
			<div class = "row col-md-12 mt-3 mb-3">
				<div class="col-12 col-md-4">
					<div class="input-group input-group-sm multi-check d-none" style="width: 60%;">
						<label class="input-group-text" for="multicheck">'.__('With Selected').'</label>
						<select class="form-select form-select-sm" id="multicheck">
							<option value="0">'.__('Disabled').'</option>
							<option value="1">'.__('Low').'</option>
							<option value="2">'.__('Medium').'</option>
							<option value="3">'.__('High').'</option>
						</select>
					</div>
				</div>
				<div class="col-12 col-md-8">
					<label class="form-label">'.__('Search Alert').' : </label>
					<span class="">
						<select class="form-select p-3 make-select2" s2-placeholder="Select Alert Type" style="width: 30%" id="a_search" name="a_search">
							<option value="0" selected>All</option>';
							foreach($cm_data['notifications'] as $key => $val){
								echo '<option value="'.$val['key'].'">'.$val['label'].'</option>';
							}
							echo'
						</select>
					</span>
				</div>
			</div>
			<div class="row mt-3">
				<form accept-charset="'.$globals['charset'].'" name="save_alerts" id="save_alerts" method="post" action=""; class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
					<table border="0" cellpadding="8" cellspacing="1" class="table webuzo-table td_font" id="alert_table">
						<thead>
						<tr>
							<th width="2%"><input type="checkbox" id="checkAll"></th>
							<th width="50%">'.__('Alert type').'</th>
							<th width="10%" class="text-center">'.__('Importance').'</th>
							<th width="10%" class="text-center">'.__('Alerts').'</th>
						</tr>
						</thead>
						<tbody>';
						foreach($cm_data['notifications'] as $key => $value){
							if(empty($value['key'])){
								continue;
							}
							echo '
						<tr class="al_search" id="'.$value['key'].'">
							<td>
								<input type="checkbox" class="check" id="check_'.$value['key'].'" name="checked_alert" value="'.$value['key'].'">
							</td>
							<td class="sai_head">'.$value['label'].'<br>'.(isset($value['label_exp']) ? '<label class="sai_exp2" id="c_type_exp">'.$value['label_exp'].'</label>' : '').'</td>
							
							<td class="me-3"  id="impselect">
								<select id="'.$value['key'].'imp" name="'.$value['key'].'" data-selected="'.$value['selected'].'" data-alert_type="'.$value['key'].'" class="form-select form-select-sm a_class" onchange="alert_data(this)">';
								foreach($cm_data['importance'] as $imp => $opts){
									echo '
										<option value="'.$imp.'" '.($value['selected'] == $imp ? 'selected' : '' ).'>'.$opts.'</option>'; 	
								}
							echo '
								</select>
							</td>
							<td style="padding-left: 45px;" id="'.$value['key'].'_imp_list"></td>
						</tr>';
						}
						echo '
						</tbody>
					</table>
					<div class="text-center">
						<input type="submit" class="btn btn-primary" id="save_alerts" name="save_alerts" value="'.__('Save Alerts').'"/>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<script>

var cm_data = '.json_encode($cm_data).';
var c_icons = '.json_encode($c_icons).';

$(document).ready(function(){
	
	$(".multi-check").addClass("d-none");
	
	$("#checkAll").change(function () {
		$(".check").prop("checked", $(this).prop("checked"));
		$(".multi-check").removeClass("d-none");
	});
	
	$("input:checkbox").change(function() {
		if($(".check:checked").length){
			$(".multi-check").removeClass("d-none");
		}else{
			$(".multi-check").addClass("d-none");
		}
	});
	
	$("#multicheck").change(function(){
		$(".a_class").each(function (ele, val) {
			var id = $(val).data("alert_type");
			console.log("#"+id);
			
			if($("#check_"+id).is(":checked") && $("#check_"+id).is(":visible")){
				$(val).val($("#multicheck").val()).change();
			}
		});
	});
	
	$(".a_class").each(function (ele, val) {
		alert_data(val);
	});
});

function alert_data(ele){
	var jEle = $(ele);
	var a_key = jEle.data("alert_type");
	var imp = jEle.val();
	var list = Object.keys(cm_data.communication);
	
	$("#"+a_key+"_imp_list").empty();
	if(empty(imp)){
		
		$("#"+a_key+"_imp_list").html("None");
	}else{
		var i;
		$(list).each(function(key, val){
			var receive = cm_data["communication"][val]["receive"];
			
			if(empty(receive)){
				return true;
			}
			
			if(imp == 1 && receive == 1){
				i=1;
				$("#"+a_key+"_imp_list").append(c_icons[val]+"&nbsp;");
				return true;
			}
			
			if(imp == 2 && (receive == 2 || receive == 1)){
				i=1;
				$("#"+a_key+"_imp_list").append(c_icons[val]+"&nbsp;");
				return true;
			}
			
			if(imp == 3 && (receive == 2 || receive == 1 || receive == 3)){
				i=1;
				$("#"+a_key+"_imp_list").append(c_icons[val]+"&nbsp;");
				return true;
			}
		})
		
		if(empty(i)){
			$("#"+a_key+"_imp_list").html("None");
		}
	}
}

function reload_form(ele){
	$(".cform").hide();
	show_form();
}

$(document).ready(function(){
	$(".cform").hide();
	show_form();
});

function show_form(){
	var d = {"type" : $("#c_type").val(), "load_form" : 1}
	$(".logo").html(c_icons[$("#c_type").val().toLowerCase()]);
	
	submitit(d, {
		handle:function(data, p){
			$(".cform").show();
			$("#c_type_exp").html(data.form_data.c_type_exp);
			$("#receive").val(data.form_data.receive).show();
			$("#destination").val(data.form_data.destination).show();
			empty(data.form_data.destination) ? $("#test").hide() : $("#test").show();
			if(!empty(data.form_data.destination)){
				$("#destination").attr("title", data.form_data.destination.split(",").join("\n"));
			}else{
				$("#destination").attr("title", "'.__js('No Destination found !').'");
			}
		}
	})
}

$("#test").click(function(){
	var d = {"type" : $("#c_type").val(), "test" : 1}
	submitit(d);
})

$("#a_search").on("select2:select", function(e, u = {}){
	var alert = $("#a_search option:selected").val();	
	$(".al_search").each(function(key, tr){
		if(alert == $(this).attr("id")){
			$(this).show(); return true;
		}else if(empty(alert)){
			$(this).show();
		}else{
			$(this).hide();
		}
	})
});

</script>';

	softfooter();
}