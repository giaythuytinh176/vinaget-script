<?php 

class dl_lumfile_com extends Download {
    
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
			elseif(!preg_match('@https?:\/\/\w+\.lumfile\.(.*)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			$this->error("notfound", true, false, 2);	
			else 	
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner') || stristr($data,'File not found')) $this->error("dead", true, false, 2);
		elseif(!preg_match('@https?:\/\/\w+\.lumfile\.(.*)?\/d\/[^"\'><\r\n\t]+@i', $data, $dl)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(!preg_match('@https?:\/\/\w+\.lumfile\.(.*)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			$this->error("notfound", true, false, 2);	
			else 	
			return trim($giay[0]);
		} 
		else   
		return trim($dl[0]);
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