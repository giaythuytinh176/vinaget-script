<?php		

class dl_mp3_zing_vn extends Download {

    public function CheckAcc($cookie){		
         $data = $this->lib->curl("http://mp3.zing.vn", $cookie, "");
         if(stristr($data, '<span class="user-vip"></span><div class="user-area')) return array(true, "accpremium");
         else if(stristr($data, '<span class=""></span><div class="user-area"')) return array(false, "accfree");
         else return array(false, "accinvalid");
    }
	
	public function Login($user, $pass){	// use cookie zsid=
		$this->error("notsupportacc");
		return false;
	}	

    public function FreeLeech($url) {
		if(stristr($url, "http://mp3.zing.vn")) $url = str_replace("http://mp3.zing.vn", "http://m.mp3.zing.vn", $url);
		$data = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($data));
		if(!preg_match('@https?:\/\/m\.mp3\.zing\.vn\/xml\/song\/[^"\'><\r\n\t]+@i', $data, $giay))
		$this->error("Cannot get XML", true, false, 2); 
		else {
			$data = $this->lib->curl($giay[0], $this->lib->cookie, "");
			if(preg_match('@https?:.+m\.mp3\.zing\.vn[^"\'><\r\n\t]+I=@i', $data, $link))  {
				$giay = str_replace("\\","", $link[0]); 
				$giay = str_replace("I=","Y=", $giay); 
				return trim($giay);
			}
		}
		return false;
    }		
	
    public function Leech($url) {	
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(!preg_match('/Lossless" href="(https?:\/\/mp3\.zing\.vn\/download\/song\/[^"\'><\r\n\t]+)" class/', $data, $giay)) {
			if(preg_match('/320Kb" href="(https?:\/\/mp3\.zing\.vn\/download\/song\/[^"\'><\r\n\t]+)" class/', $data, $link)) 
			return trim($giay[1]);
		}
		else
		return trim($giay[1]);
		return false;
    }			
	
}


/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Mp3.zing.vn Download Plugin by giaythuytinh176 [27.7.2013]
* Support 320kps MP3 free leech.  
* Downloader Class By [FZ]
*/
?>