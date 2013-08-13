<?php

class dl_solidfiles_com extends Download {

	
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if (preg_match('%class="ui-button small green" href="(.+)">%U', $data, $giay))  return trim($giay[1]);
			return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Solidfiles.com Download Plugin by giaythuytinh176 [25.7.2013]
* Downloader Class By [FZ]
*/
?>