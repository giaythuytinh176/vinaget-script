<?php
ob_start();
ob_implicit_flush (TRUE);
ignore_user_abort (0);
if( !ini_get('safe_mode') )set_time_limit(30);
define('vinaget', 'yes');
date_default_timezone_set('Asia/Jakarta');
if(empty($_REQUEST['link'])){
	echo "<center><br/><br/><br/><br/><br/><h2>DEBUG RESULT</h2></center>";
}
else{
	include("class.php");
	$obj = new stream_get();
	if(!empty($_REQUEST['proxy'])) $obj->proxy = $_REQUEST['proxy'];
	echo $obj->curl( $_REQUEST['link'], $_REQUEST['cookie'], $_REQUEST['post']);
}
ob_end_flush();
?>