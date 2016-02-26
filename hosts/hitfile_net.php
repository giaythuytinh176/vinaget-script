<?php

class dl_hitfile_net extends Download {

	public function CheckAcc($cookie) {
		$data = $this->lib->curl("http://hitfile.net/", "user_lang=en;".$cookie, "");
		if (strpos($data, "Account: <b>free</b>")) {
			return array(false, "accfree");
		} elseif (strpos($data, "Account: <b>premium</b>")) {
			$day = $this->lib->cut_str($data,"<a href='/premium'>",'<');
			return array(true, $day);
		}
		else return array(false, "accinvalid");
	}

	public function Login($user, $pass){
		$data = $this->lib->curl("http://hitfile.net/user/login", "user_lang=en", "user[login]={$user}&user[pass]={$pass}&user[memory]=1&user[submit]=Login");
		$cookie = "lang=en;".$this->lib->GetCookies($data);
		return $cookie;
	}

	public function Leech($url) {
		if(strpos($url, "/download/free/")) {
			$gach = explode('/', $url);
			$url = "http://hitfile.net/{$gach[5]}.html";
		}
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		$this->save($this->lib->GetCookies($data));
		if(stripos($data,'site is temporarily unavailable') || stripos($data,'This document was not found in System') || stripos($data,'Please wait, searching file')) {
			$this->error("dead", true, false, 2);
		} elseif(stripos($data, 'limit of premium downloads')) {
			$this->error("LimitAcc");
		} elseif(stripos($data, 'Premium access is blocked')) {
			$this->error("blockAcc", true, false);
		} elseif(preg_match('@https?:\/\/hitfile\.net\/\/download\/redirect\/[^"\'><\r\n\t]+@i', $data, $link)) {
			return trim($link[0]);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Hitfile Download Plugin
* Downloader Class By hogeunk
* 1st version by hogeunk [2015/11/17]
* Fixed for dead file by hogeunk [2015/11/19]
* Support account that is disable direct download by hogeunk [2016/02/23]
* Fix account check by hogeunk [2016/02/26]
*/
?>