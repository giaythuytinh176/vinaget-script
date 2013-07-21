<?php

class dl_filepost_com extends Download {
	
	public function PreLeech($url){
		$data = $this->lib->curl($url,"","");
		elseif (stristr($data,'This IP address has been blocked')) $this->error("blockIP", true, false);
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://filepost.com/general/login_form/","","email={$user}&password={$pass}");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$gach = explode('/', $url);
		if (count($gach) > 5) $url = 'http://filepost.com/files/' . $gach[4];
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if (preg_match("%download_file\('(http:\/\/.+filepost.com/get_file/.+)'\)%U", $data, $match)) return trim($match[1]);
		elseif (stristr($data,'File not found')) $this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Filepost Download Plugin 
* Downloader Class By [FZ]
*/
?>