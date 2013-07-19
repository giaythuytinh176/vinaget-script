<?php

class dl_lumfile_com extends Download {
	
	public function Login($user, $pass){
		$data = $this->lib->curl('http://lumfile.com/', 'lang=english', 'op=login&login='.urlencode($user).'&password='.$pass);
		if(stristr($data, "account has been blocked")) {
			$this->error("Lumfile.com account has been blocked");
			return false;
		}						
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if($this->isredirect($data)) return trim($this->redirect);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Lumfile Download Plugin 
* Downloader Class By [FZ]
*/
?>