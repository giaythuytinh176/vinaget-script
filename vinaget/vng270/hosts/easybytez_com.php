<?php

class dl_easybytez_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.easybytez.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b></TD><TD'));
        else if(stristr($data, 'Used space:') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
		$cutrand = $this->lib->curl("http://www.easybytez.com/", "lang=english", "");
		$rand = $this->lib->cut_str($cutrand, 'name="rand" value="', '">');
        $data = $this->lib->curl("http://www.easybytez.com/", "lang=english", "login={$user}&password={$pass}&op=login1&rand={$rand}&redirect=http://www.easybytez.com/");
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
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false);
			elseif(!preg_match('@https?:\/\/(\w+\.)?easybytez\.com(:\d+)?\/(?:(?:files\/\d+)|(?:d))\/[a-z0-9]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $this->lib->cut_str($data, 'dotted #bbb;padding:7px;line-height:29px;">', '">http'), $giay))
			$this->error("notfound", true, false, 2);	
			else	
			return trim($giay[0]);
		}
        if($this->isredirect($data)) return trim($this->redirect);
		elseif (stristr($data,'You have reached the download-limit'))  $this->error("LimitAcc", true, false);
		elseif(stristr($data,'<b>File Not Found</b><br><br>')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(!preg_match('@https?:\/\/(\w+\.)?easybytez\.com(:\d+)?\/(?:(?:files\/\d+)|(?:d))\/[a-z0-9]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $this->lib->cut_str($data, 'dotted #bbb;padding:7px;line-height:29px;">', '">http'), $giay))
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
* Easybytez Download Plugin by giaythuytinh176 [10.8.2013]
* Downloader Class By [FZ]
*/
?>