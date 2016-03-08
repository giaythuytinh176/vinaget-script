<?php

class dl_daofile_com extends Download {

	public function CheckAcc($cookie){
		$data = $this->lib->curl("https://daofile.com/?op=my_account", "{$cookie} lang=english;", "");
		if(stristr($data, 'Premium account expire:</TD><TD><b>')) {
			$until = $this->lib->cut_str($data, 'Premium account expire:</TD><TD><b>',  '<');
			$left = $this->lib->cut_str($data, 'Traffic available today:</TD><TD><b>', '<');
			return array(true, "Premium Till: " .$until. "<br/>Traffic available today: " .$left);
		}
		else  return array(false, "accinvalid"); 
	}
  
	public function Login($user, $pass){
		$data = $this->lib->curl("https://daofile.com/", "lang=english", "op=login&redirect=&login={$user}&password={$pass}");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
  
	public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url,$this->lib->cookie." lang=english","");
		if(stristr($data,'file was deleted')) $this->error("dead", true, false, 2); 
		elseif(stristr($data,'You have reached the download-limit'))  $this->error("LimitAcc");
		elseif($this->isredirect($data)) return $this->redirect;
		elseif(stristr($data,'Download File')) {
			$post = $this->parseForm($this->lib->cut_str($data, '<form name="F1"', '</form'));
			if (isset($post['password'])) {
				if (empty($pass)) $this->error("reportpass", true, false, 2); 
				else $post['password'] = $password;
			}
			$post['down_direct'] = 1;
			$data =  $this->lib->curl($url, $cookie, $post);
			if (stristr($data, '"err">Wrong password')) $this->error("reportpass", true, false, 2);
			else {
				preg_match('/<span style="background:#f9f9f9;border:1px dotted #bbb;padding:7px;">\s*<a href="(.*?)">\s*http/s', $data, $match2);
				return trim($match2[1]);
			}
		}
		return false;
	}
	
}
/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Daofile.com Download Plugin
* Downloader Class By hogeunk
* Mede by hogeunk [2015/12/26]
*/
?>