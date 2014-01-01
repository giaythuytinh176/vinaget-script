<?php

class dl_uptobox_com extends Download {
    
    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://uptobox.com/?op=my_account", "lang=english;{$cookie}", "");
        if(stristr($data, 'Premium-Account expire')) return array(true, "Until ".$this->lib->cut_str($data, '>Premium-Account expire:', '</'));
        else if(stristr($data, 'My affiliate link:') && !stristr($data, 'Premium-Account expire')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
        $data = $this->lib->curl("http://uptobox.com/", "lang=english", "op=login&login={$user}&password={$pass}&redirect=http://uptobox.com/");
        $cookie = "lang=english;{$this->lib->GetCookies($data)}";
		return $cookie;
    }
/*
    public function FreeLeech($url) {	//Thanks to Th3-822@rapidleech.com
		list($url, $pass) = $this->linkpassword($url);
		$page = $this->lib->curl($url, "", "");
		$this->save($this->lib->GetCookies($page));
		$page2 = $this->lib->cut_str($page, '<Form', '</Form>'); 
		$post = array();
		$post['op'] = $this->lib->cut_str($page2, 'name="op" value="', '"');
		$post['usr_login'] = (empty($this->lib->cookie['xfss'])) ? '' : $this->lib->cookie['xfss'];
		$post['id'] = $this->lib->cut_str($page2, 'name="id" value="', '"');
		$post['fname'] = $this->lib->cut_str($page2, 'name="fname" value="', '"');
		$post['referer'] = '';
		$post['method_free'] = $this->lib->cut_str($page2, 'name="method_free" value="', '"');
		
		$page = $this->lib->curl($url, $this->lib->cookie, $post);
		if(preg_match('@You have reached the download-limit: \d+ Mb for last 1 days@i', $page, $limit)) $this->error($limit[0], true, false);
		elseif(preg_match('@You have to wait (?:\d+ \w+,\s)?\d+ \w+ till next download@i', $page, $count)) 	$this->error($count[0], true, false);
		elseif(preg_match('@charger des fichiers de taille sup&eacute;rieur &agrave (\d+) Mb.@i', $page, $sizelim)) $this->error('You can download files up to '.$sizelim[1].' Mb.', true, false);
		
		$page2 = $this->lib->cut_str($page, '<Form', '</Form>');  
		$post = array();
		$post['op'] = $this->lib->cut_str($page2, 'name="op" value="', '"');
		$post['id'] = $this->lib->cut_str($page2, 'name="id" value="', '"');
		$post['rand'] = $this->lib->cut_str($page2, 'name="rand" value="', '"');
		$post['referer'] = '';
		$post['method_free'] = $this->lib->cut_str($page2, 'name="method_free" value="', '"');
		
		if (!preg_match_all("@<span style='[^\'>]*padding-left\s*:\s*(\d+)[^\'>]*'[^>]*>((?:&#\w+;)|(?:\d))</span>@i", $page2, $spans)) 
		$this->error("Error: Cannot decode captcha.", true, false);	
		else {
			$spans = array_combine($spans[1], $spans[2]);
			ksort($spans, SORT_NUMERIC);
			$captcha = '';
			foreach ($spans as $digit) $captcha .= $digit;
			$post['code'] = html_entity_decode($captcha);
			
			if(preg_match('@<span id="countdown_str"[^>]*>[^<>]+<span[^>]*>(\d+)</span>[^<>]+</span>@i', $page2, $count) && $count[1] > 0) 
			sleep($count[1]);
			
			if($pass) {
				$post = $this->parseForm($this->lib->cut_str($page, '<Form', '</Form>'));
				$post['method_free'] = "Free Download";
				$post["password"] = $pass;
				$post['code'] = html_entity_decode($captcha);
				$page = $this->lib->curl($url, $this->lib->cookie, $post);
				if(stristr($page,'Wrong password'))  $this->error("wrongpass", true, false, 2);
				elseif(stristr($page,'>Skipped countdown'))  $this->error("Error: Skipped countdown?.", true, false);
				elseif(stristr($page,'>Wrong captcha<'))  $this->error("Error: Unknown error after sending decoded captcha.", true, false);
				elseif(stristr($page,'>Expired session<'))  $this->error("Error: Expired Download Session.", true, false);
				elseif(preg_match('/a href="(https?:\/\/.*\/d\/.*)">Click here/i', $page, $link))	return trim($link[1]);
			}
			if(stristr($page,'type="password" name="password'))  $this->error("reportpass", true, false);
			
			$page = $this->lib->curl($url, $this->lib->cookie, $post);

			if(stristr($page,'>Skipped countdown'))  $this->error("Error: Skipped countdown?.", true, false);
			elseif(stristr($page,'>Wrong captcha<'))  $this->error("Error: Unknown error after sending decoded captcha.", true, false);
			elseif(stristr($page,'>Expired session<'))  $this->error("Error: Expired Download Session.", true, false);
			elseif(preg_match('/a href="(https?:\/\/.*\/d\/.*)">Click here/i', $page, $link))	return trim($link[1]);
		}
		return false;
	}				*/
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form', '</Form>'));
			$post["password"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(stristr($data,'Wrong password'))  $this->error("wrongpass", true, false, 2);
			elseif(preg_match('/a href="(https?:\/\/.*\/d\/.*)">Click here/i', $data, $link))	return trim($link[1]);
		}
		if(stristr($data,'type="password" name="password'))  $this->error("reportpass", true, false);
		elseif(stristr($data,'The file was deleted by its owner') || stristr($data,'Page not found / La page')) $this->error("dead", true, false, 2);
		elseif(!$this->isredirect($data)) {
			$post = $this->parseForm($this->lib->cut_str($data, '<Form', '</Form>'));
			$data = $this->lib->curl($url, $this->lib->cookie, $post);
			if(preg_match('/a href="(https?:\/\/.*\/d\/.*)">Click here/i', $data, $link))	return trim($link[1]);
		}
		else  return trim($this->redirect);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Uptobox Download Plugin
* Downloader Class By [FZ]
* Support file password by giaythuytinh176 [26.7.2013][18.9.2013][Fixed]
*/
?>