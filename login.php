<?php
include("lib/logboost-api-php/LogboostAPI.php") ;
ob_start();
if( !ini_get('safe_mode') ){
	set_time_limit(60);
} 
error_reporting(0); 
ignore_user_abort(TRUE);
date_default_timezone_set('Asia/Jakarta');
$data = json_decode(file_get_contents("data/config.dat"), true);
$redirect_uri = isset($_GET['redirect_uri']) ? $_GET['redirect_uri'] : null ;
if ($_GET['go']=='logout') {
	setcookie("secureid", "owner", time());
	setcookie("accessmethod", "owner", time()) ;
} else if($_GET['method']=='freeaccess') {
	$login = true;
	setcookie("accessmethod","freeaccess",time()+3600*24*7);
} else if($_GET['method']=='logboost') {
	setcookie("accessmethod","logboost",time()+3600*24*7);
	$Logboost_clientSecret = $data['logboost_secret'] ;
	$Logboost_clientID = $data['logboost_client_id'] ;
	session_regenerate_id() ;
	session_start() ;
    $_SESSION['LOGBOOST'] = serialize(new LogboostSession($redirect_uri)) ;
    unserialize($_SESSION['LOGBOOST'])->openSession() ;
} else if((isset($_GET['code']) && isset($_GET['state']))) {
	$lbSession = unserialize($_SESSION['LOGBOOST']) ;
    $lbSession->handleSession() ;
    $_SESSION['LOGBOOST'] = null ;
    $_SESSION['LOGBOOST'] = serialize($lbSession) ;
    if($redirect_uri != null) {
        header('Location: '.$redirect_uri);
    } else {
        header('Location: index.php');
    }
} else {
	$login = false;
	$password = explode(", ", $data['password']);
	$password[] = $data['admin'];
	foreach ($password as $login_vng)
	if($_POST['secure'] == $login_vng){
		#-----------------------------------------------
		$file = "data/log.txt";	//	Rename *.txt
		$date = date('H:i:s Y-m-d');
		$entry  = sprintf("Passlogin=%s\n", $_POST["secure"]);
		$entry .= sprintf("IP: ".$_SERVER['REMOTE_ADDR']." | Date: $date\n");
		$entry .= sprintf("------------------------------------------------------------------------\n");
		$handle = fopen($file, "a+")
		or die('<CENTER><font color=red size=3>could not open file! Try to chmod the file "<B>'.$file.'</B>" to 666</font></CENTER>');
		fwrite($handle, $entry)
		or die('<CENTER><font color=red size=3>could not write file! Try to chmod the file "<B>'.$file.'</B>" to 666</font></CENTER>');
		fclose($handle);
		#-----------------------------------------------
		setcookie("secureid",md5($login_vng),time()+3600*24*7);
		$login = true;
	}
	if($login == false) die("<script>alert(\"Wrong password !\"); history.go(-1)</script>");
}

header("location:index.php");
ob_end_flush();
?>
