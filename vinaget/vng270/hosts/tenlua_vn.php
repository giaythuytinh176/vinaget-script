<?php
 
class dl_tenlua_vn extends Download {
 
    public function CheckAcc($cookie){      
        if (strlen($cookie)<3) return array(false, "accinvalid");
        else {
            $data = $this->lib->curl("http://api2.tenlua.vn/?sid=".$cookie, "", '[{"a":"user_info"}]', "");
            $obj = @json_decode($data);
            $info = json_decode(json_encode($obj),true);
            if (($info[0]['free_used']) == "null") return array(false, "Free Member. <br> Expired: ".$info[0]['endGold']);
            else return array(true, "Gold Member. <br> Until ".$info[0]['endGold']);          
        }
    }
 
    public function Login($user, $pass){
        $data = $this->lib->curl('http://api2.tenlua.vn/','','[{"a":"user_login","user":"'.$user.'","password":"'.$pass.'","permanent":false}]',0);
        $invo = @json_decode($data);
        $cookie = $invo[0];
        return $cookie;
    }
     
    public function Leech($url) {
        $gach = explode('/', $url);
        $id = $gach[4];
        $seqno = mt_rand();
        $data = $this->lib->curl('http://api2.tenlua.vn/?sid='.$this->lib->cookie, '','[{"a":"filemanager_builddownload_getinfo","n":"'.$id.'","r":'.$seqno.'}]',0);
        $zess = @json_decode($data);
        if ($zess[0]->type == 'none') $this->error("dead", true, false, 2);
        else {
            $z = $zess[0]->url;
            $zes= $this->lib->curl($z,'','');
            if(preg_match('/ocation: *(.*)/i', $zes, $redir)){
                $link = trim($redir[1]);
                return $link;
            }
        }
        return false;
    }
     
}
 
/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Tenlua.vn Download Plugin by invokermoney [17.09.2014]
* Fix new API by invokermoney [26.01.2015]
* Downloader Class By [FZ]
*/