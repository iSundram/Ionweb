<?php

//////////////////////////////////////////////////////////////
//===========================================================
// WEBUZO CONTROL PANEL
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Mamta
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

function bruteforce_logs_theme(){
	
global $user, $globals, $theme, $error, $logs, $done, $bruteforce_logs;

	softheader(__('Bruteforce Logs'));
	
	echo '
<style>
	.dis_link{
		pointer-events: none;
		opacity: 0.6;
	}
</style>
	
<div class="soft-smbox p-3">
	<div class="sai_main_head">
		<img src="'.$theme['images'].'login_logs.png" alt="" class="webu_head_img"/>
		'.__('Brute Force Logs').'

		<span class="search_btn float-end mt-2">
			<a type="button" class="float-end" href="'.$globals['index'].'act=bruteforce_settings"><i class="fas fa-cogs"></i></a>
		</span>
	</div>
</div>
<div class="soft-smbox p-3 mt-4">';
	
	page_links();
		
        echo '<!-- Failed Attempts Log Form -->
	<form accept-charset="'.$globals['charset'].'" action="" method="post" name="failed_attempt_form" id="failed_attempt_form">
	
	<div class="row">
		<div class="col-12 col-md-6">
			<input type="button" class="btn btn-danger checkip mb-1'.(empty($bruteforce_logs) ? 'dis_link' : '' ).'" name="rmv_from_log" value="'.__('Remove From Logs').'" data-action="rmv_from_log"/>
			<input type="button" class="btn btn-primary checkip mb-1'.(empty($bruteforce_logs) ? 'dis_link' : '' ).'" name="blacklist_ips" value="'.__('Blacklist Selected IPs').'" data-action="blacklist_ips"/>
		</div>
		<div class="col-12 col-md-6">
			<a class="btn btn-primary float-end mr-2 '.(empty($bruteforce_logs) ? 'dis_link' : '' ).'" target="_blank" href="'.$globals['ind'].'act=bruteforce_logs&action=export_csv">'.__('Export CSV').'</a>
			<input type="button" class="btn btn-danger checkip float-end me-1 '.(empty($bruteforce_logs) ? 'dis_link' : '' ).'" name="clr_all_log" value="'.__('Clear All Logs').'" data-action="clr_all_log"/>
		</div>
	</div>
	
	<div class="col-12 mx-auto mb-5">
		<div class="table-responsive">
			<table class="table sai_form webuzo-table" >
				<thead class="sai_head2" style="background-color: #EFEFEF;">
				<caption class="caption-top text-center">'.__('Failed Login Attempts Logs').'</caption>	
					<tr>
						<th width="2%" class="align-middle text-center">
						<input type="checkbox" name="check_all" id="check_all" value=0></th>
						<th width="10%" class="align-middle text-center">'.__('IP').'</th>
						<th width="10%" class="align-middle">'.__('Attempted Username').'</th>
						<th width="10%" class="align-middle text-center">'.__('Last Failed Attempt').'</th>
						<th width="10%" class="align-middle text-center">'.__('Failed Attempts Count').'</th>
						<th width="10%" class="align-middle text-center">'.__('Lockouts Count').'</th>
						<th width="40%" class="align-middle">'.__('URL Attacked').'</th>
					</tr>			
				</thead>
				<tbody>';
				foreach ($bruteforce_logs as $key => $value) {
					echo 
					'<tr>
						<td class="align-middle text-center"><input class="fail_logins" type="checkbox" name="failed_attempts[]" value="'.$value['ip'].'">
						</td>
						<td class="align-middle text-center">'.$value['ip'].'</td>
						<td class="align-middle">'.$value['username'].'</td>
						<td class="align-middle text-center">'.date("d/m/Y", $value['time']).'</td>
						<td class="align-middle text-center">'.$value['count'].'</td>
						<td class="align-middle text-center">'.$value['lockout'].'</td>
						<td class="align-middle text-center">'.str_replace(['>', '<'], ['&gt;', '&lt;'], $value['url']).'</td>
					</tr>';
				}
				echo '</tbody>
			</table>
		</div>
	</div>
	</form>
</div>

    <script>

        $(document).ready(function(){

            $("#check_all").click(function(){
                let selbtn = $(this).val();
                if(empty(selbtn)){
                    $("#check_all").val("1");
                    $(".fail_logins").prop("checked", true);
                }else{
                    $("#check_all").val("0");
                    $(".fail_logins").prop("checked", false);
                }
            });

            $(".checkip").click(function(event){

                let act_btn = $(this).data("action");
                
                event.preventDefault();

                let d = { action : act_btn };

                // select checkbox validation
                if(act_btn != "clr_all_log"){

                    let chxbox = $(\'input[name="failed_attempts[]"]:checked\').length;

                    if(chxbox == "" || chxbox == 0){
                        var a = show_message_r("'.__js('Error').'", "'.__js('Please select checkbox !').'");
                        a.alert = "alert-danger"
                        show_message(a);
                        return false;
                    }

                }
                
                if(act_btn == "rmv_from_log" || act_btn == "blacklist_ips"){
                    // alert(act_btn);
                    let arr_ip = new Array();

                    $(\'input[name="failed_attempts[]"]:checked\').each(function() {
                        // console.log(this.value);
                        arr_ip.push(this.value);
                    });
                    
                    d.failed_attempts = arr_ip;

                }

                submitit(d, {
                    done_reload:window.location
                });

            });

        });

    </script>';
		
	softfooter();
	
}
