<?php

class dl_rapidshare_com extends Download {
    
	public function PreLeech($url) {
		if(stristr($url, "my.rapidshare.com"))  $this->error("Not Support Folder", true, false, 2);
	}
	
    public function CheckAcc($cookie){
		if(stristr($cookie, "=")) {
			$ckc =  explode("=", $cookie); 
			$cookie = $ckc[1];
		} 
        $data = $this->lib->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi", "lang=en", "sub=getaccountdetails&withcookie=1&withpublicid=1&withsession=1&cookie={$cookie}&cbf=RSAPIDispatcher&cbid=1");	
        if(stristr($data, '\nbilleduntil=0\\')) return array(false, "accfree");
		elseif(preg_match('/nbilleduntil=([0-9]+)/', $data, $giay))	{
			if(time() > $giay[1]) return array(false, "Account Expired!");
			else return array(true, "Until ".date('H:i:s Y-m-d',$giay[1])."");
		}
		else return array(false, "accinvalid");
    }
	
	public function Login($user, $pass){
        $data = $this->lib->curl("http://api.rapidshare.com/cgi-bin/rsapi.cgi", "lang=en", "sub=getaccountdetails&withcookie=1&withpublicid=1&login={$user}&cbf=RSAPIDispatcher&cbid=2&password={$pass}");
		if(preg_match('/ncookie=([A-Z0-9]+)/', $data, $thuytinh)) 
		$cookie = "enc={$thuytinh[1]};lang=en";
		return $cookie;
    }	

    public function Leech($url) {
		if(stristr($url, 'rapidshare') == true && stristr($url, 'download|') == true) {
			$url = str_replace("%21","!",$url);
			$url = str_replace("%7C","|",$url);
			$arrRSL = explode('|', $url);
			if (isset($arrRSL)){
				$idfiles = $arrRSL[2];
				$idnames = $arrRSL[3];
				$url = 'http://rapidshare.com/files/' . $arrRSL[2] . '/' . $arrRSL[3];
			}
		}
		else {		
			$arrRSL = explode('/', $url);
			if (isset($arrRSL)){
				$url = 'http://rapidshare.com/files/' . $arrRSL[4] . '/' . $arrRSL[5];
				$idfiles = $arrRSL[4];
				$idnames = $arrRSL[5];
			}
		}
		$checkfile = file_get_contents("http://api.rapidshare.com/cgi-bin/rsapi.cgi?sub=checkfiles&files={$idfiles}&filenames={$idnames}");
		if(stristr($checkfile,'0,0,0,0,0')) 	$this->error("dead", true, false, 2);
		$data = $this->lib->curl($url, "lang=en;".$this->lib->cookie, ""); 
		if(!preg_match('@https?:\/\/((rs(\d+)?)?(p(\d+\.)?)?)?rapidshare\.com(:\d+)?\/files\/\d+\/[^"\'><\r\n\t]+@i', $data, $giay))
		$this->error("notfound", true, false, 2); 
		else 	
		return trim($giay[0]);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Rapidshare Download Plugin by giaythuytinh176 [2.8.2013]
* Downloader Class By [FZ]
*/
?>