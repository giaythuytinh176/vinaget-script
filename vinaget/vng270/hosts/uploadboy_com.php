<?php		 

class dl_uploadboy_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://uploadboy.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire')) return array(true, "Until ".$this->lib->cut_str($data, '<TR><TD>Premium account expire:</TD><TD><b>','</b>'));
        else if(stristr($data, 'Payment info') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://uploadboy.com/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://uploadboy.com/");
		$cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }

    public function FreeLeech($url) {
		list($url, $pass) = $this->linkpassword($url);
        $data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(stristr($data, 'method_free" value="Free Download')){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form method="POST" action=', '<table border="0" width="600" cellspaci'));
			$post['method_free'] = 'Free Download';
			$post['method_premium'] = '';
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$this->save($this->lib->GetCookies($data));
			if($pass) {
				$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
				$post1['password'] = $pass;
				$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
				if(stristr($data1,'Wrong password'))  $this->error("wrongpass", true, false, 2);
				//$giay = $this->lib->cut_str($this->lib->cut_str($data1, 'dotted #bbb;padding:7px;">', '</span>'), 'href="', '">');
				//return trim($giay);
				if(!preg_match('#https?://(\w+).uploadboy.(.*)/d/([^"|\'|\r|\n|\s]+)#', $this->lib->cut_str($data1, 'This direct link will be available', 'src="/downloadnow.png'), $giay))
				$this->error("Cannot get direct link download", true, false);
				else
				return trim($giay[0]);
			}
			if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
			elseif(!stristr($data, 'value="Create Download Link')) $this->error("Cannot get Create Download Link", true, false);
			else{
			$post1 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$data1 = $this->lib->curl($url, $this->lib->cookie, $post1);
			//$giay = $this->lib->cut_str($this->lib->cut_str($data1, 'dotted #bbb;padding:7px;">', '</span>'), 'href="', '">');
			//return trim($giay);
			if(!preg_match('#https?://(\w+).uploadboy.(.*)/d/([^"|\'|\r|\n|\s]+)#', $this->lib->cut_str($data1, 'This direct link will be available', 'src="/downloadnow.png'), $giay))
			$this->error("Cannot get direct link download", true, false);
			else
			return trim($giay[0]);
			}
		}
        elseif(stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
		return false;
	}
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post0 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$post0["password"] = $pass;
			$data0 = $this->lib->curl($url, $this->lib->cookie, $post0);
			if(stristr($data0,'Wrong password')) $this->error("wrongpass", true, false, 2);
			if(!preg_match('#https?://(\w+).uploadboy.(.*)/d/([^"|\'|\r|\n|\s]+)#', $this->lib->cut_str($data0, 'This direct link will be available', 'src="/downloadnow.png'), $giay))
			$this->error("Cannot get direct link download", true, false);
			else
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
        elseif(!preg_match('#https?://(\w+).uploadboy.(.*)/d/([^"|\'|\r|\n|\s]+)#', $data, $dl)) {
		    $post0 = $this->parseForm($this->lib->cut_str($data, '<Form name="F1" method="POST"', '</Form>'));
			$data0 = $this->lib->curl($url, $this->lib->cookie, $post0);
			if(!preg_match('#https?://(\w+).uploadboy.(.*)/d/([^"|\'|\r|\n|\s]+)#', $this->lib->cut_str($data0, 'This direct link will be available', 'src="/downloadnow.png'), $giay))
			$this->error("Cannot get direct link download", true, false);
			else
			return trim($giay[0]);
        }
		else
        return trim($dl[0]);
		if(stristr($data,'File Not Found')) $this->error("dead", true, false, 2);
		return false;
    } 
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* uploadboy Download Plugin by giaythuytinh176 [14.8.2013]
* Downloader Class By [FZ]
*/
?>