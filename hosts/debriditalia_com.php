<?php

class dl_debriditalia_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://www.debriditalia.com/", $cookie, "");
		if(stristr($data, 'Account sospeso leggi l')) return array(false, "accinvalid");
		else if(stristr($data, 'Premium valid till')) return array(true, $this->lib->cut_str($data, "Premium valid till: ", " | Used bandwidth: "). "<br> Used Bandwidth: ".$this->lib->cut_str($data, 'width: ',' | <a href'));
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://debriditalia.com/login.php?u=$user&p=$pass&sid=0".mt_rand().mt_rand(), "", "fplang=en");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
		
    public function Leech($url) {	
		list($url, $pass) = $this->linkpassword($url);
		if($pass) {
			$this->error("Error: Don\'t support Pass Link", true, false, 2);
		}else{
			$data = $this->lib->curl("http://www.debriditalia.com/api.php?generate=&link=".$url, $this->lib->cookie, "",0);
			if (strpos($data, 'http://www.debriditalia.com/dl/') !== false) {
				$page = $this->lib->curl($data, null, $this->lib->cookie, 1);
				if (preg_match('/ocation: *(.*)/i', $page, $redir))
					return trim($redir[1]);
				else $this->error("Redirect Error", true, false);
			}else if (strpos($data, 'debriditalia.com/dl/') !== false) {
				return trim($data);
			}else if (strpos($data, 'ERROR') !== false) {
				return $this->error($data, true, false);
			}
		}
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* DebridItalia Download Plugin 
* Downloader Class By [FZ]
* 1st version by hogeunk [2015/02/21]
*/
?>
