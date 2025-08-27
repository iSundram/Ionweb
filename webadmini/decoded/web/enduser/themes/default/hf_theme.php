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

// Name of this theme
$theme['this_theme'] = 'modern';

function softheader($title = ''){

global $globals, $l, $user, $theme, $SESS, $features, $softpanel, $WE, $apps, $apps_catwise, $user_sitepad, $iapps, $act, $icons;
	
	$appids = array_keys($iapps);
	$def_mysql = $globals['WU_DEFAULT_MYSQL'];
	
	$mysql = (!empty($def_mysql) ? get_app_record($def_mysql) : $default_mysql);
	
	$def_web_server = $globals['WU_DEFAULT_SERVER'];
	$web_server = (!empty($def_web_server) ? get_app_record($def_web_server) : 3);
	$disable_sysapps = $globals['DISABLE_SYSAPPS'];
	
	$def_tomcat = $globals['WU_TOMCAT'];		
	$is_tomcat_ins = (!empty($def_tomcat) ? $def_tomcat : "not_installed");	
	
	if(optGET('jsnohf')){
		return true;
	}
	
	// Check icons allowed !
	$icons = $features;
	
	$href = 'index.php?act='.$act;

	foreach($icons as $kk => $vv){
		foreach($icons[$kk]['icons'] as $k => $v){
			if(!feature_allowed($k) || !empty($v['hidden'])){				
				unset($icons[$kk]['icons'][$k]);
			}
			
			if($href == $v['href'] && empty($sub_menu_link)){
				$mainmenu = $vv['name'];
				$submenu = $v['name'];
				
				$sub_menu_link = 'menu_'.$kk;
			}
		}
	}
	
	// If you are not allowed SSH then no need to allow
	if(empty($WE->user['P']['options']['shell'])){
		unset($icons['security']['icons']['ssh_access']);
	}
	
	//r_print($user['color_theme']);
	
	// Handle title if sitename in rebranding is set
	if($globals['sn'] == 'Webuzo'){
		$title = $title.' - '.__('Powered by Webuzo');
	}else{
		$title = $title.' - '.$globals['sn'];
	}
	
	$custom_favicon  = (!empty($globals['favicon_logo']) ? $globals['favicon_logo'] : $theme['images'].'/favicon.ico');
	
	//Lets echo the top headers
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$globals['charset'].'" />
<meta name="keywords" content="softaculous, software" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<title>'.$title.'</title>
<link rel="stylesheet" type="text/css" href="'.$theme['url'].'/bootstrap/css/bootstrap.min.css?'.$GLOBALS['globals']['version'].'" />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="'.$theme['url'].'/css/style.css?'.$GLOBALS['globals']['version'].'" />'.
(file_exists($theme['path'].'/custom.css') ? '<link rel="stylesheet" type="text/css" href="'.$theme['url'].'/custom.css?'.$GLOBALS['globals']['version'].'" />' : '').'
<link rel="stylesheet" type="text/css" href="'.$theme['url'].'/css/select2.min.css?'.$GLOBALS['globals']['version'].'" />
<link rel="shortcut icon" href='.$custom_favicon.' />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
'.js_url(['jquery.js','popper.min.js','bootstrap/js/bootstrap.min.js','universal.js','select2.min.js'], 'combined.js').'
</head>
<script language="javascript" type="text/javascript">

var l = {
	"delete" : "'.__js('Delete').'",
	"deleted" : "'.__js('Deleted').'",
	"warning" : "'.__js('Warning').'",
	"error" : "'.__js('Error').'",
	"r_connect_error" : "'.__js('Oops there was an error while connecting to the $0 Server $1', ['<strong>','</strong>']).'",
	"done" : "'.__js('Done').'",
	"following_errors_occured" : "'.__js('The following errors were found').'",
	"delete_conf" : "'.__js('Are you sure you want to delete this ?').'",
	"show" : "'.__js('Show').'",
	"hide" : "'.__js('Hide').'",
	"no_tbl_rec" : "'.__js('No record exists. Please add one !').'"
};

// To change show password images from combined.js
theme = "'.$theme['images'].'";

var appids = ["'.implode('", "', array_keys($iapps)).'"];
var tools = ["phpmyadmin","rockmongo", "rainloop", "tomcat", "monsta"];

function in_arrays(val, arr){
	for (var i in arr) {
		var tmp_val = arr[i].split("_");
		if(tmp_val[0] == val){
			return true;
		}
	}
	return false;
}

function app_installed(app_id){
	if(in_arrays(app_id, appids)){
		return true;
	}else{
		window.location = "'.$globals['admin_url'].'act=apps&app="+app_id;
		return false;
	}
}

//checks whether the app is installed or not
function is_app_inst(app_id, act, appname){
	if(in_arrays(app_id, appids)){
		if(in_arrays(act, tools)){
			if(act == "tomcat"){
				window.open("http://'.$globals['WU_PRIMARY_DOMAIN'].':8080/manager/", "_blank");
			}else if(act == "rainloop"){
				'.($iapps['35_1']['mod'] < 17 ? 'alert("'.__js('Please update Exim to use this functionality.').'")' : 'window.open("'.$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/rainloop/index.php", "_blank")' ).'
			}else{
				window.open(act+"/", "_blank");
			}
		}else if(act == "appact"){
			window.location = "'.$globals['admin_url'].'act="+act+"&app="+appname;
		}else{
			window.location = "index.php?act="+act;
		}
	}else{
		var r = confirm("'.__js('This utility is not installed. Please install it first').'")
		if(r==true){
			'.(empty($disable_sysapps) ? 'window.location = "'.$globals['admin_url'].'act=apps&app="+app_id;' : 'alert("'.__js('This functionality has been disabled from the Webuzo Admin Panel').'")').'
		}else{
			return true;
		}
	}			
}

// For navigating through dropdown list
function search_handle_keyboard(){
	$(document).on("keyup", function(e){
		var search_list = $(".search_list");
		if(!search_list.is(":visible")){
			return false;
		}
			
		var current = $(".search_list").find("li.active");				
		if (e.keyCode == 40) {
			if(current.next().length == 0){
				$("#search_input").focus();
			}
			if(current.length == 0){
				$(".search_list > li").first().attr("class", "active").children("a").focus();
			}else{
				current.attr("class", "").children("a").blur();	
				current.next().attr("class", "active").children("a").focus();
			}		 
		}				
		if (e.keyCode == 38) {
			if(current.prev().length == 0){
				$("#search_input").focus();
			}
			if(current.length == 0){
				$(".search_list > li").last().attr("class", "active").children("a").focus();			
			}else{
				current.attr("class", "").children("a").blur();	
				current.prev().attr("class", "active").children("a").focus();
			}
		}
		
	});
}
		
//Returns a Single li element to used in the dropdown
function search_result_fill(value){	
	return \'<li class="" role="presentation"><a role="menuitem" class="dropdown-item" tabindex="-1" href="\'+value.href+\'" \'+("onclick" in value ? \'onclick="\'+value.onclick+\'"\' : "")+\' \'+("target" in value ? \'target="\'+value.target+\'"\' : "")+\'>\'+value.name+\'</a></li>\';
}			

old_input_val = "";

// Fills the dropdown list according to input search value
function search_function(el){	
	var input_val = $(el).val().toLowerCase();
	input_val = input_val.trim();
	
	if(old_input_val == input_val){
		return true;
	}
	
	old_input_val = input_val;
	
	if(input_val == ""){
		$(".search_list > li").remove();
		$(".search_list").hide();
		return false;
	}
	
	var dropdown_list = "";
	var links = '.json_encode($icons).';
	$(".search_list > li").remove();
	var count = 0;
	var flag = false;
	
	for(var x in links){
		
		for(var y in links[x]["icons"]){
			var value = links[x]["icons"][y];
			var name = value.name.toLowerCase();
			
			if(count >= 15){
				continue;
			}
			
			if(name.includes(input_val) || y.includes(input_val)){
				dropdown_list += search_result_fill(value);
				count++;
			}			
		}	
	}
	
	if(dropdown_list !== ""){
		$("#search_dropdown .dropdown-toggle").dropdown("toggle");
		$(".search_list").append(dropdown_list);
		$(".search_list").show();
		$("#search_input").focus();
	}else{
		$("#search_list").hide();
	}
}

// Left panel menu toggle state
function left_panel_menu_state(){
	
	var main = $(".left-panel-menu-list>li");
	var sub_link = $(".left-panel-menu .menu-details");
	var if_isset_submenu = getcookie("sub_menu_link");
	
	sub_link.each(function(){
		
		var minimize = $(".left-panel-menu").hasClass("left-menu-minimize");
		
		var link_id = $(this).closest("li").attr("id");
		var par = $(this).parent();
		
		
		if(if_isset_submenu == link_id){
			var id = link_id+"_l";
			$("#"+id).addClass("active");
			$("#"+link_id).addClass("active");  
			$("#menu_home").removeClass("active");
	
			if(!minimize){
				par.find(".submenu-list").slideDown();
			}
		}else{	
			if(!minimize){
				par.find(".submenu-list").slideUp();
			}
		}
		
		par.click(function(){
			setcookie("sub_menu_link", link_id);
		});
		
		$(this).click(function(){
	
			if($(".left-panel-menu").hasClass("left-menu-minimize")){
				return;
			}
			
			par.find(".submenu-list").slideToggle();
		});
		
		$("#menu_home").click(function(){
			removecookie("sub_menu_link");
		});

		$(".brand").click(function(){
			removecookie("sub_menu_link");
		})
	})
}

function left_menu_handle_cookies(){
	removecookie("sub_menu_link");
	setcookie("sub_menu_link", "'.$sub_menu_link.'");
	left_panel_menu_state();
}

// Left Menu Collapsed Remember
function left_menu_minimized(minimized){
	$.ajax({
		type: "POST",
		url: "'.$globals['index'].'api=json",
		data: {"left_menu_minimized": + minimized},
		success: function(){
			//console.log("success");
		},
		error: function(){
			//console.log("error");
		}
	});
};

function left_menu_toggle(minimize){
	var jEle = $(".left-panel-menu");
	jEle.toggleClass("left-menu-minimize");
	
	// Forced minimize
	if(minimize){
		jEle.addClass("left-menu-minimize");
	}
	
	var shown = !jEle.hasClass("left-menu-minimize");
	
	// Try to remember it
	if(!minimize){
		left_menu_minimized(!shown);
	}
	
	if(!shown){
		$(".left_menu_toggle_btn").css({direction: "rtl"});
		$(".nav-top-row").addClass("soft_nav_blue");
		$("#softcontent").addClass("left_menu_min");
		jEle.find(".submenu-list").hide();
	}else{
		$(".left_menu_toggle_btn").css({direction: "ltr"});
		$(".nav-top-row").removeClass("soft_nav_blue");
		$("#softcontent").removeClass("left_menu_min");
		jEle.find(".menu_item.active .submenu-list").show();
	}
}

$(document).ready(function(){

	$("[data-bs-toggle=tooltip]").tooltip();
	
	// Handle search input events
	search_handle_keyboard();
	
	$("#left_menu_toggle_btn").on("click", function(e){left_menu_toggle();});
	var left_menu_pref = '.((int) $WE->user_prefs['left_menu_minimized']).';
	
	// Handle the screen size for menu
	if($(window).width() < 768 || left_menu_pref){
		left_menu_toggle(1);
	}
	
	left_menu_handle_cookies();
	
	// Initialize the sai_exp
	new_theme_funcs_init();
	
	$("#header_toggle_btn").click(function(){
		$(".soft_nav_mob").slideToggle("slow");
	});
	
	$(".top").click(function(){									
		$("html, body").animate({ scrollTop: 0 }, 500);
		return false;
	});
	
	if($("#error_handle_div")){
		$("#error_handle_div").height($(document).height() - $(".footer").height());
	}
	
	// Make the select2
	$(".make-select2").each(function(index){
		var jEle = $(this);
		make_select2(jEle);
	});
	
});

</script>

<body>';
	
	$navbar_top = array();
	
	//Navigation Webuzo Cpanel
	if(is_single_user() && !$globals['DISABLE_SYSAPPS']){
		$navbar_top['goto_webuzo_cpanel']['fullscreen'] = '<li class="navtop" data-bs-toggle="tooltip" title="'.__('Go to Control Panel').'">
				<a href="'.$globals['admin_url'].'" target="_blank">
					<i class="fa fa-cogs"></i>
			</a>
		</li>';
		
		$navbar_top['goto_webuzo_cpanel']['responsive'] ='<li class="res-menu-item mb-2">
			<a href="'.$globals['admin_url'].'" target="_blank">
				<i class="fa fa-cogs me-2"></i>'.__('Go to Control Panel').'
			</a>
		</li>';
	}
	
	if(is_dir('/usr/local/softaculous') && feature_allowed('wp_manager_cname')){
		$navbar_top['goto_wp_manager']['fullscreen'] = '<li class="navtop" data-bs-toggle="tooltip" title="'.__('WordPress Manager').'">
			<a target="_blank" href="'.str_replace('index.php?', '', $globals['ind']).'softaculous/?'.'act=wordpress">
			<i class="fab fa-wordpress"></i>
			</a>
		</li>';
	}
		
	$navbar_top['goto_wp_manager']['responsive'] = '<li class="res-menu-item mb-2">
		<a href="'.$globals['ind'].'act=wordpress">
			<i class="fab fa-wordpress me-2"></i>'.__('WordPress Manager').'</a>
	</li>';
	
	
	//Navigation Webuzo Home
	$navbar_top['goto_webuzo_home']['fullscreen'] = '<li class="navtop" data-bs-toggle="tooltip" title="'.__('$0 Home', $globals['sn']).'">
		<a href="'.$globals['ind'].'">
			<i class="fas fa-home"></i>
		</a>
	</li>';
	
	$navbar_top['goto_webuzo_home']['responsive'] = '<li class="res-menu-item mb-2">
		<a href="'.$globals['ind'].'">
			<i class="fas fa-home me-2"></i>'.__('$0 Home', $globals['sn']).'
		</a>
	</li>';
	
	//Navigation Webuzo App Installations
	if(is_single_user() && (!$globals['DISABLE_SYSAPPS'])){
		$navbar_top['webuzo_app_installations']['fullscreen'] = '<li class="navtop" data-bs-toggle="tooltip" title="'.__('All Installed Applications').'"><a href="'.$globals['admin_url'].'act=apps_installations"><i class="fas fa-th"></i></a></li>';
		
		$navbar_top['webuzo_app_installations']['responsive'] = '<li class="res-menu-item mb-2">
			<a href="'.$globals['admin_url'].'act=apps_installations">
				<i class="fas fa-th me-2"></i>'.__('All Installed Applications').'
			</a>
		</li>';
	}
	
	/*//Tasklist Navigation
	$navbar_top['goto_tasklist']['fullscreen'] = '<li class="navtop" data-bs-toggle="tooltip" title="'.__('Task List').'">
		<a href="'.$globals['ind'].'act=eu_tasklist">
			<i class="fas fa-th-list"></i>
		</a>
	</li>';
		
	$navbar_top['goto_tasklist']['responsive'] = '<li class="res-menu-item mb-2">
		<a href="'.$globals['ind'].'act=eu_tasklist">
			<i class="fas fa-th-list me-2"></i>'.__('Task List').'
		</a>
	</li>';*/	
	
	
	//Navigation Settings Dropdown
	$navbar_top['goto_userPreferences']['fullscreen'] = '<li class="navtop" data-bs-toggle="tooltip" title="'.(empty($WE->user['displayname']) ? (strlen($WE->user['name']) > 12 ? $WE->user['name'] : "") : "").'">
		<div class="dropdown">
			<button class="btn btn-primary dropdown-icon dropdown-toggle" href="#" role="button" id="userPreferences" data-bs-toggle="dropdown" aria-expanded="false">
				<i class="fas fa-user fa-sm"></i> &nbsp;'.(empty($WE->user['displayname']) ? (strlen($WE->user['name']) > 12 ? substr($WE->user['name'], 0, 12).".." : $WE->user['name']) : $WE->user['displayname']).'
			</button>
			<ul id="user_pref_dropdown" class="dropdown-menu dropdown-menu-right" aria-labelledby="userPreferences">
				<li>
					<a href="'.$globals['ind'].'act=settings" class="dropdown-item">
						<i class="fas fa-cog panel-icon"></i> '.__('Edit Settings').'
					</a>
				</li>
				<li>
					<a href="'.$globals['ind'].'act=email_settings" class="dropdown-item">
						<i class="fas fa-envelope panel-icon"></i> '.__('Email Settings').'
					</a>
				</li>
				<li>
					<a href="'.$globals['ind'].'act=help" class="dropdown-item">
						<i class="fas fa-question-circle panel-icon"></i> '.__('Help and Support').'
					</a>
				</li>
				<li><hr class="dropdown-divider"></li>
				<li>
					<a href="'.$softpanel->theme['logout'].'" class="dropdown-item">
						<i class="fas fa-sign-out-alt panel-icon"></i> '.__('Logout').'
					</a>
				</li>
			</ul>
		</div>
	</li>';

	$navbar_top['goto_userPreferences']['responsive'] = '<li>
		<div class="dropdown">
			<a class="dropdown-toggle dropdown-toggle-split ps-0" id="res-dropdown-btn" data-bs-target="#res-dropdown-menu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
				<i class="fas fa-user me-2"></i>'.(empty($WE->user['displayname']) ? $WE->user['name'] : $WE->user['displayname']).'
			</a>
			<ul class="dropdown-menu position-static" id="res-dropdown-menu" aria-labelledby="res-dropdown-btn" role="menu" style="z-index:11111;">
				<li>
					<a href="'.$globals['ind'].'act=settings" class="dropdown-item" >
						<i class="fas fa-cog panel-icon"></i> '.__('Edit Settings').'
					</a>
				</li>
				<li>
					<a href="'.$globals['ind'].'act=email_settings" class="dropdown-item">
						<i class="fas fa-envelope panel-icon"></i> '.__('Email Settings').'
					</a>
				</li>
				<li>
					<a href="'.$globals['ind'].'act=help" class="dropdown-item"> 
						<i class="fas fa-question-circle panel-icon"></i> '.__('Help and Support').'
					</a>
				</li>
				<li role="separator" class="divider"></li>
				<li>
					<a href="'.$softpanel->theme['logout'].'" class="dropdown-item">
						<i class="fas fa-sign-out-alt panel-icon"></i> '.__('Logout').'
					</a>
				</li>
			</ul>
		</div>	
	</li>';
	
	$navbar_top = apply_filters('navbar_links', $navbar_top);
	
echo '
<div class="loading">
	<button class="btn btn-primary" type="button" disabled>
		<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
		<span class="loading-txt">Loading...</span>
	</button>
</div>

<header class="soft_nav">
	<div class="container-fluid">
		<div class="nav-top-row row align-items-center"> 
			<div class="col p-0">
				<div class="brand">
					<a href="'.$globals['ind'].'"><img src="'.(empty($globals['logo_url']) ? $theme['images'].'header.png' : $globals['logo_url']).'" alt="" height="'.(!empty($softpanel->leftpanel_resize) ? '60' : '55').'" class="header_logo"/></a>
				</div>
			</div>
			<div class="col-auto nav-list">
				<div class="row justify-content-end align-items-center">
					<div class="col-auto">';
			$pre_selected_ind = array('php', 'perl', 'java', 'python');
			if(!empty($act)){
				echo '
						<div id="search_dropdown" class="dropdown float-end">
							<input id="search_input" onKeyUp="search_function(this);" type="text" name="search_input" class="sai_inputs dropdown-toggle float-end" autofocus placeholder='.__('Search').' autocomplete="off" />
							<ul class="search_list dropdown-menu" role="menu" tabindex="0" aria-labelledby="search_input"></ul>
						</div>';
			}else{
				echo '
						<input id="inputs_searchs" type="text" name="searchFeature" class="sai_inputs float-end" autofocus placeholder='.__('Search').' autocomplete="off">';
			}
			
			echo '
					</div>
					<div class="col-auto d-none d-lg-block d-xl-block d-xxl-block">
						'.(($SESS['orig_user'] == 'root') ? '<span class="you-are-admin badge me-3">'.__('You are the Admin').'</span>' : '').'
						<ul class="nav-options float-end">';
						foreach($navbar_top as $n => $v){
							echo $navbar_top[$n]['fullscreen'];
						}
				echo '
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<i class="fas fa-bars header_toggle_btn pull-right d-block d-lg-none d-xl-none d-xxl-none" alt="Left_menu" id="header_toggle_btn"></i>
	
	<div class="soft_nav_mob">
		<ul class="mobile-nav-list">';
			$top_bar = '';						
			
			echo $top_bar.'';
			foreach($navbar_top as $n => $v){
				echo $navbar_top[$n]['responsive'];
			}
			echo '
		</ul>
	</div><!--soft_nav_mob-->
		
	<div id="loading_soft" class="sai_loading_soft">
		<img src="'.$theme['images'].'fb_loader.gif" alt="Loading..." />
	</div>
	
</header>

<div class="main">

	<!--left panel table start here-->
	<div class="left-panel-menu">
		<div class="left_menu_toggle_btn" alt="'.__('Left Menu').'" id="left_menu_toggle_btn">‹</div>
		<ul class="left-panel-menu-list">';
			foreach($icons as $k => $v){
				// Check for main menu	
				if(!empty($v['href'])){
					echo '<li class="menu_item '.$k.' '.$v['class'].'" id="menu_'.$k.'">
						<div class="menu-details px-3">	
							<a href="'.$v['href'].'" class="menu-link text-decoration-none">
								<i data-toggle="tooltip" data-placement="bottom" title="'.$v['name'].'" class="'.$v['icon'].' menu-icon"></i> 
								<span class="menu-label">
									'.$v['name'].'
								</span>
							</a>
						</div>
					</li>';
				}
				
				if(empty($v['icons'])){
					continue;
				}
				
				echo '
				<li class="menu_item '.$k.'" id="menu_'.$k.'">
					<div class="menu-details">
						<i data-toggle="tooltip" data-placement="bottom" title="'.$v['name'].'" class="'.$v['icon'].' menu-icon"></i>
						<span class="menu-label">
							'.$v['name'].'
							<div class="float-end mt-1">
								<i class="fas fa-chevron-right"></i>
							</div>
						</span>
					</div>
					<ul id="'.$k.'_item" class="submenu-list collapse">';
					
					foreach($v['icons'] as $kk => $link){
					
						if(!empty($link['hidden'])){
							continue;
						}
						
						echo '<li class="submenu_item">
							<a href="'.$link['href'].'" '.(!empty($link['onclick']) ? 'onclick="'.$link['onclick'].'"' : '').' '.( !empty($link['target']) ? 'target="'.$link['target'].'"' : '').' '.( $link['atts'] ? $link['atts'] : "").' '.($submenu == $link['name'] ? 'class="active-link"' : '').'><span>'.__('- ').$link['name'].'</span></a>
						</li>';
					}
					
					echo '
					</ul>
				</li>';
			}
		
			echo '
		</ul>
	</div>
	
	<div id="softcontent" class="container-fluid">
	
		<div class="modal fade" id="show_message" tabindex="-1" aria-labelledby="show_messageLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="show_messageLabel">Modal title</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						...
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary ok" data-bs-dismiss="modal">'.__('Ok').'</button>
						<button type="button" class="btn btn-primary yes" data-bs-dismiss="modal">'.__('Yes').'</button>
						<button type="button" class="btn btn-danger no" data-bs-dismiss="modal">'.__('No').'</button>
					</div>
				</div>
			</div>
		</div>

	
		<div class="row px-3 py-4">
			<div class="col-12">';
	
	if($globals['lictype'] == '-2'){
		echo '
		<div id="soft_dev_banner" style="display:block;margin:0;padding:0;width:100%;background-color:#ffffd2;">
			<div style="padding:10px 35px;font-size:14px;text-align:center;color:#555;"><strong>Dev License:</strong> This installation of <b>'.APP.'</b> is running under a Development License and is not authorized to be used for production use. <br>Please report any cases of abuse to <a href="mailto:support@'.strtolower(APP).'.com">support@'.strtolower(APP).'.com</a>
			</div>
		</div><br/>';
	}
	
	apply_filters('post_header');
	
}


