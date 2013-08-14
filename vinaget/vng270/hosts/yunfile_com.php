<?php	// Need check!!!

class dl_yunfile_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.yunfile.com/user/edit.html", "language=en_us;{$cookie}", "");
        if(stristr($data, 'bottom ">Premium Member')) return array(true, "Until ".$this->lib->cut_str($data, '(Expire:',')'));
        else if(stristr($data, 'Current Password:') && !stristr($data, 'bottom ">Premium Member')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.yunfile.com/view", "language=en_us", "module=member&action=validateLogin&username={$user}&password={$pass}&remember=1");
        $cookie = "language=en_us;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		if(stristr($url, "filemarkets")) {
			$url = str_replace("http://filemarkets.com/", "http://page2.yunfile.com/", $url);
		}
		elseif(stristr($url, "http://yunfile.com/")) {
			$url = str_replace("http://yunfile.com/", "http://page2.yunfile.com/", $url);
		}
 		$data = $this->lib->curl($url, $this->lib->cookie, "");
		$link = $this->lib->cut_str($this->lib->cut_str($data, '<td  class ="down_url_table_td">', 'onclick=\'setCookie'), '<a href="', '"');
		return trim($link);
		//$cookdown = $this->lib->cut_str($data, 'onclick=\'setCookie("vid1", "', '"');
		//$linkdown = $this->lib->curl(trim($link), "vid1={$cookdown};{$this->lib->cookie}","");
		//if($this->isredirect($linkdown)) return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* yunfile Download Plugin by giaythuytinh176 [13.8.2013]
* Downloader Class By [FZ]
*/
?>