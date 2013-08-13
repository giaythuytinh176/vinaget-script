<?php

class dl_bitshare_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://bitshare.com/myaccount.html", "language_selection=EN;{$cookie}", "");
		if(stristr($data, 'Premium  <a href="http://bitshare.com/myupgrade.html">Extend</a>')) return array(true, "Until ".$this->lib->cut_str($data, 'Valid until:','            </div>')); 
		else if(stristr($data, '<i>Basic</i> <a href="http://bitshare.com/myupgrade.html">Upgrade</a>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://bitshare.com/login.html", "language_selection=EN", "user={$user}&password={$pass}&rememberlogin=&submit=Login");
		$cookie = "language_selection=EN;{$this->lib->GetCookies($data)}";
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if($this->isredirect($data)) return trim($this->redirect);
		elseif(stristr($data,'<h1>Error - File not available</h1>') || stristr($data,'We are sorry, but the requested file was not found in our database! <br /> The file was deleted either by the uploader, inactivity or due to copyright claim.'))
		$this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Bitshare.com Download Plugin
* Downloader Class By [FZ]
* Fixed By giaythuytinh176 In : 16.7.2013
* Fixed check account By giaythuytinh176 [6.8.2013]
*/
?>