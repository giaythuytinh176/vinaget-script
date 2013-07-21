<?php

class dl_depositfiles_com extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://depositfiles.com/gold/payment_history.php", $cookie, "");
		if(stristr($data, 'You have Gold access until:')) return array(true, "Until ".$this->lib->cut_str($data, '<div class="access">You have Gold access until: <b>','</b></div>'));
		else if(stristr($data, 'Your current status: FREE - member')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}

	public function Login($user, $pass){
		$this->error("notsupportacc");
		return false;
	}

    public function Leech($url) {
		list($name, $domain) = explode(".", $this->lib->cut_str(str_replace("www.", "", $url), "http://", "/"));
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if (stristr($data, "You have exceeded the")) $this->error("LimitAcc");
		elseif(strpos($data,'Please, enter the password for this file')) $this->error("notsupportpass", true, false);
		elseif($this->isredirect($data)) return trim($this->redirect);
		elseif (preg_match('%"(http:\/\/.+'.$name.'\.'.$domain.'/auth.+)" onClick="%U', $data, $redir2)) return trim($redir2[1]);
		elseif (stristr($data, "it has been removed due to infringement of copyright")) $this->error("dead", true, false, 2);
		elseif (stristr($data, "Such file does not exist")) $this->error("dead", true, false, 2);
		return false;
    }


}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Depositfiles.com Download Plugin 
* Develop by farizemo
* Plugin Download Class By giaythuytinh176
* Date: 16.7.2013
* Fix download by giaythuytinh176 [21.7.2013]
*/
?>