<?php

//////////////////////////////////////////////////////////////
//===========================================================
// WEBUZO CONTROL PANEL
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Mamta
// Date:       25th April 2022
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

function distro_packages_theme(){

global $theme, $globals, $user, $error, $done, $found_packages;

	softheader(__('Install Distro Packages'));

	echo '
<div class="soft-smbox p-3 col-12">
	<div class="sai_main_head">
		<i class="fas fa-cubes fa-xl me-1"></i> '.__('Install Distro Packages').'
	</div>
</div>
	
<div class="soft-smbox p-4 col-12 mt-4">
	<div class="section">
		<div class="row">
			<div class="col-12 col-lg-4">
				<form accept-charset="'.$globals['charset'].'" action="'.$globals['ind'].'act=distro_packages" method="post" name="distro_pack_form" id="distro_pack_form" class="form-horizontal" data-donereload="1">
                    <label class="form-label me-1">'.__('Enter a package name or pattern').'</label>
                    <div class="input-group mb-3">
                    <input type="text" class="form-control" name="package_name" id="package_name" placeholder="'.__('Search Packages').'" value="'.POSTval('package_name').'">&nbsp;&nbsp;
                    <input class="btn btn-primary" type="submit" name="search" id="search" value="'.__('Search').'">
                    </div>
				</form>
			</div>
		</div>
		<div>
		<div class="section my-5">
            <table class="table align-middle table-nowrap mb-0 webuzo-table mt-4" id="rpm_pack">			
                <thead class="sai_head2" style="background-color: #EFEFEF;">
                    <tr>
                        <th class="align-middle">'.__('Package').'</th>
                        <th class="align-middle">'.__('Version').'</th>
                        <th class="align-middle">'.__('Arch').'</th>';
						if(!is_debian()){
                        	echo '<th class="align-middle">'.__('Repo').'</th>';
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