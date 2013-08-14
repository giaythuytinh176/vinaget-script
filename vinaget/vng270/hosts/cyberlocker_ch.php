<?php

class dl_cyberlocker_ch extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://cyberlocker.ch/?op=my_account", "lang=english;".$cookie, "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b></TD><TD>'));
        else if(stristr($data, 'New password') && !stristr($data, 'Premium account expire')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://cyberlocker.ch/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
    
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
		//	$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["op"] = "download2";
			$post["id"] = $this->lib->cut_str($data, 'name="id" value="', '">');
			$post["rand"] = $this->lib->cut_str($data, 'rand" value="', '">');
			$post["referer"] = "";
			$post["method_free"] = "";
			$post["method_premium"] = "1";
			$post["down_direct"] = "1";
			$post["next"] = ""; 
			$post["password"] = $pass; 
			$giay = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($giay,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($giay)) return trim($this->redirect);
		}
        if($this->isredirect($data)) return trim($this->redirect);
		elseif (stristr($data,'You have reached the download-limit'))  $this->error("LimitAcc", true, false);
		elseif(stristr($data,'<br><b>Password:</b> <input type="password"')) 	$this->error("reportpass", true, false);
		elseif(stristr($data, '<input type="submit" id="btn_download" value="Download" class="btn_download buttons">')){
		//	$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["op"] = "download2";
			$post["id"] = $this->lib->cut_str($data, 'name="id" value="', '">');
			$post["rand"] = $this->lib->cut_str($data, 'rand" value="', '">');
			$post["referer"] = "";
			$post["method_free"] = "";
			$post["method_premium"] = "1";
			$post["down_direct"] = "1";
			$post["next"] = ""; 
			$giay = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($giay)) return trim($this->redirect);
		}
        elseif(stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Cyberlocker.ch Download Plugin by giaythutyinh176 [30.7.2013]
* Downloader Class By [FZ]
*/
?>