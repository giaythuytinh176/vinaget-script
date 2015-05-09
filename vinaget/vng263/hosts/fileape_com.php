<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?fileape\.com/#', $url)){
	$account = trim($this->get_account('fileape.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("fileape.com");
			if(!$cookie){
				$data = $this->curl("http://fileape.com/?act=login","","username=$user&password=$pass");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("fileape.com",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"");
			if(preg_match('/ocation: *(.*)/i', $data, $match)){
				$link = trim($match[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($data,"This file is either temporarily unavailable or does not exist")) die(Tools_get::report($Original,"dead"));
			else {
				$cookie = ""; 
				$this->save_cookies("fileape.com","");
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