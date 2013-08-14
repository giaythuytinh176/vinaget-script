<?php

class dl_billionuploads_com extends Download {
    
    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			else
			$giay = $this->lib->cut_str($data, '<input type="hidden" id="dl" value="','">');
			return trim($giay);
		}
		if(stristr($data,'type="password" name="password')) $this->error("reportpass", true, false);
		elseif(stristr($data, "Download or Watch") && !stristr($data,'type="password" name="password')){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$link = $this->lib->cut_str($data, '<input type="hidden" id="dl" value="','">');
			return trim($link);
		}
        elseif(stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
		return false;
	}
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* billionuploads Download Plugin by riping [22.7.2013]
* Downloader Class By [FZ]
* Support file password by giaythuytinh176 [29.7.2013]
*/
?>