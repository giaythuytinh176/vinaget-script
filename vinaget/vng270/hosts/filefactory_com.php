<?php

class dl_filefactory_com extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.filefactory.com/premium/", "ff_locale=en_US.utf8;".$cookie, "");
		if(stristr($data, 'Premium member until')) return array(true, "Until ".$this->lib->cut_str($data, '<p><a href="/premium/">','. Extend</a></p>'));
		else if(stristr($data, '<p class="greenText">Free member</p>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$post["email"] = $user;
		$post["password"] = $pass;
		$post['redirect'] = "http://www.filefactory.com/";
		$data = $this->lib->curl("http://www.filefactory.com/", "ff_locale=en_US.utf8", $post);
		$cookie = "ff_locale=en_US.utf8;".$this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$url = preg_replace("@https?:\/\/(www\.)?filefactory\.com@", "http://www.filefactory.com", $url);
		if(!stristr($url, "/n/")) {
			$ex =  explode("/", $url); 
			$url = "http://www.filefactory.com/file/".$ex[4]."/n/".$ex[5];
		}
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if($pass) {
			$post["password"] = $pass;
			$post["action"] = "password";
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Incorrect password. Please try again'))  $this->error("wrongpass", true, false, 2);
			elseif(stristr($data,'You have entered an incorrect password too many times'))  $this->error("You have entered an incorrect password too many times", true, false, 2);
			elseif(!$this->isredirect($data)) {
				if(preg_match('/href="(https?:\/\/.+filefactory\.com\/dlp\/.+)">Download with FileFactory Premium/i', $data, $link))
				return trim($link[1]);
			}
			else
			return trim($this->redirect);
		}
		if(stristr($data,'You are trying to access a password protected file')) 	$this->error("reportpass", true, false);
		elseif (stristr($data,"This error is usually caused by requesting a file that does not exist")) $this->error("dead", true, false, 2);
        elseif(!$this->isredirect($data)) {
			if(preg_match('/href="(https?:\/\/.+filefactory\.com\/dlp\/.+)">Download with FileFactory Premium/i', $data, $link))
			return trim($link[1]);
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
* Filefactory.com Download Plugin by giaythuytinh176
* Downloader Class By [FZ]
* Date: 16.7.2013
* Fixed check account: 18.7 
* Fix download link Filefactory, Add support file password by giaythuytinh176 [29.7.2013]
*/
?>