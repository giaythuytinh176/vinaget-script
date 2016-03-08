<?php	

class dl_prefiles_com extends Download {
    
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://prefiles.com/settings", $cookie, "");
		if(stristr($data, 'Premium until <span>')) return [true, $this->lib->cut_str($data, 'Premium until <span>','<')];
		else return array(false, "accinvalid");
	}

	public function Login($user, $pass){
		$data = $this->lib->curl("http://prefiles.com/login", "", "op=login&redirect=&login={$user}&password={$pass}");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}

	public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if ($this->isredirect($data) && strpos($this->redirect, 'prefil.es/files/') !== false) {
			return $this->redirect;
		}
		if (strpos($data, "File not Found!")) {
			$this->error("dead", true, false, 2);
		} elseif (strpos($data, '<form method="POST"')) {
			$post = $this->parseForm($this->lib->cut_str($data, '<form method="POST"', '</form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if (preg_match('@https?:\/\/.*file\.es/files/[^"\'><\r\n\t]+@i', $data, $link)) {
				return trim($link[0]);
			}
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Prefiles.com Download Plugin
* Downloader Class By [FZ]
* Made by hogeunk [2016/02/19]
*/
?>