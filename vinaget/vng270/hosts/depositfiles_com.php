<?php
$predefined['server'] = "depositfiles.com";
$site = "depositfiles.com";

class dl_depositfiles_com extends Download {
	
	public function Login($user, $pass){
		$this->error("notsupportacc");
		return false;
	}
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data =  $this->curl($url, $this->lib->cookie, ($pass ? "file_password={$pass}" : ""));
		$exdepo = explode(".", $this->pre['server']);
		if (stristr($data, "You have exceeded the")) $this->error("LimitAcc");
		elseif (strpos($data, 'Please, enter the password for this file')) $this->error("notsupportpass", true, false);
		elseif($this->isredirect($data)) return trim($this->redirect);
		elseif (preg_match('%"(http:\/\/.+' . $exdepo[0] . '\.' . $exdepo[1] . '/auth.+)" onClick="%U', $data, $redir2)) return trim($redir2[1]);
		elseif (stristr($data, "it has been removed due to infringement of copyright")) $this->error("dead", true, false, 2);
		elseif (stristr($data, "Such file does not exist")) $this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Depositfiles Download Plugin 
* Downloader Class By [FZ]
*/
?>