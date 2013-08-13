<?php

class dl_wipfiles_net extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://wipfiles.net/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, '<TR><TD>Premium account expire:</TD><TD><b>','</b>'));
        else if(stristr($data, 'Payment info') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://wipfiles.net/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://wipfiles.net/");
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
        if($this->isredirect($data)) return trim($this->redirect);
		elseif(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data, "Create Download Link")){
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
* Wipfiles Download Plugin by giaythuytinh176 [31.7.2013]
* Downloader Class By [FZ]
*/
?>