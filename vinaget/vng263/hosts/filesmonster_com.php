<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?filesmonster\.com/#', $url)){
	$account = trim($this->get_account('filesmonster.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if(!$cookie) $cookie = $this->get_cookie("filesmonster.com");
			if(!$cookie){
				$data = $this->curl("http://filesmonster.com/login.php","","act=login&user=".$user."&pass=".$pass."&login=Login");;
				$cookie = $this->GetCookies($data);
				if (stristr($cookie,'yab_logined=1')){
					$cookie =  "yab_logined=1;".$this->cut_str($cookie, "yab_logined=1;", "; yab_last_click");
					$this->save_cookies("filesmonster.com",$cookie);
				}
			}
			$this->cookie = $cookie;
			$data = $this->curl($url,$cookie,"");
			if(preg_match('%<a href="(.*)./"><span class="huge_button_green_left">%U', $data, $linkget)){
				$data = $this->curl($linkget[1],$cookie,"");
				if(preg_match('%get_link\("(.*)"\)%U', $data, $linkget)) {
					$data = $this->curl("http://filesmonster.com".$linkget[1],$cookie,"");
					$link = $this->cut_str($data, '"url":"', '"}');
					$link= str_replace("\\","",$link);
					$size_name = Tools_get::size_name($link, $this->cookie);
					$filesize = $size_name[0];
					$filename = $size_name[1];
					break;
				}
			}
			elseif (stristr($data,"File not found")) die(Tools_get::report($Original,"dead"));
			else {
				$cookie = ""; 
				$this->save_cookies("filesmonster.com","");
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