<?php

class dl_fshare_vn extends Download {
 
	public function CheckAcc($cookie){
		$data = $this->lib->curl("https://www.fshare.vn/account_info.php", $cookie, "");
		if(stristr($data, '<dd>VIP</dd>') && stristr($data, '<dt>Thời hạn dùng:</dt>')) return array(true, "Until ".$this->lib->cut_str($this->lib->cut_str($data, '<dt>Thời hạn dùng:</dt>','<dt>Loại thành viên:</dt>'), '<dd>','</dd>'));
		else if(stristr($data, 'Tổng file upload:') && stristr($data, '<dd>VIP</dd>')) return array(true, "Account is lifetime!!!");
		else if(stristr($data, 'Free Member')) return array(false, "accfree");
		else return array(false, "accinvalid");
	}
     
        public function Login($user, $pass){
                $post["login_useremail"]= $user;
                $post["login_password"]= $pass;
                $post['url_refe'] = 'http://www.fshare.vn';
				$post['auto_login'] = '1';
                $data = $this->lib->curl("https://www.fshare.vn/login.php","",$post);                        
                $cookie = $this->lib->GetCookies($data);
                return $cookie;
        }
	
    public function Leech($url) {
		list($url, $pass) = $this->linkpassword($url);
		$data = $this->lib->curl($url, $this->lib->cookie, "");
			if(stristr($data,"link_file_pwd_dl")) {	
				if($pass) {
					$thuytinh = $this->lib->cookie."; ".$this->lib->GetCookies($data);
					$post["file_id"] = $this->lib->cut_str($data, 'file_id" value="', '"/>');
					$post["link_file_pwd_dl"] = $pass;
					$post["action"] = 'download_file';
					$post["special"] = '';
					$data = $this->lib->curl($url, $thuytinh, $post);	
						if(preg_match('%<form action="(.+)" method="post%U', $data, $giay176))  return trim($giay176[1]);
						elseif($this->isredirect($data)) return trim($this->redirect);
				}
			}
			if(preg_match('%<form action="(.+)" method="post%U', $data, $giay))  return trim($giay[1]);
			elseif($this->isredirect($data)) return trim($this->redirect);
			elseif(stristr($data,"Tài khoản đang được sử dụng trên máy khác")) 	$this->error("blockAcc", true, false);
			elseif(stristr($data,'Vui lòng nhập mật khẩu để tải tập tin')) 	$this->error("reportpass", true, false);
 			elseif(stristr($data,'Thông tin xác thực không hợp lệ. Xin vui lòng xóa cookie của trình duyệt để tiếp tục !</b></font>'))		$this->error("blockAcc", true, false);
			elseif(stristr($data,'Liên kết bạn chọn không tồn tại trên hệ thống Fshare'))	$this->error("dead", true, false, 2);
			elseif(stristr($data,'Tài khoản của bạn thuộc GUEST nên chỉ tải xuống 1 lần'))		$this->error("accinvalid", true, false);
			elseif(stristr($data,'THÔNG TIN TẬP TIN TẢI XUỐNG') && stristr($data,'TẢI XUỐNG CHẬM'))	 $this->error("accfree", true, false);
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