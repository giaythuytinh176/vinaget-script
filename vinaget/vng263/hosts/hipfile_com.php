<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?hipfile\.com/#', $url)){
	$account = trim($this->get_account('hipfile.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("hipfile.com");
			if(!$cookie){
				$post = array();
				$post['login'] = $user;
				$post['password'] = $pass;
				$x = rand(0,30);
				$y = rand(0,20);
				$data =  $this->curl("http://hipfile.com/","",'op=login&redirect=http%3A%2F%2Fhipfile.com%2F&login='.$user.'&password='.$pass.'&x='.$x.'&y='.$y);
				$cookie = $this->GetCookies($data);
				$this->save_cookies("hipfile.com",$cookie);
			}
			$this->cookie = $cookie;
			$data =  $this->curl($url,$cookie,"");
			if (stristr($data,'<b>File Not Found</b>')) die(Tools_get::report($Original,"dead"));
			if(preg_match('/ocation: *(.*)/i', $data, $redir)){
				$link = trim($redir[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("hipfile.com","");
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