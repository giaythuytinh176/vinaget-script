<?php

class dl_oteupload_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.oteupload.com/my_account.php", "lang=english;{$cookie}", "");
        if(stristr($data, 'Your remium status runs out in:')) return array(true, "Until ".$this->lib->cut_str($data, 'Your remium status runs out in:<br /><span>', '</span></td>'));
        else if(stristr($data, 'New password') && !stristr($data, 'Your remium status runs out in')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.oteupload.com/", "lang=english", "login={$user}&password={$pass}&op=login&submit=&file_id=1&redirect=https://www.oteupload.com/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', ' <table width="890px"'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password')) $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/srv([0-9-]+)?\.oteupload\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', ' <table width="890px"'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('@https?:\/\/srv([0-9-]+)?\.oteupload\.com(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
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
* oteupload Download Plugin by giaythuytinh176 [1.8.2013]
* Downloader Class By [FZ]
*/
?>