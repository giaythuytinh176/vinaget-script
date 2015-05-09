<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?hotfile\.com/#', $url)){
	$account = trim($this->get_account('hotfile.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("hotfile.com");
			if(!$cookie){
				$data = $this->curl("http://www.hotfile.com/login.php","","user=$user&pass=$pass");
				if(preg_match('/^Set-Cookie: auth=(.*?);/m', $data, $matches)) {
					$cookie = $matches[1];
					$this->save_cookies("hotfile.com",$cookie);
				}
			}
			$cookie = preg_replace("/(auth=|AUTH=|Auth=)/","",$cookie);
			$cookie = "auth=".$cookie;
			$this->cookie = $cookie;
			$page = $this->curl( $url,$cookie,"");
			if (preg_match('/ocation: *(.*)/i', $page, $redir))$link = trim($redir[1]);

			elseif(preg_match('%"(http\:\/\/hotfile\.com\/get\/.+)"%U', $page, $redir2)){
				$link = trim($redir2[1]);
				$page=$this->curl($link,$cookie,"");
				if(preg_match('/ocation: (.*)/', $page, $match)) $link=trim($match[1]);
				elseif (stristr($page,'This link has expired')) die(Tools_get::report($Original,"dead"));
			}
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($page,"removed due to copyright claim")) die(Tools_get::report($Original,"dead"));
			else {
				$cookie = ""; 
				$this->save_cookies("hotfile.com","");
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