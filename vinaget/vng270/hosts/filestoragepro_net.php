<?php

class dl_filestoragepro_net extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://filestoragepro.net/en/members.php", "mfh_mylang=en;".$cookie, "");
		if(stristr($data, 'http://filestoragepro.net/index.php?logout=1')) return array(true, "accpremium");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://filestoragepro.net/en/login.php", "mfh_mylang=en", "user={$user}&pass={$pass}&act=login&autologin=1&login=Log me in&refer_url=");
		$cookie = "mfh_mylang=en;".$this->lib->GetCookies($data);
		return $cookie;
	}
	
	public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data, "That file has been deleted.")) $this->error("dead", true, false, 2);
		elseif($this->isredirect($data)) return trim($this->redirect);
		else $this->error("unknown", true, false);
		return false;
	}
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Filestoragepro Download Plugin by giaythuytinh176 [30.7.2013]
* Downloader Class By [FZ]
*/
?>