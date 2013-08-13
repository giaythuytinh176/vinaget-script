<?php

class dl_fileom_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://fileom.com/?op=payments", "lang=english;{$cookie}", "");
        if(stristr($data, 'Extend Premium account')) return array(true, "Until ".$this->lib->cut_str($data, '<b>Premium account expire:</b><br>','<br><br>'));
        elseif(stristr($data, '<h2>Become a PREMIUM-Member</h2>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://fileom.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://fileom.com/");
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
			if(stristr($data,'Wrong password')) $this->error("reportpass", true, false);
			elseif($this->isredirect($data)) return trim($this->redirect);
			else
			$giay = $this->lib->cut_str($this->lib->cut_str($data, 'dotted #bbb;padding:7px;">', '</span>'), 'href="', '">');
			return trim($giay);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif($this->isredirect($data)) return trim($this->redirect);
		elseif(stristr($data, 'value="Download Now">')){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$giay = $this->lib->cut_str($this->lib->cut_str($data, 'dotted #bbb;padding:7px;">', '</span>'), 'href="', '">');
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
* fileom Download Plugin by giaythuytinh176 [6.8.2013]
* Downloader Class By [FZ]
*/
?>