<?php

class dl_crocko_com extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.crocko.com/accounts", $cookie, "");
		if(stristr($data, 'Premium membership: Active')) return array(true, "Until ".$this->lib->cut_str($data, 'Ends:  ',', in '));
		else if(stristr($data, 'Premium membership: No premium')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
       
    public function Login($user, $pass){
		$post["login"]=$user;
		$post["password"]=$pass;
		$data = $this->lib->curl("http://www.crocko.com/accounts/login","language=en",$post);                        
		$cookie = "language=en;".$this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if($this->isredirect($data)) return trim($this->redirect);
		elseif (stristr($data,"Sorry,<br />the page you're looking for<br />isn't here") || stristr($data,"Please go to home page or one of this links")) 
			$this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Crocko.com Download Plugin By giaythuytinh176
* Fix by [FZ]
* Downloader Class By [FZ]
* Date: 18.7.2013
*/
?>