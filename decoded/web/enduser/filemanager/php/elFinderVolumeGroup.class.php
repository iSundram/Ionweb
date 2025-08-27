<?php
* elFinder driver for Volume Group.
*
* @author Naoki Sawada
**/
class elFinderVolumeGroup extends elFinderVolumeDriver
{
* Driver id
* Must be started from letter and contains [a-z0-9]
* Used as part of volume id
*
* @var string
**/
protected $driverId = 'g';
* Constructor
* Extend options with required fields
*/
public function __construct() {
}
* @inheritdoc
**/
protected function _dirname() {
}
* {@inheritDoc}
**/
protected function _basename() {
}
* {@inheritDoc}
**/
protected function _joinPath() {
}
* {@inheritDoc}
**/
protected function _normpath() {
}
* {@inheritDoc}
**/
protected function _relpath() {
}
* {@inheritDoc}
**/
protected function _abspath() {
}
* {@inheritDoc}
**/
protected function _path() {
}
* {@inheritDoc}
**/
protected function _inpath() {
}
* {@inheritDoc}
**/
protected function _stat() {
}
return false;
}
* {@inheritDoc}
**/
protected function _subdirs() {
}
* {@inheritDoc}
**/
protected function _dimensions() {
}
* {@inheritDoc}
**/
protected function readlink() {
}
* {@inheritDoc}
**/
protected function _scandir() {
}
* {@inheritDoc}
**/
protected function _fopen() {
}
* {@inheritDoc}
**/
protected function _fclose() {
}
* {@inheritDoc}
**/
protected function _mkdir() {
}
* {@inheritDoc}
**/
protected function _mkfile() {
}
* {@inheritDoc}
**/
protected function _symlink() {
}
* {@inheritDoc}
**/
protected function _copy() {
}
* {@inheritDoc}
**/
protected function _move() {
}
* {@inheritDoc}
**/
protected function _unlink() {
}
* {@inheritDoc}
**/
protected function _rmdir() {
}
* {@inheritDoc}
**/
protected function _save() {
}
* {@inheritDoc}
**/
protected function _getContents() {
}
* {@inheritDoc}
**/
protected function _filePutContents() {
}
* {@inheritDoc}
**/
protected function _checkArchivers() {
}
* {@inheritDoc}
**/
protected function _chmod() {
}
* {@inheritDoc}
**/
protected function _findSymlinks() {
}
* {@inheritDoc}
**/
protected function _extract() {
}
* {@inheritDoc}
**/
protected function _archive() {
}
}