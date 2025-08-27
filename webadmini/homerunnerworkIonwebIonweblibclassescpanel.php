<?php

// Language mapping for cpanel -> webuzo
$GLOBALS['cpanel_languages'] = ['ar' => 'arabic', 'bg' => 'bulgarian', 'cs' => 'czech', 'da' => 'danish', 'de' => 'german', 'el' => 'greek', 'en' => 'english', 'es' => 'spanish', 'fi' => 'finnish', 'fil' => 'filipino', 'fr' => 'french', 'he' => 'hebrew', 'hu' => 'hungarian', 'id' => 'indonesian', 'it' => 'italian', 'ja' => 'japanese', 'ko' => 'korean', 'nl' => 'dutch', 'ms' => 'malay', 'nb' => 'norwegian', 'pl' => 'polish', 'pt' => 'portuguese', 'pt_br' => 'portuguese_br', 'ro' => 'romanian', 'ru' => 'russian', 'sl' => 'slovenian', 'sk' => 'slovak', 'sv' => 'swedish', 'th' => 'thai', 'tr' => 'turkish', 'uk' => 'ukrainian', 'vi' => 'vietnamese', 'zh' => 'chinese', 'zh_cn' => 'chinese_china', 'zh_tw' => 'chinese_taiwan'];

// Make cPanel API calls
function cpanel_curl($host, $user, $server_pass, $action = '', $url = '', $http_code = ''){
	global $error, $l;
	
	if(!empty($url)){
		$url = $url;
	}else{
		$url = "https://$host:2087/json-api/$action?api.version=2";
	}
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	
	$header[0] = "Authorization: Basic " . base64_encode($user.":".$server_pass) . "\n\r";

	//$header[0] = "Authorization: whm $user:$token";
	curl_setopt($curl,CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_URL, $url);

	$result = curl_exec($curl);
   
	// Wrong server credentials.
	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	
	if(!empty($http_code)){
		return $http_status;
	}
	
	if ($http_status != 200) {
		$error['ic_unable_to_connect'] = __('Unable to connect cPanel Server').' - '.curl_error($curl).' - '.var_export($http_status, true);
		return false;
	}
	
	$resp = json_decode($result, true);
	
	// If cPanel returns malformed utf-8 characters
	if(empty($resp)){
		
		$err = '';
		if(function_exists('json_last_error')){
			$err = json_last_error();
		}
		
		//Error Code 5 : "Malformed UTF-8 characters, possibly incorrectly encoded"
		if($err == 5){
			$result = mb_convert_encoding($result, 'UTF-8', 'UTF-8');
			$resp = json_decode($result, true);
		}
		
	}

	curl_close($curl);
	
	return $resp;
}

