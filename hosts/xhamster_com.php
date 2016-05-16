<?php

class dl_xhamster_com extends Download {

	public function FreeLeech($url) {
		$data = $this->lib->curl($url,'','',1);
		if(strpos($data, '<h1 itemprop="name">') !== false){
			$basename = $this->lib->cut_str($data, '<h1 itemprop="name">', '<');
			$quality = false;
			$json = $this->lib->cut_str($data, 'sources:','},').'}';
			$array = json_decode($json, true);
			if(isset($array["720p"])) $quality = "720p";
			else if(isset($array["480p"])) $quality = "480p";
			else if(isset($array["240p"])) $quality = "240p";
			else $this->error("dead", true, false, 2);
	 		$this->lib->reserved['filename'] =  "{$basename} ({$quality}).mp4";
			$down = $array[$quality];
			return $down;
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
* xHamster Download Plugin by hogeunk
* Downloader Class By hogeunk
* 1st version by hogeunk [2019/01/26]
*/
?>