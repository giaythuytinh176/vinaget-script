<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?crocko\.com/#', $url)){
	$account = trim($this->get_account('crocko.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		//==== Fix link ====
		$gach = explode('/', $url);
		if (count($gach)> 4) $url = 'http://www.crocko.com/' . $gach[3];
		//==== Fix link ====	
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("crocko.com");
			if(!$cookie){
				$post["login"]=$user;
				$post["password"]=$pass;
				$page = $this->curl('http://www.crocko.com/accounts/login',"",$post);
				$cookie = $this->GetAllCookies($page);
				$this->save_cookies("crocko.com",$cookie);
			}
			$this->cookie = $cookie;
			$page=$this->curl($url,$cookie,"");
			if(preg_match('/ocation: (.*)/', $page, $match)){
				$link = trim($match[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($page,'Requested file is deleted')) die(Tools_get::report($Original,"dead"));
			else $this->save_cookies("crocko.com","");
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