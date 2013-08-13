<?php

class dl_datafilehost_com extends Download {

	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		$data = $this->lib->cut_str($data, 'if(document.cbf.cb.checked == false) {', '></a>"; }');
		if (preg_match('%innerHTML="<a href=\'(.+)\'><img src=%U', $data, $giay))  return trim($giay[1]);
		return false;
	}
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Datafilehost.com Download Plugin by giaythuytinh176 [25.7.2013]
* Downloader Class By [FZ]
*/
?>