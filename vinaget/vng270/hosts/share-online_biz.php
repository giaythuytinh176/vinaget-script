<?php

class dl_share_online_biz extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("https://www.share-online.biz/user/profile", "page_language=english;".$cookie, "");
        if(stristr($data, 'Penalty-Premium        </p>') || stristr($data, 'Premium        </p>') && stristr($data, '<span class=\'red\'>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, 'Account valid until:','Registration date:'), '<span class=\'red\'>', '</span>'));
		elseif(stristr($data, 'Penalty-Premium        </p>') || stristr($data, 'Premium        </p>') && stristr($data, '<span class=\'green\'>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, 'Account valid until:','Registration date:'), '<span class=\'green\'>', '</span>'));
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("https://www.share-online.biz/user/login", "page_language=english", "user={$user}&pass={$pass}&l_rememberme=1&submit=Log in");
        $cookie = "page_language=english;".$this->lib->GetCookies($data);
		return $cookie;
    }
    
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data, 'The requested file is not available'))   $this->error("dead", true, false, 2);
		if(!preg_match('/var dl="(\w+)";/', $data, $en64)) 
		$this->error("Cannot get base64_encode", true, false, 2);	
		else { 		
			$de64 = base64_decode($en64[1]);
			return trim($de64);
		}
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Share-online.biz Download Plugin by giaythuytinh176 [29.7.2013]
* Downloader Class By [FZ]
*/
?>