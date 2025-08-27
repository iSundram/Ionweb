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

function cronjob_theme(){

global $theme, $globals, $error, $softpanel, $done, $user, $_user;
	
	softheader(__('Cron Job'));
	
	echo '
<div class="col-12 col-md-12 soft-smbox mx-auto p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'cronjob.png" class="webu_head_img me-1" />&nbsp;'.__('Cron Job').'
		<button type="button" class="btn btn-primary float-end mt-1" data-bs-toggle="modal" data-bs-target="#cronModal">'.__('Add Cron Job').'</button>
	</div>
</div>

<div class="col-12 col-md-12 soft-smbox mx-auto p-3 mt-4">
	<div class="sai_sub_head text-center mb-4 position-relative">
		'.__('Cron Job for user').' <select class="form-select ms-1 make-select2" id="user_search" s2-placeholder="'.__('User').'" s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'root', 'id' => 'root']])).'" style="width:150px">';

			echo '<option value="'.optGET('user', $_user).'" selected="selected">'.optGET('user', $_user).'</option>';
	
		echo '
		</select>
	</div>
	<div class="modal fade" id="cronModal" tabindex="-1" aria-labelledby="cronModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="cronModalLabel">'.__('Add Cron Job').'</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form accept-charset="'.$globals['charset'].'" action="" method="post" name="cronjob" id="cronjob" onsubmit="return submitit(this)">
						<div class="row mb-3">
							<div class="col-12 col-md-4 col-lg-2">
								<label class="sai_head" for="selectuser">'.__('User').'</label>
							</div>
							<div class="col-12 col-md-6 col-md-4">
								<select class="form-select make-select2" name="user" id="f_user" s2-placeholder="'.__('User').'"  s2-ajaxurl="'.$globals['index'].'act=users&api=json" s2-query="search" s2-data-key="users" s2-data-subkey="user" s2-result-add="'.htmlentities(json_encode([['text' => 'root', 'id' => 'root']])).'" s2-dropdownParent="#cronModal" style="width:100%">
									<option value="'.$_user.'" selected="selected">'.$_user.'</option>
								</select>
							</div>
						</div>
						<div class="mb-3">
							<label for="common_settings" class="form-label">' . __('Common Settings') . ' </label>
							<select id="common_settings" class="form-select">
								<option value="---">---'.__('Common Settings').'---</option>
								<option value="*">'.__('Once Per Minute (*****)').'</option>
								<option value="*/5">'.__('Once Per Five Minute (*/5****)').'</option>
								<option value="0/30">'.__('Twice Per Hour (0/30****)').'</option>
								<option value="0****">'.__('Once Per Hour (0****)').'</option>
								<option value="00,12***">'.__('Twice Per Day (00,12***)').'</option>
								<option value="00***">'.__('Once Per Day (00***)').'</option>
								<option value="00**0">'.__('Once Per Week (00**0)').'</option>
								<option value="001,15**">'.__('On the 1st and 15th of the Month (001,15**)').'</option>
								<option value="001**">'.__('Once Per Month (001**)').'</option>
								<option value="0011*">'.__('Once Per Year (0011*)').'</option>
							</select>
						</div>
						<div class="row mb-3">
							<div class="col-12 col-md-2">
								<label class="sai_head" for="minute">'.__('Minute').'</label>
								<span class="sai_exp">'.__('[0-59]').'</span>
							</div>
							<div class="col-12 col-md-3 mb-1"> 
								<input type="text" name="minute" id="minute" class="form-control" value="  *" />
							</div>
							<div class="col-12 col-md-7 mb-1">
								<select id="minute_options"class="form-select" onchange="select_option(\'minute\')">
									<option value="---">---'.__('Common Settings').'---</option>
									<option value="*">'.__('Every Minute (*)').'</option>
									<option value="*/2">'.__('Every Two Minutes (*/2)').'</option>
									<option value="*/5">'.__('Every Five Minutes (*/5)').'</option>
									<option value="*/10">'.__('Every Ten Minutes (*/10)').'</option>
									<option value="*/15">'.__('Every Fifteen Minutes (*/15)').'</option>
									<option value="*/30">'.__('Every Thirty Minutes (0/30)').'</option>
									<option value="---">---'.__('Minute').'---</option>';
									for ($i = 0; $i < 60; $i++) {
										if ($i == 0) {
											echo '<option value="'.$i.'">'.__('00(At the beginning of the hour.)('.$i.')').'</option>';
										} elseif ($i == 15) {
											echo '<option value="'.$i.'">'.__('15(At quarter past the hour.)('.$i.')').'</option>';
										} elseif ($i == 30) {
											echo '<option value="'.$i.'">'.__('30(At half past the hour.)('.$i.')').'</option>';
										} elseif ($i == 45) {
											echo '<option value="'.$i.'">'.__('45(At one quarter until the hour.)('.$i.')').'</option>';
										} else {
											echo '<option value="'.$i.'">'.($i < 10 ? ':0'.$i : ':'.$i).'</option>';
										}
									}
								echo '
								</select>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-12 col-md-2">
								<label class="sai_head" for="hour">'.__('Hour').'</label>
								<span class="sai_exp">'.__('[0-23]').'</span>
							</div>
							<div class="col-12 col-md-3 mb-1">
								<input type="text" name="hour" id="hour" class="form-control" value="  * " />
							</div>
							<div class="col-12 col-md-7 mb-1">
								<select id="hour_options" class="form-select" onchange="select_option(\'hour\')">
									<option value="---">---'.__('Common Settings').'---</option>
									<option value="*">'.__('Every Hour (*)').'</option>
									<option value="*/2">'.__('Every Other Hour (*/2)').'</option> 
									<option value="*/3">'.__('Every Third Hour (*/3)').'</option>
									<option value="*/4">'.__('Every Fourth Hour (*/4)').'</option>
									<option value="*/6">'.__('Every Sixth Hour (*/6)').'</option>
									<option value="0,12">'.__('Every Twelve Hour (0,12)').'</option>
									<option value="---">---'.__('Hour').'---</option>';
									for ($i = 0; $i < 24; $i++) {
										if ($i == 0) {
											echo '<option value="'.$i.'">12:00 a.m. Midnight('.$i.')</option>';
										} elseif ($i == 12) {
											echo '<option value="'.$i.'">12:00 p.m. Noon('.$i.')</option>';
										} else {
											echo '<option value="'.$i.'">'.(($i < 12) ? $i : $i - 12).':00'.(($i < 12) ? ' a.m.' : ' p.m.').'('.$i.')</option>';
										}
									}
								echo'
								</select>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-12 col-md-2">
								<label class="sai_head" for="day">'.__('Day').'</label>
								<span class="sai_exp">'.__('[1-31]').'</span>
							</div>
							<div class="col-12 col-md-3 mb-1">
								<input type="text" name="day" id="day" class="form-control" value="  * " />
							</div>
							<div class="col-12 col-md-7 mb-1">
								<select id="day_options" class="form-select" onchange="select_option(\'day\')">
									<option value="---">---'.__('Common Settings').'---</option>
									<option value="*">'.__('Every Day(*)').'</option>
									<option value="*/2">'.__('Every Other Day(*/2)').'</option>
									<option value="1,15">'.__('On the 1st and 15th of the Month (1,15)').'</option>
									<option value="---">---'.__('Day').'---</option>';
									$ends = array('th','st','nd','rd','th','th','th','th','th','th');
									for($i = 1 ; $i < 32 ; $i++){
										if((($i % 100) >= 11) && (($i % 100) <= 13)){
											echo '<option value="'.$i.'">'.$i.'th ('.$i.')</option>';
										}
										else{
											echo '<option value="'.$i.'">'.$i.$ends[$i % 10].' ('.$i.')</option>';
										}
									}
								echo '
								</select>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-12 col-md-2">
								<label class="sai_head" id="type" for="month">'.__('Month').'</label>
								<span class="sai_exp">'.__('[1-12]').'</span>
							</div>
							<div class="col-12 col-md-3 mb-1">
								<input type="text" name="month" id="month" class="form-control" value="  * " />
							</div>
							<div class="col-12 col-md-7 mb-1">
								<select id="month_options" class="form-control" onchange="select_option(\'month\')">
									<option value="---">---'.__('Common Settings').'---</option>
									<option value="*">'.__('Every Month (*)').'</option>
									<option value="*/2">'.__('Every Other Month (*/2)').'</option>
									<option value="*/3">'.__('Every Third Month (*/3)').'</option>
									<option value="---">---'.__('Month').'---</option>';
									for($i = 1 ; $i < 13 ; $i++){
										echo '<option value="'.$i.'">'.date("F", mktime(0, 0, 0, $i, 10)).' ('.$i.')</option>';
									}
								echo'
								</select>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-12 col-md-2">
								<label class="sai_head" id="type" for="weekday">'.__('Weekday').'</label>
								<span class="sai_exp">'.__('[0-6]').'</span>
							</div>
							<div class="col-12 col-md-3">
								<input type="text" name="weekday" id="weekday" class="form-control"  value="  * " />
							</div>
							<div class="col-12 col-md-7">
								<select id="weekday_options" class="form-select" onchange="select_option(\'weekday\')">
									<option value="---">---'.__('Common Settings').'---</option>
									<option value="*">'.__('Every Day Of Week (*)').'</option>
									<option value="1-5">'.__('Every Weekday (1-5)').'</option>
									<option value="0,6">'.__('Every Weekend (0,6)').'</option>
									<option value="1,2,3">'.__('Every Monday Tuesday Wednesday (1,2,3)').'</option>
									<option value="0,2,4">'.__('Every Sunday Tuesday Thursday (0,2,4)').'</option>
									<option value="---">---'.__('Weekday').'---</option>';
									$days = array(__('Sunday'), __('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday'), __('Saturday'));
									for($i = 0 ; $i < 7 ; $i++){
										echo '<option value="'.$i.'">'.$days[$i].' ('.$i.')</option>';
									}
								echo'
								</select>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-12 col-md-2">
								<label class="sai_head" id="type" for="cmd">'.__('Command').'</label>
							</div>
							<div class="col-12 col-md-10">
								<input type="text" name="cmd" id="cmd" size="60" class="form-control" placeholder="" />
							</div>
						</div>
						<div class="text-center my-3">
							<input type="submit" value="'.__('Add Cron Job').'" name="create_record" class="btn btn-primary me-2" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="showrectab" class="table-responsive">';
		showcron();
	echo '
	</div>
	
	<!-- JavaScript code -->
	<script type="text/javascript">
	function updatecronfields() {
		var commonsetting = $("#common_settings").val();
		var minuteoptions = $("#minute_options");
		var houroptions = $("#hour_options");
		var dayoptions = $("#day_options");
		var monthoptions = $("#month_options");
		var weekdayoptions = $("#weekday_options");
		var minutefield = $("#minute");
		var hourfield = $("#hour");
		var dayfield = $("#day");
		var monthfield = $("#month");
		var weekdayfield = $("#weekday");

		// Update UI fields based on the selected common setting
		switch (commonsetting) {
			case "*":
				// Once Per Minute (*****) 
				minutefield.val("*");
				hourfield.val("*");
				dayfield.val("*");
				monthfield.val("*");
				weekdayfield.val("*");
				minuteoptions.val("*");
				houroptions.val("*");
				dayoptions.val("*");
				monthoptions.val("*");
				weekdayoptions.val("*");
				break;
			case "*/5":
				// Once Per Five Minute (*/5****) 
				minutefield.val("*/5");
				hourfield.val("*");
				dayfield.val("*");
				monthfield.val("*");
				weekdayfield.val("*");
				minuteoptions.val("*/5");
				houroptions.val("*");
				dayoptions.val("*");
				monthoptions.val("*");
				weekdayoptions.val("*");
				break;
			case "0/30":
				// Twice Per Hour (0/30****) 
				minutefield.val("0,30");
				hourfield.val("*");
				dayfield.val("*");
				monthfield.val("*");
				weekdayfield.val("*");
				minuteoptions.val("*/30");
				houroptions.val("*");
				dayoptions.val("*");
				monthoptions.val("*");
				weekdayoptions.val("*");
				break;
			case "0****":
				// Once Per Hour (0****) 
				minutefield.val("0");
				hourfield.val("*");
				dayfield.val("*");
				monthfield.val("*");
				weekdayfield.val("*");
				minuteoptions.val("0");
				houroptions.val("*");
				dayoptions.val("*");
				monthoptions.val("*");
				weekdayoptions.val("*");
				break;
			case "00,12***":
				// Twice Per Day 
				minutefield.val("0");
				hourfield.val("0,12");
				dayfield.val("*");
				monthfield.val("*");
				weekdayfield.val("*");
				minuteoptions.val("0");
				houroptions.val("0,12");
				dayoptions.val("*");
				monthoptions.val("*");
				weekdayoptions.val("*");
				break;
			case "00***":
				// Once Per Day
				minutefield.val("0");
				hourfield.val("0");
				dayfield.val("*");
				monthfield.val("*");
				weekdayfield.val("*");
				minuteoptions.val("0");
				houroptions.val("0");
				dayoptions.val("*");
				monthoptions.val("*");
				weekdayoptions.val("*");
				break;
			case "00**0":
				// Once Per Week
				minutefield.val("0");
				hourfield.val("0");
				dayfield.val("*");
				monthfield.val("*");
				weekdayfield.val("0");
				minuteoptions.val("0");
				houroptions.val("0");
				dayoptions.val("*");
				monthoptions.val("*");
				weekdayoptions.val("0");
				break;
			case "001,15**":
				// On the 1st and 15th of the Month
				minutefield.val("0");
				hourfield.val("0");
				dayfield.val("1,15");
				monthfield.val("*");
				weekdayfield.val("*");
				minuteoptions.val("0");
				houroptions.val("0");
				dayoptions.val("1,15");
				monthoptions.val("*");
				weekdayoptions.val("*");
				break;
			case "001**":
				// Once Per Month
				minutefield.val("0");
				hourfield.val("0");
				dayfield.val("1");
				monthfield.val("*");
				weekdayfield.val("*");
				minuteoptions.val("0");
				houroptions.val("0");
				dayoptions.val("1");
				monthoptions.val("*");
				weekdayoptions.val("*");
				break;
			case "0011*":
				// Once Per Year
				minutefield.val("0");
				hourfield.val("0");
				dayfield.val("1");
				monthfield.val("1");
				weekdayfield.val("*");
				minuteoptions.val("0");
				houroptions.val("0");
				dayoptions.val("1");
				monthoptions.val("1");
				weekdayoptions.val("*");
				break;
		}
		
	}
	//call function
    $("#common_settings").on("change", function() {
        updatecronfields();
    });	
