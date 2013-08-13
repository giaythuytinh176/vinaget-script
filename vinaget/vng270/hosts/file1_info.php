<?php

class dl_file1_info extends Download {
	
	public function FreeLeech($url){
		if(!stristr($url, "noscript")) {
			$ex =  explode("/", $url); 
			$url = "https://www.file1.info/".$ex[3]."/noscript";
		}
		$data = $this->lib->curl($url, "", "");
		if(preg_match('%href="(https?:\/\/.+file1\.info/.+)"><button id="downloadShow%U', $data, $giay))  return trim($giay[1]);
		elseif (stristr($data, "Not Found") || stristr($data, "This file does not exist or has been removed")) $this->error("dead", true, false, 2);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* file1.info Download Plugin by giaythuytinh176 [12.8.2013]
* Downloader Class By [FZ]
*/
?>