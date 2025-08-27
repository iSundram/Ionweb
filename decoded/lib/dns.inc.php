<?php // PHP DNS Direct Query Module
This file is the PurplePixie PHP DNS Query Classes
The software is (C) Copyright 2008-14 PurplePixie Systems
This is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
The software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this software.  If not, see www.gnu.org/licenses
For more information see www.purplepixie.org/phpdns
-------------------------------------------------------------- */
class DNSTypes
{
var $types_by_id;
var $types_by_name;
function AddType() {
}
function DNSTypes() {
}
function GetByName() {
}
function GetById() {
}
}
class DNSResult
{
var $type;
var $typeid;
var $class;
var $ttl;
var $data;
var $domain;
var $string;
var $extras=array();
}
class DNSAnswer
{
var $count=0;
var $results=array();
function AddResult($type,$typeid,$class,$ttl,$data,$domain="",$string="",$extras=array())
{
$this->results[$this->count]=new DNSResult();
$this->results[$this->count]->type=$type;
$this->results[$this->count]->typeid=$typeid;
$this->results[$this->count]->class=$class;
$this->results[$this->count]->ttl=$ttl;
$this->results[$this->count]->data=$data;
$this->results[$this->count]->domain=$domain;
$this->results[$this->count]->string=$string;
$this->results[$this->count]->extras=$extras;
$this->count++;
return ($this->count-1);
}
}
class DNSQuery
{
var $server="";
var $port;
var $timeout; // default set in constructor
var $udp;
var $debug;
var $binarydebug=false;
var $types;
var $rawbuffer="";
var $rawheader="";
var $rawresponse="";
var $header;
var $responsecounter=0;
var $lastnameservers;
var $lastadditional;
var $error=false;
var $lasterror="";
function ReadResponse() {
}
else
{
$return=substr($this->rawbuffer,$offset,$count);
}
return $return;
}
function ReadDomainLabels() {
}
else // label_len>=64 -- pointer
{
$nextitem=$this->ReadResponse(1,$offset++);
$pointer_offset = ( ($label_len & 0x3f) << 8 ) + ord($nextitem);
$this->Debug("Label Offset: ".$pointer_offset);
$pointer_labels=$this->ReadDomainLabels($pointer_offset);
foreach($pointer_labels as $ptr_label)
$labels[]=$ptr_label;
$return=true;
}
}
$counter=$offset-$startoffset;
return $labels;
}
function ReadDomainLabel() {
}
function Debug() {
}
function DebugBinary() {
}
}
}
function SetError() {
}
function ClearError() {
}
function DNSQuery() {
}
function ReadRecord() {
} else {
$ip=implode(".",unpack("Ca/Cb/Cc/Cd",$ipbin));
}
$data=$ip;
$extras['ipbin'] = $ipbin;
$string=$domain." has IPv4 address ".$ip;
break;
case "AAAA":
$ipbin = $this->ReadResponse(16);
if (function_exists('inet_ntop')) {
$ip = inet_ntop($ipbin);
} else {
$ip = implode(":",unpack("H4a/H4b/H4c/H4d/H4e/H4f/H4g/H4h",$ipbin));
}
$data = $ip;
$extras['ipbin'] = $ipbin;
$string = $domain . " has IPv6 address " . $ip;
break;
case "CNAME":
$data=$this->ReadDomainLabel();
$string=$domain." alias of ".$data;
break;
case "DNAME":
$data=$this->ReadDomainLabel();
$string=$domain." alias of ".$data;
break;
case "DNSKEY":
case "KEY":
$stuff = $this->ReadResponse(4);
$test = unpack("nflags/cprotocol/calgo",$stuff);
$extras['flags']=$test['flags'];
$extras['protocol']=$test['protocol'];
$extras['algorithm']=$test['algo'];
$data = base64_encode($this->ReadResponse($ans_header['length'] - 4));
$string = $domain." KEY ".$data;
break;
case "MX":
$prefs=$this->ReadResponse(2);
$prefs=unpack("nlevel",$prefs);
$extras['level']=$prefs['level'];
$data=$this->ReadDomainLabel();
$string=$domain." mailserver ".$data." (pri=".$extras['level'].")";
break;
case "NS":
$nameserver=$this->ReadDomainLabel();
$data=$nameserver;
$string=$domain." nameserver ".$nameserver;
break;
case "PTR":
$data=$this->ReadDomainLabel();
$string=$domain." points to ".$data;
break;
case "SOA":
$data=$this->ReadDomainLabel();
$responsible=$this->ReadDomainLabel();
$buffer=$this->ReadResponse(20);
$extras=unpack("Nserial/Nrefresh/Nretry/Nexpiry/Nminttl",$buffer); // butfix to NNNNN from nnNNN for 1.01
$dot=strpos($responsible,".");
$responsible[$dot]="@";
$extras['responsible']=$responsible;
$string=$domain." SOA ".$data." Serial ".$extras['serial'];
break;
case "SRV":
$prefs=$this->ReadResponse(6);
$prefs=unpack("npriority/nweight/nport",$prefs);
$extras['priority']=$prefs['priority'];
$extras['weight']=$prefs['weight'];
$extras['port']=$prefs['port'];
$data=$this->ReadDomainLabel();
$string=$domain." SRV ".$data.":" . $extras['port'] . " (pri=".$extras['priority'].", weight=".$extras['weight'].")";
break;
case "TXT":
case "SPF":
$data = "";
for ($string_count = 0; strlen($data) + (1 + $string_count) < $ans_header['length']; $string_count++) {
$string_length = ord($this->ReadResponse(1));
$data .= $this->ReadResponse($string_length);
}
$string=$domain." TEXT \"".$data . "\" (in " . $string_count . " strings)";
break;
default: // something we can't deal with
$stuff=$this->ReadResponse($ans_header['length']);
break;
}
$return=array(
"header" => $ans_header,
"typeid" => $typeid,
"data" => $data,
"domain" => $domain,
"string" => $string,
"extras" => $extras );
return $return;
}
function Query() {
}
if ($this->udp) $host="udp://".$this->server;
else $host=$this->server;
$errno=0;
$errstr="";
if (!$socket=fsockopen($host,$this->port,$errno,$errstr,$this->timeout))
{
$this->SetError("Failed to Open Socket");
return false;
}
if (function_exists("stream_set_timeout")) // handles timeout on stream read set using timeout as well
stream_set_timeout($socket, $this->timeout);
if (preg_match("/[a-z|A-Z]/",$question)==0 && $question!=".") // IP Address
{
$labeltmp=explode(".",$question);	// reverse ARPA format
for ($i=count($labeltmp)-1; $i>=0; $i--)
$labels[]=$labeltmp[$i];
$labels[]="IN-ADDR";
$labels[]="ARPA";
}
else if ($question == ".")
{
$labels=array("");
}
else // hostname
$labels=explode(".",$question);
$question_binary="";
for ($a=0; $a<count($labels); $a++)
{
if ($labels[$a] != "")
{
$size=strlen($labels[$a]);
$question_binary.=pack("C",$size); // size byte first
$question_binary.=$labels[$a]; // then the label
}
else
{
$size=0;
}
}
$question_binary.=pack("C",0); // end it off
$this->Debug("Question: ".$question." (type=".$type."/".$typeid.")");
$id=rand(1,255)|(rand(0,255)<<8);	// generate the ID
$flags=0x0100 & 0x0300; // recursion & queryspecmask
$opcode=0x0000; // opcode
$header="";
$header.=pack("n",$id);
$header.=pack("n",$opcode | $flags);
$header.=pack("nnnn",1,0,0,0);
$header.=$question_binary;
$header.=pack("n",$typeid);
$header.=pack("n",0x0001); // internet class
$headersize=strlen($header);
$headersizebin=pack("n",$headersize);
$this->Debug("Header Length: ".$headersize." Bytes");
$this->DebugBinary($header);
if ( ($this->udp) && ($headersize>=512) )
{
$this->SetError("Question too big for UDP (".$headersize." bytes)");
fclose($socket);
return false;
}
if ($this->udp) // UDP method
{
if (!fwrite($socket,$header,$headersize))
{
$this->SetError("Failed to write question to socket");
fclose($socket);
return false;
}
if (!$this->rawbuffer=fread($socket,4096)) // read until the end with UDP
{
$this->SetError("Failed to read data buffer");
fclose($socket);
return false;
}
}
else // TCP
{
if (!fwrite($socket,$headersizebin)) // write the socket
{
$this->SetError("Failed to write question length to TCP socket");
fclose($socket);
return false;
}
if (!fwrite($socket,$header,$headersize))
{
$this->SetError("Failed to write question to TCP socket");
fclose($socket);
return false;
}
if (!$returnsize=fread($socket,2))
{
$this->SetError("Failed to read size from TCP socket");
fclose($socket);
return false;
}
$tmplen=unpack("nlength",$returnsize);
$datasize=$tmplen['length'];
$this->Debug("TCP Stream Length Limit ".$datasize);
if (!$this->rawbuffer=fread($socket,$datasize))
{
$this->SetError("Failed to read data buffer");
fclose($socket);
return false;
}
}
fclose($socket);
$buffersize=strlen($this->rawbuffer);
$this->Debug("Read Buffer Size ".$buffersize);
if ($buffersize<12)
{
$this->SetError("Return Buffer too Small");
return false;
}
$this->rawheader=substr($this->rawbuffer,0,12); // first 12 bytes is the header
$this->rawresponse=substr($this->rawbuffer,12); // after that the response
$this->responsecounter=12; // start parsing response counter from 12 - no longer using response so can do pointers
$this->DebugBinary($this->rawbuffer);
$this->header=unpack("nid/nspec/nqdcount/nancount/nnscount/narcount",$this->rawheader);
$id = $this->header['id'];
$rcode = $this->header['spec'] & 15;
$z = ($this->header['spec'] >> 4) & 7;
$ra = ($this->header['spec'] >> 7) & 1;
$rd = ($this->header['spec'] >> 8) & 1;
$tc = ($this->header['spec'] >> 9) & 1;
$aa = ($this->header['spec'] >> 10) & 1;
$opcode = ($this->header['spec'] >> 11) & 15;
$type = ($this->header['spec'] >> 15) & 1;
$this->Debug("ID=$id, Type=$type, OPCODE=$opcode, AA=$aa, TC=$tc, RD=$rd, RA=$ra, RCODE=$rcode");
if ($tc==1 && $this->udp) { // Truncation detected
$this->SetError("Response too big for UDP, retry with TCP");
return false;
}
$answers=$this->header['ancount'];
$this->Debug("Query Returned ".$answers." Answers");
$dns_answer=new DNSAnswer();
if ($this->header['qdcount']>0)
{
$this->Debug("Found ".$this->header['qdcount']." Questions");
for ($a=0; $a<$this->header['qdcount']; $a++)
{
$c=1;
while ($c!=0)
{
$c=hexdec(bin2hex($this->ReadResponse(1)));
}
$extradata=$this->ReadResponse(4);
}
}
for ($a=0; $a<$this->header['ancount']; $a++)
{
$record=$this->ReadRecord();
$dns_answer->AddResult($record['header']['type'],$record['typeid'],$record['header']['class'],$record['header']['ttl'],
$record['data'],$record['domain'],$record['string'],$record['extras']);
}
$this->lastnameservers=new DNSAnswer();
for ($a=0; $a<$this->header['nscount']; $a++)
{
$record=$this->ReadRecord();
$this->lastnameservers->AddResult($record['header']['type'],$record['typeid'],$record['header']['class'],$record['header']['ttl'],
$record['data'],$record['domain'],$record['string'],$record['extras']);
}
$this->lastadditional=new DNSAnswer();
for ($a=0; $a<$this->header['arcount']; $a++)
{
$record=$this->ReadRecord();
$this->lastadditional->AddResult($record['header']['type'],$record['typeid'],$record['header']['class'],$record['header']['ttl'],
$record['data'],$record['domain'],$record['string'],$record['extras']);
}
return $dns_answer;
}
function SmartALookup() {
}
else if ($answer_typeid=="CNAME") // alias
{
$best_answer=$data;
$best_answer_typeid="CNAME";
}
}
if ( ($best_answer=="") || ($best_answer_typeid=="") ) return "";
if ($best_answer_typeid=="A") return $best_answer; // got an IP ok
if ($best_answer_typeid!="CNAME") return ""; // shouldn't ever happen
$newtarget=$best_answer; // this is what we now need to resolve
for ($a=0; $a<$this->lastadditional->count; $a++)
{
if ( ($this->lastadditional->results[$a]->domain==$hostname) &&
($this->lastadditional->results[$a]->typeid=="A") )
return $this->lastadditional->results[$a]->data;
}
return $this->SmartALookup($newtarget,++$depth);
}
}
if (!function_exists('inet_ntop')) {
function inet_ntop() {
} elseif(strlen($ip)==16) {
$ip=bin2hex($ip);
$ip=substr(chunk_split($ip,4,':'),0,-1);
$ip=explode(':',$ip);
$res='';
foreach($ip as $seg) {
while($seg{0}=='0') $seg=substr($seg,1);
if ($seg!='') {
$res.=($res==''?'':':').$seg;
} else {
if (strpos($res,'::')===false) {
if (substr($res,-1)==':') continue;
$res.=':';
continue;
}
$res.=($res==''?'':':').'0';
}
}
$ip=$res;
}
return $ip;
}
}
?>