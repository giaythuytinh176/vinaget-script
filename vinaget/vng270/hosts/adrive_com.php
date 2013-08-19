<?php

class dl_adrive_com extends Download {
	
	public function FreeLeech($url){
		$url = str_replace("http://adrive.com", "http://www.adrive.com", $url);
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,'The file is password protected')) 	$this->error("notsupportpass", true, false);
		elseif(stristr($data,'Not Found') || stristr($data,'The file you are trying to access is no longer available publicly') || stristr($data,'Public File Busy')) 
		$this->error("dead", true, false, 2);
		elseif(!preg_match('@https?:\/\/downloadwww(\d+\.)?adrive\.com\/public\/view/[^"\'><\r\n\t]+@i', $data, $giay))  
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
* Adrive.com Download Plugin by giaythuytinh176 [24.7.2013]
* Downloader Class By [FZ]
*/
?>