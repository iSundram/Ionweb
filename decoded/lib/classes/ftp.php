<?php
if(!defined('CRLF')) define('CRLF',"\r\n");
if(!defined("FTP_AUTOASCII")) define("FTP_AUTOASCII", -1);
if(!defined("FTP_BINARY")) define("FTP_BINARY", 1);
if(!defined("FTP_ASCII")) define("FTP_ASCII", 0);
if(!defined('FTP_FORCE')) define('FTP_FORCE', TRUE);
define('FTP_OS_Unix','u');
define('FTP_OS_Windows','w');
define('FTP_OS_Mac','m');
class ftp_base {
var $LocalEcho;
var $Verbose;
var $OS_local;
var $OS_remote;
var $_lastaction;
var $_errors;
var $_type;
var $_umask;
var $_timeout;
var $_passive;
var $_host;
var $_fullhost;
var $_port;
var $_datahost;
var $_dataport;
var $_ftp_control_sock;
var $_ftp_data_sock;
var $_ftp_temp_sock;
var $_ftp_buff_size;
var $_login;
var $_password;
var $_connected;
var $_ready;
var $_code;
var $_message;
var $_can_restore;
var $_port_available;
var $_curtype;
var $_features;
var $_error_array;
var $AuthorizedTransferMode;
var $OS_FullName;
var $_eol_code;
var $AutoAsciiExt;
var $error;
function ftp_base() {
}
function __construct() {
}
function parselisting() {
})\s+(\d+)\s+([\:\d]+)\s+(.+)$/i", $list, $ret)) {
$v=array(
"type"	=> ($ret[1]=="-"?"f":$ret[1]),
"perms"	=> 0,
"inode"	=> $ret[3],
"owner"	=> $ret[4],
"group"	=> $ret[5],
"size"	=> $ret[6],
"date"	=> $ret[7]." ".$ret[8]." ".$ret[9],
"name"	=> $ret[10]
);
$bad=array("(?)");
if(in_array($v["owner"], $bad)) $v["owner"]=NULL;
if(in_array($v["group"], $bad)) $v["group"]=NULL;
$v["perms"]+=00400*(int)($ret[2]{0}=="r");
$v["perms"]+=00200*(int)($ret[2]{1}=="w");
$v["perms"]+=00100*(int)in_array($ret[2]{2}, array("x","s"));
$v["perms"]+=00040*(int)($ret[2]{3}=="r");
$v["perms"]+=00020*(int)($ret[2]{4}=="w");
$v["perms"]+=00010*(int)in_array($ret[2]{5}, array("x","s"));
$v["perms"]+=00004*(int)($ret[2]{6}=="r");
$v["perms"]+=00002*(int)($ret[2]{7}=="w");
$v["perms"]+=00001*(int)in_array($ret[2]{8}, array("x","t"));
$v["perms"]+=04000*(int)in_array($ret[2]{2}, array("S","s"));
$v["perms"]+=02000*(int)in_array($ret[2]{5}, array("S","s"));
$v["perms"]+=01000*(int)in_array($ret[2]{8}, array("T","t"));
}
return $v;
}
function _parselisting() {
})-([0-9]{2})-([0-9]{2}) +([0-9]{2}):([0-9]{2})(AM|PM) +([0-9]+|<DIR>) +(.+)/",$line,$lucifer)) {
$b = array();
if ($lucifer[3]<70) { $lucifer[3]+=2000; } else { $lucifer[3]+=1900; } // 4digit year fix
$b['isdir'] = ($lucifer[7]=="<DIR>");
if ( $b['isdir'] )
$b['type'] = 'd';
else
$b['type'] = 'f';
$b['size'] = $lucifer[7];
$b['month'] = $lucifer[1];
$b['day'] = $lucifer[2];
$b['year'] = $lucifer[3];
$b['hour'] = $lucifer[4];
$b['minute'] = $lucifer[5];
$b['time'] = @mktime($lucifer[4]+(strcasecmp($lucifer[6],"PM")==0?12:0),$lucifer[5],0,$lucifer[1],$lucifer[2],$lucifer[3]);
$b['am/pm'] = $lucifer[6];
$b['name'] = $lucifer[8];
} else if (!$is_windows && $lucifer=preg_split("/[ ]/",$line,9,PREG_SPLIT_NO_EMPTY)) {
$lcount=count($lucifer);
if ($lcount<8) return '';
$b = array();
$b['isdir'] = $lucifer[0]{0} === "d";
$b['islink'] = $lucifer[0]{0} === "l";
if ( $b['isdir'] )
$b['type'] = 'd';
elseif ( $b['islink'] )
$b['type'] = 'l';
else
$b['type'] = 'f';
$b['perms'] = $lucifer[0];
$b['number'] = $lucifer[1];
$b['owner'] = $lucifer[2];
$b['group'] = $lucifer[3];
$b['size'] = $lucifer[4];
if ($lcount==8) {
sscanf($lucifer[5],"%d-%d-%d",$b['year'],$b['month'],$b['day']);
sscanf($lucifer[6],"%d:%d",$b['hour'],$b['minute']);
$b['time'] = @mktime($b['hour'],$b['minute'],0,$b['month'],$b['day'],$b['year']);
$b['name'] = $lucifer[7];
} else {
$b['month'] = $lucifer[5];
$b['day'] = $lucifer[6];
if (preg_match("/([0-9]{2}):([0-9]{2})/",$lucifer[7],$l2)) {
$b['year'] = date("Y");
$b['hour'] = $l2[1];
$b['minute'] = $l2[2];
if(strtotime(sprintf("%d %s %d %02d:%02d",$b['day'],$b['month'],$b['year'],$b['hour'],$b['minute'])) > time()){
$b['year'] = $b['year'] - 1;
}
} else {
$b['year'] = $lucifer[7];
$b['hour'] = 0;
$b['minute'] = 0;
}
$b['time'] = strtotime(sprintf("%d %s %d %02d:%02d",$b['day'],$b['month'],$b['year'],$b['hour'],$b['minute']));
$b['name'] = $lucifer[8];
}
}
$bad=array("(?)");
if(in_array($b["owner"], $bad)) $b["owner"]=NULL;
if(in_array($b["group"], $bad)) $b["group"]=NULL;
$orig_perm = substr($b["perms"], 1);
$b["perms"] = 0;
$b["perms"]+=00400*(int)($orig_perm{0}=="r");
$b["perms"]+=00200*(int)($orig_perm{1}=="w");
$b["perms"]+=00100*(int)in_array($orig_perm{2}, array("x","s"));
$b["perms"]+=00040*(int)($orig_perm{3}=="r");
$b["perms"]+=00020*(int)($orig_perm{4}=="w");
$b["perms"]+=00010*(int)in_array($orig_perm{5}, array("x","s"));
$b["perms"]+=00004*(int)($orig_perm{6}=="r");
$b["perms"]+=00002*(int)($orig_perm{7}=="w");
$b["perms"]+=00001*(int)in_array($orig_perm{8}, array("x","t"));
$b["perms"]+=04000*(int)in_array($orig_perm{2}, array("S","s"));
$b["perms"]+=02000*(int)in_array($orig_perm{5}, array("S","s"));
$b["perms"]+=01000*(int)in_array($orig_perm{8}, array("T","t"));
return $b;
}
function SendMSG() {
}
return TRUE;
}
function SetType() {
}
$this->_type=$mode;
$this->SendMSG("Transfer type: ".($this->_type==FTP_BINARY?"binary":($this->_type==FTP_ASCII?"ASCII":"auto ASCII") ) );
return TRUE;
}
function _settype() {
}
} elseif($this->_curtype!=FTP_ASCII) {
if(!$this->_exec("TYPE A", "SetType")) return FALSE;
$this->_curtype=FTP_ASCII;
}
} else return FALSE;
return TRUE;
}
function Passive() {
}
$this->SendMSG("Passive mode ".($this->_passive?"on":"off"));
return TRUE;
}
function SetServer() {
} else {
$ip=@gethostbyname($host);
$dns=@gethostbyaddr($host);
if(!$ip) $ip=$host;
if(!$dns) $dns=$host;
$ipaslong = ip2long($ip);
if ( ($ipaslong == false) || ($ipaslong === -1) ) {
$this->SendMSG("Wrong host name/address \"".$host."\"");
return FALSE;
}
$this->_host=$ip;
$this->_fullhost=$dns;
$this->_port=$port;
$this->_dataport=$port-1;
}
$this->SendMSG("Host \"".$this->_fullhost."(".$this->_host."):".$this->_port."\"");
if($reconnect){
if($this->_connected) {
$this->SendMSG("Reconnecting");
if(!$this->quit(FTP_FORCE)) return FALSE;
if(!$this->connect()) return FALSE;
}
}
return TRUE;
}
function SetUmask() {
}
function SetTimeout() {
}
function connect() {
}
if($this->_ready) return true;
$this->SendMsg('Local OS : '.$this->OS_FullName[$this->OS_local]);
if(!($this->_ftp_control_sock = $this->_connect($this->_host, $this->_port))) {
$this->SendMSG("Error : Cannot connect to remote host \"".$this->_fullhost." :".$this->_port."\"");
return FALSE;
}
$this->SendMSG("Connected to remote host \"".$this->_fullhost.":".$this->_port."\". Waiting for greeting.");
do {
if(!$this->_readmsg()) return FALSE;
if(!$this->_checkCode()) return FALSE;
$this->_lastaction=time();
} while($this->_code<200);
$this->_ready=true;
$syst=$this->systype();
if(!$syst) $this->SendMSG("Can't detect remote OS");
else {
if(preg_match("/win|dos|novell/i", $syst[0])) $this->OS_remote=FTP_OS_Windows;
elseif(preg_match("/os/i", $syst[0])) $this->OS_remote=FTP_OS_Mac;
elseif(preg_match("/(li|u)nix/i", $syst[0])) $this->OS_remote=FTP_OS_Unix;
else $this->OS_remote=FTP_OS_Mac;
$this->SendMSG("Remote OS: ".$this->OS_FullName[$this->OS_remote]);
}
if(!$this->features()) $this->SendMSG("Can't get features list. All supported - disabled");
else $this->SendMSG("Supported features: ".implode(", ", array_keys($this->_features)));
return TRUE;
}
function quit() {
}
$this->_quit();
return TRUE;
}
function login() {
}
$this->SendMSG("Authentication succeeded");
if(empty($this->_features)) {
if(!$this->features()) $this->SendMSG("Can't get features list. All supported - disabled");
else $this->SendMSG("Supported features: ".implode(", ", array_keys($this->_features)));
}
return TRUE;
}
function pwd() {
} \"(.+)\" .+".CRLF, "\\1", $this->_message);
return trim(preg_replace("/^[0-9]{3} \"(.+)\" .+".CRLF."/", "\\1", $this->_message));
}
function cdup() {
}
function chdir() {
}
function rmdir() {
}
function mkdir() {
}
function rename() {
} else return FALSE;
return TRUE;
}
function filesize() {
}
if(!$this->_exec("SIZE ".$pathname, "filesize")) return FALSE;
if(!$this->_checkCode()) return FALSE;
return preg_replace("/^[0-9]{3} ([0-9]+).*$/s", "\\1", $this->_message);
}
function abort() {
}
return true;
}
function mdtm() {
}
if(!$this->_exec("MDTM ".$pathname, "mdtm")) return FALSE;
if(!$this->_checkCode()) return FALSE;
$mdtm = preg_replace("/^[0-9]{3} ([0-9]+).*$/s", "\\1", $this->_message);
$date = sscanf($mdtm, "%4d%2d%2d%2d%2d%2d");
$timestamp = mktime($date[3], $date[4], $date[5], $date[1], $date[2], $date[0]);
return $timestamp;
}
function systype() {
}
function delete() {
}
function site() {
}
function chmod() {
}
function restore() {
}
if($this->_curtype!=FTP_BINARY) {
$this->PushError("restore", "can't restore in ASCII mode");
return FALSE;
}
if(!$this->_exec("REST ".$from, "resore")) return FALSE;
if(!$this->_checkCode()) return FALSE;
return TRUE;
}
function features() {
}[\s-]+/", "", trim($a));'));
$this->_features=array();
foreach($f as $k=>$v) {
$v=explode(" ", trim($v));
$this->_features[array_shift($v)]=$v;
}
return true;
}
function rawlist() {
}
function nlist() {
}
function is_exists() {
}
function file_exists() {
}
if(!$this->_checkCode()) $exists=FALSE;
}
if($exists) $this->SendMSG("Remote file ".$pathname." exists");
else $this->SendMSG("Remote file ".$pathname." does not exist");
return $exists;
}
function is_dir() {
}else{
return false;
}
}
function get() {
}
if($this->_can_restore and $rest!=0) fseek($fp, $rest);
$pi=pathinfo($remotefile);
if($this->_type==FTP_ASCII or ($this->_type==FTP_AUTOASCII and in_array(strtoupper($pi["extension"]), $this->AutoAsciiExt))) $mode=FTP_ASCII;
else $mode=FTP_BINARY;
if(!$this->_data_prepare($mode)) {
fclose($fp);
return FALSE;
}
if($this->_can_restore and $rest!=0) $this->restore($rest);
if(!$this->_exec("RETR ".$remotefile, "get")) {
$this->_data_close();
fclose($fp);
return FALSE;
}
if(!$this->_checkCode()) {
$this->_data_close();
fclose($fp);
return FALSE;
}
$out=$this->_data_read($mode, $fp);
fclose($fp);
$this->_data_close();
if(!$this->_readmsg()) return FALSE;
if(!$this->_checkCode()) return FALSE;
return $out;
}
function put() {
}
$fp = @fopen($localfile, "r");
if (!$fp) {
$this->PushError("put","Can't open local file. Cannot read file \"".$localfile."\"", "Cannot read file \"".$localfile."\"");
return FALSE;
}
if($this->_can_restore and $rest!=0) fseek($fp, $rest);
$pi=pathinfo($localfile);
if($this->_type==FTP_ASCII or ($this->_type==FTP_AUTOASCII and in_array(strtoupper($pi["extension"]), $this->AutoAsciiExt))) $mode=FTP_ASCII;
else $mode=FTP_BINARY;
if(!$this->_data_prepare($mode)) {
fclose($fp);
return FALSE;
}
if($this->_can_restore and $rest!=0) $this->restore($rest);
if(!$this->_exec("STOR ".$remotefile, "put")) {
$this->_data_close();
fclose($fp);
return FALSE;
}
if(!$this->_checkCode()) {
$this->_data_close();
fclose($fp);
return FALSE;
}
$ret=$this->_data_write($mode, $fp);
fclose($fp);
$this->_data_close();
if(!$this->_readmsg()) return FALSE;
if(!$this->_checkCode()) return FALSE;
if(defined('SOFTACULOUS_FILE_CHMOD')){
$this->chmod($remotefile, SOFTACULOUS_FILE_CHMOD);
}
return $ret;
}
function mput() {
}
if(!is_dir($local)) return $this->put($local, $remote);
if(empty($remote)) $remote=".";
elseif(!$this->file_exists($remote) and !$this->mkdir($remote)) return FALSE;
if($handle = opendir($local)) {
$list=array();
while (false !== ($file = readdir($handle))) {
if ($file != "." && $file != "..") $list[]=$file;
}
closedir($handle);
} else {
$this->PushError("mput","Can't open local folder. Cannot read folder \"".$local."\"", "Cannot read folder \"".$local."\"");
return FALSE;
}
if(empty($list)) return TRUE;
$ret=true;
foreach($list as $el) {
if(is_dir($local."/".$el)) $t=$this->mput($local."/".$el, $remote."/".$el);
else $t=$this->put($local."/".$el, $remote."/".$el);
if(!$t) {
$ret=FALSE;
if(!$continious) break;
}
}
return $ret;
}
function mget($remote, $local=".", $continious=false, $include_only=array()) {
global $__settings;
if(!@file_exists($local)) {
if(!@mkdir($local, 0755, true)) {
$this->PushError("mget","Can't create local folder \"".$local."\"", "Cannot create folder \"".$local."\"");
return FALSE;
}
}
$list=$this->rawlist($remote, "-lA");
if($list===false) {
$this->PushError("mget","Can't read remote folder list. Can't read remote folder \"".$remote."\" contents", "Can't read remote folder \"".$remote."\" contents");
return FALSE;
}
if(empty($list)) return true;
foreach($list as $k=>$v) {
$list[$k]=$this->_parselisting($v);
if($list[$k]["name"]=="." or $list[$k]["name"]=="..") unset($list[$k]);
}
$ret=true;
foreach($list as $el) {
if($el['size'] == '0'){
if($el['type'] == 'f'){
$empty_file = @fopen($local.'/'.$el['name'], "w");
@fclose($empty_file);
}elseif($el['type'] == 'd'){
mkdir($local.'/'.$el['name']);
}
@chmod($local.'/'.$el['name'], $el['perms']);
$t=$el["time"];
if($t!==-1 and $t!==false) @touch($local."/".$el["name"], $t);
unset($list[$el]);
continue;
}
$extension = get_extension($el['name']);
if(!empty($include_only) && !in_array($el['name'], $include_only) && $extension !== 'sql'){
unset($list[$el]);
continue;
}
$current_file = $local.'/'.$el['name'];
if(!empty($__settings['exclude_files']) && in_array($current_file, $__settings['exclude_files'])){
unset($list[$el]);
continue;
}
if($el["type"]=="d") {
if(!$this->mget($remote."/".$el["name"], $local."/".$el["name"], $continious)) {
$this->PushError("mget", "Can't copy remote folder \"".$remote."/".$el["name"]."\" to local \"".$local."/".$el["name"]."\"", "Can't copy remote folder \"".$remote."/".$el["name"]."\" to local \"".$local."/".$el["name"]."\"");
$ret=false;
if(!$continious) break;
}
} else {
if(!empty($__settings['exclude_ext']) && in_array($extension, $__settings['exclude_ext'])){
unset($list[$el]);
continue;
}
if(!$this->get($remote."/".$el["name"], $local."/".$el["name"])) {
$this->PushError("mget", "Can't copy remote file \"".$remote."/".$el["name"]."\" to local \"".$local."/".$el["name"]."\"", "Can't copy remote file \"".$remote."/".$el["name"]."\" to local \"".$local."/".$el["name"]."\"");
$ret=false;
if(!$continious) break;
}
}
@chmod($local."/".$el["name"], $el["perms"]);
$t=$el["time"];
if($t!==-1 and $t!==false) @touch($local."/".$el["name"], $t);
}
return $ret;
}
function mdel() {
}
foreach($list as $k=>$v) {
$list[$k]=$this->parselisting($v);
if($list[$k]["name"]=="." or $list[$k]["name"]=="..") unset($list[$k]);
}
$ret=true;
foreach($list as $el) {
if($el["type"]=="d") {
if(!$this->mdel($remote."/".$el["name"], $continious)) {
$ret=false;
if(!$continious) break;
}
} else {
if (!$this->delete($remote."/".$el["name"])) {
$this->PushError("mdel", "Can't delete remote file \"".$remote."/".$el["name"]."\"", "Can't delete remote file \"".$remote."/".$el["name"]."\"");
$ret=false;
if(!$continious) break;
}
}
}
if(!$this->rmdir($remote)) {
$this->PushError("mdel", "Can't delete remote folder \"".$remote."/".$el["name"]."\"", "Can't delete remote folder \"".$remote."/".$el["name"]."\"");
$ret=false;
}
return $ret;
}
function mmkdir() {
}
function glob() {
} else $path=getcwd();
if(is_array($handle) and !empty($handle)) {
while($dir=each($handle)) {
if($this->glob_pattern_match($pattern,$dir))
$output[]=$dir;
}
} else {
$handle=@opendir($path);
if($handle===false) return false;
while($dir=readdir($handle)) {
if($this->glob_pattern_match($pattern,$dir))
$output[]=$dir;
}
closedir($handle);
}
if(is_array($output)) return $output;
return false;
}
function glob_pattern_match() {
}','(',')','[',']','|');
while(strpos($pattern,'**')!==false)
$pattern=str_replace('**','*',$pattern);
foreach($escape as $probe)
$pattern=str_replace($probe,"\\$probe",$pattern);
$pattern=str_replace('?*','*',
str_replace('*?','*',
str_replace('*',".*",
str_replace('?','.{1,1}',$pattern))));
$out[]=$pattern;
}
if(count($out)==1) return($this->glob_regexp("^$out[0]$",$string));
else {
foreach($out as $tester)
if($this->my_regexp("^$tester$",$string)) return true;
}
return false;
}
function glob_regexp() {
}
function _checkCode() {
}
function _list() {
}
if(!$this->_checkCode()) {
$this->_data_close();
return FALSE;
}
$out="";
if($this->_code<200) {
$out=$this->_data_read();
$this->_data_close();
if(!$this->_readmsg()) return FALSE;
if(!$this->_checkCode()) return FALSE;
if($out === FALSE ) return FALSE;
$out=preg_split("/[".CRLF."]+/", $out, -1, PREG_SPLIT_NO_EMPTY);
}
return $out;
}
function PushError() {
}
$error['time']=time();
$error['fctname']=$fctname;
$error['msg'] .= $msg;
$error['desc']=$desc;
if($desc) $tmp=' ('.$desc.')'; else $tmp='';
$this->SendMSG($fctname.': '.$msg.$tmp);
array_push($this->_error_array,$error);
return(array_push($this->error, $error['msg']));
}
function PopError() {
}
}
class ftp extends ftp_base {
function ftp() {
}
function __construct() {
}
function _settimeout() {
}
return TRUE;
}
function _connect() {
}
$this->_connected=true;
return $sock;
}
function _readmsg() {
}
$result=true;
$this->_message="";
$this->_code=0;
$go=true;
do {
$tmp=@fgets($this->_ftp_control_sock, 512);
if($tmp===false) {
$go=$result=false;
$this->PushError($fnction,'Read failed');
} else {
$this->_message.=$tmp;
if(preg_match("/^([0-9]{3})(-(.*[".CRLF."]{1,2})+\\1)? [^".CRLF."]+[".CRLF."]{1,2}$/", $this->_message, $regs)) $go=false;
}
} while($go);
if($this->LocalEcho) echo "GET < ".rtrim($this->_message, CRLF).CRLF;
$this->_code=(int)$regs[1];
return $result;
}
function _exec() {
}
if($this->LocalEcho) echo "PUT > ",$cmd,CRLF;
$status=@fputs($this->_ftp_control_sock, $cmd.CRLF);
if($status===false) {
$this->PushError($fnction,'socket write failed');
return FALSE;
}
$this->_lastaction=time();
if(!$this->_readmsg($fnction)) return FALSE;
return TRUE;
}
function _data_prepare() {
}
if(!$this->_checkCode()) {
$this->_data_close();
return FALSE;
}
$ip_port = explode(",", preg_replace("/^.+ \\(?([0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]{1,3},[0-9]+,[0-9]+)\\)?.*".CRLF."$/s", "\\1", $this->_message));
$this->_datahost=$ip_port[0].".".$ip_port[1].".".$ip_port[2].".".$ip_port[3];
$this->_dataport=(((int)$ip_port[4])<<8) + ((int)$ip_port[5]);
$this->SendMSG("Connecting to ".$this->_datahost.":".$this->_dataport);
$this->_ftp_data_sock=@fsockopen($this->_datahost, $this->_dataport, $errno, $errstr, $this->_timeout);
if(!$this->_ftp_data_sock) {
$this->PushError("_data_prepare","fsockopen fails. ".$errstr." (".$errno.")", $errstr." (".$errno.")");
$this->_data_close();
return FALSE;
}
else $this->_ftp_data_sock;
} else {
$this->SendMSG("Only passive connections available!");
return FALSE;
}
return TRUE;
}
function _data_read() {
}
while (!feof($this->_ftp_data_sock)) {
$block=fread($this->_ftp_data_sock, $this->_ftp_buff_size);
if($mode!=FTP_BINARY) $block=preg_replace("/\r\n|\r|\n/", $this->_eol_code[$this->OS_local], $block);
if(is_resource($fp)) $out+=fwrite($fp, $block, strlen($block));
else $out.=$block;
}
return $out;
}
function _data_write() {
}
if(is_resource($fp)) {
while(!feof($fp)) {
$block=fread($fp, $this->_ftp_buff_size);
if(!$this->_data_write_block($mode, $block)) return false;
}
} elseif(!$this->_data_write_block($mode, $fp)) return false;
return TRUE;
}
function _data_write_block() {
}
$block=substr($block, $t);
} while(!empty($block));
return true;
}
function _data_close() {
}
function _quit() {
}
}
function softput() {
}
if($this->_can_restore and $rest!=0) $this->restore($rest);
if(!$this->_exec("STOR ".$remotefile, "put")) {
$this->_data_close();
return FALSE;
}
if(!$this->_checkCode()) {
$this->_data_close();
return FALSE;
}
$ret=$this->_data_write($mode, $softdata);
$this->_data_close();
if(!$this->_readmsg()) return FALSE;
if(!$this->_checkCode()) return FALSE;
if(defined('SOFTACULOUS_FILE_CHMOD')){
$this->chmod($remotefile, SOFTACULOUS_FILE_CHMOD);
}
return $ret;
}
function softget() {
}
if($this->_can_restore and $rest!=0) $this->restore($rest);
if(!$this->_exec("RETR ".$remotefile, "get")) {
$this->_data_close();
return FALSE;
}
if(!$this->_checkCode()) {
$this->_data_close();
return FALSE;
}
$out=$this->_data_read($mode);
$this->_data_close();
if(!$this->_readmsg()) return FALSE;
if(!$this->_checkCode()) return FALSE;
return $out;
}
}