</script>
	
	<div class="mt-3 text-center">'.__('$0 Note : $1 You can learn more about Cron Job $2 here $3', ['<strong>', '</strong>', '<a href="http://www.manpagez.com/man/5/crontab/" target="_blank">', '</a>']).'</div>
</div>

<script language="javascript" type="text/javascript">	

function select_option(input_field){
	input_field = input_field.trim();
	var dd_obj = document.getElementById(input_field+"_options");
	var ib_obj = document.getElementById(input_field);
	if(dd_obj.value !== "---"){
		ib_obj.value = dd_obj.value;
	}
}

$("#user_search").on("select2:select", function(e, u = {}){
	
	let user;
	if("user" in u){
		user = u.user;
	}else{
		user = $("#user_search option:selected").val();
	}
	
	window.location = "'.$globals['index'].'act=cronjob&user="+user;
});

$("#cronjob").on("done", function(){
	$("#user_search").trigger("select2:select", {user:$("#f_user").val()});
});

// For cancel
$(".cancel").click(function() {
	var id = $(this).attr("id");
	id = id.substr(3);	
	$("#cid"+id).hide();
	$("#eid"+id).removeClass("fa-save").addClass("fa-edit");
	$("#tr"+id).find("span").show();
	$("#tr"+id).find("input,.input").hide();	
});
				
