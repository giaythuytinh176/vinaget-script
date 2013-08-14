<?php

class dl_sharerepo_com extends Download {
    
    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data, 'value="Free Download">')){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form method="POST" action=', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
				if($pass) {
					$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST', '</Form>'));
					$post1["password"] = $pass;
					$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
					if(stristr($data1,'Wrong password'))  $this->error("wrongpass", true, false, 2);
					elseif($this->isredirect($data1)) return trim($this->redirect);
				}
			if(stristr($data,'type="password" name="password')) $this->error("reportpass", true, false);
			$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST', '</Form>'));
			$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
			if($this->isredirect($data1)) return trim($this->redirect);
		}
        elseif(stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
		return false;
	}
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* sharerepo Download Plugin by giaythuytinh176 [12.8.2013]
* Downloader Class By [FZ]
*/
?>