<?php

class dl_dailymotion_com extends Download {
	
    public function FreeLeech($url) {
		$id = $this->lib->cut_str($url, '/video/','_');
		$data = $this->lib->curl("http://www.dailymotion.com/embed/video/{$id}", "", "");
		$json = @json_decode($this->lib->cut_str($data, "), ", ");"), true); 
		if(isset($json["metadata"]["qualities"])) {
			$this->lib->cookie = $this->lib->GetCookies($data);
			$qualities = array_reverse($json["metadata"]["qualities"]);
			foreach($qualities as $q){
				$ext = $this->lib->cut_str($q[0]["url"], $id . '.','?auth=');
				$this->lib->reserved['filename'] = $json["metadata"]["title"] . ".$ext";
				return $this->getredirect($q[0]["url"]);
			}
		}
		else $this->error("dead", true, false, 2);
		return false;
	}
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Dailymotion Download Plugin 
* Downloader Class By [FZ]
* Fix by hogeunk [2016/03/06]
*/
?>