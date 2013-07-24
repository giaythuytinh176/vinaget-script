<?php

class dl_2shared_com extends Download {
	

	public function FreeLeech($url){
		list($url, $pass) = $this->linkpassword($url);
		$post = !empty($pass) ? "userPass2=".$pass : "";
		$data = $this->lib->curl($url, $this->lib->cookie, $post ? "userPass2={$pass}" : "");
		$this->save($this->lib->GetCookies($data));
        if (preg_match ('/dc(\d+)\.2shared\.com\/download\/([^\'|\"|\<|\>|\r|\n]+)/i', $data, $lik)) {
           $giay = "http://dc" . $lik[1] . ".2shared.com/download/" . $lik[2];
				return trim($giay);
		}
		elseif (stristr($data,"The file link that you requested is not valid.") && stristr($data,"Please contact link publisher or try to make a search."))   $this->error("dead", true, false, 2);
		elseif (stristr($data,"Please enter password to access this file"))  $this->error("reportpass", true, false);
		elseif (stristr($data,"Your free download limit is over."))  $this->error("outofbw", true, false);

			return false;
	}

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* 2Shared.com Download Plugin by giaythuytinh176 [24.7.2013]
* Downloader Class By [FZ]
*/
?>