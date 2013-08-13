<?php
class dl_share_online_biz extends Download {
    
    public function CheckAcc($cookie){
         $data = $this->lib->curl("https://www.share-online.biz/user/profile", "page_language=english;".$cookie, "");
         if(stristr($data, 'Penalty-Premium        </p>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, 'Account valid until:','Registration date:'), '<span class=\'red\'>', '</span>'));
			else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
         $data = $this->lib->curl("https://www.share-online.biz/user/login", "page_language=english", "user={$user}&pass={$pass}&l_rememberme=1&submit=Log in");
         $cookie = "page_language=english;".$this->lib->GetCookies($data);
			return $cookie;
    }
    
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		$giay = base64_decode($this->lib->cut_str($data, 'var dl="', '";var file'));
		if(stristr($data, 'The requested file is not available')) $this->error("dead", true, false, 2);
		elseif(stristr($giay, 'share-online.biz'))  return trim($giay);
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