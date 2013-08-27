<?php

class dl_rapidfileshare_net extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://www.rapidfileshare.net/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium account expire:')) return array(true, "Until ".$this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>','</b>'));
        else if(stristr($data, 'My affiliate link') && !stristr($data, 'Premium account expire:')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://www.rapidfileshare.net/", "lang=english", "login={$user}&password={$pass}&op=login&redirect=http://www.rapidfileshare.net/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
/*	
    public function FreeLeech($url) {	 
		list($url, $pass) = $this->linkpassword($url);
        $page = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($page));
		$post = $this->parseForm($this->lib->cut_str($page, '<Form method="POST" action=', '<input type="submit" name="method_premium"'));
		$post['method_free'] = "Free Download";
		$post['method_premium'] = "";
		
		$page = $this->lib->curl($url, $this->lib->cookie, $post); 
		if(preg_match('@<span id="countdown_str"[^>]*>[^<>]+<span[^>]*>(\d+)</span>[^<>]+</span>@i', $this->lib->cut_str($page, '<Form name="F1" method="POST', '</Form>'), $count) && $count[1] > 0) 
		sleep($count[1]);

		if(stristr($page,"6LfVbdMSAAAAALBpTjJYZdvolRRWCbWyyGZshurt")) {
			$data = $this->lib->curl("http://www.google.com/recaptcha/api/challenge?k=6LfVbdMSAAAAALBpTjJYZdvolRRWCbWyyGZshurt","","");
			if(preg_match("%challenge : '(.*)'%U", $data, $matches)) $this->error("captcha code '".trim($matches[1])."' rand '{$rand}'", true, false);
			
			$post = array('recaptcha_challenge_field' => $_POST['recaptcha_challenge_field'], 'recaptcha_response_field' => $_POST['recaptcha_response_field']);
			$post = $this->parseForm($this->lib->cut_str($page, '<Form name="F1" method="POST"', '</Form>'));
			
			$page = $this->lib->curl($url, $this->lib->cookie, $post);
			
			if(!preg_match('@https?:\/\/(\w+\.)?rapidfileshare\.net(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $page, $giay))
			$this->error("notfound", true, false, 2);	
			else 	
			return trim($giay[0]);
		}
 

		return false;	
	}	*/
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(!preg_match('@https?:\/\/(\w+\.)?rapidfileshare\.net(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			$this->error("notfound", true, false, 2); 	
			else	
			return trim($giay[0]);
		}
		if(stristr($data,'type="password" name="password')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form name="F1"', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(!preg_match('@https?:\/\/(\w+\.)?rapidfileshare\.net(:\d+)?\/d\/[^"\'><\r\n\t]+@i', $data, $giay))
			$this->error("notfound", true, false, 2);	
			else 	
			return trim($giay[0]);
		} 
		else  
		return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* rapidfileshare Download Plugin by giaythuytinh176 [27.8.2013]
* Downloader Class By [FZ]
*/
?>