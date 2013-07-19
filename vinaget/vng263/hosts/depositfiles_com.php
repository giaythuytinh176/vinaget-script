<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?depositfiles\.com/#', $url)){
	$maxacc = count($this->acc['depositfiles.com']['accounts']);
	if($maxacc > 0){
		$password = "";
		if(strpos($url,"|")) {
			$linkpass = explode('|', $url); 
			$url = $linkpass[0]; $password = $linkpass[1];
		}
		if (isset($_POST['password'])) $password = $_POST['password'];
		if($password) $post = "file_password=".$password;
		else $post = "";
		for ($k=0; $k < $maxacc; $k++){
			$account = $this->acc['depositfiles.com']['accounts'][$k];
			if (stristr($account,':')) list($user, $pass) = explode(':',$account);
			else $cookie = $account;
			if(empty($cookie)==false || ($user && $pass)){
				$url=str_replace("depositfiles.com/files","depositfiles.com/en/files",$url);
				for ($j=0; $j < 2; $j++){
					if(!$cookie) $cookie = $this->get_cookie("depositfiles.com");
					if(!$cookie){
						$page=$this->curl("http://depositfiles.com/login.php?return=%2F","lang_current=en","go=1&login=$user&password=$pass");
						$cookie = $this->GetCookies($page);
						$this->save_cookies("depositfiles.com",$cookie);
					}
					$page=$this->curl($url,$cookie.';lang_current=en;',$post);
					$cookies = $this->GetCookies($page);
					$this->cookie = $cookies;
					if(stristr($page, "You have exceeded the")){
						if($k <$maxacc-1) {
							$cookie = '';
							$this->save_cookies("depositfiles.com","");
							continue;
						}
						else die("<font color=red>Account out of bandwidth</font>");
					}
					elseif(strpos($page,'Please, enter the password for this file')) die($this->lang['reportpass']);
					elseif (preg_match('/ocation: *(.*)/i', $page, $redir))$link = trim($redir[1]);
					elseif (preg_match('%"(http:\/\/.+depositfiles\.com/auth.+)" onClick="%U', $page, $redir2))
						$link = trim($redir2[1]);
					elseif(stristr($page, "Such file does not exist")) die(Tools_get::report($Original,"dead"));
					if($link){
						$size_name = Tools_get::size_name($link, $this->cookie);
						$filesize = $size_name[0];
						$filename = $size_name[1];
						break;
					}
					else {
						$cookie = ""; 
						$this->save_cookies("depositfiles.com","");
					}
				}
				if($link) break;
			}
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