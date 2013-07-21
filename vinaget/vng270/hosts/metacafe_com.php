<?php

class dl_metacafe_com extends Download {
	
    public function FreeLeech($url) {
		$data = $this->lib->curl($url, "", "");
		if($this->isredirect($data)){
			if(stristr($this->redirect, "?pageNotFound")) $this->error("dead", true, false, 2);
			if(!stristr($this->redirect, "<img")) $data = $this->lib->curl($this->redirect, "", "");
		}
		$this->save($this->lib->GetCookies($data));
		if(stristr($data, '<link rel="video_src"')){
			$url = $this->lib->cut_str($data, '<link rel="video_src" href="','" />');
			$data = $this->lib->curl($url, $this->lib->cookie, "");
			if($this->isredirect($data)){
				$this->save($this->lib->GetCookies($data));
				$link = urldecode(urldecode($this->redirect));
				$link = $this->lib->cut_str($link, '"highDefinitionMP4"','"access"');
				$link = $this->lib->cut_str($link, '"mediaURL":"','",');
				$link = str_replace("\/", "/", $link);
				return trim($link);
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
* Metacafe Download Plugin 
* Downloader Class By [FZ]
*/
?>
