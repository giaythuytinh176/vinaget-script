<?php

class dl_gigapeta_com extends Download {

	public function CheckAcc($cookie) {
		$data = $this->lib->curl("http://gigapeta.com/", "lang=us; ".$cookie, "");
		if (strpos($data, 'You have <b>premium</b> account') !== false) {
			$day = trim($this->lib->cut_str($data,'You have <b>premium</b> account','</p>'));
			return array(true, $day);
		}elseif(strpos($data, '>logout<') !== false) return array(false, "accfree");
		else return array(false, "accinvalid");
	}

	public function Login($user, $pass){
		$data = $this->lib->curl('http://gigapeta.com/',"lang=us","");
		$cookie = $this->lib->GetCookies($data);
		$token = $this->lib->cut_str($data, 'value="', '"');
		$data = $this->lib->curl('http://gigapeta.com',$cookie,"auth_login={$user}&auth_passwd={$pass}&auth_token={$token}");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}

	public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "download=");
		if($this->isredirect($data)) {
			return trim($this->redirect);
		} else {
			$this->error("dead", true, false, 2);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Gigapeta Download Plugin
* Downloader Class By hogeunk
* Made by hogeunk [2015/11/27]
*/
?>