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

$l['no_reseller_access'] = 'Resellers are not allowed to access this page';

//Tab Icons Images
$l['tab_home'] = 'Home';
$l['tab_user_management'] = 'Users';
$l['tab_plans'] = 'Plans';
$l['tab_reseller'] = 'Resellers';
$l['tab_settings'] = 'Settings';
$l['tab_webuzoconfigs'] = 'Panel Config';
$l['tab_services'] = 'Services';
$l['tab_updates'] = 'Updates';
$l['tab_softwares'] = 'Software';
$l['tab_logs'] = 'Logs';
$l['tab_categories'] = 'Categories';
$l['tab_email'] = 'Email';
$l['tab_email_queue'] = 'Email Queue';
$l['tab_email_deliverability'] = 'Email Deliverability';
$l['tab_emails'] = 'Emails';
$l['tab_proxy'] = 'Proxy';
$l['tab_maintenance'] = 'Maintenance';
$l['tab_installations'] = 'Installations';
$l['tab_logout'] = 'Logout';
$l['tab_plans'] = 'Plans';
$l['tab_mail_trmplate'] = 'Email Templates';
$l['tab_error_log'] = 'Error Logs';
$l['tab_php'] = 'PHP';
$l['tab_disable_autoupgrade'] = 'Auto Upgrade';
$l['tab_goto_enduser'] = 'Enduser Panel';
$l['sub_license'] = 'Manage License';
$l['tab_root_pass'] = 'Change Root Password';
$l['mysql_root_pass'] = 'MySQL Root Password';
$l['tab_webuzo_acl'] = 'Webuzo ACL';
$l['tab_domain'] = 'Domain';
$l['tab_update_ssl_cert'] = 'SSL Certficate';
$l['tab_auto_installer'] = 'Auto installer';
$l['appsinst'] = 'Install an App';
$l['apps_ins_hf'] = 'Installed Apps';
$l['apps_updates'] = 'Update Apps';
$l['services'] = 'Services';
$l['apache'] = 'Apache';
$l['mysql'] = 'MySQL';
$l['servers'] = 'Servers';
$l['list_servers'] = 'List Servers';
$l['add_server'] = 'Add Server';
$l['manage_server'] = 'Manage Server';
$l['server_stats'] = 'Server Stats';
$l['servergroups'] = 'Server Groups';
$l['addsg'] = 'Add Server Group';
$l['security'] = 'Security';
$l['ipblock'] = 'IP Block';
$l['csf_conf'] = 'CSF Configuration';
$l['ssh_access'] = 'SSH Access';
$l['compiler_access'] = 'Compiler Access';
$l['manage_wheel'] = 'Manage Wheel Group';
$l['host_access'] = 'Host Access Control';
$l['apache_settings'] = 'Apache Settings';
$l['nginx_settings'] = 'Nginx Settings';
$l['lighttpd_settings'] = 'Lighttpd Settings';
$l['apache_conf'] = 'Apache Configuration';
$l['nginx_conf'] = 'Nginx Configuration';
$l['lighttpd_conf'] = 'Lighttpd Configuration';
$l['mysql_conf'] = 'MySQL Configuration';
$l['ssh'] = 'SSH';
$l['def_apps_util'] = 'Default Apps';
$l['webuzo_exim'] = 'Exim Configuration';
$l['webuzo_varnish'] = 'Varnish';
$l['configuration'] = 'Configuration';
$l['webuzo_filter_by_domains'] = 'Filter incoming email by domains';
$l['webuzo_filter_by_country'] = 'Filter incoming email by country';
$l['tab_mail_settings'] = 'Mail settings';
$l['server_utilities'] = 'Server Utilities';
$l['cpu'] = 'CPU';
$l['ram'] = 'Ram';
$l['import_webuzo'] = 'Import From Webuzo';
$l['server_reboot'] = 'Server Reboot';
$l['service_tls'] = 'Manage Service Certificate';
$l['auto_ssl'] = 'Automatic SSL';
$l['webuzo_ftp_management'] = 'FTP Server Config';
$l['reseller'] = 'Reseller';
$l['regular'] = 'Regular';
$l['tab_system_health']='System Health';
$l['tab_process_manager'] = 'Process Manager';
$l['tab_current_disk_usage'] = ' Current Disk Usage';
$l['tab_c_running_processes'] = 'Current Running Processes';
$l['email_sent_summary'] = 'View Sent Summary';
$l['tab_support'] = 'Support';
$l['tab_emailstats'] = 'Email Statistics Summary';
$l['tab_email_queue_manager'] = 'Email Queue Manager';
$l['assign_ipv6'] = 'Assign IPv6 Address';

