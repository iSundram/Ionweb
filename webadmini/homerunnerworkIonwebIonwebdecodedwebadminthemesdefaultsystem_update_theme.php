<?php

//////////////////////////////////////////////////////////////
//===========================================================
// WEBUZO CONTROL PANEL
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Mamta Malvi
// Date:       07 th April 2022
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

function system_update_theme(){

	global $theme, $globals, $user, $error;

	softheader(__('System Update'));
    
    echo'
    <div class="soft-smbox p-3">
        <div class="sai_main_head">
            <i class="fas fa-xl fa-sync-alt me-1"></i>'.__('System Update').'
        </div>
    </div>
    <div class="soft-smbox p-3 mt-4">
        <div class="row">
            <div class="col-12 col-md-12">
            <p>'.__('This action will update all the packages to the latest version. The system may remove unnecessary packages associated with the update or that conflict with your request.').'</p>
                <form action="" method="POST" name="system_update_form" id="system_update_form" onsubmit="return submitit(this)">
                    <input type="checkbox" checked="checked" name="kernel" id="kernel" value="1"> <label for="kernel">'.__('Update Kernel packages').'</label><br /><br />
                    <input type="hidden" name="run" id="run" value="1">
                    <input type="submit" name="submit" value="'.__('Update').'" class="btn btn-primary">
                </form>
                <p style="color:red;" id="unableDownStr">'.$error['log']['fileNotFound'].'</p>
            </div>
        </div>
    </div>';

	softfooter();
}