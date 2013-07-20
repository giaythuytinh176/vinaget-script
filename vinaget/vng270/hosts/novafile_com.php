<?php  

class dl_novafile_com extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://novafile.com/premium.html", $cookie, "");
		if(stristr($data, 'Premium Account expires')) return array(true, "Premium Account expires ".$this->lib->cut_str($data, 'Premium Account expires  ','	</div>'));
		else if(stristr($data, 'Your current status: FREE - member')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}

	public function Login($user, $pass){
		$post["login"]= $user;
		$post["password"]= $pass;
		$post["op"] = "login";
		$post["redirect"] = "";
		$post["rand"] = "";
		$data = $this->lib->curl("http://novafile.com/login","",$post);				
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url){
		$data = $this->lib->curl($url,$this->lib->cookie,"");
		if(stristr($data,'<div class="name">File Not Found</div>')) $this->error("dead", true, false, 2);
		elseif(stristr($data, "Create Download Link")){
                 $post = $this->parseForm($this->lib->cut_str($data, '<form action="', '</form>'));
                 $data = $this->lib->curl($url, $this->lib->cookie, $post);
				 $data = $this->lib->cut_str($data, '<div class="alert alert-success-invert">', '<p>This direct link will be active for your IP for the next 24 hours.</p>');
                 $link = $this->lib->cut_str($data, '<a href="', '" class="btn btn-green">');
                 return trim($link);
          }
		elseif(stristr($data,"different IP")) $this->error("blockIP", true, false);
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