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

function ip_migration_theme(){

global $globals, $user, $theme, $error, $done;

	softheader(__('IP Migration'));
	
	error_handle($error);

	echo '
<div class="soft-smbox p-3 col-12 col-md-8 col-lg-12 mx-auto">
	<div class="sai_main_head">
		<i class="fas fa-lock fa-lg me-2"></i>'.__('IP Migration').'
	</div>
</div>
<div class="soft-smbox p-3 col-12 col-md-8 col-lg-12 mx-auto mt-4">
	<div class="row" id="ip_form">
		<div class="sai_form col-md-8 mt-4">
			<div class="row">
				<div class="col-12 col-md-3">
					<label for="mail" class="sai_head">'.__('Enter the IPs :').'</label>
				</div>
				<div class="col-12 col-md-9 mb-3">
					<textarea class="form-control" type="text" name="ips" id="ips" style="height: 100px;"> </textarea>
				</div>
			</div>
			<div class="text-center my-3">
				<input class="btn btn-primary" type="submit" value="'.__('Submit').'" name="submitip" id="submitip"/>
			</div>
			<div class="alert alert-warning">
				'.__('$0 NOTE : $1 $2 $3 When adding multiple IP addresses, you must use Class C CIDR format. $4 [Valid example: 192.168.130.125/25] $3 IP(s) will be added if it is not present in the IP list. $4 $3 The wizard will update server configuration file and DNS zone files. $4 $3 Update your system startup scripts to reflect the new main IP address. (i.e. "/etc/sysconfig/network-scripts/ifcfg-eth0" or "/etc/network/interfaces") and restart the network. $4 $5', ['<strong>', '</strong>', '<ul>','<li>','</li>','</ul>']).'
			</div>
		</div>
		<div id="cidr_reference" class="mt-3 col-md-4 text-center ">
			<table class="webuzo-table shadow float-end">
				<thead>
					<tr>
						<th>'.__('CIDR Notation').'</th>
						<th>'.__('Number of hosts').'</th>
					</tr>
				</thead>
				<tbody>
					<tr><td>/24</td><td>256</td></tr>
					<tr><td>/25</td><td>128</td></tr>
					<tr><td>/26</td><td>64</td></tr>
					<tr><td>/27</td><td>32</td></tr>
					<tr><td>/28</td><td>16</td></tr>
					<tr><td>/29</td><td>8</td></tr>
					<tr><td>/30</td><td>4</td></tr>
					<tr><td>/31</td><td>2</td></tr>
					<tr><td>/32</td><td>1</td></tr>
				</tbody>
			</table>
		</div>
	</div>
	<div id="div_iplist" style="display:none" class="table-responsive mt-3">
		<table class="table sai_form webuzo-table">
		<thead>
			<tr>
				<th>'.__('Old IP').'</th>
				<th>'.__('New IP').'</th>
				<th>'.__('Users').'</th>
			</tr>
		</thead>
		<tbody id="iplist"></tbody>
		</table>
		<div class="text-center my-3">
			<input class="btn btn-primary" type="submit" value="'.__('Submit').'" name="changeip" id="changeip"/>
		</div>
	</div>
</div>

<script>
	
	$("#submitip").click(function(){
		var ips = $("textarea#ips").val();
		var d = {"submitip": 1, "ips" : ips};
		submitit(d, {handle:function(data, p){
			if(data.error){
				var err = Object.values(data.error);
				var a = show_message_r("'.__js('Error').'", err);
				a.alert = "alert-danger"
				show_message(a);
				return false;
			}
			$("#ip_form").hide();
			$("#div_iplist").show();
			var form = get_ips_list(data["ips_user_arr"], data["valid_ips"]);
			$("#iplist").html(form);
			
		}
		});
	});
	
	$("#changeip").click(function(){
		var arr = [];
		var newip, oldip;
		$(".input_ips").each(function(ik, iv){
			newip = $(this).val();
			oldip = $(this).data("oldip");
			
			if(!empty(newip)){
				arr.push(oldip+"-"+newip);				
			}
		})
		var d = {"changeip": 1, "ip_migrate" : arr};
		
		submitit(d, {handle:function(data, p){
			if(data.error){
				var err = Object.values(data.error);
				var a = show_message_r("'.__js('Error').'", err);
				a.alert = "alert-danger"
				show_message(a);
				return false;
			}
			var download = "<br/><a href=\"javascript:void(0)\" onclick=\"download_matrix()\" id=\"download_matrix\" >"+"'.__js('Click here to download IP translation matrix').'"+"</a>";
			var task = "<br/><a href=\"javascript:loadlogs("+data.done.taskid+");\"><b>Click here to view the logs</b></a>";
			if(!empty(data.done.msg)){
				var a = show_message_r("'.__js('Done').'", data.done.msg + download + task);				
				a.alert = "alert-success"
				a.ok.push(function(){
					location.reload();
				});
				show_message(a);
			}			
		}
		});
	});
	
	function get_ips_list(ip_usr_arr, valid_ips){
		var html = "";
		var i = 0;
		$.each(ip_usr_arr, function (k, v){			
			html += "<tr id=\'tr_1\' ip="+k+"><td><span>"+k+"</span></td><td><span><input type=\'text\' class=\'input_ips\' data-oldip="+k+" value="+(!empty(valid_ips[i]) ? valid_ips[i] : \'\')+"></span></td><td><ul>";
			$.each(v, function (uk, uv){
				html += "<li>"+uk+" ("+uv+")</li>";
			})
			html += "</ul></td></tr>";
			i++;
		});
		return html;
	}
	
	function download_matrix(){
		window.location = "'.$globals['index'].'act=ip_migration&download_matrix=1";
	}
</script>';

	softfooter();

}

