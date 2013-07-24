<?php
class dl_filestay_com extends Download {
    
    public function CheckAcc($cookie){
         $data = $this->lib->curl("http://filestay.com/?op=my_account", $cookie, "");
         if(stristr($data, '<dt>Premium until</dt>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '<dt>Premium until</dt>','<dd class="ddeditbtn">'), '<dd>', '</dd>'));
         else if(stristr($data, '<dd>Normal</dd>')) return array(false, "accfree");
         else return array(false, "accinvalid");
    }
    
	public function Login($user, $pass){
		$data = $this->lib->curl("http://filestay.com/login.html","","login={$user}&password={$pass}&op=login&redirect=http://filestay.com/");				
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
    
    public function Leech($url) {
        $data = $this->lib->curl($url,$this->lib->cookie,"");
        if($this->isredirect($data)) return trim($this->redirect);
        elseif(stristr($data,'<b>File Not Found</b><br><br>')) $this->error("dead", true, false, 2);
		elseif(stristr($data, "Create Download Link")){
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			$data = $this->lib->cut_str($data, '<a style="margin-top:15px;"', 'active">Download');
			$link = $this->lib->cut_str($data, 'href="', '" class="gbutton large bold positive');
			return trim($link);
		}
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* filestay Download Plugin by giaythuytinh176 [21.7.2013]
* Downloader Class By [FZ]
*/
?>