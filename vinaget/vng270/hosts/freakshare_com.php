<?php

class dl_freakshare_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://freakshare.com", $cookie, "");
		if (stristr($data, 'Member (premium)')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, 'valid until:</td>','</tr>'), '<b>','</b>'));
		elseif(stristr($data, 'http://freakshare.com/member/logout.html')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}   
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://freakshare.com/login.html", "", "user={$user}&pass={$pass}&submit=Login");
		return $this->lib->GetCookies($data);
	}
         
	public function Leech($url) {
		if(stristr($url, "/folder/")) $this->error("Not Support Folder", true, false, 2);
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if($this->isredirect($data)) return trim($this->redirect);
		elseif (stristr($data,'Your Traffic is used up for today!')) $this->error("LimitAcc");
		elseif (stristr($data,'This file does not exist! ')) $this->error("dead", true, false, 2);
		else $this->error($data);
		return false;
	}
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Freakshare Download Plugin
* Downloader Class By [FZ]
*/
?>