function softfooter($bottom = false){

global $user, $conn, $dbtables, $logged_in, $globals, $dmenus, $end_time, $start_time, $theme, $softpanel, $WE;

if(optGET('jsnohf')){
	return true;
}

$pageinfo = array();

if(!empty($globals['showntimetaken'])){

	$pageinfo[] = __('Page Created In').':'.substr($end_time-$start_time,0,5);

}

echo '
			</div>
		</div>
	'.(!empty($bottom) ? '<div id="error_handle_div">&nbsp;</div>' : '').'
		<footer class="row">
			<div class="col-12 text-center position-relative">
				<p class="footer-text">'.__('All times are').' '.get_timezone_offset().'. '.__('The time now is').' '.datify(time(), false).'.</p>			
				<!--Bottom Footer-->
				<p class="footer-text">'.copyright().'</p>
			</div>
			<div class="top position-fixed fixed-bottom">
				<div class="back-to-top" class="navtop" alt="'.__('Back to Top').'" title="'.__('Back to Top').'">
					<i class="fas fa-angle-up"></i>
				</div>			
			</div>
		</footer>
	</div><!-- End of #softcontent-->
</div><!-- End of .main-->';


if(!empty($theme['copyright'])){
	echo unhtmlentities($theme['copyright']);
}

echo '
</body>
</html>';

}

