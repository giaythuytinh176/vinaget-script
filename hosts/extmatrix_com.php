<?php

class dl_extmatrix_com extends Download {
  
	public function CheckAcc($cookie){
		$data = $this->lib->curl("https://www.extmatrix.com/", $cookie, "");
		$typeacc = strip_tags($this->lib->cut_str($data, '<td class="right">Account type:</td>', '</td>'));
		if(stristr($typeacc, 'Premium Member')) return array(true, "Premium End: ".$this->lib->cut_str(stristr($data, '<td class="right">Premium End:</td>'), '<td>', '</td>'));
		else if(stristr($typeacc, 'Free Member')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
         
	public function Login($user, $pass){
		$data = $this->lib->curl("https://www.extmatrix.com/login.php", "", "user={$user}&pass={$pass}&submit=Login&task=dologin&return=./members/myfiles.php");
		$cookie = $this->lib->GetCookies($data);	
		return $cookie;
	}
         
	public function Leech($url) {
/*
		$invo = explode("/", $url);
		$file_id = $invo[4];
		$data = $this->lib->curl("https://www.extmatrix.com/api/download.php?file_id={$file_id}", $this->lib->cookie, "", ""); 
		if ($data==null) $this->error("dead", true, false, 2);
		else return trim($data);
*/
		$data = $this->lib->curl($url, $this->lib->cookie, ""); 
		if($this->isredirect($data)) {
			return trim($this->redirect);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Extmatrix Download Plugin 
* Downloader Class By [FZ]
* Create by invokermoney [03.10.2014]
* Fixed error (testing) by hogeunk [2015/11/26]
*/
?>