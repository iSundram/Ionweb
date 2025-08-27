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

function bruteforce_settings_theme(){
	
global $globals, $softpanel, $theme, $error, $done, $conf;

    softheader(__('Brute Force Settings'));
	
    echo '
    <style>
        .dis_link{
            pointer-events: none;
            opacity: 0.6;
        }
        .action-btn{
            font-size: 13px;
        }
    </style>
	<div class="soft-smbox p-3">
		<div class="sai_main_head">
			<i class="fas fa-cogs me-1"></i> '.__('Brute Force Settings').'
			<a type="button" class="btn btn-primary text-decoration-none float-end" href="'.$globals['index'].'act=bruteforce_logs">'.__('Brute Force Logs').'</a>
		</div>
	</div>
        
	<div class="soft-smbox p-4 mt-4">
        <!-- Modal Bl import -->
        <div class="modal fade" id="bl_import_modal" tabindex="-1" aria-labelledby="bl_importLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="bl_import_label">'.__('Import Blacklist IPs').'</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form action="" method="POST" name="bl_import_form" id="bl_import_form" class="form-horizontal" onsubmit="return submititwithdata(this)" enctype="multipart/form-data" data-donereload=1>
                        <div class="row col-md-12 d-flex mt-2 mb-2">
                            <div class="col-12 col-md-6">
                                <input type="file" accept=".csv" class="form-control" name="bl_csv" id="bl_csv">
                            </div>
                            <div class="col-12 col-md-2">
                                <input type="hidden" name="import_csv" value="import_csv">
                                <input type="hidden" name="bl_import" value="bl_import">
                                <input type="submit" style="width:100%;" class="btn btn-primary" value="'.__('Import').'" name="bl_import" id="bl_import">
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Wl import-->
        <div class="modal fade" id="wl_import_modal" tabindex="-1" aria-labelledby="wl_importLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="wl_import_label">'.__('Import Whitelist IPs').'</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form action="" method="POST" name="wl_import_form" id="wl_import_form" class="form-horizontal" onsubmit="return submititwithdata(this)" enctype="multipart/form-data" data-donereload=1>
                        <div class="row col-md-12 d-flex mt-2 mb-2">
                            <div class="col-12 col-md-6">
                                <input type="file" accept=".csv" class="form-control" name="wl_csv" id="wl_csv">
                            </div>
                            <div class="col-12 col-md-2">
                                <input type="hidden" name="import_csv" value="import_csv">
                                <input type="hidden" name="wl_import" value="wl_import">
                                <input type="submit" style="width:100%;" target="_blank" class="btn btn-primary" value="'.__('Import').'" name="wl_import" id="wl_import">
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Brutforce setting Form -->
        <form accept-charset="'.$globals['charset'].'" action="" method="post" name="bruteforce_form" id="bruteforce_form" onsubmit="return submitit(this)" data-donereload=1>
            <div class="col-12 mx-auto">
                <div class="row">
                    <div class="col-12 col-md-6 col-sm-12 mx-auto">
                        <!-- Bruteforce settings Div -->
                        <div class="soft-smbox mb-5">
                            <div class="sai_form_head">'.__('Brute Force Settings').'</div>
                            <div class="sai_form">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="max_retry" class="sai_head">'.__('Max Retries').'
                                        <span class="sai_exp">'.__('Maximum failed attempts allowed before lockout').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="number" name="max_retry" id="max_retry" class="form-control mb-2" value="'.POSTval('max_retry', (empty($conf['max_retry']) ? $globals['max_retry'] : $conf['max_retry'])).'" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="lockout_time" class="sai_head">'.__('Lockout Time').'
                                        <span class="sai_exp">'.__('In minutes').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="number" name="lockout_time" id="lockout_time" class="form-control mb-2" value="'.POSTval('lockout_time', (empty($conf['lockout_time']) ? $globals['lockout_time'] : $conf['lockout_time']) / 60).'" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="max_lockouts" class="sai_head">'.__('Max Lockouts').'
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="number" name="max_lockouts" id="max_lockouts" class="form-control mb-2" value="'.POSTval('max_lockouts', (empty($conf['max_lockouts']) ? $globals['max_lockouts'] : $conf['max_lockouts'])).'" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="lockouts_extend" class="sai_head">'.__('Extend Lockout').'
                                            <span class="sai_exp">'.__('In hours - Extend Lockout time after Max Lockouts').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="number" name="lockouts_extend" id="lockouts_extend" class="form-control mb-2" value="'.POSTval('lockouts_extend', (empty($conf['lockouts_extend']) ? $globals['lockouts_extend'] : $conf['lockouts_extend']) / 60 / 60).'" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="reset_retries" class="sai_head">'.__('Reset Retries').'
                                            <span class="sai_exp">'.__('In hours').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="number" name="reset_retries" id="reset_retries" class="form-control mb-2" value="'.POSTval('reset_retries', (empty($conf['reset_retries']) ? $globals['reset_retries'] : $conf['reset_retries']) / 60 / 60).'" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="bf_log_reset" class="sai_head">'.__('Log Retention Days').'
                                            <span class="sai_exp">'.__('In Days').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="number" name="bf_log_reset" id="bf_log_reset" class="form-control mb-2" value="'.POSTval('bf_log_reset', (empty($conf['bf_log_reset']) ? $globals['bf_log_reset'] : $conf['bf_log_reset']) / 24 / 60 / 60).'" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="email_noti" class="sai_head">'.__('Email Notification').'
                                            <span class="sai_exp">'.__('Enable/Disable email notifications to the admin').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
									<label class="switch">
										<input type="checkbox" name="email_noti" id="email_noti" '.(empty($conf['email_noti'])? '':'checked').' class="custom-control-input" value="1">
										<span class="slider"></span>
									</label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="send_email" class="sai_head">'.__('Email Address').'
                                            <span class="sai_exp">'.__('Failed login attempts notifications will be sent to this email.').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="email" name="send_email" id="send_email" class="form-control mb-2" value="'.POSTval('send_email', (empty($conf['send_email']) ? $globals['soft_email'] : $conf['send_email'])).'" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="allow_iplist" class="sai_head">'.__('Allowed IP list to Access Panel').'
                                            <span class="sai_exp">'.__('List of IPs which are allowed to access Webuzo Admin panel. If set, only these IPs will be allowed to access Admin panel. If more than one IP, IPs must be seperated by comma (,) If left empty, no restrictions will be imposed on IPs allowed. Currently supporting only IPv4 IP.').'</span>
                                        </label>
									</div>
									<div class="col-12 col-md-7">
                                        <input type="text" name="admin_panel_allowed_ips" id="admin_panel_allowed_ips" class="form-control mb-2" value="'.POSTval('admin_panel_allowed_ips', $conf['admin_panel_allowed_ips']).'" />
										<label class="sai_exp2 mt-1 me-3" id="localip_exp">'.__('127.0.0.1 will be allowed by default for internal API\'s to work.').'</label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="send_email" class="sai_head">'.__('Disable Brute Force Protection').'
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="checkbox" name="disable_brute" id="disable_brute" '.POSTchecked('disable_brute', $conf['disable_brute']).' class="custom-control-input" />
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <div class="col-12 col-md-3">
                                        <input type="submit" class="btn btn-primary action-btn"  name="save_settings" value="'.__('Save Setting').'"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-sm-12 mx-auto">
                        <!-- Error Messages Div -->
                        <div class="soft-smbox mb-5">
                            <div class="sai_form_head">'.__('Error Messages').'</div>
                            <div class="sai_form p-3">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="ip_start_range" class="sai_head">'.__('Failed Login Attempt(s)').'
                                        <span class="sai_exp">'.__('Incorrect Username or Password').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" name="fail_login" id="fail_login" class="form-control mb-2" value="'.POSTval('fail_login', $conf['msg']['fail_login']).'"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="ip_start_range" class="sai_head">'.__('Blacklisted IP(s)').'
                                        <span class="sai_exp">'.__('Your IP has been blacklisted').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" name="ip_blacklisted" id="ip_blacklisted" class="form-control mb-2" value="'.POSTval('ip_blacklisted', $conf['msg']['ip_blacklisted']).'"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label for="ip_start_range" class="sai_head">'.__('Attempts Left').'</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" name="attempt_left" id="attempt_left" class="form-control mb-2" value="'.POSTval('attempt_left', $conf['msg']['attempt_left']).'"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label class="sai_head">'.__('Lockout Error').'
                                        <span class="sai_exp">'.__('You have exceeded maximum login retries. Please try later !').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" name="lockout_err" id="lockout_err" class="form-control mb-2" value="'.POSTval('lockout_err', $conf['msg']['lockout_err']).'"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label class="sai_head">'.__('Minutes').'</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" name="minutes" id="minutes" class="form-control mb-2" value="'.POSTval('minutes', $conf['msg']['minutes']).'"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-5">
                                        <label class="sai_head">'.__('Hours').'</label>
                                    </div>
                                    <div class="col-12 col-md-7">
                                        <input type="text" name="hours" id="hours" class="form-control mb-2" value="'.POSTval('hours', $conf['msg']['hours']).'"/>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-3">
                                        <input type="submit" class="btn btn-primary action-btn"  name="save_settings" value="'.__('Save Messages').'"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
	
        <form accept-charset="'.$globals['charset'].'" action="" method="post" name="bruteforce_ip_form" id="bruteforce_ip_form" onsubmit="return submitit(this)" data-donereload=1>
            <div class="col-12 mx-auto">
                <div class="row">
                    <div class="col-12 col-md-6 col-sm-12 mx-auto">
                        <!-- Black List Ip Div -->
                        <div class="soft-smbox mb-5">
                            <div class="sai_form_head">'.__('Blacklist IP(s)').'</div>
                            <div class="sai_form p-3">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-12">
                                        <p>'.__('Enter the IP you want to blacklist').'</p>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="ip_start_range" class="sai_head">'.__('Start IP').'
                                        <span class="sai_exp">'.__('Start IP of the range').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input type="text" name="b_ip_start" id="b_ip_start" class="form-control mb-2" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-4">
                                        <label for="ip_end_range" class="sai_head">'.__('End IP (Optional)').'
                                        <span class="sai_exp">'.__('End IP of the range. If you want to blacklist a single IP leave this field blank').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input type="text" name="b_ip_end" id="b_ip_end" class="form-control mb-2" />
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <div class="col-12 col-md-12">
                                        <center><input type="submit" class="btn btn-primary ip_submit action-btn" data-type="blacklist" name="save_ip_settings" value="'.__('Add IP Range').'"/></center>
                                    </div>
                                </div>
                                <!-- BL CSV -->
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <a style="width:100%;" type="button" class="btn btn-primary action-btn" data-bs-toggle="modal" data-bs-target="#bl_import_modal">'.__('Import CSV').'</a>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <a style="width:100%;" target="_blank" href="'.$globals['ind'].'act=bruteforce_settings&action=export_csv&type=blacklist" class="btn btn-primary action-btn '.(empty($conf['blacklist']) ? 'dis_link' : '' ).'">'.__('Export CSV').'</a>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <button type="button" class="float-end btn btn-danger action-btn '.(empty($conf['blacklist']) ? 'dis_link' : '' ).'" onclick="delete_record(this)" id="delall" data-delete="all" data-key="blacklist" title="'.__('Delete').'">'.__('Delete All').'</button>
                                    </div>
                                </div>
                                <!-- CSV -->
                            </div>
                            <div class="sai_form p-3 table-responsive">
                                <table class="table sai_form webuzo-table" >			
                                    <thead class="sai_head2" style="background-color: #EFEFEF;">
                                        <tr>
                                            <th class="align-middle text-center">'.__('Start IP').'</th>
                                            <th class="align-middle text-center">'.__('End IP').'</th>
                                            <th class="align-middle text-center">'.__('Date (DD/MM/YYYY)').'</th>
                                            <th class="align-middle text-center">'.__('Options').'</th>
                                        </tr>			
                                    </thead>
                                    <tbody>';
                                    foreach ($conf['blacklist'] as $key => $value) {
                                        echo 
                                        '<tr>
                                            <td class="align-middle text-center">'.$value['start'].'</td>
                                            <td class="align-middle text-center">'.$value['end'].'</td>
                                            <td class="align-middle text-center">'.date("d/m/Y", $value['time']).'</td>
                                            <td class="align-middle text-center">
                                            <i class="fas fa-trash text-danger delete-icon" onclick="delete_record(this)" id="did'.$key['uuid'].'" data-delete="'.$key.'" data-key="blacklist" title="'.__('Delete').'"></i>
                                            </td>
                                        </tr>';
                                    }
                                    echo '</tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-sm-12 mx-auto">
                        <!-- White List Ip Div -->
                        <div class="soft-smbox mb-5">
                            <div class="sai_form_head">'.__('Whitelist IP').'</div>
                            <div class="sai_form p-3">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-12">
                                        <p>'.__('Enter the IP you want to whitelist from login').'</p>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="ip_start_range" class="sai_head">'.__('Start IP').'
                                        <span class="sai_exp">'.__('Start IP of the range').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input type="text" name="w_ip_start" id="w_ip_start" class="form-control mb-2" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12 col-md-4">
                                        <label for="ip_end_range" class="sai_head">'.__('End IP (Optional)').'
                                        <span class="sai_exp">'.__('End IP of the range. If you want to whitelist single IP leave this field blank.').'</span>
                                        </label>
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <input type="text" name="w_ip_end" id="w_ip_end" class="form-control mb-2" />
                                    </div>
                                </div>
        
                                <div class="row mb-3">
                                    <div class="col-12 col-md-12">
                                        <center><input type="submit" class="btn btn-primary ip_submit action-btn" data-type="whitelist"  name="save_ip_settings" value="'.__('Add IP Range').'"/></center>
                                    </div>
                                </div>
        
                                <!-- WL CSV -->
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <a style="width:100%;" type="button" class="btn btn-primary action-btn" data-bs-toggle="modal" data-bs-target="#wl_import_modal">'.__('Import CSV').'</a>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <a style="width:100%;" target="_blank" href="'.$globals['ind'].'act=bruteforce_settings&action=export_csv&type=whitelist"  class="btn btn-primary action-btn '.(empty($conf['whitelist']) ? 'dis_link' : '' ).'">'.__('Export CSV').'</a>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <button type="button" class="float-end btn btn-danger action-btn '.(empty($conf['whitelist']) ? 'dis_link' : '' ).'" onclick="delete_record(this)" id="delall" data-delete="all" data-key="whitelist" title="'.__('Delete').'">'.__('Delete All').'</button>
                                    </div>
                                </div>
                                <!-- CSV -->
        
                            </div>
                            <div class="sai_form p-3 table-responsive">
                                <table class="table sai_form webuzo-table" >			
                                    <thead class="sai_head2" style="background-color: #EFEFEF;">
                                        <tr>
                                            <th class="align-middle text-center">'.__('Start IP').'</th>
                                            <th class="align-middle text-center">'.__('End IP').'</th>
                                            <th class="align-middle text-center">'.__('Date (DD/MM/YYYY)').'</th>
                                            <th class="align-middle text-center">'.__('Options').'</th>
                                        </tr>			
                                    </thead>
                                    <tbody>';
                                    foreach ($conf['whitelist'] as $key => $value) {
                                        echo 
                                        '<tr>
                                            <td class="align-middle text-center">'.$value['start'].'</td>
                                            <td class="align-middle text-center">'.$value['end'].'</td>
                                            <td class="align-middle text-center">'.date("d/m/Y", $value['time']).'</td>
                                            <td class="align-middle text-center">
                                            <i class="fas fa-trash text-danger delete-icon" onclick="delete_record(this)" id="did'.$key['uuid'].'" data-delete="'.$key.'" data-key="whitelist" title="'.__('Delete').'"></i>
                                            </td>
                                        </tr>';
                                    }
                                    echo '</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
    
    <script language="javascript" type="text/javascript">

    $(document).ready(function(){

        $(".ip_submit").click(function(event){
            let type = $(this).data("type");
            let ip;

            if(type == "blacklist"){
                ip = $("#b_ip_start").val();
            }else if(type == "whitelist"){
                ip = $("#w_ip_start").val();
            }
            
            if(ip == ""){
                var a = show_message_r("'.__js('Error').'", "'.__js('Please enter Start IP !').'");
                a.alert = "alert-danger"
                show_message(a);
                return false;
                event.preventDefault();
            }
        });
    });
    
    </script>';

	softfooter();
	
}