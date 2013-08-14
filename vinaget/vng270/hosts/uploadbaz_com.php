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
			elseif($this->isredirect($data)) return trim($this->redirect);
			else
			$giay = $this->lib->cut_str($this->lib->cut_str($data, 'dotted #bbb;padding:7px;">', '</span>'), '<a href="', '">');
			return trim($giay);
		}
		if($this->isredirect($data)) return trim($this->redirect);
		elseif(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data, "Create Download Link")){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$giay = $this->lib->cut_str($this->lib->cut_str($data, 'dotted #bbb;padding:7px;">', '</span>'), '<a href="', '">');
			return trim($giay);
		}
        elseif(stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
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