<?php

class dl_filejoker_net extends Download {

	public function CheckAcc($cookie) {
		$data = $this->lib->curl("https://filejoker.net/profile", "lang=english;".$cookie, "");
		if (stristr($data, 'Premium account expires:')) {
			$expire  = trim($this->lib->cut_str($data,'Premium account expires:','<'));
			$traffic  = trim($this->lib->cut_str(strstr($data,'>Traffic Available:</td>'),'<td>','</td>'));
			return [true, "Expire:{$expire}<br>Traffic:{$traffic}"];
		}
		return [false, "accinvalid"];
	}

	public function Leech($url) {
		$id = explode('/', $url)[3];
		$url = 'https://filejoker.net/'. $id;
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(strpos($data, ">Get Download Link<") !== false) {
			$post = $this->parseForm($data);
			$data = $this->lib->curl($url, $this->lib->cookie, http_build_query($post));
			if(preg_match('@https?:\/\/fs\d+\.filejoker.net/[^"\'><\r\n\t]+@i', $data, $link)) return trim($link[0]);
		} elseif(strpos($data, ">File Not Found<")) $this->error("dead", true, false, 2);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* filejoker.net Download Plugin
* Downloader Class By hogeunk
* 1st version by hogeunk (Only support cookie) [2016/02/14]
*/
?>