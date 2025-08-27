<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function load_backuply_icons() {
}
function backuply_tabs() {
}
echo '</ul>';
echo '<script>
$(".nav-link").click(function(){
$(".loading").show();
})
</script>';
}
function backup_file_data() {
}
$conn_backups = wdb_backuply();
$bdata = [];
$q = 'SELECT * FROM backups WHERE user = "'.$user.'" AND filename = "'.$filename.'"';
$res = wdb_query($conn_backups, $q);
if(!empty($res)){
while($row = array_unique(wdb_fetch($res))){
$comp_bac = $softpanel->user_func_exec($row['user'], 'file_exists', [$row['backup_location'].'/'.$row['filename']]);
$incr_bac = $softpanel->root_func_exec('file_exists', [$row['backup_location'].'/'.$row['user'].'/'.$row['filename']]);
if($row['backup_server_id'] == -1 && (empty($comp_bac) && empty($incr_bac))){
$row['missing'] = 1;
}
if($row['backup_server_id'] == -1){
}
$row['dbs_list'] = unserialize($row['dbs_list']);
$row['admin_backup'] = 1;
$wbackup_list[$row['type']][] = $row;
}
}
foreach($wbackup_list[$type] as $key => $value){
if($value['filename'] == trim($filename)){
$bdata = $value;
}
}
return $bdata;
}
function backuply_get_table() {
}
$data = [];
$q = 'SELECT '.$table.'.name AS name, '.$table.'.size AS size, GROUP_CONCAT(backups.filename, ", ") AS backup_files FROM '.$table.' JOIN backups ON '.$table.'.backup_id = backups.id WHERE backups.user = "'.$user.'" '.(!empty($where) ? 'AND '.$where : '').' GROUP BY '.$table.'.name';
$res = wdb_query($conn_backups, $q);
if(!empty($res)){
while($row = wdb_fetch($res)){
$data[$table][$row['name']] = $row;
}
}
return $data;
}
function wdb_backuply() {
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
return wdb($backuply_db, $sql);
}
chmod($backuply_db, 0770);
chgrp($globals['panel_user'], $backuply_db);
return wdb($backuply_db);
}
function bcli_restore() {
}else{
$log = (empty($U['user']) ? $globals['backuply_path'] : $U['WU_dir']).'/log/backuply_restore.log';
}
wmkdir(dirname($log));
file_put_contents($log, '', FILE_APPEND);
root_bg_exec(phpbin().' /usr/local/webuzo/cli.php --backuply_restore '.$params.' >> '.$log.' 2>&1 &');
return true;
}