function js_url($files = [], $output = ''){	
	
	global $globals, $theme;
	
	$jsfile = empty($globals['dev']) && !empty($output) ? 'js/'.$output.'?' : 'js/givejs.php?files='.implode(',', $files).'&target='.$output;
	
	return '<script language="javascript" src="'.$theme['url'].'/'.$jsfile.'&'.$globals['version'].'" type="text/javascript"></script>';
	
}

function js_lang_export($lang_exports, $ret = 1){
	
	global $l;
	
	$str = '';
	foreach($lang_exports as $k => $v){
		$str .= 'l["'.$v.'"] = "'.addslashes($l[$v]).'";
';
	}
	
	if(empty($ret)){
		echo $ret;
		return;
	}
	
	return $str;
}


function error_handle($error, $table_width = '100%', $center = false, $return = false){

global $l;
	
	$str = "";

	$table_width = preg_match('/%/is', $table_width) ? $table_width : $table_width.'px';

	//on error call the form
	if(!empty($error)){
	
		$error = apply_filters('error_handle', $error);
		
		$str .= (($center) ? '<center>' : '').'<div id="error_handler" class="alert alert-danger " style="width:'.$table_width.'"><a href="#close" class="close" data-bs-dismiss="alert">&times;</a><div>';
		$str .= '<p style="margin-top:4px; font-size:16px;">&nbsp;&nbsp;'.__('The following errors were found').' :</p>
			<ul type="square" style="margin-top:-4px;">';
		
		foreach($error as $ek => $ev){
		
			$str .= '<li style="font-size:13px;">'.$ev.'</li>';
		
		}
		
		$str .= '</ul>
		</div></div>'.(($center) ? '</center>' : '');
		
		if(empty($return)){
			echo $str;
		}else{
			return $str;	
		}
		
	}

}


