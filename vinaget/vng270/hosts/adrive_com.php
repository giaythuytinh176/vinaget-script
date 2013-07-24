<?php

class dl_adrive_com extends Download {
	
	public function Login($user, $pass){
		$this->error("notsupportacc");
		return false;
	}
	
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if (preg_match('%a href="(.+)">here</a>%U', $data, $giay))  return trim($giay[1]);
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