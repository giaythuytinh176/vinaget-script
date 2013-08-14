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
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data)) return trim($this->redirect);
			else
			$giay = $this->lib->cut_str($this->lib->cut_str($data, 'dotted #bbb;padding:7px;">', '</span>'), 'href="', '">');
			return trim($giay);
		}
        if($this->isredirect($data)) return trim($this->redirect);
		elseif (stristr($data,'You have reached the download-limit'))  $this->error("LimitAcc", true, false);
		elseif(stristr($data,'<br><b>Password:</b> <input type="password"')) 	$this->error("reportpass", true, false);
		elseif(stristr($data, "Create Download Link")){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$giay = $this->lib->cut_str($this->lib->cut_str($data, 'dotted #bbb;padding:7px;">', '</span>'), 'href="', '">');
			return trim($giay);
		}
        elseif(stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
        elseif(stristr($data,'No such file with this filename')) $this->error("dead", true, false, 2);
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