//This will just echo that everything went fine
function success_message($message, $table_width = '100%', $center = false){

global $l;

	//on error call the form
	if(!empty($message)){
		
		echo '<table width="'.$table_width.'" cellpadding="2" cellspacing="1" class="sai_error" '.(($center) ? 'align="center"' : '' ).'>
			<tr>
			<td>
			'.__('The following message was returned').' :
			<ul type="square">';
		
		foreach($message as $mk => $mv){
		
			echo '<li>'.$mv.'</li>';
		
		}
		
		
		echo '</ul>
			</td>
			</tr>
			</table>'.(($center) ? '</center>' : '' ).'
			<br />';
		
		
	}

}


function majorerror($title, $text, $heading = ''){

global $theme, $globals, $user, $l;

softheader(((empty($title)) ? __('Fatal Error') : $title));

?>
<div class="soft-smbox p-2 col-8  mx-auto">
	<div class="card text-light mb-4" style="width:90%;margin:auto; margin-top:20px;">
		<div class="card-header text-dark text-center fs-3 alert alert-danger">
			<i class="fa fa-exclamation-triangle fa-lg" style="color:red;" aria-hidden="true"></i>&nbsp;&nbsp;
			<?php echo ((empty($heading)) ? __('The following error occured').':' : $heading);?>
		</div>
		<div class="card-body" style="background-color:white;">
			<p class="card-text text-dark text-center"><font size="3"><?php echo $text;?></p>
		</div>
	</div>
</div>

<?php

softfooter(true);

//We must return
return true;

}