// for editing record
$(".edit").click(function() {
	var id = $(this).attr("id");
	id = id.substr(3);
	$("#cid"+id).show();	
	if($("#eid"+id).hasClass("fa-save")){			
		var d = $("#tr"+id).find("input, textarea, select").serialize();
						
		submitit(d, {
			done: function(){
				var tr = $("#tr"+id);
				tr.find(".cancel").click();// Revert showing the inputs
								
				tr.find("input, textarea, select").each(function(){
					var jE = $(this);
					if(jE.attr("type") == "hidden"){
						return;
					}
					
					var value = jE.val();
					value = value.split("<").join("&lt;").split(">").join("&gt;");
					
					jE.closest("td").find("span").html(value);
					
				});
			},
			sm_done_onclose: function(){			
				$("#tr"+id).find("span").show();
				$("#tr"+id).find("input,.input").hide();
			},
			done_reload: window.location
		});	

		$("#tr"+id).find("span").hide();
		$("#tr"+id).find("input,.input").show();
	}else{
						
		$("#eid"+id).addClass("fa-save").removeClass("fa-edit");
		$("#tr"+id).find("span").hide();
		$("#tr"+id).find("input,.input").show();
		
		//var value = $("#cmd_entryc"+id).val();
		//value = value.split("<").join("&lt;").split(">").join("&gt;");		
		//$("#cmd_entryc"+id).get(0).value = value;
		
	}
});

