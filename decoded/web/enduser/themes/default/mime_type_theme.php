<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function mime_type_theme() {
}
}else{
echo '
<tr>
<td class="text-center" colspan=6>'.__('There are no user configured MIME types').'</td>
</tr>';
}
echo '
</tbody>
</table>
</div>
<div id="showrectab" class="table-responsive pt-3">
<h5 class="d-inline-block">'.__('System MIME Types').'</h5>
<table class="table align-middle table-nowrap mb-0 webuzo-table">
<thead class="sai_head2">
<tr>
<th>'.__('MIME Type').'</th>
<th>'.__('Extension(s)').'</th>
</tr>
</thead>
<tbody>';
foreach($system_mimes as $key => $value){
echo '
<tr>
<td>'.$key.'</td>
<td>'.$value.'</td>
</tr>';
}
echo '
</tbody>
</table>
</div>
</div>
<script>
function delete_mime() {
});
});
show_message(a);
}
</script>';
softfooter();
}