<?php

class dl_ryushare_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://ryushare.com/premium.python", "lang=english;{$cookie}", "");
		if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, '<b>Premium account expire:</b><br>','<br><br>'));
		else if(stristr($data, '<a href="http://ryushare.com/premium.python">Upgrade to premium</a>')) return array(false, "accfree");
		else return array(false, "accinvalid" );
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://ryushare.com/", "lang=english", "op=login&redirect=http%3A%2F%2Fryushare.com%2F&login={$user}&password={$pass}&loginFormSubmit=Login");
		$cookie = "lang=english;".$this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif($this->isredirect($data)) return trim($this->redirect);
			$giay = $this->lib->cut_str($this->lib->cut_str($data, 'dotted #bbb;padding:7px;">', '</span></center>'), '<a href="', '">Click');
			return trim($giay);
		}
		if($this->isredirect($data)) return trim($this->redirect);
		elseif (stristr($data,'<input type="password" name="password" class="myForm">')) $this->error("reportpass", true, false);
		elseif (stristr($data, "Create Download Link")){
			$post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$giay = $this->lib->cut_str($this->lib->cut_str($data, 'dotted #bbb;padding:7px;">', '</span></center>'), '<a href="', '">Click');
			return trim($giay);
		}
		elseif (stristr($data,'403 Forbidden')) $this->error("blockIP", true, false);
		elseif (stristr($data,'You have reached the download-limit')) $this->error("LimitAcc", true, false);
		elseif (stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
		elseif (stristr($data,'This server is in maintenance mode. Refresh this page in some minutes.')) $this->error("Ryushare Under Maintenance", true, false);
		return false;
    }
	
}
  
/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Ryushare Download Plugin 
* Downloader Class By [FZ]
* Check account, fixed small error by giaythuytinh176 [18.7.2013]
* Support file password by giaythuytinh176 [29.7.2013]
*/
?>