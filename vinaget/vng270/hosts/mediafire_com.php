<?php

class dl_mediafire_com extends Download {
	
	public function PreLeech($url) {
		if(stristr($url, "/folder/")) $this->error("Not Support Folder", true, false, 2);
		if(stristr($url, "mediafire.com/?")) {
			$ex = explode("?", $url);
			$url = "http://www.mediafire.com/download/".$ex[1];
		}
		if(!stristr($url, "www")) {
			$ex = explode("mediafire.com", $url);
			$url = "http://www.mediafire.com".$ex[1];
		}
		$url = str_replace("/view/", "/download/", $url);
		$url = str_replace("/edit/", "/download/", $url);
		$url = str_replace("/watch/", "/download/", $url);
		$url = str_replace("/listen/", "/download/", $url);
		$url = str_replace("/play/", "/download/", $url);
	}
	
    public function CheckAcc($cookie){
		$data = $this->lib->curl("https://www.mediafire.com/myaccount/billinghistory.php", $cookie, "");
		if(stristr($data, 'Billed on date of purchase')) return array(true, "Until ".$this->lib->cut_str($data, '<div> <div class="lg-txt">','</div>'));
		elseif(stristr($data, 'You are not currently and never have been a MediaPro')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}	
	
	public function Login($user, $pass){
		$page1 = $this->lib->curl("http://www.mediafire.com/", "", "");
		$cookies = $this->lib->GetCookies($page1);
		$page2 = $this->lib->curl("http://www.mediafire.com/dynamic/login.php?popup=1", $cookies, "login_email={$user}&login_pass={$pass}&login_remember=1&submit_login=Log in to MediaFire");
		$cookie = "{$cookies};{$this->lib->GetCookies($page2)}";
		return $cookie;
	}
/*
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
    }	 */
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$fileID = $this->exploder('/', $url, 4);
		if($pass) $this->lib->curl("http://www.mediafire.com/?{$fileID}",$this->lib->cookie,"downloadp=".$pass);
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if(stristr($data,'Please enter password to unlock this file')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,"error.php")) $this->error("dead", true, false, 2);
		elseif(!preg_match('@http:\/\/(?:(?:[\d.]+)|(:?(download\d+\.mediafire\.com)))\/[^"\'><\r\n\t]+@i', $data, $giay))
		$this->error("notfound", true, false, 2);	
		else	
		return trim($giay[0]);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Mediafire Download Plugin 
* Downloader Class By [FZ]
* Add check account by giaythuytinh176 [19.8.2013]
* Special thanks to Rapid61@rapidleech.com for your MediaFire account.
*/
?>