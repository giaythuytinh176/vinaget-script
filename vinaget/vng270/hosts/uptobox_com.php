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
        $data = $this->lib->curl($url,$this->lib->cookie,"");
        if($this->isredirect($data)) return trim($this->redirect);
        elseif(stristr($data,'<center>File Not Found</center>')) $this->error("dead", true, false, 2);
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
*/
?>