</script>';
	
	softfooter();
	
}

function showcron(){

global $user, $globals, $theme, $softpanel, $catwise, $error, $done, $domain_list, $read_cron, $domain_name;
	

	echo '
<table border="0" cellpadding="8" cellspacing="1" width="100%" align="center" class="table td_font webuzo-table">			
	<thead>
		<tr>				
			<th width="10%">'.__('Minute').'</th>
			<th width="10%">'.__('Hour').'</th>
			<th width="10%">'.__('Day').'</th>
			<th width="10%">'.__('Month').'</th>
			<th width="10%">'.__('Weekday').'</th>
			<th width="35%">'.__('Command').'</th>
			<th class="text-end" colspan="3">'.__('Option').'</th>
		</tr>
	</thead>
	<tbody>';		

	if(empty($read_cron)){
		echo '
		<tr class="text-center">
			<td colspan=7>
				<span>'.__('No Cron Job(s) Added').'</span>
			</td>
		</<tr>';
	}else{
		foreach ($read_cron as $key => $value){
			echo '
		<tr id="tr'.$key.'">
			<td>
				<span id="minc'.$key.'">'.$read_cron[$key]['min'].'</span>
				<input id="min_entryc'.$key.'" name="minute" style="display:none;" value="'.$read_cron[$key]['min'].'" size="2" >
				<input type="hidden" name="edit_record" value="c'.$key.'" />
			</td>
			<td>
				<span id="houc'.$key.'">'.$read_cron[$key]['hou'].'</span>
				<input id="hou_entryc'.$key.'" name="hour" style="display:none;" value="'.$read_cron[$key]['hou'].'" size="2" >
			</td>
			<td>
				<span id="dayc'.$key.'">'.$read_cron[$key]['day'].'</span>
				<input id="day_entryc'.$key.'" name="day" style="display:none;" value="'.$read_cron[$key]['day'].'" size="2" >
			</td>
			<td>
				<span id="monc'.$key.'">'.$read_cron[$key]['mon'].'</span>
				<input id="mon_entryc'.$key.'" name="month" style="display:none;" value="'.$read_cron[$key]['mon'].'" size="2" >
			</td>
			<td>
				<span id="weec'.$key.'">'.$read_cron[$key]['wee'].'</span>
				<input id="wee_entryc'.$key.'" name="weekday" style="display:none;" value="'.$read_cron[$key]['wee'].'" size="2" >
			</td>
			<td>
				<code style="color:#000000;background-color:#e8e8e8"><span id="cmdc'.$key.'">'.htmlentities($read_cron[$key]['cmd']).'</span></code>
				<input id="cmd_entryc'.$key.'" name="cmd" style="display:none;" value="'.htmlentities($read_cron[$key]['cmd']).'" size="30" >
			</td>
			<td style="width:1%;">
				<i class="fas fa-undo cancel cancel-icon" title="'.__('Cancel').'" id="cid'.$key.'" style="display:none;"></i>
			</td>
			<td style="width:1%;">
				<i class="fas fa-pencil-alt edit edit-icon fa-edit" title="Edit" id="eid'.$key.'"></i>
			</td>
			<td style="width:1%;">
				<i class="fas fa-trash delete delete-icon" title="Delete" id="did'.$key.'" data-delete_record="c'.$key.'" onclick="delete_record(this)"></i>
			</td>
		</tr>';
		
		}
	}
	
	echo '
	</tbody>
</table>';

	
}


