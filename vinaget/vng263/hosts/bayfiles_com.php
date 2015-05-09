<?php
if (preg_match('#^http://(www\.)?bayfiles\.com/#', $url)){
	$account = trim($this->get_account('bayfiles.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("bayfiles.com");
			if(!$cookie){
				$data = $this->curl("http://bayfiles.com/ajax_login",'',"action=login&username=$user&password=$pass&next=%252F&=");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("bayfiles.com",$cookie);
			}
			$data =  $this->curl($url,$cookie,"");
			$cookie = $this->GetCookies($data);
			$this->cookie = $cookie;
			if (stristr($data,'file could not be found')) die(Tools_get::report($Original,"dead"));
			elseif(preg_match('/class="highlighted-btn" href="(.*)"/i', $data, $redir)){
				$link = trim($redir[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = '';
				$this->save_cookies("bayfiles.com","");
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