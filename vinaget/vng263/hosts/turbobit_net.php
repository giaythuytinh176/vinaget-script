<?php
if (preg_match('#^http://(www\.)?turbobit\.net/#', $url)){
	$account = trim($this->get_account('turbobit.net'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("turbobit.net");
			if(!$cookie){
				$data = $this->curl("http://turbobit.net/user/login","user_lang=en","user[login]=$user&user[pass]=$pass&user[memory]=on&user[submit]=Login");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("turbobit.net",$cookie);
			}
			$this->cookie = $cookie;
			$data =  $this->curl($url,$cookie,"");
			if (preg_match('/ocation: (.*)/',$data,$match)) $link = trim($match[1]);
			elseif (stristr($data,'site is temporarily unavailable')) die(Tools_get::report($Original,"dead"));
			elseif (preg_match("%h1><a href='(.*)'><b>Download</b></a></h1>%U", $data, $redir2)) $link = trim($redir2[1]);
			$size_name = Tools_get::size_name($link, $cookie);
			if($size_name[0] <= 0) {
				$data = $this->curl($link,$cookie,"");
				if (preg_match('/ocation: (.*)/',$data,$match)) {
					$link = trim($match[1]);
					$size_name = Tools_get::size_name($link, $cookie);
					if($size_name[0] <= 0) {
						$data = $this->curl($link,$cookie,"");
						if (preg_match('/ocation: (.*)/',$data,$match)) $link = trim($match[1]);
					}
				}
			}
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				$filename = preg_replace("/;/","",$filename);
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("turbobit.net","");
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