<?php

class dl_novafile_com extends Download {
	
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
		if(preg_match_all('/<input type="hidden" name="(.*)" value="(.*)"/', $this->cut_str($data, "<form", "</form>"), $matches, PREG_SET_ORDER)){
			foreach($matches as $val) $post .= "&{$val[1]}={$val[2]}";
			$data = $this->lib->curl($url,$cookie,substr($post,1));
		}
		if(preg_match('/href="http(.*)" class="btn/i', $data, $redir)) return trim("http".$redir[1]);
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
*/
?>