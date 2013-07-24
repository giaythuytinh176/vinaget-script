<?php

class dl_turbobit_net extends Download {

        public function CheckAcc($cookie){
        $data = $this->lib->curl("http://turbobit.net", $cookie, "");
        if (stristr($data, '<img src=\'/img/icon/banturbo.png\'> <u>Turbo Access</u> to')) return array(true, "Until ".$this->lib->cut_str($data, '<img src=\'/img/icon/banturbo.png\'> <u>Turbo Access</u> to','</div>'));
         else if(stristr($data, '<img src=\'/img/icon/noturbo.png\'> <u>Turbo Access</u> denied.		</div>')) return array(false, "accfree");
         else return array(false, "accinvalid");
        }
         
        public function Login($user, $pass){
                $data = $this->lib->curl("http://turbobit.net/user/login","user_lang=en","user[login]={$user}&user[pass]={$pass}&user[memory]=on&user[submit]=Login");
                $cookie = $this->lib->GetCookies($data);
                return $cookie;
        }
         
        public function Leech($url) {
                $data = $this->lib->curl($url,$this->lib->cookie,"");
                if($this->isredirect($data)) return trim($this->redirect);
                elseif(preg_match('/<a href=\'(.+)\'><b>Download/', $data, $a)) return trim($a[1]);
                elseif (stristr($data,'site is temporarily unavailable')) $this->error("dead", true, false, 2);
                elseif (stristr($data,'Please wait, searching file')) $this->error("dead", true, false, 2);
                elseif (stristr($data, '<u>Turbo Access</u> denied')) $this->error("blockAcc");
                elseif (stristr($data, 'You have reached the <a href=\'/user/messages\'>daily</a> limit of premium downloads		</div>')) $this->error("blockAcc");
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
* Fixed check account by giaythuytinh176 [24.7.2013]
*/
?>