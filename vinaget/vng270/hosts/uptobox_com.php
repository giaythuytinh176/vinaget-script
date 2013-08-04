<?php
class dl_uptobox_com extends Download {
    
    public function CheckAcc($cookie){
         $data = $this->lib->curl("http://uptobox.com/?op=my_account", "lang=english;{$cookie}", "");
         if(stristr($data, 'Premium-Account expire:')) return array(true, "Until ".$this->lib->cut_str($data, '<TR><TD>Premium-Account expire:</TD><TD><b>','</b>'));
         else if(stristr($data, 'Free member')) return array(false, "accfree");
         else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
         $data = $this->lib->curl("http://uptobox.com/login.html","lang=english","login={$user}&password={$pass}&op=login&redirect=");
         $cookie = $this->lib->GetCookies($data);
         return $cookie;
    }
    
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
			if(stristr($data,"<br><b>Password:</b> <input type=")) {	
				if($pass) {
					$thuytinh = $this->lib->cookie."; ".$this->lib->GetCookies($data);
					$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
					$post["password"] = $pass;
					$data = $this->lib->curl($url, $thuytinh, $post);
					if($this->isredirect($data)) return trim($this->redirect);
					$data = $this->lib->cut_str($data, '<span style="background:#f9f9f9;border:1px dotted #bbb;padding:7px;">', '</span>');
					$giay = $this->lib->cut_str($data, 'href="', '">Click here');
						return trim($giay);
				}
			}
        if($this->isredirect($data)) return trim($this->redirect);
        elseif(stristr($data,'<center>File Not Found</center>')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'<br><b>Password:</b> <input type="password"')) 	$this->error("reportpass", true, false);
		elseif(stristr($data, "Create Download Link")){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$data = $this->lib->cut_str($data, '<span style="background:#f9f9f9;border:1px dotted #bbb;padding:7px;">', '</span>');
			$link = $this->lib->cut_str($data, 'href="', '">Click here');
				return trim($link);
		}
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Uptobox Download Plugin
* Downloader Class By [FZ]
* Support file password by giaythuytinh176 [26.7.2013]
*/
?>