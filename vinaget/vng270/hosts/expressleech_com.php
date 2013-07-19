<?php
class dl_expressleech_com extends Download {
    
    public function CheckAcc($cookie){
         $data = $this->lib->curl("http://expressleech.com/?op=my_account", "lang=english;{$cookie}", "");
         if(stristr($data, '<a href="http://expressleech.com/?op=payments">Upgrade to premium</a>')) return array(false, "accfree");
         elseif(stristr($data, 'Premium Account expire:')) return array(true, "Until ".$this->lib->cut_str($data, '<TR><TD>Premium Account expire:</TD><TD><b>','</b>'));
         else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
         $data = $this->lib->curl("http://expressleech.com/login.html","lang=english","login={$user}&password={$pass}&op=login&redirect=");
         $cookie = $this->lib->GetCookies($data);
         return $cookie;
    }
    
    public function Leech($url) {
         $data = $this->lib->curl($url, $this->lib->cookie, "");
         if(stristr($data, "No such file with this filename</font>")) $this->error("dead", true, false, 2);
         elseif($this->isredirect($data)) return trim($this->redirect);
         elseif(stristr($data, "Create Download Link")){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$link = $this->lib->cut_str($data, '<div class="news-title"><a href="', '">');
			return trim($link);
		 }
         return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Expressleech Download Plugin by riping
* Downloader Class By [FZ]
*/
?>