<?php   

class dl_depfile_com extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("https://depfile.com/", "sdlanguageid=2; {$cookie}", "");
		if (strpos($data, '/images/i_premium.png')) {
			return [true, "Until ".$this->lib->cut_str($data, "space/premium'>", '<img')];
		} elseif (strpos($data, "<div class=exit><a href='/uploads/logout'>")) {
			return [false, "accfree"];
		}
		return [false, "accinvalid"];
	}

	public function Login($user, $pass){
		$data = $this->lib->curl("https://depfile.com/", "sdlanguageid=2", "login=login&loginemail={$user}&loginpassword={$pass}&submit=login&rememberme=on");
		$cookie = "sdlanguageid=2; {$this->lib->GetCookies($data)}";
		return $cookie;
	}

	public function Leech($url) {
		if(strpos($url, "http://") !== false) {
			$url = str_replace('http://', 'https://', $url);
		}
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if (stripos($data, 'Page Not Found!') || stripos($data, 'File was not found in the') || stripos($data, 'Provided link contains errors')) {
			$this->error("dead", true, false, 2);
		} elseif (preg_match('@onclick="this.select\(\);" value="(https?:\/\/[^"\'<>\r\n\t]+)@i', $data, $matches)) {
			return $matches[1];
		} elseif (strpos($data, 'limit on urls/files per 24 hours')) {
			$this->error("LimitAcc");
		}
		return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* depfile.com Download Plugin
* Downloader Class By [FZ]
* Download plugin by giaythuytinh176 [11.8.2013]
* Fixed by Naztek - 26.11.2015 [Naztek.tk]
* Fixed by hogeunk [2016/03/01]
*  - Checking what reached to daily limit (150 links/day)
*  - Beautifying the code
* Fix getting download link by hogeunk [2016/03/04]
* Fix account check by hogeunk [2016/03/07]
* Fix account check and LimitAcc (Message was changed) by hogeunk [2016/03/29]
* Refactoring account check by hogeunk [2016/05/15]
*/
?>