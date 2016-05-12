<?php

class dl_alldebrid_com extends Download {
	
	public function CheckAcc($cookie){
		$data = $this->lib->curl("https://alldebrid.com/account/", $cookie, "");
		if(strpos($data, 'Your subscription has expired')) return array(false, "accfree");
		else if(strpos($data, 'Your account expires')) 
			return array(true, "Left: ".trim($this->lib->cut_str($data, '<strong>','<')));
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://alldebrid.com/register/?action=login&returnpage=&login_login=".urlencode($user)."&login_password=".urlencode($pass),"","login_login=".urlencode($user)."&login_password=".urlencode($pass));
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
		
    public function Leech($url) {	
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl("https://alldebrid.com/service.php?link={$url}&json=true&pw={$pass}", $this->lib->cookie, "",0);
		$json = json_decode($data, true);
		if(empty($json["error"]) && isset($json["link"])){
			return $json["link"];
/* not checked for error */
		} elseif (strpos($data, 'disable for trial account') !== false) {
			return $this->error("Trial account", true, false);
		} elseif (strpos($data, 'Ip not allowed') !== false) {
			return $this->error('IP has been banned', true, false);
		} elseif (!empty($json["error"])) {
			return $this->error($json["error"]);
		}
		return false;
    }

}

/*
 * Open Source Project
 * Vinaget by ..::[H]::..
 * Version: 2.7.0
 * AllDebrid Download Plugin
 * Downloader Class By [FZ]
 * 1st version by hogeunk [2015/02/27]
 * Fixed to using https by hogeunk [2016/02/21]
 * Change domain www.alldebrid.com -> alldebrid.com by hogeunk [2016/05/07]
 */

?>