<?php
if (preg_match('#^(http|https)\:\/\/(www\.)?fileflyer\.com/#', $url)){
	$account = trim($this->get_account('fileflyer.com'));
	if (stristr($account,':')) list($user, $pass) = explode(':',$account);
	else $cookie = $account;
	if(empty($cookie)==false || ($user && $pass)){
		for ($j=0; $j < 2; $j++){
			if (stristr($cookie,'prekey')) {
				$cookie = preg_replace("/(;|prekey=)/","",$cookie);
				$data = $this->curl($url,'lang=en','');
				$cookies = $this->GetCookies($data);
				if(preg_match('%action="(.*)"%U', $data, $value)) {
					$loginurl = trim($value[1]);
					if(preg_match_all('/input type="hidden" name="(.*?)" id="(.*?)" value="(.*?)"/i', $data, $value)) {
						$max =count($value[1]);
						$post = "";
						for ($k=0; $k < $max; $k++){
							$value[3][$k] = str_replace("/","%2F",$value[3][$k]) ;
							$value[3][$k] = str_replace("+","%2B",$value[3][$k]) ;
							
							$post .= $value[1][$k].'='.$value[3][$k].'&';
						}
						$post .='SMSButton=Go&Password='.$cookie.'&CheckBoxPass=on&TextBox1=';
						$data =  $this->curl($loginurl,$cookies.';lang=en;',$post);
						$cookie = $this->GetCookies($data);
						$this->save_cookies("fileflyer.com",$cookie);
					}
				}
			}
			else {
				$cookie = $this->get_cookie("fileflyer.com");
				$data =  $this->curl($url,$cookie.';lang=en;','');
			}
			$this->cookie = $cookie;
			if (stristr($data,'Access enabled, download freely')) {
				if (preg_match('%class="dwlbtn" href="(.*)">Download%U', $data, $value)) {
					$link = trim($value[1]);
					$size_name = Tools_get::size_name($link, $this->cookie);
					$filesize =  $size_name[0];
					$filename = $size_name[1];
					break;
				}
			}
			else {
				$cookie = "";
				$this->save_cookies("cloudnator.com","");

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