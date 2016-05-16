<?php
$using = isset($_COOKIE['using']) ? $_COOKIE['using'] : 'default';
$using = isset($_REQUEST['using']) ? $_REQUEST['using'] : $using;
setcookie('using', $using);
ob_start();
ob_implicit_flush(TRUE);
ignore_user_abort(0);
if (!ini_get('safe_mode')) set_time_limit(30);
define('vinaget', 'yes');
require_once('class.php');
$obj = new stream_get(); 
$obj->using = $using;
$obj->current_version = 104;
$obj->msg = false;
if (!empty($_COOKIE['msg'])) $obj->msg = htmlspecialchars($_COOKIE['msg']);
setcookie('msg', '');
$host = $obj->list_host;
$skin = "skin/{$obj->skin}";
error_reporting($obj->display_error ? E_ALL : 0);
if ($obj->Deny == false){
	require_once("{$skin}/function.php");
	if (isset($_POST['urllist'])) $obj->main();
	elseif (isset($_GET['infosv'])) showStat();
	elseif (!isset($_POST['urllist'])) include("{$skin}/index.php");
}
else include("{$skin}/login.php");
ob_end_flush();
?>