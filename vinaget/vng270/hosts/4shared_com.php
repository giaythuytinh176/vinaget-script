<?php

class dl_4shared_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.4shared.com/account/download/myAccount.jsp", "4langcookie=en;{$cookie}", "");
		if(stristr($data, '<td>Account Type:</td>') && stristr($data, '<td>Premium</td>')) return array(true, "accpremium");
		else if(stristr($data, '/web/login')) return array(false, "accinvalid");
		else return array(false, "accfree");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://www.4shared.com/web/login", "4langcookie=en", "login={$user}&password={$pass}&remember=1&_remember=on&returnTo=http://www.4shared.com/account/home.jsp&ausk=&inviteId=&inviterName=");
		$cookie = "4langcookie=en; savelogin=true; {$this->lib->GetCookies($data)}";
		return $cookie;
	}
	
    public function Leech($url) {		
		if(!stristr($url, "4shared.com/file/")) {
			$ex =  explode("/", $url); 
			$url = 'http://www.4shared.com/file/'.$ex[4];
		}
		$data = $this->lib->curl($url, "4langcookie=en;".$this->lib->cookie, "");
		if (stristr($data,'The file link that you requested is not valid')) $this->error("dead", true, false, 2);
		elseif (!preg_match('@https?:\/\/dc\d+\.4shared\.com\/download\/[^"\'><\r\n\t]+@i', $data, $giay))
		$this->error("notfound", true, false, 2); 
		else 	
		return trim($giay[0]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* 4shared Download Plugin 
* Downloader Class By [FZ]
* Fixed check account by giaythuytinh176 [14.8.2013]
*/
?>