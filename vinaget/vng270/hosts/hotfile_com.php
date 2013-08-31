<?php	

class dl_hotfile_com extends Download {
	
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://hotfile.com/myaccount.html", "lang=en;".$cookie, "");
        if(stristr($data, 'Premium until')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium until: <span class="rightSide">','</span>'));
        else if(stristr($data, '<p><span>Free</span>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://hotfile.com/login.php", "lang=en", "user={$user}&pass={$pass}&returnto=/");
		$cookie = "lang=en;".$this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url,"{$this->lib->cookie};lang=en;","");
		if(stristr($data,"removed due to copyright claim") || stristr($data,"404 - Not Found"))   $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			if(preg_match('@https?:\/\/s\d+\.hotfile\.com\/get\/[^"\'><\r\n\t]+@i', $data, $link))
			return trim($link[0]);
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
* Hotfile Download Plugin 
* Downloader Class By [FZ]
* Add check acc by giaythuytinh176 [19.8.2013]
* Thanks to Rapid61@rapidleech.com for your hotfile account.
*/
?>