function message($title, $heading = '', $icon, $text){

global $theme, $globals, $user, $l;

softheader(((empty($title)) ? APP.' '.__('Message') : $title));

?>

<center><br /><br />
<div class="sai_divroundshad" style="width:70%;margin:0px auto;">
<table width="100%" cellpadding="2" cellspacing="1" class="sai_cbor" align="center">
	
	<tr>
	<td class="cbg" align="left"  >
	<b><?php echo ((empty($heading)) ? __('The following message was returned').':' : $heading);?></b>
	</td>
	</tr>
	
	<tr>
	<td class="sai_bg" colspan="2" align="center">
	<img src="<?php echo $theme['images'].(empty($icon)?'info.gif':$icon);?>" alt="" />
	</td>
	</tr>
	
	<tr>
	<td class="sai_error" align="left"><?php echo $text;?><br />
	</td>
	</tr>

</table>
</div></center>
<br /><br /><br />

<?php

softfooter();

//We must return
return true;

}

//Pagination
function page_links($text = 'Results Found', $num_res = NULL, $max = NULL){
	
	global $l, $globals, $theme;
	
	if(is_null($num_res)){
		$num_res = $globals['num_res'];
	}
	
	$page = $globals['cur_page'];
	
	if(is_null($max)){
		$max = $globals['reslen'];
	}	
	
	$pages = $max != -1 ? ceil($num_res/$max) : 0; // Number of Pages	
	$pg = ceil(($page/$max) + 1); //Current Page

	$_pages = array();
	
	if($pages > 1){
		
		// Show the Back Links if required
		if($pg != 1){			
			$_pages['&lt;&lt;'] = 1;			
			$_pages['&lt;'] = ($pg - 1);		
		}
		
		for($i = ($pg - 4); $i < $pg; $i++){			
			if($i >= 1){			
				$_pages[$i] = $i;			
			}		
		}
		
		$_pages[$pg] = $pg;
				
		for($i = ($pg + 1); $i <= ($pg + 4); $i++){		
			if($i <= $pages){			
				$_pages[$i] = $i;			
			}		
		}
		
		
		if($pg != $pages){			
			$_pages['&gt;'] = ($pg + 1);			
			$_pages['&gt;&gt;'] = $pages;		
		}	
	}

	$name = 'pgjmp_'.generateRandStr(4);
		
	$links = '
<div class="row">
	<div class="col-12 col-md-5 col-lg-4">
		<label colspan="0" class="form-label me-1">'.__('Entries Per Page').'
			<select name = "reslen" class="perpage" onchange="return go_to(this.value)">
				<option value="-1">--</option>
				<option value="10" '.($max == 10 ? 'selected="selected"' : '').'>10</option>
				<option value="25" '.($max == 25 ? 'selected="selected"' : '').'>25</option>
				<option value="50" '.($max == 50 ? 'selected="selected"' : '').'>50</option>
				<option value="100" '.($max == 100 ? 'selected="selected"' : '').'>100</option>
				<option value="all" '.($max == -1 ? 'selected="selected"' : '').'>'.__('All').'</option>
			</select>
		</label>
		<label class="form-label me-2">'.$text.' : 
			<span id="num_res">'.$num_res.'</span>
		</label>
	</div>
	<div class="col-12 col-md-7 col-lg-8" style="display:'.($max != -1 ? '' : 'none').'">					
		<nav class="d-flex align-items-center justify-content-md-end flex-wrap" aria-label="Page navigation">
			<label class="pagelinks me-2">
				'.__('Page').' '.$pg.' '.__('of').' '.$pages.'
			</label>
			<ul class="pagination mb-0 me-2">';
		foreach($_pages as $k => $lv){
			$links .= '
				<li class="page-item '.($k == $pg ? 'active' : '' ).'">
					<a class="page-link" href="javascript:go_to('.$max.', '.$lv.')">'.$k.'</a>
				</li>';
		}
	
	$links .= '
			</ul>
			<div id="'.$name.'" class="mt-2 mt-sm-2 mt-md-0">
				<input type="text" name="page" id="in'.$name.'" size="10" style="width:50px;"/> 
				<input class="perpage p-2" type="button" value="'.__('Go').'" onclick="go_to('.$max.', $_(\'in'.$name.'\').value)" style="padding:8px 10px; width:60px; border-radius:20px; border: 1px solid rgba(4, 114, 237, 9%);"/>
			</div>
		</nav>
		
	</div>
</div>';
		
	echo $links;
}