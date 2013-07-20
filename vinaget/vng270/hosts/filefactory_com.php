<?php

class dl_filefactory_com extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.filefactory.com/premium/", $cookie, "");
		if(stristr($data, 'Premium member until')) return array(true, "Until ".$this->lib->cut_str($data, '<p><a href="/premium/">','. Extend</a></p>'));
		else if(stristr($data, '<p class="greenText">Free member</p>')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}

	public function Login($user, $pass){
		$post["email"] = $user;
		$post["password"] = $pass;
		$post['redirect'] = "http://www.filefactory.com/";
		$data = $this->lib->curl("http://www.filefactory.com/","",$post);
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		if(!stristr($url, "www")) {
			$ex = explode("filefactory.com", $url);
			$url = "http://www.filefactory.com".$ex[1];
		}
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if($this->isredirect($data)) return trim($this->redirect);
		elseif(preg_match('%(http:\/\/.+filefactory\.com/dlp/.+)">Download with FileFactory Premium%U', $data, $redir2)) $link = trim($redir2[1]);
		if(!empty($link)){
			if(!stristr($link, "logout.php")) return $link;
			else $this->error("notwork");
		}
		elseif (stristr($data,"This error is usually caused by requesting a file that does not exist")) $this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Filefactory.com Download Plugin by .:[T]:.
* Downloader Class By [FZ]
* Date: 16.7.2013
* Fixed check account: 18.7 
*/
?>