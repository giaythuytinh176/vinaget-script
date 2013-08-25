<?php

class dl_sendspace_com extends Download {
	
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.sendspace.com/login.html", "", "remember=on&action=login&submit=login&username={$user}&password={$pass}");
        $cookie = $this->lib->GetCookies($data);
		return $cookie;
    }

	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data,"Sorry, the file you requested is not available.")) $this->error("dead", true, false, 2);
		elseif(!preg_match('@https?:\/\/fs(\d+)?n(\d+)?\.sendspace\.com(:\d+)?\/dl\/[^"\'><\r\n\t]+@i', $data, $giay))  
		$this->error("notfound", true, false, 2);
		else
		return trim($giay[0]);
		return false;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data,"Sorry, the file you requested is not available.")) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			if(!preg_match('@https?:\/\/fs(\d+)?n(\d+)?\.sendspace\.com(:\d+)?\/dl\/[^"\'><\r\n\t]+@i', $data, $giay))  
			$this->error("notfound", true, false, 2);
			else
			return trim($giay[0]);
		}
		else  
		return trim($this->redirect);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* sendspace.com Download Plugin by giaythuytinh176 [17.8.2013]
* Downloader Class By [FZ]
*/
?>