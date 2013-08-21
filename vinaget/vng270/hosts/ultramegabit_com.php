<?php

class dl_ultramegabit_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://ultramegabit.com/user/billing", $cookie, "");
        if(stristr($data, '"Premium Member"')) return array(true, "Until ".$this->lib->cut_str($data, '<h4>Next rebill at','</h4>'));
        else if(stristr($data, 'Active storage') && !stristr($data, '"Premium Member"')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
		$csrf = $this->lib->curl("http://ultramegabit.com/login", "", "");
		$csrftoken = $this->lib->cut_str($csrf, 'csrf_token" value="', '"');
        $data = $this->lib->curl("http://ultramegabit.com/login", "csrf_cookie={$csrftoken}", "username={$user}&password={$pass}&csrf_token={$csrftoken}&return_url=&submit=Login");
        $cookie = "csrf_cookie={$csrftoken};{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data,'<h4>File has been deleted.</h4>') || stristr($data,'File has been deleted in compliance with the')) $this->error("dead", true, false, 2);
		elseif(!preg_match('@https?:\/\/vip\d+\.ultramegabit\.com(:\d+)?\/files\/\d+(.*)hash=[^"\'><\r\n\t]+@i', $data, $giay)) {
			$post["csrf_token"] = $this->lib->cut_str($data, 'csrf_token" value="', '"');
			$post["encode"] = $this->lib->cut_str($data, 'encode" value="', '"');
			$data = $this->lib->curl("http://ultramegabit.com/file/download", $this->lib->cookie, $post);
			if(!preg_match('@https?:\/\/vip\d+\.ultramegabit\.com(:\d+)?\/files\/\d+(.*)hash=[^"\'><\r\n\t]+@i', $data, $giay))
			$this->error("notfound", true, false, 2);	
			else 	
			$this->save($this->lib->GetCookies($data));
			return trim($giay[0]);
		}
		else 	
		$this->save($this->lib->GetCookies($data));
		return trim($giay[0]);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* ultramegabit Download Plugin by giaythuytinh176 [21.8.2013]
* Downloader Class By [FZ]
* Thanks to Rapid61@rapidleech.com for your account.
*/
?>