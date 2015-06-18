<?php		 

class dl_nitroflare_com extends Download {
    
    public function Login($user, $pass){
		$dt = $this->lib->curl("https://nitroflare.com/login", "", "");
		$Cookies = $this->lib->GetCookies($dt);
        $data = $this->lib->curl("https://nitroflare.com/login", $Cookies, "email={$user}&password={$pass}&login=&token=".$this->lib->cut_str($dt, 'name="token" value="', '" />'));
		$cookie = $Cookies.$this->lib->GetCookies($data);
		return $cookie;
    }
 
    public function Leech($url) {
		
		$url = str_replace(array("https://", "http://www.", "https://www."), "http://", $url);
		
		$data = $this->lib->curl($url, $this->lib->cookie, "");  
		
		if (stristr($data, "File doesn't exist")) $this->error("dead", true, false, 2);
		
        if (!$this->isredirect($data)) {
			$this->save($this->lib->GetCookies($data));
			return trim($this->lib->cut_str($data, 'id="download" href="', '">Click')); 
		}
		else {
			$this->save($this->lib->GetCookies($data));
			return trim($this->redirect);
		}
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* nitroflare Download Plugin by giaythuytinh176 [17.05.2015]
* Downloader Class By [FZ]
*/
?>