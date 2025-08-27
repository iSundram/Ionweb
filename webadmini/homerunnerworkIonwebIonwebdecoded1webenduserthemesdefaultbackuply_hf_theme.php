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

function load_backuply_icons(){
	
$icons = [
	'restore' => [
		'name' => 'Restore and Download',
		'fa-icon' => 'fa fa-undo',
		'icons' => [
			'backupfull' => [
				'label' => 'Full Accounts',
				'fa-icon' => 'fas fa-cubes',
				'href' => 'index.php?act=sel_full_files',
				'tabtype' => 'full',
				],
			'backuphome' => [
				'label' => 'Home Directory',
				'fa-icon' => 'fas fa-folder',
				'href' => 'index.php?act=sel_home_files',
				'tabtype' => 'home',
				],
			'backupdata' => [
				'label' => 'Database',
				'fa-icon' => 'fas fa-database',
				'href' => 'index.php?act=sel_database_files',
				'tabtype' => 'data',
				],
			'backupdatausers' => [
				'label' => 'Database Users',
				'fa-icon' => 'fas fa-user-tie',
				'href' => 'index.php?act=sel_databaseuser_files',
				'tabtype' => 'datausers',
				],
			'backupdomain' => [
				'label' => 'Domains',
				'fa-icon' => 'fas fa-globe',
				'href' => 'index.php?act=sel_domain_files',
				'tabtype' => 'domains',
				],
			'backupcert' => [
				'label' => 'Certificates',
				'fa-icon' => 'fas fa-lock',
				'href' => 'index.php?act=sel_cert_files',
				'tabtype' => 'certs',
				],
			'backupmail' => [
				'label' => 'Email Accounts',
				'fa-icon' => 'fas fa-envelope',
				'href' => 'index.php?act=sel_email_files',
				'tabtype' => 'emails',
				],
			'backupcronjob' => [
				'label' => 'Cron Jobs',
				'fa-icon' => 'fas fa-user-clock',
				'href' => 'index.php?act=sel_cronjob_files',
				'tabtype' => 'cronjobs',
				],
			'backupftp' => [
				'label' => 'FTP Accounts',
				'fa-icon' => 'fas fa-file',
				'href' => 'index.php?act=sel_ftp_files',
				'tabtype' => 'ftps',
				],
			],
		],
		'view' => [
			'name' => 'View and Manage',
			'fa-icon' => 'fa fa-download',
			'icons' => [
				'downloads' => [
					'label' => 'View Downloads',
					'fa-icon' => 'fas fa-download',
					'href' => 'index.php?act=sel_download_files',
					'tabtype' => 'downloads',
					],
				'queues' => [
					'label' => 'Queues',
					'fa-icon' => 'fas fa-tasks',
					'href' => 'index.php?act=sel_queue_files',
					'tabtype' => 'queues',
					],
			],
		],
	];

	return $icons;
}

function backuply_tabs($active){
	
	global $globals, $theme;
	
	$bicons = load_backuply_icons();
	
	$tabs = $bicons['restore']['icons'];
	
	echo '<div class="sai_main_head mb-4">
			<div class="row">
				<div class="col-6">
					<img src="'.$theme['images'].'backup_restore.png" alt="" class="webu_head_img me-2"/>
					<h5 class="d-inline-block">'.__('Backuply').'</h5>
				</div>
				<div class="col-6 ">
					
				</div>
			</div>
		</div>';
	
	echo '<ul class="nav nav-tabs mb-3 webuzo-tabs" id="pills-tab" role="tablist">';
	foreach($tabs as $tk => $tv){
		echo '
		<li class="nav-item" role="presentation">';
			echo 
			'<a class="nav-link '.($tk == $active ? 'active' : '').'" id="'.$tk.'_a" href="'.$tv['href'].'" role="tab" aria-controls="'.$tk.'" aria-selected="false" data-tab="'.$tv['tabtype'].'"><i class="'.$tv['fa-icon'].' text-center"></i> '.__($tv['label']).'</a>';
		echo '
		</li>';
		
	}
	echo '</ul>';
	
	echo '<script>
		$(".nav-link").click(function(){
			$(".loading").show();
		})
	</script>';
}

// Required for backuply to get the specific backup file info
function backup_file_data($user, $type, $filename){
	
	global $globals, $error, $softpanel;
	
	if(!in_array($type, array('full', 'data', 'home', 'mail'))){
		$error[] = __('Backup type not proper');
		return false;
	}

	$conn_backups = wdb_backuply();
	
	$bdata = [];
	
	$q = 'SELECT * FROM backups WHERE user = "'.$user.'" AND filename = "'.$filename.'"';
	// echo $q;
	$res = wdb_query($conn_backups, $q);
	// r_print(wdb_fetch($res));
	if(!empty($res)){
		while($row = array_unique(wdb_fetch($res))){
			
			
			$comp_bac = $softpanel->user_func_exec($row['user'], 'file_exists', [$row['backup_location'].'/'.$row['filename']]);
			$incr_bac = $softpanel->root_func_exec('file_exists', [$row['backup_location'].'/'.$row['user'].'/'.$row['filename']]);

			if($row['backup_server_id'] == -1 && (empty($comp_bac) && empty($incr_bac))){
				$row['missing'] = 1;
			}
			
			// r_print($row); continue;
			if($row['backup_server_id'] == -1){
				// $row['size'] = $softpanel->root_func_exec('filesize', [$row['backup_location'].'/'.$row['user'].'/'.$row['filename']]);
			}
			
			$row['dbs_list'] = unserialize($row['dbs_list']);
			$row['admin_backup'] = 1;
			$wbackup_list[$row['type']][] = $row;
		}
	}
	// r_print($wbackup_list);
	foreach($wbackup_list[$type] as $key => $value){
		if($value['filename'] == trim($filename)){
			$bdata = $value;
		}
	}
	
	return $bdata;
}