// Sub Menus
$l['sub_add_user_manager'] = 'Add User';
$l['sub_list_user_manager'] = 'List Users';
$l['list_resellers'] = 'List Resellers';
$l['reset_resellers'] = 'Reset Resellers';
$l['demo_users'] = 'Demo Users';
$l['email_all'] = 'Email All Users';
$l['download_logs'] = 'Download User logs';
$l['raw_logs'] = 'Raw $webserver logs';
$l['skeleton_directory'] = 'Skeleton Directory';
$l['sub_suspended_user'] = 'Suspended Users';
$l['sub_manage_suspended'] = 'Manage Account Suspension';
$l['domains'] = 'Domains List';
$l['park_domain'] = 'Park a Domain';
$l['domain_forwarding'] = 'Domain Forwarding';
$l['parked_domains'] = 'List Parked Domains';
$l['alias_domains'] = 'List Aliases';
$l['subdomains'] = 'List Sub Domains';
$l['addon_domains'] = 'List Addon Domains';
$l['redirect_list'] = 'Domain Redirects';
$l['shell_users'] = 'Manage Shell Access';
$l['sub_add_plan'] = 'Add Plan';
$l['sub_list_plans'] = 'List Plans';
$l['sub_outdated'] = 'Outdated Installations';
$l['sub_byuser'] = 'By Users';
$l['sub_byscript'] = 'By Scripts';
$l['sub_gen_set'] = 'General';
$l['tab_import'] = 'Import';
$l['tab_dis_features'] = 'Disable Features';
$l['sub_custom_scripts'] = 'Custom Scripts';
$l['sub_general_scripts'] = 'General Scripts';
$l['adv_software'] = 'Advanced Settings';
$l['tab_tools'] = 'Tools';
$l['sub_top_scripts'] = 'Select Top Scripts';
$l['list_plan'] = 'List Plan(s)';
$l['add_plan'] = 'Add Plan';
$l['sub_upgrade'] = 'Upgradable';
$l['add_user'] = 'Add User';
$l['list_user'] = 'List Users';
$l['sub_import_export'] = 'Import/Export';
$l['sub_template_editor'] = 'Web Template Editor';
$l['sub_change_domain_ip'] = 'Change Domain IP';
$l['sub_script_req'] = 'Scripts Requirements';
$l['sub_bydomain'] = 'By Domains';
$l['sub_ins_statistics'] = 'Statistics';
$l['sub_add_domain'] = 'Add Domain';
$l['sub_list_domain'] = 'List Domain(s)';
$l['sub_manage_sets'] = 'Manage WordPress Sets';
$l['softaculous'] = 'Softaculous';
$l['sub_rebrand_gen_set'] = 'Rebranding';
$l['sub_update_gen_set'] = 'Update';
$l['sub_privileges'] = 'Reseller Privileges';
$l['sub_sharedip'] = 'Reseller\'s Shared IP';
$l['sub_ipdelegation'] = 'Reseller\'s IP Delegation';
$l['sub_change_owner'] = 'Change Ownership Of Multiple Accounts';
$l['sub_show_reseller'] = 'Show Reseller Accounts';
$l['sub_ip_usage'] = 'Show IP Address Usage';
$l['sub_view_bandwidth'] = 'View Bandwidth Usage';
$l['sub_reserved_ip'] = 'Reserved/Locked IPs';
$l['sub_multiphp_manager'] = 'MultiPHP Manager';
$l['sub_reset_bandwidth'] = 'Reset Account Bandwidth Limit';
$l['sub_quota_modification'] = 'Quota Modification';
$l['sub_email_reseller'] = 'Email All Resellers';
$l['networking'] = 'Networking';
$l['dns_functions'] = 'DNS Functions';
$l['list_ips'] = 'List IPs';
$l['add_ips'] = 'Add IP';
$l['dns_zone'] = 'DNS Zones';
$l['adz_add'] = 'Add DNS Zone';
$l['set_ttl'] = 'Set DNS Zones TTL';
$l['cleanup_dns'] = 'Cleanup DNS';
$l['resolv_cnf'] = 'Resolver Configuration';
$l['change_host'] = 'Change Hostname';
$l['add_a_record'] = 'Add A Record for Hostname';
$l['iplogs'] = 'IP Logs';
$l['storage'] = 'Storage';
$l['list_storage'] = 'List Storage';
$l['add_storage'] = 'Add Storage';
$l['sub_list_features'] = 'Feature Sets';
$l['sub_add_features'] = 'Add Feature Sets';
$l['chose_manually'] = 'Choose manually';
$l['disable_reseller_admin_t'] = 'Reseller Panel Disabled';
$l['disable_reseller_admin'] = 'Reseller Panel is Disabled for this User. Please contact System Administrator.';
$l['feature_not_available'] = 'NOTE: This feature is disabled in the free version of '.APP;
$l['all_features_enabled'] = 'Enable all features';
$l['pass_strength'] = 'Password Strength';
$l['sub_support_ticket'] = 'Create Support Ticket';
$l['sub_support_resources'] = 'Support Center';
$l['rearrange_account'] = 'Rearrange User Account';
$l['backups'] = 'Backup and Restore';
$l['create_backup'] = 'Scheduled Backups';
$l['restore_backup'] = 'Restore Backup';
$l['add_backup_server'] = 'Add Backup Servers';
$l['contact_manager_head'] = 'Contact Manager';
$l['system_mail_head'] = 'System Mail Preferences';
$l['dns_template_head'] = 'DNS Templates';
$l['sh_heading'] = 'Shell Fork Bomb Protection';
$l['log_configure'] = 'Log Rotation Config';
$l['ip_migration'] = 'IP Migration';

