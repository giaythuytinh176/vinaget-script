<?php

class dl_doraupload_com extends Download {
	
	public function CheckAcc($cookie) {
		$data = $this->lib->curl("http://www.doraupload.com/?op=my_account", $cookie, "");
		if (stripos($data, '<font color=Red><font size="4">') !== false ) {
			$date = $this->lib->cut_str($data, '<font color=Red><font size="4">', '<');
			return array(true, "Expiry Date: ".$date);
		} else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass) {
		$data   = $this->lib->curl("https://www.doraupload.com/?op=login&redirect=&login=".$user."&password=".$pass, "", "");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
	public function Leech($url) {
		$id = explode('/', $url)[3];
		$url = 'http://www.doraupload.com/'. $id;
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if (stripos($data, 'The File Was Deleted') !== false) {
			$this->error("dead", true, false, 2);
		} else {
			$post = $this->parseForm(stristr($data, '</Form>', true));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if ($this->isredirect($data)) {
				return $this->redirect;
			} else $this->error("dead", true, false, 2);
		}
		return false;
	}
	
}

/*
 * Open Source Project
 * Vinaget by ..::[H]::..
 * Version: 2.7.0
 * doraupload.com Download Plugin
 * Downloader Class By hogeunk
 * 1st version by hogeunk [2015/12/31]
 */
?>