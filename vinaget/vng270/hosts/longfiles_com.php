<?php

class dl_longfiles_com extends Download {
	
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->lib->cookie = $this->lib->GetCookies($data);
		if(stristr($data, "<h3>Download File</h3>")){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			sleep(10);
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if($this->isredirect($data)) return trim($this->redirect);
		}
		elseif(preg_match('/You have to wait ([0-9]+) seconds till next download/', $data, $giay)) $this->error('You have to wait '.$giay[1].' seconds till next download', true, false);
        elseif(stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* longfiles Download Plugin by giaythuytinh176 [12.8.2013]
* Downloader Class By [FZ]
*/
?>