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

function email_all_theme(){
	
global $theme, $globals, $error, $done, $softpanel, $tasks;


	softheader(__('Email All Users'));
	error_handle($error);
		
echo '
<div class="soft-smbox p-3 col-12 col-md-8 mx-auto">
	<div class="sai_main_head">
			<i class="fas fa-envelope fa-xl me-2"></i>'.__('Send Email To All Users').'
	</div>
</div>
<div class="soft-smbox p-4 col-12 col-md-8 mx-auto mt-4">
<form accept-charset="'.$globals['charset'].'" action="" method="post" class="form-horizontal">';
			
	echo '
	<div class="row">
		<div class="col-12 mb-3">
			<label for="mail" class="sai_head">'.__('Sender Name').'</label>
			<input type="text" required name="from_name" id="from_name" class="form-control" value="root" />
		</div>
		<div class="col-12 mb-3">
			<label for="mail" class="sai_head">'.__('Sender Email').'</label>
			<input type="email" required name="from_email" id="from_email" class="form-control" value="'.POSTval('from_email', $globals['from_email']).'" />
		</div>
		<div class="col-12 mb-3">
			<label for="mail" class="sai_head">'.__('Subject').'</label><br />
			<input type="text" required name="subject" id="subject" class="form-control subject" value="'.POSTval('subject').'" />
		</div>
		<div class="col-12 mb-3">
			<label for="mail" class="sai_head">'.__('Message Body').'</label><br />
			<textarea required class="form-control message_body" name="message_body" id="message_body" rows="10">'.POSTval('message_body').'</textarea>
		</div>
		<input type="hidden" id="taskID"/>
		<input type="hidden" id="no_res"/>
		<div class="col-12 col-sm-7 col-md-7 col-lg-7 mb-3">
            <label>
                <input class="me-1" type="checkbox" name="sendall" id="sendall" value="1">'.__('Send email to Reseller\'s Customer(s) as well').'
			</label>
			
        </div>
	</div>

	<br />
	<div id="progress_bar" class="progress" style="display: none; height: 20px;">	
		<div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" id="progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;height: 20px;"><span><span></div>
	</div>
	<div class="text-center" id="done_msg">
	</div>
	<br />
	<div class="text-center ">
		<input type="button" id="send_email" value="'.__('Send Email').'" name="savechanges" class="btn btn-primary mr-1" />
		<input type="button" id="loadlogs" class="btn btn-primary btn-logs mr-1" value="'.__('Logs').'">
	</div>
	
	
</form>
</div>
<script>
var interval = null;

$(document).ready(function(){	
	$("#loadlogs").hide(); //hide log button 
	$("#send_email").click(function() {
		$("#send_email").prop("disabled", true);
		var from_name = $("#from_name").val();
		var from_email = $("#from_email").val();
		var subject = $("#subject").val();
		var sendall =0;
		if($("#sendall").prop("checked")== true){
			sendall =1;
		}	
		var message_body = $("#message_body").val();
		var d = {"from_name": from_name,"from_email": from_email,"subject": subject,"message_body":message_body, "sendall": sendall, "savechanges":1};
		submitit(d, {
			after_handle:function(data, p){
				if(data.tasks){
					$("#taskID").val(data["tasks"]["taskID"]);
					$("#no_res").val(data["tasks"]["no_res"]);
					if(data["tasks"]["taskID"] != ""){
						getTaskID(data["tasks"]["num_res"]);					
						interval = setInterval(function(){
							getTaskID(data["tasks"]["num_res"])
						},2000);
					}
				}else{
					$("#send_email").prop("disabled", false);
				}
				
			}
		});
	})
});

function getTaskID(count){
	var taskID = $("#taskID").val();
	var no_res = $("#no_res").val();
	if(taskID != ""){
		$.ajax({				
			type: "POST",
			dataType: "json",			
			url:"'.$globals['admin_url'].'"+"act=tasks&api=json&actid="+taskID,
			success: function(data){
				var process = data["tasks"][taskID]["progress"];
				var status = data["tasks"][taskID]["status"];
				var data = data["tasks"][taskID]["data"];
				$("#loadlogs").click(function(){
					loadlogs(taskID);
				});
				$("#progress_bar").css("display","block");
				$("#loadlogs").show();
				$("#progress").attr("aria-valuenow", (count));
				$("#progress").css("width", (process)+"%");
				$("#progress span").text(data +"/"+ count);
				
				if(status = "Compelet" && process == 100  ){					
					clearInterval(interval); // stop the interval
					setTimeout(function(){
						$("#progress_bar").css("display","none");
						$("#done_msg").html("<b>'.__('Email(s) Sent Successfully').'</b>");
						$("#send_email").prop("disabled", false);
					},2000)					
				}		
			}															  
		});
	}
	
}
</script>';

	softfooter();

}