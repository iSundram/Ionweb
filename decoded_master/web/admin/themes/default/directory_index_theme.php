<?php

//////////////////////////////////////////////////////////////
//===========================================================
// WEBUZO CONTROL PANEL
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit
// Date:       10th Jan 2009
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

function directory_index_theme(){

global $globals, $theme, $softpanel, $error, $done, $directory_index, $php_file_ext;
	
	softheader(__('DirectoryIndex'));
	echo '
	<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>';
	error_handle($error, '100%');
	
	echo '
	<div class="row">
		<div class="soft-smbox p-3 mb-3 col-6 col-md-5 mx-auto">
			<div class="sai_main_head text-center">
				<i class="fas fa-book fa-xl"></i>&nbsp;&nbsp'.__('DirectoryIndex').'
			</div><hr/>
			<span>'.__('You can drag the filename to reorder DirecoryIndex priority').'</span><br /><br />
			<div class="row">
				<ul class="list_index" id="list_index">';
				foreach($directory_index as $di => $dv){
					$index_id = explode(".", $dv);
					echo '
					<li class="di_item" id='.$index_id[1].'_item >'.$dv.'<i id='.$index_id[1].' class="fas fa-times delete delete-icon" onclick="remove_index(this.id)"></i></li>';
				}
				echo '
				</ul>
			</div>
			
			<div class="text-center my-3">
				<input type="submit" class="btn btn-primary" name="submit_directory_index" value="'.__('Save Settings').'" id="submit_directory_index"/>
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_directory_index">
				'.__('Add New').'</button>
			</div>

			<div class="modal fade" id="add_directory_index" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			    <div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">'.__('Add New DirectoryIndex').'</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<span>'.__('You can add single or multiple space separated values').'</span>
							<input type="text" id="new_dir_index" name="new_dir_index" class="form-control mb-3" required placeholder="'.__('Enter new Index').'" />
						</div>
						<div class="modal-footer">
							<button type="button" id="submit_new_index" class="btn btn-primary">'.__('Add New').'</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="soft-smbox p-3 mb-3 col-6 col-md-5 mx-auto">
			<div class="sai_main_head text-center">
				<i class="fab fa-php fa-xl"></i>&nbsp;&nbsp'.__('PHP File Extension').'
			</div><hr/>
			<span>'.__('You can add multiple PHP file extensions here').'</span><br /><br />
			<div class="row">
				<ul class="list_php_file_ext" id="list_php_file_ext">';
				foreach($php_file_ext as $pe => $pv){
					$pv = str_replace('.', '', $pv);
					echo '
					<li class="pfe_item" id='.$pv.'_item >'.$pv.'<i id='.$pv.' class="fas fa-times delete delete-icon" onclick="remove_php_file_ext(this.id)"></i></li>';
				}
				echo '
				</ul>
			</div>
			
			<div class="text-center my-3">
				<input type="submit" class="btn btn-primary" name="submit_php_file_ext" value="'.__('Save Settings').'" id="submit_php_file_ext"/>
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_php_file_ext">
				'.__('Add New').'</button>
			</div>

			<div class="modal fade" id="add_php_file_ext" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">'.__('Add New PHP file extension').'</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<span>'.__('You can add single or multiple space separated values').'</span>
							<input type="text" id="new_php_file_ext" name="new_php_file_ext" class="form-control mb-3" required placeholder="'.__('Enter PHP file extension').'" />
						</div>
						<div class="modal-footer">
							<button type="button" id="submit_new_php_ext" class="btn btn-primary">'.__('Add New').'</button>
						</div>
					</div>
				</div>
			</div>
		<div>
	</div>';
	
echo '
<script language="javascript" type="text/javascript">	
	var dragIndex = document.querySelector(".list_php_file_ext");
	var draglist = document.querySelector(".list_index");
	new Sortable(dragIndex , {
	  animation: 350
	});
	new Sortable(draglist , {
	  animation: 350
	});

	$("#submit_directory_index").click(function(){
		var dir_index = "";
		$("#list_index li").each(function(index) {
			dir_index += $(this).text() + " ";
		});
		
		dir_index = dir_index.trimEnd();
		var d = "dir_index="+dir_index;
		submitit(d, {
			done_reload: window.location
		});
	});
	
	$("#submit_new_index").click(function(){
		var new_dir_index = $("#new_dir_index").val();
		var d = "new_dir_index="+new_dir_index;
		submitit(d, {
			done_reload: window.location
		});
	});
	
	function remove_index(id){
		$("#list_index").children("#"+id+"_item").remove();
	}

	$("#submit_php_file_ext").click(function(){
		var php_file_ext = "";
		$("#list_php_file_ext li").each(function(index) {
			php_file_ext += "." + $(this).text() + " ";
		});
		
		php_file_ext = php_file_ext.trimEnd();
		var d = "php_file_ext="+php_file_ext;
		submitit(d, {
			done_reload: window.location
		});
	});
	
	$("#submit_new_php_ext").click(function(){
		var new_php_file_ext = $("#new_php_file_ext").val();
		var d = "new_php_file_ext="+new_php_file_ext;
		submitit(d, {
			done_reload: window.location
		});
	});
	
	function remove_php_file_ext(id){
		$("#list_php_file_ext").children("#"+id+"_item").remove();
	}

</script>';
	softfooter();
}
	