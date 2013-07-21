<?php

class dl_putlocker_com extends Download {
	
	public function Login($user, $pass){
		$this->error("notsupportacc");
		return false;
	}
	
	public function FreeLeech($url){
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		$hash = $this->lib->cut_str($data, '<input type="hidden" value="','" name="hash">');
		sleep(2);
		$data = $this->lib->curl($url, "", "hash={$hash}&confirm=Continue as Free User");
		$id = $this->lib->cut_str($data, '<a href="/get_file.php?id=','"');
		$data = $this->lib->curl("http://www.putlocker.com/get_file.php?id=".trim($id),$this->lib->cookie,"");
		if($this->isredirect($data)) return trim($this->redirect);
		return false;
	}
	
    public function Leech($url) {
		$data =  $this->lib->curl($url, $this->lib->cookie, "");
		$id = $this->lib->cut_str($data1, '<a href="/get_file.php?id=', '"');
		$data = $this->lib->curl("http://www.putlocker.com/get_file.php?id=".trim($id),$this->lib->cookie,"");
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