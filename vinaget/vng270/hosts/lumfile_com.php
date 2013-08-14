<?php 

class dl_lumfile_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://lumfile.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '<TR><TD align=right >Premium account expire:</TD>', '<TD><input type="button" value="Extend Premium Account"'), '<TD><b>', '</b></TD>'));
        elseif(stristr($data, '<a href="/?op=payments">Upgrade Now</a>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://lumfile.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://lumfile.com/");
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
			if(stristr($data,'Wrong password')) $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data)) return trim($this->redirect);
		}
		if(stristr($data, 'value="Download File">')){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$giay = $this->lib->curl($url, $this->lib->cookie, $post);
			return trim($giay);
		}
        if($this->isredirect($data)) return trim($this->redirect);
		elseif(stristr($data,'type="password" name="password"')) 	$this->error("reportpass", true, false);
        elseif(stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Lumfile Download Plugin, updated by giaythuytinh176 [3.8.2013]
* Downloader Class By [FZ]
*/
?>