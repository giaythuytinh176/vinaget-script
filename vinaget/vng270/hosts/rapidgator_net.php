<?php

class dl_rapidgator_net extends Download {
	
	public function Login($user, $pass){
		$data = $this->lib->curl("https://rapidgator.net/auth/login","","LoginForm[email]={$user}&LoginForm[password]={$pass}&LoginForm[rememberMe]=1");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if(preg_match ( '/ocation: (\/file\/.++)/', $data, $linkpre)) $data = $this->curl('http://rapidgator.net'.trim($linkpre[1]),$this->lib->cookie.';lang=en',"");
		if(stristr($data, "You have reached daily quota")) $this->error("LimitAcc");
		elseif(stristr($data,'File not found')) $this->error("dead", true, false, 2);
		elseif(preg_match("%var premium_download_link = '(.*)';%U", $data, $matches)) return trim ($matches[1]);
		elseif(preg_match ( '/ocation: (.*)/', $data, $linkpre)) return trim ($linkpre[1]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Rapidgator Download Plugin 
* Downloader Class By [FZ]
*/
?>