// Page Jump Related :
$l['submit'] = 'Submit';
$l['user_name'] = 'User Name';

$l['search_apps'] = 'Search Apps';
$l['search_ins_menu'] = 'Search Installations';
$l['no_data'] = 'No data found !';

// Plan fields
$l['plan_default'] = 'Default';
$l['ftp_accounts'] = 'FTP Account';
$l['ftp_accounts_exp'] = 'Mention FTP account';
$l['disk_block_limit'] = 'Disk Space Quota (MB)';
$l['disk_block_limit_exp'] = 'Enter disk space in (MB)';
$l['p_custom'] = 'Custom';
$l['p_unlimited'] = 'Unlimited';
$l['cat_resource'] = 'Resource';
$l['cat_settings'] = 'Settings';
$l['cat_fetaure'] = 'Features';

$l['plan_name'] = 'Plan Name';
$l['max_disk_limit'] = 'Disk Space Quota (MB)';
$l['max_bandwidth_limit'] = 'Monthly Bandwidth Limit (MB)';
$l['max_ftp_account'] = 'Max FTP Accounts';
$l['max_email_account'] = 'Max Email Accounts';
$l['max_quota_email'] = 'Max Quota per Email Address (MB)';
$l['max_mailing_list'] = 'Max Mailing Lists';
$l['max_database'] = 'Max SQL Databases';
$l['max_subdomain'] = 'Max Sub Domains';
$l['max_parked_domain'] = 'Max Parked Domains';
$l['max_addon_domain'] = 'Max Addon Domains';
$l['max_passenger_applications'] = 'Max Passenger Applications';
$l['max_hourly_email'] = 'Max Hourly Email by Domain Relayed';
$l['max_percent_failed'] = 'Max percentage of failed or deferred messages a domain may send per hour';
$l['max_inode'] = 'Max Inodes';

// Plan settings field
$l['options'] = 'Options';
$l['dedicate_ip_opt'] = 'Dedicated IPv4';
$l['dedicated_ipv6'] = 'Dedicated IPv6';
$l['shell_acess_opt'] = 'Shell Access';
$l['deny_cron'] = 'Deny Cron';
$l['compiler_access_opt'] = 'Compiler Access';
$l['sa_access_opt'] = 'Enable SpamAssassin';
$l['cgi_access_opt'] = 'CGI Access';
$l['digest_authentication_opt'] = 'Digest Authentication at account creation.';

$l['theme_setting'] = 'Webuzo Theme';
$l['locale_setting'] = 'Locale';
$l['select_all_feat'] = 'Select All';

// Plan features field - Domains
$l['mngdomain_feat'] = 'Manage Domains';
$l['addondomains_feat'] = 'Add Domains';
$l['redirects_feat'] = 'Redirects';
$l['dnszonesetting_feat'] = 'DNS Zone Settings';
$l['networktools_feat'] = 'Network Tools';
$l['extraconfig_feat'] = 'Extra Configuration';
$l['aliases_feat'] = 'Aliases';

