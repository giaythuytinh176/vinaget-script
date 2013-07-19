<?php
// Plugin : Lumfile.com [FZ]
if (preg_match('#^http://www.lumfile.com/#', $url) || preg_match('#^http://lumfile.com/#', $url)){
	$account = trim($this->get_account('lumfile.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("lumfile.com");
			if(!$cookie) {
				$data = $this->curl('http://lumfile.com/', 'lang=english', 'op=login&login='.urlencode($user).'&password='.$pass);
				$cookie = $this->GetCookies($data);
				$this->save_cookies("lumfile.com",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"");
			if (preg_match('@Location: (http(s)?:\/\/[^\r\n]+)@i', $data, $dl)){
				$link = trim($dl[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;			
			}	
			else{
				die ("Cannot Get Link");
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