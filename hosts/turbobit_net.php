<?php

class dl_turbobit_net extends Download {

	public function CheckAcc($cookie) {
		$data = $this->lib->curl("http://turbobit.net", "user_lang=en;".$cookie, "");
		if (strpos($data, 'Turbo access till')) {
			$bw = $this->lib->curl("http://turbobit.net/jy23sro5uer6.html", "user_lang=en;".$cookie, "");
			if(strpos($bw, 'Premium access is blocked')) {
				return array(true, "blockAcc");
			} else {
				return array(true, $this->lib->cut_str($data, "<span class='note'>","</span>"));
			}
		} elseif (strpos($data, 'limit of premium downloads')) {
			return array(true, "LimitAcc");
		} else {
			return array(false, "accinvalid");
		}
	}

	public function Login($user, $pass) {
		$data = $this->lib->curl("http://turbobit.net/user/login", "user_lang=en", "user[login]=".urlencode($user)."&user[pass]=".urlencode($pass)."&user[memory]=1&user[submit]=Login");
		$cookie = "user_lang=en;".$this->lib->GetCookies($data);
		return $cookie;
	}

	public function Leech($url) {
		if(strpos($url, "/download/free/")) {
			$gach = explode('/', $url);
			$url = "http://turbobit.net/{$gach[5]}.html";
		}
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		$this->save($this->lib->GetCookies($data));
		if(strpos($data,'site is temporarily unavailable') || strpos($data,'This document was not found in System') || strpos($data,'Please wait, searching file')) {
			$this->error("dead", true, false, 2);
		} elseif(strpos($data, 'limit of premium downloads')) {
			$this->error("LimitAcc");
		} elseif(strpos($data, 'Premium access is blocked')) {
			$this->error("blockAcc", true, false);
		} elseif(preg_match('@https?:\/\/turbobit\.net\/\/download\/redirect\/[^"\'><\r\n\t]+@i', $data, $link)) {
			return trim($link[0]);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Turbobit Download Plugin
* Downloader Class By [FZ]
* Fixed By djkristoph
* Fixed check account by giaythuytinh176 [28.7.2013]
* Fix small changes by hogeunk [4.3.2015]
* Fix small changes by hogeunk [2016/02/19]
*/
?>