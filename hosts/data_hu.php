<?php

class dl_data_hu extends Download {

	public function CheckAcc($cookie) {
		$data = $this->lib->curl("http://data.hu/user.php", $cookie, "");
		if (strpos($data, 'Prémium tagságod: ')) {
			return [true, $this->lib->cut_str($data,'Prémium tagságod: ','</')];
		}
		return [false, "accinvalid"];
	}

	public function Login($user, $pass){
		$data = $this->lib->curl("http://data.hu/", '', '');
		$post = $this->parseForm($this->lib->cut_str($data, 'login.php', '</form'));
		$post["username"] = $user;
		$post[$post["login_passfield"]] = $pass;
		$data = $this->lib->curl("http://data.hu/login.php", "", $post);
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}

	public function Leech($url) {
		$id = explode('/', $url)[3];
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if (preg_match('@http://.+.data.hu/get/[^"\'><\r\n\t]+@i', $data, $link)) {
			return $link[0];
		} elseif (strpos($data, "letöltése</title>") === false) $this->error("dead", true, false, 2);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Dile.hu Download Plugin
* Downloader Class By hogeunk
* 1st version by hogeunk [2016/02/24]
*/
?>