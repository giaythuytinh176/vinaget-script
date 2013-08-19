<?php

class dl_uploadbaz_com extends Download {
	
    public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.uploadbaz.com/?op=my_account", "lang=english;{$cookie}", "");
		if(stristr($data, '<a href="http://www.uploadbaz.com/?op=payments">Upgrade to premium</a>')) return array(false, "accfree");
		elseif(stristr($data, 'Premium Account expire:')) return array(true, "Until ".$this->lib->cut_str($data, '<TR><TD>Premium Account expire:</TD><TD><b>','</b>'));
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://www.uploadbaz.com/login.html", "lang=english", "login={$user}&password={$pass}&op=login&redirect=");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
	}
	
	public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(!preg_match('@https?:\/\/s(\d+)?\.uploadbaz\.com(:\d+)?\/(?:(?:files\/\d+)|(?:d))\/[^"\'><\r\n\t]+@i', $data, $giay))
			$this->error("notfound", true, false, 2); 	
			else	
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'<b>File Not Found</b><br><br>')) $this->error("dead", true, false, 2);
		elseif(!preg_match('@https?:\/\/s(\d+)?\.uploadbaz\.com(:\d+)?\/(?:(?:files\/\d+)|(?:d))\/[^"\'><\r\n\t]+@i', $data, $dl)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(!preg_match('@https?:\/\/s(\d+)?\.uploadbaz\.com(:\d+)?\/(?:(?:files\/\d+)|(?:d))\/[^"\'><\r\n\t]+@i', $data, $giay))
			$this->error("notfound", true, false, 2);	
			else 	
			return trim($giay[0]);
		} 
		else  
		return trim($dl[0]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Uploadbaz Download Plugin by riping [22/7/2013]
* Downloader Class By [FZ]
* Support file password by giaythuytinh176 [29.7.2013]
*/
?>