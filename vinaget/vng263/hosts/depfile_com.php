<?php
if (preg_match('#^http://(www\.)?depfile\.com/#', $url)){
	$account = trim($this->get_account('depfile.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("depfile.com");
			if(!$cookie){
				$data = $this->curl($url,"sdlanguageid=2","login=login&loginemail=$user&loginpassword=$pass&submit=login&rememberme=on");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("depfile.com",$cookie);
			}
			$data =  $this->curl($url,$cookie,"");
			$cookie = $this->GetCookies($data);
			$this->cookie = $cookie;
			if (stristr($data,'A link for 24 hours')) {
				$data = $this->cut_str($data, "A link for 24 hours","<th>Download:</th>");
				if(preg_match('/value="(.*)"><\/td>/i', $data, $redir)){
					$link = trim($redir[1]);
					$size_name = Tools_get::size_name($link, $this->cookie);
					$filesize =  $size_name[0];
					$filename = $size_name[1];
					break;
				}
			}
			else if (stristr($data,'File was not found')) die(Tools_get::report($Original,"dead"));
			else {
				$cookie = "";
				$this->save_cookies("depfile.com","");
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