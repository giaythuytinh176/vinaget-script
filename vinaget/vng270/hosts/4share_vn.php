<?php

class dl_4share_vn extends Download {
	
	public function Login($user, $pass){
		$data = $this->lib->curl("http://4share.vn/?control=login","","inputUserName={$user}&inputPassword={$pass}&submit=Login");
		$cookie = $this->lib->GetCookies($data);
		return $cookie;
	}
	
    public function Leech($url) {
		$data = $this->lib->curl($url, $this->lib->cookie, "");	
		if (preg_match("%<a href='(.+)'><img src='\/images\/download.button.png'\/>%U", $data, $value) || preg_match("%<a href='(.+)'>DOWNLOAD</a>%U", $data, $value)) {
			$link = $value[1];
			$arr = explode('&',$link);
			$a = strlen($arr[2]);
			$str='';
			for($i=0;$i<$a;$i++) $str .= preg_replace("/([^a-zA-Z0-9\.\-\=])/", '_', $arr[2][$i]);
			$arr[2]=$str;
			$link = implode('&',$arr);
			return trim($link);
		}	
  		elseif (stristr($data,"File not found"))  $this->error("dead", true, false, 2);
		elseif (stristr($data,"File đã bị xóa")) $this->error("dead", true, false, 2);
  		elseif (stristr($data,"FID Không hợp lệ")) $this->error("Invalid FID", true, false, 2);				
  		elseif (stristr($data,"File có password download, hãy nhập password để download!")) $this->error("Need Password. Plugin Doesn't Support Password", true, false, 2);
  		elseif (stristr($data,"bị khóa đến")) $this->error("blockAcc");
		return false;
    }

}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* 4Share.VN Download Plugin By giaythuytinh176
* Downloader Class By [FZ]
*/
?>