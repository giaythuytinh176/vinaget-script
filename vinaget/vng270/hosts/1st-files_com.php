<?php

class dl_1st_files_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.1st-files.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b>'));
        else if(stristr($data, 'Free member')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.1st-files.com/login.html", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://www.1st-files.com/");
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
			elseif(!preg_match('@http:\/\/(ss\d+\.)?1st-files\.com\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			$this->error("notfound", true, false, 2);	
			else
			return trim($giay[0]);
		}
		if(stristr($data,'<br><b>Password:</b> <input type="password"')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'<b>File Not Found</b><br><br>')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(!preg_match('@http:\/\/(ss\d+\.)?1st-files\.com\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
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
* 1st-files.com Download Plugin
* Downloader Class By [FZ]
* Download plugin by giaythuytinh176 [28.7.2013]
*/
?>