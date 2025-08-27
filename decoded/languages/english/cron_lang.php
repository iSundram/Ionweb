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

// Bandwidth suspend error
$l['mail_error_suspend_sub'] = 'User suspension error';
$l['mail_error_suspend'] = 'Hi,

There was an error suspending the user account $user ($email) which has consumed more than the allowed bandwidth.
You can check the suspension logs of this user at the following link :
'.$globals['admin_url'].'act=tasks&actid=$task

Regards,
$sn';

// Bandwidth unsuspend error
$l['mail_error_unsuspend_sub'] = 'User unsuspension error';
$l['mail_error_unsuspend'] = 'Hi,

There was an error unsuspending the user account $user ($email) when the new bandwidth cycle started.
You can check the unsuspension logs of this user at the following link :
'.$globals['admin_url'].'act=tasks&actid=$task

Regards,
$sn';

$l['upd_avail_sub'] = APP.' Upgrades v&soft-1; is available';
$l['upd_avail'] = APP.' Upgrades v&soft-1; is available.
<br>Since \'Automatic Updates\' are OFF you will need to manually upgrade to the latest version.
<br>To do so go to:
<br>Admin Panel -> Updates -> Update '.APP.'
<br>
<br>The following information about the new version was fetched:
<br>&soft-2;
<br>
From '.APP.' Cron Jobs';

$l['upd_avail_suc_sub'] = 'Successfully Upgraded to '.APP.' v&soft-1;';
$l['upd_avail_suc'] = APP.' Upgrades v&soft-1; is available.
<br>'.APP.' successfully Upgraded to latest version.
<br>Below is the logs of the Upgrade Attempt:
<br>&soft-2;
<br>
<br>The following information about the new version was fetched:
<br>&soft-3;
From '.APP.' Cron Jobs';

$l['upd_avail_err_sub'] = 'Failed Upgrading to '.APP.' v&soft-1;';
$l['upd_avail_err'] = APP.' Upgrades v&soft-1; is available.
<br>'.APP.' tried to automatically upgrade to the latest version but failed.
<br>Below is the logs of the Upgrade Attempt:
<br>&soft-2;
<br>
<br>You will have to manually upgrade to the latest version.
<br>To do so go to:
<br>Admin Panel -> Updates -> Update '.APP.'
<br>
<br>The following information about the new version was fetched:
<br>&soft-3;
<br>
From '.APP.' Cron Jobs';

// Slave server status email
$l['storage_threshold_sub'] = 'Storage Usage Limit reached the threshold value !!!';
$l['storage_threshold'] = 'The following storage(s) exceeded the threshold value set. Please take the necessary actions.

$storage_list

Regards,
$sn $ip ($hostname)';

// Background processes killed email
$l['bg_process_killed_sub'] = 'Background Process Killed';
$l['bg_process_killed'] = 'Following malicious activity was found on your server, we have killed all the malicious processes.
Details of the process are listed below :

$processes_data

Regards,
$sn $ip ($hostname)';



