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

function pgsql_manage_theme(){
	
global $user, $globals, $theme, $softpanel, $WE, $catwise, $error, $dbname, $dbuser, $pri_list, $dbdone, $db_list, $db_list_size, $db_with_user_list, $db_user_list, $db_prefix;
		
		
	if(optGET('editdb_user')){
		
		echo '
		<div class="sai_main_head mt-3 mb-4">
			<img src="'.$theme['images'].'adddb.png" alt="" class="webu_head_img me-2"/>
			<h5 class="d-inline-block">'.__('Add User To Database').'</h5>
		</div>';
		
		if (count($db_list) > 0 && count($db_user_list) > 0){
			echo '
		<form accept-charset="'.$globals['charset'].'" name="importsoftware" method="post" action="" id="addusertodb" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
			<label class="form-label" for="sel_user">'.__('User').'</label>
			<select name="dbuser" id="sel_user" class="form-select mb-3">';
				foreach ($db_user_list as $k => $v)	{
					echo '
					<option value="'.$k.'" '.((!empty($_POST['sel_user']) && $_POST['sel_user'] == $k) ? 'selected="selected"' : '').'>'.$v.'</option>';
				}
			echo '
			</select>
			<label class="form-label" for="sel_db">'.__('Database').'</label>
			<select name="dbname" id="sel_db" class="form-select mb-3">';
				foreach ($db_list as $k => $v)	{
					echo '
					<option value="'.$k.'" '.((!empty($_POST['sel_db']) && $_POST['sel_db'] == $k) ? 'selected="selected"' : '').'>'.$v.'</option>';
				}
			echo '
			</select>
			<input type="submit" class="flat-butt" id="submitpri" name="submitpri" value="'.__('Add..').'" />
		</form>';
		}else{
			echo '
			<h4>'. __('Please add Database/User to assign privileges').'</h4>';
		}
		return true;
	}
			
	softheader(__('Manage Database(s)'));
	
	echo '
<script language="javascript" type="text/javascript">

$(document).ready(function(){
	
	// For selecting tab
	try{
		var select_tab = window.location;
		if(select_tab.length > 0){
			$(select_tab+"_a").addClass("active");
			$(select_tab).addClass("in active");
		}else{
			$("#currentdb_a").addClass("active");
			$("#currentdb").addClass("in active");
		}
	}catch(e){}
	if(window.location.hash == "#adddb"){ 
		$("#currentdb_a").removeClass("active");
		$("#currentdb").removeClass("in active");				
		$("#adddb_a").addClass("active");
		$("#adddb").addClass("in active");
	}
				
	function createDBopen(){
		var currentURL = window.location.href;
		var URLparm = currentURL.split("#");
		
		if(URLparm[1] == "adddb"){
			$("#adddb_a").trigger("click");
			$(".tab-pane").removeClass("show");
			$(".tab-pane.active").addClass("show");
		}
	}

	$(".submenu_item a").click(function(){
		createDBopen();	
	});

	createDBopen();		
});

function chkdbvalue(){			
	var w_l = window.location.toString();
	if(w_l.indexOf("#") > 0){
		w_l = w_l.substring(0, w_l.indexOf("#"));
	}
	$.ajax({
		type: "POST",
		url: w_l+"&editdb_user=1",								
		success: function(data){																		
			$("#dbtouser_disp").show();									
			$("#dbtouser_disp").html(data);	
		},
		error: function() {
			message_box.show_message("'.__js('Error').'",\''.__js('Oops there was an error while connecting to $0 Server $1', ['<strong>', '</strong>']).'\',1);			
		}	
	});					
}
</script>
<div class="card soft-card p-4">
	<div class="sai_main_head mb-3">
		<img src="'.$theme['images'].'pgsql_logo.gif" alt="" class="webu_head_img me-2" style="width: 42px;"/>
		<h5 class="d-inline-block">'.__('PostgreSQL').'</h5>
	</div>
	<!--tabs started-->
	<ul class="nav nav-tabs mb-3 webuzo-tabs" id="pills-tab" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="nav-link active heading_a" id="currentdb_a" data-bs-toggle="tab" data-bs-target="#currentdb" type="button" role="tab" aria-controls="currentdb" aria-selected="true">'.__('Database(s)').'</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" href="#adddb" id="adddb_a" data-bs-toggle="tab" data-bs-target="#adddb" type="button" role="tab" aria-controls="adddb" aria-selected="false">'.__('Create Database').'</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="currentuser_a" data-bs-toggle="tab" data-bs-target="#currentuser" type="button" role="tab" aria-controls="currentuser" aria-selected="false">'.__('Database User(s)').'</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="nav-link" id="dbtouser_a" data-bs-toggle="tab" data-bs-target="#dbtouser" type="button" role="tab" aria-controls="dbtouser" aria-selected="false" onclick="chkdbvalue();">'.__('Add User To Database').'</button>
		</li>
	</ul>
	<div class="tab-content" id="pills-tabContent">
		<div class="tab-pane fade show active" id="currentdb" role="tabpanel" aria-labelledby="currentdb_a">				
			<div class="sai_main_head mt-3 mb-4">
				<img src="'.$theme['images'].'database.png" alt="" class="webu_head_img me-2"/>
				<h5 class="d-inline-block"> '. __('Database(s)') .'</h5>
			</div>
			<div id="showdbtab" class="table-responsive">';
				showdb();
			echo '</div>
		</div>
		<div class="tab-pane fade" id="adddb" role="tabpanel" aria-labelledby="adddb_a">
			<div class="sai_main_head mt-3 mb-4">
				<img src="'.$theme['images'].'adddb.png" alt="" class="webu_head_img me-2"/>
				<h5 class="d-inline-block">'. __('Create Database').'</h5>
			</div>
			<div class="row">
				<div class="col-12 col-lg-6">
					<form accept-charset="'.$globals['charset'].'" name="importsoftware" id="createdb" method="post" action="" class="form-horizontal" onsubmit="return submitit(this)" data-donereload="1">
						<label class="form-label" for="db">'.__('New Database').'</label>
						<div class="input-group mb-3">'.
						(!empty($db_prefix) ? '
							<span class="input-group-text">
								<span id="domainname">'.$db_prefix.'</span>
							</span>' : '').'
							<input type="text" name="db" id="db" class="form-control" value="'.POSTval('db', '').'"/>
						</div>
						<p class="mb-2">
							<input type="submit" class="flat-butt" id="submitdb" name="submitdb" value="'.__('Create').'" />
						</p>
					</form>
				</div>
			</div>				
		</div>
		<div class="tab-pane fade" id="currentuser" role="tabpanel" aria-labelledby="currentuser_a">
			<div class="sai_main_head mt-3 mb-4">
				<img src="'.$theme['images'].'database.png" alt="" class="webu_head_img me-2"/>
				<h5 class="d-inline-block">'.__('Database User(s)').'</h5>
			</div>
			<div id="showusertab" class="table-responsive">';
				showuser();
			echo '</div>
		</div>
		<div class="tab-pane fade" id="dbtouser" role="tabpanel" aria-labelledby="dbtouser_a">
			<div class="row">
				<div class="col-12 col-lg-6">
					<div class="sai_main_head mt-3 mb-4">
						<img src="'.$theme['images'].'adddb.png" alt="" class="webu_head_img me-2"/>
						<h5 class="d-inline-block">'. __('Create Database User(s)') .'</h5>
					</div>
					<form accept-charset="'.$globals['charset'].'" name="importsoftware" method="post" action="" id="createdbuser" class="form-horizontal importsoftware" onsubmit="return submitit(this)" data-donereload="1">
						<label class="form-label" for="dbuser">'.__('New User').'</label>
						'.(!empty($db_prefix) ? '
						<div class="input-group mb-3">
							<span class="input-group-text">
								<span id="domainname">'.$db_prefix.'</span>
							</span>' : '').'
							<input type="text" name="dbuser" id="dbuser" class="form-control" />'.
						(!empty($db_prefix) ? '</div>' : '').'
						<div>
							<label class="sai_head form-label" for="dbpassword">'.__('Password').'</label>
							<span class="sai_exp">'.__('Password strength must be greater than or equal to $0', [pass_score_val('mysql')]).'</span>
						</div>
						<div class="input-group password-field">
							<input type="password" name="dbpassword" id="dbpassword" class="form-control" onkeyup="check_pass_strength($(\'#dbpassword\'), $(\'#pass-prog-bar\'))" />
							<span class="input-group-text" style="padding: 4px 12px" onclick="change_image(this, \'dbpassword\')">
								<i class="fas fa-eye"></i>
							</span>
							<a class="random-pass" href="javascript: void(0);" onclick="$_(\'dbpassword\').value=randstr(10,'.pass_score_val('postgresql').');check_pass_strength($(\'#dbpassword\'), $(\'#pass-prog-bar\'), \''.$globals['password_strength'].'\');return false;" title="'.__('Generate a Random Password').'">
								<i class="fas fa-key"></i>
							</a>
						</div>
						<div class="progress pass-progress mb-3">
							<div class="progress-bar bg-danger" id="pass-prog-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0">
								<span>0</span>
							</div>
						</div>							
						<input type="submit" class="flat-butt me-1" id="submituser" name="submituserdb" value="'.__('Create').'" />
					</form>
				</div>
				<div class="col-12 col-lg-6">
					<div id="dbtouser_disp"></div>
				</div>
			</div>			
		</div>
	</div><!--tabs ended-->
</div>
<button type="button" id="privilege-modal-btn" class="btn flat-butt d-none" data-bs-toggle="modal" data-bs-target="#privilageModal"></button>
<div class="privilege-modal modal fade" class="modal fade" id="privilageModal" tabindex="-1" aria-labelledby="privilageModalLabel" aria-hidden="true"></div>';
	
	softfooter();
}

function showdb(){
	global $user, $theme, $softpanel, $WE, $dbname, $dbuser, $db_list, $db_list_size, $db_with_user_list, $WE;
	
	echo '	
<div class="sai_sub_head record-table mb-2 position-relative" style="text-align:right;">
	<input type="button" class="btn btn-danger delete_selected" value="'.__('Delete Selected').'" name="delete_selected" id="delete_selected" onclick="delete_pgsqldb(this)" disabled>
</div>
<table border="0" cellpadding="6" cellspacing="1" width="100%" class="table align-middle table-nowrap mb-0 webuzo-table">
	<thead class="sai_head2">
		<tr>
			<th class="align-middle" width="5%"><input type="checkbox" id="checkall"></th>
			<th class="align-middle" width="40%">'.__('Database').'</th>
			<th class="align-middle" width="40%">'.__('User(s)').'</th>
			<th class="align-middle" width="20%" style="text-align:center">'.__('Options').'</th>
		</tr>
	</thead>
	<tbody>';
	if(empty($db_list)){
		echo '<tr><td colspan=4><center><span style="font-size:15px" >'.__('No database found').'</span></center></td></tr>';
	}else{
		$i=1;
		foreach ($db_list as $key => $value){
			$user = NULL;
			echo '<tr id="tr'.$value.'">
				<td><input type="checkbox" class="check_pgsl" name="check_pgsl" value="'.$key.'"></td>
				<td>'.$value.'</td>
				<td class="endurl">';
				$tmp_holder = array();
				foreach (array_unique($db_with_user_list[$key]) as $k => $user){
					$tmp_holder[] = $user.'<i class="fas fa-trash delete delete-icon" title="'.__('Delete user from database').'" id="did'.$user.'" onclick="delete_record(this)" data-dbname="'.$key.'" data-dbuser="'.$user.'" data-delpri="1"></i>';
				}
				echo implode(', ', $tmp_holder).'</td>
				<td align="center">
					<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="did'.$value.'" onclick="delete_record(this)" data-delete_db="'.$value.'" data-delete="1"></i>
				</td>
			</tr>';
			$i++;
		}

	}
	
	echo '
	</tbody>
</table>
<script>

$(document).ready(function(){
	$("#checkall").change(function(){
		$(".check_pgsl").prop("checked", $(this).prop("checked"));
	});
	
	$("input:checkbox").change(function(){
		if($(".check_pgsl:checked").length){
			$("#delete_selected").removeAttr("disabled");
		}else{
			$("#delete_selected").prop("disabled", true);
		}
	});
});

function delete_pgsqldb(el){
	var a;
	var jEle = $(el);
	var arr = [];
	//console.log(jEle);
	
	$("input:checkbox[name=check_pgsl]:checked").each(function(){
		var pgdb = $(this).val();
		arr.push(pgdb);
		//console.log(pgdb);
	});
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete selected database(s) ?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"delete_db" : arr.join(), "delete" : 1};
		//console.log(d);
		submitit(d ,{done_reload : window.location.href});
	});
	show_message(a);
}
</script>';
}


