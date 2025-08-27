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

function addips_theme(){

global $globals, $user, $theme, $error, $ips, $done;

softheader(__('Add IP'));

	echo '
<style>
.iptype-tab, .iptype-tab *{
text-decoration: none !important;	
}

.col-inline{
display: inline-block;
}

.ipv6-range{
margin-bottom: 10px;
}

.delip6{
padding-left:10px;
}

.blue_btn{
background-color: #1976d2;
border-color: #1976d2;
color: #fff !important;
padding: 6px 10px;
cursor:pointer;
border-radius: 2px;
transition: all 0.4s;
}

.blue_btn:hover {
background-color: #1163b4;
border-color: #1163b4;
color: #fff !important;
text-decoration:none;
}

.ipv6field{
display: none;	
}
</style>

<script language="javascript" type="text/javascript">
function addrow(id){
	var t = $_(id);

	var row = document.createElement("div");
	row.className = "d-flex";
	var col1 = document.createElement("div");
	col1.className = "flex-grow-1 p-1";
	var col2 = document.createElement("div");
	col2.className = "flex-shrink-0 align-self-center p-1";

	col1.innerHTML = \'<label class="pt-0 form-label me-1">'.__('IP Address').' :</label><input type="text" class="form-control" name="ips[]" size="20" />\';
	col2.innerHTML = \'<a class="pl-1 delip"><i class="fas fa-times text-danger cursor-pointer py-2 mt-4 d-block"></i></a>\';
	row.appendChild(col1);
	row.appendChild(col2);
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

function addrow6(id){
	var t = $("#"+id);
	var lastrow = $(".ipv6-range").length - 1;
	
	var html = \'<div class="ipv6-range">\'+
	\'<div class="col-inline mb-2"><input type="text" class="form-control ipv6-text" name="ips6[\'+lastrow+\'][1]" value="" size="4" maxlength="4" class="ips6box" /></div> <label> : </label> \'+
	\'<div class="col-inline mb-2"><input type="text" class="form-control ipv6-text" name="ips6[\'+lastrow+\'][2]" value="" size="4" maxlength="4" class="ips6box" /></div> <label> : </label> \'+
	\'<div class="col-inline mb-2"><input type="text" class="form-control ipv6-text" name="ips6[\'+lastrow+\'][3]" value="" size="4" maxlength="4" class="ips6box" /></div> <label> : </label> \'+
	\'<div class="col-inline mb-2"><input type="text" class="form-control ipv6-text" name="ips6[\'+lastrow+\'][4]" value="" size="4" maxlength="4" class="ips6box" /></div> <label> : </label> \'+
	\'<div class="col-inline mb-2"><input type="text" class="form-control ipv6-text" name="ips6[\'+lastrow+\'][5]" value="" size="4" maxlength="4" class="ips6box" /></div> <label> : </label> \'+
	\'<div class="col-inline mb-2"><input type="text" class="form-control ipv6-text" name="ips6[\'+lastrow+\'][6]" value="" size="4" maxlength="4" class="ips6box" /></div> <label> : </label> \'+
	\'<div class="col-inline mb-2"><input type="text" class="form-control ipv6-text" name="ips6[\'+lastrow+\'][7]" value="" size="4" maxlength="4" class="ips6box" /></div> <label> : </label> \'+
	\'<div class="col-inline mb-2"><input type="text" class="form-control ipv6-text" name="ips6[\'+lastrow+\'][8]" value="" size="4" maxlength="4" class="ips6box" /> </div>\'+
	\'<a class="delip6"><i class="fas fa-times text-danger cursor-pointer py-2"></i></a>\'+
	\'</div>\';
	
	t.append(html);
	
	$(".delip6").each(function(){
		$(this).unbind("click");
		$(this).click(function(){
			$(this).parent().remove();
		});
	});
};

function changetype(iptype){
	$("#ipv4").removeClass("active");
	$("#ipv6").removeClass("active");
	
	if (iptype == 4){
		$(".ips").css("display","flex");
		$("#ips6").hide();
		$("#ip_range").show();
		$("#gen_ipv6").hide();
		$("#firstip").show();
		$("#lastip").show();
		$("#ipv6_range").hide();
		$("#ipv6_num").hide();
	}else{
		$(".ips").css("display","none");
		$("#ips6").css("display","flex");
		$("#ip_range").hide();
		$("#gen_ipv6").css("display","flex");
		$("#firstip").hide();
		$("#lastip").hide();
		$("#ipv6_range").show();
		$("#ipv6_num").css("display","flex");
	}		
	$("#ipv"+iptype).addClass("active");
	$_("iptype").value = iptype;
	
};

$(document).ready(function(){
	changetype('.POSTval('iptype', 4).');
});
</script>

<div class="col-12 col-md-12 mx-auto">
<div id="form-container" class="soft-smbox mb-3 p-3">	
	<div class="sai_main_head">
		<i class="fas fa-project-diagram me-1"></i> '.__('Add IP').'
		<a href="'.$globals['docs'].'add-ip/" target="_blank" tooltip="'.__('Documentation').'" class="float-end">
			<i class="fas fa-info-circle mt-1"></i>
		</a>
	</div>
</div>
<div id="form-container" class="soft-smbox my-3 p-3">';
	error_handle($error);
	
	echo '	
	
	<form accept-charset="'.$globals['charset'].'" name="addippool" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)">
		<input type="hidden" id="iptype" name="iptype" value="'.POSTval('iptype', 4).'">
		<div class="row">
			<div class="col-md-2">'.__('IP Type').'</div>
			<div class="col-md-10">
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active iptype-tab" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" onclick="changetype(4)">IPv4</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link iptype-tab" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" onclick="changetype(6)">IPv6</button>
					</li>
				</ul>
			</div>
		</div>
		<div class="row my-3 ips" id="ips">
			<div class="col-12 col-md-4 col-lg-3">
				<label class="form-label">
					'.__('Enter IP').'
					<span class="sai_exp">'.__('Enter single IPs or create a IP Range').'</span>
				</label>
			</div>
			<div class="col-12 col-md-8 col-lg-6">
				<div id="iptable">';
		 
 	//$ips = @$_POST['ips'];
			
	if(is_array($ips) && !empty($ips)){
		foreach($ips as $k => $ip){
			if(empty($ip)){
				unset($ips[$k]);
			}
		}
	}
	
	if(empty($ips)){
		$ips = array(NULL);
	}
		 
	foreach($ips as $ip){
	
		echo '
		<div class="d-flex">
			<div class="flex-grow-1 p-1">
				<label class="pt-0 form-label me-1">'.__('IP Address').' :</label>
				<input type="text" class="form-control" name="ips[]" value="'.$ip['ip'].'" size="30" />
			</div>
			<div class="flex-shrink-0 align-self-center p-1"></div>
		</div>';
		
	}
	
	echo '	
				</div>
				<div class="ms-1 mt-2">
					<a onclick="addrow(\'iptable\')" class="go_btn blue_btn" >+</a>
				</div>
			</div>
		</div>
		<div class="row mx-auto w-100 my-3 ipv6field" id="ips6">
			<div class="col-12 col-md-4 col-lg-2 align-self-center p-0">
				<label class="sai_head">'.__('Enter IP').'
					<span class="sai_exp">'.__('Enter single IPs or generate random IPs').'</span>
				</label>
			</div>
			<div class="col-12 col-md-8 col-lg-10">
				<div id="iptable6">';
				
	$ips = @$_POST['ips6'];
			
	if(is_array($ips) && !empty($ips)){
		foreach($ips as $k => $ip){
			$ip_ = implode('', $ip);
			if(empty($ip_)){
				unset($ips[$k]);
			}
		}
	}
			
	if(empty($ips)){
		$ips = array(NULL);
	}
		 
	foreach($ips as $k => $ip){
		echo '
		<div class="ipv6-range">
			<div class="col-inline mb-2">
				<input type="text" class="form-control ipv6-text" name="ips6['.$k.'][1]" value="'.$ip[1].'" size="4" maxlength="4" class="ips6box" />
			</div>
			<label>:</label>
			<div class="col-inline mb-2">
				<input type="text" class="form-control ipv6-text" name="ips6['.$k.'][2]" value="'.$ip[2].'" size="4" maxlength="4" class="ips6box" />
			</div>
			<label>:</label>
			<div class="col-inline mb-2">
				<input type="text" class="form-control ipv6-text" name="ips6['.$k.'][3]" value="'.$ip[3].'" size="4" maxlength="4" class="ips6box" />
			</div>
			<label>:</label>
			<div class="col-inline mb-2">
				<input type="text" class="form-control ipv6-text" name="ips6['.$k.'][4]" value="'.$ip[4].'" size="4" maxlength="4" class="ips6box" />
			</div>
			<label> : </label>
			<div class="col-inline mb-2">
				<input type="text" class="form-control ipv6-text" name="ips6['.$k.'][5]" value="'.$ip[5].'" size="4" maxlength="4" class="ips6box" />
			</div>
			<label> : </label>
			<div class="col-inline mb-2">
				<input type="text" class="form-control ipv6-text" name="ips6['.$k.'][6]" value="'.$ip[6].'" size="4" maxlength="4" class="ips6box" />
			</div>
			<label> : </label>
			<div class="col-inline mb-2">
				<input type="text" class="form-control ipv6-text" name="ips6['.$k.'][7]" value="'.$ip[7].'" size="4" maxlength="4" class="ips6box" />
			</div>
			<label> : </label>
			<div class="col-inline mb-2">
				<input type="text" class="form-control ipv6-text" name="ips6['.$k.'][8]" value="'.$ip[8].'" size="4" maxlength="4" class="ips6box" />
			</div>
		</div>';
	}

				echo '
				</div>
				<a onclick="addrow6(\'iptable6\')"class="go_btn blue_btn">+</a>
			</div>
		</div>
		<div class="row mx-auto my-4" id="ip_range">
			<div class="col-12 col-md-4 col-lg-3 p-0">
				<label class="form-label">'.__('IP Range').'</label>
			</div>
			<div class="col-12 col-md-4 col-lg-4" id="firstip">
				<label class="control-label">'.__('First IP').'</label>
				<input type="text" class="form-control" name="firstip" value="'.POSTval('firstip', '').'" size="30" />
			</div>
			<div class="col-12 col-md-4 col-lg-4" id="lastip">
				<label class="control-label">'.__('Last IP').'</label>
				<input type="text" class="form-control" name="lastip" value="'.POSTval('lastip', '').'" size="30" />
			</div>
		</div>
		<div class="row mx-auto my-4 ipv6field" id="gen_ipv6">
			<div class="col-12 col-md-4 col-lg-2 tit p-0 align-self-center">
				<label class="form-label">
					'.__('Generate IP').'
				</label>
			</div>
			<div class="col-12 col-md-8 col-lg-10 ipv6field" id="ipv6_range">
				<label class="form-label d-block">'.__('Generate IPv6').'</label>
				<div class="ipv6-range">
					<div class="col-inline"> 
						<input type="text" class="form-control" name="ipv6_1" value="'.POSTval('ipv6_1', '').'" size="4" maxlength="4">
					</div>
					<label> : </label>
					<div class="col-inline">
						<input type="text" class="form-control" name="ipv6_2" value="'.POSTval('ipv6_2', '').'" size="4" maxlength="4">
					</div>
					<label> : </label>
					<div class="col-inline">
						<input type="text" class="form-control" name="ipv6_3" value="'.POSTval('ipv6_3', '').'" size="4" maxlength="4">
					</div>
					<label> : </label>
					<div class="col-inline">
						<input type="text" class="form-control" name="ipv6_4" value="'.POSTval('ipv6_4', '').'" size="4" maxlength="4">
					</div>
					<label> : </label>
					<div class="col-inline">
						<input type="text" class="form-control" name="ipv6_5" value="'.POSTval('ipv6_5', '').'" size="4" maxlength="4">
					</div>
					<label> : </label>
					<div class="col-inline">
						<input type="text" class="form-control" name="ipv6_6" value="'.POSTval('ipv6_6', '').'" size="4" maxlength="4">
					</div>
					<label> : auto : auto </label>
				</div>
			</div>
		</div>

		<div class="row my-3 ipv6field" id="ipv6_num">
			<div class="col-12 col-md-4 col-lg-2">
				<label class="form-label">'.__('Number of IPv6').'
					<span class="sai_exp">'.__('The Number of IPv6 addresses to generate.').'</span>
				</label>
			</div>
			<div class="col-12 col-md-8 col-lg-5">
				<input type="text" class="form-control" name="ipv6_num" value="'.POSTval('ipv6_num', 50).'" size="10">
			</div>
		</div>
		<div class="row mx-auto my-4" >
			<div class="col-12 col-md-4 col-lg-3 p-0">
				<label class="form-label">'.__('Netmask').'</label>
			</div>
			<div class="col-12 col-md-4 col-lg-4" id="netmask">
				<input type="text" class="form-control" name="netmask" value="'.POSTval('netmask', '').'" size="30" />
			</div>
		</div>
		<div class="row mx-auto my-4" >
			<div class="col-12 col-md-4 col-lg-3 p-0">
				<label class="form-label">'.__('Type').'</label>
			</div>
			<div class="col-12 col-md-4 col-lg-4" id="shared">
				
				<select name="shared" class="form-select">
					<option value="0" '.POSTselect('shared', 0, empty($ip['shared'])).'>'.__('Dedicated').'</option>
					<option value="1" '.POSTselect('shared', 1, !empty($ip['shared'])).'>'.__('Shared').'</option>
				</select>
			</div>
		</div>
		<div class="row mx-auto my-4" >
			<div class="col-12 col-md-4 col-lg-3 p-0">
				<label class="form-label">'.__('Status').'
					<span class="sai_exp">'.__('If disabled IP will not be added to the system.').'</span>
				</label>
			</div>
			<div class="col-12 col-md-4 col-lg-3 p-0">
				<label class="switch">				
				<input type="checkbox" name="status" value="1" checked />
				<span class="slider"></span>
				</label>
			</div>
		</div>
		<div class="text-center my-3">
			<input type="submit" name="addip" value="'.__('Add IP Address(s)').'" class="btn btn-primary"/>
		</div>
	</form>
	</div>
</div>';

	softfooter();

}