// Database
$l['mngdbs_feat'] = 'Manage Databases';
$l['adddb_feat'] = 'Add Database';
$l['phpmyadmin_feat'] = 'phpMyAdmin';

// FTP
$l['mngftp_feat'] = 'Manage FTP';
$l['addftpacc_feat'] = 'Add FTP Account';
$l['ftpconnections_feat'] = 'FTP Connections';

// SSL
$l['privkeys_feat'] = 'Private Keys';
$l['certsigningreq_feat'] = 'Cert Signing Request';
$l['certificate_feat'] = 'Certificate';
$l['installcertificate_feat'] = 'Install Certificate';
$l['letsencrypt_feat'] = 'Lets Encrypt';

// Emails
$l['emailacc_feat'] = 'Email Account';
$l['emailforward_feat'] = 'Email Forwarders';
$l['mxentry_feat'] = 'MX Entry';
$l['accessmail_feat'] = 'Access Email';
$l['mailsetting_feat'] = 'Email Settings';
$l['spamassa_feat'] = 'Spam Assassin';
$l['autoresponders_feat'] = 'Autoresponders';
$l['email_delivery_report'] = 'Email Delivery Report';
$l['email_relayers'] = 'View Relayers';
$l['email_disk_usage'] = 'Email Disk Usage';
$l['spamd_startup_config'] = 'Spamd Startup Config';
$l['email_routing_config'] = 'Email Routing Config';
$l['email_greylisting'] = 'Greylisting';

// Configurations
$l['phpconfig_feat'] = 'PHP';
$l['apacheconfig_feat'] = 'Apache';
$l['mysqlconfig_feat'] = 'MySql';
$l['apachesettings_feat'] = 'Apache Settings';
$l['eximconfig_feat'] = 'Exim';
$l['php_ext'] = 'PHP Extensions';
$l['php_pear_mod'] = 'PHP PEAR packages';

// Security
$l['pass_feat'] = 'Change Password';
$l['csf_feat'] = 'CSF Configuration';
$l['ipblock_feat'] = 'IP Block';
$l['hotlinkprotect_feat'] = 'Hotlink Protect';
$l['manage_root_ssh'] = 'Manage Root SSH Keys';
$l['ssh_access'] = 'SSH';
$l['pass_protect_dir'] = 'Directory Privacy';
$l['pass_strength'] = 'Password Strength';

// Advance Settings
$l['advance_setting_feat'] = 'Advanced settings';
$l['apps_updates_feat'] = 'Update Apps';
$l['settings_feat'] = 'Edit Settings';
$l['cron_feat'] = 'Cron Job';
$l['php_ext_feat'] = 'PHP Extensions';
$l['system_utilities_feat'] = 'Default Apps';
$l['services_feat'] = 'Services';

//Server Utitlities
$l['server_setting_feat'] = 'Server Utilities';
$l['filemanager_feat'] = 'File Manager';
$l['backup_feat'] = 'Backups';
$l['awstats_feat'] = 'AWStats';
$l['login_logs_feat'] = 'Login Logs';
$l['import_cpanel_feat'] = 'Import From cPanel';
$l['apache_tomcat_feat'] = 'Apache Tomcat';
$l['svn_manager_feat'] = 'SVN Management';
$l['server_reboot_feat'] = 'Server Reboot';
$l['import_from_webuzo_feat'] = 'Import From Webuzo';
$l['smtp_restrictions'] = 'SMTP Restrictions';

//Apps
$l['wp_manage_feat'] = 'WordPress Manager';

//Server Info
$l['cpu_feat'] = 'CPU';
$l['ram_feat'] = 'RAM';
$l['disk_feat'] = 'Disk';
$l['bandwidth_feat'] = 'Bandwidth';
$l['error_log_feat'] = 'Error Log';

// Scripts
$l['installations_feat'] = 'All Installations';
$l['backups_feat'] = 'Backups';

$l['multi_user_req'] = 'You need a Multi User License to use this feature !';
$l['backup_settings'] = 'Enduser Backup Settings';

// Plugins
$l['main_plugins'] = 'Plugins';
$l['inst_plugins'] = 'Installed Plugins';

