<?php

class dl_xvideos_com extends Download {

	public function FreeLeech($url) {
		$url = str_replace('/0/', '/', $url);
		$data = $this->lib->curl($url, "", "");
		for($i = 0; $i < 3; $i++){
			if(!$this->isredirect($data)) break;
			$url = 'http://www.xvideos.com' . trim(str_replace('http://www.xvideos.com', '', $this->redirect));
			$data = $this->lib->curl($url, '', '');
		}
		if(strpos($data, 'flv_url=') !== false){
			$flv = urldecode($this->lib->cut_str($data, 'flv_url=', '&'));
	 		$this->lib->reserved['filename'] = trim($this->lib->cut_str($data, '<h2>', '<')) . '.flv';
			return $flv;
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
* Xvideos Download Plugin
* Downloader Class By hogeunk
* 1st version by hogeunk [2015/11/29]
* Fix filename by hogeunk [2016/02/19]
* Change to using "isredirect" by hogeunk [2016/02/19]
*/
?>