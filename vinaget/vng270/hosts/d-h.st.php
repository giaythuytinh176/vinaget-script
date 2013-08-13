<?php

class dl_d_h_st extends Download {
 
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if (preg_match('%id="downloadfile" onclick="location.href=\'(.+)\'">Download</div>%U', $data, $giay)) return trim($giay[1]);
		return false;
	}
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* D-h.st Download Plugin by giaythuytinh176 [25.7.2013]
* Downloader Class By [FZ]
*/
?>