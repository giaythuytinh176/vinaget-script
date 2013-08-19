<?php		 

class dl_filechin_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.filechin.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b></TD><TD>'));
        else if(stristr($data, 'Old password') && !stristr($data, 'Premium account expire')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.filechin.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://www.filechin.com/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(!preg_match('@https?:\/\/([a-z0-9]+\.)?filechin\.com(:\d+)?\/dl\/[a-z]+\/\d+\/[a-z0-9]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $this->lib->cut_str($data, 'dotted #bbb;padding:7px;line-height:29px;">', '">http'), $giay))
				$this->error("notfound", true, false, 2);	else	return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'<b>File Not Found</b><br><br>')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'You have reached the download-limit:')) $this->error("LimitAcc", true, false);
		elseif(!preg_match('@https?:\/\/([a-z0-9]+\.)?filechin\.com(:\d+)?\/dl\/[a-z]+\/\d+\/[a-z0-9]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $data, $dl)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(!preg_match('@https?:\/\/([a-z0-9]+\.)?filechin\.com(:\d+)?\/dl\/[a-z]+\/\d+\/[a-z0-9]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $this->lib->cut_str($data, 'dotted #bbb;padding:7px;line-height:29px;">', '">http'), $giay))
				$this->error("notfound", true, false, 2);	else	return trim($giay[0]);
		}
		else  return trim($dl[0]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* filechin.com Download Plugin by giaythuytinh176 [17.8.2013]
* Downloader Class By [FZ]
*/
?>