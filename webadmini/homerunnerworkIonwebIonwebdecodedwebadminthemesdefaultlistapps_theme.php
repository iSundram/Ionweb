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

function listapps_theme(){

	global $theme, $globals, $user, $cat, $list, $category, $apps, $apps_catwise;
	
	softheader($cat);
	
	$apps_cat_icons['stacks'] = 'fa fa-layer-group';
	$apps_cat_icons['server_side_scripting'] = 'fa fa-code';
	$apps_cat_icons['web_servers'] = 'fa fa-globe';
	$apps_cat_icons['databases'] = 'fa fa-database';
	$apps_cat_icons['java_tools'] = 'fab fa-java';
	$apps_cat_icons['java_containers'] = 'fa fa-archive';
	$apps_cat_icons['utilities'] = 'fa fa-wrench';
	$apps_cat_icons['libraries'] = 'fa fa-book';
	$apps_cat_icons['statistics'] = 'fa fa-chart-pie';
	$apps_cat_icons['security'] = 'fa fa-shield-alt';
	$apps_cat_icons['version_control'] = 'fa fa-code-branch';
	$apps_cat_icons['modules'] = 'fa fa-puzzle-piece';
	$apps_cat_icons['message_queue'] = 'fa fa-envelope';
	
	// Unset features to make this file small
	foreach($apps_catwise as $k => $v){
		foreach($v as $kk => $vk){			
			unset($apps_catwise[$k][$kk]['features']);
		}
	}

	if(!empty($globals['lictype'])){
		
			echo '
<style>
._softable{
border:5px solid #F0F0F0;
}	

._deschead a:link, ._deschead a:visited, ._deschead a:active, ._deschead a:hover{
color: #666666;
font-size:15px;
font-weight:bold;
font-family: Arial, Helvetica, sans-serif;
text-decoration:none;
}

._descr{
font-size:11px;
padding-left:15px;
}

._icodesc a:link, ._icodesc a:visited, ._icodesc a:active, ._icodesc a:hover{
color:#666666;
font-weight:bold;
font-size:12px;
font-family: Arial, Helvetica, sans-serif;
text-decoration:none;
}

._ratings{
font-size:12px;
font-weight:bolder;
}

._views{
font-size:12px;
font-weight:bolder;
}

._imghr{
margin:6px 0px;
}

</style>
				
<!-- Do not edit IE conditional style below -->
<!--[if gte IE 5.5]>

<style type="text/css">
#motioncontainer {
width:expression(Math.min(this.offsetWidth, maxwidth)+\'px\');
}
</style>
<![endif]-->
<link rel="stylesheet" type="text/css" href="'.$theme['url'].'/css/motiongallery.css?'.$GLOBALS['globals']['version'].'" />
<script language="javascript" type="text/javascript" src="'.$theme['url'].'/js/motiongallery.js?'.$GLOBALS['globals']['version'].'"></script>
<div class="row">
	<div class="col-12 col-md-10">
		<link rel="stylesheet" type="text/css" href="'.$globals['mirror_images'].'sprites/80.css" />';
			
			$ids_ = array_keys($category);
			$ids = array_keys($category);
	
			foreach($ids_ as $v){			
				$v = (int) trim($v);
				if(!empty($v)){
					$ids[$v] = $v;
				}
			}
	
			if(empty($ids)){
				return false;
			}
	
			$branches = array();
	
			$url = $theme['images'];
			echo '
		<div id="motioncontainer">
			<div id="motiongallery" style="position:absolute;left:0;top:0;white-space: nowrap;">
				<div class="sai_blog_script_holder">
					<table id="trueContainer" width="100%" border="0" cellspacing="8">
						<tr>';
	
			foreach($apps as $k => $v){
			
				if(!in_array($k, $ids)){
					continue;
				}
								
				// If the script has a parent and it is enabled we will display only the parent
				if(!empty($v['parent']) && array_key_exists($v['parent'], $iscripts)){
					$branches[$v['parent']][$k] = $v['version'];
					continue;
				}
							
				echo '
							<td valign="middle">
								<a href="'.(($globals['mode'] == 'apps') ? app_link($v['aid']) : script_link($v['sid'])).'" title="'.$v['name'].'" style="display:block; text-decoration:none;" >
									<div class="sai_blog_script"><br />
										<img src="'.$globals['mirror_images'].'webuzo/softimages/'.$k.'__logo.gif" alt="'.$v['softname'].'" class="img-responsive my-3 mx-4">
										<div class="d-flex sai_script_name">
											<span class="m-auto">'.$v['name'].'</span>
										</div>
									</div>
								</a>
							</td>';
							
			}
			
			echo '
						</tr>
					</table>
				</div>
			</div>
		</div>
<script language="javascript" type="text/javascript">

if(typeof $_ != "function"){
	function $_(id){ return document.getElementById(id); };
}
function startmotiongal(){
	$_("trueContainer").style.height = $_("motioncontainer").offsetHeight+"px";
	fillup();
};
try{
	$_("trueContainer").style.height = $_("motioncontainer").offsetHeight+"px";
	$(document).ready(function(){
		setTimeout("startmotiongal();", 100);
	});
} catch(e){ }

</script>';
		
			foreach ($apps as $k => $v) {
				
				if(!in_array($k, $ids)){
					continue;
				}
		
				// If the script has a parent and it is enabled we will display only the parent
				if(!empty($v['parent']) && array_key_exists($v['parent'], $iscripts)){
					continue;
				}
		
				$ratings = array();
				$deduct = 1;
				$r = $v['ratings'];
			
				for($i=1; $i<=5; $i++){		
					if($r >= 1){
						$ratings[$i] = '<img src="'.$url.'star.png'.'" alt="('.number_format($v['ratings'], 2).' out of 5)" />';
					}elseif($r > 0){
						$ratings[$i] = '<img src="'.$url.'halfstar.png'.'" alt="('.number_format($v['ratings'], 2).' out of 5)" />';
					}else{
						$ratings[$i] = '<img src="'.$url.'nostar.png'.'" alt="('.number_format($v['ratings'], 2).' out of 5)" />';
					}
					$r = $r - $deduct;
				}	
		
				$demo_scriptname = str_replace(' ', '_', $v['name']);
		
				$logo_url = '';
				
				if($k >= 10000){
					if(file_exists(dirname($theme['path']).'/default/images/topscripts/'.$v['sid'].'__logo.gif')){
						$logo_url = $url.'topscripts/'.$v['sid'].'__logo.gif';
					}
				}else{
					$logo_url = $globals['mirror_images'].'webuzo/softimages/'.$k.'__logo.gif';
				}
		
				echo '
		<div class="soft-smbox app-info my-3 p-3">
			<div class="sai_loginhead" valign="middle">
				<a href="'.(($globals['mode'] == 'apps') ? app_link($v['aid']) : script_link($v['sid'])).'" class="text-decoration-none">'.$v['name'].'</a>
				'.($k < 10000 && empty($globals['off_rating_link']) ? '&nbsp;&nbsp;<span title="'.$v['ratings'].'">'.implode('', $ratings).'</span>' : '').'
				<a href="'.(($globals['mode'] == 'apps') ? app_link($v['aid']) : script_link($v['sid'])).'" style="text-decoration: none; color:#FFFFFF;">'.(is_app_installed($v['softname']) ? '<div class="btn btn-danger float-end">'.__('Remove') : '<div class="btn btn-primary float-end">'.__('Install')).'</a></div>
			</div>
			<hr />			
			<div class="row">
				<div class="col-12 col-md-3 col-lg-2 text-center">';
			
				if(!empty($logo_url)){
					echo '
					<a href="'.(($globals['mode'] == 'apps') ? app_link($v['aid']) : script_link($v['sid'])).'">
						<img src="'.$logo_url.'" alt="'.$v['softname'].'" class="img-responsive" style="margin:auto;">
					</a>';
				}
				
				echo '
				</div>
				<div class="col-12 col-md-6 col-lg-8">
					<div>
						<label class="d-block">
							<span class="sai_head">'.__('Version').' :</span>
							<span class="ms-1"> '.$v['version'].(!empty($branches[$k]) ? ', '.implode(', ', $branches[$k]) : '').'</span>
						</label>
						'.(!empty($v['release_date']) ? '
						<label class="d-block">
							<span class="sai_head">'.__('Release Date').' :</span>
							<span class="ms-1">'.$v['release_date'] : '').'</span>
						</label>
					</div>
					<div class="mt-2">
						<p>'.$v['overview'].'</p>
					</div>
				</div>
				<div class="col-12 col-md-3 col-lg-2">
					'.(($globals['mode'] != 'apps') ? (empty($globals['off_demo_link']) ? ' 
					<a href="'.($k >= 10000 ? $v['demo'] : $globals['ind'].'act=demos&soft='.$v['sid']).'" target="_blank" class="text-decoration-none">
						<i class="fa fa-caret-square-right"></i><span class="_links">'.__('Demo').'</span>
					</a>' : '') : '').'
					
					<a href="'.$v['support'].'" class="text-decoration-none support-btn">
						<i class="fa fa-life-ring"></i>
						<span class="_links">'.__('Support').'</span>
					</a>
					<a href="'.(($globals['mode'] == 'apps') ? app_link($v['aid']) : script_link($v['sid'])).'" class="text-decoration-none download-btn">
						<i class="fa fa-hdd"></i>
						<span class="_links">'.number_format($v['space']/1024/1024, 2).' MB</span>
					</a>
				</div>
			</div>
		</div><!--end of bg-->';
			
		}
	}else{
		echo __('In the free version of '.APP.', this feature of comparing and seeing the ratings of various software/scripts within the '.APP.' Panel itself is disabled. However you can visit the external links to see the ratings of the software.');
	}
	
	echo '
	</div>
	<div class="col-12 col-md-2 px-0">';
		dropdown_app_list();
	echo '
	</div>
</div>';
	
	softfooter();

}

