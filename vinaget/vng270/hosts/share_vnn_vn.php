<?php

class dl_share_vnn_vn extends Download {
    
    public function CheckAcc($cookie){
         $data = $this->lib->curl("http://share.vnn.vn/thong-tin-ca-nhan", $cookie, "");
         if(stristr($data, '<label class="caption">Thời hạn VIP còn lại:</label>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '<label class="caption">Thời hạn VIP còn lại:</label>','<div class="field">'), '<div class="fieldtext">','ngày') ."day");
         else if(stristr($data, 'MegaShare Free				</div>')) return array(false, "accfree");
		 else return array(false, "accinvalid");
    }
    
    public function Login($user, $pass){
		$data = $this->lib->curl('https://id.vnn.vn/login?service=http%3A%2F%2Fshare.vnn.vn%2Flogin.php%3Fdo%3Dlogin%26url%3Dhttp%253A%252F%252Fshare.vnn.vn%252F', '', '');
		if(preg_match('%JSESSIONID=(.+);%',$data,$match)) $jsid = $match[1]; 
		$lt = $this->lib->cut_str($data, '"lt" value="', '" />');	
		$cookie = 'JSESSIONID='.$jsid;
		$data = $this->lib->curl("https://id.vnn.vn/login;jsessionid={$jsid}?service=http%3A%2F%2Fshare.vnn.vn%2Flogin.php%3Fdo%3Dlogin%26url%3Dhttp%253A%252F%252Fshare.vnn.vn%252F", $cookie, "username={$user}&password={$pass}&lt={$lt}&_eventId=submit&submit=Đăng nhập");
		$thuytinh = $this->lib->GetCookies($data);
		if(preg_match("#Location: (.*)#", $data, $match)) {
			$data = $this->lib->curl($match[1], $thuytinh, '');
			$thuytinh = $thuytinh. '; ' .$this->lib->GetCookies($data);
			if(preg_match('#PHPSESSID=ST(.+)#', $thuytinh, $matchs)) 
			$cookie = "{$cookie};{$matchs[0]}";	
		}
		return $cookie;
    }
    
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if(stristr($data,'Không tìm thấy file bạn yêu cầu')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'File bạn yêu cầu đã bị khóa')) $this->error("dead", true, false, 2);
		elseif(stristr($data,'We cannot find the page you are looking for')) $this->error("dead", true, false, 2);
		elseif(!preg_match('@https?:\/\/(?:(?:((dl(\d+\.)?share\.vnn\.vn))|(?:(\d+\.\d+\.\d+\.\d+(:\d+)?))))\/dl\d+\/[^"\'><\r\n\t]+@i', $data, $giay)) 
		$this->error("notfound", true, false, 2);  
		else    
		return trim($giay[0]);
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Share.VNN.VN Download Plugin  by giaythuytinh176 [28.7.2013]
* Downloader Class By [FZ]
*/
?>