<?php

class dl_turbobit_net extends Download {

    public function CheckAcc($cookie){
        $data = $this->lib->curl("http://turbobit.net", "user_lang=en;".$cookie, "");
        if (stristr($data, '<u>Turbo Access</u> to')) return array(true, "Until ".$this->lib->cut_str($data, '<u>Turbo Access</u> to','</div>'));
        else if(stristr($data, '<u>Turbo Access</u> denied.')) return array(false, "accfree");
		else return array(false, "accinvalid");
    }
         
    public function Login($user, $pass){
        $data = $this->lib->curl("http://turbobit.net/user/login", "user_lang=en", "user[login]={$user}&user[pass]={$pass}&user[memory]=1&user[submit]=Login");
        $cookie = "user_lang=en;".$this->lib->GetCookies($data);
        return $cookie;
    }
         
    public function Leech($url) {
        $data = $this->lib->curl($url,$this->lib->cookie,"");
		$this->save($this->lib->GetCookies($data));
        if (stristr($data,'site is temporarily unavailable')) $this->error("dead", true, false, 2);
        elseif (stristr($data,'Please wait, searching file')) $this->error("dead", true, false, 2);
        elseif (stristr($data, 'You have reached the <a href=\'/user/messages\'>daily</a> limit of premium downloads') || stristr($data, 'You have reached the <a href=\'/user/messages\'>monthly</a> limit of premium downloads')) $this->error("LimitAcc");
		elseif (stristr($data, '<u>Turbo Access</u> denied')) $this->error("blockAcc");
		elseif (preg_match('@https?:\/\/turbobit\.net\/\/download\/redirect\/[^"\'><\r\n\t]+@i', $data, $giay))
		return trim($giay[0]);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Turbobit Download Plugin
* Downloader Class By [FZ]
* Fixed By djkristoph
* Fixed check account by giaythuytinh176 [28.7.2013]
*/
?>