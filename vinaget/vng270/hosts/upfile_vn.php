<?php

class dl_upfile_vn extends Download {
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://upfile.vn/login.html","","loginUsername={$user}&loginPassword={$pass}&submit=Login&submitme=1");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		return trim($url);
		return false;
    }

}
?>
