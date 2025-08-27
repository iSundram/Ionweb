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

function mod_security_theme(){
	
global $globals, $theme, $softpanel, $WE, $error, $done, $domains_list, $iapps;

	softheader(__('Manage ModSecurity'));
	
	echo '
<div class="card soft-card p-4">
	<div class="sai_main_head mb-4">
		<img src="'.$theme['images'].'mod_security.png" alt="" class="webu_head_img me-2"/>
		<h5 class="d-inline-block">'.__('Mod Security').'</h5>
		<button type="button" class="flat-butt float-end mx-3" data-mod_security="2" data-mod=1 data-donereload=1 onclick="return modtoggle(this)">'.__('Enable All').'</button>
		<button type="button" class="flat-butt float-end" data-mod_security="3" data-mod=1 data-donereload=1 onclick="return modtoggle(this)">'.__('Disable All').'</button>
	</div>
	<div class="table-responsive">
		<table class="table align-middle table-nowrap mb-0 webuzo-table" >			
			<thead class="sai_head2">
				<tr>
					<th class="align-middle">'.__('Domain').'</th>
					<th class="align-middle">'.__('Status').'</th>
				</tr>
						
			</thead>
			<tbody>';

				foreach ($domains_list as $key => $value){
					echo '
				<tr id="tr'.$key.'">
					<td class="endurl" id="domain"><a target="_blank" href="http://' . $key . '"> '.$key.'</a></td>
					<td  width="1%" style="text-align: right">
						<label class="switch">
							<input type="checkbox" class="checkbox" data-mod="1" id="edi'.$key.'" value="'.$key.'" data-mod_security="'.(!empty($value['mod']) ? 0 : 1).'" '.(!empty($value['mod']) ? 'checked' : '').' data-domain="'.$key.'" onclick="return modtoggle(this)" /><span class="slider"></span>
						</label>
					</td> 
				</tr>';
				
				}
				echo '
			</tbody>
		</table>
	</div>	
</div>

<script>

function modtoggle(jEle){
	
	var d = $(jEle).data();
	
	if("domain" in d){
		d.mod_security = $(jEle).attr("data-mod_security");
	}
	// console.log(d);
	
	var a, lan;
	
	if(d.mod_security == "0" || d.mod_security == "3"){
		
		if("domain" in d){
			lan = "'.__js('Are you want to $0 Disable $1 Mod Security for domain ', ['<b>','</b>']).'<b>"+d.domain+"</b>";
		}else{
			lan = "'.__js('Are you want to $0 Disable $1 Mod Security for all the domain', ['<b>','</b>']).'";
		}
		
	}else{
		
		if("domain" in d){
			lan = "'.__js('Are you want to $0 Enable $1 Mod Security for domain ', ['<b>','</b>']).'<b>"+d.domain+"</b>";
		}else{
			lan = "'.__js('Are you want to $0 Enable $1 Mod Security for all the domain', ['<b>','</b>']).'";
		}
		
	}
	
	a = show_message_r("'.__js('Warning').'", lan);
	a.alert = "alert-warning";
	a.confirm.push(function(){
		
		// Submit the data
		submitit(d, {
			sm_done_onclose: function(){
				
				if("domain" in d){
					let status = d.mod_security == "1" ? true : false;
					$(jEle).prop("checked", status);
					$(jEle).attr("data-mod_security", ((status) ? 0 : 1));
				}
				
				if("donereload" in d){
					location.reload();
				}
				
			}
		});
		
	});
	
	a.no.push(function(){
		if("domain" in d){
			let status = d.mod_security ? false : true;
			$(jEle).prop("checked", status);
		}
	});
	
	a.onclose.push(function(){
		if("domain" in d){
			let status = d.mod_security ? false : true;
			$(jEle).prop("checked", status);
		}
	});
	
	// console.log(a);return;
	show_message(a);
}

</script>';

	softfooter();
	
}
