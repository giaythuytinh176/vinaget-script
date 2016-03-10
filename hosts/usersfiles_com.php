<?php

class dl_usersfiles_com extends Download {

	public function FreeLeech($url) {
		$data = $this->lib->curl($url,"lang=english","");
		$this->lib->cookie = $this->lib->GetCookies($data);
		if (strpos($data,'Download File')) {
			$post = $this->parseForm($data);
			$data = $this->lib->curl($url, $cookie, $post);
			if($this->isredirect($data)) return $this->redirect;
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
* Usersfiles.com Download Plugin
* Downloader Class By hogeunk
* 1st version by hogeunk [2015/12/28]
* Support link dead by hogeunk [2016/02/26]
*/
?>