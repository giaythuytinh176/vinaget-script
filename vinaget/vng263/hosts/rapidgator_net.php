<?php
if (preg_match('#^http:\/\/(www.)?rapidgator\.net/#', $url)) {
	$account = trim($this->get_account('rapidgator.net'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	$maxacc = count($this->acc['rapidgator.net']['accounts']);
	if($maxacc > 0){
		for ($k=0; $k < $maxacc; $k++){
			for ($j=0; $j < 2; $j++){
				if(!$cookie) $cookie = $this->get_cookie("rapidgator.net");
				if(!$cookie){
					$account = trim($this->acc['rapidgator.net']['accounts'][$k]);
					if (stristr($account,':')) list($user, $pass) = explode(':',$account);
					$data = $this->curl("http://rapidgator.net/auth/login","","LoginForm[email]=$user&LoginForm[password]=$pass&LoginForm[rememberMe]=1");
					$cookie = $this->GetCookies($data);
					$this->save_cookies("rapidgator.net",$cookie);
				}
				$this->cookie = $cookie;
				$data = $this->curl($url,$cookie.';lang=en',"");
				if(preg_match ( '/ocation: (\/file\/.++)/', $data, $linkpre)) {
					$url1 = 'http://rapidgator.net'.trim ($linkpre[1]);
					$data = $this->curl($url1,$cookie.';lang=en',"");
					$data = $this->curl($url,$cookie.';lang=en',"");

				}
				if(stristr($data, "You have reached daily quota of downloaded")){
					if($k <$maxacc-1) {
						$cookie = '';
						$this->save_cookies("rapidgator.net","");
						break;
					}
					else die("<font color=red>Account out of bandwidth</font>");
				}
				
				if (stristr($data,'Account:&nbsp;<a href="/article/premium">Free</a>')) die('<font color=red>Not support with Account Free</font>');
				elseif (stristr($data,'File not found')) die(Tools_get::report($Original,"dead"));
				elseif(preg_match("%var premium_download_link = '(.*)';%U", $data, $matches)) $link = trim ($matches[1]);
				elseif(preg_match ( '/ocation: (.*)/', $data, $linkpre)) $link = trim ($linkpre[1]);
				if(isset($link) && stristr($link, 'http')) {
					$size_name = Tools_get::size_name($link, $this->cookie);
					if($size_name[0] > 200 ){
						$filesize =  round($size_name[0]/(1024*1024),2)." MB";
						$filename = $size_name[1];
						break;
					}
					else {
						$cookie = "";
						$this->save_cookies("rapidgator.net","");
					}
				}
				else {
					$link = '';
					$cookie = "";
					$this->save_cookies("rapidgator.net","");
				}
			}
			if($link) break;
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