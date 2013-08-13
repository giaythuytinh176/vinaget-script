<?php

class dl_dropbox_com extends Download {
	
 
	
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'Nothing Here') && stristr($data,'The file you\'re looking for has been deleted or moved.')) $this->error("dead", true, false, 2);
		$data = $this->lib->cut_str($data, '</span></div><div class="meta">', 'class="freshbutton-blue">Download');
		$link = $this->lib->cut_str($data, '</div><a href="', '" id="default_content_download_button"');
		$link = str_replace("https","http",$link);
				return trim($link);
			return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Dropbox.com Download Plugin by giaythuytinh176 [24.7.2013]
* Downloader Class By [FZ]
*/
?>