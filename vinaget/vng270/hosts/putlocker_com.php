<?php

class dl_putlocker_com extends Download {
	
	public function Login($user, $pass){
		$this->error("notsupportacc");
		return false;
	}
	
    public function Leech($url) {
		$data =  $this->curl($url, $this->lib->cookie, "");
		$redir = $this->cut_str($data1, '<a href="/get_file.php?id=', '" class="download_file_link_big"');
		$data = $this->curl("http://www.putlocker.com/get_file.php?id=".trim($redir),$this->lib->cookie,"");
		if($this->isredirect($data)) return trim($this->redirect);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Putlocker Download Plugin 
* Downloader Class By [FZ]
*/
?>