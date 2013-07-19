<?php
error_reporting (E_ALL);
define('vinaget', 'yes');
include("class.php");
function check_account($host,$account){
	global $obj;
	foreach ($obj->acc[$host]['accounts'] as $value)
		if ($account == $value) return true; 
	return false;
}
if (empty($_POST["accounts"])==false) {
	$obj = new stream_get();
	$type = $_POST['type'];

	$_POST["accounts"] = str_replace(" ","",$_POST["accounts"]);
	$account = trim($_POST['accounts']);
	$donate = false;
	if($type == "rapidshare"){
		if(check_account("rapidshare.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data = $obj->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi","","sub=getaccountdetails&withcookie=1&withpublicid=1&login=".$user."&cbf=RSAPIDispatcher&cbid=2&password=".$pass);
			if(strpos($data,'Login failed'))
				die("false");
			else {
				$cookie  =  $obj->cut_str($data, "ncookie=","\\n");
			}
		}
		else $cookie = $account;
		if(check_account("rapidshare.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(enc=|Enc=|ENC=)/","",$cookie);
		$data =  $obj->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi","","sub=getaccountdetails&withcookie=1&withpublicid=1&withsession=1&cookie=".$cookie."&cbf=RSAPIDispatcher&cbid=1");
		if(preg_match('/billeduntil=([0-9]+)/', $data, $matches)) {
			if (time() < $matches[1]) { 
				$obj->acc["rapidshare.com"]['accounts'][] = $account;
				$donate = true;
			}
		}
	}
################################## DONATE ACC rapidshare.com ##################################################################	

################################## DONATE ACC bitshare.com ####################################################################
	elseif($type == "bitshare"){
		if(check_account("bitshare.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data=$obj->curl("http://bitshare.com/login.html","","user=$user&password=$pass&rememberlogin=&submit=Login");
			if(strpos($data,"Click here to login"))
				die("false");
			else {
				$cookie = $obj->GetCookies($data);
			}
		}
		else $cookie = $account;
		if(check_account("bitshare.com",$cookie)==true) die("false");
		$data = $obj->curl("http://bitshare.com/myaccount.html",$cookie,"");
		if(strpos($data,'Premium  <a href="http://bitshare.com/myupgrade.html">Extend</a>')) {
			$obj->acc["bitshare.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC bitshare.com ####################################################################

################################## DONATE ACC hotfile.com #####################################################################
	elseif($type == "hotfile"){
		if(check_account("hotfile.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data = $obj->curl("http://www.hotfile.com/login.php","","user=$user&pass=$pass");
			if(strpos($data,"Bad username/password combination"))
				die("false");
			else {
				preg_match('/^Set-Cookie: auth=(.*?);/m', $data, $matches);
				$cookie = $matches[1];
			}
		}
		else $cookie = $account;
		if(check_account("hotfile.com",$cookie)==true) die("false");
		$cookie = preg_replace("/(auth=|AUTH=|Auth=)/","",$cookie);
		$ch = @curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://hotfile.com/myaccount.html");
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIE, "auth=$cookie");
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
		$data = curl_exec( $ch);
		curl_close($ch); 
		if(preg_match('%<p>Premium until: <span class="rightSide">(.+) <b>%U', $data, $matches)) {
			$obj->acc["hotfile.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC hotfile.com #####################################################################

################################## DONATE ACC depositfiles.com ################################################################
	elseif($type == "depositfiles"){
		if(check_account("depositfiles.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$data=$obj->curl("http://depositfiles.com/login.php?return=%2F","lang_current=en","go=1&login=$user&password=$pass");
			if(strpos($data,"Your password or login is incorrect"))
				die("false");
			else {
				$cookie = $obj->GetCookies($data);
			}
		}
		else $cookie = $account;
		if(check_account("depositfiles.com",$cookie)==true) die("false");
		$data = $obj->curl("http://depositfiles.com/gold/payment_history.php",$cookie.';lang_current=en;',"");
		if(strpos($data,"You have Gold access until")) {
			$obj->acc["depositfiles.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC depositfiles.com ################################################################

################################## DONATE ACC oron.com ########################################################################
	elseif($type == "oron"){
		if(check_account("oron.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$page = $obj->curl("http://oron.com/login","lang=english","login=$user&password=$pass&op=login");
			if(strpos($page,"Incorrect Login or Password"))
				die("false");
			else {
				$cookie = $obj->GetCookies($page);
			}
		}
		else $cookie = $account;
		if(check_account("oron.com",$cookie)==true) die("false");
		$data = $obj->curl("http://oron.com/?op=my_account",$cookie,"");  
		if(strpos($data,'Premium Account expires')) {
			$obj->acc["oron.com"]['accounts'][] = $account;
			$donate = true;
		}
	}

################################## DONATE ACC oron.com ########################################################################

################################## DONATE ACC uploading.com ###################################################################
	elseif($type == "uploading"){
		if(check_account("uploading.com",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$tid = str_replace(".","12",microtime(true));
			$page = $obj->curl("http://uploading.com/general/login_form/?ajax","","email=$user&password=$pass&remember=on&back_url=http%3A%2F%2Fuploading.com%2F");
			if(strpos($page,"password combination"))
				die("false");
			else {
				$cookie = $obj->GetCookies($page);
			}
		}
		else $cookie = $account;
		if(check_account("uploading.com",$cookie)==true) die("false");
		$data = $obj->curl("http://uploading.com/profile/",$cookie,"");  
		if(strpos($data,'<dd>Premium</dd>')) {
			$obj->acc["uploading.com"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC uploading.com ###################################################################

################################## DONATE ACC uploaded.to #####################################################################
	elseif($type == "uploaded"){
		if(check_account("uploaded.to",$account)==true) die("false");
		if (stristr($account,':')) {
			list($user, $pass) = explode(':', $account);
			$page = $obj->curl("http://uploaded.net/io/login",'',"id=$user&pw=$pass");
			if(strpos($page,"password combination"))
				die("false");
			else {
				$cookie = $obj->GetCookies($page);
			}
		}
		else $cookie = $account;
		if(check_account("uploaded.to",$cookie)==true) die("false");
		$data = $obj->curl("http://uploaded.net",$cookie,"");  
		if(strpos($data,'<em>Premium</em>')) {
			$obj->acc["uploaded.to"]['accounts'][] = $account;
			$donate = true;
		}
	}
################################## DONATE ACC uploaded.to #####################################################################

################################## savve account  #############################################################################
	if($donate == true && is_array($obj->acc) && count($obj->acc) > 0) {
		$str = "<?php";
		$str .= "\n";
		$str .= "\n\$this->acc = array(";
		$str .= "\n";
		$str .= "# Example: 'accounts'	=> array('user:pass','cookie'),\n";
		$str .= "# Example with letitbit.net: 'accounts'    => array('user:pass','cookie','prekey=xxxx'),\n";
		$str .= "\n";
		foreach ($obj->acc as $host => $accounts) {
			$str .= "\n	'".$host."'		=> array(";
			$str .= "\n								'max_size'	=> ".($accounts['max_size']?$accounts['max_size']:1024).",";
			$str .= "\n								'accounts'	=> array(";
			foreach ($accounts['accounts'] as $acc) {
				$str .= "'".$acc."',";
			}
			$str .= "),";
			$str .= "\n							),";
			$str .= "\n";
		}
		$str .= "\n);";
		$str .= $obj->max_size_other_host ? "\n\$this->max_size_other_host = ".$obj->max_size_other_host.";" : "\n\$this->max_size_other_host = 1024;";
		$str .= "\n";
		$str .= "\n?>";
		$accountPath = "account.php";
		$CF = fopen ($accountPath, "w")
		or die('<CENTER><font color=red size=3>could not open file! Try to chmod the file "<B>account.php</B>" to 666</font></CENTER>');
		fwrite ($CF, $str)
		or die('<CENTER><font color=red size=3>could not write file! Try to chmod the file "<B>account.php</B>" to 666</font></CENTER>');
		fclose ($CF); 
		@chmod($accountPath, 0666);

		echo "true";
	}
	else echo "false";
################################## savve account  #############################################################################

}
/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Description: 
	- Vinaget is script generator premium link that allows you to download files instantly and at the best of your Internet speed.
	- Vinaget is your personal proxy host protecting your real IP to download files hosted on hosters like RapidShare, megaupload, hotfile...
	- You can now download files with full resume support from filehosts using download managers like IDM etc
	- Vinaget is a Free Open Source, supported by a growing community.
* Code LeechViet by VinhNhaTrang
* Developed by ..:: [H] ::..

*/
?>