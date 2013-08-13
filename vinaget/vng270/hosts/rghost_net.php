<?php

class dl_rghost_net extends Download {

	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if (preg_match('%<a href="(.+)" class="header_link" onclick="%U', $data, $giay))  return trim($giay[1]);
		elseif(stristr($data,'File is deleted.') || stristr($data,'<p>this page is not found</p>')) $this->error("dead", true, false, 2);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Rghost.net Download Plugin by giaythuytinh176 [25.7.2013]
* Downloader Class By [FZ]
*/
?>