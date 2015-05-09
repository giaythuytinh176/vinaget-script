<?php
if (preg_match('#^http://d01.megashares\.com/#', $url)){
	$account = trim($this->get_account('megashares.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("megashares.com");
			if(!$cookie){
				$data = $this->curl("http://d01.megashares.com/myms_login.php",'',"httpref=&mymslogin_name=$user&mymspassword=$pass&myms_login=Login");
				$cookie = $this->GetCookies($data);
				$this->save_cookies("megashares.com",$cookie);
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"");
			if (preg_match('/ocation: (.*)/',$data,$match)) $link = trim($match[1]);
			else  {
				preg_match('%(http:\/\/.+megashares\.com/.+)"><img style="margin%U', $data, $redir2);
				$link = trim($redir2[1]);
			}
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			elseif (stristr($data,"Invalid link")) die(Tools_get::report($Original,"dead"));
			else {
				$cookie = "";
				$this->save_cookies("megashares.com","");
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