// Backuply fetch data table wise
function backuply_get_table($user, $table, $where = ''){
	
	global $globals, $error;
	
	$conn_backups = wdb_backuply();

	$backuply_tables = ['databases', 'databaseusers', 'emails', 'ftps', 'certificates', 'crons', 'domains'];
	
	if(!in_array($table, $backuply_tables)){
		$error[] = __('Invalid data request !');
		return false;
	}
	
	$data = [];
	
	$q = 'SELECT '.$table.'.name AS name, '.$table.'.size AS size, GROUP_CONCAT(backups.filename, ", ") AS backup_files FROM '.$table.' JOIN backups ON '.$table.'.backup_id = backups.id WHERE backups.user = "'.$user.'" '.(!empty($where) ? 'AND '.$where : '').' GROUP BY '.$table.'.name';
	
	// echo $q;
	
	$res = wdb_query($conn_backups, $q);
	// r_print($res);
	if(!empty($res)){
		while($row = wdb_fetch($res)){
			// r_print($row);
			$data[$table][$row['name']] = $row;
		}
	}
	
	return $data;
}

// Create connection for Backuply
function wdb_backuply(){
	
	global $globals, $softpanel;
	
	// $globals['backuply_path'] = '/var/backuply';
	
	if(!is_dir($globals['backuply_path'])){
		$softpanel->root_func_exec('mkdir', [$globals['backuply_path']]);
		$softpanel->root_func_exec('chown', [$globals['backuply_path'], $globals['panel_user']]);
		$softpanel->root_func_exec('chgrp', [$globals['backuply_path'], $globals['panel_user']]);
	}
	
	$backuply_db = $globals['backuply_path'].'/backups.db';

	if(!file_exists($backuply_db)){
		
		$sql[] = 'CREATE TABLE IF NOT EXISTS `backups` (
		`id` INTEGER PRIMARY KEY AUTOINCREMENT,
		`date` INTEGER,
		`created` INTEGER,
		`user` TEXT,
		`backup_method` TEXT,
		`type` TEXT,
		`server_name` TEXT,
		`backup_server_id` TEXT,
		`backup_location` TEXT,
		`backup_notes` TEXT,
		`filename` TEXT UNIQUE);';
		
		$sql[] = 'CREATE TABLE IF NOT EXISTS `databases` (
		`id` INTEGER PRIMARY KEY AUTOINCREMENT,
		`backup_id` INTEGER,
		`created` INTEGER,
		`name` VARCHAR(255),
		`size` VARCHAR(255),
		FOREIGN KEY (backup_id) REFERENCES backups(id));';
		
		$sql[] = 'CREATE TABLE IF NOT EXISTS `databaseusers` (
		`id` INTEGER PRIMARY KEY AUTOINCREMENT,
		`backup_id` INTEGER,
		`created` INTEGER,
		`name` VARCHAR(255),
		`size` VARCHAR(255),
		FOREIGN KEY (backup_id) REFERENCES backups(id));';
		
		$sql[] = 'CREATE TABLE IF NOT EXISTS `domains` (
		`id` INTEGER PRIMARY KEY AUTOINCREMENT,
		`backup_id` INTEGER,
		`created` INTEGER,
		`name` VARCHAR(255),
		`size` VARCHAR(255),
		FOREIGN KEY (backup_id) REFERENCES backups(id));';
		
		$sql[] = 'CREATE TABLE IF NOT EXISTS `ftps` (
		`id` INTEGER PRIMARY KEY AUTOINCREMENT,
		`backup_id` INTEGER,
		`created` INTEGER,
		`name` VARCHAR(255),
		`size` VARCHAR(255),
		FOREIGN KEY (backup_id) REFERENCES backups(id));';
		
		$sql[] = 'CREATE TABLE IF NOT EXISTS `emails` (
		`id` INTEGER PRIMARY KEY AUTOINCREMENT,
		`backup_id` INTEGER,
		`created` INTEGER,
		`name` VARCHAR(255),
		`size` VARCHAR(255),
		FOREIGN KEY (backup_id) REFERENCES backups(id));';
		
		$sql[] = 'CREATE TABLE IF NOT EXISTS `certificates` (
		`id` INTEGER PRIMARY KEY AUTOINCREMENT,
		`backup_id` INTEGER,
		`created` INTEGER,
		`name` VARCHAR(255),
		`size` VARCHAR(255),
		FOREIGN KEY (backup_id) REFERENCES backups(id));';
		
		
		// r_print($sql);
		return wdb($backuply_db, $sql);
	}
	
	// echo 1234;
	chmod($backuply_db, 0770);
	chgrp($globals['panel_user'], $backuply_db);
	return wdb($backuply_db);
}

function bcli_restore($params, $user, $log_path = ''){
	global $globals;
	
	$U = load_user($user);
	if(!empty($log_path)){
		$log = $log_path;
	}else{
		$log = (empty($U['user']) ? $globals['backuply_path'] : $U['WU_dir']).'/log/backuply_restore.log';
	}
	
	wmkdir(dirname($log));
	file_put_contents($log, '', FILE_APPEND);
	
	root_bg_exec(phpbin().' /usr/local/webuzo/cli.php --backuply_restore '.$params.' >> '.$log.' 2>&1 &');
	
	return true;
}
