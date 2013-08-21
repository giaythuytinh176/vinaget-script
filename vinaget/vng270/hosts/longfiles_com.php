<?php

class dl_longfiles_com extends Download {
	
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->lib->cookie = $this->lib->GetCookies($data);
		if(preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@i', $data, $count)) 	
		$this->error($count[0], true, false);
		sleep(15);
		if(!stristr($data, "<h3>Download File</h3>"))
		$this->error("Cannot get Download File", true, false, 2);
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(!preg_match('@https?:\/\/[^/|\r|\n|\"|\'|<|>]+\/(?:(?:files)|(?:cgi-bin\/dl\.cgi))/[^\r|\n|\"|\'|<|>]+@i', $data, $giay))
			$this->error("notfound", true, false, 2);
			else
			return trim($giay[0]);
		}
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