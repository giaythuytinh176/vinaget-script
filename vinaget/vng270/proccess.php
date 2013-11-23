<?php
ob_start();
ob_implicit_flush (TRUE);
ignore_user_abort (0);
if( !ini_get('safe_mode') )set_time_limit(30);
define('vinaget', 'yes');
date_default_timezone_set('Asia/Jakarta');

include("class.php");
$obj = new stream_get(); 

$page = isset($_GET['page']) ? $_GET['page'] : 'config';

if($page == 'config'){
	if(isset($_POST['submit'])){
		foreach($_POST['config'] as $ckey => $cval){
			if($cval == 'on' || $cval == 'off') $cval = $cval == 'on' ? true : false;
			elseif(is_numeric($cval)) $cval = intval($cval);
			else $cval = $cval;
			$obj->config[$ckey] = $cval;
		}
		setcookie("cfg", base64_encode("[".implode("][", $obj->config)."]"));
		$obj->save_json($obj->fileconfig, $obj->config);
		$msg = "Config Saved!";
	}
}
elseif($page == 'cookie'){
	if(isset($_POST['cookie'])){
		$obj->save_cookies($_POST['type'], $_POST['cookie']);
		$msg = "{$_POST['type']} Cookie Added!";
	}
	elseif(isset($_GET['del'])){
		$obj->save_cookies($_GET['del'], "");
		$msg = "{$_GET['del']} Cookie Deleted!";
	}
}
elseif($page == 'account'){
	if(isset($_POST['account'])){
		if(empty($obj->acc[$_POST['type']])) {
			$obj->acc[$_POST['type']]['max_size'] = $obj->max_size_default;
			$obj->acc[$_POST['type']]['proxy'] = "";
			$obj->acc[$_POST['type']]['direct'] = false;
		}
		$obj->save_account($_POST['type'], $_POST['account']);
		$msg = "{$_POST['type']} Account Added!";
	}
	elseif(isset($_GET['del']) && isset($_GET['host'])){
		$acc = $obj->acc[$_GET['host']]['accounts'];
		unset($obj->acc[$_GET['host']]['accounts']);
		foreach($acc as $key=>$val){
			if($key == $_GET['del']) continue;
			$obj->acc[$_GET['host']]['accounts'][] = $val;
		}
		$obj->save_json($obj->fileaccount, $obj->acc);
		$msg = "{$_GET['host']} Account Deleted!";
	}
}
elseif($page == 'host'){
	if(isset($_POST['host'])){
		foreach($_POST['host'] as $key=>$val){
			$obj->acc[$key]['max_size'] = $val['max_size'];
			$obj->acc[$key]['proxy'] = $val['proxy'];
			$obj->acc[$key]['direct'] = (isset($val['direct']) && $val['direct'] == "ON" ? true : false);
		}
		ksort($obj->acc);
		$obj->save_json($obj->fileaccount, $obj->acc);
		$msg = "Host Setting Changed!";
	}
}
else{
	header("Location: index.php");
}

setcookie("msg", $msg);
header("Location: index.php?id=admin&page={$page}");
ob_end_flush();
?>
