<?php
if (preg_match('#^http://([a-z0-9]+)\.uploading\.com/#', $url) || preg_match('#^http://uploading\.com/#', $url)){
	$account = trim($this->get_account('uploading.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			//$tid = str_replace(".","12",microtime(true));
			if(!$cookie) $cookie = $this->get_cookie("uploading.com");
			if(!$cookie){
				$page=$this->curl("http://uploading.com/general/login_form/?ajax","","email=$user&password=$pass");
				$cookie =  $this->GetCookies($page);
				$this->save_cookies("uploading.com",$cookie);
			}
			$this->cookie = $cookie;
			$page=$this->curl($url,$cookie,"");
			if (stristr($page,'file not found')) die(Tools_get::report($Original,"dead"));
			if(preg_match('#ocation: (.+)?\r\n#U', $page, $match)) $link = trim($match[1]);
			elseif(strpos($page,"Your account premium traffic has been limited")) die($this->lang['outofbw']);
			else {
				$code = trim( $this->cut_str( $page, 'code: "', '",' ) );
				$page=$this->curl("http://uploading.com/files/get/?ajax",$cookie,"code=$code&action=get_link");
				$link =str_replace("\\","",$this->cut_str($page,'answer":{"link":"','"'));
			}
			if($link){
				$size_name = Tools_get::size_name($link, $this->cookie);
				$filesize = $size_name[0];
				$filename = $size_name[1];
				break;
			}
			else {
				$cookie = "";
				$this->save_cookies("uploading.com","");
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