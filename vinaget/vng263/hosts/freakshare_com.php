<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?freakshare\.com/#', $url)){
	$account = trim($this->get_account('freakshare.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("freakshare.com");
			if(!$cookie) {
				$data = $this->curl("http://freakshare.com/login.html","","user=$user&pass=$pass&submit=Login");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("freakshare.com",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"");
			if (preg_match('/ocation: (.*)/',$data,$match)) {
				$link = trim($match[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($data,"This file does not exist"))  die(Tools_get::report($Original,"dead"));
			else {
				$cookie = "";
				$this->save_cookies("freakshare.com","");
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