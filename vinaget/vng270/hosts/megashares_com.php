<?php

class dl_megashares_com extends Download {
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://d01.megashares.com/myms_login.php",'',"httpref=&mymslogin_name={$user}&mymspassword={$pass}&myms_login=Login");				
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if($this->isredirect($data)) return trim($this->redirect);
		elseif (stristr($data,"Invalid link")) $this->error("dead", true, false, 2);
		elseif (preg_match('%(http:\/\/.+megashares\.com/.+)"><img style="margin%U', $data, $redir2)) return trim($redir2[1]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Megashares Download Plugin 
* Downloader Class By [FZ]
*/
?>