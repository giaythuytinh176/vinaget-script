<?php

class dl_hzfile_asia extends Download {

	public function CheckAcc($cookie) {
		$data = $this->lib->curl("http://hzfile.asia/?op=my_account", "{$cookie} lang=english;", "");
		if (stripos($data, 'Premium account expire</TD><TD><b>')) {
			$until = $this->lib->cut_str($data, 'Premium account expire</TD><TD><b>', '<');
			$left  = $this->lib->cut_str($data, 'Traffic available today</TD><TD><b>', '<');
			return [true, "Expire: " . $until . "<br/>Traffic available today: " . $left];
		}
		return [false, "accinvalid"];
	}

	public function Login($user, $pass) {
		$data   = $this->lib->curl("http://hzfile.asia/", "lang=english", "op=login&redirect=&login={$user}&password={$pass}");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}

	public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, "lang=english; " . $this->lib->cookie, "");
		if (stripos($data, 'file was deleted')) {
			$this->error("dead", true, false, 2);
		} elseif (stripos($data, 'You have reached the download-limit')) {
			$this->error("LimitAcc");
		} elseif ($this->isredirect($data)) {
			return $this->redirect;
		} elseif (stripos($data, 'Download File')) {
			$post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form'));
			if (isset($post['password'])) {
				if (empty($pass)) {
					$this->error("reportpass", true, false, 2);
				} else {
					$post['password'] = $pass;
				}
			}
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if ($this->isredirect($data)) {
				return $this->redirect;
			} elseif (stripos($data, '"err">Wrong password')) {
				$this->error("reportpass", true, false, 2);
			}
		}
		return false;
	}

}
/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Hzfile.asia Download Plugin
* Downloader Class By hogeunk
* Made by hogeunk [2015/12/26]
* Fix and Update by hogeunk [2016/03/09]
*  - Support password
*  - Fix syntax error
*/
?>