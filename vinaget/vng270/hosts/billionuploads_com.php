<?php
class dl_billionuploads_com extends Download {
    
    public function FreeLeech($url) {
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'<b>File Not Found</b>')) $this->error("dead", true, false, 2);
		elseif(stristr($data, "Download or Watch")){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			sleep(2);
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$link = $this->lib->cut_str($data, '<input type="hidden" id="dl" value="','">');
			return trim($link);
		}
		return false;
	}
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* billionuploads Download Plugin by riping [22.7.2013]
* Downloader Class By [FZ]
*/
?>