<?php

class dl_fshare_vn extends Download {

	public function PreLeech($url){
		if(stristr($url, "/folder/")) $this->error("Not Support Folder", true, false, 2);
	}

	public function CheckAcc($cookie){
		$data = $this->lib->curl("https://www.fshare.vn/account_info.php", $cookie, "");
		if(stristr($data, '<dd>VIP</dd>') && stristr($data, '<dt>Thời hạn dùng:</dt>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '<dt>Thời hạn dùng:</dt>','<dt>Loại thành viên:</dt>'), '<dd>','</dd>'));
		else if(stristr($data, 'Tổng file upload:') && stristr($data, '<dd>VIP</dd>')) return array(true, "Account is lifetime!!!");
		else if(stristr($data, 'Free Member')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
     
    public function Login($user, $pass){
		$data = $this->lib->curl("https://www.fshare.vn/login.php", "", "login_useremail={$user}&login_password={$pass}&auto_login=1&url_refe=https://www.fshare.vn/index.php");	
        $cookie = $this->lib->GetCookies($data);
        return $cookie;
    }
	
    public function Leech($url) {
		if(stristr($url, "mega.1280.com")) {
			$ex = explode("mega.1280.com", $url);
			$url = "http://www.fshare.vn".$ex[1];
		}
		if(!stristr($url, "www")) {
			$ex = explode("fshare.vn", $url);
			$url = "http://www.fshare.vn".$ex[1];
		}
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
		if($pass) {
			$post = $this->parseForm($this->lib->cut_str($data, '<form action="', '</form>'));
			$post["link_file_pwd_dl"] = $pass;
			$data = $this->lib->curl($url, $this->lib->cookie, $post);	
			if(stristr($data,'Mật khẩu download file không đúng')) $this->error("wrongpass", true, false, 2);
			elseif(!preg_match('@https?:\/\/download(\d+\.)?fshare\.vn\/vip\/[_a-zA-Z0-9-]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $data, $giay)) 
				$this->error("notfound", true, false, 2); 	else  return trim($giay[0]);
		}
		if(stristr($data,'Vui lòng nhập mật khẩu để tải tập tin')) 	$this->error("reportpass", true, false);
		elseif(stristr($data,"Tài khoản đang được sử dụng trên máy khác")) 	$this->error("blockAcc", true, false);
 		elseif(stristr($data,'Thông tin xác thực không hợp lệ. Xin vui lòng xóa cookie của trình duyệt</b></font>'))  $this->error("blockAcc", true, false);
		elseif(stristr($data,'Liên kết bạn chọn không tồn tại trên hệ thống Fshare'))	$this->error("dead", true, false, 2);
		elseif(stristr($data,'Tài khoản của bạn thuộc GUEST nên chỉ tải xuống 1 lần'))	 $this->error("accinvalid", true, false);
		elseif(stristr($data,'THÔNG TIN TẬP TIN TẢI XUỐNG') && stristr($data,'TẢI XUỐNG CHẬM'))	 $this->error("accfree", true, false);
		elseif(!preg_match('@https?:\/\/download(\d+\.)?fshare\.vn\/vip\/[_a-zA-Z0-9-]+\/[^/|\"|\'|<|>|\r|\n|\t]+@i', $data, $giay)) 
			$this->error("notfound", true, false, 2); 	else  return trim($giay[0]);
		return false;
    }
	
}

/*
* Open Source Project
* Vinaget by ..::[H]::..
* Version: 2.7.0
* Fshare.VN Download Plugin 
* Downloader Class By [FZ]
* Plugin By giaythuytinh176
* Date: 16.7.2013
* Fixed check account: 18.7.2013
* Support file password by giaythuytinh176 [26.7.2013]
*/
?>