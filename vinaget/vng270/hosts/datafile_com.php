<?php

class dl_datafile_com extends Download {
  
	public function CheckAcc($cookie){
		$data = $this->lib->curl("https://www.datafile.com/profile.html", "lang=en;{$cookie}", "");
		if(stristr($data, '(<a href="/getpremium.html">Prolong</a>)')) return array(true, "Until ".$this->lib->cut_str($data, '<td class="el" >',  '(<a href="/getpremium.html">Prolong</a>)'));
		else if(stristr($data, '(<span class="yellow"><a href="/getpremium.html">Upgrade</a></span>)')) return array(false, "accfree"); 
		else return array(false, "accinvalid"); 
	}
  
	public function Login($user, $pass){
		$data = $this->lib->curl("https://www.datafile.com/login.html", "lang=en", "login={$user}&password={$pass}&remember_me=1");
		$cookie = "lang=en;".$this->lib->GetCookies($data);
		return $cookie;
	}
  
	public function Leech($url) {
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if(stristr($data,'ErrorCode 0: Invalid Link')) $this->error("dead", true, false, 2); 
		elseif(!$this->isredirect($data)) {
			if(!preg_match('@https?:\/\/n(\d+\.)?datafile\.com\/[^"\'><\r\n\t]+@i', $data, $giay))
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
* DataFile.com Download Plugin by giaythuytinh176
* Downloader Class By [FZ]
* Date: 20.7.2013
* Fix check account by giaythuytinh176 [21.7.2013]
* Fix check account by giaythuytinh176 [6.8.2013]
*/
?>