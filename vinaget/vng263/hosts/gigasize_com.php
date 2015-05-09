<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?gigasize\.com/#', $url)){
	$account = trim($this->get_account('gigasize.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("gigasize.com");
			if(!$cookie){
				$data =  $this->curl("http://www.gigasize.com/signin","","email=$user&password=$pass");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("gigasize.com",$cookie);
			}
			$this->cookie = $cookie;
			$gach = explode('/', $url);
			$fileId = $gach[4];
			$token = $this->curl("http://www.gigasize.com/formtoken",$cookie,"");
			$data = $this->curl("http://www.gigasize.com/getoken",$cookie,"fileId=$fileId&token=".$token);
			if(preg_match('%getcgi(.*)"%U', $data, $redir2)){
				$pre = "http://www.gigasize.com/getcgi/".substr($redir2[1], 2, -13)."/".$fileId;
				$data =  $this->curl($pre,$cookie,"");
				if(preg_match ('/ocation: (.*)/', $data, $linkpre)){
					$link =trim($linkpre[1]);
					$link = str_replace("https","http",$link) ;
					$size_name = Tools_get::size_name($link, $this->cookie);
					$filesize = $size_name[0];
					$filename = $size_name[1];
					break;
				}
			}
			elseif (stristr($data,"Download error")|| stristr($data,"has been removed because we have received a legitimate complaint")) {
				die(Tools_get::report($Original,"dead"));
			}
			else {
				$cookie = ""; 
				$this->save_cookies("gigasize.com","");
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