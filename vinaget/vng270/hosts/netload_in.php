<?php

class dl_netload_in extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://netload.in/index.php?id=2", $cookie, "");
		if(stristr($data, 'login')) return array(false, "Account Invalid");
		else if(stristr($data, '<span style="color: green">')) return array(true, $this->lib->cut_str($data, '<span style="color: green">','</span>'));
		else return array(false, "Account is FREE");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://netload.in/", "", "txtuser={$user}&txtpass={$pass}&txtcheck=login&txtlogin=");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {	
		if (preg_match('@http:\/\/netload\.in\/(\w+)/(.+)\.htm@i', $url, $urlgiay) || preg_match('@http:\/\/netload\.in\/(\w+)\.htm@i', $url, $urlgiay))
		$url = 'http://netload.in/'.$urlgiay[1].'.htm';
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if($pass) {
			if(!preg_match('%action="([^"]+)"%', $data, $urlp))  
			$this->error("Error: Cannot get Pass Link", true, false, 2);
			else 
			$urlpass = 'http://netload.in/'.$urlp[1];
			$post["file_id"] = $this->lib->cut_str($data, 'type="hidden" value="', '"');
			$post["password"] = $pass; 
			$post["submit"] = "Show";
			$data = $this->lib->curl($urlpass, $this->lib->cookie, $post);
			if(stristr($data,'You have entered an incorrect password'))  $this->error("wrongpass", true, false, 2);
			elseif (!preg_match('@http:\/\/[\d.]+\/[^|\r|\n|\'"]+@i', $data, $dl))
			$this->error("notfound", true, false, 2); 
			else
			return trim($dl[0]);
		}
		if (stristr($data,"The file was deleted"))  $this->error("dead", true, false, 2);
		elseif (stristr($data,'This file is password-protected'))   $this->error("reportpass", true, false);
		elseif (!preg_match('@http:\/\/[\d.]+\/[^|\r|\n|\'"]+@i', $data, $dl))
		$this->error("notfound", true, false, 2); 
		else
		return trim($dl[0]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Netload Download Plugin 
* Downloader Class By [FZ]
* Fixed download link, add support file password by giaythuytinh176 [19.8.2013]
*/
?>