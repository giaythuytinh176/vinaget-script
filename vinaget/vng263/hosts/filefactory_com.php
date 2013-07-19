<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?filefactory\.com/#', $url)){
	$account = trim($this->get_account('filefactory.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("filefactory.com");
			if(!$cookie){
				$post["email"] = $user;
				$post["password"] = $pass;
				$post['redirect'] = $url;
				$page=$this->curl($url,"",$post);
				$cookie = $this->GetCookies($page);
				$this->save_cookies("filefactory.com",$cookie);
			}
			$this->cookie = $cookie;
			$page=$this->curl($url,$cookie,""); 
			if (preg_match('/location: *(.*)/i', $page, $redir))$link = trim($redir[1]);
			elseif(preg_match('%(http:\/\/.+filefactory\.com/dlp/.+)">Download with FileFactory Premium%U', $page, $redir2)) $link = trim($redir2[1]);
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($page,"This error is usually caused by requesting a file that does not exist")) die(Tools_get::report($Original,"dead"));
			else {
				$cookie = ""; 
				$this->save_cookies("filefactory.com","");
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