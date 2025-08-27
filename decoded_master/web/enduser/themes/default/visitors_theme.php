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

function visitors_theme(){

global $user, $globals, $theme, $softpanel, $WE, $error, $done, $domains, $domain_name, $visitors;
	
	// To update domains links
	softheader(__('Visitors'));
	error_handle($error, "100%");
	
	echo '
<div class="card soft-card p-3">
	<div class="sai_main_head">
		<i class="fas fa-users fa-2x  align-middle me-1" aria-hidden="true"></i>
		<h5 class="d-inline-block">'.__('Visitors').'</h5>
	</div>
</div>
<div class="card soft-card p-4 mt-4">
	<div class="tab-content" id="panel-body-part">
		<div class="tab-pane fade show active" id="lecerts" role="tabpanel" aria-labelledby="lecerts_a">';
	if(!empty($error['no_domain'])){
		echo '
			<div class="alert alert-danger " style="width:100%">
				<center style="margin-top:4px; font-size:16px;">'.__('No valid domain found on your machine').'</center>
			</div>';
	}else{
		page_links();
		
		echo '
			<form accept-charset="'.$globals['charset'].'" action="" method="post" id="editformuplode" enctype="multipart/form-data" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
				<div class="row mb-3">
					<div class="col-12 text-center">
						<label class="form-label mr-1" for="selectdomain">'.__('Select Domain').'</label>
					
						<select name="domain" id="selectdomain" class="make-select2" style="width: 20%">';					
						foreach ($domains as $key => $value){
							echo '<option value='.$key.' '.POSTselect('selectdomain', $key, optREQ('domain') == $key).'>'.$key.'</option>';
						}
						echo '
						</select>
					</div>
				</div>
				<div class="row my-3">
					<div class="col-md-6">
						<div class="input-group">
							<input class="form-control" type="text" name="search" id="search_string" placeholder="Search">					
								<span id="search" class="input-group-text"><i class="fas fa-search text-primary"></i></span>						
						</div>
					</div>
					
					<div class="col-md-6">
						<i class="float-end fas fa-sync-alt refresh-icon fa-xl ms-3 pt-1" title="Reload" id="reload"></i>
						<a href="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" class="float-end">
							<i class="fas fa-cog text-primary fa-2x"></i>
						</a>
						<ul class="dropdown-menu" style="background-color: #eff2f7;">
							<li class="dropdown-item cursor-pointer"><input type="checkbox" checked name="v_ip"/> '.__('IP').' </li>
							<li class="dropdown-item cursor-pointer"><input type="checkbox" checked name="v_url"/> '.__('URL').' </li>
							<li class="dropdown-item cursor-pointer"><input type="checkbox" checked name="v_datetime"/> '.__('Time').' </li>
							<li class="dropdown-item cursor-pointer"><input type="checkbox" checked name="v_size"/> '.__('Size').' </li>
							<li class="dropdown-item cursor-pointer"><input type="checkbox" checked name="v_status"/> '.__('Status').' </li>
							<li class="dropdown-item cursor-pointer"><input type="checkbox" checked name="v_method"/> '.__('Method').' </li>
							<li class="dropdown-item cursor-pointer"><input type="checkbox" checked name="v_protocol"/> '.__('Protocol').' </li>
							<li class="dropdown-item cursor-pointer"><input type="checkbox" checked name="v_browser"/> '.__('User Agent').' </li>			
						</ul>
					</div>
				</div>
				<div id="showrectab">';
				if(!empty($visitors)){
					echo '		
					<div class="table-responsive mb-3">
						<table class="table align-middle table-nowrap mb-0 webuzo-table td_font">
						<thead class="sai_head2">
							<th class="v_ip" style="width: 10%;">'.__('IP').'</th>
							<th class="v_url" style="width: 10%;">'.__('URL').'</th>
							<th class="v_datetime" style="width: 10%;">'.__('Time').'</th>
							<th class="v_size" style="width: 10%;">'.__('Size').'</th>
							<th class="v_status" style="width: 10%;">'.__('Status').'</th>
							<th class="v_method" style="width: 10%;">'.__('Method').'</th>
							<th class="v_protocol" style="width: 10%;">'.__('Protocol').'</th>
							<th class="v_browser" style="width: 30%;">'.__('User Agent').'</th>
						</thead>
						<tbody id="visitors_list_html">';
							
							foreach($visitors as $k => $v){
								
								echo '
							<tr class="visitors_data">
								<td class="v_ip" >'.$v['visitors_ip'].'</td>
								<td class="v_url" >'.$v['link'].'</td>
								<td class="v_datetime" >'.$v['datetime'].'</td>
								<td class="v_size" >'.$v['size'].'</td>
								<td class="v_status" >'.$v['status'].'</td>
								<td class="v_method" >'.$v['method'].'</td>
								<td class="v_protocol" >'.$v['protocol'].'</td>
								<td class="v_browser" >'.$v['browser'].'</td>
							</tr>';
							
							}
							
							echo '
						</tbody>
						</table>
					</div>';
				}else{
					echo '
					<div class="alert alert-danger mt-2" style="width:100%">
						<center style="margin-top:4px; font-size:16px;">'.__('Domain stats does not exists').'</center>
					</div>';
				}
				echo '
				</div>
			</form>';
	}
	echo '
		</div>			
		
		
	</div>
</div>
<script language="javascript" type="text/javascript">
	
$("#selectdomain").change(function(){	
	var domain = $(this).val();
	getlogs(domain);
});

$("#reload").click(function(){	
	var domain = $("#selectdomain").val();
	getlogs(domain);
});

function getlogs(domain){
	$(".loading").show();
	window.location = "'.$globals['index'].'act='.$GLOBALS['act'].'&domain="+domain;
}

$("input[type=\'checkbox\']").change(handleCheckboxChange).each(handleCheckboxChange);	
// Function to handle checkbox changes
function handleCheckboxChange() {
    var column = $(this).attr("name");
	
    if ($(this).prop("checked")) {
        $("." + column).show();
    } else {
        $("." + column).hide();
    }
}


$("#search").click(function(){
	
	var search_string = $("#search_string").val();
	$(".loading").show();
	$.getJSON("'.$globals['index'].'act=visitors&api=json&domain='.optREQ('domain').'&search="+search_string, function(data, textStatus, jqXHR) {
		// console.log(data);
		// Create the table;
		create_table_html(data);
		$("input[type=\'checkbox\']").change(handleCheckboxChange).each(handleCheckboxChange);
		$(".loading").hide();
				
	});
});

function create_table_html(data){
	// console.log("table", data);
	var visitors = data.visitors;
	var len = 0;
	try{
		len =Object.keys(data.visitors).length;
	}catch(e){}
	
	var tmphtml = "";
	$("#num_res").html(len);
		
	// If any email account exist ?
	if(len > 0){
		var i=1;
		$.map(visitors, function(value, key){
			// console.log(value, key);
			if(typeof value !== "object"){
				return false;
			}
			
			tmphtml += "<tr class=\'visitors_data\'>";
			tmphtml += "<td class=\'v_ip\' >"+value.visitors_ip+"</td>";
			tmphtml += "<td class=\'v_url\' >"+value.link+"</td>";
			tmphtml += "<td class=\'v_datetime\' >"+value.datetime+"</td>";
			tmphtml += "<td class=\'v_size\' >"+value.size+"</td>";
			tmphtml += "<td class=\'v_status\' >"+value.status+"</td>";
			tmphtml += "<td class=\'v_method\' >"+value.method+"</td>";
			tmphtml += "<td class=\'v_protocol\' >"+value.protocol+"</td>";
			tmphtml += "<td class=\'v_browser\' >"+value.browser+"</td>";
			tmphtml += "</tr>";
			
			i++;	
		});	
	}else{
			
		tmphtml += "<tr id = \'nofound\'><td colspan=\'100\' class=\'text-center\'><span>'.__js('No results found !').'</span></td></tr>";
			
	}				
	$("#visitors_list_html").html(tmphtml);
}

</script>';
	
softfooter();

}


