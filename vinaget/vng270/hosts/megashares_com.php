<?php

class dl_megashares_com extends Download {
	
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://d01.megashares.com/myms.php", $cookie, "");
        if(stristr($data, 'Premium User</span>') && stristr($data, 'Period Ends')) return array(true, "Until ".$this->lib->cut_str($data, '<p class="premium_info_box">Period Ends: ','</p>'));
        else if(stristr($data, 'Premium Upgrade</span>') && !stristr($data, 'Premium User</span>')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://d01.megashares.com/myms_login.php", "", "httpref=&mymslogin_name={$user}&mymspassword={$pass}&myms_login=Login");				
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		if(stristr($url, "megashares.com/dl/")) {
			$ex =  explode("/", $url); 
			$url = "http://d01.megashares.com/index.php?d01=".$ex[4];
		}
		$data = $this->lib->curl($url, $this->lib->cookie, "");
	/*	if(stristr($data, 'alt="download file" />')){
			$giay = $this->lib->cut_str($this->lib->cut_str($data, '<div id="show_download_button_1">', 'download file" /></a>'), 'href="', '"><img');
				return trim($giay);
		} */
		if (preg_match('%<a href="(https?:.+megashares.com.+)"><img style="margin:%U', $data, $link)) return trim($link[1]);
		elseif($this->isredirect($data)) return trim($this->redirect);
		elseif (stristr($data,"Invalid Link") || stristr($data,"Link has been deleted") || stristr($data,"Link is invalid")) $this->error("dead", true, false, 2);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Megashares Download Plugin 
* Downloader Class By [FZ]
* Add check account, Fixed download link by giaythuytinh176 [9.8.2013]
*/
?>