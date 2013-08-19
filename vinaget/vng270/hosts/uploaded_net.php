<?php

class dl_uploaded_net extends Download {
         
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://uploaded.net/", $cookie, "");
		if(stristr($data, '<a href="register"><em>Premium</em></a>')) return array(true, $this->lib->cut_str($this->lib->cut_str($data, "Duration:</td>","/th>"), "<th>","<"));
		elseif(stristr($data, '<li><a href="logout">Logout</a></li>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
         
	public function Login($user, $pass){
		$data = $this->lib->curl("http://uploaded.net/io/login", "", "id={$user}&pw={$pass}");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
         
	public function Leech($url) {
		$url = $this->getredirect($url);
		if (stristr($url,'uploaded.net/404')) $this->error("dead", true, false, 2);
		$data = $this->lib->curl($url, $this->lib->cookie, ""); 
		if (stristr($data,"<h1>Extend traffic</h1>")) $this->error("LimitAcc");
		elseif (stristr($data,"Hybrid-Traffic is completely exhausted")) $this->error("LimitAcc");
		elseif (stristr($data,"Our service is currently unavailable in your country")) $this->error("blockCountry", true, false);
		elseif (stristr($data,"You used too many different IPs")) $this->error("blockAcc", true, false);
		elseif (stristr($data,"Download Blocked (ip)")) $this->error("blockIP", true, false);
		elseif (!preg_match('@https?:\/\/[a-z0-9-]+stor(\d+\.)?uploaded\.net(:\d+)?\/dl\/[^"\'><\r\n\t]+@i', $data, $giay))
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
* Uploaded Download Plugin 
* Downloader Class By [FZ]
* Fixed By djkristoph
* Fixed download link By giaythuytinh176 [5.8.2013]
*/
?>