// Reseller privileges
$l['acc_crt_limit'] = 'Account Creation Limits';
$l['tot_acc_limit'] = 'Number of accounts';
$l['tot_acc_limit_exp'] = 'Limit the total number of accounts reseller can create';
$l['allow_overselling'] = 'Allow Overselling';
$l['acc_management'] = 'Account Management';
$l['create_acc'] = 'Create Accounts';
$l['terminate_acc'] = 'Disable Terminate Accounts';
$l['suspend_acc'] = 'Disable Suspend/Unsuspend Accounts';
$l['upgrade_acc'] = 'Upgrade/Downgrade Accounts';
$l['chg_passwd_acc'] = 'Disable Change Passwords';
$l['list_acc'] = 'List Accounts';
$l['show_bandwidth'] = 'View Account Bandwidth Usage';
$l['disable_shell'] = 'Disable Creation of Accounts with Shell Access';
$l['disable_plan'] = 'Disable Plans';
$l['view_status'] = 'View Server Status';
$l['view_info'] = 'View Server Information';
$l['server_infomation'] = 'Server Information';

// Graph
$l['webuzo_cpu'] = 'CPU';
$l['webuzo_ram'] = 'RAM';
$l['webuzo_disk'] = 'Disk';
$l['editini'] = 'Edit PHP INI';
$l['pm_lbl_head'] = 'Process Manager';
$l['msv_lbl_head'] = 'ModSecurity Vendors';
$l['msc_lbl_head'] = 'ModSecurity Configuration';
$l['mst_lbl_head'] = 'ModSecurity Tools';
$l['msrl_lbl_head'] = 'ModSecurity Rules';

// SQL Services
$l['sql_services'] = 'SQL Services';
$l['mysql_manage_dbusers'] = 'Manage Database Users';
$l['mysql_change_dbuser_pass'] = 'Change MySQL User Password';
$l['mysql_additional_access_hosts'] = 'Additional MySQL Access Hosts';
$l['mysql_manage_dbs'] = 'Manage Databases';
$l['mysql_show_processes'] = 'Show MySQL Processes';
$l['mysql_profile_settings'] = 'MySQL Profile Settings';
$l['mysql_repair_database'] = 'Repair MySQL Database';
$l['mysql_dbmap_tool'] = 'Database Map Tool';

// Notices lang
$l['notice_head'] = 'Notices';
$l['ser_no_notices'] = 'The server is running just fine ! There are no notices.';
$l['notice_service'] = 'Your <b>$service</b> service is not running currently.';
$l['task_restart_log'] = 'Service <b>$service</b> restarted';
$l['task_restartfailed_log'] = '<b>$service</b> failed to start';
$l['notice_service_title'] = '<b>$service</b> service is <b>$status</b>';
$l['task_stop_log'] = 'Webuzo tried to start <b>$service</b>, but the service failed to start';
$l['notice_restarted_info'] = 'Webuzo has started <b>$service</b>, which was stopped';

// News lang
$l['no_news'] = 'There is no Admin news';
$l['no_32bit'] = 'Apps cannot be installed on 32-bit Systems';

// Domain info
$l['validate_mails_label'] = 'Mail Validation records (Optional)';
$l['exp_validate_mails'] = 'Tick to add SPF, DKIM and DMARC records in the DNS for your primary domain';

// Notice mail send for domain
$l['notify_email_title'] = '<b>$domain</b> exceeded email send limit';
$l['notify_email_body'] = 'The domain <b>$domain</b> of user <b>$user</b> has exceeded their daily email send limit (Note: This notice will automatically be dismissed on a daily basis)';

$l['tab_webuzo_updates'] = 'Webuzo Update';
$l['tab_system_update'] = 'System Update';
$l['tab_rebuild_rpmdb'] = 'Rebuild RPM Database';
$l['tab_distropackage'] = 'Install Distro Packages';
$l['perl_module_ins'] = 'Install a Perl Module';

// Restart Services
$l['restart_services'] = 'Restart Services';

// Rebuild the IP Address Pool
$l['rebuild_pool'] = 'Rebuild IP\'s Pool';

$l['tab_daily_process_log'] = 'Daily Process Log';
$l['tab_bg_process_killer'] = 'Background Process Killer';
$l['email_troubleshooter'] = 'Email Troubleshooter';
$l['tab_bruteforce'] = 'Brute Force';
$l['tab_bruteforce_logs'] = 'Brute Force Logs';