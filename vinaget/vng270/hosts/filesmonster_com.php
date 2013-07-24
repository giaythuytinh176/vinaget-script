<?php
class dl_filesmonster_com extends Download {


	public function CheckAcc($cookie){
		$data = $this->lib->curl("http://filesmonster.com/", $cookie, "");
		if(stristr($data, 'Your membership type: <span class="em lightblack">Registered')) return array(false, "accfree");
		elseif(stristr($data, 'Your membership type: <span class="em lightblack">Premium')) return array(true, "Until ".$this->lib->cut_str($data, '<span>Valid until: <span class=\'green\'>','</span><br /><input type='));
		else return array(false, "accinvalid");
	}

	public function Login($user, $pass){
		$data = $this->lib->curl("http://filesmonster.com/login.php", "","act=login&user={$user}&pass={$pass}&captcha_shown=0&login=Login");
		if (stristr($data,'yab_logined=1')) 
			$cookie =  "yab_logined=1;".$this->lib->cut_str($cookie, "yab_logined=1;", "; yab_last_click");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
  
	public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(preg_match('%<a href="(https?:\/\/[^\r\n\s\t"]+)"><span class="huge_button_green_left">%', $data, $lik)){
			$data = $this->lib->curl($lik[1], $this->lib->cookie,"");
			if(preg_match('/get_link\("([^\r\n\s\t"]+)"\)/', $data, $lik)) {
				$data = $this->lib->curl("http://filesmonster.com".$lik[1], $this->lib->cookie,"");
				$link = $this->lib->cut_str($data, '"url":"', '"}');
				$link = str_replace('\/', '/',$link);
					return trim($link);
			}
		}
		elseif(stristr($data,'File not found')) $this->error("dead", true, false, 2); 
		elseif(stristr($data,'<h1 class="block_header">The link could not be decoded</h1>')) $this->error("dead", true, false, 2); 
		elseif(stristr($data,'<div id="error">Today you have already downloaded')) $this->error("outofbw", true, false, 2); 
		return false;
	}
}
/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Downloader Class By [FZ]
* FilesMonster.com Download Plugin by giaythuytinh176
* Date: 23.7.2013
*/
?>