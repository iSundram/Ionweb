<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function emailtemp_theme() {
}else{
echo '
<div class="sai_form table-responsive">';
error_handle($error);
echo '
<table border="0" cellpadding="8" cellspacing="1" class="table webuzo-table">
<thead>
<tr>
<th align="center" class="sai_head">'.__('Template Name').'</th>
<th width="50" align="center" class="sai_head">'.__('Options').'</th>
</tr>
</thead>';
$email_temp_lang = ['user_band_threshold' => __('User Threshold Bandwidth Alert'),
'user_band_suspended' => __('User Suspended for Bandwidth Overuse'),
'user_band_unsuspended' => __('User Unsuspended for Bandwidth new cycle'),
'reseller_bandwidth_suspended' => __('Reseller User Suspended for Bandwidth Overuse'),
'reseller_bandwidth_unsuspended' => __('Reseller User Unsuspended for Bandwidth new cycle'),
'reseller_disk_suspended' => __('Reseller User Suspended for Disk Overuse'),
'reseller_disk_unsuspended' => __('Reseller User Unsuspended for Disk new cycle'),
'reseller_inode_suspended' => __('Reseller User Suspended for Inode Overuse'),
'reseller_inode_unsuspended' => __('Reseller User Unsuspended for Inode new cycle'),
'backup_bg' => __('Backup started in background'),
'backup' => __('Backup created successfully'),
'backup_fail' => __('Backup creation failed'),
'restore_bg' => __('Restore started in Background'),
'restore' => __('Backup restored successfully'),
'restore_fail' => __('Restore Failed'),
'import_bg' => __('Import User Account started in Background'),
'import' => __('Import User Account Successful'),
'import_fail' => __('Import User Account Failed'),
'auto_backup' => __('Automatic Backup successful'),
'auto_backup_fail' => __('Automatic Backup failed'),
'2fa_email_otp' => __('2FA Login Email OTP'),
'acc_create' => __('New Account Created'),
'acc_delete' => __('Account Deleted'),
'acc_suspend' => __('Account Suspended'),
'acc_unsuspend' => __('Account Unuspended'),
'test_cm' => __('Test Message For Contact Manager'),
'service_fails' => __('Service stopped'),
'webuzo_import_failed' => __('Webuzo Import failed'),
'webuzo_import_success' => __('Webuzo Import successful'),
'cpanel_import_failed' => __('cPanel Import failed'),
'cpanel_import_success' => __('cPanel Import successful'),
'cpanel_import_partial' => __('cPanel Import successful with Errors'),
'user_pass_changed' => __('User has changed password'),
'hourly_mail' => __('Hourly mail limit exceeded'),
'hostname_change' => __('Hostname Change Notifications'),
'add_domain' => __('Notification of newly added Domains'),
'user_email_change' => __('User mail change'),
'dns_update' => __('DNS Records notifications'),
'brute_force' => __('Brute force notifications'),
'renew_ssl_failed' => __('Automatic SSL renew failed'),
'user_bandwidth_exceed' => __('User Bandwidth Limit Exceed'),
'user_disk_usage' => __('User Disk Limit Exceed'),
'enduser_disk_usage' => __('Disk Limit Exceed'),
'storage_thresholds' => __('Storage Usage Limit reached'),
'daily_email_limit' => __('Domain Exceeded Daily Send Limit'),
'hourly_email_limit' => __('Domain Exceeded Hourly Send Limit'),
'two_factor_auth' => __('User Two-Factor Authentication'),
'backup_transport_error' => __('Failed to transport Backup'),
'da_import_success' => __('DirectAdmin Import Successful'),
'da_import_failed' => __('DirectAdmin Import Failed'),
];
$i=1;
foreach($emailtemps as $k => $v){
echo '
<tr>
<td>'.$email_temp_lang[$k].'</td>
<td align="center">
<a href="'.$globals['ind'].'act=editemailtemp&temp='.$k.'"><i class="fa-regular fa-pen-to-square"></i></a>
</td>
</tr>';
$i++;
}
echo '
</table>
</div>
</div>';
}
softfooter();
}