function dropdown_app_list(){
	global $theme, $globals, $user, $cat, $list, $category, $apps, $apps_catwise, $act;
	
	$apps_cat_icons['stacks'] = 'fa fa-layer-group';
	$apps_cat_icons['server_side_scripting'] = 'fa fa-code';
	$apps_cat_icons['web_servers'] = 'fa fa-globe';
	$apps_cat_icons['databases'] = 'fa fa-database';
	$apps_cat_icons['java_tools'] = 'fab fa-java';
	$apps_cat_icons['java_containers'] = 'fa fa-archive';
	$apps_cat_icons['utilities'] = 'fa fa-wrench';
	$apps_cat_icons['libraries'] = 'fa fa-book';
	$apps_cat_icons['statistics'] = 'fa fa-chart-pie';
	$apps_cat_icons['security'] = 'fa fa-shield-alt';
	$apps_cat_icons['version_control'] = 'fa fa-code-branch';
	$apps_cat_icons['modules'] = 'fa fa-puzzle-piece';
	$apps_cat_icons['message_queue'] = 'fa fa-envelope';
	
	$str = '<script>
			var cat_lang = new Array();';
	
	$cat_lang = array('server_side_scripting' => __('Server Side Scripting'),
		'web_servers' => __('Web Servers'),
		'utilities' => __('Utilites'),
		'libraries' => __('Libraries'),
		'databases' => __('Databases'),
		'stacks' => __('Stacks'),
		'java_tools' => __('Java Tools'),
		'java_containers' => __('Java Containers'),
		'statistics' => __('Statistics'),
		'security' => __('Security'),
		'modules' => __('Modules'),
		'version_control' => __('Version Control'),
		'message_queue' => __('Message Queue')
	);
	
	foreach($apps_catwise as $cat => $softs){
		if(empty($softs)) continue;
		$str .= 'cat_lang[\'cat_apps_'.$cat.'\'] = "'.$cat_lang[$cat].'";';
	}
	
	$str .= '
			</script>';
		
	echo $str;
				
	echo '
		<div id="apps-side-menu" class="apps-side-menu">
			<div class="container p-2">
				<div class="search-box">
					<input type="text" id="search_apps" onfocus="this.value=\'\';" onKeyUp="search_apps(this.value);" value="'.__('Search Apps').'" placeholder="'.__('Search Apps').'" class="sai_search_box mt-3">
					<i class="fa-solid fa-magnifying-glass fa-xs"></i>
				</div>
			</div>			
			<div class="no_apps_found"></div>
			<div class="apps-list my-3">
				<ul id="apps-content" class="apps-content collapse out">
					<div id="load_apps_js"></div>';
	echo '
				</ul>
			</div>
		</div>
<script>

//for left panel toggle
function left_toggle(ele){
	console.log(ele);
	var act = '.json_encode($act).';
	var shown = false;
	shown=$("#"+ele+"_l").is(":visible");
	if(!shown){
	$("#"+ele+"_l").slideDown("slow");
	$("#"+ele).addClass("active");
		setcookie("a_head_"+ele, 2);
		$("#"+ele+"_img").css("display","none");
		$("#"+ele+"_img_opened").css("display","block");
	}else{
	$("#"+ele+"_l").slideUp("slow");
		removecookie("a_head_"+ele);
		$("#"+ele+"_img").css("display","block");
		$("#"+ele+"_img_opened").css("display","none");
	}
	
	if (act == "listapps") {
		window.location.href = "'.$globals['ind'].'act=listapps&cat=" + ele;
	}else{
		console.log(ele);
		window.location.reload();
	}
}
//left panel apps toggle state
function apps_left_panel_state(){
	
	var main = $(".apps-list li"); 
	var sub_link = $(".apps-list ul ul li");
	
	sub_link.each(function(){
		var link_id = $(this).closest("li").attr("id");
		
		$("#"+link_id).click(function(){
			setcookie("sub_link", link_id);
		});
		var if_isset_submenu = getcookie("sub_link");
		if(if_isset_submenu == link_id){
			var id = $("#"+link_id).parent().attr("id");
			var id = id.substring(0,id.length - 2);
			
			$("#"+id).addClass("active");
			$("#"+link_id).addClass("active");
			$("#home").removeClass("active");
		}
		$("#home").click(function(){
			removecookie("sub_link");
		});
	});

	main.each(function(){                                  
		var cookie_id = $(this).closest("li").attr("id");
		var if_isset_toggle = getcookie("a_head_"+cookie_id);
		var if_isset_menu = getcookie("a2_head_menu_"+cookie_id);
		var tmp_cookieid = String("a_head_"+cookie_id);
		$("#opened_img").hide();
		if(if_isset_menu == 3){
			$("#home").removeClass("active");
		}
		
		if(if_isset_menu == cookie_id){
			$("#home").removeClass("active");
			$("#"+if_isset_menu).addClass("active");		
		}
		
		$("#"+cookie_id).click(function(){
			
			setcookie("a2_head_menu_"+cookie_id, cookie_id);
			main.each(function(){
				var cookie_id_cookie = $(this).closest("li").attr("id");
				var if_isset_menu = getcookie("a2_head_menu_"+cookie_id_cookie);	
				$("#"+cookie_id_cookie).removeClass("active");
				if(if_isset_menu == cookie_id){
					$("#"+cookie_id).addClass("active");					
				}
				if(cookie_id_cookie != cookie_id){
					removecookie("a2_head_menu_"+cookie_id_cookie);		
				}
			});
			
		});				
	
		if(if_isset_menu == 3){
			removecookie("sub_link");
		}
		
		if(if_isset_toggle == 2 && tmp_cookieid != "undefined"){
			var id = $("#"+cookie_id+"_l");
			var img_id = $("#"+cookie_id+"_img");
			id.show();
			$("#"+cookie_id+"_img").css("display","none");
			$("#"+cookie_id+"_img_opened").css("display","block");
		}
		
	});
}

function search_apps(val){
	
	var $val = val.toLowerCase();
	var qlen = val.length;
	if(qlen >= 1){
		$(".apps-list ul ul").each(function(){
			var toggle_id  = $(this).attr("id");
			$("#"+toggle_id).slideDown();
		});
	}else{
		show_apps(); //after search if input is empty then load the apps cats again.
	}

	$(".apps-list ul li").each(function(){	
		var text = $(this).text().toLowerCase();
		if(text.indexOf($val) == 0){
			var cat_id = $(this).parent("ul").attr("id");
			var cat_id = cat_id.slice(0,-2);
			$("#"+cat_id).show();
		}
		(text.indexOf($val) == 0) ? $(this).show("fast") : $(this).hide("fast", function(){
			shown = $(".apps-list ul li").is(":visible");
			
			if(!shown){
				$(".no_apps_found").show();
				$(".no_apps_found").html("'.__('No data found !').'").css("padding-left", "10px");
			}else{
				$(".no_apps_found").hide();
			}
			
		});
		
	});

}

function show_apps(){
	
	var str_html = "";
	var icat = 0;
	var next_counter = 1;
	var i_ind = "apps";
	apps_catwise = '.json_encode($apps_catwise).';
	apps_cat_icons = '.json_encode($apps_cat_icons).';
	$.each(apps_catwise, function (i, item) {
		
		var if_isset = getcookie("head_"+icat);
		var tmp_cookieid = String("head_"+icat);
		var display_open = "none";
		if(if_isset == 2 && tmp_cookieid != "undefined"){
			display_open = "block";
		}
		var show_combined = true;	
		isset_ind = "apps";			
		var cat_key = "cat_"+i_ind+"_"+i;
		
		str_html += \'<li id="\'+i+\'" onclick="left_toggle(this.id)"><i class="\'+apps_cat_icons[i]+\' fa-1x"></i>&nbsp;&nbsp;\'+(cat_lang[cat_key] || i)+\'<div class="pull-right" id="\'+i+\'_img" style="margin-top:5%;"><img src="'.$theme['images'].'collapsed.png"></div><div class="pull-right" id="\'+i+\'_img_opened" style="margin-top:5%; display:none;"><img src="'.$theme['images'].'expanded.png"></div></li><ul class="apps-sub-menu collapse" id="\'+i+\'_l">\';
		//alert(str_html);
		
		$.each(item, function (sid, softw) {
			
			var soft = "app";
			acts = "apps";				
			var li_classes = "";
			
			str_html += \'<li id="sub_\'+i+\'"><a href="'.$globals['ind'].'act=\'+acts+\'&\'+soft+\'=\'+sid+\'" title="\'+softw.desc+\'"><i class="fa fa-chevron-right fa-1x"></i>\'+softw.name+\'</a></li>\';
			//alert(str_html);	
			//str_html += \'<li \'+li_classes+\'><a href="'.$globals['ind'].'act=\'+acts+\'&\'+soft+\'=\'+sid+\'" title="\'+softw.desc+\'" class="selectnext" id="selectnext-\'+next_counter+\'">\'+softw.name+\'</a></li>\';
				
			next_counter++;
		});
		
			
		str_html += \'</ul>\';
		icat = icat + 1;
		
	});
	$("#load_apps_js").html(str_html);
}

$(document).ready(function(){

	var url = window.location;
	var app_index = url.toString().match(/\?act=listapps/i);
	
	if(app_index){
		$(".apps-side-menu").show();
	}

	show_apps();
	apps_left_panel_state();
});

</script>';

}
