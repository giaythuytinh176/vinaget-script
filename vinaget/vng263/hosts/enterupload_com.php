<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?enterupload\.com/#', $url)){
	$account = trim($this->get_account('enterupload.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		#==== Fix link ====#
		$gach = explode('/', $url);
		if (count($gach) > 3) $url = 'http://www.enterupload.com/' . $gach[3];
		#==== Fix link ====#
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("enterupload.com");
			if(!$cookie){
				$post['op'] = "login";
				$post['login'] = $user;
				$post['password'] = $pass;
				$post['x'] = rand(0,50);
				$post['y'] = rand(0,15);
				$data = $this->curl("http://www.enterupload.com/login.html","",$post);
				$cookie = $this->GetCookies($data);
				$this->save_cookies("enterupload.com",$cookie);
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
			else {
				$cookie = ""; 
				$this->save_cookies("enterupload.com","");
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