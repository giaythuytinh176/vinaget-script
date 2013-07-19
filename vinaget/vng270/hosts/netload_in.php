<?php

class dl_netload_in extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://netload.in/index.php?id=2", $cookie, "");
		if(stristr($data, 'login')) return array(false, "Account Invalid");
		else if(stristr($data, '<span style="color: green">')) return array(true, $this->lib->cut_str($data, '<span style="color: green">','</span>'));
		else return array(false, "Account is FREE");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://netload.in/", "", "txtuser={$user}&txtpass={$pass}&txtcheck=login&txtlogin=");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {		
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if (stristr($data,"The file was deleted")) $this->error("dead", true, false, 2);
		elseif(preg_match('/ocation: *(.+)/i', $data, $a)){
			if(preg_match('/^http:/', $a[1])) return trim($a[1]);
			else {
				$url = 'http://netload.in'.trim($a[1]);
				$page = $this->lib->curl($url, $this->lib->cookie, "");
				if(preg_match('/<a class="Orange_Link" href="(.*)" >Click here for the download/Ui', $page, $b)) return trim($b[1]);
				elseif (preg_match('%ocation: (.+)\r\n%U', $page, $c)) return trim($c[1]);
			}					
		}
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Netload Download Plugin 
* Downloader Class By [FZ]
*/
?>