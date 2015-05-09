<?php
if (preg_match('#^http://([a-z0-9]+)\.mediafire\.com/#', $url) || preg_match('#^http://mediafire\.com/#', $url)){
	$account = trim($this->get_account('mediafire.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	$password = "";
	if(strpos($url,"|")) {
		$linkpass = explode('|', $url); 
		$url = $linkpass[0]; $password = $linkpass[1];
	}
	if (isset($_POST['password'])) $password = $_POST['password'];
	#==== Fix link MF ====#
	$url = str_replace("download.php", "", $url);
	if(strpos($url,"www")==false) $url = str_replace("http://", "http://www.", $url);
	#==== Fix link MF ====#
	if (isset($_POST['captcha']) && $_POST['captcha'] == 'reload') {
		$page = $this->curl("http://api.recaptcha.net/challenge?k=6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe","","");
		if(preg_match("%challenge : '(.*)'%U", $page, $matches)) 
			echo 'captcha code \''.trim($matches[1]).'\' rand \''.$rand.'\'';
		else 
			echo '<font color=blue>'.$url.'</font> <font color=red>==&#9658; Authentication Required, pls contact admin@vinaget.us</font>';
		exit;
	}
	elseif(empty($_POST['recaptcha_challenge_field'])==FALSE && empty($_POST['recaptcha_response_field'])==FALSE){
		$key = $_POST['recaptcha_challenge_field'];
		$value = $_POST['recaptcha_response_field'];
		$data = $this->curl($url,"","recaptcha_challenge_field=$key&&recaptcha_response_field=$value");
	}
	elseif($password) $data = $this->curl($url,'',"downloadp=".$password);
	else $data = $this->curl($url,"","");
	if(stristr($data,"error.php")) die(Tools_get::report($Original,"dead"));
	elseif(preg_match ( '/ocation: (.*)/', $data, $linkpre))  $link = trim ($linkpre[1]);
	else if(stristr($data,"dh('');")) die($this->lang['reportpass']);
	elseif(stristr($data,"Copy file link to clipboard")) {
		$page = $this->cut_str($data, 'class="download_link"', "Follow MediaFire");
		if (preg_match('/(http.+)"/i', $page, $value)) $link = trim($value[1]);
	}
	if((empty($cookie)==false || ($user && $pass)) && empty($link)==true){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("mediafire.com");
			if(!$cookie){
				$page = $this->curl($url,"","");
				$cookie = $this->GetCookies($page);
				$post = array();
				$post['login_email'] = $user;
				$post['login_pass'] = $pass;
				$post['submit_login.x'] = rand(0,100);
				$post['submit_login.y'] = rand(0,20);
				$page = $this->curl("http://www.mediafire.com/dynamic/login.php",$cookie,$post);
				$cookie = $cookie . "; " . $this->GetCookies($page);
				$this->save_cookies("mediafire.com",$cookie);
			}
			$this->cookie = $cookie;
			if(empty($_POST['recaptcha_challenge_field'])==FALSE && empty($_POST['recaptcha_response_field'])==FALSE){
				$key = $_POST['recaptcha_challenge_field'];
				$value = $_POST['recaptcha_response_field'];
				$page = $this->curl($url,$cookie,"recaptcha_challenge_field=$key&&recaptcha_response_field=$value");
			}
			else {
				if($password) $post = "downloadp=".$password;
				else $post = "";
				$page = $this->curl($url,$cookie,$post);
			}
			if(preg_match ( '/ocation: (.*)/', $page, $linkpre)) $link = trim ( $linkpre[1] );
			elseif(stristr($page,"dh('');")) die($this->lang['reportpass']);
			if($link) break;
			else {
				$cookie = "";
				$this->save_cookies("mediafire.com","");
			}
		}
	}
	if($link) {
		$size_name = Tools_get::size_name($link, $cookie);
		if($size_name[0] > 0 ){
			$filesize =  $size_name[0];
			$filename = $size_name[1];
		}
	}
	else {
		if(stristr($data,"6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe")) {
			$page = $this->curl("http://api.recaptcha.net/challenge?k=6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe","","");
			if(preg_match("%challenge : '(.*)'%U", $page, $matches)) 
				echo 'captcha code \''.trim($matches[1]).'\'';
			else echo '<font color=blue>'.$url.'</font> <font color=red>==&#9658; Authentication Required, pls contact admin@vinaget.us</font>';
			exit;
		}
		elseif(stristr($data,"This file is temporarily unavailable because")) {
			die('<center><b><font color=#00CC00>'.$url.'</font> <font color=red> ==&#9658; File too big ! </font><font color=#3399FF>when allowed only</font> <font color=#FFCC00>200 MB</font></b></center>');
		}
	}
}


/*
* Home page: http://vinaget.us
* Blog:	http://blog.vinaget.us
* Script Name: Vinaget 
* Version: 2.6.3
* Created: ..:: [H] ::..
*/
?>