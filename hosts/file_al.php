<?php

class dl_file_al extends Download {

	public function CheckAcc($cookie) {
		$data = $this->lib->curl("https://file.al/?op=my_account", "lang=english;".$cookie, "");
		if (strpos($data, '>Premium account expire<')) {
			$expire  = $this->lib->cut_str($data,'Premium account expire</TD><TD><b>','</');
			$traffic = $this->lib->cut_str($data,'Traffic available today</TD><TD><b>','</');
			return [true, "Expire:{$expire}<br>Traffic available today:{$traffic}"];
		}
		return [false, "accinvalid"];
	}

	public function Login($user, $pass){
		$data = $this->lib->curl("https://file.al/", '', "op=login&redirect=&login={$user}&password={$pass}");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}

	public function Leech($url) {
		$id = explode('/', $url)[3];
		$url = 'https://file.al/'. $id;
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if (preg_match('@https?:\/\/[^\.]+.secureservercloud.net:182/d/[^"\'><\r\n\t]+@i', $data, $link)) return trim($link[0]);
		elseif (strpos($data, ">No such file<") || strpos($data, '>File Not Found<')) $this->error("dead", true, false, 2);
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if (preg_match('@https?:\/\/[^\.]+.secureservercloud.net:182/d/[^"\'><\r\n\t]+@i', $data, $link)) return trim($link[0]);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* File.al Download Plugin
* Downloader Class By hogeunk
* 1st version by hogeunk [2016/02/14]
* Support "Direct Download" by hogeunk [2016/02/23]
*/
?>