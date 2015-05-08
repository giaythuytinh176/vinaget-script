<?php
 
class dl_fshare_vn extends Download {
     
    public function CheckAcc($cookie){
        $data = $this->lib->curl("https://www.fshare.vn/account/infoaccount", $cookie, "");
        if(stristr($data, '>VIP<') && stristr($data, '<dt>Thời hạn dùng:</dt>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, 'Hạn dùng</dt>','</dl>'), '<dd>','</dd>'));
        elseif(stristr($data, '>VIP<')) return array(true, "Account is lifetime!!!");
        elseif(stristr($data, '>Thành viên thường<')) return array(false, "accfree");
        else return array(false, "accinvalid");
    }
      
    public function Login($user, $pass){
        $page = $this->lib->curl("https://www.fshare.vn/login", "", "");
        $token = $this->lib->cut_str($page, 'hidden" value="', '"');
        $data = $this->lib->curl("https://www.fshare.vn/login", $this->lib->GetCookies($page), "fs_csrf={$token}&LoginForm[email]={$user}&LoginForm[password]={$pass}&LoginForm[rememberMe]=0&yt2=%C4%90%C4%83ng+nh%E1%BA%ADp");
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }
     
    public function Leech($url) {
        $url = str_replace('http://', 'https://', $url);
        list($url, $pass) = $this->linkpassword($url);  
        $data = $this->lib->curl($url, $this->lib->cookie, "");
        $token = $this->lib->cut_str($data, "fs_csrf: '", "'");
        if($pass) {
            $post = $this->parseForm($this->lib->cut_str($data, '<form id="', '</form>'));
            $post["FilePwdForm[pwd]"] = $pass;
            $post["yt0"] = "";
            $data = $this->lib->curl($url, $this->lib->cookie.$this->lib->GetCookies($data), $post);
        }
        if(stristr($data,'message-error')){
            $this->lib->curl("{$this->lib->self}?id=check&rand=".time(), "secureid={$_COOKIE["secureid"]}", "check=fshare.vn");
            $this->error("blockAcc", true, false);
        }
        elseif(stristr($page,"signup")){
            $this->lib->curl("{$this->lib->self}?id=check&rand=".time(), "secureid={$_COOKIE["secureid"]}", "check=fshare.vn");
            $this->error("cookieinvalid", true, false);  
        }
        elseif(stristr($data, '>FREE<'))  $this->error("accfree", true, false);
        elseif(stristr($data,">404<"))    $this->error("dead", true, false, 2);
        elseif(stristr($data,"filepwd-form"))   $this->error("reportpass", true, false);
        elseif(preg_match('@https?:\/\/download-?(\w+\.)?fshare\.vn\/dl\/[^"\'><\r\n\t]+@i', $data, $match)) return trim($match[0]);
        else{
            $page = $this->lib->curl('https://www.fshare.vn/download/index', $this->lib->cookie, "speed=fast&fs_csrf={$token}", 0, 0, $url);
            $json = json_decode($page, true);
            if ($json["url"]) return $json["url"];
        }
        return false;
    }
     
}
 
/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Fshare.VN Download Plugin 
* Downloader Class By [FZ]
* Plugin By -ZeSS-
* Date: 10.3.2014
*/
?>