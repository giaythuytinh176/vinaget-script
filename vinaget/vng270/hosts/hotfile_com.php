<?php

class dl_hotfile_com extends Download {
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://www.hotfile.com/login.php","","user={$user}&pass={$pass}");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url,"{$this->lib->cookie};lang=en;","");
		if($this->isredirect($data)) return trim($this->redirect);
		elseif(preg_match('%"(http\:\/\/hotfile\.com\/get\/.+)"%U', $page, $redir2)) return trim($redir2[1]);
		elseif (stristr($page,"removed due to copyright claim")) $this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Hotfile Download Plugin 
* Downloader Class By [FZ]
*/
?>