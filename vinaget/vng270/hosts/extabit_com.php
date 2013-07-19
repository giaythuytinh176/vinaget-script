<?php

class dl_extabit_com extends Download {
	
	public function Login($user, $pass){
		$post['email'] = $user;
		$post['pass'] = $pass;
		$post['auth_submit_login.x'] = rand(5,70);
		$post['auth_submit_login.y'] = rand(3,20);
		$post['remember'] = "1";
		$data = $this->lib->curl("http://extabit.com/login.jsp","language=en",$post);
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->curl($url,$this->lib->cookie,"");
		if (stristr($data,'File is temporary unavailable')) $this->error("dead", true, false, 2);
		elseif($this->isredirect($data)) return trim($this->redirect);
		elseif (preg_match('%id="download-file-btn" href="(.*)" onClick%U', $data, $redir2)) return trim($redir2[1]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Extabit Download Plugin 
* Downloader Class By [FZ]
*/
?>