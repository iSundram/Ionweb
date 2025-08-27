<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function addstorage_theme() {
});
function storageSet() {
}
storage = storage.replace(/\s/g, "-")
$("." + storage).show();
};
</script>
<div class="soft-smbox p-3 col-12 col-md-8 mx-auto">
<div class="sai_main_head">
<i class="fas fa-hdd me-1"></i> '.__('Add Storage').'
<a href="'.$globals['docs'].'add-new-storage/" target="_blank" tooltip="'.__('Documentation').'" class="float-end">
<i class="fas fa-question-circle"></i>
</a>
</div>
</div>
<div class="soft-smbox p-4 col-12 col-md-8 mx-auto mt-4">';
error_handle($error);
echo '
<div id="form-container">
<form accept-charset="'.$globals['charset'].'" id="addstorage" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)">
<div class="row">
<div class="col-12 col-md-6">
<label class="form-label">'.__('Name').'
<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('The name of the storage').'"></i>
</label>
<input type="text" class="form-control mb-3" name="name" id="st_name" size="30" value="'.POSTval('name', $storage['name']).'" />
</div>
<div class="col-12 col-md-6">
<label class="form-label">'.__('Storage Type').'
<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('The type of storage. As of now only File, EXT3, EXT4, XFS type is supported. The others are still to be developed').'"></i>
</label>
<select class="form-select" name="type" id="type" onchange="storageSet(this.value)">
<option value="file" '.POSTselect('type', 'file', $storage['type'] == 'file').'>File</option>
<option value="ext4" '.POSTselect('type', 'ext4', $storage['type'] == 'ext4').'>EXT4</option>
<option value="ext3" '.POSTselect('type', 'ext3', $storage['type'] == 'ext3').'>EXT3</option>
<option value="xfs" '.POSTselect('type', 'xfs', $storage['type'] == 'xfs').'>XFS</option>
<option value="btrfs" '.POSTselect('type', 'btrfs', $storage['type'] == 'btrfs').'>BTRFS</option>
<option value="zfs thin block" '.POSTselect('type', 'zfs thin block', $storage['type'] == 'zfs thin block').'>ZFS Thin Block</option>
<option value="zfs thin block compressed" '.POSTselect('type', 'zfs thin block compressed', $storage['type'] == 'zfs thin block compressed').'>ZFS Thin Compressed Block</option>
<option value="ceph block" '.POSTselect('type', 'ceph block', $storage['type'] == 'ceph block').'>CEPH</option>
</select>
</div>
<div class="col-12 col-md-6">
<div class="file ext4 ext3 xfs btrfs">
<label class="form-label">'.__('Path').'
<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Path to the mount point').'"></i>
</label>
</div>
<div class="zfs-thin-block zfs-thin-block-compressed">
<label class="form-label">'.__('Path').'
<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('ZFS Volume Group path /dev/zvol/YOUR_POOLNAME').'"></i>
</label>
</div>
<div class="ceph-block">
<label class="form-label">'.__('Path').'
<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('Ceph Block Device path /dev/rbd/CEPH_POOLNAME').'"></i>
</label>
</div>
<input type="text" class="form-control" name="path" size="30" value="'.POSTval('path', $storage['path']).'" />
</div>
<div class="col-12 col-md-6 mb-3">
<label class="form-label">'.__('Alert Threshold').'
<i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="'.__('If the used size crosses this percentage, an email will be sent to the Admin').'"></i>
</label>
<div class="input-group">
<input type="number" class="form-control w-25 float-left" name="alert" size="10" value="'.POSTval('alert', $storage['alert']).'" />
<span class="input-group-text">%</span>
</div>
</div>
</div>
<div class="text-center my-3">
<input type="submit" name="addstorage" class="btn btn-primary" value="'.__('Submit').'" />
</div>
</form>
</div>
</div>';
softfooter();
}