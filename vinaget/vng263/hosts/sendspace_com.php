<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?sendspace\.com/#', $url)){
	$account = trim($this->get_account('sendspace.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("sendspace.com");
			if(!$cookie){
				$data = 
				$data = $this->curl("http://www.sendspace.com/login.html","","remember=on&action=login&submit=login&username=$user&password=$pass");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("sendspace.com",$cookie);
			}
			$data =  $this->curl($url,$cookie,"");
			$cookie = $this->GetCookies($data);
			$this->cookie = $cookie;
			if (preg_match('/location: *(.*)/i', $data, $redir))$link = trim($redir[1]);
			elseif(preg_match('%<a id="download_button" href="(.*)" onclick%U', $data, $redir2)) $link = trim($redir2[1]);
			elseif (stristr($data,"the file you requested is not available")) die(Tools_get::report($Original,"dead"));
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("sendspace.com","");
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