<?php

class dl_dizzcloud_com extends Download {
   
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://dizzcloud.com/", $cookie, "");
		if(stristr($data, ">Premium till ")) return array(true, $this->lib->cut_str($data, ">Premium till ", "&nbsp;&nbsp;<"));
		elseif(stristr($data, "http://dizzcloud.com/logout") && !stristr($data, "Premium till")) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
   
    public function Login($user, $pass){
        $data = $this->lib->curl("http://dizzcloud.com/login", "", "email={$user}&pass={$pass}");
		return "{$this->lib->GetCookies($data)}";
    }
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data, ">You have exceeded the daily limit on downloads<")) $this->error("LimitAcc", true, false);
		if(!$this->isredirect($data))  {
			return trim($this->lib->cut_str($data, 'target="_blank" href="', '"  class'));
		}
		else return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Dizzcloud.com Download Plugin by giaythuytinh176 [15.11.2013]
* Thanks to Cik-iNaR™@rapidleech.com for your account.
* Downloader Class By [FZ]
*/
?>