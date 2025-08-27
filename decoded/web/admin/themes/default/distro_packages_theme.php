<?php
if(!defined('SOFTACULOUS')){
die('Hacking Attempt');
}
function distro_packages_theme() {
}
echo '<th class="align-middle">'.__('Action').'</th>
</tr>
</thead>
<tbody>';
if(empty($found_packages)){
echo '<tr><td class = "text-center" colspan="100">'.__('The installation package could not be found!').'</td></tr>';
}else{
foreach($found_packages as $key => $package){
echo '
<tr id="tr'.$package['name'].'">
<td>'.$package['name'].'</td>
<td>'.$package['ver'].'</td>
<td>'.$package['arch'].'</td>';
if(!is_debian()){
echo '<td>'.$package['repo'].'</td>';
}
echo '<td data-action="install" data-package_name="'.$package['name'].'" onclick="submitit(\'package_name='.$package['name'].'&action=install\')" style="cursor:pointer"><i class="fas fa-download"></i> Install </td>
</tr>
';
}
}
echo '
</tbody>
</table>
</div>
</div>
</div>
<script>
$("#distro_pack_form").submit(function( event ) {
let package_name = $("#package_name").val();
if(package_name == ""){
alert("Please Enter Package Name...!");
event.preventDefault(); // Prevent form from submitting
}else{
form.submit();
}
});
</script>';
softfooter();
}