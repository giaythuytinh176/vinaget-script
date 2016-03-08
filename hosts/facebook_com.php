<?php

class dl_facebook_com extends Download {
	
    public function FreeLeech($url) {
		$data = $this->lib->curl($url, '', '');
		$temp = $this->lib->cut_str($data, '"videoData":[', ']');
		$json = @json_decode($temp, true);
		$qualities = ["hd_src_no_ratelimit", "hd_src", "sd_src_no_ratelimit", "sd_src"];
		foreach($qualities as $q){
			if (isset($json[$q])) {
				$filename = explode('/', $json[$q])[6];
				$filename = explode('?', $filename)[0];
				$this->lib->reserved['filename'] = $filename;
				return $json[$q];
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
* Facebook Video Download Plugin
* Downloader Class By hogeunk
* Made by hogeunk [2016/03/07]
*/
?>