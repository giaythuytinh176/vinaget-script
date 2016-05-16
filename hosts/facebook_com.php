<?php

class dl_facebook_com extends Download {

	public function FreeLeech($url) {
		$data = $this->lib->curl($url, '', '');
		$source = ["\u00253A", "\u00252F", "\u00253F", "\u00253D", "\u002526", "\u00257B", "\u00257D", "\u002522", "\u00252C", "\u00255C", "\u00255D", "\u00255B", "\\"];
		$replace = [":", "/", "?", "=", "&", "{", "}", "\"", ",", "\\", "[", "]", ""];
		$data = str_replace($source, $replace, $data);
		$qualities = ["hd_src_no_ratelimit", "hd_src", "sd_src_no_ratelimit", "sd_src"];
		foreach ($qualities as $q) {
			if (preg_match("@{$q}\":\"(https?://[^\"'><\r\n\t ]+)@i", $data, $link)) {
				$link = $link[1];
				$filename = substr($link, strrpos($link, '/') + 1);
				$filename = explode('?', $filename);
				$filename = $filename[0];
				$this->lib->reserved['filename'] = $filename;
				return $link;
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
* Fix to support old videos by hogeunk [2016/03/10]
*/
?>