// Create a plan similar to cPanels
function cpanel_create_plan($plan){
	
	global $globals, $l, $plan_fields, $plan_options, $plan_keys, $SESS;
	
	plan_fields();
	
	$data = [];
	foreach ($plan_fields as $cat => $val) {
		foreach($val['list'] as $key => $value){			
			// This is multicheckbox
			if($value['type'] == 'multicheckbox'){
				foreach($value['list'] as $k => $v){
					if(empty($v['checked'])){
						continue;
					}
					$data[$key][$k] = (int) $v['checked'];
				}
			}else{
				$data[$key] = $value['default'];
			}
		}
	}
	
	if(isset($plan['MAX_EMAILACCT_QUOTA'])){
		$data['max_quota_email'] = $plan['MAX_EMAILACCT_QUOTA'];
	}
	
	if(isset($plan['HASSHELL']) && $plan['HASSHELL'] == 'y'){
		$data['options']['shell'] =  1;
	}
	
	if(isset($plan['MAXSUB'])){
		$data['max_subdomain'] = $plan['MAXSUB'];
	}
	
	if(isset($plan['MAX_DEFER_FAIL_PERCENTAGE'])){
		$data['max_percent_failed'] = $plan['MAX_DEFER_FAIL_PERCENTAGE'];
	}
	
	if(isset($plan['BWLIMIT'])){
		$data['max_bandwidth_limit'] = $plan['BWLIMIT'];
	}
	
	if(isset($plan['MAXSQL'])){
		$data['max_database'] = $plan['MAXSQL'];
	}
	
	if(isset($plan['MAXFTP'])){
		$data['max_ftp_account'] = $plan['MAXFTP'];
	}
	
	if(isset($plan['QUOTA'])){
		$data['max_disk_limit'] = $plan['QUOTA'];
	}
	
	if(isset($plan['IP']) && $plan['IP'] == 'y'){
		$data['options']['dedicated_ip'] = 1;
	}
	
	$data['lang'] = 'english';
	if(isset($plan['LANG'])){
		if(!empty($GLOBALS['cpanel_languages'][$plan['LANG']])){
			$data['lang'] = $GLOBALS['cpanel_languages'][$plan['LANG']];
		}
	}
	
	if(isset($plan['MAX_EMAIL_PER_HOUR'])){
		$data['max_hourly_email'] = $plan['MAX_EMAIL_PER_HOUR'];
	}
	
	if(isset($plan['MAXPARK'])){
		$data['max_parked_domain'] = $plan['MAXPARK'];
	}
	
	if(isset($plan['MAXADDON'])){
		$data['max_addon_domain'] = $plan['MAXADDON'];
	}
	
	if(isset($plan['MAXPOP'])){
		$data['max_email_account'] = $plan['MAXPOP'];
	}
		
	if(!empty($plan['plan_owner'])){
		$data['plan_owner'] = $plan['plan_owner'];
	}
	
	$data['plan_name'] = $plan['name'];
	$data['slug'] = slugify($data['plan_name']);
	
	//print_r($plan);
	//print_r($data);
	
	$path = $globals['plans_path'].'/'.$data['slug'];
	$ret = writedata($path, $data);
	
}

// Return our keys formatted data
function cpanel_user_data_convert($plan){
	
	if(isset($plan['maxaddons'])){
		$data['max_addon_domain'] = $plan['maxaddons'];
	}
	
	if(isset($plan['maxaddon'])){
		$data['max_addon_domain'] = $plan['maxaddon'];
	}
	
	if(isset($plan['suspendreason'])){
		$data['suspend_reason'] = $plan['suspendreason'];
	}
	
	if(!empty($plan['suspended'])){
		$data['status'] = 'suspended';
	}
	
	if(!empty($plan['shell']) && !preg_match('/noshell|nologin/is', $plan['shell'])){
		$data['shell'] = 1;
	}
	
	if(isset($plan['outgoing_mail_suspended'])){
		$data['outgoing_mail_suspended'] = $plan['outgoing_mail_suspended'];
	}
	
	if(isset($plan['outgoing_mail_hold'])){
		$data['outgoing_mail_hold'] = $plan['outgoing_mail_hold'];
	}
	
	if(isset($plan['inodeslimit'])){
		$data['max_inode'] = $plan['inodeslimit'];
	}
	
	// cpanel is in bytes
	if(isset($plan['bwlimit'])){
		if($plan['bwlimit'] == 'unlimited'){
			$data['max_bandwidth_limit'] = $plan['bwlimit'];
		}elseif(!empty($plan['bwlimit'])){
			$data['max_bandwidth_limit'] = (int) ($plan['bwlimit'] / 1024 / 1024);
		}else{
			$data['max_bandwidth_limit'] = (int) $plan['bwlimit'];
		}
	}
	
	if(isset($plan['plan']) && $plan['plan'] != 'undefined'){
		$data['plan'] = slugify($plan['plan']);
	}
	
	if(isset($plan['maxparked'])){
		$data['max_parked_domain'] = $plan['maxparked'];
	}
	
	if(isset($plan['owner']) && $plan['owner'] != 'root'){
		$data['owner'] = $plan['owner'];
	}
	
	if(isset($plan['maxpop'])){
		$data['max_email_account'] = $plan['maxpop'];
	}
	
	if(isset($plan['max_email_per_hour'])){
		$data['max_hourly_email'] = $plan['max_email_per_hour'];
	}
	
	// cpanel is in kilobytes
	if(isset($plan['disklimit'])){
		$data['max_disk_limit'] = ($plan['disklimit'] == 'unlimited' ? $plan['disklimit'] : (int) $plan['disklimit']);
	}
	
	if(isset($plan['DISK_BLOCK_LIMIT'])){
		
		if($plan['DISK_BLOCK_LIMIT'] == 'unlimited'){
			$data['max_disk_limit'] = $plan['DISK_BLOCK_LIMIT'];
		}elseif(!empty($plan['DISK_BLOCK_LIMIT'])){
			$data['max_disk_limit'] = (int) ($plan['DISK_BLOCK_LIMIT'] / 1024);
		}else{
			$data['max_disk_limit'] = (int) $plan['DISK_BLOCK_LIMIT'];
		}
	}
	
	if(isset($plan['maxsql'])){
		$data['max_database'] = $plan['maxsql'];
	}
	
	if(isset($plan['max_defer_fail_percentage'])){
		$data['max_percent_failed'] = $plan['max_defer_fail_percentage'];
	}
	
	if(isset($plan['max_emailacct_quota'])){
		$data['max_quota_email'] = $plan['max_emailacct_quota'];
	}
	
	if(isset($plan['maxsub'])){
		$data['max_subdomain'] = $plan['maxsub'];
	}
	
	if(isset($plan['maxftp'])){
		$data['max_ftp_account'] = $plan['maxftp'];
	}
	
	if(isset($plan['IP']) && $plan['IP'] == 'y'){
		$data['dedicated_ip'] = 1;
	}
	
	return $data;
	
}

