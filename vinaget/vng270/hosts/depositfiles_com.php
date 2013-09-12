<?php

class dl_depositfiles_com extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://depositfiles.com/gold/payment_history.php", "lang_current=en;{$cookie}", "");
		if(stristr($data, 'You have Gold access until:')) return array(true, "Until ".$this->lib->cut_str($data, '<div class="access">You have Gold access until: <b>','</b></div>'));
		else if(stristr($data, 'Your current status: FREE - member')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass){	
		$post = array(			// if df requied captcha, so not work!!!
			'login' => $user,
			'password' => $pass,
		);
		$page = $this->lib->curl('http://dfiles.eu/api/user/login', 'lang_current=en', $post, 0, 0);
		$json = json_decode($page);
		if($json->data->mode == "gold") {
			$cookie = "autologin=".urlencode($json->data->token);
			return $cookie;
		}
		return false;
	}

    public function Leech($url) {
		$url = preg_replace("@(depositfiles\.org|depositfiles\.net|dfiles\.eu|dfiles\.ru)@", "depositfiles.com", $url);
		list($url, $pass) = $this->linkpassword($url);
		list($name, $domain) = explode(".", $this->lib->cut_str(str_replace("www.", "", $url), "http://", "/"));
		$url = $this->getredirect($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if($pass) {
			$post["file_password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data, 'The file\'s password is incorrect'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('@https?:\/\/fileshare\d+\.'.$name.'\.'.$domain.'\/auth\-[^"\'<>\r\n\t]+@i', $data, $link))
			return trim($link[0]);
		}
		if (stristr($data, "You have exceeded the")) $this->error("LimitAcc");
		elseif (stristr($data,'Please, enter the password for this file')) $this->error("reportpass", true, false);
		elseif (stristr($data, "it has been removed due to infringement of copyright")) $this->error("dead", true, false, 2);
		elseif (stristr($data, "Such file does not exist")) $this->error("dead", true, false, 2);
		elseif (preg_match('@https?:\/\/fileshare\d+\.'.$name.'\.'.$domain.'\/auth\-[^"\'<>\r\n\t]+@i', $data, $link))
		return trim($link[0]);
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
* Fixed check account by giaythuytinh176 [24.7.2013]
* Add support file password by giaythuytinh176 [29.7.2013]
*/
?>