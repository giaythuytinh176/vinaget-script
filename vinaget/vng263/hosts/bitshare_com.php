<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?bitshare\.com/#', $url)){
	$account = trim($this->get_account('bitshare.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("bitshare.com");
			if(!$cookie){
				$data =  $this->curl("http://bitshare.com/login.html","","user=$user&password=$pass&rememberlogin=&submit=Login");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("bitshare.com",$cookie);
			}
			$this->cookie = $cookie;
			$data =  $this->curl($url,$cookie,"");
			if (stristr($data,'<h1>Error - File not available</h1>')) die(Tools_get::report($Original,"dead"));
			if(preg_match('/ocation: *(.*)/i', $data, $redir)){
				$link = trim($redir[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("bitshare.com","");
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