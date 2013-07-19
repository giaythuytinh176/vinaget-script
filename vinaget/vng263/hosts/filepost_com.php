<?php
if (preg_match('#^(http|https)://(www\.)?filepost\.com/#', $url)){
	$account = trim($this->get_account('filepost.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		#==== Fix link ====#
		$gach = explode('/', $url);
		if (count($gach) > 5) $url = 'http://filepost.com/files/' . $gach[4];
		#==== Fix link ====#	
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("filepost.com");
			if(!$cookie){
				$data = $this->curl("http://filepost.com/general/login_form/","","email=$user&password=$pass");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("filepost.com",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"");
			if (preg_match("%download_file\('(http:\/\/.+filepost.com/get_file/.+)'\)%U", $data, $match)){
				$link = trim($match[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($data,'File not found')) die(Tools_get::report($Original,"dead"));
			else {
				$cookie = ""; 
				$this->save_cookies("filepost.com","");
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