<?php

class dl_4shared_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.4shared.com/account/download/myAccount.jsp", $cookie, "");
		if(stristr($data, '/web/login')) return array(false, "accinvalid");
		else if(stristr($data, 'AccType = Premium download')) return array(true, "Premium Download");
		else return array(false, "accfree");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://www.4shared.com/web/login", "", "login={$user}&password={$pass}&remember=1&_remember=on&returnTo=http://www.4shared.com/account/home.jsp&ausk=&inviteId=&inviterName=");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {		
		list($url, $pass) = $this->linkpassword($url);
		$post = !empty($pass) ? "userPass2=".$pass : "";
		$url = str_replace("4shared.com/get", "4shared.com/file", $url);
		$this->save("savelogin=true;4langcookie=en;");
		$data = $this->lib->curl($url, $this->lib->cookie, $post ? "userPass2={$pass}" : "");
		$this->save($this->lib->GetCookies($data));
		if (stristr($data,'The file link that you requested is not valid')) $this->error("dead", true, false, 2);
		elseif(preg_match ( '/a id="btnLink" href="(.*)" class/i', $data, $linkpre)) return trim ($linkpre[1]);
		elseif($this->isredirect()) return trim ($this->redirect);
		elseif(stristr($data, 'Please enter a password to access this file')) $this->error("filepass", true, false);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* 4shared Download Plugin 
* Downloader Class By [FZ]
*/
?>