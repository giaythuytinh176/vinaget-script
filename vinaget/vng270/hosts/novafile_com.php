<?php  

class dl_novafile_com extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://novafile.com/premium.html", "lang=english;".$cookie, "");
		if(stristr($data, 'Premium Account expires')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium Account expires','</div>'));
		else if(stristr($data, 'FREE - member')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}

    public function Login($user, $pass){
        $data = $this->lib->curl("http://novafile.com/login", "lang=english", "login={$user}&password={$pass}&op=login&rand=&redirect=");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
	
    public function Leech($url){
		$data = $this->lib->curl($url, $this->lib->cookie, "");
        if(stristr($data,'<div class="name">File Not Found</div>')) $this->error("dead", true, false, 2);
		elseif(stristr($data,"different IP")) $this->error("blockIP", true, false);
		elseif(!stristr($data, "Create Download Link"))
		$this->error("Cannot get Create Download Link", true, false, 2);	 
		else {
			$post = $this->parseForm($this->lib->cut_str($data, '<form action="', '</form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			//if (stristr($data,'You have reached the download limit'))  $this->error("LimitAcc", true, false);
			if(preg_match('/download limit: (\d+) MB <br>/', $data, $giay)) $this->error('You have reached the download limit: '.$giay[1].' MB', true, false);
			elseif(preg_match('@https?:\/\/s(\d+)?\.novafile\.com(:\d+)?\/[^"\'><\r\n\t]+@i', $data, $thuytinh))
			return trim($thuytinh[0]);
		}
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Novafile Download Plugin 
* Downloader Class By [FZ]
* Fixed download by giaythuytinh176 [20.7.2013]
*/
?>