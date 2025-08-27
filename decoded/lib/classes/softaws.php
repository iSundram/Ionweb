<?php
* $Id$
*
* Copyright (c) 2013, Donovan Schönknecht.  All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*
* - Redistributions of source code must retain the above copyright notice,
*   this list of conditions and the following disclaimer.
* - Redistributions in binary form must reproduce the above copyright
*   notice, this list of conditions and the following disclaimer in the
*   documentation and/or other materials provided with the distribution.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
* AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
* IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
* ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
* LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
* CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
* SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
* INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
* CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
* ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
* POSSIBILITY OF SUCH DAMAGE.
*
* Amazon S3 is a trademark of Amazon.com, Inc. or its affiliates.
*/
* Amazon S3 PHP class
*
* @link http://undesigned.org.za/2007/10/22/amazon-s3-php-class
* @version 0.5.1
*/
define('SOFT_AWS_CHUNK_SIZE', 5242880); //5MB
class S3
{
const ACL_PRIVATE = 'private';
const ACL_PUBLIC_READ = 'public-read';
const ACL_PUBLIC_READ_WRITE = 'public-read-write';
const ACL_AUTHENTICATED_READ = 'authenticated-read';
const STORAGE_CLASS_STANDARD = 'STANDARD';
const STORAGE_CLASS_RRS = 'REDUCED_REDUNDANCY';
const STORAGE_CLASS_STANDARD_IA = 'STANDARD_IA';
const SSE_NONE = '';
const SSE_AES256 = 'AES256';
protected $retry_limit = 5;
* The AWS Access key
*
* @var string
* @access private
* @static
*/
private static $__accessKey = null;
* AWS Secret Key
*
* @var string
* @access private
* @static
*/
private static $__secretKey = null;
* SSL Client key
*
* @var string
* @access private
* @static
*/
private static $__sslKey = null;
* Default delimiter to be used, for example while getBucket().
* @var string
* @access public
* @static
*/
public static $defDelimiter = null;
* AWS URI
*
* @var string
* @acess public
* @static
*/
public static $endpoint = 's3.amazonaws.com';
* AWS Region
*
* @var string
* @acess public
* @static
*/
public static $region = '';
* Proxy information
*
* @var null|array
* @access public
* @static
*/
public static $proxy = null;
* Connect using SSL?
*
* @var bool
* @access public
* @static
*/
public static $useSSL = true;
* Use SSL validation?
*
* @var bool
* @access public
* @static
*/
public static $useSSLValidation = true;
* Use SSL version
*
* @var const
* @access public
* @static
*/
public static $useSSLVersion = CURL_SSLVERSION_TLSv1;
* Use PHP exceptions?
*
* @var bool
* @access public
* @static
*/
public static $useExceptions = false;
* Time offset applied to time()
* @access private
* @static
*/
private static $__timeOffset = 0;
* SSL client key
*
* @var bool
* @access public
* @static
*/
public static $sslKey = null;
* SSL client certfificate
*
* @var string
* @acess public
* @static
*/
public static $sslCert = null;
* SSL CA cert (only required if you are having problems with your system CA cert)
*
* @var string
* @access public
* @static
*/
public static $sslCACert = null;
* AWS Key Pair ID
*
* @var string
* @access private
* @static
*/
private static $__signingKeyPairId = null;
* Key resource, freeSigningKey() must be called to clear it from memory
*
* @var bool
* @access private
* @static
*/
private static $__signingKeyResource = false;
* CURL progress function callback
*
* @var function
* @access public
* @static
*/
public static $progressFunction = null;
protected static $range_lower_limit = 0;
protected static $range_upper_limit = 0;
* AWS Signature Version
*
* @var string
* @acess public
* @static
*/
public static $signVer = 'v4';
* Constructor - if you're not using the class statically
*
* @param string $accessKey Access key
* @param string $secretKey Secret key
* @param boolean $useSSL Enable SSL
* @param string $endpoint Amazon URI
* @return void
*/
public function __construct() {
}
* Set the service endpoint
*
* @param string endpoint
* @return void
*/
public static function setEndpoint() {
}
}
* Set the service endpoint
*
* @return string $endpoint
* @static
*/
public static function getEndpoint() {
}
* Set the service region
*
* @param string $region
* @return void
*/
public static function setRegion() {
}
* Get the service region
*
* @return string $region
* @static
*/
public static function getRegion() {
}
}
return empty($region) ? 'us-east-1' : $region;
}
* Set AWS access key and secret key
*
* @param string $accessKey Access key
* @param string $secretKey Secret key
* @return void
*/
public static function setAuth() {
}
* Check if AWS keys have been set
*
* @return boolean
*/
public static function hasAuth() {
}
* Set SSL on or off
*
* @param boolean $enabled SSL enabled
* @param boolean $validate SSL certificate validation
* @return void
*/
public static function setSSL() {
}
* Set SSL client certificates (experimental)
*
* @param string $sslCert SSL client certificate
* @param string $sslKey SSL client key
* @param string $sslCACert SSL CA cert (only required if you are having problems with your system CA cert)
* @return void
*/
public static function setSSLAuth() {
}
* Set proxy information
*
* @param string $host Proxy hostname and port (localhost:1234)
* @param string $user Proxy username
* @param string $pass Proxy password
* @param constant $type CURL proxy type
* @return void
*/
public static function setProxy() {
}
* Set the error mode to exceptions
*
* @param boolean $enabled Enable exceptions
* @return void
*/
public static function setExceptions() {
}
* Set AWS time correction offset (use carefully)
*
* This can be used when an inaccurate system time is generating
* invalid request signatures.  It should only be used as a last
* resort when the system time cannot be changed.
*
* @param string $offset Time offset (set to zero to use AWS server time)
* @return void
*/
public static function setTimeCorrectionOffset() {
}
self::$__timeOffset = $offset;
}
* Set signing key
*
* @param string $keyPairId AWS Key Pair ID
* @param string $signingKey Private Key
* @param boolean $isFile Load private key from file, set to false to load string
* @return boolean
*/
public static function setSigningKey() {
}
* Set Signature Version
*
* @param string $version of signature ('v4' or 'v2')
* @return void
*/
public static function setSignatureVersion() {
}
* Free signing key from memory, MUST be called if you are using setSigningKey()
*
* @return void
*/
public static function freeSigningKey() {
}
* Set progress function
*
* @param function $func Progress function
* @return void
*/
public static function setProgressFunction() {
}
* Internal error handler
*
* @internal Internal error handler
* @param string $message Error message
* @param string $file Filename
* @param integer $line Line number
* @param integer $code Error code
* @return void
*/
private static function __triggerError() {
}
* Get a list of buckets
*
* @param boolean $detailed Returns detailed bucket list when true
* @return array | false
*/
public static function listBuckets() {
}
$results = array();
if (!isset($rest->body->Buckets)) return $results;
if ($detailed)
{
if (isset($rest->body->Owner, $rest->body->Owner->ID, $rest->body->Owner->DisplayName))
$results['owner'] = array(
'id' => (string)$rest->body->Owner->ID, 'name' => (string)$rest->body->Owner->DisplayName
);
$results['buckets'] = array();
foreach ($rest->body->Buckets->Bucket as $b)
$results['buckets'][] = array(
'name' => (string)$b->Name, 'time' => strtotime((string)$b->CreationDate)
);
} else
foreach ($rest->body->Buckets->Bucket as $b) $results[] = (string)$b->Name;
return $results;
}
* Get contents for a bucket
*
* If maxKeys is null this method will loop through truncated result sets
*
* @param string $bucket Bucket name
* @param string $prefix Prefix
* @param string $marker Marker (last file listed)
* @param string $maxKeys Max keys (maximum number of keys to return)
* @param string $delimiter Delimiter
* @param boolean $returnCommonPrefixes Set to true to return CommonPrefixes
* @return array | false
*/
public static function getBucket() {
}
$results = array();
$nextMarker = null;
if (isset($response->body, $response->body->Contents))
foreach ($response->body->Contents as $c)
{
$results[(string)$c->Key] = array(
'name' => (string)$c->Key,
'time' => strtotime((string)$c->LastModified),
'size' => (int)$c->Size,
'hash' => substr((string)$c->ETag, 1, -1)
);
$nextMarker = (string)$c->Key;
}
if ($returnCommonPrefixes && isset($response->body, $response->body->CommonPrefixes))
foreach ($response->body->CommonPrefixes as $c)
$results[(string)$c->Prefix] = array('prefix' => (string)$c->Prefix);
if (isset($response->body, $response->body->IsTruncated) &&
(string)$response->body->IsTruncated == 'false') return $results;
if (isset($response->body, $response->body->NextMarker))
$nextMarker = (string)$response->body->NextMarker;
if ($maxKeys == null && $nextMarker !== null && (string)$response->body->IsTruncated == 'true')
do
{
$rest = new S3Request('GET', $bucket, '', self::$endpoint);
if ($prefix !== null && $prefix !== '') $rest->setParameter('prefix', $prefix);
$rest->setParameter('marker', $nextMarker);
if ($delimiter !== null && $delimiter !== '') $rest->setParameter('delimiter', $delimiter);
if (($response = $rest->getResponse()) == false || $response->code !== 200) break;
if (isset($response->body, $response->body->Contents))
foreach ($response->body->Contents as $c)
{
$results[(string)$c->Key] = array(
'name' => (string)$c->Key,
'time' => strtotime((string)$c->LastModified),
'size' => (int)$c->Size,
'hash' => substr((string)$c->ETag, 1, -1)
);
$nextMarker = (string)$c->Key;
}
if ($returnCommonPrefixes && isset($response->body, $response->body->CommonPrefixes))
foreach ($response->body->CommonPrefixes as $c)
$results[(string)$c->Prefix] = array('prefix' => (string)$c->Prefix);
if (isset($response->body, $response->body->NextMarker))
$nextMarker = (string)$response->body->NextMarker;
} while ($response !== false && (string)$response->body->IsTruncated == 'true');
return $results;
}
* Put a bucket
*
* @param string $bucket Bucket name
* @param constant $acl ACL flag
* @param string $location Set as "EU" to create buckets hosted in Europe
* @return boolean
*/
public static function putBucket() {
}
$rest = $rest->getResponse();
if ($rest->error === false && $rest->code !== 200)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
self::__triggerError(sprintf("S3::putBucket({$bucket}, {$acl}, {$location}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
return true;
}
* Delete an empty bucket
*
* @param string $bucket Bucket name
* @return boolean
*/
public static function deleteBucket() {
}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
return true;
}
* Create input info array for putObject()
*
* @param string $file Input file
* @param mixed $md5sum Use MD5 hash (supply a string if you want to use your own)
* @return array | false
*/
public static function inputFile() {
}
clearstatcache(false, $file);
return array('file' => $file, 'size' => filesize($file), 'md5sum' => $md5sum !== false ?
(is_string($md5sum) ? $md5sum : base64_encode(md5_file($file, true))) : '', 'sha256sum' => hash_file('sha256', $file));
}
* Create input array info for putObject() with a resource
*
* @param string $resource Input resource to read from
* @param integer $bufferSize Input byte size
* @param string $md5sum MD5 hash to send (optional)
* @return array | false
*/
public static function inputResource() {
}
if ($bufferSize === false)
{
if (fseek($resource, 0, SEEK_END) < 0 || ($bufferSize = ftell($resource)) === false)
{
self::__triggerError('S3::inputResource(): Unable to obtain resource size', __FILE__, __LINE__);
return false;
}
fseek($resource, 0);
}
$input = array('size' => $bufferSize, 'md5sum' => $md5sum);
$input['fp'] =& $resource;
return $input;
}
* Initiate a multi-part upload (http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadInitiate.html)
*
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param string $acl ACL constant
* @param array $metaHeaders Array of x-amz-meta-* headers
* @param array $requestHeaders Array of request headers or content type as a string
* @param string $storageClass Storage class constant
*
* @return string | false
*/
public static function initiateMultipartUpload($bucket, $uri, $acl = self::ACL_PRIVATE, $metaHeaders = array(), $requestHeaders = array(), $storageClass = self::STORAGE_CLASS_STANDARD){
$rest = new S3Request('POST', $bucket, $uri, self::$endpoint);
$rest->setParameter('uploads','');
if (is_array($requestHeaders))
foreach ($requestHeaders as $h => $v) $rest->setHeader($h, $v);
if ($storageClass !== self::STORAGE_CLASS_STANDARD) // Storage class
$rest->setAmzHeader('x-amz-storage-class', $storageClass);
$rest->setAmzHeader('x-amz-acl', $acl);
foreach ($metaHeaders as $h => $v) $rest->setAmzHeader('x-amz-meta-'.$h, $v);
$rest->getResponse();
if (false === $rest->response->error && 200 !== $rest->response->code) {
$rest->response->error = array('code' => $rest->response->code, 'message' => 'Unexpected HTTP status');
}
if(false !== $rest->response->error){
self::__triggerError(sprintf("S3::initiateMultipartUpload(): [%s] %s",
$rest->response->error['code'], $rest->response->error['message']), __FILE__, __LINE__);
return false;
} elseif (isset($rest->response->body)) {
if (is_a($rest->response->body, 'SimpleXMLElement')) {
$body = $rest->response->body;
} else {
$body = new SimpleXMLElement($rest->response->body);
}
return (string) $body->UploadId;
}
return false;
}
* Upload a part of a multi-part set (http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadUploadPart.html)
* The chunk is read into memory, so make sure that you have enough (or patch this function to work another way!)
*
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param string $uploadId uploadId returned previously from initiateMultipartUpload
* @param integer $partNumber sequential part number to upload
* @param string $filePath file to upload content from
* @param integer $partSize number of bytes in each part (though final part may have fewer) - pass the same value each time (for this particular upload) - default 5Mb (which is Amazon's minimum)
* @return string (ETag) | false
*/
public static function uploadPart() {
}
if(false === $rest->error && 200 !== $rest->code){
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
}
if(false !== $rest->error){
self::__triggerError(sprintf("S3::uploadPart(): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
$GLOBALS['error'][] = $rest->error['message'];
$error[] = 'Error Code : '.$rest->error['code'].', Error : '.$rest->error['message'];
return false;
}
return $rest->headers['hash'];
}
* Complete a multi-part upload (http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadComplete.html)
*
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param string $uploadId uploadId returned previously from initiateMultipartUpload
* @param array $parts an ordered list of eTags of previously uploaded parts from uploadPart
* @return boolean
*/
public static function completeMultipartUpload() {
}
$xml .= "</CompleteMultipartUpload>";
$rest->data = $xml;
$rest->size = strlen($rest->data);
$rest->setHeader('Content-Type', 'application/xml');
$rest = $rest->getResponse();
if(false === $rest->error && 200 !== $rest->code){
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
}
if(false !== $rest->error){
if ('InternalError' == $rest->error['code'] && 'This multipart completion is already in progress' == $rest->error['message']) {
return true;
}
self::__triggerError(sprintf("S3::completeMultipartUpload(): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
return true;
}
* Abort a multi-part upload (http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadAbort.html)
*
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param string $uploadId uploadId returned previously from initiateMultipartUpload
* @return boolean
*/
public static function abortMultipartUpload() {
}
if (false !== $rest->error) {
self::__triggerError(sprintf("S3::abortMultipartUpload(): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
return true;
}
* Put an object
*
* @param mixed $input Input data
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param constant $acl ACL constant
* @param array $metaHeaders Array of x-amz-meta-* headers
* @param array $requestHeaders Array of request headers or content type as a string
* @param constant $storageClass Storage class constant
* @param constant $serverSideEncryption Server-side encryption
* @return boolean
*/
public static function putObject($input, $bucket, $uri, $acl = self::ACL_PRIVATE, $metaHeaders = array(), $requestHeaders = array(), $storageClass = self::STORAGE_CLASS_STANDARD, $serverSideEncryption = self::SSE_NONE)
{
if ($input === false) return false;
$rest = new S3Request('PUT', $bucket, $uri, self::$endpoint);
if (!is_array($input)) $input = array(
'data' => $input, 'size' => strlen($input),
'md5sum' => base64_encode(md5($input, true)),
'sha256sum' => hash('sha256', $input)
);
if (isset($input['fp']))
$rest->fp =& $input['fp'];
elseif (isset($input['file']))
$rest->fp = @fopen($input['file'], 'rb');
elseif (isset($input['data']))
$rest->data = $input['data'];
if (isset($input['size']) && $input['size'] >= 0)
$rest->size = $input['size'];
else {
if (isset($input['file'])) {
clearstatcache(false, $input['file']);
$rest->size = filesize($input['file']);
}
elseif (isset($input['data']))
$rest->size = strlen($input['data']);
}
if (is_array($requestHeaders))
foreach ($requestHeaders as $h => $v)
strpos($h, 'x-amz-') === 0 ? $rest->setAmzHeader($h, $v) : $rest->setHeader($h, $v);
elseif (is_string($requestHeaders)) // Support for legacy contentType parameter
$input['type'] = $requestHeaders;
if (!isset($input['type']))
{
if (isset($requestHeaders['Content-Type']))
$input['type'] =& $requestHeaders['Content-Type'];
elseif (isset($input['file']))
$input['type'] = self::__getMIMEType($input['file']);
else
$input['type'] = 'application/octet-stream';
}
if ($storageClass !== self::STORAGE_CLASS_STANDARD) // Storage class
$rest->setAmzHeader('x-amz-storage-class', $storageClass);
if ($serverSideEncryption !== self::SSE_NONE) // Server-side encryption
$rest->setAmzHeader('x-amz-server-side-encryption', $serverSideEncryption);
if ($rest->size >= 0 && ($rest->fp !== false || $rest->data !== false))
{
$rest->setHeader('Content-Type', $input['type']);
if (isset($input['md5sum'])) $rest->setHeader('Content-MD5', $input['md5sum']);
if (isset($input['sha256sum'])) $rest->setAmzHeader('x-amz-content-sha256', $input['sha256sum']);
$rest->setAmzHeader('x-amz-acl', $acl);
foreach ($metaHeaders as $h => $v) $rest->setAmzHeader('x-amz-meta-'.$h, $v);
$rest->getResponse();
} else
$rest->response->error = array('code' => 0, 'message' => 'Missing input parameters');
if ($rest->response->error === false && $rest->response->code !== 200)
$rest->response->error = array('code' => $rest->response->code, 'message' => 'Unexpected HTTP status');
if ($rest->response->error !== false)
{
self::__triggerError(sprintf("S3::putObject(): [%s] %s",
$rest->response->error['code'], $rest->response->error['message']), __FILE__, __LINE__);
return false;
}
return true;
}
* Put an object from a file (legacy function)
*
* @param string $file Input file path
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param constant $acl ACL constant
* @param array $metaHeaders Array of x-amz-meta-* headers
* @param string $contentType Content type
* @return boolean
*/
public static function putObjectFile($file, $bucket, $uri, $acl = self::ACL_PRIVATE, $metaHeaders = array(), $contentType = null)
{
return self::putObject(self::inputFile($file), $bucket, $uri, $acl, $metaHeaders, $contentType);
}
* Put an object from a string (legacy function)
*
* @param string $string Input data
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param constant $acl ACL constant
* @param array $metaHeaders Array of x-amz-meta-* headers
* @param string $contentType Content type
* @return boolean
*/
public static function putObjectString($string, $bucket, $uri, $acl = self::ACL_PRIVATE, $metaHeaders = array(), $contentType = 'text/plain')
{
return self::putObject($string, $bucket, $uri, $acl, $metaHeaders, $contentType);
}
* Get an object
*
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param mixed $saveTo Filename or resource to write to
* @return mixed
*/
public static function getObject() {
}else
$rest->response->error = array('code' => 0, 'message' => 'Unable to open save file for writing: '.$saveTo);
} else{
$rest->setHeader('Range', 'bytes='.self::$range_lower_limit.'-'. self::$range_upper_limit);
}
if ($rest->response->error === false) $rest->getResponse();
if ($rest->response->error === false && $rest->response->code !== 200 && $rest->response->code !== 206){
$rest->response->error = array('code' => $rest->response->code, 'message' => 'Unexpected HTTP status');
}
if ($rest->response->error !== false)
{
self::__triggerError(sprintf("S3::getObject({$bucket}, {$uri}): [%s] %s",
$rest->response->error['code'], $rest->response->error['message']), __FILE__, __LINE__);
return false;
}
return $rest->response;
}
* Get object information
*
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param boolean $returnInfo Return response information
* @return mixed | false
*/
public static function getObjectInfo() {
}, {$uri}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
return $rest->code == 200 ? $returnInfo ? $rest->headers : true : false;
}
* Copy an object
*
* @param string $srcBucket Source bucket name
* @param string $srcUri Source object URI
* @param string $bucket Destination bucket name
* @param string $uri Destination object URI
* @param constant $acl ACL constant
* @param array $metaHeaders Optional array of x-amz-meta-* headers
* @param array $requestHeaders Optional array of request headers (content type, disposition, etc.)
* @param constant $storageClass Storage class constant
* @return mixed | false
*/
public static function copyObject($srcBucket, $srcUri, $bucket, $uri, $acl = self::ACL_PRIVATE, $metaHeaders = array(), $requestHeaders = array(), $storageClass = self::STORAGE_CLASS_STANDARD)
{
$rest = new S3Request('PUT', $bucket, $uri, self::$endpoint);
$rest->setHeader('Content-Length', 0);
foreach ($requestHeaders as $h => $v)
strpos($h, 'x-amz-') === 0 ? $rest->setAmzHeader($h, $v) : $rest->setHeader($h, $v);
foreach ($metaHeaders as $h => $v) $rest->setAmzHeader('x-amz-meta-'.$h, $v);
if ($storageClass !== self::STORAGE_CLASS_STANDARD) // Storage class
$rest->setAmzHeader('x-amz-storage-class', $storageClass);
$rest->setAmzHeader('x-amz-acl', $acl);
$rest->setAmzHeader('x-amz-copy-source', sprintf('/%s/%s', $srcBucket, rawurlencode($srcUri)));
if (sizeof($requestHeaders) > 0 || sizeof($metaHeaders) > 0)
$rest->setAmzHeader('x-amz-metadata-directive', 'REPLACE');
$rest = $rest->getResponse();
if ($rest->error === false && $rest->code !== 200)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
self::__triggerError(sprintf("S3::copyObject({$srcBucket}, {$srcUri}, {$bucket}, {$uri}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
return isset($rest->body->LastModified, $rest->body->ETag) ? array(
'time' => strtotime((string)$rest->body->LastModified),
'hash' => substr((string)$rest->body->ETag, 1, -1)
) : false;
}
* Set up a bucket redirection
*
* @param string $bucket Bucket name
* @param string $location Target host name
* @return boolean
*/
public static function setBucketRedirect() {
}, {$location}): Empty parameter.", __FILE__, __LINE__);
return false;
}
$dom = new DOMDocument;
$websiteConfiguration = $dom->createElement('WebsiteConfiguration');
$redirectAllRequestsTo = $dom->createElement('RedirectAllRequestsTo');
$hostName = $dom->createElement('HostName', $location);
$redirectAllRequestsTo->appendChild($hostName);
$websiteConfiguration->appendChild($redirectAllRequestsTo);
$dom->appendChild($websiteConfiguration);
$rest->setParameter('website', null);
$rest->data = $dom->saveXML();
$rest->size = strlen($rest->data);
$rest->setHeader('Content-Type', 'application/xml');
$rest = $rest->getResponse();
if ($rest->error === false && $rest->code !== 200)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
self::__triggerError(sprintf("S3::setBucketRedirect({$bucket}, {$location}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
return true;
}
* Set logging for a bucket
*
* @param string $bucket Bucket name
* @param string $targetBucket Target bucket (where logs are stored)
* @param string $targetPrefix Log prefix (e,g; domain.com-)
* @return boolean
*/
public static function setBucketLogging() {
}
if (!$aclWriteSet) $acp['acl'][] = array(
'type' => 'Group', 'uri' => 'http://acs.amazonaws.com/groups/s3/LogDelivery', 'permission' => 'WRITE'
);
if (!$aclReadSet) $acp['acl'][] = array(
'type' => 'Group', 'uri' => 'http://acs.amazonaws.com/groups/s3/LogDelivery', 'permission' => 'READ_ACP'
);
if (!$aclReadSet || !$aclWriteSet) self::setAccessControlPolicy($targetBucket, '', $acp);
}
$dom = new DOMDocument;
$bucketLoggingStatus = $dom->createElement('BucketLoggingStatus');
$bucketLoggingStatus->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/');
if ($targetBucket !== null)
{
if ($targetPrefix == null) $targetPrefix = $bucket . '-';
$loggingEnabled = $dom->createElement('LoggingEnabled');
$loggingEnabled->appendChild($dom->createElement('TargetBucket', $targetBucket));
$loggingEnabled->appendChild($dom->createElement('TargetPrefix', $targetPrefix));
$bucketLoggingStatus->appendChild($loggingEnabled);
}
$dom->appendChild($bucketLoggingStatus);
$rest = new S3Request('PUT', $bucket, '', self::$endpoint);
$rest->setParameter('logging', null);
$rest->data = $dom->saveXML();
$rest->size = strlen($rest->data);
$rest->setHeader('Content-Type', 'application/xml');
$rest = $rest->getResponse();
if ($rest->error === false && $rest->code !== 200)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
self::__triggerError(sprintf("S3::setBucketLogging({$bucket}, {$targetBucket}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
return true;
}
* Get logging status for a bucket
*
* This will return false if logging is not enabled.
* Note: To enable logging, you also need to grant write access to the log group
*
* @param string $bucket Bucket name
* @return array | false
*/
public static function getBucketLogging() {
}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
if (!isset($rest->body->LoggingEnabled)) return false; // No logging
return array(
'targetBucket' => (string)$rest->body->LoggingEnabled->TargetBucket,
'targetPrefix' => (string)$rest->body->LoggingEnabled->TargetPrefix,
);
}
* Disable bucket logging
*
* @param string $bucket Bucket name
* @return boolean
*/
public static function disableBucketLogging() {
}
* Get a bucket's location
*
* @param string $bucket Bucket name
* @return string | false
*/
public static function getBucketLocation() {
}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
return (isset($rest->body[0]) && (string)$rest->body[0] !== '') ? (string)$rest->body[0] : 'US';
}
* Set object or bucket Access Control Policy
*
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param array $acp Access Control Policy Data (same as the data returned from getAccessControlPolicy)
* @return boolean
*/
public static function setAccessControlPolicy($bucket, $uri = '', $acp = array())
{
$dom = new DOMDocument;
$dom->formatOutput = true;
$accessControlPolicy = $dom->createElement('AccessControlPolicy');
$accessControlList = $dom->createElement('AccessControlList');
$owner = $dom->createElement('Owner');
$owner->appendChild($dom->createElement('ID', $acp['owner']['id']));
$owner->appendChild($dom->createElement('DisplayName', $acp['owner']['name']));
$accessControlPolicy->appendChild($owner);
foreach ($acp['acl'] as $g)
{
$grant = $dom->createElement('Grant');
$grantee = $dom->createElement('Grantee');
$grantee->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
if (isset($g['id']))
{ // CanonicalUser (DisplayName is omitted)
$grantee->setAttribute('xsi:type', 'CanonicalUser');
$grantee->appendChild($dom->createElement('ID', $g['id']));
}
elseif (isset($g['email']))
{ // AmazonCustomerByEmail
$grantee->setAttribute('xsi:type', 'AmazonCustomerByEmail');
$grantee->appendChild($dom->createElement('EmailAddress', $g['email']));
}
elseif ($g['type'] == 'Group')
{ // Group
$grantee->setAttribute('xsi:type', 'Group');
$grantee->appendChild($dom->createElement('URI', $g['uri']));
}
$grant->appendChild($grantee);
$grant->appendChild($dom->createElement('Permission', $g['permission']));
$accessControlList->appendChild($grant);
}
$accessControlPolicy->appendChild($accessControlList);
$dom->appendChild($accessControlPolicy);
$rest = new S3Request('PUT', $bucket, $uri, self::$endpoint);
$rest->setParameter('acl', null);
$rest->data = $dom->saveXML();
$rest->size = strlen($rest->data);
$rest->setHeader('Content-Type', 'application/xml');
$rest = $rest->getResponse();
if ($rest->error === false && $rest->code !== 200)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
self::__triggerError(sprintf("S3::setAccessControlPolicy({$bucket}, {$uri}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
return true;
}
* Get object or bucket Access Control Policy
*
* @param string $bucket Bucket name
* @param string $uri Object URI
* @return mixed | false
*/
public static function getAccessControlPolicy() {
}, {$uri}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
$acp = array();
if (isset($rest->body->Owner, $rest->body->Owner->ID, $rest->body->Owner->DisplayName))
$acp['owner'] = array(
'id' => (string)$rest->body->Owner->ID, 'name' => (string)$rest->body->Owner->DisplayName
);
if (isset($rest->body->AccessControlList))
{
$acp['acl'] = array();
foreach ($rest->body->AccessControlList->Grant as $grant)
{
foreach ($grant->Grantee as $grantee)
{
if (isset($grantee->ID, $grantee->DisplayName)) // CanonicalUser
$acp['acl'][] = array(
'type' => 'CanonicalUser',
'id' => (string)$grantee->ID,
'name' => (string)$grantee->DisplayName,
'permission' => (string)$grant->Permission
);
elseif (isset($grantee->EmailAddress)) // AmazonCustomerByEmail
$acp['acl'][] = array(
'type' => 'AmazonCustomerByEmail',
'email' => (string)$grantee->EmailAddress,
'permission' => (string)$grant->Permission
);
elseif (isset($grantee->URI)) // Group
$acp['acl'][] = array(
'type' => 'Group',
'uri' => (string)$grantee->URI,
'permission' => (string)$grant->Permission
);
else continue;
}
}
}
return $acp;
}
* Delete an object
*
* @param string $bucket Bucket name
* @param string $uri Object URI
* @return boolean
*/
public static function deleteObject() {
}
return true;
}
* Get a query string authenticated URL
*
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param integer $lifetime Lifetime in seconds
* @param boolean $hostBucket Use the bucket name as the hostname
* @param boolean $https Use HTTPS ($hostBucket should be false for SSL verification)
* @return string
*/
public static function getAuthenticatedURL() {
}\n/{$bucket}/{$uri}")));
}
* Get a CloudFront signed policy URL
*
* @param array $policy Policy
* @return string
*/
public static function getSignedPolicyURL() {
}
* Get a CloudFront canned policy URL
*
* @param string $url URL to sign
* @param integer $lifetime URL lifetime
* @return string
*/
public static function getSignedCannedURL() {
}
* Get upload POST parameters for form uploads
*
* @param string $bucket Bucket name
* @param string $uriPrefix Object URI prefix
* @param constant $acl ACL constant
* @param integer $lifetime Lifetime in seconds
* @param integer $maxFileSize Maximum filesize in bytes (default 5MB)
* @param string $successRedirect Redirect URL or 200 / 201 status code
* @param array $amzHeaders Array of x-amz-meta-* headers
* @param array $headers Array of request headers or content type as a string
* @param boolean $flashVars Includes additional "Filename" variable posted by Flash
* @return object
*/
public static function getHttpUploadPostParams($bucket, $uriPrefix = '', $acl = self::ACL_PRIVATE, $lifetime = 3600,
$maxFileSize = 5242880, $successRedirect = "201", $amzHeaders = array(), $headers = array(), $flashVars = false)
{
$policy = new stdClass;
$policy->expiration = gmdate('Y-m-d\TH:i:s\Z', (self::__getTime() + $lifetime));
$policy->conditions = array();
$obj = new stdClass; $obj->bucket = $bucket; array_push($policy->conditions, $obj);
$obj = new stdClass; $obj->acl = $acl; array_push($policy->conditions, $obj);
$obj = new stdClass; // 200 for non-redirect uploads
if (is_numeric($successRedirect) && in_array((int)$successRedirect, array(200, 201)))
$obj->success_action_status = (string)$successRedirect;
else // URL
$obj->success_action_redirect = $successRedirect;
array_push($policy->conditions, $obj);
if ($acl !== self::ACL_PUBLIC_READ)
array_push($policy->conditions, array('eq', '$acl', $acl));
array_push($policy->conditions, array('starts-with', '$key', $uriPrefix));
if ($flashVars) array_push($policy->conditions, array('starts-with', '$Filename', ''));
foreach (array_keys($headers) as $headerKey)
array_push($policy->conditions, array('starts-with', '$'.$headerKey, ''));
foreach ($amzHeaders as $headerKey => $headerVal)
{
$obj = new stdClass;
$obj->{$headerKey} = (string)$headerVal;
array_push($policy->conditions, $obj);
}
array_push($policy->conditions, array('content-length-range', 0, $maxFileSize));
$policy = base64_encode(str_replace('\/', '/', json_encode($policy)));
$params = new stdClass;
$params->AWSAccessKeyId = self::$__accessKey;
$params->key = $uriPrefix.'${filename}';
$params->acl = $acl;
$params->policy = $policy; unset($policy);
$params->signature = self::__getHash($params->policy);
if (is_numeric($successRedirect) && in_array((int)$successRedirect, array(200, 201)))
$params->success_action_status = (string)$successRedirect;
else
$params->success_action_redirect = $successRedirect;
foreach ($headers as $headerKey => $headerVal) $params->{$headerKey} = (string)$headerVal;
foreach ($amzHeaders as $headerKey => $headerVal) $params->{$headerKey} = (string)$headerVal;
return $params;
}
* Create a CloudFront distribution
*
* @param string $bucket Bucket name
* @param boolean $enabled Enabled (true/false)
* @param array $cnames Array containing CNAME aliases
* @param string $comment Use the bucket name as the hostname
* @param string $defaultRootObject Default root object
* @param string $originAccessIdentity Origin access identity
* @param array $trustedSigners Array of trusted signers
* @return array | false
*/
public static function createDistribution($bucket, $enabled = true, $cnames = array(), $comment = null, $defaultRootObject = null, $originAccessIdentity = null, $trustedSigners = array())
{
if (!extension_loaded('openssl'))
{
self::__triggerError(sprintf("S3::createDistribution({$bucket}, ".(int)$enabled.", [], '$comment'): %s",
"CloudFront functionality requires SSL"), __FILE__, __LINE__);
return false;
}
$useSSL = self::$useSSL;
self::$useSSL = true; // CloudFront requires SSL
$rest = new S3Request('POST', '', '2010-11-01/distribution', 'cloudfront.amazonaws.com');
$rest->data = self::__getCloudFrontDistributionConfigXML(
$bucket.'.s3.amazonaws.com',
$enabled,
(string)$comment,
(string)microtime(true),
$cnames,
$defaultRootObject,
$originAccessIdentity,
$trustedSigners
);
$rest->size = strlen($rest->data);
$rest->setHeader('Content-Type', 'application/xml');
$rest = self::__getCloudFrontResponse($rest);
self::$useSSL = $useSSL;
if ($rest->error === false && $rest->code !== 201)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
self::__triggerError(sprintf("S3::createDistribution({$bucket}, ".(int)$enabled.", [], '$comment'): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
} elseif ($rest->body instanceof SimpleXMLElement)
return self::__parseCloudFrontDistributionConfig($rest->body);
return false;
}
* Get CloudFront distribution info
*
* @param string $distributionId Distribution ID from listDistributions()
* @return array | false
*/
public static function getDistribution() {
}
$useSSL = self::$useSSL;
self::$useSSL = true; // CloudFront requires SSL
$rest = new S3Request('GET', '', '2010-11-01/distribution/'.$distributionId, 'cloudfront.amazonaws.com');
$rest = self::__getCloudFrontResponse($rest);
self::$useSSL = $useSSL;
if ($rest->error === false && $rest->code !== 200)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
self::__triggerError(sprintf("S3::getDistribution($distributionId): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
elseif ($rest->body instanceof SimpleXMLElement)
{
$dist = self::__parseCloudFrontDistributionConfig($rest->body);
$dist['hash'] = $rest->headers['hash'];
$dist['id'] = $distributionId;
return $dist;
}
return false;
}
* Update a CloudFront distribution
*
* @param array $dist Distribution array info identical to output of getDistribution()
* @return array | false
*/
public static function updateDistribution() {
}): %s",
"CloudFront functionality requires SSL"), __FILE__, __LINE__);
return false;
}
$useSSL = self::$useSSL;
self::$useSSL = true; // CloudFront requires SSL
$rest = new S3Request('PUT', '', '2010-11-01/distribution/'.$dist['id'].'/config', 'cloudfront.amazonaws.com');
$rest->data = self::__getCloudFrontDistributionConfigXML(
$dist['origin'],
$dist['enabled'],
$dist['comment'],
$dist['callerReference'],
$dist['cnames'],
$dist['defaultRootObject'],
$dist['originAccessIdentity'],
$dist['trustedSigners']
);
$rest->size = strlen($rest->data);
$rest->setHeader('If-Match', $dist['hash']);
$rest = self::__getCloudFrontResponse($rest);
self::$useSSL = $useSSL;
if ($rest->error === false && $rest->code !== 200)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
self::__triggerError(sprintf("S3::updateDistribution({$dist['id']}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
} else {
$dist = self::__parseCloudFrontDistributionConfig($rest->body);
$dist['hash'] = $rest->headers['hash'];
return $dist;
}
return false;
}
* Delete a CloudFront distribution
*
* @param array $dist Distribution array info identical to output of getDistribution()
* @return boolean
*/
public static function deleteDistribution() {
}): %s",
"CloudFront functionality requires SSL"), __FILE__, __LINE__);
return false;
}
$useSSL = self::$useSSL;
self::$useSSL = true; // CloudFront requires SSL
$rest = new S3Request('DELETE', '', '2008-06-30/distribution/'.$dist['id'], 'cloudfront.amazonaws.com');
$rest->setHeader('If-Match', $dist['hash']);
$rest = self::__getCloudFrontResponse($rest);
self::$useSSL = $useSSL;
if ($rest->error === false && $rest->code !== 204)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
self::__triggerError(sprintf("S3::deleteDistribution({$dist['id']}): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
return true;
}
* Get a list of CloudFront distributions
*
* @return array
*/
public static function listDistributions() {
}
$useSSL = self::$useSSL;
self::$useSSL = true; // CloudFront requires SSL
$rest = new S3Request('GET', '', '2010-11-01/distribution', 'cloudfront.amazonaws.com');
$rest = self::__getCloudFrontResponse($rest);
self::$useSSL = $useSSL;
if ($rest->error === false && $rest->code !== 200)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
self::__triggerError(sprintf("S3::listDistributions(): [%s] %s",
$rest->error['code'], $rest->error['message']), __FILE__, __LINE__);
return false;
}
elseif ($rest->body instanceof SimpleXMLElement && isset($rest->body->DistributionSummary))
{
$list = array();
if (isset($rest->body->Marker, $rest->body->MaxItems, $rest->body->IsTruncated))
{
}
foreach ($rest->body->DistributionSummary as $summary)
$list[(string)$summary->Id] = self::__parseCloudFrontDistributionConfig($summary);
return $list;
}
return array();
}
* List CloudFront Origin Access Identities
*
* @return array
*/
public static function listOriginAccessIdentities() {
}
self::$useSSL = true; // CloudFront requires SSL
$rest = new S3Request('GET', '', '2010-11-01/origin-access-identity/cloudfront', 'cloudfront.amazonaws.com');
$rest = self::__getCloudFrontResponse($rest);
$useSSL = self::$useSSL;
if ($rest->error === false && $rest->code !== 200)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
trigger_error(sprintf("S3::listOriginAccessIdentities(): [%s] %s",
$rest->error['code'], $rest->error['message']), E_USER_WARNING);
return false;
}
if (isset($rest->body->CloudFrontOriginAccessIdentitySummary))
{
$identities = array();
foreach ($rest->body->CloudFrontOriginAccessIdentitySummary as $identity)
if (isset($identity->S3CanonicalUserId))
$identities[(string)$identity->Id] = array('id' => (string)$identity->Id, 's3CanonicalUserId' => (string)$identity->S3CanonicalUserId);
return $identities;
}
return false;
}
* Invalidate objects in a CloudFront distribution
*
* Thanks to Martin Lindkvist for S3::invalidateDistribution()
*
* @param string $distributionId Distribution ID from listDistributions()
* @param array $paths Array of object paths to invalidate
* @return boolean
*/
public static function invalidateDistribution() {
}
$useSSL = self::$useSSL;
self::$useSSL = true; // CloudFront requires SSL
$rest = new S3Request('POST', '', '2010-08-01/distribution/'.$distributionId.'/invalidation', 'cloudfront.amazonaws.com');
$rest->data = self::__getCloudFrontInvalidationBatchXML($paths, (string)microtime(true));
$rest->size = strlen($rest->data);
$rest = self::__getCloudFrontResponse($rest);
self::$useSSL = $useSSL;
if ($rest->error === false && $rest->code !== 201)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
trigger_error(sprintf("S3::invalidate('{$distributionId}',{$paths}): [%s] %s",
$rest->error['code'], $rest->error['message']), E_USER_WARNING);
return false;
}
return true;
}
* Get a InvalidationBatch DOMDocument
*
* @internal Used to create XML in invalidateDistribution()
* @param array $paths Paths to objects to invalidateDistribution
* @param int $callerReference
* @return string
*/
private static function __getCloudFrontInvalidationBatchXML() {
}
* List your invalidation batches for invalidateDistribution() in a CloudFront distribution
*
* http://docs.amazonwebservices.com/AmazonCloudFront/latest/APIReference/ListInvalidation.html
* returned array looks like this:
*	Array
*	(
*		[I31TWB0CN9V6XD] => InProgress
*		[IT3TFE31M0IHZ] => Completed
*		[I12HK7MPO1UQDA] => Completed
*		[I1IA7R6JKTC3L2] => Completed
*	)
*
* @param string $distributionId Distribution ID from listDistributions()
* @return array
*/
public static function getDistributionInvalidationList() {
}
$useSSL = self::$useSSL;
self::$useSSL = true; // CloudFront requires SSL
$rest = new S3Request('GET', '', '2010-11-01/distribution/'.$distributionId.'/invalidation', 'cloudfront.amazonaws.com');
$rest = self::__getCloudFrontResponse($rest);
self::$useSSL = $useSSL;
if ($rest->error === false && $rest->code !== 200)
$rest->error = array('code' => $rest->code, 'message' => 'Unexpected HTTP status');
if ($rest->error !== false)
{
trigger_error(sprintf("S3::getDistributionInvalidationList('{$distributionId}'): [%s]",
$rest->error['code'], $rest->error['message']), E_USER_WARNING);
return false;
}
elseif ($rest->body instanceof SimpleXMLElement && isset($rest->body->InvalidationSummary))
{
$list = array();
foreach ($rest->body->InvalidationSummary as $summary)
$list[(string)$summary->Id] = (string)$summary->Status;
return $list;
}
return array();
}
* Get a DistributionConfig DOMDocument
*
* http://docs.amazonwebservices.com/AmazonCloudFront/latest/APIReference/index.html?PutConfig.html
*
* @internal Used to create XML in createDistribution() and updateDistribution()
* @param string $bucket S3 Origin bucket
* @param boolean $enabled Enabled (true/false)
* @param string $comment Comment to append
* @param string $callerReference Caller reference
* @param array $cnames Array of CNAME aliases
* @param string $defaultRootObject Default root object
* @param string $originAccessIdentity Origin access identity
* @param array $trustedSigners Array of trusted signers
* @return string
*/
private static function __getCloudFrontDistributionConfigXML($bucket, $enabled, $comment, $callerReference = '0', $cnames = array(), $defaultRootObject = null, $originAccessIdentity = null, $trustedSigners = array())
{
$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;
$distributionConfig = $dom->createElement('DistributionConfig');
$distributionConfig->setAttribute('xmlns', 'http://cloudfront.amazonaws.com/doc/2010-11-01/');
$origin = $dom->createElement('S3Origin');
$origin->appendChild($dom->createElement('DNSName', $bucket));
if ($originAccessIdentity !== null) $origin->appendChild($dom->createElement('OriginAccessIdentity', $originAccessIdentity));
$distributionConfig->appendChild($origin);
if ($defaultRootObject !== null) $distributionConfig->appendChild($dom->createElement('DefaultRootObject', $defaultRootObject));
$distributionConfig->appendChild($dom->createElement('CallerReference', $callerReference));
foreach ($cnames as $cname)
$distributionConfig->appendChild($dom->createElement('CNAME', $cname));
if ($comment !== '') $distributionConfig->appendChild($dom->createElement('Comment', $comment));
$distributionConfig->appendChild($dom->createElement('Enabled', $enabled ? 'true' : 'false'));
if (!empty($trustedSigners))
{
$trusted = $dom->createElement('TrustedSigners');
foreach ($trustedSigners as $id => $type)
$trusted->appendChild($id !== '' ? $dom->createElement($type, $id) : $dom->createElement($type));
$distributionConfig->appendChild($trusted);
}
$dom->appendChild($distributionConfig);
return $dom->saveXML();
}
* Parse a CloudFront distribution config
*
* See http://docs.amazonwebservices.com/AmazonCloudFront/latest/APIReference/index.html?GetDistribution.html
*
* @internal Used to parse the CloudFront DistributionConfig node to an array
* @param object &$node DOMNode
* @return array
*/
private static function __parseCloudFrontDistributionConfig() {
}
if (isset($node->CallerReference))
$dist['callerReference'] = (string)$node->CallerReference;
if (isset($node->Enabled))
$dist['enabled'] = (string)$node->Enabled == 'true' ? true : false;
if (isset($node->S3Origin))
{
if (isset($node->S3Origin->DNSName))
$dist['origin'] = (string)$node->S3Origin->DNSName;
$dist['originAccessIdentity'] = isset($node->S3Origin->OriginAccessIdentity) ?
(string)$node->S3Origin->OriginAccessIdentity : null;
}
$dist['defaultRootObject'] = isset($node->DefaultRootObject) ? (string)$node->DefaultRootObject : null;
$dist['cnames'] = array();
if (isset($node->CNAME))
foreach ($node->CNAME as $cname)
$dist['cnames'][(string)$cname] = (string)$cname;
$dist['trustedSigners'] = array();
if (isset($node->TrustedSigners))
foreach ($node->TrustedSigners as $signer)
{
if (isset($signer->Self))
$dist['trustedSigners'][''] = 'Self';
elseif (isset($signer->KeyPairId))
$dist['trustedSigners'][(string)$signer->KeyPairId] = 'KeyPairId';
elseif (isset($signer->AwsAccountNumber))
$dist['trustedSigners'][(string)$signer->AwsAccountNumber] = 'AwsAccountNumber';
}
$dist['comment'] = isset($node->Comment) ? (string)$node->Comment : null;
return $dist;
}
* Grab CloudFront response
*
* @internal Used to parse the CloudFront S3Request::getResponse() output
* @param object &$rest S3Request instance
* @return object
*/
private static function __getCloudFrontResponse() {
}
}
return $rest->response;
}
* Get MIME type for file
*
* To override the putObject() Content-Type, add it to $requestHeaders
*
* To use fileinfo, ensure the MAGIC environment variable is set
*
* @internal Used to get mime types
* @param string &$file File path
* @return string
*/
private static function __getMIMEType() {
}
finfo_close($finfo);
if ($type !== false && strlen($type) > 0) return $type;
}
return 'application/octet-stream';
}
* Get the current time
*
* @internal Used to apply offsets to sytem time
* @return integer
*/
public static function __getTime() {
}
* Generate the auth string: "AWS AccessKey:Signature"
*
* @internal Used by S3Request::getResponse()
* @param string $string String to sign
* @return string
*/
public static function __getSignature() {
}
* Creates a HMAC-SHA1 hash
*
* This uses the hash extension if loaded
*
* @internal Used by __getSignature()
* @param string $string String to sign
* @return string
*/
private static function __getHash() {
}
* Generate the headers for AWS Signature V4
*
* @internal Used by S3Request::getResponse()
* @param array $amzHeaders
* @param array $headers
* @param string $method
* @param string $uri
* @param array $parameters
* @return array
*/
public static function __getSignatureV4() {
}
$amzPayload[] = '';
$amzPayload[] = implode(';', array_keys($combinedHeaders));
$amzPayload[] = $amzHeaders['x-amz-content-sha256'];
$amzPayloadStr = implode("\n", $amzPayload);
$credentialScope = array($amzDateStamp, $region, $service, 'aws4_request');
$stringToSignStr = implode("\n", array($algorithm, $amzHeaders['x-amz-date'],
implode('/', $credentialScope), hash('sha256', $amzPayloadStr)));
$kSecret = 'AWS4' . self::$__secretKey;
$kDate = hash_hmac('sha256', $amzDateStamp, $kSecret, true);
$kRegion = hash_hmac('sha256', $region, $kDate, true);
$kService = hash_hmac('sha256', $service, $kRegion, true);
$kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);
$signature = hash_hmac('sha256', $stringToSignStr, $kSigning);
return $algorithm . ' ' . implode(',', array(
'Credential=' . self::$__accessKey . '/' . implode('/', $credentialScope),
'SignedHeaders=' . implode(';', array_keys($combinedHeaders)),
'Signature=' . $signature,
));
}
* Sort compare for meta headers
*
* @internal Used to sort x-amz meta headers
* @param string $a String A
* @param string $b String B
* @return integer
*/
private static function __sortMetaHeadersCmp() {
}
}
* S3 Request class
*
* @link http://undesigned.org.za/2007/10/22/amazon-s3-php-class
* @version 0.5.0-dev
*/
final class S3Request
{
* AWS URI
*
* @var string
* @access private
*/
private $endpoint;
* Verb
*
* @var string
* @access private
*/
private $verb;
* S3 bucket name
*
* @var string
* @access private
*/
private $bucket;
* Object URI
*
* @var string
* @access private
*/
private $uri;
* Final object URI
*
* @var string
* @access private
*/
private $resource = '';
* Additional request parameters
*
* @var array
* @access private
*/
private $parameters = array();
* Amazon specific request headers
*
* @var array
* @access private
*/
private $amzHeaders = array();
* HTTP request headers
*
* @var array
* @access private
*/
private $headers = array(
'Host' => '', 'Date' => '', 'Content-MD5' => '', 'Content-Type' => ''
);
* Use HTTP PUT?
*
* @var bool
* @access public
*/
public $fp = false;
* PUT file size
*
* @var int
* @access public
*/
public $size = 0;
* PUT post fields
*
* @var array
* @access public
*/
public $data = false;
* S3 request respone
*
* @var object
* @access public
*/
public $response;
* Constructor
*
* @param string $verb Verb
* @param string $bucket Bucket name
* @param string $uri Object URI
* @param string $endpoint AWS endpoint URI
* @return mixed
*/
function __construct() {
}
else
{
$this->headers['Host'] = $this->endpoint;
$this->uri = $this->uri;
if ($this->bucket !== '') $this->uri = '/'.$this->bucket.$this->uri;
$this->bucket = '';
$this->resource = $this->uri;
}
}
else
{
$this->headers['Host'] = $this->endpoint;
$this->resource = $this->uri;
}
$this->headers['Date'] = gmdate('D, d M Y H:i:s T');
$this->response = new STDClass;
$this->response->error = false;
$this->response->body = null;
$this->response->headers = array();
}
* Set request parameter
*
* @param string $key Key
* @param string $value Value
* @return void
*/
public function setParameter() {
}
* Set request header
*
* @param string $key Key
* @param string $value Value
* @return void
*/
public function setHeader() {
}
* Set x-amz-meta-* header
*
* @param string $key Key
* @param string $value Value
* @return void
*/
public function setAmzHeader() {
}
* Get the S3 response
*
* @return object | false
*/
public function getResponse() {
}
$url = (S3::$useSSL ? 'https://' : 'http://') . ($this->headers['Host'] !== '' ? $this->headers['Host'] : $this->endpoint) . $this->uri;
$curl = curl_init();
curl_setopt($curl, CURLOPT_USERAGENT, 'S3/php');
if (S3::$useSSL)
{
curl_setopt($curl, CURLOPT_SSLVERSION, S3::$useSSLVersion);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, S3::$useSSLValidation ? 0 : 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, S3::$useSSLValidation ? 0 : 0);
if (S3::$sslKey !== null) curl_setopt($curl, CURLOPT_SSLKEY, S3::$sslKey);
if (S3::$sslCert !== null) curl_setopt($curl, CURLOPT_SSLCERT, S3::$sslCert);
if (S3::$sslCACert !== null) curl_setopt($curl, CURLOPT_CAINFO, S3::$sslCACert);
}
curl_setopt($curl, CURLOPT_URL, $url);
if (S3::$proxy != null && isset(S3::$proxy['host']))
{
curl_setopt($curl, CURLOPT_PROXY, S3::$proxy['host']);
curl_setopt($curl, CURLOPT_PROXYTYPE, S3::$proxy['type']);
if (isset(S3::$proxy['user'], S3::$proxy['pass']) && S3::$proxy['user'] != null && S3::$proxy['pass'] != null)
curl_setopt($curl, CURLOPT_PROXYUSERPWD, sprintf('%s:%s', S3::$proxy['user'], S3::$proxy['pass']));
}
$httpHeaders = array();
if(S3::$signVer == 'v2'){
$amz = array();
foreach ($this->amzHeaders as $header => $value)
if (strlen($value) > 0) $httpHeaders[] = $header.': '.$value;
foreach ($this->headers as $header => $value)
if (strlen($value) > 0) $httpHeaders[] = $header.': '.$value;
foreach ($this->amzHeaders as $header => $value)
if (strlen($value) > 0) $amz[] = strtolower($header).':'.$value;
if (sizeof($amz) > 0)
{
usort($amz, array('self', '__sortMetaHeadersCmp'));
$amz = "\n".implode("\n", $amz);
} else $amz = '';
}
if (S3::hasAuth())
{
if ($this->headers['Host'] == 'cloudfront.amazonaws.com')
{
# TODO: Update CloudFront authentication
foreach ($this->amzHeaders as $header => $value)
if (strlen($value) > 0) $httpHeaders[] = $header.': '.$value;
foreach ($this->headers as $header => $value)
if (strlen($value) > 0) $httpHeaders[] = $header.': '.$value;
$httpHeaders[] = 'Authorization: ' . S3::__getSignature($this->headers['Date']);
}
else
{
if('v2' === S3::$signVer){
$httpHeaders[] = 'Authorization: ' . S3::__getSignature(
$this->verb."\n".
$this->headers['Content-MD5']."\n".
$this->headers['Content-Type']."\n".
$this->headers['Date'].$amz."\n".
$this->resource
);
} else {
$this->amzHeaders['x-amz-date'] = gmdate('Ymd\THis\Z');
if (!isset($this->amzHeaders['x-amz-content-sha256']))
$this->amzHeaders['x-amz-content-sha256'] = hash('sha256', $this->data);
foreach ($this->amzHeaders as $header => $value)
if (strlen($value) > 0) $httpHeaders[] = $header.': '.$value;
foreach ($this->headers as $header => $value)
if (strlen($value) > 0){
$httpHeaders[] = $header.': '.$value;
}else{
unset($this->headers[$header]);
}
$httpHeaders[] = 'Authorization: ' . S3::__getSignatureV4(
$this->amzHeaders,
$this->headers,
$this->verb,
$this->uri,
$this->parameters
);
}
}
}
curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeaders);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
curl_setopt($curl, CURLOPT_WRITEFUNCTION, array(&$this, '__responseWriteCallback'));
curl_setopt($curl, CURLOPT_HEADERFUNCTION, array(&$this, '__responseHeaderCallback'));
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
switch ($this->verb)
{
case 'GET': break;
case 'PUT': case 'POST': // POST only used for CloudFront
if ($this->fp !== false)
{
curl_setopt($curl, CURLOPT_PUT, true);
curl_setopt($curl, CURLOPT_INFILE, $this->fp);
if ($this->size >= 0)
curl_setopt($curl, CURLOPT_INFILESIZE, $this->size);
}
elseif ($this->data !== false)
{
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->verb);
curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
}
else
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->verb);
break;
case 'HEAD':
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'HEAD');
curl_setopt($curl, CURLOPT_NOBODY, true);
break;
case 'DELETE':
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
break;
default: break;
}
if (S3::$progressFunction) {
curl_setopt($curl, CURLOPT_NOPROGRESS, false);
curl_setopt($curl, CURLOPT_PROGRESSFUNCTION, S3::$progressFunction);
}
if (curl_exec($curl))
$this->response->code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
else
$this->response->error = array(
'code' => curl_errno($curl),
'message' => curl_error($curl),
'resource' => $this->resource
);
@curl_close($curl);
if ($this->response->error === false && isset($this->response->headers['type']) &&
$this->response->headers['type'] == 'application/xml' && isset($this->response->body))
{
$this->response->body = simplexml_load_string($this->response->body);
if (!in_array($this->response->code, array(200, 204, 206)) &&
isset($this->response->body->Code, $this->response->body->Message))
{
$this->response->error = array(
'code' => (string)$this->response->body->Code,
'message' => (string)$this->response->body->Message
);
if (isset($this->response->body->Resource))
$this->response->error['resource'] = (string)$this->response->body->Resource;
unset($this->response->body);
}
}
if ($this->fp !== false && is_resource($this->fp)) fclose($this->fp);
return $this->response;
}
* CURL write callback
*
* @param resource &$curl CURL resource
* @param string &$data Data
* @return integer
*/
private function __responseWriteCallback() {
}
* Check DNS conformity
*
* @param string $bucket Bucket name
* @return boolean
*/
private function __dnsBucketName() {
}
return true;
}
* CURL header callback
*
* @param resource $curl CURL resource
* @param string $data Data
* @return integer
*/
private function __responseHeaderCallback() {
}
return $strlen;
}
* Sort compare for meta headers
*
* @internal Used to sort x-amz meta headers
* @param string $a String A
* @param string $b String B
* @return integer
*/
private static function __sortMetaHeadersCmp() {
}
}
* S3 exception class
*
* @link http://undesigned.org.za/2007/10/22/amazon-s3-php-class
* @version 0.5.0-dev
*/
class S3Exception extends Exception {
* Class constructor
*
* @param string $message Exception message
* @param string $file File in which exception was created
* @param string $line Line number on which exception was created
* @param int $code Exception code
*/
function __construct() {
}
}
########################################################################################
final class softaws extends S3 {
private $position = 0, $mode = '', $buffer, $upload_url, $part_no = 1, $chunk = 1048576;
public $app_dir = 'Webuzo Backups';
public $start_pos = 0;
public $etags = [];
public $filesize = 0;
public function url_stat() {
}
public function unlink() {
}
public function mkdir() {
}
public function rmdir() {
}
public function rename() {
}
return false;
}
public function download_file() {
}
$file_stats = $this->url_stat($src);
$this->filesize = $file_stats['size'];
while($startpos < $this->filesize){
if(($this->filesize - $startpos) < $this->chunk){
$this->chunk = (int) $this->filesize - $startpos;
}
$resp = self::getObject($buc, $src_file, $dest, $this->part_no);
if(!empty($error)) {
$error[] = 'Download Failed! Did not get any data from AWS';
return false;
}
$startpos += $this->chunk;
$this->part_no += 1;
}
return true;
}
public function dir_opendir() {
}
return true;
}
return false;
}
public function dir_readdir() {
}
public function dir_rewinddir() {
}
public function dir_closedir() {
}
public function upload_append() {
}
if($etag !== false && is_string($etag)){
array_push($etags, $etag);
}
$this->etags = $etags;
if(empty($etags)){
$error[] = 'File upload failed! As we didn\'t recieve ETAG in the response from AWS';
return false;
}
return true;
}
public function stream_close() {
}
if(!empty($this->buffer)){
$D = $this->buffer;
$this->buffer = '';
$retcode = $this->upload_append($this->upload_url, $D, $this->filesize);
$this->start_pos += strlen($D);
$this->part_no = $this->part_no + 1;
}
if(!self::completeMultipartUpload($this->url['host'], $this->url['path'], $this->upload_url, $this->etags)){
$error[] = 'Something went wrong! Unable to complete Upload.';
return false;
}
return true;
}
public function stream_stat() {
}
public function stream_open() {
}
}
if(empty($this->upload_url)){
$this->upload_url = self::initiateMultipartUpload($this->url['host'], $this->url['path']);
if(empty($this->upload_url)){
$error[] = 'Upload failed! Unable to initiate upload to AWS.';
return false;
}
}
return true;
}
public function stream_read() {
}
if ($this->mode == 'r') {
if (($this->buffer = self::getObject($this->url['host'], $this->url['path'])) !== false) {
if (is_object($this->buffer->body)) $this->buffer->body = (string)$this->buffer->body;
} else return false;
}
$this->position += strlen($this->buffer->body);
self::$range_lower_limit = $this->position + 1;
return $this->buffer->body;
}
public function stream_write() {
}
return $data_size;
}
public function stream_tell() {
}
public function stream_eof() {
}
public function stream_metadata() {
}
public function stream_seek() {
} else return false;
break;
case SEEK_CUR:
if ($offset >= 0) {
$this->position += $offset;
return true;
} else return false;
break;
case SEEK_END:
$bytes = strlen($this->buffer->body);
if ($bytes + $offset >= 0) {
$this->position = $bytes + $offset;
return true;
} else return false;
break;
default: return false;
}
}
private function __getURL() {
}" : '';
}
private function __translateMode() {
}
}