function cpanel_reseller_limits_convert($r_limits){
	
	$array = [];
	
	if(empty($r_limits)){
		return $array;
	}
	
	foreach($r_limits as $reseller => $limits){
	
		if(!empty($limits['limits']['number_of_accounts']['enabled'])){
			$array[$reseller]['reseller_tot_acc_limit'] = $limits['limits']['number_of_accounts']['accounts'];
		}
		
		if(!empty($limits['limits']['resources']['enabled'])){
			
			if(!empty($limits['limits']['resources']['overselling']['enabled'])){
				$array[$reseller]['reseller_allow_overselling'] = 1;
			}
			
			if(!empty($limits['limits']['resources']['type']['bw'])){
				$array[$reseller]['reseller_tot_bandwidth'] = $limits['limits']['resources']['type']['bw'];
			}
			
			if(!empty($limits['limits']['resources']['type']['disk'])){
				$array[$reseller]['reseller_tot_disk_limit'] = $limits['limits']['resources']['type']['disk'];
			}
		}
	}
	
	return $array;
}

// Get filters key matching cPanel filter format
function cpanel_get_filter_keys(){
	$input_arr = [];
	$input_arr['email_part'] = [
		'$header_from:' => 'header_from',
		'$header_subject:' => 'header_subject',
		'$header_to:' => 'header_to',
		'foranyaddress $h_to:,$h_cc:' => 'foranyaddress',
		'$reply_address:' => 'reply_address',
		'$message_body' => 'message_body',
		'$message_headers' => 'message_headers',
		'error_message' => 'error_message',
		'not delivered' => 'not delivered',
		'$h_List-Id:' => 'h_list',
		'$h_X-Spam-Status:' => 'h_spam_status',
		'$h_X-Spam-Bar:' => 'h_spam_bar',
		'$h_X-Spam-Score:' => 'h_spam_score',
	];
	
	$input_arr['match_part'] = [
		'contains' => 'contains',
		'matches' => 'matches',
		'does not contain' => 'd_not_contain',
		'is' => 'is',
		'begins' => 'begins',
		'ends' => 'ends',
		'does not begin' => 'd_not_begin',
		'does not end' => 'd_not_end',
		'does not match' => 'd_not_match',
		'is above' => 'is_above',
		'is not above' => 'is_not_above',
		'is below' => 'is_below',
		'is not below' => 'is_not_below',
	];
	
	
	$input_arr['action_arr'] = [
		'deliver' => 'deliver',
		'fail' => 'fail',
		'finish' => 'finish',
		'save' => 'deliver_folder',
		'pipe' => 'pipe',
	];
	
	return $input_arr;
}