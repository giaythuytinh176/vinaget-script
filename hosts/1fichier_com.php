<?php

class dl_1fichier_com extends Download {

	public function CheckAcc($cookie) {
		$data = $this->lib->curl("https://1fichier.com/console/abo.pl", $cookie, "");
		if (stristr($data, 'Your account is Premium until')) return array(true, "Premium Until: ".$this->lib->cut_str($data, "until ","</span>"));
		elseif (stristr($data, 'After test for FREE our services, choose your Offer')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
	
	public function Login($user, $pass) {
		$data = $this->lib->curl("https://1fichier.com/login.pl", "", "mail={$user}&pass={$pass}&lt=on&valider=Send");
		$cookie = "LG=en; ".$this->lib->GetCookies($data);
		return $cookie;
	}

	public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$url = str_replace('http://', 'https://', $url);
		$data = $this->lib->curl($url, $this->lib->cookie, "pass={$pass}");
		if (stristr($data, 'Password Protected')) $this->error("reportpass", true, false);
		elseif (stristr($data, 'Incorrect Password')) $this->error("wrongpass", true, false, 2);
		elseif (stristr($data, 'file could not be found') || stristr($data, 'file has been deleted')) $this->error("dead", true, false, 2);
		elseif ($this->isredirect($data)) {
			$link = str_replace('https', 'http', $this->redirect);
			$filename = explode(';', $this->lib->getname($link, $this->lib->cookie));
			$this->lib->reserved['filename'] = $filename[0];
			return $link;
		}
		return false;
	}

}

/*
 * Open Source Project
 * Vinaget by ..::[H]::..
 * Version: 2.7.0
 * 1fichier Download Plugin 
 * Downloader Class By [FZ]
 * Fixed Check Account by Enigma [06.02.2016]
 * [Jetleech.com]
 */
?>