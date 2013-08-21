<?php

class dl_putlocker_com extends Download {
	
	public function Login($user, $pass){
		$this->error("notsupportacc");
		return false;
	}
	
	public function FreeLeech($url){
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, "", "");
		$this->lib->cookie = $this->lib->GetCookies($data);
		$hash = $this->lib->cut_str($data, '<input type="hidden" value="','" name="hash">');
		if(preg_match('@var countdownNum = (\d+);@i', $data, $count) && $count[1] > 0) 
		sleep($count[1]+1);
		$data = $this->lib->curl($url, $this->lib->cookie, ($pass ? "file_password={$pass}&" : "")."hash={$hash}&confirm=Continue as Free User");
		if(stristr($data,'This file requires a password. Please enter it.')) 	$this->error("reportpass", true, false);
		$data = $this->lib->curl($url, $this->lib->cookie, "hash={$hash}&confirm=Continue as Free User");
		if(stristr($data,'This password is not correct')) 	 $this->error("wrongpass", true, false, 2);
		elseif(stristr($data,'You have exceeded the daily download limit for your country')) 	$this->error("LimitAcc", true, false);
		$this->lib->cookie .= ";".$this->lib->GetCookies($data);
		$id = $this->lib->cut_str($data, '<a href="/get_file.php?id=','"');
		if(!preg_match('@http:\/\/www\.putlocker\.com\/get_file\.php\?id=[^"\'><\r\n\t]+@i', "http://www.putlocker.com/get_file.php?id=".trim($id), $giay))
		$this->error("notfound", true, false, 2);	
		else 	
		return trim($giay[0]);
		return false;
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data =  $this->lib->curl($url, $this->lib->cookie, ($pass ? "file_password={$pass}" : ""));
		if(stristr($data, "This file requires a password")) $this->error("linkpass", true, false, 2);
		$id = $this->lib->cut_str($data1, '<a href="/get_file.php?id=', '"');
		if(!preg_match('@http:\/\/www\.putlocker\.com\/get_file\.php\?id=[^"\'><\r\n\t]+@i', "http://www.putlocker.com/get_file.php?id=".trim($id), $giay))
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
* Putlocker Download Plugin 
* Downloader Class By [FZ]
*/
?>