function showuser(){
	global $user, $theme, $softpanel, $WE, $dbname, $dbuser, $db_user_list;
	
	echo '
<div class="sai_sub_head record-table mb-2 position-relative" style="text-align:right;">
	<input type="button" class="btn btn-danger delete_selected" value="'.__('Delete Selected').'" name="delete_select" id="delete_select" onclick="delete_pguser(this)" disabled>
</div>
<table border="0" cellpadding="8" cellspacing="1" class="table align-middle table-nowrap mb-0 webuzo-table" id="usertab" align="center">
	<thead class="sai_head2">
		<tr>
			<th class="align-middle" width="5%"><input type="checkbox" id="checkedall"></th>
			<th class="align-middle" width="60%">'.__('User').'</th>
			<th class="align-middle" width="30%"></th>
			<th class="align-middle" colspan="3" width="10%" style="text-align:right">'.__('Options').'</th>
		</tr>
	</thead>
	<tbody>';
	if(empty($db_user_list)){
		echo '
		<tr class="text-center">
			<td colspan=3>
				<span>'.__('No database user found').'</span>
			</td>
		</tr>';
	}else{
		$i=1;
		foreach ($db_user_list as $key => $value){
			
			echo '
			<tr id="ur'.$key.'">
				<td><input type="checkbox" class="check_pguser" name="check_pguser" value="'.$key.'"></td>
				<td>'.$value.'</td>
				<td width="90%">
					<label id="lbl_pass'.$i.'" style="display:none;">'.__('New Password').' &nbsp; : &nbsp; </label>
					<input type="password" name="dbpass" id="chng_pass'.$i.'" style="width:150px; display:none;" >
					<input type="hidden" id="hdn'.$i.'" value="'.$i.'" />
					<input type="hidden" name="db_user" id="hdn_user'.$i.'" value="'.$key.'" />
					<input type="hidden" name="change_user_pass" id="change_user_pass'.$i.'" value="1" />
				</td>
				<td width="2%">
					<i title="Cancel" id="cid'.$i.'" class="fas fa-reply cancel cancel-icon" style="display:none;"></i>
				</td>
				<td width="2%">
					<i title="Change Password" id="uid'.$i.'" class="fas fa-pencil-alt edit-icon edit"></i>
				</td>
				<td width="2%">
					<i class="fas fa-trash delete delete-icon" title="'.__('Delete').'" id="uid'.$key.'" onclick="delete_record(this)" data-delete_dbuser="'.$key.'" data-delete="1"></i>
				</td>
			</tr>';
			$i++;
		}
	}
	
	echo '
	</tbody>
</table>	
<script language="javascript" type="text/javascript">
	
// Change DB user password
$(".edit").click(function(){
		
	var id = $(this).attr("id");
	id = id.substr(3);
	var hidn_val = $("#hdn"+id).val();
	var edit_record = $("#hdn_user"+id).val();

	$("#cid"+hidn_val).show();
	$("#chng_pass"+hidn_val).show();
	$("#lbl_pass"+hidn_val).show();
		
	if($("#uid"+id).hasClass("fa-save")){			
		var d = $("#ur"+edit_record).find("input, textarea, select").serialize();
	
		submitit(d, {
			done: function(){
				var tr = $("#ur"+edit_record);
				tr.find(".cancel").click();// Revert showing the inputs
			},
			sm_done_onclose: function(){			
				$("#ur"+edit_record).find("input,.input").hide();					
			}
		});
	}else{
		$("#uid"+id).removeClass("fa-edit").addClass("fa-save");
		$("#chng_pass"+hidn_val)
			.val($("#chng_pass"+hidn_val).text().substring(0, $("#chng_pass"+hidn_val).text().length - 1))
			.show()
			.focus();
	}
});

//Cancel action
$(".cancel").click(function() {
	var id = $(this).attr("id");
	id = id.substr(3);
	$("#cid"+id).hide();
	$("#chng_pass"+id).hide();
	$("#lbl_pass"+id).hide();	
	$("#uid"+id).removeClass("fa-save").addClass("fa-edit");
});

$(document).ready(function(){
	$("#checkedall").change(function(){
		$(".check_pguser").prop("checked", $(this).prop("checked"));
	});
	
	$("input:checkbox").change(function(){
		if($(".check_pguser:checked").length){
			$("#delete_select").removeAttr("disabled");
		}else{
			$("#delete_select").prop("disabled", true);
		}
	});
});

function delete_pguser(el){
	var a;
	var jEle = $(el);
	var arr = [];
	console.log(jEle);
	
	$("input:checkbox[name=check_pguser]:checked").each(function(){
		var pguser = $(this).val();
		console.log(pguser);
		arr.push(pguser);
	});
	a = show_message_r("'.__js('Warning').'", "'.__js('Are you sure you want to delete selected database user(s) ?').'");
	a.alert = "alert-warning";
	
	a.confirm.push(function(){
		var d = {"delete_dbuser" : arr.join(), "delete" : 1};
		console.log(d);
		submitit(d, { done_reload : window.location.href });
	});
	show_message(a);
}
</script>';
}