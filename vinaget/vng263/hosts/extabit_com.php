<?php
if (preg_match('#^http://(www\.)?extabit\.com/#', $url)){
	$account = trim($this->get_account('extabit.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("extabit.com");
			if(!$cookie){
				$post = array();
				$post['email'] = $user;
				$post['pass'] = $pass;
				$post['auth_submit_login.x'] = rand(5,70);
				$post['auth_submit_login.y'] = rand(3,20);
				$post['remember'] = "1";
				$data = $this->curl("http://extabit.com/login.jsp","language=en",$post);
				$cookie = $this->GetCookies($data);
				$this->save_cookies("extabit.com",$cookie);		
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"");
			if (stristr($data,'File is temporary unavailable')) die(Tools_get::report($Original,"dead"));
			else if (preg_match('/ocation: (.*)/',$data,$match)) {
				$link = trim($match[1]);
				$size_name = Tools_get::size_name($link, $this->cookie);
				if($size_name[0] <= 0) {
					$data = $this->curl($link,$cookie,"");
					if (stristr($data,'File is temporary unavailable')) die(Tools_get::report($Original,"dead"));
					elseif (preg_match('/ocation: (.*)/',$data,$match)) $link = trim($match[1]);
					elseif (preg_match('%id="download-file-btn" href="(.*)" onClick%U', $data, $redir2)) $link = trim($redir2[1]);
				}
			}
			elseif (preg_match('%id="download-file-btn" href="(.*)" onClick%U', $data, $redir2)) $link = trim($redir2[1]);
			if(stristr($link,'http')){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$link = '';
				$cookie = "";
				$this->save_cookies("extabit.com","");
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