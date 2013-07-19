<?php

class dl_mediafire_com extends Download {
	
	public function PreLeech($url){
		list($url, $pass) = $this->linkpassword($url);
		$url = str_replace("/view/", "/download/", $url);
		$url = str_replace("/edit/", "/download/", $url);
		$url = str_replace("/watch/", "/download/", $url);
		$this->url = $url;
		$page = $this->lib->curl($url,"","");
		$this->save($this->lib->GetCookies($page));
		if (isset($_POST['captcha']) && $_POST['captcha'] == 'reload') {
			$page = $this->lib->curl("http://api.recaptcha.net/challenge?k=6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe","","");
			if(preg_match("%challenge : '(.*)'%U", $page, $matches)) $this->error("captcha code '".trim($matches[1])."' rand '{$rand}'", true, false);
			else $this->error("Mediafire Authentication Required. Contact admin!", true, false);
		}
		elseif(!empty($_POST['recaptcha_challenge_field']) && !empty($_POST['recaptcha_response_field'])){
			$key = $_POST['recaptcha_challenge_field'];
			$value = $_POST['recaptcha_response_field'];
			$this->lib->curl($url,$this->lib->cookie,"recaptcha_challenge_field={$key}&recaptcha_response_field={$value}");
		}
	}
	
	public function Login($user, $pass){
		$post = array();
		$post['login_email'] = $user;
		$post['login_pass'] = $pass;
		$post['login_remember'] = true;
		$post['submit_login'] = "Log in to MediaFire";
		$page = $this->lib->curl("http://www.mediafire.com/dynamic/login.php?popup=1",$cookie,$post);
		$cookie = $this->lib->GetCookies($page);
		return $cookie;
	}
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$fileID = $this->exploder('/', $url, 4);
		if($pass) $this->lib->curl("http://www.mediafire.com/?{$fileID}",$this->lib->cookie,"downloadp=".$pass);
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if(stristr($data,"error.php")) $this->error("dead", true, false, 2);
		elseif(preg_match ( '/ocation: (.*)/', $data, $linkpre)) return trim ($linkpre[1]);
		elseif(stristr($data,'<div class="password_download_link"')) $this->error("notsupportpass", true, false);	
		elseif(stristr($data,'class="download_link"')) {
			$page = $this->lib->cut_str($data, 'class="download_link"', "output");
			if (preg_match('/(http.+)"/i', $page, $value)) return trim($value[1]);
		}
		elseif(stristr($data,"6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe")) {
			$page = $this->lib->curl("http://api.recaptcha.net/challenge?k=6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe","","");
			if(preg_match("%challenge : '(.*)'%U", $page, $matches)) $this->error("captcha code '".trim($matches[1])."' rand '{$rand}'", true, false);
			else $this->error("Mediafire Authentication Required. Contact admin!", true, false);
		}
		return false;
    }
	
	public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$fileID = $this->exploder('/', $url, 4);
		if($pass) $this->lib->curl("http://www.mediafire.com/?{$fileID}",$this->lib->cookie,"downloadp=".$pass);
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if(stristr($data,"error.php")) $this->error("dead", true, false, 2);
		elseif(preg_match ( '/ocation: (.*)/', $data, $linkpre)) return trim ($linkpre[1]);
		elseif(stristr($data,'<div class="password_download_link"')) $this->error("notsupportpass", true, false);	
		elseif(stristr($data,'class="download_link"')) {
			$page = $this->lib->cut_str($data, 'class="download_link"', "output");
			if (preg_match('/(http.+)"/i', $page, $value)) return trim($value[1]);
		}
		elseif(stristr($data,"6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe")) {
			$page = $this->lib->curl("http://api.recaptcha.net/challenge?k=6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe","","");
			if(preg_match("%challenge : '(.*)'%U", $page, $matches)) $this->error("captcha code '".trim($matches[1])."' rand '{$rand}'", true, false);
			else $this->error("Mediafire Authentication Required. Contact admin!", true, false);
		}
		elseif(stristr($data,"This file is temporarily unavailable because")) $this->error("File too big, only allowed 200MB", false, false, 2);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Mediafire Download Plugin 
* Downloader Class By [FZ]
*/
?>