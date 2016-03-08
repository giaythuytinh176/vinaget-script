<?php

class dl_bytewhale_com extends Download {

	public function CheckAcc($cookie) {
		$data = $this->lib->curl("https://bytewhale.com/?op=my_account", "lang=english;".$cookie, "");
		if (stristr($data, 'manewcc">PREMIUM account</div>')) {
			$day = $this->lib->cut_str($data,'style="float:right;">','</div');
			return array(true, $day);
		}
		else return array(false, "accinvalid");
	}

	public function Login($user, $pass){
		$data = $this->lib->curl("https://bytewhale.com/", '', "op=login&redirect=&login={$user}&password={$pass}");
		$cookie = "lang=english;".$this->lib->GetCookies($data);
		return $cookie;
	}

	public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$id = explode('/', $url)[3];
		$url = 'https://bytewhale.com/'. $id;
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data, "> [0 MB]<")) $this->error("dead", true, false, 2);
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form'));
			$data = $this->lib->curl($url, $this->lib->cookie, http_build_query($post));
			if($this->isredirect($data)) return trim($this->redirect); 
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Bytewhale Download Plugin
* Downloader Class By hogeunk
* 1st version by hogeunk [2015/11/23]
* Support password and Update parse form by hogeunk [2016/02/14]
*/
?>