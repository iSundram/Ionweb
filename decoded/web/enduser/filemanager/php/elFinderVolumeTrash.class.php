<?php
* elFinder driver for trash bin at local filesystem.
*
* @author NaokiSawada
**/
class elFinderVolumeTrash extends elFinderVolumeLocalFileSystem
{
* Driver id
* Must be started from letter and contains [a-z0-9]
* Used as part of volume id.
*
* @var string
**/
protected $driverId = 't';
public function __construct() {
}
public function mount() {
}
$attr = array(
'pattern' => '/./',
'locked' => true,
);
array_unshift($opts['attributes'], $attr);
}
$opts['copyJoin'] = true;
return parent::mount($opts);
}
}