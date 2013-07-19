<?php
if (preg_match('#^http://(www\.)?share-online\.biz/#', $url)){
	$account = trim($this->get_account('share-online.biz'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("share-online.biz");
			if(!$cookie){
				$data = $this->curl("https://www.share-online.biz/user/login","","user=$user&pass=$pass&l_rememberme=1");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("share-online.biz",$cookie);		
			}
			$data = $this->curl($url,$cookie,"");
			$cookie = $this->GetCookies($data);
			$this->cookie = $cookie;
			$enlink = base64_decode($this->cut_str($data,'var dl="','";var file'));
			if (stristr($data,'The requested file is not available')) die(Tools_get::report($Original,"dead"));
			if (stristr($enlink,'share-online.biz')) $link = trim($enlink);
			$size_name = Tools_get::size_name($link, $cookie);
			if($size_name[0] <= 0) {
				$data = $this->curl($link,$cookie,"");
				if (preg_match('/ocation: (.*)/',$data,$match)) $link = trim($match[1]);
				else $link ="";
			}
			if($link){
				$link = str_replace(":80", "",$link);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("share-online.biz","");
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