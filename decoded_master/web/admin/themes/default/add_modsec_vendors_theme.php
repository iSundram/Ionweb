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

function add_modsec_vendors_theme(){
	
global $globals, $softpanel, $theme, $_user, $error, $done, $SESS, $yaml_info;
	
	softheader(__('Add ModSecurity Vendors'));
	
echo '
<div class="col-12 col-md-11 mx-auto">
	<div class="soft-smbox sai_main_head mb-4 p-3">'.__('Add ModSecurity Vendor').'</div>';
	error_handle($error);
	echo '	
	<div class="soft-smbox mb-3">
		<div class="sai_form_head">'.__('Add Vendor').'</div>
			<div class="sai_form p-3">
			<div class="row d-flex align-items-center">				
				<div class="col-12 col-sm-8 col-lg-3">					
					<label for="vendor_conf_url" class="sai_head">'.__('Vendor Configuration URL').'</label>
				</div>
				<div class="col-12 col-sm-8 col-lg-6">					
					<input type="text" required name="vendor_conf_url" placeholder="https://example.com/example/meta_example.yaml" id="vendor_conf_url" class="form-control" />
				</div>
				<div class="col-12 col-sm-4 col-lg-2">
					<input type="submit" class="btn btn-primary" id="loadvendor" name="loadvendor" value="'.__('Load').'"/>
				</div>
			</div>
			<br/>
			<form accept-charset="'.$globals['charset'].'" name="addvendor" method="post" action="" id="addvendor" class="form-horizontal" onsubmit="return submitit(this)">
			<div class="sai_form p-3">
				<div class="row d-flex align-items-center">
					<div class="col-12 col-sm-4 col-lg-3">
						<label for="vendor_name" class="sai_head">'.__('Vendor Name').'</label>
					</div>
					<div class="col-12 col-sm-8 col-lg-9">
						<input type="text" readonly name="vendor_name" id="vendor_name" class="form-control mb-2"/>
					</div>
				</div>
				<div class="row d-flex align-items-center">
					<div class="col-12 col-sm-4 col-lg-3">
						<label for="vendor_desc" class="sai_head">'.__('Vendor Description').'</label>
					</div>
					<div class="col-12 col-sm-8 col-lg-9">
						<input type="text" readonly name="vendor_desc" id="vendor_desc" class="form-control mb-2"/>
					</div>
				</div>
				<div class="row d-flex align-items-center">
					<div class="col-12 col-sm-4 col-lg-3">
						<label for="vendor_url" class="sai_head">'.__('Vendor Documentation URL').'</label>
					</div>
					<div class="col-12 col-sm-8 col-lg-9">
						<input type="text" readonly name="vendor_url" id="vendor_url" class="form-control mb-2"/>
					</div>
				</div>
				<div class="row d-flex align-items-center">
					<div class="col-12 col-sm-4 col-lg-3">
						<label for="vendor_report_url" class="sai_head">'.__('Vendor Report URL').'</label>
					</div>
					<div class="col-12 col-sm-8 col-lg-9">
						<input type="text" readonly name="report_url" id="report_url" class="form-control mb-2" />
					</div>
				</div>
				<div class="row d-flex align-items-center">
					<div class="col-12 col-sm-4 col-lg-3">
						<label for="vendor_path" class="sai_head">'.__('Path').'</label>
					</div>
					<div class="col-12 col-sm-8 col-lg-9">
						<input type="text" readonly name="vendor_path" id="vendor_path" class="form-control mb-2"/>
					</div>
				</div>
				<div class="row d-flex align-items-center">
					<div class="col-12 col-sm-4 col-lg-3">
						<input type="hidden" name="download_url" id="download_url" class="form-control mb-2"/>
					</div>
					<div class="col-12 col-sm-8 col-lg-9">
						<input type="hidden" name="version" id="version" class="form-control mb-2"/>
					</div>
				</div>
			</div>
			<div class="text-center">
				<input type="submit" class="btn btn-primary" id="add_vendor" name="add_vendor" value="'.__('Save').'"/>
				<a class="btn btn-light text-decoration-none" href="'.$globals['admin'].'?act=modsec_vendors">'.__('Cancel').'</a>
			</div>			
			</form>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
	$("#add_vendor").attr("disabled",true);
    $("#loadvendor").attr("disabled",true);
    $("#vendor_conf_url").keyup(function(){
        if($(this).val().length !=0)
            $("#loadvendor").attr("disabled", false);            
        else
            $("#loadvendor").attr("disabled",true);
    })
	
});
$("#loadvendor").click(function(){
	var vendor_conf_url = $("#vendor_conf_url").val();	
	var confirmbox = "'.__js('The given path seems to be unsafe! Are you sure you want to continue?').'";
	
	// Lets first check the path is safe or not ?
	var dc = "vendor_conf_url="+vendor_conf_url+"&is_safe=1";
	var lang = confirmbox;
	
	// Reload page 
	var no = function(){
		location.reload(true);
	}
	
	// Submit the data	
	submitit(dc, {
		handle:function(data){
			if(empty(data.done) && !vendor_conf_url.toLowerCase().startsWith("http")){
				a = show_message_r("'.__js('Warning').'", lang);
				a.alert = "alert-warning";
				a.confirm.push(function(){
					load_modsec_vendor(vendor_conf_url, 1);
				});
				
				// If user closes or chooses no
				a.no.push(no);			
				show_message(a);
			}else{
				load_modsec_vendor(vendor_conf_url);
			}
		}
	});
	
});

function load_modsec_vendor(vendor_conf_url, skip_safe_check = 0){
	
	var d = "vendor_conf_url="+vendor_conf_url+"&skip_safe_check="+skip_safe_check;
	submitit(d, {
		after_handle:function(data, p){
			if(data.yaml_info){
				var res = data.yaml_info;
				$("#vendor_name").val(res.name);
				$("#vendor_desc").val(res.description);
				$("#vendor_url").val(res.vendor_url);
				$("#report_url").val(res.report_url);
				$("#vendor_path").val(res.vendor_path);
				$("#download_url").val(res.downloadurl);
				$("#version").val(res.version);
				$("#add_vendor").attr("disabled",false);
			}else{
				$("#vendor_name").val("");
				$("#vendor_desc").val("");
				$("#vendor_url").val("");
				$("#report_url").val("");
				$("#path").val("");
				$("#add_vendor").attr("disabled",true);
			}
		}
	});
	
}

</script>';

	softfooter();
	
}
