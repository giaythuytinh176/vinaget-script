<?php

class dl_youporn_com extends Download {

	public function FreeLeech($url) {
		$data = $this->lib->curl($url, '', '');
		if(strpos($data, "<h1 class='heading2'>")){
			$basename = $this->lib->cut_str($data, "<h1 class='heading2'>", '<');
			$quality = false;
			$data = $this->lib->cut_str($data, 'sources: ','},').'}';
			$data = str_replace(['240: ', '480: ', '720: ', '1080: '], ['"240": ', '"480": ', '"720": ', '"1080": '], $data);
			$data = str_replace("'", '"', $data);
			$json = json_decode($data, true);
//			$quality = ["1080_60", "1080", "720_60", "720", "480", "240"];
			$quality = ["1080", "720", "480", "240"];
			foreach($quality as $q) {
				if(!empty($json[$q])) {
			 		$this->lib->reserved['filename'] =  "{$basename} ({$q}p).mp4";
					return $json[$q];
				}
			}
		}
		$this->error("dead", true, false, 2);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Youporn Download Plugin by hogeunk
* Downloader Class By hogeunk
* Made by hogeunk [2016/03/01]
*/
?>