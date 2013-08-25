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
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(!preg_match('@https?:\/\/(\w+\.)?fileom\.com(:\d+)?\/d\/[a-z0-9]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $this->lib->cut_str($data, 'dotted #bbb;padding:7px;line-height:29px;">', '">http'), $giay))
			$this->error("notfound", true, false, 2);	
			else	
			return trim($giay[0]);
		}
		if(stristr($data,'Password:</b> <input type="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'<div class="page-title">File Not Found</div>')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(!preg_match('@https?:\/\/(\w+\.)?fileom\.com(:\d+)?\/d\/[a-z0-9]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $this->lib->cut_str($data, 'dotted #bbb;padding:7px;line-height:29px;">', '">http'), $giay))
			$this->error("notfound", true, false, 2);	
			else	
			return trim($giay[0]);
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
* fileom Download Plugin by giaythuytinh176 [6.8.2013]
* Downloader Class By [FZ]
*/
?>