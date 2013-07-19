<?php

class dl_ryushare_com extends Download {
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://ryushare.com/","lang=english","op=login&redirect=http%3A%2F%2Fryushare.com%2F&login={$user}&password={$pass}&loginFormSubmit=Login");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url,"{$this->lib->cookie};lang=english;","");
		if($this->isredirect($data)) return trim($this->redirect);
		elseif (stristr($data,'<font class="err">')){
			$err = str_replace("Your","Our",$this->lib->cut_str($data, '<font class="err">', '</font>'));
			$this->error($err);
		}
		elseif (stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Ryushare Download Plugin 
* Downloader Class By [FZ]
*/
?>