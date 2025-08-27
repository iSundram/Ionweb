<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
$l['mail_user_band_threshold_sub'] = 'Bandwidth Threshold Limit Crossed';
$l['mail_user_band_threshold'] = 'Hi,
You have consumed more than $threshold% of your monthly bandwidth allowance for the account $user ($email).
You have consumed $consumed MB of the allowed $allowed MB.
If you wish to unsubscribe from such emails, go to your $sn Control Panel -> Email Settings
Regards,
$sn';
$l['mail_user_band_suspended_sub'] = 'Account suspended for bandwidth overusage';
$l['mail_user_band_suspended'] = 'Hi,
Your bandwidth consumption for the month of $month, $year has crossed the allowed limit of $allowed MB.
You have used $consumed_percent% ($consumed MB) for the account $user ($email).
Hence your account has been suspended.
If you wish to unsubscribe from such emails, go to your $sn Control Panel -> Email Settings
Regards,
$sn';
$l['mail_user_band_unsuspended_sub'] = 'Account unsuspended';
$l['mail_user_band_unsuspended'] = 'Hi,
Your account $user ($email) which was suspended due to bandwidth overusage is now unsuspended.
If you wish to unsubscribe from such emails, go to your $sn Control Panel -> Email Settings
Regards,
$sn';
$l['mail_add_user_sub'] = 'Welcome to Softaculous Remote Installer';
$l['mail_add_user'] = 'You can now successfully login into Softaculous Remote Installer.
The details are as follows :
User 					: $username
API Key 				: $api_key
API Password 			: $api_pass
Number of Users allowed	: $number_of_users';
$l['mail_backup_sub'] = '$type backup completed successfully';
$l['mail_backup'] = 'The backup process was completed successfully.
Backup file is created with the file name:
$filename
Regards,
Webuzo Team';
$l['mail_backup_fail_sub'] = '$type backup failed';
$l['mail_backup_fail'] = 'The backup process did not complete successfully.
The following error(s) occured:
$error
Regards,
Webuzo Team';
$l['mail_restore_sub'] = '$type restore completed successfully';
$l['mail_restore'] = 'The restoration was completed successfully.
The file restored was:
$filename
Regards,
Webuzo Team';
$l['mail_restore_fail_sub'] = '$type restore failed';
$l['mail_restore_fail'] = 'The restore process encountered errors and would not be completed.
The following error(s) occured:
$error
Regards,
Webuzo Team';
$l['mail_upgrade_fail_sub'] = 'Failed : Upgrade of your $scriptname installation';
$l['mail_upgrade_fail'] = 'The upgrade of your $scriptname installation did not complete successfully.
The details are as follows :
Installation Path : $path
Installation URL : $url
The following error occured :
$error
Please try to upgrade again after some time.
If you wish to unsubscribe from such emails, go to your $sn Control Panel -> Email Settings
';
$l['mail_backup_bg_sub'] = '$type backup started in background';
$l['mail_backup_bg'] = 'The $type backup process is started in background.
Backup file is :
$filename
Regards,
Webuzo Team';
$l['mail_restore_bg_sub'] = '$type backup restore in background';
$l['mail_restore_bg'] = 'The $type backup process is restore in background.
restore file is :
$filename
Regards,
Webuzo Team';
$l['mail_auto_backup_sub'] = '$type auto backup completed successfully';
$l['mail_auto_backup'] = 'The auto backup process was completed successfully.
auto Backup file is created with the file name:
$filename
Regards,
Webuzo Team';
$l['mail_auto_backup_fail_sub'] = '$type auto backup failed';
$l['mail_auto_backup_fail'] = 'The auto backup process did not complete successfully.
The following error(s) occured:
$error';
$l['mail_import_sub'] = 'The import process of users completed successfully';
$l['mail_import'] = 'Hi,
The  import process of users completed successfully
See the list of users in list users section
Regards,
Webuzo Team';
$l['mail_import_fail_sub'] = 'Failed: The import process of users is failed ';
$l['mail_import_fail'] = 'The import  process encountered errors and would not be completed.
The users could not be imported successfully.
The following error(s) occured:
$error
Regards,
Webuzo Team';
$l['mail_import_bg_sub'] = 'The import process of users started in background';
$l['mail_import_bg'] = 'Hi,
The import process of users is started, once it is done successfully you can see the import users in list user section.
Regards,
Webuzo Team';
$l['mail_2fa_email_otp_sub'] = 'OTP : Login at $sn';
$l['mail_2fa_email_otp'] = 'Hi,
A login request was submitted for your account $username ($email) at $sn - $site_hostname
Please click on the link below to login to your account :
$login_url
Or use the following One Time password (OTP) to login :
$otp
Note : The OTP expires after 10 minutes.
If you haven\'t requested for the OTP, please ignore this email.
Regards,
$sn
';