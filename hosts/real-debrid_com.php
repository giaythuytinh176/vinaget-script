<?php

class dl_real_debrid_com extends Download {
   
	public function CheckAcc($cookie){
		$json = str_replace("dummy=", "", $cookie);
		$json = json_decode($json, true);
		if($json["error"] !== 0) {
			return [false, $json["message"]];
		}
		$data = $this->lib->curl("https://real-debrid.com/account", $json["cookie"], "");
		if (strpos($data, '<strong>Free</strong>')) {
			return [false, "accfree"];
		} elseif (strpos($data, '<strong>Premium</strong>')) {
			return [true, "Premium till: " . $this->lib->cut_str($data, "Valid untill: <strong>", "<")];
		}
		return [false, "accinvalid"];
	}

	public function Login($user, $pass){
		$data = $this->lib->curl("https://real-debrid.com/ajax/login.php?user=".urlencode($user)."&pass=".urlencode(md5($pass))."&pin_challenge=&pin_answer=PIN%3A+000000&time=".time(), "", "", 0);
		if (strpos($data, "}{")) $data = "{". $this->lib->cut_str($data, "}{", "}") . "}";
		return "dummy=".$data;
	}

	public function Leech($url) {
		$data = $this->lib->curl("https://real-debrid.com/ajax/unrestrict.php?link=".urlencode($url)."&remote=0&time=".time(), $this->lib->cookie, "", 0);
		if (strpos($data, "}{")) $data = "{". $this->lib->cut_str($data, "}{", "}") . "}";
		$page = json_decode($data, true);
		if (strpos($data,"Dedicated server detected") !== false) $this->error("blockIP", true, false);
		elseif (isset($page['error'])){
			if(isset($page['error']) && $page['error'] == '0') return trim($page['main_link']);
			else $this->error("dead", true, false, 2);
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Real-debrid Download Plugin
* Fix to using "https" (not support PIN) by hogeunk [2016/02/23]
* Show error message when account check by hogeunk [2016/05/08]
*/
?>