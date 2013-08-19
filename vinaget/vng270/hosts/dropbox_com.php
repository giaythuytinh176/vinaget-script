<?php

class dl_dropbox_com extends Download {
	
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(!preg_match('@https?:\/\/dl\.dropboxusercontent\.com\/[^"\'><\r\n\t]+@i', $data, $giay))
		$this->error("notfound", true, false, 2);  
		else  
		return trim($giay[0]);
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